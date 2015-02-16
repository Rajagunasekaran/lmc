<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************DAILY REPORTS USER SEARCH UPDATE **************************************//
//DONE BY:RAJA
//VER 0.11-SD:10/01/2015 ED:10/01/2015, TRACKER NO:74,DESC:CHANGED PRELOADER POSITION AND IMPLEMENTED AUTOFOCUS
//DONE BY:RAJA
//VER 0.10-SD:03/01/2015 ED:07/01/2015, TRACKER NO:74,DESC:IMPLEMENTED PDF BUTTON AND VALIDATED AND GAVE INPUT TO DB, CHANGED LOGIN ID AS EMPLOYEE NAME, SETTING PRELOADER POSITON, MSGBOX POSITION
//DONE BY:SASIKALA
//VER 0.09-SD:06/01/2015 ED:06/01/2015, TRACKER NO:74,DESC:ADDED GEOLOCATION FOR REPORT UPDATE
//DONE BY:LALITHA
//VER 0.08-SD:04/12/2014 ED:05/12/2014,TRACKER NO:74,Implemented If reason means updated Onduty(am/pm)/Absent(am/pm) with checked condition) nd changed query also,Updated to showned nd hide the header err msg,Updated pdf file name frm err msgs
//VER 0.07-SD:01/12/2014 ED:01/12/2014,TRACKER NO:74,Changed Preloder funct
//VER 0.06 SD:20/11/2014 ED:20/11/2014,TRACKER NO:74,DESC:Showned point by point line fr report nd reason in flx tble,Removed unwanted br tags
//VER 0.05 SD:06/11/2014 ED:06/11/2014,TRACKER NO:,74,DESC:Impmlemented auto focus in radio btn clicking,Fixed width fr all db column,Removed(report:)lbl
//DONE BY:SAFI
//VER 0.04 SD:31/10/2014 ED:03/11/2014,TRACKER NO:,74,DESC:updated text based header size while send mail and updated empty value to null.
//DONE BY:SASIKALA
//VER 0.03 SD:17/10/2014 ED 18/10/2014,TRACKER NO:74,DESC:DID PERMISSION AS MANDATORY AND BUTTON VALIDATION
//VER 0.02 SD:08/10/2014 ED:08/10/2014,TRACKER NO:74,DESC:UPDATED MAIL SEND WHEN UPDATION OCCUR
//VER 0.01-INITIAL VERSION, SD:08/08/2014 ED:01/10/2014,TRACKER NO:74
//*********************************************************************************************************//-->
<?php
include "HEADER.php";
?>
<script>
//var checkoutlocation;
//function displayLocation(latitude,longitude){
//    var request = new XMLHttpRequest();
//    var method = 'GET';
//    var url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='+latitude+','+longitude+'&sensor=true';
//    var async = true;
//
//    request.open(method, url, async);
//    request.onreadystatechange = function(){
//        if(request.readyState == 4 && request.status == 200){
//            var data = JSON.parse(request.responseText);
//            var address = data.results[0];
//            checkoutlocation=address.formatted_address;
//        }
//    };
//    request.send();
//};
//var successCallback = function(position){
//    var x = position.coords.latitude;
//    var y = position.coords.longitude;
//    displayLocation(x,y);
//};
//
//var errorCallback = function(error){
//    var errorMessage = 'Unknown error';
//    switch(error.code) {
//        case 1:
//            errorMessage = 'Permission denied';
//            break;
//        case 2:
//            errorMessage = 'Position unavailable';
//            break;
//        case 3:
//            errorMessage = 'Timeout';
//            break;
//    }
//    document.write(errorMessage);
//};
//
//var options = {
//    enableHighAccuracy: true,
//    timeout: 60000,
//    maximumAge: 0
//};
//
//navigator.geolocation.getCurrentPosition(successCallback,errorCallback,options);
// READY FUNCTION STARTS
$(document).ready(function(){
    $('.preloader', window.parent.document).show();
    USRC_paint();
    $('#wPaint').wPaint({menuOffsetLeft: -2,menuOffsetTop: -45});
    $('#USRC_UPD_btn_pdf').hide();
    $('#USRC_UPD_tble_paint').hide();
    var errmsgs;
    $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
    $('textarea').autogrow({onInitialize: true});
    var permission_array=[];
    var project_array=[];
    var min_date;
    var max_date;
    var search_max_date;
    var err_msg=[];
    var empname;
    var join_date;
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            var value_array=JSON.parse(xmlhttp.responseText);
            $('.preloader', window.parent.document).hide();
            permission_array=value_array[0];
            project_array=value_array[1];
            min_date=value_array[2];
            err_msg=value_array[4];
            if(min_date=='01-01-1970')
            {
                $('#USRC_UPD_form_usersearchupdate').replaceWith('<p><label class="errormsg">'+ err_msg[10] +'</label></p>');
            }
            else
            {
                search_max_date=value_array[3];
                join_date=value_array[5];
                empname=value_array[6];
                var mindatesplit=join_date.split('-');
                var max_date=new Date();
                var month=max_date.getMonth()+1;
                var year=max_date.getFullYear();
                var date=max_date.getDate();
                var max_date = new Date(year,month,date);
                var datepicker_maxdate=new Date(Date.parse(max_date));
                $('#USRC_UPD_tb_date').datepicker("option","maxDate",datepicker_maxdate);
                $('#USRC_UPD_tb_date').datepicker("option","minDate",join_date);
                $('#USRC_UPD_tb_enddte').datepicker("option","maxDate",search_max_date);
                $('#USRC_UPD_tb_strtdte').datepicker("option","minDate",min_date);
                $('#USRC_UPD_tb_strtdte').datepicker("option","maxDate",search_max_date);
                $('#USRC_UPD_lbl_strtdte').show();
                $('#USRC_UPD_tb_strtdte').show();
                $('#USRC_UPD_lbl_enddte').show();
                $('#USRC_UPD_tb_enddte').show();
            }
        }
    }
    var option="user_search_update";
    xmlhttp.open("GET","COMMON.do?option="+option);
    xmlhttp.send();
    $('#USRC_UPD_btn_srch').hide()
    $('#USRC_UPD_btn_submit').hide()
    //DATE PICKER FUNCTION
    $('.USRC_UPD_tb_date').datepicker({
        dateFormat:"dd-mm-yy",
        changeYear: true,
        changeMonth: true
    });
    $('textarea').autogrow({onInitialize: true});
    //BUTTON VALIDATION
    $('.valid').change(function(){
        if(($("#USRC_UPD_tb_strtdte").val()=='')||($("#USRC_UPD_tb_enddte").val()==''))
        {
            $("#USRC_UPD_btn_search").attr("disabled", "disabled");
        }
        else
        {
            $("#USRC_UPD_btn_search").removeAttr("disabled");
        }
    });
    //CHANGE EVENT FOR STARTDATE AND ENDDATE
    $(document).on('change','#USRC_UPD_tb_strtdte,#USRC_UPD_tb_enddte',function(){
        $('#USRC_UPD_div_header').hide();
        $('#USRC_UPD_btn_pdf').hide();
        UARD_clear()
        $('#USRC_UPD_tbl_htmltable').html('');
        $('#USRC_UPD_btn_srch').hide();
        $('#USRC_UPD_lbl_dte').hide();
        $('#USRC_UPD_tb_date').hide();
        $('#USRC_UPD_errmsg').hide();
        $('#USRC_UPD_div_tablecontainer').hide();
//        $('#USRC_UPD_banderrmsg').hide();
    });
    // CHANGE EVENT FOR STARTDATE
    $(document).on('change','#USRC_UPD_tb_strtdte',function(){
        $('#USRC_UPD_div_header').hide();
        $('#USRC_UPD_btn_pdf').hide();
        var USRC_UPD_startdate = $('#USRC_UPD_tb_strtdte').datepicker('getDate');
        var date = new Date( Date.parse( USRC_UPD_startdate ));
        date.setDate( date.getDate()  );
        var USRC_UPD_todate = date.toDateString();
        USRC_UPD_todate = new Date( Date.parse( USRC_UPD_todate ));
        $('#USRC_UPD_tb_enddte').datepicker("option","minDate",USRC_UPD_todate);
    });
    var values_array=[];
    $('#USRC_UPD_btn_search').click(function(){
        $('#USRC_UPD_div_header').hide();
        $('#USRC_UPD_btn_pdf').hide();
        $('#USRC_UPD_div_tablecontainer').hide();
        $('section').html('');
        $('.preloader', window.parent.document).show();
        flextable()
        $("#USRC_UPD_btn_search").attr("disabled", "disabled");
    });
    //DATE PICKER FUNCTION-->
    $('#USRC_UPD_tb_date').datepicker({
        dateFormat:"dd-mm-yy",
        changeYear: true,
        changeMonth: true
    });
    //FUNCTION FOR FORMTABLEDATEFORMAT
    function FormTableDateFormat(inputdate){
        var string = inputdate.split("-");
        return string[2]+'-'+ string[1]+'-'+string[0];
    }
    //FUNCTION FOR DATA TABLE
    function flextable()
    {
        var ure_after_mrg;
        var start_date=$('#USRC_UPD_tb_strtdte').val();
        var end_date=$('#USRC_UPD_tb_enddte').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                values_array=JSON.parse(xmlhttp.responseText);
                $('.preloader', window.parent.document).hide();
                if(values_array.length!=0){
                    $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                    //HEADER ERR MSG
                    var sd=err_msg[11].toString().replace("[LOGINID]",empname);
                    var msg=sd.toString().replace("[STARTDATE]",start_date);
                    errmsgs=msg.toString().replace("[ENDDATE]",end_date);
                    $('#USRC_UPD_div_header').text(errmsgs).show();
                    $('#USRC_UPD_btn_pdf').show();
                    var USRC_UPD_table_header='<table id="USRC_UPD_tbl_htmltable" border="1"  cellspacing="0" class="srcresult" style="width:1350px";><thead  bgcolor="#6495ed" style="color:white"><tr><th style="width:20px;"></th><th style="width:50px;" class="uk-date-column">DATE</th><th style="max-width:400px; !important;">REPORT</th><th style="width:130px;" class="uk-timestp-column">TIMESTAMP</th></tr></thead><tbody>'
                    for(var j=0;j<values_array.length;j++){
                        var emp_date=values_array[j].date;
                        var emp_report=values_array[j].report;
                        var emp_reason=values_array[j].reason;
                        var timestamp=values_array[j].timestamp;
                        var permission=values_array[j].permission;
                        var morningsession=values_array[j].morningsession;
                        var afternoonsession=values_array[j].afternoonsession;
                        var id=values_array[j].id;
                        var imgurl=values_array[j].imageurl;
                        if(permission==null)
                        {
                            if(morningsession=='PRESENT'){
                                ure_after_mrg=afternoonsession+'(PM)';
                            }
                            else
                            {
                                ure_after_mrg=morningsession+'(AM)';
                            }
                            if(emp_report==null)
                            {
                                if(morningsession=='PRESENT'){
                                    ure_after_mrg=afternoonsession;
                                }
                                else
                                {
                                    ure_after_mrg=morningsession;
                                }
                                USRC_UPD_table_header+='<tr ><td><input type="radio" name="USRC_UPD_rd_flxtbl" class="USRC_UPD_class_radio" id='+id+'  value='+id+'></td><td>'+emp_date+'</td><td> '+ure_after_mrg +' -  '+'REASON:'+emp_reason+'</td><td style="width:130px;" >'+timestamp+'</td></tr>';
                            }
                            else if(emp_reason==null)
                            {
                                USRC_UPD_table_header+='<tr ><td><input type="radio" name="USRC_UPD_rd_flxtbl" class="USRC_UPD_class_radio" id='+id+'  value='+id+'></td><td>'+emp_date+'</td><td style="max-width:400px; !important;">'+emp_report+'</td><td style="width:130px;">'+timestamp+'</td></tr>';
                            }
                            else
                            {
                                USRC_UPD_table_header+='<tr ><td><input type="radio" name="USRC_UPD_rd_flxtbl" class="USRC_UPD_class_radio" id='+id+'  value='+id+'></td><td>'+emp_date+'</td><td style="max-width:400px; !important;">'+emp_report+' <br> '+ure_after_mrg +'  -  '+'REASON:'+emp_reason+'</td><td style="width:130px;">'+timestamp+'</td></tr>';
                            }
                        }
                        else
                        {
                            if(morningsession=='PRESENT'){
                                ure_after_mrg=afternoonsession+'(PM)';
                            }
                            else
                            {
                                ure_after_mrg=morningsession+'(AM)';
                            }
                            if(emp_report==null)
                            {
                                if(morningsession=='PRESENT'){
                                    ure_after_mrg=afternoonsession;
                                }
                                else
                                {
                                    ure_after_mrg=morningsession;
                                }
                                USRC_UPD_table_header+='<tr ><td><input type="radio" name="USRC_UPD_rd_flxtbl" class="USRC_UPD_class_radio" id='+id+'  value='+id+'></td><td>'+emp_date+'</td><td> '+ure_after_mrg +'  -  '+'REASON:'+emp_reason+'<br>PERMISSION:'+permission+' hrs</td><td style="width:130px;">'+timestamp+'</td></tr>';
                            }
                            else if(emp_reason==null)
                            {
                                USRC_UPD_table_header+='<tr ><td><input type="radio" name="USRC_UPD_rd_flxtbl" class="USRC_UPD_class_radio" id='+id+'  value='+id+'></td><td>'+emp_date+'</td><td style="max-width:400px; !important;">'+emp_report+' <br>PERMISSION:'+permission+' hrs</td><td style="width:130px;">'+timestamp+'</td></tr>';
                            }
                            else
                            {
                                USRC_UPD_table_header+='<tr ><td><input type="radio" name="USRC_UPD_rd_flxtbl" class="USRC_UPD_class_radio" id='+id+'  value='+id+'></td><td>'+emp_date+'</td><td style="max-width:400px; !important;"> '+emp_report+' <br> '+ure_after_mrg +'  -  '+'REASON:'+emp_reason+' <br>PERMISSION:'+permission+' hrs</td><td style="width:130px;">'+timestamp+'</td></tr>';
                            }
                        }
                    }
                    USRC_UPD_table_header+='</tbody></table>';
                    $('section').html(USRC_UPD_table_header);
                    $('#USRC_UPD_tbl_htmltable').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers",
                        "aoColumnDefs" : [
                            { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                    });
                }
                else{
                    $('#USRC_UPD_div_tablecontainer').hide();
                    $('#USRC_UPD_div_header').hide();
                    $('#USRC_UPD_btn_pdf').hide();
                    var sd=err_msg[6].toString().replace("[SDATE]",start_date);
                    var msg=sd.toString().replace("[EDATE]",end_date);
                    $('#USRC_UPD_errmsg').text(msg).show();
                }
            }
        }
        $('#USRC_UPD_div_tablecontainer').show();
        var option='SEARCH';
        xmlhttp.open("GET","DB_DAILY_REPORTS_USER_SEARCH_UPDATE.do?start_date="+start_date+"&end_date="+end_date+"&option="+option,true);
        xmlhttp.send();
        sorting();
    }
    //FUNCTION FOR SORTING
    function sorting(){
        jQuery.fn.dataTableExt.oSort['uk_date-asc']  = function(a,b) {
            var x = new Date( Date.parse(FormTableDateFormat(a)));
            var y = new Date( Date.parse(FormTableDateFormat(b)) );
            return ((x < y) ? -1 : ((x > y) ?  1 : 0));
        };
        jQuery.fn.dataTableExt.oSort['uk_date-desc'] = function(a,b) {
            var x = new Date( Date.parse(FormTableDateFormat(a)));
            var y = new Date( Date.parse(FormTableDateFormat(b)) );
            return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
        }
        jQuery.fn.dataTableExt.oSort['uk_timestp-asc']  = function(a,b) {
            var x = new Date( Date.parse(FormTableDateFormat(a.split(' ')[0]))).setHours(a.split(' ')[1].split(':')[0],a.split(' ')[1].split(':')[1],a.split(' ')[1].split(':')[2]);
            var y = new Date( Date.parse(FormTableDateFormat(b.split(' ')[0]))).setHours(b.split(' ')[1].split(':')[0],b.split(' ')[1].split(':')[1],b.split(' ')[1].split(':')[2]);
            return ((x < y) ? -1 : ((x > y) ?  1 : 0));
        };
        jQuery.fn.dataTableExt.oSort['uk_timestp-desc'] = function(a,b) {
            var x = new Date( Date.parse(FormTableDateFormat(a.split(' ')[0]))).setHours(a.split(' ')[1].split(':')[0],a.split(' ')[1].split(':')[1],a.split(' ')[1].split(':')[2]);
            var y = new Date( Date.parse(FormTableDateFormat(b.split(' ')[0]))).setHours(b.split(' ')[1].split(':')[0],b.split(' ')[1].split(':')[1],b.split(' ')[1].split(':')[2]);
            return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
        };
    }
