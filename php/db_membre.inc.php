<?php

namespace Membre;
require_once('db_link.inc.php');

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

/**
 * Classe Message : message publié par quelqu'un sur le forum
 * @author CERFONTAINE Dorian
 * @version 1.0
 */
class Membre
{
    private $login;
    private $prenom;
    private $nom;
    private $mot_passe;
    private $courriel;
    private $tel;
    private $adresse_rue;
    private $adresse_num;
    private $adresse_code;
    private $adresse_ville;
    private $adresse_pays;
    private $avatar;
    private $carte_VISA;
    private $est_desactive;
    private $est_admin;
    private $last_seen;
    private $login_status;

    public function __get($prop)
    {
        return $this->$prop;
    }

    public function __set($prop, $val)
    {
        $this->$prop = $val;
    }
}

/**
 * Classe MemberRepository : gestionnaire du dépôt contenant les membres de la newsletter
 * @author CERFONTAINE Dorian
 * @version 1.0
 */
class MembreRepository
{
    const TABLE_NAME = 'collector_membre';


    /**
     * Retourne tous les messages
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return [Member] liste des membres triés selon l'adresse email
     */
    public function storeMembre($member, &$message)
    {
        $result = false;
        $bdd    = null;
        $password = hash("sha512",$member->mot_passe);
        $est_desac = 0;
        $est_admin = 0;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME. "(login, prenom, nom, mot_passe, courriel, tel, adresse_rue, adresse_num, adresse_code, adresse_ville, adresse_pays, avatar, carte_VISA, est_desactive,est_admin) VALUES (:username,:prenom,:nom, :password,:courriel,:tel,:rue,:num,:code,:ville,:pays,:avatar,:visa,:desac,:admin)");
            $stmt->bindValue(':username', $member->login);
            $stmt->bindValue(':prenom', $member->prenom);
            $stmt->bindValue(':nom', $member->nom);
            $stmt->bindValue(':password', $password);
            $stmt->bindValue(':courriel', $member->courriel);
            $stmt->bindValue(':tel', $member->tel);
            $stmt->bindValue(':rue', $member->adresse_rue);
            $stmt->bindValue(':num', $member->adresse_num);
            $stmt->bindValue(':code', $member->adresse_code);
            $stmt->bindValue(':ville', $member->adresse_ville);
            $stmt->bindValue(':pays', $member->adresse_pays);
            $stmt->bindValue(':avatar', $member->avatar);
            $stmt->bindValue(':visa', $member->carte_VISA);
            $stmt->bindValue(':admin', $est_admin);
            $stmt->bindValue(':desac', $est_desac);
            if ($stmt->execute() && $stmt->rowCount() > 0){
                $message .= "Utilisateur $member->login ajouté<br>";
                $result=True;
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage().'<br>';
        }
        $bdd = null;
        return $result;
    }
    public function checklogin($username, $password, &$message)
    {
        $result = false;
        $bdd    = null;
        $password = hash("sha512",$password);
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE login = :username AND mot_passe = :password AND est_desactive = 0");
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':password', $password);
            if ($stmt->execute()){
                if($stmt->fetch() !== false){
                    $result = true;
                } else {
                    $message .= 'Nom d\'utilisateur ou mot de passe incorrect<br>';
                }
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage().'<br>';
        }
        $bdd = null;
        return $result;

    }

    public function getAllInfo($username, &$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " WHERE login ='$username' ", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Membre\Membre");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    function existsInDb($val, &$message)
    {
        $result = false;
        $bdd    = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE login = :username");
            $stmt->bindValue(':username', $val);
            if ($stmt->execute()){
                if($stmt->fetch() !== false){
                    $result = false;
                    $message .= 'Le login '. $val . ' existe déjà ! ';
                }
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage().'<br>';
        }
        $bdd = null;
        return (empty($message)) ? True : False;
    }

    public function getSMTG($condition, $valeurcondition, &$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE $condition = '$valeurcondition' ");
            if ($stmt->execute()){
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'Membre\Membre');
                $result = $stmt->fetch();
            } else {
                $message .= 'Une erreur système est survenue.<br> 
                    Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. 
                    (Code erreur E: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    function getUsername($id, $message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT login FROM " . self::TABLE_NAME . " WHERE id_membre = :id_membre");
            $stmt->bindValue(':id_membre', $id);
            if ($stmt->execute()){
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'Membre\Membre');
                $result = $stmt->fetch();
            } else {
                $message .= 'Une erreur système est survenue.<br> 
                    Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. 
                    (Code erreur E: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result->login;
    }

    function getAvatar($id, $message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT avatar FROM " . self::TABLE_NAME . " WHERE id_membre = :id_membre");
            $stmt->bindValue(':id_membre', $id);
            if ($stmt->execute()){
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'Membre\Membre');
                $result = $stmt->fetch();
            } else {
                $message .= 'Une erreur système est survenue.<br> 
                    Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. 
                    (Code erreur E: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result->avatar;
    }

    function updateLogin($id, $login, $message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET login='$login' WHERE id_membre ='$id'");
            if ($stmt->execute()){
                $message .= "Changement effectué avec succès";
            } else {
                $message .= 'Une erreur système est survenue.<br> 
                    Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. 
                    (Code erreur E: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    function updatePassword($passwordNonSha, $id, $message){
        $result = false;
        $bdd    = null;
        $password = hash("sha512",$passwordNonSha);
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET mot_passe=:password WHERE id_membre='$id'");
            $stmt->bindValue(':password', $password);
            if ($stmt->execute()){
                $message .= "Mot de passe changé<br>";
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage().'<br>';
        }
        $bdd = null;
        return $result;
    }

    public function memberResearch($search, &$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " WHERE login LIKE '%$search%'", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Membre\Membre");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    function updateLocalisation($tab, $id, &$message){
        $result = false;
        $bdd    = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET adresse_rue=:adresse_rue, adresse_num=:adresse_num, adresse_code=:adresse_code, adresse_ville=:adresse_ville, adresse_pays=:adresse_pays  WHERE id_membre='$id'");
            $stmt->bindValue(':adresse_rue', $tab->adresse_rue);
            $stmt->bindValue(':adresse_num', $tab->adresse_num);
            $stmt->bindValue(':adresse_code', $tab->adresse_code);
            $stmt->bindValue(':adresse_ville', $tab->adresse_ville);
            $stmt->bindValue(':adresse_pays', $tab->adresse_pays);
            if ($stmt->execute()){
                $message .= "Données changées<br>";
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage().'<br>';
        }
        $bdd = null;
        return $result;
    }

    function updatePerso($tab, $id, &$message){
        $result = false;
        $bdd    = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET prenom=:prenom, nom=:nom, tel=:tel, courriel=:courriel WHERE id_membre='$id'");
            $stmt->bindValue(':prenom', $tab->prenom);
            $stmt->bindValue(':nom', $tab->nom);
            $stmt->bindValue(':tel', $tab->tel);
            $stmt->bindValue(':courriel', $tab->courriel);
            if ($stmt->execute()){
                $message .= "Données changées<br>";
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage().'<br>';
        }
        $bdd = null;
        return $result;
    }

    function updateVisa($visa, $id, &$message){
        $result = false;
        $bdd    = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET carte_VISA=:visa WHERE id_membre='$id'");
            $stmt->bindValue(':visa', $visa);
            if ($stmt->execute()){
                $message .= "Données changées<br>";
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage().'<br>';
        }
        $bdd = null;
        return $result;
    }

    function updateAvatar($photo, $id, &$message){
        $result = false;
        $bdd    = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET avatar=:avatar WHERE id_membre='$id'");
            $stmt->bindValue(':avatar', $photo);
            if ($stmt->execute()){
                $message .= "Données changées<br>";
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage().'<br>';
        }
        $bdd = null;
        return $result;
    }

    function updateDesactive($id, &$message){
        $result = false;
        $bdd    = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET est_desactive=1 WHERE id_membre='$id'");
            if ($stmt->execute()){
                $message .= "Données changées<br>";
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage().'<br>';
        }
        $bdd = null;
        return $result;
    }

    public function deleteProfile($id, &$message)
    {
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $result = $bdd->query("DELETE FROM " . self::TABLE_NAME . " WHERE id_membre = '$id' ", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Membre\Membre");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    function checkForDelete($id, &$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT COUNT(id_projet) AS compteur FROM collector_projet WHERE id_membre= :id_membre");
            $stmt->bindValue(':id_membre', $id);
            if ($stmt->execute()){
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'Membre\Membre');
                $result = $stmt->fetch();
            } else {
                $message .= 'Une erreur système est survenue.<br> 
                    Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. 
                    (Code erreur E: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result->compteur;
    }

    function checkForDelete2($id, &$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT COUNT(id_participation) AS compteur FROM collector_participation WHERE id_membre= :id_membre");
            $stmt->bindValue(':id_membre', $id);
            if ($stmt->execute()){
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'Membre\Membre');
                $result = $stmt->fetch();
            } else {
                $message .= 'Une erreur système est survenue.<br> 
                    Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. 
                    (Code erreur E: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result->compteur;
    }

    function updateLastSeen($id, &$message){
        $result = null;
        $bdd = null;
        $now = date("Y-m-d H:i:s");
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET last_seen='$now' WHERE id_membre = :id_membre");
            $stmt->bindValue(':id_membre', $id);
            if ($stmt->execute()){
                $message .= "Changement effectué avec succès";
            } else {
                $message .= 'Une erreur système est survenue.<br> 
                    Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. 
                    (Code erreur E: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }
    function updateLoginStatus($id, $value, &$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET login_status=:login_status WHERE id_membre = :id_membre");
            $stmt->bindValue(':id_membre', $id);
            $stmt->bindValue(':login_status', $value);
            if ($stmt->execute()){
                $message .= "Changement effectué avec succès";
            } else {
                $message .= 'Une erreur système est survenue.<br> 
                    Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. 
                    (Code erreur E: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }
    public function updateAccountStatus($id, $value, &$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET est_desactive = :est_desactive WHERE id_membre = :id_membre");
            $stmt->bindValue(':id_membre', $id);
            $stmt->bindValue(':est_desactive', $value);

            if ($stmt->execute()){
                if($value == 0){
                    $message .= "Account enabled.";
                }else{
                    $message .= "Account disabled.";
                }

            } else {
                $message .= 'Une erreur système est survenue.<br> 
                    Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. 
                    (Code erreur E: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }
    public function getAllCurrentLog(&$message)
    {
        $result = array();
        $bdd = null;
        $now = date("Y-m-d H:i:s");
        $currentDate = strtotime($now);
        $futureDate = $currentDate-(60*10);
        $formatDate = date("Y-m-d H:i:s", $futureDate);
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " WHERE last_seen > '$formatDate' AND login_status = 1", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Membre\Membre");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }
    public function getAllUsers(&$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME, PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Membre\Membre");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }
}


