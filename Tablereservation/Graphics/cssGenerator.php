<?php
	class CSSGenerator{
		private $content = array();
		private $fileHandle;
		private $dest;
		
		public function __construct($destination){
			$this->fileHandle = fopen($destination, 'w') or die("can't open file");
			$this->dest = $destination;
		}
	
	
	
		public function addContent($class,$attribute,$value){
				if(isset($this->content[$class])){
					$this->content[$class][$attribute] = $value;		
				} else {
					$this->content[$class] = array();
					$this->content[$class][$attribute] = $value;
				}
		}	
		
		function optimize(){
			$contentCounter = array();
			$newContent = array();
			
			foreach($this->content as $class => $attributes){
							foreach($attributes as $attribute => $value){	
																				
								if(isset($contentCounter[$attribute][$value])){
									$contentCounter[$attribute][$value] .= ','.$class;
								} else {
									$contentCounter[$attribute][$value] = $class;
								}		
							}	
			}


			$this->content = array();

			
			foreach($contentCounter as $attribute => $value){
				foreach($value as $key => $class){
					$this->addContent($class,$attribute,$key);
				}	
			}
		}	
		
		
		
		public function addMoreContent($class,$attributes){
			foreach($attributes as $key => $value){
				$this->addContent($class,$key,$value);
			}
		}	
		
		private function write($line){
			fwrite($this->fileHandle,$line.PHP_EOL);	
		}
	
		private function message(){
			$this->write('.warningMessageFromTheCreator'.PHP_EOL);
			$this->write('#############################################################'.PHP_EOL);
			$this->write('//This css file has been automaticly generated'.PHP_EOL);
			$this->write('//If you make any changes to this file make sure to save them somewhere else'.PHP_EOL);
			$this->write('#############################################################'.PHP_EOL);
			$this->write('{}'.PHP_EOL);
		}
	
		public function finish(){
			$this->message();
		
			foreach($this->content as $key => $attributeList){
				$this->write($key.'{');
				 foreach($attributeList as $attribute => $value){
					 $this->write($attribute.':'.$value.';');
					
				 }
				 $this->write('}');
			}
			
			fclose($this->fileHandle);
		}
	}
?>
