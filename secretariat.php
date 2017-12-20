<?php
require("user.class.php");
require("medecin.class.php");
require("log.class.php");
require("event.class.php");
require("consultation.class.php");
include('session.php');
require("config.php");

$log = new log;
$event = new event("ajouter");

//la requete concerne un usager
if(isset($_POST['usager'])){
//recherche d'un usager
   if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
     $resultU = $connection->search("usager",$_POST["search"]);
  }
//suppression d'un usager
  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete']) && isset($_POST['uid'])) {
     $connection->deleteUsager($_POST['uid'],$log);
     $event->registerEvent("ajouter");
  }
  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idU'])) {
    $id = $_POST['idU'];
    $user = $connection->searchById($id,"usager",$log);
    $event->registerEvent("edition");
  }
//actions sur les champs de saisie
  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {

    if($event->getEvent() == "edition"){
      //var_dump($_POST['civilite']);
      $userB = new user($_POST['uid'],$_POST['civilite'],$_POST['nom'],$_POST['prenom'],$_POST['adresse'],$_POST['date_naissance'],$_POST['lieu_naissance'],$_POST['num_secu'],$_POST['id_medecin']);
      $connection->updateUsager($userB,$log);
      //remise a 'ajouter'
      $event->registerEvent("ajouter");
    }else if($event->getEvent() == "ajouter" ){
      $userB = new user("0",$_POST['civilite'],$_POST['nom'],$_POST['prenom'],$_POST['adresse'],$_POST['date_naissance'],$_POST['lieu_naissance'],$_POST['num_secu'],$_POST['id_medecin']);
      $log->updateLog($connection->ajouterUsager($userB));

    }

//la requete concerne un medecin
}
}else if(isset($_POST['medecin'])){
  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $resultM = $connection->search("medecin",$_POST["search"]);
  }
  //suppression d'un usager
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete']) && isset($_POST['mid'])) {
       $connection->deleteMedecin($_POST['mid'],$log);
       $event->registerEvent("ajouter");
    }

  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idM'])) {
    $id = $_POST['idM'];
    $medecin = $connection->searchById($id,"medecin",$log);
    $event->registerEvent("edition");
  }

  //actions sur les champs de saisie
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {

      if($event->getEvent() == "edition"){
        //var_dump($_POST['civilite']);
        $medecinB = new medecin($_POST['mid'],$_POST['civilite'],$_POST['nom'],$_POST['prenom']);
        $connection->updateMedecin($medecinB,$log);
        //remise a 'ajouter'
        $event->registerEvent("ajouter");
      }else if($event->getEvent() == "ajouter" ){
        $medecinB = new medecin("0",$_POST['civilite'],$_POST['nom'],$_POST['prenom']);
        $log->updateLog($connection->ajouterMedecin($medecinB));

      }
  }

//la requete concerne une consultation
}else if(isset($_POST['consultation'])){

  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['day'])&&isset($_POST['month'])&& isset($_POST['year'])) {
    var_dump($event->getEvent());
    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $date = date("$year-$month-$day");
    $consultations = $connection->getConsultationsbyDate($date);
    var_dump($consultations);
    //maj de l'evenement
    if(empty($consultations)){
        $event->registerEvent("ajouter");
    }else{
        $event->registerEvent("edition");
    }
    var_dump($event->getEvent());

  }
  if($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['update'])) {
    var_dump($event->getEvent());
    if($event->getEvent() == "edition"){
      $consultation = new consultation($_POST['date'],$_POST['heure'],$_POST['duree'],$_POST['idm'],$_POST['idu']);
      $connection->editerConsultation($consultation,$log);
      $event->registerEvent("ajouter");
    }else if($event->getEvent() == "ajouter" ){
      $consultation = new consultation($_POST['date'],$_POST['heure'],$_POST['duree'],$_POST['idm'],$_POST['idu']);
      $connection->ajouterConsultation($consultation,$log);
      $event->registerEvent("ajouter");
    }
  }
  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
     $consultation = new consultation($_POST['date'],$_POST['heure'],$_POST['duree'],$_POST['idm'],$_POST['idu']);
     $connection->supprimerConsultation($consultation,$log);
     $event->registerEvent("ajouter");
  }

}



//mise a jour des events
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nouveau'])) {
  $event->registerEvent("ajouter");
}
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualiser'])) {
  $event->registerEvent("actualiser");
}
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edition'])) {
  $event->registerEvent("edition");
}





//var_dump($event->toString());

?>


