<?php
isset($_GET['width']) ? $width = $_GET['width'] : $width = 800;
isset($_GET['height']) ? $height = $_GET['height'] : $height = 600;
(isset($_GET['preload']) && strcmp($_GET['preload'], "false") == 0) ? $preload = false : $preload = true;
?>


<html>
<head>
<title>Projecter</title>
<script type="text/javascript">
var ajaxRequest;  // The variable that makes Ajax possible!
window.onload = init();

function init() {
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
	startTime();
	loadResponsibles();
	changeImage();
}

<?php if($preload): ?>
function preloadImage(imagePath, target) {
	var img = new Image();
	img.onload = function() { target.src = ajaxRequest.responseText; }
	img.src = imagePath;
}
<?php endif; ?>

function loadResponsibles() {
	obj = document.getElementById("responsibles_list");
	if (obj) {
		var now = new Date();
		time = checkTime(now.getHours())+checkTime(now.getMinutes())+checkTime(now.getSeconds());
		dateV = now.getFullYear()+'-'+checkTime(now.getMonth()+1)+'-'+checkTime(now.getDate());
		requestUrl = "Responsibles.php?date="+dateV+"&time="+time;
		ajaxRequest.open("GET", requestUrl, false);
		ajaxRequest.send(null);
		response = ajaxRequest.responseText;
		responsibles = JSON.parse(response);
		while(obj.hasChildNodes()) {
			obj.removeChild(obj.lastChild);
		}
		for(var i = 0; i < responsibles.length; i++) {
			resp = document.createElement("li");
			resp.innerHTML = '#'+responsibles[i]['table_id']+' - '+responsibles[i]['name'];
			obj.appendChild(resp);
		}
		setTimeout("loadResponsibles()",10000);
	} else {
		setTimeout("loadResponsibles()",500);
	}
}

function changeImage() {
	var target = 0;
	target = document.getElementById("imageContainer");
	
	if (target) {
		ajaxRequest.open("GET", "ProjekterScript.php", false);
		ajaxRequest.send(null);
		
		if(target.src != ajaxRequest.responseText) {
			<?php if($preload): ?>
				preloadImage(ajaxRequest.responseText, target);
			<?php else: ?>
				target.src = ajaxRequest.responseText;
			<?php endif; ?>
		}

		setTimeout("changeImage()", 2000);
	}
	else {
		setTimeout("changeImage()", 500);
	}
}

function checkTime(i) {
	if (i < 10) {
		i = "0" + i;
	} else {
		i = i.toString();
	}
	return i;
}

function startTime() {
	if (document.getElementById('time')) {
		var today = new Date();
		var h = today.getHours();
		var m = today.getMinutes();
		var s = today.getSeconds();
		// add a zero in front of numbers<10
		m = checkTime(m);
		s = checkTime(s);
		h = checkTime(h);
		document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
	}
	setTimeout("startTime()", 500);
}
</script>

<style type="text/css">
body {
	overflow: hidden;
	font-family: Tahoma, Sans-serif;
	font-size: 14pt;
}
div.topbar {
	
	position: absolute;
	background-color: #F0F0F0;
	border-bottom: 1px solid #C0C0C0;
	height: 30px;
	opacity: 0.5;
	width: 100%;
	clear: both;
}
div.topbar > div {
	padding: 5px;
}
ul#responsibles_list, ul#responsibles_list > li {
	display: inline;
}
ul#responsibles_list li {
	margin: 0 20px 0 20px;
	
}
div.topbar div#time {
	float: right;
}
</style>
</head>
<body style="padding: 0px; margin: 0px;">
<div id="content_image" style="padding: 0px; margin: 0px;">
<div class="topbar">
<div>
<div id="time"></div>
Vagter / ansvarlige i &oslash;jeblikket:
	<ul id="responsibles_list">
	</ul>
</div>
</div>
<img src="" alt="" id="imageContainer" width="<?php echo $width; ?>" height="<?php echo $height; ?>" />
</div>
</body>
</html>
