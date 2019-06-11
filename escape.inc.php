<?php
/**
 * This class allows a user to escape any variable type automatically 
 * using different techniques.
 *
 * @author Nabil Al-Dhamari <https://twitter.com/NabilAlDhamari>
 * @version 1.0
 * @copyright Copyright (c) 2017, Nabil Al-Dhamari
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */
	Class Escape{
		public  $type;
		public  $variable;
		
		public function __construct(){

		}
		
		public function typeof($variable){
			return gettype($variable);
		}
		
		public function escape($type,$variable){
			if ($type === string){
				$variable = $this->htmlParse($variable);
			}elseif ($type === int){
				$variable = (int) $variable;
			}elseif ($type === float){
				$variable = (float) $variable;
			}
			return $variable;
		}
		
		public function iterateArray($array){
			if (is_array($array)){
				foreach ($array as $key => $value){
					if (is_array($value)){
						$array[$key] = self::iterateArray($value);
						continue;
					}

					# sanitize value & key
					$newvalue 		= self::escape(self::typeof($value), $value);
					$newkey 		= self::escape(self::typeof($key), $key);
					$array[$newkey] = $newvalue;
				}
				return $array;
			}else{
				return false;
			}
		}
		
		public function htmlParse($string){
			$string = $this->asciiParse($string);
			$string = strip_tags($string);
			//$string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
			
			$string = addslashes(stripslashes($string));
			$string = preg_replace('/&#[0-9][0-9][0-9]/','',$string);
			return $string;
		}
		
		public function asciiParse($string){
			# ascii unicode char escape
			$string = urldecode($string);
			$string = preg_replace('/(\%(\d+))/','',$string); 	// remove double encoding
			$string = preg_replace('/0[xX][0-9a-fA-F]+/','',$string); // remove anything that starts with 0x[X][0-9][a,f][A,F]
			$string = preg_replace('/%0[0-9a-fA-F]+/','',$string); 	// remove special chars like %0d = carriage return

			return $string;
		}
	}
?>
