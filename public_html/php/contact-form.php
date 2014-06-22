<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));

header('Content-type: application/json');

require 'php-mailer/class.phpmailer.php';

$error = 0;
$success = 0;

if( isset($_POST['action']) && $_POST['action'] == 'send_mail'){

// Your email address
$to = 'you@domain.com';

$fields = empty( $_POST['fields'] ) ? array() : $_POST['fields'];
$name = @ $fields['name'];
$email = @ $fields['email'];


if($to && $name != '' && $email != '') {
	
	$message = "";

	foreach ( $fields as $field=>$value ) {
	     $message .= $field.": " . htmlspecialchars($value, ENT_QUOTES) . "<br>\n";
	}
	
	$mail = new PHPMailer;

	$mail->IsSMTP();                                      // Set mailer to use SMTP
	
	// Optional Settings
	//$mail->Host = 'mail.yourserver.com';				  // Specify main and backup server
	//$mail->SMTPAuth = true;                             // Enable SMTP authentication
	//$mail->Username = 'username';             		  // SMTP username
	//$mail->Password = 'secret';                         // SMTP password
	//$mail->SMTPSecure = 'tls';                          // Enable encryption, 'ssl' also accepted

	$mail->From = $email;
	$mail->FromName = $name;
	$mail->AddAddress($to);								  // Add a recipient
	$mail->AddReplyTo($email, $name);

	$mail->IsHTML(true);                                  // Set email format to HTML
	
	$mail->CharSet = 'UTF-8';
    // Change your Subject Here
	$mail->Subject = ' Feedback from '.$name.' <'.$email.'> ';
	$mail->Body    = $message;

	if(!$mail->Send()) 
	{
		$error = 1;
	}
	else
	{
		$success = 1;
	}
	
} else {
	 $error = 1;
}

$response = array( 'success' => $success , 'error' => $error );
echo json_encode ($response);

}

else {
	die('wrong form Submission');
}



?>