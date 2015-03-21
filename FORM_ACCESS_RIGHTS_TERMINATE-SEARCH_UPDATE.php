<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************ACCESS_RIGHTS_TERMINATE_SEARCH_UPDATE*********************************************//
//VER 0.01-INITIAL VERSION, SD:03/03/2015 ED:03/03/2015,TRACKER NO:79 DESC:updated to update login id in search/update and sending email while create login id
//*********************************************************************************************************//-->
<?php
include "NEW_MENU.php";
?>
<!DOCTYPE html>
<html lang="en">
<body>
<!--SCRIPT TAG START-->
<script>
var upload_count=0;
//START READY FUNCTION
$(document).ready(function(){
    $('#URSRC_lb_selectteam').hide();
    $('#URSRC_btn_add').hide();
    $('#URT_SRC_tb_pword').hide();
    $('#URT_SRC_tb_confirmpword').hide();
    $('#URT_SRC_lb_nloginrejoin').hide();
    $('#URT_SRC_tb_uname').hide();
    $('#URT_SRC_tb_datepickerrejoin').hide();
    $('#URT_SRC_ta_reasonupdate').hide();
    $('#URSRC_lb_selectemptype').hide();
    $('#URSRC_lbl_selectteam').hide();
    $('#URSRC_lb_selectteam').hide();
    $('#URSRC_btn_add').hide();
    $('#URT_SRC_lb_loginrejoin').hide();
    $('#URT_SRC_lb_loginupdate').hide();
    $('#URT_SRC_tb_datepickerupdate').hide();
    $('#URT_SRC_ta_reasontermination').hide();
    $('#URT_SRC_tb_datepickertermination').hide();
    $('#URT_SRC_lb_loginterminate').hide();
    $('#URSRC_lb_selectteam').hide();
    $('#URSRC_lbl_team_err').hide();
    $('.preloader').show();
    $('textarea').autogrow({onInitialize: true});
    //reomve file upload row
    $(document).on('click', 'button.removebutton', function () {
        $(this).closest('div').remove();
        var rowCount = $('#filetableuploads > div').length;
        if(rowCount!=0)
        {
            $('#attachafile').text('Attach another file');
        }
        else
        {
            $('#attachafile').text('Attach a file');
            $( "#filetableuploads" ).empty();
        }
        return false;
    });
    //file extension validation
    $(document).on("change",'.fileextensionchk', function (){
        for(var i=0;i<25;i++)
        {
            var data= $('#upload_filename'+i).val();
            var datasplit=data.split('.');
            var ext=datasplit[1].toUpperCase();
            if(ext=='PDF'|| ext=='JPG'|| ext=='PNG' || ext=='JPEG' || data==undefined || data=="")
            {}
            else
            {
                reset_field($('#upload_filename'+i));
                show_msgbox("ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",js_errormsg_array[12],"error",false)
            }
        }
    });
    //file upload reset
    function reset_field(e) {
        e.wrap('<form>').parent('form').trigger('reset');
        e.unwrap();
    }
    //add file upload row
    $(document).on("click",'#attachprompt', function (){
        var tablerowCount = $('#filetableuploads > div').length;
        upload_count++;
        var uploadfileid="upload_filename"+tablerowCount;
        var appendfile='<div class="col-sm-offset-2 col-sm-10"><label class="inline"><input type="file" style="max-width:250px " class="fileextensionchk form-control" name='+uploadfileid+' id='+uploadfileid+'></label><label class="inline" ><button  class="removebutton" value="-" title="Remove this row" style="background-color:red;color:white;font-size:10;font-weight: bold;">-</button></label></div>';
        $('#filetableuploads').append(appendfile);
        var rowCount =$("#filetableuploads > div").length// $('#filetableuploads tr').length;//
        if(rowCount!=0)
        {
            $('#attachafile').text('Attach another file');
        }
        else
        {
            $('#attachafile').text('Attach a file');
        }
    });
    //GLOBAL DECLARATION
    var URT_SRC_terminate_array=[];
    var js_errormsg_array=[];
    var URSRC_team_array=[];
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $('.preloader').hide();
                $('#RPT').hide();
                $('#AE').hide();
            var value_array=JSON.parse(xmlhttp.responseText);
            URT_SRC_terminate_array=value_array[1];
            js_errormsg_array=value_array[0];
            var URSRC_emptype_array=value_array[2];
            URSRC_team_array=value_array[3];
            var URT_SRC_radio_role='';
            for (var i=0;i<URT_SRC_terminate_array.length;i++) {
                var id="URT_SRC_tble_table"+i;
                var id1="URT_SRC_terminate_array"+i;
                var value=URT_SRC_terminate_array[i][1].replace(" ","_")
                if(i==0)
                    var temp='SELECT ROLE ACCESS<em>*</em>'
                else
                    var temp=''
                URT_SRC_radio_role+='<label class="srctitle  col-sm-2" style="white-space: nowrap!important;">'+temp+'</label><div class="col-sm-offset-2 col-sm-10"><label class="col-sm-2" style="white-space: nowrap!important;"><input type="radio" name="URT_SRC_radio_nrole" id='+id1+' value='+value+' class="URT_SRC_radio_clsrole"  />' + URT_SRC_terminate_array[i][1] + '</label></div>';
            }
            $('#URT_SRC_tble_roles').html(URT_SRC_radio_role);
            var emp_type='<option value="SELECT">SELECT</option>';

            for(var k=0;k<URSRC_emptype_array.length;k++){
                emp_type += '<option value="' + URSRC_emptype_array[k] + '">' + URSRC_emptype_array[k] + '</option>';
            }
            $('#URSRC_lb_selectemptype').html(emp_type);
//TEAM ARRAY
            if(URSRC_team_array.length!=0){
                var team='<option value="SELECT">SELECT</option>';
                for(var k=0;k<URSRC_team_array.length;k++){
                    team += '<option value="' + URSRC_team_array[k] + '">' + URSRC_team_array[k] + '</option>';
                }
                $('#URSRC_lb_selectteam').html(team);
            }
            else{
                $('#URSRC_lb_selectteam').replaceWith('<input type="text"  name="URSRC_lb_selectteam" id="URSRC_lb_selectteam" class="login_submitvalidate form-control upper check_team" /><label id="URSRC_lbl_team_err" class="errormsg"></label>');
                $('#URSRC_btn_add').hide();
            }
        }
    }
    var choice='USER_RIGHTS_TERMINATE';
    xmlhttp.open("POST","COMMON.php?option="+choice,true);
    xmlhttp.send();
    //DO VALIDATION PART
    //emp
    $(".mobileno").doValidation({rule:'numbersonly',prop:{realpart:8,leadzero:true}});
    $(".accntno").doValidation({rule:'numbersonly',prop:{leadzero:true,autosize:true}});
    $(".alphanumeric").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:false,autosize:true}});
    $(".alphanumericuppercse").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:true,autosize:true}});
    $('#URT_SRC_tb_uname').doValidation({rule:'alphanumeric',prop:{uppercase:false,autosize:true}});
    $('.autosize').doValidation({rule:'general',prop:{autosize:true}});
    //END VALIDATION PART
    //CLICK FUNCTION FOR TERMINATION BTN
    $(document).on('click','#URT_SRC_btn_termination',function(){
        $('.preloader').show();
        var URT_SRC_empname=$("#URT_SRC_lb_loginterminate option:selected").text();
        var URT_loginid=$('#URT_SRC_lb_loginrejoin').val();
        var loggin=$("#URT_SRC_lb_loginterminate").val();
        var formElement = document.getElementById("URT_SRC_form_terminatesearchupdate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var msg_alert=JSON.parse(xmlhttp.responseText);
                var success_flag=msg_alert[0];
                var ss_flag=msg_alert[1];
                var cal_flag=msg_alert[2];
                if((success_flag==1)&&(ss_flag==1)&&(cal_flag==1)){
                    var msg=js_errormsg_array[1].toString().replace("[LOGIN ID]",URT_SRC_empname);
                    show_msgbox("ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msg,"success",false)
                    $("#URT_SRC_lbl_datepickertermination").hide();
                    $("#URT_SRC_lb_loginterminate").hide();
                    $("#URT_SRC_lbl_loginterminate").hide();
                    $("#URT_SRC_tb_datepickertermination").hide();
                    $("#URT_SRC_lbl_reasontermination").hide();
                    $("#URT_SRC_ta_reasontermination").hide();
                    $("#URT_SRC_btn_termination").hide();
                    $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
                    $("#filetableuploads tr").remove();
                    $('#attachafile').text('Attach a file');
                }
                if(success_flag==0){
                    show_msgbox("ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",js_errormsg_array[7],"error",false)
                    $("#URT_SRC_lbl_datepickertermination").hide();
                    $("#URT_SRC_lb_loginterminate").hide();
                    $("#URT_SRC_lbl_loginterminate").hide();
                    $("#URT_SRC_tb_datepickertermination").hide();
                    $("#URT_SRC_lbl_reasontermination").hide();
                    $("#URT_SRC_ta_reasontermination").hide();
                    $("#URT_SRC_btn_termination").hide();
                    $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
                }
                if((success_flag==1)&&(ss_flag==0)){
                    var fileid=msg_alert[3];
                    var msg= js_errormsg_array[10].replace("[SSID]",fileid)
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:msg,position:{top:150,left:550}}});
                }
                if((success_flag==1)&&(ss_flag==1)&&(cal_flag==0)){
                    var msg= js_errormsg_array[9];
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:msg,position:{top:150,left:550}}});
                }
            }
        }
        var choice='TERMINATE';
        xmlhttp.open("POST","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.php?option="+choice+"&loggin="+loggin);
        xmlhttp.send(new FormData(formElement));
    });
    //SET DOB DATEPICKER
    var EMP_ENTRY_d = new Date();
    var EMP_ENTRY_year = EMP_ENTRY_d.getFullYear() - 18;
    EMP_ENTRY_d.setFullYear(EMP_ENTRY_year);
    //DATE PICKER
    $('#URSRC_tb_dob').datepicker(
        {
            dateFormat: 'dd-mm-yy',
            changeYear: true,
            changeMonth: true,
            yearRange: '1920:' + EMP_ENTRY_year + '',
            defaultDate: EMP_ENTRY_d
        });
    var pass_changedmonth=new Date(EMP_ENTRY_d.setFullYear(EMP_ENTRY_year));
    $('#URSRC_tb_dob').datepicker("option","maxDate",pass_changedmonth);
