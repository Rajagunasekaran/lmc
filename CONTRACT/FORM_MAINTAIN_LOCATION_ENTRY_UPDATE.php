<?php
include "../FOLDERMENU.php";
?>
<style>
    .textarea{
        resize: none;overflow: hidden;
    }
</style>
<script>
    $(document).ready(function() {
        ML_func_searchtable()
        $("textarea").autogrow({vertical: true, horizontal: true});
        $(".amountonly").doValidation({rule: 'numbersonly', prop: {realpart: 5, imaginary: 2}});
        $(".numonly").doValidation({rule: 'numbersonly', prop: {realpart: 5}});
        $(".date-picker").datepicker({
            dateFormat: "dd-mm-yy",
            changeYear: true,
            changeMonth: true
        });
        var error_message;
        $('.preloader').show();
        $.ajax({
            type: "POST",
            url: "DB_MAINTAIN_LOCATION_ENTRY_UPDATE.php",
            data:{"option":"INITIAL_DATA"},
            success: function(res) {
                $('.preloader').hide();
                var response=JSON.parse(res);
                var jobstatus=response[0];
                var verificationstatus=response[1];
                var referencnowithprefix=response[2];
                var referenceno=response[3];
                var contract_no=response[4];
                error_message=response[5];
                var job_status='<option>SELECT</option>';
                for (var i=0;i<jobstatus.length;i++) {
                    job_status += '<option value="' + jobstatus[i][0] + '">' + jobstatus[i][0] + '</option>';
                }
                $('#ML_lb_jobstatus').html(job_status);
                var verification_status='<option>SELECT</option>';
                for (var i=0;i<verificationstatus.length;i++) {
                    verification_status += '<option value="' + verificationstatus[i][0] + '">' + verificationstatus[i][0] + '</option>';
                }
                $('#ML_lb_verificaitonstatus').html(verification_status);
                var contractno='<option>SELECT</option>';
                for (var i=0;i<contract_no.length;i++) {
                    contractno += '<option value="' + contract_no[i]+ '">' + contract_no[i]+ '</option>';
                }
                $('#ML_lb_contractno').html(contractno);
                $('#ML_tb_referenceno').val(referencnowithprefix);
                $('#referencenohidden').val(referenceno);
            },
            error: function (data) {
                alert('error in getting' + JSON.stringify(data));
            }
        });
        $('#ML_btn_additem').click(function() {
            $('#mainlocationform').show();
            $('#ML_btn').show();
            $('#ML_btn_additem').hide();
            $('#ML_btn_save').val('SAVE');
        });
    //SEARCH TABLE
        var resultarray=[];
        function ML_func_searchtable(){
            $('.preloader').show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    resultarray = JSON.parse(xmlhttp.responseText);
                    $('.preloader').hide();
                    if (resultarray.length>0) {
                        var ML_UPD_table_header = '<table id="ML_tbl_htmltable" border="1"  width="1300" cellspacing="0" class="srcresult"><thead  bgcolor="#6495ed" style="color:white;text-align:center;"><tr><th>EDIT</th><th style="text-align:center;">REF NO</th><th width="350px" style="text-align:center;">LOCATION</th><th style="text-align:center;">RECIPIENT</th><th style="text-align:center;">CONTRACT</th><th style="text-align:center;">DATE ENTERED</th><th style="text-align:center;">DATE COMPLETED</th><th style="text-align:center;">DATE DRAFTBILL CREATED</th><th style="text-align:center;">JOB STATUS</th><th style="text-align:center;">DRAFTBILL STATUS</th><th style="text-align:center;">REMARK</th><th style="text-align:center;">USESTAMP</th><th>TIMESTAMP</th></tr></thead><tbody>';
                        for (var i = 0; i < resultarray.length; i++) {
                            var refno = resultarray[i].refno;
                            refno="LM/"+refno;
                            var location = resultarray[i].location;
                            var recipient = resultarray[i].receipant;
                            var contract = resultarray[i].contractno;
                            var dateentered = resultarray[i].dateifentered;
                            var datecompleted = resultarray[i].dateofcompleted;
                            var draftbillcreated = resultarray[i].verificationdate;
                            var jobstatus = resultarray[i].jobstatus;
                            var draftbillstatus = resultarray[i].verificationstatus;
                            var remark = resultarray[i].remarks;
                            var username = resultarray[i].userstamp;
                            var timestamp = resultarray[i].timestamp;
                            var rowid = resultarray[i].rowid;
                            ML_UPD_table_header += '<tr id=' + rowid + ' ><td><div class="col-lg-1"><span style="display: block;color:green" class="glyphicon glyphicon-edit  MLedit" id="MLedit_' + rowid + '"></span></div></td><td nowrap>' + refno + '</td><td>' + location + '</td><td> ' + recipient + '</td><td>' + contract + '</td><td nowrap>' + dateentered + '</td><td nowrap>' + datecompleted + '</td><td nowrap>' + draftbillcreated + '</td><td>' + jobstatus + '</td><td nowrap>' + draftbillstatus + '</td><td>' + remark + '</td><td>' + username + '</td><td nowrap>' + timestamp + '</td></tr>';
                        }
                        ML_UPD_table_header += '</tbody></table>';
                        $('section').html(ML_UPD_table_header);
                        $('#ML_tbl_htmltable').DataTable({
                            "aaSorting": [],
                            "pageLength": 10,
                            "sPaginationType": "full_numbers"
                        });
                        $('#ML_searchtable').show();
                    }
                }
            }
            var option="searchdata";
            xmlhttp.open("POST","DB_MAINTAIN_LOCATION_ENTRY_UPDATE.php?option="+option);
            xmlhttp.send();
        }
        //CANCEL BUTTON CLICK EVENT
        $('#ML_btn_cancel').click(function(){
            $('#mainlocationform').hide();
            $('#ML_btn').hide();
            $('#ML_btn_additem').show();
            $("#mainlocationform").find('input:text, input:password, input:file,textarea').val('');
            $("#mainlocationform").find('select').val('SELECT');
            $("#mainlocationform").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
        });
    //CLICK EVENT FOR SAVE AND UPDATE BUTTON
        $('#ML_btn_save').click(function(){
            $('.preloader').show();
            var buttonvalue=$('#ML_btn_save').val();
            var formElement = document.getElementById("maintainlocation");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    $('.preloader').hide();
                    var result = xmlhttp.responseText;
                    alert(result)
                    if (buttonvalue == 'SAVE') {
                        if(result==1)
                        {
                            show_msgbox("REPORT SUBMISSION ENTRY",error_message[0],"success",false);
                            ML_func_searchtable();
                            $('#mainlocationform').hide();
                            $('#ML_btn_additem').show();
                            $('#ML_btn').hide();
                            $("#mainlocationform").find('input:text, input:password, input:file,textarea').val('');
                            $("#mainlocationform").find('select').val('SELECT');
                            $("#mainlocationform").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
                        }
                        else
                        {
                            show_msgbox("REPORT SUBMISSION ENTRY",error_message[2],"success",false);
                        }
                    }
                    else
                    {
                        if(result==1)
                        {
                            show_msgbox("REPORT SUBMISSION ENTRY",error_message[1],"success",false);
                            ML_func_searchtable();
                            $('#mainlocationform').hide();
                            $('#ML_btn_additem').show();
                            $('#ML_btn').hide();
                            $("#mainlocationform").find('input:text, input:password, input:file,textarea').val('');
                            $("#mainlocationform").find('select').val('SELECT');
                            $("#mainlocationform").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
                        }
                        else
                        {
                            show_msgbox("REPORT SUBMISSION ENTRY",error_message[3],"success",false);
                        }
                    }

                }
            }
            var option="locationsaveupdate";
            xmlhttp.open("POST","DB_MAINTAIN_LOCATION_ENTRY_UPDATE.php?option="+option);
            xmlhttp.send(new FormData(formElement));
        });
        var tblerowid;
        $(document).on('click','.MLedit',function(){
            tblerowid=this.id.split('_')[1];
            $('#mainlocationform').show();
            $('#ML_btn').show();
            $('#ML_btn_additem').hide();
            $('#ML_btn_save').val('UPDATE');
            for(var j=0;j<resultarray.length;j++) {
                if (tblerowid == resultarray[j].rowid) {
                    var refno = resultarray[j].refno;
                    refno="LM/"+refno;
                    var location = resultarray[j].location;
                    var workorderno=resultarray[j].workorderno;
                    var recipient = resultarray[j].receipant;
                    var contract = resultarray[j].contractno;
                    var dateentered = resultarray[j].dateifentered;
                    var datecompleted = resultarray[j].dateofcompleted;
                    var amount = resultarray[j].amount;
                    var draftbillcreated = resultarray[j].verificationdate;
                    var jobstatus = resultarray[j].jobstatus;
                    var menworked = resultarray[j].noofworkmen;
                    var hoursworked = resultarray[j].workinday;
                    var manhours = resultarray[j].manhours;
                    var draftbillstatus = resultarray[j].verificationstatus;
                    var remark = resultarray[j].remarks;
                    $('#ML_tb_referenceno').val(refno);
                    $('#ML_lb_contractno').val(contract);
                    $('#ML_tb_workorderno').val(workorderno);
                    $('#ML_ta_locaiton').val(location);
                    $('#ML_lb_recipient').val(recipient);
                    $('#ML_tb_dateofentered').val(dateentered);
                    $('#ML_tb_dateofcompleted').val(datecompleted);
                    $('#ML_tb_amount').val(amount);
                    $('#ML_tb_workmen').val(menworked);
                    $('#ML_tb_hoursworked').val(hoursworked);
                    $('#ML_tb_manhours').val(manhours);
                    $('#ML_lb_jobstatus').val(jobstatus);
                    $('#ML_tb_dateofverification').val(draftbillcreated);
                    $('#ML_lb_verificaitonstatus').val(draftbillstatus);
                    $('#ML_ta_remark').val(remark);
                  }
            }
        });
    });
