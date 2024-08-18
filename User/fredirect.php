<?php

    session_start();
    include '../Database/dbcon.php';

    $freelancer_email = "select * from freelancer where email='".$_SESSION['email']."'";
    $check_email = mysqli_query($con, $freelancer_email);
    $res = mysqli_num_rows($check_email);
    if($res){
        header('location:fdashboard.php');
    }else{
        header('location:gigform.php');
    }
?>