<?php
  session_start();

  include '../Header&Footer/header.php';
  include '../Database/dbcon.php';

  if(isset($_POST['register'])){

    $email = mysqli_real_escape_string($con, $_POST['email']);

    $email_query = "select * from register where email='$email'";
    $query = mysqli_query($con, $email_query);

    $email_count = mysqli_num_rows($query);
    
    if($email_count){

        $userdata = mysqli_fetch_array($query);
        $username = $userdata['username'];
        $token = $userdata['token'];

          $subject = "Password Reset";
          $body = "Hi, $username. Click here to Reset your Password http://localhost/Project/Login/reset_password.php?token=$token";
          $sender_email = "From: ace15.pg@gmail.com";
          $sender_email = "MTME-Version: 1.0" . "\r\n";
          $sender_email = "Content-type:text/html;charset=UTF-8" . "\r\n";
 
          if (mail($email, $subject, $body, $sender_email)) {
            $_SESSION['msg'] = "Check your mail to reset your Password $email";
            header('location:login.php');
          } else {
            ?>
              <script> alert("Please Enter a Valid Email Id"); </script>
            <?php 
          }
        }else{
          ?>
            <script> alert("No such Email Id Found."); </script>
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
          <h5 class="modal-title" id="exampleModalLabel">Recover Your Account</h5>
        </div>
        <div class="modal-body">
          <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="textbox">
            <input type="email" name="email" placeholder="Email" class="reg" required>
            </div>
            <div class="reg-btn">
              <input type="submit" name="login" class="btn1" value="Continue">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <h6>Already a Member? <a href="login.php">Sign In</a></h6>
        </div>
      </div>

      <?php include '../Header&Footer/footer.php'; ?>