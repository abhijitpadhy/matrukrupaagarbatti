<?php

// 🔹 CORS HEADERS (VERY IMPORTANT)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// 🔹 Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 🔹 Include PHPMailer
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// 🔹 Get JSON data from React
$data = json_decode(file_get_contents("php://input"), true);

// 🔹 Validate fields
$name    = $data['name'] ?? '';
$phone   = $data['phone'] ?? '';
$email   = $data['email'] ?? '';
$message = $data['message'] ?? '';

if (!$name || !$phone || !$message) {
    echo json_encode(["success" => false, "error" => "Missing required fields"]);
    exit();
}

$mail = new PHPMailer(true);

try {

    // 🔹 SMTP SETTINGS
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'johnpadhy@gmail.com';   // your gmail
    $mail->Password   = 'gmstfyrpukbplsmz';           // gmail app password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // 🔹 EMAIL SETTINGS
    $mail->setFrom('johnpadhygmail@gmail.com', 'Agarbatti Website');
    $mail->addAddress('johnpadhygmail@gmail.com'); // where you receive leads

    // 🔹 MAIL CONTENT
    $mail->isHTML(true);
    $mail->Subject = 'New Agarbatti Inquiry';

    $mail->Body = "
        <h2>New Website Inquiry</h2>
        <p><b>Name:</b> {$name}</p>
        <p><b>Phone:</b> {$phone}</p>
        <p><b>Email:</b> {$email}</p>
        <p><b>Message:</b> {$message}</p>
    ";

    $mail->send();

    echo json_encode(["success" => true, "message" => "Mail sent"]);

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "error" => $mail->ErrorInfo
    ]);
}
