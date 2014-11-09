<?php	
//Denne fil bruges som en dynamisk CSS-fil
//Da tileSize ikke konstant (man har mulighed for ændre den i settings.php) er dette nødvendigt.

/* 
	Dependencies:
		-*dataDir*\settings.php
*/
?>
<style>
.seatText{
	height:<?php echo $tileSize ?>px;
	width:<?php echo $tileSize ?>px;
	background-color:transparent;
	text-align:center;
	font-size: <?php echo 0.6*$tileSize ?>;
	font-weight:900;
}



.reservationForm{
	width:275;
}

.textboxLabel, .seatLabel, .seatText{
	padding:0;
	margin:0;
}

.textbox{
	width:<?php echo $realRoomWidth-74?>;
}

.textboxLabel{
	width:29px;
	display:inline;
}

#errorView{
	width:<?php echo ($realRoomWidth*2+17)?>px;
}

#mainDiv{
	position:relative;
	width:<?php echo $realRoomWidth?>px;
	height:<?php echo $realRoomHeight?>px;
}



</style>