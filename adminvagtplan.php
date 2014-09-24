<?php
include('IsLoggedIn.php');
include('top2.php');
?>	
        
        <div id="page">
            
				<div id="indhold">
					<div id="indholdText2">
						<div id="indholdDiv2">
						<br>
							<?php
																			
								// Når der klikkes "opdater vagtplan" sker følgende
								if(isset($_POST["submit"]))
								{
								
								for ($x=1; $x <= 8; $x++)
								{
								
									// opdaterer fredag
									$tid1 = $_POST['tid' . $x . ''];
									$tid1 = mysqli_escape_string($db,$tid1);
									$q = "UPDATE vagtplan SET `tid`='" . $tid1 . "' WHERE keykey ={$x};";
									mysqli_query($db, $q);
									
									// opdaterer fredag
									$fredag1 = $_POST['fredag' . $x . ''];
									$fredag1 = mysqli_escape_string($db,$fredag1);
									$q = "UPDATE vagtplan SET `fredag`='" . $fredag1 . "' WHERE keykey ={$x};";
									mysqli_query($db, $q);
									
									// opdaterer lørdag
									$lørdag1 = $_POST['lørdag' . $x . ''];
									$lørdag1 = mysqli_escape_string($db,$lørdag1);
									$q = "UPDATE vagtplan SET `lørdag`='" . $lørdag1 . "' WHERE keykey ={$x};";
									mysqli_query($db, $q);
									
									// opdaterer søndag
									$søndag1 = $_POST['søndag' . $x . ''];
									$søndag1 = mysqli_escape_string($db,$søndag1);
									$q = "UPDATE vagtplan SET `søndag`='" . $søndag1 . "' WHERE keykey ={$x};";
									mysqli_query($db, $q);
									
								}
								
								}
							?>

						<table border = 1>
						<th> TID </th>
						<th> Fredag </th>
						<th> Lørdag </th>
						<th> Søndag </th>

						<form action="adminvagtplan.php" method="post">

					<?php	
			/* Her printes vagtplanen i et redigerbart format */
						$result = mysqli_query($db,"SELECT tid, fredag, lørdag, søndag FROM Vagtplan");
						$cons = 1;
						
						while($row = mysqli_fetch_array($result))
						{
							echo "<tr> <td> <textarea name='tid{$cons}'>" . $row['tid']  . "</textarea> </td> 
							      <td> <textarea name='fredag{$cons}'>" . $row['fredag'] . "</textarea> </td> 
								  <td> <textarea name='lørdag{$cons}'>" . $row['lørdag'] . "</textarea> </td> 								  
								  <td> <textarea name='søndag{$cons}'>" . $row['søndag'] . "</textarea> </td> </tr> ";
							$cons = $cons+1;
						}
						
					?>
						</table>	
							<br />
							<div id="button1">
							<input type="submit" value="Klik her for at opdatere!" name="submit" />
							</div>
						</form>


							
						</div>
					</div>
				</div>

        </div>

<?php
include('bottom.html');
?>
