<?php
    session_start();
    include '../Database/dbcon.php';

    if(!isset($_SESSION['username'])){
      header('location:../index.html');
    }

    $gid = $_GET['id'];
    $showquery = "select * from freelancer where gid='$gid'";
    $showdata = mysqli_query($con, $showquery);
    $email_exist = mysqli_num_rows($showdata);
    $arrdata = mysqli_fetch_array($showdata);

    // //Get current user ID from session
    // $userId = $_GET['id'];

    // //Get user data from database
    // $result = $db->query("SELECT * FROM users WHERE id = $userId");
    // $row = $result->fetch_assoc();

    //User profile picture
    $userPicture = !empty($arrdata['gimg'])?$arrdata['gimg']:'713516.jpg';
    $userPictureURL = '../assets/uploads/'.$userPicture;

    if(isset($_POST['submit-gig'])){

      $gtitle = mysqli_real_escape_string($con, $_POST['gtitle']);
      $gdescription = mysqli_real_escape_string($con, $_POST['gdesc']);
      $price = mysqli_real_escape_string($con, $_POST['gprice']);
      $gdelivery = mysqli_real_escape_string($con, $_POST['gdelivery']);
      $gcategory = mysqli_real_escape_string($con, $_POST['gcategory']);

      $value = str_replace ([',','.'], '' , $price);
      $gprice = (number_format($value, 2, ',',''));

      $file = $_FILES['profile-image'];

      $file_name = $file['name'];
      $file_tmp_name = $file['tmp_name'];
      $file_error = $file['error'];
      $file_type = $file['type'];

      $fileext = explode('.', $file_name);
      $fileextcheck = strtolower(end($fileext));

      $fileextneeded = array('png','jpg','jpeg');

      if(in_array($fileextcheck, $fileextneeded)){
        $destinationfolder = '../assets/uploads/'.$file_name;
        $fileuploaded = move_uploaded_file($file_tmp_name, $destinationfolder);
        if($fileuploaded AND $file_error == '0'){
          $email = "select * from register where email='".$_SESSION['email']."'";
          $query = mysqli_query($con, $email);
          $result = mysqli_num_rows($query);
          if($result){

            $gidupdate = $_GET['id'];

            $client_data = mysqli_fetch_array($query);
            $client_email = $client_data['email'];

            $freelancer = "Update freelancer set gtitle='$gtitle',gdescription='$gdescription',gprice='$gprice',gdelivery='$gdelivery', gcategory='$gcategory', gimg='$file_name' where gid=$gidupdate";
            
            $insert = mysqli_query($con, $freelancer);
            if($insert){
              ?>
                <script> alert("Updated Successfully"); window.location.replace("gig.php");</script>
              <?php
            }else{
              ?>
                <script> alert("Update not Successfully"); </script>
              <?php
            }
          }else{
            ?>
              <script> alert("Email Id not exists"); </script>
            <?php
          }
        }
      }else{
			  ?>
        <script> alert("Please Enter a JPG, PNG OR JPEG File Extension Only"); window.location.replace("gigupdate.php"); </script>
        <?php
		  }      
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Executives</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/gig.css" rel="stylesheet">

  <!-- Font Awesome CDN --> 
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">

  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  <script>
    $(document).ready(function(){
      $(".profile .profile_icon").click(function(){
			  $(this).parent().toggleClass("active");
			});
    });
  </script>
  <script>
      function validate() {
        var valid = false;
        var valid_delivery = false;
        var x = document.myform.pricecategory;
        var y = document.myform.deliverycategory;

        for (var i = 0; i < x.length; i++) {
          if (x[i].checked) {
            valid = true;
            break;
          }
        }

        for (var i = 0; i < y.length; i++) {
          if (y[i].checked) {
            valid_delivery = true;
            break;
          }
        }

        if (valid && valid_delivery) {
          
        } else {
          alert("Please Select the Price and the Delivery time");
          return false;
        }
      }
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            //If image edit link is clicked
            $(".editLink").on('click', function(e){
                e.preventDefault();
                $("#fileInput:hidden").trigger('click');
            });

            //On select file to upload
            $("#fileInput").on('change', function(){
                var image = $('#fileInput').val();
                var img_ex = /(\.jpg|\.jpeg|\.png)$/i;
                
                //validate file type
                if(!img_ex.exec(image)){
                    alert('Please upload only .jpg/.jpeg/.png file.');
                    $('#fileInput').val('');
                    return false;
                }else{
                    $('.uploadProcess').show();
                    $('#uploadForm').hide();
                    $( "#picUploadForm" ).submit();
                }
            });
        });

        //After completion of image upload process
        function completeUpload(success, fileName) {
            if(success == 1){
                $('#imagePreview').attr("src", "");
                $('#imagePreview').attr("src", fileName);
                $('#fileInput').attr("value", fileName);
                $('.uploadProcess').hide();
            }else{
                $('.uploadProcess').hide();
                alert('There was an error during file upload!');
            }
            return true;
        }
    </script>
    <script>
      $(document).ready(function(){
        $(document).on('change','#file',function(){
          var property = document.getElementById("file").files[0];
          var image_name = property.name;
          var image_extension = image_name.split('.').pop().toLowerCase();
          if(jQuery.inArray(image_extension,['png','jpg','jpeg']) == -1)
          {
            alert("Invalid Image File");

          }else{
            var form_data = new FormData();
            form_data.append("file", property);
            $.ajax({
              url:"upload.php",
              method:"POST",
              data:form_data,
              contentType:false,
              cache:false,
              processData:false,
              beforeSend:function(){
                $('#upload_image').html("<label class='text-success'>Image Uploading...</label>");
                success:function(data)
                {
                  $('#uploaded_image').html(data);
                }
              }
            })
          }
        })
      })
    </script>
