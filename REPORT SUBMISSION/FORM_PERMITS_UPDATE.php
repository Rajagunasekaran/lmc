<?php
include "../FOLDERMENU.php";
?>
<link rel="stylesheet" href="../PAINT/CSS/icon.css">
<script>
var SRC_upload_count=0;
var finalimagedata="",imagecheckflag=0;
$(document).ready(function(){
//    $('.preloader').show();
    //CODING FOR CUSTOM PAINT
    var imgflag = 1, imageData, imageDataJson;
    $(document).on('click', '#closeImage', function () {
        $("#divImage").empty();
//        if(!canvas.isEmpty()){
        if (imageData != undefined&&imageData != null&&imageData != "")
            $('<img src="' + imageData + '" style="border:1px solid #F5F5F5;align:center" class="img-responsive" width="600" height="400">').appendTo("#divImage");
//        }
        });
    $(document).on('click', '#saveImage', function () {
        canvas.deactivateAllWithDispatch().renderAll();

        $('#myModal').modal('hide');
        $("#divImage").empty();
        if(!canvas.isEmpty()){
            imageData = canvas.toDataURL();
            imageDataJson = JSON.stringify(canvas);
        if (imageData != undefined&&imageData != null&&imageData != "")
        $('<img src="' + imageData + '" style="border:1px solid #F5F5F5;align:center" class="img-responsive" width="600" height="400">').appendTo("#divImage");
        }
        else{
            imageDataJson="";
            imageData="";
        }
        canvas.deactivateAllWithDispatch().renderAll();
        });
    //END OF FINAL SUBMIT FUNCTION
    $('.open-modal').click(function () {
        $('#myModal').modal({backdrop: 'static', keyboard: false});
    });
    $('#myModal').on('shown.bs.modal', function () {
        if(imgflag==1)
        {
            loadcanvas();
            finalimagedata="";
            imagecheckflag=1;
        }
        imgflag = 0;
        canvas.clear();
        if (imageDataJson != undefined && imageDataJson!= null&&imageDataJson != "") {
            canvas.loadFromJSON(imageDataJson,canvas.renderAll.bind(canvas));
        }
    });
    $("#myModal").on('hidden.bs.modal', function () {
    });
    $(document).on("click",'.a-img-btn', function (){
        $(".a-img-btn-active").removeClass("a-img-btn-active").addClass("a-img-btn");
        $(this).addClass("a-img-btn-active").removeClass("a-img-btn");
    });
    //ENDING FOR CUSTOM CODE
    $('#SRC_radiosearchbtn').hide();
    $('#SRC_Final_Update').hide();
    $('#backtotop').hide();
    var error_message=[];
    var employee_id;
    //END OF VALIDATION
    $('#SRC_entryform').hide();
    //validation
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
// LORRY NO VALIDATIN
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
// TEAM REPORT FUNCTION
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
// END OF TEAM REPORTR FUNCTION
    // End validation
    var teamname=[];
    var empname=[];
    var machinerytype=[];
    var fittingitems=[];
    var materialitems=[];
    var jobtype=[];
    var topicname=[];
    var machineryequip=[];
    var contractnos=[];
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            var value_array=JSON.parse(xmlhttp.responseText);
            $('#RPT').hide();
            $('#AE').hide();
            teamname=value_array[0];
            machinerytype=value_array[1];
            fittingitems=value_array[2];
            materialitems=value_array[3];
            jobtype=value_array[4];
            empname=value_array[6];
            topicname=value_array[7];
            machineryequip=value_array[9];
            contractnos=value_array[10];
            //meetig topic
            var topic='<option>SELECT</option>';
            for (var i=0;i<topicname.length;i++) {
                topic += '<option value="' + topicname[i] + '">' + topicname[i] + '</option>';
            }
            $('#SRC_mt_lb_topic').html(topic);
            //TEAM
            $('#SRC_tr_tb_team').val(teamname);
            //EMPNAME
            var employeename='<option>SELECT</option>';
            for (var i=0;i<empname.length;i++) {
                employeename += '<option value="' + empname[i][1] + '">' + empname[i][0] + '</option>';
            }
            $('#SRC_team_lb_empname').html(employeename);
            //MACHINERY_QUIP TYPE
            var machineryqequip_type='<option>SELECT</option>';
            for (var i=0;i<machineryequip.length;i++) {
                machineryqequip_type += '<option value="' + machineryequip[i] + '">' + machineryequip[i] + '</option>';
            }
            $('#SRC_mtransfer_item').html(machineryqequip_type);
            //MACHINERY_TYPE
            var machinery_type='<option>SELECT</option>';
            for (var i=0;i<machinerytype.length;i++) {
                machinery_type += '<option value="' + machinerytype[i] + '">' + machinerytype[i] + '</option>';
            }
            $('#SRC_machinery_type').html(machinery_type);
            //FITTING ITEM
            var fitting_item='<option>SELECT</option>';
            for (var i=0;i<fittingitems.length;i++) {
                fitting_item += '<option value="' + fittingitems[i] + '">' + fittingitems[i] + '</option>';
            }
            $('#SRC_fitting_items').html(fitting_item);
            //MATERIAL ITEM
            var material_item='<option>SELECT</option>';
            for (var i=0;i<materialitems.length;i++) {
                material_item += '<option value="' + materialitems[i] + '">' + materialitems[i] + '</option>';
            }
            $('#SRC_material_items').html(material_item);
            //CONTRACT NO
            var contractno='<option>SELECT</option>';
            for (var i=0;i<contractnos.length;i++) {
                contractno += '<option value="' + contractnos[i].id + '">' + contractnos[i].no + '</option>';
            }
            $('#SRC_tr_lb_contractno').html(contractno);
            //TYPE OF JOB
            var typeofjob='';
            for(var i=0;i<jobtype.length;i++)
            {
                var chkboxid=jobtype[i][0].replace(" ","");
                typeofjob+='<label class="checkbox-inline no_indent"><input type="checkbox" id ="'+chkboxid+'" name="jobtype[]" value="' + jobtype[i][1] + '">' + jobtype[i][0]+'</label>'
            }
            $('#type_of_job').append(typeofjob).show();
            $('.preloader').hide();
        }
    }
    var option="COMMON_DATA";
    xmlhttp.open("GET","DB_PERMITS_ENTRY.php?option="+option);
    xmlhttp.send();
    // CHANGE EVENT FOR CONTACT NO
    var item_array=[];
    $(document).on('change','#SRC_tr_lb_contractno',function(){
        if($('#SRC_tr_lb_contractno').val()!='SELECT') {
            $('.preloader').show();
            var slctdcontractno = $('#SRC_tr_lb_contractno').find('option:selected').text();
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
                        var errormsg = error_message[13].toString().replace("[CONTRACTNO]", slctdcontractno);
                        show_msgbox("REPORT SUBMISSION UPDATE", errormsg, "error", false);
                    }
                    $('#SRC_stock_itemno').html(itemno);
                }
            }
            var option = "get_itemnos";
            xmlhttp.open("GET", "DB_PERMITS_ENTRY.php?option=" + option + "&contct_no=" + $('#SRC_tr_lb_contractno').val());
            xmlhttp.send();
        }
    });
// CHANGE EVENT FOR STOCK USAGE ITEM NO
    $(document).on('change','#SRC_stock_itemno',function(){
        var contractid = $('#SRC_stock_itemno').val();
        if(contractid!='SELECT') {
            for (var i = 0; i < item_array.length; i++) {
                if (contractid == item_array[i].no) {
                    $('#SRC_stock_itemname').val(item_array[i].name);
                }
            }
        }
        else {
            $('#SRC_stock_itemname').val('');
        }
    });
    var values_array=[];
    $(document).on("click",'#SRC_searchbtn', function (){
        datatable();
    });
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
                error_message=searchvalues[1];
                if(values_array!=null){
                    var SRC_UPD_table_header='<table id="SRC_tbl_htmltable" border="1"  cellspacing="0" class="srcresult"><thead  bgcolor="#6495ed" style="color:white;text-align:center;"><tr><th></th><th style="text-align:center;">DATE</th><th style="text-align:center;">START</th><th style="text-align:center;">END</th><th style="text-align:center;">OT</th><th width="350px" style="text-align:center;">REMARKS</th><th style="text-align:center;">USERSTAMP</th><th style="text-align:center;">TIMESTAMP</th><th style="text-align:center;">VIEW</th></tr></thead><tbody>'
                    for(var j=0;j<values_array.length;j++){
                        var empdi=values_array[j][0];
                        var reportdate=values_array[j][1];
                        var fromdate=values_array[j][2];
                        var todate=values_array[j][3];
                        var trdid=values_array[j][4];
                        var onduty=values_array[j][5];
                        var remark=values_array[j][6];
                        var userstamp=values_array[j][7];
                        var timestamp=values_array[j][8];
                        if(fromdate==null)
                        {
                            var from='';
                        }
                        else
                        {
                            var from=fromdate;
                        }
                        if(todate==null)
                        {
                            var to='';
                        }
                        else
                        {
                            var to=todate;
                        }
                        if(onduty==null)
                        {
                            var ot='';
                        }
                        else
                        {
                            var ot=onduty;
                        }
                        if(remark==null)
                        {
                            var remarks='';
                        }
                        else
                        {
                            var remarks=remark;
                        }
                        SRC_UPD_table_header+='<tr id='+trdid+' ><td style="text-align:center;"><input type="radio" name="SRC_UPD_rd_flxtbl" class="USRC_UPD_class_radio" id='+trdid+'  value='+trdid+'></td><td nowrap style="text-align:center;">'+reportdate+'</td><td style="text-align:center;">'+from+'</td><td style="text-align:center;"> '+to+'</td><td  style="text-align:center;">'+ot+'</td><td width="350px">'+remarks+'</td><td>'+userstamp+'</td><td nowrap style="text-align:center;">'+timestamp+'</td><td style="text-align:center;"><input type="button" value="VIEW PDF" class="btn btn-info pdf-open-model" id="SRC_UPD_btn_pdf" ></td></tr>';
                    }
                    SRC_UPD_table_header+='</tbody></table>';
                    $('section').html(SRC_UPD_table_header);
                    $('#SRC_tbl_htmltable').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers"
                    });
                    $('#SRC_searchbtn').attr('disabled','disabled');
                    $('#SRC_UPD_div_tablecontainer').show();
                    $('.preloader').hide();
                }
                else
                {
                    show_msgbox("REPORT SUBMISSION UPDATE",error_message[3],"error",false);
                    $('.preloader').hide();
                    $('#SRC_UPD_div_tablecontainer').hide();
                }
            }
        }
        var option="UPDATE_SEARCH_DATA";
        xmlhttp.open("GET","DB_PERMITS_ENTRY.php?option="+option+"&emp="+selectedemp+"&fromdate="+fromdate+"&todate="+todate);
        xmlhttp.send();
    }
    $(document).on('click','.pdf-open-model',function () {
        $('#pdf_show').empty();
        var selectedemp=$('#SRC_team_lb_empname').val();
        var SRC_UPD_idradiovalue = $(this).parent().parent().attr('id');
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4 && xmlhttp.status==200){
                var pdf_filename=xmlhttp.responseText;
                $('#pdfModal').modal({backdrop: 'static', keyboard: false});

                $('#pdf_show').append("<object data='"+pdf_filename+"' type='application/pdf' width='100%' height='100%' ></object>");
            }
        }
        var btn="VIEW_PDF";
        var option="UPDATE_SEARCH";
        xmlhttp.open("GET","DB_PERMITS_ENTRY.php?option="+option+"&trdid="+SRC_UPD_idradiovalue+"&btn="+btn+"&selectedemp="+selectedemp);
        xmlhttp.send();
    });
    $(document).on('change','#SRC_from_date',function(){
        var USRC_UPD_startdate = $('#SRC_from_date').datepicker('getDate');
        var date = new Date( Date.parse( USRC_UPD_startdate ));
        date.setDate( date.getDate() );
        var USRC_UPD_todate = date.toDateString();
        USRC_UPD_todate = new Date( Date.parse( USRC_UPD_todate ));
        $('#SRC_to_date').datepicker("option","minDate",USRC_UPD_todate);
    });
