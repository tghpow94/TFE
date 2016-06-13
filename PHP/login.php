<?php

require "db_connect.php";
require "userManager.php";

$mail = $_POST['mail'];
$password = $_POST['mdp'];

$um = new userManager(connexionDb());
$user = $um->getUserConnect($mail, $password);
echo json_encode($user);

?>