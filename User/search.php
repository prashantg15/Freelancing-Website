<?php
  include '../Database/dbcon.php';

  $output = '';
  $query = "Select * from freelancer where username like '%".$_POST["query"]."%'";
  $result = mysqli_query($con, $query);
  $output = '<ul class="list-unstyled">';
  if(mysqli_num_rows($result)>0){
    while($row = mysqli_fetch_array($result)){
      $output .= '<li?>'.$row["username"].'</li>';
    }
  }else{
    $output .= '<li>Country Not Found</li>';
  }
  $output .= '</ul>';
  echo $output;
?>