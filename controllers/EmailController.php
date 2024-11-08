<?php
// controllers/EmailController.php
namespace Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class EmailController extends BaseController {
    public function sendSimpleEmail($to, $subject, $message) 
    {
        $headers = "From: no-reply@example.com\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8";

        if (mail($to, $subject, $message, $headers)) {
            $this->jsonResponse(['message' => 'Email sent successfully'], 200);
        } else {
            $this->jsonResponse(['error' => 'Failed to send email'], 500);
        }
    }

    public function sendEmailWithPHPMailer($to, $subject, $message, $from = 'no-reply@example.com') 
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.example.com';  // SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your-email@example.com';
            $mail->Password   = 'your-email-password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom($from, 'Your App Name');
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            $this->jsonResponse(['message' => 'Email sent successfully'], 200);
        } catch (Exception $e) {
            error_log("Failed message error: " . $e->getMessage());
            $this->jsonResponse(['error' => "Message could not be sent. Error: {$mail->ErrorInfo}"], 500);
        }
    }
}
