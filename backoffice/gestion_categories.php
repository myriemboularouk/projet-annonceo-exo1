<?php
require('../inc/init.inc.php');


// Attention à personnaliser pour chaque page

if($_POST){
    $resultat = $pdo -> exec("UPDATE categorie set titre = '$_POST[titre]', motscles = '$_POST[motscles]' WHERE id_categorie = '$_GET[id_categorie]'");


    header('location:' . URL . 'backoffice/gestion_categories.php');
    $msg .= '<div class="validation" > Le membre a bien été modifié </div>';
}

$resultat = $pdo -> query("SELECT * FROM categorie");

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

    while($gestion_categorie = $resultat -> fetch(PDO::FETCH_ASSOC)){
        $contenu .= '<tr>';
        foreach($gestion_categorie as $indices => $informations){
            if($indices != 'mdp'){
                $contenu .= '<td>' . $informations . '</td>';
            }
        }

    $contenu .= '<td class = "modif"><a href="?action=modification&id_categorie=' . $gestion_categorie['id_categorie']  . '"><button type="button" class="btn btn-success"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></button></a></td>';

    $contenu .= '<td class = "modif"><a href="modif_membres.php"><button type="button" class="btn btn-info"><span class= "glyphicon glyphicon-plus" aria-hidden="true"></button></a></td>';

    $contenu .= '<td class="supr"><a href="supprim_categories.php?id='. $gestion_categorie['id_categorie'] .'"><button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></a></td>';

    $contenu .= '</tr>';
    }


    $contenu .= '<table/>';
    $contenu .= '</div>';


?>

<h1>Gestion des catégories</h1>



<?php
    echo $contenu;
if(isset($_GET['action']) && $_GET['action'] == 'modification')
{
    if(isset($_GET['id_categorie']))
    {
        $resultat = $pdo->query("SELECT * FROM categorie WHERE id_categorie = '$_GET[id_categorie]'");
        $categorie_actuel = $resultat->fetch(PDO::FETCH_ASSOC);
    }

    $id_categorie = (isset($categorie_actuel)) ? $categorie_actuel['id_categorie'] : '';
    $titre = (isset($categorie_actuel)) ? $categorie_actuel['titre'] : ''; // meme finalité que le if/else du dessus mais de manière simplifiée
    $motscles = (isset($categorie_actuel)) ? $categorie_actuel['motscles'] : '';

?>



    <div class="container">
        <div class="row">
            <div class="col-md-8 order-md-0">
                <form class="form-horizontal" action="" method="post">

                        <input type="hidden" class="form-control" name="id_categorie" id ="id_categorie" value="<?= $id_categorie ?>">


                    <div class="form-group">
                        <label for="titre">Titre </label>
                        <input type="text" class="form-control" name="titre" id ="titre" value="<?= $titre ?>">
                    </div>

                    <div class="form-group">
                        <label for="motscles">Mots clés</label>
                        <input type="text" class="form-control" name="motscles" id ="motscles" value="<?= $motscles ?>">
                    </div>

            </div>
            <input type="submit" class="form-control" name=""  value="Modifier">
        </form>
    </div>


<?php
}
?>
<h2>Ajout d'une catégorie</h2>;

<div class="container">
    <div class="row">
        <div class="col-md-8 order-md-0">
            <form class="form-horizontal" action="" method="post">

                    <input type="hidden" class="form-control" name="id_categorie" id ="id_categorie" value="">


                <div class="form-group">
                    <label for="titre">Titre </label>
                    <input type="text" class="form-control" name="titre" id ="titre" value="">
                </div>

                <div class="form-group">
                    <label for="motscles">Mots clés</label>
                    <input type="text" class="form-control" name="motscles" id ="motscles" value="">
                </div>

        </div>
        <div class="col-md-5">
            <input type="submit" class="form-control btn btn-info" name=""  value="Ajouter">
        </div>
    </form>
</div>
<?php
$resultat2 = $pdo -> prepare("INSERT INTO categorie (titre, motscles) VALUES (:titre, :motscles)");

$resultat2 -> bindParam(':titre', $_POST['titre'], PDO::PARAM_STR);
$resultat2 -> bindParam(':motscles', $_POST['motscles'], PDO::PARAM_STR);

if($resultat2 -> execute()){
    echo  '<div class="alert alert-success">Catégorie ajoutée!</div>';

}
// echo $msg;
 include('../inc/footer.inc.php');
?>
