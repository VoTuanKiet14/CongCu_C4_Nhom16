<?php

namespace App\Controllers;

require_once __DIR__ . '/../../config/routes.php';

use App\Models\Healthcheck;
use App\Models\Appointment;
use App\Models\User;
use Exception;
use Illuminate\Database\Capsule\Manager as Capsule;

class HealthcheckController
{
    // Admin functionality for HealthCheck
    public function adminIndex()
    {
        try {
            // Enable more detailed error reporting for debugging
            error_reporting(E_ALL);
            ini_set('display_errors', 1);

            // For more detailed error debugging
            error_log("HealthcheckController@adminIndex: Attempting to fetch healthchecks");

            // Initialize healthchecks as an empty array first
            global $healthchecks;
            $healthchecks = [];

            // Try a simple query first to verify connection
            try {
                global $capsule;
                $testConnection = $capsule->getConnection()->getPdo();
                error_log("Database connection test successful");
            } catch (\Exception $connEx) {
                error_log("Database connection test failed: " . $connEx->getMessage());
                throw new \Exception("Database connection failed: " . $connEx->getMessage(), 0, $connEx);
            }

            // Fetch healthchecks with comprehensive related data
            try {
                // Use Eloquent to load healthchecks with all related information
                $healthchecksQuery = Healthcheck::with([
                    'appointment',
                    'appointment.user',
                    'appointment.user.userInfo',
                    'appointment.event',
                    'appointment.event.donationUnit',
                    'appointment.bloodDonationHistory',
                ]);

                $healthchecksCollection = $healthchecksQuery->get();

                // Convert to array with all necessary related data
                $healthchecks = [];
                foreach ($healthchecksCollection as $check) {
                    $item = $check->toArray();

                    // Add donor health history if available
                    if ($check->appointment && $check->appointment->user) {
                        $userId = $check->appointment->user->cccd;

                        // Get blood donation history for this donor
                        try {
                            $donationHistory = \App\Models\BloodDonationHistory::where('user_id', $userId)
                                ->orderBy('donation_date_time', 'desc')
                                ->get();

                            $item['donation_history'] = $donationHistory->toArray();
                            $item['total_donations'] = $donationHistory->count();

                            // Calculate health statistics if possible
                            if ($check->appointment->user->userInfo) {
                                $birthDate = $check->appointment->user->userInfo->dob;
                                if ($birthDate) {
                                    $age = date_diff(date_create($birthDate), date_create('now'))->y;
                                    $item['donor_age'] = $age;
                                }
                            }
                        } catch (\Exception $e) {
                            error_log("Failed to load donation history for user {$userId}: " . $e->getMessage());
                            $item['donation_history'] = [];
                        }
                    }

                    // Parse health metrics for better display and analysis
                    if (!empty($check->health_metrics)) {
                        try {
                            $metrics = json_decode($check->health_metrics, true);

                            // Extract vital signs from metrics if they exist
                            $vitalSigns = [];
                            if (is_array($metrics)) {
                                if (isset($metrics['bloodPressure'])) $vitalSigns['bloodPressure'] = $metrics['bloodPressure'];
                                if (isset($metrics['temperature'])) $vitalSigns['temperature'] = $metrics['temperature'];
                                if (isset($metrics['pulse'])) $vitalSigns['pulse'] = $metrics['pulse'];
                                if (isset($metrics['weight'])) $vitalSigns['weight'] = $metrics['weight'];
                                if (isset($metrics['height'])) $vitalSigns['height'] = $metrics['height'];
                                if (isset($metrics['hemoglobin'])) $vitalSigns['hemoglobin'] = $metrics['hemoglobin'];
                            }

                            $item['vital_signs'] = $vitalSigns;

                            // Generate health summary
                            $summary = [];
                            if (is_array($metrics)) {
                                if (isset($metrics['hasChronicDiseases']) && $metrics['hasChronicDiseases']) {
                                    $summary[] = "Có bệnh mãn tính";
                                }
                                if (isset($metrics['hasRecentDiseases']) && $metrics['hasRecentDiseases']) {
                                    $summary[] = "Có bệnh trong 3 tháng gần đây";
                                }
                                if (isset($metrics['hasSymptoms']) && $metrics['hasSymptoms']) {
                                    $summary[] = "Có triệu chứng bệnh";
                                }
                                if (isset($metrics['isPregnantOrNursing']) && $metrics['isPregnantOrNursing']) {
                                    $summary[] = "Đang mang thai hoặc cho con bú";
                                }
                            }

                            $item['health_summary'] = empty($summary) ? "Đủ điều kiện hiến máu" : implode(", ", $summary);
                        } catch (\Exception $e) {
                            error_log("Failed to parse health metrics for healthcheck {$check->id}: " . $e->getMessage());
                        }
                    }

                    $healthchecks[] = $item;
                }

                error_log("Successfully loaded " . count($healthchecks) . " healthchecks with comprehensive data");
            } catch (\Exception $e) {
                error_log("Failed to load healthchecks: " . $e->getMessage());
                // Use empty array if loading failed
                $healthchecks = [];
            }

            // Include the view
            require_once '../app/views/admin/HealthCheck/HealthCheckList.php';
        } catch (\Exception $e) {
            error_log('Error in HealthcheckController@adminIndex: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());

            // Display detailed error message for debugging
            echo '<h3>Error in HealthcheckController@adminIndex</h3>';
            echo '<p><strong>Message:</strong> ' . $e->getMessage() . '</p>';
            echo '<p><strong>File:</strong> ' . $e->getFile() . '</p>';
            echo '<p><strong>Line:</strong> ' . $e->getLine() . '</p>';
            echo '<pre><strong>Stack Trace:</strong><br>' . $e->getTraceAsString() . '</pre>';
        }
    }

    public function adminCreate()
    {
        try {
            // More robust error logging
            error_log("HealthcheckController@adminCreate: Loading appointments");

            // Create global variable that can be accessed in the view
            global $appointments;
            global $previousHealthData;
            $appointments = [];
            $previousHealthData = [];

            try {
                // Load appointments with all related data for better context
                $appointments = Appointment::with([
                    'user',
                    'user.userInfo',
                    'event',
                    'event.donationUnit',
                    'bloodDonationHistory'
                ])
                    ->where('status', 2) // Active appointments 
                    ->whereNull('blood_inventory_id') // Not yet processed appointments
                    // ->whereDoesntHave('healthcheck') // Only appointments that don't have a health check yet
                    ->get();

                $secondAppointments = Appointment::all();

                error_log("Successfully loaded " . count($appointments) . " appointments for health check creation");
                error_log("Actually loaded appointments: " . count($secondAppointments));

                // Load previous health data for returning donors
                foreach ($appointments as $appointment) {
                    if ($appointment->user) {
                        $userId = $appointment->user->cccd;

                        // Find previous healthchecks for this user
                        $previousHealthchecks = \App\Models\Healthcheck::whereHas('appointment', function ($query) use ($userId) {
                            $query->where('user_cccd', $userId);
                        })->orderBy('id', 'desc')->first();

                        if ($previousHealthchecks) {
                            $previousHealthData[$appointment->id] = $previousHealthchecks->health_metrics;
                        }

                        // Get blood donation history
                        $donationHistory = \App\Models\BloodDonationHistory::where('user_id', $userId)
                            ->orderBy('donation_date_time', 'desc')
                            ->get();

                        if ($donationHistory->count() > 0) {
                            $appointment->previous_donations = $donationHistory->count();
                            $appointment->last_donation = $donationHistory->first();
                        }
                    }
                }

                // Log:
                error_log("appointments get from controller: " . json_encode($appointments));
                error_log("previousHealthData get from controller: " . json_encode($previousHealthData));
            } catch (\Exception $e) {
                error_log("Failed to load appointments: " . $e->getMessage());
                $appointments = [];
            }

            // Include the view directly
            require_once '../app/views/admin/HealthCheck/HealthCheckCreate.php';
        } catch (\Exception $e) {
            error_log('Error in HealthcheckController@adminCreate: ' . $e->getMessage());
            echo '<h3>Error in HealthcheckController@adminCreate</h3>';
            echo '<p><strong>Message:</strong> ' . $e->getMessage() . '</p>';
            echo '<p><strong>File:</strong> ' . $e->getFile() . '</p>';
            echo '<p><strong>Line:</strong> ' . $e->getLine() . '</p>';
        }
    }

    public function adminStore()
    {
        try {
            // Get POST data
            $data = $_POST;

            // Validate required fields
            if (empty($data['healthMetrics']) || empty($data['notes']) || empty($data['appointment_id'])) {
                throw new Exception("All fields are required");
            }

            $healthcheck = new Healthcheck();
            $healthcheck->health_metrics = json_encode($data['healthMetrics']);
            $healthcheck->notes = $data['notes'];
            $healthcheck->appointment_id = $data['appointment_id'];

            // Validate health check and set result
            $healthcheck->isValidHealthCheck();
            $healthcheck->save();

            // Redirect to health check list page
            header('Location: ' . HEALTH_CHECK_ROUTE);
            exit();
        } catch (Exception $e) {
            echo '<h3>Error in HealthcheckController@adminStore</h3>';
            echo '<p><strong>Message:</strong> ' . $e->getMessage() . '</p>';
        }
    }

    public function adminEdit($id = null)
    {
        try {
            // Get ID from GET parameter if not provided as function argument
            if ($id === null && isset($_GET['id'])) {
                $id = $_GET['id'];
            }

            if (!$id) {
                throw new Exception("No healthcheck ID provided");
            }

            error_log("HealthcheckController@adminEdit: Loading healthcheck with ID " . $id);

            // Create global variables that can be accessed in the view
            global $healthcheck;
            global $appointments;

            // Initialize with default values
            $healthcheck = null;
            $appointments = [];

            try {
                $healthcheck = Healthcheck::find($id);
                if (!$healthcheck) {
                    throw new Exception("Health check with ID $id not found");
                }

                error_log("Successfully loaded healthcheck with ID " . $id);
            } catch (Exception $e) {
                error_log("Failed to load healthcheck: " . $e->getMessage());
                echo '<h3>Error Loading Health Check Record</h3>';
                echo '<p><strong>Message:</strong> ' . $e->getMessage() . '</p>';
                return;
            }

            try {
                // Load appointments without relationships
                $appointmentsCollection = Appointment::all();
                if ($appointmentsCollection) {
                    $appointments = $appointmentsCollection;
                    error_log("Successfully loaded " . count($appointments) . " appointments");
                } else {
                    error_log("No appointments found");
                }
            } catch (Exception $e) {
                error_log("Failed to load appointments: " . $e->getMessage());
            }

            require_once '../app/views/admin/HealthCheck/HealthCheckEdit.php';
        } catch (Exception $e) {
            error_log('Error in HealthcheckController@adminEdit: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            echo '<h3>Error in HealthcheckController@adminEdit</h3>';
            echo '<p><strong>Message:</strong> ' . $e->getMessage() . '</p>';
            echo '<p><strong>File:</strong> ' . $e->getFile() . '</p>';
            echo '<p><strong>Line:</strong> ' . $e->getLine() . '</p>';
        }
    }

    public function adminUpdate($id = null)
    {
        try {
            // Get the ID from GET parameter if not provided as an argument
            if (!$id && isset($_GET['id'])) {
                $id = $_GET['id'];
            }

            if (!$id) {
                throw new Exception("No healthcheck ID provided for update");
            }

            $data = $_POST;

            // Validate required fields
            if (empty($data['healthMetrics']) || empty($data['notes'])) {
                throw new Exception("All fields are required");
            }

            $healthcheck = Healthcheck::find($id);
            if (!$healthcheck) {
                throw new Exception("Health check not found");
            }

            $healthcheck->health_metrics = json_encode($data['healthMetrics']);
            $healthcheck->notes = $data['notes'];
            if (!empty($data['appointment_id'])) {
                $healthcheck->appointment_id = $data['appointment_id'];
            }

            // Validate health check and set result
            $healthcheck->isValidHealthCheck();
            $healthcheck->save();

            header('Location: ' . HEALTH_CHECK_ROUTE);
            exit();
        } catch (Exception $e) {
            echo '<h3>Error in HealthcheckController@adminUpdate</h3>';
            echo '<p><strong>Message:</strong> ' . $e->getMessage() . '</p>';
            error_log("Error updating healthcheck: " . $e->getMessage());
        }
    }

    public function adminDelete($id = null)
    {
        try {
            // Get ID from GET parameter if not provided as function argument
            if ($id === null && isset($_GET['id'])) {
                $id = $_GET['id'];
            }

            if (!$id) {
                throw new Exception("No healthcheck ID provided for deletion");
            }

            $healthcheck = Healthcheck::find($id);
            if ($healthcheck) {
                $healthcheck->delete();
            }
            header('Location: ' . HEALTH_CHECK_ROUTE);
        } catch (Exception $e) {
            echo '<h3>Error in HealthcheckController@adminDelete</h3>';
            echo '<p><strong>Message:</strong> ' . $e->getMessage() . '</p>';
        }
    }
}
