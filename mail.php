<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form inputs
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r", "\n"), array(" ", " "), $name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    // $number = trim($_POST["number"]);
    $website = isset($_POST["website"]) ? trim($_POST["website"]) : 'MAZZCUY ART';
    // $date = isset($_POST["date"]) && trim($_POST["date"]) !== '' ? trim($_POST["date"]) : 'Not specified';
    $comment = trim($_POST["message"]);

    // Validate required fields
    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Please complete the form and try again.";
        
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Configure SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'al3jandro9400@gmail.com'; // Your Gmail address
        $mail->Password = 'oltz syth ndav etqo'; // Your Gmail app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Set sender and recipient
        $mail->setFrom($email, $website);
        $mail->addAddress('al3jandroads@gmail.com'); // Your recipient email

        // Set email format and content
        $mail->isHTML(true);
        $mail->Subject = "Mail contact from $name";
        $mail->Body = "Name: $name<br>Email: $email<br>Comment: $comment";

        // Attempt to send the email
        $mail->send();
        
        // Respond with success message for AJAX
        http_response_code(200);
        echo "Thank you! Your message has been sent.";
        

    } catch (Exception $e) {
        // Respond with error message for AJAX
        http_response_code(500);
        echo "Oops! Something went wrong, and we couldn't send your message. Error: {$mail->ErrorInfo}";
        
    }
} else {
    // Respond with 403 error if not a POST request
    http_response_code(403);
    echo "There was an issue with your submission. Please try again.";
    
}
?>
