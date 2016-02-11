<?php
require 'classes/autoload.php';

$db = DBFactory::getMysqlConnexionWithPDO();
$manager = new NewsManagerPDO($db);
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

        <title>Accueil du site de news</title>

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
                <p><a href="admin.php">Accéder à l'espace d'administration</a></p>

                <?php
                if (isset($_GET['id'])) {
                    $news = $manager->getUnique((int) $_GET['id']);

                    echo '<p>Par <em>', $news->getAuteur(), '</em>, le ', $news->getDateAjout()->format('d/m/Y à H\hi'), '</p>', "\n",
                    '<h2>', $news->getTitre(), '</h2>', "\n",
                    '<p>', nl2br($news->getContenu()), '</p>', "\n";

                    if ($news->getDateAjout() != $news->getDateModif()) {
                        echo '<p style="text-align: right;"><small><em>Modifiée le ', $news->getDateModif()->format('d/m/Y à H\hi'), '</em></small></p>';
                    }
                    
                } else {
                    
                    echo '<h1 style="text-align:center">Liste des 5 dernières news</h1>';

                    foreach ($manager->getList(0, 5) as $news) {
                        if (strlen($news->getContenu()) <= 200) {
                            $contenu = $news->getContenu();
                        } else {
                            $debut = substr($news->getContenu(), 0, 200);
                            $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

                            $contenu = $debut;
                        }

                        echo '<h4><a href="?id=', $news->getId(), '">', $news->getTitre(), '</a></h4>', "\n",
                        '<p>', nl2br($contenu), '</p>';
                    }
                }
                ?>
            </div>
        </div>
    </body>
</html>