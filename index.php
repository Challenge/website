<?php
include('top.php');
?>	
     
        <div id="page">
			
				<div id="indhold">
					<div id="indholdText2">
						<div id="indholdDiv2">
						
					<!-- De følgende 8 div's printer forsiden ud -->
						
							<div id="inholdTop">
						<h1> 
							<?php
								$result = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM forside WHERE num = 1"));
								echo $result["desc"];								
							?>
						</h1> </br>
							</div>
							<div id="inholdTop">
						<h3><?php
								$result = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM forside WHERE num = 2"));
								echo nl2br($result["desc"]);
							?></h3>
							
							</div>
							<div id="tab1">
								<h2>
									<?php
										$result = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM forside WHERE num = 3"));
										echo nl2br($result["desc"]);
									?>
								</h2>
								
								<p>
									<?php
										$result = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM forside WHERE num = 4"));
										echo nl2br($result["desc"]);	
									?>
								</p>
							</div>
							
							<div id="tab2">
								<h2>
									<?php
										$result = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM forside WHERE num = 5"));
										echo nl2br($result["desc"]);	
									?>	
								</h2>
								<p>
									<?php
										$result = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM forside WHERE num = 6"));
										echo nl2br($result["desc"]);	
									?>	
								</p>
							</div>
							
							<div id="tab3">
								<h2>
									<?php
										$result = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM forside WHERE num = 7"));
										echo nl2br($result["desc"]);	
									?>	
								</h2>
								
								<p>
									<?php
										$result = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM forside WHERE num = 8"));
										echo nl2br($result["desc"]);	
									?>	
								</p>
							</div>
						
						</div>
					</div>
					
				</div>
			
        </div>

		
<?php
include('bottom.html');
?>
