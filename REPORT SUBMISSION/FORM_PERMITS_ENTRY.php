<?php
include "../FOLDERMENU.php";
?>
<link rel="stylesheet" href="../PAINT/CSS/icon.css">
<script>
var ENT_upload_count=0;
$(document).ready(function(){
    $('#rootwizard').bootstrapWizard({'tabClass': 'nav nav-pills'});
    $('.hide').hide();
    $('#Final_submit').hide();
    //CODING FOR CUSTOM PAINT
    //drawing tool start
    $(document).on('click', '.previous', function () {
        canvas.deactivateAllWithDispatch().renderAll();
        imageData = canvas.toDataURL();
        imageDataJson = JSON.stringify(canvas);
    });
    var flag = 1, imageData, imageDataJson;

    $(document).on('click', '.next', function () {
        if(flag==1)
        {
            loadcanvas();
        }
        flag=0;
//        canvas.clear();
        if (imageDataJson != undefined) {
//            canvas.loadFromJSON(imageDataJson)
            updateImage(imageDataJson);
        }
//        if (imageData != undefined && imageData!= null) {
//            updateImage(imageData);
//        }
    });
    //drawing tool end
    //ENDING OF CUSTOM CODE
//    $('.preloader').show();

    //END OF VALIDATION
    var teamname=[];
    var empname=[];
    var machinerytype=[];
    var fittingitems=[];
    var materialitems=[];
    var jobtype=[];
    var errormessage=[];
    var employeeid;
    var topicname=[];
    var mtransferitem=[];
    var contractnos=[];
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $('.preloader').hide();
            $('#RPT').hide();
            $('#AE').hide();
            var value_array=JSON.parse(xmlhttp.responseText);
            teamname=value_array[0];
            machinerytype=value_array[1];
            fittingitems=value_array[2];
            materialitems=value_array[3];
            jobtype=value_array[4];
            errormessage=value_array[5];
            topicname=value_array[7];
            var username=value_array[8];
            mtransferitem=value_array[9];
            contractnos=value_array[10];
            if(teamname==null)
            {
                var msg=errormessage[9].replace('[UNAME]',username);
                $('#entryform').replaceWith(show_msgbox("REPORT SUBMISSION ENTRY",msg,"error",false));
            }
            else
            {
                //topic
                var topic='<option>SELECT</option>';
                for (var i=0;i<topicname.length;i++) {
                    topic += '<option value="' + topicname[i] + '">' + topicname[i] + '</option>';
                }
                $('#mt_lb_topic').html(topic);
                $('#tr_tb_team').val(teamname);

                //MACHINERY_TYPE
                var machinery_type='<option>SELECT</option>';
                for (var i=0;i<machinerytype.length;i++) {
                    machinery_type += '<option value="' + machinerytype[i] + '">' + machinerytype[i] + '</option>';
                }
                $('#machinery_type').html(machinery_type);

                //FITTING ITEM
                var fitting_item='<option>SELECT</option>';
                for (var i=0;i<fittingitems.length;i++) {
                    fitting_item += '<option value="' + fittingitems[i] + '">' + fittingitems[i] + '</option>';
                }
                $('#fitting_items').html(fitting_item);

                //MATERIAL ITEM
                var material_item='<option>SELECT</option>';
                for (var i=0;i<materialitems.length;i++) {
                    material_item += '<option value="' + materialitems[i] + '">' + materialitems[i] + '</option>';
                }
                $('#material_items').html(material_item);
                //TYPE OF JOB
                var typeofjob='';
                for(var i=0;i<jobtype.length;i++)
                {
                    typeofjob+='<label class="checkbox-inline no_indent"><input type="checkbox" id ="jobtype" name="jobtype[]" value="' + jobtype[i][1] + '">' + jobtype[i][0]+'</label>'
                }
                $('#type_of_job').append(typeofjob).show();
                //MTRANSFER ITEM
                var mtransfer_item='<option>SELECT</option>';
                for (var i=0;i<mtransferitem.length;i++) {
                    mtransfer_item += '<option value="' + mtransferitem[i] + '">' + mtransferitem[i] + '</option>';
                }
                $('#mtransfer_item').html(mtransfer_item);
                //CONTRACT NO
                var contractno='<option>SELECT</option>';
                for (var i=0;i<contractnos.length;i++) {
                    contractno += '<option value="' + contractnos[i].id + '">' + contractnos[i].no + '</option>';
                }
                $('#tr_lb_contractno').html(contractno);
            }
        }
    }
    var option="COMMON_DATA";
    xmlhttp.open("GET","DB_PERMITS_ENTRY.php?option="+option);
    xmlhttp.send();
// CHANGE EVENT FOR CONTACT NO
    var item_array=[];
    $(document).on('change','#tr_lb_contractno',function(){
        if($('#tr_lb_contractno').val()!='SELECT') {
            $('.preloader').show();
            var slctdcontractno = $('#tr_lb_contractno').find('option:selected').text();
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    item_array = JSON.parse(xmlhttp.responseText);
                    //ITEM NO FOR SITE STOCK USAGE
                    var itemno = '<option>SELECT</option>';
                    if (item_array.length > 0) {
                        for (var i = 0; i < item_array.length; i++) {
                            itemno += '<option value="' + item_array[i].no + '">' + item_array[i].no + '</option>';
                        }
                        $('.preloader').hide();
                    }
                    else {
                        $('.preloader').hide();
                        var errormsg = errormessage[13].toString().replace("[CONTRACTNO]", slctdcontractno);
                        show_msgbox("REPORT SUBMISSION ENTRY", errormsg, "error", false);
                    }
                    $('#stock_itemno').html(itemno);
                }
            }
            var option = "get_itemnos";
            xmlhttp.open("GET", "DB_PERMITS_ENTRY.php?option=" + option + "&contct_no=" + $('#tr_lb_contractno').val());
            xmlhttp.send();
        }
    });
// CHANGE EVENT FOR STOCK USAGE ITEM NO
    $(document).on('change','#stock_itemno',function(){
        var contractid = $('#stock_itemno').val();
        if(contractid!='SELECT') {
            for (var i = 0; i < item_array.length; i++) {
                if (contractid == item_array[i].no) {
                    $('#stock_itemname').val(item_array[i].name);
                }
            }
        }
        else {
            $('#stock_itemname').val('');
        }
    });
//CHANGE EVENT FOR REPORT DATE
    $('#tr_txt_date').change(function(){
        $('.preloader').show();
        var reportdate=$('#tr_txt_date').val();
        var teamname=$('#tr_tb_team').val();
        if(reportdate!=""){
            var empname=[];
            var report_details=[];
            var currentemp=[];
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function(){
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var value_array=JSON.parse(xmlhttp.responseText);
                    empname=value_array[0];
                    report_details=value_array[1];
                    currentemp=value_array[2];
                    employeeid=currentemp;
                    var reportflag=value_array[3];
                    //EMPLOYEE DETAILS
                    $('#Employee_table tr:not(:first)').remove();
                    if(reportflag==0)
                    {
                        for(var i=0;i<empname.length;i++)
                        {
                            if(report_details!=null)
                            {
                                for(var j=0;j<report_details.length;j++)
                                {
                                    if(report_details[j][0]==empname[i][1])
                                    {
                                        var starttime=report_details[j][1];   var endtime=report_details[j][2]; var ottime=report_details[j][3];if(ottime==null){ottime='';} var remark=report_details[j][4];if(remark==null){remark='';}
                                        break;
                                    }
                                    else{starttime='';endtime='';ottime='';remark='';}
                                }
                            }
                            else
                            {starttime='';endtime='';ottime='';remark='';}
                            var autoid=i+1;
                            var emp_name="Emp_name"+autoid;
                            var emp_id="Emp_id"+autoid;
                            var emp_start="Emp_starttime"+autoid;
                            var emp_end="Emp_endtime"+autoid;
                            var emp_ot="Emp_ot"+autoid;
                            var emp_remark="Emp_remark"+autoid;
                            if(empname[i][1]==currentemp)
                            {
                                var appendrow='<tr id="'+autoid+'" class="active"><td><div><input type="text" class="form-control" readonly style="max-width: 560px" name="name" id="'+emp_name+'" value="'+empname[i][0]+'"><input type="hidden" class="form-control" style="max-width: 100px" id="'+emp_id+'" value="'+empname[i][1]+'"></div></td><td><div class="col-lg-10"><input type="text" class="form-control time-picker stime" style="max-width: 100px"  id="'+emp_start+'" value="'+starttime+'" ></div></td><td><div class="col-lg-10"><input type="text" class="form-control time-picker etime" style="max-width: 100px" id="'+emp_end+'" value="'+endtime+'"></div></td><td><div class="col-lg-10"><input type="text" class="form-control amountonly  size" style="max-width: 100px" id="'+emp_ot+'" value="'+ottime+'"></div></td><td><div><textarea class="form-control remarklen removecap textareaaccinjured" rows="1" id="'+emp_remark+'">'+remark+'</textarea><div></td></tr>';
                            }
                            else
                            {
                                appendrow='<tr id="'+autoid+'" class="active"><td><div><input type="text" class="form-control" readonly style="max-width: 560px" name="name" id="'+emp_name+'" value="'+empname[i][0]+'"><input type="hidden" class="form-control" style="max-width: 100px" id="'+emp_id+'" value="'+empname[i][1]+'"></div></td><td><div class="col-lg-10"><input type="text" readonly class="form-control time-picker stime" style="max-width: 100px" id="'+emp_start+'" value="'+starttime+'"></div></td><td><div class="col-lg-10"><input type="text" readonly class="form-control time-picker etime" style="max-width: 100px" id="'+emp_end+'" value="'+endtime+'"></div></td><td><div class="col-lg-10"><input type="text" readonly class="form-control amountonly  size" style="max-width: 100px" id="'+emp_ot+'" value="'+ottime+'"></div></td><td><div><textarea readonly class="form-control remarklen removecap textareaaccinjured" rows="1" id="'+emp_remark+'">'+remark+'</textarea><div></td></tr>';
                            }
                            $('#Employee_table tr:last').after(appendrow);
                            $('.time-picker').datetimepicker({
                                format:'H:mm'
                            });
                            $(".remarklen").prop("maxlength", 500);
                            $(".size").prop("maxlength", 4);
                            $(".time-picker").prop("maxlength", 5);
                            $( "textarea" ).autogrow( { vertical : true, horizontal : true } );
                            $('.decimal').keyup(function(){
                                var val = $(this).val();
                                if(isNaN(val)){
                                    val = val.replace(/[^0-9\.]/g,'');
                                    if(val.split('.').length>2)
                                        val =val.replace(/\.+$/,"");
                                }
                                $(this).val(val);
                            });
                            $(document).on("keyup",'.removecap',function() {
                                if (this.value.match(/[\^]/g)) {
                                    this.value = this.value.replace(/[\^]/g, '');
                                }
                            });
                            $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:1,imaginary:2}});
                        }
                    }
                    else
                    {
                        var errormsg=errormessage[1].toString().replace("[DATE]",reportdate);
                        show_msgbox("REPORT SUBMISSION ENTRY",errormsg,"error",false);
//                        existsform_clear();
                    }
                }
            }
            var option="EMPLOYEE_NAME";
            xmlhttp.open("GET","DB_PERMITS_ENTRY.php?option="+option+"&teamname="+teamname+"&date="+reportdate);
            xmlhttp.send();
        }else{
            $('#Employee_table tr:not(:first)').remove();
        }
    });
//set max length
    $(".txtlen").prop("maxlength", 40);
    $(".size").prop("maxlength", 5);
    $(".time-picker").prop("maxlength", 5);
    $(".quantity").prop("maxlength", 10);
    $(".remarklen").prop("maxlength", 500);
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
// text only
    $(".autosizealph").doValidation({rule:'alphabets',prop:{whitespace:true,autosize:true}});
// LORRY NO VALIDATION
    $('.lorryno').keyup(function() {
        if (this.value.match(/[^a-zA-Z0-9\-\.\/]/g)) {
            this.value = this.value.replace(/[^a-zA-Z0-9\-\.\/]/g, '');
        }
    });
    $(document).on("keyup",'.alphanumeric',function() {
        if (this.value.match(/[^a-zA-Z0-9\-\ \.\,\/]/g)) {
            this.value = this.value.replace(/[^a-zA-Z0-9\-\ \.\,\/]/g, '');
        }
    });
    $(document).on("keyup",'.removecap',function() {
        if (this.value.match(/[\^]/g)) {
            this.value = this.value.replace(/[\^]/g, '');
        }
    });
//TEAM REPORT FUNCTION
    $('.time-picker').datetimepicker({
        format:'H:mm'
    });
//    $('.time-picker').timepicker();
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
    $( "textarea" ).autogrow( { vertical : true, horizontal : true } );
//END OF TEAM REPORTR FUNCTION
//DATEPICKER MINDATE
    var min_mindate=new Date();
    var min_month=min_mindate.getMonth()-3;
    var min_year=min_mindate.getFullYear();
    var min_date=min_mindate.getDate();
    var mindate = new Date(min_year,min_month,min_date);
    var report_mindate=new Date(Date.parse(mindate));
    $('.date-picker').datepicker("option","minDate",report_mindate);
//DATEPICKER MAXDATE
    var max_maxdate=new Date();
    var max_month=max_maxdate.getMonth()+1;
    var max_year=max_maxdate.getFullYear();
    var max_date=max_maxdate.getDate();
    var maxdate = new Date(max_year,max_month,max_date);
    var report_maxdate=new Date(Date.parse(maxdate));
    $('.date-picker').datepicker("option","maxDate",report_maxdate);
    //MEETING ADD,DELETE AND UPDATE FUNCTION
    $('#mt_btn_update').hide();
//CLICK EVENT FOR MEETING ADD BUTTON
    $('#mt_btn_addrow').click(function(){
        var topic=$('#mt_lb_topic').val();
        var remark=$('#mt_ta_remark').val();
        if((topic!='SELECT'))
        {
            var mt_tablerowcount=$('#meeting_table tr').length;
            var mt_trrowid=mt_tablerowcount;
            if(mt_tablerowcount>1){
                var mt_lastid=$('#meeting_table tr:last').attr('id');
                var splittrid=mt_lastid.split('tr_');
                mt_trrowid=parseInt(splittrid[1])+1;
            }
            var mt_editid='mt_editrow/'+mt_trrowid;
            var mt_deleterowid='mt_deleterow/'+mt_trrowid;
            var mt_row_id="mt_tr_"+mt_trrowid;
            var appendrow='<tr class="active" id='+mt_row_id+'><td style="max-width: 150px"><div class="col-md-1"><span style="display:block;" class="glyphicon glyphicon-edit mt_editbutton" id='+mt_editid+'></span></div><div class="col-md-1"><span style="display:block;" class="glyphicon glyphicon-trash mt_removebutton" id='+mt_deleterowid+'></div></td><td style="max-width: 350px">'+topic+'</td><td style="max-width: 150px;">'+remark+'</td></tr>';
            $('#meeting_table tr:last').after(appendrow);
            mt_formclear();
//            $('#mt_btn_addrow').attr('disabled','disabled');
            $('#mt_btn_update').hide();
        }
        else if((topic=='SELECT')&&(remark!=''))
        {
            var msg=errormessage[12].toString().replace('[NAME]','MEETING TOPIC');
            show_msgbox("REPORT SUBMISSION ENTRY",msg,"error",false);
        }
        else
        {
            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[11],"error",false);
        }
    });
// FUNCTION FOR MEETING FORM CLEAR
    function mt_formclear(){
        $('#mt_lb_topic').val('SELECT').show();
        $('#mt_ta_remark').val('').height('22');
    }
// CLICK EVENT FOR MEETING REMOVE BUTTON
    $(document).on("click",'.mt_removebutton', function (){
        $(this).closest('tr').remove();
        mt_formclear();
        $('#mt_btn_update').hide();
        $('#mt_btn_addrow').show();
        return false;
    });
