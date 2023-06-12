<?php

/**
 * Classe des requêtes SQL
 *
 */
class RequetesSQL extends RequetesPDO {
    
  /**
   * Récupération d'une liste de voitures filtrée par des critères de sélection 
   * @param array tableau associatif contenant les critères voiture_prix maximal et voiture_km maximal  
   * @return array tableau associatif de lignes de la table voiture
   */ 
  // public function rechercher($criteres)
  // {
  //   $this->sql = '
  //   SELECT voiture_id, voiture_marque, voiture_modele, voiture_annee, voiture_km, voiture_prix
  //   FROM voiture
  //   WHERE voiture_prix <= :voiture_prix AND voiture_km <= :voiture_km
  //   ORDER BY voiture_annee ASC
  //   ';
  //   return $this->getLignes($criteres);
  // }
  
  /**
   * Statistiques selon le prix par tranche de 5000$ 
   * @return array tableau associatif avec par exemple les clés inf5000,sup5001inf10000,... ,sup25001inf30000 et sup30001  
   */ 
  // public function statistiques()
  // {
  //   // ===> à compléter
  //   $this->sql = 'SELECT voiture_id , voiture_prix
  //   FROM voiture';
  //   return $this->getLignes();
  // }

  public function connexion($email, $password)
  {
    // var_dump($email, $password);
    // die();
      $this->sql = "SELECT * FROM membre WHERE email = :email AND password = :password";
      $this->params = array(
          ":email" => $email,
          ":password" => $password
      );
      return $this->getLignes(['email'=>$email,'password'=>$password], RequetesPDO::UNE_SEULE_LIGNE);
  }

  public function inscription($nom, $prenom, $email, $password , $adresse , $telephone)
  {
    $this->sql = "INSERT INTO membre (nom, prenom, email, password , adresse , telephone, Role_idRole) VALUES (:nom, :prenom, :email, :password , :adresse , :telephone , 2)";
    $this->params = array(
        ":nom" => $nom,
        ":prenom" => $prenom,
        ":email" => $email,
        ":password" => $password,
        ":adresse" => $adresse,
        ":telephone" => $telephone
    );
    return $this->getLignes(['nom'=>$nom,'prenom'=>$prenom,'email'=>$email,'password'=>$password,'adresse'=>$adresse,'telephone'=>$telephone], RequetesPDO::UNE_SEULE_LIGNE);

  }
  
