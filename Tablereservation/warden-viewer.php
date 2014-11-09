	<?php
	include 'Data/dbcon.php';
	$dbcon = $dbcon;
	$days = array(
	0 => convertStr("Søndag"),
	1 => "Mandag",
	2 => "Tirsdag",
	3 => "Onsdag",
	4 => "Torsdag",
	5 => "Fredag",
	6 => convertStr("Lørdag")
	);
	
	function convertStr($str){
		return str_replace(array('æ','ø','å'), array('&aelig;','&oslash;','&aring;'), $str);
	}
	
	class wardenViewer{
		private $selectFrom = 'SELECT DATE_FORMAT(start_time,\'%w\'),warden,DATE_FORMAT(start_time,\'%H:%i\'), DATE_FORMAT(end_time,\'%H:%i\')
			    FROM wardentimetable ';
		private $dbcon;
	
		public function __construct($dbcon){
			$this->dbcon = $dbcon;
		}
		
		private function doSQL($sqlStmt, $input){
			$stmt = $this->dbcon->prepare($sqlStmt);
			$stmt->execute($input);
			return $stmt;
		}
		
		function getAllWardens(){
			$sql = $this->selectFrom.'ORDER BY start_time ASC';
			return 	$this->doSQL($sql,null)->fetchAll();
		}

		function getFutureWardens(){
			$sql = $this->selectFrom.'WHERE start_time > now() ORDER BY start_time ASC';
			return 	$this->doSQL($sql,null)->fetchAll();
		}

		function getNextWarden(){
			return $this->getNextNWardens(1);
		}

		function getNextNWardens($n){
		global $dbcon;
			$sql = $this->selectFrom.'
				WHERE start_time > now()
				ORDER BY start_time ASC
				LIMIT 0, :n';
			$stmt = $dbcon->prepare($sql);
			$stmt->bindParam(':n',$n,PDO::PARAM_INT);
			$stmt->execute();

			return $stmt->fetchAll();
		}

		function getPrevWarden(){
			$sql = $this->selectFrom.'
				WHERE start_time < now()
				ORDER BY start_time DESC
				LIMIT 0,1';

			return $this->doSQL($sql,null)->fetchAll();
		}

		function getPrevNWardens($n){
			global $dbcon;
				$sql = $this->selectFrom.'
					WHERE start_time < now()
					ORDER BY start_time DESC
					LIMIT 0, :n';
				$stmt = $dbcon->prepare($sql);
				$stmt->bindParam(':n',$n,PDO::PARAM_INT);
				$stmt->execute();

			return $stmt->fetchAll();
		}

		function getWardensInRange($YYYYMMDDHHMMSS1,$YYYYMMDDHHMMSS2){
		global $dbcon;
			$sql = $this->selectFrom.'
				WHERE start_time BETWEEN :t1 AND :t2
				ORDER BY start_time ASC';
				
			$stmt = $dbcon->prepare($sql);
			$stmt->bindParam(':t1',$YYYYMMDDHHMMSS1,PDO::PARAM_STR);
			$stmt->bindParam(':t2',$YYYYMMDDHHMMSS2,PDO::PARAM_STR);
			$stmt->execute();

		return $stmt->fetchAll();

		}

		function getCurrentWardens(){
			$sql =$this->selectFrom.'
				WHERE start_time <= now() AND end_time > now()
				ORDER BY start_time ASC';

			return $this->doSQL($sql,null)->fetchAll();
		}

	}
	
	$events = new wardenViewer($dbcon);

	?>
	<h3><?php echo convertStr("Nuværende Vagt/Vagter"); ?><h3>

	<table border="1px">
	<tr>
	<td><b>Dato <b></td>
	<td><b>Vagt <b></td>
	<td><b>Start<b></td>
	<td><b>Slut<b></td>
	</tr>
	
	<?php 
	foreach($events->getCurrentWardens() as $row){
	?>
	<tr>
	<td><?php echo $days[$row[0]]; ?></td>
	<td><?php echo $row[1]; ?></td>
	<td><?php echo $row[2]; ?></td>
	<td><?php echo $row[3]; ?></td>
	</tr>

	<?php
	}
	?>
	
	</table>
	
	
	<h3>Kommende Vagt/Vagter<h3>
	<table border="1px">
	<tr>
	<td><b>Dato <b></td>
	<td><b>Vagt <b></td>
	<td><b>Start<b></td>
	<td><b>Slut<b></td>
	</tr>
	<?php 
	foreach($events->getFutureWardens() as $row){
	?>
	<tr>
	<td><?php echo $days[$row[0]]; ?></td>
	<td><?php echo $row[1]; ?></td>
	<td><?php echo $row[2]; ?></td>
	<td><?php echo $row[3]; ?></td>
	</tr>
	<?php
	}
	?>
	
	</table>