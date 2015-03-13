<?php

session_start();

if(isset($_SESSION['login_user'])){
$UserStamp=$_SESSION['login_user'];
}

?>