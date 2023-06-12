<?php

/**
 * Classe Contrôleur des requêtes sur l'entité enchere de l'application admin
 */
class AdminEnchere extends Admin
{

  protected $methodes = [
    'l'           => 'listerEnchere',
    'a'           => 'ajouterEnchere',
    'm'           => 'modifierEnchere',
    's'           => 'supprimerEnchere'
    
  ];

    /**
   * Constructeur qui initialise des propriétés à partir du query string
   */
  

   public function __construct()
   {
     $this->timbre_id = $_GET['idTimbre'] ?? null;
     $this->utilisateur_id = $_GET['idMembre'] ?? null;
     $this->oRequetesSQL = new RequetesSQL;
   }


   public function listerEnchere()
   {
    
    //  session_start();  
  
    
     if(!isset($_SESSION))
     {
     session_start();
     }

     $u = $_SESSION;
    //  var_dump($u);
     $idMembre = $u['idMembre'];
     $tous_les_membres = $this->oRequetesSQL->getAllMembre();
     $tous_les_timbres = $this->oRequetesSQL->getAllTimbres();
     if($u['role'] == 1){ $encheres = $this->oRequetesSQL->getEnchere();}
     else{
      $idMembre = $u['idMembre'];
      $encheres = $this->oRequetesSQL->getEnchereParMembre($idMembre);
    }
     
        
     $idTimbre = $encheres[0]['Timbre_idTimbre'];
     $idMembre = $encheres[0]['Membre_idMembre'];
     $idStatus = $encheres[0]['Status_idStatus'];

     $proprietaire = $this->oRequetesSQL->getUtilisateurParId($idMembre);
     $timbre = $this->oRequetesSQL->getTimbreParId($idTimbre);
     $status = $this->oRequetesSQL->getStatusParId($idStatus);
     $nom_complet = "";
     $nom_timbre = "";
     $params = [
        'encheres'        => $encheres,
        'u'           =>  $u,
        'timbre' => $timbre,
        'proprietaire' => $proprietaire,
        'status' => $status,
        'tous_les_membres' => $tous_les_membres,
        'nom_complet' => $nom_complet,
        'nom_des_timbres' => $nom_timbre,
        'tous_les_timbres' => $tous_les_timbres
    ];
    // var_dump($params);
    // die();
     new Vue('vAdminEnchere', $params, 'gabarit-admin');

   }

   public function ajouterEnchere()
   {
        $prix = null;
        $date = null;
        $enchere = $_POST;
        $idTimbre = intval($enchere['timbre']);
        $status_enchere = $this->oRequetesSQL->getEnchereParTimbre($idTimbre);
        if($status_enchere == null) $status_enchere = 3;
        $idUtilisateur = intval($enchere['idMembre']);
        $idTimbre = intval($enchere['timbre']);
        $status_enchere = intval($status_enchere);
        $erreurs  = [];
        $oEnchere = new Enchere($enchere);
        $oEnchere->setPrix($enchere['prix_depart']);
        $date = $this->setDateExpiration($enchere['expiration']);
        $oEnchere->verifStatusEcnhere($status_enchere);
        $erreurs = $oEnchere->getErreurs();
        // var_dump($erreurs);
        // die();
        if(empty($erreurs)){
            if($idUtilisateur == 0) 
                {
                    if(!isset($_SESSION))
                    {
                    session_start();
                    }
                
                    $idUtilisateur = $_SESSION['idMembre'] ;
                }
            $prix = $enchere['prix_depart'];
            $status_enchere = 1;
            $this->oRequetesSQL->ajouterEchere($idTimbre, $idUtilisateur, $date,$prix, $status_enchere);
            $this->listerEnchere();
            exit;
        }else{
            if(!isset($_SESSION))
            {
            session_start();
            // $idUtilisateur = $_SESSION['idMembre'] ;
            }
            
            $timbres = $this->oRequetesSQL->getTimbreParUtilisateur($_SESSION['idMembre']);
            
                $params = [
                'timbres' => $timbres,
                'erreurs' => $erreurs,
                ];
            }
        
            // Charge la vue avec les erreurs ou redirige selon votre besoin
            new Vue('vAdminEnchereAjouter', $params, 'gabarit-admin-small');
       
   }

