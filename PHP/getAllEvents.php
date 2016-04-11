<?php

require "db_connect.php"
require "eventManager.php"

function getAllEvents(){
	$em = new eventManager(connexionDb());
	$tab = $em->getAllEvents();
	echo json_encode($tab);
}
?>