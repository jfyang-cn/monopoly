
function setCookie(cname,cvalue,exdays)
{
  var d = new Date();
  d.setTime(d.getTime()+(exdays*24*60*60*1000));
  var expires = "expires="+d.toGMTString();
  document.cookie = cname+"="+cvalue+"; "+expires;
}

function getCookie(cname)
{
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for(var i=0; i<ca.length; i++) 
  {
    var c = ca[i].trim();
    if (c.indexOf(name)==0) return c.substring(name.length,c.length);
  }
  return "";
}

function delCookie(cname){
  var date = new Date(); 
  date.setTime(date.getTime() - 10000); 
  document.cookie = cname + "=a; expires=" + date.toGMTString(); 
} 

function logout()
{
  delCookie("mono_userid");
  delCookie("mono_token");
  delCookie("mono_username");

  location.href="login.htm";
}

function GetRequest() { 
  var url = location.search; //获取url中"?"符后的字串 
  var theRequest = new Object(); 
  if (url.indexOf("?") != -1) { 
    var str = url.substr(1); 
    strs = str.split("&"); 
    for(var i = 0; i < strs.length; i ++) { 
      theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]); 
    } 
  }
 
  return theRequest; 
} 

function showTips(txt, ok) {
  if (txt == "") {
    $("#div-succ").hide();
    $("#div-error").hide();
  } else {
    if (ok) {
      $("#info-succ").text(txt);
      $("#div-succ").show();
      $("#div-error").hide();
    } else {
      $("#info-error").text(txt);
      $("#div-succ").hide();
      $("#div-error").show();
    }
  }
}

function usernameVerifier() {

  var v = $("#username").val();
  if (v.length>40 || v.length<6) {
    showTips("用户名不能少于6个字符或超过40个字符", false);
    $("button").attr("disabled", true);
    return false;
  }

  var n = v.match(/[^a-zA-Z0-9@]/gi);
  if (n!=null) {
    showTips("用户名只能包含数字0-9，大小写字母和@符", false);
    $("button").attr("disabled", true);
    return false;
  }

  showTips("");
  $("button").removeAttr("disabled");
  return true;
}

function passwordVerifier() {
  
  var v = $("#password").val();
  if (v.length>40 || v.length<6) {
    showTips("密码不能少于6个字符或超过40个字符", false);
    $("button").attr("disabled", true);
    return false;
  }

  var n = v.match(/[^a-zA-Z0-9@]/gi);
  if (n!=null) {
    showTips("密码只能包含数字0-9，大小写字母", false);
    $("button").attr("disabled", true);
    return false;
  }

  showTips("");
  $("button").removeAttr("disabled");
  return true;
}

function showOrHideCity() {
  if ($("#showOrHideCity").text()=="展开城市") {
    $(".mycity").removeClass("city");
    $("#showOrHideCity").text("隐藏城市");
  } else {
    $(".mycity").addClass("city");
    $("#showOrHideCity").text("展开城市");
  }
}

function drawOnCanvas(file) 
{ 
  var reader = new FileReader(); 
  reader.onload = function (e) { 
    var dataURL = e.target.result, 
    canvas = document.querySelector('canvas'), 
    ctx = canvas.getContext('2d'), 
    img = new Image(); 
    img.onload = function() { 
      //var square = 240; 
      canvas.width = 240; 
      canvas.height = 240; 
      var context = canvas.getContext('2d'); 
      context.clearRect(0, 0, 240, 240); 
      var imageWidth=240; 
      var imageHeight=240; 
      var offsetX = 0; 
      var offsetY = 0; 

      /*if (this.width > this.height) { 
        imageWidth = Math.round(240* this.width / this.height); 
        imageHeight = 320; 
        offsetX = - Math.round((imageWidth - 240) / 2); 
      } else { 
        imageHeight = Math.round(320* this.height / this.width); 
        imageWidth = 240; 
        offsetY = - Math.round((imageHeight - 320) / 2); 
      }*/

      if (this.width > this.height) {
        imageWidth = this.height;
        imageHeight = this.height;
        offsetX = Math.round((this.width-this.height)/2);
      } else {
        imageWidth = this.width;
        imageHeight = this.width;
        offsetY = Math.round((this.height-this.width)/2);
      }
      context.drawImage(this, offsetX, offsetY, imageWidth, imageHeight, 0, 0, canvas.width, canvas.height);
      //context.drawImage(this, offsetX, offsetY, imageWidth, imageHeight);
      var base64 = canvas.toDataURL('image/jpeg',0.5); 
      //$('#j_thumb').val(base64.substr(22)); 
    }; 
    
    img.src = dataURL; 
  }; 

  reader.readAsDataURL(file); 
}

