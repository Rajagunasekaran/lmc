<?php
include "../FOLDERMENU.php";
?>
<script>
$(document).ready(function(){
    commondata();
    var filenameinarray=[];
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $('.preloader').hide();
            var value_array=JSON.parse(xmlhttp.responseText);
            if(value_array.length!=0)
            {
                $('#update_form').show();
                $('#exsistingfiletable').empty();
                $('#filetableuploads').empty();
                $('#attachafile').text('Attach a file');
                $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                var wrp_rowid=value_array[0][0].wrp_rowid;
                var wrp_name=value_array[0][0].wrp_name;
                var wrp_no=value_array[0][0].wrp_no;
                var wrp_rcid=value_array[0][0].wrp_rcid;
                var wrp_emailid=value_array[0][0].wrp_emailid;
                var wrp_address=value_array[0][0].wrp_address;
                var wrp_nricno=value_array[0][0].wrp_nricno;
                var wrp_mobno=value_array[0][0].wrp_mobno;
                var wrp_uname=value_array[0][0].wrp_uname;
                var wrp_pswd=value_array[0][0].wrp_pswd;
                var rcname=value_array[1];
                $('#wrs_tb_rowid').val(wrp_rowid);
                $('#wrs_tb_name').val(wrp_name);
                $('#wrs_tb_number').val(wrp_no);
                $('#wrs_tb_loginid').val(wrp_uname);
                $('#wrs_tb_pword').val(wrp_pswd);
                $('#wrs_tb_nric').val(wrp_nricno);
                $('#wrs_tb_permobile').val(wrp_mobno);
                $('#wrs_tb_emailid').val(wrp_emailid);
                $('#wrs_ta_address').val(wrp_address);
//                $('#wrs_tble_rolecreation').empty();
//                var wrs_roles='';
//                for (var i = 0; i < rcname.length; i++){
//                    var value=rcname[i][1];
//                    var id1="wrs_role_array"+i;
//
//                    if(wrp_rcid=='SUPER ADMIN'){
//                        if(rcname[i][0]==wrp_rcid){
//                            if(i==0){
//                                var wrs_roles='<label class="col-lg-2 control-label" style="white-space: nowrap!important;">SELECT ROLE ACCESS<em>*</em></label>'
//                                wrs_roles+= '<div class="col-lg-10"><div class="radio"><label><input type="radio" name="roles1" id='+id1+' value='+value+' class="wrs_class_role1 tree login_submitvalidate" checked> ' +rcname[i][0] + '</label></div></div>';
//                                $('#wrs_tble_rolecreation').append(wrs_roles);
//                            }
//                            else{
//                                wrs_roles='<div class="col-sm-offset-2 col-lg-10"><div class="radio"><label style="white-space: nowrap!important;"><input type="radio" name="roles1" id='+id1+' value='+value+' class="wrs_class_role1 tree login_submitvalidate" checked> ' + rcname[i][0] + '</label></div></div>';
//                                $('#wrs_tble_rolecreation').append(wrs_roles);
//                            }
//                        }
//                    }
//                    if(rcname[i][0]==wrp_rcid){
//                        if(i==0){
//                            var wrs_roles='<label class="col-lg-2 control-label" style="white-space: nowrap!important;">SELECT ROLE ACCESS<em>*</em></label>'
//                            wrs_roles+= '<div class="col-lg-10"><div class="radio"><label><input type="radio" name="roles1" id='+id1+' value='+value+' class="wrs_class_role1 tree login_submitvalidate" checked> ' +rcname[i][0] + '</label></div></div>';
//                            $('#wrs_tble_rolecreation').append(wrs_roles);
//                        }
//                        else if(i!=0 && rcname[i][0]!='SUPER ADMIN'){
//                            wrs_roles='<div class="col-sm-offset-2 col-lg-10"><div class="radio"><label style="white-space: nowrap!important;"><input type="radio" name="roles1" id='+id1+' value='+value+' class="wrs_class_role1 tree login_submitvalidate" checked> ' + rcname[i][0] + '</label></div></div>';
//                            $('#wrs_tble_rolecreation').append(wrs_roles);
//                        }
//                    }
//                    else{
//                        if(i==0){
//                            var wrs_roles='<label class="col-lg-2 control-label" style="white-space: nowrap!important;">SELECT ROLE ACCESS<em>*</em></label>'
//                            wrs_roles+= '<div class="col-lg-10"><div class="radio"><label><input type="radio" name="roles1" id='+id1+' value='+value+' class="wrs_class_role1 tree login_submitvalidate"> ' +rcname[i][0] + '</label></div></div>';
//                            $('#wrs_tble_rolecreation').append(wrs_roles);
//                        }
//                        else if(i!=0 && rcname[i][0]!='SUPER ADMIN'){
//                            wrs_roles='<div class="col-sm-offset-2 col-lg-10"><div class="radio"><label style="white-space: nowrap!important;"><input type="radio" name="roles1" id='+id1+' value='+value+' class="wrs_class_role1 tree login_submitvalidate"> ' + rcname[i][0] + '</label></div></div>';
//                            $('#wrs_tble_rolecreation').append(wrs_roles);
//                        }
//                    }
//                }
                var filenamein_array=value_array[0][0].wrp_filename;
                var wrs_folder_name=value_array[0][0].folder_id;
                if(filenamein_array!=''){
                    filenameinarray=filenamein_array.split('/');
                    for(var j=0;j<filenameinarray.length;j++){
                        var name=wrs_folder_name+"/"+filenameinarray[j];
                        var file_count='filecount'+j;
                        if(role=='ADMIN' || role=='SUPER ADMIN'){
                            var appendfile=' <div class="col-sm-offset-2 col-sm-10" style="padding-bottom: 5px" id='+file_count+'><a href="../LMC_LIB/download.php?filename='+name+'" class="links">'+filenameinarray[j]+'</a> <input type="button" id="Del" class="submit_enable" value="X" style="background-color:red;color:white;font-size:9;font-weight: bold;"/></div>';
                        }
                        else{
                            var appendfile=' <div class="col-sm-offset-2 col-sm-10" style="padding-bottom: 5px" id='+file_count+'><a href="../LMC_LIB/download.php?filename='+name+'" class="links">'+filenameinarray[j]+'</a></div>';
                            $('#attachprompt').hide();
                            $("#wrs_btn_submitbutton").hide();
                        }
                        $('#exsistingfiletable').append(appendfile);
                    }
                }
            }
            else{
                show_msgbox("WORKER PROFILE SEARCH UPDATE",errmsg[7],"error",false)
                $('#wrs_searchupdate').hide();
            }
        }
    }
    var option="search_data";
    xmlhttp.open("GET","DB_WORKER_PROFILE_SEARCH.php?option="+option+"&empname="+empname);
    xmlhttp.send();