//CLICK EVENT FOR MEETING EDIT BUTTON
    $(document).on("click",'.mt_editbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#mt_rowid').val(rowid);
        $('#mt_btn_addrow').hide();
        $('#mt_btn_update').show();
        var $tds= $(this).closest('tr').children('td'),
            mt_topic = $tds.eq(1).text(),
            mt_remarks = $tds.eq(2).text();
        $('#mt_lb_topic').val(mt_topic);
        $('#mt_ta_remark').val(mt_remarks);
    });
// CLICK EVENT FORM MEETING UPDATE ROW
    $(document).on("click",'.mt_btn_updaterow', function (){
        var mt_topic=$('#mt_lb_topic').val();
        var mt_remarks=$('#mt_ta_remark').val();
        var mt_rowid=$('#mt_rowid').val();
        if((mt_topic!='SELECT'))
        {
            var objUser = {"mt_id":mt_rowid,"mt_topic":mt_topic,"mt_remarks":mt_remarks};
            var objKeys = ["","mt_topic","mt_remarks"];
            $('#mt_tr_' + objUser.mt_id + ' td').each(function(i) {
                $(this).text(objUser[objKeys[i]]);
            });
            $('#mt_btn_addrow').show();
            $('#mt_btn_update').hide();
        }
        else if((mt_topic=='SELECT')&&(mt_remarks!=''))
        {
            var msg=errormessage[12].toString().replace('[NAME]','MEETING TOPIC');
            show_msgbox("REPORT SUBMISSION ENTRY",msg,"error",false);
            $('#mt_ta_remark').val(mt_remarks);
            $('#mt_btn_addrow').hide();
            $('#mt_btn_update').show();
        }
        else{
            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[11],"error",false);
            $('#mt_lb_topic').val(mt_topic);
            $('#mt_btn_addrow').hide();
            $('#mt_btn_update').show();
        }
//        $('#mt_btn_update,#mt_btn_addrow').attr("disabled", "disabled");
//        mt_formclear();
    });
// FORM VALIDATION FOR BUTTONS
    $(document).on("change",'.meetingform-validation', function (){
        var mt_topic=$('#mt_lb_topic').val();
        var mt_remarks=$('#mt_ta_remark').val();
//        if((mt_topic!='SELECT') && (mt_remarks!=''))
//        {
//            $("#mt_btn_addrow,#mt_btn_update").removeAttr("disabled");
//        }
//        else
//        {
//            $("#mt_btn_addrow,#mt_btn_update").attr("disabled", "disabled");
//        }
    });
// MEETING ADD,DELETE AND UPDATE FUNCTION

//SITE VISIT ADD,DELETE AND UPDATE FUNCTION
    $('#sv_btn_update').hide();
//CLICK EVENT FOR SITEVISIT ADD BUTTON
    $('#sv_btn_addrow').click(function(){
        var desingnation=$('#sv_txt_designation').val();
        var name=$('#sv_txt_name').val();
        var start=$('#sv_txt_start').val();
        var end=$('#sv_txt_end').val();
        var remark=$('#sv_txt_remark').val();
        if((desingnation!='') || (name!='') || (start!='') || (end!='')||(remark!=''))
        {
            var sv_tablerowcount=$('#sv_tbl tr').length;
            var sv_trrowid=sv_tablerowcount;
            if(sv_tablerowcount>1){
                var sv_lastid=$('#sv_tbl tr:last').attr('id');
                var splittrid=sv_lastid.split('tr_');
                sv_trrowid=parseInt(splittrid[1])+1;
            }
            var sv_editid='sv_editrow/'+sv_trrowid;
            var sv_deleterowid='sv_deleterow/'+sv_trrowid;
            var sv_row_id="sv_tr_"+sv_trrowid;
            var appendrow='<tr class="active" id='+sv_row_id+'><td style="max-width: 150px"><div class="col-md-1"><span style="display:block;" class="glyphicon glyphicon-edit sv_editbutton" id='+sv_editid+'></span></div><div class="col-md-1"><span style="display:block;" class="glyphicon glyphicon-trash sv_removebutton" id='+sv_deleterowid+'></div></td><td style="max-width: 250px">'+desingnation+'</td><td style="max-width: 250px">'+name+'</td><td style="max-width: 250px">'+start+'</td><td style="max-width: 250px">'+end+'</td><td style="max-width: 250px">'+remark+'</td></tr>';
            $('#sv_tbl tr:last').after(appendrow);
            sv_formclear()
//            $('#sv_btn_addrow').attr('disabled','disabled');
            $('#sv_btn_update').hide();
        }
        else
        {
            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[11],"error",false);
        }
    });
// FUNCTION FOR SITEVISIT FORM CLEAR
    function sv_formclear(){
        $('#sv_txt_designation').val('');
        $('#sv_txt_name').val('');
        $('#sv_txt_start').val('');
        $('#sv_txt_end').val('');
        $('#sv_txt_remark').val('').height('22');
    }
// CLICK EVENT FOR SITEVISIT REMOVE BUTTON
    $(document).on("click",'.sv_removebutton', function (){
        $(this).closest('tr').remove();
        sv_formclear();
        $('#sv_btn_update').hide();
        $('#sv_btn_addrow').show();
        return false;
    });
//CLICK EVENT FOR SITEVISIT EDIT BUTTON
    $(document).on("click",'.sv_editbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#sv_rowid').val(rowid);
        $('#sv_btn_addrow').hide();
        $('#sv_btn_update').show();
        var $tds = $(this).closest('tr').children('td'),
            sv_desgn = $tds.eq(1).text(),
            sv_name = $tds.eq(2).text(),
            sv_start = $tds.eq(3).text(),
            sv_end = $tds.eq(4).text(),
            sv_remarks = $tds.eq(5).text();
        $('#sv_txt_designation').val(sv_desgn);
        $('#sv_txt_name').val(sv_name);
        $('#sv_txt_start').val(sv_start);
        $('#sv_txt_end').val(sv_end);
        $('#sv_txt_remark').val(sv_remarks);
    });
// CLICK EVENT FORM SITEVISIT UPDATE ROW
    $(document).on("click",'.sv_btn_updaterow', function (){
        var sv_desgn=$('#sv_txt_designation').val();
        var sv_name=$('#sv_txt_name').val();
        var sv_start=$('#sv_txt_start').val();
        var sv_end=$('#sv_txt_end').val();
        var sv_remarks=$('#sv_txt_remark').val();
        var sv_rowid=$('#sv_rowid').val();
        if((sv_desgn!='') || (sv_name!='') || (sv_start!='') || (sv_end!='') ||(sv_remarks!=''))
        {
            var objUser = {"sv_id":sv_rowid,"sv_desgn":sv_desgn,"sv_name":sv_name,"sv_start":sv_start,"sv_end":sv_end,"sv_remark":sv_remarks};
            var objKeys = ["","sv_desgn","sv_name", "sv_start", "sv_end","sv_remark"];
            $('#sv_tr_' + objUser.sv_id + ' td').each(function(i) {
                $(this).text(objUser[objKeys[i]]);
            });
                $('#sv_btn_addrow').show();
                $('#sv_btn_update').hide();
    //        $('#sv_btn_update,#sv_btn_addrow').attr("disabled", "disabled");
                sv_formclear();
        }
        else
        {
            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[11],"error",false);
            $('#sv_btn_addrow').hide();
            $('#sv_btn_update').show();
//        $('#sv_btn_update,#sv_btn_addrow').attr("disabled", "disabled");
            sv_formclear();
        }

    });
// FORM VALIDATION FOR BUTTONS
    $(document).on("change blur",'.sitevisitform-validation', function (){
        var sv_design=$('#sv_txt_designation').val();
        var sv_name=$('#sv_txt_name').val();
        var sv_start=$('#sv_txt_start').val();
        var sv_end=$('#sv_txt_end').val();
        var sv_remarks=$('#sv_txt_remark').val();
//        if((sv_design=='') && (sv_name=='') && (sv_start=='') && (sv_end=='') )
//        {
//            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[11],"error",false);
////            $("#sv_btn_update,#sv_btn_addrow").removeAttr("disabled");
//        }
//        else
//        {
//            $("#sv_btn_update,#sv_btn_addrow").attr("disabled", "disabled");
//        }
    });
////SITE VISIT ADD,DELETE AND UPDATE FUNCTION
//MACHINERY/EQUIPMENT TRANSFER ADD,DELETE,UPDATE ROW FUNCTION
    $('#mtransfer_update').hide();
//CLICK EVENT FOR MACHINERY ADD BUTTON
    $('#mtransfer_addrow').click(function(){
        var mtranser_from=$('#mtranser_from').val();
        var mtransfer_item=$('#mtransfer_item').val();
        var mtransfer_to=$('#mtransfer_to').val();
        var mtransfer_remark=$('#mtransfer_remark').val();
        if(mtransfer_item!='SELECT')
        {
            var mtransfertablerowcount=$('#mtransfer_table tr').length;
            var mtrans_trrowid=mtransfertablerowcount;
            if(mtransfertablerowcount>1){
                var mtrans_lastid=$('#mtransfer_table tr:last').attr('id');
                var splittrid=mtrans_lastid.split('tr_');
                mtrans_trrowid=parseInt(splittrid[1])+1;
            }
            var mtransfereditid='mtransfereditrow/'+mtrans_trrowid;
            var mtransferdeleterowid='mtransferdeleterow/'+mtrans_trrowid;
            var mtransfer_row_id="mtranser_tr_"+mtrans_trrowid;
            var appendrow='<tr class="active" id='+mtransfer_row_id+'><td style="max-width: 150px"><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit mtransfereditbutton" id='+mtransfereditid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash mtransferremovebutton"  id='+mtransferdeleterowid+'></div></td><td style="max-width: 250px">'+mtranser_from+'</td><td style="max-width: 250px">'+mtransfer_item+'</td><td style="max-width: 250px">'+mtransfer_to+'</td><td style="max-width: 250px">'+mtransfer_remark+'</td></tr>';
            $('#mtransfer_table tr:last').after(appendrow);
            mtransferformclear()
//            $('#mtransfer_addrow').attr('disabled','disabled');
            $('#mtransfer_update').hide();
        }
        else if((mtransfer_item=='SELECT') && ((mtranser_from!='') || (mtransfer_to!='') || (mtransfer_remark!='')))
        {
            var msg=errormessage[12].toString().replace('[NAME]','ITEM');
            show_msgbox("REPORT SUBMISSION ENTRY",msg,"error",false);
        }
        else
        {
            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[11],"error",false);
        }
    });
// FUNCTION FOR MACHINERY FORM CLEAR
    function mtransferformclear(){
        $('#mtranser_from').val('');
        $('#mtransfer_item').val('SELECT');
        $('#mtransfer_to').val('');
        $('#mtransfer_remark').val('').height('22');
    }
// CLICK EVENT FOR MACHINERY REMOVE BUTTON
    $(document).on("click",'.mtransferremovebutton', function (){
        $(this).closest('tr').remove();
        mtransferformclear();
        $('#mtransfer_update').hide();
        $('#mtransfer_addrow').show();
        return false;
    });
//CLICK EVENT FOR MACHINERY EDIT BUTTON
    $(document).on("click",'.mtransfereditbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#mtransfer_rowid').val(rowid);
        $('#mtransfer_addrow').hide();
        $('#mtransfer_update').show();
        var $tds =$(this).closest('tr').children('td'),
            mtranser_from = $tds.eq(1).text(),
            mtransfer_item = $tds.eq(2).text(),
            mtransfer_to = $tds.eq(3).text(),
            mtransfer_remark = $tds.eq(4).text();
        $('#mtranser_from').val(mtranser_from);
        $('#mtransfer_item').val(mtransfer_item);
        $('#mtransfer_to').val(mtransfer_to);
        $('#mtransfer_remark').val(mtransfer_remark);
    });
// CLICK EVENT FORM MACHINER UPDATE ROW
    $(document).on("click",'.mtransfer_updaterow', function (){
        var mtranser_from=$('#mtranser_from').val();
        var mtransfer_item=$('#mtransfer_item').val();
        var mtransfer_to=$('#mtransfer_to').val();
        var mtransfer_remark=$('#mtransfer_remark').val();
        var mtransfer_rowid=$('#mtransfer_rowid').val();
        if(mtransfer_item!='SELECT')
        {
            var objUser = {"mtransferid":mtransfer_rowid,"mtranserfrom":mtranser_from,"mtransferitem":mtransfer_item,"mtransferto":mtransfer_to,"mtransferremark":mtransfer_remark};
            var objKeys = ["","mtranserfrom", "mtransferitem", "mtransferto","mtransferremark"];
            $('#mtranser_tr_' + objUser.mtransferid + ' td').each(function(i) {
                $(this).text(objUser[objKeys[i]]);
            });
            $('#mtransfer_addrow').show();
            $('#mtransfer_update').hide();
    //        $('#mtransfer_update,#mtransfer_addrow').attr("disabled", "disabled");
            mtransferformclear();
        }
        else if((mtransfer_item=='SELECT') && ((mtranser_from!='') || (mtransfer_to!='') || (mtransfer_remark!='')))
        {
            var msg=errormessage[12].toString().replace('[NAME]','ITEM');
            show_msgbox("REPORT SUBMISSION ENTRY",msg,"error",false);
            $('#mtransfer_addrow').hide();
            $('#mtransfer_update').show();
//        $('#mtransfer_update,#mtransfer_addrow').attr("disabled", "disabled");
//            mtransferformclear();
            $('#mtranser_from').val(mtranser_from);
            $('#mtransfer_to').val(mtransfer_to);
            $('#mtransfer_remark').val(mtransfer_remark);
            $('#mtransfer_rowid').val(mtransfer_rowid);
        }
        else
        {
            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[11],"error",false);
            $('#mtransfer_addrow').hide();
            $('#mtransfer_update').show();
//        $('#mtransfer_update,#mtransfer_addrow').attr("disabled", "disabled");
//            mtransferformclear();
            $('#mtransfer_item').val(mtransfer_item);
        }
    });
// FORM VALIDATION FOR BUTTONS
    $(document).on("change blur",'.mtransferform-validation', function (){
        var mtranser_from=$('#mtranser_from').val();
        var mtransfer_item=$('#mtransfer_item').val();
        var mtransfer_to=$('#mtransfer_to').val();
//        if(mtranser_from!="" && mtransfer_item!="" && mtransfer_to!="")
//        {
//            $("#mtransfer_update,#mtransfer_addrow").removeAttr("disabled");
//        }
//        else
//        {
//            $("#mtransfer_update,#mtransfer_addrow").attr("disabled", "disabled");
//        }
    });
