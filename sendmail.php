<?php

function email($to, $sub, $body) {

  # $to_email = "njitit490@gmail.com";
  # $subject = "Simple Email Test via PHP";
  # $body = "Hi, This is test email send by PHP Script";
   $headers = "From: njitit490@gmail.com";

   if ( mail($to, $sub, $body, $headers)) {
      echo("Email successfully sent to $to...");
   } else {
      echo("Email sending failed...");
   }
   }
?>

