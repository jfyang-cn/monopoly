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
$datalists=array(); 

if ($userid <= 0) {
  $sql = "SELECT appdate, project, id FROM t_application LIMIT 0 , 30;";
} else {
  $sql = "SELECT appdate, project, id FROM t_application WHERE userid!="
     . $userid . " LIMIT 0 , 30;";
}

$result = $conn->query($sql);  
if ($result->num_rows > 0) {
  $i=0;
  while($row = $result->fetch_assoc()) {
    $datalists[$i] = $row;
    $i++;
  }
} else {
  ;
}

class Result {
  public $error = "";
  public $datalists;
}

$e = new Result();
$e->error = $error;
$e->datalists = $datalists;

echo json_encode($e);

?>
