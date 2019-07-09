<?php
  require "dbConnect.php";

  if(!$conn)
    die("Server connection failed. Please check your Internet connection");
  else{
    if(isset($_POST['survey']) && isset($_POST['survey_title'])){
      $jsonData = $_POST['survey'];

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

      $query = "INSERT INTO forms(title) VALUES(?)";

      $stmt = $conn->prepare($query);
      $stmt->bind_param("s",$title);

      if($stmt->execute()){
        $error = false;
      }
      else{
        $error = true;
      }

      $stmt->close();

      if(!$error){
        $last_id = mysqli_insert_id($conn);

        $query = "UPDATE forms SET jsonForm = ?, jsonHtml = ? WHERE id =?";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("ssi",$jsonData,$str,$last_id);

        if($stmt->execute()){
          echo "1";
        }
        else{
          echo "0";
        }
      }
      else{
        echo "0";
      }

    }
  }


?>