function fileOnChange(e)
{
  var input = document.querySelector('input[type=file]');
  var file = input.files[0]; 
  drawOnCanvas(file);
}

function progressFunction(evt) {
  var progressBar = document.getElementByIdx_x_x("progressBar");
  if (evt.lengthComputable) {
    progressBar.max = evt.total;      
    progressBar.value = evt.loaded;              
  }
}

function upladFile() {

  //var fileObj = document.getElementByIdx_x_x("file").files[0]; // js 获取文件对象
  var FileController = "do_upload.php";                    // 接收上传文件的后台地址 

  // FormData 对象
  var form = document.querySelector('form');
  //form.append("author", "hooyes");                        // 可以增加表单数据
  //form.append("file", fileObj);                           // 文件对象

  // XMLHttpRequest 对象
  var xhr = new XMLHttpRequest();
  xhr.open("post", FileController, true);
  xhr.onload = function () {
    alert("上传完成!");
  };

  xhr.upload.addEventListener("progress", progressFunction, false);           
  xhr.send(form);
}

function progressFunction(evt) {

  var progressBar = document.getElementByIdx_x_x("progressBar");
  var percentageDiv = document.getElementByIdx_x_x("percentage");

  if (evt.lengthComputable) {
    progressBar.max = evt.total;
    progressBar.value = evt.loaded;
    percentageDiv.innerHTML = Math.round(evt.loaded / evt.total * 100) + "%";
  }
}  

// 上传图片，jQuery版 
function sendImage(imgtype, callback){ 
  // 获取 canvas DOM 对象 
  var canvas = document.getElementById("myCanvas"); 
  // 获取Base64编码后的图像数据，格式是字符串 
  // "data:image/png;base64,"开头,需要在客户端或者服务器端将其去掉，后面的部分可以直接写入文件。 
  var dataurl = canvas.toDataURL("image/png"); 
  // 为安全 对URI进行编码 
  // data%3Aimage%2Fpng%3Bbase64%2C 开头 
  var imagedata = encodeURIComponent(dataurl); 
  var url = "do_uploadimg.php"; 
  // 1. 如果form表单不好处理,可以使用某个hidden隐藏域来设置请求地址 
  // <input type="hidden" name="action" value="receive.jsp" /> 
  //var url = $("input[name='action']").val(); 
  // 2. 也可以直接用某个dom对象的属性来获取 
  // <input id="imageaction" type="hidden" action="receive.jsp"> 
  // var url = $("#imageaction").attr("action"); 
  // 因为是string，所以服务器需要对数据进行转码，写文件操作等。 
  // 个人约定，所有http参数名字全部小写 
  console.log(dataurl); 
  //console.log(imagedata);
  token = getCookie("mono_token");
  
  var data = { 
    imagename: "myImage.png", 
    imagedata: imagedata,
    token: token,
    imgtype: imgtype
  }; 

  jQuery.ajax({ 
    url : url, 
    data : data, 
    type : "POST", 
    // 期待的返回值类型 
    dataType: "json", 
    complete : function(xhr,result) { 
      //alert(xhr.responseText); 
      var $tip2 = $("#tip2"); 
      if(!xhr){ 
        $tip2.text('网络连接失败!'); 
        return false; 
      }

      var text = xhr.responseText; 
      if(!text){ 
        $tip2.text('网络错误!'); 
        return false; 
      } 
 
      var obj = JSON.parse(text );
      if (obj.error == "0") {
        $tip2.text('上传成功!');
        if (callback != null)
          callback(obj);
      } else {
        $tip2.text('上传失败('+obj.error+')!');
      }
      
      //console.dir(json); 
      //console.log(xhr.responseText); 
    } 
  }); 
}

