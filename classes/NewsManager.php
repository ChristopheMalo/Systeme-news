<?php

/**
 * Classe premettant de gérer les news
 * Cette classe doit intéragir avec la DB
 * Elle n'est pas dépendante de PDO
 * D'où la création de 2 classes spécialisées, une pour PDO, une pour MySQLi
 * Les 2 classes spécialisées vont hériter de NewsManager
 * 
 * TP Créer un système de news - POO en PHP
 * 
 * @author      Victor Thuillier
 * @adaptation  Christophe Malo
 * @updated     08/02/2016
 * @version     3.0
 */
abstract class NewsManager {

    /**
     * Méthode permettant d'ajouter une news.
     * @param $news News La news à ajouter
     * @return void
     */
    abstract protected function add(News $news);

    /**
     * Méthode renvoyant le nombre de news total.
     * @return int
     */
    abstract public function count();

    /**
     * Méthode permettant de supprimer une news.
     * @param $id int L'identifiant de la news à supprimer
     * @return void
     */
    abstract public function delete($id);

    /**
     * Méthode retournant une liste de news demandée.
     * @param $debut int La première news à sélectionner
     * @param $limite int Le nombre de news à sélectionner
     * @return array La liste des news. Chaque entrée est une instance de News.
     */
    abstract public function getList($debut = -1, $limite = -1);

    /**
     * Méthode retournant une news précise.
     * @param $id int L'identifiant de la news à récupérer
     * @return News La news demandée
     */
    abstract public function getUnique($id);

    /**
     * Méthode permettant d'enregistrer une news.
     * @param $news News la news à enregistrer
     * @see self::add()
     * @see self::modify()
     * @return void
     */
    public function save(News $news) {
        if ($news->isValid()) {
            $news->isNew() ? $this->add($news) : $this->update($news);
        } else {
            throw new RuntimeException('La news doit être valide pour être enregistrée');
        }
    }

    /**
     * Méthode permettant de modifier une news.
     * @param $news news la news à modifier
     * @return void
     */
    abstract protected function update(News $news);
}
