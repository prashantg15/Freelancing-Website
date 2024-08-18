<?php

    session_start();
    include '../Database/dbcon.php';

    if(!isset($_SESSION['username'])){
    header('location:../index.html');
    }

    $gid = $_GET['id'];
    $deletequery = "delete from freelancer where gid=$gid";
    $query = mysqli_query($con, $deletequery);
    if($query){
        header('location:gig.php');
    }else{
        ?>
        <script>  alert("Delete Unsuccessful"); window.location.replace("gig.php");  </script>
        <?php
    }

?>