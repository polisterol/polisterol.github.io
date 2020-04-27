<?
	if (! (isset($_SERVER) && $_SERVER['HTTP_REFERER'] != null) ) return;
	if (! (isset($_SERVER) && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST') ) return;
	if (! (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && $_POST['name'] != '' && $_POST['email'] != '' && $_POST['phone'] != '') ) return;

	$form_name  = htmlspecialchars( strip_tags($_POST['name']) );
	$form_email = htmlspecialchars( strip_tags($_POST['email']) );
	$form_phone = htmlspecialchars( strip_tags($_POST['phone']) );

	// Please write this data for authentication and successful send email
	$host	  = 'smtp.mail.ru';
	$port     = 587;
	$login    = 'from_email';
	$password = 'password';
	$from     = array(
		'email' => $login,
		'name'  => 'Site'
	);
	$to       = array(
		'email' => 'to_email',
		'name'  => ''
	);
	// end data section
	

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	require 'mailer/Exception.php';
	require 'mailer/SMTP.php';
	require 'mailer/PHPMailer.php';
	
	// Instantiation and passing `true` enables exceptions
	$mail = new PHPMailer(true);
	$mail->CharSet = 'UTF-8';

	try {
	    //Server settings
	    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
	    $mail->SMTPDebug = SMTP::DEBUG_OFF;							   // Disable verbose debug output
	    $mail->isSMTP();                                            // Send using SMTP
	    $mail->Host       = $host;                    // Set the SMTP server to send through
	    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
	    $mail->Username   = $login;                     // SMTP username
	    $mail->Password   = $password;                               // SMTP password
	    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
		// $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
		$mail->Port       = $port;

	    //Recipients
		// $mail->setFrom('mail@mail.ru', 'Site');
		$mail->setFrom($from["email"], $from["name"]);
	    // $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
		// $mail->addAddress('who@mail.ru');               // Name is optional
		$mail->addAddress($to["email"], $to["name"]);
	    // $mail->addReplyTo('info@example.com', 'Information');
	    // $mail->addCC('cc@example.com');
	    // $mail->addBCC('bcc@example.com');

	    // Attachments
	    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

	    // Content
	    $mail->isHTML(true);                                  // Set email format to HTML
	    $mail->Subject = 'Заявка на бесплатный урок';
		$mail->Body    = "
			Имя:                 {$form_name},<br>
			Электронная почта:   {$form_email},<br>
			Телефон:             {$form_phone}<br>
			<br><br>
			Письмо сгенерировано скриптом: {$_SERVER['SCRIPT_FILENAME']}.
			<br><br>
		";
	    $mail->AltBody = "
		Имя:                 {$form_name},\n
		Электронная почта:   {$form_email},\n
		Телефон:             {$form_phone}\n
		\n\n
		Письмо сгенерировано скриптом: {$_SERVER['SCRIPT_FILENAME']}.
		\n\n
	";
	    $mail->send();
	    // echo 'Message has been sent';
} catch (Exception $e) {
    // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
header("Location: ".$_SERVER['HTTP_REFERER']);
?>