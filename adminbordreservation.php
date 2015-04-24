<?php
include('isLoggedIn.php');
include('top2.php');
include('variable.php');
?>	

<div id="page">

    <div id="indhold">
        <div id="indholdText2">
            <div id="indholdDiv2">
			<!-- Knap for at udprinte billet-ID'erne -->
                <h2>Knap for at udprinte billet-ID'erne:</h2>
                Klik her for billetter.
                <form action="billetter.php">
                    <button name="status" value="Submit">Se billetter</button>
                </form>
			
			
			
			<!-- Her ses der om administratoren har valgt at åbne eller lukke for bordreservationen -->
                <?php
                if (isset($_POST['openDIKULAN'])) {
                    mysqli_query($db, "UPDATE openclosed SET yesNo='ja'");
                }

                if (isset($_POST['lukDIKULAN'])) {
                    mysqli_query($db, "UPDATE openclosed SET yesNo='nej'");
                }
				?>
				
				
                <h2>Status over bordreservationen:</h2>
                Klik for at se for at se status over hvem,
                der har reserveret plads og hvor.
                <form action="statusBordres.php">
                    <button name="status" value="Submit">Se status</button>
                </form>

                <h2> Åben/Luk for tilmelding til DIKULAN: </h2>

			<!-- Her er knapperne for at åbne og lukke for bordreservationen -->
                <?php
                $isOpen = mysqli_fetch_assoc(mysqli_query($db, "SELECT yesNo FROM openclosed"));

                if ($isOpen["yesNo"] == "ja") {
                    echo ("<p>Bordreservationssystemet er i øjeblikket: </p> <h5> ÅBENT! </h5> ");
                } else {
                    echo ("<p>Bordreservationssystemet er i øjeblikket: </p> <h5> LUKKET! </h5> ");
                }
                ?>

                <form action="" method="POST">
                    <button name="openDIKULAN" value="Submit"> Åben for bordreservation</button>
                </form>

                </br>

                <form action="" method="POST">
                    <button name="lukDIKULAN" value="Submit"> Luk for bordreservation</button>
                </form>



			<!-- Her findes linket til genstart DIKULAN (doom.php) -->
                <br>
                <h2>Genstart DIKULAN</h2>
                Klik kun på nedenstående knap, for at lave nye billetID'er, før 
                et nyt DIKULAN, og for at reset'e bordreservationen!.
                <br>
                KLIK ALDRIG PÅ DEN UNDER ET LAN ELLER NÅR BILLETTERNE ER BLEVET 
                PRINTET OG ER I SALG!!!
                <div id="buttonOfDespair">
				
				<form action="doom.php">
					<input type="submit" value="GENSTART DIKULAN">
				</form>
					
                </div>
            </div>
        </div>
    </div>

</div>

<?php
include('bottom.html');
?>
