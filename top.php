<?php
include("db.php");

?>
<!DOCTYPE html>
<html>


<head>
    <title>DIKUlan PLAYGROUND</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="MainCSS.css" />
	
</head>
	
	
    <body>
	
	
		<div id="header-wrap">
		
			<div id="twitterLogo">
				<a href=<?php echo('admin' . $page) ?> > <img src="images/twitter.png" width="31" height="30"></a>
			</div>
			
			<div id="facebookLogo">
				<a href="https://www.facebook.com/events/1424545007805387/?fref=ts" target="_blank"> <img src="images/facebook.png" width="31" height="30"></a>
			</div>
			
			<header>
			<a href="index.php"> <img src="images/DIKULAN4.png"> </a>
			</header>
			
			
			<div id="menu">
				<div id="menuText">
			
				<a <?php  if ($page=='index.php') echo 'class="current"'; ?> href="index.php">Forside</a>
				<a <?php  if ($page=='galleri.php') echo 'class="current"'; ?> href="galleri.php">Galleri</a>
				<a <?php  if ($page=='bordreservation.php') echo 'class="current"'; ?> href="bordreservation.php">Bordreservation</a>
				<a <?php  if ($page=='projektor.php') echo 'class="current"'; ?> href="projektor.php">Projektor</a>
				<a <?php  if ($page=='turnering.php') echo 'class="current"'; ?> href="turnering.php">Tilmeld Turnering</a>
				
				</div>
			</div>
			
		</div>
