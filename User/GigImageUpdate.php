<?php

    session_start();
    if(!isset($_SESSION['username'])){
        header('location:../index.html');
    }

	include '../Database/dbcon.php';
	
	$gid = $_GET['id'];
    $showquery = "select * from freelancer where gid='$gid'";
    $showdata = mysqli_query($con, $showquery);
    $email_exist = mysqli_num_rows($showdata);
    $arrdata = mysqli_fetch_array($showdata);

    if(!empty($_FILES['profile-image'])){
		$file = $_FILES['profile-image'];

		$file_name = $file['name'];
		$file_tmp_name = $file['tmp_name'];
		$file_error = $file['error'];
		$file_type = $file['type'];

		$fileext = explode('.', $file_name);
		$fileextcheck = strtolower(end($fileext));

		$fileextneeded = array('png','jpg','jpeg');

		if(in_array($fileextcheck, $fileextneeded)){
			$gidupdate = $_GET['id'];
            $destinationfolder = '../assets/uploads/'.$file_name;
            $fileuploaded = move_uploaded_file($file_tmp_name, $destinationfolder);
            if($fileuploaded AND $file_error == '0'){
                $sql = "UPDATE freelancer SET gimg='$file_name' WHERE gid='$gidupdate'";
                $res = mysqli_query($con,$sql);
                if($res){
						?>
							<!-- <script> alert("Profile Image Uploaded Successfully"); 
							// window.location.replace("gigupdate.php?id=<?php echo $gid ?>"); </script> -->
                    	<?php
				}
				else{
					header('Location:gig.php');
					exit();
				}

			}
		}
		else{
			?>
			  <script> alert("Please Enter a JPG, PNG OR JPEG File Extension Only"); 
			  window.location.replace("gigupdate.php?<?php echo $gid ?>"); </script>
            <?php
		}
	}

?>