//END OF MACHINERY/EQUIPMENT TRANSFER ADD,DELETE,UPDATE ROW FUNCTION
//RENTAL MACHINERY/EQUIPMENT TRANSFER ADD,DELETE,UPDATE FUNCTION//
    $('#rentalmechinery_updaterow').hide();
    $('#rentalmechinery_addrow').click(function(){
        var rental_lorryno=$('#rental_lorryno').val();
        var rental_throwearthstore=$('#rental_throwearthstore').val();
        var rental_throwearthoutside=$('#rental_throwearthoutside').val();
        var rental_start=$('#rental_start').val();
        var rental_end=$('#rental_end').val();
        var rental_remarks=$('#rental_remarks').val();
        if((rental_lorryno!="") || (rental_throwearthstore!='') || (rental_throwearthoutside!='') || (rental_start!='') || (rental_end!='') ||(rental_remarks!=''))
        {
            var rentaltablerowcount=$('#rental_table tr').length;
            var rental_trrowid=rentaltablerowcount;
            if(rentaltablerowcount>1){
                var rental_lastid=$('#rental_table tr:last').attr('id');
                var splittrid=rental_lastid.split('tr_');
                rental_trrowid=parseInt(splittrid[1])+1;
            }
            var rentaleditid='machineryeditrow/'+rental_trrowid;
            var rentaldeleterowid='machinerydeleterow/'+rental_trrowid;
            var rental_row_id="rental_tr_"+rental_trrowid;
            var appendrow='<tr class="active" id='+rental_row_id+'><td style="max-width: 150px"><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit rentalmechinery_editbutton" id='+rentaleditid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash rental_machineryremovebutton"  id='+rentaldeleterowid+'></div></td><td style="max-width: 250px">'+rental_lorryno+'</td><td style="max-width: 250px">'+rental_throwearthstore+'</td><td style="max-width: 250px">'+rental_throwearthoutside+'</td><td style="max-width: 250px">'+rental_start+'</td><td style="max-width: 250px">'+rental_end+'</td><td style="max-width: 250px">'+rental_remarks+'</td>';
            $('#rental_table tr:last').after(appendrow);
//            $('#rentalmechinery_addrow').attr("disabled", "disabled");
            $('#rentalmechinery_updaterow').hide();
            Rentalmachineryclear()
        }
        else{
            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[11],"error",false);
        }
    });
// CLICK EVENT FOR RENTAL MACHINERY REMOVE BUTTON
    $(document).on("click",'.rental_machineryremovebutton', function (){
        $(this).closest('tr').remove();
        Rentalmachineryclear();
        $('#rentalmechinery_addrow').show();
        $('#rentalmechinery_updaterow').hide();
        return false;
    });
//CLICK EVENT FOR RENTAL MACHINERY EDIT BUTTON
    $(document).on("click",'.rentalmechinery_editbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#rentalmechinery_id').val(rowid);
        $('#rentalmechinery_addrow').hide();
        $('#rentalmechinery_updaterow').show();
        var $tds = $(this).closest('tr').children('td'),
            lorry_no = $tds.eq(1).text(),
            store = $tds.eq(2).text(),
            outside = $tds.eq(3).text(),
            start = $tds.eq(4).text(),
            end = $tds.eq(5).text(),
            remarks = $tds.eq(6).text();
        $('#rental_lorryno').val(lorry_no);
        $('#rental_throwearthstore').val(store);
        $('#rental_start').val(start);
        $('#rental_end').val(end);
        $('#rental_remarks').val(remarks);
        $('#rental_throwearthoutside').val(outside);
    });
    // CLICK EVENT FORM RENTAL MACHINERY UPDATE ROW
    $(document).on("click",'.rentalmechineryupdaterow', function (){
        var rental_lorryno=$('#rental_lorryno').val();
        var rental_throwearthstore=$('#rental_throwearthstore').val();
        var rental_throwearthoutside=$('#rental_throwearthoutside').val();
        var rental_start=$('#rental_start').val();
        var rental_end=$('#rental_end').val();
        var rental_remarks=$('#rental_remarks').val();
        var rental_rowid=$('#rentalmechinery_id').val();
        if((rental_lorryno!="") || (rental_throwearthstore!='') || (rental_throwearthoutside!='') || (rental_start!='') || (rental_end!='') ||(rental_remarks!=''))
        {
        var objUser = {"rentalrowid":rental_rowid,"lorryno":rental_lorryno,"throwstore":rental_throwearthstore,"throwoutside":rental_throwearthoutside,"start":rental_start,"end":rental_end,"remarks":rental_remarks};
        var objKeys = ["","lorryno", "throwstore", "throwoutside","start","end","remarks"];
        $('#rental_tr_' + objUser.rentalrowid + ' td').each(function(i) {
            $(this).text(objUser[objKeys[i]]);
        });
            $('#rentalmechinery_addrow').show();
            $('#rentalmechinery_updaterow').hide();
//        $('#rentalmechinery_updaterow,#rentalmechinery_addrow').attr("disabled", "disabled");
            Rentalmachineryclear()
        }
        else{
            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[11],"error",false);
            $('#rentalmechinery_addrow').hide();
            $('#rentalmechinery_updaterow').show();
//        $('#rentalmechinery_updaterow,#rentalmechinery_addrow').attr("disabled", "disabled");
            Rentalmachineryclear()
        }
    });
    function Rentalmachineryclear()
    {
        $('#rental_lorryno').val('');
        $('#rental_throwearthstore').val('');
        $('#rental_throwearthoutside').val('');
        $('#rental_start').val('');
        $('#rental_end').val('');
        $('#rental_remarks').val('').height('22');
    }
    // FORM VALIDATION FOR BUTTONS
    $(document).on("change blur",'.rentalform-validation', function (){
        var rental_lorryno=$('#rental_lorryno').val();
        var rental_throwearthstore=$('#rental_throwearthstore').val();
        var rental_throwearthoutside=$('#rental_throwearthoutside').val();
        var rental_start=$('#rental_start').val();
        var rental_end=$('#rental_end').val();
//        if(rental_lorryno!="" && rental_throwearthstore!="" && rental_throwearthoutside!="" && rental_start!='' && rental_end!='')
//        {
//            $("#rentalmechinery_updaterow,#rentalmechinery_addrow").removeAttr("disabled");
//        }
//        else
//        {
//            $("#rentalmechinery_updaterow,#rentalmechinery_addrow").attr("disabled", "disabled");
//        }
    });
//RENTAL MACHINERY USAGE ADD,DELETE AND UPDATE FUNCTION
//CLICK EVENT FOR MACHINERY ADD BUTTON
    $('#machinery_update').hide();
    $('#machinery_addrow').click(function(){
        var machinerytype=$('#machinery_type').val();
        var machinery_start=$('#machinery_start').val();
        var machinery_end=$('#machinery_end').val();
        var machinery_remarks=$('#machinery_remarks').val();
        if((machinerytype!="SELECT"))
        {
            var machinerytablerowcount=$('#machinery_table tr').length;
            var machinery_trrowid=machinerytablerowcount;
            if(machinerytablerowcount>1){
                var machinery_lastid=$('#machinery_table tr:last').attr('id');
                var splittrid=machinery_lastid.split('tr_');
                machinery_trrowid=parseInt(splittrid[1])+1;
            }
            var machineryeditid='machineryeditrow/'+machinery_trrowid;
            var machinerydeleterowid='machinerydeleterow/'+machinery_trrowid;
            var machinery_row_id="machinery_tr_"+machinery_trrowid;
            var appendrow='<tr class="active" id='+machinery_row_id+'><td style="max-width: 150px"><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit machineryeditbutton" id='+machineryeditid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash machineryremovebutton"  id='+machinerydeleterowid+'><div></td><td style="max-width: 250px">'+machinerytype+'</td><td style="max-width: 250px">'+machinery_start+'</td><td style="max-width: 250px">'+machinery_end+'</td><td style="max-width: 250px">'+machinery_remarks+'</td></tr>';
            $('#machinery_table tr:last').after(appendrow);
            machineryformclear();
//            $('#machinery_addrow').attr('disabled','disabled');
            $('#machinery_update').hide();
        }
        else if((machinerytype=='SELECT') && ((machinery_start!='') || (machinery_end!='') || (machinery_remarks!='')))
        {
            var msg=errormessage[12].toString().replace('[NAME]','MACHINERY TYPE');
            show_msgbox("REPORT SUBMISSION ENTRY",msg,"error",false);
        }
        else
        {
            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[11],"error",false);
        }
    });
    // FUNCTION FOR MACHINERY FORM CLEAR
    function machineryformclear(){
        $('#machinery_type').val('SELECT').show();
        $('#machinery_start').val('');
        $('#machinery_end').val('');
        $('#machinery_remarks').val('').height('22');
    }
    // CLICK EVENT FOR MACHINERY REMOVE BUTTON
    $(document).on("click",'.machineryremovebutton', function (){
        $(this).closest('tr').remove();
        machineryformclear();
        $('#machinery_addrow').show();
        $('#machinery_update').hide();
        return false;
    });
    //CLICK EVENT FOR MACHINERY EDIT BUTTON
    $(document).on("click",'.machineryeditbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#machinery_rowid').val(rowid);
        $('#machinery_addrow').hide();
        $('#machinery_update').show();
        var $tds = $(this).closest('tr').children('td'),
            machinery_type = $tds.eq(1).text(),
            machinery_start = $tds.eq(2).text(),
            machinery_end = $tds.eq(3).text(),
            machinery_remarks = $tds.eq(4).text();
        $('#machinery_type').val(machinery_type);
        $('#machinery_start').val(machinery_start);
        $('#machinery_end').val(machinery_end);
        $('#machinery_remarks').val(machinery_remarks);
    });
    // CLICK EVENT FORM MACHINER UPDATE ROW
    $(document).on("click",'.machinery_updaterow', function (){
        var machinery_type=$('#machinery_type').val();
        var machinery_start=$('#machinery_start').val();
        var machinery_end=$('#machinery_end').val();
        var machinery_remarks=$('#machinery_remarks').val();
        var machinery_rowid=$('#machinery_rowid').val();
        if((machinery_type!="SELECT"))
        {
        var objUser = {"machineryid":machinery_rowid,"machinerytype":machinery_type,"machinerystart":machinery_start,"machineryend":machinery_end,"machineryremark":machinery_remarks};
        var objKeys = ["","machinerytype", "machinerystart", "machineryend","machineryremark"];
        $('#machinery_tr_' + objUser.machineryid + ' td').each(function(i) {
            $(this).text(objUser[objKeys[i]]);
        });
            $('#machinery_addrow').show();
            $('#machinery_update').hide();
//        $('#machinery_update,#machinery_addrow').attr("disabled", "disabled");
            machineryformclear();
         }
        else if((machinery_type=='SELECT')&&((machinery_start!='') || (machinery_end!='') || (machinery_remarks!='')))
        {
            var msg=errormessage[12].toString().replace('[NAME]','MACHINERY TYPE');
            show_msgbox("REPORT SUBMISSION ENTRY",msg,"error",false);
            $('#machinery_addrow').hide();
            $('#machinery_update').show();
//        $('#machinery_update,#machinery_addrow').attr("disabled", "disabled");
//            machineryformclear();
            $('#machinery_start').val(machinery_start);
            $('#machinery_end').val(machinery_end);
            $('#machinery_remarks').val(machinery_remarks);
        }
        else
         {
        show_msgbox("REPORT SUBMISSION ENTRY",errormessage[11],"error",false);
             $('#machinery_addrow').hide();
             $('#machinery_update').show();
//        $('#machinery_update,#machinery_addrow').attr("disabled", "disabled");
//            machineryformclear();
            $('#machinery_type').val(machinery_type);
        }
    });
    // FORM VALIDATION FOR BUTTONS
    $(document).on("change blur",'.machineryform-validation', function (){
        var machinery_type=$('#machinery_type').val();
        var machinery_start=$('#machinery_start').val();
        var machinery_end=$('#machinery_end').val();
//        if(machinery_type!="SELECT" && machinery_start!="" && machinery_end!="")
//        {
//            $("#machinery_update,#machinery_addrow").removeAttr("disabled");
//        }
//        else
//        {
//            $("#machinery_update,#machinery_addrow").attr("disabled", "disabled");
//        }
    });
//END OF MACHINERY USAGE ADD,DELETE AND UPDATE FUNCTION
//EQUIPMENT USAGE ADD,DELETE AND UPDATE FUNCTION
    $('#equipment_update').hide();
//CLICK EVENT FOR MACHINERY ADD BUTTON
    $('#equipment_addrow').click(function(){
        var equipment_aircompressor=$('#equipment_aircompressor').val();
        var equipment_lorryno=$('#equipment_lorryno').val();
        var equipment_start=$('#equipment_start').val();
        var equipment_end=$('#equipment_end').val();
        var equipment_remark=$('#equipment_remark').val();
        if((equipment_aircompressor!="") || (equipment_lorryno!='') || (equipment_start!='') || (equipment_end!='') ||(equipment_remark!=''))
        {
            var equipmenttablerowcount=$('#equipment_table tr').length;
            var equipment_trrowid=equipmenttablerowcount;
            if(equipmenttablerowcount>1){
                var equipment_lastid=$('#equipment_table tr:last').attr('id');
                var splittrid=equipment_lastid.split('tr_');
                equipment_trrowid=parseInt(splittrid[1])+1;
            }
            var equipmenteditid='equipmenteditrow/'+equipment_trrowid;
            var equipmentdeleterowid='equipementdeleterow/'+equipment_trrowid;
            var equipment_row_id="equipment_tr_"+equipment_trrowid;
            var appendrow='<tr class="active" id='+equipment_row_id+'><td style="max-width: 150px"><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit equipmenteditbutton" id='+equipmenteditid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash equipmentremovebutton" id='+equipmentdeleterowid+'></div></td><td style="max-width: 250px">'+equipment_aircompressor+'</td><td style="max-width: 250px">'+equipment_lorryno+'</td><td style="max-width: 250px">'+equipment_start+'</td><td style="max-width: 250px">'+equipment_end+'</td><td style="max-width: 250px">'+equipment_remark+'</td></tr>';
            $('#equipment_table tr:last').after(appendrow);
            equipmentformclear()
//            $('#equipment_addrow').attr('disabled','disabled');
            $('#equipment_update').hide();
        }
        else
        {
            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[11],"error",false);
        }
    });
// FUNCTION FOR MACHINERY FORM CLEAR
    function equipmentformclear(){
        $('#equipment_aircompressor').val('');
        $('#equipment_lorryno').val('');
        $('#equipment_start').val('');
        $('#equipment_end').val('');
        $('#equipment_remark').val('').height('22');
    }
// CLICK EVENT FOR MACHINERY REMOVE BUTTON
    $(document).on("click",'.equipmentremovebutton', function (){
        $(this).closest('tr').remove();
        equipmentformclear()
        $('#equipment_addrow').show();
        $('#equipment_update').hide();
        return false;
    });
//CLICK EVENT FOR MACHINERY EDIT BUTTON
    $(document).on("click",'.equipmenteditbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#equipment_rowid').val(rowid);
        $('#equipment_addrow').hide();
        $('#equipment_update').show();
        var $tds = $(this).closest('tr').children('td'),
            equipment_aircompressor = $tds.eq(1).text(),
            equipment_lorryno = $tds.eq(2).text(),
            equipment_start = $tds.eq(3).text(),
            equipment_end = $tds.eq(4).text(),
            equipment_remark = $tds.eq(5).text();
        $('#equipment_aircompressor').val(equipment_aircompressor);
        $('#equipment_lorryno').val(equipment_lorryno);
        $('#equipment_start').val(equipment_start);
        $('#equipment_end').val(equipment_end);
        $('#equipment_remark').val(equipment_remark);
    });
// CLICK EVENT FORM MACHINER UPDATE ROW
    $(document).on("click",'.equipment_updaterow', function (){
        var equipment_aircompressor=$('#equipment_aircompressor').val();
        var equipment_lorryno=$('#equipment_lorryno').val();
        var equipment_start=$('#equipment_start').val();
        var equipment_end=$('#equipment_end').val();
        var equipment_remark=$('#equipment_remark').val();
        var equipment_rowid=$('#equipment_rowid').val();
        if((equipment_aircompressor!="") || (equipment_lorryno!='') || (equipment_start!='') || (equipment_end!='') ||(equipment_remark!=''))
        {
        var objUser = {"equipmentrowid":equipment_rowid,"equipmentaircompressor":equipment_aircompressor,"equipmentlorryno":equipment_lorryno,"equipmentstart":equipment_start,"equipmentend":equipment_end,"equipmentremark":equipment_remark};
        var objKeys = ["","equipmentaircompressor", "equipmentlorryno", "equipmentstart","equipmentend","equipmentremark"];
        $('#equipment_tr_' + objUser.equipmentrowid + ' td').each(function(i) {
            $(this).text(objUser[objKeys[i]]);
        });
            $('#equipment_addrow').show();
            $('#equipment_update').hide();
//        $('#equipment_update,#equipment_addrow').attr("disabled", "disabled");
            equipmentformclear();
        }
        else
        {
            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[11],"error",false);
            $('#equipment_addrow').hide();
            $('#equipment_update').show();
//        $('#equipment_update,#equipment_addrow').attr("disabled", "disabled");
            equipmentformclear();
        }

    });

