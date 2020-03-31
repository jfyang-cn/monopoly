<?php
header("Content-type: text/html; charset=utf-8");

$longitude = isset($_GET['longitude']) ? htmlspecialchars($_GET['longitude']) : '';
$latitude = isset($_GET['latitude']) ? htmlspecialchars($_GET['latitude']) : '';

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<style type="text/css">
		body, html,#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;}
		#golist {display: none;}
		@media (max-device-width: 780px){#golist{display: block !important;}}
	</style>
	<script type="text/javascript" src="http://api.map.baidu.com/api?type=quick&ak=xUliin2HEtAYliZ0Vfk7mvkv&v=1.0"></script>
	<title>地图官网展示效果</title>
</head>
<body>
	<a id="golist" href="myproperty.htm">返回</a>
	<div id="allmap"></div>
</body>
</html>
<script type="text/javascript">
  var longitude = <?php echo $longitude; ?>;
  var latitude = <?php echo $latitude ; ?>;

	// 百度地图API功能
	var map = new BMap.Map("allmap");            // 创建Map实例
	var point = new BMap.Point(longitude, latitude); // 创建点坐标
	map.centerAndZoom(point,15);                 // 初始化地图,设置中心点坐标和地图级别。
	map.addControl(new BMap.ZoomControl());      //添加地图缩放控件

  //创建小狐狸
	var pt = new BMap.Point(longitude, latitude);
	var myIcon = new BMap.Icon("http://developer.baidu.com/map/jsdemo/img/fox.gif", new BMap.Size(300,157));
	var marker2 = new BMap.Marker(pt,{icon:myIcon});  // 创建标注
	map.addOverlay(marker2);              // 将标注添加到地图中
</script>
