<?php
	
	$name = $_POST['name'];
	$mail = $_POST['mail'];
	$message = $_POST['message'];
	$lang = $_POST['lang'];
	
	$mailVerif = champsEmailValable($mail);
	$message = str_replace("<", "", $message);
	$message = str_replace(";", "", $message);
	$name = str_replace("<", "", $name);
	$name = str_replace(";", "", $name);
	
	if ($mailVerif) {
		$message1 = $name." a envoyé le message suivant : <br><br>".$message;
		$to = "thomaspicke2@gmail.com";
		$from = $mail;
		$sujet = "BPHO Appli : Message de ".$name;
		$entete = "From:" . $from . "\r\n";
        $entete .= "Content-Type: text/html; charset=utf-8\r\n";
		mail($to, $sujet, $message1, $entete);

		switch($lang) {
			case "nl" :
				$messageConfirm = "Het volgende bericht werd verzonden naar Brussels Philharmonic Orchestra : <br><br>".$message;
				break;
			case "en" :
				$messageConfirm = "The following message was sent to Brussels Philharmonic Orchestra : <br><br>".$message;
				break;
			default :
				$messageConfirm = "Le message suivant a été envoyé au Brussels Philharmonic Orchestra : <br><br>".$message;
				break;
		}
		$to = $mail;
		$from = "thomaspicke2@gmail.com";
		$sujet = "BPHO Appli : votre message a bien été envoyé";
		$entete = "From:" . $from . "\r\n";
		$entete .= "Content-Type: text/html; charset=utf-8\r\n";
		$retour = mail($to, $sujet, $messageConfirm, $entete);
		echo json_encode($retour);
	} else {
		$retour = false;
		echo json_encode($retour);
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