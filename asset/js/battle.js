
 $("td.clabel").css("text-align", "center");
 
 var soldiers = [1,2,2,3,3,3];  
 var cur=0; 
 var deploys = new Array(); 
 var teams=3
 
 var missiles = new Array();
 var rounds = 5;
 //var deployString = "B-2,C-2,C-3,G-2,G-4,G-3,G-5,F-6,H-4,E-7,C-9,D-9,E-9,F-9,G-9";
 
 // test
 //var injects = deployString.split(",");
 //var l=0;
 //for (l=0;l<injects.length;l++) {   
 //  onCellClick(document.getElementById(injects[l]));
 //}
 
 function assignSoldier(obj, noti) {
   
   if (cur>=soldiers.length)
     return;    
   
   var i=0;   
   for (i=0;i<deploys.length;i++) {
     if (deploys[i]==obj.id) {
       //alert(deploys[i]);
       showTips("请不要相同位置重复部署", false);
       return;
     }
   }
   
   $("#"+obj.id).css('background-color','green');
   $("#"+obj.id).css('text-align','center');   
   $("#"+obj.id).text(soldiers[cur]);
   deploys.push(obj.id);
   cur++;

   showTips("");

   if (cur==soldiers.length && noti) {
     $("table").trigger("onSubmit");
   }   
 }
 
 function launchMissile(obj) {
   
   var a=obj.id.split("-");
   var c=a[0];
   var r=a[1];
   
   if (rounds--<=0)
   {
     showTips("弹尽粮绝了！", false);
     return;
   }  

   for (i=0;i<3;i++)
   {
     for (j=0;j<3;j++)
     {
       if (c.charCodeAt()-1+j<"A".charCodeAt()
       || c.charCodeAt()-1+j>"I".charCodeAt()
       || r.charCodeAt()-1+i<"1".charCodeAt()
       || r.charCodeAt()-1+i>"9".charCodeAt())
       {
         continue;
       }
       
       var newid=String.fromCharCode(c.charCodeAt()-1+j)+"-"+String.fromCharCode(r.charCodeAt()-1+i);              
       $("#"+newid).css('background-color','red');
       
       if ($.inArray(newid, missiles)<0)
       {
         missiles.push(newid);
       
         var k=$.inArray(newid, deploys);       
         if (k>=0) {
           $("#info-team"+soldiers[k]).text($("#info-team"+soldiers[k]).text()-1);
         }
       }
     }
   }
   
   $("span.tinfo").trigger("onChange");
 }
 
 
 function onResetClick() {
    $("td.cmap").text('');
    $("td.cmap").css('background-color','');
    cur=0;
    deploys=[];
 }
 
 function onUndoClick() {
   if (cur>0)
   {
     $("#"+deploys[cur-1]).css("background-color","");
     $("#"+deploys[cur-1]).text("");
     deploys.pop();
     cur--;
   }
 }
 
 function markError(num) {
   var i=0;
   
   for (i=0;i<deploys.length;i++)
   {
     if (num==soldiers[i])
     {
       $("#"+deploys[i]).css("background-color","red");
       showTips("部署错误，相同小队的士兵必须成直线排在一起（横，竖，斜）", false);
     }
   }
 }
 
 function removeMark(num) {
   var i=0;
   
   for (i=0;i<deploys.length;i++)
   {
     if (num==soldiers[i])
     {
       $("#"+deploys[i]).css("background-color","green");       
     }
   }
 }
 
 function getDeployString() {
   var delta=soldiers.length-deploys.length;
   if (delta>0)
   {
     showTips("还有"+delta+"个士兵没有部署", false);
   }
   
   var err = 0;
   var a = new Array();
   var j=0;
   var k=0;
   var i=0;
   for (j=0;j<teams;j++) {
     for (;i<soldiers.length;i++) {
       if (j+1==soldiers[i]) {
         a[k++]=deploys[i];        
       } else {         
         if (!checkSoldiers(a)) {
           markError(j+1);
           err++;
         } else {
           removeMark(j+1);
         }
         
         a=[];
         k=0;
         break;
       }
     }
     
     if (i==soldiers.length) {
       if (!checkSoldiers(a)) {
         markError(j+1);
         err++;
       } else {
         removeMark(j+1);
       }      
       break;
     }    
   }

   if (err!=0)
     return "";
   else
     return deploys.join();
 }
 
 function checkSoldiers(a) {

   if (a.length<2)
     return true;
   
   a.sort();   
   
   var i=0;
   for (i=0;i<a.length;i++)
   {
     a[i]=a[i].split("").reverse().join("");     
   }  

   if (a[0].charAt(0).charCodeAt()==a[1].charAt(0).charCodeAt()) {     
     for (i=0;i<a.length-1;i++) {
       if (!(a[i].charAt(0).charCodeAt()==a[i+1].charAt(0).charCodeAt()
       && a[i].charAt(2).charCodeAt()+1==a[i+1].charAt(2).charCodeAt())){
         //$("#info-error").text(a.join());
         return false;
       }
     }
   } else {   
      for (i=0;i<a.length-1;i++) {      
       if (!((a[i].charAt(0).charCodeAt()+1==a[i+1].charAt(0).charCodeAt()
       && a[i].charAt(2).charCodeAt()+1==a[i+1].charAt(2).charCodeAt())
       || (a[i].charAt(0).charCodeAt()+1==a[i+1].charAt(0).charCodeAt() 
       && a[i].charAt(2).charCodeAt()==a[i+1].charAt(2).charCodeAt())
       || (a[i].charAt(0).charCodeAt()-1==a[i+1].charAt(0).charCodeAt() 
       && a[i].charAt(2).charCodeAt()==a[i+1].charAt(2).charCodeAt())
       || (a[i].charAt(0).charCodeAt()-1==a[i+1].charAt(0).charCodeAt() 
       && a[i].charAt(2).charCodeAt()+1==a[i+1].charAt(2).charCodeAt())
       )){
         //$("#info-error").text(a.join());
         return false;
       }
     }
   }
   
   return true;
 }


