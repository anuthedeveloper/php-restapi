<?php
// controllers/EmailController.php
namespace App\Http\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailController {
    public function sendSimpleEmail($to, $subject, $message) 
    {
        $headers = "From: no-reply@example.com\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8";

        if (mail($to, $subject, $message, $headers)) {
            http_response_code(200);
            echo json_encode(['message' => 'Email sent successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to send email']);
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
            http_response_code(200);
            echo json_encode(['message' => 'Email sent successfully']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => "Message could not be sent. Error: {$mail->ErrorInfo}"]);
        }
    }
}