// FORM VALIDATION FOR BUTTONS
    $(document).on("change blur",'.equipmentform-validation', function (){
        var equipment_aircompressor=$('#equipment_aircompressor').val();
        var equipment_lorryno=$('#equipment_lorryno').val();
        var equipment_start=$('#equipment_start').val();
        var equipment_end=$('#equipment_end').val();
//        if(equipment_aircompressor!="" && equipment_lorryno!="" && equipment_start!="" && equipment_end!='')
//        {
//            $('#equipment_update,#equipment_addrow').removeAttr("disabled");
//        }
//        else
//        {
//            $('#equipment_update,#equipment_addrow').attr("disabled", "disabled");
//        }
    });
    //END OF EQUIPMENT USAGE ADD,DELETE AND UPDATE FUNCTION
//FITTING  USAGE TABLE ADD FUNCTION//
    //*****ADD NEW ROW********//
    $('#fitting_updaterow').hide();
    $(document).on("click",'#fitting_addrow', function (){
        var items=$('#fitting_items').val();
        var size=$('#fitting_size').val();
        var qty=$('#fitting_quantity').val();
        var remarks=$('#fitting_remarks').val();
        if((items!="SELECT"))
        {
            var tablerowCount=$('#fitting_table tr').length;
            var fitting_trrowid=tablerowCount;
            if(tablerowCount>1){
                var fitting_lastid=$('#fitting_table tr:last').attr('id');
                var splittrid=fitting_lastid.split('tr_');
                fitting_trrowid=parseInt(splittrid[1])+1;
            }
            var editid='fitting_editrow/'+fitting_trrowid;
            var deleterowid='fitting_deleterow/'+fitting_trrowid;
            var row_id="fitting_tr_"+fitting_trrowid;
            var appendrow='<tr  class="active" id='+row_id+'><td style="max-width: 150px"><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit fitting_editbutton" id='+editid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash fitting_removebutton"  id='+deleterowid+'></div></td><td style="max-width: 250px">'+items+'</td><td style="max-width: 250px">'+size+'</td><td style="max-width: 250px">'+qty+'</td><td style="max-width: 250px">'+remarks+'</td></tr>';
            $('#fitting_table tr:last').after(appendrow);
//            $("#fitting_addrow").attr("disabled", "disabled");
            $('#fitting_updaterow').hide();
            fittingformclear();
        }
        else if((items=="SELECT") && ((size!='') || (qty!='') || (remarks!='')))
        {
            var msg=errormessage[12].toString().replace('[NAME]','FITTINGS ITEM');
            show_msgbox("REPORT SUBMISSION ENTRY",msg,"error",false);
        }
        else
        {
            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[11],"error",false);
        }
    });
    //**********DELETE ROW*************//
    $(document).on("click",'.fitting_removebutton', function (){
        $('#fitting_updaterow').hide();
        $(this).closest('tr').remove();
        $("#fitting_addrow").show();
        fittingformclear();
        return false;
    });
    //**********EDIT ROW**************//
    $(document).on("click",'.fitting_editbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#fitting_id').val(rowid);
        $('#fitting_addrow').hide();
        $("#fitting_updaterow").show();
        var $tds = $(this).closest('tr').children('td'),
            items = $tds.eq(1).text(),
            size = $tds.eq(2).text(),
            quantity = $tds.eq(3).text(),
            remark = $tds.eq(4).text();
        $('#fitting_items').val(items);
        $('#fitting_size').val(size);
        $('#fitting_quantity').val(quantity);
        $('#fitting_remarks').val(remark);
    });
    //********UPDATE ROW****************//
    $(document).on("click",'.fittingupdaterow', function (){
        var items=$('#fitting_items').val();
        var size=$('#fitting_size').val();
        var qty=$('#fitting_quantity').val();
        var remarks=$('#fitting_remarks').val();
        var rowid=$('#fitting_id').val();
        if((items!="SELECT"))
        {
        var objUser = {"id":rowid,"items":items,"size":size,"quantity":qty,"remark":remarks};
        var objKeys = ["","items", "size", "quantity","remark"];
        $('#fitting_tr_' + objUser.id + ' td').each(function(i) {
            $(this).text(objUser[objKeys[i]]);
        });
            $('#fitting_addrow').show();
            $('#fitting_updaterow').hide();
//        $('#fitting_addrow,#fitting_updaterow').attr("disabled", "disabled");
            fittingformclear();
        }
        else if((items=="SELECT") && ((size!='') || (qty!='') || (remarks!='')))
        {
            var msg=errormessage[12].toString().replace('[NAME]','FITTINGS ITEM');
            show_msgbox("REPORT SUBMISSION ENTRY",msg,"error",false);
            $('#fitting_addrow').hide();
            $('#fitting_updaterow').show();
//        $('#fitting_addrow,#fitting_updaterow').attr("disabled", "disabled");
//            fittingformclear();
            $('#fitting_size').val(size);
            $('#fitting_quantity').val(qty);
            $('#fitting_remarks').val(remarks);
        }
        else{
            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[11],"error",false);
            $('#fitting_addrow').hide();
            $('#fitting_updaterow').show();
//        $('#fitting_addrow,#fitting_updaterow').attr("disabled", "disabled");
//            fittingformclear();
            $('#fitting_items').val(items);
        }
    });
    //*****FITTING FORM CLEAR**********//
    function fittingformclear()
    {
        $('#fitting_items').val('SELECT').show();
        $('#fitting_size').val('');
        $('#fitting_quantity').val('');
        $('#fitting_remarks').val('').height('22');
    }
    $(document).on("change blur",'.fittingform-validation', function (){
        var items=$('#fitting_items').val();
        var size=$('#fitting_size').val();
        var qty=$('#fitting_quantity').val();
//        if(items!="SELECT" && size!="" && qty!="")
//        {
//            $('#fitting_addrow,#fitting_updaterow').removeAttr("disabled");
//        }
//        else
//        {
//            $('#fitting_addrow,#fitting_updaterow').attr("disabled", "disabled");
//        }
    });
//END OF FITTING  USAGE TABLE ADD FUNCTION//
//MATERIAL USAGE ADD,DELETE AND UPDATE ROW FUNCTION//
    $('#material_updaterow').hide();
    $(document).on("click",'#material_addrow', function (){
        var items=$('#material_items').val();
        var receipt=$('#material_receipt').val();
        var qty=$('#material_quantity').val();
        if((items!="SELECT"))
        {
            var tablerowCount=$('#material_table tr').length;
            var mat_trrowid=tablerowCount;
            if(tablerowCount>1){
                var mat_lastid=$('#material_table tr:last').attr('id');
                var splittrid=mat_lastid.split('tr_');
                mat_trrowid=parseInt(splittrid[1])+1;
            }
            var editid='material_editrow/'+mat_trrowid;
            var deleterowid='material_deleterow/'+mat_trrowid;
            var row_id="material_tr_"+mat_trrowid;
            var appendrow='<tr class="active" id='+row_id+'><td style="max-width: 150px"><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit material_editbutton" id='+editid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash material_removebutton"  id='+deleterowid+'></div></td><td style="max-width: 250px">'+items+'</td><td style="max-width: 250px">'+receipt+'</td><td style="max-width: 250px">'+qty+'</td></tr>';
            $('#material_table tr:last').after(appendrow);
//            $("#material_addrow").attr("disabled","disabled");
            $('#material_updaterow').hide();
            MATERIALformclear();
        }
        else if((items=="SELECT") && ((receipt!='') || (qty!='')))
        {
            var msg=errormessage[12].toString().replace('[NAME]','MATERIAL ITEM');
            show_msgbox("REPORT SUBMISSION ENTRY",msg,"error",false);
        }
        else
        {
            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[11],"error",false);
        }
    });
    //**********DELETE ROW*************//
    $(document).on("click",'.material_removebutton', function (){
        $('#material_updaterow').hide();
        $(this).closest('tr').remove();
        $("#material_addrow").show();
        MATERIALformclear();
        return false;
    });
    // **********EDIT ROW**************//
    $(document).on("click",'.material_editbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#material_id').val(rowid);
        $('#material_addrow').hide();
        $('#material_updaterow').show();
        var $tds = $(this).closest('tr').children('td'),
            items = $tds.eq(1).text(),
            receipt = $tds.eq(2).text(),
            quantity = $tds.eq(3).text();
        $('#material_items').val(items);
        $('#material_receipt').val(receipt);
        $('#material_quantity').val(quantity);
    });
    //********UPDATE ROW****************//
    $(document).on("click",'.materialupdaterow', function (){
        var material_items=$('#material_items').val();
        var material_receipt=$('#material_receipt').val();
        var material_quantity=$('#material_quantity').val();
        var material_id=$('#material_id').val();
        if((material_items!="SELECT"))
        {
        var objUser = {"materialid":material_id,"materialitems":material_items,"materialreceipt":material_receipt,"materialquantity":material_quantity};
        var objKeys = ["","materialitems", "materialreceipt", "materialquantity"];
        $('#material_tr_' + objUser.materialid + ' td').each(function(i) {
            $(this).text(objUser[objKeys[i]]);
        });
            $('#material_addrow').show();
            $('#material_updaterow').hide();
//        $('#material_addrow,#material_updaterow').attr("disabled", "disabled");
            MATERIALformclear();
        }
        else if((material_items=="SELECT") && ((material_receipt!='') || (material_quantity!='')))
        {
            var msg=errormessage[12].toString().replace('[NAME]','MATERIAL ITEM');
            show_msgbox("REPORT SUBMISSION ENTRY",msg,"error",false);
            $('#material_addrow').hide();
            $('#material_updaterow').show();
//        $('#material_addrow,#material_updaterow').attr("disabled", "disabled");
//            MATERIALformclear();
            $('#material_receipt').val(material_receipt);
            $('#material_quantity').val(material_quantity);
        }
        else
        {
            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[11],"error",false);
            $('#material_addrow').hide();
            $('#material_updaterow').show();
//        $('#material_addrow,#material_updaterow').attr("disabled", "disabled");
//            MATERIALformclear();
            $('#material_items').val(material_items);
        }
    });
    //*****MATERIAL FORM CLEAR**********//
    function MATERIALformclear() {
        $('#material_items').val('SELECT').show();
        $('#material_receipt').val('');
        $('#material_quantity').val('');
    }
    $(document).on("change blur",'.materialform-validation', function (){
        var items=$('#material_items').val();
        var receipt=$('#material_receipt').val();
        var qty=$('#material_quantity').val();
//        if(items!="SELECT" && receipt!="" && qty!="")
//        {
//            $('#material_addrow,#material_updaterow').removeAttr("disabled");
//        }
//        else
//        {
//            $('#material_addrow,#material_updaterow').attr("disabled", "disabled");
//        }
    });
//END OF MATERIAL USAGE ADD,DELETE AND UPDATE ROW FUNCTION//
//SITE STOCK USAGE ADD,DELETE AND UPDATE ROW FUNCTION//
    $('#stock_updaterow').hide();
    $(document).on("click",'#stock_addrow', function (){
        var itemno=$('#stock_itemno').val();
        var itemname=$('#stock_itemname').val();
        var qty=$('#stock_quantity').val();
        if((itemno!="SELECT") && (itemname!='') && (qty!=''))
        {
            var tablerowCount=$('#stockusage_table tr').length;
            var stk_trrowid=tablerowCount;
            if(tablerowCount>1){
                var stk_lastid=$('#stockusage_table tr:last').attr('id');
                var splittrid=stk_lastid.split('tr_');
                stk_trrowid=parseInt(splittrid[1])+1;
            }
            var editid='stock_editrow/'+stk_trrowid;
            var deleterowid='stock_deleterow/'+stk_trrowid;
            var row_id="stock_tr_"+stk_trrowid;
            var appendrow='<tr class="active" id='+row_id+'><td style="max-width: 150px"><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit stock_editbutton" id='+editid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash stock_removebutton"  id='+deleterowid+'></div></td><td style="max-width: 250px">'+itemno+'</td><td style="max-width: 250px">'+itemname+'</td><td style="max-width: 250px">'+qty+'</td></tr>';
            $('#stockusage_table tr:last').after(appendrow);
//            $("#stock_addrow").attr("disabled","disabled");
            $('#stock_updaterow').hide();
            stockformclear();
        }
        else if((itemno=="SELECT") && ((itemname=='') || (qty!='')))
        {
            var msg=errormessage[12].toString().replace('[NAME]','SITE STOCK ITEM');
            show_msgbox("REPORT SUBMISSION ENTRY",msg,"error",false);
        }
        else
        {
            var errmsg=errormessage[11].toString().replace('ANY ONE', 'ALL');
            show_msgbox("REPORT SUBMISSION ENTRY",errmsg,"error",false);
        }
    });
    //**********DELETE ROW*************//
    $(document).on("click",'.stock_removebutton', function (){
        $('#stock_updaterow').hide();
        $(this).closest('tr').remove();
        $("#stock_addrow").show();
        stockformclear();
        return false;
    });
    // **********EDIT ROW**************//
    $(document).on("click",'.stock_editbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#stock_id').val(rowid);
        $('#stock_addrow').hide();
        $('#stock_updaterow').show();
        var $tds = $(this).closest('tr').children('td'),
            itemno = $tds.eq(1).text(),
            itemname = $tds.eq(2).text(),
            quantity = $tds.eq(3).text();
        $('#stock_itemno').val(itemno);
        $('#stock_itemname').val(itemname);
        $('#stock_quantity').val(quantity);
    });
    //********UPDATE ROW****************//
    $(document).on("click",'.stockupdaterow', function (){
        var stock_itemno=$('#stock_itemno').val();
        var stock_itemname=$('#stock_itemname').val();
        var stock_quantity=$('#stock_quantity').val();
        var stock_id=$('#stock_id').val();
        if((stock_itemno!="SELECT") && (stock_itemname!='') && (stock_quantity!=''))
        {
            var objUser = {"stockid":stock_id,"stockitemno":stock_itemno,"stockitemname":stock_itemname,"stockquantity":stock_quantity};
            var objKeys = ["","stockitemno", "stockitemname", "stockquantity"];
            $('#stock_tr_' + objUser.stockid + ' td').each(function(i) {
                $(this).text(objUser[objKeys[i]]);
            });
            $('#stock_addrow').show();
            $('#stock_updaterow').hide();
//        $('#stock_addrow,#stock_updaterow').attr("disabled", "disabled");
            stockformclear();
        }
        else if((stock_itemno=="SELECT") && ((stock_itemname=='') || (stock_quantity!='')))
        {
            var msg=errormessage[12].toString().replace('[NAME]','SITE STOCK ITEM');
            show_msgbox("REPORT SUBMISSION ENTRY",msg,"error",false);
            $('#stock_addrow').hide();
            $('#stock_updaterow').show();
//        $('#stock_addrow,#stock_updaterow').attr("disabled", "disabled");
//            stockformclear();
        }
        else
        {
            var errmsg=errormessage[11].toString().replace('ANY ONE', 'ALL');
            show_msgbox("REPORT SUBMISSION ENTRY",errmsg,"error",false);
            $('#stock_addrow').hide();
            $('#stock_updaterow').show();
//        $('#stock_addrow,#stock_updaterow').attr("disabled", "disabled");
//            stockformclear();
        }
    });
    //*****STOCK FORM CLEAR**********//
    function stockformclear() {
        $('#stock_itemno').val('SELECT').show();
        $('#stock_itemname').val('');
        $('#stock_quantity').val('');
    }
    $(document).on("change blur",'.stockform-validation', function (){
        var itemno=$('#stock_itemno').val();
        var itemname=$('#stock_itemname').val();
        var qty=$('#stock_quantity').val();
//        if(itemno!="SELECT" && itemname!="" && qty!="")
//        {
//            $('#stock_addrow,#stock_updaterow').removeAttr("disabled");
//        }
//        else
//        {
//            $('#stock_addrow,#stock_updaterow').attr("disabled", "disabled");
//        }
    });
