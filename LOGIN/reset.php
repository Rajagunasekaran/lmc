<!DOCTYPE html>
<?php
include '../FOLDERHEADER.php';
$username=$_GET['username'];
?>
<html>
<head>
    <title>LIH MING CONSTRUCTION PTE LTD</title>
    <link href="../CSS/StyleSheet.css" rel="stylesheet" type="text/css">
    <style>
        h2 {
            background-color:#498af3;
            text-align:center;
            color: #fff;
            border-radius:10px 10px 0 0;
            margin:-10px -45px;
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
            function capitalize(str){
                return str[0].toUpperCase() + str.slice(1).toLowerCase();
            }
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
            xmlhttp.open("GET","../LMC_LIB/COMMON.php?option="+option);
            xmlhttp.send();
            $('#password').change(function(){
                var URSRC_pass_length=($('#password').val()).length;
                if(URSRC_pass_length<8){
                    var passerr=capitalize(reset_errormsg[2]);
                    $('#errPassword').text(passerr).show();
                    $('#resetBtn').attr("disabled","disabled");
                }
                else{
                    $('#errPassword').hide();
                    $('#resetBtn').removeAttr('disabled');
                }
            });
            //CHANGE EVENT FOR CONFIRM PASSWORD
            $(document).on("change",'#confirm-password,#password', function (){
                var password=$('#password').val();
                var confirmpassword=$('#confirm-password').val();
                if(confirmpassword!=''){
                    if(password!=confirmpassword)
                    {
                        var confpasserr=capitalize(reset_errormsg[1]);
                        $('#errconPassword').text(confpasserr).show();
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
                        }
                    });
                }
                $(document).on('click','.msgconfirm',function(){
                    window.location.href='../index.php';
                });
            });
            $(document).on('click','#back_btn',function(){
                window.location.href='../index.php';
            });
            $('.preloaderimg').attr('src','../CSS/images/preloader.gif');
        });
    </script>
</head>
<body style="padding-top:0px;">
<div class="lg-container">
<div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div id="main">
        <h1 align="center"><img src="../image/LOGO.png" align="middle"/></h1>
        <form action=""  method="post" class="reset-form" id="lg-form" name="lg-form">
            <div id="login">
                <h2>RESET FORM</h2>
                <div class="form-group">
                    <label>USER NAME</label>
                    <input id="username" name="username"  value="<?php  echo $username?>" type="text" placeholder="Username" class="form-control" disabled>
                </div>
                <div class="form-group">
                    <label>PASSWORD <em>*</em></label>
                    <input id="password" name="password"  type="password" placeholder="Password" class="form-control">
                    <div style="padding-top: 10px" class="errormsg titlecase" id="errPassword" hidden></div>
                </div>
                <div class="form-group">
                    <label>CONFIRM PASSWORD <em>*</em></label>
                    <input id="confirm-password" name="confirm-password" type="password" placeholder="Confirm Password" class="form-control">
                    <div style="padding-top: 10px" class="errormsg titlecase" id="errconPassword" hidden></div>
                </div>
                <div class="form-group">
                    <input name="resetBtn" type="button" value="RESET PASSWORD" id="resetBtn" class="btn btn-info">
                    <input name="back" type="button" value="BACK" class="btn btn-info" id="back_btn">
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>