<?php
header("Content-type: text/html; charset=utf-8");

include "conn.php";

$token = isset($_GET['token']) ? htmlspecialchars($_GET['token']) : '';
$id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';

$error = "0";
$userid = get_userid_bytoken($conn, $token);
$sql="";

if ($userid <= 0) {
  $error="1";
  exit();
} else {
  $sql = "SELECT * FROM t_application WHERE id=" .$id . ";";
  $result = $conn->query($sql);  
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
  } else {
    exit();
  }
}

?>
<!DOCTYPE html>
<!--[if IE 8]>
<html xmlns="http://www.w3.org/1999/xhtml" class="ie8" lang="zh-CN">
<![endif]-->
<!--[if !(IE 8) ]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
<link href="asset/css/app.css" rel="stylesheet">		
<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
<script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="asset/js/utils.js"></script>
<title>申购</title>
<body>
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-header">          
        <a class="navbar-brand" href="index.htm">Monopoly</a>
        <ul class="nav navbar-nav nav-pills">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-user"></span><span id="info-username">Thomas</span><b class="caret"></b>
          </a>
         
          <ul class="dropdown-menu">
            <li><a href="login.htm">登录</a></li>
            <li><a href="register.htm">注册</a></li>
            <li><a href="setting.htm">设置</a></li>
            <li><a href="#" onclick="logout();">注销</a></li>
          </ul>
        </li>
        </ul>
    </div>
  </nav>
  
  <form id="myform" class="form-horizontal" role="form" action="ticket.htm">

    <div class="form-group">
      <label for="application" class="col-sm-2 control-label">关迷：</label>      
      <div class="col-sm-10">
        <img id="default-icon" src="<?php echo $row["puzzle"]?>" style="display:none" />
        <canvas id="myCanvas" width="250" height="300" style="border:1px solid #d3d3d3;">您的浏览器不支持 HTML5 canvas 标签。</canvas>
        <input type="file" capture="camera" accept="image/*" id="file" name="file" style="display:none" onchange="fileOnChange(this.value); document.getElementById('btn').disabled=false;"/>
      </div>
    </div>
    <div class="form-group">
      <label for="application" class="col-sm-2 control-label">地段：</label>
      <div class="col-sm-10">
        <span id="info-project"><?php echo $row["project"] ?></span>        
        <input type="hidden" name="project" id="project" class="form-control" value="<?php echo $row["project"] ?>">
        <input type="hidden" name="latitude" id="latitude" value="<?php echo $row["latitude"] ?>">
        <input type="hidden" name="longitude" id="longitude" value="<?php echo $row["longitude"] ?>">
        <input type="hidden" name="token" id="token">
        <input type="hidden" name="puzzle" id="puzzle" value="<?php echo $row["puzzle"] ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="application" class="col-sm-2 control-label">答案：</label>
      <div class="col-sm-10">
        <input type="text" name="answer" id="answer" class="form-control" placeholder="请根据图片输入闯关答案">
      </div>
    </div>

    <!-- div message -->    
    <div id="div-error" class="alert alert-warning"><span id="info-error">申购失败！</span></div>
    <div id="div-succ" class="alert alert-success"><span id="info-succ"></span></div>    

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button class="btn btn-large btn-primary" type="button">闯关</button>
      </div>
    </div>
  </form>
  <div id="allmap" style="display:none"></div>
  <nav class="navbar navbar-inverse navbar-fixed-bottom" role="navigation">
    
    <div>
      <ul class="nav nav-pills navbar-nav">
        <li class="active"><a href="declare.htm">申购</a></li>
        <li><a href="myproperty.htm">我的</a></li>
        <li><a href="ranking.htm">排名</a></li>
        <li><a href="help.htm">帮助</a></li>
      </ul>
    </div>
  </nav>

  <!-- -->
  
  <script type="text/javascript">
  userid = getCookie("mono_userid");
  token = getCookie("mono_token");
  username = getCookie("mono_username");
  $("#info-username").replaceWith(username);

  $("#div-error").hide();
  $("#div-succ").hide();  
  $("#token").val(token);
 
  var c=document.getElementById("myCanvas");
  var ctx=c.getContext("2d");
  var img=document.getElementById("default-icon");
  ctx.drawImage(img,0,0);

  function showError(err) {
    $("#info-error").text(err);
    $("#div-succ").hide();
    $("#div-error").show();
    $("button").attr("disabled","true");
  }

  function submitData(e) {
    if (e != null)
      $("#puzzle").val(e.filepath);

    var formdata = $("#myform").serializeArray();
    $.post("do_apply.php",    
      formdata,    
      function(data,status){      
        //alert("Data: " + data + " nStatus: " + status);     
        var obj = JSON.parse(data);        
        if (obj.error == "0") {
           $("#div-error").hide();
           if (obj.ticket == "0") {
             $("#info-succ").text("申购成功！剩余资金$"+obj.balance);
           } else if (obj.ticket == "1") {
             $("#info-succ").text("闯关失败，"+obj.project+"属于"+obj.owner+",支付过路费$"+obj.bill+",剩余资金$"+obj.balance+".");
           } else if (obj.ticket == "2") {
             $("#info-succ").text("续约成功!");
           } else if (obj.ticket == "3") {
             $("#info-succ").text("闯关成功!");
           } else {
             $("#info-succ").text("资金不足!");
           }
           $("#div-succ").show();
        } else {   
           showError("申购失败("+obj.error+")！");
        }
        $("button").attr("disabled","true");
      }); 
  }

  $("button").click(function(){
      submitData(null);
    });
  
  </script>
</body>
</html>