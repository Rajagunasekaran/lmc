<?php
include "../FOLDERMENU.php"
?>
<script>
    $(document).ready(function(){
        var upload_count=0;
        //DATE PICKER FUNCTION
        $(".date-picker").datepicker({
            dateFormat:"dd-mm-yy",
            changeYear: true,
            changeMonth: true
        });
        $(".autosizealph").doValidation({rule:'alphabets',prop:{whitespace:true,autosize:true}});
        $(document).on("keyup",'.alphanumeric',function() {
            if (this.value.match(/[^a-zA-Z0-9\-]/g)) {
                this.value = this.value.replace(/[^a-zA-Z0-9\-]/g, '');
            }
        });
        $(document).on("keyup",'.loginidvalid',function() {
            if (this.value.match(/[^a-zA-Z0-9\_\.\@]/g)) {
                this.value = this.value.replace(/[^a-zA-Z0-9\_\.\@]/g, '');
            }
        });
// common data
        var errmsg=[];
        var wr_role_array=[];
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var value_array=JSON.parse(xmlhttp.responseText);
                $('.preloader').hide();
                errmsg=value_array[1];
                wr_role_array=value_array[0];
                $('#wr_tble_rolecreation').empty();
                var wr_roles='';
                for (var i = 0; i < wr_role_array.length; i++){
                    var value=wr_role_array[i][1];
                    var id1="wr_role_array"+i;
                    if(i==0){
                        var wr_roles='<label class="col-lg-2 control-label" style="white-space: nowrap!important;">ROLE ACCESS<em>*</em></label>'
                        wr_roles+= '<div class="col-lg-10"><div class="radio"><label><input type="radio" name="roles1" id='+id1+' value='+value+' class="wr_class_role1 tree login_submitvalidate"> ' + wr_role_array[i][0] + '</label></div></div>';
                        $('#wr_tble_rolecreation').append(wr_roles);
                    }
                    else if(wr_role_array[i][0]!='SUPER ADMIN' && i!=0){
                        wr_roles='<div class="col-sm-offset-2 col-lg-10"><div class="radio"><label style="white-space: nowrap!important;"><input type="radio" name="roles1" id='+id1+' value='+value+' class="wr_class_role1 tree login_submitvalidate"> ' + wr_role_array[i][0] + '</label></div></div>';
                        $('#wr_tble_rolecreation').append(wr_roles);
                    }
                }
            }
        }
        var option="COMMON_DATA";
        xmlhttp.open("POST","DB_WORKER_PROFILE_ENTRY.php?option="+option);
        xmlhttp.send();

        var email_flag=0;
        $(document).on("blur change",'#wr_tb_emailid', function (){
            var emailid=($('#wr_tb_emailid').val().toLowerCase());
            $('#wr_tb_emailid').val(emailid)
            var atpos=emailid.indexOf("@");
            var dotpos=emailid.lastIndexOf(".");
            if ((atpos<1 || dotpos<atpos+2 || dotpos+2>=emailid.length)||(/^[@a-zA-Z0-9-\\.]*$/.test(emailid) == false))
            {
                email_flag=1;
                show_msgbox("WORKER PROFILE ENTRY",errmsg[1],"error",false);
                $('#wr_tb_emailid').addClass("invalid");
            }
            else{
                $('#wr_tb_emailid').removeClass("invalid");
                email_flag=0;
            }
        });

        $(document).on("keyup",'.wr_email_validate',function() {
            $(this).val().toLowerCase();
            $('#wr_tb_loginid').val($('#wr_tb_loginid').val().toLowerCase());
            if (this.value.match(/[^a-zA-Z0-9\_\.\@]/g)) {
                this.value = this.value.replace(/[^a-zA-Z0-9\_\.\@]/g, '');
            }
        });
        var pass_flag=0;
        $(document).on("change blur",'.chk_password',function() {
            var wr_pass_length=($('#wr_tb_pword').val()).length;
            if(wr_pass_length<8){

                show_msgbox("WORKER PROFILE ENTRY",errmsg[7],"error",false);
                $('#wr_tb_pword').addClass("invalid");
                pass_flag=1;
            }
            else{
                $('#wr_tb_pword').removeClass("invalid");
                pass_flag=0;
            }
        });
        var contact_flag=0;
        $(document).on("change blur",'.mobileno',function() {
            var wr_pass_length=($('#wr_tb_permobile').val()).length;
            if(wr_pass_length<8){
                var msg=errmsg[0].toString().replace('[MOB NO]',$('#wr_tb_permobile').val());
                show_msgbox("WORKER PROFILE ENTRY",msg,"error",false);
                $('#wr_tb_permobile').addClass("invalid");
                contact_flag=1;
            }
            else{
                $('#wr_tb_permobile').removeClass("invalid");
                contact_flag=0;
            }
        });
        //reomve file upload row
        var filecnt;
        $(document).on('click', 'button.removebutton', function () {
            upload_count=upload_count-1;
            $(this).closest('div').remove();
//            rowCount_check=$('#filetableuploads > div').length;
            var rowcnt = $('#filetableuploads > div').length;
            if(rowcnt!=0)
            {
                $('#attachafile').text('Attach another file');
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
                    $('#wr_btn_submitbutton').removeAttr("disabled");
                }
                else
                {
                    $('#wr_btn_submitbutton').attr("disabled", "disabled");
                }
            }
            if(rowcnt==0)
            {
                $('#attachafile').text('Attach a file');
                $('#wr_btn_submitbutton').removeAttr("disabled");
            }
            return false;
        });
        //file extension validation
        $(document).on("change",'.fileextensionchk', function (){
            var fileid=$(this).attr("id");
            var data= $('#'+fileid).val();
            var datasplit=data.split('.');
            var ext=datasplit[1].toUpperCase();
            if(ext=='PDF'|| ext=='JPG'|| ext=='PNG' || ext=='JPEG' || ext=='GIF' || data==undefined || data==""){
            }
            else{
                show_msgbox("WORKER PROFILE ENTRY",errmsg[6],"error",false);
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
        $(document).on("click",'#attachprompt', function (){
            var tablerowCount = $('#filetableuploads > div').length;
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
            var appendfile='<div class="col-sm-offset-2 col-sm-5" style="padding-bottom: 8px"><label class=""><input type="file" style="max-width:250px " class="fileextensionchk form-control" id='+uploadfileid+' name='+uploadfileid+'></label><label class="inline" ><button  class="removebutton" value="-" title="Remove this row" style="background-color:red;color:white;font-size:10;font-weight: bold;"></button></label></div>';
            $('#filetableuploads').append(appendfile);
            upload_count++;
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

        var finalrow;
        // button validation
        $(document).on('change blur','#wr_entry_form',function(){
            var empname=$('#wr_tb_name').val();
            var empnumber=$('#wr_tb_number').val();
            var loginide=$('#wr_tb_loginid').val();
            var passwrd=$('#wr_tb_pword').val();
            var nricno=$('#wr_tb_nric').val();
            var contactno=$('#wr_tb_permobile').val();
            var emailadd=$('#wr_tb_emailid').val();
            var address=$('#wr_ta_address').val();
            var role_id=$("input[name=roles1]").is(":checked");
            var rowCount=$("#filetableuploads > div").length;
            finalrow=$('#temptextbox').val();
            var count=0;
            if(rowCount==count && empname!='' && empnumber!='' && passwrd!='' && loginide!='' && nricno!='' && role_id==true
                && emailadd!='' && address!='' && contactno!='' && email_flag==0 && pass_flag==0 && contact_flag==0)
            {
                if(finalrow=='' || finalrow==0){
                    $('#wr_btn_submitbutton').removeAttr('disabled');
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
                if(rowCount==count && empname!='' && empnumber!='' && passwrd!='' && loginide!='' && nricno!=''&& role_id==true
                    && emailadd!='' && address!='' && contactno!='' && email_flag==0 && pass_flag==0 && contact_flag==0)
                {
                    $('#wr_btn_submitbutton').removeAttr('disabled');
                }
                else
                {
                    $('#wr_btn_submitbutton').attr('disabled','disabled');
                }
            }
        });
// logincheck
        $(document).on('change','.logincheck',function(){
            var loginname=$('#wr_tb_loginid').val();
            if(loginname!='')
            {
                $('.preloader').show();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $('.preloader').hide();
                        var exist_flag=xmlhttp.responseText;
                        if(exist_flag!=0){
                           var msg=errmsg[4].replace('USER NAME','LOGIN ID');
                           msg=msg.replace('[NAME]',$('#wr_tb_loginid').val());
                           show_msgbox("WORKER PROFILE ENTRY",msg,"error",false);
                            $('#wr_tb_loginid').val('');
                       }
                    }
                }
            }
            var option="logincheck";
            xmlhttp.open("POST","DB_WORKER_PROFILE_ENTRY.php?option="+option+"&loginname="+loginname);
            xmlhttp.send();
        });
// emailcheck
        $(document).on('change','.emailcheck',function(){
            var emailid=$('#wr_tb_emailid').val();
            if(email_flag==0){
                if(emailid!='')
                {
                    $('.preloader').show();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $('.preloader').hide();
                            var exist_flag=xmlhttp.responseText;
                            if(exist_flag!=0){
                                show_msgbox("WORKER PROFILE ENTRY",errmsg[2],"error",false)
                                $('#wr_tb_emailid').val('');
                            }
                        }
                    }
                }
                var option="emailcheck";
                xmlhttp.open("POST","DB_WORKER_PROFILE_ENTRY.php?option="+option+"&emailid="+emailid);
                xmlhttp.send();
            }
        });
// worknocheck
        $(document).on('change','.worknocheck',function(){
            var wrkerno=$('#wr_tb_number').val();
            if(wrkerno!='')
            {
                $('.preloader').show();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $('.preloader').hide();
                        var exist_flag=xmlhttp.responseText;
                        if(exist_flag!=0){
                            var msg=errmsg[2].replace('EMAIL ID','WORKER NUMBER');
                            show_msgbox("WORKER PROFILE ENTRY",msg,"error",false)
                            $('#wr_tb_number').val('');
                        }
                    }
                }
            }
            var option="worknocheck";
            xmlhttp.open("POST","DB_WORKER_PROFILE_ENTRY.php?option="+option+"&workerno="+wrkerno);
            xmlhttp.send();
        });
        $(document).on("click",'#wr_btn_submitbutton', function (){
            $('.preloader').show();
            var wrkrname= $('#wr_tb_name').val();
            var rolechecked=$("input[name=roles1]:checked" ).val();
            var formElement = document.getElementById("wr_entry_form");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function(){
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var values=xmlhttp.responseText;
                    if(values==1){
                        var msg=errmsg[3].replace('[NAME]',wrkrname);
                        show_msgbox("WORKER PROFILE ENTRY",msg,"success",false);
//                        $("#filetableuploads").empty();
//                        $('#attachafile').text('Attach a file');
//                        $("#wr_entry_form").find('input:text, input:password, input:file, select, textarea').val('');
//                        $("#wr_entry_form").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
//                        $("#wr_btn_submitbutton").attr("disabled", "disabled");
//                        $('#wr_tb_emailid').removeClass("invalid");
//                        $('#wr_tb_pword').removeClass("invalid");
//                        $('#wr_tb_permobile').removeClass("invalid");
                        email_flag=0;
                        contact_flag=0;
                        pass_flag=0;
                    }
                    else if(values==0){
                        var msg=errmsg[5].toString().replace('[NAME]',$('#wr_tb_loginid').val());
                        show_msgbox("WORKER PROFILE ENTRY",msg,"error",false);
                    }
                    else{
                        show_msgbox("WORKER PROFILE ENTRY",values,"error",false);
                    }
                }
            }
            var option="save";
            xmlhttp.open("POST","DB_WORKER_PROFILE_ENTRY.php?option="+option+"&upload_count="+finalrow+"&rolechecked="+rolechecked);
            xmlhttp.send(new FormData(formElement));
        });
    });
