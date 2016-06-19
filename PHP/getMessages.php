<?php

require "db_connect.php";
require "messageManager.php";
require "userManager.php";

$idUser = $_POST['id'];

$mm = new messageManager(connexionDb());
$tab = $mm->getMessages();
echo json_encode($tab);

?>