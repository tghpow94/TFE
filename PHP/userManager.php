<?php

class UserManager {
    private $db;

    public function __construct(PDO $database) {
        $this->db = $database;
        $ini = $this->db->prepare("SET NAMES 'utf8'");
        $ini->execute(array());
    }

    public function getUserConnect($mail, $password) {
        $user = $this->getUserByMail($mail);
        if ($user != null && is_numeric($user['id'])) {
            $password = hash("sha256", $password . $user['salt']);
            $resultats = $this->db->prepare("SELECT * FROM Users where email = :email AND password = :password  ");
            $resultats->execute(array(
                ":email" => $mail,
                ":password" => $password
            ));

            $tabUsers = $resultats->fetchAll(PDO::FETCH_ASSOC);
            if (isset($tabUsers[0]['id'])) {
                $tabUsers[0]['droit'] = $this->getUserDroit($tabUsers[0]['id']);
                $tabUsers[0]['error'] = false;
                $this->updateLastConnect($mail);
            } else {
                $tabUsers[0]['error'] = true;
            }
        } else {
            $tabUsers[0]['error'] = true;
        }
        return $tabUsers[0];
    }

    function updateLastConnect($mail) {
        $resultats = $this->db->prepare("UPDATE Users SET dateLastConnect = :dateLastConnect WHERE email = :email ");
        $resultats->execute(array(
            ":email" => $mail,
            ":dateLastConnect" => date("Y-m-d H:i:s")
        ));
    }

    public function getUserDroit($id) {
        $resultats = $this->db->prepare("SELECT * FROM User_right where idUser = :idUser");
        $resultats->execute(array(
            ":idUser" => $id
        ));

        $tabUsers = $resultats->fetchAll(PDO::FETCH_ASSOC);

        if (isset($tabUsers[0]['idRight']))
            return $tabUsers[0]['idRight'];
        else
            return null;
    }

    public function getUserByMail($mail) {
        $resultats = $this->db->prepare("SELECT * FROM Users where email = :email");
        $resultats->execute(array(
            ":email" => $mail
        ));

        $tabUsers = $resultats->fetchAll(PDO::FETCH_ASSOC);

        if (isset($tabUsers[0]['id']))
            return $tabUsers[0];
        else
            return null;
    }

    public function resetPassword($mail) {
        $resultats = $this->db->prepare("SELECT * FROM Users where email = :email");
        $resultats->execute(array(
            ":email" => $mail
        ));

        $tabUsers = $resultats->fetchAll(PDO::FETCH_ASSOC);

        if (isset($tabUsers[0]['id'])) {
            $user = $tabUsers[0];
            $code = $this->genererCode();

            $resultats = $this->db->prepare("DELETE FROM Activation WHERE idUser = :idUser AND description = 'mot de passe oublié'");
            $resultats->execute(array(
                ":idUser" => $user['id']
            ));

            $resultats = $this->db->prepare("INSERT INTO Activation (idUser, code, date, description) VALUES (:idUser, :code, :date, :description)");
            $resultats->execute(array(
                ":idUser" => $user['id'],
                ":code" => $code,
                ":date" => date("Y-m-d H:i:s"),
                ":description" => "mot de passe oublié"
            ));

            $message = "Bonjour, <br><br>
						Vous avez demandé à réinitialiser votre mot de passe.<br><br>
						Cliquez sur le lien suivant pour finaliser la procédure : <a href='http://91.121.151.137/TFE/bpho/admin/passwordreset_final?code=".$code."'>http://91.121.151.137/TFE/bpho/admin/passwordreset_final?code=".$code."</a>";

            $to = $mail;
            $from = "thomaspicke2@gmail.com";
            $sujet = "BPHO : Réinitialisation de votre mot de passe";
            $entete = "From:" . $from . "\r\n";
            $entete .= "Content-Type: text/html; charset=utf-8\r\n";
            mail($to, $sujet, $message, $entete);

            return true;
        } else
            return false;
    }

    function genererCode() {
        $characts    = 'abcdefghijklmnopqrstuvwxyz';
        $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characts   .= '1234567890';
        $code_aleatoire      = '';

        for($i=0;$i < 10;$i++){
            $code_aleatoire .= substr($characts,rand()%(strlen($characts)),1);
        }
        return $code_aleatoire;
    }

    public function getAllEvents($offset, $lang) {
        $date = date("Y-m-d H:i:s");
        $newDate = date("Y-m-d H:i:s", strtotime("$date -$offset month"));
        $resultats = $this->db->prepare("SELECT * FROM Events where startDate > :newDate  ORDER BY startDate");
        $resultats->execute(array(
            ":newDate" => $newDate
        ));

        $tabEvent = $resultats->fetchAll(PDO::FETCH_ASSOC);
        $tab = array();
        $lm = new labelManager(connexionDb());
        foreach($tabEvent as $row){
            $event = $row;
            $id = $event['id'];
            $cityCode = $event['cityCode'];
            foreach($event as &$elem) {
                if(is_numeric($elem)) {
                    $label = $lm->getLabelById($elem, $lang);
                    $elem = $label[$lang];
                }
            }
            $event['id'] = $id;
            $event['cityCode'] = $cityCode;
            $tab[] = $event;
        }
        return $tab;
    }
}
?>