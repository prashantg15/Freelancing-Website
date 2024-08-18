<?php
  session_start();

  include '../Header&Footer/header.php';
  include '../Database/dbcon.php';

  $username = $email = $dataError = '';
  $errors = array('username'=>'','email'=>'','password'=>'','cpassword'=>'');

  if(isset($_POST['register'])){

    // Username Validation
    if (empty($_POST['username'])) {  
      $errors['username'] = 'Name is required';  
    } else {  
      $username = mysqli_real_escape_string($con, $_POST['username']); 
      // check if name only contains letters and whitespace  
      if (!preg_match("/^[a-zA-Z ]*$/",$username)) {  
        $errors['username'] = "Only alphabets and white space are allowed"; 
      }  
    }  

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

    // Confirm Password Validation
    if (empty($_POST['cpassword'])){
      $errors['cpassword'] = "Password is required";
    }else{
      $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    }

    if($errors == array('username'=>'','email'=>'','password'=>'','cpassword'=>'')){
      $pass = password_hash($password, PASSWORD_BCRYPT);
      $cpass = password_hash($cpassword, PASSWORD_BCRYPT);

      $token = bin2hex(random_bytes(15));

      $email_query = "select * from register where email='$email'";
      $query = mysqli_query($con, $email_query);

      $email_count = mysqli_num_rows($query);

      if($email_count){
        $dataError = 'Email ID Already Exists';
      }
      else{
        if($password === $cpassword){
          
            $subject = "Email Activation";
            $body = "Hi, $username. Click here to activate your account http://localhost/Executives/Presento/Login&Register/activate.php?token=$token";
            $sender_email = "From: ace15.pg@gmail.com";
            $sender_email = "MTME-Version: 1.0" . "\r\n";
            $sender_email = "Content-type:text/html;charset=UTF-8" . "\r\n";
  
            if (mail($email, $subject, $body, $sender_email)) {

                $insertquery = "insert into register(username, email, password, cpassword, token, status) values('$username','$email','$pass','$cpass','$token','inactive')";
                $iquery = mysqli_query($con, $insertquery);
                
                $_SESSION['msg'] = " Check your mail to activate your account $email";
                header('location:login.php');
            } else {
              $dataError = 'Please Enter a Valid Email Id';
            }
          }else{
            $dataError = 'Passwords not Matching';   
          }
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
              <li><a href="../freelancer.html">BECOME A SELLER</a></li>
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
        <h5 class="modal-title" id="exampleModalLabel">Join Executives</h5>
      </div>
      <div class="modal-body">
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
          <div class="textbox">
            <input type="text" name="username" placeholder="Username" class="reg" maxlength="10" value="<?php echo htmlspecialchars($username); ?>">
          </div>
          <div class="red-text"><?php echo $errors['username']; ?></div>
          <div class="textbox">
            <input type="email" name="email" placeholder="Email" class="reg" value="<?php echo htmlspecialchars($email); ?>">
          </div>
          <div class="red-text"><?php echo $errors['email']; ?></div>
          <div class="textbox">
            <input type="password" name="password" placeholder="Password" class="reg">
          </div>
          <div class="red-text"><?php echo $errors['password']; ?></div>
          <div class="textbox">
            <input type="password" name="cpassword" placeholder="Confirm Password" class="reg">
          </div>
          <div class="red-text"><?php echo $errors['cpassword']; ?></div>
          <div class="red-text text-center px-0"><?php echo $dataError; ?></div>
          <div class="reg-btn">
            <input type="submit" name="register" class="btn1" value="Register">
          </div>
          <div class="reg4">
            <label class="reg3" for="defaultCheck1">
              By joining I agree to receive emails from Executives.
            </label> 
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <h6>Already a Member? <a href="login.php">Sign In</a></h6>
      </div>
    </div>

<?php include '../Header&Footer/footer.php'; ?>