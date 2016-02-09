<?php

/**
 * Autoload -> Charge tous les classes du projet
 * TP Créer un système de news - POO en PHP
 * 
 * @author      Victor Thuillier
 * @adaptation  Christophe Malo
 * @updated     09/02/2016
 * @version     3.0
 */
function autoload($classname) {
    if (file_exists($filename = __DIR__ . '/' . $classname . '.php')) {
        require $filename;
    }
}

spl_autoload_register('autoload');