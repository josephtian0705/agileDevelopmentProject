<?php
  require "dbConnect.php";

  if(!$conn)
    die("Server connection failed. Please check your Internet connection");
  else{
    if(isset($_POST['survey']) && isset($_POST['survey_id']) && isset($_POST['survey_title'])){
      $jsonData = $_POST['survey'];
      $survey_id = $_POST['survey_id'];
      $title = $_POST['survey_title'];

      $data = json_decode($jsonData, true);
      $size = sizeof($data);

      $str = "<table id='myTable' border=1><tr>";
      for($i=0; $i<$size; $i++){

          foreach($data[$i] as $key => $value){

              if($key == "type" && $value == "text" ){
                  $str .=  "<th>".$data[$i]['label']."</th>";
              }

              if($key == "type" && $value == "header"){
                  $str .= "<th>Header</th>";
              }

              if($key == "type" && $value == "radio-group"){
                  $str .= "<th>".$data[$i]['label']."</th>";
              }

              if($key == "type" && $value == "checkbox-group"){
                  $str .= "<th>".$data[$i]['label']."</th>";
              }

          }

      }
      $str .= "</tr>";

      $query = "UPDATE forms SET title = ?, jsonForm = ?, jsonHtml = ? WHERE ID = ?";

      $stmt = $conn->prepare($query);
      $stmt->bind_param("sssi",$title,$jsonData,$str,$survey_id);

      if($stmt->execute()){
        echo "1";
      }
      else{
        echo "0";
      }

      $stmt->close();

    }
  }


?>
