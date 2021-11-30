<?php

namespace Quote;
require_once('db_link.inc.php');

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

/**
 * Classe Message : message publié par quelqu'un sur le forum
 * @author CERFONTAINE Dorian
 * @version 1.0
 */
class Quote
{
    private $quote;

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
class QuoteRepository
{
    const TABLE_NAME = 'collector_quote';


    /**
     * Retourne tous les messages
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return [Member] liste des membres triés selon l'adresse email
     */

    public function getARandomQuote(&$message)
    {
        $result = array();
        $random = rand(1, 45);
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            //version "objet", l'appel au constructeur de la classe peut être forcé avant d'affecter les propriétés en spécifiant les styles PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE.
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " WHERE id_quote ='$random' ", PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Quote\Quote");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }
}