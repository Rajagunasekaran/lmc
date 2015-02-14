<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************ACCESS_RIGHTS_TERMINATE_SEARCH_UPDATE*********************************************//
//VER 0.01-INITIAL VERSION, SD:03/03/2015 ED:03/03/2015,TRACKER NO:79 DESC:updated to update login id in search/update and sending email while create login id
//*********************************************************************************************************//-->
<?php
include "COMMON.php";
include "HEADER.php";
?>
<!--SCRIPT TAG START-->
<script>
//START READY FUNCTION
$(document).ready(function(){
    $('.preloader', window.parent.document).show();
    $('textarea').autogrow({onInitialize: true});
    //reomve file upload row
    $(document).on('click', 'button.removebutton', function () {
        $(this).closest('tr').remove();
        var rowCount = $('#filetableuploads tr').length;
        if(rowCount!=0)
        {
            $('#attachafile').text('Attach another file');
        }
        else
        {
            $('#attachafile').text('Attach a file');
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
                $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:js_errormsg_array[12],position:{top:1800,left:550}}});

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
        var tablerowCount = $('#filetableuploads tr').length;
        var uploadfileid="upload_filename"+tablerowCount;
        var appendfile='<tr><td ><input type="file" class="fileextensionchk" id='+uploadfileid+'></td><td><button type="button" class="removebutton" title="Remove this row" style="background-color:red;color:white;font-size:10;font-weight: bold;">Remove</button></td></tr></br>';
        $('#filetableuploads').append(appendfile);
        var rowCount = $('#filetableuploads tr').length;
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
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $('.preloader', window.parent.document).hide();
            var value_array=JSON.parse(xmlhttp.responseText);
            URT_SRC_terminate_array=value_array[1];
            js_errormsg_array=value_array[0];
            var URSRC_emptype_array=value_array[2];
            var URT_SRC_radio_role='';
            for (var i=0;i<URT_SRC_terminate_array.length;i++) {
                var id="URT_SRC_tble_table"+i;
                var id1="URT_SRC_terminate_array"+i;
                var value=URT_SRC_terminate_array[i][1].replace(" ","_")
                if(i==0)
                    var temp='SELECT ROLE ACCESS<em>*</em>'
                else
                    var temp=''
                URT_SRC_radio_role+='<tr ><td><label class="srctitle">'+temp+'</label></td><td width="195"><input type="radio" name="URT_SRC_radio_nrole" id='+id1+' value='+value+' class="URT_SRC_radio_clsrole"  />' + URT_SRC_terminate_array[i][1] + '</td></tr>';
            }
            $('#URT_SRC_tble_roles').html(URT_SRC_radio_role);
            var emp_type='<option value="SELECT">SELECT</option>';

            for(var k=0;k<URSRC_emptype_array.length;k++){
                emp_type += '<option value="' + URSRC_emptype_array[k] + '">' + URSRC_emptype_array[k] + '</option>';
            }
            $('#URSRC_lb_selectemptype').html(emp_type);
        }
    }
    var choice='USER_RIGHTS_TERMINATE';
    xmlhttp.open("POST","COMMON.do?option="+choice,true);
    xmlhttp.send();
    //DO VALIDATION PART
    //emp
    $(".mobileno").doValidation({rule:'numbersonly',prop:{realpart:10,leadzero:true}});
    $(".accntno").doValidation({rule:'numbersonly',prop:{leadzero:true,autosize:true}});
    $(".alphanumeric").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:false,autosize:true}});
    $(".alphanumericuppercse").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:true,autosize:true}});
    //END VALIDATION PART
    //CLICK FUNCTION FOR TERMINATION BTN
    $(document).on('click','#URT_SRC_btn_termination',function(){

        $('.preloader',window.parent.document).show();
        var URT_SRC_empname=$("#URT_SRC_lb_loginterminate option:selected").text();
        var URT_loginid=$('#URT_SRC_lb_loginrejoin').val();
        var loggin=$("#URT_SRC_lb_loginterminate").val();
        var formElement = document.getElementById("URT_SRC_form_terminatesearchupdate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader',window.parent.document).hide();
                var msg_alert=JSON.parse(xmlhttp.responseText);
                var success_flag=msg_alert[0];
                var ss_flag=msg_alert[1];
                var cal_flag=msg_alert[2];
                if((success_flag==1)&&(ss_flag==1)&&(cal_flag==1)){
                    var msg=js_errormsg_array[1].toString().replace("[LOGIN ID]",URT_SRC_empname);
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:msg,position:{top:150,left:550}}});
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
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:js_errormsg_array[6],position:{top:150,left:550}}});
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
                    var msg= js_errormsg_array[8].replace("[SSID]",fileid)
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:msg,position:{top:150,left:550}}});

                }
                if((success_flag==1)&&(ss_flag==1)&&(cal_flag==0)){
                    var msg= js_errormsg_array[9];
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:msg,position:{top:150,left:550}}});

                }
            }
        }
        var choice='TERMINATE';
        xmlhttp.open("POST","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?option="+choice+"&loggin="+loggin);
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

        $('.preloader',window.parent.document).show();
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

                $('.preloader',window.parent.document).hide();
                var msg_alert=JSON.parse(xmlhttp.responseText);
                var success_flag=msg_alert[0];
                var ss_flag=msg_alert[1];
                var cal_flag=msg_alert[2];
                var file_flag=msg_alert[4];
                var folder_id=msg_alert[5];
                if((success_flag==1)&&(ss_flag==1)&&(cal_flag==1)){
                    var loggin=$("#URT_SRC_lb_loginrejoin").val();
                    var msg=js_errormsg_array[2].toString().replace("[LOGIN ID]",URT_loginid_val);
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:msg,position:{top:150,left:550}}});
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
                    $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
                    $("input[name=URT_SRC_radio_nselectoption]:checked").attr('checked',false);
                    $("#filetableuploads tr").remove();
                    $('#attachafile').text('Attach a file');
                }
                else if(success_flag==0){
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:js_errormsg_array[6],position:{top:150,left:550}}});
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
                    $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
                    $("input[name=URT_SRC_radio_nselectoption]:checked").attr('checked',false);
                }
                else if((success_flag==1)&&(ss_flag==0)){
                    var fileid=msg_alert[3];
                    var msg= js_errormsg_array[8].replace("[SSID]",fileid)
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:msg,position:{top:150,left:550}}});
//                    $("#URT_SRC_lbl_datepickerrejoin").hide();
//                    $("#URT_SRC_lbl_loginrejoin").show();
//                    $("#URT_SRC_tble_roles").hide();
//                    $('#URSRC_table_employeetbl').hide();
//                    $("#URT_SRC_lb_loginupdate").hide();
//                    $("#URT_SRC_tb_datepickerrejoin").hide();
//                    $("#URT_SRC_btn_rejoin").hide();
//                    $('#URT_SRC_lbl_loginupdate').hide();
//                    $('#URT_SRC_lbl_datepickerupdate').hide();
//                    $('#URT_SRC_tb_datepickerupdate').hide();
//                    $('#URT_SRC_lbl_reasonupdate').hide();
//                    $('#URT_SRC_ta_reasonupdate').hide();
//                    $('#URT_SRC_btn_update').hide();
//                    $('#URT_SRC_radio_selectrejoin').hide();
//                    $('#URT_SRC_radio_selectsearchupdate').hide()
//                    $('#URT_SRC_lb_loginrejoin').hide();
//                    $('#URT_SRC_lbl_loginrejoin').hide();
//                    $('#URT_SRC_lbl_selectsearchupdate').hide();
//                    $('#URT_SRC_lbl_selectrejoin').hide();
//                    $('#URT_SRC_lbl_selectoption').hide();
//                    $("#URSRC_lbl_emptype").hide();
//                    $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
//                    $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
//                    $("input[name=URT_SRC_radio_nselectoption]:checked").attr('checked',false);
                }
                else if((success_flag==1)&&(ss_flag==1)&&(file_flag==0)){

                    var msg=js_errormsg_array[11].replace("[FID]",folder_id);
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:msg ,position:{top:150,left:500}}});

                }
                else if((success_flag==1)&&(ss_flag==1)&&(cal_flag==0)){
                    var msg= js_errormsg_array[9];
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:msg,position:{top:150,left:550}}});
//                    $("#URT_SRC_lbl_datepickerrejoin").hide();
//                    $("#URT_SRC_lbl_loginrejoin").show();
//                    $("#URT_SRC_tble_roles").hide();
//                    $('#URSRC_table_employeetbl').hide();
//                    $("#URT_SRC_lb_loginupdate").hide();
//                    $("#URT_SRC_tb_datepickerrejoin").hide();
//                    $("#URT_SRC_btn_rejoin").hide();
//                    $('#URT_SRC_lbl_loginupdate').hide();
//                    $('#URT_SRC_lbl_datepickerupdate').hide();
//                    $('#URT_SRC_tb_datepickerupdate').hide();
//                    $('#URT_SRC_lbl_reasonupdate').hide();
//                    $('#URT_SRC_ta_reasonupdate').hide();
//                    $('#URT_SRC_btn_update').hide();
//                    $('#URT_SRC_radio_selectrejoin').hide();
//                    $('#URT_SRC_radio_selectsearchupdate').hide()
//                    $('#URT_SRC_lb_loginrejoin').hide();
//                    $('#URT_SRC_lbl_loginrejoin').hide();
//                    $('#URT_SRC_lbl_selectsearchupdate').hide();
//                    $('#URT_SRC_lbl_selectrejoin').hide();
//                    $('#URT_SRC_lbl_selectoption').hide();
//                    $("#URSRC_lbl_emptype").hide();
//                    $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);;
//                    $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
//                    $("input[name=URT_SRC_radio_nselectoption]:checked").attr('checked',false);
                }
            }
        }
        var option='REJOIN';
        xmlhttp.open("POST","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?option="+option+"&login_id="+login_id+"&filearray="+filenames,true);
        xmlhttp.send(new FormData(formElement));

    });
    $(document).on('click','#URT_SRC_btn_update',function(){
        $('.preloader', window.parent.document).show();
        var URT_SRC_loggin=$("#URT_SRC_lb_loginupdate").val();
        var URT_SRC_empname_upd=$("#URT_SRC_lb_loginupdate option:selected").text();
        var formElement = document.getElementById("URT_SRC_form_terminatesearchupdate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
                var msg_alert=xmlhttp.responseText;
                if(msg_alert==1){
                    var loggin=$("#URT_SRC_lb_loginupdate").val();
                    var msg=js_errormsg_array[0].toString().replace("[LOGIN ID]",URT_SRC_empname_upd);
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:msg,position:{top:150,left:550}}});
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
        xmlhttp.open("POST","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?option="+choice+"&URT_SRC_loggin="+URT_SRC_loggin,true);
        xmlhttp.send(new FormData(formElement));
    });
    $('#URT_SRC_lb_loginupdate').change(function(){
        $('#URT_SRC_lb_recordversion').hide();
        $('#URT_SRC_lbl_recordversion').hide();
        $('.preloader', window.parent.document).show();
        var URT_SRC_loggin=$(this).val();
        var URT_SRC_empname_upd=$("#URT_SRC_lb_loginupdate option:selected").text();
        var recver_array=[];
        if(URT_SRC_empname_upd !="SELECT"){
            $('#URT_SRC_lb_recordversion').hide();
            $('#URT_SRC_lbl_recordversion').hide();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader', window.parent.document).hide();
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
            $('.preloader', window.parent.document).hide();
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
            $('.preloader', window.parent.document).hide();
        }
        var option='FETCH';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option,true);
        xmlhttp.send();
    });
    //CHANGE FUNCTION FOR RECORD VERSION
    $('#URT_SRC_lb_recordversion').change(function(){
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        var recver= $('#URT_SRC_lb_recordversion').val();
        var URT_SRC_loggin=$('#URT_SRC_lb_loginupdate').val();
        if(recver!='SELECT'){
            $('.preloader', window.parent.document).show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader', window.parent.document).hide();
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
            xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option+"&recver="+recver,true);
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
        $('.preloader', window.parent.document).show();
        var URT_SRC_loggin=$(this).val();
        if(URT_SRC_loggin !=""){
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader', window.parent.document).hide();
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
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option,true);
        xmlhttp.send();
    });
    var err_flag=0;
    $('#URT_SRC_tb_datepickertermination').change(function(){
        $('.preloader', window.parent.document).show();
        var URT_SRC_loggin=$('#URT_SRC_lb_loginterminate').val();
        var date_value=$('#URT_SRC_tb_datepickertermination').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
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
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option+"&date_value="+date_value,true);
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
        }
        else
        {
            $("#URT_SRC_tble_roles").show();
            $('#URSRC_table_employeetbl').show();
            $("#URT_SRC_tb_datepickerrejoin").val('').show();
            $("#URT_SRC_lbl_datepickerrejoin").show();
            $("#URT_SRC_btn_rejoin").show();
            $("#URSRC_lbl_emptype").show();
            $('#URSRC_lb_selectemptype').show();
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
            $('.preloader', window.parent.document).show();
            var URT_SRC_loggin=$(this).val();
            if(URT_loginid_val !=""){
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                        $('.preloader', window.parent.document).hide();
                        var values_array=JSON.parse(xmlhttp.responseText);
                        var min_date=values_array[0][1];
                        var firstname=values_array[0][0].firstname;
                        var lastname=values_array[0][0].lastname;
                        var dob=values_array[0][0].dob;
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
                        var laptop=values_array[0][0].laptop;
                        var chargerno=values_array[0][0].chargerno;
                        var bag=values_array[0][0].bag;
                        var mouse=values_array[0][0].mouse;
                        var dooraccess=values_array[0][0].dooraccess;
                        var idcard=values_array[0][0].idcard;
                        var headset=values_array[0][0].headset;
                        var bankname=values_array[0][0].bankname;
                        var branchname=values_array[0][0].branchname;
                        var accountname=values_array[0][0].accountname;
                        var accountno=values_array[0][0].accountno;
                        var ifsccode=values_array[0][0].ifsccode;
                        var accountype=values_array[0][0].accountype;
                        var branchaddr=values_array[0][0].branchaddress;
                        var aadharno=values_array[0][0].URSRC_aadhar;
                        var passportno=values_array[0][0].URSRC_passport;
                        var votersid=values_array[0][0].URSRC_voterid;
                        var comments=values_array[0][0].URSRC_comments;
                        var mindate=min_date.toString().split('-');
                        var month=mindate[1]-1;
                        var year=mindate[2];
                        var date=parseInt(mindate[0])+1;
                        var minimumdate = new Date(year,month,date);
                        $('#URT_SRC_tb_datepickerrejoin').datepicker("option","minDate",minimumdate);
                        $('#URT_SRC_tb_datepickerrejoin').datepicker("option","maxDate",new Date());
                        $('#URSRC_table_employeetbl').show();
                        $('#URSRC_table_others').show();
                        var emp_firstname=firstname.length;
                        $('#URSRC_tb_firstname').val(firstname).attr("size",emp_firstname+3);
                        var emp_lastname=lastname.length;
                        $('#URSRC_tb_lastname').val(lastname).attr("size",emp_lastname+3);
                        $('#URSRC_tb_dob').val(dob);
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
                        if(laptop!=null){
                        var emp_laptop=laptop.length;
                        $('#URSRC_tb_laptopno').val(laptop).attr("size",emp_laptop+2);
                        }
                        if(chargerno!=null){
                        var emp_cahrgerno=chargerno.length;
                        $('#URSRC_tb_chargerno').val(chargerno).attr("size",emp_cahrgerno+1);
                        }
                        $('#URSRC_ta_comments').val(comments);
                        if(bag=='X')
                        {
                            $('#URSRC_chk_bag').attr('checked',true);
                        }
                        else
                        {
                            $('#URSRC_chk_bag').attr('checked',false);
                        }
                        if(mouse=='X')
                        {
                            $('#URSRC_chk_mouse').attr('checked',true);
                        }
                        else
                        {
                            $('#URSRC_chk_mouse').attr('checked',false);
                        }
                        if(dooraccess=='X')
                        {
                            $('#URSRC_chk_dracess').attr('checked',true);
                        }
                        else
                        {
                            $('#URSRC_chk_dracess').attr('checked',false);
                        }
                        if(idcard=='X')
                        {
                            $('#URSRC_chk_idcrd').attr('checked',true);
                        }
                        else
                        {
                            $('#URSRC_chk_idcrd').attr('checked',false);
                        }
                        if(headset=='X')
                        {
                            $('#URSRC_chk_headset').attr('checked',true);
                        }
                        else
                        {
                            $('#URSRC_chk_headset').attr('checked',false);
                        }
                        if(aadharno!=null)
                        {
                            $('#URSRC_chk_aadharno').attr('checked',true);
                            var emp_aadharno=aadharno.length;
                            $('#URSRC_tb_aadharno').val(aadharno).show().attr("size",emp_aadharno);
                        }
                        else
                        {
                            $('#URSRC_chk_aadharno').attr('checked',false);
                            $('#URSRC_tb_aadharno').val('').hide();
                        }
                        if(passportno!=null)
                        {
                            $('#URSRC_chk_passportno').attr('checked',true);
                            var emp_passportno=passportno.length;
                            $('#URSRC_tb_passportno').val(passportno).show().attr("size",emp_passportno);
                        }
                        else
                        {
                            $('#URSRC_chk_passportno').attr('checked',false);
                            $('#URSRC_tb_passportno').val('').hide();
                        }
                        if(votersid!=null)
                        {
                            $('#URSRC_chk_votersid').attr('checked',true);
                            var emp_votersid=votersid.length;
                            $('#URSRC_tb_votersid').val(votersid).show().attr("size",emp_votersid);
                        }
                        else
                        {
                            $('#URSRC_chk_votersid').attr('checked',false);
                            $('#URSRC_tb_votersid').val('').hide();
                        }
                    }
                }
            }
        }
        var option='GETENDDATE';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option,true);
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
        $('.preloader', window.parent.document).show();
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
                $('.preloader', window.parent.document).hide();
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
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:js_errormsg_array[3],position:{top:150,left:500}}});
                }
            }
        }
        var option='TERMINATIONLB';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?radio_value_loginidsearch="+radio_value_loginidsearch+"&option="+option,true);
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
//        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        $('.preloader', window.parent.document).show();
        var radio_value_loginidsearch=$(this).val();
        $('#URT_SRC_lb_recordversion').hide();
        $('#URT_SRC_lbl_recordversion').hide();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
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
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:js_errormsg_array[4],position:{top:150,left:500}}});
                }
            }
        }
        var option='REJOINLB';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?radio_value_loginidsearch="+radio_value_loginidsearch+"&option="+option,true);
        xmlhttp.send();
    });
    //CLICK FUNCTION FOR RADIO SEARCH ND UPDATE BTN
    $('#URT_SRC_radio_selectsearchupdate').click(function(){
//        $("html, body").animate({ scrollTop: $(document).height() }, "fast");
        $('.preloader', window.parent.document).show();
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
                $('.preloader', window.parent.document).hide();
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
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:js_errormsg_array[5],position:{top:150,left:500}}});
                    $('#URT_SRC_lbl_loginupdate').hide();
                    $('#URT_SRC_lb_loginupdate').hide();
                }
            }
        }
        var option='SEARCHLB';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?radio_value_loginidsearch="+radio_value_loginidsearch+"&option="+option,true);
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
        $("#URT_SRC_tb_datepickerrejoin").hide();
        $("#URT_SRC_lbl_datepickerrejoin").hide();
        $("#URT_SRC_btn_rejoin").hide();
        $("#URT_SRC_lbl_loginupdate").show();
        $('#URT_SRC_lbl_datepickerupdate').hide();
        $('#URT_SRC_tb_datepickerupdate').hide();
        $('#URT_SRC_lbl_reasonupdate').hide();
        $('#URT_SRC_ta_reasonupdate').hide();
        $('#URT_SRC_btn_update').hide();
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
    $(document).on('change','#URT_SRC_form_terminatesearchupdate',function(){
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
            if(Selectedsearchradiooption=='URT_SRC_radio_valuerejoin')
            {
                if(($("#URT_SRC_lbl_loginupdate").val()!='SELECT') &&($('#URSRC_lb_selectemptype').val()!='SELECT') && ($("#URT_SRC_tb_datepickerrejoin").val()!="")&& ($("input[name=URT_SRC_radio_nrole]").is(":checked")==true)&&(URSRC_Firstname!='') && (URSRC_Lastname!='' ) && (URSRC_tb_dob!='' ) && (URSRC_empdesig!='' )&&( URSRC_Mobileno!='' && (parseInt($('#URSRC_tb_permobile').val())!=0)) && (URSRC_kinname!='')&& (URSRC_relationhd!='' )&& (URSRC_Mobileno.length>=10)&&(URSRC_mobile.length>=10 )&&(URSRC_brnchaddr!="")&&(URSRC_accttyp!="")&&(URSRC_ifsc!="")&&(URSRC_acctno!="")&&(URSRC_accname!="")&&(URSRC_tb_brnname!="")&&(URSRC_bnkname!=""))
                {
                    $("#URT_SRC_btn_rejoin").removeAttr("disabled");
                    if(($("input[name=URSRC_chk_aadharno]").is(":checked")==true)||($("input[name=URSRC_chk_votersid]").is(":checked")==true)||($("input[name=URSRC_chk_passportno]").is(":checked")==true)){
                        if((URT_SRC_aadharno=='' && $("input[name=URSRC_chk_aadharno]").is(":checked")==true) ||(URT_SRC_passportnono=='' && $("input[name=URSRC_chk_passportno]").is(":checked")==true)||(URT_SRC_votersidno=='' && $("input[name=URSRC_chk_votersid]").is(":checked")==true))
                            $("#URT_SRC_btn_rejoin").attr("disabled", "disabled");
                        else
                            $("#URT_SRC_btn_rejoin").removeAttr("disabled");
                    }

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
        if(URSRC_Mobilenoval.length==10)
        {
            if(URSRC_Mobileno=='URSRC_tb_permobile')
                $('#URSRC_lbl_validnumber').hide();
            else
                $('#URSRC_lbl_validnumber1').hide();
        }
        else
        {
            if(URSRC_Mobileno=='URSRC_tb_permobile')
                $('#URSRC_lbl_validnumber').text(js_errormsg_array[7]).show();
            else
                $('#URSRC_lbl_validnumber1').text(js_errormsg_array[7]).show();
        }
    });
});
</script>
<body>
<div class="wrapper">
<div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
<div class="title" id="fhead" ><div style="padding-left:500px; text-align:left;"><p><h3>ACCESS RIGHTS:TERMINATE SEARCH/UPDATE</h3><p></div></div>
<form id="URT_SRC_form_terminatesearchupdate"  class ='content'>
<table>
<tr> <td> <input type="radio" name="URT_SRC_radio_nterminndupdatesearch" id="URT_SRC_radio_logintermination"  value="URT_SRC_radio_valuelogintermination" ><label name="URT_SRC_lbl_nlogintermination" id="URT_SRC_lbl_logintermination" hidden>LOGIN TERMINATION</label></td> </tr>
<tr> <td> <input type="radio" name="URT_SRC_radio_nterminndupdatesearch" id="URT_SRC_radio_loginsearchupdate" value="URT_SRC_radio_valueloginsearchupdate" ><label name="URT_SRC_lbl_nloginsearchupdate" id="URT_SRC_lbl_loginsearchupdate"  hidden>SEARCH/UPDATE</label></td></tr>
<!--URT_SRC_radio_nterminndupdatesearch termination-->
<tr> <td> <label name="URT_SRC_lbl_nloginterminate" id="URT_SRC_lbl_loginterminate" class="srctitle" hidden>EMPLOYEE NAME<em>*</em> </label></td></tr>
<tr> <td> <select name="URT_SRC_lb_nloginterminate" id="URT_SRC_lb_loginterminate" hidden> <option>SELECT</option></select></td></tr>
<tr><td><table>
            <tr> <td> <label name="URT_SRC_lbl_datepickertermination" id="URT_SRC_lbl_datepickertermination" class="srctitle" hidden> SELECT A END DATE <em>*</em> </label> </td> </tr>
            <tr> <td> <input type="text" name="URT_SRC_tb_ndatepickertermination" id="URT_SRC_tb_datepickertermination" class="URT_SRC_tb_termindatepickerclass datemandtry" style="width:75px;" hidden></td><td><label id="URT_SRC_errdate" name="URT_SRC_errdate" class="errormsg"></label></td></tr>
        </table></td></tr>
