<?php

include "config.php";

$conn = new mysqli(DB_SERV, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error)
{
  //echo DB_SERV . DB_USER . DB_PASS . DB_NAME;
  die("Connection failed: " . $conn->connect_error);
}

function get_userid_bytoken($con, $token)
{
  $userid = 0;
  $sql = "SELECT * FROM t_token WHERE token='" . $token. "';";
  $result = $con->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userid = $row["userid"];
  }

  return $userid;
}

function get_username_byuserid($con, $userid)
{
  $username = "unkown";
  $sql = "SELECT * FROM t_user WHERE id=" . $userid . ";";
  $result = $con->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row["username"];
  }

  return $username ;
}

function get_userid_byusername($con, $username)
{
  $userid = 0;
  $sql = "SELECT * FROM t_user WHERE username='" . $username. "';";
  $result = $con->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userid = $row["id"];
  }

  return $userid;
}

function pay_bill($con, $userid, $bill, $ownerid)
{
  $sql = "UPDATE t_property SET deposit=deposit-".$bill." WHERE userid=".$userid.";";
  $result = $con->query($sql);
  $sql = "SELECT deposit FROM t_property WHERE userid=".$userid.";";
  $result = $con->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $deposit = $row["deposit"];
    if ($deposit >= 0) {
      $sql = "UPDATE t_property SET deposit=deposit+".$bill." WHERE userid=".$ownerid.";";
      $result = $con->query($sql);
    }

    return $deposit;
  }

  return -1;
}

function freeze_funds($con, $userid, $funds)
{
  $deposit = -1;
  $sql = "UPDATE t_property SET deposit=deposit-".$funds." property_num=property_num+1 WHERE userid=".$userid.";";
  $result = $con->query($sql);
  $sql = "SELECT deposit FROM t_property WHERE userid=".$userid.";";
  $result = $con->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $deposit = $row["deposit"];
    if ($deposit < 0)
    {
      $sql = "UPDATE t_property SET deposit=deposit+".$funds." property_num=property_num-1 WHERE userid=".$userid.";";
      $result = $con->query($sql);
    }
  }

  return $deposit;
}

function refund($con, $userid, $funds)
{
  $sql = "UPDATE t_property SET deposit=deposit+".$funds." property_num=property_num-1 WHERE userid=".$userid.";";
  $result = $con->query($sql);

  return TRUE;
}

?>