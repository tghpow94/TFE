<?php
	
	$name = $_POST['name'];
	$mail = $_POST['mail'];
	$message = $_POST['message'];
	
	$mailVerif = champsEmailValable($mail);
	$message = str_replace("<", "", $message);
	$message = str_replace(";", "", $message);
	$name = str_replace("<", "", $name);
	$name = str_replace(";", "", $name);
	
	if ($mailVerif) {
		$to = "thomaspicke2@gmail.com";
		$from = $mail;
		$sujet = "BPHO Appli : Message de ".$name;
		$entete = "From:" . $from . "\r\n";
        $entete .= "Content-Type: text/html; charset=utf-8\r\n";
		mail($to, $sujet, $message, $entete);

		$to = $mail;
		$from = "thomaspicke2@gmail.com";
		$sujet = "BPHO Appli : votre message a bien été envoyé";
		$entete = "From:" . $from . "\r\n";
		$entete .= "Content-Type: text/html; charset=utf-8\r\n";
		mail($to, $sujet, $message, $entete);
	}
	
	function champsEmailValable($champ) {
		if(preg_match('#^[a-zA-Z0-9@._]*$#', $champ)) {
			return true;
		}
		else {
			return false;
		}
	}
	
?>