//END DATE PICKER FUNCTION
    //CLICK FUNCTION FOR REJOIN BTN
    $(document).on('click','#URT_SRC_btn_rejoin',function(){
        $('.preloader').show();
        //Removing fakepath in all files
        var filearray=[];
        for(var i=0;i<25;i++)
        {
            var data=$('#upload_filename'+i).val();
            if(data!='' && data!=undefined)
            {
                data=(data.toString()).replace("C:\\fakepath\\", "");
                filearray.push(data);
            }
        }
        var filenames='';
        for(var j=0;j<filearray.length;j++)
        {
            if(j==0){filenames=filearray[j];}
            else
            {filenames=filenames+','+filearray[j];}
        }
        //End Removing fakepath in all files
        var login_id=$("#URT_SRC_lb_loginrejoin").val();
        var URT_loginid_val=$("#URT_SRC_lb_loginrejoin option:selected").text();
        var formElement = document.getElementById("URT_SRC_form_terminatesearchupdate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var msg_alert=JSON.parse(xmlhttp.responseText);
                var success_flag=msg_alert[0];
                if(success_flag==1){
                    var loggin=$("#URT_SRC_lb_loginrejoin").val();
                    var msg=js_errormsg_array[2].toString().replace("[LOGIN ID]",URT_loginid_val);
                    show_msgbox("ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msg,"success",false)
                    $("#URT_SRC_lbl_datepickerrejoin").hide();
                    $("#URT_SRC_lbl_loginrejoin").show();
                    $("#URT_SRC_tble_roles").hide();
                    $('#URSRC_table_employeetbl').hide();
                    $("#URT_SRC_lb_loginupdate").hide();
                    $("#URT_SRC_tb_datepickerrejoin").hide();
                    $("#URT_SRC_btn_rejoin").hide();
                    $('#URT_SRC_lbl_loginupdate').hide();
                    $('#URT_SRC_lbl_datepickerupdate').hide();
                    $('#URT_SRC_tb_datepickerupdate').hide();
                    $('#URT_SRC_lbl_reasonupdate').hide();
                    $('#URT_SRC_ta_reasonupdate').hide();
                    $('#URT_SRC_btn_update').hide();
                    $('#URT_SRC_radio_selectrejoin').hide();
                    $('#URT_SRC_radio_selectsearchupdate').hide()
                    $('#URT_SRC_lb_loginrejoin').hide();
                    $('#URT_SRC_lbl_loginrejoin').hide();
                    $('#URT_SRC_lbl_selectsearchupdate').hide();
                    $('#URT_SRC_lbl_selectrejoin').hide();
                    $('#URT_SRC_lbl_selectoption').hide();
                    $("#URSRC_lbl_emptype").hide();
                    $('#URT_SRC_lbl_pword').val('').hide();
                    $('#URT_SRC_tb_pword').val('').hide();
                    $("#URT_SRC_lbl_uname").val('').hide();
                    $("#URT_SRC_tb_uname").val('').hide();
                    $("#URT_SRC_lbl_confirmpword").val('').hide();
                    $("#URT_SRC_tb_confirmpword").val('').hide();
                    $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
                    $('#URSRC_lbl_selectteam').hide();
                    $('#URSRC_lb_selectteam').hide();
                    $('#URSRC_btn_add').hide();
                    $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
                    $("input[name=URT_SRC_radio_nselectoption]:checked").attr('checked',false);
                    $("#filetableuploads tr").remove();
                    $('#attachafile').text('Attach a file');
                }
                else {
                    show_msgbox("ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",js_errormsg_array[7],"error",false)
                    $("#URT_SRC_lbl_datepickerrejoin").hide();
                    $("#URT_SRC_lbl_loginrejoin").show();
                    $("#URT_SRC_tble_roles").hide();
                    $('#URSRC_table_employeetbl').hide();
                    $("#URT_SRC_lb_loginupdate").hide();
                    $("#URT_SRC_tb_datepickerrejoin").hide();
                    $("#URT_SRC_btn_rejoin").hide();
                    $('#URT_SRC_lbl_loginupdate').hide();
                    $('#URT_SRC_lbl_datepickerupdate').hide();
                    $('#URT_SRC_tb_datepickerupdate').hide();
                    $('#URT_SRC_lbl_reasonupdate').hide();
                    $('#URT_SRC_ta_reasonupdate').hide();
                    $('#URT_SRC_btn_update').hide();
                    $('#URT_SRC_radio_selectrejoin').hide();
                    $('#URT_SRC_radio_selectsearchupdate').hide()
                    $('#URT_SRC_lb_loginrejoin').hide();
                    $('#URT_SRC_lbl_loginrejoin').hide();
                    $('#URT_SRC_lbl_selectsearchupdate').hide();
                    $('#URT_SRC_lbl_selectrejoin').hide();
                    $('#URT_SRC_lbl_selectoption').hide();
                    $("#URSRC_lbl_emptype").hide();
                    $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
                    $('#URSRC_lbl_selectteam').hide();
                    $('#URSRC_lb_selectteam').hide();
                    $('#URSRC_btn_add').hide();
                    $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
                    $("input[name=URT_SRC_radio_nselectoption]:checked").attr('checked',false);
                }
            }
        }
        var option='REJOIN';
        xmlhttp.open("POST","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.php?option="+option+"&login_id="+login_id+"&filearray="+filenames+"&upload_count="+upload_count,true);
        xmlhttp.send(new FormData(formElement));
    });
    $(document).on('click','#URT_SRC_btn_update',function(){
        $('.preloader').show();
        var URT_SRC_loggin=$("#URT_SRC_lb_loginupdate").val();
        var URT_SRC_empname_upd=$("#URT_SRC_lb_loginupdate option:selected").text();
        var formElement = document.getElementById("URT_SRC_form_terminatesearchupdate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var msg_alert=xmlhttp.responseText;
                if(msg_alert==1){
                    var loggin=$("#URT_SRC_lb_loginupdate").val();
                    var msg=js_errormsg_array[0].toString().replace("[LOGIN ID]",URT_SRC_empname_upd);
                    show_msgbox("ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msg,"success",false)
                }
                $('#URT_SRC_lbl_loginrejoin').hide();
                $('#URT_SRC_lb_loginrejoin').hide();
                $('#URT_SRC_lbl_loginterminate').hide();
                $('#URT_SRC_lb_loginterminate').hide();
                $("#URT_SRC_tble_roles").hide();
                $('#URSRC_table_employeetbl').hide();
                $("#URT_SRC_tb_datepickerrejoin").hide();
                $("#URT_SRC_lbl_datepickerrejoin").hide();
                $("#URT_SRC_btn_rejoin").hide();
                $("#URT_SRC_lbl_loginupdate").show();
                $('#URT_SRC_lbl_datepickerupdate').hide();
                $('#URT_SRC_tb_datepickerupdate').hide();
                $('#URT_SRC_lbl_reasonupdate').hide();
                $('#URT_SRC_ta_reasonupdate').hide();
                $('#URT_SRC_btn_update').hide();
                $('#URT_SRC_lbl_loginupdate').hide();
                $('#URT_SRC_lb_loginupdate').hide();
                $('#URT_SRC_lbl_selectsearchupdate').hide();
                $('#URT_SRC_lbl_selectrejoin').hide();
                $('#URT_SRC_radio_selectsearchupdate').hide();
                $('#URT_SRC_radio_selectrejoin').hide();
                $('#URT_SRC_lbl_selectoption').hide();
                $('#URT_SRC_lb_recordversion').hide();
                $('#URT_SRC_lbl_recordversion').hide();
                $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
            }
        }
        var choice='UPDATE';
        xmlhttp.open("POST","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.php?option="+choice+"&URT_SRC_loggin="+URT_SRC_loggin,true);
        xmlhttp.send(new FormData(formElement));
    });
    $('#URT_SRC_lb_loginupdate').change(function(){
        $('#URT_SRC_lb_recordversion').hide();
        $('#URT_SRC_lbl_recordversion').hide();
        $('#URT_SRC_tb_datepickerupdate').hide();
        $('#URT_SRC_lbl_datepickerupdate').hide();
        $('#URT_SRC_ta_reasonupdate').hide();
        $('#URT_SRC_lbl_reasonupdate').hide();
        $('#URT_SRC_btn_update').hide();
        $('.preloader').show();
        var URT_SRC_loggin=$(this).val();
        var URT_SRC_empname_upd=$("#URT_SRC_lb_loginupdate option:selected").text();
        var recver_array=[];
        if(URT_SRC_empname_upd !="SELECT"){
            $('#URT_SRC_lb_recordversion').hide();
            $('#URT_SRC_lbl_recordversion').hide();
            $('#URT_SRC_tb_datepickerupdate').hide();
            $('#URT_SRC_lbl_datepickerupdate').hide();
            $('#URT_SRC_ta_reasonupdate').hide();
            $('#URT_SRC_lbl_reasonupdate').hide();
            $('#URT_SRC_btn_update').hide();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var values_array=JSON.parse(xmlhttp.responseText);
                    var rec_ver='<option value="SELECT">SELECT</option>';
                    recver_array=(values_array.recver);
                    for(var k=0;k<recver_array.length;k++){
                        rec_ver += '<option value="' + recver_array[k] + '">' + recver_array[k] + '</option>';
                    }
                    $('#URT_SRC_lb_recordversion').html(rec_ver);
                    var recver=(values_array.recver).length;
                    if(recver==1){
                        var min_date=values_array.enddate;
                        var mindate=min_date.toString().split('-');
                        var month=mindate[1]-1;
                        var year=mindate[2];
                        var date=parseInt(mindate[0]);
                        var minimumdate = new Date(year,month,date);
                        $('#URT_SRC_tb_datepickerupdate').val(values_array.enddate);
                        $('#URT_SRC_ta_reasonupdate').val(values_array.reasonn);
                        $('#URT_SRC_lb_recordversion').val(values_array.recver);
                        $('#URT_SRC_tb_datepickerupdate').datepicker("option","minDate",minimumdate);
                        var mindate=min_date.toString().split('-');
                        var month=parseInt(mindate[1]-1)+1;//mindate[1]-1;
                        var year=mindate[2];
                        var date=parseInt(mindate[0])+1;
                        var minimumdate = new Date(year,month,date);
                        $('#URT_SRC_tb_datepickerupdate').datepicker("option","maxDate",new Date());
                    }
                    else{
                        $('#URT_SRC_tb_datepickerupdate').hide();
                        $('#URT_SRC_ta_reasonupdate').hide();
                        $('#URT_SRC_lbl_datepickerupdate').hide();
                        $('#URT_SRC_lbl_reasonupdate').hide();
                        $('#URT_SRC_btn_update').hide();
                        $('#URT_SRC_lb_recordversion').show();
                        $('#URT_SRC_lbl_recordversion').show();
                    }
                }
            }
            $('.preloader').hide();
        }
        else
        {
            $('#URT_SRC_tb_datepickerupdate').hide();
            $('#URT_SRC_ta_reasonupdate').hide();
            $('#URT_SRC_lbl_datepickerupdate').hide();
            $('#URT_SRC_lbl_reasonupdate').hide();
            $('#URT_SRC_btn_update').hide();
            $('#URT_SRC_lb_recordversion').hide();
            $('#URT_SRC_lbl_recordversion').hide();
            $('.preloader').hide();
        }
        var option='FETCH';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.php?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option,true);
        xmlhttp.send();
    });
    //CHANGE FUNCTION FOR RECORD VERSION
    $('#URT_SRC_lb_recordversion').change(function(){
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        var recver= $('#URT_SRC_lb_recordversion').val();
        var URT_SRC_loggin=$('#URT_SRC_lb_loginupdate').val();
        if(recver!='SELECT'){
            $('.preloader').show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var values_array=JSON.parse(xmlhttp.responseText);
                    var min_date=values_array.enddate;
                    var mindate=min_date.toString().split('-');
                    var month=mindate[1]-1;
                    var year=mindate[2];
                    var date=parseInt(mindate[0]);
                    var minimumdate = new Date(year,month,date);
                    $('#URT_SRC_tb_datepickerupdate').val(values_array.enddate);
                    $('#URT_SRC_ta_reasonupdate').val(values_array.reasonn);
                    $('#URT_SRC_tb_datepickerupdate').datepicker("option","minDate",minimumdate);
                    $('#URT_SRC_tb_datepickerupdate').datepicker("option","maxDate",new Date());
                    $('#URT_SRC_tb_datepickerupdate').show();
                    $('#URT_SRC_ta_reasonupdate').show();
                    $('#URT_SRC_lbl_datepickerupdate').show();
                    $('#URT_SRC_lbl_reasonupdate').show();
                    $('#URT_SRC_btn_update').show();
                    $('#URT_SRC_lb_recordversion').show();
                    $('#URT_SRC_lbl_recordversion').show();
                }
            }
            var option='FETCH DATA';
            xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.php?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option+"&recver="+recver,true);
            xmlhttp.send();
        }
        else{
            $('#URT_SRC_tb_datepickerupdate').hide();
            $('#URT_SRC_ta_reasonupdate').hide();
            $('#URT_SRC_lbl_datepickerupdate').hide();
            $('#URT_SRC_lbl_reasonupdate').hide();
            $('#URT_SRC_btn_update').hide();
        }
    });
    //CHANGE FUNCTION FOR LOGIN TERMINATE FORM
    $('#URT_SRC_lb_loginterminate').change(function(){
        $('#URT_SRC_errdate').hide();
        $('.preloader').show();
        var URT_SRC_loggin=$(this).val();
        if(URT_SRC_loggin !=""){
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var min_date=(xmlhttp.responseText);
                    var mindate=min_date.toString().split('-');
                    var month=mindate[1]-1;
                    var year=mindate[2];
                    var date=parseInt(mindate[0])+1;
                    var minimumdate = new Date(year,month,date);
                    $('#URT_SRC_tb_datepickertermination').datepicker("option","minDate",minimumdate);
                    $(".URT_SRC_tb_termindatepickerclass").datepicker("option","maxDate",new Date())
                }
            }
        }
        var option='GETDATE';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.php?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option,true);
        xmlhttp.send();
    });
    var err_flag=0;
    $('#URT_SRC_tb_datepickertermination').change(function(){
        $('.preloader').show();
        var URT_SRC_loggin=$('#URT_SRC_lb_loginterminate').val();
        var date_value=$('#URT_SRC_tb_datepickertermination').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var final_value=xmlhttp.responseText;
                if(final_value!=''){
                    err_flag=1;
                    var URT_loginid_val=$("#URT_SRC_lb_loginterminate option:selected").text();

                    var msg=js_errormsg_array[10].replace('[LOGIN ID]',URT_loginid_val);
                    msg=msg.replace('[DATE]',final_value);
                    $('#URT_SRC_errdate').text(msg).show();
                    $('#URT_SRC_btn_termination').attr('disabled','disabled');
                }
                else{
                    err_flag=0;
                    $('#URT_SRC_errdate').hide();
                    URT_SRC_validation();
                }
            }
        }
        var option='GET_VALUE';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.php?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option+"&date_value="+date_value,true);
        xmlhttp.send();
    });
    //CHANGE FUNCTION FOR REJOIN LOGIN ID LISTBX
    $('#URT_SRC_lb_loginrejoin').change(function(){
        var URT_loginid_val=$("#URT_SRC_lb_loginrejoin option:selected").text();
        if(URT_loginid_val=='SELECT')
        {
            $("#URT_SRC_tble_roles").hide();
            $('#URSRC_table_employeetbl').hide();
            $("#URT_SRC_tb_datepickerrejoin").hide();
            $("#URT_SRC_btn_rejoin").hide();
            $("#URT_SRC_lbl_datepickerrejoin").hide();
            $("#URSRC_lbl_emptype").hide();
            $('#URSRC_lb_selectemptype').hide();
            $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
            $('#URSRC_lbl_selectteam').hide();
            $('#URSRC_lb_selectteam').hide();
            $('#URSRC_btn_add').hide();
            $('#URT_SRC_lbl_pword').val('').hide();
            $('#URT_SRC_tb_pword').val('').hide();
            $("#URT_SRC_lbl_uname").val('').hide();
            $("#URT_SRC_tb_uname").val('').hide();
            $("#URT_SRC_lbl_confirmpword").val('').hide();
            $("#URT_SRC_tb_confirmpword").val('').hide();
            $('#URT_SRC_lbl_match').hide();
        }
        else
        {
            $("#URT_SRC_lbl_uname").show();
            $("#URT_SRC_tb_uname").show();
            $('#URT_SRC_lbl_pword').val('').show();
            $('#URT_SRC_tb_pword').val('').show();
            $("#URT_SRC_lbl_confirmpword").val('').show();
            $("#URT_SRC_tb_confirmpword").val('').show();
            $("#URT_SRC_tble_roles").show();
            $('#URSRC_table_employeetbl').show();
            $("#URT_SRC_tb_datepickerrejoin").val('').show();
            $("#URT_SRC_lbl_datepickerrejoin").show();
            $("#URT_SRC_btn_rejoin").show();
            $("#URSRC_lbl_emptype").show();
            $('#URSRC_lb_selectemptype').show();
            $('#URSRC_lbl_selectteam').show();
            $('#URSRC_lb_selectteam').show();
            if(URSRC_team_array.length!=0){
            $('#URSRC_btn_add').show();
            }
            $('#URSRC_tb_firstname').val('');
            $('#URSRC_tb_lastname').val('');
            $('#URSRC_tb_dob').val('');
            $('#URSRC_tb_designation').val('');
            $('#URSRC_rd_male').attr('checked',false);
            $('#URSRC_rd_female').attr('checked',false);
            $('#URSRC_tb_permobile').val('');
            $('#URSRC_tb_kinname').val('');
            $('#URSRC_tb_relationhd').val('');
            $('#URSRC_tb_mobile').val('');
            $('#URSRC_tb_bnkname').val('');
            $('#URSRC_tb_brnchname').val('');
            $('#URSRC_tb_accntname').val('');
            $('#URSRC_tb_accntno').val('');
            $('#URSRC_tb_ifsccode').val('');
            $('#URSRC_tb_accntyp').val('');
            $('#URSRC_ta_brnchaddr').val('');
            $('#URSRC_tb_laptopno').val('');
            $('#URSRC_tb_chargerno').val('');
            $('#URSRC_ta_comments').val('');
            $('#URSRC_ta_addr').val('');
            $('#URSRC_tb_aadharno').val('').hide();
            $('#URSRC_tb_passportno').val('').hide();
            $('#URSRC_tb_votersid').val('').hide();
            $("input[name=URSRC_chk_bag]:checked").attr('checked',false);
            $("input[name=URSRC_chk_mouse]:checked").attr('checked',false);
            $("input[name=URSRC_chk_dracess]:checked").attr('checked',false);
            $("input[name=URSRC_chk_idcrd]:checked").attr('checked',false);
            $("input[name=URSRC_chk_headset]:checked").attr('checked',false);
            $("input[name=URSRC_chk_aadharno]:checked").attr('checked',false);
            $("input[name=URSRC_chk_passportno]:checked").attr('checked',false);
            $("input[name=URSRC_chk_votersid]:checked").attr('checked',false);
            $('#URSRC_lbl_validnumber').hide();
            $('#URSRC_lbl_validnumber1').hide();
            $("input[name=URT_SRC_radio_nrole]:checked").attr('checked',false);
            $('.preloader').show();
            var URT_SRC_loggin=$(this).val();
            if(URT_loginid_val !=""){
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $("html, body").animate({ scrollDown: 200 }, 1000);
//                        $("html, body").animate({ scrollTop: 100 }, "1000");
                        $('.preloader').hide();
                        var values_array=JSON.parse(xmlhttp.responseText);
                        var min_date=values_array[0][1];
                        var URSRC_team_array=values_array[0][2];
                        var firstname=values_array[0][0].firstname;
                        var lastname=values_array[0][0].lastname;
                        var nricno=values_array[0][0].nricno;
                        var dob=values_array[0][0].dob;
                        var address=values_array[0][0].address;
                        var gender=values_array[0][0].gender;
                        if(gender=='MALE')
                        {
                            $('#URSRC_rd_male').attr('checked',true);
                        }
                        if(gender=='FEMALE')
                        {
                            $('#URSRC_rd_female').attr('checked',true);
                        }
                        var designation=values_array[0][0].designation;
                        var mobile=values_array[0][0].mobile;
                        var kinname=values_array[0][0].kinname;
                        var relationhood=values_array[0][0].relationhood;
                        var altmobile=values_array[0][0].altmobile;
                        var bankname=values_array[0][0].bankname;
                        var branchname=values_array[0][0].branchname;
                        var accountname=values_array[0][0].accountname;
                        var accountno=values_array[0][0].accountno;
                        var ifsccode=values_array[0][0].ifsccode;
                        var accountype=values_array[0][0].accountype;
                        var branchaddr=values_array[0][0].branchaddress;
                        var comments=values_array[0][0].URSRC_comments;
                        var username=values_array[0][0].username;
                        var emailid=values_array[0][0].URSRC_emailid;
                        var mindate=min_date.toString().split('-');
                        var month=mindate[1]-1;
                        var year=mindate[2];
                        var date=parseInt(mindate[0])+1;
                        var minimumdate = new Date(year,month,date);
                        $('#URT_SRC_tb_datepickerrejoin').datepicker("option","minDate",minimumdate);
                        $('#URT_SRC_tb_datepickerrejoin').datepicker("option","maxDate",new Date());
                        var emp_firstname=firstname.length;
                        $('#URSRC_tb_firstname').val(firstname).attr("size",emp_firstname+3);
                        var emp_lastname=lastname.length;
                        $('#URSRC_tb_lastname').val(lastname).attr("size",emp_lastname+3);
                        $('#URSRC_tb_nric').val(nricno);
                        $('#URSRC_tb_dob').val(dob);
                        $('#URSRC_ta_addr').val(address);
                        var emp_designation=designation.length;
                        $('#URSRC_tb_designation').val(designation).attr("size",emp_designation+4);
                        $('#URSRC_tb_permobile').val(mobile);
                        var emp_kinname=kinname.length;
                        $('#URSRC_tb_kinname').val(kinname).attr("size",emp_kinname+1);
                        var emp_relationhood=relationhood.length;
                        $('#URSRC_tb_relationhd').val(relationhood).attr("size",emp_relationhood+2);
                        $('#URSRC_tb_mobile').val(altmobile);
                        var emp_bankname=bankname.length;
                        $('#URSRC_tb_bnkname').val(bankname).attr("size",emp_bankname+2);
                        var emp_branchname=branchname.length;
                        $('#URSRC_tb_brnchname').val(branchname).attr("size",emp_branchname+3);
                        var emp_accountname=accountname.length;
                        $('#URSRC_tb_accntname').val(accountname).attr("size",emp_accountname+2);
                        var emp_accountno=accountno.length;
                        $('#URSRC_tb_accntno').val(accountno).attr("size",emp_accountno+2);
                        var emp_ifsccode=ifsccode.length;
                        $('#URSRC_tb_ifsccode').val(ifsccode).attr("size",emp_ifsccode+2);
                        var emp_accountype=accountype.length;
                        $('#URSRC_tb_accntyp').val(accountype).attr("size",emp_accountype+2);
                        $('#URSRC_ta_brnchaddr').val(branchaddr);
                        $('#URSRC_ta_comments').val(comments);
                        $('#URSRC_tb_emailid').val(emailid);
                        $('#URT_SRC_tb_uname').val(username);//
                        $('#URSRC_lb_selectteam').replaceWith('<select id="URSRC_lb_selectteam" name="URSRC_lb_selectteam"  maxlength="25" class="login_submitvalidate form-control upper"   ></select>')
                        var team='<option value="SELECT">SELECT</option>';
                        for(var k=0;k<URSRC_team_array.length;k++){
                            team += '<option value="' + URSRC_team_array[k] + '">' + URSRC_team_array[k] + '</option>';
                        }
                        $('#URSRC_lb_selectteam').html(team);
                        $(this).val('ADD');
                        $('#URSRC_lb_selectteam').show();
                    }
                }
            }
        }
        var option='GETENDDATE';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.php?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option,true);
        xmlhttp.send();
    });