//    });
    $('#wrs_btn_submitbutton').hide();
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
    var email_flag;
    $(document).on("blur change",'#wrs_tb_emailid', function (){

        var emailid=($('#wrs_tb_emailid').val().toLowerCase());
        $('#wrs_tb_emailid').val(emailid)
        var atpos=emailid.indexOf("@");
        var dotpos=emailid.lastIndexOf(".");
        if ((atpos<1 || dotpos<atpos+2 || dotpos+2>=emailid.length)||(/^[@a-zA-Z0-9-\\.]*$/.test(emailid) == false))
        {
            email_flag=0;
            show_msgbox("WORKER PROFILE SEARCH UPDATE",errmsg[1],"error",false);
            $('#wrs_tb_emailid').addClass("invalid");
        }
        else{
            $('#wrs_tb_emailid').removeClass("invalid");
            email_flag=1;
        }
    });

    $(document).on("keyup",'.wrs_email_validate',function() {
        $(this).val().toLowerCase();
        $('#wrs_tb_loginid').val($('#wrs_tb_loginid').val().toLowerCase());
        if (this.value.match(/[^a-zA-Z0-9\_\.\@]/g)) {
            this.value = this.value.replace(/[^a-zA-Z0-9\_\.\@]/g, '');
        }
    });

    var contact_flag=0;
    $(document).on("change blur",'.mobileno',function() {
        var wrs_pass_length=($('#wrs_tb_permobile').val()).length;
        if(wrs_pass_length<8){
            var msg=errmsg[0].toString().replace('[MOB NO]',$('#wrs_tb_permobile').val());
            show_msgbox("WORKER PROFILE SEARCH UPDATE",msg,"error",false);
            $('#wrs_tb_permobile').addClass("invalid");
            contact_flag=0;
        }
        else{
            $('#wrs_tb_permobile').removeClass("invalid");
            contact_flag=1;
        }
    });