</head>

<body>
    
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
              <li><a href="fredirect.php">DASHBOARD</a></li>
              <li><a href="#">ORDERS</a></li>
              <li><a href="gig.php">GIGS</a></li>
              <div class="profile">
                <div class="profile_icon">
                  <?php 
                    $sql = "SELECT * FROM register WHERE email='".$_SESSION['email']."'";
                    $res = mysqli_query($con,$sql);

                    if(mysqli_num_rows($res)>0){
                      while($result = mysqli_fetch_array($res)){
                        if($result['img'] == NULL OR $result['img'] == '' OR $result['img'] == ' '){       
                          echo "<img src='../assets/img/default_profile_picture.jpg' alt=''>";
                        }
                        else{
                          echo "<img src='../assets/uploads/".$result['img']."'/>";
                        }
                      }
                    }
                  ?>
                </div>
                <div class="profile_dd">
                  <ul class="profile_ul">
                    <li><a href="profile.php">Profile</a></li>
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

<!-- ======= GIG ======= -->
<section id="section1">
      <div class="container p-0">
        <div class="row">
          <div class="col-md-1"></div>
          <div class="col-md-10">
            <div class="gig-form">
              <h1>Create your Gig!</h1>
              <form
                action=""
                method="POST"
                id="gig-form"
                enctype="multipart/form-data"
                name="myform"
                onsubmit= "return validate()";
              >
                <div class="gtitle">
                  <h5>Gig Title:</h5>
                  <input
                    class="input"
                    type="text"
                    name="gtitle"
                    placeholder="I will Provide this Service to you"
                    value="<?php echo $arrdata['gtitle']; ?>"
                    required
                  />
                </div>
                <div class="gdesc">
                  <h5>Description:</h5>
                  <textarea
                    class="input"
                    placeholder="About this Service"
                    name="gdesc"
                    cols="50"
                    rows="3"
                    required
                  ><?php echo $arrdata['gdescription']; ?></textarea>
                </div>
                <div class="gprice">
                  <h5>Pricing:</h5>
                    <select name="gprice" id="">
                      <option value=""> Select your Price </option>
                      <?php for($x = 250; $x<=70000; $x += 250) {?> 
                      <option value="<?php echo $x ?>"<?php if($arrdata["gprice"]== $x){ echo "selected"; } ?>>
                      <?php echo $x ?>
                      </option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="gdelivery">
                  <h5>Delivery:</h5>
                    <select name="gdelivery" id="">
                      <option value=""> Select your Delivery Day </option>
                      <?php for($y = 1; $y<=30; $y++) {?> 
                      <option value="<?php echo "$y Day Delivery" ?>"<?php if($arrdata["gdelivery"]== $y){ echo "selected"; } ?>><?php echo $y ?> Day Delivery</option>
                      <?php } ?>
                    </select>
                  </div>
                  
                  <div class="gcategory">
                  <h5>Category:</h5>
                    <select name="gcategory" id="">
                      <option value=""> Select your Category </option>
                      <?php 
                      $category = ['Graphics & Design','Digital Marketing','Writing & Translation','Video & Animation','Music & Audio','Programming & Tech'];
                      for($z = 0; $z<=5; $z++) {?> 
                      <option value="<?php echo $category[$z] ?>"<?php if($arrdata["gcategory"]== $z){ echo "selected"; } ?>><?php echo $category[$z] ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <label>Uplaod Image</label>
                  <input type="file" name="profile-image" id="fileInput"/>
                  <br/>
                  <span id="uploaded_image"></span>

                <div class="button">
                    <a href="gig.php" class="btn btn1">
                        Cancel
                    </a>
                  <input
                    type="submit"
                    class="btn btn-primary"
                    value="Save Changes"
                    name="submit-gig"
                  />
                </div>
              </form>
              <div class="gupload-img">
                  <h5>Upload Your GIG Photo:</h5>

                  <!-- Loading image -->
                  <div class="overlay uploadProcess" style="display: none;">
                      <div class="overlay-content"><img src="../assets/uploads/200.gif"/></div>
                  </div>
                  
                  <!-- Hidden upload form -->
                  <form method="post" action="upload.php" enctype="multipart/form-data" id="picUploadForm" target="uploadTarget">
                      <input type="file" name="profile-image" id="fileInput"  style="display:none"/>
                  </form>
                  <iframe id="uploadTarget" name="uploadTarget" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
                  
                  <!-- Image update link -->
                  <a class="editLink" href="javascript:void(0);"><img src="../assets/img/Check.png"/></a>
                  
                  <!-- Profile image -->
                  <img src="<?php echo $userPictureURL; ?>" id="imagePreview" style="width:200px;height:120px;border-radius:3px">
                  <!-- <input type="file" name="profile-image" required/> -->
                </div>
            </div>
          </div>
          <div class="col-md-1"></div>
        </div>
      </div>

      <!--Modal - Upload Profile Pic-->
      <!-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
          <form action="GigImageUpdate.php?id=<?php // echo $gid; ?>" method="POST" id="profile-form" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Profile Picture</h5>
              </div>
              <div class="modal-body">
                <input type="file" name="profile-image"/>
              </div>
              <div class="modal-footer">
                <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Close" />
                <input type="submit" class="btn btn-primary" value="Save Changes" name="submit-profile" form="profile-form" />
              </div>
          </form>
            
          </div>
        </div>
      </div>-->
    </section> 
    <!-- End GIG -->

    
    <!-- ======= Footer ======= -->
    <footer id="footer">
      <div class="footer-top">
        <div class="container">
          <div class="row">
            <div class="col-lg-3 col-md-6 footer-contact">
              <h3>Execu<span>tives.</span></h3>
              <p>
                A108 Marine Lines <br />
                Mumbai, 920004 <br />
                India <br /><br />
                <span><strong>Phone:</strong></span> +91 5589 5488 55<br />
                <span><strong>Email:</strong></span>
                executives@support.com<br />
              </p>
            </div>

            <div class="col-lg-3 col-md-6 footer-links">
              <h4>Useful Links</h4>
              <ul>
                <li>
                  <i class="bx bx-chevron-right"></i> <a href="#">Home</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i> <a href="#">About us</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i> <a href="#">Services</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="#">Terms of service</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="#">Privacy policy</a>
                </li>
              </ul>
            </div>

            <div class="col-lg-3 col-md-6 footer-links">
              <h4>Our Services</h4>
              <ul>
                <li>
                  <i class="bx bx-chevron-right"></i> <a href="#">Web Design</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="#">Web Development</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="#">Product Management</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i> <a href="#">Marketing</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="#">Graphic Design</a>
                </li>
              </ul>
            </div>

            <div class="col-lg-3 col-md-6 footer-links">
              <h4>Community</h4>
              <ul>
                <li>
                  <i class="bx bx-chevron-right"></i> <a href="#">Events</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="#">Blog</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="#">Forum</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="#">Community Standards</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="#">Affiliates</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="footer-bottom container d-md-flex py-4">
        <div class="mr-md-auto text-center text-md-left">
          <div class="copyright">
            <h1 class="footer-logo mr-auto">Executives.</h1>
            &copy; Executives. International Ltd. 2020
          </div>
        </div>
        <div class="social-links text-center text-md-right pt-3 pt-md-0">
          <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
          <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
          <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
          <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
          <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
        </div>
      </div>
    </footer>
    <!-- End Footer -->

    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery.easing/jquery.easing.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>
    <script src="../assets/vendor/owl.carousel/owl.carousel.min.js"></script>
    <script src="../assets/vendor/waypoints/jquery.waypoints.min.js"></script>
    <script src="../assets/vendor/counterup/counterup.min.js"></script>
    <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="../assets/vendor/venobox/venobox.min.js"></script>
    <script src="../assets/vendor/aos/aos.js"></script>

    <!-- Template Main JS File -->
    <script src="../assets/js/main.js"></script>
  </body>
</html>