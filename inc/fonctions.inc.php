<?php
function userConnecte(){
    if(isset($_SESSION['membre'])){
        return TRUE;
    }
    else{
        return FALSE;
    }
}

function userAdmin(){
    if(userConnecte() && $_SESSION['membre']['statut'] == 1){
        return TRUE;
    }
    else{
        return FALSE;
    }
}


function debug($tab) {
   echo '<div style="color: white; padding: 20px; font-weight: bold; background: #' . rand(111111, 999999) . '">';

   $trace = debug_backtrace(); // Retourne les infos sur les emplacements où est exécutée une fonction
   echo 'Le debug a été demandé dans le fichier : ' . $trace[0]['file'] . ' à la ligne : ' .$trace[0]['line']  . '<hr>';

   echo "<pre>";
   print_r($tab);
   echo "</pre>";

   echo '</div>';
}
