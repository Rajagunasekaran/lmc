<?php
include "../FOLDERMENU.php"
?>
<script>
var stime='';
var etime='';
var upload_count=0;
$(document).ready(function(){
    $(".titlecase").Setcase({caseValue : 'title'});
    $('#SRC_radiosearchbtn').hide();
    $('#SRC_Final_Update').hide();
    $('#SRC_entryform').hide();
    $('#REV_nodata_startenddate').hide();
//    $('.preloader').show();
    var teamname=[];
    var empname=[];
    var machinerytype=[];
    var fittingitems=[];
    var materialitems=[];
    var jobtype=[];
    var ardid;
    var errormessage=[];
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $('.preloader').hide();
            $('#RPT').hide();
            $('#AE').hide();
            var value_array=JSON.parse(xmlhttp.responseText);
            empname=value_array[0];
            errormessage=value_array[1];
            //TEAM
            var team='<option>SELECT</option>';
            for (var i=0;i<teamname.length;i++) {
                team += '<option value="' + teamname[i] + '">' + teamname[i] + '</option>';
            }
            $('#SRC_tr_lb_team').html(team);
            //EMPNAME
            var employeename='<option>SELECT</option>';
            for (var i=0;i<empname.length;i++) {
                employeename += '<option value="' + empname[i][1] + '">' + empname[i][0] + '</option>';
            }
            $('#SRC_team_lb_empname').html(employeename);

        }
    }
    var option="COMMON_DATA";
    xmlhttp.open("GET","DB_ACCIDENT_SEARCH_UPDATE.php?option="+option);
    xmlhttp.send();
    // time and date picker
    $('.time-picker').datetimepicker({
        format:'H:mm'
    });
    $(".date-pickers").datepicker({
        dateFormat:"dd-mm-yy",
        changeYear: true,
        changeMonth: true
    });
    //set max length
    $(".txtlen").prop("maxlength", 50);
    $(".passno").prop("maxlength", 15);
    $(".time-picker").prop("maxlength", 5);
    $(".len").prop("maxlength", 10);
    $(".charlen").prop("maxlength",25);
    // numbers only
    $('.decimal').keyup(function(){
        var val = $(this).val();
        if(isNaN(val)){
            val = val.replace(/[^0-9\.]/g,'');
            if(val.split('.').length>2)
                val =val.replace(/\.+$/,"");
        }
        $(this).val(val);
    });
    $( "textarea" ).autogrow( { vertical : true, horizontal : true } );
    // text only
    $(".autosizealph").doValidation({rule:'alphabets',prop:{whitespace:true,autosize:true}});
    //SET DOB DATEPICKER
    var EMP_ENTRY_d = new Date();
    var EMP_ENTRY_year = EMP_ENTRY_d.getFullYear() - 18;
    EMP_ENTRY_d.setFullYear(EMP_ENTRY_year);
    $('#acc_tb_dob').datepicker(
        {
            dateFormat: 'dd-mm-yy',
            changeYear: true,
            changeMonth: true,
            yearRange: "1920:" + EMP_ENTRY_year,
            defaultDate: EMP_ENTRY_d
        });
    var pass_changedmonth=new Date(EMP_ENTRY_d.setFullYear(EMP_ENTRY_year));
    $('#acc_tb_dob').datepicker("option","maxDate",pass_changedmonth);
    //DATEPICKER MINDATE
    var min_mindate=new Date();
    var min_month=min_mindate.getMonth()-3;
    var min_year=min_mindate.getFullYear();
    var min_date=min_mindate.getDate();
    var mindate = new Date(min_year,min_month,min_date);
    var report_mindate=new Date(Date.parse(mindate));
    $('#acc_tb_dateofaccident').datepicker("option","minDate",report_mindate);
    //DATEPICKER MAXDATE
    var max_maxdate=new Date();
    var max_month=max_maxdate.getMonth();
    var max_year=max_maxdate.getFullYear();
    var max_date=max_maxdate.getDate();
    var maxdate = new Date(max_year,max_month,max_date);
    var report_maxdate=new Date(Date.parse(maxdate));
    $('#acc_tb_dateofaccident').datepicker("option","maxDate",report_maxdate);
