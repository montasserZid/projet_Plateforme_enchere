<?php

/**
 * Classe de l'entité Timbre
 *
 */
class Timbre{
    private $idTimbre;
    private $nomTimbre;
    private $description;

    private $reference;
    private $erreurs = array();

    
    

  /**
   * Constructeur de la classe 
   * @param array $proprietes, tableau associatif des propriétés 
   */ 
  public function __construct($idTimbre = null, $nomTimbre = null, $description = null, $reference = null) {
    
    $this->idTimbre = $idTimbre;
    $this->nomTimbre = $nomTimbre;
    $this->description = $description;
    $this->reference = $reference;
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

    // public function setNomTimbre($nomTimbre) {
    //     $regex = "(\w+\s){1,}\w+";
    //     if (is_string($nomTimbre)) {
    //         $this->nomTimbre = $nomTimbre;
    //     }
    // }

    public function setNomTimbre($nomTimbre) {
        unset($this->erreurs['nomTimbre']);
        $nomTimbre = trim($nomTimbre);
        $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
        if (!preg_match($regExp, $nomTimbre)) {
          $this->erreurs['nom'] = 'Au moins 2 caractères pour le nom.';
        }
        $this->nom = $nomTimbre;
        return $this;
       
      }

        public function setDescription($description) {
            unset($this->erreurs['description']);
            $description = trim($description);
            $regExp = '/.{20,}/';
            if (!preg_match($regExp, $description)) {
            $this->erreurs['description'] = 'Au moins 20 caractères pour la description.';
            }
            $this->description = $description;
            return $this;
         
        }

        public function setReference($reference) {
            unset($this->erreurs['reference']);
            $reference = trim($reference);
            $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,}){4}$/i';
            if (!preg_match($regExp, $reference)) {
            $this->erreurs['reference'] = 'Au moins 2 caractères pour la reference.';
            }
            $this->reference = $reference;
            return $this;
         
        }





}