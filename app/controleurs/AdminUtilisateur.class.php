<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Utilisateur de l'application admin
 */

class AdminUtilisateur extends Admin
{

  protected $methodes = [
    'l'           => 'listerUtilisateurs',
    'a'           => 'ajouterUtilisateur',
    'm'           => 'modifierUtilisateur',
    's'           => 'supprimerUtilisateur',
    'd'           => 'deconnecter',
    'generer_mdp' => 'genererMdp'
  ];

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   */
  

  public function __construct()
  {
    $this->utilisateur_id = $_GET['idMembre'] ?? null;

    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * Connecter un utilisateur à l'application
   */
  public function connecter()
  {
    $email = null;
    $password = null;
    $erreurs  = [];
    $this->oRequetesSQL = new RequetesSQL;
    $messageErreurConnexion = "";

    if (count($_POST) !== 0) {

      $utilisateur_connecte = $this->oRequetesSQL->connexion($email , $password);  
      // var_dump($utilisateur_connecte);
      // die();
      if ($utilisateur_connecte !== false) {
        $_SESSION['u'] = new Utilisateur($utilisateur_connecte);
        parent::gererEntite();
        exit;         
      } else {

        $messageErreurConnexion = "email ou mot de passe incorrect.";
      }
    }

    $utilisateur = [];

    new Vue('vConnexion', array('email' => $email, 'password' => $password, 'erreur' => $messageErreurConnexion), 'gabarit-frontend');
  }

  /**
   * Déconnecter un utilisateur de l'application
   */
  public function deconnecter()
  {
    unset($_SESSION['u']);
    parent::gererEntite();
  }

  /**
   * Lister les utilisateurs de l'application
   */
  public function listerUtilisateurs()
  {
    // if (self::$utilisateur_connecte->utilisateur_profil !== Utilisateur::PROFIL_ADMINISTRATEUR)
    //   throw new Exception(self::ERROR_FORBIDDEN);
    session_start();
    // var_dump($_SESSION);
    // die();
    $u = $_SESSION;
    $utilisateurs = $this->oRequetesSQL->getUtilisateurs();
    // var_dump($utilisateurs);
    // die();
    new Vue('vAdminUtilisateurs', array('utilisateurs' => $utilisateurs, 'u' => $u), 'gabarit-admin');
    // (new Vue)->generer('vAdminUtilisateurs',
    //         array(
    //           'u'                   => self::$utilisateur_connecte,
    //           'titre'               => 'Gestion des utilisateurs',
    //           'utilisateurs'        => $utilisateurs,
    //           'classRetour'         => $this->classRetour,  
    //           'messageRetourAction' => $this->messageRetourAction
    //         ),
    //         'gabarit-admin');
  }

  /**
   * Ajouter un utilisateur dans la base de données
   */
  public function ajouterUtilisateur()
  {
  
    // if (self::$utilisateur_connecte->utilisateur_profil !== Utilisateur::PROFIL_ADMINISTRATEUR)
    //   throw new Exception(self::ERROR_FORBIDDEN);
    
    if (count($_POST) !== 0) {
      $utilisateur = $_POST;
      // var_dump($utilisateur);
      // die();
      $oUtilisateur = new Utilisateur($utilisateur);
      // $oUtilisateur->courrielExiste();
      $erreurs = $oUtilisateur->erreurs;
      // if (count($erreurs) === 0) {
        $retour = $this->oRequetesSQL->ajouterUtilisateurAdmin([
          'nom'      => $oUtilisateur->utilisateur_nom,
          'prenom'   => $oUtilisateur->utilisateur_prenom,
          'email'    => $oUtilisateur->utilisateur_courriel,
          'password' => $oUtilisateur->utilisateur_mdp,
          'adresse'  => $oUtilisateur->utilisateur_adresse,
          'telephone' => $oUtilisateur->utilisateur_telephone,
          'idMembre' => $oUtilisateur->utilisateur_id,
        ]);
        if ($retour !== Utilisateur::ERR_COURRIEL_EXISTANT) {
          // if ($retour === true)  {
          //   $this->messageRetourAction = "Modification de l'utilisateur numéro $this->utilisateur_id effectuée.";    
          // } else {  
          //   $this->classRetour = "erreur";
          //   $this->messageRetourAction = "Modification de l'utilisateur numéro $this->utilisateur_id non effectuée.";
          // }
          $this->listerUtilisateurs();
          exit;
        } else {
          $erreurs['utilisateur_courriel'] = $retour;
        }
      // }

      new Vue(
        'vAdminUtilisateurAjouter',
        array(
          'u'           => self::$utilisateur_connecte,
          'titre'       => 'Ajouter un utilisateur',
          'utilisateur' => $utilisateur,
          'erreurs'     => $erreurs
        ),
        'gabarit-admin'
      );
    }
  }

  /**
   * Modifier un utilisateur dans la base de données
   */
  public function modifierUtilisateur()
  {

    // if (self::$utilisateur_connecte->utilisateur_profil !== Utilisateur::PROFIL_ADMINISTRATEUR)
    //   throw new Exception(self::ERROR_FORBIDDEN);

    // if (!preg_match('/^\d+$/', $this->utilisateur_id))
    //   throw new Exception("Numéro d'utilisateur non renseigné pour une modification");
    
    if (count($_POST) !== 0) {
      
      $utilisateur = $_POST;
      $erreurs = [];
     
      $oUtilisateur = new Utilisateur($utilisateur);
      // $oUtilisateur->courrielExiste();
      $erreurs = $oUtilisateur->erreurs;
      // if (count($erreurs) === 0) {
      $retour = $this->oRequetesSQL->modifierUtilisateur([
        'nom'      => $oUtilisateur->utilisateur_nom,
        'prenom'   => $oUtilisateur->utilisateur_prenom,
        'email'    => $oUtilisateur->utilisateur_courriel,
        // 'password' => $oUtilisateur->utilisateur_mdp,
        // 'adresse'  => $oUtilisateur->utilisateur_adresse,
        // 'telephone' => $oUtilisateur->utilisateur_telephone,
        'idMembre' => $oUtilisateur->utilisateur_id,
      ]);
      if(empty($erreurs)){
        // $password = $_POST['password'];
        // $nom = $_POST['nom'];
        // $prenom = $_POST['prenom'];
        // $adresse = $_POST['adresse'];
        // $telephone = $_POST['telephone'];
        
        // header('Location: ./connexion');
        // exit;
        
      }
      
      // if ($retour !== Utilisateur::ERR_COURRIEL_EXISTANT) {
        // if ($retour === true)  {
        //   $this->messageRetourAction = "Modification de l'utilisateur numéro $this->utilisateur_id effectuée.";    
        // } else {  
        //   $this->classRetour = "erreur";
        //   $this->messageRetourAction = "Modification de l'utilisateur numéro $this->utilisateur_id non effectuée.";
        // }
      //   $this->listerUtilisateurs();
      //   exit;
      // } else {
      //   $erreurs['utilisateur_courriel'] = $retour;
      //   var_dump($erreurs);
      // }
    // }
    // } else {
    // $utilisateur = $this->oRequetesSQL->getUtilisateur($this->utilisateur_id);
    //   $erreurs = [];
    // }

    new Vue(
      'vAdminUtilisateurModifier',
      array(
        'u'           => self::$utilisateur_connecte,
        'titre'       => "Modifier l'utilisateur numéro $this->utilisateur_id",
        'utilisateur' => $utilisateur,
        'erreurs'     => $erreurs
      ),
      'gabarit-admin'
    );
  }
}

  // /**
  //  * Supprimer un utilisateur de la base de données
  //  */
  public function supprimerUtilisateur() {
    // var_dump('here');
    // die();
    // if (self::$utilisateur_connecte->utilisateur_profil !== Utilisateur::PROFIL_ADMINISTRATEUR)
    //   throw new Exception(self::ERROR_FORBIDDEN);

    if (!preg_match('/^\d+$/', $this->utilisateur_id))
      throw new Exception("Numéro d'utilisateur incorrect pour une suppression.");
    
    $retour = $this->oRequetesSQL->supprimerUtilisateur($this->utilisateur_id);
    // if ($retour === false) $this->classRetour = "erreur";
    // $this->messageRetourAction = "Suppression de l'utilisateur numéro $this->utilisateur_id ".($retour ? "" : "non ")."effectuée.";
    // $this->listerUtilisateurs();
    if($_SESSION == 0)session_start();
    // var_dump($_SESSION);
    // die();
    $u = $_SESSION;
    $utilisateurs = $this->oRequetesSQL->getUtilisateurs();
    new Vue('vAdminUtilisateurs', array('utilisateurs' => $utilisateurs, 'u' => $u), 'gabarit-admin');
  }

}