//LOAD COMMON DATA
    var arrayfilename=[];
    var errmsg=[];
    var role;
    var empname=[];
    var wrs_role_array=[];
    function commondata(){
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var value_array=JSON.parse(xmlhttp.responseText);
                $('.preloader').hide();
                arrayfilename=value_array[0];
                errmsg=value_array[1];
                role=value_array[2];
                empname=value_array[3];
                wrs_role_array=value_array[4];
                var emp_name='<option>SELECT</option>';
                for (var j=0;j<empname.length;j++) {
                    emp_name += '<option value="' + empname[j][1] + '">' + empname[j][1] + '</option>';
                }
                $('#wrs_team_lb_empname').html(emp_name);
            }
        }
        var option="COMMON_DATA";
        xmlhttp.open("GET","DB_WORKER_PROFILE_SEARCH.php?option="+option);
        xmlhttp.send();
    }

//CLICK EVENT FOR SEARCH BUTTON

    $(document).on('change','#wrs_team_lb_empname',function(){
        $('#update_form').hide();
        $('#wrs_btn_submitbutton').attr('disabled','disabled').hide();
        $('#temptextbox').val('');
        $('#wrs_searchbtn').attr('disabled','disabled');
        var name=$('#wrs_team_lb_empname').val();
        if(name!='SELECT'){
            $('#wrs_searchbtn').removeAttr('disabled');
        }
    });
//
    var removedfilename
    //CLICK EVENT DELETE BUTTON
    $(document).on("click", "#Del", function (){
        $(this).closest("div").remove();
        var divcount=$('#exsistingfiletable > div').length;
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
    $(document).on('click', 'button.removebutton', function () {
        upload_count=upload_count-1;
        $(this).closest('div').remove();
        var rowCount = $('#filetableuploads > div').length;
        if(rowCount!=0)
        {
            $('#attachafile').text('Attach another file');
            $('#wrs_btn_submitbutton').removeAttr('disabled');
        }
        else
        {
            $('#attachafile').text('Attach a file');
            $('#wrs_btn_submitbutton').attr('disabled','disabled');
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
            show_msgbox("WORKER PROFILE ENTRY",errmsg[8],"error",false);
            reset_field($('#'+fileid));
            $('#wrs_btn_submitbutton').attr("disabled", "disabled");
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

        var appendfile='<div class="col-sm-offset-2 col-sm-5" style="padding-bottom: 8px"><label class=""><input type="file" style="max-width:250px " class="fileextensionchk form-control submit_enable" id='+uploadfileid+' name='+uploadfileid+'></label><label class="inline" ><button  class="removebutton" value="-" title="Remove this row" style="background-color:red;color:white;font-size:10;font-weight: bold;"></button></label></div>';
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
//                $('#wrs_btn_submitbutton').attr('disabled','disabled');
        }
    });

    $(document).on("change blur click",'.submit_enable',function(){

        var empname=$('#wrs_lb_selectempname').val();
        var date=$('#wrs_tb_date').val();
        var ex_div=$('#exsistingfiletable > div').length;
        var fileuploadCount = $('#filetableuploads > div').length;
        if(ex_div!=0 || fileuploadCount!=0){
            $("#wrs_btn_submitbutton").removeAttr("disabled");
        }
        else{
            $("#wrs_btn_submitbutton").attr("disabled", "disabled");
        }
    });

// emailcheck
    $(document).on('change','.emailcheck',function(){
        var emailid=$('#wrs_tb_emailid').val();
        var empname=$('#wrs_tb_name').val();
        if(emailid!='')
        {
            $('.preloader').show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var exist_flag=xmlhttp.responseText;
                    if(exist_flag!=0){
                        show_msgbox("WORKER PROFILE SEARCH UPDATE",errmsg[3],"error",false)
                        $('#wrs_tb_emailid').val('');
                    }
                }
            }
        }
        var option="emailcheck";
        xmlhttp.open("POST","DB_WORKER_PROFILE_SEARCH.php?option="+option+"&emailid="+emailid+"&empname="+empname);
        xmlhttp.send();
    });