<tr> <td> <label name="URT_SRC_lbl_nreasontermination" id="URT_SRC_lbl_reasontermination" class="srctitle" hidden> REASON OF TERMINATION <em>*</em> </label> </td> </tr>
<tr> <td> <textarea name="URT_SRC_ta_nreasontermination" id="URT_SRC_ta_reasontermination" hidden> </textarea> </td></td> </tr>
<tr> <td> <input type="button"  value="TERMINATE" id="URT_SRC_btn_termination" class="maxbtn" hidden> </td></tr>
<!--select an option-->
<tr> <td> <label name="URT_SRC_lbl_nselectoption" id="URT_SRC_lbl_selectoption" class="srctitle" hidden> SELECT A OPTION </label></td></tr>
<tr> <td> <input type="radio" name="URT_SRC_radio_nselectoption" id="URT_SRC_radio_selectrejoin"    value="URT_SRC_radio_valuerejoin" hidden> <label name="URT_SRC_lbl_nselectrejoin" id="URT_SRC_lbl_selectrejoin"  hidden> REJOIN </label></td></tr>
<tr> <td> <input type="radio" name="URT_SRC_radio_nselectoption" id="URT_SRC_radio_selectsearchupdate" hidden><label name="URT_SRC_lbl_nselectsearchupdate" id="URT_SRC_lbl_selectsearchupdate"  hidden> SEARCH/UPDATE </label></td></tr>
<!--terminate rejoin-->
<tr> <td> <label name="URT_SRC_lbl_nloginrejoin" id="URT_SRC_lbl_loginrejoin" class="srctitle" hidden>EMPLOYEE NAME<em>*</em> </label> </td>
    <td> <select name="URT_SRC_lb_nloginrejoin" id="URT_SRC_lb_loginrejoin"  hidden > <option>SELECT</option> </select></td></tr>
