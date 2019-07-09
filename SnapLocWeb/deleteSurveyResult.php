<?php
  if(isset($_POST['survey_result_id'])){
    $resultID = $_POST['survey_result_id'];

    require "dbConnect.php";

    if(!$conn)
      echo "Server connection failed. Please check your Internet connection";
    else{
      $query = "DELETE FROM forms_results WHERE id = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("i",$resultID);

      if($stmt->execute()){
        echo "Successfully removed post";
      }
      else{
        echo "Fail to remove post. Please try again";
      }
  }
}
?>