  public function verifEmail($email) {
    $this->sql = "SELECT * FROM membre WHERE email = :email";
    $this->params = array(":email" => $email);
    $result = $this->getLignes(['email'=>$email], RequetesPDO::UNE_SEULE_LIGNE);
 
    return $result;
}
public function getAllMembre() {
  $this->sql = "SELECT idMembre, CONCAT(nom, ' ', prenom) AS nom_complet FROM membre";
  $this->params = array();
  $result = $this->getLignes();
  return $result;
}
public function getAllTimbres() {
  $this->sql = "SELECT idTimbre, nom FROM timbre";
  $this->params = array();
  $result = $this->getLignes();
  return $result;
}
public function getAllImages() {
  $this->sql = "SELECT * FROM `image`";
  $this->params = array();
  $result = $this->getLignes();
  return $result;
}
public function getImageParIdTimbre($idTimbre) {
  $this->sql = "SELECT * FROM `image` WHERE Timbre_idTimbre = :idTimbre";
  $this->params = array(":idTimbre" => $idTimbre);
  $result = $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
  return $result;
}
public function modifierImageParIdTimbre($chemin_image_variable , $idTimbre) {
  $this->sql = "UPDATE `image` SET chemin_image = :chemin_image_variable WHERE Timbre_idTimbre = :idTimbre";
  $this->params = array(
    ":chemin_image_variable" => $chemin_image_variable,
    ":idTimbre" => $idTimbre
  );
  $result = $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
  return $result;
}
public function supprimerImageParIdTimbre($idTimbre)	{
  $this->sql = "DELETE FROM `image` WHERE Timbre_idTimbre = :idTimbre";
  $this->params = array(":idTimbre" => $idTimbre);
  $result = $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
  return $result;
}
public function getUtilisateurs() {
  $this->sql = "SELECT * FROM membre";
  $this->params = array();
  $result = $this->getLignes();
  return $result;
}

public function getUtilisateurParId($idMembre) {
  $this->sql = "SELECT *  FROM membre WHERE idMembre = :idMembre";
  $this->params = array(":idMembre" => $idMembre);
  $result = $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
  return $result;
}

public function modifierUtilisateur($utilisateur){
  $this->sql = "UPDATE membre SET nom = :nom, prenom = :prenom, email = :email WHERE idMembre = :idMembre";
  $this->params = array(
      ":nom" => $utilisateur['nom'],
      ":prenom" => $utilisateur['prenom'],
      ":email" => $utilisateur['email'],
      // ":password" => $utilisateur['password'],
      // ":adresse" => $utilisateur['adresse'],
      // ":telephone" => $utilisateur['telephone'],
      ":idMembre" => $utilisateur['idMembre']
  );
  return $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
}
public function ajouterUtilisateurAdmin($utilisateur){
$this->sql = "INSERT INTO membre (nom, prenom, email, password ,adresse, telephone, Role_idRole) VALUES (:nom, :prenom, :email, :password ,:adresse, :telephone,1)";

$this->params = array(
    ":nom" => $utilisateur['nom'],
    ":prenom" => $utilisateur['prenom'],
    ":email" => $utilisateur['email'],
    ":password" => $utilisateur['password'],
    ":adresse" => $utilisateur['adresse'],
    ":telephone" => $utilisateur['telephone'],
    // ":idMembre" => $utilisateur['idMembre']
);
// var_dump($this->params);
// die();
return $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
}

public function supprimerUtilisateur($idMembre){
  $this->sql = "DELETE FROM membre WHERE idMembre = :idMembre";
  $this->params = array(":idMembre" => $idMembre);
  return $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
}
public function getTimbres(){
  $this->sql = "SELECT * FROM timbre";
  $this->params = array();
  $result = $this->getLignes();
  return $result;
}

public function getTimbreParId($idTimbre) {
  $this->sql = "SELECT *  FROM timbre WHERE idTimbre = :idTimbre";
  $this->params = array(":idTimbre" => $idTimbre);
  $result = $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
  return $result;
}

public function getTimbreParMembre($idMembre) {
  $this->sql = "SELECT *  FROM timbre WHERE membre_idMembre = :membre_idMembre";
  $this->params = array(":membre_idMembre" => $idMembre);
  $result = $this->getLignes($this->params);
  return $result;
}

public function getTimbreParUtilisateur($idUtilisateur) {
  $this->sql = "SELECT *  FROM timbre WHERE membre_idMembre = :membre_idMembre";
  $this->params = array(":membre_idMembre" => $idUtilisateur);
  $result = $this->getLignes($this->params);
  return $result;
}

public function getReference()
{
  $this->sql = "SELECT idTimbre FROM timbre ORDER BY idTimbre DESC LIMIT 1";
  $this->params = array();
  $result = $this->getLignes();
  return $result;
}
public function ajouterTimbreAdmin($nom, $description,$reference,$etat,$idUtilisateur){
  $this->sql = "INSERT INTO timbre (nom, description, reference, etat, membre_idMembre) VALUES (:nom, :description, :reference, :etat, :membre_idMembre)";

  $this->params = array(
      ":nom" => $nom,
      ":description" => $description,
      ":reference" => $reference,
      ":etat" => $etat,
      ":membre_idMembre" => $idUtilisateur,
  );
  // var_dump($timbre);
  // die();
  return $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
}

public function modifierTimbre($nom, $description,$etat,$idTimbre){
  $this->sql = "UPDATE timbre SET nom = :nom, description = :description, etat = :etat WHERE idTimbre = :idTimbre";
  $this->params = array(
      ":nom" => $nom,
      ":description" => $description,
      // ":reference" => $timbre['reference'],
      ":etat" => $etat,
      ":idTimbre" => $idTimbre
  );
  return $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);

}
public function supprimerTimbre($idTimbre){
  $this->sql = "DELETE FROM timbre WHERE idTimbre = :idTimbre";
  $this->params = array(":idTimbre" => $idTimbre);
  return $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
}

public function getEnchere(){
  $this->sql = "SELECT * FROM enchere";
  $this->params = array();
  $result = $this->getLignes();
  return $result;
}
public function getActiveEnchere(){
  $this->sql = "SELECT * FROM enchere WHERE Status_idStatus = 1";
  $this->params = array();
  $result = $this->getLignes();
  return $result;
}
public function getStatusParId($idStatus){
  $this->sql = "SELECT * FROM status WHERE idStatus = :idStatus";
  $this->params = array(":idStatus" => $idStatus);
  $result = $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
  return $result;
}
public function getEnchereParTimbre($idTimbre)
{
  $this->sql = "SELECT * FROM enchere WHERE timbre_idTimbre = :timbre_idTimbre";
  $this->params = array(":timbre_idTimbre" => $idTimbre);
  $result = $this->getLignes($this->params);
  return $result;
}

