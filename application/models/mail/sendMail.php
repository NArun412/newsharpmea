<?php

require('PHPMailer/src/PHPMailer.php');
require('PHPMailer/src/Exception.php');
require('PHPMailer/src/SMTP.php');

class CI_Sendmail{
public function sendMail($toMail,$subject,$body)
{
	$mail = new PHPMailer\PHPMailer\PHPMailer;
	$mail->isSMTP(); 
	$mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
	$mail->Host = "smtp.gmail.com"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
	$mail->Port = 587; // TLS only
	//$mail->SMTPSecure = 'tls'; // ssl is depracated
	$mail->SMTPAuth = true;
	$mail->Username = "noreply@smefworld.com";
	$mail->Password = "Welcome@12345";
	$mail->setFrom("noreply@smefworld.com", "noreply@smefworld.com");
	$mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => false
            )
        );
        $mail->CharSet = "UTF-8";
	$mail->addAddress($toMail);
	$mail->Subject = $subject;
	//$mail->msgHTML("From : ".$data['mailId']." <br/>Subject ".$data['subject']." <br/>Message : ".$data['message']); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
	$mail->MsgHTML($body);
	// $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file
        //$mail->send()
        if(!$mail->send()){
            echo $mail->ErrorInfo;
	//	echo "Mailer Error: ";
	}
 
    }
}
?>