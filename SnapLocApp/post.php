<?php
	require "dbConnect.php";

	$result = array(); //response
	$result['error'] = false;


	if(!$conn){
		$result['error'] = true;
		$result['error_msg'] = "Server connection failed";
	}
	else{
		if(isset($_POST['post_image_url']) && isset($_POST['post_comments'])
			&& isset($_POST['post_type']) && isset($_POST['user_id'])
                        && isset($_POST['latitude']) && isset($_POST['longitude'])){

			$image_url = $_POST['post_image_url'];
			$comments = $_POST['post_comments'];
			$type = $_POST['post_type'];
			$user = $_POST['user_id'];
                        $latitude = $_POST['latitude'];
                        $longitude = $_POST['longitude'];

			$stmt = $conn->prepare("INSERT INTO post (post_image_url, post_comments, post_type, latitude, longitude, post_date, user_id) VALUES (?, ?, ?, ?, ?, NOW(), ?)");
			$stmt->bind_param("sssddi", $image_url, $comments, $type, $latitude, $longitude, $user);

			if($stmt->execute()){

                                $latest_id = $conn->insert_id;
                                $link = "http://malaysianow.today/SnapLocApp2/images/".$latest_id.".png";
                                $stmt2 = $conn->prepare("UPDATE post SET post_image_url = ? WHERE post_id = ?");
			        $stmt2->bind_param("si", $link, $latest_id);
                                if($stmt2->execute()){
                                   $result['error'] = false;
                                   $result['id'] = $latest_id;
                                }
                                else{
                                   $result['error'] = true;
                                   $result['error_msg'] = "Failed to upload this post. Please resubmit.";
                                }
                                $stmt2->close();
			}
			else{
				$result['error'] = true;
				$result['error_msg'] = "Failed to upload this post. Please resubmit.";
			}

                        $stmt->close();
		}
		else{
			$result['error'] = true;
			$result['error_msg'] = "Please fill in all the details";
		}
	}
	$conn->close();
	echo json_encode($result);
?>
