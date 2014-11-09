<?php

	include 'cssGenerator.php';
	include '../Data/settings.php';

	class Schema{
		private $destination,$fileHandle,
				$css, $content = array(), $tileSets, $idCount = 0;
		private $tileSize;
	
		public function __construct($destination,$cssPath){		
			global $tileSize;
			$this->tileSize = $tileSize;
		
			$this->css = new CSSGenerator($cssPath);		
			$this->fileHandle = fopen($destination, 'w') or die("can't open file");
			$this->destionation = $destination;
			
			$this->css->addMoreContent('.tile', 
								array('position' => 'absolute',
								'background-size' => $this->tileSize .' '.$this->tileSize ));
		
		}
	
		function addContent($class,$attribute,$value){			
			$this->css->addContent($class,$attribute,$value);
		}

		
		function doArea($x1,$y1,$x2,$y2,$tileSetName,$title){
				global $graphicDir;
				$attributes = array('class' => $tileSetName,'title' => $title);
				$this->_doArea($x1,$y1,$x2,$y2,$attributes);
		}
	
		function initTile($name, $path){		
			$this->addContent('.'.$name,'background','url("'.$path.'")');
			$this->addContent('.'.$name,'z-index',count($this->tileSets));
			$this->addContent('.'.$name,'background-size', $this->tileSize .' '.$this->tileSize );

		}
	
		function initTileSet($name,$class,$path){
			$tiles = array('N' => $class.'N',
						   'S' => $class.'S',
						   'E' => $class.'E',
						   'W' => $class.'W',
						   'NW' => $class.'NW',
						   'NE' => $class.'NE',
						   'SW' => $class.'SW',
						   'SE' => $class.'SE',
						   '-' => $class.'Fill');
			
			foreach($tiles as $key => $file){
				$filename = $path.'/'.$file.'.png';
					if (file_exists($filename)) {
						$this->addContent('.'.$tiles[$key],'background','url("'.$path.'/'.$tiles[$key].'.png")');
						$this->addContent('.'.$tiles[$key],'background-size', $this->tileSize .' '.$this->tileSize );
					} else {
						if($key == '-'){
							unset($tiles['-']);					
						}else{
							$this->addContent('.'.$tiles[$key],'background','url("'.$path.'/'.$class.'Fill.png")');
							$this->addContent('.'.$tiles[$key],'background-size', $this->tileSize.' '.$this->tileSize );
						}
					}
					
				
			}			
			$this->tileSets[$name] = $tiles;
		}
	
		function addDiv($x1,$y1,$x2,$y2,$attributes){
			$tileSize = $this->tileSize;
			$id = $attributes['id'];
			$this->content[$id] = $attributes;
			$this->content[$id]['class'] .= ' tile';
			$this->addContent('#'.$id,'left',$x1*$tileSize);
			$this->addContent('#'.$id,'top',$y1*$tileSize);
			$this->addContent('#'.$id,'width',($x2-$x1)*$tileSize);
			$this->addContent('#'.$id,'height',($y2-$y1)*$tileSize);	
			$this->addContent('#'.$id,'z-index',$this->idCount);	
			

		}

		
		function addObject($x1,$y1,$width,$height,$attributes){
			$attributes['id'] .= $this->idCount;
			$this->addDiv($x1,$y1,$x1+$width,$y1+$height,$attributes);
			$this->idCount++;			
		}
		
		private function _doArea($x1,$y1,$x2,$y2,$attributes){	
			$tileSets = $this->tileSets;
			$class = $attributes['class'];
			$idCount = $this->idCount;
			$id = $class.$idCount;
			$title = $attributes['title'];
			
			
			// North and South walls
			$this->addDiv($x1+1,$y1,$x2,$y1+1,array('id' => $tileSets[$class]['N'].$idCount,'class' => $tileSets[$class]['N'] ,'title' => $title));
			$this->addDiv($x1+1,$y2,$x2,$y2+1,array('id' => $tileSets[$class]['S'].$idCount,'class' => $tileSets[$class]['S'] ,'title' => $title));
			
			// East and West walls
			$this->addDiv($x1,$y1+1,$x1+1,$y2,array('id' => $tileSets[$class]['W'].$idCount,'class' => $tileSets[$class]['W'] ,'title' => $title));
			$this->addDiv($x2,$y1+1,$x2+1,$y2,array('id' => $tileSets[$class]['E'].$idCount,'class' => $tileSets[$class]['E'] ,'title' => $title));
			
			//corners
			$this->addDiv($x2,$y1,$x2+1,$y1+1,array('id' => $tileSets[$class]['NE'].$idCount,'class' => $tileSets[$class]['NE'].' Corners' ,'title' => $title));
			$this->addDiv($x1,$y1,$x1+1,$y1+1,array('id' => $tileSets[$class]['NW'].$idCount,'class' => $tileSets[$class]['NW'].' Corners' ,'title' => $title));
			$this->addDiv($x2,$y2,$x2+1,$y2+1,array('id' => $tileSets[$class]['SE'].$idCount,'class' => $tileSets[$class]['SE'].' Corners' ,'title' => $title));
			$this->addDiv($x1,$y2,$x1+1,$y2+1,array('id' => $tileSets[$class]['SW'].$idCount,'class' => $tileSets[$class]['SW'].' Corners' ,'title' => $title));
				
			//middle
			if($x2-$x1 >= 2 && $y2-$y1 >= 2 &&  isset($tileSets[$class]['-']))
				$this->addDiv($x1+1,$y1+1,$x2,$y2,array('id' => $class.$idCount,'class' => $tileSets[$class]['-'] ,'title' => $title));

			$this->idCount++;
		}
		
		private function write($line){
			echo $line.'</br'>
			fwrite($this->fileHandle,$line);	
		}
	
		
		function make($varName){
		
			$this->write('<?php'.PHP_EOL);	
			$this->write('$'.$varName.' = array('.PHP_EOL);			
			foreach($this->content as $key => $div){				
			$this->write('"'.$div['id'].'" => array(');
				foreach($div as $attribute => $value){
					
						$this->write('"'.$attribute.'" => "'.$value.'", ');
				}
				$this->write('), '.PHP_EOL);
			}
			$this->write(');'.PHP_EOL);
			//$this->css->optimize();
			$this->css->finish();
			$this->write(
			"
			function echoContent(){
				global \$$varName;
				foreach(\$$varName as \$id => \$attributes){
					echo '<div ';
					foreach(\$attributes as \$att => \$val){
						if(\$att != 'innerHTML')
							echo \$att.'=\"'.\$val.'\" ';
					}
					\$inner = '';".
					'if(isset($'.$varName.'[$id]["innerHTML"]))'.
						'$inner = $'.$varName.'[$id]["innerHTML"];'.
						"echo '>'.\$inner.'</div>';
				}
			}");
			$this->write('?>');

			
		}
		
		
	}
	
	
	
	















?>