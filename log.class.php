<?php
class log
{




  function __construct(){
    if(!isset($_SESSION['log'])){
        $_SESSION['log'] = array();
    }

  }

  public function updateLog($string){
    $message = date("Y-m-d")." : ".date("h:i:s")." : ".$string;
    array_push($_SESSION['log'], $message);
  }

  public function getLogs(){
    return $_SESSION['log'];
  }



}?>
