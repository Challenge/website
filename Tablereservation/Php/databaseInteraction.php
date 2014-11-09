<?php
//Denne fil tager sig af alt database interaktion der skal bruges.
/* Dependcies :
	- *DataDir*\settings.php
	- *DataDir*\dbcon.php
*/

?>

<?php

	include $dataDir.'dbcon.php';

	/* prepared and executes the given sql statement, with the given input*/
	function doSQL($sqlStmt, $input){
		global $dbcon;
		$stmt = $dbcon->prepare($sqlStmt);
		$stmt->execute($input);
		
		return $stmt;
	}
	
	/* executes the given sql query, and prints a the message succesMsg if succesful*/
	function executeQuery($query,$succesMsg){
		global $dbcon;
		$dbcon->exec($query);
		wasQuerySuccesful($succesMsg);
	}
	
	/* prints the given errorInfo array*/
	function printErrorArray($errorInfo){
		echo '#####################################<br />';	
		echo $errorInfo[0].'<br />';
		echo $errorInfo[1].'<br />';
		echo $errorInfo[2].'<br />';
		echo '#####################################<br />';	
	}
	
	/* returns false if not reservations has been made for the given ticketid */
	function ticketExist($ticket){
			$sql = 'SELECT * 
					FROM reservationstable 
					WHERE ticket_id = ?';
			$input = array($ticket);
	
			$stmt = doSQL($sql,$input);

			$count = sizeof($stmt->fetchAll());

			return $count == 1;
					
	}
	
	/* prints succesMsg if the latest query was successful*/
	function wasQuerySuccesful($succesMsg){
	global $dbcon;
	
		if($dbcon->errorCode() == 00000){
			echo $succesMsg;
		} else {
			$errorInfo = $dbcon->errorInfo();
		}
		
		return $dbcon->errorCode();
	}

	/* Begins an transaction and locks the reservationstable */
	function startTransaction(){
		global $dbcon;
		$dbcon->beginTransaction();
		$sqlStmt = 'LOCK TABLES reservationstable';
		doSQL($sqlStmt, array());
	}
	
	/* Commits a transaction amd unlocks the tables*/
	function commit(){
		global $dbcon;
		$dbcon->commit();
		$sqlStmt = 'UNLOCK TABLES';
		doSQL($sqlStmt, array());
	}
	
	/* Rollback a transaction amd unlocks the tables*/
	function rollbackTransaction(){
		global $dbcon;
		$dbcon->rollback();
		$sqlStmt = 'UNLOCK TABLES';
		doSQL($sqlStmt, array());
	
	}
	
	/* Change the seat and name value for the given ticket to the given seat*/
	function changeReservation($seat, $name, $ticket){
			$sql = 'UPDATE reservationstable SET seat_number = ?, name = ? WHERE ticket_id = ?';
			$input = array($seat,$name, $ticket);
			return doSQL($sql,$input)->errorCode();
	}
	
	/* Deletes a reservation with the given ticket Id*/
	function cancelReservation($ticket){
		$sql = 'DELETE FROM reservationstable WHERE ticket_id = ?';
			$input = array($ticket);
			return doSQL($sql,$input)->errorCode();
	}
	
	/* Adds a reservation with the given ticket and seat*/
	function makeReservation($seat, $name, $ticket){
			$sql = 'INSERT INTO reservationstable (seat_number, name, ticket_id ) VALUES (?,?,?)';
			$input = array($seat, $name, $ticket);
			return doSQL($sql,$input)->errorCode();	
	}

	/* Returns a PDOStatement with all reservations in the reservationtable*/
	function getReservations(){
		$sql = 'SELECT seat_number, ticket_id, name FROM reservationstable';
		return doSQL($sql,array());
	}
	
	/* returns an array of all the taken seats */
	function getTakenSeats(){
		$takenArray = array();
		$sql = 'SELECT seat_number, name, ticket_id FROM reservationstable';
	    $arr = doSQL($sql,array())->fetchAll();
	
		foreach($arr as $row){
			$takenArray[$row[0]] = array($row[1],$row[2]);
		}
		
			return $takenArray;
	}

	/* returns false if the given ticket is invalid returns true otherwise */
	function isValidTicket($ticket){
		$sql = 'SELECT ticket_id FROM tickettable WHERE ticket_id = ?';
		$res = doSQL($sql,array($ticket));
		
		return count($res->fetchAll()) == 1;
	}

	?>