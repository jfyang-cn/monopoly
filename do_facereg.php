<?php
header("Content-type: text/html; charset=utf-8");

include "conn.php";
include "utils.php";

require_once 'facepp/facepp_sdk.php';

$username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
$imgtype = isset($_POST['imgtype']) ? htmlspecialchars($_POST['imgtype']) : '';

$sql = "SELECT * FROM t_user WHERE username='" . $username . "';";
$result = $conn->query($sql);
$error = "0";
$userid = 0;
$face_id = "";
$filepath = "";

if ($result->num_rows > 0) {
  $error="1";
} else {

  $img = $_POST['imagedata'];
  $img = iconv("UTF-8", "gbk",  urldecode($img));
 
  if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img, $result)){
    $type = $result[2];
    $guid = guid();
    $filepath="asset/images/" . $imgtype . "/" . $guid .".{$type}";
    if (file_put_contents($filepath, base64_decode(str_replace($result[1], '', $img)))) {
      move_uploaded_file($_FILES["file"]["tmp_name"], $filepath);
      $error="0";
    } else {
      $error="3";
    }
  } else {
    $error="4";
  }

  if ($error === "0") {
    // facepp
    $facepp = new Facepp();
    $params['img']          = $filepath;
    $params['attribute']    = 'gender,age,race,smiling,glass,pose';

    $response               = $facepp->execute('/detection/detect',$params);
    if($response['http_code'] == 200) {
      #json decode 
      $data = json_decode($response['body'], 1);
      $faces = $data['face'];
      $face = $faces[0];
      $face_id = $face['face_id'];
      $response = $facepp->execute('/faceset/add_face', array('faceset_name' => 'monopoly', 'face_id' => $face_id));
      if($response['http_code'] == 200) {
        #json decode 
        $data = json_decode($response['body'], 1);
        if ($data['success'] == "true") {
          $error="0";
        } else {
          $error="5";
        }
      } else {
        $error="7";
      }
    } else {
      $error="6";
    }
  }

  if ($error === "0") {
    $sql = "INSERT INTO t_user (username, password, face_id, avatar) VALUES ('" . $username . "', '" . $face_id ."', '" . $face_id . "','" . $filepath . "');";
    if ($conn->query($sql) === TRUE) {
      $error="0";
    } else {
      $error="2";
    }
  }

  /*if ($error === "0") {
    $userid = get_userid_byusername($conn, $username);
    if ($userid != 0) {
      $sql = "INSERT INTO t_property (userid, deposit, property_num) VALUES (" . $userid . ",5000000, 0);";
      if ($conn->query($sql) === TRUE) {
        $error="0";
      } else {
        $error="2";
      }
    }
  }*/
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
