<?php
class medecin
{
  private $id;
  private $civilite;
  private $nom;
  private $prenom;


  function __construct($id,$civilite,$nom, $prenom)
      {
          $this->id = $id;
          $this->civilite = $civilite;
          $this->nom = $nom;
          $this->prenom = $prenom;

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


  public function toString()
  {
    return $this->id
    . ' '. $this->civilite
    . ' '. $this->nom
    . ' '. $this->prenom;
  }


}
