<?php

require "db_connect.php";
require "userManager.php";

$mail = $_POST['mail'];

$um = new userManager(connexionDb());
$checkUser["check"] = $um->resetPassword($mail);
echo json_encode($checkUser);

?>