//DAPT PICKER
    $(".date-picker").datepicker({
        dateFormat:"dd-mm-yy",
        changeYear: true,
        changeMonth: true
    });
    $(".date-picker").on("change", function () {
        var id = $(this).attr("id");
        var val = $("label[for='" + id + "']").text();
        $("#msg").text(val + " changed");
    });
    $(document).on('change','#SRC_from_date',function(){
        $('#SRC_Final_Update').hide();
        $('#REV_nodata_startenddate').hide();
        var USRC_UPD_startdate = $('#SRC_from_date').datepicker('getDate');
        var date = new Date( Date.parse( USRC_UPD_startdate ));
        date.setDate( date.getDate() );
        var USRC_UPD_todate = date.toDateString();
        USRC_UPD_todate = new Date( Date.parse( USRC_UPD_todate ));
        $('#SRC_to_date').datepicker("option","minDate",USRC_UPD_todate);
    });
//CHANGE EVENT FOR  DATERANGE
    $('.dterange').change(function(){
        $('#SRC_Final_Update').hide();
        $('#REV_nodata_startenddate').hide();
        $('#SRC_UPD_div_tablecontainer').hide();
        $('#SRC_entryform').hide();
        $('#SRC_radiosearchbtn').hide();
        if(($('#SRC_team_lb_empname').val()!='SELECT') && ($('#SRC_from_date').val()!='') && ($('#SRC_to_date').val()!=''))
        {
            $('#SRC_searchbtn').removeAttr("disabled");
        }
        else
        {
            $('#SRC_searchbtn').attr("disabled","disabled");
        }
    });
    //CHANGE EVENT FOR EMPLOYEE NAME
    $('#SRC_team_lb_empname').change(function(){
        $('#SRC_Final_Update').hide();
        $('#SRC_UPD_div_tablecontainer').hide();
        $('#SRC_entryform').hide();
        $('#SRC_radiosearchbtn').hide();
        $('#SRC_from_date').val('');
        $('#SRC_to_date').val('');
        if($(this).val()=="SELECT")
        {
            $('#SRC_from_date').val('');
            $('#SRC_to_date').val('');
            $('#SRC_UPD_div_tablecontainer').hide();
            $('#SRC_entryform').hide();
            $('#SRC_radiosearchbtn').hide();
            $('#SRC_Final_Update').hide();
            $('#SRC_searchbtn').attr("disabled","disabled");
        }
    });
    var reportdate;
    var place;
    var typeofinj;
    var natofinj;
    var location;
    var injuredpart;
    var time;
    var machinery;
    var lm;
    var description;
    var operator;
    var name;
    var age;
    var address;
    var nric;
    var fin;
    var permit;
    var passport;
    var nationality;
    var sex;
    var dob;
    var marr;
    var designation;
    var service;
    var comment;
    var empdi;
    var sex;
    var option;
    //CLICK FUNCTION FOR SEARCH BTN IN TOP
    var values_array=[];
    $(document).on("click",'#SRC_searchbtn', function (){
        $('#SRC_UPD_div_tablecontainer').hide();
        datatable();
    });

    //FUNCTION FOR FORMTABLEDATEFORMAT
    function FormTableDateFormat(inputdate){
        var string = inputdate.split("-");
        return string[2]+'-'+ string[1]+'-'+string[0];
    }
    //FUNCTION FOR DATA TABLE
    function datatable(){
        $('.preloader').show();
        var selectedemp=$('#SRC_team_lb_empname').val();
        var fromdate=$('#SRC_from_date').val();
        var todate=$('#SRC_to_date').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var searchvalues=JSON.parse(xmlhttp.responseText);
                values_array=searchvalues[0];
                if(values_array!=null){
                    $('#REV_nodata_startenddate').hide();
                    var SRC_UPD_table_header='<table id="SRC_tbl_htmltable" border="1"  cellspacing="0" class="srcresult"><thead  bgcolor="#6495ed" style="color:white"><tr><th></th><th class="uk-date-column">DATE</th><th>NAME</th><th>PLACE</th><th>TYPE OF INJURY</th><th>NATURE OF INJURY</th><th>LOCATION</th><th>USERSTAMP</th><th class="uk-timestp-column">TIMESTAMP</th><th align="center">VIEW</th></tr></thead><tbody>'
                    for(var j=0;j<values_array.length;j++){
                        empdi=values_array[j][0];
                        reportdate=values_array[j][1];
                        place=values_array[j][2];
                        typeofinj=values_array[j][3];
                        natofinj=values_array[j][4];
                        location=values_array[j][5];
                        var remarks=values_array[j][6];
                        var userstamp=values_array[j][7];
                        var timestamp=values_array[j][8];
                        ardid=values_array[j][9];
                        injuredpart=values_array[j][10];
                        time=values_array[j][11];
                        machinery=values_array[j][12];
                        lm=values_array[j][13];
                        operator=values_array[j][14];
                        description=values_array[j][15];
                        age=values_array[j][16];
                        address=values_array[j][17];
                        nric=values_array[j][18];
                        fin=values_array[j][19];
                        permit=values_array[j][20];
                        passport=values_array[j][21];
                        nationality=values_array[j][22];
                        sex=values_array[j][23];
                        dob=values_array[j][24];
                        marr=values_array[j][25];
                        designation=values_array[j][26];
                        service=values_array[j][27];
                        comment=values_array[j][28];
                        SRC_UPD_table_header+='<tr id='+ardid+'><td><input type="radio" name="SRC_UPD_rd_flxtbl" class="USRC_UPD_class_radio" id='+ardid+'  value='+ardid+'></td><td nowrap>'+reportdate+'</td><td>'+empdi+'</td><td> '+place+'</td><td> '+typeofinj+'</td><td> '+natofinj+'</td><td >'+location+'</td><td >'+userstamp+'</td><td nowrap>'+timestamp+'</td><td><input type="button" class="ajaxview btn btn-info btn-sm"   value="VIEW PDF" id="ACD_pdfbtn"/> </td></tr>';
                    }

                    SRC_UPD_table_header+='</tbody></table>';
                    $('section').html(SRC_UPD_table_header);
                    $('#SRC_tbl_htmltable').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers",
                        "aoColumnDefs" : [
                            { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                    });
                    $('#SRC_UPD_div_tablecontainer').show();
                    $('#SRC_searchbtn').attr('disabled','disabled');
                    $('.preloader').hide();
                }
                else
                {
                    var fromdate=$('#SRC_from_date').val();
                    var todate=$('#SRC_to_date').val();
                    var sd=errormessage[2].toString().replace("[SDATE]",fromdate);
                    var msg=sd.toString().replace("[EDATE]",todate);
//                    $('#REV_nodata_startenddate').text(msg).show();
                    $('#SRC_searchbtn').attr('disabled','disabled');
                    show_msgbox("ACCIDENT SEARCH UPDATE",msg,"error",false);
                    $('#SRC_from_date').val('');
                    $('#SRC_to_date').val('');
                    $('#SRC_UPD_div_tablecontainer').hide();
                    $('.preloader').hide();
                }
            }
        }
        var option="FETCH_DATA";
        xmlhttp.open("GET","DB_ACCIDENT_SEARCH_UPDATE.php?option="+option+"&emp="+selectedemp+"&fromdate="+fromdate+"&todate="+todate);
        xmlhttp.send();
        sorting();
    }
    // CLICK EVENT FR RADIO BUTTON
    $(document).on('click','.USRC_UPD_class_radio',function(){
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        $('#SRC_radiosearchbtn').removeAttr('disabled').show();
        $('#SRC_entryform').hide();
        $('#SRC_Final_Update').hide();
    });
