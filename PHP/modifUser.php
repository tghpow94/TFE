<?php

require "db_connect.php";
require "userManager.php";

$id = $_POST['id'];
$name = $_POST['name'];
$firstName = $_POST['firstName'];
$oldMail = $_POST['oldMail'];
$mail = $_POST['mail'];
$phone = $_POST['phone'];
$newPassword = $_POST['newPassword'];
$oldPassword = $_POST['oldPassword'];

$user['id'] = $id;
$user['name'] = $name;
$user['firstName'] = $firstName;
$user['oldMail'] = $oldMail;
$user['mail'] = $mail;
$user['phone'] = $phone;
$user['newPassword'] = $newPassword;
$user['oldPassword'] = $oldPassword;

$um = new userManager(connexionDb());
$tab = $um->updateUser($user);
echo json_encode($tab);

?>