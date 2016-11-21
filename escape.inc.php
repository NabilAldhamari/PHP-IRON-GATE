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
			$this->variable = $variable;
			$this->type 	= $this->typeof($variable);
		}
		
		public function typeof($variable){
			if (is_string($variable)){
				if ((int) $variable > 0){ // is it a numerical string ?
					return int;
				}else{
					return string;
				}
			}elseif (is_numeric($variable)){
				if (is_float($variable)){
					return float;
				}elseif ((int) $variable > 0){
					return int;
				}
			}elseif (is_array($variable)){
				if (count($variable) > 0){
					return array();
				}
			}else{
				return "unknown";
			}
		}
		
		public function escape($type,$variable){
			if ($type == string){
				$variable = $this->htmlParse($variable)->asciiParse($variable)->sqlParse($variable);
			}elseif ($type == int){
				$variable = (int) $variable;
			}elseif ($type == float){
				$variable = (float) $variable;
			}
			return $variable;
		}
		
		public function iterateArray($array){
			if (is_array($array)){
				foreach ($array as $key => $value){
					# sanitize value & key
					$newvalue 		=  $this->escape($this->typeof($value),$value);
					$newkey 		=  $this->escape($this->typeof($key),$key);
					$array[$newkey] = $newvalue;
				}
				return $array;
			}else{
				return false;
			}
		}
		
		public function htmlParse($string){
			$string = htmlspecialchars($string);
			$string = strip_tags($string);
			$string = htmlentities($string);
			$string = preg_replace('&#[0-9][0-9][0-9]','',$string);
			//;
			return $string;
		}
		
		public function asciiParse($string){
			# ascii unicode char escape
			$string = preg_replace('0[xX][0-9a-fA-F]+','',$string); // remove anything that starts with 0x[X][0-9][a,f][A,F]
			$string = preg_replace('%0[0-9a-fA-F]+','',$string); 	// remove special chars like %0d = carriage return
			return $string;
		}
		
		public function sqlParse($string){
			$string = preg_replace('\/\*.*?\*\/|--.*?\n','',$string); // single and multilined comments
			
			if( get_magic_quotes_gpc()){
				$string = stripslashes($string);
			}
			
			if( function_exists("mysql_real_escape_string") ){
				  $string = mysql_real_escape_string($string);
			}
			
			return $string;
		}
		
	}
?>
