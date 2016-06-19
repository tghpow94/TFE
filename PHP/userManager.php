<?php

class UserManager {
    private $db;

    public function __construct(PDO $database) {
        $this->db = $database;
        $ini = $this->db->prepare("SET NAMES 'utf8'");
        $ini->execute(array());
    }

    public function getUsers() {
        $resultats = $this->db->prepare("SELECT id, name, firstName FROM Users ");
        $resultats->execute(array(
        ));

        $tabUsers = $resultats->fetchAll(PDO::FETCH_ASSOC);

        if (isset($tabUsers[0]['id'])) {
            $tabUsers[0]['error'] = false;
        } else {
            return $tabUsers;
            $tabUsers[0]['error'] = true;
        }

        return $tabUsers;
    }

    public function getUser($id) {
        $resultats = $this->db->prepare("SELECT id, name, firstName, dateRegister, dateLastConnect, email, phone FROM Users where id = :id");
        $resultats->execute(array(
            ":id" => $id
        ));

        $tabUsers = $resultats->fetchAll(PDO::FETCH_ASSOC);

        if (isset($tabUsers[0]['id'])) {
            $tabUsers[0]['error'] = false;
            $tabUsers[0]['droit'] = $this->getUserDroitName($tabUsers[0]['id']);
            $tabUsers[0]['hasInstrument'] = $this->hasInstrument($id);
            if($tabUsers[0]['hasInstrument']) {
                $tabUsers[0]['instruments'] = $this->getUserInstruments($id);
            }
        } else {
            $tabUsers[0]['error'] = true;
        }

        return $tabUsers[0];
    }

    public function hasInstrument($id) {
        $resultats = $this->db->prepare("SELECT * FROM User_instrument where idUser = :id");
        $resultats->execute(array(
            ":id" => $id
        ));
        $tabUsers = $resultats->fetchAll(PDO::FETCH_ASSOC);
        if(isset($tabUsers[0]['idUser'])) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserInstruments($id) {
        $resultats = $this->db->prepare("SELECT * FROM Instruments where id IN (SELECT idInstrument FROM User_instrument WHERE idUser = :id AND idInstrument = id)");
        $resultats->execute(array(
            ":id" => $id
        ));

        $tabInstruments = $resultats->fetchAll(PDO::FETCH_ASSOC);
        $tabReturn = array();

        if(isset($tabInstruments[0]['name'])) {
            foreach($tabInstruments as $instrument) {
                $tabReturn[] = $instrument['name'];
            }
            return $tabReturn;
        } else {
            return false;
        }
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

    public function getUserDroitName($id) {
        $resultats = $this->db->prepare("SELECT * FROM Rights WHERE id IN (SELECT idRight FROM User_right WHERE idRight = id AND idUser = :id)");
        $resultats->execute(array(
            ":id" => $id
        ));

        $tabUsers = $resultats->fetchAll(PDO::FETCH_ASSOC);

        if (isset($tabUsers[0]['name']))
            return $tabUsers[0]['name'];
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

    function updateUser($newUser) {
        $newUser['name'][0] = strtoupper($newUser['name'][0]);
        $newUser['firstName'][0] = strtoupper($newUser['firstName'][0]);
        $retour['error'] = "ok";
        $retour['name'] = $newUser['name'];
        $retour['firstName'] = $newUser['firstName'];

        if($newUser['mail'] != $newUser['oldMail']) {
            $emailChange = true;
        } else {
            $emailChange = false;
        }

        if($this->champsEmailValable($newUser['mail'])) {
            if($this->getUserByMail($newUser['mail']) != null && $emailChange) {
                $retour['error'] = "email taken";
            } else {
                $user = $this->getUserConnect($newUser['oldMail'], $newUser['oldPassword']);
                if (isset($user['id']) && $user['error'] == false) {
                    $retour['error'] = "ok";
                    if($newUser['mail'] != $newUser['oldMail']) {
                        $this->updateEmail($newUser['id'], $newUser['mail']);
                    }
                    $resultats = $this->db->prepare("UPDATE Users SET name = :name, firstName = :firstName, phone = :phone  WHERE id = :id");
                    $resultats->execute(array(
                        ":name" => $newUser['name'],
                        ":firstName" => $newUser['firstName'],
                        ":phone" => $newUser['phone'],
                        ":id" => $newUser['id']
                    ));

                    if ($newUser['newPassword'] != "") {
                        $password = hash("sha256", $newUser['newPassword'] . $user['salt']);
                        $resultats = $this->db->prepare("UPDATE Users SET password = :password WHERE id = :id");
                        $resultats->execute(array(
                            ":password" => $password,
                            ":id" => $newUser['id']
                        ));
                    }

                } else {
                    $retour['error'] = "wrong user";
                }
            }
        } else {
            $retour['error'] = "wrong email";
        }

        return $retour;
    }

    function updateEmail($idUser, $mail) {
        $resultats = $this->db->prepare("UPDATE Users SET email = :email  WHERE id = :id");
        $resultats->execute(array(
            ":email" => $mail,
            ":id" => $idUser
        ));
    }

    function champsEmailValable($champ) {
        if(preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $champ)) {
            return true;
        }
        else {
            return false;
        }
    }
}
?>