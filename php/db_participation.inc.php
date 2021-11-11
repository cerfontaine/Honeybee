<?php

namespace Participation ;
require_once('db_link.inc.php');

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');


class Participation
{
    private $date_participation;
    private $montant;
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


class ParticipationRepository
{
    const TABLE_NAME = 'collector_participation';

    public function storeParticipation($tab, &$message)
    {
        $result = false;
        $bdd    = null;
        $date_participation = date("Y-n-j");
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME. " (date_participation, montant, id_membre, id_projet) VALUES (:date_participation, :montant, :id_membre, :id_projet)");
            $stmt->bindValue(':date_participation', $date_participation);
            $stmt->bindValue(':montant', $tab->montant);
            $stmt->bindValue(':id_membre', $tab->id_membre);
            $stmt->bindValue(':id_projet', $tab->id_projet);
            if ($stmt->execute() && $stmt->rowCount() > 0){
                $message .= "Participation enregistrée avec succès !<br>";
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

    public function getAllParticipationForP($id, &$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT SUM(montant) AS total FROM " . self::TABLE_NAME . " WHERE id_projet ='$id' ");
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            if ($stmt->execute()){
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'Participation\Participation');
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
        return $result->total;
    }


    public function getAllParticipantForP($id, &$message)
    {
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT COUNT(id_membre) AS counter FROM " . self::TABLE_NAME . " WHERE id_projet ='$id' ");
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            if ($stmt->execute()){
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'Participation\Participation');
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

    public function siParticipation($id_projet, $id_membre, &$message)
    {
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT COUNT(*) AS counter FROM " . self::TABLE_NAME . " WHERE id_projet ='$id_projet' AND id_membre = '$id_membre' ");
            if ($stmt->execute()){
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'Participation\Participation');
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

    public function getIdParticipation($id_projet, $id_membre, &$message)
    {
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT id_participation FROM " . self::TABLE_NAME . " WHERE id_projet ='$id_projet' AND id_membre = '$id_membre' ");
            if ($stmt->execute()){
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'Participation\Participation');
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
        return $result->id_participation;
    }

    public function deleteParticipation($id, &$message)
    {
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("DELETE FROM " . self::TABLE_NAME . " WHERE id_participation = '$id' ");
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

    public function getParticipationForMember($id, &$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " WHERE id_membre='$id' ORDER BY date_participation DESC", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Participation\Participation");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function deleteParticipationByP($id_projet, &$message)
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

    function getStatParticipation(&$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT SUM(montant) AS counter FROM " . self::TABLE_NAME);
            if ($stmt->execute()){
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'Participation\Participation');
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





