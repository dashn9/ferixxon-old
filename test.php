<?php
require_once('./private/notification/email_sender.php');

$emailSender = new EmailSender();

try {
    $message = $emailSender->activateEmailTemplate("Daniel", "ishogbon@gmail.com", "3848477477727272277884727732938293");
    $messageId = $emailSender->sendEmail(EmailSender::ALLOWED_SENDERS[0], 'ishogbon@gmail.com', $message["subject"], $message["body"]);
    echo $messageId;
} catch (Exception $e) {
    error_log('Email sending error: ' . $e->getMessage());
}
?>