<table>
    <tr>
        <td width="185"><label id="URSRC_lbl_emptype" hidden>SELECT TYPE OF EMPLOYEE<em>*</em></label></td>
        <td><select id='URSRC_lb_selectemptype' name="URSRC_lb_selectemptype"  maxlength="40" hidden  >
                <option value='SELECT' selected="selected"> SELECT</option>
            </select></td></tr>
</table>
<tr> <td> <table id="URT_SRC_tble_roles"> </table></td></tr>
<tr> <table><td> <label name="URT_SRC_lbl_ndatepickerrejoin" id="URT_SRC_lbl_datepickerrejoin" class=" srctitle" hidden> SELECT A REJOIN DATE <em>*</em> </label> </td>
        <td width="185"> <input type="text" name="URT_SRC_tb_ndatepickerrejoin" id="URT_SRC_tb_datepickerrejoin" class="URT_SRC_tb_rejoinndsearchdatepicker datemandtry" style="width:75px;" hidden></td></table></tr>
<!--EMPLOYEE DETAILS-->
<table id="URSRC_table_employeetbl" hidden>
    <tr>
        <td><label class="srctitle"  name="URSRC_lbl_personnaldtls" id="URSRC_lbl_personnaldtls">PERSONAL DETAILS</label></td>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_firstname" id="URSRC_lbl_firstname">FIRST NAME <em>*</em></label></td>
        <td><input type="text" name="URSRC_tb_firstname" id="URSRC_tb_firstname" maxlength='30' class="autosizealph sizefix title_alpha login_submitvalidate" ></td>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_lastname" id="URSRC_lbl_lastname">LAST NAME <em>*</em></label></td>
        <td><input type="text" name="URSRC_tb_lastname" id="URSRC_tb_lastname" maxlength='30' class="autosizealph sizefix title_alpha login_submitvalidate"></td>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_dob" id="URSRC_lbl_dob">DATE OF BIRTH<em>*</em></label></td>
        <td><input type="text" name="URSRC_tb_dob" id="URSRC_tb_dob" class="datepickerdob datemandtry login_submitvalidate" style="width:75px;"></td>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_gender" id="URSRC_lbl_gender">GENDER<em>*</em></label></td>
        <td> <input type="radio" name="URSRC_rd_gender" id="URSRC_rd_male"  value="MALE" class="login_submitvalidate"><label name="URSRC_lbl_gender" id="URSRC_lbl_gender" >MALE</label>
            <input type="radio" name="URSRC_rd_gender" id="URSRC_rd_female"  value="FEMALE" class="login_submitvalidate"><label name="URSRC_lbl_gender" id="URSRC_lbl_gender" >FEMALE</label></td> </tr>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_designation" id="URSRC_lbl_designation">DESIGNATION<em>*</em></label></td>
        <td><input type="text" name="URSRC_tb_designation" id="URSRC_tb_designation" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate"></td>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_permobile" id="URSRC_lbl_permobile">PERSONAL MOBILE<em>*</em></label></td>
        <td><input type="text" name="URSRC_tb_permobile" id="URSRC_tb_permobile"  maxlength='10' class="mobileno title_nos valid login_submitvalidate" style="width:75px" >
            <label id="URSRC_lbl_validnumber" name="URSRC_lbl_validnumber" class="errormsg"></label></td>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_kinname" id="URSRC_lbl_kinname">NEXT KIN NAME<em>*</em></label></td>
        <td><input type="text" name="URSRC_tb_kinname" id="URSRC_tb_kinname" maxlength='30' class="autosizealph sizefix title_alpha login_submitvalidate"></td>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_relationhd" id="URSRC_lbl_relationhd">RELATION HOOD<em>*</em></label></td>
        <td><input type="text" name="URSRC_tb_relationhd" id="URSRC_tb_relationhd" maxlength='30' class="autosizealph sizefix title_alpha login_submitvalidate" ></td>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_mobile" id="URSRC_lbl_mobile">MOBILE NO<em>*</em></label></td>
        <td><input type="text" name="URSRC_tb_mobile" id="URSRC_tb_mobile" class="mobileno title_nos valid login_submitvalidate" maxlength='10' style="width:75px">
            <label id="URSRC_lbl_validnumber1" name="URSRC_lbl_validnumber1" class="errormsg"></label></td>
    </tr>
    <tr>
        <td><label class="srctitle"  name="URSRC_lbl_bnkdtls" id="URSRC_lbl_bnkdtls">BANK DETAILS</label></td>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_bnkname" id="URSRC_lbl_bnkname">BANK NAME <em>*</em></label></td>
        <td><input type="text" name="URSRC_tb_bnkname" id="URSRC_tb_bnkname" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate" ></td>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_brnchname" id="URSRC_lbl_brnchname">BRANCH NAME <em>*</em></label></td>
        <td><input type="text" name="URSRC_tb_brnchname" id="URSRC_tb_brnchname" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate" ></td>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_accntname" id="URSRC_lbl_accntname">ACCOUNT NAME <em>*</em></label></td>
        <td><input type="text" name="URSRC_tb_accntname" id="URSRC_tb_accntname" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate" ></td>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_accntno" id="URSRC_lbl_accntno">ACCOUNT NUMBER <em>*</em></label></td>
        <td><input type="text" name="URSRC_tb_accntno" id="URSRC_tb_accntno" maxlength='50' class=" sizefix accntno login_submitvalidate" ></td>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_ifsccode" id="URSRC_lbl_ifsccode">IFSC CODE<em>*</em></label></td>
        <td><input type="text" name="URSRC_tb_ifsccode" id="URSRC_tb_ifsccode" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate" ></td>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_accntyp" id="URSRC_lbl_accntyp">ACCOUNT TYPE<em>*</em></label></td>
        <td><input type="text" name="URSRC_tb_accntyp" id="URSRC_tb_accntyp" maxlength='15' class="alphanumericuppercse sizefix login_submitvalidate" ></td>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_brnchaddr" id="URSRC_lbl_brnchaddr">BRANCH ADDRESS<em>*</em></label></td>
        <td><textarea rows="4" cols="50" name="URSRC_ta_brnchaddr" id="URSRC_ta_brnchaddr" class="maxlength login_submitvalidate"></textarea></td>
    </tr>
    <tr>
        <td><label class="srctitle"  name="URSRC_lbl_others" id="URSRC_lbl_others">OTHERS</label></td>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_laptopno" id="URSRC_lbl_laptopno">LAPTOP NUMBER</label></td>
        <td><input type="text" name="URSRC_tb_laptopno" id="URSRC_tb_laptopno" maxlength='25' class="alphanumeric sizefix login_submitvalidate"></td>
    </tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_laptopno" id="URSRC_lbl_laptopno">CHARGER NO</label></td>
        <td><input type="text" name="URSRC_tb_chargerno" id="URSRC_tb_chargerno" maxlength='25' class="alphanumeric sizefix login_submitvalidate"></td>
    </tr>
    <tr><td></td><td>
            <table id="URSRC_table_others" style="width:500px" hidden>
                <tr>
                    <td>
                        <input type="checkbox" name="URSRC_chk_bag" id="URSRC_chk_bag" class="login_submitvalidate">
                        <label name="URSRC_lbl_laptopbag" id="URSRC_lbl_laptopbag">LAPTOP BAG</label></td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="URSRC_chk_mouse" id="URSRC_chk_mouse" class="login_submitvalidate">
                        <label name="URSRC_lbl_laptopno" id="URSRC_lbl_laptopno">MOUSE</label></td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="URSRC_chk_dracess" id="URSRC_chk_dracess"  class="login_submitvalidate">
                        <label name="URSRC_lbl_dracess" id="URSRC_lbl_dracess">DOOR ACCESS</label></td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="URSRC_chk_idcrd" id="URSRC_chk_idcrd" class="login_submitvalidate">
                        <label name="URSRC_lbl_idcrd" id="URSRC_lbl_idcrd">ID CARD</label></td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="URSRC_chk_headset" id="URSRC_chk_headset" class="login_submitvalidate">
                        <label name="URSRC_lbl_headset" id="URSRC_lbl_headset">HEAD SET</label></td>
                </tr>
                <tr>
                    <td width="375">
                        <input type="checkbox" name="URSRC_chk_aadharno" id="URSRC_chk_aadharno" class="login_submitvalidate">
                        <label name="URSRC_lbl_aadharno" id="URSRC_lbl_aadharno">AADHAAR NO</label><input type="text" name="URSRC_tb_aadharno" id="URSRC_tb_aadharno" maxlength='15' class=" sizefix login_submitvalidate" hidden></td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="URSRC_chk_passportno" id="URSRC_chk_passportno" class="login_submitvalidate">
                        <label name="URSRC_lbl_passportno" id="URSRC_lbl_passportno">PASSPORT NO</label><input type="text" name="URSRC_tb_passportno" id="URSRC_tb_passportno" maxlength='15' class="alphanumeric sizefix login_submitvalidate" hidden></td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="URSRC_chk_votersid" id="URSRC_chk_votersid" class="login_submitvalidate">
                        <label name="URSRC_lbl_votersid" id="URSRC_lbl_votersid">VOTERS ID NO</label><input type="text" name="URSRC_tb_votersid" id="URSRC_tb_votersid" maxlength='25' class="alphanumeric sizefix login_submitvalidate" hidden></td>
                </tr>
            </table></td></tr>
    <tr>
        <td width="175">
            <label name="URSRC_lbl_comments" id="URSRC_lbl_comments">COMMENTS</label></td>
        <td><textarea rows="4" cols="50" name="URSRC_ta_comments" id="URSRC_ta_comments" class="maxlength login_submitvalidate"></textarea></td>
    </tr>
    <tr>
        <td> </td>
        <td>
            <table ID="filetableuploads">

            </table>
        </td>
    </tr>
    <tr>
        <td><label></label></td>
        <td>
                        <span id="attachprompt"><img width="15" height="15" src="https://ssl.gstatic.com/codesite/ph/images/paperclip.gif" border="0">
                        <a href="javascript:_addAttachmentFields('attachmentarea')" id="attachafile">Attach a file</a>
                        </span>
        </td>
    </tr>
