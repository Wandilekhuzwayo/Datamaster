<?php
//use Swift_Mailer;
require_once '../vendor/autoload.php';
require_once '../Php/connection.php';
//require_once 'EuX/vendor/swiftmailer/swiftmailer/lib/swift_required.php';

// Create the Transport
$transport = (new Swift_SmtpTransport('mail.sdcreatives.co.za', 465,'ssl'))
  ->setUsername('noreply@sdcreatives.co.za')
  ->setPassword('Ginger5241$')
;

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

// Send the message
function verifyAccount($email, $verifytoken)
{
    global $mailer;
    $body='<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verify Your Account</title>
    </head>
    <body>
        <div class="wrapper">
            <p>
               Hi, a new account with your email has been created please click the link below to verify your account.
            </p>
            <a href="http://localhost/datamaster/Admin_Dash-board/php/verify.php?token=' . $verifytoken . '">Verify your account</a>
        </div>
    </body>
    </html>';

    $message = (new Swift_Message('Verify your account'))
        ->setFrom('noreply@sdcreatives.co.za')
        ->setTo($email)
        ->setBody($body, 'text/html');

    // Send the message
    $result = $mailer->send($message);
}

// Send the message
function sendEmail($userEmail, $token)
{
    global $mailer;
    $body='<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Password</title>
    </head>
    <body>
        <div class="wrapper">
            <p>
               Thank you for requesting to reset your password, and below is the link that will redirect you to reset your password.
            </p>
        
            <a href="http://localhost/datamaster/Admin_Dash-board/pages/resetPassword.html?token=' . $token . '"> Click here to reset your password </a>
            
        </div>
    </body>
    </html>';

    $message = (new Swift_Message('Reset your password'))
        ->setFrom('noreply@sdcreatives.co.za')
        ->setTo($userEmail)
        ->setBody($body, 'text/html');

    // Send the message
    $result = $mailer->send($message);
}
?>

