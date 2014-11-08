<html>
<head>
    <title>Liste over billetter</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="MainCSS.css" />
</head>

<!-- Her er en liste over alle reserverede borde, i et printervenligt format -->
<?php
include("db.php");

$BookingData = mysqli_query($db, "SELECT TicketID FROM ticket");
													  
													  
while ($row = mysqli_fetch_array($BookingData)) {
    echo $row['TicketID'] . "<br>";
}

?>
</html>