<?php
    session_start();
    include '../Database/dbcon.php';

    if(!isset($_SESSION['username'])){
      header('location:../index.html');
    }

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
            $client_data = mysqli_fetch_array($query);
            $client_email = $client_data['email'];
            $client_username = $client_data['username'];
            $client_img = $client_data['img'];
            $client_token = $client_data['token'];

            $freelancer = "Insert into Freelancer(email, username, img, token, gtitle, gdescription, gprice, gdelivery,gcategory,gimg) values('$client_email','$client_username','$client_img','$client_token','$gtitle','$gdescription','$gprice','$gdelivery','$gcategory','$file_name')";
            $insert = mysqli_query($con, $freelancer);
            if($insert){
              ?>
                <script> alert("Inserted Successfully"); window.location.replace("fdashboard.php");</script>
              <?php
            }else{
              ?>
                <script> alert("Inserted not Successfully"); </script>
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
        <script> alert("Please Enter a JPG, PNG OR JPEG File Extension Only"); window.location.replace("gigform.php"); </script>
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
        function loadfile(event){
            var output = document.getElementById('preimage');
            output.src = URL.createObjectURL(event.target.files[0]);
        };
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
                  ></textarea>
                </div>

                <div class="gprice">
                  <h5>Pricing:</h5>
                    <select name="gprice" id="">
                      <option value=""> Select your Price </option>
                      <?php for($x = 250; $x<=70000; $x += 250) {?> 
                      <option value="<?php echo $x ?>"><?php echo $x ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="gdelivery">
                  <h5>Delivery:</h5>
                    <select name="gdelivery" id="">
                      <option value=""> Select your Delivery Day </option>
                      <?php for($y = 1; $y<=30; $y++) {?> 
                      <option value="<?php echo "$y Day Delivery" ?>"><?php echo $y ?> Day Delivery</option>
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
                      <option value="<?php echo $category[$z] ?>"><?php echo $category[$z] ?></option>
                      <?php } ?>
                    </select>
                  </div>

                <!-- <div class="gprice">
                  <h5>Pricing:</h5>
                  <div class="select-box">
                    <div class="options-container">
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 250"
                          name="pricecategory"
                          value="250"
                        />
                        <label for="₹ 250">₹ 250</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 500"
                          name="pricecategory"
                          value="500"
                        />
                        <label for="₹ 500">₹ 500</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 750"
                          name="pricecategory"
                          value="750"
                        />
                        <label for="₹ 750">₹ 750</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 1,000"
                          name="pricecategory"
                          value="1,000"
                        />
                        <label for="₹ 1,000">₹ 1,000</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 1,250"
                          name="pricecategory"
                          value="1,250"
                        />
                        <label for="₹ 1,250">₹ 1,250</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 1,500"
                          name="pricecategory"
                          value="1,500"
                        />
                        <label for="₹ 1,500">₹ 1,500</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 1,750"
                          name="pricecategory"
                          value="1,750"
                        />
                        <label for="₹ 1,750">₹ 1,750</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 2,000"
                          name="pricecategory"
                          value="2,000"
                        />
                        <label for="₹ 2,000">₹ 2,000</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 2,250"
                          name="pricecategory"
                          value="2,250"
                        />
                        <label for="₹ 2,250">₹ 2,250</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 2,500"
                          name="pricecategory"
                          value="2,500"
                        />
                        <label for="₹ 2,500">₹ 2,500</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 2,750"
                          name="pricecategory"
                          value="2,750"
                        />
                        <label for="₹ 2,750">₹ 2,750</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 3,000"
                          name="pricecategory"
                          value="3,000"
                        />
                        <label for="₹ 3,000">₹ 3,000</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 3,250"
                          name="pricecategory"
                          value="3,250"
                        />
                        <label for="₹ 3,250">₹ 3,250</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 3,500"
                          name="pricecategory"
                          value="3,500"
                        />
                        <label for="₹ 3,500">₹ 3,500</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 3,750"
                          name="pricecategory"
                          value="3,750"
                        />
                        <label for="₹ 3,750">₹ 3,750</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 4,000"
                          name="pricecategory"
                          value="4,000"
                        />
                        <label for="₹ 4,000">₹ 4,000</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 4,250"
                          name="pricecategory"
                          value="4,250"
                        />
                        <label for="₹ 4,250">₹ 4,250</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 4,500"
                          name="pricecategory"
                          value="4,500"
                        />
                        <label for="₹ 4,500">₹ 4,500</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 4,750"
                          name="pricecategory"
                          value="4,750"
                        />
                        <label for="₹ 4,750">₹ 4,750</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 5,000"
                          name="pricecategory"
                          value="5,000"
                        />
                        <label for="₹ 5,000">₹ 5,000</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 5,250"
                          name="pricecategory"
                          value="5,250"
                        />
                        <label for="₹ 5,250">₹ 5,250</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 5,500"
                          name="pricecategory"
                          value="5,500"
                        />
                        <label for="₹ 5,500">₹ 5,500</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 5,750"
                          name="pricecategory"
                          value="5,750"
                        />
                        <label for="₹ 5,750">₹ 5,750</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 6,000"
                          name="pricecategory"
                          value="6,000"
                        />
                        <label for="₹ 6,000">₹ 6,000</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 6,250"
                          name="pricecategory"
                          value="6,250"
                        />
                        <label for="₹ 6,250">₹ 6,250</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 6,500"
                          name="pricecategory"
                          value="6,500"
                        />
                        <label for="₹ 6,500">₹ 6,500</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 6,750"
                          name="pricecategory"
                          value="6,750"
                        />
                        <label for="₹ 6,750">₹ 6,750</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 7,000"
                          name="pricecategory"
                          value="7,000"
                        />
                        <label for="₹ 7,000">₹ 7,000</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 7,250"
                          name="pricecategory"
                          value="7,250"
                        />
                        <label for="₹ 7,250">₹ 7,250</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 7,500"
                          name="pricecategory"
                          value="7,500"
                        />
                        <label for="₹ 7,500">₹ 7,500</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 7,750"
                          name="pricecategory"
                          value="7,750"
                        />
                        <label for="₹ 7,750">₹ 7,750</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 8,000"
                          name="pricecategory"
                          value="8,000"
                        />
                        <label for="₹ 8,000">₹ 8,000</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 8,250"
                          name="pricecategory"
                          value="8,250"
                        />
                        <label for="₹ 8,250">₹ 8,250</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 8,500"
                          name="pricecategory"
                          value="8,500"
                        />
                        <label for="₹ 8,500">₹ 8,500</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 8,750"
                          name="pricecategory"
                          value="8,750"
                        />
                        <label for="₹ 8,750">₹ 8,750</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 9,000"
                          name="pricecategory"
                          value="9,000"
                        />
                        <label for="₹ 9,000">₹ 9,000</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 9,250"
                          name="pricecategory"
                          value="9,250"
                        />
                        <label for="₹ 9,250">₹ 9,250</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 9,500"
                          name="pricecategory"
                          value="9,500"
                        />
                        <label for="₹ 9,500">₹ 9,500</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 9,750"
                          name="pricecategory"
                          value="9,750"
                        />
                        <label for="₹ 9,750">₹ 9,750</label>
                      </div>
                      <div class="option">
                        <input
                          type="radio"
                          class="radio"
                          id="₹ 10,000"
                          name="pricecategory"
                          value="10,000"
                        />
                        <label for="₹ 10,000">₹ 10,000</label>
                      </div>
                    </div>

                    <div class="selected">Select Your Price</div>
                  </div>
                </div>
                <div class="gdelivery">
                  <h5>Delivery Time:</h5>
                  <div class="select-box2">
                    <div class="options-container2">
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id=" 1 Day Delivery "
                          name="deliverycategory"
                          value="1 Day Delivery"
                        />
                        <label for=" 1 Day Delivery "> 1 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="2 Day Delivery"
                          name="deliverycategory"
                          value="2 Day Delivery"
                        />
                        <label for="2 Day Delivery"> 2 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="3 Day Delivery"
                          name="deliverycategory"
                          value="3 Day Delivery"
                        />
                        <label for="3 Day Delivery"> 3 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="4 Day Delivery"
                          name="deliverycategory"
                          value="4 Day Delivery"
                        />
                        <label for="4 Day Delivery"> 4 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="5 Day Delivery"
                          name="deliverycategory"
                          value="5 Day Delivery"
                        />
                        <label for="5 Day Delivery"> 5 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="6 Day Delivery"
                          name="deliverycategory"
                          value="6 Day Delivery"
                        />
                        <label for="6 Day Delivery"> 6 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="7 Day Delivery"
                          name="deliverycategory"
                          value="7 Day Delivery"
                        />
                        <label for="7 Day Delivery"> 7 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="8 Day Delivery"
                          name="deliverycategory"
                          value="8 Day Delivery"
                        />
                        <label for="8 Day Delivery"> 8 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="9 Day Delivery"
                          name="deliverycategory"
                          value="9 Day Delivery"
                        />
                        <label for="9 Day Delivery"> 9 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="10 Day Delivery"
                          name="deliverycategory"
                          value="10 Day Delivery"
                        />
                        <label for="10 Day Delivery"> 10 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="11 Day Delivery"
                          name="deliverycategory"
                          value="11 Day Delivery"
                        />
                        <label for="11 Day Delivery"> 11 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="12 Day Delivery"
                          name="deliverycategory"
                          value="12 Day Delivery"
                        />
                        <label for="12 Day Delivery"> 12 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="13 Day Delivery"
                          name="deliverycategory"
                          value="13 Day Delivery"
                        />
                        <label for="13 Day Delivery"> 13 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="14 Day Delivery"
                          name="deliverycategory"
                          value="14 Day Delivery"
                        />
                        <label for="14 Day Delivery"> 14 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="15 Day Delivery"
                          name="deliverycategory"
                          value="15 Day Delivery"
                        />
                        <label for="15 Day Delivery"> 15 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="16 Day Delivery"
                          name="deliverycategory"
                          value="16 Day Delivery"
                        />
                        <label for="16 Day Delivery"> 16 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="17 Day Delivery"
                          name="deliverycategory"
                          value="17 Day Delivery"
                        />
                        <label for="17 Day Delivery"> 17 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="18 Day Delivery"
                          name="deliverycategory"
                          value="18 Day Delivery"
                        />
                        <label for="18 Day Delivery"> 18 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="19 Day Delivery"
                          name="deliverycategory"
                          value="19 Day Delivery"
                        />
                        <label for="19 Day Delivery"> 19 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="20 Day Delivery"
                          name="deliverycategory"
                          value="20 Day Delivery"
                        />
                        <label for="20 Day Delivery"> 20 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="21 Day Delivery"
                          name="deliverycategory"
                          value="21 Day Delivery"
                        />
                        <label for="21 Day Delivery"> 21 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="22 Day Delivery"
                          name="deliverycategory"
                          value="22 Day Delivery"
                        />
                        <label for="22 Day Delivery"> 22 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="23 Day Delivery"
                          name="deliverycategory"
                          value="23 Day Delivery"
                        />
                        <label for="23 Day Delivery"> 23 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="24 Day Delivery"
                          name="deliverycategory"
                          value="24 Day Delivery"
                        />
                        <label for="24 Day Delivery"> 24 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="25 Day Delivery"
                          name="deliverycategory"
                          value="25 Day Delivery"
                        />
                        <label for="25 Day Delivery"> 25 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="26 Day Delivery"
                          name="deliverycategory"
                          value="26 Day Delivery"
                        />
                        <label for="26 Day Delivery"> 26 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="27 Day Delivery"
                          name="deliverycategory"
                          value="27 Day Delivery"
                        />
                        <label for="27 Day Delivery"> 27 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="28 Day Delivery"
                          name="deliverycategory"
                          value="28 Day Delivery"
                        />
                        <label for="28 Day Delivery"> 28 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="29 Day Delivery"
                          name="deliverycategory"
                          value="29 Day Delivery"
                        />
                        <label for="29 Day Delivery"> 29 Day Delivery </label>
                      </div>
                      <div class="option2">
                        <input
                          type="radio"
                          class="radio2"
                          id="30 Day Delivery"
                          name="deliverycategory"
                          value="30 Day Delivery"
                        />
                        <label for="30 Day Delivery"> 30 Day Delivery </label>
                      </div>
                    </div>

                    <div class="selected2">Select Your Delivery Time</div>
                  </div>
                </div> -->

                <div class="gupload-img">
                  <h5>Upload Your GIG Photo:</h5>
                  <input type="file" name="profile-image" onchange="loadfile(event)" required/>
                  <!-- $userPicture = !empty($arrdata['gimg'])?$arrdata['gimg']:'713516.jpg';
                  $userPictureURL = '../assets/uploads/'.$userPicture; -->
                  <img id="preimage" width="220px" height="120px" style="margin-top: 20px;">
                </div>
                <div class="button">
                    <a href="user.php" class="btn btn1">
                        Cancel
                    </a>
                  <input
                    type="submit"
                    class="btn btn-primary"
                    value="Submit"
                    name="submit-gig"
                  />
                </div>
              </form>
            </div>
          </div>
          <div class="col-md-1"></div>
        </div>
      </div>

      <!--Modal - Upload Profile Pic-->
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
          <!-- <form action="" method="POST" id="profile-form" enctype="multipart/form-data"> -->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Profile Picture</h5>
              </div>
              <div class="modal-body">
                <input type="file" name="profile-image"/>
              </div>
              <div class="modal-footer">
                <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Close" />
                <!-- <input type="submit" class="btn btn-primary" value="Save Changes" name="submit-profile" form="profile-form" /> -->
              </div>
          <!-- </form> -->
            
          </div>
        </div>
      </div>
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
    <script src="../assets/js/gigform.js"></script>
  </body>
</html>
