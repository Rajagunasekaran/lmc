<?php
include "../FOLDERMENU.php";
?>
<script>
var upload_count=0;
$(document).ready(function(){
//    $('.preloader').show();
    $('#backtotop').hide();
    var error_message=[];

    //END OF VALIDATION
    $('#SRCH_entryform').hide();
    //validation
    //set max length
    $(".txtlen").prop("maxlength", 40);
    $(".size").prop("maxlength", 5);
    $(".time-picker").prop("maxlength", 5);
    $(".quantity").prop("maxlength", 10);
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
// LORRY NO VALIDATIN
    $('.lorryno').keyup(function() {
        if (this.value.match(/[^a-zA-Z0-9\-]/g)) {
            this.value = this.value.replace(/[^a-zA-Z0-9\-]/g, '');
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
//CHANGE EVENT FOR REPORT DATRE
    $('#SRCH_search_date').change(function(){
        if($(this).val()!='')
        {
           $('#SRCH_searchbtn').removeAttr("disabled");
        }
        else
        {
            $('#SRCH_searchbtn').attr("disabled","disabled");
            $('#backtotop').hide();
        }
        $('#SRCH_entryform').hide();
        $('#backtotop').hide();

    });
    //End validation

    var employee_id;
    var imagefolderid;
    $(document).on("click",'#SRCH_searchbtn', function (){
        $('.preloader').show();
        $('#appendimg').empty();
        var selectedteam=$('#SRCH_team_lb_team').val();
        var selecteddate=$('#SRCH_search_date').val();
        $('#SRCH_jd_chk_road').attr('checked', false);
        $('#SRCH_jd_chk_roadm').val('');
        $('#SRCH_jd_chk_roadmm').val('');
        $('#SRCH_jd_chk_contc').attr('checked', false);
        $('#SRCH_jd_chk_concm').val('');
        $('#SRCH_jd_chk_concmm').val('');
        $('#SRCH_jd_chk_truf').attr('checked', false);
        $('#SRCH_jd_chk_trufm').val('');
        $('#SRCH_jd_chk_trufmm').val('');
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var value_array=JSON.parse(xmlhttp.responseText);
                error_message=value_array[16];
                $('#backtotop').show();
                if(value_array[8]!=null) {
                    empname = value_array[0];
                    var sitevisit = value_array[1];
                    var mech_equip_transfer = value_array[2];
                    var machinery_details = value_array[3];
                    var rentalmachinery_details = value_array[4];
                    var equipmentusage_details = value_array[5];
                    var fittingusage_details = value_array[6];
                    var material_details = value_array[7];
                    var teamreport_details = value_array[8];
                    var teamjob = value_array[10];
                    employee_id = value_array[11];
                    if (value_array[12] != '') {
                        var jobdone_pipilaid = ((value_array[12]).toString()).split(',');
                    }
                    if (value_array[13] != '') {
                        var jobdone_size = ((value_array[13]).toString()).split(',');
                    }
                    if (value_array[14] != '') {
                        var jobdone_length = ((value_array[14]).toString()).split(',');
                    }
                    var srchimgdata = value_array[15];
                    imagefolderid = value_array[17];
                    var meeting_details = value_array[18];
                    var stock_details=value_array[19];
                    $('#SRCH_tr_txt_wftime').val('');
                    $('#SRCH_tr_txt_wttime').val('');
                    $('#SRCH_tr_txt_weather').val('');
                    if (srchimgdata != null) {
                        $('<div><img src="' + srchimgdata + '" width="500" height="400" alt="embedded folder icon"></div>').appendTo($("#appendimg"));
                    }
                    else {
                        $('<div>No Image Available</div>').appendTo($("#appendimg"));
                    }
                    if ((value_array[12] != '') && (value_array[13] != '') && (value_array[14] != '')) {
                        if (jobdone_pipilaid[0] == 'ROAD' || jobdone_pipilaid[1] == 'ROAD' || jobdone_pipilaid[2] == 'ROAD') {
                            $('#SRCH_jd_chk_road').attr('checked', true);
                            $('#SRCH_jd_chk_roadm').val(jobdone_size[0]);
                            $('#SRCH_jd_chk_roadmm').val(jobdone_length[0]);
                        }
                        if (jobdone_pipilaid[0] == 'CONC' || jobdone_pipilaid[1] == 'CONC' || jobdone_pipilaid[2] == 'CONC') {
                            if (jobdone_pipilaid[0] == 'CONC') {
                                $('#SRCH_jd_chk_contc').attr('checked', true);
                                $('#SRCH_jd_chk_concm').val(jobdone_size[0]);
                                $('#SRCH_jd_chk_concmm').val(jobdone_length[0]);
                            }
                            else if (jobdone_pipilaid[1] == 'CONC') {
                                $('#SRCH_jd_chk_contc').attr('checked', true);
                                $('#SRCH_jd_chk_concm').val(jobdone_size[1]);
                                $('#SRCH_jd_chk_concmm').val(jobdone_length[1]);
                            }
                            else if (jobdone_pipilaid[2] == 'CONC') {
                                $('#SRCH_jd_chk_contc').attr('checked', true);
                                $('#SRCH_jd_chk_concm').val(jobdone_size[2]);
                                $('#SRCH_jd_chk_concmm').val(jobdone_length[2]);
                            }
                        }
                        if (jobdone_pipilaid[0] == 'TURF' || jobdone_pipilaid[1] == 'TURF' || jobdone_pipilaid[2] == 'TURF') {
                            if (jobdone_pipilaid[0] == 'TURF') {
                                $('#SRCH_jd_chk_truf').attr('checked', true);
                                $('#SRCH_jd_chk_trufm').val(jobdone_size[0]);
                                $('#SRCH_jd_chk_trufmm').val(jobdone_length[0]);
                            }
                            else if (jobdone_pipilaid[1] == 'TURF') {
                                $('#SRCH_jd_chk_truf').attr('checked', true);
                                $('#SRCH_jd_chk_trufm').val(jobdone_size[1]);
                                $('#SRCH_jd_chk_trufmm').val(jobdone_length[1]);
                            }
                            else if (jobdone_pipilaid[2] == 'TURF') {
                                $('#SRCH_jd_chk_truf').attr('checked', true);
                                $('#SRCH_jd_chk_trufm').val(jobdone_size[2]);
                                $('#SRCH_jd_chk_trufmm').val(jobdone_length[2]);
                            }

                        }
                    }
                    if ((teamjob != '') || (teamjob != null)) {
                        for (var t = 0; t < teamjob.length; t++) {
                            var id = teamjob[t][0];
                            id = id.replace(" ", "");
                            $('#' + id).attr('checked', false);
                        }
                    }
                    if (value_array[9] != null) {
                        var jobdetails = value_array[9].split(',');
                        for (var s = 0; s < jobdetails.length; s++) {
                            var id = jobdetails[s];
                            id = id.replace(" ", "");
                            $('#' + id).attr('checked', true);
                        }
                    }
                    //TEAM REPORT DETAILS
                    for (var a = 0; a < teamreport_details.length; a++) {

                        if (teamreport_details[a][1] == null) {
                            teamreport_details[a][1] = "";
                        }
                        if (teamreport_details[a][2] == null) {
                            teamreport_details[a][2] = "";
                        }
                        if (teamreport_details[a][3] == null) {
                            teamreport_details[a][3] = "";
                        }
                        if (teamreport_details[a][4] == null || teamreport_details[a][4] == '00:00') {
                            teamreport_details[a][4] = "";
                        }
                        if (teamreport_details[a][5] == null || teamreport_details[a][5] == '00:00') {
                            teamreport_details[a][5] = "";
                        }
                        if (teamreport_details[a][6] == null) {
                            teamreport_details[a][6] = "";
                        }
                        if (teamreport_details[a][7] == null) {
                            teamreport_details[a][7] = "";
                        }
                        if (teamreport_details[a][8] == null) {
                            teamreport_details[a][8] = "";
                        }
                        if (teamreport_details[a][9] == null) {
                            teamreport_details[a][9] = "";
                        }
                        if (teamreport_details[a][10] == null || teamreport_details[a][10] == '00:00') {
                            teamreport_details[a][10] = "";
                        }
                        if (teamreport_details[a][11] == null || teamreport_details[a][11] == '00:00') {
                            teamreport_details[a][11] = "";
                        }
                        if (teamreport_details[a][12] == null) {
                            teamreport_details[a][12] = "";
                        }

                        $('#SRCH_tr_txt_location').val(teamreport_details[a][1]);
                        $('#SRCH_tr_txt_date').val(teamreport_details[a][0]);
                        $('#SRCH_tr_txt_contractno').val(teamreport_details[a][2]);
                        $('#SRCH_tr_tb_team').val(teamreport_details[a][3]);
                        $('#SRCH_tr_txt_wftime').val(teamreport_details[a][7]);
                        $('#SRCH_tr_txt_wttime').val(teamreport_details[a][8]);
                        $('#SRCH_tr_txt_reachsite').val(teamreport_details[a][4]);
                        $('#SRCH_tr_txt_leavesite').val(teamreport_details[a][5]);
                        $('#SRCH_jd_txt_pipetesting').val(teamreport_details[a][9]);
                        $('#SRCH_jd_txt_start').val(teamreport_details[a][10]);
                        $('#SRCH_jd_txt_end').val(teamreport_details[a][11]);
                        $('#SRCH_jd_ta_remark').val(teamreport_details[a][12]).height(22);
                        $('#SRCH_tr_txt_weather').val(teamreport_details[a][13]);
                        if (teamreport_details[a][13] == '') {
                            $("#SRCH_tr_txt_wftime").val('');
                            $("#SRCH_tr_txt_wttime").val('');
                        }
                    }
                    //EMPLOYEE DETAILS
                    $('#SRCH_Employee_table tr:not(:first)').remove();
                    for (var i = 0; i < empname.length; i++) {
                        var autoid = i + 1;
                        var emp_name = "SRCH_Emp_name" + autoid;
                        var emp_id = "SRCH_Emp_id" + autoid;
                        var emp_start = "SRCH_Emp_starttime" + autoid;
                        var emp_end = "SRCH_Emp_endtime" + autoid;
                        var emp_ot = "SRCH_Emp_ot" + autoid;
                        var emp_remark = "SRCH_Emp_remark" + autoid;
                        if (empname[i][5] == null) {
                            empname[i][5] = "";
                        }
                        if (empname[i][4] == null) {
                            empname[i][4] = "";
                        }
                        if (empname[i][3] == null || empname[i][3] == '00:00') {
                            empname[i][3] = "";
                        }
                        if (empname[i][2] == null || empname[i][2] == '00:00') {
                            empname[i][2] = "";
                        }

                        if (employee_id == empname[i][0]) {
                            var appendrow = '<tr class="active"><td><input type="text" class="form-control" readonly style="max-width: 560px" name="name" id="' + emp_name + '" value="' + empname[i][1] + '"><input type="hidden" class="form-control" style="max-width: 100px" id="' + emp_id + '" value="' + empname[i][0] + '"></td><td><div class="col-lg-10"><input type="text" readonly class="form-control time-picker stime" style="max-width: 100px" id="' + emp_start + '" value="' + empname[i][2] + '"></div></td><td><div class="col-lg-10"><input type="text" readonly class="form-control time-picker etime" style="max-width: 100px" id="' + emp_end + '" value="' + empname[i][3] + '"></div></td><td><input type="text" readonly class="form-control" style="max-width: 100px" id="' + emp_ot + '" value="' + empname[i][4] + '"></td><td><textarea readonly class="form-control" rows="1" id="' + emp_remark + '">' + empname[i][5] + '</textarea></td></tr>';
                        }
                        else {
                            appendrow = '<tr class="active"><td><input type="text" class="form-control" readonly style="max-width: 560px" name="name" id="' + emp_name + '" value="' + empname[i][1] + '"><input type="hidden" class="form-control" style="max-width: 100px" id="' + emp_id + '" value="' + empname[i][0] + '"></td><td><div class="col-lg-10"><input type="text" readonly class="form-control time-picker stime" style="max-width: 100px" id="' + emp_start + '" value="' + empname[i][2] + '"></div></td><td><div class="col-lg-10"><input type="text" readonly class="form-control time-picker etime" style="max-width: 100px" id="' + emp_end + '" value="' + empname[i][3] + '"></div></td><td><input type="text" readonly class="form-control" style="max-width: 100px" id="' + emp_ot + '" value="' + empname[i][4] + '"></td><td><textarea readonly class="form-control" rows="1" id="' + emp_remark + '">' + empname[i][5] + '</textarea></td></tr>';
                        }
                        $('#SRCH_Employee_table tr:last').after(appendrow);
                        $('.time-picker').datetimepicker({
                            format: 'H:mm'
                        });
                        $('#SRCH_entryform').show();
                        $("textarea").autogrow({vertical: true, horizontal: true});
                    }
                    //MEETING DETAILS
                    $('#SRCH_meeting_table tr:not(:first)').remove();
                    if (meeting_details != null) {
                        for (var v = 0; v < meeting_details.length; v++) {
                            var mt_tablerowcount = $('#SRCH_meeting_table tr').length;
                            var mt_editid = 'SRCH_mt_editrow/' + mt_tablerowcount;
                            var mt_deleterowid = 'SRCH_mt_deleterow/' + mt_tablerowcount;
                            var mt_row_id = "SRCH_mt_tr_" + mt_tablerowcount;
                            var temp_textbox_id = "SRCH_mttemp_id" + mt_tablerowcount;
                            var mt_remark;
                            if (meeting_details[v][2] == null) {
                                mt_remark = "";
                            }
                            else {
                                mt_remark = meeting_details[v][2];
                            }
                            var appendrow = '<tr class="active" id=' + mt_row_id + '><td style="max-width: 450px">' + meeting_details[v][1] + '</td><td style="max-width: 300px">' + mt_remark + '</td></tr>';
                            $('#SRCH_meeting_table tr:last').after(appendrow);
                        }
                    }
                    //SITE VISIT DETAILS
                    $('#SRCH_sv_tbl tr:not(:first)').remove();
                    if (sitevisit != null) {
                        for (var j = 0; j < sitevisit.length; j++) {
                            var sv_tablerowcount = $('#SRCH_sv_tbl tr').length;
                            var sv_editid = 'SRCH_sv_editrow/' + sv_tablerowcount;
                            var sv_deleterowid = 'SRCH_sv_deleterow/' + sv_tablerowcount;
                            var sv_row_id = "SRCH_sv_tr_" + sv_tablerowcount;
                            var temp_textbox_id = "SRCH_svtemp_id" + sv_tablerowcount;
                            if (sitevisit[j][1] == null) {
                                sitevisit[j][1] = "";
                            }
                            if (sitevisit[j][2] == null) {
                                sitevisit[j][2] = "";
                            }
                            if (sitevisit[j][3] == null || sitevisit[j][3] == '00:00') {
                                sitevisit[j][3] = "";
                            }
                            if (sitevisit[j][4] == null || sitevisit[j][4] == '00:00') {
                                sitevisit[j][4] = "";
                            }
                            var siteremarks;
                            if (sitevisit[j][5] == null) {
                                siteremarks = '';
                            }
                            else {
                                siteremarks = sitevisit[j][5];
                            }

                            var appendrow = '<tr class="active" id=' + sv_row_id + '><td style="max-width: 250px">' + sitevisit[j][2] + '</td><td style="max-width: 250px">' + sitevisit[j][1] + '</td><td style="max-width: 250px">' + sitevisit[j][3] + '</td><td style="max-width: 250px">' + sitevisit[j][4] + '</td><td style="max-width: 250px">' + siteremarks + '</td></tr>';
                            $('#SRCH_sv_tbl tr:last').after(appendrow);
                        }
                    }
//                MACHINERY_EQUIPMENT DETAILS
                    $('#SRCH_mtransfer_table tr:not(:first)').remove();
                    if (mech_equip_transfer != null) {
                        for (var k = 0; k < mech_equip_transfer.length; k++) {
                            var mtransfertablerowcount = $('#SRCH_mtransfer_table tr').length;
                            var mtransfereditid = 'SRCH_mtransfereditrow/' + mtransfertablerowcount;
                            var mtransferdeleterowid = 'SRCH_mtransferdeleterow/' + mtransfertablerowcount;
                            var mtransfer_row_id = "SRCH_mtranser_tr_" + mtransfertablerowcount;
                            var temp_textbox_id = "SRCH_mtransfertemp_id" + mtransfertablerowcount;
                            if (mech_equip_transfer[k][1] == null) {
                                mech_equip_transfer[k][1] = "";
                            }
                            if (mech_equip_transfer[k][2] == null) {
                                mech_equip_transfer[k][2] = "";
                            }
                            if (mech_equip_transfer[k][3] == null) {
                                mech_equip_transfer[k][3] = "SELECT";
                            }
                            var mech_equip_remarks;
                            if (mech_equip_transfer[k][4] == null) {
                                mech_equip_remarks = '';
                            }
                            else {
                                mech_equip_remarks = mech_equip_transfer[k][4];
                            }
                            var appendrow = '<tr class="active" id=' + mtransfer_row_id + '><td style="max-width: 250px">' + mech_equip_transfer[k][1] + '</td><td style="max-width: 250px">' + mech_equip_transfer[k][3] + '</td><td style="max-width: 250px">' + mech_equip_transfer[k][2] + '</td><td style="max-width: 250px">' + mech_equip_remarks + '</td></tr>';
                            $('#SRCH_mtransfer_table tr:last').after(appendrow);
                        }
                    }
                    //MACHINERY USAGE DETAILS
                    $('#SRCH_machinery_table tr:not(:first)').remove();
                    if (machinery_details != null) {
                        for (var l = 0; l < machinery_details.length; l++) {
                            var machinerytablerowcount = $('#SRCH_machinery_table tr').length;
                            var machineryeditid = 'SRCH_machineryeditrow/' + machinerytablerowcount;
                            var machinerydeleterowid = 'SRCH_machinerydeleterow/' + machinerytablerowcount;
                            var machinery_row_id = "SRCH_machinery_tr_" + machinerytablerowcount;
                            var temp_textbox_id = "SRCH_machinerytemp_id" + machinerytablerowcount;
                            if (machinery_details[l][1] == null) {
                                machinery_details[l][1] = "SELECT";
                            }
                            if (machinery_details[l][2] == null || machinery_details[l][2] == '00:00') {
                                machinery_details[l][2] = "";
                            }
                            if (machinery_details[l][3] == null || machinery_details[l][3] == '00:00') {
                                machinery_details[l][3] = "";
                            }
                            var machineryremarks;
                            if (machinery_details[l][4] == null) {
                                machineryremarks = '';
                            }
                            else {
                                machineryremarks = machinery_details[l][4];
                            }
                            var appendrow = '<tr class="active" id=' + machinery_row_id + '><td style="max-width: 250px">' + machinery_details[l][1] + '</td><td style="max-width: 250px">' + machinery_details[l][2] + '</td><td style="max-width: 250px">' + machinery_details[l][3] + '</td><td style="max-width: 250px">' + machineryremarks + '</td></tr>';
                            $('#SRCH_machinery_table tr:last').after(appendrow);
                        }
                    }
                    //RENTAL MACHINERY DETAILS
                    $('#SRCH_rental_table tr:not(:first)').remove();
                    if (rentalmachinery_details != null) {
                        for (var m = 0; m < rentalmachinery_details.length; m++) {
                            var rentaltablerowcount = $('#SRCH_rental_table tr').length;
                            var rentaleditid = 'SRCH_machineryeditrow/' + rentaltablerowcount;
                            var rentaldeleterowid = 'SRCH_machinerydeleterow/' + rentaltablerowcount;
                            var rental_row_id = "SRCH_rental_tr_" + rentaltablerowcount;
                            var temp_textbox_id = "SRCH_rentaltemp_id" + rentaltablerowcount;
                            if (rentalmachinery_details[m][1] == null) {
                                rentalmachinery_details[m][1] = "";
                            }
                            if (rentalmachinery_details[m][2] == null) {
                                rentalmachinery_details[m][2] = "";
                            }
                            if (rentalmachinery_details[m][3] == null) {
                                rentalmachinery_details[m][3] = "";
                            }
                            if (rentalmachinery_details[m][4] == null || rentalmachinery_details[m][4] == '00:00') {
                                rentalmachinery_details[m][4] = "";
                            }
                            if (rentalmachinery_details[m][5] == null || rentalmachinery_details[m][5] == '00:00') {
                                rentalmachinery_details[m][5] = "";
                            }
                            var rentalremarks;
                            if (rentalmachinery_details[m][6] == null) {
                                rentalremarks = '';
                            }
                            else {
                                rentalremarks = rentalmachinery_details[m][6];
                            }
                            var appendrow = '<tr class="active" id=' + rental_row_id + '><td style="max-width: 250px">' + rentalmachinery_details[m][1] + '</td><td style="max-width: 250px">' + rentalmachinery_details[m][2] + '</td><td style="max-width: 250px">' + rentalmachinery_details[m][3] + '</td><td style="max-width: 250px">' + rentalmachinery_details[m][4] + '</td><td style="max-width: 250px">' + rentalmachinery_details[m][5] + '</td><td style="max-width: 250px">' + rentalremarks + '</td>';
                            $('#SRCH_rental_table tr:last').after(appendrow);
                        }
                    }
                    //EQUIPMENT USAGE DETAILS
                    $('#SRCH_equipment_table tr:not(:first)').remove();
                    if (equipmentusage_details != null) {
                        for (var n = 0; n < equipmentusage_details.length; n++) {
                            var equipmenttablerowcount = $('#SRCH_equipment_table tr').length;
                            var equipmenteditid = 'SRCH_equipmenteditrow/' + equipmenttablerowcount;
                            var equipmentdeleterowid = 'SRCH_equipementdeleterow/' + equipmenttablerowcount;
                            var equipment_row_id = "SRCH_equipment_tr_" + equipmenttablerowcount;
                            var temp_textbox_id = "SRCH_equipmenttemp_id" + equipmenttablerowcount;
                            if (equipmentusage_details[n][1] == null) {
                                equipmentusage_details[n][1] = "";
                            }
                            if (equipmentusage_details[n][2] == null) {
                                equipmentusage_details[n][2] = "";
                            }
                            if (equipmentusage_details[n][3] == null || equipmentusage_details[n][3] == '00:00') {
                                equipmentusage_details[n][3] = "";
                            }
                            if (equipmentusage_details[n][4] == null || equipmentusage_details[n][4] == '00:00') {
                                equipmentusage_details[n][4] = "";
                            }
                            var equipmentremarks;
                            if (equipmentusage_details[n][5] == null) {
                                equipmentremarks = '';
                            }
                            else {
                                equipmentremarks = equipmentusage_details[n][5];
                            }
                            var appendrow = '<tr class="active" id=' + equipment_row_id + '><td style="max-width: 250px">' + equipmentusage_details[n][1] + '</td><td style="max-width: 250px">' + equipmentusage_details[n][2] + '</td><td style="max-width: 250px">' + equipmentusage_details[n][3] + '</td><td style="max-width: 250px">' + equipmentusage_details[n][4] + '</td><td style="max-width: 250px">' + equipmentremarks + '</td></tr>';
                            $('#SRCH_equipment_table tr:last').after(appendrow);
                        }
                    }
                    //FITTING USAGE DETAILS
                    $('#SRCH_fitting_table tr:not(:first)').remove();
                    if (fittingusage_details != null) {
                        for (var o = 0; o < fittingusage_details.length; o++) {
                            var tablerowCount = $('#SRCH_fitting_table tr').length;
                            var editid = 'SRCH_fitting_editrow/' + tablerowCount;
                            var deleterowid = 'SRCH_fitting_deleterow/' + tablerowCount;
                            var row_id = "SRCH_fitting_tr_" + tablerowCount;
                            var temp_textbox_id = "SRCH_fittingtemp_id" + tablerowCount;
                            if (fittingusage_details[o][1] == null) {
                                fittingusage_details[o][1] = "SELECT";
                            }
                            if (fittingusage_details[o][2] == null) {
                                fittingusage_details[o][2] = "";
                            }
                            if (fittingusage_details[o][3] == null) {
                                fittingusage_details[o][3] = "";
                            }
                            var fittingremarks;
                            if (fittingusage_details[o][4] == null) {
                                fittingremarks = '';
                            }
                            else {
                                fittingremarks = fittingusage_details[o][4];
                            }
                            var appendrow = '<tr  class="active" id=' + row_id + '><td style="max-width: 250px">' + fittingusage_details[o][1] + '</td><td style="max-width: 250px">' + fittingusage_details[o][2] + '</td><td style="max-width: 250px">' + fittingusage_details[o][3] + '</td><td style="max-width: 250px">' + fittingremarks + '</td></tr>';
                            $('#SRCH_fitting_table tr:last').after(appendrow);
                        }
                    }
                    //MATERIAL USAGE DETAILS
                    $('#SRCH_material_table tr:not(:first)').remove();
                    if (material_details != null) {
                        for (var p = 0; p < material_details.length; p++) {
                            var tablerowCount = $('#SRCH_material_table tr').length;
                            var editid = 'SRCH_material_editrow/' + tablerowCount;
                            var deleterowid = 'SRCH_material_deleterow/' + tablerowCount;
                            var row_id = "SRCH_material_tr_" + tablerowCount;
                            var temp_textbox_id = "SRCH_materialtemp_id" + tablerowCount;
                            if (material_details[p][1] == null) {
                                material_details[p][1] = "SELECT";
                            }
                            if (material_details[p][2] == null) {
                                material_details[p][2] = "";
                            }
                            if (material_details[p][3] == null) {
                                material_details[p][3] = "";
                            }
                            var appendrow = '<tr class="active" id=' + row_id + '><td style="max-width: 250px">' + material_details[p][1] + '</td><td style="max-width: 250px">' + material_details[p][2] + '</td><td style="max-width: 250px">' + material_details[p][3] + '</td></tr>';
                            $('#SRCH_material_table tr:last').after(appendrow);
                        }
                    }
                    //STOCK USAGE DETAILS
                    $('#SRCH_stockusage_table tr:not(:first)').remove();
                    if (stock_details != null) {
                        for (var q = 0; q < stock_details.length; q++) {
                            var tablerowCount = $('#SRCH_stockusage_table tr').length;
                            var editid = 'SRCH_stock_editrow/' + tablerowCount;
                            var deleterowid = 'SRCH_stock_deleterow/' + tablerowCount;
                            var row_id = "SRCH_stock_tr_" + tablerowCount;
                            var temp_textbox_id = "SRCH_stocktemp_id" + tablerowCount;
                            if (stock_details[q][1] == null) {
                                stock_details[q][1] = "SELECT";
                            }
                            if (stock_details[q][2] == null) {
                                stock_details[q][2] = "";
                            }
                            if (stock_details[q][3] == null) {
                                stock_details[q][3] = "";
                            }
                            var appendrow = '<tr class="active" id=' + row_id + '><td style="max-width: 250px">' + stock_details[q][1] + '</td><td style="max-width: 250px">' + stock_details[q][2] + '</td><td style="max-width: 250px">' + stock_details[q][3] + '</td></tr>';
                            $('#SRCH_stockusage_table tr:last').after(appendrow);
                        }
                    }
                    $('#SRCH_searchbtn').attr('disabled', 'disabled');
                    $('.preloader').hide();
                }
                else
                {
                    show_msgbox("REPORT SUBMISSION SEARCH",error_message[2],"error",false);
                    $('#SRCH_entryform').hide();
                    $('#backtotop').hide();
                    $('#SRCH_searchbtn').attr('disabled','disabled');
                    $('.preloader').hide();
                }
          }
        }
        var option="SEARCH_DATA";
        xmlhttp.open("GET","DB_PERMITS_ENTRY.php?option="+option+"&team="+selectedteam+"&date="+selecteddate);
        xmlhttp.send();
//        $('#SRCH_entryform').show();
    });

    var teamname=[];
    var empname=[];
    var machinerytype=[];
    var fittingitems=[];
    var materialitems=[];
    var jobtype=[];
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $('.preloader').hide();
            var value_array=JSON.parse(xmlhttp.responseText);
            teamname=value_array[0];
            machinerytype=value_array[1];
            fittingitems=value_array[2];
            materialitems=value_array[3];
            jobtype=value_array[4];
            //TEAM
            var team='<option>SELECT</option>';
            for (var i=0;i<teamname.length;i++) {
                team += '<option value="' + teamname[i] + '">' + teamname[i] + '</option>';
            }
            $('#SRCH_tr_tb_team').html(team);
            $('#SRCH_team_lb_team').html(team);
            //MACHINERY_TYPE
            var machinery_type='<option>SELECT</option>';
            for (var i=0;i<machinerytype.length;i++) {
                machinery_type += '<option value="' + machinerytype[i] + '">' + machinerytype[i] + '</option>';
            }
            $('#SRCH_machinery_type').html(machinery_type);

            //FITTING ITEM
            var fitting_item='<option>SELECT</option>';
            for (var i=0;i<fittingitems.length;i++) {
                fitting_item += '<option value="' + fittingitems[i] + '">' + fittingitems[i] + '</option>';
            }
            $('#SRCH_fitting_items').html(fitting_item);

            //MATERIAL ITEM
            var material_item='<option>SELECT</option>';
            for (var i=0;i<materialitems.length;i++) {
                material_item += '<option value="' + materialitems[i] + '">' + materialitems[i] + '</option>';
            }
            $('#SRCH_material_items').html(material_item);
            //TYPE OF JOB
            var typeofjob='';
            for(var i=0;i<jobtype.length;i++)
            {
                var chkboxid=jobtype[i][0].replace(" ","");
                typeofjob+='<label class="checkbox-inline no_indent"><input type="checkbox" id ="'+chkboxid+'" name="jobtype[]" value="' + jobtype[i][1] + '">' + jobtype[i][0]+'</label>'
            }
            $('#type_of_job').append(typeofjob).show();
        }
    }
    var option="COMMON_DATA";
    xmlhttp.open("GET","DB_PERMITS_ENTRY.php?option="+option);
    xmlhttp.send();
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
//SITE VISIT ADD,DELETE AND UPDATE FUNCTION
    $('#SRCH_sv_btn_update').hide();
//CLICK EVENT FOR SITEVISIT ADD BUTTON
    $('#SRCH_sv_btn_addrow').click(function(){
        var desingnation=$('#SRCH_sv_txt_designation').val();
        var name=$('#SRCH_sv_txt_name').val();
        var start=$('#SRCH_sv_txt_start').val();
        var end=$('#SRCH_sv_txt_end').val();
        var remark=$('#SRCH_sv_txt_remark').val();
        if((desingnation!='') && (name!='') && (start!='') && (end!='')&& (remark!=''))
        {
            var sv_tablerowcount=$('#SRCH_sv_tbl tr').length;
            var sv_editid='SRCH_sv_editrow/'+sv_tablerowcount;
            var sv_deleterowid='SRCH_sv_deleterow/'+sv_tablerowcount;
            var sv_row_id="SRCH_sv_tr_"+sv_tablerowcount;
            var temp_textbox_id="SRCH_svtemp_id"+sv_tablerowcount;
            var appendrow='<tr class="active" id='+sv_row_id+'><td><div class="col-md-1"><span style="display:block;" class="glyphicon glyphicon-edit SRCH_sv_editbutton" id='+sv_editid+'></span></div><div class="col-md-1"><span style="display:block;" class="glyphicon glyphicon-trash SRCH_sv_removebutton" id='+sv_deleterowid+'></div><input type="hidden" class="form-control"  style="max-width: 100px" id='+temp_textbox_id+'></td><td>'+desingnation+'</td><td>'+name+'</td><td>'+start+'</td><td>'+end+'</td><td>'+remark+'</td></tr>';
            $('#SRCH_sv_tbl tr:last').after(appendrow);
            sv_formclear()
            $('#SRCH_sv_btn_addrow').attr('disabled','disabled');
            $('#SRCH_sv_btn_update').hide();
        }
    });
// FUNCTION FOR SITEVISIT FORM CLEAR
    function sv_formclear(){
        $('#SRCH_sv_txt_designation').val('');
        $('#SRCH_sv_txt_name').val('');
        $('#SRCH_sv_txt_start').val('');
        $('#SRCH_sv_txt_end').val('');
        $('#SRCH_sv_txt_remark').val('').height('22');
    }
// CLICK EVENT FOR SITEVISIT REMOVE BUTTON
    $(document).on("click",'.SRCH_sv_removebutton', function (){
        $(this).closest('tr').remove();
        sv_formclear()
        $('#SRCH_sv_btn_update').hide();
        $('#SRCH_sv_btn_addrow').attr('disabled','disabled').show();
        return false;
    });
//CLICK EVENT FOR SITEVISIT EDIT BUTTON
    $(document).on("click",'.SRCH_sv_editbutton', function (){
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#SRCH_sv_rowid').val(rowid);
        $('#SRCH_sv_btn_update').show();
        $('#SRCH_sv_btn_addrow').hide();
        $('#SRCH_sv_btn_update').attr("disabled", "disabled");
        $('#SRCH_sv_tbl tr:eq('+rowid+')').each(function () {
            var $tds = $(this).find('td'),
                sv_desgn = $tds.eq(1).text(),
                sv_name = $tds.eq(2).text(),
                sv_start = $tds.eq(3).text(),
                sv_end = $tds.eq(4).text(),
                sv_remarks = $tds.eq(5).text();
            $('#SRCH_sv_txt_designation').val(sv_desgn);
            $('#SRCH_sv_txt_name').val(sv_name);
            $('#SRCH_sv_txt_start').val(sv_start);
            $('#SRCH_sv_txt_end').val(sv_end);
            $('#SRCH_sv_txt_remark').val(sv_remarks);
        });
    });
// CLICK EVENT FORM SITEVISIT UPDATE ROW
    $(document).on("click",'.SRCH_sv_btn_updaterow', function (){
        var sv_desgn=$('#SRCH_sv_txt_designation').val();
        var sv_name=$('#SRCH_sv_txt_name').val();
        var sv_start=$('#SRCH_sv_txt_start').val();
        var sv_end=$('#SRCH_sv_txt_end').val();
        var sv_remarks=$('#SRCH_sv_txt_remark').val();
        var sv_rowid=$('#SRCH_sv_rowid').val();
        var objUser = {"sv_id":sv_rowid,"sv_desgn":sv_desgn,"sv_name":sv_name,"sv_start":sv_start,"sv_end":sv_end,"sv_remark":sv_remarks};
        var objKeys = ["","sv_desgn","sv_name", "sv_start", "sv_end","sv_remark"];
        $('#SRCH_sv_tr_' + objUser.sv_id + ' td').each(function(i) {
            $(this).text(objUser[objKeys[i]]);
        });
        $('#SRCH_sv_btn_addrow').show();
        $('#SRCH_sv_btn_update').hide();
        $('#SRCH_sv_btn_update').attr("disabled", "disabled");
        sv_formclear();
    });
// FORM VALIDATION FOR BUTTONS
    $(document).on("change",'.form-validation', function (){
        var sv_design=$('#SRCH_sv_txt_designation').val();
        var sv_name=$('#SRCH_sv_txt_name').val();
        var sv_start=$('#SRCH_sv_txt_start').val();
        var sv_end=$('#SRCH_sv_txt_end').val();
        var sv_remarks=$('#SRCH_sv_txt_remark').val();
        if(sv_design!='' && sv_name!='' && sv_start!='' && sv_end!='')
        {
            $("#SRCH_sv_btn_addrow").removeAttr("disabled");
            $("#SRCH_sv_btn_update").removeAttr("disabled");
        }
        else
        {
            $("#SRCH_sv_btn_addrow").attr("disabled", "disabled");
            $('#SRCH_sv_btn_update').attr("disabled", "disabled");
        }
    });
////SITE VISIT ADD,DELETE AND UPDATE FUNCTION
//MACHINERY/EQUIPMENT TRANSFER ADD,DELETE,UPDATE ROW FUNCTION
    $('#SRCH_mtransfer_update').hide();

//CLICK EVENT FOR MACHINERY ADD BUTTON
    $('#SRCH_mtransfer_addrow').click(function(){
        var mtranser_from=$('#SRCH_mtranser_from').val();
        var mtransfer_item=$('#SRCH_mtransfer_item').val();
        var mtransfer_to=$('#SRCH_mtransfer_to').val();
        var mtransfer_remark=$('#SRCH_mtransfer_remark').val();
        if((mtranser_from!="") && (mtransfer_item!='') && (mtransfer_to!=''))
        {
            var mtransfertablerowcount=$('#SRCH_mtransfer_table tr').length;
            var mtransfereditid='SRCH_mtransfereditrow/'+mtransfertablerowcount;
            var mtransferdeleterowid='SRCH_mtransferdeleterow/'+mtransfertablerowcount;
            var mtransfer_row_id="SRCH_mtranser_tr_"+mtransfertablerowcount;
            var temp_textbox_id="SRCH_mtransfertemp_id"+mtransfertablerowcount;
            var appendrow='<tr class="active" id='+mtransfer_row_id+'><td><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRCH_mtransfereditbutton" id='+mtransfereditid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRCH_mtransferremovebutton"  id='+mtransferdeleterowid+'></div><input type="hidden" class="form-control"  style="max-width: 100px" id='+temp_textbox_id+'></td><td>'+mtranser_from+'</td><td>'+mtransfer_item+'</td><td>'+mtransfer_to+'</td><td>'+mtransfer_remark+'</td></tr>';
            $('#SRCH_mtransfer_table tr:last').after(appendrow);
            mtransferformclear()
            $('#SRCH_mtransfer_addrow').attr('disabled','disabled');
            $('#SRCH_mtransfer_update').hide();
        }
    });
// FUNCTION FOR MACHINERY FORM CLEAR
    function mtransferformclear(){
        $('#SRCH_mtranser_from').val('');
        $('#SRCH_mtransfer_item').val('');
        $('#SRCH_mtransfer_to').val('');
        $('#SRCH_mtransfer_remark').val('').height('22');
    }
// CLICK EVENT FOR MACHINERY REMOVE BUTTON
    $(document).on("click",'.SRCH_mtransferremovebutton', function (){
        $(this).closest('tr').remove();
        mtransferformclear()
        $('#SRCH_mtransfer_update').hide();
        $('#SRCH_mtransfer_addrow').attr("disabled", "disabled").show();
        return false;
    });
//CLICK EVENT FOR MACHINERY EDIT BUTTON
    $(document).on("click",'.SRCH_mtransfereditbutton', function (){
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#SRCH_mtransfer_rowid').val(rowid);
        $('#SRCH_mtransfer_update').show();
        $('#SRCH_mtransfer_addrow').hide();
        $('#SRCH_mtransfer_update').attr("disabled", "disabled");
        $('#SRCH_mtransfer_table tr:eq('+rowid+')').each(function () {
            var $tds = $(this).find('td'),
                mtranser_from = $tds.eq(1).text(),
                mtransfer_item = $tds.eq(2).text(),
                mtransfer_to = $tds.eq(3).text(),
                mtransfer_remark = $tds.eq(4).text();
            $('#SRCH_mtranser_from').val(mtranser_from);
            $('#SRCH_mtransfer_item').val(mtransfer_item);
            $('#SRCH_mtransfer_to').val(mtransfer_to);
            $('#SRCH_mtransfer_remark').val(mtransfer_remark);
        });
    });
// CLICK EVENT FORM MACHINER UPDATE ROW
    $(document).on("click",'.SRCH_mtransfer_updaterow', function (){
        var mtranser_from=$('#SRCH_mtranser_from').val();
        var mtransfer_item=$('#SRCH_mtransfer_item').val();
        var mtransfer_to=$('#SRCH_mtransfer_to').val();
        var mtransfer_remark=$('#SRCH_mtransfer_remark').val();
        var mtransfer_rowid=$('#SRCH_mtransfer_rowid').val();
        var objUser = {"mtransferid":mtransfer_rowid,"mtranserfrom":mtranser_from,"mtransferitem":mtransfer_item,"mtransferto":mtransfer_to,"mtransferremark":mtransfer_remark};
        var objKeys = ["","mtranserfrom", "mtransferitem", "mtransferto","mtransferremark"];

        $('#SRCH_mtranser_tr_' + objUser.mtransferid + ' td').each(function(i) {
            $(this).text(objUser[objKeys[i]]);
        });
        $('#SRCH_mtransfer_addrow').show();
        $('#SRCH_mtransfer_update').hide();
        $('#SRCH_mtransfer_update').attr("disabled", "disabled");
        mtransferformclear();
    });

// FORM VALIDATION FOR BUTTONS
    $(document).on("change",'.SRCH_form-validation', function (){
        var mtranser_from=$('#SRCH_mtranser_from').val();
        var mtransfer_item=$('#SRCH_mtransfer_item').val();
        var mtransfer_to=$('#SRCH_mtransfer_to').val();
        if(mtranser_from!="" && mtransfer_item!="" && mtransfer_to!="")
        {
            $("#SRCH_mtransfer_addrow").removeAttr("disabled");
            $("#SRCH_mtransfer_update").removeAttr("disabled");

        }
        else
        {
            $("#SRCH_mtransfer_addrow").attr("disabled", "disabled");
            $('#SRCH_mtransfer_update').attr("disabled", "disabled");
        }
    });
//END OF MACHINERY/EQUIPMENT TRANSFER ADD,DELETE,UPDATE ROW FUNCTION
//RENTAL MACHINERY/EQUIPMENT TRANSFER ADD,DELETE,UPDATE FUNCTION//
    $('#SRCH_rentalmechinery_updaterow').hide();
    $('#SRCH_rentalmechinery_addrow').click(function(){
        var rental_lorryno=$('#SRCH_rental_lorryno').val();
        var rental_throwearthstore=$('#SRCH_rental_throwearthstore').val();
        var rental_throwearthoutside=$('#SRCH_rental_throwearthoutside').val();
        var rental_start=$('#SRCH_rental_start').val();
        var rental_end=$('#SRCH_rental_end').val();
        var rental_remarks=$('#SRCH_rental_remarks').val();
        if((rental_lorryno!="") && (rental_throwearthstore!='') && (rental_throwearthoutside!='') && (rental_start!='') && (rental_end!=''))
        {
            var rentaltablerowcount=$('#SRCH_rental_table tr').length;
            var rentaleditid='SRCH_machineryeditrow/'+rentaltablerowcount;
            var rentaldeleterowid='SRCH_machinerydeleterow/'+rentaltablerowcount;
            var rental_row_id="SRCH_rental_tr_"+rentaltablerowcount;
            var temp_textbox_id="SRCH_rentaltemp_id"+rentaltablerowcount;
            var appendrow='<tr class="active" id='+rental_row_id+'><td><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRCH_rentalmechinery_editbutton" id='+rentaleditid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRCH_rental_machineryremovebutton"  id='+rentaldeleterowid+'></div><input type="hidden" class="form-control"  style="max-width: 100px" id='+temp_textbox_id+'></td><TD>'+rental_lorryno+'</td><td>'+rental_throwearthstore+'</td><td>'+rental_throwearthoutside+'</td><td>'+rental_start+'</td><td>'+rental_end+'</td><td>'+rental_remarks+'</td>';
            $('#SRCH_rental_table tr:last').after(appendrow);
            $('#SRCH_rentalmechinery_addrow').attr("disabled", "disabled");
            Rentalmachineryclear()
        }
    });
// CLICK EVENT FOR RENTAL MACHINERY REMOVE BUTTON
    $(document).on("click",'.SRCH_rental_machineryremovebutton', function (){
        $(this).closest('tr').remove();
        Rentalmachineryclear()
        $('#SRCH_rentalmechinery_addrow').attr("disabled", "disabled").show();
        $('#SRCH_rentalmechinery_updaterow').hide();
        return false;
    });
//CLICK EVENT FOR RENTAL MACHINERY EDIT BUTTON
    $(document).on("click",'.SRCH_rentalmechinery_editbutton', function (){
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#SRCH_rentalmechinery_id').val(rowid);
        $('#SRCH_rentalmechinery_updaterow').show();
        $('#SRCH_rentalmechinery_addrow').hide();
        $('#SRCH_rentalmechinery_updaterow').attr("disabled", "disabled");
        $('#SRCH_rental_table tr:eq('+rowid+')').each(function () {
            var $tds = $(this).find('td'),
                lorry_no = $tds.eq(1).text(),
                store = $tds.eq(2).text(),
                outside = $tds.eq(3).text(),
                start = $tds.eq(4).text(),
                end = $tds.eq(5).text(),
                remarks = $tds.eq(6).text();
            $('#SRCH_rental_lorryno').val(lorry_no).show();
            $('#SRCH_rental_throwearthstore').val(store);
            $('#SRCH_rental_throwearthoutside').val(outside);
            $('#SRCH_rental_start').val(start);
            $('#SRCH_rental_end').val(end);
            $('#SRCH_rental_remarks').val(remarks);
        });
    });
    // CLICK EVENT FORM RENTAL MACHINERY UPDATE ROW
    $(document).on("click",'.SRCH_rentalmechineryupdaterow', function (){
        var rental_lorryno=$('#SRCH_rental_lorryno').val();
        var rental_throwearthstore=$('#SRCH_rental_throwearthstore').val();
        var rental_throwearthoutside=$('#SRCH_rental_throwearthoutside').val();
        var rental_start=$('#SRCH_rental_start').val();
        var rental_end=$('#SRCH_rental_end').val();
        var rental_remarks=$('#SRCH_rental_remarks').val();
        var rental_rowid=$('#SRCH_rentalmechinery_id').val();
        var objUser = {"rentalrowid":rental_rowid,"lorryno":rental_lorryno,"throwstore":rental_throwearthstore,"throwoutside":rental_throwearthoutside,"start":rental_start,"end":rental_end,"remarks":rental_remarks};
        var objKeys = ["","lorryno", "throwstore", "throwoutside","start","end","remarks"];

        $('#SRCH_rental_tr_' + objUser.rentalrowid + ' td').each(function(i) {
            $(this).text(objUser[objKeys[i]]);
        });
        $('#SRCH_rentalmechinery_addrow').show();
        $('#SRCH_rentalmechinery_updaterow').hide();
        Rentalmachineryclear()
    });
    function Rentalmachineryclear()
    {
        $('#SRCH_rental_lorryno').val('').show();
        $('#SRCH_rental_throwearthstore').val('');
        $('#SRCH_rental_throwearthoutside').val('');
        $('#SRCH_rental_start').val('');
        $('#SRCH_rental_end').val('');
        $('#SRCH_rental_remarks').val('').height('22');
    }
    // FORM VALIDATION FOR BUTTONS
    $(document).on("change",'.SRCH_rentalform-validation', function (){

        var rental_lorryno=$('#SRCH_rental_lorryno').val();
        var rental_throwearthstore=$('#SRCH_rental_throwearthstore').val();
        var rental_throwearthoutside=$('#SRCH_rental_throwearthoutside').val();
        var rental_start=$('#SRCH_rental_start').val();
        var rental_end=$('#SRCH_rental_end').val();
        if(rental_lorryno!="" && rental_throwearthstore!="" && rental_throwearthoutside!="" && rental_start!='' && rental_end!='')
        {
            $("#SRCH_rentalmechinery_addrow").removeAttr("disabled");
            $("#SRCH_rentalmechinery_updaterow").removeAttr("disabled");

        }
        else
        {
            $("#SRCH_rentalmechinery_addrow").attr("disabled", "disabled");
            $('#SRCH_rentalmechinery_updaterow').attr("disabled", "disabled");
        }
    });
//RENTAL MACHINERY USAGE ADD,DELETE AND UPDATE FUNCTION
//CLICK EVENT FOR MACHINERY ADD BUTTON
    $('#SRCH_machinery_update').hide();
    $('#SRCH_machinery_addrow').click(function(){
        var machinerytype=$('#SRCH_machinery_type').val();
        var machinery_start=$('#SRCH_machinery_start').val();
        var machinery_end=$('#SRCH_machinery_end').val();
        var machinery_remarks=$('#SRCH_machinery_remarks').val();
        if((machinerytype!="Select") && (machinery_start!='') && (machinery_end!=''))
        {
            var machinerytablerowcount=$('#SRCH_machinery_table tr').length;
            var machineryeditid='SRCH_machineryeditrow/'+machinerytablerowcount;
            var machinerydeleterowid='SRCH_machinerydeleterow/'+machinerytablerowcount;
            var machinery_row_id="SRCH_machinery_tr_"+machinerytablerowcount;
            var temp_textbox_id="SRCH_machinerytemp_id"+machinerytablerowcount;
            var appendrow='<tr class="active" id='+machinery_row_id+'><td><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRCH_machineryeditbutton" id='+machineryeditid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRCH_machineryremovebutton"  id='+machinerydeleterowid+'><div><input type="hidden" class="form-control"  style="max-width: 100px" id='+temp_textbox_id+'></td><td>'+machinerytype+'</td><td>'+machinery_start+'</td><td>'+machinery_end+'</td><td>'+machinery_remarks+'</td></tr>';
            $('#SRCH_machinery_table tr:last').after(appendrow);
            machineryformclear()
            $('#SRCH_machinery_addrow').attr('disabled','disabled');
            $('#SRCH_machinery_update').hide();
        }
    });

    // FUNCTION FOR MACHINERY FORM CLEAR
    function machineryformclear(){
        $('#SRCH_machinery_type').val('SELECT').show();
        $('#SRCH_machinery_start').val('');
        $('#SRCH_machinery_end').val('');
        $('#SRCH_machinery_remarks').val('').height('22');
    }
    // CLICK EVENT FOR MACHINERY REMOVE BUTTON
    $(document).on("click",'.SRCH_machineryremovebutton', function (){
        $(this).closest('tr').remove();
        machineryformclear()
        $('#SRCH_machinery_addrow').attr('disabled','disabled').show();
        $('#SRCH_machinery_update').hide();
        return false;
    });
    //CLICK EVENT FOR MACHINERY EDIT BUTTON
    $(document).on("click",'.SRCH_machineryeditbutton', function (){
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#SRCH_machinery_rowid').val(rowid);
        $('#SRCH_machinery_update').show();
        $('#SRCH_machinery_addrow').hide();
        $('#SRCH_machinery_update').attr("disabled", "disabled");
        $('#SRCH_machinery_table tr:eq('+rowid+')').each(function () {
            var $tds = $(this).find('td'),
                machinery_type = $tds.eq(1).text(),
                machinery_start = $tds.eq(2).text(),
                machinery_end = $tds.eq(3).text(),
                machinery_remarks = $tds.eq(4).text();
            $('#SRCH_machinery_type').val(machinery_type);
            $('#SRCH_machinery_start').val(machinery_start);
            $('#SRCH_machinery_end').val(machinery_end);
            $('#SRCH_machinery_remarks').val(machinery_remarks);
        });
    });
    // CLICK EVENT FORM MACHINER UPDATE ROW
    $(document).on("click",'.SRCH_machinery_updaterow', function (){
        var machinery_type=$('#SRCH_machinery_type').val();
        var machinery_start=$('#SRCH_machinery_start').val();
        var machinery_end=$('#SRCH_machinery_end').val();
        var machinery_remarks=$('#SRCH_machinery_remarks').val();
        var machinery_rowid=$('#SRCH_machinery_rowid').val();
        var objUser = {"machineryid":machinery_rowid,"machinerytype":machinery_type,"machinerystart":machinery_start,"machineryend":machinery_end,"machineryremark":machinery_remarks};
        var objKeys = ["","machinerytype", "machinerystart", "machineryend","machineryremark"];

        $('#SRCH_machinery_tr_' + objUser.machineryid + ' td').each(function(i) {
            $(this).text(objUser[objKeys[i]]);
        });
        $('#SRCH_machinery_addrow').show();
        $('#SRCH_machinery_update').hide();
        $('#SRCH_machinery_update').attr("disabled", "disabled");
        machineryformclear();
    });

    // FORM VALIDATION FOR BUTTONS
    $(document).on("change",'.SRCH_machineryform-validation', function (){

        var machinery_type=$('#SRCH_machinery_type').val();
        var machinery_start=$('#SRCH_machinery_start').val();
        var machinery_end=$('#SRCH_machinery_end').val();
        if(machinery_type!="Select" && machinery_start!="" && machinery_end!="")
        {
            $("#SRCH_machinery_addrow").removeAttr("disabled");
            $("#SRCH_machinery_update").removeAttr("disabled");

        }
        else
        {
            $("#SRCH_machinery_addrow").attr("disabled", "disabled");
            $('#SRCH_machinery_update').attr("disabled", "disabled");
        }
    });

//END OF MACHINERY USAGE ADD,DELETE AND UPDATE FUNCTION
    //EQUIPMENT USAGE ADD,DELETE AND UPDATE FUNCTION
    $('#SRCH_equipment_update').hide();
//CLICK EVENT FOR MACHINERY ADD BUTTON
    $('#SRCH_equipment_addrow').click(function(){
        var equipment_aircompressor=$('#SRCH_equipment_aircompressor').val();
        var equipment_lorryno=$('#SRCH_equipment_lorryno').val();
        var equipment_start=$('#SRCH_equipment_start').val();
        var equipment_end=$('#SRCH_equipment_end').val();
        var equipment_remark=$('#SRCH_equipment_remark').val();
        if((equipment_aircompressor!="") && (equipment_lorryno!='') && (equipment_start!='') && (equipment_end!='') && (equipment_remark!=''))
        {
            var equipmenttablerowcount=$('#SRCH_equipment_table tr').length;
            var equipmenteditid='SRCH_equipmenteditrow/'+equipmenttablerowcount;
            var equipmentdeleterowid='SRCH_equipementdeleterow/'+equipmenttablerowcount;
            var equipment_row_id="SRCH_equipment_tr_"+equipmenttablerowcount;
            var temp_textbox_id="SRCH_equipmenttemp_id"+equipmenttablerowcount;
            var appendrow='<tr class="active" id='+equipment_row_id+'><td><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRCH_equipmenteditbutton" id='+equipmenteditid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRCH_equipmentremovebutton" id='+equipmentdeleterowid+'></div><input type="hidden" class="form-control"  style="max-width: 100px" id='+temp_textbox_id+'></td><td>'+equipment_aircompressor+'</td><td>'+equipment_lorryno+'</td><td>'+equipment_start+'</td><td>'+equipment_end+'</td><td>'+equipment_remark+'</td></tr>';
            $('#SRCH_equipment_table tr:last').after(appendrow);
            equipmentformclear()
            $('#SRCH_equipment_addrow').attr('disabled','disabled');
            $('#SRCH_equipment_update').hide();
        }
    });

// FUNCTION FOR MACHINERY FORM CLEAR
    function equipmentformclear(){
        $('#SRCH_equipment_aircompressor').val('');
        $('#SRCH_equipment_lorryno').val('');
        $('#SRCH_mtransfer_to').val('');
        $('#SRCH_equipment_end').val('');
        $('#SRCH_equipment_remark').val('').height('22');
    }

// CLICK EVENT FOR MACHINERY REMOVE BUTTON
    $(document).on("click",'.SRCH_equipmentremovebutton', function (){
        $(this).closest('tr').remove();
        equipmentformclear()
        $('#SRCH_equipment_addrow').attr('disabled','disabled').show();
        $('#SRCH_equipment_update').hide();
        return false;
    });
//CLICK EVENT FOR MACHINERY EDIT BUTTON
    $(document).on("click",'.SRCH_equipmenteditbutton', function (){
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#SRCH_equipment_rowid').val(rowid);
        $('#SRCH_equipment_update').show();
        $('#SRCH_equipment_addrow').hide();
        $('#SRCH_equipment_update').attr("disabled", "disabled");
        $('#SRCH_equipment_table tr:eq('+rowid+')').each(function () {
            var $tds = $(this).find('td'),
                equipment_aircompressor = $tds.eq(1).text(),
                equipment_lorryno = $tds.eq(2).text(),
                equipment_start = $tds.eq(3).text(),
                equipment_end = $tds.eq(4).text(),
                equipment_remark = $tds.eq(5).text();
            $('#SRCH_equipment_aircompressor').val(equipment_aircompressor);
            $('#SRCH_equipment_lorryno').val(equipment_lorryno);
            $('#SRCH_equipment_start').val(equipment_start);
            $('#SRCH_equipment_end').val(equipment_end);
            $('#SRCH_equipment_remark').val(equipment_remark);
        });
    });
// CLICK EVENT FORM MACHINER UPDATE ROW
    $(document).on("click",'.SRCH_equipment_updaterow', function (){
        var equipment_aircompressor=$('#SRCH_equipment_aircompressor').val();
        var equipment_lorryno=$('#SRCH_equipment_lorryno').val();
        var equipment_start=$('#SRCH_equipment_start').val();
        var equipment_end=$('#SRCH_equipment_end').val();
        var equipment_remark=$('#SRCH_equipment_remark').val();
        var equipment_rowid=$('#SRCH_equipment_rowid').val();
        var objUser = {"equipmentrowid":equipment_rowid,"equipmentaircompressor":equipment_aircompressor,"equipmentlorryno":equipment_lorryno,"equipmentstart":equipment_start,"equipmentend":equipment_end,"equipmentremark":equipment_remark};
        var objKeys = ["","equipmentaircompressor", "equipmentlorryno", "equipmentstart","equipmentend","equipmentremark"];

        $('#SRCH_equipment_tr_' + objUser.equipmentrowid + ' td').each(function(i) {
            $(this).text(objUser[objKeys[i]]);
        });
        $('#SRCH_equipment_addrow').show();
        $('#SRCH_equipment_update').hide();
        $('#SRCH_equipment_update').attr("disabled", "disabled");
        equipmentformclear();
    });

// FORM VALIDATION FOR BUTTONS
    $(document).on("change",'.SRCH_equipmentform-validation', function (){
        var equipment_aircompressor=$('#SRCH_equipment_aircompressor').val();
        var equipment_lorryno=$('#SRCH_equipment_lorryno').val();
        var equipment_start=$('#SRCH_equipment_start').val();
        var equipment_end=$('#SRCH_equipment_end').val();
        if(equipment_aircompressor!="" && equipment_lorryno!="" && equipment_start!="" && equipment_end!='')
        {
            $("#SRCH_equipment_addrow").removeAttr("disabled");
            $("#SRCH_equipment_update").removeAttr("disabled");
        }
        else
        {
            $("#SRCH_equipment_addrow").attr("disabled", "disabled");
            $('#SRCH_equipment_update').attr("disabled", "disabled");
        }
    });
    //END OF EQUIPMENT USAGE ADD,DELETE AND UPDATE FUNCTION
//FITTING  USAGE TABLE ADD FUNCTION//
    //*****ADD NEW ROW********//
    $('#SRCH_fitting_updaterow').hide();
    $(document).on("click",'#SRCH_fitting_addrow', function (){
        var items=$('#SRCH_fitting_items').val();
        var size=$('#SRCH_fitting_size').val();
        var qty=$('#SRCH_fitting_quantity').val();
        var remarks=$('#SRCH_fitting_remarks').val();
        if((items!="Select") && (size!='') && (qty!=''))
        {
            var tablerowCount=$('#SRCH_fitting_table tr').length;
            var editid='SRCH_fitting_editrow/'+tablerowCount;
            var deleterowid='SRCH_fitting_deleterow/'+tablerowCount;
            var row_id="SRCH_fitting_tr_"+tablerowCount;
            var temp_textbox_id="SRCH_fittingtemp_id"+tablerowCount;
            var appendrow='<tr  class="active" id='+row_id+'><td><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRCH_fitting_editbutton" id='+editid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRCH_fitting_removebutton"  id='+deleterowid+'></div><input type="hidden" class="form-control"  style="max-width: 100px" id='+temp_textbox_id+'></td><td>'+items+'</td><td>'+size+'</td><td>'+qty+'</td><td>'+remarks+'</td></tr>';
            $('#SRCH_fitting_table tr:last').after(appendrow);
            $("#SRCH_fitting_addrow").attr("disabled", "disabled");
            fittingformclear();
        }
    });
    //**********DELETE ROW*************//
    $(document).on("click",'.SRCH_fitting_removebutton', function (){
        $('#SRCH_fitting_updaterow').hide();
        $(this).closest('tr').remove();
        $("#SRCH_fitting_addrow").attr("disabled", "disabled").show();
        fittingformclear();
        return false;
    });
    //**********EDIT ROW**************//
    $(document).on("click",'.SRCH_fitting_editbutton', function (){
        $('#SRCH_fitting_addrow').hide();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#SRCH_fitting_id').val(rowid);
        $('#SRCH_fitting_table tr:eq('+rowid+')').each(function () {
            var $tds = $(this).find('td'),
                items = $tds.eq(1).text(),
                size = $tds.eq(2).text(),
                quantity = $tds.eq(3).text(),
                remark = $tds.eq(4).text();
            $('#SRCH_fitting_items').val(items);
            $('#SRCH_fitting_size').val(size);
            $('#SRCH_fitting_quantity').val(quantity);
            $('#SRCH_fitting_remarks').val(remark);
            $('#SRCH_fitting_updaterow').show();
        });
    });
    //********UPDATE ROW****************//
    $(document).on("click",'.SRCH_fittingupdaterow   ', function (){
        var items=$('#SRCH_fitting_items').val();
        var size=$('#SRCH_fitting_size').val();
        var qty=$('#SRCH_fitting_quantity').val();
        var remarks=$('#SRCH_fitting_remarks').val();
        var rowid=$('#SRCH_fitting_id').val();
        var objUser = {"id":rowid,"items":items,"size":size,"quantity":qty,"remark":remarks};
        var objKeys = ["","items", "size", "quantity","remark"];

        $('#SRCH_fitting_tr_' + objUser.id + ' td').each(function(i) {
            $(this).text(objUser[objKeys[i]]);
        });
        $('#SRCH_fitting_addrow').show();
        $('#SRCH_fitting_updaterow').hide();
        fittingformclear();
    });
    //*****FITTING FORM CLEAR**********//
    function fittingformclear()
    {
        $('#SRCH_fitting_items').val('SELECT').show();
        $('#SRCH_fitting_size').val('');
        $('#SRCH_fitting_quantity').val('');
        $('#SRCH_fitting_remarks').val('').height('22');
        $("#SRCH_fitting_addrow").attr("disabled", "disabled");
        $('#SRCH_fitting_updaterow').attr("disabled", "disabled");
    }

    $(document).on("change",'.SRCH_fittingform-validation', function (){
        var items=$('#SRCH_fitting_items').val();
        var size=$('#SRCH_fitting_size').val();
        var qty=$('#SRCH_fitting_quantity').val();
        if(items!="Select" && size!="" && qty!="")
        {
            $("#SRCH_fitting_addrow").removeAttr("disabled");
            $("#SRCH_fitting_updaterow").removeAttr("disabled");
        }
        else
        {
            $("#SRCH_fitting_addrow").attr("disabled", "disabled");
            $('#SRCH_fitting_updaterow').attr("disabled", "disabled");
        }
    });
//END OF FITTING  USAGE TABLE ADD FUNCTION//
//MATERIAL USAGE ADD,DELETE AND UPDATE ROW FUNCTION//
    $('#SRCH_material_updaterow').hide();
    $(document).on("click",'#SRCH_material_addrow', function (){
        var items=$('#SRCH_material_items').val();
        var receipt=$('#SRCH_material_receipt').val();
        var qty=$('#SRCH_material_quantity').val();
        if((items!="Select") && (receipt!='') && (qty!=''))
        {
            var tablerowCount=$('#SRCH_material_table tr').length;
            var editid='SRCH_material_editrow/'+tablerowCount;
            var deleterowid='SRCH_material_deleterow/'+tablerowCount;
            var row_id="SRCH_material_tr_"+tablerowCount;
            var temp_textbox_id="SRCH_materialtemp_id"+tablerowCount;
            var appendrow='<tr class="active" id='+row_id+'><td><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit SRCH_material_editbutton" id='+editid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash SRCH_material_removebutton"  id='+deleterowid+'></div><input type="hidden" class="form-control"  style="max-width: 100px" id='+temp_textbox_id+'></td><td>'+items+'</td><td>'+receipt+'</td><td>'+qty+'</td></tr>';
            $('#SRCH_material_table tr:last').after(appendrow);
            $("#SRCH_material_addrow").attr("disabled","disabled");
            MATERIALformclear();
        }
    });
    //**********DELETE ROW*************//
    $(document).on("click",'.SRCH_material_removebutton', function (){
        $('#SRCH_material_updaterow').hide();
        $(this).closest('tr').remove();
        $("#SRCH_material_addrow").attr("disabled","disabled").show();
        MATERIALformclear();
        return false;
    });
    // **********EDIT ROW**************//
    $(document).on("click",'.SRCH_material_editbutton', function (){
        $('#SRCH_material_addrow').hide();
        var id = this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#SRCH_material_id').val(rowid);
        $('#SRCH_material_table tr:eq('+rowid+')').each(function () {
            var $tds = $(this).find('td'),
                items = $tds.eq(1).text(),
                receipt = $tds.eq(2).text(),
                quantity = $tds.eq(3).text();
            $('#SRCH_material_items').val(items);
            $('#SRCH_material_receipt').val(receipt);
            $('#SRCH_material_quantity').val(quantity);
        });
        $('#SRCH_material_updaterow').show();
    });
    //********UPDATE ROW****************//
    $(document).on("click",'.SRCH_materialupdaterow', function (){
        var material_items=$('#SRCH_material_items').val();
        var material_receipt=$('#SRCH_material_receipt').val();
        var material_quantity=$('#SRCH_material_quantity').val();
        var material_id=$('#SRCH_material_id').val();
        var objUser = {"materialid":material_id,"materialitems":material_items,"materialreceipt":material_receipt,"materialquantity":material_quantity};
        var objKeys = ["","materialitems", "materialreceipt", "materialquantity"];
        $('#SRCH_material_tr_' + objUser.materialid + ' td').each(function(i) {
            $(this).text(objUser[objKeys[i]]);
        });
        $('#SRCH_SRCH_material_addrow').show();
        $('#SRCH_material_updaterow').hide();
        MATERIALformclear();
    });
    //*****MATERIAL FORM CLEAR**********//
    function MATERIALformclear()
    {
        $('#SRCH_material_items').val('SELECT').show();
        $('#SRCH_material_receipt').val('');
        $('#SRCH_material_quantity').val('');
    }

    $(document).on("change",'.SRCH_materialform-validation', function (){
        var items=$('#SRCH_material_items').val();
        var receipt=$('#SRCH_material_receipt').val();
        var qty=$('#SRCH_material_quantity').val();
        if(items!="Select" && receipt!="" && qty!="")
        {
            $("#SRCH_material_addrow").removeAttr("disabled");
            $("#SRCH_material_updaterow").removeAttr("disabled");
        }
        else
        {
            $("#SRCH_material_addrow").attr("disabled", "disabled");
            $('#SRCH_material_updaterow').attr("disabled", "disabled");
        }
    });
//END OF MATERIAL USAGE ADD,DELETE AND UPDATE ROW FUNCTION//
    //FINAL SUBMIT FUNCTION
    $(document).on("click",'#SRCH_Final_Update', function (){
        $('.preloader').show();
        //MATERIAL DETAILS TABLE RECORDS
        var metrialrefTab = document.getElementById("SRCH_material_table");
        var materialusage_array=[];
        for ( var i = 1; row = metrialrefTab.rows[i]; i++ )
        {
            var materialrowid=$('#SRCH_materialtemp_id'+i).val();
            if(materialrowid==""){materialrowid=" "}
            row = metrialrefTab.rows[i];
            var materialinnerarray=[];
            materialinnerarray.push(materialrowid);
            for ( var j = 1; col = row.cells[j]; j++ ) {
                materialinnerarray.push(col.firstChild.nodeValue);
            }
            materialusage_array.push(materialinnerarray) ;
        }
        if(materialusage_array.length==0)
        {
            materialusage_array='null';
        }
        //FITTING DETAILS TABLE RECORDS
        var fittingrefTab = document.getElementById("SRCH_fitting_table");
        var fittingusage_array=[];
        for ( var i = 1; row = fittingrefTab.rows[i]; i++ ) {
            row = fittingrefTab.rows[i];
            var fittingrowid=$('#SRCH_fittingtemp_id'+i).val();
            var fittinginnerarray=[];
            if(fittingrowid==""){fittingrowid=" "}
            fittinginnerarray.push(fittingrowid);
            for ( var j = 1; col = row.cells[j]; j++ ) {
                fittinginnerarray.push(col.firstChild.nodeValue);
            }
            fittingusage_array.push(fittinginnerarray) ;
        }
        if(fittingusage_array.length==0)
        {
            fittingusage_array='null';
        }
        //EQUIPMENT DETAILS TABLE RECORDS
        var equipmentrefTab = document.getElementById("SRCH_equipment_table");
        var equipmentusage_array=[];
        for ( var i = 1; row = equipmentrefTab.rows[i]; i++ ) {
            var equipmentrowid=$('#SRCH_equipmenttemp_id'+i).val();
            row = equipmentrefTab.rows[i];
            var equipmentinnerarray=[];
            if(equipmentrowid==""){equipmentrowid=" "}
            equipmentinnerarray.push(equipmentrowid);
            for ( var j = 1; col = row.cells[j]; j++ ) {
                equipmentinnerarray.push(col.firstChild.nodeValue);
            }
            equipmentusage_array.push(equipmentinnerarray) ;
        }
        if(equipmentusage_array.length==0)
        {
            equipmentusage_array='null';
        }
        //RENTAL MACHINERY TABLE RECORDS
        var rentalrefTab = document.getElementById("SRCH_rental_table");

        var rentalmechinery_array=[];
        for ( var i = 1; row = rentalrefTab.rows[i]; i++ ) {
            var rentalrowid=$('#SRCH_rentaltemp_id'+i).val();
            row = rentalrefTab.rows[i];
            var rentalinnerarray=[];
            if(rentalrowid==""){rentalrowid=" "}
            rentalinnerarray.push(rentalrowid);
            for ( var j = 1; col = row.cells[j]; j++ ) {
                rentalinnerarray.push(col.firstChild.nodeValue);
            }
            rentalmechinery_array.push(rentalinnerarray) ;
        }
        if(rentalmechinery_array.length==0)
        {
            rentalmechinery_array='null';
        }
        //MACHINERY USAGE TABLE RECORDS
        var mechineryrefTab = document.getElementById("SRCH_machinery_table");
        var mechineryusage_array=[];
        for ( var i = 1; row = mechineryrefTab.rows[i]; i++ ) {
            var mecineryrowid=$('#SRCH_machinerytemp_id'+i).val();
            row = mechineryrefTab.rows[i];
            var machineryinnerarray=[];
            if(mecineryrowid==""){mecineryrowid=" "}
            machineryinnerarray.push(mecineryrowid);
            for ( var j = 1; col = row.cells[j]; j++ ) {
                machineryinnerarray.push(col.firstChild.nodeValue);
            }
            mechineryusage_array.push(machineryinnerarray) ;
        }
        if(mechineryusage_array.length==0)
        {
            mechineryusage_array='null';
        }
        //MACHINERY / EQUIPMENT TRANSFER TABLE RECORDS
        var mech_eqp_refTab = document.getElementById("SRCH_mtransfer_table");
        var mech_eqp_array=[];
        for ( var i = 1; row = mech_eqp_refTab.rows[i]; i++ ) {
            var mech_eqprowid=$('#SRCH_mtransfertemp_id'+i).val();
            row = mech_eqp_refTab.rows[i];
            var mach_eqp_innerarray=[];
            if(mech_eqprowid==""){mech_eqprowid=" "}
            mach_eqp_innerarray.push(mech_eqprowid);
            for ( var j = 1; col = row.cells[j]; j++ ) {
                mach_eqp_innerarray.push(col.firstChild.nodeValue);
            }
            mech_eqp_array.push(mach_eqp_innerarray) ;
        }
        if(mech_eqp_array.length==0)
        {
            mech_eqp_array='null';
        }
        //SITE VISIT TABLE RECORDS
        var SV_refTab = document.getElementById("SRCH_sv_tbl");
        var SV_array=[];
        for ( var i = 1; row = SV_refTab.rows[i]; i++ ) {
            var svrowid=$('#SRCH_svtemp_id'+i).val();
            row = SV_refTab.rows[i];
            var SV_innerarray=[];
            if(svrowid==""){svrowid=" "}
            SV_innerarray.push(svrowid);
            for ( var j = 1; col = row.cells[j]; j++ ) {
                SV_innerarray.push(col.firstChild.nodeValue);
            }
            SV_array.push(SV_innerarray) ;
        }
        if(SV_array.length==0)
        {
            SV_array='null';
        }
        //EMPPLOYEE TABLE RECORDS
        var employeerowcount=$('#SRCH_Employee_table tr').length;
        for(var j=0;j<employeerowcount-1;j++)
        {
            var autoid=j+1;
            var emp_id=$('#SRCH_Emp_id'+autoid).val();
            if(emp_id==employee_id)
            {
                var emp_name=$('#SRCH_Emp_name'+autoid).val();
                var emp_start=$('#SRCH_Emp_starttime'+autoid).val();
                var emp_end=$('#SRCH_Emp_endtime'+autoid).val();
                var emp_ot=$('#SRCH_Emp_ot'+autoid).val();
                var emp_remark=$('#SRCH_Emp_remark'+autoid).val();
                var Employeeid;var Start;var End;var OT;var Remark;
                Employeeid=emp_id;Start=emp_start;End=emp_end;OT=emp_ot;Remark=emp_remark;
            }
        }
        var EmployeeDetails=[Employeeid,Start,End,OT,Remark];
        var formelement =$('#SRCH_entryform').serialize();
        var dataURL = canvas.toDataURL();
        var arraydata={"Option":"UpdateForm","SRCH_MaterialDetails": materialusage_array,"SRCH_FittingDetails":fittingusage_array,"SRCH_EquipmentDetails":equipmentusage_array,"SRCH_RentalDetails":rentalmechinery_array,"SRCH_MechineryUsageDetails":mechineryusage_array,"SRCH_MechEqptransfer":mech_eqp_array,"SRCH_SiteVisit":SV_array,"SRCH_EmployeeDetails":EmployeeDetails,"imgData": dataURL};
        data=formelement + '&' + $.param(arraydata);
        $.ajax({
            type: "POST",
            url: "DB_PERMITS_ENTRY.php",
            data:data,
            success: function(msg){
                $('.preloader').hide();
                if(msg==1)
                {
                show_msgbox("REPORT SUBMISSION SEARCH",error_message[0],"success",false)
                    $('#SRCH_entryform').hide();
                    $('#SRCH_team_lb_team').val('SELECT').show();
                    $('#SRCH_search_date').val('').show();
                }
                else if(msg==0)
                {
                show_msgbox("REPORT SUBMISSION SEARCH",error_message[1],"error",false)
                }
                else
                {
                    show_msgbox("REPORT SUBMISSION SEARCH",msg,"error",false)
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
            <h3 class="panel-title">REPORT SUBMISSION SEARCH</h3>
        </div>
        <div class="panel-body">
            <div class="row form-group">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    <label>DATE</label>
                    <div class="input-group">
                        <input id="SRCH_search_date" name="SRCH_search_date" type="text" class="date-picker datemandtry form-control" placeholder="Date"/>
                        <label for="SRCH_search_date" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span>
                        </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="col-md-3" style="padding-top:25px">
                        <button type="button" id="SRCH_searchbtn" class="btn btn-info" disabled>SEARCH</button>
                    </div>
                </div>
            </div>
            <form id="SRCH_entryform" class="form-horizontal" hidden>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">TEAM REPORT</h3>
                    </div>
                    <div class="panel-body">
                        <!--        <form id="teamreport" class="form-horizontal">-->
                        <fieldset disabled>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label id="tr_lbl_location">LOCATION</label>
                                    <input type="text" class="form-control txtlen" id="SRCH_tr_txt_location" name="SRCH_tr_txt_location" placeholder="Location">
                                </div>
                                <div class="col-md-3">
                                    <label  id="tr_lbl_contactno">CONTRACT NO <em>*</em></label>
                                    <input type="text" class="form-control" id="SRCH_tr_txt_contractno" name="SRCH_tr_txt_contractno" placeholder="Contract No">
                                </div>
                                <div class="col-md-3 selectContainer">
                                    <label id="tr_lbl_team">TEAM</label>
                                    <input type="text" class="form-control" id="SRCH_tr_tb_team" disabled name="SRCH_tr_tb_team" placeholder="Team" />
                                </div>
                                <div class="col-md-2">
                                    <label id="tr_lbl_date">DATE <em>*</em></label>
                                    <div class="input-group">
                                        <input id="SRCH_tr_txt_date" name="SRCH_tr_txt_date" type="text" class="form-control" placeholder="Date"/>
                                        <label for="SRCH_tr_txt_date" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label id="SRCH_tr_lbl_weather">WEATHER</label>
                                    <input type="text" class="form-control txtlen" id="SRCH_tr_txt_weather" name="SRCH_tr_txt_weather" placeholder="Weather">
                                </div>
                                <div class="col-md-2">
                                    <label id="SRCH_tr_lbl_reachsite">FROM</label>
                                    <input type="text" class="form-control time-picker" id="SRCH_tr_txt_wftime" name="SRCH_tr_txt_wftime" placeholder="Weather Time">
                                </div>
                                <div class="col-md-2">
                                    <label id="tr_lbl_leavesite">TO</label>
                                    <input type="text" class="form-control time-picker" id="SRCH_tr_txt_wttime" name="SRCH_tr_txt_wttime" placeholder="Weather Time">
                                </div>
                                <div class="col-md-2">
                                    <label id="tr_lbl_reachsite">REACH SITE</label>
                                    <input type="text" class="form-control time-picker" id="SRCH_tr_txt_reachsite"  name="SRCH_tr_txt_reachsite" placeholder="Time">
                                </div>
                                <div class="col-md-2">
                                    <label id="tr_lbl_leavesite">LEAVE SITE</label>
                                    <input type="text" class="form-control time-picker" id="SRCH_tr_txt_leavesite" name="SRCH_tr_txt_leavesite" placeholder="Time">
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
                        <fieldset disabled hidden>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label for="SRCH_mt_lbl_topic" id="SRCH_mt_lbl_topic">TOPIC</label>
                                    <input type="text" class="form-control meetingform-validation" id="SRCH_mt_lb_topic" name="SRCH_mt_lb_topic" placeholder="Topic">
                                </div>
                                <div class="col-md-8">
                                    <label for="SRCH_mt_lbl_remark" id="SRCH_mt_lbl_remark">REMARKS</label>
                                    <textarea class="form-control meetingform-validation" rows="1" id="SRCH_mt_ta_remark" name="SRCH_mt_ta_remark" placeholder="Remarks"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" name="SRCH_mt_rowid" id="SRCH_mt_rowid" class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRCH_mt_btn_addrow" class="btn btn-info" disabled>ADD</button>
                                <button type="button" id="SRCH_mt_btn_update" class="btn btn-info SRCH_mt_btn_updaterow" disabled>UPDATE</button>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRCH_meeting_table">
                                <thead>
                                <tr class="active">
                                    <!--                    <th width="300px">EDIT/REMOVE</th>-->
                                    <th width="500">TOPIC</th>
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
                        <fieldset disabled>
                            <div class="table-responsive">
                                <table class="table" border="1" style="border: #ddd;">
                                    <tr>
                                        <td class="jobthl">
                                            <label style="padding-bottom: 15px"></label>
                                            <label id="SRCH_tr_lbl_pipelaid">PIPE LAID</label>
                                        </td>
                                        <td colspan="2" style="text-align: center">
                                            <div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="SRCH_jd_chk_road" name="SRCH_jd_chk_road"> ROAD
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="2" style="text-align: center">
                                            <div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="SRCH_jd_chk_contc" name="SRCH_jd_chk_contc"> CONC
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="2" style="text-align: center">
                                            <div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="SRCH_jd_chk_truf" name="SRCH_jd_chk_truf"> TURF
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="jobthl" style="border-top: 1px solid white;">
                                            <label style="padding-bottom: 15px"> </label>
                                            <label id="SRCH_tr_lbl_location">SIZE/LENGTH</label>
                                        </td>
                                        <td class="jobtd" style="border-top: 1px solid white;">
                                            <div>
                                                <label>M</label>
                                                <input type="text" class="form-control decimal size" id="SRCH_jd_chk_roadm" name="SRCH_jd_chk_roadm" placeholder="M">
                                            </div>
                                        </td>
                                        <td style="border-top: 1px solid white;">
                                            <div>
                                                <label>MM</label>
                                                <input type="text" class="form-control decimal size" id="SRCH_jd_chk_roadmm"  name="SRCH_jd_chk_roadmm" placeholder="MM">
                                            </div>
                                        </td>
                                        <td class="jobtd" style="border-top: 1px solid white;">
                                            <div>
                                                <label>M</label>
                                                <input type="text" class="form-control decimal size" id="SRCH_jd_chk_concm"   name="SRCH_jd_chk_concm" placeholder="M">
                                            </div>
                                        </td>
                                        <td style="border-top: 1px solid white;">
                                            <div>
                                                <label>MM</label>
                                                <input type="text" class="form-control decimal size" id="SRCH_jd_chk_concmm" name="SRCH_jd_chk_concmm" placeholder="MM">
                                            </div>
                                        </td>
                                        <td class="jobtd" style="border-top: 1px solid white;">
                                            <div>
                                                <label>M</label>
                                                <input type="text" class="form-control decimal size" id="SRCH_jd_chk_trufm" name="SRCH_jd_chk_trufm" placeholder="M">
                                            </div>
                                        </td>
                                        <td class="jobthr" style="border-top: 1px solid white;">
                                            <div>
                                                <label>MM</label>
                                                <input type="text" class="form-control decimal size" id="SRCH_jd_chk_trufmm" name="SRCH_jd_chk_trufmm" placeholder="MM">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </fieldset>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label for="SRCH_jd_txt_testing" id="SRCH_jd_lbl_testing">PIPE TESTING</label>
                                <input type="text" class="form-control txtlen" id="SRCH_jd_txt_pipetesting" name="SRCH_jd_txt_pipetesting" placeholder="Pipe Testing" disabled>
                            </div>
                            <div class="col-md-3">
                                <label for="SRCH_jd_txt_start" id="SRCH_jd_lbl_start" >START (PRESSURE)</label>
                                <input type="text" class="form-control quantity"  id="SRCH_jd_txt_start" name="SRCH_jd_txt_start" placeholder="Start Pressure" disabled>
                            </div>
                            <div class="col-md-3">
                                <label for="SRCH_jd_txt_end" id="SRCH_jd_lbl_end">END (PRESSURE)</label>
                                <input type="text" class="form-control quantity" id="SRCH_jd_txt_end" name="SRCH_jd_txt_end" placeholder="End Pressure" disabled>
                            </div>
                            <div class="col-md-3">
                                <label for="SRCH_jd_ta_remark" id="SRCH_jd_lbl_remark">REMARKS</label>
                                <textarea class="form-control" rows="1" id="SRCH_jd_ta_remark" name="SRCH_jd_ta_remark" placeholder="Remarks" readonly></textarea>
                            </div>
                        </div>
<!--                        </fieldset>-->
                        <!--        </form>-->
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">EMPLOYEE REPORT DETAILS</h3>
                    </div>
                    <div class="panel-body">
                        <!--        <form>-->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRCH_Employee_table" readonly name="SRCH_Employee_table">
                                <thead>
                                <tr class="active">
                                    <th>NAME</th>
                                    <th>START TIME</th>
                                    <th>END TIME</th>
                                    <th>OT</th>
                                    <th>REMARKS</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <!--        </form>-->
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">SITE VISIT</h3>
                    </div>
                    <div class="panel-body">
                        <!--        <form class="form-horizontal">-->
                        <fieldset hidden disabled>
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label>DESIGNATION</label>
                                    <input class="form-control form-validation txtlen" id="SRCH_sv_txt_designation" placeholder="Designation"/>
                                </div>
                                <div class="col-md-3">
                                    <label>NAME</label>
                                    <input class="form-control form-validation txtlen" id="SRCH_sv_txt_name" placeholder="Name"/>
                                </div>

                                <div class="col-md-1">
                                    <label>START</label>
                                    <input type="text" class="form-control form-validation time-picker" id="SRCH_sv_txt_start" placeholder="Time">
                                </div>
                                <div class="col-md-1">
                                    <label>END</label>
                                    <input type="text" class="form-control form-validation time-picker" id="SRCH_sv_txt_end" placeholder="Time">
                                </div>
                                <div class="col-md-4">
                                    <label>REMARKS</label>
                                    <textarea class="form-control form-validation"  rows="1" id="SRCH_sv_txt_remark" placeholder="Remarks"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" id="SRCH_sv_rowid" name="sv_rowid" class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRCH_sv_btn_addrow" class="btn btn-info" disabled>ADD</button>
                                <button type="button" id="SRCH_sv_btn_update" class="btn btn-info SRCH_sv_btn_updaterow" disabled>UPDATE</button>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRCH_sv_tbl">
                                <thead>
                                <tr class="active">
                                    <!--                <th>Edit Remove</th>-->
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
                        <!--        </form>-->
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">MACHINERY / EQUIPMENT TRANSFER</h3>
                    </div>
                    <div class="panel-body">
                        <!--        <form class="form-horizontal">-->
                        <fieldset hidden disabled>
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label>FROM (LORRY NO)</label>
                                    <input type="text" class="form-control SRCH_form-validation quantity" id="SRCH_mtranser_from" name="SRCH_mtranser_from" placeholder="From (Lorry No)">
                                </div>
                                <div class="col-md-3">
                                    <label>ITEM</label>
                                    <input type="text" class="form-control SRCH_form-validation txtlen" id="SRCH_mtransfer_item" name="SRCH_mtransfer_item" placeholder="Item">
                                </div>

                                <div class="col-md-3">
                                    <label>TO (LORRY NO)</label>
                                    <input type="text" class="form-control SRCH_form-validation quantity" id="SRCH_mtransfer_to"  name="SRCH_mtransfer_to" placeholder="To (Lorry No)">
                                </div>

                                <div class="col-md-3">
                                    <label>REMARK</label>
                                    <textarea class="form-control SRCH_form-validation" id="SRCH_mtransfer_remark"  rows="1" name="SRCH_mtransfer_remark" placeholder="Remarks"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" id="SRCH_mtransfer_rowid" name="SRCH_mtransfer_rowid" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRCH_mtransfer_addrow" class="btn btn-info" disabled>ADD</button>
                                <button type="button" id="SRCH_mtransfer_update" class="btn btn-info SRCH_mtransfer_updaterow" disabled>UPDATE</button>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRCH_mtransfer_table" name="SRCH_mtransfer_table">
                                <thead>
                                <tr class="active">
                                    <!--                <th>Edit Remove</th>-->
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
                        <!--        </form>-->
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">MACHINERY USAGE</h3>
                    </div>
                    <div class="panel-body">
                        <!--        <form class="form-horizontal">-->
                        <fieldset hidden disabled>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label>MACHINERY TYPE</label>
                                    <select class="form-control SRCH_machineryform-validation" id="SRCH_machinery_type" name="SRCH_machinery_type">
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>START</label>
                                    <input type="text" class="form-control SRCH_machineryform-validation time-picker"  id="SRCH_machinery_start" name="SRCH_machinery_start" placeholder="Time">
                                </div>

                                <div class="col-md-2">
                                    <label>END</label>
                                    <input type="text" class="form-control SRCH_machineryform-validation time-picker"  id="SRCH_machinery_end"  name="SRCH_machinery_end" placeholder="Time">
                                </div>

                                <div class="col-md-4">
                                    <label>REMARKS</label>
                                    <textarea class="form-control SRCH_machineryform-validation" id="SRCH_machinery_remarks" rows="1" name="SRCH_machinery_remarks" placeholder="Remarks"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">

                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" id="SRCH_machinery_rowid" name="SRCH_machinery_rowid" class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRCH_machinery_addrow" class="btn btn-info" disabled>ADD</button>
                                <button type="button" id="SRCH_machinery_update" class="btn btn-info SRCH_machinery_updaterow" disabled>UPDATE</button>

                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRCH_machinery_table" name="SRCH_machinery_table">
                                <thead>
                                <tr class="active">
                                    <!--                <th>Edit Remove</th>-->
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
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">RENTAL MACHINERY</h3>
                    </div>
                    <div class="panel-body">
                        <!--        <form class="form-horizontal">-->
                        <fieldset hidden disabled>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label>LORRY NUMBER</label>
                                    <input type="text" class="form-control SRCH_rentalform-validation quantity lorryno" id="SRCH_rental_lorryno" name="SRCH_rental_lorryno" placeholder="Lorry Name">
                                </div>
                                <div class="col-md-4">
                                    <label>THROW EARTH(STORE)</label>
                                    <input type="text" class="form-control SRCH_rentalform-validation decimal size" id="SRCH_rental_throwearthstore" name="SRCH_rental_throwearthstore" placeholder="Throw Earth(Store)">
                                </div>

                                <div class="col-md-4">
                                    <label>THROW EARTH(OUTSIDE)</label>
                                    <input type="text" class="form-control SRCH_rentalform-validation decimal size" id="SRCH_rental_throwearthoutside" name="SRCH_rental_throwearthoutside" placeholder="Throwe Earth(Outside)">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-2">
                                    <label>START TIME</label>
                                    <input type="text" class="form-control SRCH_rentalform-validation time-picker" id="SRCH_rental_start" name="SRCH_rental_start" placeholder="Time">
                                </div>

                                <div class="col-md-2">
                                    <label>END TIME</label>
                                    <input type="text" class="form-control SRCH_rentalform-validation  time-picker" id="SRCH_rental_end"  name="SRCH_rental_end" placeholder="Time">
                                </div>

                                <div class="col-md-4">
                                    <label>REMARK</label>
                                    <textarea class="form-control SRCH_rentalform-validation" id="SRCH_rental_remarks" rows="1" name="SRCH_rental_remarks" placeholder="Remarks"></textarea>
                                    <input type="hidden" class="form-control" id="SRCH_rentalmechinery_id" name="SRCH_rentalmechinery_id">
                                </div>
                            </div>

                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRCH_rentalmechinery_addrow" class="btn btn-info" disabled>ADD</button>
                                <button type="button" id="SRCH_rentalmechinery_updaterow" class="btn btn-info SRCH_rentalmechineryupdaterow" disabled>UPDATE</button>

                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRCH_rental_table">
                                <thead>
                                <tr class="active">
                                    <!--                <th>Edit Remove</th>-->
                                    <th>LORRY NO</th>
                                    <th>THROW EARTH(STORE)</th>
                                    <th>THROW EARTH(OUTSIDE)</th>
                                    <th>START TIME</th>
                                    <th>END TIME</th>
                                    <th>REMAKRS</th>
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
                        <h3 class="panel-title">EQUIPMENT USAGE</h3>
                    </div>
                    <div class="panel-body">
                        <!--        <form class="form-horizontal">-->
                        <fieldset hidden disabled>
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label>AIR-COMPRESSOR</label>
                                    <input type="text" class="form-control SRCH_equipmentform-validation txtlen "  id="SRCH_equipment_aircompressor" name="SRCH_equipment_aircompressor" placeholder="Air-Compressor">
                                </div>
                                <div class="col-md-3">
                                    <label>LORRY NO(TRANSPORT)</label>
                                    <input type="text" class="form-control SRCH_equipmentform-validation quantity lorryno" id="SRCH_equipment_lorryno" name="SRCH_equipment_lorryno" placeholder="Lorry No(Transport)">
                                </div>
                                <div class="col-md-1">
                                    <label>START</label>
                                    <input type="text" class="form-control SRCH_equipmentform-validation time-picker" id="SRCH_equipment_start"  name="SRCH_equipment_start" placeholder="Time">
                                </div>
                                <div class="col-md-1">
                                    <label>END</label>
                                    <input type="text" class="form-control SRCH_equipmentform-validation time-picker" id="SRCH_equipment_end"  name="SRCH_equipment_end" placeholder="Time">
                                </div>
                                <div class="col-md-4">
                                    <label>REMARK</label>
                                    <textarea class="form-control SRCH_equipmentform-validation" rows="1" id="SRCH_equipment_remark"  name="SRCH_equipment_remark" placeholder="Remarks"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" id="SRCH_equipment_rowid" name="SRCH_equipment_rowid" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRCH_equipment_addrow" class="btn btn-info" disabled>ADD</button>
                                <button type="button" id="SRCH_equipment_update" class="btn btn-info SRCH_equipment_updaterow" disabled>UPDATE</button>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRCH_equipment_table" name="SRCH_equipment_table">
                                <thead>
                                <tr class="active">
                                    <!--                <th>Edit Remove</th>-->
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
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">FITTINGS USAGE</h3>
                    </div>
                    <div class="panel-body">
                        <!--        <form class="form-horizontal">-->
                        <fieldset hidden disabled>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label>ITEMS</label>
                                    <select class="form-control SRCH_fittingform-validation" id="SRCH_fitting_items" name="SRCH_fitting_items" placeholder="Items">
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>SIZE</label>
                                    <input type="text" class="form-control SRCH_fittingform-validation decimal size" id="SRCH_fitting_size" name="SRCH_fitting_size" placeholder="MM">
                                </div>
                                <div class="col-md-2">
                                    <label>QUANTITY</label>
                                    <input type="text" class="form-control SRCH_fittingform-validation decimal size" id="SRCH_fitting_quantity" name="SRCH_fitting_quantity" placeholder="Quantity">
                                </div>
                                <div class="col-md-4">
                                    <label>REMARKS</label>
                                    <textarea class="form-control SRCH_fittingform-validation" rows="1" id="SRCH_fitting_remarks" name="SRCH_fitting_remarks" placeholder="Remarks"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" class="form-control" id="SRCH_fitting_id" name="SRCH_fitting_id">
                                </div>
                            </div>
                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRCH_fitting_addrow" class="btn btn-info" disabled>ADD</button>
                                <button type="button" id="SRCH_fitting_updaterow" class="btn btn-info  SRCH_fittingupdaterow" disabled>UPDATE</button>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRCH_fitting_table">
                                <thead>
                                <tr class="active">
                                    <!--                <th>Edit Remove</th>-->
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
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">MATERIAL USAGE</h3>
                    </div>
                    <div class="panel-body">
                        <!--        <form class="form-horizontal">-->
                        <fieldset hidden disabled>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label>ITEMS</label>
                                    <select class="form-control SRCH_materialform-validation" id="SRCH_material_items" name="SRCH_material_items" placeholder="Items">
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>RECEIPT NO</label>
                                    <input type="text" class="form-control SRCH_materialform-validation quantity" id="SRCH_material_receipt" name="SRCH_material_receipt" placeholder="Receipt No">
                                </div>

                                <div class="col-md-4">
                                    <label>QUANTITY</label>
                                    <input type="text" class="form-control SRCH_materialform-validation decimal" id="SRCH_material_quantity" name="SRCH_material_quantity" placeholder="Quantity">
                                    <input type="hidden" class="form-control" id="SRCH_material_id" name="SRCH_material_id">
                                </div>
                            </div>
                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRCH_material_addrow" class="btn btn-info" disabled>ADD</button>
                                <button type="button" id="SRCH_material_updaterow" class="btn btn-info SRCH_materialupdaterow" disabled>UPDATE</button>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRCH_material_table">
                                <thead>
                                <tr class="active">
                                    <!--                    <th>Edit Remove</th>-->
                                    <th>ITEMS</th>
                                    <th>RECEIPT NO</th>
                                    <th>QTY (KG/BAGS/LTR/PCS)</th>
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
                        <fieldset hidden disabled>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label>ITEM NO</label>
                                    <select class="form-control SRCH_stockusageform-validation" id="SRCH_stock_itemno" name="SRCH_stock_itemno">
                                        <option>SELECT</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>ITEM NAME</label>
                                    <input type="text" class="form-control SRCH_stockusageform-validation" id="SRCH_stock_itemname" name="SRCH_stock_itemname" placeholder="Item Name" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label>QUANTITY</label>
                                    <input type="text" class="form-control SRCH_stockusageform-validation decimal size" id="SRCH_stock_quantity" name="SRCH_stock_quantity" placeholder="Quantity">
                                    <input type="hidden" class="form-control" id="SRCH_stock_id" name="SRCH_stock_id">
                                </div>
                            </div>
                            <div class="col-lg-9 col-lg-offset-11">
                                <button type="button" id="SRCH_stock_addrow" class="btn btn-info" >ADD</button>
                                <button type="button" id="SRCH_stock_updaterow" class="btn btn-info SRCH_stockupdaterow" >UPDATE</button>
                            </div>
                        </fieldset>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="SRCH_stockusage_table">
                                <thead>
                                <tr class="active">
<!--                                    <th>EDIT/REMOVE</th>-->
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
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">DRAWING AREA</h3>
                    </div>
                    <div class="panel-body">
                        <div id="appendimg">

                        </div>
                        <!--        </form>-->
                    </div>
                </div>
            </form>
        </div>
        <div class="form-group-sm" id="backtotop">
            <ul class="nav-pills">
                <li class="pull-right"><a href="#top">Back to top</a></li>
            </ul>
        </div>
        <script src="../PAINT/JS/customShape1.js"> </script>
    </div>
</div>
</body>
</html>