// CLICK EVENT FR RADIO BUTTON
    $(document).on('click','.USRC_UPD_class_radio',function(){
        err_flag=0;
        $("html, body").animate({ scrollTop: $(document).height() }, "fast");
        $("#USRC_UPD_tble_reasonlbltxtarea,#USRC_UPD_secndselectprojct,#USRC_UPD_tble_enterthereport,#USRC_UPD_mrg_projectlistbx,#USRC_UPD_aftern_projectlistbx,#USRC_UPD_lb_tableafternproj,#USRC_UPD_tble_frstsel_projectlistbx,#USRC_UPD_btn_submit").html('')
        $('#USRC_UPD_btn_srch').show();
        $('#USRC_UPD_errmsg').hide();
        $('#USRC_UPD_lbl_dte').hide();
        $('#USRC_UPD_tb_date').hide();
        $("#USRC_UPD_btn_srch").removeAttr("disabled");
        $('#USRC_UPD_rd_permission').hide();
        $('#USRC_UPD_lbl_permission').hide();
        $('#USRC_UPD_rd_nopermission').hide();
        $('#USRC_UPD_lbl_nopermission').hide();
        $('#USRC_UPD_lbl_session').hide();
        $('#USRC_UPD_ta_report').hide();
//        $('#USRC_UPD_tb_band').hide();
        $('#USRC_UPD_ta_reason').hide();
        $('#USRC_UPD_tble_attendence').hide();
        $('#USRC_UPD_lbl_band').hide();
        $('#USRC_UPD_lbl_reason').hide();
        $('#USRC_UPD_lbl_report').hide();
        $('#USRC_UPD_btn_submit').hide();
        $('#USRC_UPD_lb_timing').hide();
        $('#USRC_UPD_lb_ampm').hide();
        $('#USRC_UPD_lbl_txtselectproj').hide();
        $('#USRC_UPD_tble_projectlistbx').hide();
//        $('#USRC_UPD_banderrmsg').hide();
        $('#USRC_UPD_tble_paint').hide();

    });
    // CLICK EVENT FOR SEACH BUTTON
    var attendance;
    var date;
    var report;
    var userstamp;
    var timestamp;
    var reason;
    var permission;
    var pdid;
    var morningsession;
    var afternoonsession;
    var projectid_array;
    var imgurl;
    $(document).on('click','#USRC_UPD_btn_srch',function(){
        $("html, body").animate({ scrollTop: $(document).height() }, "fast");
        $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
        var SRC_UPD__idradiovalue=$('input:radio[name=USRC_UPD_rd_flxtbl]:checked').attr('id');
        $("#USRC_UPD_btn_srch").attr("disabled", "disabled");
        $('#USRC_UPD_lbl_txtselectproj').hide();
        for(var j=0;j<values_array.length;j++){
            var id=values_array[j].id;
            if(id==SRC_UPD__idradiovalue)
            {
                date=  values_array[j].date;
                report=values_array[j].report1;
                userstamp=values_array[j].userstamp;
                timestamp=values_array[j].timestamp;
                reason=values_array[j].reason1;
                permission=values_array[j].permission;
                attendance=values_array[j].attendance;
                pdid=values_array[j].pdid;
                morningsession=values_array[j].morningsession;
                afternoonsession=values_array[j].afternoonsession;
                imgurl=values_array[j].imageurl;
                if(attendance=='1')
                {
                    var permission_list='<option>SELECT</option>';
                    for (var i=0;i<permission_array.length;i++) {
                        permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                    }
                    $('#USRC_UPD_lb_timing').html(permission_list);
                }
                else if((attendance=='0.5') ||(attendance=='0.5OD'))
                {
                    var permission_list='<option>SELECT</option>';
                    for (var i=0;i<4;i++) {
                        permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                    }
                    $('#USRC_UPD_lb_timing').html(permission_list);
                }
                $('#USRC_UPD_tble_attendence').show();
                form_show(attendance);
                $("#wPaint").wPaint("image",imgurl);
            }
        }
    });

    $(document).on('click','.paginate_button',function(){
//    alert('inside');
        UARD_clear();
        $("#USRC_UPD_tb_date").val('').hide()
        $('#USRC_UPD_lbl_dte').hide();
        $('input:radio[name=USRC_UPD_rd_flxtbl]').attr('checked',false);


    });



    // FUNCTION FOR PROJECTID CHECKED
    function projecdid(){
        for(var i=0;i<project_array.length;i++){
            for(var j=0;j<projectid_array.length;j++){
                if(projectid_array[j]==project_array[i][1]){
                    $("#" + project_array[i][1]+'p').prop( "checked", true );
                }
            }
        }
    }
    // FUNCTION FOR FORM SHOW
    function form_show(attendance){
        if(attendance=='1')
        {
            projectid_array=pdid.split(",");
            $('#USRC_UPD_lbl_dte').show();
            $('#USRC_UPD_tb_date').show();
            $('#USRC_UPD_tb_date').val(date);
            $('#USRC_UPD_lb_attendance').val('1');
            $('#USRC_UPD_rd_permission').show();
            $('#USRC_UPD_lbl_permission').show();
            $('#USRC_UPD_rd_nopermission').show();
            $('#USRC_UPD_lbl_nopermission').show();
            $('#USRC_UPD_lbl_session').hide();
            $('#USRC_UPD_lb_ampm').hide();
            $('#USRC_UPD_tble_projectlistbx').show();
            $('#USRC_UPD_lbl_txtselectproj').show();
            $('#USRC_UPD_tble_paint').show();
            projectlist();
            projecdid();
            USRC_UPD_report()
            $('#USRC_UPD_ta_report').val(report);
//            USRC_UPD_tble_bandwidth()
//            $('#USRC_UPD_tb_band').val(bandwidth);
            $('#USRC_UPD_btn_submit').show();
            $('#USRC_UPD_rd_permission').removeAttr("disabled");
            $('#USRC_UPD_rd_nopermission').removeAttr("disabled");
        }
        if(attendance=='0')
        {
            $('#USRC_UPD_lbl_dte').show();
            $('#USRC_UPD_tb_date').show();
            $('#USRC_UPD_tb_date').val(date);
            $('#USRC_UPD_lb_attendance').val('0');
            $('#USRC_UPD_rd_permission').hide();
            $('#USRC_UPD_lbl_permission').hide();
            $('#USRC_UPD_rd_nopermission').hide();
            $('#USRC_UPD_lbl_nopermission').hide();
            $('#USRC_UPD_lbl_session').show();
            $('#USRC_UPD_lb_ampm').show();
            $('#USRC_UPD_lb_ampm').val("FULLDAY");
            USRC_UPD_reason()
            $('#USRC_UPD_ta_reason').val(reason);
            $('#USRC_UPD_btn_submit').show();
            $('#USRC_UPD_rd_permission').attr('disabled','disabled');
            $('#USRC_UPD_rd_nopermission').attr('disabled','disabled');
            $('#USRC_UPD_tble_paint').hide();
        }
        if(attendance=='0.5')
        {
            projectid_array=pdid.split(",");
            $('#USRC_UPD_lbl_dte').show();
            $('#USRC_UPD_tb_date').show();
            $('#USRC_UPD_tb_date').val(date);
            $('#USRC_UPD_lb_attendance').val('0');
            $('#USRC_UPD_rd_permission').show();
            $('#USRC_UPD_lbl_permission').show();
            $('#USRC_UPD_rd_nopermission').show();
            $('#USRC_UPD_lbl_nopermission').show();
            $('#USRC_UPD_rd_permission').removeAttr("disabled");
            $('#USRC_UPD_rd_nopermission').removeAttr("disabled");
            $('#USRC_UPD_lbl_session').show();
            $('#USRC_UPD_lb_ampm').show();
            $('#USRC_UPD_tble_paint').show();
            if((morningsession=='PRESENT') && (afternoonsession=='ABSENT'))
            {
                $('#USRC_UPD_lb_ampm').val('PM');
                $('#USRC_UPD_tble_projectlistbx').show();
                $('#USRC_UPD_lbl_txtselectproj').show();
                projectlist();
                projecdid();
            }
            else if((morningsession=='ABSENT' && afternoonsession=='PRESENT'))
            {
                $('#USRC_UPD_lb_ampm').val('AM');
                $('#USRC_UPD_tble_projectlistbx').show();
                $('#USRC_UPD_lbl_txtselectproj').show();
                projectlist();
                projecdid();
            }
            USRC_UPD_reason()
            $('#USRC_UPD_ta_reason').val(reason);
            USRC_UPD_report()
            $('#USRC_UPD_ta_report').val(report);
//            USRC_UPD_tble_bandwidth()
//            $('#USRC_UPD_tb_band').val(bandwidth);
            $('#USRC_UPD_btn_submit').show();
        }
        if(attendance=='OD')
        {
            $('#USRC_UPD_lb_attendance').val('OD');
            $('#USRC_UPD_lbl_dte').show();
            $('#USRC_UPD_tb_date').show();
            $('#USRC_UPD_tb_date').val(date);
            $('#USRC_UPD_rd_permission').hide();
            $('#USRC_UPD_lbl_permission').hide();
            $('#USRC_UPD_rd_nopermission').hide();
            $('#USRC_UPD_lbl_nopermission').hide();
            $('#USRC_UPD_lbl_session').show();
            $('#USRC_UPD_lb_ampm').show();
            $('#USRC_UPD_lb_ampm').val("FULLDAY");
            USRC_UPD_reason()
            $('#USRC_UPD_ta_reason').val(reason);
            $('#USRC_UPD_btn_submit').show();
            $('#USRC_UPD_rd_permission').attr('disabled','disabled');
            $('#USRC_UPD_rd_nopermission').attr('disabled','disabled');
            $('#USRC_UPD_tble_paint').hide();
        }
        if(attendance=='0.5OD')
        {
            projectid_array=pdid.split(",");
            $('#USRC_UPD_lbl_dte').show();
            $('#USRC_UPD_tb_date').show();
            $('#USRC_UPD_tb_date').val(date);
            $('#USRC_UPD_lb_attendance').val('OD');
            $('#USRC_UPD_rd_permission').show();
            $('#USRC_UPD_lbl_permission').show();
            $('#USRC_UPD_rd_nopermission').show();
            $('#USRC_UPD_lbl_nopermission').show();
            $('#USRC_UPD_rd_permission').removeAttr("disabled");
            $('#USRC_UPD_rd_nopermission').removeAttr("disabled");
            $('#USRC_UPD_lbl_session').show();
            $('#USRC_UPD_lb_ampm').show();
            $('#USRC_UPD_tble_paint').show();
            if((morningsession=='PRESENT') && (afternoonsession=='ONDUTY'))
            {
                $('#USRC_UPD_lb_ampm').val('PM');
                $('#USRC_UPD_tble_projectlistbx').show();
                $('#USRC_UPD_lbl_txtselectproj').show();
                projectlist();
                projecdid();
            }
            else if((morningsession=='ONDUTY' && afternoonsession=='PRESENT'))
            {
                $('#USRC_UPD_lb_ampm').val('AM');
                $('#USRC_UPD_tble_projectlistbx').show();
                $('#USRC_UPD_lbl_txtselectproj').show();
                projectlist();
                projecdid();
            }
            USRC_UPD_reason()
            $('#USRC_UPD_ta_reason').val(reason);
            USRC_UPD_report()
            $('#USRC_UPD_ta_report').val(report);
//            USRC_UPD_tble_bandwidth()
//            $('#USRC_UPD_tb_band').val(bandwidth);
            $('#USRC_UPD_btn_submit').show();
        }
        if(permission!=null)
        {
            $('#USRC_UPD_rd_permission').attr('checked','checked');
            $('#USRC_UPD_lb_timing').show();
            $('#USRC_UPD_lb_timing').val(permission);
        }
        else
        {
            $('#USRC_UPD_rd_nopermission').attr('checked','checked');
        }
    }
    var err_flag=0;
    // CHANGE EVENT FOR DATE ALREADY EXISTS
    $(document).on('change ','#USRC_UPD_tb_date',function(){
        var reportdate=$('#USRC_UPD_tb_date').val();
        if(date!=reportdate){
            $('.preloader', window.parent.document).show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var msgalert=xmlhttp.responseText;
                    $('.preloader', window.parent.document).hide();
                    if(msgalert==1)
                    {
                        err_flag=1;
                        var msg=err_msg[3].toString().replace("[DATE]",reportdate);
                        $('#USRC_UPD_errmsg').text(msg).show();
                        $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
                    }
                    else
                    {
                        err_flag=0;
                        $('#USRC_UPD_errmsg').hide();
                        USRC_UPD_form_validation();
                    }
                }
            }
            var option="DATE";
            xmlhttp.open("GET","DB_DAILY_REPORTS_USER_SEARCH_UPDATE.do?date_change="+reportdate+"&option="+option);
            xmlhttp.send();
        }
        else{
            err_flag=0;
            $('#USRC_UPD_errmsg').hide();
        }
    });