// CLICK EVENT FR RADIO BUTTON
    $(document).on('click','.USRC_UPD_class_radio',function(){
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        $('#SRC_radiosearchbtn').removeAttr('disabled').show();
        $('#SRC_entryform').hide();
        $('#backtotop').hide();
        $('#SRC_Final_Update').hide();
    });
    var employee_name=[];
    var docfilename;
    var imagefolderid;
    $(document).on('click','#SRC_radiosearchbtn',function(){
        $('.preloader').show();
        $('#divImage').empty();
        $('#SRC_jd_chk_road').attr('checked', false);
        $('#SRC_jd_chk_roadm').val('');
        $('#SRC_jd_chk_roadmm').val('');
        $('#SRC_jd_chk_contc').attr('checked', false);
        $('#SRC_jd_chk_concm').val('');
        $('#SRC_jd_chk_concmm').val('');
        $('#SRC_jd_chk_truf').attr('checked', false);
        $('#SRC_jd_chk_trufm').val('');
        $('#SRC_jd_chk_trufmm').val('');
        var selectedemp=$('#SRC_team_lb_empname').val();
        var SRC_UPD_idradiovalue=$('input:radio[name=SRC_UPD_rd_flxtbl]:checked').attr('id');
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4 && xmlhttp.status==200){
                var value_array=JSON.parse(xmlhttp.responseText);
                $('#SRC_radiosearchbtn').attr('disabled','disabled');
                $('#SRC_Final_Update').attr('disabled','disabled').hide();
                imageDataJson="";
                imageData="";
                $('.preloader').hide();
                $('#backtotop').show();
                if(value_array[8]!=null)
                {
                    $('#SRC_Final_Update').show();
                    $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                    employee_name=value_array[0];
                    var sitevisit=value_array[1];
                    var mech_equip_transfer=value_array[2];
                    var machinery_details=value_array[3];
                    var rentalmachinery_details=value_array[4];
                    var equipmentusage_details=value_array[5];
                    var fittingusage_details=value_array[6];
                    var material_details=value_array[7];
                    var teamreport_details=value_array[8];
                    var teamjob=value_array[10];
                    employee_id=value_array[11];
                    var jobdone_pipilaid='';
                    var jobdone_size='';
                    var jobdone_length='';
                    if(value_array[12]!='')
                    {
                        jobdone_pipilaid=((value_array[12]).toString()).split(',');
                    }
                    else{
                        jobdone_pipilaid='';
                    }
                    if(value_array[13]!='')
                    {
                        jobdone_size=((value_array[13]).toString()).split(',');
                    }
                    else{
                        jobdone_size='';
                    }
                    if(value_array[14]!='')
                    {
                        jobdone_length=((value_array[14]).toString()).split(',');
                    }
                    else{
                        jobdone_length='';
                    }
                    item_array=[];
                    imageData=value_array[15];
                    error_message=value_array[16];
                    var meeting_details=value_array[18];
                    var stock_details=value_array[19];
                    item_array=value_array[20];
                    $('#SRC_tr_txt_wftime').val('');
                    $('#SRC_tr_txt_wttime').val('');
                    $('#SRC_tr_txt_weather').val('');
                    if(imageData!=null&&imageData!="")
                    {
                        finalimagedata=imageData;
                        imageDataJson=imageData[0];
                        imageData=imageData[1];
                        $('<img src="' +imageData+ '" style="border:1px solid #F5F5F5;align:center" class="img-responsive" width="600" height="400">').appendTo("#divImage");
                    }
                    if((value_array[12]!='') || (value_array[13]!='') || (value_array[14]!=''))
                    {
                        if(jobdone_pipilaid[0]=='ROAD' || jobdone_pipilaid[1]=='ROAD' || jobdone_pipilaid[2]=='ROAD')
                        {
                            $('#SRC_jd_chk_road').attr('checked', true);
                            if(jobdone_size[0]!=''){
                                $('#SRC_jd_chk_roadm').val(jobdone_size[0]);
                            }
                            if(jobdone_length[0]!=''){
                                $('#SRC_jd_chk_roadmm').val(jobdone_length[0]);
                            }
                            $('#SRC_jd_chk_roadm').removeAttr('disabled');
                            $('#SRC_jd_chk_roadmm').removeAttr('disabled');
                        }
                        else{
                            $('#SRC_jd_chk_road').attr('checked', false);
                            $("#SRC_jd_chk_roadm").attr("disabled", "disabled");
                            $("#SRC_jd_chk_roadmm").attr("disabled", "disabled");
                            $("#SRC_jd_chk_roadm").val('');
                            $("#SRC_jd_chk_roadmm").val('');
                        }
                        if(jobdone_pipilaid[0]=='CONC' || jobdone_pipilaid[1]=='CONC' || jobdone_pipilaid[2]=='CONC')
                        {
                            if(jobdone_pipilaid[0]=='CONC')
                            {
                                $('#SRC_jd_chk_contc').attr('checked', true);
                                if(jobdone_size[0]!=''){
                                    $('#SRC_jd_chk_concm').val(jobdone_size[0]);
                                }
                                if(jobdone_length[0]!=''){
                                    $('#SRC_jd_chk_concmm').val(jobdone_length[0]);
                                }
                                $('#SRC_jd_chk_concm').removeAttr('disabled');
                                $('#SRC_jd_chk_concmm').removeAttr('disabled');
                            }
                            else if(jobdone_pipilaid[1]=='CONC')
                            {
                                $('#SRC_jd_chk_contc').attr('checked', true);
                                if(jobdone_size[1]!=''){
                                    $('#SRC_jd_chk_concm').val(jobdone_size[1]);
                                }
                                if(jobdone_length[1]!=''){
                                    $('#SRC_jd_chk_concmm').val(jobdone_length[1]);
                                }
                                $('#SRC_jd_chk_concm').removeAttr('disabled');
                                $('#SRC_jd_chk_concmm').removeAttr('disabled');
                            }
                            else if(jobdone_pipilaid[2]=='CONC')
                            {
                                $('#SRC_jd_chk_contc').attr('checked', true);
                                if(jobdone_size[2]!=''){
                                    $('#SRC_jd_chk_concm').val(jobdone_size[2]);
                                }
                                if(jobdone_length[2]!=''){
                                    $('#SRC_jd_chk_concmm').val(jobdone_length[2]);
                                }
                                $('#SRC_jd_chk_concm').removeAttr('disabled');
                                $('#SRC_jd_chk_concmm').removeAttr('disabled');
                            }
                        }
                        else{
                            $('#SRC_jd_chk_contc').attr('checked', false);
                            $("#SRC_jd_chk_concm").attr("disabled", "disabled");
                            $("#SRC_jd_chk_concmm").attr("disabled", "disabled");
                            $("#SRC_jd_chk_concm").val('');
                            $("#SRC_jd_chk_concmm").val('');
                        }
                        if(jobdone_pipilaid[0]=='TURF' || jobdone_pipilaid[1]=='TURF' || jobdone_pipilaid[2]=='TURF')
                        {
                            if(jobdone_pipilaid[0]=='TURF')
                            {
                                $('#SRC_jd_chk_truf').attr('checked', true);
                                if(jobdone_size[0]!=''){
                                    $('#SRC_jd_chk_trufm').val(jobdone_size[0]);
                                }
                                if(jobdone_length[0]!=''){
                                    $('#SRC_jd_chk_trufmm').val(jobdone_length[0]);
                                }
                                $('#SRC_jd_chk_trufm').removeAttr('disabled');
                                $('#SRC_jd_chk_trufmm').removeAttr('disabled');
                            }
                            else if(jobdone_pipilaid[1]=='TURF')
                            {
                                $('#SRC_jd_chk_truf').attr('checked', true);
                                if(jobdone_size[1]!=''){
                                    $('#SRC_jd_chk_trufm').val(jobdone_size[1]);
                                }
                                if(jobdone_length[1]!=''){
                                    $('#SRC_jd_chk_trufmm').val(jobdone_length[1]);
                                }
                                $('#SRC_jd_chk_trufm').removeAttr('disabled');
                                $('#SRC_jd_chk_trufmm').removeAttr('disabled');
                            }
                            else if(jobdone_pipilaid[2]=='TURF')
                            {
                                $('#SRC_jd_chk_truf').attr('checked', true);
                                if(jobdone_size[2]!=''){
                                    $('#SRC_jd_chk_trufm').val(jobdone_size[2]);
                                }
                                if(jobdone_length[2]!=''){
                                    $('#SRC_jd_chk_trufmm').val(jobdone_length[2]);
                                }
                                $('#SRC_jd_chk_trufm').removeAttr('disabled');
                                $('#SRC_jd_chk_trufmm').removeAttr('disabled');
                            }
                        }
                        else{
                            $('#SRC_jd_chk_truf').attr('checked', false);
                            $("#SRC_jd_chk_trufm").attr("disabled", "disabled");
                            $("#SRC_jd_chk_trufmm").attr("disabled", "disabled");
                            $("#SRC_jd_chk_trufm").val('');
                            $("#SRC_jd_chk_trufmm").val('');
                        }
                    }
                    else{
                        $("#SRC_jd_chk_roadm").attr("disabled", "disabled");
                        $("#SRC_jd_chk_roadmm").attr("disabled", "disabled");
                        $("#SRC_jd_chk_concm").attr("disabled", "disabled");
                        $("#SRC_jd_chk_concmm").attr("disabled", "disabled");
                        $("#SRC_jd_chk_trufm").attr("disabled", "disabled");
                        $("#SRC_jd_chk_trufmm").attr("disabled", "disabled");
                    }
                    if((teamjob!='') || (teamjob!=null))
                    {
                        for(var t=0;t<teamjob.length;t++)
                        {
                            var id=teamjob[t][0];
                            id=id.replace(" ","");
                            $('#'+id).attr('checked', false);
                        }
                    }
                    if(value_array[9]!=null)
                    {
                        var jobdetails=value_array[9].split(',');

                        for(var s=0;s<jobdetails.length;s++)
                        {
                            var id=jobdetails[s];
                            id=id.replace(" ","");
                            $('#'+id).attr('checked', true);
                        }
                    }
                    //TEAM REPORT DETAILS
                    for(var a=0;a<teamreport_details.length;a++)
                    {
                        if(teamreport_details[a][1]==null){teamreport_details[a][1]="";}
                        if(teamreport_details[a][2]==null){teamreport_details[a][2]="";}
                        if(teamreport_details[a][3]==null){teamreport_details[a][3]="";}
                        if(teamreport_details[a][4]==null || teamreport_details[a][4]=='00:00'){teamreport_details[a][4]="";}
                        if(teamreport_details[a][5]==null || teamreport_details[a][5]=='00:00'){teamreport_details[a][5]="";}
                        if(teamreport_details[a][6]==null){teamreport_details[a][6]="";}
                        if(teamreport_details[a][7]==null){teamreport_details[a][7]="";}
                        if(teamreport_details[a][8]==null){teamreport_details[a][8]="";}
                        if(teamreport_details[a][9]==null){teamreport_details[a][9]="";}
                        if(teamreport_details[a][10]==null || teamreport_details[a][10]=='00:00'){teamreport_details[a][10]="";}
                        if(teamreport_details[a][11]==null || teamreport_details[a][11]=='00:00'){teamreport_details[a][11]="";}
                        if(teamreport_details[a][12]==null){teamreport_details[a][12]="";}

                        $('#SRC_tr_txt_location').val(teamreport_details[a][1]);
                        $('#SRC_tr_txt_date').val(teamreport_details[a][0]);
                        $('#SRC_tr_lb_contractno').val(teamreport_details[a][2]);
                        $('#SRC_tr_tb_team').val(teamreport_details[a][3]);
                        $('#SRC_tr_txt_wftime').val(teamreport_details[a][7]);
                        $('#SRC_tr_txt_wttime').val(teamreport_details[a][8]);
                        $('#SRC_tr_txt_reachsite').val(teamreport_details[a][4]);
                        $('#SRC_tr_txt_leavesite').val(teamreport_details[a][5]);
                        $('#SRC_jd_txt_pipetesting').val(teamreport_details[a][9]);
                        $('#SRC_jd_txt_start').val(teamreport_details[a][10]);
                        $('#SRC_jd_txt_end').val(teamreport_details[a][11]);
                        $('#SRC_jd_ta_remark').val(teamreport_details[a][12]).height(22);
                        $('#SRC_tr_txt_weather').val(teamreport_details[a][13]);
                        if(teamreport_details[a][13]=='')
                        {
                            $("#SRC_tr_txt_wftime").attr("disabled", "disabled");
                            $("#SRC_tr_txt_wttime").attr("disabled", "disabled");
                            $("#SRC_tr_txt_wftime").val('');
                            $("#SRC_tr_txt_wttime").val('');
                        }
                        else{
                            $('#SRC_tr_txt_wftime').removeAttr('disabled');
                            $('#SRC_tr_txt_wttime').removeAttr('disabled');
                        }
                    }
                    //EMPLOYEE DETAILS
                    $('#SRC_Employee_table tr:not(:first)').remove();
                    for(var i=0;i<employee_name.length;i++)
                    {
                        var autoid=i+1;
                        var emp_name="SRC_Emp_name"+autoid;
                        var emp_id="SRC_Emp_id"+autoid;
                        var emp_start="SRC_Emp_starttime"+autoid;
                        var emp_end="SRC_Emp_endtime"+autoid;
                        var emp_ot="SRC_Emp_ot"+autoid;
                        var emp_remark="SRC_Emp_remark"+autoid;
                        if(employee_name[i][5]==null){employee_name[i][5]="";}
                        if(employee_name[i][4]==null){employee_name[i][4]="";}
                        if(employee_name[i][3]==null || employee_name[i][3]=='00:00'){employee_name[i][3]="";}
                        if(employee_name[i][2]==null || employee_name[i][2]=='00:00'){employee_name[i][2]="";}

                        if(employee_id==employee_name[i][0])
                        {
                            var appendrow='<tr id="'+autoid+'" class="active"><td><div><input type="text" class="form-control" readonly style="max-width: 560px" name="name" id="'+emp_name+'" value="'+employee_name[i][1]+'"><input type="hidden" class="form-control" style="max-width: 100px" id="'+emp_id+'" value="'+employee_name[i][0]+'"></div></td><td><div class="col-lg-10"><input type="text" class="form-control time-picker stime" style="max-width: 100px" id="'+emp_start+'" value="'+employee_name[i][2]+'"></div></td><td><div class="col-lg-10"><input type="text" class="form-control time-picker etime" style="max-width: 100px" id="'+emp_end+'" value="'+employee_name[i][3]+'"></div></td><td><div class="col-lg-10"><input type="text" class="form-control amountonly size ot" style="max-width: 100px" id="'+emp_ot+'" value="'+employee_name[i][4]+'"></div></td><td><div><textarea class="form-control remarklen removecap textareaaccinjured" rows="1" id="'+emp_remark+'">'+employee_name[i][5]+'</textarea></div></td></tr>';
                        }
                        else
                        {
                            appendrow='<tr id="'+autoid+'" class="active"><td><div><input type="text" class="form-control" readonly style="max-width: 560px" name="name" id="'+emp_name+'" value="'+employee_name[i][1]+'"><input type="hidden" class="form-control" style="max-width: 100px" id="'+emp_id+'" value="'+employee_name[i][0]+'"></div></td><td><div class="col-lg-10"><input type="text" readonly class="form-control time-picker stime" style="max-width: 100px" id="'+emp_start+'" value="'+employee_name[i][2]+'"></div></td><td><div class="col-lg-10"><input type="text" readonly class="form-control time-picker etime" style="max-width: 100px" id="'+emp_end+'" value="'+employee_name[i][3]+'"></div></td><td><div class="col-lg-10"><input type="text" readonly class="form-control amountonly size ot" style="max-width: 100px" id="'+emp_ot+'" value="'+employee_name[i][4]+'"></div></td><td><div><textarea readonly class="form-control remarklen removecap textareaaccinjured" rows="1" id="'+emp_remark+'">'+employee_name[i][5]+'</textarea></div></td></tr>';
                        }
                        $('#SRC_Employee_table tr:last').after(appendrow);
                        $('.time-picker').datetimepicker({
                            format:'H:mm'
                        });
                        $('#SRC_entryform').show();
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
                        $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:1,imaginary:2}});
                        $(document).on("keyup",'.removecap',function() {
                            if (this.value.match(/[\^]/g)) {
                                this.value = this.value.replace(/[\^]/g, '');
                            }
                        });
                    }
                    //MEETING DETAILS
                    $('#SRC_meeting_table tr:not(:first)').remove();
                    if(meeting_details!=null)
                    {
                        for(var v=0;v<meeting_details.length;v++)
                        {
                            var mt_tablerowcount=$('#SRC_meeting_table tr').length;
                            var mt_editid='SRC_mt_editrow/'+mt_tablerowcount;
                            var mt_deleterowid='SRC_mt_deleterow/'+mt_tablerowcount;
                            var mt_row_id="SRC_mt_tr_"+mt_tablerowcount;
                            var temp_textbox_id="SRC_mttemp_id"+mt_tablerowcount;
                            if(meeting_details[v][1]==null){meeting_details[v][1]="SELECT";}
                            var mt_remark;
                            if(meeting_details[v][2]==null){
                                mt_remark="";
                            }
                            else
                            {
                                mt_remark=meeting_details[v][2];
                            }
                            var appendrow='<tr class="active" id='+mt_row_id+'><td style="max-width: 150px"><div class="col-md-1"><span style="display:block;" class="glyphicon glyphicon-edit SRC_mt_editbutton" id='+mt_editid+'></span></div><div class="col-md-1"><span style="display:block;" class="glyphicon glyphicon-trash SRC_mt_removebutton" id='+mt_deleterowid+'></div><input type="hidden" id='+temp_textbox_id+' value='+meeting_details[v][0]+'></td><td style="max-width: 250px">'+meeting_details[v][1]+'</td><td style="max-width: 250px">'+mt_remark+'</td></tr>';
                            $('#SRC_meeting_table tr:last').after(appendrow);
                        }
                    }
                    //SITE VISIT DETAILS
                    $('#SRC_sv_tbl tr:not(:first)').remove();
                    if(sitevisit!=null)
                    {
                        for(var j=0;j<sitevisit.length;j++)
                        {
                            var sv_tablerowcount=$('#SRC_sv_tbl tr').length;
                            var sv_editid='SRC_sv_editrow/'+sv_tablerowcount;
                            var sv_deleterowid='SRC_sv_deleterow/'+sv_tablerowcount;
                            var sv_row_id="SRC_sv_tr_"+sv_tablerowcount;
                            var temp_textbox_id="SRC_svtemp_id"+sv_tablerowcount;
                            if(sitevisit[j][1]==null){sitevisit[j][1]="";}
                            if(sitevisit[j][2]==null){sitevisit[j][2]="";}
                            if(sitevisit[j][3]==null || sitevisit[j][3]=='00:00'){sitevisit[j][3]="";}
                            if(sitevisit[j][4]==null || sitevisit[j][4]=='00:00'){sitevisit[j][4]="";}
                            var siteremark;
                            if(sitevisit[j][5]==null){
                                siteremark="";
                            }
                            else
                            {
                                siteremark=sitevisit[j][5];
                            }
                            var appendrow='<tr class="active" id='+sv_row_id+'><td style="max-width: 150px"><div class="col-md-1"><span style="display:block;" class="glyphicon glyphicon-edit SRC_sv_editbutton" id='+sv_editid+'></span></div><div class="col-md-1"><span style="display:block;" class="glyphicon glyphicon-trash SRC_sv_removebutton" id='+sv_deleterowid+'></div><input type="hidden" id='+temp_textbox_id+' value='+sitevisit[j][0]+'></td><td style="max-width: 250px">'+sitevisit[j][1]+'</td><td style="max-width: 250px">'+sitevisit[j][2]+'</td><td style="max-width: 250px">'+sitevisit[j][3]+'</td><td style="max-width: 250px">'+sitevisit[j][4]+'</td><td style="max-width: 250px">'+siteremark+'</td></tr>';
                            $('#SRC_sv_tbl tr:last').after(appendrow);
                        }
                    }
                // MACHINERY_EQUIPMENT DETAILS
                    $('#SRC_mtransfer_table tr:not(:first)').remove();
                    if(mech_equip_transfer!=null)
                    {
                        for(var k=0;k<mech_equip_transfer.length;k++)
                        {
                            var mtransfertablerowcount=$('#SRC_mtransfer_table tr').length;
                            var mtransfereditid='SRC_mtransfereditrow/'+mtransfertablerowcount;
                            var mtransferdeleterowid='SRC_mtransferdeleterow/'+mtransfertablerowcount;
                            var mtransfer_row_id="SRC_mtranser_tr_"+mtransfertablerowcount;
                            var temp_textbox_id="SRC_mtransfertemp_id"+mtransfertablerowcount;
                            if(mech_equip_transfer[k][1]==null){mech_equip_transfer[k][1]="";}
                            if(mech_equip_transfer[k][2]==null){mech_equip_transfer[k][2]="";}
                            if(mech_equip_transfer[k][3]==null){mech_equip_transfer[k][3]="SELECT";}
                            var mtransferremark;
                            if(mech_equip_transfer[k][4]==null){
                                mtransferremark="";
                            }
                            else
                            {
                                mtransferremark=mech_equip_transfer[k][4];
                            }
                            var appendrow='<tr class="active" id='+mtransfer_row_id+'><td style="max-width: 150px"><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRC_mtransfereditbutton" id='+mtransfereditid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRC_mtransferremovebutton"  id='+mtransferdeleterowid+'></div><input type="hidden" id='+temp_textbox_id+' value='+mech_equip_transfer[k][0]+'></td><td style="max-width: 250px">'+mech_equip_transfer[k][1]+'</td><td style="max-width: 250px">'+mech_equip_transfer[k][3]+'</td><td style="max-width: 250px">'+mech_equip_transfer[k][2]+'</td><td style="max-width: 250px">'+mtransferremark+'</td></tr>';
                            $('#SRC_mtransfer_table tr:last').after(appendrow);
                        }
                    }
                    //MACHINERY USAGE DETAILS
                    $('#SRC_machinery_table tr:not(:first)').remove();
                    if(machinery_details!=null)
                    {
                        for(var l=0;l<machinery_details.length;l++)
                        {
                            var machinerytablerowcount=$('#SRC_machinery_table tr').length;
                            var machineryeditid='SRC_machineryeditrow/'+machinerytablerowcount;
                            var machinerydeleterowid='SRC_machinerydeleterow/'+machinerytablerowcount;
                            var machinery_row_id="SRC_machinery_tr_"+machinerytablerowcount;
                            var temp_textbox_id="SRC_machinerytemp_id"+machinerytablerowcount;
                            if(machinery_details[l][1]==null){machinery_details[l][1]="SELECT";}
                            if(machinery_details[l][2]==null || machinery_details[l][2]=='00:00'){machinery_details[l][2]="";}
                            if(machinery_details[l][3]==null || machinery_details[l][3]=='00:00'){machinery_details[l][3]="";}
                            var machineryremark;
                            if(machinery_details[l][4]==null){
                                machineryremark="";
                            }
                            else
                            {
                                machineryremark=machinery_details[l][4];
                            }
                            var appendrow='<tr class="active" id='+machinery_row_id+'><td style="max-width: 150px"><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRC_machineryeditbutton" id='+machineryeditid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRC_machineryremovebutton"  id='+machinerydeleterowid+'><div><input type="hidden" id='+temp_textbox_id+' value='+machinery_details[l][0]+'></td><td style="max-width: 250px">'+machinery_details[l][1]+'</td><td style="max-width: 250px">'+machinery_details[l][2]+'</td><td style="max-width: 250px">'+machinery_details[l][3]+'</td><td style="max-width: 250px">'+machineryremark+'</td></tr>';
                            $('#SRC_machinery_table tr:last').after(appendrow);
                        }
                    }
                    //RENTAL MACHINERY DETAILS
                    $('#SRC_rental_table tr:not(:first)').remove();
                    if(rentalmachinery_details!=null)
                    {
                        for(var m=0;m<rentalmachinery_details.length;m++)
                        {
                            var rentaltablerowcount=$('#SRC_rental_table tr').length;
                            var rentaleditid='SRC_machineryeditrow/'+rentaltablerowcount;
                            var rentaldeleterowid='SRC_machinerydeleterow/'+rentaltablerowcount;
                            var rental_row_id="SRC_rental_tr_"+rentaltablerowcount;
                            var temp_textbox_id="SRC_rentaltemp_id"+rentaltablerowcount;
                            if(rentalmachinery_details[m][1]==null){rentalmachinery_details[m][1]="";}
                            if(rentalmachinery_details[m][2]==null){rentalmachinery_details[m][2]="";}
                            if(rentalmachinery_details[m][3]==null){rentalmachinery_details[m][3]="";}
                            if(rentalmachinery_details[m][4]==null || rentalmachinery_details[m][4]=='00:00'){rentalmachinery_details[m][4]="";}
                            if(rentalmachinery_details[m][5]==null || rentalmachinery_details[m][5]=='00:00'){rentalmachinery_details[m][5]="";}
                            var rentalremark;
                            if(rentalmachinery_details[m][6]==null){
                                rentalremark="";
                            }
                            else
                            {
                                rentalremark=rentalmachinery_details[m][6];
                            }
                            var appendrow='<tr class="active" id='+rental_row_id+'><td style="max-width: 150px"><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRC_rentalmechinery_editbutton" id='+rentaleditid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRC_rental_machineryremovebutton"  id='+rentaldeleterowid+'></div><input type="hidden"  id='+temp_textbox_id+' value='+rentalmachinery_details[m][0]+'></td><td style="max-width: 250px">'+rentalmachinery_details[m][1]+'</td><td style="max-width: 250px">'+rentalmachinery_details[m][2]+'</td><td style="max-width: 250px">'+rentalmachinery_details[m][3]+'</td><td style="max-width: 250px">'+rentalmachinery_details[m][4]+'</td><td style="max-width: 250px">'+rentalmachinery_details[m][5]+'</td><td style="max-width: 250px">'+rentalremark+'</td>';
                            $('#SRC_rental_table tr:last').after(appendrow);
                        }
                    }
                    //EQUIPMENT USAGE DETAILS
                    $('#SRC_equipment_table tr:not(:first)').remove();
                    if(equipmentusage_details!=null)
                    {
                        for(var n=0;n<equipmentusage_details.length;n++)
                        {
                            var equipmenttablerowcount=$('#SRC_equipment_table tr').length;
                            var equipmenteditid='SRC_equipmenteditrow/'+equipmenttablerowcount;
                            var equipmentdeleterowid='SRC_equipementdeleterow/'+equipmenttablerowcount;
                            var equipment_row_id="SRC_equipment_tr_"+equipmenttablerowcount;
                            var temp_textbox_id="SRC_equipmenttemp_id"+equipmenttablerowcount;
                            if(equipmentusage_details[n][1]==null){equipmentusage_details[n][1]="";}
                            if(equipmentusage_details[n][2]==null){equipmentusage_details[n][2]="";}
                            if(equipmentusage_details[n][3]==null || equipmentusage_details[n][3]=='00:00'){equipmentusage_details[n][3]="";}
                            if(equipmentusage_details[n][4]==null || equipmentusage_details[n][4]=='00:00'){equipmentusage_details[n][4]="";}
                            var equipmentremark;
                            if(equipmentusage_details[n][5]==null){
                                equipmentremark="";
                            }
                            else
                            {
                                equipmentremark=equipmentusage_details[n][5];
                            }
                            var appendrow='<tr class="active" id='+equipment_row_id+'><td style="max-width:150px"><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRC_equipmenteditbutton" id='+equipmenteditid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRC_equipmentremovebutton" id='+equipmentdeleterowid+'></div><input type="hidden" id='+temp_textbox_id+' value='+equipmentusage_details[n][0]+'></td><td style="max-width: 250px">'+equipmentusage_details[n][1]+'</td><td style="max-width: 250px">'+equipmentusage_details[n][2]+'</td><td style="max-width: 250px">'+equipmentusage_details[n][3]+'</td><td style="max-width: 250px">'+equipmentusage_details[n][4]+'</td><td style="max-width: 250px">'+equipmentremark+'</td></tr>';
                            $('#SRC_equipment_table tr:last').after(appendrow);
                        }
                    }
                    //FITTING USAGE DETAILS
                    $('#SRC_fitting_table tr:not(:first)').remove();
                    if(fittingusage_details!=null)
                    {
                        for(var o=0;o<fittingusage_details.length;o++)
                        {
                            var tablerowCount=$('#SRC_fitting_table tr').length;
                            var editid='SRC_fitting_editrow/'+tablerowCount;
                            var deleterowid='SRC_fitting_deleterow/'+tablerowCount;
                            var row_id="SRC_fitting_tr_"+tablerowCount;
                            var temp_textbox_id="SRC_fittingtemp_id"+tablerowCount;
                            if(fittingusage_details[o][1]==null){fittingusage_details[o][1]="SELECT";}
                            if(fittingusage_details[o][2]==null){fittingusage_details[o][2]="";}
                            if(fittingusage_details[o][3]==null){fittingusage_details[o][3]="";}
                            var fittingremark;
                            if(fittingusage_details[o][4]==null){
                                fittingremark="";
                            }
                            else
                            {
                                fittingremark=fittingusage_details[o][4];
                            }
                            var appendrow='<tr  class="active" id='+row_id+'><td style="max-width: 150px"><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRC_fitting_editbutton" id='+editid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRC_fitting_removebutton"  id='+deleterowid+'></div><input type="hidden" id="'+temp_textbox_id+'" value='+fittingusage_details[o][0]+'></td><td style="max-width: 250px">'+fittingusage_details[o][1]+'</td><td style="max-width: 250px">'+fittingusage_details[o][2]+'</td><td style="max-width: 250px">'+fittingusage_details[o][3]+'</td><td style="max-width: 250px">'+fittingremark+'</td></tr>';
                            $('#SRC_fitting_table tr:last').after(appendrow);
                        }
                    }
                    //MATERIAL USAGE DETAILS
                    $('#SRC_material_table tr:not(:first)').remove();
                    if(material_details!=null)
                    {
                        for(var p=0;p<material_details.length;p++)
                        {
                            var tablerowCount=$('#SRC_material_table tr').length;
                            var editid='SRC_material_editrow/'+tablerowCount;
                            var deleterowid='SRC_material_deleterow/'+tablerowCount;
                            var row_id="SRC_material_tr_"+tablerowCount;
                            var temp_textbox_id="SRC_materialtemp_id"+tablerowCount;
                            if(material_details[p][1]==null){material_details[p][1]="SELECT";}
                            if(material_details[p][2]==null){material_details[p][2]="";}
                            if(material_details[p][3]==null){material_details[p][3]="";}
                            var appendrow='<tr class="active" id='+row_id+'><td style="max-width: 150px"><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRC_material_editbutton" id='+editid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRC_material_removebutton"  id='+deleterowid+'></div><input type="hidden" id="'+temp_textbox_id+'" value='+material_details[p][0]+'></td><td style="max-width: 250px">'+material_details[p][1]+'</td><td style="max-width: 250px">'+material_details[p][2]+'</td><td style="max-width: 250px">'+material_details[p][3]+'</td></tr>';
                            $('#SRC_material_table tr:last').after(appendrow);
                        }
                    }
                    //STOCK USAGE DETAILS
                    $('#SRC_stockusage_table tr:not(:first)').remove();
                    if(stock_details!=null)
                    {
                        for(var q=0;q<stock_details.length;q++)
                        {
                            var tablerowCount=$('#SRC_stockusage_table tr').length;
                            var editid='SRC_stock_editrow/'+tablerowCount;
                            var deleterowid='SRC_stock_deleterow/'+tablerowCount;
                            var row_id="SRC_stock_tr_"+tablerowCount;
                            var temp_textbox_id="SRC_stocktemp_id"+tablerowCount;
                            if(stock_details[q][1]==null){stock_details[q][1]="SELECT";}
                            if(stock_details[q][2]==null){stock_details[q][2]="";}
                            if(stock_details[q][3]==null){stock_details[q][3]="";}
                            var appendrow='<tr class="active" id='+row_id+'><td style="max-width: 150px"><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRC_stock_editbutton" id='+editid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRC_stock_removebutton"  id='+deleterowid+'></div><input type="hidden" id="'+temp_textbox_id+'" value='+stock_details[q][0]+'></td><td style="max-width: 250px">'+stock_details[q][1]+'</td><td style="max-width: 250px">'+stock_details[q][2]+'</td><td style="max-width: 250px">'+stock_details[q][3]+'</td></tr>';
                            $('#SRC_stockusage_table tr:last').after(appendrow);
                        }
                    }
                    var itemnos = '<option>SELECT</option>';
                    if (item_array.length > 0) {
                        for (var i = 0; i < item_array.length; i++) {
                            itemnos += '<option value="' + item_array[i].no + '">' + item_array[i].no + '</option>';
                        }
                    }
                    $('#SRC_stock_itemno').html(itemnos);
                }
                else
                {
                    show_msgbox("REPORT SUBMISSION UPDATE",error_message[3],"error",false);
                    $('#SRC_entryform').hide();
                    $('#SRC_Final_Update').hide();
                }
            }
        }
        var option="UPDATE_SEARCH";
        xmlhttp.open("GET","DB_PERMITS_ENTRY.php?option="+option+"&trdid="+SRC_UPD_idradiovalue+"&selectedemp="+selectedemp);
        xmlhttp.send();
    });

