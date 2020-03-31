<?php
header("Content-type: text/html; charset=utf-8");

include "conn.php";

$token = isset($_POST['token']) ? htmlspecialchars($_POST['token']) : '';
$userid = get_userid_bytoken($conn, $token);
$sql = "";
$error = "0";
$datalists=array(); 

$sql = "SELECT count(*) as property_num, u.username FROM t_application as a, t_user as u WHERE a.userid=u.id GROUP BY a.userid ORDER BY property_num DESC LIMIT 0,30;";
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

class Result {
  public $error = "";
  public $datalists;
}

$e = new Result();
$e->error = $error;
$e->datalists = $datalists;

echo json_encode($e);

?>
