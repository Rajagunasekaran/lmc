<?php
error_reporting(0);
session_start();
setcookie('tmidcookieName',0);
setcookie('pricetypecookieName',0);
setcookie('checklist_projectid',0);
setcookie('checklist_flagid',0);
setcookie('oldtmidcookieName',0);
include 'LMC_LIB/CONNECTION.php';
$errorPassword ='';$errorName='';$error='';
$error_message='';
if (isset($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error = "Invalid Username or Password";
    }
    else
    {
        $username=$_POST['username'];
        $password=$_POST['password'];
        //$username = stripslashes($username);
        //$password = stripslashes($password);
        $password=  base64_encode($password);
        $username = $con->real_escape_string($username);
        $password = $con->real_escape_string($password);
        $check_terminate = mysqli_query($con,"select * from VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS where ULD_USERNAME='$username'");
        $rows = mysqli_num_rows($check_terminate);
        if($rows>0)
        {
            $query = mysqli_query($con,"SELECT * FROM LMC_USER_LOGIN_DETAILS LUD,LMC_ROLE_CREATION LRC WHERE LRC.RC_ID=LUD.RC_ID AND ULD_PASSWORD='$password' AND ULD_USERNAME='$username'");
            $rows = mysqli_num_rows($query);
            if ($rows == 1) {
                // session_start();
                $_SESSION['login_user']=$username; // Initializing Session
                // Redirecting To Other Page
                echo '<script type="text/javascript">
                location.href = "MENU.php";
                </script>';
            } else {
                $error='Invalid Password';
            }
        }
        else{
//            $errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM LMC_ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN (61)");
//            if($row=mysqli_fetch_array($errormsg)){
//                $errormessage=$row["EMC_DATA"];
//                $error=str_replace('[LOGIN ID]',$username,$errormessage);
//            }
            $error = "Invalid Username";
        }

    }
}
?>