public function getEnchereParMembre($idMembre)
{
  $this->sql = "SELECT * FROM enchere WHERE membre_idMembre = :membre_idMembre";
  $this->params = array(":membre_idMembre" => $idMembre);
  $result = $this->getLignes($this->params);
  return $result;
}
public function supprimerEnchereParIdTimbre($idTimbre){
  $this->sql = "DELETE FROM `enchere` WHERE Timbre_idTimbre = :idTimbre";
  $this->params = array(":idTimbre" => $idTimbre);
  $result = $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
  return $result;
}
public function ajouterEchere($idTimbre, $idUtilisateur, $date,$prix, $status_enchere)
{
  $this->sql = "INSERT INTO enchere (timbre_idTimbre, membre_idMembre,dateFin ,prix ,status_idStatus) VALUES (:timbre_idTimbre, :membre_idMembre,:dateFin ,:prix, :status_idStatus)";
  $this->params = array(
      ":timbre_idTimbre" => $idTimbre,
      ":membre_idMembre" => $idUtilisateur,
      ":prix" => $prix,
      ":dateFin" => $date,
      ":status_idStatus" => $status_enchere
  );
  return $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
}
public function modifierEnchereParIdTimbre($idTimbre, $prix, $dateFin)
{
  $this->sql = "UPDATE enchere SET prix = :prix, dateFin = :dateFin WHERE timbre_idTimbre = :timbre_idTimbre";
  $this->params = array(
      ":timbre_idTimbre" => $idTimbre,
      ":prix" => $prix,
      ":dateFin" => $dateFin
  );
  return $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
}
public function supprimerEnchere($idTimbre){
  $this->sql = "DELETE FROM enchere WHERE timbre_idTimbre = :timbre_idTimbre";
  $this->params = array(":timbre_idTimbre" => $idTimbre);
  return $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
}
public function ajouterImageParIdTimbre($chemin_image_variable , $idTimbreImage){
  $this->sql = "INSERT INTO image (chemin_image, timbre_idTimbre) VALUES (:chemin_image, :timbre_idTimbre)";
  $this->params = array(
      ":chemin_image" => $chemin_image_variable,
      ":timbre_idTimbre" => $idTimbreImage
  );
  return $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
}
public function getMiseParIdMembre($idMembre){
  $this->sql = "SELECT * FROM mise WHERE idOffrant = :Membre_idMembre";
  $this->params = array(":Membre_idMembre" => $idMembre);
  $result = $this->getLignes($this->params);
  return $result;
}
public function ajouterMise($montant,$idTimbre,$utilisateurConnecter,$idMembre){
  // INSERT INTO `mise` (`mise`, `idOffrant`, `Membre_idMembre`, `Enchere_Membre_idMembre`, `Enchere_Timbre_idTimbre`) VALUES (528, 20, 28, 28, 47); 
  $this->sql = "INSERT INTO mise (mise, idOffrant, Membre_idMembre, Enchere_Membre_idMembre, Enchere_Timbre_idTimbre 	) VALUES (:mise, :idOffrant, :Membre_idMembre, :Enchere_Membre_idMembre, :Enchere_Timbre_idTimbre)";
  $this->params = array(
      ":mise" => $montant,
      ":idOffrant" => $utilisateurConnecter,
      ":Membre_idMembre" => $idMembre,
      ":Enchere_Membre_idMembre" => $idMembre,
      ":Enchere_Timbre_idTimbre" => $idTimbre
  );
  return $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
}
public function ajusterPrixEnchere($idTimbre,$montant){
  $this->sql = "UPDATE enchere SET prix = :montant WHERE Timbre_idTimbre = :idTimbre;
  ";
  $this->params = array(
      ":idTimbre" => $idTimbre,
      ":montant" => $montant
  );
  return $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
}
public function ajouterFavoris($idMembre,$idTimbre){

  $this->sql = "INSERT INTO `favoris` (`idFavoris`, `Membre_idMembre`, `Enchere_idTimbre`) VALUES (NULL, :Membre, :Enchere)";
  $this->params = array(
    ":Membre" => $idMembre,
      ":Enchere" => $idTimbre
      
  );
  return $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
}
public function effacerFavoris($idMembre,$idTimbre){
  $this->sql = "DELETE FROM `favoris` WHERE `favoris`.`Membre_idMembre` = :Membre AND `favoris`.`Enchere_idTimbre` = :Enchere";
  $this->params = array(
    ":Membre" => $idMembre,
      ":Enchere" => $idTimbre
      
  );
  return $this->getLignes($this->params, RequetesPDO::UNE_SEULE_LIGNE);
}
public function getFavorisParId($utilisateurConnecter){
  $this->sql = "SELECT * FROM favoris WHERE Membre_idMembre = :Membre_idMembre";
  $this->params = array(":Membre_idMembre" => $utilisateurConnecter);
  $result = $this->getLignes($this->params);
  
  return $result;
}
}