// CLICK EVENT FR PDFBTN BUTTON
    $(document).on('click','#ACD_pdfbtn',function(){
        $('#pdf_show').empty();
        var pdfid= $(this).parent().parent().attr('id');
        var selectedemp=$('#SRC_team_lb_empname').val();
        var fromdate=$('#SRC_from_date').val();
        var todate=$('#SRC_to_date').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var viewfilename=xmlhttp.responseText;
                $('#pdfModal').modal({backdrop: 'static', keyboard: false});
                $('#pdf_show').append('<object data="'+viewfilename+'" type="application/pdf" width="100%" height="100%" ></object>');
            }
        }
        var option="VIEW_PDF_FETCH";
        xmlhttp.open("GET","DB_ACCIDENT_SEARCH_UPDATE.php?option="+option+"&pdfid="+pdfid+"&fromdate="+fromdate+"&todate="+todate);
        xmlhttp.send();
    });
    // CLICK EVENT FR RADIO SEARCH BTN BUTTON
    $(document).on('click','#SRC_radiosearchbtn',function(){
        $("html, body").animate({ scrollTop: $(document).height() }, "fast");
        $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
        var SRC_UPD__idradiovalue=$('input:radio[name=SRC_UPD_rd_flxtbl]:checked').attr('id');
        $("#SRC_radiosearchbtn").attr("disabled", "disabled");
        for(var j=0;j<values_array.length;j++){
            var id=values_array[j][9];
            if(id==SRC_UPD__idradiovalue)
            {
                var dateaccident=values_array[j][1];
                var acc_empdi=values_array[j][0];
                var acc_reportdate=values_array[j][1];
                var acc_place=values_array[j][2];
                var acc_typeofinj=values_array[j][3];
                var acc_natofinj=values_array[j][4];
                var acc_location=values_array[j][5];
                var acc_remarks=values_array[j][6];
                var userstamp=values_array[j][7];
                var timestamp=values_array[j][8];
                var acc_ardid=values_array[j][9];
                var acc_injuredpart=values_array[j][10];
                var acc_time=values_array[j][11];
                var acc_machinery=values_array[j][12];
                var acc_lm=values_array[j][13];
                var acc_operator=values_array[j][14];
                var acc_description=values_array[j][15];
                var acc_age=values_array[j][16];
                var acc_address=values_array[j][17];
                var acc_nric=values_array[j][18];
                var acc_fin=values_array[j][19];
                var acc_permit=values_array[j][20];
                var acc_passport=values_array[j][21];
                var acc_nationality=values_array[j][22];
                var acc_sex=values_array[j][23];
                var dateofbirt=values_array[j][24];
                var acc_marr=values_array[j][25];
                var acc_designation=values_array[j][26];
                var acc_service=values_array[j][27];
                var comment=values_array[j][28];
                // UPDATE FORM LOADING
                $('#acc_tb_dateofaccident').val(dateaccident).show();
                $('#acc_tb_placeofacc').val(acc_place).show();
                $('#acc_tb_timeofaccident').val(acc_time).show();
                $('#acc_tb_locofacc').val(acc_location).show();
                $('#acc_tb_typeofinju').val(acc_typeofinj).show();
                $('#acc_tb_natureofinju').val(acc_natofinj).show();
                $('#acc_tb_partsofbody').val(acc_injuredpart).show();
                $('#acc_tb_typeofmachinery').val(acc_machinery).show();
                $('#acc_tb_lmno').val(acc_lm).show();
                $('#acc_tb_nameofoperator').val(acc_operator).show();
                $('#acc_ta_description').val(acc_description).show();
                $('#acc_tb_name').val(acc_empdi).show();
                $('#acc_tb_age').val(acc_age).show();
                $('#acc_ta_adrs').val(acc_address).show();
                $('#acc_tb_nric').val(acc_nric).show();
                $('#acc_tb_fin').val(acc_fin).show();
                $('#acc_tb_workpermit').val(acc_permit).show();
                $('#acc_tb_passportno').val(acc_passport).show();
                $('#acc_tb_nationality').val(acc_nationality).show();
                if(acc_sex=='Male')
                {
                    $('#male').attr('checked',true);
                }
                if(acc_sex=='Female')
                {
                    $('#female').attr('checked',true);
                }
                if(comment=='Yes')
                {
                    $('#yes').attr('checked',true);
                }
                if(comment=='No')
                {
                    $('#no').attr('checked',true);
                }
                $('#acc_tb_dob').val(dateofbirt).show();
                $('#acc_tb_maritalstatus').val(acc_marr).show();
                $('#acc_tb_des').val(acc_designation).show();
                $('#acc_tb_length').val(acc_service).show();
                $('#SRC_Final_Update').show();
                $('#SRC_entryform').show();
            }
        }
    });
    //FINAL SUBMIT BUTTON VALIDATION
    $(document).on('change blur','#SRC_entryform',function(){
        var dateofaccident=$('#acc_tb_dateofaccident').val();
        var timeofaccident=$('#acc_tb_timeofaccident').val();
        var placeofaccident=$('#acc_tb_placeofacc').val();
        var locationofaccident=$('#acc_tb_locofacc').val();
        var typeofinjury=$('#acc_tb_typeofinju').val();
        var natureofinjury=$('#acc_tb_natureofinju').val();
        var partsofinjured=$('#acc_tb_partsofbody').val();
        var name=$('#acc_tb_name').val();
        var age=$('#acc_tb_age').val();
        var addrssofinjured= $("#acc_ta_adrs").val();
        var nricno=$("#acc_tb_nric").val();
        var finno=$("#acc_tb_fin").val();
        var workspermit=$("#acc_tb_workpermit").val();
        var passportno=$('#acc_tb_passportno').val();
        var nationality=$('#acc_tb_nationality').val();
        var dob=$('#acc_tb_dob').val();
        var maritalstatus=$('#acc_tb_maritalstatus').val();
        var designation=$('#acc_tb_des').val();
        var lengthofservice=$('#acc_tb_length').val();
        var commensy=$("input[name=work]:checked").val()=="yes";
        var commensn=$("input[name=work]:checked").val()=="no";
        var description=$('#acc_ta_description').val();
        var genderm=$("input[name=sex]:checked").val()=="male";
        var genderf=$("input[name=sex]:checked").val()=="female";
        if((dateofaccident!='')&&(timeofaccident!='') && (placeofaccident!='') && (locationofaccident!='')  && (typeofinjury!='') && (natureofinjury!='') && (partsofinjured!='')
            && (name!='') && (age!='') && (addrssofinjured!='') && (nricno!='') && (finno!='') && (workspermit!='') && (passportno!='')
            && (nationality!='') && (dob!='') && (maritalstatus!='') && (designation!='') && (lengthofservice!='') && (description!=''))
        {
            if(((genderf==true)|| (genderm==true)) && ((commensy==true) || (commensn==true)))
            {
                $('#SRC_Final_Update').removeAttr('disabled');
            }
        }
        else
        {
            $('#SRC_Final_Update').attr('disabled','disabled');
        }
    });
    //  CLICK EVENT FOR BUTTON SAVE
    $('#SRC_Final_Update').click(function(){
        $('.preloader').show();
        var radioid=$("input[name='SRC_UPD_rd_flxtbl']:checked").val();
        var formelement =$('#SRC_entryform').serialize();
        var option="SAVE";
        $.ajax({
            type: "POST",
            url: "DB_ACCIDENT_SEARCH_UPDATE.php",
            data: formelement+"&option="+option+"&radioid="+radioid,
            success: function(msg){
                $('.preloader').hide();
                var msg_alert=msg;
                if(msg_alert==1){
                    show_msgbox("ACCIDENT SEARCH UPDATE",errormessage[0],"success",false);
                    $("#SRC_entryform").find('input:text, input:password, input:file, select, textarea').val('');
                    $("#SRC_entryform").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
                    $('#SRC_entryform').hide();
                    $('#SRC_radiosearchbtn').hide();
                    $('#SRC_Final_Update').hide();
                    $("input[name=SRC_UPD_rd_flxtbl]:checked").attr('checked',false);
                    $('#acc_ta_adrs').height('22');
                    $('#acc_ta_description').height('214');
                    datatable();
                }
                else if(msg_alert==0)
                {
                    show_msgbox("ACCIDENT SEARCH UPDATE",errormessage[3],"error",false)
                }
                else
                {
                    show_msgbox("ACCIDENT SEARCH UPDAT",msg_alert,"error",false)
                }
            }
        });
    });
});
</script>
</head>
<body>
<div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
<div class="container">
<div class="panel panel-info">
<div class="panel-heading">
    <h3 class="panel-title">ACCIDENT SEARCH UPDATE</h3>
