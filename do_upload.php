<?php
header("Content-type: text/html; charset=utf-8");

include "conn.php";
include "utils.php"

$token = isset($_POST['token']) ? htmlspecialchars($_POST['token']) : '';

$error = "0";
$userid = get_userid_bytoken($conn, $token);
$sql="";
$filepath="";

if ($userid <= 0) {
  $error="1";
} else {
  $allowedExts = array("jpeg", "jpg", "png");
  $temp = explode(".", $_FILES["file"]["name"]);
  $extension = end($temp);
  if ((($_FILES["file"]["type"] == "image/jpeg")
    || ($_FILES["file"]["type"] == "image/jpg")
    || ($_FILES["file"]["type"] == "image/pjpeg")
    || ($_FILES["file"]["type"] == "image/x-png")
    || ($_FILES["file"]["type"] == "image/png"))
    && ($_FILES["file"]["size"] < 20000)
    && in_array($extension, $allowedExts))
  {
    if ($_FILES["file"]["error"] > 0)
    {
      $error="1";
    }
    else
    {
      $sql = "UPDATE t_user SET avatar='".$filepath."' WHERE id=".$userid.";";
      if ($con->query($sql) === TRUE) {
        $guid = guid();
        $filepath="asset/images/avatar/" . $guid;
        move_uploaded_file($_FILES["file"]["tmp_name"], $filepath);
        $error="0";
      } else {
        $error="1";
      }
    }
  }
  else
  {
    $error="1";
  }
}

class Result {
  public $error = "";
  public $filepath = "";
}

$e = new Result();
$e->error = $error;
$e->filepath = $filepath;

echo json_encode($e);

?> 