//CHANGE EVENT FOR EMPLOYEE NAME
    $('#SRC_team_lb_empname').change(function(){
        $('#SRC_Final_Update').hide();
        $('#SRC_UPD_div_tablecontainer').hide();
        $('#SRC_entryform').hide();
        $('#SRC_radiosearchbtn').hide();
        $('#SRC_from_date').val('');
        $('#SRC_to_date').val('');
        $('#backtotop').hide();
        if($(this).val()=="SELECT")
        {
            $('#SRC_from_date').val('');
            $('#SRC_to_date').val('');
            $('#SRC_UPD_div_tablecontainer').hide();
            $('#SRC_entryform').hide();
            $('#SRC_radiosearchbtn').hide();
            $('#SRC_Final_Update').hide();
        }

    });
//CHANGE EVENT FOR  DATERANGE
    $('.dterange').change(function(){
        $('#SRC_UPD_div_tablecontainer').hide();
        $('#SRC_entryform').hide();
        $('#SRC_radiosearchbtn').hide();
        $('#backtotop').hide();
        $('#SRC_Final_Update').hide();
        if(($('#SRC_team_lb_empname').val()!='SELECT') && ($('#SRC_from_date').val()!='') && ($('#SRC_to_date').val()!=''))
        {
            $('#SRC_searchbtn').removeAttr("disabled");
        }
        else
        {
            $('#SRC_searchbtn').attr("disabled","disabled");
        }
    });
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
//TEAM REPORT FUNCTION
//    $('.time-picker').timepicker();
    $(".date-picker").datepicker();
    $(".date-picker").on("change", function () {
        var id = $(this).attr("id");
        var val = $("label[for='" + id + "']").text();
        $("#msg").text(val + " changed");
    });
//END OF TEAM REPORTR FUNCTION

//MEETING ADD,DELETE AND UPDATE FUNCTION
    $('#SRC_mt_btn_update').hide();
//CLICK EVENT FOR MEETING ADD BUTTON
    $('#SRC_mt_btn_addrow').click(function(){
        var topic=$('#SRC_mt_lb_topic').val();
        var remark=$('#SRC_mt_ta_remark').val();
        if((topic!='SELECT'))
        {
            var mt_tablerowcount=$('#SRC_meeting_table tr').length;
            var mt_trrowid=mt_tablerowcount;
            if(mt_tablerowcount>1){
                var mt_lastid=$('#SRC_meeting_table tr:last').attr('id');
                var splittrid=mt_lastid.split('tr_');
                mt_trrowid=parseInt(splittrid[1])+1;
            }
            var mt_editid='SRC_mt_editrow/'+mt_trrowid;
            var mt_deleterowid='SRC_mt_deleterow/'+mt_trrowid;
            var mt_row_id="SRC_mt_tr_"+mt_trrowid;
            var temp_textbox_id="SRC_mttemp_id"+mt_trrowid;
            var appendrow='<tr class="active" id='+mt_row_id+'><td style="max-width: 150px"><div class="col-md-1"><span style="display:block;" class="glyphicon glyphicon-edit SRC_mt_editbutton" id='+mt_editid+'></span></div><div class="col-md-1"><span style="display:block;" class="glyphicon glyphicon-trash SRC_mt_removebutton" id='+mt_deleterowid+'></div><input type="hidden" class="form-control"  style="max-width: 100px" id='+temp_textbox_id+'></td></td><td>'+topic+'</td><td>'+remark+'</td></tr>';
            $('#SRC_meeting_table tr:last').after(appendrow);
            mt_formclear();
//            $('#SRC_mt_btn_addrow').attr('disabled','disabled');
            $('#SRC_mt_btn_update').hide();
        }
        else if((topic=='SELECT')&&(remark!=''))
        {
            var msg=error_message[12].toString().replace('[NAME]','MEETING TOPIC');
            show_msgbox("REPORT SUBMISSION UPDATE",msg,"error",false);
        }
        else
        {
            show_msgbox("REPORT SUBMISSION UPDATE",error_message[11],"error",false);
        }

    });
// FUNCTION FOR MEETING FORM CLEAR
    function mt_formclear(){
        $('#SRC_mt_lb_topic').val('SELECT').show();
        $('#SRC_mt_ta_remark').val('').height('22');
    }
// CLICK EVENT FOR MEETING REMOVE BUTTON
    $(document).on("click",'.SRC_mt_removebutton', function (){
        $(this).closest('tr').remove();
        mt_formclear();
        $('#SRC_mt_btn_update').hide();
        $('#SRC_mt_btn_addrow').show();
        return false;
    });
//CLICK EVENT FOR MEETING EDIT BUTTON
    $(document).on("click",'.SRC_mt_editbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#SRC_mt_rowid').val(rowid);
        $('#SRC_mt_btn_addrow').hide();
        $('#SRC_mt_btn_update').show();
        var $tds= $(this).closest('tr').children('td'),
            mt_topic = $tds.eq(1).text(),
            mt_remarks = $tds.eq(2).text();
        $('#SRC_mt_lb_topic').val(mt_topic);
        $('#SRC_mt_ta_remark').val(mt_remarks);
    });
// CLICK EVENT FORM MEETING UPDATE ROW
    $(document).on("click",'.SRC_mt_btn_updaterow', function (){
        var mt_topic=$('#SRC_mt_lb_topic').val();
        var mt_remarks=$('#SRC_mt_ta_remark').val();
        var mt_rowid=$('#SRC_mt_rowid').val();
        if((mt_topic!='SELECT'))
        {
            var objUser = {"mt_id":mt_rowid,"mt_topic":mt_topic,"mt_remarks":mt_remarks};
            var objKeys = ["","mt_topic","mt_remarks"];
            $('#SRC_mt_tr_' + objUser.mt_id + ' td').each(function(i) {
                $(this).text(objUser[objKeys[i]]);
            });
            $('#SRC_mt_btn_addrow').show();
            $('#SRC_mt_btn_update').hide();
            mt_formclear();
        }
        else if((mt_topic=='SELECT')&&(mt_remarks!=''))
        {
            var msg=error_message[12].toString().replace('[NAME]','MEETING TOPIC');
            show_msgbox("REPORT SUBMISSION UPDATE",msg,"error",false);
            $('#SRC_mt_btn_update').show();
            $('#SRC_mt_btn_addrow').hide();
        }
        else
        {
            show_msgbox("REPORT SUBMISSION UPDATE",error_message[11],"error",false);
            $('#SRC_mt_btn_addrow').hide();
            $('#SRC_mt_btn_update').show();
        }
//        $('#SRC_mt_btn_update,#SRC_mt_btn_addrow').attr("disabled", "disabled");
    });
// FORM VALIDATION FOR BUTTONS
    $(document).on("change blur",'.meetingform-validation', function (){
        var mt_topic=$('#SRC_mt_lb_topic').val();
        var mt_remarks=$('#SRC_mt_ta_remark').val();
//        if((mt_topic!='SELECT') && (mt_remarks!=''))
//        {
//            $("#SRC_mt_btn_addrow,#SRC_mt_btn_update").removeAttr("disabled");
//        }
//        else
//        {
//            $("#SRC_mt_btn_addrow,#SRC_mt_btn_update").attr("disabled", "disabled");
//        }
    });

// MEETING ADD,DELETE AND UPDATE FUNCTION

//SITE VISIT ADD,DELETE AND UPDATE FUNCTION
    $('#SRC_sv_btn_update').hide();
//CLICK EVENT FOR SITEVISIT ADD BUTTON
    $('#SRC_sv_btn_addrow').click(function(){
        var desingnation=$('#SRC_sv_txt_designation').val();
        var name=$('#SRC_sv_txt_name').val();
        var start=$('#SRC_sv_txt_start').val();
        var end=$('#SRC_sv_txt_end').val();
        var remark=$('#SRC_sv_txt_remark').val();
        if((desingnation!='') || (name!='') || (start!='') || (end!='')||(remark!=''))
        {
            var sv_tablerowcount=$('#SRC_sv_tbl tr').length;
            var sv_trrowid=sv_tablerowcount;
            if(sv_tablerowcount>1){
                var sv_lastid=$('#SRC_sv_tbl tr:last').attr('id');
                var splittrid=sv_lastid.split('tr_');
                sv_trrowid=parseInt(splittrid[1])+1;
            }
            var sv_editid='SRC_sv_editrow/'+sv_trrowid;
            var sv_deleterowid='SRC_sv_deleterow/'+sv_trrowid;
            var sv_row_id="SRC_sv_tr_"+sv_trrowid;
            var temp_textbox_id="SRC_svtemp_id"+sv_trrowid;
            var appendrow='<tr class="active" id='+sv_row_id+'><td><div class="col-md-1"><span style="display:block;" class="glyphicon glyphicon-edit SRC_sv_editbutton" id='+sv_editid+'></span></div><div class="col-md-1"><span style="display:block;" class="glyphicon glyphicon-trash SRC_sv_removebutton" id='+sv_deleterowid+'></div><input type="hidden" class="form-control"  style="max-width: 100px" id='+temp_textbox_id+'></td><td>'+desingnation+'</td><td>'+name+'</td><td>'+start+'</td><td>'+end+'</td><td>'+remark+'</td></tr>';
            $('#SRC_sv_tbl tr:last').after(appendrow);
            sv_formclear();
//            $('#SRC_sv_btn_addrow').attr('disabled','disabled');
            $('#SRC_sv_btn_update').hide();
        }
        else
        {
            show_msgbox("REPORT SUBMISSION UPDATE",error_message[11],"error",false);
        }
    });
