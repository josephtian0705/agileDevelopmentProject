<?php
session_start();
require "dbConnect.php";

if(!$conn)
  die("Server connection failed. Please check your Internet connection");
else{
  $query = "SELECT admin_id FROM admin WHERE admin_email = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s",$_SESSION['email']);

  if($stmt->execute()){
    $result = $stmt->get_result()->fetch_assoc();
    $admin_id = $result['admin_id'];
  }

  $stmt->close();

  $query = "UPDATE post SET status = ?, viewed_by = ? WHERE post_id = ?";
  $stmt = $conn->prepare($query);

  if(isset($_POST['post_id']) && isset($_POST['status'])){
    $id = $_POST['post_id'];
    $status = $_POST['status'];

    if($status == 1)
      $admin_id = NULL;

    $id_final = substr($id,4);
    $stmt->bind_param("iii",$status,$admin_id,$id_final);
    $stmt->execute();
  }

  $stmt->close();
}

$conn->close();


?>
