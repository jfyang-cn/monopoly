<?php
header("Content-type: text/html; charset=utf-8");

include "conn.php";

$token = isset($_POST['token']) ? htmlspecialchars($_POST['token']) : '';
$userid = get_userid_bytoken($conn, $token);
$sql = "";
$error = "0";
$datalists=array(); 
$deposit = 0;
$property_num = 0;

if ($userid <= 0) {
  $error="1";
} else {
  $sql = "SELECT id, appdate, userid, project, FORMAT(longitude,3) as longitude, FORMAT(latitude,3) as latitude FROM  t_application WHERE userid=" . $userid . " ORDER BY appdate DESC;";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $i=0;
    while($row = $result->fetch_assoc()) {
      $datalists[$i] = $row;
      $i++;
    }
  }else {
    ;
  }

  /*$sql = "SELECT * FROM  t_property WHERE userid=" . $userid . ";";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $deposit = $row["deposit"];
    $property_num = $row["property_num"];
  }*/

}

class Result {
  public $error = "";
  public $deposit = 0;
  public $property_num = 0;
  public $datalists;
}

$e = new Result();
$e->error = $error;
$e->datalists = $datalists;
$e->deposit = $deposit;
$e->property_num = $property_num;

echo json_encode($e);

?>
