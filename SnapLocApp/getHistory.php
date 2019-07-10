<?php
	require "dbConnect.php";
	
	$result = array(); //response 
	$result['error'] = false;
	
	//$conn = mysqli_connect($host,$username,$pwd,$db);
	
	if(!$conn){
		$result['error'] = true;
		$result['error_msg'] = "Server connection failed";
	}
	else{
		if(isset($_GET['user_id'])){
			$userID = $_GET['user_id'];
			
			$stmt = $conn->prepare("SELECT * FROM post p INNER JOIN status s ON (p.status=s.id) LEFT JOIN admin a ON (p.viewed_by = a.admin_id) WHERE user_id = ?");
			$stmt->bind_param("i", $userID);
			
			if($stmt->execute()){
				$result['error'] = false;
				$resultSQL = $stmt->get_result();
				
				$postsList = array();
				
				if(mysqli_num_rows($resultSQL)>0){
					while($row = mysqli_fetch_assoc($resultSQL)){
						$tempArray = array();
						$tempArray['post_id'] = $row['post_id'];
						$tempArray['post_comments'] = $row['post_comments'];
						$tempArray['post_image_url'] = $row['post_image_url'];
						$tempArray['post_date'] = $row['post_date'];
						$tempArray['post_status'] = $row['status_name'];
                                                $tempArray['admin_email'] = $row['admin_email'];
						
					        $postsList[] = $tempArray;
					}
				}
				
				$result['postsList'] = $postsList;
			}
			else{
				$result['error'] = true;
				$result['error_msg'] = "Failed to get data";
			}
			
			$stmt->close();
		}
		else{
			$result['error'] = true;
			$result['error_msg'] = "User ID not given";
		}
	}
	
	$conn->close();
	
	echo json_encode($result);
?>