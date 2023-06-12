<?php

/**
 * Classe des requêtes PDO 
 *
 */
class RequetesPDO {

  protected $sql;
  protected $params;

  const UNE_SEULE_LIGNE = true;



  /**
   * Requête $sql SELECT de récupération d'une ou plusieurs lignes
   * @param array   $params paramètres de la requête préparée
   * @param boolean $uneSeuleLigne true si une seule ligne à récupérer false sinon 
   * @return array
   */ 
  public function getLignes($params = [], $uneSeuleLigne = false) {
    $sPDO = SingletonPDO::getInstance();
    $oPDOStatement = $sPDO->prepare($this->sql);
    $nomsParams = array_keys($params);
    // var_dump($oPDOStatement);
    // var_dump($params);
    // die();
    // foreach ($nomsParams as $nomParam) {
    //  $oPDOStatement->bindParam(':'.$nomParam, $params[$nomParam]);
    // }
      // var_dump($params);
    $oPDOStatement->execute($params);


    $result = $uneSeuleLigne ? $oPDOStatement->fetch(PDO::FETCH_ASSOC) : $oPDOStatement->fetchAll(PDO::FETCH_ASSOC);

    return $result;
  }
}

    /*
    UPDATE membre SET nom = :nom,
      prenom = :prenom,
      email = :email,
      password = :password,
      adresse = :adresse,
      telephone = :telephone 
    WHERE idMembre = :idMembre
    
    Array
(
    [:nom] => zzzz
    [:prenom] => zzzz
    [:email] => zz@gmail.com
    [:password] => 
    [:adresse] => 
    [:telephone] => 
    [:idMembre] => 
)
Array
(
    [0] => :nom
    [1] => :prenom
    [2] => :email
    [3] => :password
    [4] => :adresse
    [5] => :telephone
    [6] => :idMembre
)

nomParam = :nom => params[:nom]
    echo $this->sql;
    die();
    */