// FUNCTION FOR SITEVISIT FORM CLEAR
    function sv_formclear(){
        $('#SRC_sv_txt_designation').val('');
        $('#SRC_sv_txt_name').val('');
        $('#SRC_sv_txt_start').val('');
        $('#SRC_sv_txt_end').val('');
        $('#SRC_sv_txt_remark').val('').height('22');
    }
// CLICK EVENT FOR SITEVISIT REMOVE BUTTON
    $(document).on("click",'.SRC_sv_removebutton', function (){
        $(this).closest('tr').remove();
        sv_formclear()
        $('#SRC_sv_btn_update').hide();
        $('#SRC_sv_btn_addrow').show();
        return false;
    });
//CLICK EVENT FOR SITEVISIT EDIT BUTTON
    $(document).on("click",'.SRC_sv_editbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#SRC_sv_rowid').val(rowid);
        $('#SRC_sv_btn_addrow').hide();
        $('#SRC_sv_btn_update').show();
        var $tds = $(this).closest('tr').children('td'),
            sv_desgn = $tds.eq(1).text(),
            sv_name = $tds.eq(2).text(),
            sv_start = $tds.eq(3).text(),
            sv_end = $tds.eq(4).text(),
            sv_remarks = $tds.eq(5).text();
        $('#SRC_sv_txt_designation').val(sv_desgn);
        $('#SRC_sv_txt_name').val(sv_name);
        $('#SRC_sv_txt_start').val(sv_start);
        $('#SRC_sv_txt_end').val(sv_end);
        $('#SRC_sv_txt_remark').val(sv_remarks);
    });
// CLICK EVENT FORM SITEVISIT UPDATE ROW
    $(document).on("click",'.SRC_sv_btn_updaterow', function (){
        var sv_desgn=$('#SRC_sv_txt_designation').val();
        var sv_name=$('#SRC_sv_txt_name').val();
        var sv_start=$('#SRC_sv_txt_start').val();
        var sv_end=$('#SRC_sv_txt_end').val();
        var sv_remarks=$('#SRC_sv_txt_remark').val();
        var sv_rowid=$('#SRC_sv_rowid').val();
        if((sv_desgn!='') || (sv_name!='') || (sv_start!='') || (sv_end!='')||(sv_remarks!=''))
        {
            var objUser = {"sv_id":sv_rowid,"sv_desgn":sv_desgn,"sv_name":sv_name,"sv_start":sv_start,"sv_end":sv_end,"sv_remark":sv_remarks};
            var objKeys = ["","sv_desgn","sv_name", "sv_start", "sv_end","sv_remark"];
            $('#SRC_sv_tr_' + objUser.sv_id + ' td').each(function(i) {
                $(this).text(objUser[objKeys[i]]);
            });
            $('#SRC_sv_btn_addrow').show();
            $('#SRC_sv_btn_update').hide();
            sv_formclear();
        }
        else
        {
            show_msgbox("REPORT SUBMISSION UPDATE",error_message[11],"error",false);
            $('#SRC_sv_btn_addrow').hide();
            $('#SRC_sv_btn_update').show();
        }
//        $('#SRC_sv_btn_update,#SRC_sv_btn_addrow').attr("disabled", "disabled");
    });
// FORM VALIDATION FOR BUTTONS
    $(document).on("change blur",'.form-validation', function (){
        var sv_design=$('#SRC_sv_txt_designation').val();
        var sv_name=$('#SRC_sv_txt_name').val();
        var sv_start=$('#SRC_sv_txt_start').val();
        var sv_end=$('#SRC_sv_txt_end').val();
        var sv_remarks=$('#SRC_sv_txt_remark').val();
//        if(sv_design!='' && sv_name!='' && sv_start!='' && sv_end!='')
//        {
//            $("#SRC_sv_btn_addrow,#SRC_sv_btn_update").removeAttr("disabled");
//        }
//        else
//        {
//            $("#SRC_sv_btn_addrow,#SRC_sv_btn_update").attr("disabled", "disabled");
//        }
    });
////SITE VISIT ADD,DELETE AND UPDATE FUNCTION
//MACHINERY/EQUIPMENT TRANSFER ADD,DELETE,UPDATE ROW FUNCTION
    $('#SRC_mtransfer_update').hide();
//CLICK EVENT FOR MACHINERY ADD BUTTON
    $('#SRC_mtransfer_addrow').click(function(){
        var mtranser_from=$('#SRC_mtranser_from').val();
        var mtransfer_item=$('#SRC_mtransfer_item').val();
        var mtransfer_to=$('#SRC_mtransfer_to').val();
        var mtransfer_remark=$('#SRC_mtransfer_remark').val();
        if((mtransfer_item!='SELECT'))
        {
            var mtransfertablerowcount=$('#SRC_mtransfer_table tr').length;
            var mtrans_trrowid=mtransfertablerowcount;
            if(mtransfertablerowcount>1){
                var mtrans_lastid=$('#SRC_mtransfer_table tr:last').attr('id');
                var splittrid=mtrans_lastid.split('tr_');
                mtrans_trrowid=parseInt(splittrid[1])+1;
            }
            var mtransfereditid='SRC_mtransfereditrow/'+mtrans_trrowid;
            var mtransferdeleterowid='SRC_mtransferdeleterow/'+mtrans_trrowid;
            var mtransfer_row_id="SRC_mtranser_tr_"+mtrans_trrowid;
            var temp_textbox_id="SRC_mtransfertemp_id"+mtrans_trrowid;
            var appendrow='<tr class="active" id='+mtransfer_row_id+'><td><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRC_mtransfereditbutton" id='+mtransfereditid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRC_mtransferremovebutton"  id='+mtransferdeleterowid+'></div><input type="hidden" class="form-control"  style="max-width: 100px" id='+temp_textbox_id+'></td><td>'+mtranser_from+'</td><td>'+mtransfer_item+'</td><td>'+mtransfer_to+'</td><td>'+mtransfer_remark+'</td></tr>';
            $('#SRC_mtransfer_table tr:last').after(appendrow);
            mtransferformclear();
//            $('#SRC_mtransfer_addrow').attr('disabled','disabled');
            $('#SRC_mtransfer_update').hide();
        }
        else if((mtransfer_item=='SELECT') && ((mtranser_from!="") || (mtransfer_to!='')||(mtransfer_remark!='')))
        {
            var msg=error_message[12].toString().replace('[NAME]','ITEM');
            show_msgbox("REPORT SUBMISSION UPDATE",msg,"error",false);
        }
        else
        {
            show_msgbox("REPORT SUBMISSION UPDATE",error_message[11],"error",false);
        }
    });
// FUNCTION FOR MACHINERY FORM CLEAR
    function mtransferformclear(){
        $('#SRC_mtranser_from').val('');
        $('#SRC_mtransfer_item').val('SELECT').show();
        $('#SRC_mtransfer_to').val('');
        $('#SRC_mtransfer_remark').val('').height('22');
    }
// CLICK EVENT FOR MACHINERY REMOVE BUTTON
    $(document).on("click",'.SRC_mtransferremovebutton', function (){
        $(this).closest('tr').remove();
        mtransferformclear()
        $('#SRC_mtransfer_update').hide();
        $('#SRC_mtransfer_addrow').show();
        return false;
    });
//CLICK EVENT FOR MACHINERY EDIT BUTTON
    $(document).on("click",'.SRC_mtransfereditbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#SRC_mtransfer_rowid').val(rowid);
        $('#SRC_mtransfer_addrow').hide();
        $('#SRC_mtransfer_update').show();
        var $tds =$(this).closest('tr').children('td'),
            mtranser_from = $tds.eq(1).text(),
            mtransfer_item = $tds.eq(2).text(),
            mtransfer_to = $tds.eq(3).text(),
            mtransfer_remark = $tds.eq(4).text();
        $('#SRC_mtranser_from').val(mtranser_from);
        $('#SRC_mtransfer_item').val(mtransfer_item);
        $('#SRC_mtransfer_to').val(mtransfer_to);
        $('#SRC_mtransfer_remark').val(mtransfer_remark);
    });
// CLICK EVENT FORM MACHINER UPDATE ROW
    $(document).on("click",'.SRC_mtransfer_updaterow', function (){
        var mtranser_from=$('#SRC_mtranser_from').val();
        var mtransfer_item=$('#SRC_mtransfer_item').val();
        var mtransfer_to=$('#SRC_mtransfer_to').val();
        var mtransfer_remark=$('#SRC_mtransfer_remark').val();
        var mtransfer_rowid=$('#SRC_mtransfer_rowid').val();
        if((mtransfer_item!='SELECT'))
        {
            var objUser = {"mtransferid":mtransfer_rowid,"mtranserfrom":mtranser_from,"mtransferitem":mtransfer_item,"mtransferto":mtransfer_to,"mtransferremark":mtransfer_remark};
            var objKeys = ["","mtranserfrom", "mtransferitem", "mtransferto","mtransferremark"];
            $('#SRC_mtranser_tr_' + objUser.mtransferid + ' td').each(function(i) {
                $(this).text(objUser[objKeys[i]]);
            });
            $('#SRC_mtransfer_addrow').show();
            $('#SRC_mtransfer_update').hide();
            mtransferformclear();
        }
        else if((mtransfer_item=='SELECT') && ((mtranser_from!="") || (mtransfer_to!='')||(mtransfer_remark!='')))
        {
            var msg=error_message[12].toString().replace('[NAME]','ITEM');
            show_msgbox("REPORT SUBMISSION UPDATE",msg,"error",false);
            $('#SRC_mtransfer_addrow').hide();
            $('#SRC_mtransfer_update').show();
        }
        else{
            show_msgbox("REPORT SUBMISSION UPDATE",error_message[11],"error",false);
            $('#SRC_mtransfer_addrow').hide();
            $('#SRC_mtransfer_update').show();
        }
//        $('#SRC_mtransfer_update,#SRC_mtransfer_addrow').attr("disabled", "disabled");
    });

// FORM VALIDATION FOR BUTTONS
    $(document).on("change blur",'.SRC_form-validation', function (){
        var mtranser_from=$('#SRC_mtranser_from').val();
        var mtransfer_item=$('#SRC_mtransfer_item').val();
        var mtransfer_to=$('#SRC_mtransfer_to').val();
//        if(mtranser_from!="" && mtransfer_item!="" && mtransfer_to!="")
//        {
//            $("#SRC_mtransfer_addrow,#SRC_mtransfer_update").removeAttr("disabled");
//
//        }
//        else
//        {
//            $("#SRC_mtransfer_addrow,#SRC_mtransfer_update").attr("disabled", "disabled");
//        }
    });
//END OF MACHINERY/EQUIPMENT TRANSFER ADD,DELETE,UPDATE ROW FUNCTION
//RENTAL MACHINERY ADD,DELETE,UPDATE FUNCTION//
    $('#SRC_rentalmechinery_updaterow').hide();
    $('#SRC_rentalmechinery_addrow').click(function(){
        var rental_lorryno=$('#SRC_rental_lorryno').val();
        var rental_throwearthstore=$('#SRC_rental_throwearthstore').val();
        var rental_throwearthoutside=$('#SRC_rental_throwearthoutside').val();
        var rental_start=$('#SRC_rental_start').val();
        var rental_end=$('#SRC_rental_end').val();
        var rental_remarks=$('#SRC_rental_remarks').val();
        if((rental_lorryno!="") || (rental_throwearthstore!='') || (rental_throwearthoutside!='') || (rental_start!='') || (rental_end!='')||(rental_remarks!=''))
        {
            var rentaltablerowcount=$('#SRC_rental_table tr').length;
            var rental_trrowid=rentaltablerowcount;
            if(rentaltablerowcount>1){
                var rental_lastid=$('#SRC_rental_table tr:last').attr('id');
                var splittrid=rental_lastid.split('tr_');
                rental_trrowid=parseInt(splittrid[1])+1;
            }
            var rentaleditid='SRC_machineryeditrow/'+rental_trrowid;
            var rentaldeleterowid='SRC_machinerydeleterow/'+rental_trrowid;
            var rental_row_id="SRC_rental_tr_"+rental_trrowid;
            var temp_textbox_id="SRC_rentaltemp_id"+rental_trrowid;
            var appendrow='<tr class="active" id='+rental_row_id+'><td><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRC_rentalmechinery_editbutton" id='+rentaleditid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRC_rental_machineryremovebutton"  id='+rentaldeleterowid+'></div><input type="hidden" class="form-control"  style="max-width: 100px" id='+temp_textbox_id+'></td><td>'+rental_lorryno+'</td><td>'+rental_throwearthstore+'</td><td>'+rental_throwearthoutside+'</td><td>'+rental_start+'</td><td>'+rental_end+'</td><td>'+rental_remarks+'</td>';
            $('#SRC_rental_table tr:last').after(appendrow);
//            $('#SRC_rentalmechinery_addrow').attr("disabled", "disabled");
            $('#SRC_rentalmechinery_updaterow').hide();
            Rentalmachineryclear()
        }
        else
        {
            show_msgbox("REPORT SUBMISSION UPDATE",error_message[11],"error",false);
        }
    });
// CLICK EVENT FOR RENTAL MACHINERY REMOVE BUTTON
    $(document).on("click",'.SRC_rental_machineryremovebutton', function (){
        $(this).closest('tr').remove();
        Rentalmachineryclear()
        $('#SRC_rentalmechinery_addrow').show();
        $('#SRC_rentalmechinery_updaterow').hide();
        return false;
    });
//CLICK EVENT FOR RENTAL MACHINERY EDIT BUTTON
    $(document).on("click",'.SRC_rentalmechinery_editbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#SRC_rentalmechinery_id').val(rowid);
        $('#SRC_rentalmechinery_addrow').hide();
        $('#SRC_rentalmechinery_updaterow').show();
        var $tds = $(this).closest('tr').children('td'),
            lorry_no = $tds.eq(1).text(),
            store = $tds.eq(2).text(),
            outside = $tds.eq(3).text(),
            start = $tds.eq(4).text(),
            end = $tds.eq(5).text(),
            remarks = $tds.eq(6).text();
        $('#SRC_rental_lorryno').val(lorry_no);
        $('#SRC_rental_throwearthstore').val(store);
        $('#SRC_rental_throwearthoutside').val(outside);
        $('#SRC_rental_start').val(start);
        $('#SRC_rental_end').val(end);
        $('#SRC_rental_remarks').val(remarks);
    });
    // CLICK EVENT FORM RENTAL MACHINERY UPDATE ROW
    $(document).on("click",'.SRC_rentalmechineryupdaterow', function (){
        var rental_lorryno=$('#SRC_rental_lorryno').val();
        var rental_throwearthstore=$('#SRC_rental_throwearthstore').val();
        var rental_throwearthoutside=$('#SRC_rental_throwearthoutside').val();
        var rental_start=$('#SRC_rental_start').val();
        var rental_end=$('#SRC_rental_end').val();
        var rental_remarks=$('#SRC_rental_remarks').val();
        var rental_rowid=$('#SRC_rentalmechinery_id').val();
        if((rental_lorryno!="") || (rental_throwearthstore!='') || (rental_throwearthoutside!='') || (rental_start!='') || (rental_end!='')||(rental_remarks!=''))
        {
            var objUser = {"rentalrowid":rental_rowid,"lorryno":rental_lorryno,"throwstore":rental_throwearthstore,"throwoutside":rental_throwearthoutside,"start":rental_start,"end":rental_end,"remarks":rental_remarks};
            var objKeys = ["","lorryno", "throwstore", "throwoutside","start","end","remarks"];
            $('#SRC_rental_tr_' + objUser.rentalrowid + ' td').each(function(i) {
                $(this).text(objUser[objKeys[i]]);
            });
            $('#SRC_rentalmechinery_addrow').show();
            $('#SRC_rentalmechinery_updaterow').hide();
            Rentalmachineryclear();
        }
        else
        {
            show_msgbox("REPORT SUBMISSION UPDATE",error_message[11],"error",false);
            $('#SRC_rentalmechinery_addrow').hide();
            $('#SRC_rentalmechinery_updaterow').show();
        }
//        $('#SRC_rentalmechinery_addrow,#SRC_rentalmechinery_updaterow').attr("disabled", "disabled");
    });
    function Rentalmachineryclear()
    {
        $('#SRC_rental_lorryno').val('');
        $('#SRC_rental_throwearthstore').val('');
        $('#SRC_rental_throwearthoutside').val('');
        $('#SRC_rental_start').val('');
        $('#SRC_rental_end').val('');
        $('#SRC_rental_remarks').val('').height('22');
    }
    // FORM VALIDATION FOR BUTTONS
    $(document).on("change blur",'.SRC_rentalform-validation', function (){

        var rental_lorryno=$('#SRC_rental_lorryno').val();
        var rental_throwearthstore=$('#SRC_rental_throwearthstore').val();
        var rental_throwearthoutside=$('#SRC_rental_throwearthoutside').val();
        var rental_start=$('#SRC_rental_start').val();
        var rental_end=$('#SRC_rental_end').val();
//        if(rental_lorryno!="" && rental_throwearthstore!="" && rental_throwearthoutside!="" && rental_start!='' && rental_end!='')
//        {
//            $("#SRC_rentalmechinery_addrow,#SRC_rentalmechinery_updaterow").removeAttr("disabled");
//
//        }
//        else
//        {
//            $('#SRC_rentalmechinery_addrow,#SRC_rentalmechinery_updaterow').attr("disabled", "disabled");
//        }
    });