</div>
<div class="panel-body">
<div class="row form-group">
    <div class="col-md-1">
    </div>
    <div class="col-md-2">
        <label>FROM DATE</label>
        <div class="input-group">
            <input id="SRC_from_date" name="SRC_from_date" type="text" class="date-picker datemandtry dterange form-control" placeholder="From Date"/>
            <label for="SRC_from_date" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
        </div>
        <br><label id="REV_nodata_startenddate" name="REV_nodata_startenddate" class="errormsg col-sm-10" style="white-space: nowrap!important;" hidden  ></label>
    </div>
    <div class="col-md-2">
        <label>TO DATE</label>
        <div class="input-group">
            <input id="SRC_to_date" name="SRC_to_date" type="text" class="date-picker datemandtry dterange form-control" placeholder="To Date"/>
            <label for="SRC_to_date" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-3" style="padding-top: 25px">
            <button type="button" id="SRC_searchbtn" class="btn btn-info" disabled>SEARCH</button>
        </div>
    </div>
</div>
<div id="pdfModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">INCIDENT INVESTIGATION REPORT
                </h4>
            </div>
            <div class="modal-body">
                <div id="pdf_show"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="closepdf" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive" id="SRC_UPD_div_tablecontainer" hidden>
    <section>
    </section>
