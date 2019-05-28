<?php
	
	require "dbConnect.php";
	
	$result = array(); //response 
	$result['error'] = false;
	
	if(!$conn){
		$result['error'] = true;
		$result['error_msg'] = "Server connection failed";
	}
	else{
		if(isset($_POST['token'])){
			$token = $_POST['token'];
			
			$stmt = $conn->prepare("INSERT INTO device_token(token) VALUES(?)");
			$stmt->bind_param("s", $token);
			
			if($stmt->execute()){
				$result['error'] = false;
			}
			else{
				$result['error'] = true;
				$result['error_msg'] = "Fail to update token";
			}
		}
		else{
			$result['error'] = true;
			$result['error_msg'] = "Token not given";
		}
	}

        echo json_encode($result);
?>