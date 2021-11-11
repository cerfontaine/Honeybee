<?php

namespace News;
require_once('db_link.inc.php');

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

/**
 * Classe
 * @author
 * @version
 */
class News
{
    private $intitule;
    private $description;
    private $url;
    private $date_publication;
    private $id_projet;


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
 * Classe SeuilRepository
 * @author
 * @version
 */
class NewsRepository
{
    const TABLE_NAME = 'collector_news';


    public function storeNews($tab, &$message)
    {
        $result = false;
        $bdd    = null;
        $date_creation = date("Y-n-j");
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME. " (intitule,description, url,date_publication,id_projet) VALUES (:intitule, :description,:url, :date_creation, :id_projet)");
            $stmt->bindValue(':intitule', $tab->intitule);
            $stmt->bindValue(':description', $tab->description);
            $stmt->bindValue(':url', $tab->url);
            $stmt->bindValue(':date_creation', $date_creation);
            $stmt->bindValue(':id_projet', $tab->id_projet);
            if ($stmt->execute() && $stmt->rowCount() > 0){
                $message .= "News ajoutée<br>";
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

    public function getAllNewsForP($id, &$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " WHERE id_projet ='$id' ", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "News\News");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function deleteNews($id, &$message)
    {
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $result = $bdd->query("DELETE FROM " . self::TABLE_NAME . " WHERE id_news = '$id' ", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "News\News");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function deleteNewsByP($id_projet, &$message)
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
?>