//CLICK FUNCTION FOR AADHAR RDO BTN
    $('#URSRC_chk_aadharno').click(function(){
        if($("input[name=URSRC_chk_aadharno]").is(":checked")==true){
            $('#URSRC_tb_aadharno').show();
        }
        else{
            $('#URSRC_tb_aadharno').hide().val("");
        }
    });
    //CLICK FUNCTION FOR RD PASSPORT BTN
    $('#URSRC_chk_passportno').click(function(){
        if($("input[name=URSRC_chk_passportno]").is(":checked")==true){
            $('#URSRC_tb_passportno').show();
        }
        else{
            $('#URSRC_tb_passportno').hide().val("");
        }
    });
//CLICK FUNCTION FOR VOTERID BTN
    $('#URSRC_chk_votersid').click(function(){
        if($("input[name=URSRC_chk_votersid]").is(":checked")==true){
            $('#URSRC_tb_votersid').show();
        }
        else{
            $('#URSRC_tb_votersid').hide().val("");
        }
    });
    //CLICK FUNCTION FOR LOGIN TERMINATION RADIO BTN
    $('#URT_SRC_radio_logintermination').click(function(){
        err_flag=0;
        $('.preloader').show();
        $("#URT_SRC_lbl_datepickertermination").hide();
        $("#URT_SRC_tb_datepickertermination").hide();
        $("#URT_SRC_lbl_reasontermination").hide();
        $("#URT_SRC_ta_reasontermination").hide();
        $("#URT_SRC_btn_termination").hide();
        $('#URT_SRC_errdate').hide();
        var radio_value_loginidsearch=$(this).val();
        $('#URT_SRC_lb_recordversion').hide();
        $('#URT_SRC_lbl_recordversion').hide();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var loginid_array=JSON.parse(xmlhttp.responseText);
                if(loginid_array.length!=0){
                    var URT_SRC_loginid_options='<option>SELECT</option>'
                    for(var l=0;l<loginid_array.length;l++){
                        URT_SRC_loginid_options+= '<option value="' + loginid_array[l][1] + '">' + loginid_array[l][0]+ '</option>';
                    }
                    $('#URT_SRC_lb_loginterminate').html(URT_SRC_loginid_options);
                    $('#URT_SRC_lb_loginterminate').show().prop('selectedIndex',0);
                }
                else
                {
                    show_msgbox("ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",js_errormsg_array[3],"error",false)
                    $('#URT_SRC_lb_loginterminate').hide();
                    $('#URT_SRC_lbl_loginterminate').hide();
                }
            }
        }
        var option='TERMINATIONLB';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.php?radio_value_loginidsearch="+radio_value_loginidsearch+"&option="+option,true);
        xmlhttp.send();
    });