// CHANGE EVENT FOR ATTENDANCE
    $('#USRC_UPD_lb_attendance').change(function(){
        err_flag=0;
        $('#wPaint').wPaint('clear');
        if(attendance==$('#USRC_UPD_lb_attendance').val())
        {
            $('#USRC_UPD_tble_reasonlbltxtarea').html('');
            $('#USRC_UPD_tble_frstsel_projectlistbx').html('');
            $('#USRC_UPD_tble_enterthereport').html('');
//            $('#USRC_UPD_tble_bandwidth').html('');
            $('#USRC_UPD_tble_paint').hide();
            $('#USRC_UPD_lb_timing').hide();
            $('#USRC_UPD_lbl_permission').hide();
            $('#USRC_UPD_rd_permission').hide();
            $('#USRC_UPD_lbl_nopermission').hide();
            $('#USRC_UPD_rd_nopermission').hide();
            form_show(attendance)
            $('#USRC_UPD_btn_submit').attr('disabled','disabled');
            $("#wPaint").wPaint("image",imgurl);
        }
        else
        {
            projectid_array='';
            $('#USRC_UPD_tble_frstsel_projectlistbx').html('');
            $('#USRC_UPD_btn_submit').attr('disabled','disabled');
            $('#USRC_UPD_tble_reasonlbltxtarea').html('');
            if($('#USRC_UPD_lb_attendance').val()=='1')
            {
                $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                $('#USRC_UPD_tble_enterthereport,#USRC_UPD_ta_reason').html('');
                $('#USRC_UPD_rd_permission').attr('checked',false);
                $('#USRC_UPD_rd_nopermission').attr('checked',false);
                $('#USRC_UPD_lb_timing').hide();
                $('#USRC_UPD_lbl_permission').show();
                $('#USRC_UPD_rd_permission').show();
                $('#USRC_UPD_lbl_nopermission').show();
                $('#USRC_UPD_rd_nopermission').show();
                $('#USRC_UPD_tble_paint').show();
                var permission_list='<option>SELECT</option>';
                for (var i=0;i<permission_array.length;i++) {
                    permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                }
                $('#USRC_UPD_lb_timing').html(permission_list);
                $('#USRC_UPD_lbl_session').hide();
                $('#USRC_UPD_lb_ampm').hide();
                $('#USRC_UPD_tble_projectlistbx').show();
                $('#USRC_UPD_lbl_txtselectproj').show();
                projectlist();
                USRC_UPD_report();
//                USRC_UPD_tble_bandwidth();
                $('#USRC_UPD_btn_submit').hide();
                $('#USRC_UPD_lb_timing').prop('selectedIndex',0);
                $('#USRC_UPD_rd_permission').removeAttr("disabled");
                $('#USRC_UPD_rd_nopermission').removeAttr("disabled");
                $('#USRC_UPD_errmsg').hide();
//                $('#USRC_UPD_banderrmsg').hide();
            }
            else if($('#USRC_UPD_lb_attendance').val()=='0')
            {
                $('#USRC_UPD_rd_permission').attr('checked',false);
                $('#USRC_UPD_rd_nopermission').attr('checked',false);
                $('#USRC_UPD_lb_timing').hide();
                $('#USRC_UPD_lbl_permission').show();
                $('#USRC_UPD_rd_permission').show();
                $('#USRC_UPD_lbl_nopermission').show();
                $('#USRC_UPD_rd_nopermission').show();
                var permission_list='<option>SELECT</option>';
                for (var i=0;i<4;i++) {
                    permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                }
                $('#USRC_UPD_lb_timing').html(permission_list);
                $('#USRC_UPD_lbl_session').show();
                $('#USRC_UPD_lb_ampm').val('SELECT').show();
                $('#USRC_UPD_tble_projectlistbx').hide();
                $('#USRC_UPD_tble_reasonlbltxtarea').html('');
                $('#USRC_UPD_tble_enterthereport').html('');
//                $('#USRC_UPD_tble_bandwidth').html('');
                $('#USRC_UPD_btn_submit').hide();
                $('#USRC_UPD_rd_permission').attr('disabled','disabled');
                $('#USRC_UPD_rd_nopermission').attr('disabled','disabled');
                $('#USRC_UPD_errmsg').hide();
                $('#USRC_UPD_lb_timing').prop('selectedIndex',0);
//                $('#USRC_UPD_banderrmsg').hide();
                $('#USRC_UPD_tble_paint').hide();
            }
            else if($('#USRC_UPD_lb_attendance').val()=='OD')
            {
                $('#USRC_UPD_rd_permission').attr('checked',false);
                $('#USRC_UPD_rd_nopermission').attr('checked',false);
                $('#USRC_UPD_lb_timing').hide();
                $('#USRC_UPD_lbl_permission').show();
                $('#USRC_UPD_rd_permission').show();
                $('#USRC_UPD_lbl_nopermission').show();
                $('#USRC_UPD_rd_nopermission').show();
                var permission_list='<option>SELECT</option>';
                for (var i=0;i<4;i++) {
                    permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                }
                $('#USRC_UPD_lb_timing').html(permission_list);
                $('#USRC_UPD_lbl_session').show();
                $('#USRC_UPD_lb_ampm').val('SELECT').show();
                $('#USRC_UPD_tble_projectlistbx').hide();
                $('#USRC_UPD_tble_reasonlbltxtarea').html('');
                $('#USRC_UPD_tble_enterthereport').html('');
//                $('#USRC_UPD_tble_bandwidth').html('');
                $('#USRC_UPD_btn_submit').hide();
                $('#USRC_UPD_rd_permission').attr('disabled','disabled');
                $('#USRC_UPD_rd_nopermission').attr('disabled','disabled');
                $('#USRC_UPD_errmsg').hide();
//                $('#USRC_UPD_banderrmsg').hide();
                $('#USRC_UPD_tble_paint').hide();
            }
        }
    });
