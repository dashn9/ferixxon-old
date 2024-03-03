<?php

require_once __DIR__ . "/../config.php";

class EmailVerifier {
    private $db;

    public function __construct() {
        // Create a PDO instance
        $this->db = new PDO("mysql:host=" . DB_HOST . ";dbname=youxerze", DB_USERNAME, DB_PASSWORD);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function generateActivationReference($email) {
        // Generate a random activation reference
        $activationReference = $this->generateRandomString(30);
        $activationUrl = "https://www.ferixxon.com/email/activate?activationReference=".$activationReference;

        // Insert activation request into the database
        $stmt = $this->db->prepare("INSERT INTO email_activation_requests (email_to_activate, activation_url, activation_reference, created_at, expires_at) VALUES (:email, '$activationUrl', :activationReference, NOW(), NOW() + INTERVAL 1 DAY)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':activationReference', $activationReference);
        $stmt->execute();

        return [$activationReference, $activationUrl];
    }

    public function verifyActivationReference($activationReference) {
        // Check if activation reference exists and is not used
        $stmt = $this->db->prepare("SELECT email_to_activate FROM email_activation_requests WHERE activation_reference = :activationReference AND used = 0 AND expires_at > NOW()");
        $stmt->bindParam(':activationReference', $activationReference);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Fetch and return the associated email address
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Mark the activation reference as used
            $stmt = $this->db->prepare("UPDATE email_activation_requests SET used = 1 WHERE activation_reference = :activationReference");
            $stmt->bindParam(':activationReference', $activationReference);
            $stmt->execute();

            return $result['email_to_activate'];
        } else {
            return false; // Activation reference not found or expired
        }
    }

    private function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public function activateEmail($email) {
        try {
            // Update the email_activated_at field
            $stmt = $this->db->prepare("UPDATE youxerze_baexxic_enfor SET email_activated_at = NOW() WHERE EE_MEOWL = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return true; // Successfully updated
        } catch (PDOException $e) {
            // Handle errors
            echo "Error activating email: " . $e->getMessage();
            return false;
        }
    }

    public function activateEmailIfReferenceValid($activationReference) {
        // Verify the activation reference
        $verifiedEmail = $this->verifyActivationReference($activationReference);
        if ($verifiedEmail !== false) {
            // Activation reference is valid, activate the email
            return $this->activateEmail($verifiedEmail);
        } else {
            // Activation reference is not valid
            return false;
        }
    }
}
?>