//LOGIN SECOND SEARCH ND UPDATE
    $('#URT_SRC_radio_selectrejoin').click(function(){
        $("#URT_SRC_tble_roles").hide();
        $('#URSRC_table_employeetbl').hide();
        $("#URT_SRC_tb_datepickerrejoin").hide();
        $("#URT_SRC_btn_rejoin").hide();
        $("#URT_SRC_lbl_datepickerrejoin").hide();
        $("#URSRC_lbl_emptype").hide();
        $('#URSRC_lb_selectemptype').hide();
        $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
        $('#URSRC_lbl_selectteam').hide();
        $('#URSRC_lb_selectteam').hide();
        $('#URSRC_btn_add').hide();
//        $("html, body").animate({ scrollDown: 100 }, "1000");
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
        $('.preloader').show();
        var radio_value_loginidsearch=$(this).val();
        $('#URT_SRC_lb_recordversion').hide();
        $('#URT_SRC_lbl_recordversion').hide();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var loginid_array=JSON.parse(xmlhttp.responseText);
                if(loginid_array.length!=0){
                    var URT_SRC_loginid_options='<option>SELECT</option>'
                    for(var l=0;l<loginid_array.length;l++){
                        URT_SRC_loginid_options+= '<option value="' + loginid_array[l][1] + '">' + loginid_array[l][0]+ '</option>';
                    }
                    $('#URT_SRC_lb_loginrejoin').html(URT_SRC_loginid_options);
                    $('#URT_SRC_lb_loginrejoin').show().prop('selectedIndex',0);
                }
                else
                {
                    $('#URT_SRC_lbl_loginrejoin').hide();
                    $('#URT_SRC_lb_loginrejoin').hide();
                    show_msgbox("ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",js_errormsg_array[4],"error",false)
                }
            }
        }
        var option='REJOINLB';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.php?radio_value_loginidsearch="+radio_value_loginidsearch+"&option="+option,true);
        xmlhttp.send();
    });
    //CLICK FUNCTION FOR RADIO SEARCH ND UPDATE BTN
    $('#URT_SRC_radio_selectsearchupdate').click(function(){
        $('.preloader').show();
        $('#URT_SRC_lbl_pword').val('').hide();
        $("#URT_SRC_tb_pword").val('').hide();
        $("#URT_SRC_lbl_uname").val('').hide();
        $("#URT_SRC_tb_uname").val('').hide();
        $("#URT_SRC_lbl_confirmpword").val('').hide();
        $("#URT_SRC_tb_confirmpword").val('').hide();
        $('#URT_SRC_lbl_match').hide();
        $('#URT_SRC_lbl_datepickerupdate').hide();
        $('#URT_SRC_tb_datepickerupdate').hide();
        $('#URT_SRC_lbl_reasonupdate').hide();
        $('#URT_SRC_ta_reasonupdate').hide();
        $('#URT_SRC_btn_update').hide();
        var radio_value_loginidsearch=$(this).val();
        $('#URT_SRC_lb_recordversion').hide();
        $('#URT_SRC_lbl_recordversion').hide();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var loginid_array=JSON.parse(xmlhttp.responseText);
                if(loginid_array.length!=0){
                    var URT_SRC_loginid_options='<option>SELECT</option>'
                    for(var l=0;l<loginid_array.length;l++){
                        URT_SRC_loginid_options+= '<option value="' + loginid_array[l][1] + '">' + loginid_array[l][0]+ '</option>';
                    }
                    $('#URT_SRC_lb_loginupdate').html(URT_SRC_loginid_options);
                    $('#URT_SRC_lb_loginupdate').show().prop('selectedIndex',0);

                }
                else
                {
                        show_msgbox("ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",js_errormsg_array[5],"error",false)
                    $('#URT_SRC_lbl_loginupdate').hide();
                    $('#URT_SRC_lb_loginupdate').hide();
                }
            }
        }
        var option='SEARCHLB';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.php?radio_value_loginidsearch="+radio_value_loginidsearch+"&option="+option,true);
        xmlhttp.send();
    });
    $("#URT_SRC_btn_rejoin").hide();
    $("#URT_SRC_btn_termination").hide();
    $("#URT_SRC_btn_update").hide();
    $("#URT_SRC_tble_roles").hide();
    $('#URSRC_table_employeetbl').hide();
    $("#URT_SRC_lbl_logintermination").show();
    $("#URT_SRC_lbl_loginsearchupdate").show();
    $('#URT_SRC_radio_logintermination').change(function(){
        $("#URT_SRC_lb_loginupdate").hide();
        $("#URT_SRC_lbl_loginterminate").show();
        $("#URT_SRC_tble_roles").hide();
        $('#URSRC_table_employeetbl').hide();
        $("#URT_SRC_tb_datepickerrejoin").hide();
        $("#URT_SRC_lbl_datepickerrejoin").hide();
        $("#URT_SRC_btn_rejoin").hide();
        $("#URT_SRC_lbl_loginterminate").val("SELECT");
        $("#URT_SRC_lb_loginterminate").show();
        $("#URT_SRC_lbl_selectoption").hide();
        $("#URSRC_lbl_emptype").hide();
        $('#URSRC_lb_selectemptype').hide();
        $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
        $('#URSRC_lbl_selectteam').hide();
        $('#URSRC_lb_selectteam').hide();
        $('#URSRC_btn_add').hide();
        $("#URT_SRC_radio_selectrejoin").hide();
        $("#URT_SRC_lbl_selectrejoin").hide();
        $("#URT_SRC_radio_selectsearchupdate").hide();
        $("#URT_SRC_lbl_selectsearchupdate").hide();
        $("#URT_SRC_lbl_loginrejoin").hide();
        $("#URT_SRC_lb_loginrejoin").hide();
        $('#URT_SRC_lbl_loginupdate').hide();
        $('#URT_SRC_lbl_datepickerupdate').hide();
        $('#URT_SRC_tb_datepickerupdate').hide();
        $('#URT_SRC_lbl_reasonupdate').hide();
        $('#URT_SRC_ta_reasonupdate').hide();
        $('#URT_SRC_btn_update').hide();
        $('#URT_SRC_lbl_pword').val('').hide();
        $("#URT_SRC_tb_pword").val('').hide();
        $("#URT_SRC_lbl_uname").val('').hide();
        $("#URT_SRC_tb_uname").val('').hide();
        $("#URT_SRC_lbl_confirmpword").val('').hide();
        $("#URT_SRC_tb_confirmpword").val('').hide();
        $('#URT_SRC_lbl_match').hide();
    });
    $('#URT_SRC_lb_loginterminate').change(function(){
        var loginid=$('#URT_SRC_lb_loginterminate').val();
        if(loginid=='SELECT')
        {
            $("#URT_SRC_lbl_datepickertermination").hide();
            $("#URT_SRC_tb_datepickertermination").hide();
            $("#URT_SRC_lbl_reasontermination").hide();
            $("#URT_SRC_ta_reasontermination").hide();
            $("#URT_SRC_btn_termination").hide();
        }
        else
        {
            $("#URT_SRC_lbl_datepickertermination").show();
            $("#URT_SRC_tb_datepickertermination").val('').show();
            $("#URT_SRC_lbl_reasontermination").show();
            $("#URT_SRC_ta_reasontermination").val('').show();
            $("#URT_SRC_btn_termination").show();
        }
    });