   public function modifierEnchere(){
        $prix = null;
        $dateFin = null;
        $enchere = $_POST;
        $idTimbre = intval($enchere['idTimbre']);
        $idMembre = intval($enchere['idMembre']);
        $erreurs  = [];
        //  var_dump($idTimbre, $enchere,$idMembre);
        //  die();
        $oEnchere = new Enchere($enchere);
        $oEnchere->setPrix($enchere['prix_depart']);
        $dateFin = $this->setDateExpiration($enchere['expiration']);
        $erreurs = $oEnchere->getErreurs();
        if(empty($erreurs)){
            if($idMembre == 0) 
                {
                    if(!isset($_SESSION))
                    {
                    session_start();
                    }
                
                    $idMembre = $_SESSION['idMembre'] ;
                }
            $prix = $enchere['prix_depart'];
            $this->oRequetesSQL->modifierEnchereParIdTimbre($idTimbre, $prix, $dateFin);
            $this->listerEnchere();
            exit;
        }else{
            if(!isset($_SESSION))
            {
            session_start();
            // $idUtilisateur = $_SESSION['idMembre'] ;
            }
            
            $timbres = $this->oRequetesSQL->getTimbreParUtilisateur($_SESSION['idMembre']);
            
                $params = [
                'timbres' => $timbres,
                'erreurs' => $erreurs,
                ];
            }
        
            // Charge la vue avec les erreurs ou redirige selon votre besoin
            new Vue('vAdminEnchereModifier', $params, 'gabarit-admin-small');
   }
   public function supprimerEnchere() {
    // var_dump($_GET,$_POST,$_SESSION);
    // die();
    $idTimbre = $_GET['idTimbre'];
   $this->oRequetesSQL->supprimerEnchere($idTimbre);
   if($_SESSION == 0)session_start();
   $u = $_SESSION;
   $timbres = $this->oRequetesSQL->getTimbres();
    new Vue('vAdminEnchere', array('timbres' => $timbres, 'u' => $u), 'gabarit-admin');
   }
   public function setDateExpiration($date) {
    
    if ($date == "1j") {
        $date_actuelle = date("Y-m-d");
        $date = date("Y-m-d", strtotime("+1 day", strtotime($date_actuelle)));
    }else if($date == "3j"){
        $date_actuelle = date("Y-m-d");
        $date = date("Y-m-d", strtotime("+3 day", strtotime($date_actuelle)));
    }else if($date == "7j"){
        $date_actuelle = date("Y-m-d");
        $date = date("Y-m-d", strtotime("+7 day", strtotime($date_actuelle)));
    }
    
    return $date;
}
//    public function ajouterTimbre()
//    {
//        $nom = null;
//        $description = null;
//        $erreurs  = [];
//        $idUtilisateur = intval($_POST['idMembre']);
//        $timbre = $_POST;
//        $oTimbre = new Timbre($timbre);
//        $oTimbre->setNomTimbre($timbre['timbre_nom']);
//        $oTimbre->setDescription($timbre['timbre_description']);
//        $erreurs = $oTimbre->getErreurs();
   
//        if(empty($erreurs))
//        {
//             if($idUtilisateur == 0) 
//             {
//               // session_start();
//               if(!isset($_SESSION))
//               {
//               session_start();
//               }
         
//               $idUtilisateur = $_SESSION['idMembre'] ;
//             }
//            $nom = $timbre['timbre_nom'];
//            $description = $timbre['timbre_description'];
//            $etat = $timbre['timbre_etat'];
//            $reference = $this->oRequetesSQL->getReference();
//            $this->oRequetesSQL->ajouterTimbreAdmin($nom, $description,$reference[0]['idTimbre'],$etat,$idUtilisateur);
//            $this->listerTimbres();
//            exit;
//        }
//        else
//        {
//         if(!isset($_SESSION))
//         {
//         session_start();
//         }
//         // session_start();
//         $idUtilisateur = $_SESSION['idMembre'] ;
//         // var_dump($_SESSION);
//            $params = [
//                'timbre' => $timbre,
//                'erreurs' => $erreurs,
//            ];
//        }
   
//        // Charge la vue avec les erreurs ou redirige selon votre besoin
//        new Vue('vAdminTimbreAjouter', $params, 'gabarit-admin-small');
//    }

//    public function modifierTimbre()
//    {
//     $nom = null;
//     $description = null;
//     $erreurs  = [];
//     $variables = [];
//     if (count($_POST) !== 0) 
//     {
//       $timbre = $_POST;
//       $oTimbre = new Timbre($timbre);
//       $oTimbre->setNomTimbre($timbre['timbre_nom']);
//       $oTimbre->setDescription($timbre['timbre_description']);
//       $erreurs = $oTimbre->getErreurs();
//       // var_dump($erreurs);
//       // die();
//       if(empty($erreurs))
//       {
//         $nom = $timbre['timbre_nom'];
//         $description = $timbre['timbre_description'];
//         $etat = $timbre['timbre_etat'];
//         $idTimbre = $timbre['idTimbre'];
//         $this->oRequetesSQL->modifierTimbre($nom, $description,$etat,$idTimbre);
//         header('Location: ./timbre');
//       }else
//       {
//         $variables = [
//           'timbre' => $timbre,
//           'erreurs' => $erreurs,
//         ];
//         // header('Location: ./modifierTimbre');
//       }
//     }
//     new Vue('vAdminTimbreModifier', $variables, 'gabarit-admin-small');
//    }
//    public function supprimerTimbre() {
//     $idTimbre = $_GET['idTimbre'];
//    $this->oRequetesSQL->supprimerTimbre($idTimbre);
//    if($_SESSION == 0)session_start();
//    $u = $_SESSION;
//    $timbres = $this->oRequetesSQL->getTimbres();
//     new Vue('vAdminTimbre', array('timbres' => $timbres, 'u' => $u), 'gabarit-admin');
//   }
   
}