// CLICK EVENT PERMISSION RADIO BTN
    $(document).on('click','#USRC_UPD_rd_permission',function()
    {
        if($('#USRC_UPD_rd_permission').attr("checked","checked"))
        {
            $('#USRC_UPD_lb_timing').val('SELECT').show();
        }
        else
        {
            $('#USRC_UPD_lb_timing').hide();
            $('#USRC_UPD_lb_timing').prop('selectedIndex',0);
        }
    });
    // CLICK EVENT NOPERMISSION RADIO BTN
    $(document).on('click','#USRC_UPD_rd_nopermission',function()
    {
        $('#USRC_UPD_lb_timing').hide();
        $('#USRC_UPD_lb_timing').prop('selectedIndex',0);
    });
    // FUNCTION FOR CLEAR
    function UARD_clear(){
        $('#USRC_UPD_tble_attendence').hide();
        $('#USRC_UPD_tble_reasonlbltxtarea').html('');
        $('#USRC_UPD_tble_frstsel_projectlistbx').html('');
        $('#USRC_UPD_tble_enterthereport').html('');
//        $('#USRC_UPD_tble_bandwidth').html('');
        $('#USRC_UPD_btn_submit').html('');
        $('#USRC_UPD_lbl_session').hide();
        $('#USRC_UPD_lbl_permission').hide();
        $('#USRC_UPD_rd_permission').hide();
        $('#USRC_UPD_lbl_nopermission').hide();
        $('#USRC_UPD_rd_nopermission').hide();
        $('#USRC_UPD_lb_timing').hide();
        $('#USRC_UPD_lb_timing').prop('selectedIndex',0);
        $('#USRC_UPD_lb_ampm').hide();
        $('#USRC_UPD_btn_submit').hide();
        $('#USRC_UPD_tble_projectlistbx').hide();
//        $('#USRC_UPD_banderrmsg').hide();
        $('#USRC_UPD_tble_paint').hide();
        $('#wPaint').wPaint('clear');
    }
    // CHANGE EVENT SESSION LISTBX
    $('#USRC_UPD_lb_ampm').change(function(){
        $('#USRC_UPD_tble_paint').hide();
        projectid_array='';
        $('#USRC_UPD_tble_reasonlbltxtarea,#USRC_UPD_tble_enterthereport,#USRC_UPD_tble_frstsel_projectlistbx').html('');
        if($('#USRC_UPD_lb_ampm').val()=='SELECT')
        {
            $('#USRC_UPD_tble_reasonlbltxtarea').html('');
            $('#USRC_UPD_tble_frstsel_projectlistbx').html('');
            $('#USRC_UPD_tble_enterthereport').html('');
            $('#USRC_UPD_tble_projectlistbx').hide();
//            $('#USRC_UPD_tble_bandwidth').html('');
            $('#USRC_UPD_btn_submit').hide();
//            $('#USRC_UPD_banderrmsg').hide();
            $('#USRC_UPD_tble_paint').hide();
        }
        else if($('#USRC_UPD_lb_ampm').val()=='FULLDAY')
        {
            $("html, body").animate({ scrollTop: $(document).height() }, "fast");
            $('#USRC_UPD_tble_projectlistbx').hide();
            USRC_UPD_reason();
            $('#USRC_UPD_rd_permission').attr('checked',false);
            $('#USRC_UPD_rd_nopermission').attr('checked',false);
            $('#USRC_UPD_lb_timing').prop('selectedIndex',0);
            $('#USRC_UPD_rd_permission').attr('disabled','disabled');
            $('#USRC_UPD_rd_nopermission').attr('disabled','disabled');
            $('#USRC_UPD_lb_timing').hide();
            $('#USRC_UPD_lbl_permission').hide();
            $('#USRC_UPD_rd_permission').hide();
            $('#USRC_UPD_lbl_nopermission').hide();
            $('#USRC_UPD_rd_nopermission').hide();
            $('#USRC_UPD_btn_submit').show();
//            $('#USRC_UPD_banderrmsg').hide();
            $('#USRC_UPD_tble_paint').hide();
        }
        else
        {
            $("html, body").animate({ scrollTop: $(document).height() }, "fast");
            USRC_UPD_reason();
            $('#USRC_UPD_tble_projectlistbx').show();
            projectlist();
            USRC_UPD_report();
//            USRC_UPD_tble_bandwidth();
            $('#USRC_UPD_rd_permission').attr('checked',false);
            $('#USRC_UPD_rd_nopermission').attr('checked',false);
            $('#USRC_UPD_lb_timing').hide();
            $('#USRC_UPD_lbl_permission').show();
            $('#USRC_UPD_rd_permission').show();
            $('#USRC_UPD_lbl_nopermission').show();
            $('#USRC_UPD_rd_nopermission').show();
            var permission_list='<option>SELECT</option>';
            for (var i=0;i<4;i++) {
                permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
            }
            $('#USRC_UPD_lb_timing').html(permission_list);
            $('#USRC_UPD_lbl_txtselectproj').show();
            $('#USRC_UPD_btn_submit').hide();
            $('#USRC_UPD_lb_timing').prop('selectedIndex',0);
            $('#USRC_UPD_rd_permission').removeAttr("disabled");
            $('#USRC_UPD_rd_nopermission').removeAttr("disabled");
//            $('#USRC_UPD_banderrmsg').hide();
            $('#USRC_UPD_tble_paint').show();
        }
    });
