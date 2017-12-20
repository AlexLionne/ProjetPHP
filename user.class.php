<?php
class user
{
  private $id;
  private $civilite;
  private $nom;
  private $prenom;
  private $adresse;
  private $date_naissance;
  private $lieu_naissance;
  private $num_secu;
  private $id_medecin;




  function __construct($id,$civilite,$nom, $prenom, $adresse,$date_naissance,$lieu_naissance,$num_secu,$id_medecin)
      {
          $this->id = $id;
          $this->civilite = $civilite;
          $this->nom = $nom;
          $this->prenom = $prenom;
          $this->adresse = $adresse;
          $this->date_naissance = $date_naissance;
          $this->lieu_naissance = $lieu_naissance;
          $this->num_secu = $num_secu;
          $this->id_medecin = $id_medecin;


      }

  public function getID(){
    return $this->id;
  }
  public function getCivilite(){
    return $this->civilite;
  }
  public function getNom(){
    return $this->nom;
  }
  public function getPrenom(){
    return $this->prenom;
  }
  public function getAdresse(){
    return $this->adresse;
  }
  public function getDateNaissance(){
    return $this->date_naissance;
  }
  public function getLieuNaissance(){
    return $this->lieu_naissance;
  }
  public function getNumeroSecu(){
    return $this->num_secu;
  }
  public function getIDMedecin(){
    return $this->id_medecin;
  }

  public function toString()
  {
    return $this->id
    . ' '. $this->civilite
    . ' '. $this->nom
    . ' '. $this->prenom
    . ' '. $this->adresse
    . ' '. $this->date_naissance
    . ' '. $this->lieu_naissance
    . ' '. $this->num_secu
    . ' '. $this->id_medecin;
  }


}
