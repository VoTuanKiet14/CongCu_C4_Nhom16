<?php
// Script to set up PHPMailer without Composer

// Check if PHPMailer is already installed
$phpmailerDir = __DIR__ . '/vendor/phpmailer/phpmailer/src';
if (file_exists($phpmailerDir . '/PHPMailer.php')) {
    echo "PHPMailer is already installed.\n";
    exit;
}

// Create vendor directory structure
if (!is_dir(__DIR__ . '/vendor/phpmailer/phpmailer/src')) {
    mkdir(__DIR__ . '/vendor/phpmailer/phpmailer/src', 0777, true);
}

// URLs for PHPMailer files
$files = [
    'PHPMailer.php' => 'https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/PHPMailer.php',
    'SMTP.php' => 'https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/SMTP.php',
    'Exception.php' => 'https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/Exception.php'
];

// Download each file
foreach ($files as $filename => $url) {
    echo "Downloading $filename...\n";
    $fileContent = file_get_contents($url);
    if ($fileContent === false) {
        echo "Error downloading $filename\n";
        continue;
    }
    
    $result = file_put_contents("$phpmailerDir/$filename", $fileContent);
    if ($result === false) {
        echo "Error saving $filename\n";
    } else {
        echo "Successfully downloaded $filename\n";
    }
}

echo "\nPHPMailer setup complete.\n";
echo "Now make sure your .env file has the correct MAIL_* settings for Gmail:\n";
echo "MAIL_HOST=smtp.gmail.com\n";
echo "MAIL_PORT=587\n";
echo "MAIL_USERNAME=your-gmail@gmail.com\n";
echo "MAIL_PASSWORD=your-app-password\n\n";
echo "Note: For Gmail, you need to create an App Password at https://myaccount.google.com/apppasswords\n";
?>
