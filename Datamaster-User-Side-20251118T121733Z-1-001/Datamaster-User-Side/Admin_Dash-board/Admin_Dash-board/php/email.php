<?php

require_once '../vendor/autoload.php';
require_once '../Php/connection.php';

// Create the Transport
// Create the Transport
$transport = (new Swift_SmtpTransport('mail.datmaster.co.za', 465,'ssl'))

  ->setUsername('noreply@datmaster.co.za')
  ->setPassword('Ginger5241$')
;

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

// Create a message
$message = (new Swift_Message('Wonderful Subject'))
  ->setFrom(['john@doe.com' => 'John Doe'])
  ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
  ->setBody('Here is the message itself')
  ;
  $result = $mailer->send($message);

 function verifyAccount($email,$verifytoken)
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
             Hi, a new account with your email has been created please click the link below to verify your account.
          </p>
          <a  href="http://localhost/Admin_Dash-board/pages/signin.html?token=<?php echo $verifytoken;?>"> Verify your account</a>
      </div>
  </body>
  </html>';
  $message = (new Swift_Message('Verify your account'))
->setFrom('noreply@datmaster.co.za')
->setTo($email)
->setBody($body,'text/html')
;

// Send the message
$result = $mailer->send($message);
  
 }
// Send the message
function sendEmail($userEmail,$token)
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
               Thank you for rquesting to reset your password, and below  is the link that will redirect you to reset your password.
            </p>
            <a  href="http://localhost/Admin_Dash-board/pages/resetPassword.html?token=<?php echo $token;?>"> Click here to reset your password</a>
        </div>
    </body>
    </html>';
    $message = (new Swift_Message('Reset your password'))
  ->setFrom('noreply@datmaster.co.za')
  ->setTo($userEmail)
  ->setBody($body,'text/html')
  ;

// Send the message
$result = $mailer->send($message);

}