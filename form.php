
<html lang="en" >
<head>
  <title>Formulaire</title>
      <link rel="stylesheet" href="style.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
      <script src="js/kube.js"></script>
        <link rel="stylesheet" href="kube.css">

</head>
<body>
</body>
</html>

<?PHP
try{
  $user = 'root';
  $password = 'root';
  $db = 'iut-info-tlse3';
  $host = 'localhost';
  $port = 8889;
  $table = 'carnet_adresse';

  $conn=mysqli_connect($host,$user,$password,$db);

  echo "<div class='message focus' data-component='message'>Connection ok<span class='close small'></span></div>";
}catch(Exception $e){

  echo "<div class='message error' data-component='message'> Erreur <span class='close small'></span></div>";
  exit();

};


$sql = "INSERT INTO carnet_adresse(nom, prenom, adresse,code,ville,telephone)
VALUES ('$_POST[nom]', '$_POST[prenom]', '$_POST[adresse]','$_POST[code]','$_POST[ville]','$_POST[telephone]')";

if (mysqli_query($conn, $sql)) {

    echo "<div class='message focus' data-component='message'>Enregistré<span class='close small'></span></div>";

    echo "<table style='margin-left:25%;margin-top:10%;width:50%' class='bordered striped'>";
    echo "<th>Uid</th><th>nom</th><th>Prenom</th><th>Adresse</th><th>Code</th><th>Ville</th><th>Téléphone</th>";
    $result = mysqli_query($conn,"select * from carnet_adresse");
    while ($row = mysqli_fetch_array($result))
    {
    echo"<tr><td>".$row['uid']."</td><td>".$row['nom']."</td><td>".$row['prenom']."</td><td>".$row['adresse']."</td><td>".$row['code']."</td><td>".$row['ville']."</td><td>".$row['telephone']."</td></tr>";
    }
    echo "</table>";
    mysqli_close($conn);
} else {
  $err = mysqli_error($conn);
    echo "<div class='message error' data-component='message'> Erreur <span class='close small'></span></div>";
}

mysqli_close($conn);

?>



<script>

//code before the pause
setTimeout(function(){
    //do what you need here
    $('.message.focus').message('close');
}, 3000);


</script>
