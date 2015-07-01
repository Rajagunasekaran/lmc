<?php
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
error_reporting(0);
include 'CONNECTION.php';
//$connection = mysql_connect("localhost", "root", "");
// Selecting Database
//$db = mysql_select_db("company", $connection);
session_start();// Starting Session
// Storing Session
if(isset($_SESSION['login_user'])){
$user_check=$_SESSION['login_user'];
// SQL Query To Fetch Complete Information Of User
$query = mysqli_query($con,"select * from LMC_USER_LOGIN_DETAILS where  ULD_USERNAME='$user_check'");
//$rows = mysqli_num_rows($query);
$row = mysqli_fetch_array($query);

$login_session =$row['ULD_USERNAME'];
}
if(!isset($login_session)){
//    header('Location: index.php'); // Redirecting To Home Page
    echo '<script type="text/javascript">
           location.href = "../index.php";
      </script>';

}
?>