//CHANGE FUNCTION FOR LOGIN LIST BX OF SEARCH ND UPDATE OPTION
    $('#URT_SRC_lb_loginupdate').change(function(){
        $('#URT_SRC_lbl_recordversion').hide();
        $('#URT_SRC_lb_recordversion').hide();
        $('#URT_SRC_tb_datepickerupdate').hide();
        $('#URT_SRC_lbl_datepickerupdate').hide();
        $('#URT_SRC_ta_reasonupdate').hide();
        $('#URT_SRC_lbl_reasonupdate').hide();
        $('#URT_SRC_btn_update').hide();
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        var loginvalue=$('#URT_SRC_lb_loginupdate').val();
        var URT_SRC_empname_upd=$("#URT_SRC_lb_loginupdate option:selected").text();
        if(URT_SRC_empname_upd=='SELECT')
        {
            $('#URT_SRC_lbl_datepickerupdate').hide();
            $('#URT_SRC_tb_datepickerupdate').hide();
            $('#URT_SRC_lbl_reasonupdate').hide();
            $('#URT_SRC_ta_reasonupdate').hide();
            $('#URT_SRC_btn_update').hide();
            $('#URT_SRC_lb_recordversion').hide();
            $('#URT_SRC_lbl_recordversion').hide();
        }
        else
        {
            $('#URT_SRC_lbl_datepickerupdate').show();
            $('#URT_SRC_tb_datepickerupdate').val('').show();
            $('#URT_SRC_lbl_reasonupdate').show();
            $('#URT_SRC_ta_reasonupdate').val('').show();
            $('#URT_SRC_btn_update').show();
        }
    });
//CHNAGE FUNCTION FOR RADIO OF LOGIN SEARCH ND UPDATE BTN
    $('#URT_SRC_radio_loginsearchupdate').change(function(){
        err_flag=0;
        email_flag=0;
        $("#URT_SRC_lbl_selectoption").show();
        $('#URT_SRC_errdate').hide();
        $("#URT_SRC_radio_selectrejoin").show();
        $("#URT_SRC_lbl_selectrejoin").show();
        $("#URT_SRC_lbl_selectsearchupdate").show();
        $("#URT_SRC_radio_selectsearchupdate").show();
        $("#URT_SRC_lbl_loginterminate").hide();
        $("#URSRC_lbl_emptype").hide();
        $('#URT_SRC_lb_recordversion').hide();
        $('#URT_SRC_lbl_recordversion').hide();
        $('#URSRC_lb_selectemptype').hide();
        $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
        $('#URSRC_lbl_selectteam').hide();
        $('#URSRC_lb_selectteam').hide();
        $('#URSRC_btn_add').hide();
        $("#URT_SRC_lb_loginterminate").hide();
        $("#URT_SRC_lbl_datepickertermination").hide();
        $("#URT_SRC_tb_datepickertermination").hide();
        $("#URT_SRC_lbl_reasontermination").hide();
        $("#URT_SRC_ta_reasontermination").hide();
        $("#URT_SRC_btn_termination").hide();
        $("input[name=URT_SRC_radio_nselectoption]:checked").attr('checked',false);
    });

    $('#URT_SRC_radio_selectsearchupdate').change(function(){
        $('#URT_SRC_errdate').hide();
        $('#URT_SRC_lbl_loginrejoin').hide();
        $('#URT_SRC_lb_loginrejoin').hide();
        $('#URT_SRC_lbl_loginterminate').hide();
        $('#URT_SRC_lb_loginterminate').hide();
        $("#URT_SRC_tble_roles").hide();
        $('#URSRC_table_employeetbl').hide();
        $("#URSRC_lbl_emptype").hide();
        $('#URSRC_lb_selectemptype').hide();
        $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
        $('#URSRC_lbl_selectteam').hide();
        $('#URSRC_lb_selectteam').hide();
        $('#URSRC_btn_add').hide();
        $("#URT_SRC_tb_datepickerrejoin").hide();
        $("#URT_SRC_lbl_datepickerrejoin").hide();
        $("#URT_SRC_btn_rejoin").hide();
        $("#URT_SRC_lbl_loginupdate").show();
        $('#URT_SRC_lbl_datepickerupdate').hide();
        $('#URT_SRC_tb_datepickerupdate').hide();
        $('#URT_SRC_lbl_reasonupdate').hide();
        $('#URT_SRC_ta_reasonupdate').hide();
        $('#URT_SRC_btn_update').hide();
        ('#URT_SRC_lbl_pword').val('').hide();
        $("#URT_SRC_tb_pword").val('').hide();
        $("#URT_SRC_lbl_uname").val('').hide();
        $("#URT_SRC_tb_uname").val('').hide();
        $("#URT_SRC_lbl_confirmpword").val('').hide();
        $("#URT_SRC_tb_confirmpword").val('').hide();
        $('#URT_SRC_lb_loginupdate').show();
    });
    $('#URT_SRC_radio_selectrejoin').change(function(){
        $("#URT_SRC_lbl_datepickerrejoin").hide();
        $("#URT_SRC_lbl_loginrejoin").show();
        $("#URT_SRC_tble_roles").hide();
        $('#URSRC_table_employeetbl').hide();
        $("#URT_SRC_lb_loginupdate").hide();
        $("#URT_SRC_tb_datepickerrejoin").hide();
        $("#URT_SRC_btn_rejoin").hide();
        $('#URT_SRC_lbl_loginupdate').hide();
        $('#URT_SRC_lbl_datepickerupdate').hide();
        $('#URT_SRC_tb_datepickerupdate').hide();
        $('#URT_SRC_lbl_reasonupdate').hide();
        $('#URT_SRC_ta_reasonupdate').hide();
        $('#URT_SRC_btn_update').hide();
        $("input[name=URT_SRC_radio_nrole]:checked").attr('checked',false);

    });
