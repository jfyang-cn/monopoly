<?php
header("Content-type: text/html; charset=utf-8");

include "conn.php";
include "utils.php";

require_once 'facepp/facepp_sdk.php';

$token = isset($_POST['token']) ? htmlspecialchars($_POST['token']) : '';
$userid = get_userid_bytoken($conn, $token);
$imgtype = isset($_POST['imgtype']) ? htmlspecialchars($_POST['imgtype']) : '';
$password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';

$sql = "SELECT * FROM t_user WHERE id=" . $userid . ";";
$result = $conn->query($sql);
$error = "0";
$face_id = "";
$filepath = "";
$old_face = "";
$old_file = "";

if ($result->num_rows == 0) {
  $error="1";
} else {

  if ($password!=null&&$password!="") {
    $sql = "UPDATE t_user SET password='". $password."' WHERE id=" . $userid . ";";
    if ($conn->query($sql) === TRUE) {
      $error="0";
    } else {
      $error="2";
    }
  }

  if ($imgtype!=null&&$imgtype!="") {
    $row = $result->fetch_assoc();
    $userid = $row["id"];
    $username = $row["username"];
    $old_face = $row["face_id"];
    //$old_file = $row["avatar"];

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
            $response = $facepp->execute('/faceset/remove_face', array('faceset_name' => 'monopoly', 'face_id' => $old_face));
            //@unlink($old_file);
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
      $sql = "UPDATE t_user SET avatar='". $filepath."', face_id='". $face_id ."' WHERE id=" . $userid . ";";
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