<html >
<head>
  <title>Rendez-vous</title>
      <link rel="stylesheet" href="kube.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src="js/kube.js"></script>
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">


</head>
<body style="padding:3%">



  <nav class="tabs" data-component="tabs">
      <ul>
          <li><a href="#usager">Usager</a></li>
          <li><a href="#medecin">Medecin</a></li>
            <li><a href="#consultations">Consultations</a></li>
            <li><a href="#statistiques">Statistiques</a></li>
            <li><a href="#output">Output</a></li>
      </ul>
  </nav>

  <div id="usager">
    <div class="row">
      <div class="col col-9"  style="padding:3%">
        <form class="form" action="secretariat.php" method="post">
        <div class="form-item">
        <blockquote>Voici la liste des Usagers, vous pouvez les rechercher et les modifier</blockquote>
        <div class="append w50">

            <input type="text" name="usager" value="usager"  req style="display:none;"/>
            <input type="text" name="search" placeholder="Nom de l'usager">
            <input type="submit" class="button"value='rechercher' >

        </div>

        </div>
        </form>
        <form class="form" action="secretariat.php" method="post">
        <input type="text" name="usager" value="usager"  req style="display:none;"/>
        <button id="actualiser"style="background-color:#2ecc71" class="button"type="submit"><i class="material-icons">refresh</i></button>
        <button id="add"style="background-color:#9b59b6" name="nouveau"class="button"type="submit"><i class="material-icons">add</i></button>

        </form>
            <? if(isset($_POST["search"])&&isset($resultU)){
                if($resultU){
                  echo "<table  class='bordered striped'>";
                  echo "<th>Uid</th><th>Civilite</th><th>Nom</th><th>Prenom</th><th>Adresse</th><th>Naissance</th><th>Lieu Naissance</th><th>Num Sécu</th><th>Médecin</th><th>Modifier</th>";

                  while ($row = mysqli_fetch_array($resultU))
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
                }else{
                  $string ="Erreur : Usager non trouvé";
                  $log->updateLog($string);
                }

                  echo "</table>";
              }else{
                $connection->afficherUsagers();
               }

            ?>

      </div>

      <div class="col col-3"  style="padding:3%">
        <form id="form" class="form" action="secretariat.php" method="post">
          <input type="text" name="usager" value="usager"  req style="display:none;"/>
          <h2><?if(isset($_POST['idU'])):?>
           <?if(isset($_POST['idU'])) echo $user->getPrenom()." ".$user->getNom();?>

           <input type="submit" name="delete" style="background-color:#ff3366;"class="button small round" value='Suprrimer'>
           <?endif;?>
         </h2>
        <div class="form-item">
          <label>Civilite</label>
            <input type="text" name="civilite" value="<?if(isset($_POST['idU'])) echo $user->getCivilite();?>" required>
        </div>
            <div class="form-item">
         <label>Nom</label>
         <input type="text" name="nom" value="<?if(isset($_POST['idU'])) echo $user->getNom();?>" required>
          </div>
          <div class="form-item">
        <label>Prenom</label>
        <input type="text" name="prenom" value="<?if(isset($_POST['idU'])) echo $user->getPrenom();?>"required>
        </div>
        <div class="form-item">
         <label>Adresse</label>
         <input type="text" name="adresse" value="<?if(isset($_POST['idU'])) echo $user->getAdresse();?>"required>
        </div>
        <div class="form-item">
         <label>Date Naissance (AAAA-MM-JJ)</label>
         <input type="text" name="date_naissance" value="<?if(isset($_POST['idU'])) echo  $user->getDateNaissance();?>"required>
        </div>
        <div class="form-item">
         <label>lieu Naissance </label>
         <input type="text" name="lieu_naissance" value="<?if(isset($_POST['idU'])) echo $user->getLieuNaissance();?>"required>
        </div>
        <div class="form-item">
         <label>Numero de Sécurité Social </label>
         <input type="text" name="num_secu" value="<?if(isset($_POST['idU'])) echo $user->getNumeroSecu();?>"required>
        </div>
        <div class="form-item">
         <label>ID Medecin</label>
         <input type="text" name="id_medecin" value="<?if(isset($_POST['idU'])) echo $user->getIDMedecin();?>"required>
        </div>
        <input name='uid' value='"<?if(isset($_POST['idU'])) echo $_POST['idU'];?>"'  req style='display:none;'/>
        <input type="submit" name="update" class="button" value='Enregistrer'>

        </form>
      </div>
    </div>



  </div>




  <div id="medecin">
    <div class="row">
      <div class="col col-9"  style="padding:3%">
        <form class="form" action="secretariat.php#medecin" method="post">
        <div class="form-item">
        <blockquote>Voici la liste des medecins, vous pouvez les rechercher et les modifier</blockquote>
        <div class="append w50">

            <input type="text" name="medecin" value="medecin"  req style="display:none;"/>
            <input type="text" name="search" placeholder="Nom du medecin">
            <input type="submit" class="button"value='rechercher' >

        </div>

        </div>
        </form>
        <form class="form" action="secretariat.php#medecin" method="post">
        <input type="text" name="medecin" value="medecin"  req style="display:none;"/>
        <button id="actualiser"style="background-color:#2ecc71" class="button"type="submit"><i class="material-icons">refresh</i></button>
        <button id="add"style="background-color:#9b59b6" name="nouveau"class="button"type="submit"><i class="material-icons">add</i></button>

        </form>
            <? if(isset($_POST["search"])&&isset($resultM)){
                if($resultM){
                  echo "<table class='bordered striped'>";
                  echo "<th>Mid</th><th>Civilite</th><th>Nom</th><th>Prenom</th><th>Modifier</th>";

                  while ($row = mysqli_fetch_array($resultM))
                  {
                    echo"<tr>";
                    echo "<td>".$row['id_medecin']."</td>";
                    echo "<td>".$row['civilite']."</td>";
                    echo "<td>".$row['nom']."</td>";
                    echo "<td>".$row['prenom']."</td>";
                    echo "<td>";
                    echo"<form action='secretariat.php#medecin' method='post'>";
                    echo "<input name='idM' value='".$row['id_medecin']."'  req style='display:none;'/>";
                    echo "<input name='medecin' value='medecin'  req style='display:none;'/>";
                    echo "<input type='submit' class='button' value='edit'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                    }
                }else{
                  $string ="Erreur : Medecin non trouvé";
                  $log->updateLog($string);
                }

                  echo "</table>";
              }else{
                $connection->afficherMedecins();
               }

            ?>

      </div>

      <div class="col col-3"  style="padding:3%">
        <form id="form" class="form" action="secretariat.php#medecin" method="post">
        <input type="text" name="medecin" value="medecin"  req style="display:none;"/>
          <h2><?if(isset($_POST['idM'])):?>
           <?if(isset($_POST['idM'])) echo $medecin->getPrenom()." ".$medecin->getNom();?>

           <input type="submit" name="delete" style="background-color:#ff3366;"class="button small round" value='Suprrimer'>
           <?endif;?>
         </h2>
        <div class="form-item">
          <label>Civilite</label>
            <input type="text" name="civilite" value="<?if(isset($_POST['idM'])) echo $medecin->getCivilite();?>" required>
        </div>
            <div class="form-item">
         <label>Nom</label>
         <input type="text" name="nom" value="<?if(isset($_POST['idM'])) echo $medecin->getNom();?>" required>
          </div>
          <div class="form-item">
        <label>Prenom</label>
        <input type="text" name="prenom" value="<?if(isset($_POST['idM'])) echo $medecin->getPrenom();?>"required>
        </div>

        <input name='mid' value='"<?if(isset($_POST['idM'])) echo $_POST['idM'];?>"'  req style='display:none;'/>
        <input type="submit" name="update" class="button" value='Enregistrer'>

        </form>
      </div>
    </div>
  </div>
  <div id="statistiques">



  </div>
  <div id="consultations">
    <div class="row">
      <div class="col col-9" style="padding:3%">
        <?php
        // Set your timezone!!
        date_default_timezone_set('Europe/Paris');
        // Get prev & next month
        if (isset($_GET['ym'])) {
            $ym = $_GET['ym'];
            $y = date('Y',strtotime($ym));
            $m = date('m',strtotime($ym));
        } else {
            // This month
            $ym = date('Y-m');
            $y = date('Y');
            $m = date('m');
        }

        // Check format
        $timestamp = strtotime($ym . '-01');
        if ($timestamp == false) {
            $timestamp = time();
        }

        // Today
        $today = date('Y-m-d', time());

        // For H3 title
        $html_title = date('F Y', $timestamp);

        // Create prev & next month link     mktime(hour,minute,second,month,day,year)
        $prev = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)-1, 1, date('Y', $timestamp)));
        $next = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)+1, 1, date('Y', $timestamp)));

        // Number of days in the month
        $day_count = date('t', $timestamp);

        // 0:Sun 1:Mon 2:Tue ...
        $str = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));


        // Create Calendar!!
        $weeks = array();
        $week = '';

        // Add empty cell
        $week .= str_repeat('<td></td>', $str);
        $cons = null;


        for ( $day = 1; $day <= $day_count; $day++, $str++) {
          $date_unformated = date_create($y.'-'.$m.'-'.$day);
          $date = date_format($date_unformated,"Y-m-d");

            $cons = $connection->searchConsultation($date);
            //todo consultation full !
            if ($date == $today) {
                if(isset($cons) && $cons->getDate()==$date){
                    $week .= '<td class="booked today">';
                }else{
                    $week .= '<td class="today">';

                }

            } else {

              if(isset($cons) && $cons->getDate()==$date){
                  $week .= '<td class="booked day">';
              }else{
                  $week .= '<td class="day">';

              }
            }

            $week .=
            '<form class="form-day"action="secretariat.php#consultations" method="post" >
            <input  req style="display:none;" name="consultation" value="consultation"/>
            <input  req style="display:none;" name="month" value="'.$m.'"/>
            <input  req style="display:none;" name="year" value="'.$y.'"/>
            <input value="'.$day.'"style="width:100%;height:50px;border:none; background-color:transparent;" type="submit" name="day"/>
            </form>';
            if ($date == $today) {
                if(isset($cons) && $cons->getDate()==$date){
                    $array = $connection->getNBConsultations($date);
                    $row = mysqli_fetch_array($array);
                    $week .= '<div style="text-align:center;width:100%;height:50px">'.$row[0].'&nbsp consultation(s)</div></td>';
                }

            } else {

              if(isset($cons) && $cons->getDate()==$date){
                $array = $connection->getNBConsultations($date);
                $row = mysqli_fetch_array($array);
                $week .= '<div style="text-align:center;width:100%;height:50px">'.$row[0].'&nbsp consultation(s)</div></td>';
              }
            }


            // End of the week OR End of the month
            if ($str % 7 == 6 || $day == $day_count) {

                if($day == $day_count) {
                    // Add empty cell
                    $week .= str_repeat('<td></td>', 6 - ($str % 7));
                }

                $weeks[] = '<tr>'.$week.'</tr>';

                // Prepare for new week
                $week = '';
            }

        }
        ?>
        <div class="container">
            <h3><a href="?ym=<?php echo $prev; ?>#consultations"><span class="caret left"></span></a> <?php echo $html_title; ?> <a href="?ym=<?php echo $next; ?>#consultations"><span class="caret right"></span></a></h3>
            <br>
            <table class="table bordered">
                <tr>
                    <th>Lundi</th>
                    <th>Mardi</th>
                    <th>Mercredi</th>
                    <th>Jeudi</th>
                    <th>Vendredi</th>
                    <th>Samedi</th>
                    <th>Dimanche</th>
                </tr>
                <?php
                    foreach ($weeks as $week) {
                        echo $week;
                    }
                ?>
            </table>
        </div>
      </div>
      <div class="col col-3" style="padding:3%">

        <div id="collapse" data-component="collapse">



    <?php

    // si page defaut
    if(isset($consultations)&& !empty($consultations)){
      $date = date("j F Y", strtotime($consultations[0]->getDate()));
      $today = date("j F Y",time());
      echo  "<h3>Consultations du $date</h3>";
      if(strtotime($date) <= strtotime($today)) {
        //la date de la consultation est antérieure a la dat actuelle
        //on se propose de déasactivé la modification de la consultation
      echo "<blockquote>Ces consultations ont déjà eu lieu, vous ne pouvez pas les modifier</blockquote>";
      foreach ($consultations as $consultation) {
        $id = str_replace(":","",$consultation->getIDUsager().$consultation->getIDMedecin().$consultation->getDate().$consultation->getHeure());
        $id = str_replace("-","",$id);


        echo "<h4>

        <a href='#$id' class='collapse-toggle'>".date("H:i",strtotime($consultation->getHeure()))."<span style='margin-left:65%'class='caret down'></a></span></h4>
        <div class='collapse-box hide' id='$id'>";




        echo
        "<form id='form' class='form' action='secretariat.php#consultations' method='post'>
      <input type='text' name='consultation'   value='consultation'  req style='display:none;'/>
        <div class='form-item'>
          <label>Date (aaaa-mm-jj)</label>
            <input type='text' name='date'  disabled='true' value='".$consultation->getDate()."' required>
        </div>
        <div class='form-item'>
          <label>Heure</label>
            <input type='text' name='heure'  disabled='true' value='".$consultation->getHeure()."' required>
        </div>
        <div class='form-item'>
          <label>Duree</label>
            <input type='text' name='duree'  disabled='true' value='".$consultation->getDuree()."' required>
        </div>
        <div class='form-item'>
          <label>Medecin</label>
            <input type='text' name='idm'  disabled='true' value='".$consultation->getIDMedecin()."' required>
        </div>
        <div class='form-item'>
          <label>Usager</label>
            <input type='text' name='idu'  disabled='true' value='".$consultation->getIDUsager()."' required>
        </div>


      </form></div>";
    }
    }else{
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
      foreach ($consultations as $consultation) {
        $id = str_replace(":","",$consultation->getIDUsager().$consultation->getIDMedecin().$consultation->getDate().$consultation->getHeure());
        $id = str_replace("-","",$id);

        echo "<h4>

        <a href='#$id' class='collapse-toggle'>".date("H:i",strtotime($consultation->getHeure()))."<span style='margin-left:65%'class='caret down'></a></span></h4>
        <div class='collapse-box hide' id='$id'>";




        echo
        "<form id='form' class='form' action='secretariat.php#consultations' method='post'>
        <input type='text' name='consultation'  value='consultation'  req style='display:none;'/>

        <div class='form-item'>
          <label>Date (aaaa-mm-jj)</label>
            <input type='text' name='date'  value='".$consultation->getDate()."' required>
        </div>
        <div class='form-item'>
          <label>Heure</label>
            <input type='text' name='heure'  value='".$consultation->getHeure()."' required>
        </div>
        <div class='form-item'>
          <label>Duree</label>
            <input type='text' name='duree'  value='".$consultation->getDuree()."' required>
        </div>
        <div class='form-item'>
          <label>Medecin</label>
            <input type='text' name='idm'  value='".$consultation->getIDMedecin()."' required>
        </div>
        <div class='form-item'>
          <label>Usager</label>
            <input type='text' name='idu'  value='".$consultation->getIDUsager()."' required>
        </div>


        <input type='submit' name='update' class='button' value='Enregistrer'>
        <input type='submit' name='delete' style='background-color:#ff3366;'class='button small round' value='Suprrimer'>

      </form></div>";
      }


    }
    }else {
      $connection->getConsultations();
    }

    // si clique sur une date?>