//RENTAL MACHINERY USAGE ADD,DELETE AND UPDATE FUNCTION
//CLICK EVENT FOR MACHINERY ADD BUTTON
    $('#SRC_machinery_update').hide();
    $('#SRC_machinery_addrow').click(function(){
        var machinerytype=$('#SRC_machinery_type').val();
        var machinery_start=$('#SRC_machinery_start').val();
        var machinery_end=$('#SRC_machinery_end').val();
        var machinery_remarks=$('#SRC_machinery_remarks').val();
        if((machinerytype!="SELECT"))
        {
            var machinerytablerowcount=$('#SRC_machinery_table tr').length;
            var machinery_trrowid=machinerytablerowcount;
            if(machinerytablerowcount>1){
                var machinery_lastid=$('#SRC_machinery_table tr:last').attr('id');
                var splittrid=machinery_lastid.split('tr_');
                machinery_trrowid=parseInt(splittrid[1])+1;
            }
            var machineryeditid='SRC_machineryeditrow/'+machinery_trrowid;
            var machinerydeleterowid='SRC_machinerydeleterow/'+machinery_trrowid;
            var machinery_row_id="SRC_machinery_tr_"+machinery_trrowid;
            var temp_textbox_id="SRC_machinerytemp_id"+machinery_trrowid;
            var appendrow='<tr class="active" id='+machinery_row_id+'><td><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRC_machineryeditbutton" id='+machineryeditid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRC_machineryremovebutton"  id='+machinerydeleterowid+'><div><input type="hidden" class="form-control"  style="max-width: 100px" id='+temp_textbox_id+'></td><td>'+machinerytype+'</td><td>'+machinery_start+'</td><td>'+machinery_end+'</td><td>'+machinery_remarks+'</td></tr>';
            $('#SRC_machinery_table tr:last').after(appendrow);
            machineryformclear();
//            $('#SRC_machinery_addrow').attr('disabled','disabled');
            $('#SRC_machinery_update').hide();
        }
        else if((machinerytype=='SELECT') && ((machinery_start!='') || (machinery_end!='') || (machinery_remarks!='')))
        {
            var msg=error_message[12].toString().replace('[NAME]','MACHINERY TYPE');
            show_msgbox("REPORT SUBMISSION UPDATE",msg,"error",false);
        }
        else
        {
            show_msgbox("REPORT SUBMISSION UPDATE",error_message[11],"error",false);
        }
    });

    // FUNCTION FOR MACHINERY FORM CLEAR
    function machineryformclear(){
        $('#SRC_machinery_type').val('SELECT').show();
        $('#SRC_machinery_start').val('');
        $('#SRC_machinery_end').val('');
        $('#SRC_machinery_remarks').val('').height('22');
    }
    // CLICK EVENT FOR MACHINERY REMOVE BUTTON
    $(document).on("click",'.SRC_machineryremovebutton', function (){
        $(this).closest('tr').remove();
        machineryformclear()
        $('#SRC_machinery_addrow').show();
        $('#SRC_machinery_update').hide();
        return false;
    });
    //CLICK EVENT FOR MACHINERY EDIT BUTTON
    $(document).on("click",'.SRC_machineryeditbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#SRC_machinery_rowid').val(rowid);
        $('#SRC_machinery_addrow').hide();
        $('#SRC_machinery_update').show();
        var $tds = $(this).closest('tr').children('td'),
            machinery_type = $tds.eq(1).text(),
            machinery_start = $tds.eq(2).text(),
            machinery_end = $tds.eq(3).text(),
            machinery_remarks = $tds.eq(4).text();
        $('#SRC_machinery_type').val(machinery_type);
        $('#SRC_machinery_start').val(machinery_start);
        $('#SRC_machinery_end').val(machinery_end);
        $('#SRC_machinery_remarks').val(machinery_remarks);
    });
    // CLICK EVENT FORM MACHINER UPDATE ROW
    $(document).on("click",'.SRC_machinery_updaterow', function (){
        var machinery_type=$('#SRC_machinery_type').val();
        var machinery_start=$('#SRC_machinery_start').val();
        var machinery_end=$('#SRC_machinery_end').val();
        var machinery_remarks=$('#SRC_machinery_remarks').val();
        var machinery_rowid=$('#SRC_machinery_rowid').val();
        if((machinery_type!="SELECT"))
        {
            var objUser = {"machineryid":machinery_rowid,"machinerytype":machinery_type,"machinerystart":machinery_start,"machineryend":machinery_end,"machineryremark":machinery_remarks};
            var objKeys = ["","machinerytype", "machinerystart", "machineryend","machineryremark"];
            $('#SRC_machinery_tr_' + objUser.machineryid + ' td').each(function(i) {
                $(this).text(objUser[objKeys[i]]);
            });
            $('#SRC_machinery_addrow').show();
            $('#SRC_machinery_update').hide();
            machineryformclear();
        }
        else if((machinery_type=='SELECT')&&((machinery_start!='') || (machinery_end!='') || (machinery_remarks!='')))
        {
            var msg=error_message[12].toString().replace('[NAME]','MACHINERY TYPE');
            show_msgbox("REPORT SUBMISSION UPDATE",msg,"error",false);
            $('#SRC_machinery_addrow').hide();
            $('#SRC_machinery_update').show();
        }
        else
        {
            show_msgbox("REPORT SUBMISSION UPDATE",error_message[11],"error",false);
            $('#SRC_machinery_addrow').hide();
            $('#SRC_machinery_update').show();
        }
//        $('#SRC_machinery_update,#SRC_machinery_addrow').attr("disabled", "disabled");
    });

    // FORM VALIDATION FOR BUTTONS
    $(document).on("change blur",'.SRC_machineryform-validation', function (){
        var machinery_type=$('#SRC_machinery_type').val();
        var machinery_start=$('#SRC_machinery_start').val();
        var machinery_end=$('#SRC_machinery_end').val();
//        if(machinery_type!="SELECT" && machinery_start!="" && machinery_end!="")
//        {
//            $('#SRC_machinery_update,#SRC_machinery_addrow').removeAttr("disabled");
//        }
//        else
//        {
//            $('#SRC_machinery_update,#SRC_machinery_addrow').attr("disabled", "disabled");
//        }
    });
//END OF MACHINERY USAGE ADD,DELETE AND UPDATE FUNCTION
//EQUIPMENT USAGE ADD,DELETE AND UPDATE FUNCTION
    $('#SRC_equipment_update').hide();
//CLICK EVENT FOR MACHINERY ADD BUTTON
    $('#SRC_equipment_addrow').click(function(){
        var equipment_aircompressor=$('#SRC_equipment_aircompressor').val();
        var equipment_lorryno=$('#SRC_equipment_lorryno').val();
        var equipment_start=$('#SRC_equipment_start').val();
        var equipment_end=$('#SRC_equipment_end').val();
        var equipment_remark=$('#SRC_equipment_remark').val();
        if((equipment_aircompressor!="") || (equipment_lorryno!='') || (equipment_start!='') || (equipment_end!='')||(equipment_remark!=''))
        {
            var equipmenttablerowcount=$('#SRC_equipment_table tr').length;
            var equipment_trrowid=equipmenttablerowcount;
            if(equipmenttablerowcount>1){
                var equipment_lastid=$('#SRC_equipment_table tr:last').attr('id');
                var splittrid=equipment_lastid.split('tr_');
                equipment_trrowid=parseInt(splittrid[1])+1;
            }
            var equipmenteditid='SRC_equipmenteditrow/'+equipment_trrowid;
            var equipmentdeleterowid='SRC_equipementdeleterow/'+equipment_trrowid;
            var equipment_row_id="SRC_equipment_tr_"+equipment_trrowid;
            var temp_textbox_id="SRC_equipmenttemp_id"+equipment_trrowid;
            var appendrow='<tr class="active" id='+equipment_row_id+'><td><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRC_equipmenteditbutton" id='+equipmenteditid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRC_equipmentremovebutton" id='+equipmentdeleterowid+'></div><input type="hidden" class="form-control"  style="max-width: 100px" id='+temp_textbox_id+'></td><td>'+equipment_aircompressor+'</td><td>'+equipment_lorryno+'</td><td>'+equipment_start+'</td><td>'+equipment_end+'</td><td>'+equipment_remark+'</td></tr>';
            $('#SRC_equipment_table tr:last').after(appendrow);
            equipmentformclear()
//            $('#SRC_equipment_addrow').attr('disabled','disabled');
            $('#SRC_equipment_update').hide();
        }
        else
        {
            show_msgbox("REPORT SUBMISSION UPDATE",error_message[11],"error",false)
        }
    });

// FUNCTION FOR MACHINERY FORM CLEAR
    function equipmentformclear(){
        $('#SRC_equipment_aircompressor').val('');
        $('#SRC_equipment_lorryno').val('');
        $('#SRC_equipment_start').val('');
        $('#SRC_equipment_end').val('');
        $('#SRC_equipment_remark').val('').height('22');
    }

// CLICK EVENT FOR MACHINERY REMOVE BUTTON
    $(document).on("click",'.SRC_equipmentremovebutton', function (){
        $(this).closest('tr').remove();
        equipmentformclear()
        $('#SRC_equipment_addrow').show();
        $('#SRC_equipment_update').hide();
        return false;
    });
//CLICK EVENT FOR MACHINERY EDIT BUTTON
    $(document).on("click",'.SRC_equipmenteditbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#SRC_equipment_rowid').val(rowid);
        $('#SRC_equipment_addrow').hide();
        $('#SRC_equipment_update').show();
        var $tds = $(this).closest('tr').children('td'),
            equipment_aircompressor = $tds.eq(1).text(),
            equipment_lorryno = $tds.eq(2).text(),
            equipment_start = $tds.eq(3).text(),
            equipment_end = $tds.eq(4).text(),
            equipment_remark = $tds.eq(5).text();
        $('#SRC_equipment_aircompressor').val(equipment_aircompressor);
        $('#SRC_equipment_lorryno').val(equipment_lorryno);
        $('#SRC_equipment_start').val(equipment_start);
        $('#SRC_equipment_end').val(equipment_end);
        $('#SRC_equipment_remark').val(equipment_remark);
    });
// CLICK EVENT FORM MACHINER UPDATE ROW
    $(document).on("click",'.SRC_equipment_updaterow', function (){
        var equipment_aircompressor=$('#SRC_equipment_aircompressor').val();
        var equipment_lorryno=$('#SRC_equipment_lorryno').val();
        var equipment_start=$('#SRC_equipment_start').val();
        var equipment_end=$('#SRC_equipment_end').val();
        var equipment_remark=$('#SRC_equipment_remark').val();
        var equipment_rowid=$('#SRC_equipment_rowid').val();
        if((equipment_aircompressor!="") || (equipment_lorryno!='') || (equipment_start!='') || (equipment_end!='')||(equipment_remark!=''))
        {
            var objUser = {"equipmentrowid":equipment_rowid,"equipmentaircompressor":equipment_aircompressor,"equipmentlorryno":equipment_lorryno,"equipmentstart":equipment_start,"equipmentend":equipment_end,"equipmentremark":equipment_remark};
            var objKeys = ["","equipmentaircompressor", "equipmentlorryno", "equipmentstart","equipmentend","equipmentremark"];
            $('#SRC_equipment_tr_' + objUser.equipmentrowid + ' td').each(function(i) {
                $(this).text(objUser[objKeys[i]]);
            });
            $('#SRC_equipment_addrow').show();
            $('#SRC_equipment_update').hide();
            equipmentformclear();
        }
        else
        {
            show_msgbox("REPORT SUBMISSION UPDATE",error_message[11],"error",false);
            $('#SRC_equipment_addrow').hide();
            $('#SRC_equipment_update').show();
        }
//        $('#SRC_equipment_update,#SRC_equipment_addrow').attr("disabled", "disabled");
    });

// FORM VALIDATION FOR BUTTONS
    $(document).on("change blur",'.SRC_equipmentform-validation', function (){
        var equipment_aircompressor=$('#SRC_equipment_aircompressor').val();
        var equipment_lorryno=$('#SRC_equipment_lorryno').val();
        var equipment_start=$('#SRC_equipment_start').val();
        var equipment_end=$('#SRC_equipment_end').val();
//        if(equipment_aircompressor!="" && equipment_lorryno!="" && equipment_start!="" && equipment_end!='')
//        {
//            $('#SRC_equipment_update,#SRC_equipment_addrow').removeAttr("disabled");
//        }
//        else
//        {
//            $('#SRC_equipment_update,#SRC_equipment_addrow').attr("disabled", "disabled");
//        }
    });
//END OF EQUIPMENT USAGE ADD,DELETE AND UPDATE FUNCTION
//FITTING  USAGE TABLE ADD FUNCTION//
    //*****ADD NEW ROW********//
    $('#SRC_fitting_updaterow').hide();
    $(document).on("click",'#SRC_fitting_addrow', function (){
        var items=$('#SRC_fitting_items').val();
        var size=$('#SRC_fitting_size').val();
        var qty=$('#SRC_fitting_quantity').val();
        var remarks=$('#SRC_fitting_remarks').val();
        if((items!="SELECT"))
        {
            var tablerowCount=$('#SRC_fitting_table tr').length;
            var fitting_trrowid=tablerowCount;
            if(tablerowCount>1){
                var fitting_lastid=$('#SRC_fitting_table tr:last').attr('id');
                var splittrid=fitting_lastid.split('tr_');
                fitting_trrowid=parseInt(splittrid[1])+1;
            }
            var editid='SRC_fitting_editrow/'+fitting_trrowid;
            var deleterowid='SRC_fitting_deleterow/'+fitting_trrowid;
            var row_id="SRC_fitting_tr_"+fitting_trrowid;
            var temp_textbox_id="SRC_fittingtemp_id"+fitting_trrowid;
            var appendrow='<tr  class="active" id='+row_id+'><td><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRC_fitting_editbutton" id='+editid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRC_fitting_removebutton"  id='+deleterowid+'></div><input type="hidden" class="form-control"  style="max-width: 100px" id='+temp_textbox_id+'></td><td>'+items+'</td><td>'+size+'</td><td>'+qty+'</td><td>'+remarks+'</td></tr>';
            $('#SRC_fitting_table tr:last').after(appendrow);
//            $("#SRC_fitting_addrow").attr("disabled", "disabled");
            $('#SRC_fitting_updaterow').hide();
            fittingformclear();
        }
        else if((items=="SELECT") && ((size!='') || (qty!='') || (remarks!='')))
        {
            var msg=error_message[12].toString().replace('[NAME]','FITTINGS ITEM');
            show_msgbox("REPORT SUBMISSION UPDATE",msg,"error",false);
        }
        else
        {
            show_msgbox("REPORT SUBMISSION UPDATE",error_message[11],"error",false);
        }
    });
    //**********DELETE ROW*************//
    $(document).on("click",'.SRC_fitting_removebutton', function (){
        $('#SRC_fitting_updaterow').hide();
        $(this).closest('tr').remove();
        $("#SRC_fitting_addrow").show();
        fittingformclear();
        return false;
    });
    //**********EDIT ROW**************//
    $(document).on("click",'.SRC_fitting_editbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#SRC_fitting_id').val(rowid);
        $('#SRC_fitting_addrow').hide();
        $('#SRC_fitting_updaterow').show();
        var $tds = $(this).closest('tr').children('td'),
            items = $tds.eq(1).text(),
            size = $tds.eq(2).text(),
            quantity = $tds.eq(3).text(),
            remark = $tds.eq(4).text();
        $('#SRC_fitting_items').val(items);
        $('#SRC_fitting_size').val(size);
        $('#SRC_fitting_quantity').val(quantity);
        $('#SRC_fitting_remarks').val(remark);
    });
    //********UPDATE ROW****************//
    $(document).on("click",'.SRC_fittingupdaterow', function (){
        var items=$('#SRC_fitting_items').val();
        var size=$('#SRC_fitting_size').val();
        var qty=$('#SRC_fitting_quantity').val();
        var remarks=$('#SRC_fitting_remarks').val();
        var rowid=$('#SRC_fitting_id').val();
        if((items!="SELECT"))
        {
            var objUser = {"id":rowid,"items":items,"size":size,"quantity":qty,"remark":remarks};
            var objKeys = ["","items", "size", "quantity","remark"];
            $('#SRC_fitting_tr_' + objUser.id + ' td').each(function(i) {
                $(this).text(objUser[objKeys[i]]);
            });
            $('#SRC_fitting_addrow').show();
            $('#SRC_fitting_updaterow').hide();
            fittingformclear();
        }
        else if((items=="SELECT") && ((size!='') || (qty!='') || (remarks!='')))
        {
            var msg=error_message[12].toString().replace('[NAME]','FITTINGS ITEM');
            show_msgbox("REPORT SUBMISSION UPDATE",msg,"error",false);
            $('#SRC_fitting_addrow').hide();
            $('#SRC_fitting_updaterow').show();
        }
        else
        {
            show_msgbox("REPORT SUBMISSION UPDATE",error_message[11],"error",false);
            $('#SRC_fitting_addrow').hide();
            $('#SRC_fitting_updaterow').show();
        }
//        $('#SRC_fitting_addrow,#SRC_fitting_updaterow').attr("disabled", "disabled");
    });
    //*****FITTING FORM CLEAR**********//
    function fittingformclear()
    {
        $('#SRC_fitting_items').val('SELECT').show();
        $('#SRC_fitting_size').val('');
        $('#SRC_fitting_quantity').val('');
        $('#SRC_fitting_remarks').val('').height('22');
    }

    $(document).on("change blur",'.SRC_fittingform-validation', function (){
        var items=$('#SRC_fitting_items').val();
        var size=$('#SRC_fitting_size').val();
        var qty=$('#SRC_fitting_quantity').val();
//        if(items!="SELECT" && size!="" && qty!="")
//        {
//            $('#SRC_fitting_addrow,#SRC_fitting_updaterow').removeAttr("disabled");
//        }
//        else
//        {
//            $('#SRC_fitting_addrow,#SRC_fitting_updaterow').attr("disabled", "disabled");
//        }
    });
