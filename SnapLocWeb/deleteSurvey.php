<?php
  if(isset($_POST['survey_id'])){
    $surveyID = $_POST['survey_id'];

    require "dbConnect.php";

    if(!$conn)
      echo "Server connection failed. Please check your Internet connection";
    else{
      $query = "DELETE FROM forms WHERE id = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("i",$surveyID);

      if($stmt->execute()){
        echo "Successfully removed survey.";
      }
      else{
        echo "Fail to remove survey. Please try again";
      }
  }
}
?>
