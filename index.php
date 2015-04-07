<?php
include('login.php'); // Includes Login Script
include "HEADER.php";
//$error=$error;
//$errorPassword=$error;
//$errorName=$error;

?>

<!DOCTYPE html>
<html>
<head>
    <title>LIH MING CONSTRUCTION PTE LTD</title>
    <!--<link href="../CSS/StyleSheet.css" rel="stylesheet" type="text/css">-->
    <style>
        /*#main {*/
            /*width:960px;*/
            /*margin:50px auto;*/
            /*font-family:raleway*/
        /*}*/
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
            $(document).on("change blur", '.userPassword', function () {
                $("#errPassword").text('');
                $('.errormsg').hide();
                $('#errpassword').hide();

            });

            $(document).on("change blur", '#username', function () {
                $('#username').val($('#username').val().toLowerCase())

            });

        //REDIRECT LOGIN FORM
$(document).on('click','#reset_btn',function(){
    $("#errPassword").text('');

    $('#errpassword').hide();

            if ( $("#username").val() != ''){

                var xmlhttp=new XMLHttpRequest();
               var user_name=$("#username").val();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {

                        var value_array=JSON.parse(xmlhttp.responseText);
                       var  errmsg=value_array[1];
                        var no_user=value_array[0];

                        if(no_user!=0){
                            var url=document.location.href='reset.php?username='+$("#username").val();

                        }
                        else{
                             var msg=errmsg[0].replace('TO TERMINATE',"");
                            show_msgbox("LOGIN",msg,"error",false);

                        }

                    }
                }
                var option="checkusername";
                xmlhttp.open("GET","resetpwd.php?option="+option+"&username="+user_name);
                xmlhttp.send();

                }

            else
                $("#errPassword").text('ENTER VALID USERNAME');
        });
        });
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
            <input id="username" name="username" class="userPassword form-control" placeholder="Username" type="text">
            <div class="errormsg" id="errorName"><?php echo $errorName; ?></div>
            <br>
            <label>PASSWORD<em>*</em></label>
            <input id="password" class="userPassword form-control" name="password" placeholder="Password" type="password">
            <div class="errormsg" id="errorPassword"><?php echo $errorPassword; ?></div>
            <div class="errormsg" id='errpassword'><?php echo $error_message ?></div>
            <br>
            <input name="submit" type="submit" value=" LOGIN " class="btn btn-info" id="login_create">
            <input name="reset" type="button" value="RESET PASSWORD" class="btn btn-info" id="reset_btn">
            <div class="errormsg" id='errPassword'><?php echo $error ?></div>

        </form>
    </div>
</div>
    </div>
</body></html>