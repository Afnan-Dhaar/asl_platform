<?php
include("config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$message = htmlspecialchars($_POST['message']);

$stmt = $conn->prepare("INSERT INTO contact_messages(name,email,message) VALUES(?,?,?)");
$stmt->bind_param("sss",$name,$email,$message);
$stmt->execute();

$mail = new PHPMailer(true);

try{

$mail->isSMTP();

$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;

$mail->Username = 'afnandhar786@gmail.com';
$mail->Password = 'zdfs mosm tash pylf';

$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom($email, $name);

$mail->addAddress('afnandhar786@gmail.com');

$mail->Subject = "New Contact Message";

$mail->Body = "
Name: $name

Email: $email

Message:
$message
";

$mail->send();

header("Location: contact.php?success=1");

}catch(Exception $e){

header("Location: contact.php?error=1");

}

}