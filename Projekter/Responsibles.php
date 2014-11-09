<?php 
date_default_timezone_set("Europe/Copenhagen");

try {
	$date = htmlspecialchars(urldecode($_GET['date']));
	$time = htmlspecialchars($_GET['time']);
	$time = substr($time, 0, 2).':'.substr($time,2,2).':'.substr($time,4,2);
	$dbcon = new PDO('sqlite:upload.db');
	$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$datetime = $date.' '.$time;

	/*
	 * Get the current responsibles to show from the database
	 */
	$sql = "SELECT `table_id`,`name` FROM `responsible` WHERE datetime(?) BETWEEN `start` AND `stop`";
	$stmt = $dbcon->prepare($sql);
	$stmt->execute(array($datetime));
	$result = $stmt->fetchAll();
	print(json_encode($result));

} catch (PDOException $e) {
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
