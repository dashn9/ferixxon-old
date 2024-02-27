<?php
require_once(__DIR__ . '/vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

// Set AWS credentials
$credentials = [
    'Statement' => [
        'Effect' => 'Allow',
        'Action' => [
            'ses' => '*'
        ],
        'Resource' => '*'
    ],
    'credentials' => [
        'key'    => '',
        'secret' => ''
    ],
    'version' => 'latest',
    'region' => 'us-east-1', // Change this to your desired AWS region
];

// Create an SES client
$sesClient = new SesClient($credentials);

// Define the email parameters
$params = [
    'Destination' => [
        'ToAddresses' => ['ishogbon@gmail.com'], // Change this to the recipient's email address
    ],
    'Message' => [
        'Body' => [
            'Text' => [
                'Charset' => 'UTF-8',
                'Data' => 'This is the body of the email.',
            ],
        ],
        'Subject' => [
            'Charset' => 'UTF-8',
            'Data' => 'Test email subject',
        ],
    ],
    'Source' => 'do-not-reply@ferixxon.com', // Change this to your verified sender email address
];

try {
    // Send the email
    $result = $sesClient->sendEmail($params);
    echo 'Email sent! Message ID: ' . $result['MessageId'];
} catch (AwsException $e) {
    // Handle errors
    echo 'Error: ' . $e->getAwsErrorMessage();
}


?>