<?php
/**
 * Created by PhpStorm.
 * User: Flavian Ovyn
 * Date: 28/09/2015
 * Time: 11:14
 */
namespace Entity;

use DateTime;
/**
 * Class User
 * Entité de la base de donnée définissant un utilisateur de l'application et du site.
 */
class User{
    private $id;
    private $UserName;
    private $Mdp;
    private $tel;
    private $email;
    private $DateInscription;
    private $DateLastIdea;
    private $DateLastConnect;
    private $isPrivate;
    private $droit = array();
    private $salt;

    /**
     * Fonction permettant l'hydratation de la classe.
     * @param array $tab est un tableau associatif selon les attributs a assigner.
     */
    private function __hydrate(array $tab)
    {
        foreach($tab as $key => $value)
        {
            if(property_exists($this,$key))$this->$key = $value;
        }
    }
    public function __construct(array $user)
    {
        $this->__hydrate($user);
    }

    /**GETTER**/
    public function getId()
    {
        return $this->id;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getUserName()
    {
        return $this->UserName;
    }
    public function getMdp()
    {
        return $this->Mdp;
    }
    public function getDateInscription()
    {
        return $this->DateInscription;
    }
    public function getDateLastIdea()
    {
        return $this->DateLastIdea;
    }
    public function getDateLastConnect()
    {
        return $this->DateLastConnect;
    }
    public function getDroit()
    {
        return $this->droit;
    }
    public function getTel()
    {
        return $this->tel;
    }
    public function getIsPrivate()
    {
        return $this->isPrivate;
    }
    public function getSalt() {
        return $this->salt;
    }

    /**SETTER**/
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setUserName($UserName)
    {
        $this->UserName = $UserName;
    }
    public function setMdp($Mdp)
    {
        $this->Mdp = $Mdp;
    }
    public function setDateInscription($DateInscription)
    {
        $this->DateInscription = $DateInscription;
    }
    public function setDateLastIdea($DateLastIdea)
    {
        $this->DateLastIdea = $DateLastIdea;
    }
    public function setDateLastConnect($DateLastConnect)
    {
        $this->DateLastConnect = $DateLastConnect;
    }

    public function setDroit(array $droit)
    {
        $this->droit = $droit;
    }
    public function setTel($tel)
    {
        $this->tel = $tel;
    }
    public function setIsPrivate($isPrivate)
    {
        $this->isPrivate = $isPrivate;
    }
    public function setSalt($salt) {
        $this->salt = $salt;
    }

    /**
     * Fonction permettant le hashage du mots de passe.
     * @use inscription
     * @use profil
     */
    public function setHashMdp()
    {
        $this->setMdp(hash("sha256", $this->getMdp().$this->salt));
    }

    public function toStringList()
    {
        return "<tr><td>".$this->getUserName()."</td><td>". $this->getTel()."</td></tr>";
    }
    public function toStringArray()
    {
        $array = array();
        $array["User_name"] = $this->getUserName();
        $array["Derniere_connexion"] = new DateTime($this->getDateLastConnect());
        return $array;
    }
}