<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Bord reservation til DIKULAN</title>	



	<?php 
		  $settingsDir = 'Data/';
		  include $settingsDir."settings.php";	
		  include $phpDir.'header.php'; 
	?>

</head>
<body onload="init()">
<div id="text">
<p>
Hvis du &oslash;nsker at &aelig;ndre en reservation, skal du blot reserve en ny plads med samme billet. <br/>
<br/>
&Oslash;nsker du derimod at aflyse en reservation, kan dette g&oslash;res <a href="Delete.php">her</a>.
</p>

</div>
<div id="mainView" > 
	<div id="errorView"> </div>
	<div>
		<?php include $phpDir.'themeChooser.php';?>
	</div>
	<div id="mainDiv">
		<?php 
			schemaInit();
		?>
	</div>
	<div id="fieldDiv"></div>
	<div id="validationDiv">
		<input type="button" onclick="submit()" value="Bestil pladser" id="submitButton"></input>
		<input type="button" onclick="reset()" value="Ryd bestilling" id="resetButton"></input>
	</div>
</div>

</body>
</html>