<?php

    session_start();
    if(!isset($_SESSION['username'])){
        header('location:../index.html');
    }

// if(!empty($_FILES['profile-image']['name'])){
//     //Include database configuration file
// 	include '../Database/dbcon.php';
    
//     //File uplaod configuration
//     $result = 0;
//     $uploadDir = "../assets/uploads/";
//     $fileName = time().'_'.basename($_FILES['profile-image']['name']);
//     $targetPath = $uploadDir. $fileName;
    
//     //Upload file to server
//     if(@move_uploaded_file($_FILES['profile-image']['tmp_name'], $targetPath)){
//         //Get current user ID from session
//         $userId = $_GET['id'];
//         //Update picture name in the database
//         $update = $db->query("UPDATE freelancer SET gimg = '".$fileName."' WHERE gid = $userId");
        
//         //Update status
//         if($update){
//             $result = 1;
//         }
//     }
    
//     //Load JavaScript function to show the upload status
//     echo '<script type="text/javascript">window.top.window.completeUpload(' . $result . ',\'' . $targetPath . '\');</script>  ';
// }

if($_FILES['profile-image']['name']){
    $test = explode(".", $_FILES["file"]["name"]);
    $extension = end($test);
    $name = rand(100,999) . '.' . $extension;
    $location = '../assets/uploads/'.$name;
    move_uploaded_file($_FILES["profile-image"]["tmp_name"], $location);
    echo '<img src="'.$location.'" height="150" width="225" class="img-thumbnail"'
}