//END OF SITE STOCK USAGE ADD,DELETE AND UPDATE ROW FUNCTION//
// full form clear
    function form_clear(){
        $('#tr_txt_location').val('');
        $('#tr_lb_contractno').val('SELECT');
        $('#tr_txt_date').val('');
        $('#tr_txt_weather').val('');
        $('#tr_txt_wftime').val('');
        $('#tr_txt_wttime').val('');
        $('#tr_txt_reachsite').val('');
        $('#tr_txt_leavesite').val('');
        $('input:checkbox').removeAttr('checked');
        $('#jd_chk_roadm').val('');
        $('#jd_chk_roadmm').val('');
        $('#jd_chk_concm').val('');
        $('#jd_chk_concmm').val('');
        $('#jd_chk_trufm').val('');
        $('#jd_chk_trufmm').val('');
        $('#jd_txt_testing').val('');
        $('#jd_txt_start').val('');
        $('#jd_txt_end').val('');
        $('#jd_ta_remark').val('');
        $('#mt_lb_topic').val('SELECT').show();
        $('#mt_ta_remark').val('');
        $("#jd_chk_roadm").attr("disabled", "disabled");
        $("#jd_chk_roadmm").attr("disabled", "disabled");
        $("#jd_chk_concm").attr("disabled", "disabled");
        $("#jd_chk_concmm").attr("disabled", "disabled");
        $("#jd_chk_trufm").attr("disabled", "disabled");
        $("#jd_chk_trufmm").attr("disabled", "disabled");
        $('textarea').height('22');
        sv_formclear();
        mtransferformclear();
        Rentalmachineryclear();
        machineryformclear();
        equipmentformclear();
        fittingformclear();
        MATERIALformclear();
        stockformclear();
        mt_formclear();
        $('#Employee_table tr:not(:first)').remove();
        $('#sv_tbl tr:not(:first)').remove();
        $('#mtransfer_table tr:not(:first)').remove();
        $('#rental_table tr:not(:first)').remove();
        $('#machinery_table tr:not(:first)').remove();
        $('#equipment_table tr:not(:first)').remove();
        $('#fitting_table tr:not(:first)').remove();
        $('#material_table tr:not(:first)').remove();
        $('#stockusage_table tr:not(:first)').remove();
        $('#meeting_table tr:not(:first)').remove();
        canvas.clear();
        $("#divImage").empty();
        $('#myTab a:first').tab('show');
        $('#nextbtn').attr("disabled","disabled").show();
        $('#prevbtn').hide();
        $('#Final_submit').hide();
        ncount=1;
    }
//ALREADY EXIST FORM CLEAR
    function existsform_clear(){
        $('#tr_txt_date').val('');
        $('#tr_txt_weather').val('');
        $('#tr_txt_wftime').val('');
        $('#tr_txt_wttime').val('');
        $('#tr_txt_reachsite').val('');
        $('#tr_txt_leavesite').val('');
        $('input:checkbox').removeAttr('checked');
        $('#jd_chk_roadm').val('');
        $('#jd_chk_roadmm').val('');
        $('#jd_chk_concm').val('');
        $('#jd_chk_concmm').val('');
        $('#jd_chk_trufm').val('');
        $('#jd_chk_trufmm').val('');
        $('#jd_txt_testing').val('');
        $('#jd_txt_start').val('');
        $('#jd_txt_end').val('');
        $('#jd_ta_remark').val('');
        $("#jd_chk_roadm").attr("disabled", "disabled");
        $("#jd_chk_roadmm").attr("disabled", "disabled");
        $("#jd_chk_concm").attr("disabled", "disabled");
        $("#jd_chk_concmm").attr("disabled", "disabled");
        $("#jd_chk_trufm").attr("disabled", "disabled");
        $("#jd_chk_trufmm").attr("disabled", "disabled");
        $('textarea').height('22');
        sv_formclear();
        mtransferformclear();
        Rentalmachineryclear();
        machineryformclear();
        equipmentformclear();
        fittingformclear();
        MATERIALformclear();
        stockformclear();
        mt_formclear();
        $('#Employee_table tr:not(:first)').remove();
        $('#sv_tbl tr:not(:first)').remove();
        $('#mtransfer_table tr:not(:first)').remove();
        $('#rental_table tr:not(:first)').remove();
        $('#machinery_table tr:not(:first)').remove();
        $('#equipment_table tr:not(:first)').remove();
        $('#fitting_table tr:not(:first)').remove();
        $('#material_table tr:not(:first)').remove();
        $('#stockusage_table tr:not(:first)').remove();
        $('#meeting_table tr:not(:first)').remove();
        canvas.clear();
        $('#myTab a:first').tab('show');
        $('#nextbtn').attr("disabled","disabled").show();
        ncount=1;
    }
//FINAL SUBMIT BUTTON VALIDATION
    $(document).on('change blur','#entryform',function(){
//        var location=$('#tr_txt_location').val();
//        var contractno=$('#tr_lb_contractno').val();
//        var teamname=$('#tr_tb_team').val();
        var reportdate=$('#tr_txt_date').val();
        var weather=$('#tr_txt_weather').val();
        var reachsite=$('#tr_txt_reachsite').val();
        var leavesite=$('#tr_txt_leavesite').val();
        var jobtype = $("input[id=jobtype]").is(":checked");
        var roadchk=$("input[id=jd_chk_road]").is(":checked");
        var concchk=$("input[id=jd_chk_contc]").is(":checked");
        var turfchk=$("input[id=jd_chk_truf]").is(":checked");
        var roadm=$('#jd_chk_roadm').val();
        var roadmm=$('#jd_chk_roadmm').val();
        var concm=$('#jd_chk_concm').val();
        var concmm=$('#jd_chk_concmm').val();
        var trufm=$('#jd_chk_trufm').val();
        var trufmm=$('#jd_chk_trufmm').val();
//        var pipetesting=$('#jd_txt_testing').val();
//        var startpressure=$('#jd_txt_start').val();
//        var endpressure=$('#jd_txt_end').val();
////        var employeetable=$('#Employee_table tr').length;
//        // pipelaid validation
        if(roadchk==true){
            $('#jd_chk_roadm').removeAttr('disabled');
            $('#jd_chk_roadmm').removeAttr('disabled');
        }
        else{
            $("#jd_chk_roadm").attr("disabled", "disabled");
            $("#jd_chk_roadmm").attr("disabled", "disabled");
            $("#jd_chk_roadm").val('');
            $("#jd_chk_roadmm").val('');
        }
        if(concchk==true){
            $('#jd_chk_concm').removeAttr('disabled');
            $('#jd_chk_concmm').removeAttr('disabled');
        }
        else{
            $("#jd_chk_concm").attr("disabled", "disabled");
            $("#jd_chk_concmm").attr("disabled", "disabled");
            $("#jd_chk_concm").val('');
            $("#jd_chk_concmm").val('');
        }
        if(turfchk==true){
            $('#jd_chk_trufm').removeAttr('disabled');
            $('#jd_chk_trufmm').removeAttr('disabled');
        }
        else{
            $("#jd_chk_trufm").attr("disabled", "disabled");
            $("#jd_chk_trufmm").attr("disabled", "disabled");
            $("#jd_chk_trufm").val('');
            $("#jd_chk_trufmm").val('');
        }
        //weather time validation
        if(weather!=''){
            $('#tr_txt_wftime').removeAttr('disabled');
            $('#tr_txt_wttime').removeAttr('disabled');
        }
        else{
            $("#tr_txt_wftime").attr("disabled", "disabled");
            $("#tr_txt_wttime").attr("disabled", "disabled");
        }
//        if((location!=' ')&&(contractno!='') && (teamname!='SELECT') && (reportdate!='')  && (reachsite!='') && (leavesite!='') && (jobtype==true))
//        {
//            if((pipetesting!='') && (startpressure!='') && (endpressure!=''))
//            {
//                if(roadchk==true){
//                    if((roadm!='') && (roadmm!='')){
//                        var chkflag=1;
//                    }
//                    else{
//                        var chkflag=0;
//                    }
//                }
//                if(concchk==true){
//                    if((concm!='') && (concmm!='')){
//                        var chkflag=1;
//                    }
//                    else{
//                        var chkflag=0;
//                    }
//                }
//                if(turfchk==true){
//                    if((trufm!='') && (trufmm!='')){
//                        var chkflag=1;
//                    }
//                    else{
//                        var chkflag=0;
//                    }
//                }
//                if(concchk==true && turfchk==true){
//                    if((trufm!='') && (trufmm!='') && (concm!='') && (concmm!='')){
//                        var chkflag=1;
//                    }
//                    else{
//                        var chkflag=0;
//                    }
//                }
//                if(concchk==true && roadchk==true){
//                    if((roadm!='') && (roadmm!='') && (concm!='') && (concmm!='')){
//                        var chkflag=1;
//                    }
//                    else{
//                        var chkflag=0;
//                    }
//                }
//                if(roadchk==true && turfchk==true){
//                    if((trufm!='') && (trufmm!='') && (roadm!='') && (roadmm!='')){
//                        var chkflag=1;
//                    }
//                    else{
//                        var chkflag=0;
//                    }
//                }
//                if(roadchk==true && turfchk==true && concchk==true){
//                    if((trufm!='') && (trufmm!='') && (roadm!='') && (roadmm!='') && (concm!='') && (concmm!='')){
//                        var chkflag=1;
//                    }
//                    else{
//                        var chkflag=0;
//                    }
//                }
//                if(canvas.isEmpty())
//                {
//                    chkflag==0;
//                }
//                if(chkflag==1){
//                    $('#Final_submit').removeAttr('disabled');
//                }else{
//                    $('#Final_submit').attr('disabled','disabled');
//                }
//            }
        if(reportdate!='')
        {
            $('#Final_submit').removeAttr('disabled');
        }
        else
        {
            $('#Final_submit').attr('disabled','disabled');
        }
    });
    //CHECK TOPIC
    $(document).on("change",'#mt_lb_topic', function (){
        var meetingrefTab = document.getElementById("meeting_table");
        var mttopic=$('#mt_lb_topic').val();
        var errormsg=errormessage[5].toString().replace("[TOPIC]",mttopic);
        for ( var i = 1; row = meetingrefTab.rows[i]; i++ )
        {
            row = meetingrefTab.rows[i];
            var meetinginnerarray=[];
            col = row.cells[1];
            meetinginnerarray.push(col.firstChild.nodeValue);
            if(mttopic==meetinginnerarray){
                show_msgbox("REPORT SUBMISSION ENTRY",errormsg,"error",false);
                $('#mt_lb_topic').val('SELECT').show();
            }
        }
    });
//check machinary type
    $(document).on("change",'#machinery_type', function (){
        var machineryrefTab = document.getElementById('machinery_table');
        var machinarytopic=$('#machinery_type').val();
        var errormsg=errormessage[7].toString().replace("[TYPE]",machinarytopic);
        for ( var i = 1; row = machineryrefTab.rows[i]; i++ )
        {
            row = machineryrefTab.rows[i];
            var machineryinnerarray=[];
            col = row.cells[1];
            machineryinnerarray.push(col.firstChild.nodeValue);
            if(machinarytopic==machineryinnerarray){
                show_msgbox("REPORT SUBMISSION ENTRY",errormsg,"error",false);
                $('#machinery_type').val('SELECT').show();
            }
        }
    });
    //check mtransfer type
    $(document).on("change",'#mtransfer_item', function (){
        var mtransfertable = document.getElementById('mtransfer_table');
        var mtransferitem=$('#mtransfer_item').val();
        var errormsg=errormessage[8].toString().replace("[ITEM]",mtransferitem)
        for ( var i = 1; row = mtransfertable.rows[i]; i++ )
        {
            row = mtransfertable.rows[i];
            var mtransferinnerarray=[];
            col = row.cells[2];
            mtransferinnerarray.push(col.firstChild.nodeValue);
            if(mtransferitem==mtransferinnerarray){
                show_msgbox("REPORT SUBMISSION ENTRY",errormsg,"error",false);
                $('#mtransfer_item').val('SELECT').show();
            }
        }
    });
    //check fitting item
    $(document).on("change",'#fitting_items', function (){
        var fittingtable = document.getElementById('fitting_table');
        var fittingitem=$('#fitting_items').val();
        var errormsg=errormessage[8].toString().replace("[ITEM]",fittingitem)
        for ( var i = 1; row = fittingtable.rows[i]; i++ )
        {
            row = fittingtable.rows[i];
            var fittinginnerarray=[];
            col = row.cells[1];
            fittinginnerarray.push(col.firstChild.nodeValue);
            if(fittingitem==fittinginnerarray){
                show_msgbox("REPORT SUBMISSION ENTRY",errormsg,"error",false);
                $('#fitting_items').val('SELECT').show();
            }
        }
    });
    //check material item
    $(document).on("change",'#material_items', function (){
        var metrialrefTab = document.getElementById("material_table");
        var materialitem=$('#material_items').val();
        var errormsg=errormessage[8].toString().replace("[ITEM]",materialitem);
        for ( var i = 1; row = metrialrefTab.rows[i]; i++ )
        {
            row = metrialrefTab.rows[i];
            var materialinnerarray=[];
            col = row.cells[1];
            materialinnerarray.push(col.firstChild.nodeValue);
            if(materialitem==materialinnerarray){
                show_msgbox("REPORT SUBMISSION ENTRY",errormsg,"error",false);
                $('#material_items').val('SELECT').show();
            }
        }
    });
    //check stock usage item
    $(document).on("change",'#stock_itemno', function (){
        var stockrefTab = document.getElementById("stockusage_table");
        var stockitem=$('#stock_itemno').val();
        var errormsg=errormessage[8].toString().replace("[ITEM]",stockitem);
        for ( var i = 1; row = stockrefTab.rows[i]; i++ )
        {
            row = stockrefTab.rows[i];
            var stockinnerarray=[];
            col = row.cells[1];
            stockinnerarray.push(col.firstChild.nodeValue);
            if(stockitem==stockinnerarray){
                show_msgbox("REPORT SUBMISSION ENTRY",errormsg,"error",false);
                $('#stock_itemno').val('SELECT').show();
            }
        }
    });
