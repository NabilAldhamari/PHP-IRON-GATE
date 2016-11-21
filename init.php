<?php
	if (isset($_FILES)){
		require('Classes/upload.inc.php');
		$interceptUpload;
		foreach ($_FILES as $key => $value){
			# escape fields
			$_FILES[$key]['name'] 		= $escape->escape(string,$_FILES[$key]['name']);
			$_FILES[$key]['type'] 		= $escape->escape(string,$_FILES[$key]['type']);
			$_FILES[$key]['tmp_name'] 	= $escape->escape(string,$_FILES[$key]['tmp_name']);
			$_FILES[$key]['size'] 		= $escape->escape(int,$_FILES[$key]['size']);
			
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
	if ((isset($_POST)) OR (isset($_GET))){
		include("Classes/escape.inc.php");
		$escape = new Escape();
		
		# has a form been submitted ?
		if (isset($_POST)){
			$_POST = (array) $escape->iterateArray($_POST);
		}
		
		# do we have variables in the URL ?
		if (isset($_GET)){
			$_GET = (array) $escape->iterateArray($_GET);
		}
	}
?>
