<?php
include("db.php");
?>
<!DOCTYPE html>
<html>

<!-- Hele Top2.php står for designet af hjemmesiden, samt menu'en -->

<head>
    <title>DIKUlan PLAYGROUND</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="MainCSS.css" />
</head>
	
	
    <body>
	
	
		<div id="header-wrap">
		
			<div id="twitterLogo">
				<a href=<?php echo($page) ?> > <img src="images/twitter.png" width="31" height="30"></a>
			</div>
			
			<div id="facebookLogo">
				<a href="https://www.facebook.com/events/1424545007805387/?fref=ts" target="_blank"> <img src="images/facebook.png" width="31" height="30"></a>
			</div>
			
			<div id="facebookLogo">
									<form action="logout.php" method="post">
							<input type="submit" name="Submit" value="Logout">
                        </form>
			</div>
			
			<header>
			<a href="index.php"> <img src="images/DIKULAN4.png"> </a>
			</header>
			
			
			<div id="menu">
				<div id="menuText2">
			
				<a <?php  if ($page=='adminindex.php') echo 'class="current"'; ?> href="adminindex.php">Ret forside</a>
				<a <?php  if ($page=='admingalleri.php') echo 'class="current"'; ?> href="admingalleri.php">Ret galleri</a>
				<a <?php  if ($page=='adminbordreservation.php') echo 'class="current"'; ?> href="adminbordreservation.php">Ret bordreservation</a>
				<a <?php  if ($page=='adminvagtplan.php') echo 'class="current"'; ?> href="adminvagtplan.php">Ret vagtplan</a>
				<a <?php  if ($page=='adminturnering.php') echo 'class="current"'; ?> href="adminturnering.php">Ret turneringer</a>
				
				
				</div>
			</div>
			
		</div>
