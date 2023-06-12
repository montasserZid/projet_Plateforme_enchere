<?php

/**
 * Classe de l'entité Membre
 *
 */
class Membre
{


  private $idMembre;
  private $nom;
  private $prenom;
  private $email;
  private $password;

  private $erreurs = array();

  /**
   * Constructeur de la classe 
   * @param array $proprietes, tableau associatif des propriétés 
   */ 
  // public function __construct($proprietes = []) {
  //   foreach ($proprietes as $nom_propriete => $val_propriete) {
  //     $this->__set($nom_propriete, $val_propriete);
  //   } 
  // }
  public function __construct($idMembre = null, $nom = null, $prenom = null, $email = null, $password = null , $telephone = null) {
   
    $this->idMembre = $idMembre;
    $this->nom = $nom;
    $this->prenom = $prenom;
    $this->email = $email;
    $this->password = $password;
    $this->telephone = $telephone;
}

public function getErreurs()              { return $this->erreurs; }
  /**
   * Accesseur magique d'une propriété de l'objet
   * @param string $prop, nom de la propriété
   * @return property value
   */     
  // public function __get($prop) {
  //   return $this->$prop;
  // }
  public function __get($property) {
    if (property_exists($this, $property)) {
        return $this->$property;
    }
}

  /**
   * Mutateur magique qui exécute le mutateur de la propriété en paramètre 
   * @param string $prop, nom de la propriété
   * @param $val, contenu de la propriété à mettre à jour
   */   
  // public function __set($prop, $val) {
  //   $setProperty = 'set'.ucfirst($prop);
  //   $this->$setProperty($val);
  // }
  public function __set($property, $value) {
    if (property_exists($this, $property)) {
        $this->$property = $value;
    }
    return $this;
}

  public function setEmail($email) {
    unset($this->erreurs['email']);
    $email = trim(strtolower($email));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->erreurs['email'] = 'Format incorrect.';
    }
    $this->email = $email;
    return $this;
    
}

public function setPassword($password) {
  unset($this->erreurs['password']);
  $password = trim($password);
  $regExp = '/^(?=.*[!%:=])(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{10,}$/';
  if (!preg_match($regExp, $password)) {
    $this->erreurs['password'] = 'Au moins 10 caracteres un cara parmi !%:=, une majuscule , une minuscule et un chiffre';
  }
  $this->$password = $password;
  return $this;
 
}
public function setNom($nom) {
  unset($this->erreurs['nom']);
  $nom = trim($nom);
  $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
  if (!preg_match($regExp, $nom)) {
    $this->erreurs['nom'] = 'Au moins 2 caractères pour le nom.';
  }
  $this->nom = $nom;
  return $this;
 
}
public function setPrenom($prenom) {
  unset($this->erreurs['prenom']);
  $prenom = trim($prenom);
  $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
  if (!preg_match($regExp, $prenom)) {
    $this->erreurs['prenom'] = 'Au moins 2 caractères alphabétiques pour le prenom.';
  }
  $this->prenom = $prenom;
  return $this;
}
public function setTelephone($telephone){
  unset($this->erreurs['telephone']);
  $telephone = trim($telephone);
  $regExp = '/^[0-9]{10}$/';
  if (!preg_match($regExp, $telephone)) {
    $this->erreurs['telephone'] = 'Le numero de telephone doit contenir exactement 10 chiffres.';
  }
  $this->telephone = $telephone;
  return $this;
}
public function  setAdresse($adresse) {
  unset($this->erreurs['adresse']);
  $adresse = trim($adresse);
  $regExp = '/^[a-zA-Z0-9\s\',.\-]+$/';
  if (!preg_match($regExp, $adresse)) {
    $this->erreurs['adresse'] = 'Ce format est invalide (adresse).';
  }
  $this->utilisateur_adresse = $adresse;
  return $this;
}


public function verifierExistence() {
  // if (!$this->oRequetesPDO->estConnecte()) {
  //     // La connexion à la base de données n'est pas établie
  //     return false;
  // }
    // var_dump($this->email, $this->password);
    // die();
  // $sql = "SELECT * FROM Membre WHERE email = :email AND password = :password";
  // $params = array(':email' => $this->email, ':password' => $this->password);
  // $resultat = $this->oRequetesSQL->getLignes($sql, $params, RequetesPDO::UNE_SEULE_LIGNE);
  $resultat = $this->oRequetesSQL->connexion($this->email, $this->password);
  return $resultat;
}

}