// worknocheck
    $(document).on('change','.worknocheck',function(){
        var wrkerno=$('#wrs_tb_number').val();
        var empname=$('#wrs_tb_name').val();
        if(wrkerno!='')
        {
            $('.preloader').show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var exist_flag=xmlhttp.responseText;
                    if(exist_flag!=0){
                        var msg=errmsg[3].replace('EMAIL ID','WORKER NUMBER');
                        show_msgbox("WORKER PROFILE SEARCH UPDATE",msg,"error",false)
                        $('#wrs_tb_number').val('');
                    }
                }
            }
        }
        var option="worknocheck";
        xmlhttp.open("POST","DB_WORKER_PROFILE_SEARCH.php?option="+option+"&workerno="+wrkerno+"&empname="+empname);
        xmlhttp.send();
    });
    var upload_count=0;
    $(document).on("click",'#wrs_btn_submitbutton', function (){
        $('.preloader').show();
        var formElement = document.getElementById("wrs_searchupdate");
        var rolechecked=$("input[name=roles1]:checked" ).val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var values=xmlhttp.responseText;
                if(values==1){
                    var msg=errmsg[4].replace('[NAME]',$('#wrs_tb_name').val());
                    show_msgbox("WORKER PROFILE SEARCH UPDATE",msg,"success",false);
                    removedfilename='undefined';
                    $('#update_form').hide();
                    $('#wrs_btn_submitbutton').attr("disabled", "disabled").hide();
                    $('#wrs_searchupdatebtn').hide();
                    $("#filetableuploads").empty();
                    $('#attachafile').text('Attach a file');
                    $('#exsistingfiletable').empty();
                    $("#wrs_searchupdate").find('input:text, input:password, input:file, select, textarea').val('');
                    $("#wrs_searchupdate").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
                    upload_count=0;
                    $('#temptextbox').val('');
                    commondata();
                }
                else if(values==0){
                    show_msgbox("WORKER PROFILE SEARCH UPDATE",errmsg[6],"error",false);
                }
                else{
                    show_msgbox("WORKER PROFILE SEARCH UPDATE",values,"error",false);
                }
            }
        }
        var option="UPDATE";
        xmlhttp.open("POST","DB_WORKER_PROFILE_SEARCH.php?option="+option+"&upload_count="+finalrow+"&oldfilename="+removedfilename+"&rolechecked="+rolechecked);
        xmlhttp.send(new FormData(formElement));
    });

    var finalrow;
    // button validation
    $(document).on('change blur','#wrs_searchupdate',function(){
        var empname=$('#wrs_tb_name').val();
        var empnumber=$('#wrs_tb_number').val();
        var nricno=$('#wrs_tb_nric').val();
        var contactno=$('#wrs_tb_permobile').val();
        var emailadd=$('#wrs_tb_emailid').val();
        var address=$('#wrs_ta_address').val();
        var role_id=$("input[name=roles1]").is(":checked");
        var rowCount=$("#filetableuploads > div").length;
        finalrow=$('#temptextbox').val();
        var count=0;
        for(var j=1;j<=finalrow;j++)
        {
            var data= $('#upload_filename'+j).val();
            if(data!='' && data!=undefined && data!=null)
            {
                count++;
            }
        }
        if(rowCount==count && empname!='' && empnumber!='' && nricno!='' && role_id==true && emailadd!='' && address!='' && contactno!='' && email_flag!=0)
        {
            $('#wrs_btn_submitbutton').removeAttr('disabled');
        }
        else
        {
            $('#wrs_btn_submitbutton').attr('disabled','disabled');
        }

    });
});
</script>
</head>
<body>
<form id="wrs_searchupdate" name="wrs_searchupdate" class="form-horizontal">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">WORKER PROFILE SEARCH</h2>
            </div>
            <div class="panel-body">
                <div id="update_form" hidden>
                    <fieldset disabled>
                        <div class="form-group">
                            <label id="wrs_lbl_name" class=" col-sm-2">WORKER NAME<em>*</em></label>
                            <div class="col-sm-3"> <input type="text" name="wrs_tb_name" id="wrs_tb_name" placeholder="Worker Name" maxlength="40" class="autosizealph form-control"/></div>
                            <input type="hidden" name="wrs_tb_rowid" id="wrs_tb_rowid"/>
                        </div>
                        <div class="form-group">
                            <label id="wrs_lbl_number" class=" col-sm-2">WORKER NUMBER<em>*</em></label>
                            <div class="col-sm-3"> <input type="text" name="wrs_tb_number" id="wrs_tb_number" placeholder="Worker Number" maxlength="10" class="alphanumeric form-control worknocheck"/></div>
                        </div>
                        <div class="form-group">
                            <label id="wrs_lbl_loginid" class=" col-sm-2" >LOGIN ID<em>*</em></label>
                            <div class="col-sm-3"><input type="text" name="wrs_tb_loginid" id="wrs_tb_loginid" placeholder="Login Id" maxlength="40" class="wrs_email_validate form-control logincheck"/></div>
                            <label id="wrs_lbl_email_errupd" class="errormsg  col-sm-2" ></label>
                        </div>
                        <div class="form-group">
                            <label name="wrs_lbl_pword" id="wrs_lbl_pword" class="col-sm-2">PASSWORD<em>*</em></label>
                            <div class="col-sm-3"><input type="text" name="wrs_tb_pword" id="wrs_tb_pword" class="chk_password form-control" maxlength="20" placeholder="Password"/></div>
                            <label id="wrs_lbl_passwrd_errupd" class="errormsg  col-sm-2"></label>
                        </div>