</script>
<!--BODY TAG START-->
<body>
<form id="wr_entry_form" name="wr_entry_form" class="form-horizontal" role="form">
<div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
<div class="container">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h2 class="panel-title">WORKER PROFILE ENTRY</h2>
        </div>
        <div class="panel-body">
            <fieldset>
                <div class="form-group">
                    <label id="wr_lbl_name" class=" col-sm-2">WORKER NAME<em>*</em></label>
                    <div class="col-sm-3"> <input type="text" name="wr_tb_name" id="wr_tb_name" placeholder="Worker Name" maxlength="40" class="autosizealph submitenable form-control"/></div>
                    <label id="wr_lbl_email_err" class="errormsg"></label>
                </div>
                <div class="form-group">
                    <label id="wr_lbl_number" class=" col-sm-2">WORKER NUMBER<em>*</em></label>
                    <div class="col-sm-3"> <input type="text" name="wr_tb_number" id="wr_tb_number" placeholder="Worker Number" maxlength="10" class="alphanumeric form-control submitenable worknocheck"/></div>
                    <label id="wr_lbl_email_err" class="errormsg"></label>
                </div>
                <div class="form-group">
                    <label id="wr_lbl_loginid" class=" col-sm-2" >LOGIN ID<em>*</em></label>
                    <div class="col-sm-3"><input type="text" name="wr_tb_loginid" id="wr_tb_loginid" placeholder="Login Id" maxlength="40" class="wr_email_validate form-control submitenable logincheck"/></div>
                    <label id="wr_lbl_email_errupd" class="errormsg  col-sm-2" ></label>
                </div>
                <div class="form-group">
                    <label name="wr_lbl_pword" id="wr_lbl_pword"class="col-sm-2">PASSWORD<em>*</em></label>
                    <div class="col-sm-3"><input type="password"  name="wr_tb_pword" id="wr_tb_pword" class="chk_password form-control submitenable" maxlength="20" placeholder="Password"/></div>
                    <label id="wr_lbl_passwrd_errupd" class="errormsg  col-sm-2"></label>
                </div>
                <div class="form-group" id="wr_tble_rolecreation">
                </div>
                <div class="form-group">
                    <label name="wr_lbl_emailid" id="wr_lbl_emailid" class="col-sm-2">EMAIL ID<em>*</em></label>
                    <div class="col-sm-3"><input type="text" name="wr_tb_emailid" id="wr_tb_emailid" maxlength='50' placeholder="Email Id" class="form-control submitenable emailcheck"></div>
                    <div><label id="wr_lbl_email_error" class="errormsg"></label></div>
                </div>
                <div class="form-group">
                    <label name="wr_lbl_permobile" id="wr_lbl_permobile" class="col-sm-2">CONTACT NO<em>*</em></label>
                    <div class="col-sm-3"><input type="text" name="wr_tb_permobile" id="wr_tb_permobile" placeholder="Contact No" maxlength='8' class="mobileno submitenable valid numonlynozero form-control " style="width:120px" ></div>
                    <label id="wr_lbl_validnumber" name="wr_lbl_validnumber" class="errormsg"></label>
                </div>
                <div class="form-group">
                    <label class="col-sm-2">NRIC NO<em>*</em></label>
                    <div class="col-sm-3"><input type="text"  name="wr_tb_nric" id="wr_tb_nric" maxlength="10" class="alphanumeric submitenable form-control" placeholder="NRIC No" style="width:120px"/></div>
                    <label id="wr_lbl_passwrd_errupd" class="errormsg  col-sm-2"></label>
                </div>
                <div class="form-group">
                    <label name="wr_lbl_address" id="wr_lbl_address" class="col-sm-2">ADDRESS<em>*</em></label>
                    <div class="col-sm-10"> <textarea  name="wr_ta_address" id="wr_ta_address" placeholder="Address" maxlength="300" class="maxlength submitenable textareaupd form-control"></textarea>
                    </div>
                </div>
                <div>
                    <div><input type="hidden" id="temptextbox" name="temptextbox"></div>
                    <div ID="filetableuploads" class="form-group row">
                    </div>
                </div>
                <div class="form-group">
                    <div id="attachprompt" class="col-sm-offset-2 col-sm-2"><img width="15" height="15" src="../image/paperclip.gif" border="0">
                        <a href="javascript:_addAttachmentFields('attachmentarea')" id="attachafile">Attach a file</a>
                    </div>
                </div>
                <div class="col-sm-offset-10 col-sm-2">
                    <input class="btn btn-info btn-lg" type="button"  id="wr_btn_submitbutton" name="SAVE" value="CREATE" disabled />
                </div>
            </fieldset>
        </div>
    </div>
</div>
</form>
</body>
</html>