//DATE PICKER FUNCTION
    $('.URT_SRC_tb_termindatepickerclass').datepicker({
        dateFormat:"dd-mm-yy",
        changeYear: true,
        changeMonth: true

    });
    //TO SET REJOIN DATE PICKER
    $('.URT_SRC_tb_rejoinndsearchdatepicker').datepicker({
        dateFormat:"dd-mm-yy",
        changeYear: true,
        changeMonth: true
    });
    //FORM VALIDATION
    $(document).on('change','#URT_SRC_form_terminatesearchupdate,.login_submitvalidate',function(){
        URT_SRC_validation();
    });
    //FORM VALIDATION FUNCTION CALLIN
    function URT_SRC_validation(){
        var Selectedradiooption = $("input[name='URT_SRC_radio_nterminndupdatesearch']:checked").val();
        var Selectedradiooption = $("input[name='URT_SRC_radio_nterminndupdatesearch']:checked").val();
        if(Selectedradiooption=='URT_SRC_radio_valuelogintermination')
        {
            if(($('#URT_SRC_lb_loginterminate').val()!='SELECT') && ($("#URT_SRC_tb_datepickertermination").val()!="") && (($("#URT_SRC_ta_reasontermination").val()).trim()!="")&& (err_flag==0))
            {
                $("#URT_SRC_btn_termination").removeAttr("disabled");
            }
            else
            {
                $("#URT_SRC_btn_termination").attr("disabled", "disabled");
            }
        }
        if(Selectedradiooption=='URT_SRC_radio_valueloginsearchupdate')
        {
            var Selectedsearchradiooption = $("input[name='URT_SRC_radio_nselectoption']:checked").val();
            var URSRC_Firstname= $("#URSRC_tb_firstname").val();
            var URSRC_Lastname =$("#URSRC_tb_lastname").val();
            var URSRC_tb_dob=$('#URSRC_tb_dob').val();
            var URSRC_empdesig =$("#URSRC_tb_designation").val();
            var URSRC_Mobileno = $("#URSRC_tb_permobile").val();
            var URSRC_kinname = $("#URSRC_tb_kinname").val();
            var URSRC_relationhd = $("#URSRC_tb_relationhd").val();
            var URSRC_mobile= $("#URSRC_tb_mobile").val();
            var URSRC_bnkname =$("#URSRC_tb_bnkname").val();
            var URSRC_tb_brnname=$('#URSRC_tb_brnchname').val();
            var URSRC_accname =$("#URSRC_tb_accntname").val();
            var URSRC_acctno = $("#URSRC_tb_accntno").val();
            var URSRC_ifsc = $("#URSRC_tb_ifsccode").val();
            var URSRC_accttyp = $("#URSRC_tb_accntyp").val();
            var URSRC_brnchaddr= $("#URSRC_ta_brnchaddr").val();
            var URT_SRC_aadharno=$('#URSRC_tb_aadharno').val();
            var URT_SRC_passportnono=$('#URSRC_tb_passportno').val();
            var URT_SRC_votersidno=$('#URSRC_tb_votersid').val();
            var new_address=$('#URSRC_ta_addr').val();
            var passwrd=$('#URT_SRC_tb_pword').val();
            var confrmpasswrd=$('#URT_SRC_tb_confirmpword').val();
            var teamlb_txtbx=$('#URSRC_lb_selectteam').val();
            var URSRC_emailid=$('#URSRC_tb_emailid').val();
            if(Selectedsearchradiooption=='URT_SRC_radio_valuerejoin')
            {
                if((URSRC_emailid!='')&&(email_flag==0)&&((teamlb_txtbx!='SELECT' && $('#URSRC_btn_add').val()=='ADD')||(teamlb_txtbx!="" && $('#URSRC_btn_add').val()=='CLEAR') )&&($('#URSRC_lb_selectemptype').val()!='SELECT') && ($("#URT_SRC_tb_datepickerrejoin").val()!="")&& ($("input[name=URT_SRC_radio_nrole]").is(":checked")==true)&&(URSRC_Firstname!='') && (URSRC_Lastname!='' ) && (URSRC_tb_dob!='' ) && (URSRC_empdesig!='' )&&( URSRC_Mobileno!='' && (parseInt($('#URSRC_tb_permobile').val())!=0)) && (URSRC_kinname!='')&& (URSRC_relationhd!='' )&& (URSRC_Mobileno.length>=8)&&(URSRC_mobile.length>=8 )&&(URSRC_brnchaddr!="")&&(URSRC_accttyp!="")&&(URSRC_ifsc!="")&&(URSRC_acctno!="")&&(URSRC_accname!="")&&(URSRC_tb_brnname!="")&&(URSRC_bnkname!="") &&(new_address!="")&&(passwrd!="")&&(confrmpasswrd!=""))
                {
                    $("#URT_SRC_btn_rejoin").removeAttr("disabled");
                }
                else
                {
                    $("#URT_SRC_btn_rejoin").attr("disabled", "disabled");
                }
            }
            else
            {
                if(($('#URT_SRC_lb_loginupdate').val()!='SELECT') && ($("#URT_SRC_tb_datepickerupdate").val()!='') && (($("#URT_SRC_ta_reasonupdate").val()).trim()) && ($('#URT_SRC_lb_recordversion').val()!='SELECT'))
                {
                    $("#URT_SRC_btn_update").removeAttr("disabled");
                }
                else
                {
                    $("#URT_SRC_btn_update").attr("disabled", "disabled");
                }
            }
        }
    }
    //BLUR FUNCTION FOR MOBILE NUMBER VALIDATION
    $(document).on('blur','.valid',function(){
        var URSRC_Mobileno=$(this).attr("id");
        var URSRC_Mobilenoval=$(this).val();
        if(URSRC_Mobilenoval.length==8)
        {
            if(URSRC_Mobileno=='URSRC_tb_permobile')
                $('#URSRC_lbl_validnumber').hide();
            else
                $('#URSRC_lbl_validnumber1').hide();
        }
        else
        {
            if(URSRC_Mobileno=='URSRC_tb_permobile')
                $('#URSRC_lbl_validnumber').text(js_errormsg_array[8]).show();
            else
                $('#URSRC_lbl_validnumber1').text(js_errormsg_array[8]).show();
        }
    });
    //CLICK FUNCTION FOR TEAM ADD BTN
    $('#URSRC_btn_add').click(function(){
        var URSRC_btn_value=$(this).val();
        if(URSRC_btn_value=='ADD'){
            $('#URSRC_lb_selectteam').replaceWith('<input type="text"  name="URSRC_lb_selectteam" id="URSRC_lb_selectteam" class="login_submitvalidate form-control upper check_team" /><label id="URSRC_lbl_team_err" class="errormsg"></label>');
            $(this).val('CLEAR');
            URT_SRC_validation();
        }
        else{
            $('#URSRC_lb_selectteam').replaceWith('<select id="URSRC_lb_selectteam" name="URSRC_lb_selectteam"  maxlength="25" class="login_submitvalidate form-control upper" hidden  ></select>')
            var team='<option value="SELECT">SELECT</option>';
            for(var k=0;k<URSRC_team_array.length;k++){
                team += '<option value="' + URSRC_team_array[k] + '">' + URSRC_team_array[k] + '</option>';
            }
            $('#URSRC_lb_selectteam').html(team);
            URT_SRC_validation();
            $(this).val('ADD');
        }
    });
    var pass_flag=0;
    $(document).on('change','.chk_password',function(){
        var URSRC_pass_length=($('#URT_SRC_tb_pword').val()).length;
        if(URSRC_pass_length<8){
            $('#URSRC_lbl_passwrd_errupd').text(js_errormsg_array[15]).show();
            pass_flag=0;
        }
        else{
            pass_flag=1;
            $('#URSRC_lbl_passwrd_errupd').hide();
        }
    });
    var incorrectflag=0;
    //CHANGE EVENT FOR CONFIRM PASSWORD
    $(document).on("change",'#URT_SRC_tb_confirmpword,.chk_password', function (){
        var password=$('#URT_SRC_tb_pword').val();
        var confirmpassword=$('#URT_SRC_tb_confirmpword').val();
        if(confirmpassword!=''){
            if(password!=confirmpassword)
            {
                $('#URSRC_lbl_confirmpasswrd_errupd').text(js_errormsg_array[14]).show();
                incorrectflag=0;
            }
            else
            {
                $('#URSRC_lbl_confirmpasswrd_errupd').hide();
                incorrectflag=1;
            }
        }
    });
    $(document).on("keyup",'.upper',function() {
        if (this.value.match(/[^a-zA-Z0-9\-]/g)) {
            this.value = this.value.replace(/[^a-zA-Z0-9\-]/g, '');
        }
        $('#URSRC_lb_selectteam').val($('#URSRC_lb_selectteam').val().toUpperCase())
    });
    var team_flag=0;
    $(document).on("change",'.check_team',function() {
        var team_name=$('#URSRC_lb_selectteam').val();

        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var msgalert=JSON.parse(xmlhttp.responseText);

                if(msgalert==0)
                {
                    $('#URSRC_lbl_team_err').hide()
                    team_flag=1;
                }
                else{
                    var msg=js_errormsg_array[7].replace('RECORD NOT UPDATED','ROLE :[NAME] ALREADY EXISTS')
                    var finalmsg=msg.replace("[NAME]",$('#URSRC_lb_selectteam').val())
                    var fnlmsg=finalmsg.replace("ROLE",'TEAM')
                    $('#URSRC_lbl_team_err').text(fnlmsg).show()
                    team_flag=0;
                }
            }
        }
        var choice='URSRC_check_team';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.php?URSRC_team_name="+team_name+"&option="+choice,true);
        xmlhttp.send();
    });

    var email_flag=0;
    $(document).on("blur change",'#URSRC_tb_emailid', function (){

        var emailid=($('#URSRC_tb_emailid').val().toLowerCase());
        $('#URSRC_tb_emailid').val(emailid)
        var atpos=emailid.indexOf("@");
        var dotpos=emailid.lastIndexOf(".");
        if ((atpos<1 || dotpos<atpos+2 || dotpos+2>=emailid.length)||(/^[@a-zA-Z0-9-\\.]*$/.test(emailid) == false))
        {
            email_flag=1;
            $("#URSRC_lbl_email_error").text(js_errormsg_array[6]).show();
            $('#URSRC_tb_emailid').addClass("invalid")

        }
        else{
            email_flag=0;
            $("#URSRC_lbl_email_error").hide();
            $('#URSRC_tb_emailid').removeClass("invalid")

        }



    });


});
</script>
<div class="container">
<div class="panel panel-info">
<div class="panel-heading">
    <h2 class="panel-title">ACCESS RIGHTS TERMINATE SEARCH / UPDATE</h2>
