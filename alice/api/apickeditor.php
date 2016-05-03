<?php //print_r($_SERVER);?>
<?php
	
	include_once("./lib/upload.php");
	$upload=new upload();
	$path="/temp/imgckeditor/";
	try{
		$doc_src=$upload->uploaded_img('upload',$path);
	}catch(Exception $e){
		exit("<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(".$_REQUEST['CKEditorFuncNum'].",'".$e->getMessage()."');</script>");
	}
	header('Content-Type:text/html;charset=UTF-8');
	exit("<script type=\"text/javascript\">
window.parent.CKEDITOR.tools.callFunction(".$_REQUEST['CKEditorFuncNum'].",'".$doc_src['url']."','success');
</script>");
?>
