<?php

require "db_connect.php";
require "eventManager.php";
require "labelManager.php";

	$em = new eventManager(connexionDb());
	$tab = $em->getAllEvents();
	echo json_encode($tab);
?>