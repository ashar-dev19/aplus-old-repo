<?php


$to = "obhatti11@gmail.com";
$subject = "Our 3rd newest email";

$message = "
Hi! My email is an example. This is just a test email to see if code is working!
";


// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <reports@aplustudents.com>' . "\r\n";
$headers .= 'Cc: obhatti11@gmail.com' . "\r\n";

mail($to,$subject,$message,$headers);


?>

