<?php
header("Content-type: text/html; charset=utf-8");

include "conn.php";

$token = isset($_POST['token']) ? htmlspecialchars($_POST['token']) : '';
$project = isset($_POST['project']) ? htmlspecialchars($_POST['project']) : '';
$latitude = isset($_POST['latitude']) ? htmlspecialchars($_POST['latitude']) : '';
$longitude = isset($_POST['longitude']) ? htmlspecialchars($_POST['longitude']) : '';
$id = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : '';
$deploy = isset($_POST['deploy']) ? htmlspecialchars($_POST['deploy']) : '';

$error = "0";
$userid = get_userid_bytoken($conn, $token);
$sql="";
$owner="";
$balance=0;
$bill=0;
$ticket="0";
$ownerid = 0;

if ($userid <= 0) {
  $error="1";
} else {
  if ($id != "")
  {
    $sql = "UPDATE t_application SET deploy='".$deploy."' WHERE id=".$id;
    $result = $conn->query($sql);  
    if ($result===TRUE) {
      $error=0;
      $ticket=2;
    } else {
      $error=1;
    }
  } else {
    $sql = "INSERT INTO t_application (project, userid, latitude, longitude, appdate, puzzle, deploy) VALUES ('" 
      . $project . "'," 
      . $userid . ","  
      . $latitude . "," 
      . $longitude . ", NOW(),'"
      . $puzzle ."','"
      . $deploy. "');";
    if ($conn->query($sql) === TRUE) {
      $error="0";
      $ticket=0;
    } else {    
      $error="2";
    }
  }
}

class Result {
  public $error = "";
  public $token = "";
  public $userid = 0;
  public $project = "";
  public $ticket = "";
  public $balance = "";
  public $bill = "";
  public $owner = "";
}

$e = new Result();
$e->error = $error;
$e->token = $token;
$e->userid = $userid;
$e->project = $project;
$e->ticket = $ticket;
$e->balance = $balance;
$e->bill = $bill;
$e->owner = $owner;

echo json_encode($e);

?>
