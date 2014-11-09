	<?php
	include 'Data/dbcon.php';

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
	
	class eventViewer{
		private $selectFrom = 'SELECT DATE_FORMAT(event_start,\'%w\'), event_name, DATE_FORMAT(event_start,\'%H:%i\'), DATE_FORMAT(event_end,\'%H:%i\')
			    FROM eventtable ';
		private $dbcon;
	
		public function __construct($dbcon){
			$this->dbcon = $dbcon;
		}
		
		private function doSQL($sqlStmt, $input){
			$stmt = $this->dbcon->prepare($sqlStmt);
			$stmt->execute($input);
			return $stmt;
		}
		
		function getAllEvents(){
			$sql = $this->selectFrom.'ORDER BY event_start ASC';
			return 	$this->doSQL($sql,null)->fetchAll();
		}

		function getFutureEvents(){
			$sql = $this->selectFrom.'WHERE event_start > now() ORDER BY event_start ASC';
			return 	$this->doSQL($sql,null)->fetchAll();
		}

		function getNextEvent(){
			return $this->getNextNEvents(1);
		}

		function getNextNEvents($n){
		global $dbcon;
			$sql = $this->selectFrom.'
				WHERE event_start > now()
				ORDER BY event_start ASC
				LIMIT 0, :n';
			$stmt = $dbcon->prepare($sql);
			$stmt->bindParam(':n',$n,PDO::PARAM_INT);
			$stmt->execute();

			return $stmt->fetchAll();
		}

		function getPrevEvent(){
			$sql = $this->selectFrom.'
				WHERE event_start < now()
				ORDER BY event_start DESC
				LIMIT 0,1';

			return $this->doSQL($sql,null)->fetchAll();
		}

		function getPrevNEvents($n){
			global $dbcon;
				$sql = $this->selectFrom.'
					WHERE event_start < now()
					ORDER BY event_start DESC
					LIMIT 0, :n';
				$stmt = $dbcon->prepare($sql);
				$stmt->bindParam(':n',$n,PDO::PARAM_INT);
				$stmt->execute();

			return $stmt->fetchAll();
		}

		function getEventsInRange($YYYYMMDDHHMMSS1,$YYYYMMDDHHMMSS2){
		global $dbcon;
			$sql = $this->selectFrom.'
				WHERE event_start BETWEEN :t1 AND :t2
				ORDER BY event_start ASC';
				
			$stmt = $dbcon->prepare($sql);
			$stmt->bindParam(':t1',$YYYYMMDDHHMMSS1,PDO::PARAM_STR);
			$stmt->bindParam(':t2',$YYYYMMDDHHMMSS2,PDO::PARAM_STR);
			$stmt->execute();

		return $stmt->fetchAll();

		}

		function getCurrentEvents(){
			$sql =$this->selectFrom.'
				WHERE event_start >= now() AND event_end < now()
				ORDER BY event_start ASC';

			return $this->doSQL($sql,null)->fetchAll();
		}

	}
	
	$events = new eventViewer($dbcon);

	?>
	
	<h3><?php echo convertStr("Nuværende Events"); ?><h3>
	<table border="1px">
	<tr>
	<td><b>Event <b></td>
	<td><b>Dag</b></td>
	<td><b>Starttid<b></td>
	<td><b>Forventet sluttid<b></td>
	</tr>
	
	<?php 
	foreach($events->getCurrentEvents() as $row){
	?>
	<tr>
	<td><?php echo $days[$row[0]]; ?></td>
	<td><?php echo convertStr($row[1]); ?></td>
	<td><?php echo $row[2]; ?></td>
	<td><?php echo $row[3]; ?></td>
		</tr>

	<?php
	}
	?>
	
	</table>
	
	
	<h3>Kommende Events<h3>
	<table border="1px">
	<tr>
	<td><b>Event</b></td>
	<td><b>Dag</b></td>
	<td><b>Start tid</b></td>
	<td><b>Forventet slut tid</b></td>
	</tr>
	<?php 
	foreach($events->getFutureEvents() as $row){
	?>
	<tr>
	<td><?php echo $days[$row[0]]; ?></td>
	<td><?php echo convertStr($row[1]); ?></td>
	<td><?php echo $row[2]; ?></td>
	<td><?php echo $row[3]; ?></td
	</tr>
	<?php
	}
	?>
	
	</table>