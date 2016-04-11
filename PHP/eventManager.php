<?php

class EventManager {
    private $db;

    public function __construct(PDO $database)
    {
        $this->db = $database;
    }
	
	public function getAllEvents() {
		$resultats = $this->db->query("SELECT * FROM Events");
        $resultats->execute();

        $tabEvent = $resultats->fetchAll(PDO::FETCH_ASSOC);
		foreach($tabEvent as $row){
			if $row['endDate'] = null or !isset($row['endDate']) {
				$row['endDate'] = "";
			}
		}
		return $tabEvent;
	}
}
?>