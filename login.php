<?php
session_start();

include 'CONNECTION.php';
   $errorPassword ='';$errorName='';$error='';
if (isset($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $errorName = "Username or Password is invalid";
//        echo $error;
    }
    else
    {
   $username=$_POST['username'];
$password=$_POST['password'];
$username = stripslashes($username);
$password = stripslashes($password);
$username = mysql_real_escape_string($username);
$password = mysql_real_escape_string($password);
$password=  md5($password);

// Selecting Database
//$db = mysql_select_db("alliance_ts", $connection);
// SQL query to fetch information of registerd users and finds user match.
$query = mysqli_query($con,"select * from LMC_USER_LOGIN_DETAILS where ULD_PASSWORD='$password' AND ULD_USERNAME='$username'");
$rows = mysqli_num_rows($query);
if ($rows == 1) {
//    session_start();
    $_SESSION['login_user']=$username; // Initializing Session
 // Redirecting To Other Page
echo '<script type="text/javascript">
           location.href = "MENU.php";
      </script>';
} else {
    $errorPassword='Password Wrong';
//header("location: index.php");
}
//mysql_close($connection); // Closing Connection
}
}
?>