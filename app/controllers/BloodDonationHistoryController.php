<?php

namespace App\Controllers;

use App\Models\BloodDonationHistory;
use App\Models\User;
use App\Models\Appointment;
use Exception;
use App\Controllers\AuthController;

class BloodDonationHistoryController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        // Only authenticated users can access this page
        AuthController::authorize([]);

        try {
            // Get donation histories with related data
            $donationHistories = BloodDonationHistory::with(['user', 'user.userInfo', 'appointment', 'appointment.event'])
                ->orderBy('donation_date_time', 'desc')
                ->get();
            // Debugging output (optional, remove in production)
            //             echo '<pre>';
            //             print_r($donationHistories);
            //             echo '</pre>';
            // Pass data to view
            $data = ['donationHistories' => $donationHistories];
            require_once '../app/views/blood_donation_history/index.php';
            
        } catch (Exception $e) {
            error_log('Error in BloodDonationHistoryController@index: ' . $e->getMessage());
            echo '<div class="alert alert-danger">Error loading donation history data.</div>';
        }
    }

    public function view($id)
    {
        // Only authenticated users can access this page
        AuthController::authorize([]);
        
        try {
            // Find the donation history record
            $donationHistory = BloodDonationHistory::with(['user', 'user.userInfo', 'appointment', 'appointment.event'])
                ->find($id);
            
            if (!$donationHistory) {
                header('Location: index.php?controller=BloodDonationHistory&action=index');
                exit;
            }
            
            // Pass data to view
            $data = ['donationHistory' => $donationHistory];
            require_once '../app/views/blood_donation_history/view.php';
            
        } catch (Exception $e) {
            error_log('Error in BloodDonationHistoryController@view: ' . $e->getMessage());
            echo '<div class="alert alert-danger">Error loading donation history record.</div>';
        }
    }

    // Admin interface - view all donation histories
    public function adminIndex()
    {
        // Only admin/staff can access this page
        AuthController::authorize(['ADMIN', 'STAFF']);
        
        try {
            // Get all donation histories with related data
            $donationHistories = BloodDonationHistory::with(['user', 'user.userInfo', 'appointment', 'appointment.event'])
                ->orderBy('donation_date_time', 'desc')
                ->get();
                
            // Pass data to view
            $data = ['donationHistories' => $donationHistories];
            require_once '../app/views/admin/blood_donation_history/index.php';
            
        } catch (Exception $e) {
            error_log('Error in BloodDonationHistoryController@adminIndex: ' . $e->getMessage());
            echo '<div class="alert alert-danger">Error loading donation history data.</div>';
        }
    }

    // Calculate statistics for reports and dashboard
    public function getStatistics()
    {
        // Only admin/staff can access this page
        AuthController::authorize(['ADMIN', 'STAFF']);
        
        try {
            // Get total donations
            $totalDonations = BloodDonationHistory::count();
            
            // Get donations by blood type
            $donationsByBloodType = BloodDonationHistory::join('users', 'blood_donation_history.user_id', '=', 'users.cccd')
                ->join('user_info', 'users.cccd', '=', 'user_info.cccd')
                ->selectRaw('user_info.blood_type, COUNT(*) as count')
                ->groupBy('user_info.blood_type')
                ->get()
                ->toArray();
                
            // Get donations by month (for the current year)
            $currentYear = date('Y');
            $donationsByMonth = BloodDonationHistory::whereRaw("YEAR(donation_date_time) = ?", [$currentYear])
                ->selectRaw('MONTH(donation_date_time) as month, COUNT(*) as count')
                ->groupBy('month')
                ->get()
                ->toArray();
                
            $statistics = [
                'totalDonations' => $totalDonations,
                'donationsByBloodType' => $donationsByBloodType,
                'donationsByMonth' => $donationsByMonth
            ];
            
            return $statistics;
            
        } catch (Exception $e) {
            error_log('Error in BloodDonationHistoryController@getStatistics: ' . $e->getMessage());
            return [
                'totalDonations' => 0,
                'donationsByBloodType' => [],
                'donationsByMonth' => []
            ];
        }
    }
}