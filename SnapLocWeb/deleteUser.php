<?php
  if(isset($_POST['user_id'])){
    $userID = $_POST['user_id'];

    require "dbConnect.php";

    if(!$conn)
      echo "Server connection failed. Please check your Internet connection";
    else{
      $query = "DELETE FROM users WHERE id = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("i",$userID);

      if($stmt->execute()){
        echo "Successfully removed user.";
      }
      else{
        echo "Fail to remove user. Please try again";
      }
  }
}
?>
