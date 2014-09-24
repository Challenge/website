<?php
include('top.php');
	$gallery = $_GET["gallery"];
?>	
     
        <div id="page">	
			<div id="indhold">
				<div id="indholdText2">
					<div id="indholdDiv2">
						
						<script type="text/javascript">
						
				<!-- Her loader vi billederne fra den ønskede mappe, og gør klar til at vise dem (via en Array) -->
						<?php
							chdir('Gallery/'. $gallery);
							$result = array();
							$files = scandir(getcwd());

							foreach ($files as $file)
							{
								switch (ltrim(strstr($file, '.'), '.'))
								{
									case 'jpg': 
									array_push($result, $file);
										break;
									case 'jpeg': 
									array_push($result, $file);
										break;
									case 'png':
									array_push($result, $file);
										break;
								}
							}
						?>

                    var gallery = <?php echo json_encode($result); ?>;

                </script>

			<!-- Overskriften, navn via mappe-navn -->
                <h4><?php echo $gallery ?></h4>
				
                <div style="text-align:center">
                
				<!-- Her er gallery-div'en, som indeholder 2 pile samt nuværende billede -->
					<div id='gallery' style='width: 400px; height: 288px; text-align:center; display: inline;'>
                   
						<div id="left" onclick="preimg();">
						</div>
					
						<img align="center" id="currentimg" style="left: 0px; max-width: 800px; max-height: 540px; border:solid;" src="gallery/<?php echo $gallery; ?>/<?php echo $result[0]; ?>">
                    
						<div id="right" onclick="nextimg();">
						</div>
					
					</div>
				
                </div>
				
				<!-- Her er gallery-count, som under billedet tæller hvillket billede man er på -->
                    <div id ='galleryNumber' style=" margin-top: 10px; position: absolute; margin-left: 48%">
					</div>

                <script type="text/javascript">
                    var galleryIndex = 0;

                    document.getElementById("galleryNumber").textContent = galleryIndex+1 + " / " + gallery.length;
					
                    function preimg() {
                        galleryIndex--;
                        if (galleryIndex === -1 || galleryIndex === -2) {
                            galleryIndex = gallery.length - 1;
                        }
                        document.getElementById('currentimg').src = "gallery/<?php echo $gallery; ?>/"+ gallery[galleryIndex];
                        var div = document.getElementById("galleryNumber");
                        div.textContent = galleryIndex+1 + " / " + gallery.length;
                        var galleryNumber = div.textContent;
                    }	
					
                    function nextimg() {
                        galleryIndex++;
                        if (galleryIndex === gallery.length) {
                            galleryIndex = 0;
                        }
                        document.getElementById('currentimg').src = "gallery/<?php echo $gallery; ?>/"+ gallery[galleryIndex];
                        var div = document.getElementById("galleryNumber");
                        div.textContent = galleryIndex+1 + " / " + gallery.length;
                        var galleryNumber = div.textContent;
                    }

                </script>
					</div>
				</div>				
			</div>
		</div>

		
<?php
include('bottom.html');
?>
