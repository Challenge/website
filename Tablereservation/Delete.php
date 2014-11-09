
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Bord reservation til DIKULAN</title>	

<?php 
      include "Data/settings.php";
      include $phpDir.'databaseInteraction.php';  
?>

</head>
<script type="text/javascript"> 

/* redirects the user to the current page, but with a new querystring 
   containing which reservation to delete
   */
	function onClick(){
	/* MAKE URL */
		var textboxText = document.getElementById("deleteTextBox").value;
		
		window.location.href = '?delete='+textboxText;
	}	
	
	
</script>

<body>
<div id="text">
<p> 
Indtast billet ID for den reservation du gerne vil slette nedenfor. </br>
Tryk derefter "Slet reservation" for slette reservationen lavet til det indtastede billet ID.
</p>

</div>
<div id="mainView" > 
	<div id="mainDiv">
		<input id="deleteTextBox" class="textbox" type="text" value="Indtast billet id"> </input>
		<input id="deleteButton" class="button" type="button" value="Slet reservation" onclick="onClick()"> </input>
		
			<?php
		
		/* if the user has pressed delete*/
		if(isset($_REQUEST['delete'])){
			$delete = $_REQUEST['delete'];

			/* does the given ticket ($delete) exist? */
			if(ticketExist($delete) == 1){
				$succes = cancelReservation($delete);
			}else{
				$succes = 'No such ticket';
			}
				
			/* if it the reservation was canceled print succes messege else print fail message */			
			if($succes == '00000'){
			?>		
					<p> Reservationen for billet id: <?php echo htmlentities($delete)?> er nu blevet annueleret </p>	
			<?php
				}else{ 
			?>
					<p> Der er ikke nogen reservation til billet id:  <?php echo htmlentities($delete)?></p>	
			<?php
				}
			}
			?>
	</div>
</div>

</body>
</html>