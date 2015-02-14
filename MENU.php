<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************MENU*********************************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:21/01/2015 ED:21/01/2015
//*********************************************************************************************************//-->
<?php
include "GET_USERSTAMP.php";
include "HEADER.php";
$Userstamp=json_encode($UserStamp);
?>
<script>
var ErrorControl ={MsgBox:'false'}
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
    $(".preloader").show();
    $('#checkin').attr("disabled","disabled");
//    function displayLocation(latitude,longitude){
//        var request = new XMLHttpRequest();
//        var method = 'GET';
//        var url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='+latitude+','+longitude+'&sensor=true';
//        var async = true;
//        request.open(method, url, async);
//        request.onreadystatechange = function(){
//            if(request.readyState == 4 && request.status == 200){
//                var data = JSON.parse(request.responseText);
//                address = data.results[0];
//                $('#location').text(address.formatted_address);
//                $('#checkin').removeAttr("disabled");
//                $('#errmsg').hide();
//            }
//        };
//        request.send();
//    };
//    var successCallback = function(position){
//        var x = position.coords.latitude;
//        var y = position.coords.longitude;
//        displayLocation(x,y);
//    };
//    var errorCallback = function(error){
//        var errorMessage = 'Unknown error';
//        switch(error.code) {
//            case 1:
//                errorMessage = 'Permission denied';
//                break;
//            case 2:
//                errorMessage = 'Position unavailable';
//                break;
//            case 3:
//                errorMessage = 'Timeout';
//                break;
//        }
//        document.write(errorMessage);
//    };
//    var options = {
//        enableHighAccuracy: true,
//        timeout: 60000,
//        maximumAge: 0
//    };
//    navigator.geolocation.getCurrentPosition(successCallback,errorCallback,options);
    <?php echo  "var Userstamp = ". $Userstamp.PHP_EOL;?>
    setInterval('updateClock()', 1000);
    var Page_url;
    $(document).on("click",'.btnclass', function (){
        Page_url =$(this).data('pageurl');
        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"MENU CONFIRMATION",msgcontent:"Do You Want to Open "+$(this).attr("id")+" "+$(this).text()+" ?",confirmation:true,position:{top:150,left:480}}});
        return false;
    });
    function init () {
        document.getElementById('menu_frame').onload = function () {
            $(".preloader").hide();
        }
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
//            if($('#location').text()==""){
//                $('#errmsg').text(checkinerrormsg[2]).show();
//            }
//            else{
//                $('#errmsg').hide();
//            }
            if(all_menu_array[0]!=''){
                ACRMENU_getallmenu_result(all_menu_array)
            }
            else{
                var error_msg= checkinerrormsg[0];
                error_msg=(error_msg).toString().replace('[LOGIN ID]',Userstamp);
                $('#ACRMENU_lbl_errormsg').text(error_msg);
                $('#ACRMENU_lbl_errormsg').show();
                $(".preloader").hide();
                $('#buttondiv').css('display','none');
                $('#liveclock').hide();
                $('#checkin').hide();
                $('#clockmsg').hide();
            }
        }
    }
    var option="MENU";
    xmlhttp.open("POST","DB_MENU.do?option="+option,true);
    xmlhttp.send();
    $(document).on('click','.messageconfirm',function(){
        $(".preloader").show();
        if(Page_url){
            $('#menu_frame').attr('src', Page_url)
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
        for(var i=0;i<ACRMENU_mainmenu.length;i++)//add main menu
        {
            var main='mainmenu'+i
            var submen='submenu'+i;
            var filename=filelist[count]+'.do';
            if(ARCMENU_first_submenu.length==0)
            {
                mainmenuItem='<li class="active"><a data-pageurl="'+filename+' href="'+filename+'" id="'+ACRMENU_mainmenu[i]+'" target="iframe_a"  >'+ACRMENU_mainmenu[i]+'</a></li>'
            }
            else
            {
                mainmenuItem='<li class="has-sub"><a href="#" >'+ACRMENU_mainmenu[i]+'</a><ul class='+submen+'>'
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
                                var file_name=filelist[count]+'.do';
                            }
                            else{

                                var file_name='ERROR_PAGE.do'
                            }
                            submenuItem='<li class="active"><a data-pageurl="'+file_name+'" href="'+file_name+'"   id="'+ACRMENU_mainmenu[i]+'" class=" btnclass"   >'+ARCMENU_first_submenu[j][k]+'</a></li></ul>'
                        }
                        else
                        {
                            submenuItem='<li class="has-sub"><a href="#" >'+ARCMENU_first_submenu[j][k]+'</a><ul class='+sub_submenu+'>'
                        }
                        $("."+submen).append(submenuItem);
                        for(var m=0;m<ARCMENU_second_submenu[count].length;m++)//add submenu2
                        {
                            if(script_flag[count][m]!='X'){
                                var file_name=filelist[count][m]+'.do';

                            }
                            else{
                                var file_name='ERROR_PAGE.do'
                            }
                            sub_submenuItem='<li class="active"><a data-pageurl="'+file_name+'" href="'+file_name+'"   id="'+ARCMENU_first_submenu[j][k]+'" class=" btnclass"     >'+ARCMENU_second_submenu[count][m]+'</a></li>'
                            $("."+sub_submenu).append(sub_submenuItem);
                        }
                        count++;
                        $("#ACRMENU_ulclass_mainmenu").append('</ul></li>');
                    }
                }
            }
            $("#ACRMENU_ulclass_mainmenu").append('</li>');
        }
        $("#cssmenu").show()
        $(".preloader").hide();
        MenuPage=0;
        CheckPageStatus();
    }
});
</script>
<title>SSOMENS TIME SHEET</title>
</head>
<body >
<div class="wrapper" >
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
<!--       <table>-->
<!--          <tr>-->
<!--            <td style="width:1300px";><img src="image/SSOMENS_TIME_SHEET.jpg" align="middle"/></td>-->
<!--         </tr>-->
<!--       </table>-->

      <table>
        <tr>
            <td style="width:1000px";><b><h4><span id="clock" ></span></h4></b></td><td><b><?php echo $UserStamp ?></b></td>
        </tr>
        <tr>
            <td><b><label id="clockmsg" name="clockmsg" ></label></b> </td>
        </tr>
      </table>
       <div id='cssmenu' width="1500">
           <ul class="nav" id="ACRMENU_ulclass_mainmenu">
          </ul>
       </div>
    <br><label id="ACRMENU_lbl_errormsg" class="errormsg" hidden ></label>
    <iframe id="menu_frame" name="iframe_a" width="100%" height="100%"  frameborder="0"></iframe>
</div>
</body>
</html>