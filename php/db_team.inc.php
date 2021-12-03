<?php

namespace Team;
require_once('db_link.inc.php');

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

/**
 * Classe Message : message publié par quelqu'un sur le forum
 * @author CERFONTAINE Dorian
 * @version 1.0
 */
class Team
{
    private $name;
    private $title;

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
class TeamRepository
{
    const TABLE_NAME = 'collector_team';


    /**
     * Retourne tous les messages
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return [Member] liste des membres triés selon l'adresse email
     */

    public function getAllInfo(&$message)
    {
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME, PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Team\Team");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }
}