</div>
<div class="panel-body">
<form id="URT_SRC_form_terminatesearchupdate" name="URT_SRC_form_terminatesearchupdate" class="form-horizontal" role="form">
<div class="form-group row">
<div class="radio">
    <label class="col-sm-2" name="URT_SRC_lbl_nlogintermination" id="URT_SRC_lbl_logintermination" style="white-space: nowrap!important;">
       <input align="right" type="radio"  name="URT_SRC_radio_nterminndupdatesearch" id="URT_SRC_radio_logintermination"  value="URT_SRC_radio_valuelogintermination" >
        LOGIN TERMINATION
    </label>
    </div>
</div>
<div class="radio">
<div class="form-group row">
    <label class="col-sm-2" name="URT_SRC_lbl_nloginsearchupdate" id="URT_SRC_lbl_loginsearchupdate" style="white-space: nowrap!important;">
      <input type="radio" name="URT_SRC_radio_nterminndupdatesearch" id="URT_SRC_radio_loginsearchupdate" value="URT_SRC_radio_valueloginsearchupdate" >
        SEARCH/UPDATE
    </label>
</div>
</div>
<div class="form-group row">
    <label name="URT_SRC_lbl_nloginterminate" id="URT_SRC_lbl_loginterminate" class="srctitle  col-sm-2" style="white-space: nowrap!important;" hidden>
        EMPLOYEE NAME<em>*</em>
    </label>
    <div class="col-sm-5">
        <select class="form-control" name="URT_SRC_lb_nloginterminate" id="URT_SRC_lb_loginterminate" hidden style="max-width: 309px"> <option >SELECT</option></select>
    </div>
</div>
<div class="form-group row">
    <label name="URT_SRC_lbl_datepickertermination" id="URT_SRC_lbl_datepickertermination" class="srctitle  col-sm-2"  hidden> SELECT A END DATE <em>*</em> </label>
    </label>
    <div class="col-sm-10">
        <input type="text" name="URT_SRC_tb_ndatepickertermination" id="URT_SRC_tb_datepickertermination" class="URT_SRC_tb_termindatepickerclass datemandtry form-control" placeholder="End Date" style="width:100px;" hidden>
    </div>
    <label id="URT_SRC_errdate" name="URT_SRC_errdate" class="errormsg  col-sm-2"></label>
</div>
<div class="form-group row">
    <label name="URT_SRC_lbl_nreasontermination" id="URT_SRC_lbl_reasontermination" class="srctitle  col-sm-2" hidden>
        REASON OF TERMINATION <em>*</em>
    </label>
    <div class="col-sm-10">
        <textarea name="URT_SRC_ta_nreasontermination" id="URT_SRC_ta_reasontermination" class="form-control tarea" placeholder="Reason Of Termination" hidden> </textarea>
    </div>
</div>
<div>
    <input type="button"  value="TERMINATE" id="URT_SRC_btn_termination" class="btn  btn-info"  style="width:120px;" hidden>
</div>
<div class="form-group row">
    <label name="URT_SRC_lbl_nselectoption" id="URT_SRC_lbl_selectoption" class="srctitle col-sm-2" style="white-space: nowrap!important;" hidden>
        SELECT A OPTION
    </label>
</div>
<div class="form-group">
<div class="radio">
    <label class="col-sm-2" name="URT_SRC_lbl_nselectrejoin" id="URT_SRC_lbl_selectrejoin"  style="white-space: nowrap!important;" hidden>
      <input type="radio" name="URT_SRC_radio_nselectoption" id="URT_SRC_radio_selectrejoin"    value="URT_SRC_radio_valuerejoin" hidden>
        REJOIN
    </label>
</div>
</div>
<div class="form-group">
    <div class="radio">
    <label class="col-sm-2" name="URT_SRC_lbl_nselectsearchupdate" id="URT_SRC_lbl_selectsearchupdate"  style="white-space: nowrap!important;" hidden>
       <input type="radio" name="URT_SRC_radio_nselectoption" id="URT_SRC_radio_selectsearchupdate" hidden>
        SEARCH/UPDATE
    </label>
</div>
</div>
<div class="form-group row">
    <label name="URT_SRC_lbl_nloginrejoin" id="URT_SRC_lbl_loginrejoin" class="srctitle col-sm-2" hidden>
        EMPLOYEE NAME<em>*</em>
    </label>
    <div class="col-sm-3">
        <select class=" form-control" name="URT_SRC_lb_nloginrejoin" id="URT_SRC_lb_loginrejoin"  hidden > <option>SELECT</option> </select>
    </div>
</div>
<div class="form-group row">
    <label name="URT_SRC_lbl_uname" id="URT_SRC_lbl_uname" class="col-sm-2" hidden>
        USERNAME
    </label>
    <div class="col-sm-3">
        <input type="text" name="URT_SRC_tb_uname" id="URT_SRC_tb_uname" placeholder="UserName" maxlength='30' class="sizefix title_alpha login_submitvalidate form-control " readonly hidden>
    </div>
    <label id="URSRC_lbl_email_err" class="errormsg"></label>
</div>
<div class="form-group row">
    <label name="URT_SRC_lbl_pword" id="URT_SRC_lbl_pword" class="col-sm-2" hidden>
        PASSWORD <em>*</em>
    </label>
    <div class="col-sm-3">
        <input type="password" name="URT_SRC_tb_pword" placeholder="PassWord" id="URT_SRC_tb_pword" maxlength='30' class="login_submitvalidate form-control chk_password" hidden>
    </div>
    <label id="URSRC_lbl_passwrd_errupd" class="errormsg  col-sm-2"></label>
</div>
<div class="form-group row">
    <label name="URT_SRC_lbl_confirmpword" id="URT_SRC_lbl_confirmpword" class="col-sm-2" hidden>
        CONFIRM PASSWORD <em>*</em>
    </label>
    <div class="col-sm-3">
        <input type="text" name="URT_SRC_tb_confirmpword" placeholder="Confirm PassWord" id="URT_SRC_tb_confirmpword" maxlength='30' class="login_submitvalidate form-control chk_password" hidden>
    </div>
    <label id="URSRC_lbl_confirmpasswrd_errupd" class="errormsg  col-sm-2"></label>
</div>
<div class="form-group row">
    <label id="URSRC_lbl_emptype" class="col-sm-2" hidden>
        SELECT TYPE OF EMPLOYEE<em>*</em>
    </label>
    <div class="col-sm-3">
        <select class="form-control" id='URSRC_lb_selectemptype' name="URSRC_lb_selectemptype"  maxlength="40" hidden  >
            <option value='SELECT' selected="selected"> SELECT</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label id="URSRC_lbl_selectteam" class="col-sm-2" hidden>TEAM<em>*</em></label>
    <div class="col-sm-3"><select id='URSRC_lb_selectteam' name="URSRC_lb_selectteam"  maxlength="40" class="login_submitvalidate form-control" hidden  >
            <option value='SELECT' selected="selected"> SELECT</option>
        </select>
    </div><input type="button" value="ADD" class="btn btn-info login_submitvalidate " id="URSRC_btn_add" hidden>
</div>
<div class="form-group ">
    <div id="URT_SRC_tble_roles" > </div>
</div>
<div class="form-group row">
    <label name="URT_SRC_lbl_ndatepickerrejoin" id="URT_SRC_lbl_datepickerrejoin" class="srctitle col-sm-2"  hidden>
        SELECT A REJOIN DATE <em>*</em>
    </label>
    <div class="col-sm-10">
        <input type="text" name="URT_SRC_tb_ndatepickerrejoin" placeholder="Rejoin Date" id="URT_SRC_tb_datepickerrejoin" class="form-control URT_SRC_tb_rejoinndsearchdatepicker datemandtry" style="width:100px;" hidden>
    </div>
