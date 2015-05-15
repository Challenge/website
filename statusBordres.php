<html>
<head>
    <title>Liste over deltagere til DIKULAN</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="MainCSS.css" />
</head>

<!-- Her er en liste over alle reserverede borde, i et printervenligt format -->
<?php
include("db.php");

echo "<h1>Status over bordreservationen</h1>";

echo "<table border='1'>
            <tr><th>Seat</th><th>Name</th><th>TicketID</th></tr>";

$BookingData = mysqli_query($db, "SELECT SeatID, PlayerName, TicketID
                                                      FROM booking");
while ($row = mysqli_fetch_array($BookingData)) {
    echo "  <tr>
                <td>" . $row['SeatID'] . "</td>
                <td width='300px'; style='text-align:center; vertical-align:middle;';>" . $row['PlayerName'] . "</td>
                <td width='300px'; style='text-align:center; vertical-align:middle;';>" . $row['TicketID'] . "</td>
            </tr>";
}

echo "</table>";
?>
</html>