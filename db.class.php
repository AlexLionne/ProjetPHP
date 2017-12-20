<?php
class db
{

  private $connection;
  private $db;
  private $user;
  private $consultation;


  public function connection($host,$user,$password,$db)
  {
    try{
      $this->db = $db;
      $this->connection = mysqli_connect($host,$user,$password,$db);
      //echo "<div style='background-color:#2ecc71'class='message focus' data-component='message'>Connection base de donnée ok<span class='close small'></span></div>";
      return $this->connection;
    }catch(Exception $e){
      echo "<div class='message error' data-component='message'>Connection base de donnée ko<span class='close small'></span></div>";
      exit();
    };
  }

  public function reconnect(){
    try{
      $log = new log;
      $user = 'root';
      $password = 'root';
      $db = 'iut-info-tlse3';
      $host = 'localhost';
      $port = 8889;

      $this->connection = mysqli_connect($host,$user,$password,$db);
      //echo "<div style='background-color:#2ecc71'class='message focus' data-component='message'>Connection base de donnée ok<span class='close small'></span></div>";
      return $this->connection;
    }catch(Exception $e){
      echo "<div class='message error' data-component='message'>Connection base de donnée ko<span class='close small'></span></div>";
      exit();
    };
  }

  public function ajouterUsager($user)
  {
    $this->reconnect();
    $sql = 'INSERT INTO usager(civilite,nom, prenom, adresse,date_naissance,lieu_naissance,num_secu,id_medecin)
    VALUES  ("'.$user->getCivilite().
    '", "'.$user->getNom().
    '", "'.$user->getPrenom().
    '", "'.$user->getAdresse().
    '", "'.$user->getDateNaissance().
    '", "'.$user->getLieuNaissance().
    '","'.$user->getNumeroSecu().
    '","'.$user->getIDMedecin().
    '");';

  //verification
    if (mysqli_query($this->connection, $sql)) {
        $date = getdate();
        $string ="Usager :".$user->toString()." enregistré avec succés";
        return $string;
      }else {
        $string ="erreur";
        mysqli_close($this->connection);
        return $string;
      }
}
public function ajouterMedecin($medecin)
{
  $this->reconnect();
  $sql = 'INSERT INTO medecin(civilite,nom, prenom)
  VALUES  ("'.$medecin->getCivilite().
  '", "'.$medecin->getNom().
  '", "'.$medecin->getPrenom().
  '");';

//verification
  if (mysqli_query($this->connection, $sql)) {
      $date = getdate();
      $string ="Medecin :".$medecin->toString()." enregistré avec succés";
      mysqli_close($this->connection);
      return $string;
    }else {
      $string ="erreur";
      mysqli_close($this->connection);
      return $string;
    }
}

  public function afficherUsagers(){
    $this->reconnect();
      $result = mysqli_query($this->connection,"select * from usager");
      echo "<table class='bordered striped'>";
      echo "<th>Uid</th><th>Civilite</th><th>Nom</th><th>Prenom</th><th>Adresse</th><th>Naissance</th><th>Lieu Naissance</th><th>Num Sécu</th><th>Médecin</th><th>Modifier</th>";
        while ($row = mysqli_fetch_array($result))
        {
          echo"<tr>";
          echo "<td>".$row['id_usager']."</td>";
          echo "<td>".$row['civilite']."</td>";
          echo "<td>".$row['nom']."</td>";
          echo "<td>".$row['prenom']."</td>";
          echo "<td>".$row['adresse']."</td>";
          echo "<td>".$row['date_naissance']."</td>";
          echo "<td>".$row['lieu_naissance']."</td>";
          echo "<td>".$row['num_secu']."</td>";
          echo "<td>".$row['id_medecin']."</td>";
          echo "<td>";
          echo"<form action='secretariat.php' method='post'>";
          echo "<input name='idU' value='".$row['id_usager']."'  req style='display:none;'/>";
          echo "<input name='usager' value='usager'  req style='display:none;'/>";
          echo "<input type='submit' class='button' value='edit' >";
          echo "</form>";
          echo "</td>";
          echo "</tr>";
          }
          echo "</table>";
          mysqli_close($this->connection);
}
public function afficherMedecins(){
  $this->reconnect();
    $result = mysqli_query($this->connection,"select * from medecin");
    echo "<table class='bordered striped'>";
    echo "<th>Mid</th><th>Civilite</th><th>Nom</th><th>Prenom</th><th>Modifier</th>";
      while ($row = mysqli_fetch_array($result))
      {
        echo"<tr>";
        echo "<td>".$row['id_medecin']."</td>";
        echo "<td>".$row['civilite']."</td>";
        echo "<td>".$row['nom']."</td>";
        echo "<td>".$row['prenom']."</td>";
        echo "<td>";
        echo"<form action='secretariat.php#medecin' method='post'>";
        echo "<input name='idM' value='".$row['id_medecin']."'  req style='display:none;'/>";
        echo "<input name='medecin' value='medecin' req style='display:none;'/>";
        echo "<input type='submit' class='button' value='edit' >";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
        }
        echo "</table>";
        mysqli_close($this->connection);
}

public function login($login,$password,$log){

     $this->reconnect();
     $sql = "SELECT id_user FROM user WHERE login= '$login' and password = '$password'";
     $result = mysqli_query($this->connection,$sql);
     $row = mysqli_fetch_row($result,MYSQLI_ASSOC);

     $count = mysqli_num_rows($result);
     if($count == 1) {
       $string ="Login : OK <br>";
       $log->updateLog($string);
       $_SESSION['user'] = $login;
       ob_start();
       header('Location:secretariat.php');
       exit();
       ob_end_flush();

     }else {
          echo "<div class='message error' data-component='message'>Erreur<span class='close small'></span></div>";
     }

     mysqli_close($this->connection);
}
public function search($body,$nom){
  $this->reconnect();
  $sql = "SELECT * FROM $body WHERE nom= '$nom'";
  $result = mysqli_query($this->connection,$sql);
  mysqli_close($this->connection);
  return $result;
}

public function searchById($id,$table,$log){
  $this->reconnect();
  if($table == "usager"){
    $key = "id_usager";
  }else if($table == "medecin"){
    $key = "id_medecin";
  }else if($table == "consultation"){
    $key = "id_consultation";
  }

  $sql = "SELECT * FROM $table WHERE $key = '$id'";
  $result = mysqli_query($this->connection,$sql);

    while ($row = mysqli_fetch_array($result))
    {
      if($key == "id_usager"){
        $this->user = new user($row['id_usager'],$row['civilite'],$row['nom'],$row['prenom'],$row['adresse'],$row['date_naissance'],$row['lieu_naissance'],$row['num_secu'],$row['id_medecin']);
      }else if($key == "id_medecin"){
        $this->user = new medecin($row['id_medecin'],$row['civilite'],$row['nom'],$row['prenom']);
      }

    }

    if($result){
      $string ="Usager trouvé ";
      $log->updateLog($string);
    }else{
      $string ="Erreur : Usager non rouvé  :".$this->user->getID();
      $log->updateLog($string);
  }


mysqli_close($this->connection);
return $this->user;

}

public function deleteUsager($uid,$log){
  $this->reconnect();
  $sql = "DELETE FROM usager WHERE id_usager = $uid";
  $result = mysqli_query($this->connection,$sql);

  if($result){

    $string ="Usager supprimé : UID :".$uid;
    $log->updateLog($string);
  }else{
    $string ="Erreur : Usager non supprmé : UID :".$uid;
    $log->updateLog($string);
  }
  mysqli_close($this->connection);
}

public function deleteMedecin($mid,$log){
  $this->reconnect();
  $sql = "DELETE FROM medecin WHERE id_medecin = $mid";
  $result = mysqli_query($this->connection,$sql);

  if($result){

    $string ="Medecin supprimé : MID :".$mid;
    $log->updateLog($string);
  }else{
    $string ="Erreur : Medecin non supprmé : MID :".$mid;
    $log->updateLog($string);
  }
  mysqli_close($this->connection);
}

public function updateUsager($user,$log){
  $this->reconnect();
  //var_dump($this->connection);
  $sql = "UPDATE usager SET
  civilite = '".$user->getCivilite().
  "',nom = '".$user->getNom().
  "',prenom = '".$user->getPrenom().
  "',adresse = '".$user->getAdresse().
  "',date_naissance = '".$user->getDateNaissance().
  "',lieu_naissance = '".$user->getLieuNaissance().
  "',num_secu = '".$user->getNumeroSecu().
  "',id_medecin = '".$user->getIDMedecin().
  "' WHERE id_usager = ".$user->getID().";";
  $result = mysqli_query($this->connection,$sql);
  if($result){
    $string ="Usager mis a jour : UID :".$user->getID();
    $log->updateLog($string);
  }else{
    $string ="Erreur : Usager non mis a jour : UID :".$user->getID();
    $log->updateLog($string);
  }

mysqli_close($this->connection);
}
public function updateMedecin($medecin,$log){
$this->reconnect();
  $sql = "UPDATE medecin SET
  civilite = '".$medecin->getCivilite().
  "',nom = '".$medecin->getNom().
  "',prenom = '".$medecin->getPrenom().
  "' WHERE id_medecin = ".$medecin->getID().";";
  $result = mysqli_query($this->connection,$sql);
  if($result){
    $string ="Medecin mis a jour : UID :".$medecin->getID();
    $log->updateLog($string);
  }else{
    $string ="Erreur : Medecin non mis a jour : UID :".$medecin->getID();
    $log->updateLog($string);
  }

mysqli_close($this->connection);
}

//Consultations
//C//Rechercher une consultation
public function searchConsultation($date){
  $this->reconnect();
  //on s'asure que la liste est bien vide

  $sql = "SELECT * FROM consultation WHERE date= '$date'";
  $result = mysqli_query($this->connection,$sql);

  while ($row = mysqli_fetch_array($result))
  {
      $this->consultation = new consultation($row['date'],$row['heure'],$row['duree'],$row['id_medecin'],$row['id_usager']);
  }
  mysqli_close($this->connection);
  return $this->consultation;
}
public function getNBConsultations($date){
  $this->reconnect();
  $sql = "SELECT COUNT(*) FROM consultation WHERE consultation.date = '$date'";
  $result = mysqli_query($this->connection,$sql);
  mysqli_close($this->connection);
  return $result;
}

public function getConsultations(){
  $this->reconnect();
    $sql = "SELECT * from consultation";
    $result = mysqli_query($this->connection,$sql);
    echo  "<h3>Consultations à venir</h3>";

    $today=date_create(time());
    echo "<h4>
    <a href='#nouveau' style='color:white;background-color:#2ecc71!important;'class='collapse-toggle'>"."Nouvelle"."<span style='color:white;margin-left:50%'class='caret down'></a></span></h4>
    <div class='collapse-box hide' id=nouveau>";
    echo
    "<form id='form' class='form' action='secretariat.php#consultations' method='post'>
    <input type='text' name='consultation'   value='consultation'  req style='display:none;'/>
    <div class='form-item'>
      <label>Date (aaaa-mm-jj)</label>
        <input type='text' name='date'   value='' required>
    </div>
    <div class='form-item'>
      <label>Heure</label>
        <input type='text' name='heure'  value='' required>
    </div>
    <div class='form-item'>
      <label>Duree</label>
        <input type='text' name='duree'   value='' required>
    </div>
    <div class='form-item'>
      <label>Medecin</label>
        <input type='text' name='idm'   value='' required>
    </div>
    <div class='form-item'>
      <label>Usager</label>
        <input type='text' name='idu'   value='' required>
    </div>
  <input type='submit' name='update' class='button' value='Enregistrer'>

  </form></div>";
    while ($row = mysqli_fetch_array($result))
    {


      $id = str_replace(":","",$row['id_usager'].$row['id_medecin'].$row['date'].$row['heure']);
      $id = str_replace("-","",$id);
      $date = date("j F Y", strtotime($row['date']));
      $today = date("j F Y",time());

      if(strtotime($date) >= strtotime($today)) {
      echo "<h4>

      <a href='#$id' class='collapse-toggle'>".$date." ".date("H:i",strtotime($row["heure"]))."</a></h4>
      <div class='collapse-box hide' id='$id'>";




      echo
      "<form id='form' class='form' action='secretariat.php#consultations' method='post'>
      <input type='text' name='consultation' value='consultation'  req style='display:none;'/>
      <div class='form-item'>
        <label>Date</label>
          <input type='text' name='date'  value='".$row['date']."' required>
      </div>
      <div class='form-item'>
        <label>Heure</label>
          <input type='text' name='heure'  value='".$row['heure']."' required>
      </div>
      <div class='form-item'>
        <label>Duree</label>
          <input type='text' name='duree'  value='".$row['duree']."' required>
      </div>
      <div class='form-item'>
        <label>Medecin</label>
          <input type='text' name='idm'  value='".$row['id_medecin']."' required>
      </div>
      <div class='form-item'>
        <label>Usager</label>
          <input type='text' name='idu'  value='".$row['id_usager']."' required>
      </div>


      <input type='submit' name='update' class='button' value='Enregistrer'>
      <input type='submit' name='delete' style='background-color:#ff3366;'class='button small round' value='Suprrimer'>

    </form></div>";
    }
}

  mysqli_close($this->connection);
}
public function getConsultationsByDate($date){
  $this->reconnect();
    $sql = "SELECT * from consultation WHERE date ='".$date."' ORDER BY date";
    $result = mysqli_query($this->connection,$sql);


    $today=date_create(time());


    $consultations = array();
    while ($row = mysqli_fetch_array($result))
    {
      array_push($consultations,new consultation($row['date'],$row['heure'],$row['duree'],$row['id_medecin'],$row['id_usager']));
    }
    return $consultations;
  mysqli_close($this->connection);
}
public function editerConsultation($consultation,$log){
$this->reconnect();

  $sql = "UPDATE consultation SET
  date = '".$consultation->getDate().
  "',heure = '".$consultation->getHeure().
  "',duree= '".$consultation->getDuree().
  "',id_usager = '".$consultation->getIDUsager().
  "',id_medecin = '".$consultation->getIDMedecin().
  "' WHERE date = '".$consultation->getDate().
  "' AND id_usager= '".$consultation->getIDUsager().
  "' AND id_medecin= '".$consultation->getIDMedecin()."';";
var_dump($sql);
  $result = mysqli_query($this->connection,$sql);
  if($result){
    $string ="Consultation miss àjour ";
    $log->updateLog($string);
  }else{
    $string ="Erreur : Consultation non mise à jour";
    $log->updateLog($string);
  }

mysqli_close($this->connection);
}

public function ajouterConsultation($consultation,$log)
{
  $this->reconnect();
  /*
  1 : verifier qu'il n'y a pas de consultation a cette emplacement ( date + heure )
  2 : verrifier que la duree est valide et ne déborde pas sur un autre enregistrement
  3 : ajouter la consultation
  */

  //ini a 0 pour la duree initaile

  for ($i = 0; $i <=  $consultation->getDuree() ; $i++) {
    $duree = $consultation->getDuree() + $i;

    $sql = "select count(*) from consultation where date = '".$consultation->getDate().
    "' AND duree= '".$duree.
    "' AND id_usager= '".$consultation->getIDUsager().
    "' AND id_medecin= '".$consultation->getIDMedecin()."';";

    $result = mysqli_query($this->connection, $sql);

    if ($result) {
        $string ="Consultation :".strtotime($consultation->getDate()+$duree)." existe";
        var_dump($string);
      }else {
        $string ="Erreur, ".strtotime($consultation->getDate()+$duree)." n'existe pas";
        var_dump($string);
      }

}
mysqli_close($this->connection);

}

public function supprimerConsultation($consultation,$log){
  $this->reconnect();

  $date = $consultation->getDate();
  $heure = $consultation->getHeure();
  $usager = $consultation->getIDUsager();
  $medecin = $consultation->getIDMedecin();

  $sql = "DELETE FROM consultation WHERE date = '$date' and heure = '$heure' and id_medecin = '$medecin' and id_usager = '$usager'";
  var_dump($sql);
  $result = mysqli_query($this->connection,$sql);

  if($result){

    $string ="Consultation supprimée";
    $log->updateLog($string);
  }else{
    $string ="Erreur : Consultation non supprmé";
    $log->updateLog($string);
  }
  mysqli_close($this->connection);
}



//todo
public function getMedecinByID($id){

}
public function getBDD(){
  return $this->db;
}





}
