<?php //print_r($_SERVER);?>
<?php
	header("Access-Control-Allow-Origin: *");
	
	include_once("./lib/upload.php");
	$upload=new upload();
	$path="/temp/img/";
	try{
		$doc_src=$upload->uploaded_img('fileupload',$path);
	}catch(Exception $e){
		print $e->getMessage();
		exit;
	}
	echo json_encode($doc_src);
	exit;
?>
