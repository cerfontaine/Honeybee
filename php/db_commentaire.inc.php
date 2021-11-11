<?php

namespace Commentaire;
require_once('db_link.inc.php');

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');


class Commentaire
{
    private $commentaire;
    private $date_modification;
    private $est_supprime;
    private $id_projet;
    private $id_membre;


    public function __get($prop)
    {
        return $this->$prop;
    }

    public function __set($prop, $val)
    {
        $this->$prop = $val;
    }
}


class CommentaireRepository
{
    const TABLE_NAME = 'collector_commentaire';

    public function storeComment($tab, &$message)
    {
        $result = false;
        $bdd    = null;
        $date_creation = date("Y-n-j");
        $est_supprime = 0 ;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME. " (commentaire, date_modification, est_supprime, id_projet, id_membre) VALUES (:commentaire, :date_creation, :est_supprime, :id_projet, :id_membre)");
            $stmt->bindValue(':commentaire', $tab->commentaire);
            $stmt->bindValue(':date_creation', $date_creation);
            $stmt->bindValue(':est_supprime', $est_supprime);
            $stmt->bindValue(':id_projet', $tab->id_projet);
            $stmt->bindValue(':id_membre', $tab->id_membre);
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

    public function getAllComment($id, &$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME , PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Commentaire\Commentaire");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function getAllCommentP($id, &$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " WHERE id_projet = '$id' AND est_supprime = 0", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Commentaire\Commentaire");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function deleteComment($id, &$message)
    {
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("UPDATE " . self::TABLE_NAME . " SET est_supprime = '1' WHERE id_comment = '$id' ", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Commentaire\Commentaire");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function deleteComByP($id_projet, &$message)
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

}




