<!DOCTYPE html>
<?php
include 'HEADER.php';
$username=$_GET['username'];
?>
<html>
    <head>
        <title>RESET PASSWORD</title>
        <link href="CSS/StyleSheet.css" rel="stylesheet" type="text/css">
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
            /*#login {*/
                /*width:400px;*/
                /*float:center;*/
                /*border-radius:10px;*/
                /*font-family:raleway;*/
                /*border:2px solid #ccc;*/
                /*padding:10px 40px 25px;*/
                /*margin-top:70px;*/
                /*margin-left:290px;*/
            /*}*/
            /*input[type=text],input[type=password] {*/
                /*width:99.5%;*/
                /*padding:10px;*/
                /*margin-top:8px;*/
                /*border:1px solid #ccc;*/
                /*padding-left:5px;*/
                /*font-size:16px;*/
                /*font-family:raleway*/
            /*}*/
            /*input[type=submit] {
            width:100%;
            background-color:#FFBC00;
            color:#fff;
            border:2px solid #FFCB00;
            padding:10px;
            font-size:20px;
            cursor:pointer;
            border-radius:5px;
            margin-bottom:15px
            }*/
            /*#profile {*/
                /*padding:50px;*/
                /*border:1px dashed grey;*/
                /*font-size:20px;*/
                /*background-color:#DCE6F7*/
            /*}*/
            /*#logout {*/
                /*float:right;*/
                /*padding:5px;*/
                /*border:dashed 1px gray*/
            /*}*/
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
                $("#password,#confirm-password").val('');
                 $("#errPassword").text('');
var reset_errormsg=[];
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var value_array=JSON.parse(xmlhttp.responseText);
                        reset_errormsg=value_array;
                    }
                }
                var option="RESET_FORM";
                xmlhttp.open("GET","COMMON.php?option="+option);
                xmlhttp.send();
                $('#password').change(function(){
                    var URSRC_pass_length=($('#password').val()).length;
                    if(URSRC_pass_length<8){
                        $('#errPassword').text(reset_errormsg[2]).show();
                        $('#resetBtn').attr("disabled","disabled");

                    }
                    else{
                        $('#errPassword').hide();
                        $('#resetBtn').removeAttr('disabled')


                    }
                });
                //CHANGE EVENT FOR CONFIRM PASSWORD
                $(document).on("change",'#confirm-password,#password', function (){
                    var password=$('#password').val();
                    var confirmpassword=$('#confirm-password').val();
                    if(confirmpassword!=''){
                        if(password!=confirmpassword)
                        {
                            $('#errconPassword').text(reset_errormsg[1]).show();
                            $('#resetBtn').attr("disabled","disabled");

                        }
                        else
                        {
                            $('#errconPassword').hide();
                            $('#resetBtn').removeAttr('disabled')
                        }
                    }
                });

                $(document).on("click", '#resetBtn', function () {

                    var postData = {"choice": "resetPassword", "password": $("#confirm-password").val(), "username": $("#username").val()};
                    if ($("#confirm-password").val() != '' && $("#username").val() != '' && $("#password").val() == $("#confirm-password").val()) {
                        $('.preloader').show();
                        $.ajax({
                            type: 'POST',
                            data: postData,
                            url: 'resetpwd.php',
                            success: function (data) {
                                $('.preloader').hide();
                             if(data==1){
                                show_msgbox("RESET FORM",reset_errormsg[0],"success",false);
                                 $("#password").val('');
                                 $("#confirm-password").val('');


                            }
//
                            }
                        });
                    }
                    $(document).on('click','.msgconfirm',function(){

                        window.location.href='index.php';

                    });

                });
            });
        </script>
    </head>
    <body>
    <div class="lg-container">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
        <div id="main">
            <h1 align="center"><img src="image/LOGO.png" align="middle"/></h1>
            <form action=""  method="post" class="reset-form" id="lg-form" name="lg-form">
                <div id="login">
                        <h2>RESET FORM</h2>
                    <label>USER NAME</label>
                    <input id="username" name="username"  value="<?php  echo $username?>" type="text" placeholder="USERNAME" class="form-control" disabled>
                   <br><br> <label>PASSWORD<em>*</em></label>
                    <input id="password" name="password"  type="password" placeholder="PASSWORD" class="form-control">
                    <div class="errormsg" id="errPassword"></div>
                    <br><br><label>CONFIRM PASSWORD<em>*</em></label>
                    <input id="confirm-password" name="confirm-password"  type="password" placeholder="CONFIRM PASSWORD" class="form-control">
                    <div class="errormsg" id="errconPassword"></div>
                    <br><br>
                    <div class="footer"><input name="resetBtn" type="button" value=" RESET PASSWORD" id="resetBtn" class="btn btn-info"></div>
                </div>
            </form>
         </div>
    </div>
    </body>
</html>