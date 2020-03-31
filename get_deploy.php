<?php
header("Content-type: text/html; charset=utf-8");

include "conn.php";

$token = isset($_POST['token']) ? htmlspecialchars($_POST['token']) : '';
$project = isset($_POST['project']) ? htmlspecialchars($_POST['project']) : '';
$latitude = isset($_POST['latitude']) ? htmlspecialchars($_POST['latitude']) : '';
$longitude = isset($_POST['longitude']) ? htmlspecialchars($_POST['longitude']) : '';
$id = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : '';

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
$deploy = "";

if ($id!=null&&$id!="") {
  $sql = "SELECT * FROM t_application WHERE id=".$id.";";
} else {
  $sql = "SELECT * FROM t_application WHERE FORMAT(latitude,3)="
     .number_format($latitude,3)." and FORMAT(longitude,3)=".number_format($longitude,3).";";
}

$result = $conn->query($sql);  
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $deploy = $row["deploy"];
  if ($deploy===null||$deploy==="") $deploy="B-2,C-2,C-3,G-2,G-4,G-3";
  $id = $row["id"];
  $project = $row["project"];
  if ($userid === $row["userid"]) {
    $ticket="2";
  } else {
    $ticket="1";
  }
} else {
  $ticket="0";
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
  public $deploy = "";
  public $id = 0;
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
$e->deploy = $deploy;
$e->id = $id;

echo json_encode($e);

?>
