<?php
/**
 * This class allows a user to validate an upload attempt 
 * using different techniques.
 *
 * @author Nabil Al-Dhamari <https://twitter.com/NabilAlDhamari>
 * @version 1.0
 * @copyright Copyright (c) 2017, Nabil Al-Dhamari
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */
class validateUpload{
	public $invalidExtensions = array('php','py','pl');
	
	
	function __construct($filesArray){
		$this->filesArray 		 = $filesArray;	
		$this->invalidExtensions = array('php','py','pl','asp','phps','html','xhtml','txt','rb');
		$this->invalidMimeTypes	 = array('text/html','text/x-script.phyton');
	}
	
	public function checkName($name){
		$extArray = explode('.',$name);		
		foreach($extArray as $ext){
			if ($ext == ''){
				break;
			}else{
				if (in_array(strtolower($ext), $this->invalidExtensions)) {
					# raise error
					return false;
				}
			}
		}
		return true;
	}
	
	public function checkType($tmp_name){
		//$finfo = new finfo();
		//$mimeType = $finfo->file($tmp_name, FILEINFO_MIME_TYPE);
		$mimeType  = mime_content_type($tmp_name);
		$mimeType2 = mime_content_type($tmp_name);
		
		if ($mimeType == $mimeType2){
			if (in_array(strtolower($ext), $this->invalidMimeTypes)) {
				# raise error
				return false;
			}
		}else{
			# raise error conflict in mime type
			return false;
		}
		
		return true;
	}
	
	public function checkImage($tmp_name){
		# if stated 
		if(!getimagesize($tmp_name)){
			return false;
		}
	}	
}
?>
