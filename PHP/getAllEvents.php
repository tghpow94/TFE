<?php

require "db_connect.php";
require "eventManager.php";
require "labelManager.php";

	$offset = $_POST['offset'];
	$lang = $_POST['lang'];

	$em = new eventManager(connexionDb());
	$tab = $em->getAllEvents($offset, $lang);
	echo json_encode($tab);
?>