//END OF FITTING  USAGE TABLE ADD FUNCTION//
//MATERIAL USAGE ADD,DELETE AND UPDATE ROW FUNCTION//
    $('#SRC_material_updaterow').hide();
    $(document).on("click",'#SRC_material_addrow', function (){
        var items=$('#SRC_material_items').val();
        var receipt=$('#SRC_material_receipt').val();
        var qty=$('#SRC_material_quantity').val();
        if((items!="SELECT"))
        {
            var tablerowCount=$('#SRC_material_table tr').length;
            var mat_trrowid=tablerowCount;
            if(tablerowCount>1){
                var mat_lastid=$('#SRC_material_table tr:last').attr('id');
                var splittrid=mat_lastid.split('tr_');
                mat_trrowid=parseInt(splittrid[1])+1;
            }
            var editid='SRC_material_editrow/'+mat_trrowid;
            var deleterowid='SRC_material_deleterow/'+mat_trrowid;
            var row_id="SRC_material_tr_"+mat_trrowid;
            var temp_textbox_id="SRC_materialtemp_id"+mat_trrowid;
            var appendrow='<tr class="active" id='+row_id+'><td><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRC_material_editbutton" id='+editid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRC_material_removebutton"  id='+deleterowid+'></div><input type="hidden" class="form-control"  style="max-width: 100px" id='+temp_textbox_id+'></td><td>'+items+'</td><td>'+receipt+'</td><td>'+qty+'</td></tr>';
            $('#SRC_material_table tr:last').after(appendrow);
//            $("#SRC_material_addrow").attr("disabled","disabled");
            $('#SRC_material_updaterow').hide();
            MATERIALformclear();
        }
        else if((items=="SELECT") && ((receipt!='') || (qty!='')))
        {
            var msg=error_message[12].toString().replace('[NAME]','MATERIAL ITEM');
            show_msgbox("REPORT SUBMISSION UPDATE",msg,"error",false);
        }
        else
        {
            show_msgbox("REPORT SUBMISSION UPDATE",error_message[11],"error",false);
        }
    });
    //**********DELETE ROW*************//
    $(document).on("click",'.SRC_material_removebutton', function (){
        $('#SRC_material_updaterow').hide();
        $(this).closest('tr').remove();
        $("#SRC_material_addrow").show();
        MATERIALformclear();
        return false;
    });
    // **********EDIT ROW**************//
    $(document).on("click",'.SRC_material_editbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#SRC_material_id').val(rowid);
        $('#SRC_material_addrow').hide();
        $('#SRC_material_updaterow').show();
        var $tds = $(this).closest('tr').children('td'),
            items = $tds.eq(1).text(),
            receipt = $tds.eq(2).text(),
            quantity = $tds.eq(3).text();
        $('#SRC_material_items').val(items);
        $('#SRC_material_receipt').val(receipt);
        $('#SRC_material_quantity').val(quantity);
    });
    //********UPDATE ROW****************//
    $(document).on("click",'.SRC_materialupdaterow', function (){
        var material_items=$('#SRC_material_items').val();
        var material_receipt=$('#SRC_material_receipt').val();
        var material_quantity=$('#SRC_material_quantity').val();
        var material_id=$('#SRC_material_id').val();
        if((material_items!="SELECT"))
        {
            var objUser = {"materialid":material_id,"materialitems":material_items,"materialreceipt":material_receipt,"materialquantity":material_quantity};
            var objKeys = ["","materialitems", "materialreceipt", "materialquantity"];
            $('#SRC_material_tr_' + objUser.materialid + ' td').each(function(i) {
                $(this).text(objUser[objKeys[i]]);
            });
            $('#SRC_material_addrow').show();
            $('#SRC_material_updaterow').hide();
            MATERIALformclear();
        }
        else if((material_items=="SELECT") && ((material_receipt!='') || (material_quantity!='')))
        {
            var msg=error_message[12].toString().replace('[NAME]','MATERIAL ITEM');
            show_msgbox("REPORT SUBMISSION UPDATE",msg,"error",false);
            $('#SRC_material_addrow').hide();
            $('#SRC_material_updaterow').show();
        }
        else
        {
            show_msgbox("REPORT SUBMISSION UPDATE",error_message[11],"error",false);
            $('#SRC_material_addrow').hide();
            $('#SRC_material_updaterow').show();
        }
//        $('#SRC_material_addrow,#SRC_material_updaterow').attr("disabled", "disabled");
    });
    //*****MATERIAL FORM CLEAR**********//
    function MATERIALformclear()
    {
        $('#SRC_material_items').val('SELECT').show();
        $('#SRC_material_receipt').val('');
        $('#SRC_material_quantity').val('');
    }

    $(document).on("change blur",'.SRC_materialform-validation', function (){
        var items=$('#SRC_material_items').val();
        var receipt=$('#SRC_material_receipt').val();
        var qty=$('#SRC_material_quantity').val();
//        if(items!="SELECT" && receipt!="" && qty!="")
//        {
//            $('#SRC_material_addrow,#SRC_material_updaterow').removeAttr("disabled");
//        }
//        else
//        {
//            $('#SRC_material_addrow,#SRC_material_updaterow').attr("disabled", "disabled");
//        }
    });
//END OF MATERIAL USAGE ADD,DELETE AND UPDATE ROW FUNCTION//
//STOCK USAGE ADD,DELETE AND UPDATE ROW FUNCTION//
    $('#SRC_stock_updaterow').hide();
    $(document).on("click",'#SRC_stock_addrow', function (){
        var items=$('#SRC_stock_itemno').val();
        var itemname=$('#SRC_stock_itemname').val();
        var qty=$('#SRC_stock_quantity').val();
        if((items!="SELECT") && (itemname!='') && (qty!=''))
        {
            var tablerowCount=$('#SRC_stockusage_table tr').length;
            var stck_trrowid=tablerowCount;
            if(tablerowCount>1){
                var stck_lastid=$('#SRC_stockusage_table tr:last').attr('id');
                var splittrid=stck_lastid.split('tr_');
                stck_trrowid=parseInt(splittrid[1])+1;
            }
            var editid='SRC_stock_editrow/'+stck_trrowid;
            var deleterowid='SRC_stock_deleterow/'+stck_trrowid;
            var row_id="SRC_stock_tr_"+stck_trrowid;
            var temp_textbox_id="SRC_stocktemp_id"+stck_trrowid;
            var appendrow='<tr class="active" id='+row_id+'><td><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRC_stock_editbutton" id='+editid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRC_stock_removebutton"  id='+deleterowid+'></div><input type="hidden" class="form-control"  style="max-width: 100px" id='+temp_textbox_id+'></td><td>'+items+'</td><td>'+itemname+'</td><td>'+qty+'</td></tr>';
            $('#SRC_stockusage_table tr:last').after(appendrow);
//            $("#SRC_stock_addrow").attr("disabled","disabled");
            $('#SRC_stock_updaterow').hide();
            stockformclear();
        }
        else if((items=="SELECT") && ((itemname=='') || (qty!='')))
        {
            var msg=error_message[12].toString().replace('[NAME]','SITE STOCK ITEM');
            show_msgbox("REPORT SUBMISSION UPDATE",msg,"error",false);
        }
        else
        {
            var errmsg=error_message[11].toString().replace('ANY ONE', 'ALL');
            show_msgbox("REPORT SUBMISSION UPDATE",errmsg,"error",false);
        }
    });
    //**********DELETE ROW*************//
    $(document).on("click",'.SRC_stock_removebutton', function (){
        $('#SRC_stock_updaterow').hide();
        $(this).closest('tr').remove();
        $("#SRC_stock_addrow").show();
        stockformclear();
        return false;
    });
    // **********EDIT ROW**************//
    $(document).on("click",'.SRC_stock_editbutton', function (event){
        event.preventDefault();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#SRC_stock_id').val(rowid);
        $('#SRC_stock_addrow').hide();
        $('#SRC_stock_updaterow').show();
        var $tds = $(this).closest('tr').children('td'),
            itemno = $tds.eq(1).text(),
            itemname = $tds.eq(2).text(),
            quantity = $tds.eq(3).text();
        $('#SRC_stock_itemno').val(itemno);
        $('#SRC_stock_itemname').val(itemname);
        $('#SRC_stock_quantity').val(quantity);
    });
    //********UPDATE ROW****************//
    $(document).on("click",'.SRC_stockupdaterow', function (){
        var stock_itemno=$('#SRC_stock_itemno').val();
        var stock_itemname=$('#SRC_stock_itemname').val();
        var stock_quantity=$('#SRC_stock_quantity').val();
        var stock_id=$('#SRC_stock_id').val();
        if((stock_itemno!="SELECT") && (stock_itemname!='') && (stock_quantity!=''))
        {
            var objUser = {"stockid":stock_id,"stockitemno":stock_itemno,"stockitemname":stock_itemname,"stockquantity":stock_quantity};
            var objKeys = ["","stockitemno", "stockitemname", "stockquantity"];
            $('#SRC_stock_tr_' + objUser.stockid + ' td').each(function(i) {
                $(this).text(objUser[objKeys[i]]);
            });
            $('#SRC_stock_addrow').show();
            $('#SRC_stock_updaterow').hide();
            stockformclear();
        }
        else if((stock_itemno=="SELECT") && ((stock_itemname=='') || (stock_quantity!='')))
        {
            var msg=error_message[12].toString().replace('[NAME]','SITE STOCK ITEM');
            show_msgbox("REPORT SUBMISSION UPDATE",msg,"error",false);
            $('#SRC_stock_addrow').hide();
            $('#SRC_stock_updaterow').show();
        }
        else
        {
            var errmsg=error_message[11].toString().replace('ANY ONE', 'ALL');
            show_msgbox("REPORT SUBMISSION UPDATE",errmsg,"error",false);
            $('#SRC_stock_addrow').hide();
            $('#SRC_stock_updaterow').show();
        }
//        $('#SRC_stock_addrow,#SRC_stock_updaterow').attr("disabled", "disabled");
    });
    //*****STOCK FORM CLEAR**********//
    function stockformclear()
    {
        $('#SRC_stock_itemno').val('SELECT').show();
        $('#SRC_stock_itemname').val('');
        $('#SRC_stock_quantity').val('');
    }

    $(document).on("change blur",'.SRC_stockform-validation', function (){
        var itemno=$('#SRC_stock_itemno').val();
        var itemname=$('#SRC_stock_itemname').val();
        var qty=$('#SRC_stock_quantity').val();
//        if(itemno!="SELECT" && itemname!="" && qty!="")
//        {
//            $('#SRC_stock_addrow,#SRC_stock_updaterow').removeAttr("disabled");
//        }
//        else
//        {
//            $('#SRC_stock_addrow,#SRC_stock_updaterow').attr("disabled", "disabled");
//        }
    });
