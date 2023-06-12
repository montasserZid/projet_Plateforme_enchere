<?php

/**
 * Classe de l'entité Mise
 *
 */
class Mise
{
    // $idMembre, $idTimbre, $montant 
    private $idMembre;

    private $utilisateurConnecter;
    private $idTimbre;
    private $montant;
    private $erreurs = array();

    public function __construct($idMembre = null, $idTimbre = null, $montant = null , $utilisateurConnecter = null)
    {
        $this->idMembre = $idMembre;
        $this->idTimbre = $idTimbre;
        $this->montant = $montant;
        $this->utilisateurConnecter = $utilisateurConnecter;
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

    public function setMise($montant,$PrixActuelle,$idMembre,$utilisateurConnecter) {
        unset($this->erreurs['montant']);
        // $montant = trim($montant);
        $regExp = '/^\d{0,3}$/';
        $montant= intval($montant);
        if($utilisateurConnecter == $idMembre){
            $this->erreurs['montant'] = 'Vous ne pouvez pas miser sur votre propre timbre.';
        }else if (!preg_match($regExp, $montant)) {
            $this->erreurs['montant'] = 'Entrez un montant valide max 3 chiffres (nombre entier seulement).';
        }else if(($montant - $PrixActuelle) < 5){
            $this->erreurs['montant'] = 'Entrez un montant supérieur au prix actuel (minimum 5$).';
        }
        $this->montant = $montant;
        return $this;
    }
}