function faceReg(imgtype, username){ 
  // 获取 canvas DOM 对象 
  var canvas = document.getElementById("myCanvas"); 
  // 获取Base64编码后的图像数据，格式是字符串 
  // "data:image/png;base64,"开头,需要在客户端或者服务器端将其去掉，后面的部分可以直接写入文件。 
  var dataurl = canvas.toDataURL("image/png"); 
  // 为安全 对URI进行编码 
  // data%3Aimage%2Fpng%3Bbase64%2C 开头 
  var imagedata = encodeURIComponent(dataurl); 
  var url = "do_facereg.php"; 
  // 1. 如果form表单不好处理,可以使用某个hidden隐藏域来设置请求地址 
  // <input type="hidden" name="action" value="receive.jsp" /> 
  //var url = $("input[name='action']").val(); 
  // 2. 也可以直接用某个dom对象的属性来获取 
  // <input id="imageaction" type="hidden" action="receive.jsp"> 
  // var url = $("#imageaction").attr("action"); 
  // 因为是string，所以服务器需要对数据进行转码，写文件操作等。 
  // 个人约定，所有http参数名字全部小写 
  console.log(dataurl); 
  //console.log(imagedata);
  token = getCookie("mono_token");
  
  var data = { 
    imagename: "myImage.png", 
    imagedata: imagedata,
    token: token,
    username: username,
    imgtype: imgtype
  }; 

  jQuery.ajax({ 
    url : url, 
    data : data, 
    type : "POST", 
    // 期待的返回值类型 
    dataType: "json", 
    complete : function(xhr,result) { 
      //alert(xhr.responseText); 
      if(!xhr){ 
        return false; 
      }

      var text = xhr.responseText; 
      if(!text){ 
        return false; 
      } 
 
      var obj = JSON.parse(text );
      if (obj.error == "0") {
        showTips("注册成功！3秒后返回登录页面", true);
        setTimeout("location.href = 'login.htm';", 3000);
      } else {
        showTips("注册失败！", false);
      }
      
      //console.dir(json); 
      //console.log(xhr.responseText); 
    } 
  }); 
}

function faceIn(imgtype, username){ 
  // 获取 canvas DOM 对象 
  var canvas = document.getElementById("myCanvas"); 
  // 获取Base64编码后的图像数据，格式是字符串 
  // "data:image/png;base64,"开头,需要在客户端或者服务器端将其去掉，后面的部分可以直接写入文件。 
  var dataurl = canvas.toDataURL("image/png"); 
  // 为安全 对URI进行编码 
  // data%3Aimage%2Fpng%3Bbase64%2C 开头 
  var imagedata = encodeURIComponent(dataurl); 
  var url = "do_facein.php"; 
  // 1. 如果form表单不好处理,可以使用某个hidden隐藏域来设置请求地址 
  // <input type="hidden" name="action" value="receive.jsp" /> 
  //var url = $("input[name='action']").val(); 
  // 2. 也可以直接用某个dom对象的属性来获取 
  // <input id="imageaction" type="hidden" action="receive.jsp"> 
  // var url = $("#imageaction").attr("action"); 
  // 因为是string，所以服务器需要对数据进行转码，写文件操作等。 
  // 个人约定，所有http参数名字全部小写 
  console.log(dataurl); 
  //console.log(imagedata);
  token = getCookie("mono_token");
  
  var data = { 
    imagename: "myImage.png", 
    imagedata: imagedata,
    token: token,
    username: username,
    imgtype: imgtype
  }; 

  jQuery.ajax({ 
    url : url, 
    data : data, 
    type : "POST", 
    // 期待的返回值类型 
    dataType: "json", 
    complete : function(xhr,result) { 
      //alert(xhr.responseText); 
      if(!xhr){ 
        return false; 
      }

      var text = xhr.responseText; 
      if(!text){ 
        return false; 
      } 
 
      var obj = JSON.parse(text );
      if (obj.error == "0") {
        $("#div-error").hide();
        setCookie("mono_token", obj.token, 30);
        setCookie("mono_userid", obj.userid, 30);
        setCookie("mono_username", obj.username, 30);
        setCookie("mono_avatar", obj.avatar, 30)
        location.href = "battle.htm";
      } else {
        $("#div-error").show();
      }
      
      //console.dir(json); 
      //console.log(xhr.responseText); 
    } 
  }); 
}

