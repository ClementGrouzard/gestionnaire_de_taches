<?php
$tacheAjoutee = false;
// ajout d'une tache a la list
// si je reçoit une tache (dans un formulaire)
if (!empty($_POST['tache'])) {
    $tache = $_POST['tache'];
    $tacheAjoutee = true;
    // j'ajoute la tache reçue dans le fichier 
    $file = fopen('data/taches.txt', 'a');
    // j'ajoute un saut de ligne pour avoir une tache par ligne
    fwrite($file, $tache . "\n");
    fclose($file);
}

?>

<!DOCTYPE html>

<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todolist collective</title>
    <link rel="stylesheet" href="marx.css">

    <style>
        h1 {
            margin: 0;
        }

        header {
            background-color: #e8ecef;
            width: 60%;
            height: auto;
            padding: 50px 20px;
        }

        main {
            max-width: 60%;
            padding: 0;
        }

        #taches {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;

        }

        .tache {
            border: solid 1px #00000040;
            border-radius: 3px;
            padding: 15px;
        }

        input.yann {
            background-color: #fec004;
            color: black;
        }

        input.yann:hover {
            background-color: #f44129;
            color: white;
            border: none;
        }

        #addTask {
            margin-top: 20px;
        }

        #ajoutTache {
            background-color: #d4edda;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            color: #51806c;
            font-size: 1.5em;
        }

        #suppTache {
            background-color: #f8d7da;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            color: #721c24;
            font-size: 1.5em;
        }
    </style>
</head>

<body>
    <header>
        <h1>Todolist tous ensemble</h1>
        <p>Un petit gestionnaire de taches, simple et sans base de donnée</p>
    </header>
    <main>
        <form method="post">
            <label for="input_tache">Ajouter une tâche :</label>
            <input id="input_tache" type="text" name="tache">
            <input type="submit" value="Ajouter la tâche" id="addTask">
        </form>



        <?php


        if ($tacheAjoutee) {
            echo "<div id='ajoutTache'>Tache ajoutée</div>";
        }

        touch('data/taches.txt');
        //affichage des taches
        if (filesize('data/taches.txt') > 0) {
            $file = fopen('data/taches.txt', 'r');
            $affichage = fread($file, filesize('data/taches.txt'));
            // permet de separer des taches à chaque saut de ligne
            $taches = explode("\n", $affichage);
            fclose($file);
            // si on reçoit une demande de suppression 
            // (un formulaire avec un id de tache à supprimer)
            // je supprime la tache dans mon tableau $taches
            if (isset($_POST["supprimer"])) {
                echo "<div id='suppTache'>Tache supprimee : " . $taches[$_POST["supprimer"]] . "</div>";
                array_splice($taches, $_POST['supprimer'], 1);
                $files = fopen("data/taches.txt", "w");
                // on réécrit toutes les tache (sans la tache supprimée) dans le fichier
                fwrite($files, implode("\n", $taches));
                fclose($files);
            }
            array_pop($taches); // supprime la dernière ligne (vide)
            $compteur = sizeof($taches);
            echo "<h2>Liste des taches ($compteur) </h2>";

            echo "<section id='taches'>";
            // affiche les cartes pour chaque tache dans la liste
            foreach ($taches as $id => $tache) {

                // on filtre le contenu des tache pour eviter les injections html (on peut aussi utiliser htmlspecialchars)
                $tacheFiltree = htmlentities($tache);

                echo "<div class='tache'><h3>$tacheFiltree</h3>";
                echo "<form method='post'>
                <input class='yann' type='submit' value='Supprimer la tache'>
                <input type='hidden' value='$id' name='supprimer'>
                </form>"; // formulaire de suppression avec l'id de la tache
                echo "</div>";
            }
            echo "</section>";
        } else {
            echo "<h2>Liste des taches (0) </h2>";
        }
        ?>



    </main>
    <footer>

    </footer>
    <script>
        let supp = document.getElementById("suppTache");
        let ajout = document.getElementById("ajoutTache");
        setTimeout(function() {
            if (supp) {
                supp.style.display = "none";
            }
            if (ajout) {
                ajout.style.display = "none";
            }
        }, 5000)
    </script>
</body>

</html>