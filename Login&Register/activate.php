<?php
    session_start();

    include '../Database/dbcon.php';

    if(isset($_GET['token'])){
        
        $token = $_GET['token'];
        
        $updatequery = "update register set status='active' where token  = '$token'";
        $query = mysqli_query($con, $updatequery);

        if($query){
            if(isset($_SESSION['msg'])){
                $_SESSION['msg'] = "Account updated Successfully";
                header('location:login.php');
            }else{
                $_SESSION['msg'] = "You are Logged Out.";
                header('location:login.php');
            }
        }else{
            $_SESSION['msg'] = "Account not updated Successfully";
            header('location:register.php');
        }
    }
?>