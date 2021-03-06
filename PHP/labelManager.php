<?php

class labelManager {
    private $db;

    public function __construct(PDO $database) {
        $this->db = $database;
		$ini = $this->db->prepare("SET NAMES 'utf8'");
		$ini->execute(array());
    }
	
	public function getLabelById($id, $lang) {
		$resultats = $this->db->prepare("SELECT id, ".$lang." FROM Labels where id = :id");
        $resultats->execute(array(
            ":id" => $id
        ));
		if ($retour = $resultats->fetch(PDO::FETCH_ASSOC)) {
			if ($retour[$lang] == null) {
				$resultat = $this->db->prepare("SELECT id, en as ".$lang." FROM Labels where id = :id");
				$resultat->execute(array(
					":id" => $id
				));
				$retour = $resultat->fetch(PDO::FETCH_ASSOC);
			}
		} else {
			$retour['id'] = $id;
			$retour[$lang] = "";
		}
		return $retour;
	}
}
?>