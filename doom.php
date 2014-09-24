<html>

<?php
include('IsLoggedIn2.php');
include("db.php");
?>

<head>
    <title>BUTTON OF DOOM</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="MainCSS.css" />
</head>

<div id"=doom3">

<?php
                /* Her er GENSTART DIKULAN funktionen, kaldet via button */
                if (isset($_POST['DestroyCreate']))
				{
                    $dpSeatTicket = "UPDATE Booking
                                     SET Playername='', TicketID='', Color='White'";
                    $resSeatTicket = mysqli_query($db, $dpSeatTicket);
                    if ($resSeatTicket) {
                        $dpTicket = "DROP TABLE Ticket";
                        $resDpTicket = mysqli_query($db, $dpTicket);
                        if ($resDpTicket) {
                            $createTicket = "CREATE TABLE Ticket (
                                             TicketID VARCHAR(40),
                                             PRIMARY KEY (TicketID))";
                            $resCreTicket = mysqli_query($db, $createTicket);
                            if ($resCreTicket) {
                                for ($x = 1; $x <= 80; $x++) {
                                    $input = array("A", "B", "C", "D", "E", "F", "G",
                                                   "H", "I", "J", "K", "L", "M", "N",
                                                   "P", "Q", "R", "S", "T", "U", "V",
                                                   "W", "X", "Y", "Z");
                                    $rand_keys = array_rand($input, 9);
                                    $streg = "-";
                                    $createRandTID = "INSERT INTO Ticket (TicketID)
                                    VALUES ('" . $input[$rand_keys[0]]
                                                            . $input[$rand_keys[1]]
                                                            . $input[$rand_keys[2]]
                                                            . $streg
                                                            . $input[$rand_keys[3]]
                                                            . $input[$rand_keys[4]]
                                                            . $input[$rand_keys[5]]
                                                            . $streg
                                                            . $input[$rand_keys[6]]
                                                            . $input[$rand_keys[7]]
                                                            . $input[$rand_keys[8]]
                                                            . "')";
                                    $resCreRandTID = mysqli_query($db, $createRandTID);
                                }
                                if ($resCreRandTID) {
                                    echo "<script type='text/javascript'>"
                                             . "alert('RESTART SUCCES!\\n"
                                             . "Old bookings removed\\n"
                                             . "Old ticketIDs dropped\\n"
                                             . "New random ticketIDs created');"
                                        . "</script>";
                                } else {
                                    echo "Error in creating random ticketIDs";
                                }
                            } else {
                                echo "Ticket Table NOT created why?";
                            }
                        } else {
                            echo "Ticket not dropped, why";
                        }
                    } else {
                        echo "Booking Table was not updated correctly";
                    }
                }
                ?> 


<div id="doom1">
	<h1> DU SKAL NU TIL AT GENSTARTE HELE DIKULAN! </br>
         KNAPPEN NEDERST PÅ SIDEN HER VIL GØRE FØLGENDE: </br>
		 - Ryde alle reservationer på bordene til LAN'et </br>
		 - Slette ALLE Billet-id'er </br>
		 - Genererer nye Billet-id'er til Databasen </br> </br>
		 
		 Under INGEN omstændigheder klik denne knap ~2 måneder før et LAN!</h1>
</div>

<style media="screen" type="text/css">
    .btnStyle1 { width:250px; height:150px;}
</style>

<style media="screen" type="text/css">
    .btnStyle2 { width:200px; height:50px;}
</style>


<div id="doom2">
<td>
                    <form action="" method="POST">
                        <button name="DestroyCreate" value="Submit" class="btnStyle1"
                                onClick="return confirm('Confirm only if you want all of the below:\n\
                                                         \n- old bookings removed\n\
                                                         \n- old ticket IDs removed\n\
                                                         \n- new ticket IDs created\n\
                                                         \nDo NOT confirm if tickets already have been printed\n\
                                                         \nand the ticket sale has begun!')"><h1>Klik her for at genstarte DIKULAN!</h1></button>
                    </form> </td>
</div>

<div id="doom3">
<td>
				<form action="logout.php" method="post">
					<input type="submit" value="GÅ TILBAGE, DØDELIGE!" class="btnStyle2">
				</form>

</td>
</div>

</div>
</html>