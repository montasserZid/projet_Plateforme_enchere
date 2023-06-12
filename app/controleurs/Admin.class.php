<?php

/**
 * ici le contrôleur de l'interface d'administration
 */

class Admin extends Routeur {
    protected $timbre_id;
    protected $enchere_id;
    protected $utilisateur_id;
    protected $utilisateur;
    protected $timbre;
    protected $enchere;
    protected static $entite;
    protected static $action;
    protected $methodes;
    protected static $utilisateur_connecte;
    public function __construct() {
        self::$entite = $_GET['entite']  ?? 'utilisateur';
        self::$action = $_GET['action'] ?? 'm';
        $this->oRequetesSQL = new RequetesSQL;
        session_start(); // Démarrer la session
         if (empty($_SESSION)) { // Vérifier si l'utilisateur est connecté
          
            header('Location: ./connexion'); // Rediriger l'utilisateur vers la page de connexion
            exit(); // Arrêter l'exécution du script
        }
        $this->utilisateur_id = $_SESSION['idMembre']; // Enregistrer l'ID de l'utilisateur dans la propriété protégée
    }
    public function gererEntite() {
     
        if (!isset($_SESSION['idMembre'])) {
            header('Location: ./connexion');
            exit();
        }
    
        $variables = array(
   
            "nom_utilisateur" => $_SESSION['nom'],
            "utilisateur" => $this->utilisateur,
            "timbre" => $this->timbre,
            "enchere" => $this->enchere,

        );
        if (strpos($_SERVER['REQUEST_URI'], 'entite=utilisateur&action=m') !== false) {
             $this->oRequetesSQL = new RequetesSQL;
             $this->utilisateur = $this->oRequetesSQL->getUtilisateurParId($_GET['idMembre']); //KEY
             
            $variables = array(

                "utiliasteur" => $this->utilisateur
    
            );
            new Vue('vAdminUtilisateurModifier', $variables, 'gabarit-admin-small');
        } else if (strpos($_SERVER['REQUEST_URI'], 'entite=utilisateur&action=a') !== false) { 
            $variables = array(

                "utilisateur" => $this->utilisateur
    
            );
            new Vue('vAdminUtilisateurAjouter', $variables, 'gabarit-admin-small');
        }else if (strpos($_SERVER['REQUEST_URI'], 'entite=utilisateur&action=s') !== false){
            $adminUtilisateur = new AdminUtilisateur();
            $adminUtilisateur->supprimerUtilisateur();
            // admin?aFavoris
         } else if (strpos($_SERVER['REQUEST_URI'], 'admin?aFavoris') !== false){
            // var_dump('favoris');
            // die();
            $this->oRequetesSQL = new RequetesSQL;
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
            $idMembre = intval($_GET['idMembre']);
            $idTimbre = intval($_GET['idTimbre']);
            $this->oRequetesSQL->ajouterFavoris($idMembre,$idTimbre);
            // var_dump($utilisateurConnecter);
            $favoris = $this->oRequetesSQL->getFavorisParId($utilisateurConnecter);

            $timbre_V = [];
            
            // var_dump('timbres',$timbres);
            $variables = array(

                "post" => $_POST,
                "session" => $_SESSION,
                "timbres" => $timbres,
                "encheres" => $encheres,
                "images" => $images,
                "timbre_V" => $timbre_V,
                "favoris" => $favoris,
            );
            
            // var_dump($timbres);
            // die();
            
            
            new Vue('vAccueil', $variables, 'gabarit-frontend');

        }else if (strpos($_SERVER['REQUEST_URI'], 'admin?rFavoris') !== false){
            $this->oRequetesSQL = new RequetesSQL;
            $utilisateurConnecter = $_SESSION['idMembre'] ?? 0;
            if($utilisateurConnecter == 0)
            {
            if(!isset($_SESSION))
            {
            session_start();
            $utilisateurConnecter = $_SESSION;
            }
            }
            $timbres = $this->oRequetesSQL->getTimbres();
            $encheres = $this->oRequetesSQL->getActiveEnchere();
            $images = $this->oRequetesSQL->getAllImages();
            $idMembre = intval($_GET['idMembre']);
            $idTimbre = intval($_GET['idTimbre']);
            $this->oRequetesSQL->effacerFavoris($idMembre,$idTimbre);
            // var_dump($utilisateurConnecter);
            $favoris = $this->oRequetesSQL->getFavorisParId($utilisateurConnecter);

            $timbre_V = [];
            
            // var_dump('timbres',$timbres);
            $variables = array(

                "post" => $_POST,
                "session" => $_SESSION,
                "timbres" => $timbres,
                "encheres" => $encheres,
                "images" => $images,
                "timbre_V" => $timbre_V,
                "favoris" => $favoris,
            );
            
            // var_dump($timbres);
            // die();
            
            
            new Vue('vAccueil', $variables, 'gabarit-frontend');
        }else if (strpos($_SERVER['REQUEST_URI'], 'entite=timbre') !== false){
            var_dump('timbre');
            new Vue('vAdminTimbre', $variables, 'gabarit-admin-small');
        }else if (strpos($_SERVER['REQUEST_URI'], 'admin?entite=details&action=m') !== false)
        {
            $this->oRequetesSQL = new RequetesSQL;
            $this->timbre = $this->oRequetesSQL->getTimbreParId($_GET['idTimbre']); 
            
           $variables = array(

                "timbre" => $this->timbre
    
            );
            new Vue('vAdminTimbreModifier', $variables, 'gabarit-admin-small');
        }else if (strpos($_SERVER['REQUEST_URI'], 'admin?entite=details&action=a') !== false) { 
            
            $utilisateur_connecte = $_SESSION['idMembre'];
            // var_dump($utilisateur_connecte);
            $timbres = $this->oRequetesSQL->getTimbres();
            // var_dump($timbre);
            $variables = array(

                "utilisateur" => $this->utilisateur,
                "timbres" => $timbres,
                "membre" => $utilisateur_connecte,
                
    
            );
            new Vue('vAdminTimbreAjouter', $variables, 'gabarit-admin-small');
         }else if (strpos($_SERVER['REQUEST_URI'], 'entite=details&action=s') !== false){
            // var_dump('hello');
            // die();
            $adminTimbre = new AdminTimbre();
            $adminTimbre->supprimerTimbre($_GET['idTimbre']);
           
         }else if (strpos($_SERVER['REQUEST_URI'], 'entite=details') !== false) { 
            // var_dump($_GET['idTimbre']);
            $timbre = $this->oRequetesSQL->getTimbreParId($_GET['idTimbre']);
            $enchere = $this->oRequetesSQL->getEnchereParTimbre($_GET['idTimbre']);
            $imageTimbre = $this->oRequetesSQL->getImageParIdTimbre($_GET['idTimbre']);
            // var_dump($timbre);
            // var_dump($enchere[0]);
            $enchere = $enchere[0];
            $session = $_SESSION;
            var_dump($session);
            $variables = array(
                
                "utilisateur" => $this->utilisateur,
                "timbre" => $timbre,
                "session" => $session,
                "enchere" => $enchere,
                "imageTimbre" => $imageTimbre,
                
    
            );
            new Vue('vDetailsTimbre', $variables, 'gabarit-frontend');
            
        
    }else if (strpos($_SERVER['REQUEST_URI'], 'admin?entite=enchere&action=a') !== false){
            // $idTimbre = intval($_GET['idTimbre']);
            $timbres = $this->oRequetesSQL->getTimbreParUtilisateur($_GET['idMembre']);
            $variables = array(
                "timbres" => $timbres,
                "membre" => $_GET['idMembre'],
                
            );
            new Vue('vAdminEnchereAjouter', $variables, 'gabarit-admin-small');
            
         }
         else if (strpos($_SERVER['REQUEST_URI'], 'admin?entite=enchere&action=m') !== false){
            
            $idTimbre = intval($_GET['idTimbre']);
            $idMembre = intval($_GET['idMembre']);
            $variables = array(
                "timbre" => $this->timbre,
                "enchere" => $this->enchere,
                "idTimbre" => $idTimbre,
                "idMembre" => $idMembre,
                
            );
            
            new Vue('vAdminEnchereModifier', $variables, 'gabarit-admin-small');
         }else if (strpos($_SERVER['REQUEST_URI'], 'admin?entite=enchere&action=s') !== false){
            // $idMembre = intval($_GET['idMembre']);
            $adminEnchere = new AdminEnchere();
            $adminEnchere->supprimerEnchere();
         }
         else{
          
             new Vue('gabarit-admin', $variables, 'gabarit-admin-small');
    }
    }
}