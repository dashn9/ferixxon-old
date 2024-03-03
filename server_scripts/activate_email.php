<?php
require_once __DIR__ . "/../private/config.php";
require_once __DIR__ . "/../private/auth/email_verification.php";

// Check if activation reference is provided in the URL
if(isset($_GET['activationReference'])) {
    $activationReference = $_GET['activationReference'];

    try {
        $authenticator = new EmailVerifier();

        // Activate email using the provided activation reference
        if ($authenticator->activateEmailIfReferenceValid($activationReference)) {
            header("Location: https://www.ferixxon.com/signin", true, 302);
            exit; // Ensure no further output is sent
        } else {
            echo "Invalid or expired activation reference.";
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Activation reference not provided.";
}
?>
