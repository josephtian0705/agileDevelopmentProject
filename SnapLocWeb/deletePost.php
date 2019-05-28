<?php
  if(isset($_POST['post_id'])){
    $postID = $_POST['post_id'];

    require "dbConnect.php";

    if(!$conn)
      echo "Server connection failed. Please check your Internet connection";
    else{
      $query = "DELETE FROM post WHERE post_id = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("i",$postID);

      if($stmt->execute()){
        echo "Successfully removed post";
      }
      else{
        echo "Fail to remove post. Please try again";
      }
  }
}
?>