<!--                        <div class="form-group" id="wrs_tble_rolecreation"></div>-->
                        <div class="form-group">
                            <label name="wrs_lbl_emailid" id="wrs_lbl_emailid" class="col-sm-2">EMAIL ID<em>*</em></label>
                            <div class="col-sm-3"><input type="text" name="wrs_tb_emailid" id="wrs_tb_emailid" maxlength='50' placeholder="Email Id" class="form-control emailcheck"></div>
                            <div><label id="wrs_lbl_email_error" class="errormsg"></label></div>
                        </div>
                        <div class="form-group">
                            <label name="wrs_lbl_permobile" id="wrs_lbl_permobile" class="col-sm-2">CONTACT NO<em>*</em></label>
                            <div class="col-sm-3"><input type="text" name="wrs_tb_permobile" id="wrs_tb_permobile" placeholder="Contact No" maxlength='8' class="mobileno  valid numonlynozero form-control " style="width:120px" ></div>
                            <label id="wrs_lbl_validnumber" name="wrs_lbl_validnumber" class="errormsg"></label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2">NRIC NO<em>*</em></label>
                            <div class="col-sm-3"><input type="text"  name="wrs_tb_nric" id="wrs_tb_nric" maxlength="10" class="alphanumeric form-control" placeholder="NRIC No" style="width:120px"/></div>
                            <label id="wrs_lbl_passwrd_errupd" class="errormsg  col-sm-2"></label>
                        </div>
                        <div class="form-group">
                            <label name="wrs_lbl_address" id="wrs_lbl_address" class="col-sm-2">ADDRESS<em>*</em></label>
                            <div class="col-sm-10"> <textarea  name="wrs_ta_address" id="wrs_ta_address" placeholder="Address" maxlength="300" class="maxlength textareaupd form-control"></textarea>
                            </div>
                        </div>
                        <div>
                            <div id="exsistingfiletable" class="form-group"></div>
                            <div><input type="hidden" id="temptextbox" name="temptextbox"></div>
                            <div ID="filetableuploads" class="form-group row">
                            </div>
                        </div>
                        <div class="form-group">
                            <div id="attachprompt" class="col-sm-offset-2 col-sm-2"><img width="15" height="15" src="../image/paperclip.gif" border="0">
                                <a href="javascript:_addAttachmentFields('attachmentarea')" id="attachafile">Attach a file</a>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>