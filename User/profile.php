<?php
  session_start();

  if(!isset($_SESSION['username'])){
    header('location:../index.html');
  }

  include '../Database/dbcon.php';
  include '../Header&Footer/header.php';

    if(isset($_POST['update'])){
      
      $username = mysqli_real_escape_string($con, $_POST['username']);
      $email = mysqli_real_escape_string($con, $_POST['email']);
      $skills = mysqli_real_escape_string($con, $_POST['skills']);
      $description = mysqli_real_escape_string($con, $_POST['description']);

      $email_query = "select * from register where email='$email'";
      $query = mysqli_query($con, $email_query);

      $email_count = mysqli_num_rows($query);

      if($email_count){
        if($_SESSION['email'] === $email){
          $updatequery = "update register set username='$username', skills='$skills', description='$description' where email = '$email'";
          $iquery = mysqli_query($con, $updatequery);

            if($iquery){
              $fupdatequery = "update freelancer set username='$username' where email = '$email'";
              $fquery = mysqli_query($con, $fupdatequery);
              if($fquery){
                $_SESSION['username'] = "$username";
                $_SESSION['skills'] = "$skills";
                $_SESSION['description'] = "$description";
                ?>
                  <script> alert("Profile Got Updated Successfully"); </script>
                <?php
                header('location:profile.php');
              }     
            }else{
              ?>
                <script> alert("Profile Update UnSuccessfull"); </script>
              <?php
              header('location:profile.php');
            } 
        }else{
          ?>
            <script> alert("The new email you provided is either invalid or already taken."); </script>
          <?php 
        }
      }else{

        $token = $_SESSION['token'];

          $subject = "Verify your new Executives email address";
          $body = "Hi $username,

          We received a request to update your Executives email address. To verify this address as your new email address on Executives, Click Here. http://localhost/Executives/Presento/User/profile.php?email=$email";
          $sender_email = "From: ace15.pg@gmail.com";
          $sender_email = "MTME-Version: 1.0" . "\r\n";
          $sender_email = "Content-type:text/html;charset=UTF-8" . "\r\n";

          if (mail($email, $subject, $body, $sender_email)) {

            $updatequery = "update register set username='$username', skills='$skills', description='$description' where token = '$token'";
            $iquery = mysqli_query($con, $updatequery);
              if($iquery){
                $fupdatequery = "update freelancer set username='$username' where token = '$token'";
                $fquery = mysqli_query($con, $fupdatequery);
                if($fquery){
                  $_SESSION['username'] = "$username";
                  $_SESSION['skills'] = "$skills";
                  $_SESSION['description'] = "$description";
                }
              } 
              $_SESSION['Profile_update'] = "Check your mail to activate your Email Id $email";
              header('location:profile.php');
          }else {
            ?>
              <script> alert("Please Enter a Valid Email Id"); </script>
            <?php
          }

      }
    }

if(isset($_GET['email'])){

  $token = $_SESSION['token'];
  $email = $_GET['email'];

  $updatequery = "update register set email='$email' where token = '$token'";
  $query = mysqli_query($con, $updatequery);

  if($query){
    $feupdatequery = "update freelancer set email='$email' where token = '$token'";
    $fequery = mysqli_query($con, $feupdatequery);
    if($fequery){
      if(isset($_SESSION['Profile_update'])){
        $_SESSION['Profile_update'] = "Profile updated Successfully";
        $_SESSION['email'] = "$email";
        header('location:profile.php');
      }else{
          $_SESSION['Profile_update'] = "";
          header('location:profile.php');
      }
    }  
  }else{
      $_SESSION['Profile_update'] = "Profile not Updated Successfully";
      header('location:profile.php');
  }

}

?>

<head>
  <link href="../assets/css/profile.css" rel="stylesheet">
