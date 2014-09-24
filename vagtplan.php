<?php
include('top.php');
?>	
        
        <div id="page">
            
				<div id="indhold">
					<div id="indholdText2">
						<div id="indholdDiv2">
						
				<!-- Her skabes en table hvor informationen angående Vagtplan postes (fra databasen) -->
						<table class="phpTable" >
						<th> TID </th>
						<th> Fredag </th>
						<th> Lørdag </th>
						<th> Søndag </th>

					<?php	
						$result = mysqli_query($db,"SELECT tid, fredag, lørdag, søndag FROM Vagtplan");
						
						while($row = mysqli_fetch_array($result))
						{
							echo "<tr> <td>" . $row['tid']    . "</td> 
							      <td> <h3>" . $row['fredag'] . "</h3></td> 
								  <td> <h3>" . $row['lørdag'] . "</h3></td> 								  
								  <td> <h3>" . $row['søndag'] . "</h3></td> </tr> ";
						}
					?>
						</table>	
						</div>
					</div>
				</div>

        </div>

<?php
include('bottom.html');
?>