//FINAL SUBMIT FUNCTION
    $(document).on("click",'#Final_submit', function (){
        $('.preloader').show();
        //STOCK USAGE DETAILS TABLE RECORDS
        var stockusage_array=[];
        var stockrefTab = document.getElementById('stockusage_table');
        for (var r = 1, n = stockrefTab.rows.length; r < n; r++) {
            var stockinnerarray=[];
            for (var c = 1, m = stockrefTab.rows[r].cells.length; c < m; c++) {
                stockinnerarray.push(stockrefTab.rows[r].cells[c].innerHTML);
            }
            stockusage_array.push(stockinnerarray)
        }
        if(stockusage_array.length==0)
        {
            stockusage_array='null';
        }
        //MATERIAL DETAILS TABLE RECORDS
        var materialusage_array=[];
        var metrialrefTab = document.getElementById('material_table');
        for (var r = 1, n = metrialrefTab.rows.length; r < n; r++) {
            var materialinnerarray=[];
            for (var c = 1, m = metrialrefTab.rows[r].cells.length; c < m; c++) {
                materialinnerarray.push(metrialrefTab.rows[r].cells[c].innerHTML);
            }
            materialusage_array.push(materialinnerarray)
        }
        if(materialusage_array.length==0)
        {
            materialusage_array='null';
        }
        //FITTING DETAILS TABLE RECORDS
        var fittingusage_array=[];
        var fittingtable = document.getElementById('fitting_table');
        for (var r = 1, n = fittingtable.rows.length; r < n; r++) {
            var fittinginnerarray=[];
            for (var c = 1, m = fittingtable.rows[r].cells.length; c < m; c++) {
                fittinginnerarray.push(fittingtable.rows[r].cells[c].innerHTML);
            }
            fittingusage_array.push(fittinginnerarray)
        }
        if(fittingusage_array.length==0)
        {
            fittingusage_array='null';
        }
        //EQUIPMENT DETAILS TABLE RECORDS
        var equipmentusage_array=[];
        var equipmenttable = document.getElementById('equipment_table');
        for (var r = 1, n = equipmenttable.rows.length; r < n; r++) {
            var equipmentinnerarray=[];
            for (var c = 1, m = equipmenttable.rows[r].cells.length; c < m; c++) {
                equipmentinnerarray.push(equipmenttable.rows[r].cells[c].innerHTML);
            }
            equipmentusage_array.push(equipmentinnerarray)
        }
        if(equipmentusage_array.length==0)
        {
            equipmentusage_array='null';
        }
        //RENTAL MACHINERY TABLE RECORDS
        var rentalmechinery_array=[];
        var rentaltable = document.getElementById('rental_table');
        for (var r = 1, n = rentaltable.rows.length; r < n; r++) {
            var rentalinnerarray=[];
            for (var c = 1, m = rentaltable.rows[r].cells.length; c < m; c++) {
                rentalinnerarray.push(rentaltable.rows[r].cells[c].innerHTML);
            }
            rentalmechinery_array.push(rentalinnerarray)
        }
        if(rentalmechinery_array.length==0)
        {
            rentalmechinery_array='null';
        }
        //MACHINERY USAGE TABLE RECORDS
        var mechineryusage_array=[];
        var machinerytable = document.getElementById('machinery_table');
        for (var r = 1, n = machinerytable.rows.length; r < n; r++) {
            var machineryinnerarray=[];
            for (var c = 1, m = machinerytable.rows[r].cells.length; c < m; c++) {
                machineryinnerarray.push(machinerytable.rows[r].cells[c].innerHTML);
            }
            mechineryusage_array.push(machineryinnerarray)
        }
        if(mechineryusage_array.length==0)
        {
            mechineryusage_array='null';
        }
        //MACHINERY / EQUIPMENT TRANSFER TABLE RECORDS
        var mech_eqp_array=[];
        var mtransfertable = document.getElementById('mtransfer_table');
        for (var r = 1, n = mtransfertable.rows.length; r < n; r++) {
            var mach_eqp_innerarray=[];
            for (var c = 1, m = mtransfertable.rows[r].cells.length; c < m; c++) {
                mach_eqp_innerarray.push(mtransfertable.rows[r].cells[c].innerHTML);
            }
            mech_eqp_array.push(mach_eqp_innerarray)
        }
        if(mech_eqp_array.length==0)
        {
            mech_eqp_array='null';
        }
        //SITE VISIT TABLE RECORDS
        var SV_array=[];
        var svtable = document.getElementById('sv_tbl');
        for (var r = 1, n = svtable.rows.length; r < n; r++) {
            var SV_innerarray=[];
            for (var c = 1, m = svtable.rows[r].cells.length; c < m; c++) {
                SV_innerarray.push(svtable.rows[r].cells[c].innerHTML);
            }
            SV_array.push(SV_innerarray)
        }
        if(SV_array.length==0)
        {
            SV_array='null';
        }
        // MEETING DETAILS TABLE RECORDS
        var meeting_array=[];
        var meetingrefTab = document.getElementById('meeting_table');
        for (var r = 1, n = meetingrefTab.rows.length; r < n; r++) {
            var meetinginnerarray=[];
            for (var c = 1, m = meetingrefTab.rows[r].cells.length; c < m; c++) {
                meetinginnerarray.push(meetingrefTab.rows[r].cells[c].innerHTML);
            }
            meeting_array.push(meetinginnerarray)
        }
        if(meeting_array.length==0)
        {
            meeting_array='null';
        }
        //EMPPLOYEE TABLE RECORDS
        var employeerowcount=$('#Employee_table tr').length;
        for(var j=0;j<employeerowcount-1;j++)
        {
            var autoid=j+1;
            var emp_id=$('#Emp_id'+autoid).val();
            if(emp_id==employeeid)
            {
                var emp_name=$('#Emp_name'+autoid).val();
                var emp_start=$('#Emp_starttime'+autoid).val();
                var emp_end=$('#Emp_endtime'+autoid).val();
                var emp_ot=$('#Emp_ot'+autoid).val();
                var emp_remark=$('#Emp_remark'+autoid).val();
                var Employeeid;var Start;var End;var OT;var Remark;

                Employeeid=emp_id;Start=emp_start;End=emp_end;OT=emp_ot;Remark=emp_remark;
            }
        }
        var EmployeeDetails=[Employeeid,Start,End,OT,Remark,emp_name];
        var dataURL;
        if(canvas.isEmpty())
        {
            dataURL='';
        }
        else
        {
         dataURL = JSON.stringify(canvas)+"DrawToolImageurl:"+canvas.toDataURL();
        }
        var formelement =$('#entryform').serialize();
        var arraydata={"Option":"InputForm","StockDetails": stockusage_array,"MaterialDetails": materialusage_array,"FittingDetails":fittingusage_array,"EquipmentDetails":equipmentusage_array,"RentalDetails":rentalmechinery_array,"MechineryUsageDetails":mechineryusage_array,"MechEqptransfer":mech_eqp_array,"SiteVisit":SV_array,"MeetingDetails":meeting_array,"EmployeeDetails":EmployeeDetails,"imgData": dataURL};
        data=formelement + '&' + $.param(arraydata);
        $.ajax({
            type: "POST",
            url: "DB_PERMITS_ENTRY.php",
            data: data,
            success: function(msg){
                var msg_alert=JSON.parse(msg);
                var spflag=msg_alert[0];
                var dirflag=msg_alert[1];
                var writeable=msg_alert[2];
                if(spflag==1){
                    show_msgbox("REPORT SUBMISSION ENTRY",errormessage[0],"success",false);
                    form_clear();
                    $('#stock_itemno').html('<option>SELECT</option>');
                    $('.preloader').hide();
                }
                else if(spflag==0)
                {
                    show_msgbox("REPORT SUBMISSION ENTRY",errormessage[2],"error",false);
                    $('.preloader').hide();
                }
                else if(dirflag==0)
                {
                    show_msgbox("REPORT SUBMISSION ENTRY",errormessage[6],"error",false);
                    $('.preloader').hide();
                }
                else if(writeable==0)
                {
                    show_msgbox("REPORT SUBMISSION ENTRY",errormessage[10],"error",false);
                    $('.preloader').hide();
                }
                else
                {
                    show_msgbox("REPORT SUBMISSION ENTRY",msg,"error",false);
                    $('.preloader').hide();
                }
            }
        });
    });
//END OF FINAL SUBMIT FUNCTION
    var ncount=1;
    if(ncount==1)
    {
        $('#prevbtn').hide();
    }
    else
    {
        $('#prevbtn').show();
    }
    $('.next').click(function(){
        ncount++;
        $('#nextbtn').attr("disabled","disabled");
        $('#prevbtn').removeAttr('disabled');
        if(ncount==2)
        {
            $('#nextbtn').hide();
            $('#prevbtn').removeAttr('disabled').show();
            $('#Final_submit').show();
        }
    });
    $('.previous').click(function(){
        ncount--;
        $('#nextbtn').removeAttr("disabled");
        if(ncount==1 || ncount==0){
            $('#prevbtn').attr('disabled','disabled').hide();
            $('#nextbtn').show();
            $('#Final_submit').hide();
        }
        else if(ncount>1){
            $('#prevbtn').removeAttr("disabled");
        }
    });
