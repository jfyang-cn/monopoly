<?php
header("Content-type: text/html; charset=utf-8");

include "conn.php";

$username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
$password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';

$sql = "SELECT * FROM t_user WHERE username='" . $username . "';";
$result = $conn->query($sql);
$error = "0";
$userid = 0;

if ($result->num_rows > 0) {
  $error="1";
} else {

  $sql = "INSERT INTO t_user (username, password) VALUES ('" . $username . "', '" . $password . "');";
  if ($conn->query($sql) === TRUE) {
    $error="0";
  } else {
    $error="2";
  }

  if ($error === "0") {
    $userid = get_userid_byusername($conn, $username);
    if ($userid != 0)
    {
      $sql = "INSERT INTO t_property (userid, deposit, property_num) VALUES (" . $userid . ",5000000, 0);";
      if ($conn->query($sql) === TRUE) {
        $error="0";
      } else {
        $error="2";
      }
    }
  }
}

class Result {
  public $error = "";
  public $userid = 0;
}

$e = new Result();
$e->error = $error;
$e->userid = $userid;

echo json_encode($e);

?>
