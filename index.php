<?php
include('login.php'); // Includes Login Script
include "HEADER.php";


?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
    <!--<link href="../CSS/StyleSheet.css" rel="stylesheet" type="text/css">-->
    <style>

        h2 {
            background-color:#498af3;
            text-align:center;
            color: #fff;
            border-radius:10px 10px 0 0;
            margin:-10px -40px;
            margin-bottom:10px;
            padding:15px
        }
        hr {
            border:0;
            border-bottom:1px solid #ccc;
            margin:10px -40px;
            margin-bottom:30px
        }
        a {
            text-decoration:none;
            color:#6495ed
        }
        i {
            color:#6495ed
        }

    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#username").val('');
            $("#password").val('');
            $("#errPassword").text('');
            $(document).on("change", '.userPassword', function () {
                $("#errPassword").text('');

            });

            $(document).on("change blur", '#username', function () {
                $('#username').val($('#username').val().toLowerCase())

            });
        });
        //REDIRECT LOGIN FORM
        function checkLogin(){
            if ( $("#username").val() != ''){
                var url=document.location.href='reset.php?username='+$("#username").val();
            }
            else
                $("#errPassword").text('ENTER VALID USERNAME');
        }
    </script>
</head>
<body>
<div class="lg-container">
<div id="main">
    <h1 align="center"><img src="image/LOGO.png" align="middle"/></h1>

    <div id="login" >
        <h2>LOGIN</h2>
        <form action="" method="POST" id="lg-form" name="lg-form">
            <label >USER NAME<em>*</em></label>
            <input id="username" name="username" class="userPassword form-control" placeholder="USERNAME" type="text">
            <div class="errormsg" id="errorName"><?php echo $errorName; ?></div>
            <br>
            <label>PASSWORD<em>*</em></label>
            <input id="password" class="userPassword form-control" name="password" placeholder="PASSWORD" type="password">
            <div class="errormsg" id="errorPassword"><?php echo $errorPassword; ?></div>
            <br>
            <input name="submit" type="submit" value=" LOGIN " class="btn btn-info" id="login_create">
            <input name="reset" type="button" value="RESET PASSWORD" class="btn btn-info" onclick="checkLogin()">
            <div class="errormsg" id='errPassword'><?php echo $error?></div>
        </form>
    </div>
</div>
    </div>
</body>
</html>