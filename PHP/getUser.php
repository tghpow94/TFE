<?php

require "db_connect.php";
require "userManager.php";

$id = $_POST['id'];

$um = new userManager(connexionDb());
$tab = $um->getUser($id);
echo json_encode($tab);

?>