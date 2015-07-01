<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include '../LMC_LIB/CONNECTION.php';
include '../LMC_LIB/COMMON.php';

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
//    $username = stripslashes($username);
    $username = $con->real_escape_string($username);
    $password = ($_POST['password']);
    $password = $con->real_escape_string($password);
    $password = base64_encode($password);
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
if($_REQUEST['option']=='checkusername'){
    $username = $_REQUEST['username'];
    $sql="select * from LMC_USER_LOGIN_DETAILS where ULD_USERNAME='$username'";
    $sql_result= mysqli_query($con,$sql);
    $row=mysqli_num_rows($sql_result);
    $x=$row;
    if($x > 0)
    {
        $flag=1;
    }
    else{
        $flag=0;
    }
    $errormsg=get_error_msg('12');
    $value_array=array($flag,$errormsg);
    echo json_encode($value_array);
}
