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
		//private $dbconfile = "dbutilties/escapedbcon.inc.php";
		
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
				# database escape
				$variable = $this->dbescape($variable); // mysql_real_escape_string
				//$variable = $this->sig($variable); // signiture check
				
				# html escape
				$variable = htmlspecialchars($variable);
				$variable = addslashes($variable);
			}elseif ($type == int){
				$variable = (int) $variable;
			}elseif ($type == float){
				$variable = (float) $variable;
			}
			return $variable;
		}
		
		private function dbescape($string){
			//@require_once($dbconfile);
			//@$string = mysql_real_escape_string($string);
			return $string;
		}
		
		public function iterateArray($array){
			if (is_array($array)){
				foreach ($array as $key => $value){
					# sanitize value & key
					$newvalue =  $this->escape($this->typeof($value),$value);
					$newkey =  $this->escape($this->typeof($key),$key);
					$array[$newkey] = $newvalue;
				}
				return $array;
			}else{
				return false;
			}
		}
		
		private function sig($string){ ##NOT READY##
			$original_str = $string;
			# emulate mysql LIKE function
			# locate and open the sqli.sig file
			if (file_exists("signitures/sqli.sig")){
				$sigfile = file_get_contents("signitures/sqli.sig");
				$sigs = explode('\n',$sigfile);
				
				# fix the string [lowercase, spaces and such]
				$string = strtolower($string);
				$string = str_replace('+',' ',$string);
				$string = str_replace('/**/',' ',$string);
				
				foreach($sigs as $line){
					#regex
					if ((preg_match("#^{$string}.*$#",trim($line))) OR (preg_match("#^.*$string.*$#", $line)) OR (preg_match("#^$string\.com$#", $line))){
						# raise error/block request based on signiture
						$string = str_replace($line,'*',$string);
						$flag = true;
					}else{
						$flag = false;
					}
				}
			}
			if ($flag == true){
				return $string;	
			}else{
				return $original_str;
			}	
		}
	}
?>
