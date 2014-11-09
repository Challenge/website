<?php
$content = array(
);

			function echoContent(){
				global $content;
				foreach($content as $id => $attributes){
					echo '<div ';
					foreach($attributes as $att => $val){
						if($att != 'innerHTML')
							echo $att.'="'.$val.'" ';
					}
					$inner = '';if(isset($content[$id]["innerHTML"]))$inner = $content[$id]["innerHTML"];echo '>'.$inner.'</div>';
				}
			}?>