</table>
<table>
    <!--EMPL DETAILS-->
    <tr> <td> <input type="button" value="REJOIN" id="URT_SRC_btn_rejoin" class="btn"  hidden> </td></tr>
    <!--terminate updation-->
    <tr> <td> <label name="URT_SRC_lbl_nloginupdate" id="URT_SRC_lbl_loginupdate" class="srctitle " hidden>LOGIN ID<em>*</em> </label></td></tr>
    <tr> <td> <select name="URT_SRC_lb_nloginupdate" id="URT_SRC_lb_loginupdate" hidden> <option>SELECT</option></select>
            <label id="URT_SRC_lbl_recordversion" class="srctitle" hidden >RECORD VERSION<em>*</em></label></td><td><select name="URT_SRC_lb_recordversion" id="URT_SRC_lb_recordversion" hidden ></select></td></tr>
    <tr> <td> <label name="URT_SRC_lbl_ndatepickerupdate" id="URT_SRC_lbl_datepickerupdate" class=" srctitle" hidden> SELECT A END DATE <em>*</em> </label> </td> </tr>
    <tr> <td> <input type="text" name="URT_SRC_tb_ndatepickerupdate" id="URT_SRC_tb_datepickerupdate" class="URT_SRC_tb_rejoinndsearchdatepicker datemandtry" style="width:75px;" hidden></td></tr>
    <tr> <td> <label name="URT_SRC_lbl_nreasonupdate" id="URT_SRC_lbl_reasonupdate" class=" srctitle"hidden> REASON OF TERMINATION <em>*</em> </label> </td> </tr>
    <tr> <td> <textarea name="URT_SRC_ta_nreasonupdate" id="URT_SRC_ta_reasonupdate" hidden> </textarea> </td> </tr>
    <tr> <td> <input type="button" value="UPDATE" id="URT_SRC_btn_update" class="btn" hidden> </td></tr>
</table>
</form>
</body>
</html>