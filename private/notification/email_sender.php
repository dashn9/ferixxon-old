<?php

require_once __DIR__ . '/../config.php';

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

class EmailSender {
    private $sesClient;
    private $allowedSenders;

    public const ALLOWED_SENDERS = [
        'email-activation-do-not-reply@ferixxon.com'
    ];

    public function __construct() {
        $this->sesClient = new SesClient([
            'credentials' => [
                'key'    => AWS_KEY,
                'secret' => AWS_SECRET,
            ],
            'version' => 'latest',
            'region' => AWS_REGION,
        ]);
    }

    public function activateEmailTemplate(string $name = "", string $email, string $activation_reference) {
        $activation_url = "https://www.ferixxon.com/email/activation/$activation_reference";
        return [
            "subject" => "Activate Account", 
            "body" => "Hi, $name <br><br>
            Please <a href='$activation_url'>verify your newly created ferixxon account</a> or follow this link $activation_url. <br><br>
            If you never attempted to create an account with the email: $email, disregard this email. <br><br>
            For more information and enquires, please contact 08178376372. Thanks"
        ];
    }

    public function sendEmail(string $sender, string $recipient, string $subject, string $body) {
        if (!in_array($sender, SELF::ALLOWED_SENDERS)) {
            throw new Exception("Unauthorized sender.");
        }

        $params = [
            'Destination' => [
                'ToAddresses' => [$recipient],
            ],
            'Message' => [
                'Body' => [
                    'Html' => [
                        'Charset' => 'UTF-8',
                        'Data' => $body,
                    ],
                ],
                'Subject' => [
                    'Charset' => 'UTF-8',
                    'Data' => $subject,
                ],
            ],
            'Source' => "\"Ferixxon\" <$sender>",
        ];

        try {
            $result = $this->sesClient->sendEmail($params);
            return $result['MessageId'];
        } catch (AwsException $e) {
            // Log or handle errors appropriately
            throw new Exception('Email sending failed: ' . $e->getAwsErrorMessage());
        }
    }
}


?>
