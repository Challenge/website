<?php
//Dette svare til <head> </head> sectionen i et normalt html dokument.
//Her kan man få et overblik over alle PHP,CSS og JS filer der bruges

/*
	Dependencies:
		- *dataDir*\settings.php
*/
?>

<LINK href="<?php echo $dataDir;?>style.css" rel="stylesheet" type="text/css">	
<?php

	include $dataDir.'style.php';
	include $phpDir.'databaseInteraction.php';
	include $phpDir.'schema.php'; 
	include $phpDir.'submit.php';	
	include $phpDir.'reservationFields.php';

?>

<script type="text/javascript" src="<?php echo $jsDir; ?>selected.js"> </script>
<script type="text/javascript"> 

	/*Makes a basic link with all the need informations 
	 this includes:
		- current theme
		- currently selected seats
	*/
	function makeQueryString(){
	
		var temp = document.getElementById("themeMenu");
	
		var style = "style="+temp.value;
		var selectedStr = selected.toString();
		return "<?php global $root; echo $root;?>"+"?" + style + selectedStr;
			
	}


	/* Initialize all the necesary parts of the reservation system front-end
	 * this function is called when a user loads the page, submits or change the theme.
	 */
	function init(){
	
	
	<?php
	/* sets the value of the theme-selector (the dropdown menu) */
	if(isset($_REQUEST['style'])){?>
		document.getElementById('themeMenu').value = "<?php echo htmlentities($_REQUEST['style']);?>";
	<?php
	} else {
	global $defualtTheme;
	?>
		document.getElementById('themeMenu').value = "<?php echo htmlentities($defaultTheme);?>";
	<?php
	}
	
	/* Selected all the previously selected seats */
		for($i = 0; isset($_REQUEST['selected'.$i]);$i++){
			$id = explode('_',htmlentities($_REQUEST['selected'.$i]));
			$seat = $id[0];
			$name = $id[1];
			$ticket = $id[2];
		
			echo 'clickSeat("'.$seat.'",0);';	
			
			if($name == 'undefined'){
				$name = $defaultTextboxTextName;
			}
			
			if($ticket == 'undefined'){
				$ticket = $defaultTextboxTextTicket;
			}
			
			echo 'setTextBoxValue('.$seat.',"'.$name.'","'.$ticket.'");';

		}
		/* calls the init function located in *phpDir*\submit.php */
			init();			
		
	?>			
	}

</script>

<script type="text/javascript" src="<?php echo $jsDir; ?>roomEvents.js"> </script>
<script type="text/javascript" src="<?php echo $jsDir; ?>submit.js"> </script>

