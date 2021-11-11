<?php

namespace Type;
require_once('db_link.inc.php');

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

/**
 * Classe Message : message publié par quelqu'un sur le forum
 * @author CERFONTAINE Dorian
 * @version 1.0
 */
class Type
{
    private $libelle;

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
 * Classe Type
 * @author CERFONTAINE Dorian
 * @version 1.0
 */
class TypeRepository
{
    const TABLE_NAME = 'collector_type_participation';


    public function getAllInfo(&$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME , PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Type\Type");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

}


