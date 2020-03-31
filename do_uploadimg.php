<?php
header("Content-type: text/html; charset=utf-8");

include "conn.php";
include "utils.php";

$token = isset($_POST['token']) ? htmlspecialchars($_POST['token']) : '';
$imgtype = isset($_POST['imgtype']) ? htmlspecialchars($_POST['imgtype']) : '';

$error = "0";
$userid = get_userid_bytoken($conn, $token);
$sql="";
$filepath="";

if ($userid <= 0 || ($imgtype != "avatar" && $imgtype != "puzzle")) {
  $error="1";
} else {

  $img = $_POST['imagedata'];
  $img = iconv("UTF-8", "gbk",  urldecode($img));
  //$img = str_replace('data:image/png;base64,', '', $img);
  //$img = str_replace(' ', '+', $img);
  //$data = base64_decode($img);
 
  if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img, $result)){
    $type = $result[2];
    $guid = guid();
    $filepath="asset/images/" . $imgtype . "/" . $guid .".{$type}";
    if (file_put_contents($filepath, base64_decode(str_replace($result[1], '', $img)))){
      $sql = "UPDATE t_user SET avatar='".$filepath."' WHERE id=".$userid.";";
      if ($conn->query($sql) === TRUE) {       
        move_uploaded_file($_FILES["file"]["tmp_name"], $filepath);
        $error="0";
      } else {
        $error="2";
      }
    } else {
      $error="3";
    }
  } else {
    $error="4";
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