<?php
// $to = "reports@aplustudents.com";
$to = "umairgilani64@gmail.com";
$subject = "My subject";
$txt = "Hello world!";
$headers = "From: umairgilani64@gmail.com" . "\r\n" .
"CC: somebodyelse@example.com";

// Attempt to send the email
if (mail($to, $subject, $txt, $headers)) {
    echo "Email sent successfully.";
} else {
    echo "Email sending failed. Server did not respond.";
}
?>
