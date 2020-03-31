<?php
header("Content-type: text/html; charset=utf-8");

include "conn.php";
include "utils.php";

$username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
$password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';

$sql = "SELECT * FROM t_user where username='" . $username . "' and password='" . $password . "';";
$result = $conn->query($sql);
$error = "0";
$token = "";
$userid = "";
$username = "unknown";
$avatar = "";

if ($result->num_rows > 0) {

  $row = $result->fetch_assoc();
  $userid = $row["id"];
  $username = $row["username"];
  $avatar = $row["avatar"];
  $token = guid();
  $sql = "INSERT INTO t_token (token, userid) VALUES ('" . $token . "', '" . $userid . "');";

  if ($conn->query($sql) === TRUE) {
    $error="0";    
  } else {
    $error="2";
  }  

} else {
  $error="1";
}

class Result {
  public $error = "";
  public $token = "";
  public $userid = "";
  public $username = "";
  public $avatar = "";
}

$e = new Result();
$e->error = $error;
$e->token = $token;
$e->userid = $userid;
$e->username = $username;
$e->avatar = $avatar;

echo json_encode($e);

?>
