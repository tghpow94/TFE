<?php

require "db_connect.php";
require "eventManager.php";
require "labelManager.php";

$offset = $_POST['offset'];
$lang = $_POST['lang'];
$type = $_POST['type'];
$idUser = $_POST['idUser'];

$em = new eventManager(connexionDb());
$tab = $em->getAllEvents($offset, $lang, $type, $idUser);
echo json_encode($tab);

?>