function faceSet(imgtype){ 
  // 获取 canvas DOM 对象 
  var canvas = document.getElementById("myCanvas"); 
  // 获取Base64编码后的图像数据，格式是字符串 
  // "data:image/png;base64,"开头,需要在客户端或者服务器端将其去掉，后面的部分可以直接写入文件。 
  var dataurl = canvas.toDataURL("image/png"); 
  // 为安全 对URI进行编码 
  // data%3Aimage%2Fpng%3Bbase64%2C 开头 
  var imagedata = encodeURIComponent(dataurl); 
  var url = "do_setting.php"; 
  // 1. 如果form表单不好处理,可以使用某个hidden隐藏域来设置请求地址 
  // <input type="hidden" name="action" value="receive.jsp" /> 
  //var url = $("input[name='action']").val(); 
  // 2. 也可以直接用某个dom对象的属性来获取 
  // <input id="imageaction" type="hidden" action="receive.jsp"> 
  // var url = $("#imageaction").attr("action"); 
  // 因为是string，所以服务器需要对数据进行转码，写文件操作等。 
  // 个人约定，所有http参数名字全部小写 
  console.log(dataurl); 
  //console.log(imagedata);
  token = getCookie("mono_token");
  
  var data = { 
    imagename: "myImage.png", 
    imagedata: imagedata,
    token: token,
    imgtype: imgtype
  }; 

  jQuery.ajax({ 
    url : url, 
    data : data, 
    type : "POST", 
    // 期待的返回值类型 
    dataType: "json", 
    complete : function(xhr,result) { 
      //alert(xhr.responseText); 
      if(!xhr){ 
        return false; 
      }

      var text = xhr.responseText; 
      if(!text){ 
        return false; 
      } 
 
      var obj = JSON.parse(text );
      if (obj.error == "0") {
        showTips("头像修改成功", true);
      } else {
        showTips("头像修改失败", false);
      }  
      
      //console.dir(json); 
      //console.log(xhr.responseText); 
    } 
  }); 
}

function changePass() {

  token = getCookie("mono_token");
  var pass = $("#password").val();

  var v = pass;
  if (v.length>40 || v.length<6) {
    showTips("密码不能少于6个字符或超过40个字符", false);
    return false;
  }

  var n = v.match(/[^a-zA-Z0-9@]/gi);
  if (n!=null) {
    showTips("密码只能包含数字0-9，大小写字母", false);
    return false;
  }

  showTips("");
  
  var data = { 
    password: pass,
    token: token
  }; 

  jQuery.ajax({ 
    url : "do_setting.php", 
    data : data, 
    type : "POST", 
    // 期待的返回值类型 
    dataType: "json", 
    complete : function(xhr,result) { 
      //alert(xhr.responseText); 
      if(!xhr){ 
        return false; 
      }

      var text = xhr.responseText; 
      if(!text){ 
        return false; 
      } 

      var obj = JSON.parse(text );
      //alert(text);
      if (obj.error == "0") {
        showTips("密码修改成功", true);
      } else {
        showTips("密码修改失败", false);
      }      
    } 
  }); 

  return true;
}

function requestPuzzle(project, longitude, latitude) {

  var c=document.getElementById("myCanvas");
  var ctx=c.getContext("2d");
  var img=document.getElementById("default-icon");
  ctx.drawImage(img,61,86);

  token = getCookie("mono_token");
  
  var data = { 
    project: project, 
    longitude: longitude,
    latitude: latitude,
    token: token
  }; 

  jQuery.ajax({ 
    url : "get_puzzle.php", 
    data : data, 
    type : "POST", 
    // 期待的返回值类型 
    dataType: "json", 
    complete : function(xhr,result) { 
      //alert(xhr.responseText); 
      if(!xhr){ 
        return false; 
      }

      var text = xhr.responseText; 
      if(!text){ 
        return false; 
      } 

      var obj = JSON.parse(text );
      //alert(text);
      if (obj.error == "0") {
        if (obj.ticket == "2") {
          if (obj.puzzle != "") {
            $("#default-icon").attr("src", obj.puzzle);
            var c=document.getElementById("myCanvas");
            var ctx=c.getContext("2d");
            var img=document.getElementById("default-icon");
            ctx.drawImage(img,0,0);
          }

          $("#answer").val(obj.answer);
          $("#answer").attr("disabled","true");
          $("#myCanvas").attr("disabled","true");
          $("button").text("续约");          
        } else if (obj.ticket == "1") {
          $("#default-icon").attr("src", obj.puzzle);
          var c=document.getElementById("myCanvas");
          var ctx=c.getContext("2d");
          var img=document.getElementById("default-icon");
          ctx.drawImage(img,0,0);
          $("#myCanvas").attr("disabled","true");
          $("button").text("闯关");
        } else {
          $("button").text("申购");
        }
      } else {
        ;
      }      
    } 
  }); 

}


