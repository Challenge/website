<?php 
include '../Data/dbcon.php';

	function makeTable(){
		global $dbcon,$wardenArray;
		try {
			/* drops all the tables */
			executeQuery('DROP TABLE EVENTTABLE', 'Eventtable dropped<br>');
			
			/* creates the tables again */
			executeQuery(
			'Create TABLE EVENTTABLE(	
				event_Id int NOT NULL AUTO_INCREMENT,
				event_name varchar(255) UNIQUE NOT NULL,
				event_start DATETIME ,	
				event_end DATETIME ,
				PRIMARY KEY (event_Id)
			)','EventTable created<br>');
		
		} catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	function executeQuery($query,$succesMsg){
		global $dbcon;
		$dbcon->exec($query);
		wasQuerySuccesful($succesMsg);
	}
	
	function printErrorArray($errorInfo){
		echo '#####################################<br />';	
		echo $errorInfo[0].'<br />';
		echo $errorInfo[1].'<br />';
		echo $errorInfo[2].'<br />';
		echo '#####################################<br />';	
	}
	
	
	function wasQuerySuccesful($succesMsg){
	global $dbcon;
	
		if($dbcon->errorCode() == 00000){
			echo $succesMsg;
		} else {
			$errorInfo = $dbcon->errorInfo();
			printErrorArray($errorInfo);
		}
		
		return $dbcon->errorCode();
	}

	makeTable();
	?>