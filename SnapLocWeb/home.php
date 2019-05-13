
<?php

  session_start();

  function verifyUsername($username){

    require "dbConnect.php";

    if(!$conn)
      return false;
    else{
      $query = "SELECT * FROM users WHERE username = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("s",$ic);

      if($stmt->execute()){
        $result = $stmt->get_result();
        if(mysqli_num_rows($result)>0){
          return false;
        }
        else{
          return true;
        }
      }
      else {
        return false;
      }
    }
  }

  function verifyEmail($email){
      require "dbConnect.php";

      if(!$conn)
        return false;
      else{
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s",$email);

        if($stmt->execute()){
          $result = $stmt->get_result();
          if(mysqli_num_rows($result)>0){
            return false;
          }
          else{
            return true;
          }
        }
        else {
          return false;
        }
      }
  }

  function verifyIC($ic){
    $pattern = "/^\d{6}-\d{2}-\d{4}$/";
    if(preg_match($pattern,$ic))
      return true;
    else
      return false;
  }

  function verifyPassword($password){
    if(strlen($password)<8)
      return false;
    else
      return true;
  }


  if(!isset($_SESSION['email'])){
    header("location:index.php");
  }

  require "dbConnect.php";

  if(!$conn)
    die("Server connection failed. Please check your Internet connection");
  else{

    $userCount = 0;

    $query = "SELECT * FROM users;";
    $stmt = $conn->prepare($query);
    $usersArray = array();
    if($stmt->execute()){
      $result = $stmt->get_result();
      //invalid
      if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)){
          $usersArray[] = $row;
          $userCount++;
        }
      }
    }

    $stmt->close();

    $query = "SELECT COUNT(*) AS postCount FROM post";
    $stmt = $conn->prepare($query);

    $postCount = 0;

    if($stmt->execute()){
      $result = $stmt->get_result();
      //invalid
      if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)){
          $postCount = $row['postCount'];
        }
      }
    }

    $stmt->close();

    $query = "SELECT COUNT(*) AS surveyCount FROM survey s INNER JOIN admin a ON (s.admin_id = a.admin_id) WHERE a.admin_email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s",$_SESSION['email']);

    $surveyCount = 0;

    if($stmt->execute()){
      $result = $stmt->get_result();
      //invalid
      if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)){
          $surveyCount = $row['surveyCount'];
        }
      }
    }

    $stmt->close();

    if(isset($_POST['submit'])){
      if(isset($_POST['user_username']) && isset($_POST['user_email']) && isset($_POST['user_password']) && isset($_POST['user_confirm_password']) && isset($_POST['user_ic'])){
        $username = $_POST['user_username'];
        $email =  $_POST['user_email'];
        $password = $_POST['user_password'];
        $confirm_password = $_POST['user_confirm_password'];
        $ic = $_POST['user_ic'];

        if(empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($ic)){
          echo "<script>alert('All details are required')</script>";
        }
        else{
          if(!verifyUsername($username)){
            echo "<script>alert('Username already exists')</script>";
          }
          else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email')</script>";
          }
          else if(!verifyEmail($email)){
            echo "<script>alert('Email already exists')</script>";
          }
          else if(!verifyPassword($password)){
            echo "<script>alert('Password must be at least than 8 characters')</script>";
          }
          else if($password!=$confirm_password){
            echo "<script>alert('Confirm password must be the same as password')</script>";
          }
          else if(!verifyIC($ic)){
            echo "<script>alert('Invalid IC')</script>";
          }
          else{
            $hash = password_hash($password,PASSWORD_BCRYPT);

            $query = "INSERT INTO users(username,email,password,ic) VALUES(?,?,?,?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss",$username,$email,$hash,$ic);

            if($stmt->execute()){
              echo "<script>alert('Successfully added user');location.href='home.php'</script>";
            }
            else{
              echo "<script>alert('Fail to add user. Please try again')</script>";
            }

            $stmt->close();
          }
        }
      }
      else{
        echo "<script>alert('All details are required')</script>";
      }
    }

    if(isset($_POST['submit_edit'])){
      if(isset($_POST['user_username']) && isset($_POST['user_email']) && isset($_POST['user_ic']) && isset($_POST['edit_id'])){
        $username = $_POST['user_username'];
        $email =  $_POST['user_email'];
        $ic = $_POST['user_ic'];
        $id = $_POST['edit_id'];
        $old_ic = $_POST['edit_old_ic'];
        $old_username = $_POST['edit_old_username'];
        $old_email = $_POST['edit_old_email'];

        if(empty($username) || empty($email) || empty($ic) || empty($id)){
          echo "<script>alert('All details are required')</script>";
        }
        else{
          $error = false;

          if($username!=$old_username){
            if(!verifyUsername($username)){
              $error = true;
              echo "<script>alert('Username already exists')</script>";
            }
          }
          else if($email!=$old_email){
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
              $error = true;
              echo "<script>alert('Invalid email')</script>";
            }
            else if(!verifyEmail($email)){
              $error = true;
              echo "<script>alert('Email already exists')</script>";
            }
          }
          else if($ic!=$old_ic){
            if(!verifyIC($ic)){
              $error = true;
              echo "<script>alert('Invalid IC')</script>";
            }
          }
          if(!$error){

            $query = "UPDATE users SET username = ?, email = ?, ic = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi",$username,$email,$ic,$id);

            if($stmt->execute()){
              echo "<script>alert('Successfully edited user details');location.href='home.php'</script>";
            }
            else{
              echo "<script>alert('Fail to edit user details. Please try again');</script>";
            }

            $stmt->close();
          }
        }
      }
      else{
        echo "<script>alert('All details are required')</script>";
      }
    }
  }

  $conn->close();
