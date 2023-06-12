<?php

/**
 * Classe Routeur
 * analyse l'url et exécute la méthode associée  
 *
 */
class Routeur {
  
  private $routes = [
  // url, classe, méthode
  // --------------------
    ["admin",         "Admin",    "gererEntite"],
    ["index" ,   "Frontend"    , "accueil"   ],
    ["rFavoris" ,"AdminFavoris"    , "removeFavoris"   ],
    ["mise" , "AdminMise"    ,"afficherMise"],
    ["" ,   "Frontend"    , "accueil"   ], 
    ["rechercher" ,   "Frontend"    , "rechercher"   ], 
    ["connexion" , "Frontend"    ,"connexion"],
    ["inscription" , "Frontend"    ,"inscription"],
    ["deconnexion" , "Frontend"    ,"deconnexion"],
    ["enchere" , "AdminEnchere"    ,"listerEnchere"],
    ["ajouterEnchere" , "AdminEnchere"    ,"ajouterEnchere"],
    ["modifierEnchere" , "AdminEnchere"    ,"modifierEnchere"],
    ["timbre" , "AdminTimbre"    ,"listerTimbres"],
    ["ajouterTimbre" , "AdminTimbre"    ,"ajouterTimbre"],
    ["modifierTimbre" , "AdminTimbre"    ,"modifierTimbre"],
    ["utilisateur" , "AdminUtilisateur"    ,"listerUtilisateurs"],
    ["modifierUtilisateur" , "AdminUtilisateur"    ,"modifierUtilisateur"],
    ["ajouterUtilisateur" , "AdminUtilisateur"    ,"ajouterUtilisateur"],
    ["ajouterMise" , "AdminMise"    ,"ajouterMise"]
    
    
  // ===> ajoutez les routes associées aux méthodes accueil, rechercher et statistiques de la classe Frontend
  ];

  protected $oRequetesSQL; // objet RequetesSQL utilisé par tous les contrôleurs
  
  const BASE_URI = '/monta/projet/dembele/version-v2/MVC-examen/'; // pour le PHP Server de Visual Studio Code

  const ERROR_FORBIDDEN = "HTTP 403";
  const ERROR_NOT_FOUND = 'HTTP 404';
  
  /**
   * Constructeur qui valide l'URI,
   * instancie le contrôleur et exécute la méthode de ce contrôleur qui lui sont associés
   *
   */
  public function __construct() {
    try {

      // contrôle de l'uri si l'action coïncide 
      $uri =  $_SERVER['REQUEST_URI'];
      if (strpos($uri, '?')) $uri = strstr($uri, '?', true);

      // balayage du tableau des routes
      foreach ($this->routes as $route) {

        $routeUri     = self::BASE_URI.$route[0];
        $routeClasse  = $route[1];
        $routeMethode = $route[2];
        
        if ($routeUri ===  $uri) {
          // on exécute la méthode associée à l'uri
          $oControleur = new $routeClasse;
          // var_dump($oControleur);
          $oControleur->$routeMethode();  
          exit;
        }
      }
      // aucune route ne correspond à l'uri
      throw new Exception(self::ERROR_NOT_FOUND);
    }
    catch (Error | Exception $e) {
      $this->erreur($e);
    }
  }

  /**
   * Méthode qui envoie un compte-rendu d'erreur
   * @param Exception $e 
   */
  public function erreur($e) {
    $message = $e->getMessage();
    if ($message == self::ERROR_FORBIDDEN) {
      header('HTTP/1.1 403 Forbidden');
    } else if ($message == self::ERROR_NOT_FOUND) {
      header('HTTP/1.1 404 Not Found');
      new Vue('vErreur404', [], 'gabarit-erreur');
    } else {
      header('HTTP/1.1 500 Internal Server Error');
      new Vue('vErreur500',
             ['message' => $message, 'fichier' => $e->getFile(), 'ligne' => $e->getLine()],
             'gabarit-erreur');
    }
    exit;
  }
}