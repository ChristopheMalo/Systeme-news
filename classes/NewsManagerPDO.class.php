<?php

/**
 * Classe spécialisée pour se connecter à la DB en PDO
 * Cette classe hérite de NewsMAnager
 * 
 * TP Créer un système de news - POO en PHP
 * 
 * @author      Victor Thuillier
 * @adaptation  Christophe Malo
 * @updated     08/02/2016
 * @version     3.0
 */
class NewsManagerPDO extends NewsManager {

    /**
     * Attribut contenant l'instance représentant la BDD.
     * @type PDO
     */
    protected $db;

    /**
     * Constructeur étant chargé d'enregistrer l'instance de PDO dans l'attribut $db.
     * @param $db PDO Le DAO
     * @return void
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * @see NewsManager::add()
     */
    protected function add(News $news) {
        $requete = $this->db->prepare('INSERT INTO news SET auteur = :auteur, titre = :titre, contenu = :contenu, dateAjout = NOW(), dateModif = NOW()');

        $requete->bindValue(':titre', $news->getTitre());
        $requete->bindValue(':auteur', $news->getAuteur());
        $requete->bindValue(':contenu', $news->getContenu());

        $requete->execute();
    }

    /**
     * @see NewsManager::count()
     */
    public function count() {
        return $this->db->query('SELECT COUNT(*) FROM news')->fetchColumn();
    }

    /**
     * @see NewsManager::delete()
     */
    public function delete($id) {
        $this->db->exec('DELETE FROM news WHERE id = ' . (int) $id);
    }

    /**
     * @see NewsManager::getList()
     */
    public function getList($debut = -1, $limite = -1) {
        $sql = 'SELECT id, auteur, titre, contenu, dateAjout, dateModif FROM news ORDER BY id DESC';

        // On vérifie l'intégrité des paramètres fournis.
        if ($debut != -1 || $limite != -1) {
            $sql .= ' LIMIT ' . (int) $limite . ' OFFSET ' . (int) $debut;
        }

        $requete = $this->db->query($sql);
        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'News');

        $listeNews = $requete->fetchAll();

        // On parcourt notre liste de news pour pouvoir placer des instances de DateTime en guise de dates d'ajout et de modification.
        foreach ($listeNews as $news) {
            $news->setDateAjout(new DateTime($news->getDateAjout()));
            $news->setDateModif(new DateTime($news->getDateModif()));
        }

        $requete->closeCursor();

        return $listeNews;
    }

    /**
     * @see NewsManager::getUnique()
     */
    public function getUnique($id) {
        $requete = $this->db->prepare('SELECT id, auteur, titre, contenu, dateAjout, dateModif FROM news WHERE id = :id');
        $requete->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'News');

        $news = $requete->fetch();

        $news->setDateAjout(new DateTime($news->getDateAjout()));
        $news->setDateModif(new DateTime($news->getDateModif()));

        return $news;
    }

    /**
     * @see NewsManager::update()
     */
    protected function update(News $news) {
        $requete = $this->db->prepare('UPDATE news SET auteur = :auteur, titre = :titre, contenu = :contenu, dateModif = NOW() WHERE id = :id');

        $requete->bindValue(':titre', $news->getTitre());
        $requete->bindValue(':auteur', $news->getAuteur());
        $requete->bindValue(':contenu', $news->getContenu());
        $requete->bindValue(':id', $news->getId(), PDO::PARAM_INT);

        $requete->execute();
    }

}