</div>
<div class="input-group">
    <button type="button" id="SRC_radiosearchbtn" class="btn btn-info" disabled hidden>SEARCH</button>
</div>
<br>
<form id="SRC_entryform" class="form-horizontal" hidden>
    <div class="panel panel-primary" >
        <div class="panel-heading">
            <h3 class="panel-title">PARTICULARS OF ACCIDENT</h3>
        </div>
        <div class="panel-body">
            <fieldset>
                <div class="row form-group">
                    <div class="col-md-2">
                        <label>DATE OF ACCIDENT<em>*</em></label>
                        <div class="input-group">
                            <input type="text" class="form-control date-pickers datemandtry" id="acc_tb_dateofaccident" name="acc_tb_dateofaccident" placeholder="Date">
                            <label for="acc_tb_dateofaccident" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>TIME OF ACCIDENT<em>*</em></label>
                        <input type="text" class="form-control time-picker" id="acc_tb_timeofaccident" name="acc_tb_timeofaccident" placeholder="Time of Accident">
                    </div>

                    <div class="col-md-4">
                        <label>PLACE OF ACCIDENT<em>*</em></label>
                        <input type="text" class="form-control txtlen titlecase" id="acc_tb_placeofacc"  name="acc_tb_placeofacc" placeholder="Place of Accident">
                    </div>
                    <div class="col-md-4">
                        <label>LOCATION OF ACCIDENT<em>*</em></label>
                        <input type="text" class="form-control txtlen titlecase" id="acc_tb_locofacc"  name="acc_tb_locofacc" placeholder="Location of Accident">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4">
                        <label>TYPE OF INJURY<em>*</em></label>
                        <input type="text" class="form-control txtlen titlecase" id="acc_tb_typeofinju" name="acc_tb_typeofinju" placeholder="Type of Injury">
                    </div>
                    <div class="col-md-4">
                        <label>NATURE OF INJURY<em>*</em></label>
                        <input type="text" class="form-control txtlen titlecase" id="acc_tb_natureofinju" name="acc_tb_natureofinju" placeholder="Nature of Injury">
                    </div>

                    <div class="col-md-4">
                        <label>PARTS OF BODY INJURED<em>*</em></label>
                        <input type="text" class="form-control txtlen titlecase" id="acc_tb_partsofbody"  name="acc_tb_partsofbody" placeholder="Parts of Body Injured">
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">MACHINERY INVOLVED(IF ANY)</h3>
        </div>
        <div class="panel-body">
            <fieldset>
                <div class="row form-group">
                    <div class="col-md-4">
                        <label>TYPE OF MACHINERY</label>
                        <input type="text" class="form-control txtlen titlecase" id="acc_tb_typeofmachinery" name="acc_tb_typeofmachinery" placeholder="Type of Machinery">
                    </div>
                    <div class="col-md-4">
                        <label>LM NO</label>
                        <input type="text" class="form-control charlen" id="acc_tb_lmno" name="acc_tb_lmno" placeholder="LM No">
                    </div>

                    <div class="col-md-4">
                        <label>NAME OF OPERATOR</label>
                        <input type="text" class="form-control txtlen autosizealph" id="acc_tb_nameofoperator"  name="acc_tb_nameofoperator" placeholder="Name of Operator">
                    </div>

                </div>
            </fieldset>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">PARTICULARS OF INJURED</h3>
        </div>
        <div class="panel-body">
            <fieldset>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label>NAME<em>*</em></label>
                        <input type="text" class="form-control txtlen autosizealph" id="acc_tb_name" name="acc_tb_name" placeholder="Name">
                    </div>
                    <div class="col-md-2">
                        <label>AGE<em>*</em></label>
                        <input type="text" class="form-control decimal" maxlength="2" id="acc_tb_age" name="acc_tb_age" placeholder="Age">
                    </div>
                    <div class="col-md-4">
                        <label>ADDRESS OF INJURED<em>*</em></label>
                        <textarea class="form-control textareaaccinjured titlecase" id="acc_ta_adrs" maxlength="200" rows="1"  name="acc_ta_adrs" placeholder="Address"></textarea>
                    </div>
                    <div class="col-md-3">
                        <label>NRIC NO<em>*</em></label>
                        <input type="text" class="form-control len" id="acc_tb_nric" name="acc_tb_nric" placeholder="NRIC No">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label>FIN NO<em>*</em></label>
                        <input type="text" class="form-control len" id="acc_tb_fin" name="acc_tb_fin" placeholder="FIN No">
                    </div>
                    <div class="col-md-3">
                        <label>WORK PERMIT NO<em>*</em></label>
                        <input type="text" class="form-control decimal passno" id="acc_tb_workpermit" name="acc_tb_workpermit" placeholder="Work Permit No">
                    </div>
                    <div class="col-md-3">
                        <label>PASSPORT NO<em>*</em></label>
                        <input type="text" class="form-control passno" id="acc_tb_passportno" name="acc_tb_passportno" placeholder="Passport No">
                    </div>

                    <div class="col-md-3">
                        <label>NATIONALITY<em>*</em></label>
                        <input type="text" class="form-control charlen autosizealph titlecase" id="acc_tb_nationality" name="acc_tb_nationality" placeholder="Nationality">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label>SEX<em>*</em></label>
                        <div class="radio">
                            <label class="checkbox-inline no_indent"> <input type="radio" name="sex" id="male" value="male"> Male </label>
                            <label class="checkbox-inline no_indent"> <input type="radio" name="sex" id="female" value="female"> Female </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>DATE OF BIRTH<em>*</em></label>
                        <div class="input-group">
                            <input type="text" class="form-control date-pickers datemandtry" id="acc_tb_dob" name="acc_tb_dob" placeholder="Date of Birth">
                            <label for="acc_tb_dob" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>MARITAL STATUS<em>*</em></label>
                        <input type="text" class="form-control charlen titlecase" id="acc_tb_maritalstatus" name="acc_tb_maritalstatus" placeholder="Marital Status">
                    </div>
                    <div class="col-md-3">
                        <label>DESIGNATION<em>*</em></label>
                        <input type="text" class="form-control txtlen titlecase" id="acc_tb_des" name="acc_tb_des" placeholder="Designation">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label>LENGTH OF SERVICE<em>*</em></label>
                        <input type="text" class="form-control txtlen" id="acc_tb_length" name="acc_tb_length" placeholder="Length of Service">
                    </div>
                    <div class="col-md-6">
                        <label>WAS BRIEFLY CARRIED OUT BEFORE WORK COMMENCEMENT<em>*</em></label>
                        <div class="radio no_indent">
                            <label class="checkbox-inline no_indent"> <input type="radio" name="work" id="yes" value="yes"> Yes </label>
                            <label class="checkbox-inline no_indent"> <input type="radio" name="work" id="no" value="no"> No </label>
                        </div>
                    </div>
                </div>

            </fieldset>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">DESCRIPTION OF ACCIDENT</h3>
        </div>
        <div class="panel-body">
            <fieldset>
                <div class="row form-group">
                    <div class="col-md-10">
                        <label>DESCRIPTION OF ACCIDENT<em>*</em></label>
                        <textarea class="form-control textareaupdacc" id="acc_ta_description" maxlength="3000" rows="10" name="acc_ta_description"  placeholder="Description"></textarea>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</div>
</div>
<div class="col-lg-offset-10">
    <button type="button" id="SRC_Final_Update" class="btn btn-info btn-lg" hidden disabled>UPDATE</button>
</div>
<div class="form-group-sm">
    <ul class="nav-pills">
        <li class="pull-right"><a href="#top">Back to top</a></li>
    </ul>
</div>
</div>
<script src="../PAINT/JS/customShape.js"> </script>
</div>
</div>
</form>
</body>
</html>