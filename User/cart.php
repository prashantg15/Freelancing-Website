<?php 

    session_start();

    if(!isset($_SESSION['username'])){
    header('location:../index.html');
    }

    include '../Database/dbcon.php';

    if(isset($_GET['id'])){
    
        $id = $_GET['id'];

        $query = "Select * from freelancer where gid='$id'";
        $query_run = mysqli_query($con, $query);
        $check_gig = mysqli_num_rows($query_run);

        if($check_gig){

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
  <link href="../assets/css/cart.css" rel="stylesheet">

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
          <!-- <form action="details.php" class="search mr-auto">
              <i class="fas fa-search"></i><input type="text" name="search" placeholder="Find Services">
              <input type="submit" name="submit" value="Search" class="button">  
          </form> -->
          <nav class="  nav-menu d-none d-lg-block">
            <ul>
              <li><a href="#">ORDERS</a></li>
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

<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center">
    <div class="container-fluid" data-aos="zoom-out" data-aos-delay="100">
      <div class="row justify-content-center navgation">
        <div class="col-xl-10">
          <div class="row">
              <?php
                if($row = mysqli_fetch_array($query_run)){
                  $gig_price = $row['gprice'];
                  $gprice = number_format($gig_price);
                  $servicefee = $gig_price*8/100;
                  $sfee = number_format($servicefee);
                  $tot = $gig_price + $servicefee;
                  $total = number_format($tot);
              ?>
            <div class="col-md-8 mt-5">
                <div class="cart">
                    <h1>Order Details</h1>
                    <div class="row">
                        <div class="gig-info col-md-4">
                            <?php echo "<img src='../assets/uploads/".$row['gimg']."' style='width: 220px; height: 130px; border-radius: 3px; margin-top: 40px;'/>";?>
                            <!-- <img src="../assets/uploads/123.jpg" alt="" style="width: 220px; height: 130px; border-radius: 3px; margin-top: 40px;">    -->
                        </div>
                        <div class="gig-info col-md-8 text-left">
                            <h2 style="font-weight: 600; font-size: 28px; border-bottom: 1px #444444 solid;width: 90%;padding-bottom: 8px;">Gig Title</h2>
                            <h3 style="padding-top: 8px;font-size: 22px; color: black;"><?php echo $row['gtitle']; ?></h3>
                            <h2 style="font-weight: 600; font-size: 28px; border-bottom: 1px #444444 solid; width: 90%; padding-bottom: 8px; padding-top: 30px;">Price</h2>
                            <h4 class="" style="padding-top: 8px;font-size: 22px; color: black;"><span><i class="fas fa-rupee-sign "></i> <?php echo $gprice; ?> </span></h4>
                            <h2 style="font-weight: 600; font-size: 28px; border-bottom: 1px #444444 solid; width: 90%; padding-bottom: 8px; padding-top: 30px;">Delivery Time</h2>
                            <h3 style="padding-top: 8px;font-size: 22px; color: black;"><?php echo $row['gdelivery']; ?></h3>
                        </div>
                    </div>
                </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="row">
                <div class="Total col-md-6">
                  <h4 style="">Summary</h4>
                  <div class="card-body mt-3" style="">
                    <h5>Subtotal</h5>
                    <h5 style="border-bottom: 1px #444444 solid; padding-bottom: 10px !important;">Service Fee</h5> 
                    <h5>Total</h5>
                    <h5>Delivery Time</h5>    
                  </div>
                </div>
                <div class="price col-md-6">
                  <div class="card-body mt-3">
                    <h5><span><i class="fas fa-rupee-sign "></i> <?php echo $gprice; ?> </span></h5>
                    <h5 style="border-bottom: 1px #444444 solid; padding-bottom: 10px !important;"><span><i class="fas fa-rupee-sign "></i> <?php echo $sfee; ?> </span></h5>   
                    <h5><span><i class="fas fa-rupee-sign "></i> <?php echo $total; ?> </span></h5>
                    <h5><?php echo $row['gdelivery']; ?></h5> 
                  </div>
                </div>
              </div>
                <div class="checkout">
                    <form method="POST" action="../PaytmKit/pgRedirect.php">
                      <input type="hidden" id="ORDER_ID" tabindex="1" maxlength="20" size="20"
                      name="ORDER_ID" autocomplete="off"
                      value="<?php echo  "ORDS" . rand(10000,99999999)?>">
                      <input type="hidden" id="CUST_ID" tabindex="2" maxlength="12" size="12" name="CUST_ID" autocomplete="off" value="<?php echo "CUST0".$_SESSION['uid'] ?>">
                      <input type="hidden" id="INDUSTRY_TYPE_ID" tabindex="4" maxlength="12" size="12" name="INDUSTRY_TYPE_ID" autocomplete="off" value="Retail">
                      <input type="hidden" id="CHANNEL_ID" tabindex="4" maxlength="12"
                      size="12" name="CHANNEL_ID" autocomplete="off" value="WEB">
                      <input type="hidden" title="TXN_AMOUNT" tabindex="10"
                      type="text" name="TXN_AMOUNT"
                      value="<?php echo $tot; ?>">
                      <?php $_SESSION["gid"] = $row['gid'];
                            $_SESSION["tot"] = $tot;
                      ?>
                      <input value="Continue to Checkout" class="btn" type="submit"	onclick="">
                      <h5 style="font-size: 15px; padding: 8px;">You won't be charged yet</h5>
                    </form>
                    <!-- <a href="../PaytmKit/pgRedirect.php" class="btn">Continue to Checkout</a>
                    <h5 style="font-size: 15px; padding: 8px;">You won't be charged yet</h5> -->
                </div>
              </div>
              <div class="cards">
                <img src="../assets/uploads/Tulips.jpg" alt="">
                <img src="../assets/uploads/Tulips.jpg" alt="">
                <img src="../assets/uploads/Tulips.jpg" alt="">
                <img src="../assets/uploads/Tulips.jpg" alt="">
                <img src="../assets/uploads/Tulips.jpg" alt=""> 
              </div>
              <div class="content1">
                <h6 style="padding-left: 115px; padding-top: 40px; font-size: 15px;font-weight: 500;">SSL SECURED PAYMENT</h6>
                <h6 style="padding-left: 90px; font-size: 15px;font-weight: 500; margin-top: -8px;">Your information is protected by </br></h6>
                <h6 style="padding-left: 123px; font-size: 15px;font-weight: 500; margin-top: -8px;">256-bit SSL encryption.</h6>
            </div> 
        </div>
            <?php
                    }
                }
            }
            ?>
        </div>
      </div>
    </div>
  </section><!-- End Hero -->
  
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
  