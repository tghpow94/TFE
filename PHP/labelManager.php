<?php

class labelManager {
    private $db;

    public function __construct(PDO $database) {
        $this->db = $database;
    }
	
	public function getLabelById($id, $lang) {
		$resultats = $this->db->prepare("SELECT id, ".$lang." FROM Labels where id = :id");
        $resultats->execute(array(
            ":id" => $id
        ));
		if ($retour = $resultats->fetch(PDO::FETCH_ASSOC)) {
			return $retour;
		} else {
			$resultat = $this->db->prepare("SELECT id, fr FROM Labels where id = :id");
			$resultat->execute(array(
				":id" => $id
			));
			$retour = $resultat->fetch(PDO::FETCH_ASSOC);
			return $retour;
		}
	}
}
?>