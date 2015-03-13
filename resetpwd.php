<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include 'CONNECTION.php';
//if ($_POST['choice'] == "password") {
//    $username = $_POST['username'];
//    $username = stripslashes($username);
//    $username = mysql_real_escape_string($username);
//    $password = stripslashes($_POST['password']);
//    $password = mysql_real_escape_string($password);
//    $password = md5($password);
////    $connection = mysql_connect("localhost", "root", "");
//// Selecting Database
////    $db = mysql_select_db("alliance_ts", $connection);
//// SQL query to fetch information of registerd users and finds user match.
//    $query = mysqli_query($con,"select * from LMC_USER_LOGIN_DETAILS where ULD_PASSWORD='$password' AND ULD_USERNAME='$username'");
//    $rows = mysqli_num_rows($query);
//    if ($rows == 1) {
//        $error = "GIVE ANOTHER PASSWORD";
//    } else {
//        $error = "";
//    }
//    echo $error;
//}
if ($_POST['choice'] == "resetPassword") {

    $username = $_POST['username'];
    $username = stripslashes($username);
    $username = mysql_real_escape_string($username);
    $password = stripslashes($_POST['password']);
    $password = mysql_real_escape_string($password);
    $password = md5($password);
    $sql = "UPDATE LMC_USER_LOGIN_DETAILS SET ULD_PASSWORD='$password' WHERE (ULD_USERNAME='$username')";
    if (!mysqli_query($con, $sql)) {
        die('Error: ' . mysqli_error($con));
        $error = '';
        $flag=0;
    } else {

        $error = 'UPDATED';
        $flag=1;
    }
    echo $flag;
}
