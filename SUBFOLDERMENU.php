<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************MENU*********************************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:21/01/2015 ED:21/01/2015
//*********************************************************************************************************//-->
<?php
include("LMC_LIB/GET_USERSTAMP.php");
include("SUBFOLDERHEADER.php");
include('LMC_LIB/SESSION.php');
$Userstamp=json_encode($UserStamp);
?>
<html>
<head>
</head>
</html>
<script>
    var ErrorControl ={MsgBox:'false'};
    var MenuPage=1;
    var SubPage=2;
    var address='';
    function CheckPageStatus(){
        if(MenuPage!=1 && SubPage!=1)
            $(".preloader").hide();
    }
    function updateClock ( )
    {
        var currentTime = new Date ( );
        var currentHours = currentTime.getHours ( );
        var currentMinutes = currentTime.getMinutes ( );
        var currentSeconds = currentTime.getSeconds ( );

        // Pad the minutes and seconds with leading zeros, if required
        currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
        currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

        // Choose either "AM" or "PM" as appropriate
        var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

        // Convert the hours component to 12-hour format if needed
        currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

        // Convert an hours component of "0" to "12"
        currentHours = ( currentHours == 0 ) ? 12 : currentHours;

        // Compose the string for display
        var currentTimeString = currentTime+":"+currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
        $("#clock").html(currentTime);
    }
    $(document).ready(function(){
        $('.preloaderimg').attr('src','../../CSS/images/preloader.gif');
        $('#checkin').attr("disabled","disabled");

        <?php echo  "var Userstamp = ". $Userstamp.PHP_EOL;?>
        setInterval('updateClock()', 1000);
        var Page_url;
        $(document).on("click",'.btnclass', function (){
            Page_url =$(this).attr('page');
            var attr_id=$(this).attr("id");
            if(attr_id==undefined){
                attr_id='';
            }
            if(attr_id==$(this).text())
            {
                show_msgbox("MENU CONFIRMATION","Do You Want to Open "+attr_id+" ?","success",true);
            }
            else{
                show_msgbox("MENU CONFIRMATION","Do You Want to Open "+attr_id+" "+$(this).text()+" ?","success",true);
            }
            return false;
        });
        function init () {
//        document.getElementById('menu_frame').onload = function () {
//            $(".preloader").hide();
//        }
        }
        var all_menu_array=[];
        var checkintime;
        var checkouttime;
        var checkinerrormsg=[];
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var value_array=JSON.parse(xmlhttp.responseText);
                all_menu_array= value_array;
                checkinerrormsg=value_array[1];
                if(all_menu_array[0]!=''){
                    $('#menu_nav').show();
                    $('#RPT').show();
                    $('#AE').show();
                    ACRMENU_getallmenu_result(all_menu_array)
                }
                else{
                    var error_msg= checkinerrormsg[0];
                    error_msg=(error_msg).toString().replace('[LOGIN ID]',Userstamp);
                    $('#ACRMENU_lbl_errormsg').text(error_msg);
                    $('#ACRMENU_lbl_errormsg').show();
                    $(".preloader").hide();
                    $('#menu_nav').hide();
                    $('#RPT').hide();
                    $('#AE').hide();
                }
            }
        }
        var option="MENU";
        xmlhttp.open("POST","../../DB_MENU.php?option="+option,true);
        xmlhttp.send();
        $(document).on('click','.menuconfirm',function(){
            $(".preloader").show();
            if(Page_url){
                $('#RPT').hide();
                $('#AE').hide();
//            $('#menu_frame').replaceWith( '<div id="menu_frame" name="iframe_a" ></div>');
//            $('#menu_frame').load(Page_url);
                window.location.href="../../"+Page_url;
                init();
            }
        });
        $("#cssmenu").hide()
