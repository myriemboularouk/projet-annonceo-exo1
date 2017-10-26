<?php
require_once('inc/init.inc.php');

// Traitement pour la déconnexion :
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion'){ // Si une action est demandée dans l'url et que cette action est "deconnexion" alors on procède à la deconnexion.
    unset($_SESSION['membre']);
    header('location:' . URL . 'connexion.php');
}

// Traitement pour rediriger l'utilisateur s'il est déjà connécté
if(userConnecte()){
    header('location:profil.php');
}

// Traitements pour la connexion

// Vérifier que le formulaire n'est pas vide
// Debug() pour vérifier
// On vérifie que les deux champs ne sont pas vides
// On connecte le membre en enregistrant ses infos dans la session
   // -> Le membre existe-t-il en BDD ?
   // -> Le MDP saisi correspond au pseudo en BDD
   // -> Enregistrement en session
   // -> Redirection vers profil

// Vérifier que le formulaire n'est pas vide
if(!empty($_POST)){

    // Debug() pour vérifier
    // debug($_POST);

    // On vérifie que les deux champs ne sont pas vides
    if(!empty($_POST['pseudo']) && !empty($_POST['mdp'])){

        // On connecte le membre en enregistrant ses infos dans la session
        // -> Le membre existe-t-il en BDD ?
        $resultat = $pdo -> prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
        $resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $resultat -> execute();

        if($resultat -> rowCount() > 0){ // signifie que le pseudo est déja inscrit dans la BDD puisque supérieur à 0 donc pas nul


            // -> Le MDP saisi correspond au pseudo en BDD
            $membre = $resultat -> fetch(PDO::FETCH_ASSOC); // On récupère toutes les infos du membre qui souhaite se connecter sous la forme d'un array
            if($membre['mdp'] == md5($_POST['mdp'])){ // si (mdp_crypte_en_bdd == mdp saisi + crypté... ALORS TOUT EST OK)
                // Tout est OK on peut connecter l'utilisateur
                foreach($membre as $indice => $valeur){
                    if($indice != 'mdp'){
                        $_SESSION['membre'][$indice] = $valeur;
                    }
                }
                debug($membre);

                // redirection
                header('location:profil.php');
            }
            else{
                $msg .= '<div class="alert alert-danger">Mot de passe erroné !</div>';
            }
        }


        else{
            $msg .= '<div class="alert alert-danger"> Le pseudo '. $_POST['pseudo'].' n\'est pas reconnu.</div>';
        }

    }// fin du if de verification si les deux champs ne sont pas vides
    else{
        $msg .= '<div class="alert alert-danger"> Veuillez renseigner un pseudo et un mot de passe !</div>';
    }



} // fin du if (!empty($_POST))


$page = 'Connexion';
require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');
?>
<!-- Contenu HTML -->
<h1>Connexion</h1>

    <form class ="form-inline" action="" method="post">
        <?= $msg ?>
        <div class="form-group">
            <label>Pseudo :</label>
            <input type="text" class="form-control" name="pseudo">
        </div>
        <div class="form-group">
            <label>Mot de passe :</label>
            <input type="text" class="form-control" name="mdp">
        </div>
        <input type="submit" class="btn btn-default" value="Connexion">
    </form>







<?php
require_once('inc/footer.inc.php');
?>
