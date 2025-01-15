<?php
// $conn = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database)or die('Cannot open database');	
// $con=mysqli_connect("localhost", "id13019632codeastro.com", "PASS=word@codeastro.com", "id13019632_attendance");
// $con=mysqli_connect("localhost", "id13019632Geniusit", "PASS=IattendGeniusitBrainery", "id13019632_attendance");

$conn=mysqli_connect("localhost", "root", "", "resultgrading");
if(mysqli_connect_errno()){
    echo "Connection Fail".mysqli_connect_error(); 
}

    // $conn=mysqli_connect("localhost", "root", "codeastro.com", "amsys");
    // $conn=mysqli_connect("localhost", "root", "", "amsys");
    // if(mysqli_connect_errno()){
    // echo "Connection Fail".mysqli_connect_error();
    // }

?>
