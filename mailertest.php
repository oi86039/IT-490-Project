<?php

//to the sender
$to = 'reeleex@yahoo.com';

//subject
$subject = 'Test mail';

//message body

$message = '<h1>Whats good bruh!</h>';

//headers

$headers = "From: Butch Cassidy <anwarrshaw@gmail.com>\r\n";
$headers .= "Reply-To: anwarrshaw@gmail.com\r\n";
$headers .= "Content-Type: text/html\r\n";


// Send email

mail($to, $subject, $message, $headers);



//test the mail function
//$headers[] = 'Content-type: text/html; charset=iso-8859-1';
//mail ('omar.0426@yahoo.com', 'test email', 'test', $headers[] = 'Content-type: text/html; charset=iso-8859-1');

//echo "email successfully sent";

?>
 

