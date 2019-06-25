<?php
require "dbConnect.php";

$response = array(); //response
$response['error'] = false;

if(!$conn){
  $response['error'] = true;
  $response['error_msg'] = "Server connection failed";
}
else{
  if(isset($_POST['jsonForm']) && isset($_POST['user_id']) && isset($_POST['form_id'])){
    $jsonForm = $_POST['jsonForm'];
    $user_id = $_POST['user_id'];
    $form_id = $_POST['form_id'];

    $query = "INSERT INTO forms_results(result,user_id,form_id) VALUES(?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii",$jsonForm,$user_id,$form_id);

    if($stmt->execute()){
      $response['error'] = false;
    }
    else{
      $response['error'] = true;
      $response['error_msg'] = "Fail to submit survey result. Please try again";
    }

    $stmt->close();
  }

}

$conn->close();

echo json_encode($response);
?>