// CHANGE EVENT FOR REPORT TEXTAREA
    $(document).on('change','#USRC_UPD_ta_report',function(){
//        $('#USRC_UPD_btn_submit').show();
        $('#USRC_UPD_btn_submit').attr('disabled','disabled');
    });
    //CHANGE EVENT FOR PAINT
    $(document).on('click','#wPaint',function(){
        $('#USRC_UPD_btn_submit').show();
        $('#USRC_UPD_btn_submit').attr('disabled','disabled');
    });
    //FUNCTION FOR PROJECT LIST
//    function projectlist(){
//        var project_list;
//        for (var i=0;i<project_array.length;i++) {
//            project_list += '<tr><td><input type="checkbox" id="' + project_array[i][1] +'p'+ '" class="update_validate" name="checkbox[]" value="' + project_array[i][1] + '" >' + project_array[i][0] + '</td></tr>';
//        }
//        $('#USRC_UPD_tble_frstsel_projectlistbx').append(project_list);
//    }

    function projectlist(){
        var project_list;
        for (var i=0;i<project_array.length;i++) {
//            if(project_array[i][3]==3){
//                project_list += '<tr><td><input type="checkbox" id="' + project_array[i][1] +'p'+ '" class="update_validate" name="checkbox[]" value="' + project_array[i][1] + '" readonly >' + project_array[i][0] +' - '+ project_array[i][2]+ '</td></tr>';
//            }
//            else{

            project_list += '<tr><td><input type="checkbox" id="' + project_array[i][1] +'p'+ '" class="update_validate" name="checkbox[]" value="' + project_array[i][1] + '" >' + project_array[i][0] +' - '+ project_array[i][2]+ '</td></tr>';


//            }
        }


        $('#USRC_UPD_tble_frstsel_projectlistbx').append(project_list);
    }



    //FUNCTION FOR REASON
    function USRC_UPD_reason(){
        $('<tr><td width="150"><label name="USRC_UPD_lbl_reason" id="USRC_UPD_lbl_reason" >REASON<em>*</em></label></td><td><textarea  name="USRC_UPD_ta_reason" id="USRC_UPD_ta_reason" class="update_validate" ></textarea></td></tr>').appendTo($("#USRC_UPD_tble_reasonlbltxtarea"));
    }
    // FUNCTIO FOR REPORT
    function USRC_UPD_report(){
        $('<tr><td width="150"><label name="USRC_UPD_lbl_report" id="USRC_UPD_lbl_report" >ENTER THE REPORT<em>*</em></label></td><td><textarea  name="USRC_UPD_ta_report" id="USRC_UPD_ta_report" class="update_validate" ></textarea></td></tr>').appendTo($("#USRC_UPD_tble_enterthereport"));
    }
    function USRC_paint(){
        $('<tr><td width="150"><label name="USRC_lbl_paint" id="USRC_lbl_paint" >DRAWING SURFACE</label></td><td><div id="wPaint" class="isResizable update_validate"></div><center id="wPaint-img"></center></td></tr>').appendTo($("#USRC_UPD_tble_paint"));
    }
    //FORM VALIDATION
    $(document).on('click change blur','.update_validate',function(){

        USRC_UPD_form_validation();

    });


    function USRC_UPD_form_validation(){
        var USRC_UPD_sessionlstbx= $("#USRC_UPD_lb_ampm").val();
        var USRC_UPD_reasontxtarea =$("#USRC_UPD_ta_reason").val();
        var USRC_UPD_reportenter =$("#USRC_UPD_ta_report").val();
//        var USRC_UPD_bndtxt = $("#USRC_UPD_tb_band").val();
        var USRC_UPD_projectselectlistbx=$('input[name="checkbox[]"]:checked').length;
        var USRC_UPD_permissionlstbx = $("#USRC_UPD_lb_timing").val();
        var USRC_UPD_permission=$("input[name=permission]:checked").val()=="PERMISSION";
        var USRC_UPD_nopermission=$("input[name=permission]:checked").val()=="NOPERMISSION";
        var USRC_UPD_presenthalfdysvld=$("#USRC_UPD_lb_attendance").val();
        if(err_flag!=1){
            if(((USRC_UPD_presenthalfdysvld=='0') && (USRC_UPD_sessionlstbx=='AM' || USRC_UPD_sessionlstbx=="PM")) || ((USRC_UPD_presenthalfdysvld=='OD') && (USRC_UPD_sessionlstbx=='AM' || USRC_UPD_sessionlstbx=="PM") ))
            {
                if(((USRC_UPD_reasontxtarea.trim()!="")&&(USRC_UPD_reportenter!='')&&( USRC_UPD_projectselectlistbx>0) && ((USRC_UPD_permission==true) || (USRC_UPD_nopermission==true))))
                {
                    if(USRC_UPD_permission==true)
                    {
                        if(USRC_UPD_permissionlstbx!='SELECT')
                        {
                            $("#USRC_UPD_btn_submit").removeAttr("disabled");
                        }
                        else
                        {
                            $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
                        }
                    }
                    else
                    {
                        $("#USRC_UPD_btn_submit").removeAttr("disabled");
                    }
                }
                else
                {
                    $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
                }
            }
            else if((USRC_UPD_presenthalfdysvld=='0' && USRC_UPD_sessionlstbx=='FULLDAY') || (USRC_UPD_presenthalfdysvld=='OD' && USRC_UPD_sessionlstbx=='FULLDAY'))
            {
                if(USRC_UPD_reasontxtarea.trim()=="")
                {
                    $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
                }
                else
                {
                    $("#USRC_UPD_btn_submit").removeAttr("disabled");
                }
            }
            else if(USRC_UPD_presenthalfdysvld=='1')
            {
                if(((USRC_UPD_reportenter.trim()!="")&&( USRC_UPD_projectselectlistbx>0) && ((USRC_UPD_permission==true) || (USRC_UPD_nopermission==true))))
                {
                    if(USRC_UPD_permission==true)
                    {
                        if(USRC_UPD_permissionlstbx!='SELECT')
                        {
                            $("#USRC_UPD_btn_submit").removeAttr("disabled");
                        }
                        else
                        {
                            $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
                        }
                    }
                    else
                    {
                        $("#USRC_UPD_btn_submit").removeAttr("disabled");
                    }
                }
                else
                {
                    $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
                }
            }
        }
    }
    // CHANGE EVENT FOR UPDATE BUTTON
    $(document).on('click','#USRC_UPD_btn_submit',function(){
        $('.preloader', window.parent.document).show();
        var request;
        var imageData = $("#wPaint").wPaint("image");
        $("#canvasImage").attr('src', imageData);
        var formElement = $('#USRC_UPD_form_usersearchupdate').serialize();
        var option="UPDATE";
        // Send Request
        request = $.ajax({
            url: "DB_DAILY_REPORTS_USER_SEARCH_UPDATE.do",
            type: "POST",
            data: formElement+"&option="+option+"&string="+imageData,
            success: function(response){
                var recived=JSON.parse(response);
                var msg_alert=recived[0];
                var parnt_foldid=recived[1];
                $('.preloader', window.parent.document).hide();
                if(msg_alert==1)
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"USER SEARCH AND UPDATE",msgcontent:err_msg[1],position:{top:150,left:500}}});
                    UARD_clear()
                    flextable()
                    $("#USRC_UPD_tb_date").val('').hide()
                    $('#USRC_UPD_lbl_dte').hide();
                }
                if(msg_alert==0)
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"USER SEARCH AND UPDATE",msgcontent:err_msg[7],position:{top:150,left:500}}});
                    UARD_clear()
                    flextable()
                    $("#USRC_UPD_tb_date").val('').hide()
                    $('#USRC_UPD_lbl_dte').hide();
                }
                if(msg_alert==0 && parnt_foldid!='')
                {
                    var foldererrmsg=err_msg[11].replace("[FID]",parnt_foldid);
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"USER SEARCH AND UPDATE",msgcontent:foldererrmsg,position:{top:150,left:500}}});
                    UARD_clear()
                    flextable()
                    $("#USRC_UPD_tb_date").val('').hide()
                    $('#USRC_UPD_lbl_dte').hide();
                }
                if(msg_alert!=0 && msg_alert!=1)
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"USER SEARCH AND UPDATE",msgcontent:msg_alert,position:{top:150,left:500}}});
                    UARD_clear()
                    flextable()
                    $("#USRC_UPD_tb_date").val('').hide()
                    $('#USRC_UPD_lbl_dte').hide();
                }
            }
        });
    });
    $(document).on('click','#USRC_UPD_btn_pdf',function(){
        var inputValOne=$('#USRC_UPD_tb_strtdte').val();
        inputValOne = inputValOne.split("-").reverse().join("-");
        var inputValTwo=$('#USRC_UPD_tb_enddte').val();
        inputValTwo = inputValTwo.split("-").reverse().join("-");
        var url=document.location.href='COMMON_PDF.do?flag=18&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&title='+errmsgs;
    });
});
// READY FUNCTION ENDS
</script>
</head>
<!--HEAD TAG END-->
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title" id="fhead" ><div style="padding-left:500px; text-align:left;"><p><h3>USER REPORT SEARCH/UPDATE</h3><p></div></div>
    <form name="USRC_UPD_form_usersearchupdate" id="USRC_UPD_form_usersearchupdate" class ='content'>
        <table>
            <table>
                <tr>
                    <td width="150"><label name="USRC_UPD_lbl_strtdte" id="USRC_UPD_lbl_strtdte" >START DATE<em>*</em></label></td>
                    <td><input type="text" name="USRC_UPD_tb_strtdte" id="USRC_UPD_tb_strtdte" class="USRC_UPD_tb_date valid clear" style="width:75px;"></td><br>
                </tr>
                <tr>
                    <td width="150"><label name="USRC_UPD_lbl_enddte" id="USRC_UPD_lbl_enddte" >END DATE<em>*</em></label></td>
                    <td><input type="text" name="USRC_UPD_tb_enddte" id="USRC_UPD_tb_enddte" class="USRC_UPD_tb_date valid clear" style="width:75px;"></td><br>
                </tr>
                <td><input type="button" class="btn" name="USRC_UPD_btn_search" id="USRC_UPD_btn_search" value="SEARCH" disabled ></td><br>
            </table>
            <div class="srctitle" name="USRC_UPD_div_header" id="USRC_UPD_div_header" hidden></div>
            <div><input type="button" id='USRC_UPD_btn_pdf' class="btnpdf" value="PDF"></div>
            <!--            <div class="errormsg" name="USRC_UPD_errmsg" id="USRC_UPD_errmsg" hidden></div>-->
            <div class="container" id="USRC_UPD_div_tablecontainer" hidden>
                <section>
                </section>
            </div>
            <table>
                <tr><td><input type="button" id="USRC_UPD_btn_srch" class="btn" name="USRC_UPD_btn_srch" value="SEARCH" hidden/></td></tr>
            </table>
            <table>
                <tr>
                    <td width="150"><label name="USRC_UPD_lbl_dte" id="USRC_UPD_lbl_dte" hidden>DATE</label></td>
                    <td><input type ="text" id="USRC_UPD_tb_date" class='proj datemandtry formshown update_validate' name="USRC_UPD_tb_date" style="width:75px;" hidden/></td><td><label id="USRC_UPD_errmsg" name="USRC_UPD_errmsg" class="errormsg" hidden></label></td>
                </tr>
            </table>
            <table id="USRC_UPD_tble_attendence" hidden>
                <tr>
                    <td width="150"><label name="USRC_UPD_lbl_attendance" id="USRC_UPD_lbl_attendance" >ATTENDANCE</label></td>
                    <td width="150">
                        <select id="USRC_UPD_lb_attendance" name="USRC_UPD_lb_attendance" class="update_validate">
                            <option value="1">PRESENT</option>
                            <option value="0">ABSENT</option>
                            <option value="OD">ONDUTY</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><input type="radio" id="USRC_UPD_rd_permission" name="permission" value="PERMISSION" class="update_validate"/>
                        <label name="USRC_UPD_permission" id="USRC_UPD_lbl_permission" >PERMISSION <em>*</em></label></td>
                    <td>
                        <select name="USRC_UPD_lb_timing" id="USRC_UPD_lb_timing" class="update_validate" hidden >
                        </select>
                    </td>
                </tr>
                <tr>
                    <td nowrap><input type="radio" id="USRC_UPD_rd_nopermission" name="permission" value="NOPERMISSION" class="update_validate"/>
                        <label name="USRC_UPD_permission" id="USRC_UPD_lbl_nopermission" nowrap>NO PERMISSION <em>*</em></label></td>
                </tr>
                <tr>
                    <td><label name="USRC_UPD_lbl_session" id="USRC_UPD_lbl_session" hidden >SESSION</label></td>
                    <td><select name="USRC_UPD_lb_ampm" id="USRC_UPD_lb_ampm" class="update_validate" >
                            <option>SELECT</option>
                            <option>FULLDAY</option>
                            <option>AM</option>
                            <option>PM</option>
                        </select></td>
                </tr>
            </table>
            <table id="USRC_UPD_tble_reasonlbltxtarea"></table>
            <table id="USRC_UPD_tble_projectlistbx" hidden>
                <tr><td width="150"><label name="USRC_UPD_lbl_txtselectproj" id="USRC_UPD_lbl_txtselectproj" >PROJECT</label><em>*</em></td>
                    <td> <table id="USRC_UPD_tble_frstsel_projectlistbx" ></table></td>
                </tr>
            </table>
            <table id="USRC_UPD_tble_enterthereport"></table>
            <table id="USRC_UPD_tble_paint" width=820"></table>
<!--            <table id="USRC_UPD_tble_bandwidth"></table>-->
<!--            <table>-->
<!--                <tr>-->
<!--                    <label id="USRC_UPD_banderrmsg" name="USRC_UPD_banderrmsg" class="errormsg" hidden></label>-->
<!--                </tr>-->
<!--            </table>-->
            <tr>
                <input type="button"  class="btn" name="USRC_UPD_btn_submit" id="USRC_UPD_btn_submit"  value="UPDATE" disabled >
            </tr>
            <table id="USRC_UPD_btn_submit"></table>
            </tr>
        </table>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->