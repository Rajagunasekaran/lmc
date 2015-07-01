<?php
include "../../SUBFOLDERMENU.php";
?>
<!--SCRIPT TAG START-->
<script>
    var PH_ENTRY_errorAarray=[];
    //START DOCUMENT READY FUNCTION
    $(document).ready(function(){
        $(".preloader").hide();
        $(document).on('change','.publicentry',function(){
            var click=$(this).val();
            if(click=='entry_click')
            {
                $('#PH_lbl_report_entry').html('PUBLIC HOLIDAY ENTRY');
                $('#PH_ENTRY_table').show();
                $('#publicsearch').hide();
                $('#PH_ENTRY_tb_ss').val('');
                $('#PH_ENTRY_tb_gid').val('');
                $(".preloader").show();
                //GETTING ERR MSG FROM DB
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        var value_array=JSON.parse(xmlhttp.responseText);
                        PH_ENTRY_errorAarray=value_array[0];
                    }
                }
                var option="PUBLIC_HOLIDAY";
                xmlhttp.open("GET","../../LMC_LIB/COMMON.php?option="+option);
                xmlhttp.send();
                //DO VALIDATION START
                $(".autosizealph").doValidation({rule:'alphanumeric',prop:{whitespace:true,autosize:true,uppercase:false}});
                //DO VALIDATION END
                //FORM VALIDATION PART
                $(document).on('change','#PH_entry_form',function(){
                    var PH_ENTRY_ssid= $("#PH_ENTRY_tb_ss").val();
                    var PH_ENTRY_gid =$("#PH_ENTRY_tb_gid").val();
                    if(PH_ENTRY_ssid!='' && PH_ENTRY_gid!='' ){
                        $("#PH_ENTRY_btn_save").removeAttr("disabled");
                    }
                });
                //CLICK FUNCTION FOR SAVE BUTTON
                $(document).on('click','#PH_ENTRY_btn_save',function(){
                    $(".preloader").show();
                    var formElement = document.getElementById("PH_entry_form");
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            var msg_alert_array=JSON.parse(xmlhttp.responseText);
                            var valid_ss=msg_alert_array[2];
                            var ph_date_already_exixst=msg_alert_array[0];
                            var ph_saved=msg_alert_array[1];
                            if(ph_date_already_exixst==0 && ph_saved==1 && valid_ss!=0){
                                show_msgbox("PUBLIC HOLIDAY ENTRY",PH_ENTRY_errorAarray[1],"success",true);
                                PH_ENTRY_holiday_rset();
                            }
                            else if(ph_date_already_exixst==1){
                                //ALREADY EXIT MSG
                                $('#PH_ENTRY_lbl_already').text(PH_ENTRY_errorAarray[3]).show();
                                $("#PH_ENTRY_btn_save").attr("disabled","disabled");
                            }
                            else if(valid_ss==0){
                                //VALID KEY NS SS ID
                                $('#PH_ENTRY_lbl_valid').text(PH_ENTRY_errorAarray[2]).show();
                                $('#PH_ENTRY_lbl_valid1').text(PH_ENTRY_errorAarray[2]).show();
                                $("#PH_ENTRY_btn_save").attr("disabled","disabled");
                            }
                            else if(valid_ss>='1186'){
                                $('#PH_ENTRY_lbl_valid1').text(PH_ENTRY_errorAarray[2]).show();
                                $('#PH_ENTRY_lbl_valid').text(PH_ENTRY_errorAarray[2]).show();
                                $("#PH_ENTRY_btn_save").attr("disabled","disabled");
                            }
                            else
                            {
                                show_msgbox("PUBLIC HOLIDAY ENTRY",PH_ENTRY_errorAarray[0],"success",true);
                            }
                        }
                    }
                    var choice="ph_save"
                    xmlhttp.open("POST","DB_PUBLIC_HOLIDAY_ENTRY_SEARCH_UPDATE.php?option="+choice,true);
                    xmlhttp.send(new FormData(formElement));
                });
                //SAVE BUTTON VALIDATION
                $(document).on('change','#PH_entry_form',function(){
                    $('#PH_ENTRY_lbl_valid').hide();
                    $('#PH_ENTRY_lbl_already').hide();
                    $('#PH_ENTRY_lbl_valid1').hide();
                    $("#PH_ENTRY_tb_ss").removeClass('invalid');
                    $("#PH_ENTRY_tb_gid").removeClass('invalid');
                    var PH_ENTRY_ss= $("#PH_ENTRY_tb_ss").val();
                    var PH_ENTRY_gid =$("#PH_ENTRY_tb_gid").val();
                    if((PH_ENTRY_ss!='') && (PH_ENTRY_gid!='' ))
                    {
                        $("#PH_ENTRY_btn_save").removeAttr("disabled");
                    }
                    else
                    {
                        $("#PH_ENTRY_btn_save").attr("disabled","disabled");
                    }
                });
                //CLEAR ALL FIELDS
                function PH_ENTRY_holiday_rset()
                {
                    $("#PH_entry_form")[0].reset();
                    $("#PH_ENTRY_btn_save").attr("disabled", "disabled");
                    $('.sizefix').prop("size","20");
                    $('#PH_ENTRY_lbl_valid').hide();
                    $('#PH_ENTRY_lbl_valid1').hide();
                    $('#PH_ENTRY_lbl_already').hide();
                }
            }
            else
            {
                var err_msg_array;
                var PH_SRC_UPD_yr_listbx;
                $('#PH_lbl_report_entry').html('PUBLIC HOLIDAY SEARCH UPDATE');
                $('#PH_ENTRY_table').hide();
                $('#publicsearch').show();
                $('#PH_SRC_UPD_nodate').hide();
                $('#tablecontainer').hide();
                $('#PH_SRC_UP_btn_pdf').hide();
                $('#PH_SRC_UPD_btn_search').hide();
                $(".preloader").show();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var values_array=JSON.parse(xmlhttp.responseText);
                        $(".preloader").hide();
                        PH_SRC_UPD_yr_listbx=values_array[0];
                        err_msg_array=values_array[1];
                        if(PH_SRC_UPD_yr_listbx.length!=0){
                            var project_list='<option>SELECT</option>';
                            for (var i=0;i<PH_SRC_UPD_yr_listbx.length;i++) {
                                project_list += '<option value="' + PH_SRC_UPD_yr_listbx[i] + '">' + PH_SRC_UPD_yr_listbx[i] + '</option>';
                            }
                            $('#PH_SRC_UPD_lb_yr').html(project_list);
                            $('#PH_SRC_UPD_lb_yr').show();
                            $('#PH_SRC_UPD_lbl_yr').show();
                        }
                        else
                        {
                            $('#PH_SRC_UPD_nodaterr').text(err_msg_array[3]).show();
                            $('#PH_SRC_UP_btn_pdf').hide();
                            $('#PH_SRC_UPD_nodate').hide();
                        }
                    }
                }
                var option="common";
                xmlhttp.open("GET","DB_PUBLIC_HOLIDAY_ENTRY_SEARCH_UPDATE.php?option="+option);
                xmlhttp.send();
                //FUNCTION FOR FORMTABLEDATEFORMAT
                function FormTableDateFormat(inputdate){
                    var string = inputdate.split("-");
                    return string[2]+'-'+ string[1]+'-'+string[0];
                }

                //DATE PICKER FUNCTION
                $('.PH_SRC_UPD_tb_dates').datepicker({
                    dateFormat:"dd-mm-yy",
                    changeYear: true,
                    changeMonth: true
                });
                //CHANGE FUNCTION FOR YEAR
                var values_arraystotal=[];
                var values_array=[];
                var id;
                $(document).on('change','#PH_SRC_UPD_lb_yr',function(){
                    $(".preloader").show();
                    $('#tablecontainer').hide();
                    $('#PH_SRC_UPD_tble_htmltable').html('');
                    $('section').html('');
                    $('#PH_SRC_UPD_nodate').hide();
                    $("#PH_SRC_UPD_updateform").hide();
                    $('#PH_SRC_UP_btn_pdf').hide();
                    var yr=$('#PH_SRC_UPD_lb_yr').val()
                    var msg=err_msg_array[0].replace("[DATE]",yr);
                    $('#PH_SRC_UPD_lbl_norole_err').text(msg).hide();
                    flex_table();
                });
                //FUNCTION FOR FLEX TABLE
                function flex_table(){
                    if($('#PH_SRC_UPD_lb_yr').val()!="SELECT")
                    {
                        var yr=$('#PH_SRC_UPD_lb_yr').val();
                        var formElement = document.getElementById("PH_entry_form");
                        var xmlhttp=new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function() {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                                $(".preloader").hide();
                                values_arraystotal=JSON.parse(xmlhttp.responseText);
                                values_array=values_arraystotal[0];
                                if(values_array.length!=0)
                                {
                                    $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                                    var msg=err_msg_array[4].replace("[SCRIPT]",'PUBLIC HOLIDAY FOR '+yr);
                                    pdfmsg=msg;
                                    $('#PH_SRC_UPD_nodate').text(msg).show();
                                    $('#PH_SRC_UP_btn_pdf').show();
                                    var PH_SRC_UPD_table_header='<table id="PH_SRC_UPD_tble_htmltable" border="1"  cellspacing="0" class="srcresult"><thead bgcolor="#6495ed" style="color:white"><tr><th class="uk-date-column" >DATE</th><th>DESCRIPTION</th><th>USERSTAMP</th><th class="uk-timestp-column" >TIMESTAMP</th></tr></thead><tbody>'
                                    for(var j=0;j<values_array.length;j++){
                                        var PH_SRC_UPD_date=values_array[j].PH_SRC_UPD_date;
                                        var PH_SRC_UPD_desc=values_array[j].PH_SRC_UPD_descr;
                                        var PH_SRC_UPD_userstamp=values_array[j].PH_SRC_UPD_userstamp;
                                        var PH_SRC_UPD_timestamp=values_array[j].PH_SRC_UPD_timestamp;
                                        id=values_array[j].id;
                                        PH_SRC_UPD_table_header+='<tr></td><td id=date_'+id+' class="edit">'+PH_SRC_UPD_date+'</td><td id=desc_'+id+' class="edit">'+PH_SRC_UPD_desc+'</td><td>'+PH_SRC_UPD_userstamp+'</td><td>'+PH_SRC_UPD_timestamp+'</td></tr>';
                                    }
                                    PH_SRC_UPD_table_header+='</tbody></table>';
                                    $('#tablecontainer').show();
                                    $('section').html(PH_SRC_UPD_table_header);
                                    $('#PH_SRC_UPD_tble_htmltable').DataTable( {
                                        "aaSorting": [],
                                        "pageLength": 10,
                                        "sPaginationType":"full_numbers",
                                        "aoColumnDefs" : [
                                            { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                                    });
                                }
                                else
                                {
                                    var msg=err_msg_array[1].replace("[DATE]",yr);
                                    $('#PH_SRC_UPD_lbl_norole_err').text(msg).show();
                                    $('#PH_SRC_UPD_updateform').hide();
                                    $('#PH_SRC_UPD_btn_search').hide();
                                    $('#tablecontainer').hide();
                                    $('#PH_SRC_UPD_tble_htmltable').html('');

                                }
                                $(".preloader").hide();
                            }
                        }
                        $('#tablecontainer').show();
                        var choice="PUBLIC_HOLIDAY_DETAILS";
                        xmlhttp.open("POST","DB_PUBLIC_HOLIDAY_ENTRY_SEARCH_UPDATE.php?option="+choice,true);
                        xmlhttp.send(new FormData(formElement));
                        sorting();
                    }
                    else
                    {
                        $(".preloader").hide();
                        $('#PH_SRC_UPD_updateform').hide();
                        $('#PH_SRC_UPD_btn_search').hide();
                        $('#tablecontainer').hide();
                        $('#PH_SRC_UPD_tble_htmltable').html('');
                    }
                }


                var previous_id;
                var combineid;
                var pdid;
                var psid;
                var tdvalue;
                var ifcondition;
                $(document).on('click','.edit', function (){
                    if(previous_id!=undefined){
                        $('#'+previous_id).replaceWith("<td class='edit' id='"+previous_id+"' >"+tdvalue+"</td>");
                    }
                    var cid = $(this).attr('id');
                    var id=cid.split('_');
                    ifcondition=id[0];
                    previous_id=cid;

                    pdid=id[1];
                    psid=id[2];
                    tdvalue=$(this).text();

                    if(ifcondition=='desc')
                    {
                        $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><input type='text' id='project_des' name='project_des'  class='update'  value='"+tdvalue+"'></td>");
                    }
                    if(ifcondition=='date')
                    {
                        $('#'+cid).replaceWith("<td  class='new' id='"+previous_id+"'><input type='text' id='start_date' name='start_date'  class='update date-picker' style='width: 110px'  value='"+tdvalue+"'></td>");
                        $(".date-picker").datepicker({dateFormat:'dd-mm-yy',
                            changeYear: true,
                            changeMonth: true
                        });
                        $('.date-picker').datepicker("option","maxDate",new Date());
                    }
                });


                $(document).on('change','.update',function(){
                    $(".preloader").show();

                    if($('#desc_'+pdid).hasClass("edit")==true){

                        var babypdesc=$('#desc_'+pdid).text();
                    }
                    else{
                        var babypdesc=$('#project_des').val();
                    }

                    if($('#date_'+pdid).hasClass("edit")==true){

                        var babysdate=$('#date_'+pdid).text();
                    }
                    else{
                        var babysdate=$('#start_date').val();
                    }
                    $(".preloader").show();
                    $.ajax({
                        type: 'POST',
                        url: 'SETTINGS/DB_PUBLIC_HOLIDAY_SEARCH_UPDATE.do',
                        data:'&option=PROJECT_DETAILS_UPDATE&EMPSRC_UPD_DEL_rd_flxtbl='+pdid+'&PH_SRC_UPD_tb_des='+babypdesc+'&PH_SRC_UPD_tb_date='+babysdate,
                        success: function(data) {
                            var resultflag=data;
                            if(resultflag==1)
                            {
                                var msg=err_msg_array[0].replace("REPORT",'RECORD');
                                show_msgbox("PUBLIC HOLIDAY SEARCH/UPDATE",msg,"success",true);
                                flex_table();
                            }
                            else
                            {
                                show_msgbox("PUBLIC HOLIDAY SEARCH/UPDATE",err_msg_array[2],"success",true);
                                $(".preloader").hide();
                            }
                            $(".preloader").hide();
                        }
                    });
                });

                //CLICK EVENT FUCNTION FOR RESET
                $('#PH_SRC_UPD_btn_reset').click(function()
                {
                    $(".preloader").show();
                    PH_SRC_UPD_detailrset()
                });
                //RESET ALL THE ELEMENT
                function PH_SRC_UPD_detailrset()
                {
                    $(".preloader").hide();
                    $('#PH_SRC_UPD_tb_date').val('');
                    $('#PH_SRC_UPD_tb_des').val('');
                    $("#PH_SRC_UPD_btn_update").attr("disabled","disabled");
                }
                $(document).on('click','#PH_SRC_UP_btn_pdf',function(){
                    var inputValOne=$('#PH_SRC_UPD_lb_yr').val();
                    var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=16&inputValOne='+inputValOne+'&title='+pdfmsg;
                });
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
                    };
                }

            }
        });
        //RESET CLICK FUNCTION
        $(document).on('click','#PH_ENTRY_btn_reset',function(){
            $("#PH_entry_form")[0].reset();
            $("#PH_ENTRY_btn_save").attr("disabled", "disabled");
            $('.sizefix').prop("size","20");
            $('#PH_ENTRY_lbl_valid').hide();
            $('#PH_ENTRY_lbl_valid1').hide();
            $('#PH_ENTRY_lbl_already').hide();
        });
    });
    <!--SCRIPT TAG END-->
