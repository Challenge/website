<?php 
/* 
	Dependencies:
		- *dataDir*\settings.php
		- *phpDir*\submit.php
*/


/* the List of themes */
$options = array(
"Standard" => "Graphics/roomStandardStyle.css",
"Zelda" => "Graphics/roomZeldaStyle.css"
);

?>

<script type="text/javascript"> 

/* Changes the current theme by reloading the page with a new query string */
function themeChange(){

	var temp = document.getElementById("themeMenu");

	<?php 
	global $showErrors;
	?>
	
	/* if error = 0, errors won't be visible */
	var error =  <?php echo $showErrors;?>;
	
	window.location.href = makeQueryString()+"&showErrors="+error;
	
}

</script>


<p>
Tema for bordplanen: <select id="themeMenu" onChange="themeChange()">
<?php 
	/* creates an option in the dropdown menu foreach element in the options-array */
foreach($options as $option => $value){
	echo "<option value=\"$option\">$option</option>".PHP_EOL;
}

?>

</select>
</p>