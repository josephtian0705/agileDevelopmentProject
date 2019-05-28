<?php

require "dbConnect.php";

if(!$conn)
  die("Server connection failed. Please check your Internet connection");
else{
  $query = "UPDATE users SET status = ? WHERE id = ?";
  $stmt = $conn->prepare($query);

  if(isset($_POST['user_id']) && isset($_POST['status'])){
    $id = $_POST['user_id'];
    $status = $_POST['status'];

    $id_final = substr($id,4);
    $stmt->bind_param("ii",$status,$id_final);
    $stmt->execute();
  }

  $stmt->close();
}

$conn->close();


?>
