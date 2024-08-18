<?php

    session_start();
    if(!isset($_SESSION['username'])){
        header('location:../index.html');
    }

    include '../Database/dbcon.php';

    if(isset($_POST['submit-profile'])){
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
                $sql = 'UPDATE register SET img="'.$file_name.'" WHERE email="'.$_SESSION['email'].'"';
                $res = mysqli_query($con,$sql);

                if($res){
					$fsql = 'UPDATE freelancer SET img="'.$file_name.'" WHERE email="'.$_SESSION['email'].'"';
					$fres = mysqli_query($con,$fsql);
					if($fres){
						?>
							<script> alert("Profile Image Uploaded Successfully"); 
							window.location.replace("profile.php"); </script>
                    	<?php
					}
				}
				else{
					header('Location:user.php');
					exit();
				}

			}
		}
		else{
			?>
			  <script> alert("Please Enter a JPG, PNG OR JPEG File Extension Only"); 
			  window.location.replace("profile.php"); </script>
            <?php
		}
	}

?>
