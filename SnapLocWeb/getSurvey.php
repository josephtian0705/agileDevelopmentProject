<?php
  require "dbConnect.php";

  if(!$conn)
    die("Server connection failed. Please check your Internet connection");
  else{
    if(isset($_POST['survey_id'])){

      $survey_id = $_POST['survey_id'];

      $query = "SELECT title,jsonForm FROM forms WHERE id = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("i",$survey_id);

      if($stmt->execute()){
        $result = $stmt->get_result()->fetch_assoc();
        echo json_encode($result);
      }
      else{
        echo "0";
      }

    }
  }


?>