function requestDeploy(battleid, project, longitude, latitude) {

  token = getCookie("mono_token");
  
  var data = {
    id: battleid,
    project: project, 
    longitude: longitude,
    latitude: latitude,
    token: token
  }; 

  jQuery.ajax({ 
    url : "get_deploy.php", 
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
        $("#info-project").text(obj.project);
        if (obj.ticket == "2") {
          if (obj.deploy != "") {
            var rets=obj.deploy.split(",");
            var l=0;
            for (l=0;l<rets.length;l++) {   
             assignSoldier(document.getElementById(rets[l]), false);
            }
          }
          $("#id").val(obj.id);
          $("td.cmap").attr("onclick", "assignSoldier(this,true);");
          $("#div-tinfo").hide();
        } else if (obj.ticket == "1") {
          deploys = obj.deploy.split(",");
          $("td.cmap").attr("onclick", "launchMissile(this);");
          $("#id").val(obj.id);
          $("#div-btn").hide();
        } else {
          $("td.cmap").attr("onclick", "assignSoldier(this,true);");
          $("#div-tinfo").hide();
        }
      } else {
        ;
      }      
    } 
  }); 

}

function sendWin(battleid) {

  token = getCookie("mono_token");

  var data = { 
    token: token,
    id: battleid,
  }; 

  jQuery.ajax({ 
    url : "do_battle.php", 
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

      var obj = JSON.parse(text);
      //alert(text);
      if (obj.error == "0") {
        showTips("成功占领城池，3秒后跳转到部署页面", true);
        setTimeout("location.href='battle.htm?id="+obj.id+"'", 3000);
      } else if (obj.error == "1") {
        showTips("尚未登录或已过期需重新登录，3秒后跳转到登录页面", false);
        setTimeout("location.href='login.htm'", 3000);
      } else {
        showTips("数据更新错误", false);
      }      
    } 
  }); 

}
