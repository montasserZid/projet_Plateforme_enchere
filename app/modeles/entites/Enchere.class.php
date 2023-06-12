<?php

/**
 * Classe de l'entité Enchere
 *
 */
class Enchere
{
    private $prix;
    private $date;
    private $enchere;
    private $idUtilisateur;
    private $idTimbre;
    private $status_enchere;
    protected $erreurs = array();

      /**
   * Constructeur de la classe 
   * @param array $proprietes, tableau associatif des propriétés 
   */ 
    public function __construct($prix = null, $date = null, $enchere = null, $idUtilisateur = null, $idTimbre = null) {
        
        $this->prix = $prix;
        $this->date = $date;
        $this->enchere = $enchere;
        $this->idUtilisateur = $idUtilisateur;
        $this->idTimbre = $idTimbre;
}
public function getErreurs()              { return $this->erreurs; }
public function __get($property) {
    if (property_exists($this, $property)) {
        return $this->$property;
    }
}

public function __set($property, $value) {
    if (property_exists($this, $property)) {
        $this->$property = $value;
    }
    return $this;
}

public function setPrix($prix) {
    unset($this->erreurs['prix']);
    $prix = trim($prix);
    $regExp = '/^\d{1,5}$/';
    if (!preg_match($regExp, $prix)) {
      $this->erreurs['prix'] = 'Entrez un prix valide (5 chiffres max).';
    }
    $this->prix = $prix;
    return $this;
}

public function verifStatusEcnhere($status_enchere) {
    unset($this->erreurs['status_enchere']);
    if ($status_enchere == 1) {
      $this->erreurs['status_enchere'] = 'Une enchère est déjà en cours pour ce timbre.';
    }
    $this->status_enchere = $status_enchere;
    return $this;
}
// public function setDateExpiration($date) {
//     unset($this->erreurs['date']);
//     if ($date == "1j") {
//         $date_actuelle = date("Y-m-d");
//         $date = date("Y-m-d", strtotime("+1 day", strtotime($date_actuelle)));
//     }else if($date == "3j"){
//         $date_actuelle = date("Y-m-d");
//         $date = date("Y-m-d", strtotime("+3 day", strtotime($date_actuelle)));
//     }else if($date == "7j"){
//         $date_actuelle = date("Y-m-d");
//         $date = date("Y-m-d", strtotime("+7 day", strtotime($date_actuelle)));
//     }
//     $this->date = $date;
//     return $this;
// }
}