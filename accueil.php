<?php
require('inc/init.inc.php');


// Attention à personnaliser pour chaque page

$resultat = $pdo -> query("SELECT ville FROM annonce");
$annonce_ville = $resultat -> fetchAll(PDO::FETCH_ASSOC);

$resultat = $pdo -> query("SELECT DISTINCT prenom, nom FROM membre");
$annonce_membre = $resultat -> fetchAll(PDO::FETCH_ASSOC);

$resultat = $pdo -> query("SELECT titre FROM categorie");
$annonce_categorie = $resultat -> fetchAll(PDO::FETCH_ASSOC);

$resultat = $pdo -> query("SELECT * FROM annonce");
$affiche_annonce = $resultat -> fetchAll(PDO::FETCH_ASSOC);


include('inc/header.inc.php');
include('inc/nav.inc.php');
?>


        <!-- Contenu de la page -->


<h1>Accueil</h1>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form type="#" method="post">
                        <div class="form-group">
                            <label for="categorie">Catégorie</label>
                            <select class="form-control" id="categorie" name="categorie">
                                <?php foreach ($annonce_categorie as $value): ?>
                                     <?= '<option>'. $value['titre'] .'</option>'?>
                                <?php endforeach; ?>
                            </select>
                            <label for="ville">Ville</label>
                            <select class="form-control" id="ville" name="ville">
                                <?php foreach ($annonce_ville as $value): ?>
                                     <?= '<option>'. $value['ville'] .'</option>'?>
                                <?php endforeach; ?>
                            </select>
                            <label for="membre">Membres</label>
                            <select class="form-control" id="membre" name="membre">
                                <?php foreach ($annonce_membre as $value): ?>
                                     <?= '<option>'. $value['nom'].' ' . $value['prenom'] .'</option>'?>
                                <?php endforeach; ?>
                            </select>
                            <div class="range">
                                <span ="gras">Prix</span>
                                <input type="range" name="range" min="1" max="100" value="50" onchange="range.value=value">
                                <p>maximum 100000€</p>

                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        <!-- <?php echo '<pre>'; print_r($annonce); echo '</pre>' ?> -->
        <!-- <?php echo '<pre>'; print_r($annonce); echo '</pre>' ?> -->
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-4">
                    <form type="#" method="post">
                        <div class="form-group">
                            <select class="form-control" id="membre" name="membre">
                                <option>Trier par prix(du moins cher au plus cher)</option>
                                <option>#</option>
                            </select>

                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <table class="table">
                    <?php foreach($affiche_annonce as $value) : ?>
                	<!-- Debut vignette produit -->
                	<div>
                		<h3><?= $value['titre'] ?></h3>
                		<a href="fiche_produit.php?id=<?= $value['id_annonce'] ?>"><img src=" <?= $value['photo'] ?>" height="100"/></a>
                		<p style="font-weight: bold; font-size: 20px;"><?= $value['prix'] ?>€</p>

                		<p style="height: 40px"><?= substr($value['description_longue'], 0, 100) ?></p>
                		<a href="fiche_annonce.php?id=<?= $value['id_annonce'] ?>" style="padding:5px 15px; background: red; color:white; text-align: center; border: 2px solid black; border-radius: 3px" >Voir la fiche annonce</a>
                		<!-- href="fiche_produit.php?id=id_du_produit" -->
                	</div>
                <?php endforeach; ?>

                </table>
            </div>

        </div>


    </div>
</div>
</div>








<?php require('inc/footer.inc.php');?>
