<style>
table {
	margin: -20px 0px 0px 0px;
	border-spacing: 0px 20px;
	font-size: 22px;
}
th, td {
	border: 1px solid;
	padding: 0px 10px;
	text-align: center;
	line-height: 2.5;
}
p.center {
	text-align: center;
}
</style>

<?php

include "Data/settings.php";
include "Php/databaseInteraction.php";

/* bubblesort because im fucking lazy*/
function doSort($array){
	for($i = 0; $i < sizeof($array); $i++){
		for($j = 0; $j < sizeof($array); $j++){
			if($array[$i][0] < $array[$j][0]){
				$temp = $array[$i];
				$array[$i] = $array[$j];
				$array[$j] = $temp;
			}
		}
	}
	return $array;
}

function fillReservations($array, $max_seat) {
	$newArray = array();
	$oldArrayCounter = 0;

	for($i = 1; $i <= $max_seat; $i++) {
		if($array[$oldArrayCounter][0] == $i) {
			$newArray[$i] = $array[$oldArrayCounter];
			$oldArrayCounter++;
		}
		else {
			$newArray[$i] = array($i, '', '');
		}
	}
	
	return $newArray;
}


$reservations = getReservations()->fetchAll();
$reservations = doSort($reservations);

if(isset($_GET[view]) && $_GET[view] == "wrapper") {
	echo '<p class="center"><a href="' . $_SERVER["SCRIPT_URI"] . '" target="_blank">Click here for full view</a></p>', PHP_EOL;
	
}
else {
	$reservations = fillReservations($reservations, 80);
	//print_r($reservations);
}


function fixString($str) {
	$str = str_replace(array('æ','ø','å'), array('&aelig;','&oslash;','&aring;'), $str);
	return $str;
}

?>
<table>
<tr> 
<th>Plads nummer</th>
<th>Navn</th>
<th>Billet ID</th>
</tr>
<?php

foreach($reservations as $row) {
	$seat_number = ((isset($_GET[view]) && $_GET[view] == "wrapper") ? $row[0] : fixString($row[0]));
	$name = ((isset($_GET[view]) && $_GET[view] == "wrapper") ? $row[2] : fixString($row[2]));
	$ticket_id = ((isset($_GET[view]) && $_GET[view] == "wrapper") ? $row[1] : fixString($row[1]));
	
	?>
	<tr>
	<td> <?php echo $seat_number;?> </td>
	
	<?php if(!empty($ticket_id)) { ?>
		<td> <?php echo $name;?> </td>
		<td> <?php echo $ticket_id;?> </td>
	<?php } else { ?>
		<td colspan="2">Pladsen er ikke reserveret</td>
	<?php } ?>
	
	</tr>
	
	<?php
}
?>

</table>




