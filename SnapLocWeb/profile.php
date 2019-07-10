<?php
  session_start();

  if(!isset($_SESSION['email'])){
    header("location:index.php");
  }


  require "dbConnect.php";

  function checkOldPassword($password){
      require "dbConnect.php";

      if(!$conn)
        die("Server connection failed. Please check your Internet connection");
      else{
        $query = "SELECT admin_password FROM admin WHERE admin_email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s",$_SESSION['email']);

        if($stmt->execute()){
          $result = $stmt->get_result();
          //invalid
          if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
              $admin_password = $row['admin_password'];
            }

            if(password_verify($password,$admin_password))
              return true;
            else
              return false;
          }
          else{
            return false;
          }
        }
        else{
          return false;
        }
        $stmt->close();
      }

      $conn->close();
  }

  function updateNewPassword($password){
    require "dbConnect.php";

    if(!$conn)
      die("Server connection failed. Please check your Internet connection");
    else{
      $hash = password_hash($password,PASSWORD_BCRYPT);

      $query = "UPDATE admin SET admin_password = ? WHERE admin_email = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("ss",$hash,$_SESSION['email']);

      if($stmt->execute()){
        return true;
      }
      else{
        return false;
      }
    }

    $conn->close();
  }

  if(isset($_POST['submit'])){
    if(!empty($_POST['old_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_new_password'])){
      $oldPassword = $_POST['old_password'];
      $newPassword = $_POST['new_password'];
      $confirmNewPassword = $_POST['confirm_new_password'];

      if($newPassword==$confirmNewPassword){
          if(checkOldPassword($oldPassword)){
            if(updateNewPassword($newPassword)){
              echo "<script>alert('Password changed successfully');</script>";
              session_unset();
              session_destroy();
              echo "<script>location.href='profile.php';</script>";
            }
            else{
              echo "<script>alert('Password fail to be changed')</script>";
            }
          }
          else{
            echo "<script>alert('Invalid old password. Please try again')</script>";
          }
      }
      else{
        echo "<script>alert('Confirm new password must be same as new password')</script>";
      }
    }
    else {
      echo "<script>alert('Please fill in all the credentials')</script>";
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
          <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Edit Profile</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form class="form-horizontal form-label-left" action="profile.php" method="post">
                      <div class="form-group">
                        <label for="email" class="control-label col-md-3 col-sm-3 col-xs-12">Email<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="email" value="<?php echo $_SESSION['email']; ?>" class="form-control col-md-7 col-xs-12" type="text" name="email" disabled>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="old-password" class="control-label col-md-3 col-sm-3 col-xs-12">Old Password<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="old_password" id="old-password" class="form-control col-md-7 col-xs-12" required="required" type="password">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="new-password" class="control-label col-md-3 col-sm-3 col-xs-12">New Password<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="new_password" id="new-password" class="form-control col-md-7 col-xs-12" required="required" type="password">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="confirm-password" class="control-label col-md-3 col-sm-3 col-xs-12">Confirm New Password<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="confirm_new_password" id="confirm-password" class="form-control col-md-7 col-xs-12" required="required" type="password">
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button type="submit" value="submit" name="submit" class="btn btn-success pull-right">Submit</button>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <!-- /page content -->

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
  </body>
</html>
