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
        searchtable()
        var upload_count;
        $( "textarea" ).autogrow( { vertical : true, horizontal : true } );
        $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:5,imaginary:2}});
        $(".date-picker").datepicker({
            dateFormat: "dd-mm-yy",
            changeYear: true,
            changeMonth: true
        });
    //INITIAL DATA FETCHING
        $('#CC_btn_additem').click(function() {
            $('#contractentry').show();
            $('#CC_btn').show();
            $('#CC_btn_additem').hide();
            $('#CC_btn_save').val('SAVE');
            $("#contractfiletableuploads").empty();
            $('#contractattachafile').text('Attach a file');
            $('#contractexsistingfiletable').empty();
        });
        var errormessage;
        $.ajax({
            type: "POST",
            url: "DB_CONTRACT_CREATION_UPDATE.php",
            data: {"option": "INITIAL_DATA"},
            success: function (res) {
                $('.preloader').hide();
                var response = JSON.parse(res);
                var teamname = response[0];
                var jobstatus = response[1];
                errormessage=response[2];
                var teamlist = '<option>SELECT</option>';
                for (var i = 0; i < teamname.length; i++) {
                    teamlist += '<option value="' + teamname[i][0] + '">' + teamname[i][0] + '</option>';
                }
                $('#CC_team').html(teamlist);
                var status = '<option>SELECT</option>';
                for (var i = 0; i < jobstatus.length; i++) {
                    status += '<option value="' + jobstatus[i][0] + '">' + jobstatus[i][0] + '</option>';
                }
                $('#CC_status').html(status);
            },
            error: function (data) {
                alert('error in getting' + JSON.stringify(data));
            }
        });
    //ATTACH FILE STARTS
        //file extension validation
        $(document).on("change",'.contractfileextensionchk', function (){
            var fileid=$(this).attr("id");
            var data= $('#'+fileid).val();
            var datasplit=data.split('.');
            var ext=datasplit[1].toUpperCase();
            if(ext=='PDF'|| ext=='JPG'|| ext=='PNG' || ext=='JPEG' || ext=='GIF' || data==undefined || data==""){
            }
            else{
                show_msgbox("CONTRACT CREATION ENTRY/UPDATE",errmsg[6],"error",false);
                reset_field($('#'+fileid));
                $('#wr_btn_submitbutton').attr("disabled", "disabled");
            }
        });
        //file upload reset
        function reset_field(e) {
            e.wrap('<form>').parent('form').trigger('reset');
            e.unwrap();
        }
        //add file upload row
        $(document).on("click",'#contractattachprompt', function (){
            var tablerowCount = $('#contractfiletableuploads > div').length;
            if(tablerowCount==0)
            {
                var row_count=parseInt(tablerowCount)+1;
                var uploadfileid="upload_filename"+row_count;
                $('#temptextbox').val(row_count);
            }
            else
            {
                var rowvalue=$('#temptextbox').val();
                var rowcount=parseInt(rowvalue)+1;
                uploadfileid="upload_filename"+rowcount;
                $('#temptextbox').val(rowcount);
            }
            var appendfile='<div class="col-sm-offset-3 col-sm-5" style="padding-bottom: 8px"><label class=""><input type="file" style="max-width:250px " class="contractfileextensionchk form-control" id='+uploadfileid+' name='+uploadfileid+'></label><label class="inline" ><button  class="removebutton" value="-" title="Remove this row" style="background-color:red;color:white;font-size:10;font-weight: bold;"></button></label></div>';
            $('#contractfiletableuploads').append(appendfile);
            upload_count++;
            var rowCount =$("#contractfiletableuploads > div").length// $('#filetableuploads tr').length;//
            if(rowCount!=0)
            {
                $('#contractattachafile').text('Attach another file');
            }
            else
            {
                $('#contractattachafile').text('Attach a file');
            }
        });
        var removedfilename;
        //CLICK EVENT DELETE BUTTON
        $(document).on("click", "#contract_Del", function (){
            $(this).closest("div").remove();
            var Count = $('#contractfiletableuploads > div').length;
            if(Count==0)
            {
                $('#contractattachafile').text('Attach a file');
                $('#CC_btn_save').removeAttr('disabled');
            }
            else
            {
                $('#contractattachafile').text('Attach another file');
                $('#CC_btn_save').attr('disabled','disabled');
            }
            var divcount=$('#contractexsistingfiletable > div').length;
            var oldfilename;
            if(divcount>=0){
                for(var i=0;i<=divcount;i++)
                {
                    var div_value=$('#filecount'+i).text();
                    if(div_value!='')
                    {
                        if(i==0)
                        {
                            oldfilename=div_value;
                        }
                        else
                        {
                            oldfilename =oldfilename+'/'+div_value
                        }
                    }
                }
            }
            if(oldfilename==undefined){
                removedfilename='';
            }
            else{
                removedfilename=oldfilename.replace("undefined/",'');
            }
        });
        //reomve file upload row
        var filecnt;
        $(document).on('click', 'button.removebutton', function () {
            upload_count=upload_count-1;
            $(this).closest('div').remove();
            var rowcnt = $('#contractfiletableuploads > div').length;
            if(rowcnt!=0)
            {
                $('#contractattachafile').text('Attach another file');
                filecnt=$('#temptextbox').val();
                var count=0;
                for(var j=1;j<=filecnt;j++)
                {
                    var data= $('#upload_filename'+j).val();
                    if(data!='' && data!=undefined && data!=null)
                    {
                        count++;
                    }
                }
                if(rowcnt==count)
                {
                    $('#CC_btn_save').removeAttr("disabled");
                }
                else
                {
                    $('#CC_btn_save').attr("disabled", "disabled");
                }
            }
            if(rowcnt==0)
            {
                $('#contractattachafile').text('Attach a file');
                $('#CC_btn_save').removeAttr("disabled");
            }
            return false;
        });
        var finalrow;
        // button validation
        $(document).on('change blur','#contractcreation',function(){
            var contractno=$('#CC_contractid').val();
            var teamname=$('#CC_team').val();
            var status=$('#CC_status').val();
            var rowCount=$("#contractfiletableuploads > div").length;
            finalrow=$('#temptextbox').val();
            var count=0;
            if(rowCount==count && contractno!='' && teamname!='SELECT' && status!='SELECT')
            {
                if(finalrow=='' || finalrow==0){
                    $('#CC_btn_save').removeAttr('disabled');
                }
            }
            else{
                for(var j=1;j<=finalrow;j++)
                {
                    var data= $('#upload_filename'+j).val();
                    if(data!='' && data!=undefined && data!=null)
                    {
                        count++;
                    }
                }
                if(rowCount==count && contractno!='' && teamname!='SELECT' && status!='SELECT')
                {
                    $('#CC_btn_save').removeAttr('disabled');
                }
                else
                {
                    $('#CC_btn_save').attr('disabled','disabled');
                }
            }
        });
    //SET MINDATE FOR ENDDATE EXTENDED DATE
        $(document).on('change','#CC_enddate',function(){
            var CC_enddate = $('#CC_enddate').datepicker('getDate');
            var date = new Date( Date.parse( CC_enddate ));
            date.setDate( date.getDate()  );
            var CC_extenddate = date.toDateString();
            CC_extenddate = new Date( Date.parse( CC_extenddate ));
            $('#CC_extendeddate').datepicker("option","minDate",CC_extenddate);
        });
    //CANCEL BUTTON CLICK EVENT
        $('#CC_btn_cancel').click(function(){
            $('#contractentry').hide();
            $('#CC_btn').hide();
            $('#CC_btn_additem').show();
            $("#contractentry").find('input:text, input:password, input:file,textarea').val('');
            $("#contractentry").find('select').val('SELECT')
            $("#contractentry").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
        });
        var result=[];