</div>
<!--<!--EMPLOYEE DETAILS-->
<div id="URSRC_table_employeetbl" hidden>
    <div class="form-group row">
        <label class="srctitle  col-sm-2"  name="URSRC_lbl_personnaldtls" id="URSRC_lbl_personnaldtls" style="white-space: nowrap!important;">
            PERSONAL DETAILS
        </label>
    </div>
    <div class="form-group row">
        <label name="URSRC_lbl_firstname" id="URSRC_lbl_firstname" class="col-sm-2">
            FIRST NAME <em>*</em>
        </label>
        <div class="col-sm-3">
            <input type="text" name="URSRC_tb_firstname" id="URSRC_tb_firstname" maxlength='30' class="autosizealph sizefix title_alpha login_submitvalidate form-control" >
        </div>
    </div>
    <div class="form-group row">
        <label name="URSRC_lbl_lastname" id="URSRC_lbl_lastname" class="col-sm-2">
            LAST NAME <em>*</em>
        </label>
        <div class="col-sm-3">
            <input type="text" name="URSRC_tb_lastname" id="URSRC_tb_lastname" maxlength='30' class="autosizealph sizefix title_alpha login_submitvalidate form-control">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2" name="URSRC_lbl_dob" id="URSRC_lbl_dob">DATE OF BIRTH<em>*</em></label>
        <div class="col-sm-5">
            <input type="text" name="URSRC_tb_dob" id="URSRC_tb_dob" class="datepickerdob datemandtry login_submitvalidate form-control" style="width:100px;">
        </div>
    </div>
    <div class="row form-group">
        <label name="URSRC_lbl_gender" id="URSRC_lbl_gender" class="col-sm-2">GENDER<em>*</em></label>
        <label class="radio-inline">
            <input type="radio" id="URSRC_rd_male"  name="URSRC_rd_gender" value="MALE" class="login_submitvalidate ">MALE
        </label>
        <label class="radio-inline">
            <input type="radio" name="URSRC_rd_gender" id="URSRC_rd_female"  value="FEMALE" class="login_submitvalidate  ">FEMALE
        </label>
    </div>
    <div class="form-group">
        <label name="URSRC_lbl_nric" id="URSRC_lbl_nric" class="col-sm-2">NRIC NO<em>*</em></label>
        <div class="col-sm-3"><input type="text" name="URSRC_tb_nric" id="URSRC_tb_nric" maxlength='10' class="alphanumericuppercse sizefix login_submitvalidate form-control check_nric" style="width:120px"></div>
        <!--        <label id="URSRC_lbl_invalidnric" name="URSRC_lbl_invalidnric" class="errormsg"></label>-->
    </div>
    <div class="form-group row">
        <label class="col-sm-2" name="URSRC_lbl_designation" id="URSRC_lbl_designation" style="white-space: nowrap!important;">DESIGNATION<em>*</em></label>
        <div class="col-sm-3">
            <input type="text" name="URSRC_tb_designation" id="URSRC_tb_designation" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate form-control">
        </div>
    </div>
    <div class="form-group">
        <label name="URSRC_lbl_emailid" id="URSRC_lbl_emailid" class="col-sm-2">EMAIL ID<em>*</em></label>
        <div class="col-sm-3"><input type="text" name="URSRC_tb_emailid" id="URSRC_tb_emailid" maxlength='50' placeholder="Email Id" class="login_submitvalidate form-control"></div>
        <div><label id="URSRC_lbl_email_error" class="errormsg"></label></div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2" name="URSRC_lbl_permobile" id="URSRC_lbl_permobile" style="white-space: nowrap!important;">PERSONAL MOBILE<em>*</em></label>
        <div class="col-sm-10">
            <input type="text" name="URSRC_tb_permobile" id="URSRC_tb_permobile"  maxlength='8' class="mobileno title_nos valid login_submitvalidate form-control" style="width:90px" >
        </div>
        <label id="URSRC_lbl_validnumber" name="URSRC_lbl_validnumber" class="errormsg"></label>
    </div>
    <div class="form-group row">
        <label class="col-sm-2" name="URSRC_lbl_kinname" id="URSRC_lbl_kinname" style="white-space: nowrap!important;">NEXT KIN NAME<em>*</em></label>
        <div class="col-sm-3">
            <input type="text" name="URSRC_tb_kinname" id="URSRC_tb_kinname" maxlength='30' class="autosizealph sizefix title_alpha login_submitvalidate form-control">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2" name="URSRC_lbl_relationhd" id="URSRC_lbl_relationhd">RELATION HOOD<em>*</em></label>
        <div class="col-sm-3">
            <input type="text" name="URSRC_tb_relationhd" id="URSRC_tb_relationhd" maxlength='30' class="autosizealph sizefix title_alpha login_submitvalidate form-control" >
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2" name="URSRC_lbl_mobile" id="URSRC_lbl_mobile">MOBILE NO<em>*</em></label>
        <div class="col-sm-3">
            <input type="text" name="URSRC_tb_mobile" id="URSRC_tb_mobile" class="mobileno title_nos valid login_submitvalidate form-control" maxlength='8' style="width:90px">
        </div>
        <label id="URSRC_lbl_validnumber1" name="URSRC_lbl_validnumber1" class="errormsg">
    </div>
    <div class="form-group row">
        <label class="col-sm-2" name="URSRC_lbl_addr" id="URSRC_lbl_addr" style="white-space: nowrap!important;">ADDRESS<em>*</em></label>
        <div class="col-sm-10">
            <textarea  rows="4" cols="50" name="URSRC_ta_addr" id="URSRC_ta_addr" class="maxlength login_submitvalidate textareaupd form-control"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="srctitle col-sm-2"  name="URSRC_lbl_bnkdtls" id="URSRC_lbl_bnkdtls" style="white-space: nowrap!important;">BANK DETAILS</label>
    </div>
    <div class="form-group row">
        <label class="col-sm-2" name="URSRC_lbl_bnkname" id="URSRC_lbl_bnkname" style="white-space: nowrap!important;">BANK NAME <em>*</em></label>
        <div class="col-sm-3">
            <input type="text" name="URSRC_tb_bnkname" id="URSRC_tb_bnkname" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate form-control" >
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2" name="URSRC_lbl_brnchname" id="URSRC_lbl_brnchname" style="white-space: nowrap!important;">BRANCH NAME <em>*</em></label>
        <div class="col-sm-3">
            <input type="text" name="URSRC_tb_brnchname" id="URSRC_tb_brnchname" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate form-control" >
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2" name="URSRC_lbl_accntname" id="URSRC_lbl_accntname" style="white-space: nowrap!important;">ACCOUNT NAME <em>*</em></label>
        <div class="col-sm-3">
            <input type="text" name="URSRC_tb_accntname" id="URSRC_tb_accntname" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate form-control" >
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2" name="URSRC_lbl_accntno" id="URSRC_lbl_accntno" style="white-space: nowrap!important;">ACCOUNT NUMBER <em>*</em></label>
        <div class="col-sm-3">
            <input type="text" name="URSRC_tb_accntno" id="URSRC_tb_accntno" maxlength='50' class=" sizefix accntno login_submitvalidate form-control" >
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2" name="URSRC_lbl_ifsccode" id="URSRC_lbl_ifsccode" style="white-space: nowrap!important;">IFSC CODE<em>*</em></label>
        <div class="col-sm-3">
            <input type="text" name="URSRC_tb_ifsccode" id="URSRC_tb_ifsccode" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate form-control" >
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2" name="URSRC_lbl_accntyp" id="URSRC_lbl_accntyp" style="white-space: nowrap!important;">ACCOUNT TYPE<em>*</em></label>
        <div class="col-sm-3">
            <input type="text" name="URSRC_tb_accntyp" id="URSRC_tb_accntyp" maxlength='15' class="alphanumericuppercse sizefix login_submitvalidate form-control" >
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2" name="URSRC_lbl_brnchaddr" id="URSRC_lbl_brnchaddr" style="white-space: nowrap!important;">BRANCH ADDRESS<em>*</em></label>
        <div class="col-sm-10">
            <textarea  rows="4" cols="50" name="URSRC_ta_brnchaddr" id="URSRC_ta_brnchaddr" class="maxlength login_submitvalidate textareaupd form-control"></textarea>
        </div>
    </div>
    <!--        <div class="form-group row">-->
    <div class="form-group row">
        <label class="col-sm-2" style="white-space: nowrap!important;" name="URSRC_lbl_comments" id="URSRC_lbl_comments">COMMENTS</label>
        <div class="col-sm-10">
            <textarea rows="4" cols="50" name="URSRC_ta_comments" id="URSRC_ta_comments" class="maxlength login_submitvalidate textareaupd form-control"></textarea>
        </div>
    </div>
<!--    <div>-->
<!--        <div ID="filetableuploads" class="form-group row">-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="">-->
<!--        <div id="attachprompt" class="col-sm-offset-2 col-sm-10"><img width="15" height="15" src="image/paperclip.gif" border="0" >-->
<!--            <a href="javascript:_addAttachmentFields('attachmentarea')" id="attachafile">Attach a file</a>-->
<!--        </div>-->
<!--    </div>-->
</div>
<!--    EMPL DETAILS-->
<div>
    <input type="button" value="REJOIN" id="URT_SRC_btn_rejoin" class="btn  btn-info"  hidden>
</div>
<div class="form-group row">
    <label class="srctitle col-sm-2" name="URT_SRC_lbl_nloginupdate" id="URT_SRC_lbl_loginupdate" class="srctitle  col-sm-2 "  hidden>
        LOGIN ID<em>*</em>
    </label>
    <div class="col-sm-5">
        <select name="URT_SRC_lb_nloginupdate" id="URT_SRC_lb_loginupdate" class="form-control" hidden> <option>SELECT</option></select>
    </div>

    <div class="col-sm-offset-2 ">
        <label id="URT_SRC_lbl_recordversion" class="srctitle" hidden >RECORD VERSION<em>*</em></label>
    </div>
    <div class="col-sm-offset-2 ">
        <select name="URT_SRC_lb_recordversion" id="URT_SRC_lb_recordversion" hidden ></select>
    </div>
</div>
<div class="form-group row">
    <label name="URT_SRC_lbl_ndatepickerupdate" id="URT_SRC_lbl_datepickerupdate" class="srctitle  col-sm-2" hidden >
        SELECT A END DATE <em>*</em>
    </label>
    <div class="col-sm-10">
        <input type="text" name="URT_SRC_tb_ndatepickerupdate" id="URT_SRC_tb_datepickerupdate" class="URT_SRC_tb_rejoinndsearchdatepicker datemandtry form-control " style="width:100px;" hidden>
    </div>
</div>
<div class="form-group row">
    <label name="URT_SRC_lbl_nreasonupdate" id="URT_SRC_lbl_reasonupdate" class="srctitle col-sm-2" hidden >
        REASON OF TERMINATION <em>*</em>
    </label>
    <div class="col-sm-10">
        <textarea name="URT_SRC_ta_nreasonupdate" id="URT_SRC_ta_reasonupdate" hidden class="form-control textareaupd" > </textarea>
    </div>
</div>
<div>
    <input type="button" value="UPDATE" id="URT_SRC_btn_update" class="btn  btn-info" hidden>
</div>
</form>
</div>
</div>
</div>
</body>
</html>


