<?php
date_default_timezone_set("Europe/Copenhagen");

try {
	$dbcon = new PDO('sqlite:upload.db');
	$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	/*
	 * Get the current image to show from the database.
	 * Assumes there is only one entry in the database at any given time.
	 */
	$sql = "SELECT time, id FROM timestamp LIMIT 1";
	$stmt = $dbcon->prepare($sql);
	$stmt->execute();
	$result = $stmt->fetchAll();
	
	if(!empty($result)) {
		$time = $result[0][0];
		$id = $result[0][1];
		
		//The image has been shown in the specified interval, change to new image.
		if ($time < time()) {
			$sql = "SELECT sequence FROM upload WHERE id = ?";
			$stmt = $dbcon->prepare($sql);
			$stmt->execute(array($id));
			$result = $stmt->fetchAll();
			$sequence = $result[0][0];
			
			$sql = "SELECT id, imagepath, sequence, interval, active FROM upload WHERE sequence = ?";
			$stmt = $dbcon->prepare($sql);
			$stmt->execute(array($sequence + 1));
			$result = $stmt->fetchAll();
			
			if(!empty($result)) {
				$sql = "UPDATE timestamp SET time = ?, id = ?";
				$stmt = $dbcon->prepare($sql);
				$stmt->execute(array(time() + $result[0][3], $result[0][0]));
				
				echo $result[0][1];
				exit(0);
			}
			else {
				//Do nothing here, execute rest of code.
			}
		}
		else {
			//return imagepath to current image.
			$sql = "SELECT imagepath FROM upload WHERE id = ?";
			$stmt = $dbcon->prepare($sql);
			$stmt->execute(array($id));
			$result = $stmt->fetchAll();
			
			echo $result[0][0];
			exit(0);
		}
	}
	else {
		/*
		 * No rows found in the database.
		 * Insert some data into the database, so we can update it in the following code.
		 */
		$sql = "INSERT INTO timestamp(time, id) VALUES(?, ?)";
		$stmt = $dbcon->prepare($sql);
		$stmt->execute(array(0, 0));
	}
	
	$sql = "SELECT id, imagepath, sequence, interval, active FROM upload WHERE sequence = ?";
	$stmt = $dbcon->prepare($sql);
	$stmt->execute(array(1));
	$result = $stmt->fetchAll();
	
	if(!empty($result)) {
		$sql = "UPDATE timestamp SET time = ?, id = ?";
		$stmt = $dbcon->prepare($sql);
		$stmt->execute(array(time() + $result[0][3], $result[0][0]));
		
		echo $result[0][1];
		exit(0);
	}
	
	/*
	 * No other image is active in the database.
	 * We then select the default image, which has sequence 0.
	 */
	$sql = "SELECT id, imagepath, sequence, interval, active FROM upload WHERE sequence = ?";
	$stmt = $dbcon->prepare($sql);
	$stmt->execute(array(0));
	$result = $stmt->fetchAll();
	
	if(!empty($result)) {
		echo $result[0][1];
		exit(0);
	}
	else {
		/*
		 * No active images was found and the default images was undefined.
		 * We then return exitcode 1, to indicate error.
		 */
		exit(1);
	}
}
catch (PDOException $e) {
	$str = "";
	$str .= "Could not establish connection to the database.";
	$str .= "This is unfortunately a fatal error that cannot be ignored and further execution has been halted." . "<br />";
	$str .= "Please contact an administrator immediately";
	$str .= "If you don't know any administrators, please visit the contact page." . "<br />";
	$str .= "<br />" . "Please give the administrator the following information:" . "<br />";
	$str .= $e;
	die($str);
}

?>