var rowid,contractno,customername,createddate,enddate,extendedate,contactperson,notifications,address,payterm,nextnumber,type,telno,
    faxno,mail,website,inchargeperson,hpno,amount,remark1,remark2,teamname,status,approvedfile,completedfile,userstamp,timestamp;
     //FUNCTION FOR SEARCH DATATABLE
        function searchtable(){
            $('.preloader').show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    result = JSON.parse(xmlhttp.responseText);
                    $('.preloader').hide();
                    if (result.length>0) {
                        var CC_UPD_table_header = '<table id="CC_tbl_htmltable" border="1"  cellspacing="0" class="srcresult"><thead  bgcolor="#6495ed" style="color:white;text-align:center;"><tr><th>EDIT</th><th style="text-align:center;">CONTRACT ID</th><th style="text-align:center;">CUSTOMER NAME</th><th width="350px"  style="text-align:center;">ADDRESS</th><th style="text-align:center;">PERSON</th><th style="text-align:center;">CREATED DATE</th><th style="text-align:center;">END DATE</th><th style="text-align:center;">EXTENDED DATE</th><th style="text-align:center;">TEAM</th><th style="text-align:center;">STATUS</th><th style="text-align:center;">USESTAMP</th><th>TIMESTAMP</th></tr></thead><tbody>';
                        for (var i = 0; i <result.length; i++) {
                            rowid = result[i].rowid;
                            contractno = result[i].contractno;
                            customername = result[i].customername;
                            if (customername == null) {
                                customername = '';
                            }
                            createddate = result[i].createddate;
                            if (customername == null) {
                                customername = '';
                            }
                            enddate = result[i].endate;
                            if (enddate == null) {
                                enddate = '';
                            }
                            extendedate = result[i].extendeddate;
                            if (extendedate == null) {
                                extendedate = '';
                            }
                            contactperson = result[i].contactperson;
                            if (contactperson == null) {
                                contactperson = '';
                            }
                            address = result[i].address;
                            if (address == null) {
                                address = '';
                            }
                            teamname = result[i].teamname;
                            if (teamname == null) {
                                teamname = '';
                            }
                            status = result[i].status;
                            userstamp = result[i].userstamp;
                            timestamp = result[i].timestamp;
                            CC_UPD_table_header += '<tr id='+rowid+' ><td><div class="col-lg-1"><span style="display: block;color:green" class="glyphicon glyphicon-edit  edit" id="edit_'+rowid+'"></span></div></td><td nowrap>' +contractno+ '</td><td>' +customername+ '</td><td> '+address+ '</td><td>' +contactperson+ '</td><td nowrap>' +createddate+ '</td><td nowrap>' +enddate+ '</td><td nowrap>' +extendedate+ '</td><td>' +teamname+ '</td><td nowrap>' +status+ '</td><td>' +userstamp+ '</td><td nowrap>' +timestamp+ '</td></tr>';
                        }
                        CC_UPD_table_header += '</tbody></table>';
                        $('section').html(CC_UPD_table_header);
                        $('#CC_tbl_htmltable').DataTable({
                            "aaSorting": [],
                            "pageLength": 10,
                            "sPaginationType": "full_numbers"
                        });
                        $('#CC_searchtable').show();
                    }
                }
            }
            var option="search_data";
            xmlhttp.open("POST","DB_CONTRACT_CREATION_UPDATE.php?option="+option);
            xmlhttp.send();
        }
        var filenameinarray=[];
        var tblerowid;
        $(document).on('click','.edit',function(){
            tblerowid=this.id.split('_')[1];
            $('#contractentry').show();
            $('#CC_btn').show();
            $('#CC_btn_additem').hide();
            $('#CC_btn_save').val('UPDATE');
            $("#contractfiletableuploads").empty();
            $('#contractattachafile').text('Attach a file');
            $('#contractexsistingfiletable').empty();
            for(var j=0;j<result.length;j++) {
                if (tblerowid == result[j].rowid) {
                    contractno = result[j].contractno;
                    customername = result[j].customername;
                    if (customername == null) {
                        customername = '';
                    }
                    createddate = result[j].createddate;
                    if (customername == null) {
                        customername = '';
                    }
                    enddate = result[j].endate;
                    if (enddate == null) {
                        enddate = '';
                    }
                    extendedate = result[j].extendeddate;
                    if (extendedate == null) {
                        extendedate = '';
                    }
                    contactperson = result[j].contactperson;
                    if (contactperson == null) {
                        contactperson = '';
                    }
                    notifications = result[j].notifications;
                    if (notifications == null) {
                        notifications = '';
                    }
                    address = result[j].address;
                    if (address == null) {
                        address = '';
                    }
                    payterm = result[j].paymentterm;
                    if (payterm == null) {
                        payterm = '';
                    }
                    nextnumber = result[j].nextnumber;
                    if (nextnumber == null) {
                        nextnumber = '';
                    }
                    type = result[j].type;
                    if (type == null) {
                        type = 'SELECT';
                    }
                    telno = result[j].telno;
                    if (telno == null) {
                        telno = '';
                    }
                    faxno = result[j].faxno;
                    if (faxno == null) {
                        faxno = '';
                    }
                    mail = result[j].mail;
                    if (mail == null) {
                        mail = '';
                    }
                    website = result[j].website;
                    if (website == null) {
                        website = '';
                    }
                    inchargeperson = result[j].inchargeperson;
                    if (inchargeperson == null) {
                        inchargeperson = '';
                    }
                    hpno = result[j].hpno;
                    if (hpno == null) {
                        hpno = '';
                    }
                    amount = result[j].amount;
                    if (amount == null) {
                        amount = '';
                    }
                    remark1 = result[j].remark1;
                    if (remark1 == null) {
                        remark1 = '';
                    }
                    remark2 = result[j].remark2;
                    if (remark2 == null) {
                        remark2 = '';
                    }
                    teamname = result[j].teamname;
                    if (teamname == null) {
                        teamname = '';
                    }
                    status = result[j].status;
                    approvedfile = result[j].approvedfile;
                    completedfile = result[j].completedfile;
                    $('#CC_contractid').val(contractno);
                    $('#CC_telphoneno').val(telno);
                    $('#CC_customername').val(customername);
                    $('#CC_faxno').val(faxno);
                    $('#CC_contactperson').val(contactperson);
                    $('#CC_email').val(mail);
                    $('#CC_website').val(website);
                    $('#CC_address').val(address);
                    $('#CC_inchargeperson').val(inchargeperson);
                    $('#CC_paymentterm').val(payterm);
                    $('#CC_hpno').val(hpno);
                    $('#CC_nextnumber').val(nextnumber);
                    $('#CC_amount').val(amount);
                    $('#CC_type').val(type);
                    $('#CC_remark1').val(remark1);
                    $('#CC_remark2').val(remark2);
                    $('#CC_createddate').val(createddate);
                    $('#CC_enddate').val(enddate);
                    $('#CC_team').val(teamname);
                    $('#CC_extendeddate').val(extendedate);
                    $('#CC_notification').val(notifications);
                    $('#CC_status').val(status);
                    if(approvedfile!=''){
                        filenameinarray=approvedfile.split('/');
                        for(var k=0;k<filenameinarray.length;k++){
                            var name=contractno+"/"+filenameinarray[k];
                            var file_count='filecount'+k;
                            var appendfile=' <div class="col-sm-offset-3 col-sm-10" style="padding-bottom: 5px" id='+file_count+'><a href="../LMC_LIB/contractfiledownload.php?filename='+name+'" class="links">'+filenameinarray[k]+'</a> <input type="button" id="contract_Del" class="submit_enable" value="X" style="background-color:red;color:white;font-size:9px;font-weight: bold;"/></div>';
                            $('#contractexsistingfiletable').append(appendfile);
                        }
                    }
                }
            }
        });
        //SAVE&UPDATE PART FOR CONTRACT
        $(document).on('click','#CC_btn_save',function(){
            $('.preloader').show();
            var buttonvalue=$('#CC_btn_save').val();
            var formElement = document.getElementById("contractcreation");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var result=xmlhttp.responseText;
                    if(buttonvalue=='SAVE'){
                        if(result==1)
                        {
                            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[0],"success",false);
                            searchtable();
                            $("#contractfiletableuploads").empty();
                            $('#contractattachafile').text('Attach a file');
                            $('#contractentry').hide();
                            $('#CC_btn_additem').show();
                            $('#CC_btn').hide();
                            $("#contractentry").find('input:text, input:password, input:file,textarea').val('');
                            $("#contractentry").find('select').val('SELECT');
                            $("#contractentry").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
                        }
                        else
                        {
                            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[2],"success",false);
                        }
                    }
                    else
                    {
                        if(result==1)
                        {
                            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[1],"success",false);
                            searchtable();
                            $('#contractentry').hide();
                            $('#CC_btn').hide();
                            $('#CC_btn_additem').show();
                            $("#contractentry").find('input:text, input:password, input:file,textarea').val('');
                            $$("#contractentry").find('select').val('SELECT');
                            $("#contractentry").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
                        }
                        else
                        {
                            show_msgbox("REPORT SUBMISSION ENTRY",errormessage[3],"success",false);
                        }
                    }
                }
            }
            var option="contractsaveupdate";
            xmlhttp.open("POST","DB_CONTRACT_CREATION_UPDATE.php?option="+option+"&buttonvalue="+buttonvalue+"&rowid="+tblerowid+"&upload_count="+finalrow+"&removedfilename="+removedfilename);
            xmlhttp.send(new FormData(formElement));
        });
    });
