<?php

/**
 * Classe Contrôleur des requêtes sur l'entité timbre de l'application admin
 */
class AdminTimbre extends Admin
{

  protected $methodes = [
    'l'           => 'listerTimbres',
    'a'           => 'ajouterTimbre',
    'm'           => 'modifierTimbre',
    's'           => 'supprimerTimbre'
    
  ];

    /**
   * Constructeur qui initialise des propriétés à partir du query string
   */
  

   public function __construct()
   {
     $this->timbre_id = $_GET['idTimbre'] ?? null;
 
     $this->oRequetesSQL = new RequetesSQL;
   }

   public function listerTimbres()
   {
    
    //  session_start();
    
     if(!isset($_SESSION))
     {
     session_start();
     }

     $u = $_SESSION;
     if($u['idMembre'] == 1){ $timbres = $this->oRequetesSQL->getTimbres();}
     else{
      $idMembre = $u['idMembre'];
      $timbres = $this->oRequetesSQL->getTimbreParMembre($idMembre);
      
     }
    

     $params = [
        'timbres'           => $timbres,
        'u'           =>  $u,
    ];

     new Vue('vAdminTimbre', $params, 'gabarit-admin');

   }

   public function ajouterTimbre()
   {
        $erreur_image = '';
        if($_FILES['timbre_image']['error'] == 4) {
          $erreur_image = "Aucune image n'a été sélectionnée.";
        }
        $nom_image = $_POST['timbre_nom'] . ".PNG";
        $chemin_image = "uploads/" . $nom_image;
        // move_uploaded_file($_FILES['timbre_image']['tmp_name'], $chemin_image);
        if (isset($_FILES['timbre_image']) && $_FILES['timbre_image']['error'] == UPLOAD_ERR_OK )
        {
          $extensions_autorisees = array('jpg', 'jpeg', 'png', 'gif');
          $extension_upload = strtolower(substr(strrchr($_FILES['timbre_image']['name'], '.'), 1));
          if (in_array($extension_upload, $extensions_autorisees)) 
          {
            $nom_image = $_POST['timbre_nom'] . ".jpg";
            $chemin_image = "uploads/" . $nom_image;
            // move_uploaded_file($_FILES['timbre_image']['tmp_name'], $chemin_image);
            $chemin_image_variable = "./".$chemin_image;
            // var_dump($chemin_image_variable);
          }else{
            $erreur_image = "L'extension de l'image n'est pas autorisée.";
          }
        }
    
       $nom = null;
       $description = null;
       $erreurs  = [];
       $idUtilisateur = intval($_POST['idMembre']);
       $timbre = $_POST;
       $oTimbre = new Timbre($timbre);
       $oTimbre->setNomTimbre($timbre['timbre_nom']);
       $oTimbre->setDescription($timbre['timbre_description']);
       $erreurs = $oTimbre->getErreurs();
   
       if(empty($erreurs)&&$erreur_image == ''&& $_FILES['timbre_image']['error']!= 4)
       {
            if($idUtilisateur == 0) 
            {
              // session_start();
              if(!isset($_SESSION))
              {
              session_start();
              }
         
              $idUtilisateur = $_SESSION['idMembre'] ;
            }
           $nom = $timbre['timbre_nom'];
           $description = $timbre['timbre_description'];
           $etat = $timbre['timbre_etat'];
      
           $reference = $this->oRequetesSQL->getReference();
           $idTimbreImage = $reference[0]['idTimbre'] + 1;
          //  var_dump('chemin_var',$chemin_image_variable);
          //  var_dump('chemin',$chemin_image);
          //  var_dump('files',$_FILES['timbre_image']['tmp_name']);
          //  die();
           $this->oRequetesSQL->ajouterTimbreAdmin($nom, $description,$reference[0]['idTimbre'],$etat,$idUtilisateur);
           $this->oRequetesSQL->ajouterImageParIdTimbre($chemin_image_variable , $idTimbreImage);
           
           move_uploaded_file($_FILES['timbre_image']['tmp_name'], $chemin_image);
           $this->listerTimbres();
           exit;
       }
       else
       {
        if(!isset($_SESSION))
        {
        session_start();
        }
        // session_start();
        $idUtilisateur = $_SESSION['idMembre'] ;
        // var_dump($_SESSION);
           $params = [
               'timbre' => $timbre,
               'erreurs' => $erreurs,
               'erreur_image' => $erreur_image,
           ];
       }
   
       // Charge la vue avec les erreurs ou redirige selon votre besoin
       new Vue('vAdminTimbreAjouter', $params, 'gabarit-admin-small');
   }

   public function modifierTimbre()
   {
    $erreur_image = '';
    $nom_image = $_POST['timbre_nom'] . ".PNG";
    $chemin_image = "uploads/" . $nom_image;
    if (isset($_FILES['timbre_image']) && $_FILES['timbre_image']['error'] == UPLOAD_ERR_OK )
    {
      $extensions_autorisees = array('jpg', 'jpeg', 'png', 'gif');
      $extension_upload = strtolower(substr(strrchr($_FILES['timbre_image']['name'], '.'), 1));
      if (in_array($extension_upload, $extensions_autorisees)) 
      {
        $nom_image = $_POST['timbre_nom'] . ".jpg";
        $chemin_image = "uploads/" . $nom_image;
        // move_uploaded_file($_FILES['timbre_image']['tmp_name'], $chemin_image);
        $chemin_image_variable = "./".$chemin_image;
      }else{
        $erreur_image = "L'extension de l'image n'est pas autorisée.";
      }
    }
    $nom = null;
    $description = null;
    $erreurs  = [];
    $variables = [];
    if (count($_POST) !== 0) 
    {
      $timbre = $_POST;
      $oTimbre = new Timbre($timbre);
      $oTimbre->setNomTimbre($timbre['timbre_nom']);
      $oTimbre->setDescription($timbre['timbre_description']);
      $erreurs = $oTimbre->getErreurs();
      // var_dump($erreurs);
      // die();
      if(empty($erreurs))
      {
        $nom = $timbre['timbre_nom'];
        $description = $timbre['timbre_description'];
        $etat = $timbre['timbre_etat'];
        $idTimbre = $timbre['idTimbre'];
        $this->oRequetesSQL->modifierTimbre($nom, $description,$etat,$idTimbre);
        if($erreur_image == '')
        {
          $this->oRequetesSQL->modifierImageParIdTimbre($chemin_image_variable , $idTimbre);
          move_uploaded_file($_FILES['timbre_image']['tmp_name'], $chemin_image);
        }
        header('Location: ./timbre');
      }else
      {
        $variables = [
          'timbre' => $timbre,
          'erreurs' => $erreurs,
        ];
        // header('Location: ./modifierTimbre');
      }
    }
    new Vue('vAdminTimbreModifier', $variables, 'gabarit-admin-small');
   }
   public function supprimerTimbre() {
    $idTimbre = $_GET['idTimbre'];
    $this->oRequetesSQL->supprimerImageParIdTimbre($idTimbre);
    $this->oRequetesSQL->supprimerEnchereParIdTimbre($idTimbre);
    $this->oRequetesSQL->supprimerTimbre($idTimbre);
   if($_SESSION == 0)session_start();
   $u = $_SESSION;
   if($u['role']== 1){$timbres = $this->oRequetesSQL->getTimbres();}
    else{$timbres = $this->oRequetesSQL->getTimbreParMembre($u['idMembre']);}
    new Vue('vAdminTimbre', array('timbres' => $timbres, 'u' => $u), 'gabarit-admin');
  }
   
}