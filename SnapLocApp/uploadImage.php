<?php
	$result = array("success" => $_FILES["file"]["name"]);
	$file_path = "images/".basename( $_FILES['file']['name']);
	if(move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
		$result = array("success" => "File successfully uploaded");
	} else{
		$result = array("success" => "error uploading file");
	}
	echo json_encode($result);
?>