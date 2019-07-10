<?php
	
	require "dbConnect.php";
	
	$response = array(); //response 
	$response['error'] = false;
	
	if(!$conn){
		$response['error'] = true;
		$response['error_msg'] = "Server connection failed";
	}
	else{
			
		$stmt = $conn->prepare("SELECT * FROM forms");
		
		if($stmt->execute()){
			$response['error'] = false;
			$result = $stmt->get_result();
			
			$surveyList = array();
			
			if(mysqli_num_rows($result)>0){
				while($row = mysqli_fetch_assoc($result)){
					$tempArray = array();
					$tempArray['survey_id'] = $row['id'];
					$tempArray['survey_title'] = $row['title'];
					$tempArray['survey_json'] = $row['jsonForm'];
					$tempArray['survey_date'] = $row['updatedAt'];
					
					$surveyList[] = $tempArray;
				}
			}
			
			$response['surveyList'] = $surveyList;
		}
		else{
			$response['error'] = true;
			$response['error_msg'] = "Failed to get data";
		}
		
		$stmt->close();
	}
	
	$conn->close();
	
	echo json_encode($response);
?>