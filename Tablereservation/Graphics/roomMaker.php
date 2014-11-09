<?php
include 'roomParser.php';

$name = 'Standard';

$destination = '../Data/'.$name.'.php';
$inputPath = '../Data/bordplan'.$name.'.dlbp';
$cssPath = '../Graphics/'.$name.'Style.css';

$parser = new Schema($destination,$cssPath);
$input = file($inputPath,FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
$seatCount = 1;
$objectCount = 1;	
	foreach ($input as $line_num => $line) {
		if(substr($line,0,2) != '//'){
			$arr = explode(' ',$line);
			switch($arr[0]){
			case 'TILE::':
				$name = $arr[1];
				$path = $arr[2];
				
				if(count($arr) >= 4){
					for($i = 4; $i < count($arr);$i++)
							$path .= ' '.$arr[$i];
				}
				$parser->initTile($name,$path);
				echo "TILE $name added. PATH: $path <br/>"	;

			break;
			case 'TILESET::':
				$name = $arr[1];
				$class = $arr[2];
				$path = $arr[3];
				
				if(count($arr) >= 4){
					for($i = 4; $i < count($arr);$i++)
							$path .= ' '.$arr[$i];
				}
				
				$parser->initTileSet($name,$class,$path);
				echo "TILESET $class added. PATH: $path <br/>"	;	
			break;
			case 'AREA::':
				if( count($arr) >= 6){
						$s = '';
						$i = 0;
					
						for($i = 6; $i < count($arr);$i++)
							$s .= ' '.$arr[$i];	
				}
				
				$s = str_replace(array('æ','ø','å'), array('&aelig;','&oslash;','&aring;'), $s);
	
				$parser->doArea($arr[1],$arr[2],$arr[3],$arr[4],$arr[5],$s);
				echo "AREA $arr[5] added <br/>"		;

			break;
			
			case 'SEATAREA::':
				for($y = $arr[2]; $y < $arr[4]+1; $y++){
					for($x = $arr[1]; $x < $arr[3]+1; $x++){
						$attributes = 
						array( 
						'id' => 'seat'.$seatCount,
						'class' => 'seat clickable',
						'title' => 'Plads '.$seatCount,
						'innerHTML' =>  '<p class=\"seatText clickable\">'.$seatCount.'</p>'
						);
						$parser->addDiv($x,$y,$x+1,$y+1,$attributes);						
						
						$seatCount++;	
					}
				}
			break;
			case 'OBJECT::':
			global $objectCount,$tileSize;
			
			$attributes = 
					array( 
						'id' => $arr[5],
						'class' => $arr[5],
						'title' => str_replace(array('æ','ø','å'), array('&aelig;','&oslash;','&aring;'), $arr[6])
						);
						
				$parser->addContent('.'.$arr[5],'background-size', $tileSize*$arr[3].'px '.$tileSize*$arr[4].'px');
				$parser->addObject($arr[1],$arr[2],$arr[3],$arr[4],$attributes);
				echo "OBJECT $arr[5] added <br/>"		;
			break;
			
			}
		}
	}
		$parser->make('content');
?>