<?php

require "db_connect.php";
require "userManager.php";

$idUser = $_POST['idUser'];

$um = new userManager(connexionDb());
$tab = $um->getUsers();
echo json_encode($tab);

?>