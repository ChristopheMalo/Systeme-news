<?php
require 'classes/autoload.php';

$db = DBFactory::getMysqlConnexionWithPDO();
$manager = new NewsManagerPDO($db);

if (isset($_GET['modifier'])) {
    $news = $manager->getUnique((int) $_GET['modifier']);
}

if (isset($_GET['supprimer'])) {
    $manager->delete((int) $_GET['supprimer']);
    $message = 'La news a bien été supprimée !';
}

if (isset($_POST['auteur'])) {
    $news = new News(
            [
        'auteur' => $_POST['auteur'],
        'titre' => $_POST['titre'],
        'contenu' => $_POST['contenu']
            ]
    );

    if (isset($_POST['id'])) {
        $news->setId($_POST['id']);
    }

    if ($news->isValid()) {
        $manager->save($news);

        $message = $news->isNew() ? 'La news a bien été ajoutée !' : 'La news a bien été modifiée !';
    } else {
        $erreurs = $news->getErreurs();
    }
}
?>
<!DOCTYPE html>
 <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <meta name="description" content="Déboguer le code - jQuery - basé sur le MOOC Simplifiez vos développements Javascript avec jQuery - OpenClassrooms - Adaptation Christophe Malo">
        <meta name="keywords" content="jQuery, Ajax, Javascript, HTML5, CSS3, Bootstrap">
        <meta name="author" content="Christophe Malo">
            
        <title>Administration du site de news</title>

        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css"  href="css/style.css" media="all">
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container">
            <div class="row">
                <header class="col-sm-12">
                    <h1>Interface de gestion des news</h1>
                    <p><a href="index.php">Accéder à l'accueil du site</a></p>
                </header>

                <div id="content-form" class="col-sm-12">             
                    <form action="admin.php" class="form-horizontal" method="post">
                        
                        <!-- Affichage message -->
                        <?php
                        if (isset($message)) {
                            echo '<div class="col-sm-12"><p class="alert alert-success" role="alert"><strong>' . $message . '</strong></p>';
                        }
                        ?>


                        <?php
                        // Gestion erreurs Auteur
                        if (isset($erreurs) && in_array(News::AUTEUR_INVALIDE, $erreurs)) {
                            echo 'L\'auteur est invalide.<br />';
                        }
                        ?>

                        <!-- Champ de saisie Auteur -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="auteur">Auteur :</label>
                            <div class="col-sm-10 focus">
                                <input class="form-control" type="text" name="auteur" placeholder="Prénom et nom de l'auteur" autofocus required value="<?php if (isset($news)) { echo $news->getAuteur(); } ?>">
                            </div>
                        </div>


                        <?php
                        // Gestion erreurs Titre
                        if (isset($erreurs) && in_array(News::TITRE_INVALIDE, $erreurs)) {
                            echo 'Le titre est invalide.<br />';
                        }
                        ?>

                        <!-- Champs de saisie Titre -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="titre">Titre :</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" name="titre" placeholder="Titre de la news" value="<?php if (isset($news)) { echo $news->getTitre(); } ?>">
                            </div>
                        </div>


                        <?php
                        // Gestion erreurs Contenu
                        if (isset($erreurs) && in_array(News::CONTENU_INVALIDE, $erreurs)) {
                            echo 'Le contenu est invalide.<br />';
                        }
                        ?>

                        <!-- Champs saisie Contenu -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="contenu">Contenu :</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="8" name="contenu"><?php if (isset($news)) { echo $news->getContenu(); } ?></textarea>
                            </div>
                        </div>


                        <!-- Bouton modifier ou Ajouter -->
                        <?php
                        if (isset($news) && !$news->isNew()) {
                        ?>

                            <input type="hidden" name="id" value="<?php echo $news->getId() ?>">
                            <input class="btn btn-primary pull-right" type="submit" value="Modifier" name="modifier">

                        <?php
                        } else {
                        ?>

                            <input class="btn btn-primary pull-right" type="submit" value="Ajouter">

                        <?php
                        }
                        ?>

                    </form>
                </div>

                <div class="col-sm-12">
                    <p class="alert alert-info"><strong>Il y a actuellement <?php echo $manager->count(); ?> news. En voici la liste :</strong></p>
                </div>

                <!-- Liste des news -->
                <div  class="col-sm-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>Auteur</th>
                            <th>Titre</th>
                            <th>Date d'ajout</th>
                            <th>Dernière modification</th>
                            <th>Action</th>
                        </tr>
                        <?php
                        foreach ($manager->getList() as $news)
                        {
                          echo '<tr><td>' . $news->getAuteur() . '</td><td>' . $news->getTitre() . '</td><td>' . $news->getDateAjout()->format('d/m/Y à H\hi') . '</td><td>' . ($news->getDateAjout() == $news->getDateModif() ? '-' : $news->getDateModif()->format('d/m/Y à H\hi')) . '</td><td><a href="?modifier=' . $news->getId() . '">Modifier</a> | <a href="?supprimer=' . $news->getId() . '">Supprimer</a></td></tr>';
                        }
                        ?>
                    </table>
                </div>

                <footer class="col-sm-12">
                    <p>copyright OpenClassrooms - Adaptation : Christophe Malo</p>
                </footer>
            </div>
        </div>    
    </body>
</html>