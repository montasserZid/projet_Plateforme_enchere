<?php

/**
 * Classe de l'entité Utilisateur
 *
 */
class Utilisateur extends Entite
{
  protected $utilisateur_id = 0;
  protected $utilisateur_nom;
  protected $utilisateur_prenom;
  protected $utilisateur_courriel;
  protected $utilisateur_adresse;
  protected $utilisateur_telephone;
  protected $utilisateur_mdp;
  protected $utilisateur_profil;

  const PROFIL_ADMINISTRATEUR = "administrateur";
  const PROFIL_EDITEUR        = "editeur";
  const PROFIL_CORRECTEUR     = "correcteur";
  const PROFIL_CLIENT         = "client";
  
  const ERR_COURRIEL_EXISTANT = "Courriel déjà utilisé.";

  // tous les getters
  public function getUtilisateur_id()       { return $this->utilisateur_id; }
  public function getUtilisateur_nom()      { return $this->utilisateur_nom; }
  public function getUtilisateur_prenom()   { return $this->utilisateur_prenom; }
  public function getUtilisateur_courriel() { return $this->utilisateur_courriel; }
  public function getUtilisateur_telephone()  { return $this->utilisateur_telephone; }
  public function getUtilisateur_mdp()      { return $this->utilisateur_mdp; }
  // public function getUtilisateur_profil()   { return $this->utilisateur_profil; }
  public function getErreurs()              { return $this->erreurs; }
  
  /**
   * Mutateur de la propriété utilisateur_id 
   * @param int $utilisateur_id
   * @return $this
   */    
  public function setIdMembre($utilisateur_id) {
    unset($this->erreurs['idMembre']);
    $regExp = '/^\d+$/';
    if (!preg_match($regExp, $utilisateur_id)) {
      $this->erreurs['idMe'] = 'Numéro incorrect.';
    }
    $this->utilisateur_id = $utilisateur_id;
    return $this;
  }    

  /**
   * Mutateur de la propriété utilisateur_nom 
   * @param string $utilisateur_nom
   * @return $this
   */    
  public function setUtilisateur_nom($utilisateur_nom) {
    unset($this->erreurs['nom']);
    $utilisateur_nom = trim($utilisateur_nom);
    $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
    if (!preg_match($regExp, $utilisateur_nom)) {
      $this->erreurs['nom'] = 'Au moins 2 caractères alphabétiques pour chaque mot.';
    }
    $this->utilisateur_nom = $utilisateur_nom;
    return $this;
  }

  /**
   * Mutateur de la propriété utilisateur_prenom 
   * @param string $utilisateur_prenom
   * @return $this
   */    
  public function setUtilisateur_prenom($utilisateur_prenom) {
    unset($this->erreurs['prenom']);
    $utilisateur_prenom = trim($utilisateur_prenom);
    $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
    if (!preg_match($regExp, $utilisateur_prenom)) {
      $this->erreurs['prenom'] = 'Au moins 2 caractères alphabétiques pour chaque mot.';
    }
    $this->utilisateur_prenom = $utilisateur_prenom;
    return $this;
  }

  /**
   * Mutateur de la propriété utilisateur_courriel
   * @param string $utilisateur_courriel
   * @return $this
   */    
  public function setUtilisateur_courriel($utilisateur_courriel) {
    unset($this->erreurs['email']);
    $utilisateur_courriel = trim(strtolower($utilisateur_courriel));
    if (!filter_var($utilisateur_courriel, FILTER_VALIDATE_EMAIL)) {
      $this->erreurs['email'] = 'Format incorrect.';
    }
    $this->utilisateur_courriel = $utilisateur_courriel;
    return $this;
  }

  /**
   * Mutateur de la propriété utilisateur_profil
   * @param string $utilisateur_profil
   * @return $this
   */    
  public function setUtilisateur_profil($utilisateur_profil) {
    unset($this->erreurs['utilisateur_profil']);
    if ($utilisateur_profil !== self::PROFIL_ADMINISTRATEUR &&
        $utilisateur_profil !== self::PROFIL_EDITEUR        &&
        $utilisateur_profil !== self::PROFIL_CORRECTEUR     &&
        $utilisateur_profil !== self::PROFIL_CLIENT) {
      $this->erreurs['utilisateur_profil'] = 'Profil incorrect.';
    }
    $this->utilisateur_profil = $utilisateur_profil;
    return $this;
  }

  /**
   * Controler l'existence du courriel 
   */    
  // public function courrielExiste() {
  //   if (!isset($this->erreurs['utilisateur_courriel'])) {
  //     $retour = (new RequetesSQL)->controlerCourriel(['utilisateur_courriel' => $this->utilisateur_courriel,
  //                                                     'utilisateur_id'       => $this->utilisateur_id
  //                                                    ]);
  //     if ($retour) $this->erreurs['utilisateur_courriel'] = self::ERR_COURRIEL_EXISTANT;
  //   }
  // }
  /**
   * Mutateur de la propriété utilisateur_nom 
   * @param string $utilisateur_mdp
   * @return $this
   */    
  public function setUtilisateur_mot_de_passe($utilisateur_mdp) {
    unset($this->erreurs['utilisateur_mdp']);
    $utilisateur_mdp = trim($utilisateur_mdp);
    $regExp = '/^(?=.*[!%:=])(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{10,}$/';
    if (!preg_match($regExp, $utilisateur_mdp)) {
      $this->erreurs['utilisateur_mdp'] = 'Au moins 10 caracteres un cara parmi !%:=, une majuscule , une minuscule et un chiffre';
    }
    $this->utilisateur_mdp = $utilisateur_mdp;
    return $this;
  }
  public function  setUtilisateur_adresse($utilisateur_adresse) {
    unset($this->erreurs['utilisateur_adresse']);
    $utilisateur_adresse = trim($utilisateur_adresse);
    $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
    if (!preg_match($regExp, $utilisateur_adresse)) {
      $this->erreurs['utilisateur_adresse'] = 'Ce format est invalide (adresse).';
    }
    $this->utilisateur_adresse = $utilisateur_adresse;
    return $this;
  }
  public function setUtilisateur_telephone($utilisateur_telephone){
    unset($this->erreurs['utilisateur_telephone']);
    $utilisateur_telephone = trim($utilisateur_telephone);
    $regExp = '/^[0-9]{10}$/';
    if (!preg_match($regExp, $utilisateur_telephone)) {
      $this->erreurs['utilisateur_telephone'] = 'Le numero de telephone doit contenir exactement 10 chiffres.';
    }
    $this->utilisateur_telephone = $utilisateur_telephone;
    return $this;
  }
}