?>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="images/favicon.ico" type="image/ico" />

    <title>Pusat Khidmat Adun Indera Kayangan</title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">

    <!-- Datatables -->
    <link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="home.php" class="site_title" style="overflow:visible"> <span>Pusat Khidmat Adun Indera Kayangan</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix" style="margin-top:50px">
              <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo $_SESSION['email'] ?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <ul class="nav side-menu">
                  <li><a href="home.php"><i class="fa fa-home"></i>Home</a></li>
                  <li><a href="posts.php"><i class="fa fa-bars"></i>User Posts</a></li>
                  <li><a href="profile.php"><i class="fa fa-user"></i>Profile</a></li>

                  <li><a><i class="fa fa-bar-chart-o"></i> Survey <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="survey.php"><i class="fa fa-bar-chart-o"></i>Survey List</a></li>
                      <li><a href="survey_builder.php"><i class="fa fa-bar-chart-o"></i>Survey Builder</a</li>
                      <li><a href="survey_result.php"><i class="fa fa-bar-chart-o"></i>Survey Results</a</li>
                    </ul>
                  </li>

                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <?php echo $_SESSION['email'] ?>
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="index.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>

              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
          <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Registered Users</span>
              <div class="count"><?php echo $userCount; ?></div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-bar-chart-o"></i> Total Surveys</span>
              <div class="count"><?php echo $surveyCount; ?></div>

            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-bars"></i> Total Posts</span>
              <div class="count green"><?php echo $postCount; ?></div>
            </div>

          </div>
          <!-- /top tiles -->
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Users</h2>
                  <?php
                    if($_SESSION['permission']==1){
                  ?>
                  <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#userModal">Add User</button>
                  <?php
                    }
                   ?>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>IC</th>
                        <th>Status</th>
                        <?php
                          if($_SESSION['permission']==1){
                        ?>
                          <th>Action</th>
                        <?php
                          }
                        ?>
                      </tr>
                    </thead>


                    <tbody>
                      <?php
                        if($usersArray){
                          foreach($usersArray as $user){
                              echo "<tr id='user{$user['id']}'>
                                      <td>{$user['id']}</td>
                                      <td>{$user['username']}</td>
                                      <td>{$user['email']}</td>
                                      <td>{$user['ic']}</td>";

                              echo   "<td>
                                        <select id='user{$user['id']}' class='form-control'>";
                              echo       "<option value='1' "; if($user['status']==1) echo "selected"; echo ">Pending</option>";
                              echo       "<option value='2' "; if($user['status']==2) echo "selected"; echo ">Approved</option>";
                              echo       "<option value='3' "; if($user['status']==3) echo "selected"; echo ">Rejected</option>";
                              echo     "</select>
                                      </td>";
                              if($_SESSION['permission']==1){
                                echo  "<td><button type='button' onclick='loadEditUser(\"{$user['username']}\",\"{$user['email']}\",\"{$user['ic']}\",\"{$user['id']}\")' class='btn btn-default' data-toggle='modal' data-target='#editModal'>Edit</button><a class='btn btn-danger' onclick='deleteUser({$user['id']})'>Delete</a></td>";
                              }
                              echo  "</tr>";
                          }
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- /page content -->

        <?php
          if($_SESSION['permission']==1){
        ?>
        <!-- Modal -->
        <div id="userModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add User</h4>
              </div>
              <div class="modal-body">
                <form action="home.php" method="post">
                  <div>
                    <input name="user_username" type="text" class="form-control" placeholder="Username" required="" />
                  </div>
                  <br>
                  <div>
                    <input name="user_email" type="email" class="form-control" placeholder="Email" required="" />
                  </div>
                  <br>
                  <div>
                    <input name="user_password" type="password" class="form-control" placeholder="Password (Min 8 Characters)" required="" />
                  </div>
                  <br>
                  <div>
                    <input name="user_confirm_password" type="text" class="form-control" placeholder="Confirm Password (Min 8 Characters)" required="" />
                  </div>
                  <br>
                  <div>
                    <input name="user_ic" type="text" class="form-control" placeholder="IC (E.g. 111111-11-1111)" required="" />
                  </div>
                  <button style="margin-top:16px;margin-right:0px" type="submit" value="submit" name="submit" class="btn btn-default submit pull-right">Submit</button>
                </form>
              </div>
              <div class="modal-footer">

              </div>
            </div>

          </div>
        </div>

        <!-- Modal -->
        <div id="editModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit User</h4>
              </div>
              <div class="modal-body">
                <form action="home.php" method="post">
                  <input type="hidden" name="edit_id" id="edit_id">
                  <input type="hidden" name="edit_old_username" id="edit_old_username">
                  <input type="hidden" name="edit_old_email" id="edit_old_email">
                  <input type="hidden" name="edit_old_ic" id="edit_old_ic">
                  <div>
                    <input id='edit_username' name="user_username" type="text" class="form-control" placeholder="Username" required="" />
                  </div>
                  <br>
                  <div>
                    <input id='edit_email' name="user_email" type="email" class="form-control" placeholder="Email" required="" />
                  </div>
                  <br>
                  <div>
                    <input id='edit_ic' name="user_ic" type="text" class="form-control" placeholder="IC (E.g. 111111-11-1111)" required="" />
                  </div>
                  <button style="margin-top:16px;margin-right:0px" type="submit" value="submit" name="submit_edit" class="btn btn-default submit pull-right">Submit</button>
                </form>
              </div>
              <div class="modal-footer">

              </div>
            </div>

          </div>
        </div>
        <?php
          }
        ?>

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            Developed by : Legaxus Software
          </div>
          <div class="clearfix"></div>
           <div class="pull-right">
            <a href="mailto:contact@legaxus.com">contact@legaxus.com</a>
          </div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="vendors/Flot/jquery.flot.js"></script>
    <script src="vendors/Flot/jquery.flot.pie.js"></script>
    <script src="vendors/Flot/jquery.flot.time.js"></script>
    <script src="vendors/Flot/jquery.flot.stack.js"></script>
    <script src="vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="vendors/moment/min/moment.min.js"></script>
    <script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="build/js/custom.min.js"></script>

    <!-- Datatables -->
    <script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="vendors/jszip/dist/jszip.min.js"></script>
    <script src="vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="vendors/pdfmake/build/vfs_fonts.js"></script>

    <script src="ajax.js"></script>
  </body>
</html>
