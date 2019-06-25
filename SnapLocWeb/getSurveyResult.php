<?php
  require "dbConnect.php";

  if(!$conn)
    die("Server connection failed. Please check your Internet connection");
  else{
    if(isset($_POST['survey_result_id'])){

      $survey_result_id = $_POST['survey_result_id'];

      $query = "SELECT title,result FROM forms_results fs INNER JOIN forms f ON (fs.form_id = f.id) WHERE fs.id = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("i",$survey_result_id);

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
