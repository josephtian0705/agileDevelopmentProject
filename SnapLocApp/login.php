<?php
  require "dbConnect.php";
  $response = array("error" => false);
  
  if(!$conn)
    die("Server connection failed. Please check your Internet connection");
  else{
    if(isset($_POST['email']) && isset($_POST['password'])){
      $email = $_POST['email'];
      $password = $_POST['password'];
      if(empty($email) || empty($password)){
        $response['error'] = true;
        $response['error_msg'] = "Please fill in all the credentials";
      }
      else{
        $query = "SELECT * FROM users WHERE email = ? AND status != 3";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s",$email);

        if($stmt->execute()){
          $result = $stmt->get_result();
          if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
              $password_db = $row['password'];
              $id = $row['id'];
            }

            if(password_verify($password,$password_db)){
              $response['error'] = false;
              $response['id'] = $id;
            }
            else{
              $response['error'] = true;
              $response['error_msg'] = "Invalid credentials";
            }
          }
          else{
            $response['error'] = true;
            $response['error_msg'] = "Invalid credentials";
          }
        }
        else{
          $response['error'] = true;
          $response['error_msg'] = "Server error. Please try again";
        }
		$stmt->close();
      }
    }
    else{
      $response['error'] = true;
      $response['error_msg'] = "Please fill in all the credentials";
    }
  }
  
  echo json_encode($response);
?>
