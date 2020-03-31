<?php
header("Content-type: text/html; charset=utf-8");

include "conn.php";

$token = isset($_POST['token']) ? htmlspecialchars($_POST['token']) : '';
$project = isset($_POST['project']) ? htmlspecialchars($_POST['project']) : '';
$latitude = isset($_POST['latitude']) ? htmlspecialchars($_POST['latitude']) : '';
$longitude = isset($_POST['longitude']) ? htmlspecialchars($_POST['longitude']) : '';

$error = "0";
$userid = get_userid_bytoken($conn, $token);
$sql="";
$owner="";
$balance=0;
$bill=0;
$ticket="0";
$ownerid = 0;
$puzzle = "";
$answer = "";

if ($userid <= 0) {
  $error="1";
} else {
  $sql = "SELECT * FROM t_application WHERE FORMAT(latitude,3)="
     .number_format($latitude,3)." and FORMAT(longitude,3)=".number_format($longitude,3).";";
  $result = $conn->query($sql);  
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $puzzle = $row["puzzle"];    
    if ($userid === $row["userid"]) {
      $ticket="2";
      $answer = $row["answer"];
    } else {
      $ticket="1";
    }
  } else {
    $ticket="0";
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
  public $puzzle = "";
  public $answer = "";
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
$e->puzzle = $puzzle;
$e->answer = $answer;

echo json_encode($e);

?>