</div>
      </div>
    </div>


  </div>
  <div id="output">
    <style type="text/css">
samp {
  background: #fff;
  color: #000;
  display: block;
  padding: 10px;
  width: 50%;
}
td.today{
  background-color: #9b59b6;
  color:white!important;
}
td.day{
  color:white;
}
td.booked.today{
  background-color: #9b59b6!important;
}
td.booked.today input{
  color:white!important;
}
td.booked.day input{
  color:white!important;
}
td.day:hover{
  background-color:#E1F5FE;
}
td.booked.day:hover{
  background-color:#1c86f2;
}
td.booked.day:hover input{
  color:white!important;
}
td.day:hover input{
  color:black!important;
}
.booked{
  background-color: #1c86f2;
  color:white!important;
}

h4{
  margin:0px;
}
a:hover{ color:#676b72 ; }
.collapse-toggle{
  text-decoration: none;
  background-color: #f8f8f8;
  color:#676b72;
  width: 100%;
  height:auto;
  margin:0;
  padding: 5%;
  display: block;
}
table.bordered td:first-child, table.striped td:first-child, table.striped th:first-child,
table.bordered td:last-child, table.striped td:last-child, table.striped th:last-child{
  padding-left:0;
  padding-right: 0;
}
</style>
<p class="center"><samp>
  Logs de la base de données
  <?php echo $connection->getBDD()."<br>";
        echo "------------------------------------------ <br>";

  foreach (array_reverse($log->getLogs()) as $value) {
    if (strpos($value, 'Erreur') !== false) {
       echo "<span class='label tag error'>Error &nbsp</span>";
      echo "$value<br>";
    }else{
      echo "<span style='color:#35beb1'class='label tag sucess'>Sucess &nbsp</span>";
      echo "$value<br>";
    }

  }
  ?>


</samp></p>
  </div>

<script>

  $(".toconnect" ).click(function() {
  $('.tabs').tabs('open', '#connection');
  $('.tabs').tabs('close', this);
});



</script>


<script>

//fermeture des messages
setTimeout(function(){
    //do what you need here
    $('.message.focus').message('close');
}, 2000);


</script>



</body>
</html>
