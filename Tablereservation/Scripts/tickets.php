<?php
include "../Data/dbcon.php";

$inputPath = 'tickets.txt';
$input = file($inputPath,FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
$ticketArray = array();
$i = 0;

foreach ($input as $line_num => $line) {
	$info = explode('|',$line);

	$ticketArray[$i] = $info[0];
	$i++;
}


function printTickets(){
global $ticketArray;
	$i = 1;
	foreach($ticketArray as $ticket) {
		print_r($i++." : ".$ticket.'</br>');
	}
}

function doSQL($sqlStmt, $input){
	global $dbcon;
	$stmt = $dbcon->prepare($sqlStmt);
	$stmt->execute($input);
	
	return $stmt;
}


function toSql(){
	global $ticketArray;
	$output = 'tickets.sql';
	$output_file =  fopen($output,'w');

	fwrite($output_file, "DELETE FROM tickettable;\n");
	foreach($ticketArray as $ticket){
		$sql = "INSERT INTO tickettable(ticket_id) VALUES ('$ticket');";
		fwrite($output_file, $sql."\n");
		print $sql."</br>";
	}



}

printTickets();
toSql();
?>