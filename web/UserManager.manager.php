<?php
/**
 * Created by PhpStorm.
 * User: Flavian Ovyn
 * Date: 28/09/2015
 * Time: 11:21
 */

use \Entity\User as User;

class UserManager {
    private $db;
    /**
     * Fonction générant un manager en fonction de la BDD.
     * @param PDO $database : la base de données.
     */
    public function __construct(PDO $database)
    {
        $this->db = $database;
    }

    /**
     * Méthode permettant la récupération de tous les utilisateurs
     * @return array $tab contentant tous les users
     */
    public function getAllUser() {
        $resultats = $this->db->query("SELECT * FROM user");
        $resultats->execute();

        $tabUser = $resultats->fetchAll(PDO::FETCH_ASSOC);

        $tab = array();

        foreach($tabUser as $elem)
        {
            $user = new User($elem);
            $tabDroit = $this->getUserDroit($user);
            $user->setDroit($tabDroit);
            $tab[] = $user;

        }

        return $tab;

    }

    /**
     * Fonction permettant de ramener tous les utilisateurs dont le nom contient le string donné.
     * @param $name : le string devant être contenu dans le nom d'utilisateur.
     * @return array : tableau contenant les utilisateurs trouvés.
     */
    public function searchAllUserByName($name) {

        $resultats = $this->db->prepare("SELECT * FROM user WHERE UserName like :userName");
        $resultats->execute(array(
            ":userName" => "%".$name."%"
        ));

        $tabUser = $resultats->fetchAll(PDO::FETCH_ASSOC);

        $tab = array();

        foreach($tabUser as $elem)
        {
            $tab[] = new User($elem);
        }

        return $tab;
    }

    /**
     * Fonction permettant de récupérer un utilisateur en fonction de son id.
     * @param $id : l'id du membre à retrouver.
     * @return User : l'utilisateur trouvé via l'id.
     */
    public function getUserById($id)
    {
        $query = $this->db->prepare("SELECT * FROM user WHERE id = :id");
        $query->execute(array(
            ":id" => $id
        ));

        if ($tabUser = $query->fetch(PDO::FETCH_ASSOC)) {
            $userToConnect = new User($tabUser);
            $tabDroit = $this->getUserDroit($userToConnect);
            $userToConnect->setDroit($tabDroit);
        } else {
            $userToConnect = new User(array());
        }



        return $userToConnect;
    }

    /**
     * Fonction permettant de retrouver un user en fonction de son nom.
     * @param $userName : le nom de l'utilisateur.
     * @return User : la classe utilisateur trouvée.
     */
    public function getUserByUserName($userName)
    {
        $query = $this->db->prepare("SELECT * FROM user WHERE UserName = :userName");
        $query->execute(array(
            ":userName" => $userName
        ));

        if($tabUser = $query->fetch(PDO::FETCH_ASSOC))
        {
            $userToConnect = new User($tabUser);
            $tabDroit = $this->getUserDroit($userToConnect);
            $userToConnect->setDroit($tabDroit);
        }
        else
        {
            $userToConnect = new User(array());
        }
        return $userToConnect;
    }

    /**
     * Fonction permettant de retrouver un user en fonction de son email.
     * @param $email : l'email de l'utilisateur.
     * @return User : la classe user trouvée.
     */
    public function getUserByEmail($email)
    {
        $query = $this
            ->db
            ->prepare("SELECT * FROM user WHERE email = :email");
        $query->execute(array(
            ":email" => $email
        ));

        if($tabUser = $query->fetch(PDO::FETCH_ASSOC))
        {
            $userToConnect = new User($tabUser);
            $tabDroit = $this->getUserDroit($userToConnect);
            $userToConnect->setDroit($tabDroit);
        }
        else
        {
            $userToConnect = new User(array());
        }

        return $userToConnect;
    }

