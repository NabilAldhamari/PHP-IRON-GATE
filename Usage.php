<?php
include("escape.inc.php");
$escape = new Escape();

// sanitize $_POST
if (isset($_POST)){
	$_POST = $escape->iterateArray($_POST);
}

// sanitize $_GET
if (isset($_GET)){
	$_GET = $escape->iterateArray($_GET);
}

// sanitize upload attempts
require("upload.inc.php");
	$interceptUpload;
	if (isset($_FILES)){
		foreach ($_FILES as $key => $value){
			# escape fields
			$_FILES[$key]['name']     = $escape->escape(string,$_FILES[$key]['name']);
			$_FILES[$key]['type']     = $escape->escape(string,$_FILES[$key]['type']);
			$_FILES[$key]['tmp_name'] = $escape->escape(string,$_FILES[$key]['tmp_name']);
			$_FILES[$key]['size']     = $escape->escape(int,$_FILES[$key]['size']);
			
			# Intercept the upload process
			$interceptUpload = new validateUpload($_FILES[$key]);
			
			# check file extenstion
			$checkName = $interceptUpload->checkName($_FILES[$key]['name']);
			if (!$checkName){
				unset($_FILES);	
			}
			
			# check uploaded file mime type
			$checkType = $interceptUpload->checkType($_FILES[$key]['tmp_name']);
			if (!$checkType){
				unset($_FILES);	
			}
			
			# check if image [if stated]
			$checkImage = $interceptUpload->checkImage($_FILES[$key]['tmp_name']);
			if (!$checkImage){
				unset($_FILES);	
			}
		}
	}
	
# go on with your upload
?>
