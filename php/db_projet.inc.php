<?php

namespace Projet;
require_once('db_link.inc.php');

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

/**
 * Classe
 * @author
 * @version
 */
class Projet
{
    private $intitule;
    private $description;
    private $date_echeance;
    private $mot_creation;
    private $montant;
    private $montant_min;
    private $illustration_apercu;
    private $carte_visa;
    private $nom_visa;
    private $est_prolonge;
    private $est_valide;
    private $taux_participation;
    private $id_membre;
    private $id_categorie;
    private $id_type_part;

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
 * Classe ProjetRepository
 * @author
 * @version
 */
class ProjetRepository
{
    const TABLE_NAME = 'collector_projet';


    /**
     * Retourne tous les messages
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return [Member] liste des membres triés selon l'adresse email
     */
    public function storeProjet($tab, &$message)
    {
        $result = false;
        $bdd    = null;
        $date_creation = date("Y-n-j");
        $est_prolonge = 0;
        $est_valide= NULL;
        $taux = 0;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME. " (intitule, description, date_echeance, date_creation, montant, montant_min, illustration_apercu, carte_visa, nom_visa, est_prolonge, est_valide, taux_participation, id_membre, id_categorie, id_type_part) VALUES (:intitule, :description, :date_echeance, :date_creation, :montant, :montant_min, :illustration, :carte_visa, :nom_visa, :est_prolonge, :est_valide, :taux_participation, :id_membre, :id_categorie, :id_type_part)");
            $stmt->bindValue(':intitule', $tab->intitule);
            $stmt->bindValue(':description', $tab->description);
            $stmt->bindValue(':date_echeance', $tab->date_echeance);
            $stmt->bindValue(':date_creation', $date_creation);
            $stmt->bindValue(':montant', $tab->montant);
            $stmt->bindValue(':montant_min', $tab->montant_min);
            $stmt->bindValue(':illustration', $tab->illustration_apercu);
            $stmt->bindValue(':carte_visa', $tab->carte_visa);
            $stmt->bindValue(':nom_visa', $tab->nom_visa);
            $stmt->bindValue(':est_prolonge', $est_prolonge);
            $stmt->bindValue(':est_valide', $est_valide);
            $stmt->bindValue(':taux_participation', $taux);
            $stmt->bindValue(':id_membre', $tab->id_membre);
            $stmt->bindValue(':id_categorie', $tab->id_categorie);
            $stmt->bindValue(':id_type_part', $tab->id_type_part);
            if ($stmt->execute() && $stmt->rowCount() > 0){
                $message .= "Projet créé avec succès !<br>";
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

    public function getAllInfoP($id, &$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " WHERE id_projet ='$id' ", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Projet\Projet");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function getAllProject(&$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " ORDER BY date_creation DESC", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Projet\Projet");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function getAllProjectFin(&$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " WHERE taux_participation>75 AND taux_participation<100", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Projet\Projet");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function getAllProjectByCat($id, &$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " WHERE id_categorie = '$id' ", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Projet\Projet");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function projectResearch($search, &$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " p JOIN collector_membre m ON m.id_membre = p.id_membre WHERE p.intitule LIKE '%$search%' or m.login LIKE '%$search%' or p.description LIKE '%$search%' ", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Projet\Projet");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    function getProjectName($id, $message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT intitule FROM " . self::TABLE_NAME . " WHERE id_projet ='$id' ");
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            if ($stmt->execute()){
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'Projet\Projet');
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
        return $result->intitule;
    }

    function getProjectMin($id, $message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT montant_min FROM " . self::TABLE_NAME . " WHERE id_projet ='$id' ");
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            if ($stmt->execute()){
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'Projet\Projet');
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
        return $result->montant_min;
    }

    public function getProjetByIdMember($id, &$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " WHERE id_membre = '$id'", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Projet\Projet");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function getProjetToValidate(&$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " WHERE taux_participation >= 100", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Projet\Projet");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    function updateTaux($id, $message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE collector_projet p SET p.taux_participation = (SELECT SUM(part.montant) FROM collector_participation part WHERE part.id_projet = '$id')/p.montant*100 WHERE p.id_projet='$id'");
            if ($stmt->execute()){
                $message .= '';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    function updateValidation($id, $val, $message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET est_valide = '$val' WHERE id_projet='$id'");
            if ($stmt->execute()){
                $message .= '';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    function updateDescription($id, $description, $message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET description=:description WHERE id_projet='$id'");
            $stmt->bindValue(':description', $description);
            if ($stmt->execute()){
                $message .= '';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    function updateEcheance($id, $date, $message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET date_echeance=:date, est_prolonge='1' WHERE id_projet='$id'");
            $stmt->bindValue(':date', $date);
            if ($stmt->execute()){
                $message .= '';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function deleteProjet($id_projet, &$message)
    {
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("DELETE FROM " . self::TABLE_NAME . " WHERE id_projet = '$id_projet' ");
            if ($stmt->execute()){
                $message .= 'Suppression réalisée avec succès';
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

    function getStatProjetFinance(&$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT COUNT(id_projet) AS counter FROM " . self::TABLE_NAME . " WHERE taux_participation >= 100");
            if ($stmt->execute()){
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'Projet\Projet');
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
        return $result->counter;
    }

    function getStatProjet(&$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT COUNT(id_projet) AS counter FROM " . self::TABLE_NAME);
            if ($stmt->execute()){
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'Projet\Projet');
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
        return $result->counter;
    }
}


