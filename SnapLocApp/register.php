<?php
  require "dbConnect.php";

  $response = array("error" => false);

  function verifyUsername($username){
	require "dbConnect.php";
	  
    if(!$conn)
      return false;
    else{
      $query = "SELECT * FROM users WHERE username = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("s",$ic);

      if($stmt->execute()){
        $result = $stmt->get_result();
        if(mysqli_num_rows($result)>0){
          return false;
        }
        else{
          return true;
        }
      }
      else {
        return false;
      }
    }
  }

  function verifyEmail($email){
	  require "dbConnect.php";
		
      if(!$conn)
        return false;
      else{
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s",$email);

        if($stmt->execute()){
          $result = $stmt->get_result();
          if(mysqli_num_rows($result)>0){
            return false;
          }
          else{
            return true;
          }
        }
        else {
          return false;
        }
      }
  }

  function verifyIC($ic){
    $pattern = "/^\d{6}-\d{2}-\d{4}$/";
    if(preg_match($pattern,$ic))
      return true;
    else
      return false;
  }

  function verifyPassword($password){
    if(strlen($password)<8)
      return false;
    else
      return true;
  }

 
  if(!$conn)
      die("Server connection failed. Please check your Internet connection");
  else{
    if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['ic'])){
      $username = $_POST['username'];
      $email =  $_POST['email'];
      $password = $_POST['password'];
      $ic = $_POST['ic'];

      if(empty($username) || empty($email) || empty($password) || empty($ic)){
        $response['error'] = true;
        $response['error_msg'] = "All details are required";
      }
      else{
        if(!verifyUsername($username)){
          $response['error'] = true;
          $response['error_msg'] = "Username already exists";
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $response['error'] = true;
          $response['error_msg'] = "Invalid email";
        }
        else if(!verifyEmail($email)){
          $response['error'] = true;
          $response['error_msg'] = "Email already exists";
        }
        else if(!verifyPassword($password)){
          $response['error'] = true;
          $response['error_msg'] = "Password must be more than 8 characters";
        }
        else if(!verifyIC($ic)){
          $response['error'] = true;
          $response['error_msg'] = "Invalid IC";
        }
        else{
          $hash = password_hash($password,PASSWORD_BCRYPT);

          $query = "INSERT INTO users(username,email,password,ic) VALUES(?,?,?,?)";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("ssss",$username,$email,$hash,$ic);

          if($stmt->execute()){
            $response['error'] = false;
          }
          else{
            $response['error'] = true;
            $response['error_msg'] = "Fail to register. Please try again";
          }
        }
      }
    }
    else{
      $response['error'] = true;
      $response['error_msg'] = "All details are required";
    }
  }

  echo json_encode($response);
?>
