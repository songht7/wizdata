<?php
	class upload{
		function uploaded_img($filename,$path){
			$res=array('error'=>0,'url'=>'','error_info'=>'');
			$size = 1000000;
			$allowtype = array("gif","png","jpg","jpeg");
			try{
				if(!empty($_FILES[$filename])){
				if($_FILES[$filename]['error'] >$size){
					$res['error']=6;
					$res['error_info']= '上传文件大小超出了预定值';
				}
				if($_FILES[$filename]['error'] > 0) {
					switch ($_FILES['pic']['error']){
						case 1: 
							$res['error']=1;
							$res['error_info']= '上传文件大小超出了预定值 : upload_max_filesize';
						case 2: 
							$res['error']=2;
						case 3: 
							$res['error']=3;
						case 4: 
							$res['error']=4;
						default :
							$res['error']=9;
					}
				}else{
					$name=$_FILES[$filename]['name'];
					$pos=strrpos($name,"."); //取得文件名中后缀名的开始位置
					$ext=substr($name,$pos);//取得后缀名，包括点号
					if(!in_array(substr($ext,1),$allowtype)){
						$res['error']=5;
						$res['error_info']= '格式不对';
					}
					$name=date("Yndhis").rand(1000,9999).$ext;
					$full_path=$path.$name;
					move_uploaded_file($_FILES[$filename]['tmp_name'],$_SERVER['DOCUMENT_ROOT'].$full_path);
					$res['url']="http://".$_SERVER['HTTP_HOST'].$full_path;
				}
				}
				return $res;
				
			}catch(Exception $e){
				print $e->getMessage();
				exit;
			}
		}
	}
?>