//END OF STOCK USAGE ADD,DELETE AND UPDATE ROW FUNCTION//
// form validation
    $(document).on('change blur','#SRC_entryform',function(){
//        var location=$('#SRC_tr_txt_location').val();
        var contractno=$('#SRC_tr_lb_contractno').val();
//        var teamname=$('#SRC_tr_tb_team').val();
        var reportdate=$('#SRC_tr_txt_date').val();
        var weather=$('#SRC_tr_txt_weather').val();
        var reachsite=$('#SRC_tr_txt_reachsite').val();
        var leavesite=$('#SRC_tr_txt_leavesite').val();
        var jobtype=$("input[name=jobtype]").is(":checked");
        var roadchk=$("input[id=SRC_jd_chk_road]").is(":checked");
        var concchk=$("input[id=SRC_jd_chk_contc]").is(":checked");
        var turfchk=$("input[id=SRC_jd_chk_truf]").is(":checked");
        var roadm=$('#SRC_jd_chk_roadm').val();
        var roadmm=$('#SRC_jd_chk_roadmm').val();
        var concm=$('#SRC_jd_chk_concm').val();
        var concmm=$('#SRC_jd_chk_concmm').val();
        var trufm=$('#SRC_jd_chk_trufm').val();
//        var trufmm=$('#SRC_jd_chk_trufmm').val();
//        var pipetesting=$('#SRC_jd_txt_testing').val();
//        var startpressure=$('#SRC_jd_txt_start').val();
//        var endpressure=$('#SRC_jd_txt_end').val();
//        // pipelaid validation
        if(roadchk==true){
            $('#SRC_jd_chk_roadm').removeAttr('disabled');
            $('#SRC_jd_chk_roadmm').removeAttr('disabled');
        }
        else{
            $("#SRC_jd_chk_roadm").attr("disabled", "disabled");
            $("#SRC_jd_chk_roadmm").attr("disabled", "disabled");
            $("#SRC_jd_chk_roadm").val('');
            $("#SRC_jd_chk_roadmm").val('');
        }
        if(concchk==true){
            $('#SRC_jd_chk_concm').removeAttr('disabled');
            $('#SRC_jd_chk_concmm').removeAttr('disabled');
        }
        else{
            $("#SRC_jd_chk_concm").attr("disabled", "disabled");
            $("#SRC_jd_chk_concmm").attr("disabled", "disabled");
            $("#SRC_jd_chk_concm").val('');
            $("#SRC_jd_chk_concmm").val('');
        }
        if(turfchk==true){
            $('#SRC_jd_chk_trufm').removeAttr('disabled');
            $('#SRC_jd_chk_trufmm').removeAttr('disabled');
        }
        else{
            $("#SRC_jd_chk_trufm").attr("disabled", "disabled");
            $("#SRC_jd_chk_trufmm").attr("disabled", "disabled");
            $("#SRC_jd_chk_trufm").val('');
            $("#SRC_jd_chk_trufmm").val('');
        }
        //weather time validation
        if(weather!=''){
            $('#SRC_tr_txt_wftime').removeAttr('disabled');
            $('#SRC_tr_txt_wttime').removeAttr('disabled');
        }
        else{
            $("#SRC_tr_txt_wftime").attr("disabled", "disabled");
            $("#SRC_tr_txt_wttime").attr("disabled", "disabled");
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
////                //EMPPLOYEE TABLE RECORDS
//                var employeerowcount=$('#SRC_Employee_table tr').length;
//                for(var j=0;j<employeerowcount-1;j++)
//                {
//                    var autoid=j+1;
//                    var emp_id=$('#SRC_Emp_id'+autoid).val();
//                    if(emp_id==employee_id)
//                    {
//                        var emp_name=$('#SRC_Emp_name'+autoid).val();
//                        var emp_start=$('#SRC_Emp_starttime'+autoid).val();
//                        var emp_end=$('#SRC_Emp_endtime'+autoid).val();
//                    }
//                }
//                if((emp_id!='') && (emp_name!='') && (emp_start!='') && (emp_end!='')){
//                    var chkflag=1;
//                }else{
//                    var chkflag=0;
//                }
//                if(chkflag==1){
//                    $('#SRC_Final_Update').removeAttr('disabled');
//                }else{
//                    $('#SRC_Final_Update').attr('disabled','disabled');
//                }
//            }
        if(reportdate!=''&&contractno!='SELECT')
        {
            $('#SRC_Final_Update').removeAttr('disabled');
        }
        else
        {
            $('#SRC_Final_Update').attr('disabled','disabled');
        }
    });
//CHECK TOPIC
    $(document).on("change",'#SRC_mt_lb_topic', function (){
        var meetingrefTab = document.getElementById("SRC_meeting_table");
        var mttopic=$('#SRC_mt_lb_topic').val();
        var errormsg=error_message[6].toString().replace("[TOPIC]",mttopic);
        for ( var i = 1; row = meetingrefTab.rows[i]; i++ )
        {
            row = meetingrefTab.rows[i];
            var meetinginnerarray=[];
            col = row.cells[1];
            meetinginnerarray.push(col.firstChild.nodeValue);
            if(mttopic==meetinginnerarray){
                show_msgbox("REPORT SUBMISSION UPDATE",errormsg,"error",false);
                $('#SRC_mt_lb_topic').val('SELECT').show();
            }
        }
    });
    //CHECK MACHINARY EQUIP ITEM
    $(document).on("change",'#SRC_mtransfer_item', function (){
        var mechineryequiprefTab = document.getElementById("SRC_mtransfer_table");
        var mechineryequiptype=$('#SRC_mtransfer_item').val();
        var errormsg=error_message[9].toString().replace("[ITEM]",mechineryequiptype);
        for ( var i = 1; row = mechineryequiprefTab.rows[i]; i++ )
        {
            row = mechineryequiprefTab.rows[i];
            var machineryequipinnerarray=[];
            col = row.cells[2];
            machineryequipinnerarray.push(col.firstChild.nodeValue);
            if(mechineryequiptype==machineryequipinnerarray){
                show_msgbox("REPORT SUBMISSION UPDATE",errormsg,"error",false);
                $('#SRC_mtransfer_item').val('SELECT').show();
            }
        }
    });
    //CHECK MACHINARY TYPE
    $(document).on("change",'#SRC_machinery_type', function (){
        var mechineryrefTab = document.getElementById("SRC_machinery_table");
        var mechinerytype=$('#SRC_machinery_type').val();
        var errormsg=error_message[8].toString().replace("[TYPE]",mechinerytype);
        for ( var i = 1; row = mechineryrefTab.rows[i]; i++ )
        {
            row = mechineryrefTab.rows[i];
            var machineryinnerarray=[];
            col = row.cells[1];
            machineryinnerarray.push(col.firstChild.nodeValue);
            if(mechinerytype==machineryinnerarray){
                show_msgbox("REPORT SUBMISSION UPDATE",errormsg,"error",false);
                $('#SRC_machinery_type').val('SELECT').show();
            }
        }
    });
    //CHECK fittng item
    $(document).on("change",'#SRC_fitting_items', function (){
        var fittingrefTab = document.getElementById('SRC_fitting_table');
        var fittngtype=$('#SRC_fitting_items').val();
        var errormsg=error_message[9].toString().replace("[ITEM]",fittngtype);
        for ( var i = 1; row = fittingrefTab.rows[i]; i++ )
        {
            row = fittingrefTab.rows[i];
            var fittnginnerarray=[];
            col = row.cells[1];
            fittnginnerarray.push(col.firstChild.nodeValue);
            if(fittngtype==fittnginnerarray){
                show_msgbox("REPORT SUBMISSION UPDATE",errormsg,"error",false);
                $('#SRC_fitting_items').val('SELECT').show();
            }
        }
    });
    //CHECK material item
    $(document).on("change",'#SRC_material_items', function (){
        var metrialrefTab = document.getElementById("SRC_material_table");
        var materialtype=$('#SRC_material_items').val();
        var errormsg=error_message[9].toString().replace("[ITEM]",materialtype);
        for ( var i = 1; row = metrialrefTab.rows[i]; i++ )
        {
            row = metrialrefTab.rows[i];
            var materialinnerarray=[];
            col = row.cells[1];
            materialinnerarray.push(col.firstChild.nodeValue);
            if(materialtype==materialinnerarray){
                show_msgbox("REPORT SUBMISSION UPDATE",errormsg,"error",false);
                $('#SRC_material_items').val('SELECT').show();
            }
        }
    });
    //CHECK stock usage item
    $(document).on("change",'#SRC_stock_itemno', function (){
        var stockrefTab = document.getElementById("SRC_stockusage_table");
        var stocktype=$('#SRC_stock_itemno').val();
        var errormsg=error_message[9].toString().replace("[ITEM]",stocktype);
        for ( var i = 1; row = stockrefTab.rows[i]; i++ )
        {
            row = stockrefTab.rows[i];
            var stockinnerarray=[];
            col = row.cells[1];
            stockinnerarray.push(col.firstChild.nodeValue);
            if(stocktype==stockinnerarray){
                show_msgbox("REPORT SUBMISSION UPDATE",errormsg,"error",false);
                $('#SRC_stock_itemno').val('SELECT').show();
            }
        }
    });
    //FINAL SUBMIT FUNCTION
    $(document).on("click",'#SRC_Final_Update', function (){
        $('.preloader').show();
        //SITE STOCK DETAILS TABLE RECORDS
        var stockrefTab = document.getElementById("SRC_stockusage_table");
        var stockusage_array=[];
        for (var r = 1, n = stockrefTab.rows.length; r < n; r++) {
            var stockrowid;
            var stockinnerarray=[];
            var stockinputval = stockrefTab.getElementsByTagName('input');
            for (var j=0; j < r; j++){
                if (stockinputval[j].value != ""){
                    stockrowid=stockinputval[j].value;
                }
                if (stockinputval[j].value == ""){
                    stockrowid="";
                }
            }
            if(stockrowid==""){stockrowid=" "}
            stockinnerarray.push(stockrowid);
            for (var c = 1, m = stockrefTab.rows[r].cells.length; c < m; c++) {
                stockinnerarray.push(stockrefTab.rows[r].cells[c].innerHTML);
            }
            stockusage_array.push(stockinnerarray) ;
        }
        if(stockusage_array.length==0)
        {
            stockusage_array='null';
        }
        //MATERIAL DETAILS TABLE RECORDS
        var metrialrefTab = document.getElementById("SRC_material_table");
        var materialusage_array=[];
        for (var r = 1, n = metrialrefTab.rows.length; r < n; r++) {
            var materialrowid;
            var materialinnerarray=[];
            var metrialinputval = metrialrefTab.getElementsByTagName('input');
            for (var j=0; j < r; j++){
                if (metrialinputval[j].value != ""){
                    materialrowid=metrialinputval[j].value;
                }
                if (metrialinputval[j].value == ""){
                    materialrowid="";
                }
            }
            if(materialrowid==""){materialrowid=" "}
            materialinnerarray.push(materialrowid);
            for (var c = 1, m = metrialrefTab.rows[r].cells.length; c < m; c++) {
                materialinnerarray.push(metrialrefTab.rows[r].cells[c].innerHTML);
            }
            materialusage_array.push(materialinnerarray) ;
        }
        if(materialusage_array.length==0)
        {
            materialusage_array='null';
        }
        //FITTING DETAILS TABLE RECORDS
        var fittingusage_array=[];
        var fittingrefTab = document.getElementById('SRC_fitting_table');
        for (var r = 1, n = fittingrefTab.rows.length; r < n; r++) {
            var fittinginnerarray=[];
            var fittingrowid;
            var fittinginputval = fittingrefTab.getElementsByTagName('input');
            for (var j=0; j < r; j++){
                if (fittinginputval[j].value != ""){
                    fittingrowid=fittinginputval[j].value;
                }
                if (fittinginputval[j].value == ""){
                    fittingrowid="";
                }
            }
            if(fittingrowid==""){fittingrowid=" "}
            fittinginnerarray.push(fittingrowid);
            for (var c = 1, m = fittingrefTab.rows[r].cells.length; c < m; c++) {
                fittinginnerarray.push(fittingrefTab.rows[r].cells[c].innerHTML);
            }
            fittingusage_array.push(fittinginnerarray);
        }
        if(fittingusage_array.length==0)
        {
            fittingusage_array='null';
        }
        //EQUIPMENT DETAILS TABLE RECORDS
        var equipmentrefTab = document.getElementById("SRC_equipment_table");
        var equipmentusage_array=[];
        for (var r = 1, n = equipmentrefTab.rows.length; r < n; r++) {
            var equipmentrowid;
            var equipmentinnerarray=[];
            var equipmentinputval = equipmentrefTab.getElementsByTagName('input');
            for (var j=0; j < r; j++){
                if (equipmentinputval[j].value != ""){
                    equipmentrowid=equipmentinputval[j].value;
                }
                if (equipmentinputval[j].value == ""){
                    equipmentrowid="";
                }
            }
            if(equipmentrowid==""){equipmentrowid=" "}
            equipmentinnerarray.push(equipmentrowid);
            for (var c = 1, m = equipmentrefTab.rows[r].cells.length; c < m; c++) {
                equipmentinnerarray.push(equipmentrefTab.rows[r].cells[c].innerHTML);
            }
            equipmentusage_array.push(equipmentinnerarray) ;
        }
        if(equipmentusage_array.length==0)
        {
            equipmentusage_array='null';
        }
        //RENTAL MACHINERY TABLE RECORDS
        var rentalrefTab = document.getElementById("SRC_rental_table");
        var rentalmechinery_array=[];
        for (var r = 1, n = rentalrefTab.rows.length; r < n; r++) {
            var rentalrowid;
            var rentalinnerarray=[];
            var rentalinputval = rentalrefTab.getElementsByTagName('input');
            for (var j=0; j < r; j++){
                if (rentalinputval[j].value != ""){
                    rentalrowid=rentalinputval[j].value;
                }
                if (rentalinputval[j].value == ""){
                    rentalrowid="";
                }
            }
            if(rentalrowid==""){rentalrowid=" "}
            rentalinnerarray.push(rentalrowid);
            for (var c = 1, m = rentalrefTab.rows[r].cells.length; c < m; c++) {
                rentalinnerarray.push(rentalrefTab.rows[r].cells[c].innerHTML);
            }
            rentalmechinery_array.push(rentalinnerarray) ;
        }
        if(rentalmechinery_array.length==0)
        {
            rentalmechinery_array='null';
        }
        //MACHINERY USAGE TABLE RECORDS
        var mechineryrefTab = document.getElementById("SRC_machinery_table");
        var mechineryusage_array=[];
        for (var r = 1, n = mechineryrefTab.rows.length; r < n; r++) {
            var mecineryrowid;
            var machineryinnerarray=[];
            var mecineryinputval = mechineryrefTab.getElementsByTagName('input');
            for (var j=0; j < r; j++){
                if (mecineryinputval[j].value != ""){
                    mecineryrowid=mecineryinputval[j].value;
                }
                if (mecineryinputval[j].value == ""){
                    mecineryrowid="";
                }
            }
            if(mecineryrowid==""){mecineryrowid=" "}
            machineryinnerarray.push(mecineryrowid);
            for (var c = 1, m = mechineryrefTab.rows[r].cells.length; c < m; c++) {
                machineryinnerarray.push(mechineryrefTab.rows[r].cells[c].innerHTML);
            }
            mechineryusage_array.push(machineryinnerarray) ;
        }
        if(mechineryusage_array.length==0)
        {
            mechineryusage_array='null';
        }
        //MACHINERY / EQUIPMENT TRANSFER TABLE RECORDS
        var mech_eqp_refTab = document.getElementById("SRC_mtransfer_table");
        var mech_eqp_array=[];
        for (var r = 1, n = mech_eqp_refTab.rows.length; r < n; r++) {
            var mech_eqprowid;
            var mach_eqp_innerarray=[];
            var mech_eqpinputval = mech_eqp_refTab.getElementsByTagName('input');
            for (var j=0; j < r; j++){
                if (mech_eqpinputval[j].value != ""){
                    mech_eqprowid=mech_eqpinputval[j].value;
                }
                if (mech_eqpinputval[j].value == ""){
                    mech_eqprowid="";
                }
            }
            if(mech_eqprowid==""){mech_eqprowid=" "}
            mach_eqp_innerarray.push(mech_eqprowid);
            for (var c = 1, m = mech_eqp_refTab.rows[r].cells.length; c < m; c++) {
                mach_eqp_innerarray.push(mech_eqp_refTab.rows[r].cells[c].innerHTML);
            }
            mech_eqp_array.push(mach_eqp_innerarray) ;
        }
        if(mech_eqp_array.length==0)
        {
            mech_eqp_array='null';
        }
        //SITE VISIT TABLE RECORDS
        var SV_refTab = document.getElementById("SRC_sv_tbl");
        var SV_array=[];
        for (var r = 1, n = SV_refTab.rows.length; r < n; r++) {
            var svrowid;
            var SV_innerarray=[];
            var SV_inputval = SV_refTab.getElementsByTagName('input');
            for (var j=0; j < r; j++){
                if (SV_inputval[j].value != ""){
                    svrowid=SV_inputval[j].value;
                }
                if (SV_inputval[j].value == ""){
                    svrowid="";
                }
            }
            if(svrowid==""){svrowid=" "}
            SV_innerarray.push(svrowid);
            for (var c = 1, m = SV_refTab.rows[r].cells.length; c < m; c++) {
                SV_innerarray.push(SV_refTab.rows[r].cells[c].innerHTML);
            }
            SV_array.push(SV_innerarray);
        }
        if(SV_array.length==0)
        {
            SV_array='null';
        }
        // MEETING TABLE RECORDS
        var meetingrefTab = document.getElementById("SRC_meeting_table");
        var meeting_array=[];
        for (var r = 1, n = meetingrefTab.rows.length; r < n; r++) {
            var meetingrowid;
            var meetinginnerarray=[];
            var mt_inputval = meetingrefTab.getElementsByTagName('input');
            for (var j=0; j < r; j++){
                if (mt_inputval[j].value != ""){
                    meetingrowid=mt_inputval[j].value;
                }
                if (mt_inputval[j].value == ""){
                    meetingrowid="";
                }
            }
            if(meetingrowid==""){meetingrowid=" ";}
            meetinginnerarray.push(meetingrowid);
            for (var c = 1, m = meetingrefTab.rows[r].cells.length; c < m; c++) {
                meetinginnerarray.push(meetingrefTab.rows[r].cells[c].innerHTML);
            }
            meeting_array.push(meetinginnerarray) ;
        }
        if(meeting_array.length==0)
        {
            meeting_array='null';
        }
        //EMPPLOYEE TABLE RECORDS
        var employeerowcount=$('#SRC_Employee_table tr').length;
        for(var j=0;j<employeerowcount-1;j++)
        {
            var autoid=j+1;
            var emp_id=$('#SRC_Emp_id'+autoid).val();
            if(emp_id==employee_id)
            {
                var emp_name=$('#SRC_Emp_name'+autoid).val();
                var emp_start=$('#SRC_Emp_starttime'+autoid).val();
                var emp_end=$('#SRC_Emp_endtime'+autoid).val();
                var emp_ot=$('#SRC_Emp_ot'+autoid).val();
                var emp_remark=$('#SRC_Emp_remark'+autoid).val();
                var Employeeid;var Start;var End;var OT;var Remark;
                Employeeid=emp_id;Start=emp_start;End=emp_end;OT=emp_ot;Remark=emp_remark;
            }
        }
        var EmployeeDetails=[Employeeid,Start,End,OT,Remark];

        var formelement =$('#SRC_entryform').serialize();
//            var dataURL = canvas.toDataURL();
        var dataURL;
        if(imagecheckflag==0)
        {
        if(finalimagedata!=""&&finalimagedata!=null&&finalimagedata!=undefined)
        {
            dataURL = imageDataJson+"DrawToolImageurl:"+imageData;//finalimagedata;//JSON.stringify(canvas)+"DrawToolImageurl:"+canvas.toDataURL();
        }
        else if(finalimagedata==""||finalimagedata==null||finalimagedata==undefined)
        {
        dataURL='';
        }
        }
        else
        {
        if(canvas.isEmpty())
        {
            dataURL='';
        }
        else
        {
            dataURL = JSON.stringify(canvas)+"DrawToolImageurl:"+canvas.toDataURL();
        }
        }

        var arraydata={"Option":"UpdateForm","SRC_StockDetails": stockusage_array,"SRC_MaterialDetails": materialusage_array,"SRC_FittingDetails":fittingusage_array,"SRC_EquipmentDetails":equipmentusage_array,"SRC_RentalDetails":rentalmechinery_array,"SRC_MechineryUsageDetails":mechineryusage_array,"SRC_MechEqptransfer":mech_eqp_array,"SRC_SiteVisit":SV_array,"SRC_MeetingDetails":meeting_array,"SRC_EmployeeDetails":EmployeeDetails,"imgData": dataURL};
        data=formelement + '&' + $.param(arraydata);
        $.ajax({
            type: "POST",
            url: "DB_PERMITS_ENTRY.php",
            data:data,
            success: function(msg){
                var msg_alert=JSON.parse(msg);
                var spflag=msg_alert[0];
                var dirflag=msg_alert[1];
                var writeable=msg_alert[2];
                if(spflag==1)
                {
                    $('#SRC_entryform').hide();
                    $('#SRC_stock_itemno').html('<option>SELECT</option>');
                    $('#SRC_Final_Update').attr('disabled','disabled').hide();
                    datatable();
                    show_msgbox("REPORT SUBMISSION UPDATE",error_message[0],"success",false);
                    item_array=[];
                }
                else if(spflag==0)
                {
                    show_msgbox("REPORT SUBMISSION UPDATE",error_message[1],"error",false);
                    $('.preloader').hide();
                }
                else if(dirflag==0)
                {
                    show_msgbox("REPORT SUBMISSION UPDATE",error_message[7],"error",false);
                    $('.preloader').hide();
                }
                else if(writeable==0)
                {
                    show_msgbox("REPORT SUBMISSION UPDATE",error_message[10],"error",false);
                    $('.preloader').hide();
                }
                else
                {
                    show_msgbox("REPORT SUBMISSION UPDATE",msg_alert,"error",false);
                    $('.preloader').hide();
                }
            }
        });
    });
//END OF FINAL SUBMIT FUNCTION

});
</script>
</head>
<body>
<div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
<div class="container">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">REPORT SUBMISSION UPDATE</h3>
        </div>
        <div class="panel-body">
            <div class="row form-group">
                <div class="col-md-1"></div>
                <div class="col-md-3 selectContainer">
                    <label id="tr_lbl_team">EMPLOYEE NAME</label>
                    <select class="form-control" id="SRC_team_lb_empname" name="SRC_team_lb_empname">
                        <option>SELECT</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>FROM DATE</label>
                    <div class="input-group">
                        <input id="SRC_from_date" name="SRC_from_date" type="text" class="date-picker datemandtry dterange form-control" placeholder="From Date"/>
                        <label for="SRC_from_date" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                    </div>
                </div>
                <div class="col-md-2">
                    <label>TO DATE</label>
                    <div class="input-group">
                        <input id="SRC_to_date" name="SRC_to_date" type="text" class="date-picker datemandtry dterange form-control" placeholder="To Date"/>
                        <label for="SRC_to_date" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                    </div>
                </div>
                <div class="col-md-3" style="padding-top:25px">
                    <button type="button" id="SRC_searchbtn" class="btn btn-info" disabled>SEARCH</button>
                </div>
            </div>
            <div id="pdfModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">REPORT PDF</h4>
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
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">TEAM REPORT</h3>
                    </div>
                    <div class="panel-body">
                        <!--        <form id="teamreport" class="form-horizontal">-->
                        <fieldset>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label id="tr_lbl_location">LOCATION</label>
                                    <input type="text" class="form-control alphanumeric txtlen" id="SRC_tr_txt_location" name="SRC_tr_txt_location" placeholder="Location">
                                </div>
                                <div class="col-md-3">
                                    <label  id="tr_lbl_contactno">CONTRACT NO <em>*</em></label>
                                    <select class="form-control" id="SRC_tr_lb_contractno" name="SRC_tr_lb_contractno"><option>SELECT</option></select>
                                </div>
                                <div class="col-md-3 selectContainer">
                                    <label id="tr_lbl_team">TEAM</label>
                                    <input type="text" class="form-control" id="SRC_tr_tb_team" name="SRC_tr_tb_team" placeholder="Team" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label id="tr_lbl_date">DATE <em>*</em></label>
                                    <div class="input-group">
                                        <input id="SRC_tr_txt_date" name="SRC_tr_txt_date" type="text" class="form-control" readonly placeholder="Date"/>
                                        <label for="SRC_tr_txt_date" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label id="SRC_tr_lbl_weather">WEATHER</label>
                                    <input type="text" class="form-control alphanumeric txtlen" id="SRC_tr_txt_weather" name="SRC_tr_txt_weather" placeholder="Weather">
                                </div>
                                <div class="col-md-2">
                                    <label id="SRC_tr_lbl_reachsite">FROM</label>
                                    <input type="text" class="form-control time-picker" id="SRC_tr_txt_wftime" name="SRC_tr_txt_wftime" placeholder="Weather Time">
                                </div>
                                <div class="col-md-2">
                                    <label id="tr_lbl_leavesite">TO</label>
                                    <input type="text" class="form-control time-picker" id="SRC_tr_txt_wttime" name="SRC_tr_txt_wttime" placeholder="Weather Time">
                                </div>
                                <div class="col-md-2">
                                    <label id="tr_lbl_reachsite">REACH SITE</label>
                                    <input type="text" class="form-control time-picker" id="SRC_tr_txt_reachsite"  name="SRC_tr_txt_reachsite" placeholder="Time">
                                </div>
                                <div class="col-md-2">
                                    <label id="tr_lbl_leavesite">LEAVE SITE</label>
                                    <input type="text" class="form-control time-picker" id="SRC_tr_txt_leavesite" name="SRC_tr_txt_leavesite" placeholder="Time">
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
                        <!--      </form>-->
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">TOOLBOX MEETING</h3>
                    </div>
                    <div class="panel-body">
                        <!--        <form>-->
                        <fieldset>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label for="SRC_mt_lbl_topic" id="SRC_mt_lbl_topic">TOPIC</label>
                                    <select class="form-control meetingform-validation" id="SRC_mt_lb_topic" name="SRC_mt_lb_topic">
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label for="SRC_mt_lbl_remark" id="SRC_mt_lbl_remark">REMARKS</label>
                                    <textarea class="form-control meetingform-validation remarklen removecap" style="min-height: 35px;" rows="1" id="SRC_mt_ta_remark" name="SRC_mt_ta_remark" placeholder="Remarks"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" name="SRC_mt_rowid" id="SRC_mt_rowid" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRC_mt_btn_addrow" class="btn btn-info" >ADD</button>
                                <button type="button" id="SRC_mt_btn_update" class="btn btn-info SRC_mt_btn_updaterow" >UPDATE</button>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRC_meeting_table">
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
                        <!--        </form>-->
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">JOB DONE</h3>
                    </div>
                    <div class="panel-body">
                        <!--        <form id="jobdone" class="form-horizontal" role="form">-->
                        <fieldset>
                            <div class="table-responsive">
                                <table class="table" border="1" style="border: #ddd;">
                                    <tr>
                                        <td class="jobthl">
                                            <label style="padding-bottom: 15px"></label>
                                            <label id="SRC_tr_lbl_pipelaid">PIPE LAID</label>
                                        </td>
                                        <td colspan="2" style="text-align: center">
                                            <div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="SRC_jd_chk_road" name="SRC_jd_chk_road"> ROAD
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="2" style="text-align: center">
                                            <div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="SRC_jd_chk_contc" name="SRC_jd_chk_contc"> CONC
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="2" style="text-align: center">
                                            <div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="SRC_jd_chk_truf" name="SRC_jd_chk_truf"> TURF
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="jobthl" style="border-top: 1px solid white;">
                                            <label style="padding-bottom: 15px"> </label>
                                            <label id="SRC_tr_lbl_location">SIZE/LENGTH</label>
                                        </td>
                                        <td class="jobtd" style="border-top: 1px solid white;">
                                            <div>
                                                <label>M</label>
                                                <input type="text" class="form-control decimal size" id="SRC_jd_chk_roadm" name="SRC_jd_chk_roadm" placeholder="M">
                                            </div>
                                        </td>
                                        <td style="border-top: 1px solid white;">
                                            <div>
                                                <label>MM</label>
                                                <input type="text" class="form-control decimal size" id="SRC_jd_chk_roadmm"  name="SRC_jd_chk_roadmm" placeholder="MM">
                                            </div>
                                        </td>
                                        <td class="jobtd" style="border-top: 1px solid white;">
                                            <div>
                                                <label>M</label>
                                                <input type="text" class="form-control decimal size" id="SRC_jd_chk_concm"   name="SRC_jd_chk_concm" placeholder="M">
                                            </div>
                                        </td>
                                        <td style="border-top: 1px solid white;">
                                            <div>
                                                <label>MM</label>
                                                <input type="text" class="form-control decimal size" id="SRC_jd_chk_concmm" name="SRC_jd_chk_concmm" placeholder="MM">
                                            </div>
                                        </td>
                                        <td class="jobtd" style="border-top: 1px solid white;">
                                            <div>
                                                <label>M</label>
                                                <input type="text" class="form-control decimal size" id="SRC_jd_chk_trufm" name="SRC_jd_chk_trufm" placeholder="M">
                                            </div>
                                        </td>
                                        <td class="jobthr" style="border-top: 1px solid white;">
                                            <div>
                                                <label>MM</label>
                                                <input type="text" class="form-control decimal size" id="SRC_jd_chk_trufmm" name="SRC_jd_chk_trufmm" placeholder="MM">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label for="SRC_jd_txt_testing" id="SRC_jd_lbl_testing">PIPE TESTING</label>
                                    <input type="text" class="form-control alphanumeric txtlen" id="SRC_jd_txt_pipetesting" name="SRC_jd_txt_pipetesting" placeholder="Pipe Testing">
                                </div>
                                <div class="col-md-3">
                                    <label for="SRC_jd_txt_start" id="SRC_jd_lbl_start" >START (PRESSURE)</label>
                                    <input type="text" class="form-control alphanumeric quantity"  id="SRC_jd_txt_start" name="SRC_jd_txt_start" placeholder="Start Pressure">
                                </div>
                                <div class="col-md-3">
                                    <label for="SRC_jd_txt_end" id="SRC_jd_lbl_end">END (PRESSURE)</label>
                                    <input type="text" class="form-control alphanumeric quantity" id="SRC_jd_txt_end" name="SRC_jd_txt_end" placeholder="End Pressure">
                                </div>
                                <div class="col-md-3">
                                    <label for="SRC_jd_ta_remark" id="SRC_jd_lbl_remark">REMARKS</label>
                                    <textarea class="form-control remarklen removecap textareaaccinjured" rows="1" id="SRC_jd_ta_remark" name="SRC_jd_ta_remark" placeholder="Remarks"></textarea>
                                </div>
                            </div>
                        </fieldset>
                        <!--        </form>-->
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">EMPLOYEE REPORT DETAILS</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRC_Employee_table" name="SRC_Employee_table">
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
                    </div>
                </div>
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
                                    <input class="form-control alphanumeric form-validation txtlen" id="SRC_sv_txt_designation" placeholder="Designation"/>
                                </div>
                                <div class="col-md-3">
                                    <label>NAME</label>
                                    <input class="form-control form-validation txtlen autosizealph" id="SRC_sv_txt_name" placeholder="Name"/>
                                </div>
                                <div class="col-md-1">
                                    <label>START</label>
                                    <input type="text" class="form-control form-validation time-picker" id="SRC_sv_txt_start" placeholder="Time">
                                </div>
                                <div class="col-md-1">
                                    <label>END</label>
                                    <input type="text" class="form-control form-validation time-picker" id="SRC_sv_txt_end" placeholder="Time">
                                </div>
                                <div class="col-md-4">
                                    <label>REMARKS</label>
                                    <textarea class="form-control form-validation remarklen removecap textareaaccinjured"  rows="1" id="SRC_sv_txt_remark" placeholder="Remarks"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" id="SRC_sv_rowid" name="sv_rowid" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRC_sv_btn_addrow" class="btn btn-info" >ADD</button>
                                <button type="button" id="SRC_sv_btn_update" class="btn btn-info SRC_sv_btn_updaterow" >UPDATE</button>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRC_sv_tbl">
                                <thead>
                                <tr class="active">
                                    <th>EDIT/REMOVE</th>
                                    <th>DESIGNATION</th>
                                    <th>NAME</th>
                                    <th>START</th>
                                    <th>END</th>
                                    <th>REMARKS</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
                                    <input type="text" class="form-control SRC_form-validation quantity lorryno" id="SRC_mtranser_from" name="SRC_mtranser_from" placeholder="From (Lorry No)">
                                </div>
                                <div class="col-md-4">
                                    <label>ITEM</label>
                                    <select class="form-control SRC_form-validation" id="SRC_mtransfer_item" name="SRC_mtransfer_item">
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>TO (LORRY NO)</label>
                                    <input type="text" class="form-control SRC_form-validation quantity lorryno" id="SRC_mtransfer_to"  name="SRC_mtransfer_to" placeholder="To (Lorry No)">
                                </div>
                                <div class="col-md-4">
                                    <label>REMARKS</label>
                                    <textarea class="form-control SRC_form-validation remarklen removecap textareaaccinjured" id="SRC_mtransfer_remark"  rows="1" name="SRC_mtransfer_remark" placeholder="Remarks"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" id="SRC_mtransfer_rowid" name="SRC_mtransfer_rowid" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRC_mtransfer_addrow" class="btn btn-info" >ADD</button>
                                <button type="button" id="SRC_mtransfer_update" class="btn btn-info SRC_mtransfer_updaterow" >UPDATE</button>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRC_mtransfer_table" name="SRC_mtransfer_table">
                                <thead>
                                <tr class="active">
                                    <th>EDIT/REMOVE</th>
                                    <th>FROM(LORRY NO)</th>
                                    <th>ITEM</th>
                                    <th>TO(LORRY NO)</th>
                                    <th>REMARKS</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
                                    <select class="form-control SRC_machineryform-validation" id="SRC_machinery_type" name="SRC_machinery_type">
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>START</label>
                                    <input type="text" class="form-control SRC_machineryform-validation time-picker"  id="SRC_machinery_start" name="SRC_machinery_start" placeholder="Time">
                                </div>

                                <div class="col-md-2">
                                    <label>END</label>
                                    <input type="text" class="form-control SRC_machineryform-validation time-picker"  id="SRC_machinery_end"  name="SRC_machinery_end" placeholder="Time">
                                </div>
                                <div class="col-md-4">
                                    <label>REMARKS</label>
                                    <textarea class="form-control remarklen removecap SRC_machineryform-validation textareaaccinjured" id="SRC_machinery_remarks" rows="1" name="SRC_machinery_remarks" placeholder="Remarks"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" id="SRC_machinery_rowid" name="SRC_machinery_rowid" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRC_machinery_addrow" class="btn btn-info" >ADD</button>
                                <button type="button" id="SRC_machinery_update" class="btn btn-info SRC_machinery_updaterow" >UPDATE</button>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRC_machinery_table" name="SRC_machinery_table">
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
                    </div>
                </div>
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
                                    <input type="text" class="form-control SRC_rentalform-validation quantity lorryno" id="SRC_rental_lorryno" name="SRC_rental_lorryno" placeholder="Lorry Name">
                                </div>
                                <div class="col-md-4">
                                    <label>THROW EARTH(STORE)</label>
                                    <input type="text" class="form-control SRC_rentalform-validation decimal size" id="SRC_rental_throwearthstore" name="SRC_rental_throwearthstore" placeholder="Throw Earth(Store)">
                                </div>
                                <div class="col-md-4">
                                    <label>THROW EARTH(OUTSIDE)</label>
                                    <input type="text" class="form-control SRC_rentalform-validation decimal size" id="SRC_rental_throwearthoutside" name="SRC_rental_throwearthoutside" placeholder="Throwe Earth(Outside)">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-2">
                                    <label>START TIME</label>
                                    <input type="text" class="form-control SRC_rentalform-validation time-picker" id="SRC_rental_start" name="SRC_rental_start" placeholder="Time">
                                </div>
                                <div class="col-md-2">
                                    <label>END TIME</label>
                                    <input type="text" class="form-control SRC_rentalform-validation  time-picker" id="SRC_rental_end"  name="SRC_rental_end" placeholder="Time">
                                </div>
                                <div class="col-md-4">
                                    <label>REMARKS</label>
                                    <textarea class="form-control SRC_rentalform-validation remarklen removecap textareaaccinjured" id="SRC_rental_remarks" rows="1" name="SRC_rental_remarks" placeholder="Remark"></textarea>
                                    <input type="hidden" class="form-control" id="SRC_rentalmechinery_id" name="SRC_rentalmechinery_id">
                                </div>
                            </div>
                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRC_rentalmechinery_addrow" class="btn btn-info" >ADD</button>
                                <button type="button" id="SRC_rentalmechinery_updaterow" class="btn btn-info SRC_rentalmechineryupdaterow" >UPDATE</button>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRC_rental_table">
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
                    </div>
                </div>
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
                                    <input type="text" class="form-control alphanumeric SRC_equipmentform-validation txtlen "  id="SRC_equipment_aircompressor" name="SRC_equipment_aircompressor" placeholder="Air-Compressor">
                                </div>
                                <div class="col-md-3">
                                    <label>LORRY NO(TRANSPORT)</label>
                                    <input type="text" class="form-control SRC_equipmentform-validation quantity lorryno" id="SRC_equipment_lorryno" name="SRC_equipment_lorryno" placeholder="Lorry No(Transport)">
                                </div>
                                <div class="col-md-1">
                                    <label>START</label>
                                    <input type="text" class="form-control SRC_equipmentform-validation time-picker" id="SRC_equipment_start"  name="SRC_equipment_start" placeholder="Time">
                                </div>
                                <div class="col-md-1">
                                    <label>END</label>
                                    <input type="text" class="form-control SRC_equipmentform-validation time-picker" id="SRC_equipment_end"  name="SRC_equipment_end" placeholder="Time">
                                </div>
                                <div class="col-md-4">
                                    <label>REMARKS</label>
                                    <textarea class="form-control SRC_equipmentform-validation remarklen removecap textareaaccinjured" rows="1" id="SRC_equipment_remark"  name="SRC_equipment_remark" placeholder="Remarks"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" id="SRC_equipment_rowid" name="SRC_equipment_rowid" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRC_equipment_addrow" class="btn btn-info" >ADD</button>
                                <button type="button" id="SRC_equipment_update" class="btn btn-info SRC_equipment_updaterow" >UPDATE</button>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRC_equipment_table" name="SRC_equipment_table">
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
                    </div>
                </div>
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
                                    <select class="form-control SRC_fittingform-validation" id="SRC_fitting_items" name="SRC_fitting_items" placeholder="Items">
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>SIZE</label>
                                    <input type="text" class="form-control SRC_fittingform-validation decimal size" id="SRC_fitting_size" name="SRC_fitting_size" placeholder="MM">
                                </div>
                                <div class="col-md-2">
                                    <label>QUANTITY</label>
                                    <input type="text" class="form-control SRC_fittingform-validation decimal size" id="SRC_fitting_quantity" name="SRC_fitting_quantity" placeholder="Quantity">
                                </div>
                                <div class="col-md-4">
                                    <label>REMARKS</label>
                                    <textarea class="form-control remarklen removecap SRC_fittingform-validation textareaaccinjured" rows="1" id="SRC_fitting_remarks" name="SRC_fitting_remarks" placeholder="Remarks"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" class="form-control" id="SRC_fitting_id" name="SRC_fitting_id">
                                </div>
                            </div>
                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRC_fitting_addrow" class="btn btn-info" >ADD</button>
                                <button type="button" id="SRC_fitting_updaterow" class="btn btn-info  SRC_fittingupdaterow" >UPDATE</button>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRC_fitting_table">
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
                    </div>
                </div>
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
                                    <select class="form-control SRC_materialform-validation" id="SRC_material_items" name="SRC_material_items" placeholder="Items">
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>RECEIPT NO</label>
                                    <input type="text" class="form-control alphanumeric SRC_materialform-validation quantity" id="SRC_material_receipt" name="SRC_material_receipt" placeholder="Receipt No">
                                </div>

                                <div class="col-md-4">
                                    <label>QUANTITY</label>
                                    <input type="text" class="form-control SRC_materialform-validation decimal size" id="SRC_material_quantity" name="SRC_material_quantity" placeholder="Quantity">
                                    <input type="hidden" class="form-control" id="SRC_material_id" name="SRC_material_id">
                                </div>
                            </div>
                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRC_material_addrow" class="btn btn-info" >ADD</button>
                                <button type="button" id="SRC_material_updaterow" class="btn btn-info SRC_materialupdaterow" >UPDATE</button>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRC_material_table">
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
                                    <select class="form-control SRC_stockusageform-validation" id="SRC_stock_itemno" name="SRC_stock_itemno">
                                        <option>SELECT</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>ITEM NAME</label>
                                    <input type="text" class="form-control SRC_stockusageform-validation" id="SRC_stock_itemname" name="SRC_stock_itemname" placeholder="Item Name" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label>QUANTITY</label>
                                    <input type="text" class="form-control SRC_stockusageform-validation decimal size" id="SRC_stock_quantity" name="SRC_stock_quantity" placeholder="Quantity">
                                    <input type="hidden" class="form-control" id="SRC_stock_id" name="SRC_stock_id">
                                </div>
                            </div>
                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRC_stock_addrow" class="btn btn-info" >ADD</button>
                                <button type="button" id="SRC_stock_updaterow" class="btn btn-info SRC_stockupdaterow" >UPDATE</button>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRC_stockusage_table">
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
                <!-- DRAWING SURFACE--->
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">DRAWING AREA</h3>
                    </div>
                    <div class="panel-body">
                    </div>
                    <div class="bs-example">
                        <!-- Modal HTML -->
                        <div id="myModal" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title">DRAWING SURFACE</h4>
                                        <input type="button" id="divExample" style="opacity:0" >
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-xs-9"><canvas id="canvas" style="border:1px solid #F5F5F5;"></canvas></div>
                                            <div class="row">
                                                <div class="col-xs-3 canvasshapes"><div class="general" style="background-color:#F5F5F5;border:1px solid lavender;">
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
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" id="closeImage" data-dismiss="modal">CLOSE</button>
                                        <button type="button" class="btn btn-primary" id="saveImage">SAVE CHANGES</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Button HTML (to Trigger Modal) -->
                        <input type="button" class="btn btn-lg btn-primary open-modal" value="SHOW DRAW TOOL">
                    </div>
                    <div id="divImage"></div>
                </div>
                <!-- ENDING DRAWING SURFACE--->
                <div class="col-lg-offset-10">
                    <button type="button" id="SRC_Final_Update" class="btn btn-info btn-lg" disabled>UPDATE</button>
                </div>
                <script src="../PAINT/JS/customShape.js"> </script>
            </form>
        </div>
        <div class="form-group-sm" id="backtotop">
            <ul class="nav-pills">
                <li class="pull-right"><a href="#top">Back to top</a></li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>