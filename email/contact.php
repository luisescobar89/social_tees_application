<?php
if (isset($_POST['Submit'])) {

$to      = 'luis.scobr@gmail.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: webmaster@example.com' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

$mail=mail($to, $subject, $message, $headers);

if($mail){
echo "email sent";

}
else{
echo "Mail sending failed";
}

}
?>

<form action="" method="POST">
<input type="submit" value="Submit" name="Submit">
</form>