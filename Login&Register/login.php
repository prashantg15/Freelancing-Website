<?php
  session_start();
  ob_start();

  include '../Header&Footer/header.php';
  include '../Database/dbcon.php';

  $errors = array('email'=>'','password'=>'');
  $email = $dataErrors = '';

if(isset($_POST['login'])){

  // Email Validation
  if (empty($_POST['email'])) {  
    $errors['email'] = "Email is required";  
  }else{
    $email = mysqli_real_escape_string($con, $_POST['email']); 
    // check that the e-mail address is well-formed  
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {  
      $errors['email'] = "Invalid email format";  
    }  
  }

  // Password Validation
  if (empty($_POST['password'])){
    $errors['password'] = "Password is required";
  }else{
    $password = mysqli_real_escape_string($con, $_POST['password']);
  }

  if($errors == array('email'=>'','password'=>'')){
    $email = mysqli_real_escape_string($con, $_POST['email']);
  $password = mysqli_real_escape_string($con, $_POST['password']);

  $email_search = "select * from register where email='$email' and status='active'";
  $query = mysqli_query($con, $email_search);

  $email_count = mysqli_num_rows($query);
  
  if($email_count){
    $email_pass = mysqli_fetch_assoc($query);
    $db_pass = $email_pass['password'];

    $_SESSION['username'] = $email_pass['username'];
    $_SESSION['email'] = $email_pass['email'];
    $_SESSION['skills'] = $email_pass['skills'];
    $_SESSION['description'] = $email_pass['description'];
    $_SESSION['token'] = $email_pass['token'];
    $_SESSION['uid'] = $email_pass['id'];

    $pass_decode = password_verify($password, $db_pass);

    if($pass_decode){
        if(isset($_POST['rememberme'])){
          
          setcookie('emailcookie',$email,time()+86400);
          setcookie('passwordcookie',$password,time()+86400);
          
          header('location:../User/user.php');
        }else{
          header('location:../User/user.php');
        }
      }else{
        $dataErrors = 'Wrong Password. Enter Correct Password';
      }
    }else{
      $dataErrors = 'Your Email Id is not Active.';
    }
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
      
      <div class="mes12">
      <?php 
              if(isset($_SESSION['msg'])){
                ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  <strong>Hey! &nbsp; </strong>&nbsp;<?php echo $_SESSION['msg']; ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <?php  
                unset($_SESSION['msg']);
              }else{
                $_SESSION['msg'] = " You are logged Out. Please Login Again.";
                ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  <strong>Hey!</strong><?php echo $_SESSION['msg']; ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <?php 
                unset($_SESSION['msg']);
              }
            ?>
      </div>
        
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Sign In to Executives</h5>
        </div>
        <div class="modal-body">
          <div>
        </div>
          <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="textbox">
            <input type="email" name="email" placeholder="Email" class="reg" value="<?php  if(isset($_COOKIE['emailcookie'])){
              echo $_COOKIE['emailcookie'];
            }else{
              echo htmlspecialchars($email);
            } ?>">
            </div>
            <div class="red-text"><?php echo $errors['email']; ?></div>
            
            <div class="textbox">
              <input type="password" name="password" placeholder="Password" class="reg" value="<?php 
              if(isset($_COOKIE['passwordcookie'])){
                echo $_COOKIE['passwordcookie'];
              } ?>">
            </div>
            <div class="red-text"><?php echo $errors['password']; ?></div>

            <div class="red-text text-center px-0"><?php echo $dataErrors; ?></div>
            <div class="reg-btn">
              <input type="submit" name="login" class="btn1" value="Sign In">
            </div>
            <div class="log4">
              <input name="rememberme" class="reg2" type="checkbox" value="" id="defaultCheck1"> Remember Me
              <a href="recovery_email.php">Forget Password?</a> 
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <h6>Not a Member yet? <a href="register.php">Join Now</a></h6>
        </div>
      </div>

      <?php include '../Header&Footer/footer.php'; ?>