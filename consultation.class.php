<?php
class consultation
{

  private $date;
  private $heure;
  private $duree;
  private $id_medecin;
  private $id_usager;





  function __construct($date,$heure, $duree, $id_medecin,$id_usager)
      {
          $this->date = $date;
          $this->heure = $heure;
          $this->duree = $duree;
          $this->id_medecin = $id_medecin;
          $this->id_usager = $id_usager;

      }

  public function getDate(){
    return $this->date;
  }
  public function getDuree(){
    return $this->duree;
  }
  public function getHeure(){
    return $this->heure;
  }
  public function getIDMedecin(){
    return $this->id_medecin;
  }
  public function getIDUsager(){
    return $this->id_usager;
  }

  public function toString()
  {
    return $this->date
    . ' '. $this->heure
    . ' '. $this->duree
    . ' '. $this->id_medecin
    . ' '. $this->id_usager;
  }


}
