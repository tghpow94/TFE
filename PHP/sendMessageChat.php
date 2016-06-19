<?php

require "db_connect.php";
require "messageManager.php";
require "userManager.php";

$idUser = $_POST['idUser'];
$text = $_POST['text'];

$mm = new messageManager(connexionDb());
$tab = $mm->addMessage($idUser, $text);
echo json_encode($tab);

?>