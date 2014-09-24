<?php
include('top.php');
?>	
     
        <div id="page">	
			<div id="indhold">
				<div id="indholdText2">
					<div id="indholdDiv2">
						
						<h1> Vælg et Galleri </h1>
					<?php

					chdir('gallery');
					echo ("</br>");
					

$dir=opendir(".");
$files=array();
while (($file=readdir($dir)) !== false)
{
if ($file != "." and $file != ".." and $file != "index.php")
{
array_push($files, $file);
}
}
closedir($dir);
sort($files);
foreach ($files as $file)
print "<div id='newGallery'> <a href='slideshow.php?gallery=$file' style='text-decoration: none;'><h1> $file </h1></a> </div>
"; 


					?>	
					</div>
				</div>				
			</div>
		</div>

		
<?php
include('bottom.html');
?>