</head>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container-fluid">
          <div class="row justify-content-center navgation">
            <div class="col-xl-10 d-flex align-items-center">
              <h1 class="logo mr-auto"><a href="user.php">Execu<span>tives.</span></a></h1>
              <!-- Uncomment below if you prefer to use an image logo -->
              <!-- <a href="index.html" class="logo mr-auto"><img src="assets/img/logo.png" alt=""></a>--> 
              <nav class="  nav-menu d-none d-lg-block">
                <ul>
                  <li><a href="#">ORDERS</a></li>
                  <div class="profile">
                    <div class="profile_icon">
                      <?php 
                        $sql = "SELECT * FROM register WHERE email='".$_SESSION['email']."'";
                        $res = mysqli_query($con,$sql);

                        if(mysqli_num_rows($res)){
                          while($row = mysqli_fetch_array($res)){
                            if($row['img'] == NULL OR $row['img'] == '' OR $row['img'] == ' '){       
                              echo "<img src='../assets/img/default_profile_picture.jpg' alt=''>";
                            }
                            else{
                              echo "<img src='../assets/uploads/".$row['img']."'/>";
                            }
                          }
                        }
                      ?>
                    </div>
                    <div class="profile_dd">
                      <ul class="profile_ul">
                        <li><a href="user.php">Home</a></li>
                        <li><a href="fredirect.php">Become a Seller</a></li>
                        <li><a href="../logout.php">Logout</a></li>
                      </ul>
                    </div>
                  </div>
                </ul>
              </nav><!-- .nav-menu -->
            </div>
          </div>
        </div>
      </header><!-- End Header -->

    <section class="profile-card">
      <div class="container">
        <div class="row">
          <div class="col-md-4 col-sm-12">
            <div class="User-Profile">
              <?php 
                  $sql = "SELECT * FROM register WHERE email='".$_SESSION['email']."'";
                  $res = mysqli_query($con,$sql);

                  if(mysqli_num_rows($res)){
                    while($row = mysqli_fetch_array($res)){
                      if($row['img'] == NULL OR $row['img'] == '' OR $row['img'] == ' '){       
                        echo "<img src='../assets/img/default_profile_picture.jpg' alt=''>";
                      }
                      else{
                        echo "<img src='../assets/uploads/".$row['img']."'/>";
                      }
                    }
                  }
                ?>
              <h4><?php echo $_SESSION['username']; ?></h4>
              <h6><?php echo $_SESSION['email']; ?></h6>
              <div class="skills">
                <h4>Skills</h4>
                <h6><?php echo $_SESSION['skills']; ?></h6>
              </div>
              <div class="desc">
                <h4>Description</h4>
                <p>
                  <?php echo $_SESSION['description']; ?>
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-8 col-sm-12">
            <div class="Update-Profile">
              <form action="" method="POST">
                <?php 
                  $sql = "SELECT * FROM register WHERE email='".$_SESSION['email']."'";
                  $res = mysqli_query($con,$sql);

                  if(mysqli_num_rows($res)){
                    while($row = mysqli_fetch_array($res)){
                      if($row['img'] == NULL OR $row['img'] == '' OR $row['img'] == ' '){       
                        echo "<div class='wrapper'><img src='../assets/img/default_profile_picture.jpg' alt=''><div class='icon1'><a href='#' data-toggle='modal' data-target='#exampleModal'><i class='fas fa-camera'></i></a></div></div>";
                      }
                      else{
                        echo "<div class='wrapper'><img src='../assets/uploads/".$row['img']."'/></a><div class='icon1'><a href='#' data-toggle='modal' data-target='#exampleModal'><i class='fas fa-camera'></i></a></div></div>";
                      }
                    }
                  }
                ?>
                <div class="username">
                  <h6>Username :</h6>
                  <input
                    class="input1"
                    name="username"
                    type="text"
                    placeholder="Username"
                    maxlength="10"
                    value="<?php  if(isset($_SESSION['username'])){
                      echo $_SESSION['username'];
                    } ?>"
                    required
                  />
                </div>
                <div class="email">
                  <h6>Email :</h6>
                  <input
                    class="input"
                    name="email"
                    type="email"
                    placeholder="Email"
                    value="<?php  if(isset($_SESSION['email'])){
                      echo $_SESSION['email'];
                    } ?>"
                    required
                  />
                </div>
                <div class="skills">
                  <h6>Skills :</h6>
                  <input
                    class="input"
                    name="skills"
                    type="text"
                    placeholder="Skills"
                    value="<?php  if(isset($_SESSION['skills'])){
                      echo $_SESSION['skills'];
                    } ?>"
                  />
                </div>
                <div class="desc">
                  <h6>Description :</h6>
                  <textarea name="description"placeholder="Description"maxlength="240"cols="77"rows="4"><?php  if(isset($_SESSION['description'])){
                    echo $_SESSION['description'];
                  } ?></textarea>
                </div>
                <input
                  type="submit"
                  name="update"
                  class="button"
                  value="Save Changes"
                />
              </form>
            </div>
          </div>
        </div>
      </div>

      <!--Modal - Upload Profile Pic-->
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
          <form action="updateProfile.php" method="POST" id="profile-form" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Profile Picture</h5>
              </div>
              <div class="modal-body">
                <input type="file" name="profile-image" required/>
              </div>
              <div class="modal-footer">
                <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Close" />
                <input type="submit" class="btn btn-primary" value="Save Changes" name="submit-profile" form="profile-form" />
              </div>
          </form>
            
          </div>
        </div>
      </div>
    </section>

    <?php include '../Header&Footer/footer.php'; ?>