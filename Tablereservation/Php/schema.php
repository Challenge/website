<?php
/* I denne fil bliver hele oprettelsen af skemaet behandlet
 * - Der sørges for at hente informationer om hvilke plader der er taget.
 * - At de plader der er oprettet i room.php ($content arrayet) bliver initialiseret
 */
 
 
/* 
	Dependencies:
		- *dataDir*\settings.php
*/
 
 /* has the style been defined in the query string?.
	if true, set values in settings.php
 */
if(isset($_REQUEST['style'])){
	$room = $dataDir.$_REQUEST['style'].".php";
	$roomStyle = $graphicDir.$_REQUEST['style']."Style.css";
} 


/* Use css file (roomStyle) defined settings.php */
 ?>
 <LINK id="roomStyle" href="<?php echo $roomStyle;?>" rel="stylesheet" type="text/css">	
 
<?php 
/* Use php file (room) defined settings.php */
 include $room;
 
 /* an array containg all occupied seats*/
 $takenArray = getTakenSeats();
 
 
 /*initialize the shcema/table-plan */
function schemaInit(){
	global $content;
	
	$i = 1;
	while(isset($content['seat'.$i])){			
		makeSeat($i);
		$i++;
	}

	/* this function is located in the file defined by $room. 
	 * it adds all the elements from the content array, also defined in $room
	 */
 	echoContent();
}
 
 /* Initialize the given seat, by changeing the values in the content array (defined in $room) */
function makeSeat($seatNumber){
	global $takenArray,$content;
	$seat = 'seat'.$seatNumber;
	
	if(isset($takenArray[$seatNumber])){
		$content[$seat]['class'] = "tile seat-taken unclickable";			
		$content[$seat]['title'] = $content[$seat]['title'].'
Reserveret af: '. $takenArray[$seatNumber][0];
	} else {
		$content[$seat]['onmouseover'] = "onMouseOver($seatNumber)";
		$content[$seat]['onmouseout'] = "onMouseLeave($seatNumber)";
		$content[$seat]['onclick'] = "clickSeat($seatNumber,0)";		
	}
}

?>