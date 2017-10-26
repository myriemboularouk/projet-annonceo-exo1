<?php
require('../inc/init.inc.php');


// Attention à personnaliser pour chaque page

if($_POST){
    $resultat = $pdo -> exec("UPDATE membre set pseudo = '$_POST[pseudo]', mdp = '$_POST[mdp]', nom = '$_POST[nom]', prenom = '$_POST[prenom]', email = '$_POST[email]', telephone = '$_POST[telephone]', civilite = '$_POST[civilite]', statut = '$_POST[statut]' WHERE id_membre = '$_GET[id_membre]'");


    header('location:' . URL . 'backoffice/gestion_membres.php');
    $msg .= '<div class="validation" > Le membre a bien été modifié </div>';
}

$resultat = $pdo -> query("SELECT * FROM membre");

include('../inc/header.inc.php');
include('../inc/nav.inc.php');

$contenu ='';
$contenu .= 'Nombre de résultats : '.$resultat -> rowCount(). '<br><hr>';

$contenu .= '<div class="container">';
$contenu .= '<table class="table table-bordered">'; // création du tableau HTML
$contenu .= '<tr>'; // création de la ligne de titre

    for($i = 0; $i < $resultat -> columnCount(); $i++){
        $colonne = $resultat -> getColumnMeta($i);
             if($colonne['name'] != 'mdp')
               {
                  $contenu .= '<th>'. $colonne['name'] . '</th>';
               }
    }
    $contenu .= '<th colspan="3">Actions</th>';
    $contenu .= '</tr>';

    while($gestion_membre = $resultat -> fetch(PDO::FETCH_ASSOC)){
        $contenu .= '<tr>';
        foreach($gestion_membre as $indices => $informations){
            if($indices != 'mdp'){
                $contenu .= '<td>' . $informations . '</td>';
            }
        }

    $contenu .= '<td class = "modif"><a href="?action=modification&id_membre=' . $gestion_membre['id_membre']  . '"><button type="button" class="btn btn-success"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></button></a></td>';

    $contenu .= '<td class = "modif"><a href="modif_membres.php"><button type="button" class="btn btn-info"><span class="glyphicon glyphicon-user" aria-hidden="true"></button></a></td>';

    $contenu .= '<td class="supr"><a href="supprim_membres.php?id='. $gestion_membre['id_membre'] .'"><button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></a></td>';

    $contenu .= '</tr>';
    }


    $contenu .= '<table/>';
    $contenu .= '</div>';


?>

<h1>Gestion des membres</h1>



<?php
    echo $contenu;
if(isset($_GET['action']) && $_GET['action'] == 'modification')
{
    if(isset($_GET['id_membre']))
    {
        $resultat = $pdo->query("SELECT * FROM membre WHERE id_membre = '$_GET[id_membre]'");
        $membre_actuel = $resultat->fetch(PDO::FETCH_ASSOC);
    }

    $id_membre = (isset($membre_actuel)) ? $membre_actuel['id_membre'] : '';
    $pseudo = (isset($membre_actuel)) ? $membre_actuel['pseudo'] : ''; // meme finalité que le if/else du dessus mais de manière simplifiée
    $mdp = (isset($membre_actuel)) ? $membre_actuel['mdp'] : '';
    $nom = (isset($membre_actuel)) ? $membre_actuel['nom'] : '';
    $prenom = (isset($membre_actuel)) ? $membre_actuel['prenom'] : '';
    $email = (isset($membre_actuel)) ? $membre_actuel['email'] : '';
    $telephone = (isset($membre_actuel)) ? $membre_actuel['telephone'] : '';
    $civilite = (isset($membre_actuel)) ? $membre_actuel['civilite'] : '';
    $statut = (isset($membre_actuel)) ? $membre_actuel['statut'] : '';



?>
<?= $msg ?>
    <div class="container">
        <div class="row">
            <div class="col-md-6 order-md-0">
                <form class="form-horizontal" action="" method="post">

                        <input type="hidden" class="form-control" name="id_membre" id ="id_membre" value="<?= $id_membre ?>">


                    <div class="form-group">
                        <label for="pseudo">Pseudo </label>
                        <input type="text" class="form-control" name="pseudo" id ="pseudo" value="<?= $pseudo ?>">
                    </div>

                    <div class="form-group">
                        <label for="mdp">Mot de passe</label>
                        <input type="text" class="form-control" name="mdp" id ="mdp" value="<?= $mdp ?>">
                    </div>

                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" class="form-control" name="nom" id ="nom" value="<?= $nom ?>">
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" class="form-control" name="prenom" id ="prenom" value="<?= $prenom ?>">
                    </div>
            </div>
            <div class="col-md-6 ">
                <div class="form-group">
                    <label for="email">Email </label>
                    <input type="text" class="form-control" name="email" id ="email" value="<?= $email ?>">
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="text" class="form-control" name="telephone" id ="telephone" value="<?= $telephone ?>">
                </div>

                <div class="form-group">
                    <label for="civilite">Civilité</label>
                    <select class="form-control" name="civilite" id="civilite">
                        <option <?= ($civilite == 'm') ? 'selected' : '' ?> value="m">Homme</option>
                        <option <?= ($civilite == 'f') ? 'selected' : '' ?>  value="f">Femme</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="statut">Statut</label>
                    <select class="form-control" name="statut" id="statut">
                        <option <?= ($statut == '0') ? 'selected' : '' ?>value="0">Membre</option>
                        <option <?= ($statut == '1') ? 'selected' : '' ?>value="1">Admin</option>
                    </select>
                </div>
            </div>
            <input type="submit" class="form-control" name=""  value="Modifier">
        </form>
    </div>
<?php
}

 include('../inc/footer.inc.php');