//TEAM REPORT VALIDATION
    $(document).on('change blur','#tab1',function(){
//        var location=$('#tr_txt_location').val();
        var contractno=$('#tr_lb_contractno').val();
//        var teamname=$('#tr_tb_team').val();
        var reportdate=$('#tr_txt_date').val();
//        var weather=$('#tr_txt_weather').val();
//        var reachsite=$('#tr_txt_reachsite').val();
//        var leavesite=$('#tr_txt_leavesite').val();
//        var jobtype = $("input[id=jobtype]").is(":checked");
//        var roadchk=$("input[id=jd_chk_road]").is(":checked");
//        var concchk=$("input[id=jd_chk_contc]").is(":checked");
//        var turfchk=$("input[id=jd_chk_truf]").is(":checked");
//        var roadm=$('#jd_chk_roadm').val();
//        var roadmm=$('#jd_chk_roadmm').val();
//        var concm=$('#jd_chk_concm').val();
//        var concmm=$('#jd_chk_concmm').val();
//        var trufm=$('#jd_chk_trufm').val();
//        var trufmm=$('#jd_chk_trufmm').val();
//        var pipetesting=$('#jd_txt_testing').val();
//        var startpressure=$('#jd_txt_start').val();
//        var endpressure=$('#jd_txt_end').val();
//        var employeerowcount=$('#Employee_table tr').length;
//        for(var j=0;j<employeerowcount-1;j++)
//        {
//            var autoid=j+1;
//            var emp_id=$('#Emp_id'+autoid).val();
//            if(emp_id==employeeid)
//            {
//                var emp_name=$('#Emp_name'+autoid).val();
//                var emp_start=$('#Emp_starttime'+autoid).val();
//                var emp_end=$('#Emp_endtime'+autoid).val();
//            }
//        }
//        // pipelaid validation
//        if(roadchk==true){
//            $('#jd_chk_roadm').removeAttr('disabled');
//            $('#jd_chk_roadmm').removeAttr('disabled');
//        }
//        else{
//            $("#jd_chk_roadm").attr("disabled", "disabled");
//            $("#jd_chk_roadmm").attr("disabled", "disabled");
//            $("#jd_chk_roadm").val('');
//            $("#jd_chk_roadmm").val('');
//        }
//        if(concchk==true){
//            $('#jd_chk_concm').removeAttr('disabled');
//            $('#jd_chk_concmm').removeAttr('disabled');
//        }
//        else{
//            $("#jd_chk_concm").attr("disabled", "disabled");
//            $("#jd_chk_concmm").attr("disabled", "disabled");
//            $("#jd_chk_concm").val('');
//            $("#jd_chk_concmm").val('');
//        }
//        if(turfchk==true){
//            $('#jd_chk_trufm').removeAttr('disabled');
//            $('#jd_chk_trufmm').removeAttr('disabled');
//        }
//        else{
//            $("#jd_chk_trufm").attr("disabled", "disabled");
//            $("#jd_chk_trufmm").attr("disabled", "disabled");
//            $("#jd_chk_trufm").val('');
//            $("#jd_chk_trufmm").val('');
//        }
//        //weather time validation
//        if(weather!=''){
//            $('#tr_txt_wftime').removeAttr('disabled');
//            $('#tr_txt_wttime').removeAttr('disabled');
//        }
//        else{
//            $("#tr_txt_wftime").attr("disabled", "disabled");
//            $("#tr_txt_wttime").attr("disabled", "disabled");
//        }
//
//        if((location!=' ')&&(contractno!='') && (teamname!='SELECT') && (reportdate!='')  && (reachsite!='') && (leavesite!='') && (jobtype==true))
//        {
//            if((pipetesting!='') && (startpressure!='') && (endpressure!='') && (emp_id!='') && (emp_name!='') && (emp_start!='') && (emp_end!=''))
//            {
//                if(roadchk==true){
//                    if((roadm!='') && (roadmm!='')){
//                        var chkflag=1;
//                    }
//                    else{
//                        var chkflag=0;
//                    }
//                }
//                if(concchk==true){
//                    if((concm!='') && (concmm!='')){
//                        var chkflag=1;
//                    }
//                    else{
//                        var chkflag=0;
//                    }
//                }
//                if(turfchk==true){
//                    if((trufm!='') && (trufmm!='')){
//                        var chkflag=1;
//                    }
//                    else{
//                        var chkflag=0;
//                    }
//                }
//                if(concchk==true && turfchk==true){
//                    if((trufm!='') && (trufmm!='') && (concm!='') && (concmm!='')){
//                        var chkflag=1;
//                    }
//                    else{
//                        var chkflag=0;
//                    }
//                }
//                if(concchk==true && roadchk==true){
//                    if((roadm!='') && (roadmm!='') && (concm!='') && (concmm!='')){
//                        var chkflag=1;
//                    }
//                    else{
//                        var chkflag=0;
//                    }
//                }
//                if(roadchk==true && turfchk==true){
//                    if((trufm!='') && (trufmm!='') && (roadm!='') && (roadmm!='')){
//                        var chkflag=1;
//                    }
//                    else{
//                        var chkflag=0;
//                    }
//                }
//                if(roadchk==true && turfchk==true && concchk==true){
//                    if((trufm!='') && (trufmm!='') && (roadm!='') && (roadmm!='') && (concm!='') && (concmm!='')){
//                        var chkflag=1;
//                    }
//                    else{
//                        var chkflag=0;
//                    }
//                }
//
//                if(chkflag==1){
//                    $('#nextbtn').removeAttr('disabled');
//                }else{
//                    $('#nextbtn').attr('disabled','disabled');
//                }
//            }
        if(reportdate!=''&&contractno!='SELECT')
        {
            $('#nextbtn').removeAttr('disabled');
        }
        else
        {
            $('#nextbtn').attr('disabled','disabled');
        }
    });
});
</script>
</head>
<body>
<form id="entryform" class="form-horizontal">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">REPORT SUBMISSION ENTRY</h2>
            </div>
            <div class="panel-body">
                <div id="rootwizard">
                    <ul class="hide" id="myTab">
                        <li><a href="#tab1" data-toggle="tab">First</a></li>
                        <li><a href="#tab2" data-toggle="tab">Second</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="tab1">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">TEAM REPORT</h3>
                                </div>
                                <div class="panel-body">
                                    <fieldset>
                                        <div class="row form-group">
                                            <div class="col-md-4">
                                                <label id="tr_lbl_location">LOCATION</label>
                                                <input type="text" class="form-control txtlen alphanumeric" id="tr_txt_location" name="tr_txt_location" maxlength="40" placeholder="Location">
                                            </div>
                                            <div class="col-md-3">
                                                <label  id="tr_lbl_contractno">CONTRACT NO <em>*</em></label>
                                                <select class="form-control" id="tr_lb_contractno" name="tr_lb_contractno"><option>SELECT</option></select>
                                            </div>
                                            <div class="col-md-3 selectContainer">
                                                <label id="tr_lbl_team">TEAM</label>
                                                <input type="text" class="form-control" id="tr_tb_team" name="tr_tb_team" placeholder="Team" readonly/>
                                            </div>
                                            <div class="col-md-2">
                                                <label id="tr_lbl_date">DATE <em>*</em></label>
                                                <div class="input-group">
                                                    <input id="tr_txt_date" name="tr_txt_date" type="text" class="date-picker datemandtry form-control employee" placeholder="Date"/>
                                                    <label for="tr_txt_date" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-md-4">
                                                <label id="tr_lbl_weather">WEATHER</label>
                                                <input type="text" class="form-control txtlen alphanumeric" id="tr_txt_weather" name="tr_txt_weather" placeholder="Weather">
                                            </div>
                                            <div class="col-md-2">
                                                <label id="tr_lbl_reachsite">FROM (Time)</label>
                                                <input type="text" class="form-control time-picker" id="tr_txt_wftime" name="tr_txt_wftime" disabled placeholder="Weather Time">
                                            </div>
                                            <div class="col-md-2">
                                                <label id="tr_lbl_leavesite">TO (Time)</label>
                                                <input type="text" class="form-control time-picker" id="tr_txt_wttime" name="tr_txt_wttime" disabled placeholder="Weather Time">
                                            </div>
                                            <div class="col-md-2">
                                                <label id="tr_lbl_reachsite">REACH SITE</label>
                                                <input type="text" class="form-control time-picker" id="tr_txt_reachsite" name="tr_txt_reachsite" placeholder="Time">
                                            </div>
                                            <div class="col-md-2">
                                                <label id="tr_lbl_leavesite">LEAVE SITE</label>
                                                <input type="text" class="form-control time-picker" id="tr_txt_leavesite" name="tr_txt_leavesite" placeholder="Time">
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="checkbox-inline">
                                                <label>TYPE OF JOB</label>
                                                <div id="type_of_job">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <!--</div>-->
                            <!--<div class="tab-pane" id="tab2">-->
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">TOOLBOX MEETING</h3>
                                </div>
                                <div class="panel-body">
                                    <fieldset>
                                        <div class="row form-group">
                                            <div class="col-md-4">
                                                <label for="mt_lbl_topic" id="mt_lbl_topic">TOPIC</label>
                                                <select class="form-control meetingform-validation" id="mt_lb_topic" name="mt_lb_topic">
                                                    <option>SELECT</option>
                                                </select>
                                            </div>
                                            <div class="col-md-8">
                                                <label for="mt_lbl_remark" id="mt_lbl_remark">REMARKS</label>
                                                <textarea class="form-control meetingform-validation remarklen removecap" style="min-height: 35px;" rows="1" id="mt_ta_remark" name="mt_ta_remark" placeholder="Remarks"></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="hidden" name="mt_rowid" id="mt_rowid" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-9 col-lg-offset-11">
                                            <button type="button" id="mt_btn_addrow" class="btn btn-info">ADD</button>
                                            <button type="button" id="mt_btn_update" class="btn btn-info mt_btn_updaterow">UPDATE</button>
                                        </div>
                                    </fieldset>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="meeting_table">
                                            <thead>
                                            <tr class="active">
                                                <th width="300px">EDIT/REMOVE</th>
                                                <th>TOPIC</th>
                                                <th>REMARKS</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--</div>-->
                            <!--<div class="tab-pane" id="tab3">-->
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">JOB DONE</h3>
                                </div>
                                <div class="panel-body">
                                    <fieldset>
                                        <div class="table-responsive">
                                            <table class="table" border="1" style=" border: #ddd;">
                                                <tr>
                                                    <td class="jobthl">
                                                        <label style="padding-bottom: 15px"></label>
                                                        <label id="tr_lbl_pipelaid">PIPE LAID</label>
                                                    </td>
                                                    <td colspan="2" style="text-align: center">
                                                        <div>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" id="jd_chk_road" name="jd_chk_road"> ROAD
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td colspan="2" style="text-align: center">
                                                        <div>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" id="jd_chk_contc" name="jd_chk_contc"> CONC
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td colspan="2" style="text-align: center" class="jobthr">
                                                        <div>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" id="jd_chk_truf" name="jd_chk_truf"> TURF
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="jobthl" style="border-top: 1px solid white;">
                                                        <label style="padding-bottom: 15px"> </label>
                                                        <label id="tr_lbl_location">SIZE/LENGTH</label>
                                                    </td>
                                                    <td class="jobtd" style="border-top: 1px solid white;">
                                                        <div>
                                                            <label>M</label>
                                                            <input type="text" class="form-control decimal size" id="jd_chk_roadm" name="jd_chk_roadm" disabled placeholder="M">
                                                        </div>
                                                    </td>
                                                    <td style="border-top: 1px solid white;">
                                                        <div>
                                                            <label>MM</label>
                                                            <input type="text" class="form-control decimal size" id="jd_chk_roadmm" name="jd_chk_roadmm" disabled placeholder="MM">
                                                        </div>
                                                    </td>
                                                    <td class="jobtd" style="border-top: 1px solid white;">
                                                        <div>
                                                            <label>M</label>
                                                            <input type="text" class="form-control decimal size" id="jd_chk_concm" name="jd_chk_concm" disabled placeholder="M">
                                                        </div>
                                                    </td>
                                                    <td style="border-top: 1px solid white;">
                                                        <div>
                                                            <label>MM</label>
                                                            <input type="text" class="form-control decimal size" id="jd_chk_concmm" name="jd_chk_concmm" disabled placeholder="MM">
                                                        </div>
                                                    </td>
                                                    <td class="jobtd" style="border-top: 1px solid white;">
                                                        <div>
                                                            <label>M</label>
                                                            <input type="text" class="form-control decimal size" id="jd_chk_trufm" name="jd_chk_trufm" disabled placeholder="M">
                                                        </div>
                                                    </td>
                                                    <td class="jobthr" style="border-top: 1px solid white;">
                                                        <div>
                                                            <label>MM</label>
                                                            <input type="text" class="form-control decimal size" id="jd_chk_trufmm" name="jd_chk_trufmm" disabled placeholder="MM">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-md-3">
                                                <label for="jd_txt_testing" id="jd_lbl_testing">PIPE TESTING</label>
                                                <input type="text" class="form-control txtlen alphanumeric" id="jd_txt_testing" name="jd_txt_testing" placeholder="Pipe Testing">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="jd_txt_start" id="jd_lbl_start" >START (PRESSURE)</label>
                                                <input type="text" class="form-control quantity alphanumeric"  id="jd_txt_start" name="jd_txt_start" placeholder="Start Pressure">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="jd_txt_end" id="jd_lbl_end">END (PRESSURE)</label>
                                                <input type="text" class="form-control quantity alphanumeric" id="jd_txt_end" name="jd_txt_end" placeholder="End Pressure">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="jd_ta_remark" id="jd_lbl_remark">REMARKS</label>
                                                <textarea class="form-control remarklen removecap textareaaccinjured" rows="1" id="jd_ta_remark" name="jd_ta_remark" placeholder="Remarks"></textarea>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <!--        </form>-->
                                </div>
                            </div>
                            <!--</div>-->
                            <!--<div class="tab-pane" id="tab4">-->
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">EMPLOYEE REPORT DETAILS</h3>
                                </div>
                                <div class="panel-body">
                                    <!--        <form>-->
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="Employee_table" name="Employee_table">
                                            <thead>
                                            <tr class="active">
                                                <th><div>NAME</div></th>
                                                <th><div class="col-lg-10">START TIME</div></th>
                                                <th><div class="col-lg-10">END TIME</div></th>
                                                <th><div class="col-lg-10">OT</div></th>
                                                <th><div>REMARKS</div></th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <!--        </form>-->
                                </div>
                            </div>
                            <!--</div>-->
                            <!--<div class="tab-pane" id="tab5">-->
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">SITE VISIT</h3>
                                </div>
                                <div class="panel-body">
                                    <!--        <form class="form-horizontal">-->
                                    <fieldset>
                                        <div class="row form-group">
                                            <div class="col-md-3">
                                                <label>DESIGNATION</label>
                                                <input class="form-control sitevisitform-validation txtlen alphanumeric" name="sv_txt_designation" id="sv_txt_designation" placeholder="Designation"/>
                                            </div>
                                            <div class="col-md-3">
                                                <label>NAME</label>
                                                <input class="form-control sitevisitform-validation txtlen autosizealph " name="sv_txt_name" id="sv_txt_name" placeholder="Name"/>
                                            </div>

                                            <div class="col-md-1">
                                                <label>START</label>
                                                <input type="text" class="form-control sitevisitform-validation time-picker" name="sv_txt_start" id="sv_txt_start" placeholder="Time">
                                            </div>
                                            <div class="col-md-1">
                                                <label>END</label>
                                                <input type="text" class="form-control sitevisitform-validation time-picker" name="sv_txt_end" id="sv_txt_end" placeholder="Time">
                                            </div>
                                            <div class="col-md-4">
                                                <label>REMARKS</label>
                                                <textarea class="form-control sitevisitform-validation remarklen removecap textareaaccinjured" rows="1" name="sv_txt_remark" id="sv_txt_remark" placeholder="Remarks"></textarea>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="hidden" name="sv_rowid" id="sv_rowid" name="sv_rowid" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-9 col-lg-offset-11">
                                            <button type="button" id="sv_btn_addrow" class="btn btn-info" >ADD</button>
                                            <button type="button" id="sv_btn_update" class="btn btn-info sv_btn_updaterow" >UPDATE</button>
                                        </div>
                                    </fieldset>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="sv_tbl">
                                            <thead>
                                            <tr class="active">
                                                <th>EDIT/REMOVE</th>
                                                <th>DESIGNATION</th>
                                                <th>NAME</th>
                                                <th>START TIME</th>
                                                <th>END TIME</th>
                                                <th>REMARKS</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--        </form>-->
                                </div>
                            </div>
                            <!--</div>-->
                            <!--<div class="tab-pane" id="tab6">-->
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">MACHINERY / EQUIPMENT TRANSFER</h3>
                                </div>
                                <div class="panel-body">
                                    <!--        <form class="form-horizontal">-->
                                    <fieldset>
                                        <div class="row form-group">
                                            <div class="col-md-2">
                                                <label>FROM (LORRY NO)</label>
                                                <input type="text" class="form-control mtransferform-validation quantity lorryno" id="mtranser_from" name="mtranser_from" placeholder="From (Lorry No)">
                                            </div>
                                            <div class="col-md-4">
                                                <label>ITEMS</label>
                                                <select class="form-control alphanumeric mtransferform-validation" id="mtransfer_item" name="mtransfer_item">
                                                    <option>SELECT</option>
                                                </select>
                                                <!--                    <input type="text" class="form-control alphanumeric mtransferform-validation txtlen" id="mtransfer_item" name="mtransfer_item" placeholder="Item">-->
                                            </div>

                                            <div class="col-md-2">
                                                <label>TO (LORRY NO)</label>
                                                <input type="text" class="form-control mtransferform-validation quantity lorryno" id="mtransfer_to"  name="mtransfer_to" placeholder="To (Lorry No)">
                                            </div>

                                            <div class="col-md-4">
                                                <label>REMARKS</label>
                                                <textarea class="form-control mtransferform-validation remarklen removecap textareaaccinjured" id="mtransfer_remark"  rows="1" name="mtransfer_remark" placeholder="Remarks"></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="hidden" id="mtransfer_rowid" name="mtransfer_rowid" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-9 col-lg-offset-11">
                                            <button type="button" id="mtransfer_addrow" class="btn btn-info" >ADD</button>
                                            <button type="button" id="mtransfer_update" class="btn btn-info mtransfer_updaterow" >UPDATE</button>
                                        </div>
                                    </fieldset>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="mtransfer_table" name="mtransfer_table">
                                            <thead>
                                            <tr class="active">
                                                <th>EDIT/REMOVE</th>
                                                <th>FROM(LORRY NO)</th>
                                                <th>ITEMS</th>
                                                <th>TO(LORRY NO)</th>
                                                <th>REMARKS</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--        </form>-->
                                </div>
                            </div>
                            <!--</div>-->
                            <!--<div class="tab-pane" id="tab7">-->
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">MACHINERY USAGE</h3>
                                </div>
                                <div class="panel-body">
                                    <!--        <form class="form-horizontal">-->
                                    <fieldset>
                                        <div class="row form-group">
                                            <div class="col-md-4">
                                                <label>MACHINERY TYPE</label>
                                                <select class="form-control alphanumeric machineryform-validation" id="machinery_type" name="machinery_type">
                                                    <option>SELECT</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label>START (Time)</label>
                                                <input type="text" class="form-control machineryform-validation time-picker"  id="machinery_start" name="machinery_start" placeholder="Time">
                                            </div>

                                            <div class="col-md-2">
                                                <label>END (Time)</label>
                                                <input type="text" class="form-control machineryform-validation time-picker"  id="machinery_end"  name="machinery_end" placeholder="Time">
                                            </div>

                                            <div class="col-md-4">
                                                <label>REMARKS</label>
                                                <textarea class="form-control remarklen removecap machineryform-validation textareaaccinjured" id="machinery_remarks" rows="1" name="machinery_remarks" placeholder="Remarks"></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">

                                            </div>
                                            <div class="col-md-4">
                                                <input type="hidden" id="machinery_rowid" name="machinery_rowid" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-9 col-lg-offset-11">
                                            <button type="button" id="machinery_addrow" class="btn btn-info" >ADD</button>
                                            <button type="button" id="machinery_update" class="btn btn-info machinery_updaterow" >UPDATE</button>

                                        </div>
                                    </fieldset>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="machinery_table" name="machinery_table">
                                            <thead>
                                            <tr class="active">
                                                <th>EDIT/REMOVE</th>
                                                <th>MACHINERY TYPE</th>
                                                <th>START TIME</th>
                                                <th>END TIME</th>
                                                <th>REMARKS</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--        </form>-->
                                </div>
                            </div>
                            <!--</div>-->
                            <!--<div class="tab-pane" id="tab8">-->
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">RENTAL MACHINERY</h3>
                                </div>
                                <div class="panel-body">
                                    <!--        <form class="form-horizontal">-->
                                    <fieldset>
                                        <div class="row form-group">
                                            <div class="col-md-4">
                                                <label>LORRY NUMBER</label>
                                                <input type="text" class="form-control rentalform-validation quantity lorryno" id="rental_lorryno" name="rental_lorryno" placeholder="Lorry Name">
                                            </div>
                                            <div class="col-md-4">
                                                <label>THROW EARTH(STORE)</label>
                                                <input type="text" class="form-control rentalform-validation decimal size" id="rental_throwearthstore" name="rental_throwearthstore" placeholder="Throw Earth(Store)">
                                            </div>

                                            <div class="col-md-4">
                                                <label>THROW EARTH(OUTSIDE)</label>
                                                <input type="text" class="form-control rentalform-validation decimal size" id="rental_throwearthoutside" name="rental_throwearthoutside" placeholder="Throwe Earth(Outside)">
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-md-2">
                                                <label>START (Time)</label>
                                                <input type="text" class="form-control rentalform-validation time-picker" id="rental_start" name="rental_start" placeholder="Time">
                                            </div>

                                            <div class="col-md-2">
                                                <label>END (Time)</label>
                                                <input type="text" class="form-control rentalform-validation time-picker" id="rental_end"  name="rental_end" placeholder="Time">
                                            </div>

                                            <div class="col-md-4">
                                                <label>REMARKS</label>
                                                <textarea class="form-control rentalform-validation remarklen removecap textareaaccinjured" id="rental_remarks" rows="1" name="rental_remarks" placeholder="Remarks"></textarea>
                                                <input type="hidden" class="form-control" id="rentalmechinery_id" name="rentalmechinery_id">
                                            </div>
                                        </div>

                                        <div class="col-lg-9 col-lg-offset-11">
                                            <button type="button" id="rentalmechinery_addrow" class="btn btn-info" >ADD</button>
                                            <button type="button" id="rentalmechinery_updaterow" class="btn btn-info rentalmechineryupdaterow" >UPDATE</button>

                                        </div>
                                    </fieldset>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="rental_table">
                                            <thead>
                                            <tr class="active">
                                                <th>EDIT/REMOVE</th>
                                                <th>LORRY NO</th>
                                                <th>THROW EARTH (STORE)</th>
                                                <th>THROW EARTH (OUTSIDE)</th>
                                                <th>START TIME</th>
                                                <th>END TIME</th>
                                                <th>REMARKS</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--        </form>-->
                                </div>
                            </div>
                            <!--</div>-->
                            <!--<div class="tab-pane" id="tab9">-->
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">EQUIPMENT USAGE</h3>
                                </div>
                                <div class="panel-body">
                                    <!--        <form class="form-horizontal">-->
                                    <fieldset>
                                        <div class="row form-group">
                                            <div class="col-md-3">
                                                <label>AIR-COMPRESSOR</label>
                                                <input type="text" class="form-control alphanumeric equipmentform-validation txtlen" id="equipment_aircompressor" name="equipment_aircompressor" placeholder="Air-Compressor">
                                            </div>
                                            <div class="col-md-3">
                                                <label>LORRY NO(TRANSPORT)</label>
                                                <input type="text" class="form-control equipmentform-validation quantity lorryno" id="equipment_lorryno" name="equipment_lorryno" placeholder="Lorry No(Transport)">
                                            </div>
                                            <div class="col-md-1">
                                                <label>START</label>
                                                <input type="text" class="form-control equipmentform-validation time-picker" id="equipment_start"  name="equipment_start" placeholder="Time">
                                            </div>
                                            <div class="col-md-1">
                                                <label>END</label>
                                                <input type="text" class="form-control equipmentform-validation time-picker" id="equipment_end"  name="equipment_end" placeholder="Time">
                                            </div>
                                            <div class="col-md-4">
                                                <label>REMARKS</label>
                                                <textarea class="form-control equipmentform-validation remarklen removecap textareaaccinjured" rows="1" id="equipment_remark"  name="equipment_remark" placeholder="Remarks"></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="hidden" id="equipment_rowid" name="equipment_rowid" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-9 col-lg-offset-11">
                                            <button type="button" id="equipment_addrow" class="btn btn-info">ADD</button>
                                            <button type="button" id="equipment_update" class="btn btn-info equipment_updaterow">UPDATE</button>
                                        </div>
                                    </fieldset>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="equipment_table" name="equipment_table">
                                            <thead>
                                            <tr class="active">
                                                <th>EDIT/REMOVE</th>
                                                <th>AIR COMPRESSOR</th>
                                                <th>LORRY NO(TRANSPORT)</th>
                                                <th>START TIME</th>
                                                <th>END TIME</th>
                                                <th>REMARKS</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--        </form>-->
                                </div>
                            </div>
                            <!--</div>-->
                            <!--<div class="tab-pane" id="tab10">-->
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">FITTINGS USAGE</h3>
                                </div>
                                <div class="panel-body">
                                    <!--        <form class="form-horizontal">-->
                                    <fieldset>
                                        <div class="row form-group">
                                            <div class="col-md-4">
                                                <label>ITEMS</label>
                                                <select class="form-control fittingform-validation" id="fitting_items" name="fitting_items" placeholder="Items">
                                                    <option>SELECT</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label>SIZE</label>
                                                <input type="text" class="form-control fittingform-validation decimal size" id="fitting_size" name="fitting_size" placeholder="MM">
                                            </div>
                                            <div class="col-md-2">
                                                <label>QUANTITY</label>
                                                <input type="text" class="form-control fittingform-validation decimal size" id="fitting_quantity" name="fitting_quantity" placeholder="Quantity">
                                            </div>
                                            <div class="col-md-4">
                                                <label>REMARKS</label>
                                                <textarea class="form-control remarklen removecap fittingform-validation textareaaccinjured" rows="1" id="fitting_remarks" name="fitting_remarks" placeholder="Remarks"></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="hidden" class="form-control" id="fitting_id" name="fitting_id">
                                            </div>
                                        </div>
                                        <div class="col-lg-9 col-lg-offset-11">
                                            <button type="button" id="fitting_addrow" class="btn btn-info" >ADD</button>
                                            <button type="button" id="fitting_updaterow" class="btn btn-info  fittingupdaterow" >UPDATE</button>
                                        </div>
                                    </fieldset>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="fitting_table">
                                            <thead>
                                            <tr class="active">
                                                <th>EDIT/REMOVE</th>
                                                <th>ITEMS</th>
                                                <th>SIZE</th>
                                                <th>QUANTITY</th>
                                                <th>REMARKS</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--        </form>-->
                                </div>
                            </div>
                            <!--</div>-->
                            <!--<div class="tab-pane" id="tab11">-->
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">MATERIAL USAGE</h3>
                                </div>
                                <div class="panel-body">
                                    <!--        <form class="form-horizontal">-->
                                    <fieldset>
                                        <div class="row form-group">
                                            <div class="col-md-4">
                                                <label>ITEMS</label>
                                                <select class="form-control materialform-validation" id="material_items" name="material_items" placeholder="Items">
                                                    <option>SELECT</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>RECEIPT NO</label>
                                                <input type="text" class="form-control alphanumeric materialform-validation quantity" id="material_receipt" name="material_receipt" placeholder="Receipt No">
                                            </div>
                                            <div class="col-md-4">
                                                <label>QUANTITY</label>
                                                <input type="text" class="form-control materialform-validation decimal size" id="material_quantity" name="material_quantity" placeholder="Quantity">
                                                <input type="hidden" class="form-control" id="material_id" name="material_id">
                                            </div>
                                        </div>
                                        <div class="col-lg-9 col-lg-offset-11">
                                            <button type="button" id="material_addrow" class="btn btn-info" >ADD</button>
                                            <button type="button" id="material_updaterow" class="btn btn-info materialupdaterow" >UPDATE</button>
                                        </div>
                                    </fieldset>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="material_table">
                                            <thead>
                                            <tr class="active">
                                                <th>EDIT/REMOVE</th>
                                                <th>ITEMS</th>
                                                <th>RECEIPT NO</th>
                                                <th>QTY(KG/BAGS/LTR/PCS)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--        </form>-->
                                </div>
                            </div>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">SITE STOCK USAGE</h3>
                                </div>
                                <div class="panel-body">
                                    <!--        <form class="form-horizontal">-->
                                    <fieldset>
                                        <div class="row form-group">
                                            <div class="col-md-4">
                                                <label>ITEM NO</label>
                                                <select class="form-control stockusageform-validation" id="stock_itemno" name="stock_itemno">
                                                    <option>SELECT</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>ITEM NAME</label>
                                                <input type="text" class="form-control stockusageform-validation" id="stock_itemname" name="stock_itemname" placeholder="Item Name" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label>QUANTITY</label>
                                                <input type="text" class="form-control stockusageform-validation decimal size" id="stock_quantity" name="stock_quantity" placeholder="Quantity">
                                                <input type="hidden" class="form-control" id="stock_id" name="stock_id">
                                            </div>
                                        </div>
                                        <div class="col-lg-9 col-lg-offset-11">
                                            <button type="button" id="stock_addrow" class="btn btn-info" >ADD</button>
                                            <button type="button" id="stock_updaterow" class="btn btn-info stockupdaterow" >UPDATE</button>
                                        </div>
                                    </fieldset>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="stockusage_table">
                                            <thead>
                                            <tr class="active">
                                                <th>EDIT/REMOVE</th>
                                                <th>ITEM NO</th>
                                                <th>ITEM NAME</th>
                                                <th>QUANTITY</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--        </form>-->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab2">
                            <!-- DRAWING SURFACE--->
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">DRAWING AREA</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="bs-example">
                                        <div class="col-xs-9"><canvas id="canvas" style="border:1px solid #F5F5F5;" onclick="canvasonlick()"></canvas></div>
                                        <div class="row">
                                            <div class="col-xs-3 canvasshapes">
                                                <div class="general" style="background-color:#F5F5F5;border:1px solid lavender;">
                                                    <a onClick="setColor()"  class="btn primary a-img-btn" title='FILL WITH COLOR'><img src="../PAINT/IMAGES/fill.jpg"  class="img-rounded"/></a>
                                                    <a onClick="eclipse()"   class="btn primary a-img-btn" title='ECLIPSE'><img src="../PAINT/IMAGES/eclipse.jpg"  class="img-rounded"/></a>
                                                    <a onClick="triangle()"   class="btn primary a-img-btn" title='TRIANGLE'><img src="../PAINT/IMAGES/triangle.jpg"  class="img-rounded"/></a>
                                                    <a onClick="circle()"  class="btn primary a-img-btn-active" title='CIRCLE'><img src="../PAINT/IMAGES/cir.png"  class="img-rounded"/></a>
                                                    <a onClick="rectangle()" class="btn primary a-img-btn" title='RECTANGLE'><img src="../PAINT/IMAGES/rectangle.png"  class="img-rounded"/></a>
                                                    <a onClick="drawLine()"  class="btn primary a-img-btn" title='LINE' id="drawing-line"><img src="../PAINT/IMAGES/line.jpg"  class="img-rounded"/></a>
                                                    <a onClick="pencil()"  class="btn primary a-img-btn" title='PENCIL'><img src="../PAINT/IMAGES/pencil.png"  class="img-rounded"/></a>
                                                    <a onClick="eraser()"  class="btn primary a-img-btn" title='ERASER'><img src="../PAINT/IMAGES/eraser.jpg"  class="img-rounded"/></a>
                                                    <a onClick="textEditor1()"  class="btn primary a-img-btn" title='TEXTd'><img src="../PAINT/IMAGES/text.jpg"  class="img-rounded"/></a>
                                                    <a onClick="clearCanvas()" class="btn primary a-img-btn" title='CLEAR'><img src="../PAINT/IMAGES/cancel.jpg"  class="img-rounded"/></a>
                                                    <a onClick="selector()" class="btn primary a-img-btn" title='SELECTOR'><img src="../PAINT/IMAGES/select.jpg"  class="img-rounded"/></a>
                                                    <a onClick="cut()" class="btn primary a-img-btn" title='REMOVE'><img src="../PAINT/IMAGES/cut.jpg"  class="img-rounded"/></a>
                                                </div>
                                                <div class="font" style="background-color:#F5F5F5;"><br>
                                                    <label>Color:</label><input type="color" value="#36bac9" id="drawing-color" title="COLOR">&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label>Size:</label><input type="number"  value="10" id="drawing-line-width" title="SIZE" style="width:45" min="10" max="100" maxlength="2">
                                                    <br>
                                                    <a onClick="bold()" class="btn primary a-img-btn-font-rem font" title='BOLD' id="fontbold"><img src="../PAINT/IMAGES/bold.jpg"  class="img-rounded"/></a>
                                                    <a onClick="italic()" class="btn primary  a-img-btn-font-rem font" title='ITALIC' id="fontitalic"><img src="../PAINT/IMAGES/italic.jpg"  class="img-rounded"/></a>
                                                    <a onClick="underline()" class="btn primary  a-img-btn-font-rem font" title='UNDERLINE' id="fontunderline"><img src="../PAINT/IMAGES/underline.jpg"  class="img-rounded"/></a>
                                                </div>
                                                <div class="shapes" style="background-color:#F5F5F5;border:1px solid lavender;">
                                                    <a onClick="tappingTee1()"  class="btn primary a-img-btn" title='TAPPING TEE'><img src="../PAINT/IMAGES/tappingtee.jpg"  class="img-rounded"/></a>
                                                    <a onClick="tJoint1()"  class="btn primary a-img-btn" title='T/JOINT'><img src="../PAINT/IMAGES/tjoint.jpg"  class="img-rounded"/></a>
                                                    <a onClick="stubBlang1()"  class="btn primary a-img-btn" title='STUB FLANGE'><img src="../PAINT/IMAGES/stubblang.jpg"  class="img-rounded"/></a>
                                                    <a onClick="reducer1()"  class="btn primary a-img-btn" title='REDUCER'><img src="../PAINT/IMAGES/reducer.jpg"  class="img-rounded"/></a>
                                                    <a onClick="lastDegelbow1()"  class="btn primary a-img-btn" title='45/90 DEG ELBOW'><img src="../PAINT/IMAGES/lastdegelbow.jpg"  class="img-rounded"/></a>
                                                    <a onClick="halfDegelbow1()"  class="btn primary a-img-btn" title='45 DEG ELBOW'><img src="../PAINT/IMAGES/halfdegelbow.jpg"  class="img-rounded"/></a>
                                                    <a onClick="fullDegelbow1()"  class="btn primary a-img-btn" title='90 DEG ELBOW'><img src="../PAINT/IMAGES/fulldegelbow.jpg"  class="img-rounded"/></a>
                                                    <a onClick="equalTee1()"  class="btn primary a-img-btn" title='EQUAL TEE'><img src="../PAINT/IMAGES/equaltee.jpg"  class="img-rounded"/></a>
                                                    <a onClick="endCap1()"  class="btn primary a-img-btn" title='END CAP'><img src="../PAINT/IMAGES/endcap.jpg"  class="img-rounded"/></a>
                                                    <a onClick="diTee1()"  class="btn primary a-img-btn" title='DI TEE'><img src="../PAINT/IMAGES/ditee.jpg"  class="img-rounded"/></a>
                                                    <a onClick="diGatevalue1()"  class="btn primary a-img-btn" title='DI GATE VALVE'><img src="../PAINT/IMAGES/digatevalue.jpg"  class="img-rounded"/></a>
                                                    <a onClick="diFlanging1()"  class="btn primary a-img-btn" title='DI FLANGE SPIGOT'><img src="../PAINT/IMAGES/diflanging.jpg"  class="img-rounded" width="25px"/></a>
                                                    <a onClick="diFlangesotcket1()" class="btn primary a-img-btn" title='DI FLANGE STOCKET'><img src="../PAINT/IMAGES/diflangestocket.jpg"  class="img-rounded"  /></a>
                                                    <a onClick="diColor1()"  class="btn primary a-img-btn" title='DI COLLAR'><img src="../PAINT/IMAGES/dicolor.jpg"  class="img-rounded"/></a>
                                                    <a onClick="diCap1()"  class="btn primary a-img-btn" title='DI CAP'><img src="../PAINT/IMAGES/dicap.jpg"  class="img-rounded" width="25px"/></a>
                                                    <a onClick="coupler1()"  class="btn primary a-img-btn" title='COUPLER'><img src="../PAINT/IMAGES/dicolor.jpg"  class="img-rounded" width="25px"/></a>
                                                    <a onClick="beEndCateValue1()"  class="btn primary a-img-btn" title='PE END GATE VALUE'><img src="../PAINT/IMAGES/beendcatevalue.jpg"  class="img-rounded"/></a>
                                                    <a onClick="di90degElbow()"  class="btn primary a-img-btn" title='DI 90 DEG ELBOW'><img src="../PAINT/IMAGES/90degElbow.jpg"  class="img-rounded"/></a>
                                                    <a onClick="di45DegElbow()"  class="btn primary a-img-btn" title='DI 45 DEG ELBOW'><img src="../PAINT/IMAGES/di45DegElbow.jpg"  class="img-rounded"/></a>
                                                    <a onClick="diReducer()"  class="btn primary a-img-btn" title='DI REDUCER'><img src="../PAINT/IMAGES/diReducer.jpg"  class="img-rounded"/></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="divImage"></div>
                            </div><!-- ENDING DRAWING SURFACE--->
                        </div>
                        <ul class="pager wizard">
                            <li class="previous first" style="display:none;"><a href="#">First</a></li>
                            <li class="previous"><a class="btn btn-primary" type="button" href="#" id="prevbtn" disabled>PREVIOUS</a></li>
                            <li class="next last" style="display:none;"><a href="#">Last</a></li>
                            <li class="next"><a class="btn btn-primary" type="button" href="#" id="nextbtn" disabled>NEXT</a></li>
                        </ul>
                        <div class="col-lg-offset-10">
                            <a class="btn btn-primary btn-lg" type="button" id="Final_submit" name="Final_submit" disabled >FINAL SUBMIT</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group-sm">
                <ul class="nav-pills">
                    <li class="pull-right"><a href="#top">Back to top</a></li>
                </ul>
            </div>
        </div>
    </div>
</form>
<script src="../PAINT/JS/customShape.js"> </script>
</body>
</html>​