</script>
<!--BODY TAG START-->
<body>
<div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
<div class="container">
    <form  name="PH_entry_form" id="PH_entry_form" class="form-horizontal">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h2 class="panel-title">PUBLIC HOLIDAY ENTRY/SEARCH/UPDATE</h2>
        </div>
        <div class="panel-body">
                <div style="padding-bottom: 15px">
                    <div class="radio">
                        <label name="public_entry" id="pub_entry">
                            <input type="radio" name="public" id="entry" class="publicentry" value="entry_click">PUBLIC HOLIDAY ENTRY</label>
                    </div>

                    <div class="radio">
                        <label name="public_search" id="pub_search">
                            <input type="radio" name="public" class="publicentry" id="search" value="search_click">PUBLIC HOLIDAY SEARCH/UPDATE</label>
                    </div>
                    </div>

                <div>
                    <label id="PH_lbl_report_entry" class="srctitle"></label>
                </div>
                <!--entry part-->
                <div id="PH_ENTRY_table"  hidden>
                    <div class="form-group">
                        <label class="col-sm-2"  id="PH_ENTRY_lbl_ss">SS KEY<em>*</em></label>
                        <div class="col-sm-3">
                            <input type="text" name="PH_ENTRY_tb_ss" id="PH_ENTRY_tb_ss"  class="autosizealph sizefix title_alpha form-control" >
                            <label id="PH_ENTRY_lbl_valid" name="PH_ENTRY_lbl_valid" class="errormsg"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2" name="PH_ENTRY_lbl_gid" id="PH_ENTRY_lbl_gid">GID <em>*</em></label>
                        <div class="col-sm-3">
                            <input type="text" name="PH_ENTRY_tb_gid" id="PH_ENTRY_tb_gid"  class="autosizealph sizefix title_alpha form-control">
                            <label id="PH_ENTRY_lbl_already" name="PH_ENTRY_lbl_already" class="errormsg"></label>
                            <label id="PH_ENTRY_lbl_valid1" name="PH_ENTRY_lbl_valid1" class="errormsg"></label>
                        </div>
                    </div>
                    <div class="col-lg-offset-10">
                        <input class="btn btn-info" type="button"  id="PH_ENTRY_btn_save" name="SAVE" value="SAVE" disabled  />
                        <input type="button" class="btn btn-info" name="PH_ENTRY_btn_reset" id="PH_ENTRY_btn_reset" value="RESET">
                    </div>
                </div>
                <!--search update part-->
                <div id="publicsearch" hidden>
                    <div class="row-fluid form-group">
                        <label name="PH_SRC_UPD_lbl_yr" class="col-sm-3" id="PH_SRC_UPD_lbl_yr">SELECT A YEAR<em>*</em></label>
                        <div class="col-sm-4">
                            <select id="PH_SRC_UPD_lb_yr" name="PH_SRC_UPD_lb_yr" class="form-control">
                            </select>
                        </div></div>
                    <div><label id="PH_SRC_UPD_nodaterr" name="PH_SRC_UPD_nodaterr" class="errormsg"></label></div>
                    <!--        </div>-->
                    <div class="srctitle" name="PH_SRC_UPD_nodate" id="PH_SRC_UPD_nodate" hidden></div>
                    <div><input type="button" id="PH_SRC_UP_btn_pdf" class="btnpdf" value="PDF"></div>
                    <div><label id="UPH_SRC_UPD_lbl_header" name="UPH_SRC_UPD_lbl_header" class="errormsg"></label></div>
                    <div  id="tablecontainer" style="max-width:800px;" class="table-responsive" hidden>
                        <section>
                        </section>
                    </div>
                    <div><label id="PH_SRC_UPD_lbl_norole_err" name="PH_SRC_UPD_lbl_norole_err" class="errormsg"></label></div>

                    <div class="row-fluid form-group">
                        <input class="btn" type="button" id="PH_SRC_UPD_btn_search" name="PH_SRC_UPD_btn_search" value="SEARCH" hidden />
                    </div>

                    <div id="PH_SRC_UPD_updateform" hidden>
                        <div class="row-fluid form-group">
                            <label name="PH_SRC_UPD_lbl_dte" class="col-sm-2"  id="PH_SRC_UPD_lbl_dte">DATE</label>
                            <div class="col-sm-8">
                                <input type ="text" id="PH_SRC_UPD_tb_date" class='PH_SRC_UPD_tb_dates minmax proj datemandtry formshown update_validate' name="PH_SRC_UPD_tb_date" style="width:75px;"/>
                            </div></div>
                        <div class="row-fluid form-group">
                            <label name="PH_SRC_UPD_lbl_des"  class="col-sm-2" id="PH_SRC_UPD_lbl_des">DESCRIPTION</label>
                            <div class="col-sm-8">
                                <textarea rows="5" cols="100" name="PH_SRC_UPD_tb_des" id="PH_SRC_UPD_tb_des" class="validation uppercase maxlength"></textarea>
                            </div></div>
                        <div>
                            <input class="btn" type="button"  id="PH_SRC_UPD_btn_update" name="SAVE" value="UPDATE" disabled hidden />
                            <input type="button" class="btn" name="PH_SRC_UPD_btn_reset" id="PH_SRC_UPD_btn_reset" value="RESET">
                        </div>
                    </div>
                </div>
        </div>
     </div>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->