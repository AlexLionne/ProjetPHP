<?php
/**
 *
 */
class event
{


  function __construct($event){
    if(!isset($_SESSION['event'])){
        $_SESSION["event"] = $event;
    }

  }

  public function registerEvent($event){
    $_SESSION["event"] = $event;
  }

  public function unRegisterEvent(){
  $_SESSION["event"] = null;
  }

  public function getEvent(){
    return $_SESSION["event"];
  }

  public function toString(){
    return "event :".$_SESSION["event"];
  }
}




 ?>
