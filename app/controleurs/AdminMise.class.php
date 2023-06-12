<?php

/**
 * Classe Contrôleur des requêtes sur l'entité mise de l'application admin
 */

 class AdminMise extends Admin
{

  protected $methodes = [
    'l'           => 'listerMise',
    'a'           => 'ajouterMise',
    'm'           => 'modifierMise',
    's'           => 'supprimerMise'
    
  ];
  public function __construct()
  {
    $this->timbre_id = $_GET['idTimbre'] ?? null;
    $this->utilisateur_id = $_GET['idMembre'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
    if(!isset($_SESSION))
    {
    session_start();
    
    }
  }

  public function afficherMise(){


    $u = $_SESSION;
    $idMembre = $u['idMembre'];
    $membreMise = $this->oRequetesSQL->getMiseParIdMembre($idMembre);
    $membreNom = $this->oRequetesSQL->getUtilisateurParId($idMembre);
    $timbres = $this->oRequetesSQL->getTimbres();
    // var_dump($membreMise,$timbres);
    $params = [
      'membreMise'           => $membreMise,
      'u'           =>  $u,
      'membreNom' => $membreNom,
      'timbres' => $timbres
    ];

   new Vue('vAdminMise', $params, 'gabarit-admin');
  }
  public function ajouterMise(){
    
    $mise = $_POST;
    $idMembre = intval($mise['idMembre']);
    $idTimbre = intval($mise['idTimbre']);
    $utilisateurConnecter = intval($mise['utilisateurConnecter']);
    $montant = $mise['miser'];
    $PrixActuelle = intval($mise['PrixActuelle']);
    $erreurs  = [];
    $oMise = new Mise($idMembre, $idTimbre, $montant);
    $oMise->setMise($montant,$PrixActuelle,$idMembre,$utilisateurConnecter);
    $erreurs = $oMise->getErreurs();
    // var_dump($erreurs);
    // die();
    if(empty($erreurs)){
       if($utilisateurConnecter == 0)
       {
        if(!isset($_SESSION))
        {
        session_start();
        $session = $_SESSION;
        }
    
        $utilisateurConnecter = $_SESSION['idMembre'] ;
       } 
       $montant = intval($montant);
       $this->oRequetesSQL->ajouterMise($montant,$idTimbre,$utilisateurConnecter,$idMembre);
       $this->oRequetesSQL->ajusterPrixEnchere($idTimbre,$montant);
       $timbre = $this->oRequetesSQL->getTimbreParId($idTimbre);
       $enchere = $this->oRequetesSQL->getEnchereParTimbre($idTimbre);
       $imageTimbre = $this->oRequetesSQL->getImageParIdTimbre($idTimbre);
       $enchere = $enchere[0];
       $success = "Votre mise de $$montant a été Accepté";
       $session = [$mise['sessionNom'],$mise['sessionPrenom'],$mise['idMembre']];
       $sessionObjet = array_combine(['nom', 'prenom', 'idMembre'], $session);

       $params=[
        "utilisateur" => $this->utilisateur,
        "timbre" => $timbre,
        "session" => $sessionObjet,
        "enchere" => $enchere,
        "imageTimbre" => $imageTimbre,
        'success' => $success,
        
   
    ];
       
       new Vue('vDetailsTimbre', $params, 'gabarit-frontend');
        exit;
    }else{
        if(!isset($_SESSION))
        {
        session_start();
        $session = $_SESSION;
        }
        $timbre = $this->oRequetesSQL->getTimbreParId($idTimbre);
        $enchere = $this->oRequetesSQL->getEnchereParTimbre($idTimbre);
        $imageTimbre = $this->oRequetesSQL->getImageParIdTimbre($idTimbre);
        $enchere = $enchere[0];
        $session = [$mise['sessionNom'],$mise['sessionPrenom'],$mise['idMembre']];
        $sessionObjet = array_combine(['nom', 'prenom', 'idMembre'], $session);
        $params=[
            "utilisateur" => $this->utilisateur,
            "timbre" => $timbre,
            "enchere" => $enchere,
            "imageTimbre" => $imageTimbre,
            'erreurs' => $erreurs,
            'session' => $sessionObjet,
       
        ];
        new Vue('vDetailsTimbre', $params, 'gabarit-frontend');
    }
}
}