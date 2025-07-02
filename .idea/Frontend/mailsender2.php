<?php
// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Autoload PHPMailer

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP(); // Use SMTP for sending email
    $mail->Host = 'smtp.gmail.com'; // Set the SMTP server (e.g., Gmail: smtp.gmail.com)
    $mail->SMTPAuth = true;
    $mail->Username = 'ilunga866@gmail.com'; // SMTP username
    $mail->Password = 'reymesterio8121'; // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587; // SMTP port

    // Recipients
    $mail->setFrom('ilunga866@gmai.com', 'Mailer'); // Sender's email address
    $mail->addAddress('crazzytuber043@gmail.com', 'Joe User'); // Add a recipient

    // Content
    $mail->isHTML(true); // Send email as HTML
    $mail->Subject = 'Test Email Subject';
    $mail->Body    = '<h1>This is a test email</h1><p>Hello, this is a test email using PHPMailer!</p>';
    $mail->AltBody = 'This is the plain text version of the email.';

    $mail->send(); // Send email
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}