    /**
     * Fonction permettant d'aller retrouver les droits d'un membre.
     * @param User $user : le membre concerné.
     * @return array : le tableau des droits de l'utilisateur.
     */
    public function getUserDroit(User $user)
    {
        $dm = new DroitManager(connexionDb());
        $query = $this->db->prepare("SELECT * FROM user_droit WHERE id_User = :idUser");
        $query->execute(array(
            ":idUser" => $user->getId()
        ));

        $tabDroit = $query->fetchAll(PDO::FETCH_ASSOC);

        $tab = array();
        foreach($tabDroit as $elem)
        {
            $droitUser = $dm->getDroitById($elem['id_Droits']);
            $tab[] = $droitUser;

        }
        return $tab;
    }

    /**
     * Fonction permettant de mettre à jour les droits d'un utilisateur.
     * @param User $user : l'utilisateur concerné.
     * @param $droits : l'id du droit modifié.
     */
    public function setUserDroit(User $user, $droits)
    {
        $query = $this->db->prepare("INSERT INTO user_droit(id_Droits, id_User, Date) values (:idDroits, :idUser, NOW())");
        $query->execute(array(
            ":idUser" => $user->getId(),
            ":idDroits" => $droits
        ));
    }

    /**
     * Fonction permettant d'ajouter un utilisateur à la BDD.
     * @param User $user : l'utilisateur à ajouter.
     */
    public function addUser(User $user)
    {
        $salt = uniqid(mt_rand(), true);
        $query = $this
            ->db
            ->prepare("INSERT INTO user(UserName, Mdp, DateInscription, DateLastConnect, email, salt) VALUES (:username , :mdp , NOW(),NOW(), :email, :salt)");

        $user-> setMdp(hash("sha256", $user->getMdp().$salt));
        $query->execute(array(
            ":username" => $user->getUserName(),
            ":mdp" => $user->getMdp(),
            ":email" => $user->getEmail(),
            ":salt" => $salt,
        ));
    }

    /**
     * Fonction permettant de mettre à jour les données d'un utilisateur.
     * @param User $user : la classe modifiée de l'utilisateur.
     */
    public function updateUserProfil(User $user)
    {

            $query = $this
                ->db
                ->prepare("UPDATE user SET UserName = :username , Mdp = :mdp , email = :email, tel = :tel WHERE id = :id");

            $query
                ->execute(array(
                    ":id" => $user->getId(),
                    ":username" => $user->getUserName(),
                    ":email" => $user->getEmail(),
                    ":mdp" => $user->getMdp(),
                    ":tel" => $user->getTel(),
                ));


    }

    /**
     * Fonction permettant de mettre à jour la date de dernière connexion de l'utilisateur.
     * @param User $user : l'utilisateur concerné.
     */
    public function updateUserConnect(User $user)
    {
        $query = $this
            ->db
            ->prepare("UPDATE user SET DateLastConnect = NOW() WHERE id = :id");

        $query
            ->execute(array(
                ":id" => $user->getId(),
            ));

    }

    /**
     * fonction permettant de mettre à jour la date de dernière activité proposée d'un membre.
     * @param User $user : l'utilisateur concerné.
     */
    public function updateUserLastIdea(User $user)
    {
        $query = $this
            ->db
            ->prepare("UPDATE user SET DateLastIdea = NOW() WHERE id = :id");

        $query
            ->execute(array(
                ":id" => $user->getId(),
            ));

    }

    /**
     * Fonction permettant de mettre à jour le mot de passe d'un utilisateur.
     * @param User $user : l'utilisateur modifié.
     */
    public function updateUserMdp (User $user) {

        $query = $this
            -> db
            ->prepare("UPDATE user SET Mdp = :mdp where id = :id");
        $user->setMdp(hash("sha256", $user->getMdp().$user->getSalt()));
        $query
            ->execute(array(
                ":id" => $user->getId(),
                ":mdp" => $user->getMdp(),
            ));
    }

}