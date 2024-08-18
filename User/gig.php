<?php
    session_start();
    include '../Database/dbcon.php';
    include '../Header&Footer/header.php';

    if(!isset($_SESSION['username'])){
      header('location:../index.html');
    }

    $gig = "select * from freelancer where email='".$_SESSION['email']."'";
    $gig_query = mysqli_query($con, $gig);
    $egig = mysqli_num_rows($gig_query);
        
?>
<head>
  <link href="../assets/css/gig.css" rel="stylesheet">
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
                        <li><a href="user.php">Switch to Buying</a></li>
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

      <section id="gig-list">
        <div class="container">
          <div class="row">
            <div class="col-md-12 mt-5">
              <div class="card">
                <div class="card-header">
                  <h4>
                    Gigs
                    <a href="gigform.php" class="btn btn-primary float-right"
                      >Add Gig</a
                    >
                  </h4>
                </div>
                <div class="card-body text-center">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">Gig</th>
                        <th scope="col">Price</th>
                        <th scope="col">Delivery</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if($egig){
                        while($gig_data = mysqli_fetch_array($gig_query)){
                            $gig_price = $gig_data['gprice'];
                            $gprice = number_format($gig_price);
                      ?>
                      <tr>
                        <td class="gig">
                          <?php 
                          echo "<img src='../assets/uploads/".$gig_data['gimg']."'/>";
                          echo $gig_data['gtitle']; ?>
                          </td>
                        <td><?php echo "$gprice"; ?></td>
                        <td><?php echo $gig_data['gdelivery']; ?></td>
                        <td>
                          <a href="gigupdate.php?id=<?php echo $gig_data['gid']; ?>" class="btn btn-success">Edit</a>
                        </td>
                        <td>
                          <a href="gigdelete.php?id=<?php echo $gig_data['gid']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                      </tr>
                      <?php
                        }
                    }else{
                      ?>
                        <tr>
                          <th colspan="5">"No Records Found: Please Create a Gig"</th>
                        </tr>
                      <?php
                    }
                    ?>
                    </tbody> 
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <?php include '../Header&Footer/footer.php'; ?>