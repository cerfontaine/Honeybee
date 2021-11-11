<?php

namespace Categorie;
require_once('db_link.inc.php');

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

/**
 * Classe Categorie
 * @author CERFONTAINE Dorian
 * @version 1.0
 */
class Categorie
{
    private $categorie;

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
 * Classe Categorierepository
 * @author CERFONTAINE Dorian
 * @version 1.0
 */
class CategorieRepository
{
    const TABLE_NAME = 'collector_categorie';


    public function getAllcategorie(&$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME, PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Categorie\Categorie");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function getAllInfoC($id, &$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " WHERE id_categorie ='$id' ", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Categorie\Categorie");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    function getCategorie($id, &$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT categorie FROM " . self::TABLE_NAME . " WHERE id_categorie ='$id' ");
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            if ($stmt->execute()){
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'Categorie\Categorie');
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
        return $result->categorie;
    }

    function getDeletableCategorie(&$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $result = $bdd->query("SELECT c.* FROM collector_categorie c LEFT OUTER JOIN collector_projet p ON p.id_categorie = c.id_categorie WHERE p.id_categorie IS NULL", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Categorie\Categorie");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function deleteCategorie($id, &$message)
    {
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $result = $bdd->query("DELETE FROM " . self::TABLE_NAME . " WHERE id_categorie = '$id' ", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Categorie\Categorie");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function addCategorie($categorie, &$message)
    {
        $result = false;
        $bdd    = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME. "(categorie) VALUES (:categorie)");
            $stmt->bindValue(':categorie', $categorie);
            if ($stmt->execute() && $stmt->rowCount() > 0){
                $message .= "Catégorie $categorie ajoutée ! <br>";
                $result=True;
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage().'<br>';
        }
        $bdd = null;
        return $result;
    }

    function getStatCategorie(&$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT c.categorie, COUNT(p.id_projet) AS compteur FROM collector_categorie c LEFT OUTER JOIN collector_projet p ON p.id_categorie = c.id_categorie GROUP BY categorie");
            if ($stmt->execute()){
                $result = $stmt->fetchAll();
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
}
