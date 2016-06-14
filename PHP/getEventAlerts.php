<?php

require "db_connect.php";
require "eventManager.php";
require "labelManager.php";

$idEvent = $_POST['idEvent'];
$idUser = $_POST['idUser'];

$em = new eventManager(connexionDb());
$tab = $em->getEventAlerts($idEvent, $idUser);
echo json_encode($tab);

?>