<?php
  session_start();
  ob_start();

  include '../Header&Footer/header.php';
  include '../Database/dbcon.php';

  if(isset($_POST['register'])){

    if(isset($_GET['token'])){
        
        $token = $_GET['token'];

        $password = mysqli_real_escape_string($con, $_POST['password']);
        $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);

        $pass = password_hash($password, PASSWORD_BCRYPT);
        $cpass = password_hash($cpassword, PASSWORD_BCRYPT);

        if($password === $cpassword){
        
            $updatequery = "update register set password='$pass', cpassword='$cpass' where token='$token'";
            $iquery = mysqli_query($con, $updatequery);

            if($iquery){
                $_SESSION['msg'] = "Your Password has been updated Successfully";
                header('location:login.php');
            }else{
                $_SESSION['passmsg'] = "Your Password is Not updated!";
                header('location:reset_password.php');
            }   
        }else{
            $_SESSION['passmsg'] = "Your Password is not Matching";
        }
      }else{
        ?>
          <script> alert("Please Click on the Link send to your Register Email"); </script>
        <?php 
      }
    }
?>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-xl-10 d-flex align-items-center">
              <h1 class="logo mr-auto">
                <a href="index.html">Execu<span>tives.</span></a>
              </h1>
              <!-- Uncomment below if you prefer to use an image logo -->
              <!-- <a href="index.html" class="logo mr-auto"><img src="assets/img/logo.png" alt=""></a>-->
  
              <nav class="nav-menu d-none d-lg-block">
                <ul>
                  <li><a href="freelancer.html">BECOME A SELLER</a></li>
                  <li><a href="login.php">SIGN IN</a></li>
                </ul>
              </nav>
              <!-- .nav-menu -->
  
              <a href="register.php" class="get-started-btn scrollto"
                >JOIN</a
              >
            </div>
          </div>
        </div>
      </header>
      <!-- End Header -->

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Reset Your Password</h5>
        </div>
        <p class="pmsg">
          <?php 
                if(isset($_SESSION['passmsg'])){
                  echo $_SESSION['passmsg']; 
                }else{
                  echo $_SESSION['passmsg'] = "";
                }
          ?>
          </p>
        <div class="modal-body">
          <form action="" method="POST">
            <div class="textbox">
                <input type="password" name="password" placeholder="Password" class="reg" required>
              </div>
              <div class="textbox">
                <input type="password" name="cpassword" placeholder="Confirm Password" class="reg" required>
              </div>
            <div class="reg-btn">
              <input type="submit" name="login" class="btn1" value="Update Password">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <h6>Already a Member? <a href="login.php">Sign In</a></h6>
        </div>
      </div>

      <?php include '../Header&Footer/footer.php'; ?>