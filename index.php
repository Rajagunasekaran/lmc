<?php
include('LOGIN/login.php'); // Includes Login Script
include "HEADER.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>LIH MING CONSTRUCTION PTE LTD</title>
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
        #lg-form div:nth-child(3){
            text-align:left;
        }
    </style>
    <script type="text/javascript">
    $(document).ready(function () {
        $("#username").val('');
        $("#password").val('');
        $(document).on("change blur", '.userPassword', function () {
            $("#errPassword").text('');
        });
        $(document).on("change blur", '#username', function () {
            $('#username').val($('#username').val().toLowerCase());
            $("#errPassword").text('');
        });
    //REDIRECT LOGIN FORM
        $(document).on('click','#reset_btn',function(){
        $("#errPassword").text('');
            if ( $("#username").val() != ''){
                var xmlhttp=new XMLHttpRequest();
                var user_name=$("#username").val();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var value_array=JSON.parse(xmlhttp.responseText);
                        var  errmsg=value_array[1];
                        var no_user=value_array[0];
                        if(no_user!=0){
                            var url=document.location.href='LOGIN/reset.php?username='+$("#username").val();
                        }
                        else{
                             var msg=errmsg[0].replace('TO TERMINATE',"");
                            show_msgbox("LOGIN",msg,"error",false);
                        }
                    }
                }
                var option="checkusername";
                xmlhttp.open("GET","LOGIN/resetpwd.php?option="+option+"&username="+user_name);
                xmlhttp.send();
            }
            else
                $("#errPassword").text('Enter Valid Username');
        });
        $(document).on('change blur','#lg-form',function(){
            if($('#username').val()!='' && $('#password').val()!=''){
                $('#login_create').removeAttr('disabled');
            }
            else if($('#username').val()!=''){
                $('#login_create').attr('disabled','disabled');
                $('#reset_btn').removeAttr('disabled');
            }
            else {
                $('#login_create').attr('disabled','disabled');
                $('#reset_btn').attr('disabled','disabled');
            }
        });
    });
    </script>
</head>
<body style="padding-top:0px;">
<div class="lg-container">
<div id="main">
    <h1 align="center"><img src="image/LOGO.png" align="middle"/></h1>
    <div id="login" >
        <h2>LOGIN</h2>
        <form action="" method="POST" id="lg-form" name="lg-form">
            <div class="form-group">
                <label>USER NAME <em>*</em></label>
                <input id="username" name="username" class="userPassword form-control" placeholder="Username" type="text">
            </div>
            <div class="form-group">
                <label>PASSWORD <em>*</em></label>
                <input id="password" class="userPassword form-control" name="password" placeholder="Password" type="password">
            </div>
            <div class="errormsg" id='errPassword'><?php echo $error ?></div>
            <div class="form-group">
                <input name="submit" type="submit" value="LOGIN" class="btn btn-info" id="login_create" disabled>
                <input name="reset" type="button" value="RESET PASSWORD" class="btn btn-info" id="reset_btn" disabled>
            </div>
        </form>
    </div>
</div>
</div>
</body></html>