</script>
</head>
<body>
<form id="contractcreation" name="contractcreation" class="form-horizontal">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/></div>
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">CONTRACT CREATION</h2>
            </div>
            <div class="panel-body">
            <div id="contractentry" hidden>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="CC_contractid">CUSTOMER/CONTRACT NO<em>*</em></label>
                    <div class="col-sm-3"><input type="text" id="CC_contractid" name="CC_contractid" placeholder="Contract No" class="form-control"/></div>
                    <label class="col-sm-2 control-label" for="CC_telphoneno">TEL NO</label>
                    <div class="col-sm-3"><input type="text" id="CC_telphoneno" name="CC_telphoneno" placeholder="Telephone No" class="form-control"/></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="CC_customername">CUSTOMER NAME</label>
                    <div class="col-sm-3"><input type="text" id="CC_customername" name="CC_customername" placeholder="Customer Name" class="form-control"/></div>
                    <label class="col-sm-2 control-label" for="CC_faxno">FAX NO</label>
                    <div class="col-sm-3"><input type="text" id="CC_faxno" name="CC_faxno" placeholder="Fax No" class="form-control"/></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="CC_contactperson">CONTACT PERSON</label>
                    <div class="col-sm-3"><input type="text" id="CC_contactperson" name="CC_contactperson" placeholder="Contact Person" class="form-control"/></div>
                    <label class="col-sm-2 control-label" for="CC_email">EMAIL</label>
                    <div class="col-sm-3"><input type="text" id="CC_email" name="CC_email" placeholder="Email" class="form-control"/></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="CC_status">STATUS<em>*</em></label>
                    <div class="col-sm-3"><select  id="CC_status" name="CC_status" class="form-control"></select></div>
                    <label class="col-sm-2 control-label" for="CC_website">WEBSITE</label>
                    <div class="col-sm-3"><input type="text" id="CC_website" name="CC_website" placeholder="Website" class="form-control"/></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="CC_address">ADDRESS</label>
                    <div class="col-sm-3"><textarea id="CC_address" name="CC_address" placeholder="Address" class="form-control"></textarea></div>
                    <label class="col-sm-2 control-label" for="CC_inchargeperson">IN-CHARGE PERSON</label>
                    <div class="col-sm-3"><input type="text" id="CC_inchargeperson" name="CC_inchargeperson" placeholder="In-Charge Person" class="form-control"/></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="CC_paymentterm">PAYMENT TERM</label>
                    <div class="col-sm-3"><input type="text" id="CC_paymentterm" name="CC_paymentterm" placeholder="Payment Term" class="form-control"/></div>
                    <label class="col-sm-2 control-label" for="CC_hpno">HP NO</label>
                    <div class="col-sm-3"><input type="text" id="CC_hpno" name="CC_hpno" placeholder="Hp No" class="form-control"/></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="CC_nextnumber">NEXT NUMBER</label>
                    <div class="col-sm-3"><input type id="CC_nextnumber" name="CC_nextnumber" placeholder="Next Number" class="form-control"/></div>
                    <label class="col-sm-2 control-label" for="CC_amount">AMOUNT</label>
                    <div class="col-sm-3"><input type="text" id="CC_amount" name="CC_amount" placeholder="Amount" class="form-control amountonly"/></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="CC_type">TYPE</label>
                    <div class="col-sm-3"><select  id="CC_type" name="CC_type" class="form-control">
                            <option>SELECT</option>
                            <option>aaaa</option>
                            <option>bbbb</option>
                            <option>cccc</option>
                        </select></div>
                    <label class="col-sm-2 control-label" for="CC_remark1">REMARK 1</label>
                    <div class="col-sm-3"><textarea id="CC_remark1" name="CC_remark1" placeholder="Remark 1" class="form-control"></textarea></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="CC_createddate">CREATED DATE</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text"  id="CC_createddate" name="CC_createddate" placeholder="Created Date" class="form-control date-picker datemandtry"/>
                            <label for="CC_createddate" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                        </div>
                    </div>
                    <label class="col-sm-2 control-label" for="CC_remark2">REMARK 2</label>
                    <div class="col-sm-3"><textarea id="CC_remark2" name="CC_remark2" placeholder="Remark 2" class="form-control"></textarea></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="CC_enddate">END DATE</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text"  id="CC_enddate" name="CC_enddate" placeholder="End Date" class="form-control date-picker datemandtry"/>
                            <label for="CC_enddate" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                        </div>
                    </div>
                    <label class="col-sm-2 control-label" for="CC_team">TEAM<em>*</em></label>
                    <div class="col-sm-3"><select  id="CC_team" name="CC_team" class="form-control"></select></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="CC_extendeddate">EXTENDED DATE</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text"  id="CC_extendeddate" name="CC_extendeddate" placeholder="Extended Date" class="form-control date-picker datemandtry"/>
                            <label for="CC_extendeddate" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                        </div>
                    </div>
                    <label class="col-sm-2 control-label" for="CC_notification">NOTIFICATIONS</label>
                    <div class="col-sm-3"><textarea id="CC_notification" name="CC_notification" placeholder="Notifications" class="form-control"></textarea></div>
                </div>
                <div>
                    <div id="contractexsistingfiletable" class="form-group"></div>
                    <div><input type="hidden" id="temptextbox" name="temptextbox"></div>
                    <div id="contractfiletableuploads" class="form-group row">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="status">ATTACHMENTS</label>
                    <div id="contractattachprompt" class="col-sm-3"><img width="15" height="15" src="../image/paperclip.gif" border="0">
                        <a href="javascript:_addAttachmentFields('attachmentarea')" id="contractattachafile">Attach a file</a>
                    </div>
                    <label class="col-sm-2 control-label" for="CC_machineryusage"></label>
                    <div class="col-sm-3">
                        <div class="checkbox">
                              <label><input type="checkbox" name="CC_machineryusage" id="CC_machineryusage" value="machineryusage" class="">MACHINERY USAGE</label>
                        </div>
                    </div>
                </div>
            </div>
                <div class="form-group">
                    <div class="col-lg-2" style="padding-bottom:15px">
                        <input type="button" class="btn btn-info" name="CC_btn_additem" id="CC_btn_additem" value="ADD NEW">
                    </div>
                    <div class="col-lg-offset-10" style="padding-left:15px" id="CC_btn" hidden>
                        <input type="button" class="btn btn-info" name="CC_btn_save" id="CC_btn_save" value="SAVE" disabled>
                        <input type="button" class="btn btn-info" name="CC_btn_cancel" id="CC_btn_cancel" value="CANCEL">
                    </div>
                </div>

                <div id="CC_searchtable" class="table-responsive">
                    <section>

                    </section>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