</script>
<body>
<form id="maintainlocation" name="formworkdone" class="form-horizontal">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">MAINTAIN LOCATION</h2>
            </div>
            <div class="panel-body">
                <div id="mainlocationform" hidden>
                <div class="form-group">
                    <label class="col-sm-3"  id="ML_contractno">CONTRACT NO</label>
                    <div class="col-sm-3">
                        <select id="ML_lb_contractno" name="ML_lb_contractno" class="form-control"></select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3"  id="ML_referenceno">OURE REF</label>
                    <div class="col-sm-3">
                        <input type="text" id="ML_tb_referenceno" name="ML_tb_referenceno" class="form-control" readonly/>
                        <input type="hidden" id="referencenohidden" name="referencenohidden"/>
                    </div>
                    <label class="col-sm-2"  id="ML_workorderno">WORK ORDER NO</label>
                    <div class="col-sm-3">
                        <input type="text" id="ML_tb_workorderno" name="ML_tb_workorderno" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3" id="ML_location">LOCATION</label>
                    <div class="col-sm-5">
                        <textarea id="ML_ta_locaiton" name="ML_ta_locaiton" class="form-control"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3"  id="ML_recipient">RECIPIENT</label>
                    <div class="col-sm-2">
                        <select id="ML_lb_recipient" name="ML_lb_recipient" class="form-control">
                            <option>SELECT</option>
                            <option>sasi</option>
                            <option>kala</option>
                            <option>sasikala</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3"  id="ML_dateofentered">DATE OF ENTERED</label>
                    <div class="col-sm-2">
                        <div class="input-group">
                        <input type="text" id="ML_tb_dateofentered" name="ML_tb_dateofentered" class="form-control date-picker datemandtry"/>
                        <label for="ML_tb_dateofentered" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3"  id="ML_dateofcompleted">DATE OF COMPLETED</label>
                    <div class="col-sm-2">
                        <div class="input-group">
                        <input type="text" id="ML_tb_dateofcompleted" name="ML_tb_dateofcompleted" class="form-control date-picker datemandtry"/>
                        <label for="ML_tb_dateofcompleted" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3"  id="ML_amount">AMOUNT</label>
                    <div class="col-sm-2">
                        <input type="text" id="ML_tb_amount" name="ML_tb_amount" class="form-control amountonly"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3"  id="ML_workmen">NO. OF WORKMEN</label>
                    <div class="col-sm-2">
                        <input type="text" id="ML_tb_workmen" name="ML_tb_workmen" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3"  id="ML_hoursworked">NO. OF HRS WORKED IN THE DAY</label>
                    <div class="col-sm-2">
                        <input type="text" id="ML_tb_hoursworked" name="ML_tb_hoursworked" class="form-control"/>
                    </div>
                    <label class="col-sm-2 col-lg-offset-1"  id="ML_manhours">MANHOURS</label>
                    <div class="col-sm-2">
                        <input type="text" id="ML_tb_manhours" name="ML_tb_manhours" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3"  id="ML_jobstatus">JOB STATUS</label>
                    <div class="col-sm-3">
                        <select id="ML_lb_jobstatus" name="ML_lb_jobstatus" class="form-control"></select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3"  id="ML_dateofverification">DATE OF VERIFICATION</label>
                    <div class="col-sm-2">
                        <div class="input-group">
                        <input type="text" id="ML_tb_dateofverification" name="ML_tb_dateofverification" class="form-control date-picker datemandtry"/>
                            <label for="ML_tb_dateofverification" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3"  id="ML_verificaitonstatus">VERIFICATION STATUS</label>
                    <div class="col-sm-2">
                        <select id="ML_lb_verificaitonstatus" name="ML_lb_verificaitonstatus" class="form-control"></select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3" id="ML_remark">REMARK</label>
                    <div class="col-sm-5">
                        <textarea id="ML_ta_remark" name="ML_ta_remark" class="form-control"></textarea>
                    </div>
                </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-2" style="padding-bottom:15px">
                        <input type="button" class="btn btn-info" name="ML_btn_additem" id="ML_btn_additem" value="ADD NEW">
                    </div>
                    <div class="col-lg-offset-10" style="padding-left:15px" id="ML_btn" hidden>
                        <input type="button" class="btn btn-info" name="ML_btn_save" id="ML_btn_save" value="SAVE">
                        <input type="button" class="btn btn-info" name="ML_btn_cancel" id="ML_btn_cancel" value="CANCEL">
                    </div>
                </div>
                <div id="ML_searchtable" class="table-responsive">
                    <section>

                    </section>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
