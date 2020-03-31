<?php
header("Content-type: text/html; charset=utf-8");

include "conn.php";

$token = isset($_POST['token']) ? htmlspecialchars($_POST['token']) : '';
$project = isset($_POST['project']) ? htmlspecialchars($_POST['project']) : '';
$latitude = isset($_POST['latitude']) ? htmlspecialchars($_POST['latitude']) : '';
$longitude = isset($_POST['longitude']) ? htmlspecialchars($_POST['longitude']) : '';
$puzzle = isset($_POST['puzzle']) ? htmlspecialchars($_POST['puzzle']) : '';

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
  $sql = "SELECT * FROM t_application WHERE FORMAT(latitude,3)="
     .number_format($latitude,3)." and FORMAT(longitude,3)=".number_format($longitude,3).";";
  $result = $conn->query($sql);  
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();    
    if ($userid === $row["userid"]) {
      $ticket="2";
    } else {
      $ticket="1";
      $bill = 1000;
      $ownerid = $row["userid"];
      $balance = pay_bill($conn, $userid, $bill, $ownerid); 
      $owner = get_username_byuserid($conn, $ownerid);
    }
  } else {
    if (freeze_funds($conn, $userid, 100000) >= 0) {
      $sql = "INSERT INTO t_application (project, userid, latitude, longitude, appdate, puzzle) VALUES ('" 
        . $project . "'," 
        . $userid . ","  
        . $latitude . "," 
        . $longitude . ", NOW(),'"
        .$puzzle."');";
      if ($conn->query($sql) === TRUE) {
        $error="0";
      } else {
        refund($conn, $userid, 10000);
        $error="2";
      }
    } else {
      $error = "3";
    }
  }

  // write log
  $sql = "INSERT INTO t_history (project, userid, latitude, longitude, appdate, bill, ticket, puzzle, ownerid) VALUES ('" 
    . $project . "'," 
    . $userid . ","  
    . $latitude . "," 
    . $longitude . ", NOW()," 
    . $bill . "," 
    . $ticket .",'"
    . $puzzle."',"
    . $ownerid.");";
  $conn->query($sql);
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
