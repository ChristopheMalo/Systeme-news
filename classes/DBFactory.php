<?php

/**
 * Classe qui permet d'accéder aux instances PDO et MySQLi
 * Cette classe utilise le design pattern Factory
 * 
 * TP Créer un système de news - POO en PHP
 * 
 * @author      Victor Thuillier
 * @adaptation  Christophe Malo
 * @updated     09/02/2016
 * @version     3.0
 */
class DBFactory {

    public static function getMysqlConnexionWithPDO() {
        $db = new PDO('mysql:host=nom_du_host;dbname=nom_de_la_db', 'nom_log', 'mot_pass');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $db;
    }

    public static function getMysqlConnexionWithMySQLi() {
        return new MySQLi('nom_du_host', 'nom_log', 'mot_pass', 'nom_DB');
    }

}
