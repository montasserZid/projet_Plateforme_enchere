<?php
class AdminFavoris extends Admin
{
    public function __construct()
    {
        $this->oRequetesSQL = new RequetesSQL;
    }
 public function removeFavoris(){
    $utilisateurConnecter = $_SESSION['idMembre'] ?? 0;
    if($utilisateurConnecter == 0)
    {
     if(!isset($_SESSION))
     {
     session_start();
     $utilisateurConnecter = $_SESSION;
     }
    }
    // var_dump("hello",$utilisateurConnecter);
    $timbres = $this->oRequetesSQL->getTimbres();
    $encheres = $this->oRequetesSQL->getActiveEnchere();
    $images = $this->oRequetesSQL->getAllImages();
    $timbre_V = [];
    // var_dump('enchere',$encheres);
    // var_dump('timbres',$timbres);
    $variables = array(

        "post" => $_POST,
        "session" => $_SESSION,
        "timbres" => $timbres,
        "encheres" => $encheres,
        "images" => $images,
        "timbre_V" => $timbre_V
    );
    
    // var_dump($timbres);
    // die();
    new Vue('vAccueil', $variables, 'gabarit-frontend');
}

public function addFavoris(){
    $utilisateurConnecter = $_SESSION['idMembre'] ?? 0;
    if($utilisateurConnecter == 0)
    {
     if(!isset($_SESSION))
     {
     session_start();
     $utilisateurConnecter = $_SESSION;
     }
    }
    // var_dump("hello",$utilisateurConnecter);
    $timbres = $this->oRequetesSQL->getTimbres();
    $encheres = $this->oRequetesSQL->getActiveEnchere();
    $images = $this->oRequetesSQL->getAllImages();
    $timbre_V = [];
    // var_dump('enchere',$encheres);
    // var_dump('timbres',$timbres);
    $variables = array(

        "post" => $_POST,
        "session" => $_SESSION,
        "timbres" => $timbres,
        "encheres" => $encheres,
        "images" => $images,
        "timbre_V" => $timbre_V
    );
    
    // var_dump($timbres);
    // die();
    new Vue('vAccueil', $variables, 'gabarit-frontend');
}

}