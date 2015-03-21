<?php
session_start();

include 'CONNECTION.php';
   $errorPassword ='';$errorName='';$error='';
$error_message='';
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

        $check_terminate = mysqli_query($con,"select * from VW_ACCESS_RIGHTS_TERMINATE_LOGINID where ULD_USERNAME='$username'");
        $rows = mysqli_num_rows($check_terminate);
      if($rows>0)
        {
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

        }

        else{
            $errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM LMC_ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN (61)");
            if($row=mysqli_fetch_array($errormsg)){
                $errormessage=$row["EMC_DATA"];

                $error_message=str_replace('[LOGIN ID]',$username,$errormessage);
//                $errormessage=$errormessage.explode("[LOGINID]",$username);
            }


        }

    }
}
?>