<?php
header("Content-type: text/html; charset=utf-8");

include "conn.php";

$token = isset($_POST['token']) ? htmlspecialchars($_POST['token']) : '';
$error = "0";
$userid = get_userid_bytoken($conn, $token);
$sql="";
$avatar="";
$face_id="";

if ($userid <= 0) {
  $error="1";
} else {
  $sql = "SELECT * FROM t_user WHERE id=" . $userid . ";";
  $result = $conn->query($sql);  
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $avatar = $row["avatar"];
    $face_id = $row["face_id"];
    $error="0";
  } else {
    $error="2";
  }
}

if ($error=="0") {


}

class Result {
  public $error = "";
  public $token = "";
  public $userid = 0;
  public $avatar = "";
}

$e = new Result();
$e->error = $error;
$e->token = $token;
$e->userid = $userid;
$e->avatar = $avatar;

echo json_encode($e);

?>
