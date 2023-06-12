<?php

/**
 * Classe Contrôleur des requêtes de l'application frontend
 */

class Frontend extends Routeur {

  
  
  /**
   * Constructeur qui initialise le contexte du contrôleur  
   */  
  public function __construct() {
    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * Page statique d'accueil
   */  
  public function accueil() {
    session_start();
    $nom = "John";
    $prenom = "Doe";
    $utilisateurConnecter = $_SESSION['idMembre'] ?? 0;
    
    // $date_ajoudhui = date("Y-m-d");
    $timbres = $this->oRequetesSQL->getTimbres();
    $encheres = $this->oRequetesSQL->getActiveEnchere();
    $images = $this->oRequetesSQL->getAllImages();
    $favoris = $this->oRequetesSQL->getFavorisParId($utilisateurConnecter);
    $timbre_V = [];

    $variables = array(
        "nom" => $nom,
        "prenom" => $prenom,
        "post" => $_POST,
        "session" => $_SESSION,
        "timbres" => $timbres,
        "encheres" => $encheres,
        "images" => $images,
        "timbre_V" => $timbre_V,
        "favoris" => $favoris
    );

    new Vue('vAccueil', $variables, 'gabarit-frontend');
}

public function deconnexion() {
  // unset ($_SESSION['oUtilConn']);
  // echo json_encode(true);
  // Démarre ou restaure une session
    session_start();

    // Supprime toutes les données de la session
    session_destroy();

    // Redirige l'utilisateur vers la page d'accueil ou une autre page de votre choix
    header('Location: ./connexion');
    exit();
}


  /**
   * Statistiques selon le prix par tranches de 5000 $ 
   */  
  public function connexion() {
    
    $email = null;
    $password = null;
    $erreurs  = [];

    // Vérification du formulaire de connexion
    if (count($_POST) !== 0) {
   
        $email = $_POST['email'];
        $password = $_POST['password'];
        $membreExiste = $this->oRequetesSQL->connexion($email, $password);
        
        if ($membreExiste) {
            // Enregistrement de la session
            session_start();
            $_SESSION['idMembre'] = $membreExiste['idMembre'];
            $_SESSION['nom'] = $membreExiste['nom'];
            $_SESSION['prenom'] = $membreExiste['prenom'];
            $_SESSION['role'] = $membreExiste['Role_idRole'];
            header('Location: ./index');
            exit;
        } else {
            $erreurs['connexion'] = "L'email et/ou le mot de passe est incorrect";
        }
    }
  
    new Vue('vConnexion', array('email' => $email, 'password' => $password, 'erreurs' => $erreurs), 'gabarit-frontend');
}
  //inscription
  public function inscription(){
    $email = null;
    $password = null;
    $nom = null;
    $prenom = null;
    $adresse = null;
    $telephone = null;
    $erreurs  = [];

    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $count = $this->oRequetesSQL->verifEmail($email);
        if ($count) {
          $erreurs['email'] = "L'email est déjà utilisé.";
        } else {
            $membre = new Membre();
            $membre->setPassword($_POST['password']);
            $membre->setEmail($_POST['email']);
            $membre->setNom($_POST['nom']);
            $membre->setPrenom($_POST['prenom']);
            $membre->setTelephone($_POST['telephone']);
            $membre->setAdresse($_POST['adresse']);
            $erreurs = $membre->getErreurs();
            if(empty($erreurs)){
              $password = $_POST['password'];
              $nom = $_POST['nom'];
              $prenom = $_POST['prenom'];
              $adresse = $_POST['adresse'];
              $telephone = $_POST['telephone'];
              $this->oRequetesSQL->inscription( $nom,$prenom ,$email, $password , $adresse, $telephone);
              header('Location: ./connexion');
              exit;
            }
          }
    }

    new Vue('vInscription', array( 'erreurs' => $erreurs), 'gabarit-frontend');
}

}