<?php
require "dbConnect.php";

    function checkLogin($email,$password){

      require "dbConnect.php";

      if(!$conn)
        return false;
      else{
        //connection success
        $query = "SELECT * FROM admin WHERE admin_email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s",$email);

        if($stmt->execute()){
          $result = $stmt->get_result();
          //invalid
          if(mysqli_num_rows($result)==0){
            return false;
          }
          else //valid
          {
            $user = $result->fetch_assoc();
            if(password_verify($password,$user['admin_password'])){
              return true;
            }
            else{
              return false;
            }
          }
        }
        else {
          return false;
        }
      }

    }

    function checkPermissionLevel($email){
      require "dbConnect.php";

      if(!$conn)
        return false;
      else{

        //connection success
        $query = "SELECT permission_level FROM admin WHERE admin_email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s",$email);

        if($stmt->execute()){
          $result = $stmt->get_result();
          //invalid
          if(mysqli_num_rows($result)==0)
            return NULL;
          else //valid
          {
            $user = $result->fetch_assoc();
            return $user['permission_level'];
          }
        }
        else {
          return NULL;
        }
      }
    }

    if(isset($_POST['email']) && isset($_POST['password'])){
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(empty($email) || empty($password)){
          echo "<script>alert('Please fill in all the details')</script>";
        }
        else{
          if(checkLogin($email,$password)){
            session_start();
            $_SESSION["email"] = $email;
            $_SESSION["permission"] = checkPermissionLevel($email);
            header("location: home.php");
            exit();
          }
          else{
            echo "<script>alert('Invalid login credentials.')</script>";
          }
        }
    }
    else{

      if(isset($_SESSION)){
        session_unset();
        session_destroy();
      }
    }

 ?>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin</title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">

    <link href="style.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form action="index.php" method="post">
              <h1>Admin Login</h1>
              <div>
                <input name="email" type="text" class="form-control" placeholder="Email" required="" />
              </div>
              <div>
                <input name="password" type="password" class="form-control" placeholder="Password" required="" />
              </div>
              <div>
                <button type="submit" class="btn btn-default submit form-control">Log in</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">

                <div class="clearfix"></div>
                <br/>

                <div>
                  <h1>Pusat Khidmat Adun Indera Kayangan</h1>
                  <p>Developed by : Legaxus Software</p>
                  <a style="margin:0px" href="mailto:contact@legaxus.com">contact@legaxus.com</a>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>