//FUNCTION TO SET ALL MENUS
        function ACRMENU_getallmenu_result(all_menu_array)
        {

            var ACRMENU_mainmenu=all_menu_array[0];//['ACCESS RIGHTS','DAILY REPORTS','PROJECT','REPORT']//main menu
            var ARCMENU_first_submenu=all_menu_array[1];
            //[['ACCESS RIGHTS-SEARCH/UPDATE','TERMINATE-SEARCH/UPDATE','USER SEARCH DETAILS'],['ADMIN ','USER '],['PROJECT ENTRY','PROJECT SEARCH/UPDATE'],['ATTENDANCE','REVENUE']]//submenu
            var ARCMENU_second_submenu=[];
            ARCMENU_second_submenu=all_menu_array[2]//[[], [], [], ['REPORT ENTRY', 'SEARCH/UPDATE/DELETE','WEEKLY REPORT ENTRY','WEEKLY SEARCH/UPDATE'], ['REPORT ENTRY', 'SEARCH/UPDATE'],[],[],[],[]];
            var count=0;
            var mainmenuItem="";
            var submenuItem="";
            var filelist=all_menu_array[4];
            var sub_submenuItem="";
            var script_flag=all_menu_array[3];
            var rolelogin=all_menu_array[5];
            for(var i=0;i<ACRMENU_mainmenu.length;i++)//add main menu
            {
                var main='mainmenu'+i
                var submen='submenu'+i;
                var filename=filelist[count]+'.php';
                if(rolelogin=='USER') {
                    if (ARCMENU_first_submenu[i].length == 1) {
                        mainmenuItem = '<li><a class="btnclass" page="' + filename + '" href="#"  id="' + ACRMENU_mainmenu[i] + '" >' + ACRMENU_mainmenu[i] + '</a></li>'

                    }
                    else {
                        mainmenuItem = '<li class="dropdown"><a tabindex="0" href="#" data-toggle="dropdown">' + ACRMENU_mainmenu[i] + '<b class="caret"></b></a><ul class="dropdown-menu fa-ul ' + submen + '">'
                    }
                }
                else
                {
                    if (ARCMENU_first_submenu[i].length == 0) {
                        mainmenuItem = '<li><a class="btnclass" page="' + filename + '" href="#"  id="' + ACRMENU_mainmenu[i] + '" >' + ACRMENU_mainmenu[i] + '</a></li>'

                    }
                    else {
                        mainmenuItem = '<li class="dropdown"><a tabindex="0" href="#" data-toggle="dropdown">' + ACRMENU_mainmenu[i] + '<b class="caret"></b></a><ul class="dropdown-menu fa-ul ' + submen + '">'
                    }
                }
                $("#ACRMENU_ulclass_mainmenu").append(mainmenuItem);
                for(var j=0;j<ARCMENU_first_submenu.length;j++)
                {
                    if(i==j)
                    {
                        for(var k=0;k<ARCMENU_first_submenu[j].length;k++)//add submenu1
                        {
                            var sub_submenu='sub_submenu'+j+k;
                            if(ARCMENU_second_submenu[count].length==0)
                            {
                                if(script_flag[count]!='X'){
                                    var file_name=filelist[count]+'.php';

                                }
                                else{

                                    var file_name='ERROR_PAGE.php'
                                }
                                submenuItem='<li class=""><a class="btnclass" page="'+file_name+'" href="#"   id="'+ACRMENU_mainmenu[i]+'" >'+ARCMENU_first_submenu[j][k]+'</a></li></ul>'
                            }
                            else
                            {
                                submenuItem='<li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">'+ARCMENU_first_submenu[j][k]+'</a><ul class="dropdown-menu '+sub_submenu+'" role="menu">'
                            }
                            $("."+submen).append(submenuItem);
                            for(var m=0;m<ARCMENU_second_submenu[count].length;m++)//add submenu2
                            {
                                if(script_flag[count][m]!='X'){
//                                    var file_name=filelist[count][m]
                                    var file_name=filelist[count][m]+'.php';

                                }
                                else{
                                    var file_name='ERROR_PAGE.php'
                                }
//                            alert(ARCMENU_second_submenu[count].length+"ARCMENU_second_submenu[count].length"+file_name)

                                sub_submenuItem='<li class=""><a class="btnclass" page="'+file_name+'" href="#"   id="'+ARCMENU_first_submenu[j][k]+'" >'+ARCMENU_second_submenu[count][m]+'</a></li>'
                                $("."+sub_submenu).append(sub_submenuItem);
                            }
                            count++;
                            $("#ACRMENU_ulclass_mainmenu").append('</ul></li>');
                        }
                    }
                }
                $("#ACRMENU_ulclass_mainmenu").append('</li>');
            }

//            var dashbord='<ul class="nav navbar-nav navbar-right "><li ><a href="MENU.php">DASH BOARD</a></li></ul>';
//            $("#menu").append(dashbord);
            $(".preloader").hide();
            MenuPage=0;
            CheckPageStatus();
        }
    });
</script>
<title>LIH MING CONSTRUCTION PTE LTD</title>
<meta charset="utf-8">
<link rel="shortcut icon" type="image/ico" href="../../image/favicon.ico">
<meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
</head>
<body >

<div class="container-fluid">
    <div class="wrapper" >
        <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
        <!--        <table>-->
        <!--            <tr>-->
        <!--            <td style="width:1300px";><h1>LIH MING CONSTRUCTION PTE LTD</h1></td>-->
        <img src="../../image/LOGO.png" align="middle"/>
        <!--                <td style="width:1300px";><img src="image/LOGO.png" align="middle"/></td>-->
        <!--            </tr>-->
        <!--        </table>-->

        <table>
            <tr>
                <td style="width:1000px";><b><h4><span style="font-family:Helvetica Neue" id="clock" ></span></h4></b></td><td style="width:100px" style="font-family:Helvetica Neue;font-size: 2em;"><b><i class="glyphicon glyphicon-user "></i>  <?php echo $UserStamp ?></b></td><td><b><a href="../../LOGIN/logout.php">Logout <i class="glyphicon glyphicon-log-out"></i></b></a></td>
            </tr>
            <tr>
                <td><b><label id="clockmsg" name="clockmsg" ></label></b> </td>
            </tr>
        </table>
        <nav class="navbar navbar-default" id="menu_nav">
            <div class="navbar-header">
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

            </div>
            <div class="collapse navbar-collapse" id="menu" >
                <ul class="nav navbar-nav" id="ACRMENU_ulclass_mainmenu">
<!--                    <li ><a href="MENU.php">DASH BOARD</a></li>-->
                </ul>
            </div>
        </nav>


<!--        <div  id="buttondiv"  >-->
<!--            <button type="button" class="btn  btn-info" name="AE" id="AE"  value="ACCIDENT ENTRY"><a page="FORM_ACCIDENT_ENTRY.php" href="#" class="btnclass" style="color:white">ACCIDENT ENTRY</a></button>-->
<!--            <button id="RPT" class="btn btn-info" name="RPT" value="REPORT PERMISSION ENTRY"  ><a page="FORM_PERMITS_ENTRY.php" href="#" class="btnclass" style="color:white">REPORT SUBMISSION ENTRY</a></button>-->
<!---->
<!--        </div>-->
        <br><label id="ACRMENU_lbl_errormsg" class="errormsg" hidden ></label>
        <div id="menu_frame" name="iframe_a" ></div>
    </div>
</div>
</body>
</html>