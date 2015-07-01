<?php
include "../FOLDERMENU.php";
?>
<script>
    $(document).ready(function(){
        $('#wrsu_searchbtn').attr('disabled','disabled').hide();
        commondata();
        loadtable();
        var value_array;
        function loadtable(){
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200)
                {
                    value_array=JSON.parse(xmlhttp.responseText);
                    $('.preloader').hide();
                    if(value_array.length!=0)
                    {
                        var DT_UPD_table_header='<table id="DT_tbl_htmltable" border="1"  cellspacing="0" class="srcresult"><thead  bgcolor="#6495ed" style="color:white"><tr><th></th><th style="text-align: center">WORKER NAME</th><th style="text-align: center">WORKER NO</th><th style="text-align: center">USER NAME</th><th style="text-align: center">ROLE</th><th style="text-align: center">EMAIL ID</th><th style="text-align: center">CONTACT NO</th><th style="text-align: center">USERSTAMP</th><th style="text-align: center">TIMESTAMP</th></tr></thead><tbody>';
                        $('#wrsu_searchbtn').attr('disabled','disabled');
                        $('#exsistingfiletable').empty();
                        $('#filetableuploads').empty();
                        $('#attachafile').text('Attach a file');
                        for(var i=0;i<value_array[0].length;i++){
                            var wrp_rowid=value_array[0][i].wrp_rowid;
                            var wrp_name=value_array[0][i].wrp_name;
                            var wrp_no=value_array[0][i].wrp_no;
                            var wrp_rcid=value_array[0][i].wrp_rcid;
                            var wrp_emailid=value_array[0][i].wrp_emailid;
                            var wrp_address=value_array[0][i].wrp_address;
                            var wrp_nricno=value_array[0][i].wrp_nricno;
                            var wrp_mobno=value_array[0][i].wrp_mobno;
                            var wrp_uname=value_array[0][i].wrp_uname;
                            var wrp_pswd=value_array[0][i].wrp_pswd;
                            var wrp_userstamp=value_array[0][i].wrp_userstamp;
                            var wrp_timestamp=value_array[0][i].wrp_timestamp;
                            var rcname=value_array[1];
                            DT_UPD_table_header+='<tr><td style="text-align: center"><input type="radio" name="DT_UPD_rd_flxtbl" class="DT_UPD_class_radio" id='+wrp_rowid+' value='+wrp_rowid+'></td><td nowrap>'+wrp_name+'</td><td style="text-align: center">'+wrp_no+'</td><td> '+wrp_uname+'</td><td style="text-align: center"> '+wrp_rcid+'</td><td> '+wrp_emailid+'</td><td style="text-align: center"> '+wrp_mobno+'</td><td >'+wrp_userstamp+'</td><td style="text-align: center" nowrap>'+wrp_timestamp+'</td></tr>';
                        }
                    }
                    else{
                        show_msgbox("WORKER PROFILE SEARCH UPDATE",errmsg[7],"error",false);
                        $('#DT_div_tablecontainer').hide();
                        $('#wrsu_searchbtn').hide();
                        $('#wrsu_searchupdate').hide();
                    }
                    DT_UPD_table_header+='</tbody></table>';
                    $('section').html(DT_UPD_table_header);
                    $('#DT_tbl_htmltable').DataTable({
                        "aSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers"
                    });
                }
            }
            $('#DT_div_tablecontainer').show();
            var option="search_data";
            xmlhttp.open("GET","DB_WORKER_PROFILE_SEARCH_UPDATE.php?option="+option);
            xmlhttp.send();
        }
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
        var email_flag=0;
        $(document).on("blur change",'#wrsu_tb_emailid', function (){
            var emailid=($('#wrsu_tb_emailid').val().toLowerCase());
            $('#wrsu_tb_emailid').val(emailid)
            var atpos=emailid.indexOf("@");
            var dotpos=emailid.lastIndexOf(".");
            if ((atpos<1 || dotpos<atpos+2 || dotpos+2>=emailid.length)||(/^[@a-zA-Z0-9-\\.]*$/.test(emailid) == false))
            {
                email_flag=1;
                show_msgbox("WORKER PROFILE SEARCH UPDATE",errmsg[1],"error",false);
                $('#wrsu_tb_emailid').addClass("invalid");
            }
            else{
                $('#wrsu_tb_emailid').removeClass("invalid");
                email_flag=0;
            }
        });

        $(document).on("keyup",'.wrsu_email_validate',function() {
            $(this).val().toLowerCase();
            $('#wrsu_tb_loginid').val($('#wrsu_tb_loginid').val().toLowerCase());
            if (this.value.match(/[^a-zA-Z0-9\_\.\@]/g)) {
                this.value = this.value.replace(/[^a-zA-Z0-9\_\.\@]/g, '');
            }
        });
        var pass_flag=0;
        $(document).on("change blur",'.chk_password',function() {
            var wr_pass_length=($('#wrsu_tb_pword').val()).length;
            if(wr_pass_length<8){

                show_msgbox("WORKER PROFILE SEARCH UPDATE",errmsg[9],"error",false);
                $('#wrsu_tb_pword').addClass("invalid");
                pass_flag=1;
            }
            else{
                $('#wrsu_tb_pword').removeClass("invalid");
                pass_flag=0;
            }
        });
        var contact_flag=0;
        $(document).on("change blur",'.mobileno',function() {
            var wrsu_pass_length=($('#wrsu_tb_permobile').val()).length;
            if(wrsu_pass_length<8){
                var msg=errmsg[0].toString().replace('[MOB NO]',$('#wrsu_tb_permobile').val());
                show_msgbox("WORKER PROFILE SEARCH UPDATE",msg,"error",false);
                $('#wrsu_tb_permobile').addClass("invalid");
                contact_flag=1;
            }
            else{
                $('#wrsu_tb_permobile').removeClass("invalid");
                contact_flag=0;
            }
        });
//LOAD COMMON DATA
        var arrayfilename=[];
        var errmsg=[];
        var role;
        var wrsu_role_array=[];
        function commondata(){
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var value_array=JSON.parse(xmlhttp.responseText);
                    $('.preloader').hide();
                    arrayfilename=value_array[0];
                    errmsg=value_array[1];
                    role=value_array[2];
                }
            }
            var option="COMMON_DATA";
            xmlhttp.open("GET","DB_WORKER_PROFILE_SEARCH_UPDATE.php?option="+option);
            xmlhttp.send();
        }
//CLICK EVENT FOR  RADIO BUTTON
        $(document).on('click','.DT_UPD_class_radio',function(){
            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
            $('#exsistingfiletable').empty();
            $('#filetableuploads').empty();
            $('#attachafile').text('Attach a file');
            $('#wrsu_searchbtn').removeAttr('disabled').show();
            $('#update_form').hide();
            $('#temptextbox').val('');
            $('#wrsu_tb_emailid').removeClass("invalid");
            $('#wrsu_tb_pword').removeClass("invalid");
            $('#wrsu_tb_permobile').removeClass("invalid");
            DT_idradiovalue='';
            email_flag=0;
            contact_flag=0;
            pass_flag=0;
        });
//CLICK EVENT FOR SEARCH BUTTON
        var filenameinarray=[];
        var DT_idradiovalue='';
        $(document).on('click','#wrsu_searchbtn',function(){
            DT_idradiovalue=$('input:radio[name=DT_UPD_rd_flxtbl]:checked').attr('id');
            for(var k=0;k<value_array[0].length;k++){
                var id=value_array[0][k].wrp_rowid;
                if(id==DT_idradiovalue)
                {
                    $('#update_form').show();
                    $('#wrsu_btn_submitbutton').attr('disabled','disabled').show();
                    $('#wrsu_searchbtn').attr('disabled','disabled');
                    $('#exsistingfiletable').empty();
                    $('#filetableuploads').empty();
                    $('#attachafile').text('Attach a file');
                    $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                    var wrp_rowid=value_array[0][k].wrp_rowid;
                    var wrp_name=value_array[0][k].wrp_name;
                    var wrp_no=value_array[0][k].wrp_no;
                    var wrp_rcid=value_array[0][k].wrp_rcid;
                    var wrp_emailid=value_array[0][k].wrp_emailid;
                    var wrp_address=value_array[0][k].wrp_address;
                    var wrp_nricno=value_array[0][k].wrp_nricno;
                    var wrp_mobno=value_array[0][k].wrp_mobno;
                    var wrp_uname=value_array[0][k].wrp_uname;
                    var wrp_pswd=value_array[0][k].wrp_pswd;
                    var rcname=value_array[1];
                    $('#wrsu_tb_rowid').val(wrp_rowid);
                    $('#wrsu_tb_name').val(wrp_name);
                    $('#wrsu_tb_number').val(wrp_no);
                    $('#wrsu_tb_loginid').val(wrp_uname);
                    $('#wrsu_tb_pword').val(wrp_pswd);
                    $('#wrsu_tb_nric').val(wrp_nricno);
                    $('#wrsu_tb_permobile').val(wrp_mobno);
                    $('#wrsu_tb_emailid').val(wrp_emailid);
                    $('#wrsu_ta_address').val(wrp_address);
                    $('#wrsu_tble_rolecreation').empty();
                    var wrsu_roles='';
                    for (var i = 0; i < rcname.length; i++){
                        var value=rcname[i][1];
                        var id1="wrsu_role_array"+i;
                        if(wrp_rcid=='SUPER ADMIN'){
                            if(rcname[i][0]==wrp_rcid){
                                if(i==0){
                                    var wrsu_roles='<label class="col-lg-2 control-label" style="white-space: nowrap!important;">ROLE ACCESS<em>*</em></label>'
                                    wrsu_roles+= '<div class="col-lg-10"><div class="radio"><label><input type="radio" name="roles1" id='+id1+' value='+value+' class="wrsu_class_role1 tree login_submitvalidate" checked> ' +rcname[i][0] + '</label></div></div>';
                                    $('#wrsu_tble_rolecreation').append(wrsu_roles);
                                }
                                else{
                                    wrsu_roles='<div class="col-sm-offset-2 col-lg-10"><div class="radio"><label style="white-space: nowrap!important;"><input type="radio" name="roles1" id='+id1+' value='+value+' class="wrsu_class_role1 tree login_submitvalidate" checked> ' + rcname[i][0] + '</label></div></div>';
                                    $('#wrsu_tble_rolecreation').append(wrsu_roles);
                                }
                            }
                        }
                        if(rcname[i][0]==wrp_rcid){
                            if(i==0){
                                var wrsu_roles='<label class="col-lg-2 control-label" style="white-space: nowrap!important;">ROLE ACCESS<em>*</em></label>'
                                wrsu_roles+= '<div class="col-lg-10"><div class="radio"><label><input type="radio" name="roles1" id='+id1+' value='+value+' class="wrsu_class_role1 tree login_submitvalidate" checked> ' +rcname[i][0] + '</label></div></div>';
                                $('#wrsu_tble_rolecreation').append(wrsu_roles);
                            }
                            else if(i!=0 && rcname[i][0]!='SUPER ADMIN'){
                                wrsu_roles='<div class="col-sm-offset-2 col-lg-10"><div class="radio"><label style="white-space: nowrap!important;"><input type="radio" name="roles1" id='+id1+' value='+value+' class="wrsu_class_role1 tree login_submitvalidate" checked> ' + rcname[i][0] + '</label></div></div>';
                                $('#wrsu_tble_rolecreation').append(wrsu_roles);
                            }
                        }
                        else{
                            if(i==0){
                                var wrsu_roles='<label class="col-lg-2 control-label" style="white-space: nowrap!important;">ROLE ACCESS<em>*</em></label>'
                                wrsu_roles+= '<div class="col-lg-10"><div class="radio"><label><input type="radio" name="roles1" id='+id1+' value='+value+' class="wrsu_class_role1 tree login_submitvalidate"> ' +rcname[i][0] + '</label></div></div>';
                                $('#wrsu_tble_rolecreation').append(wrsu_roles);
                            }
                            else if(i!=0 && rcname[i][0]!='SUPER ADMIN'){
                                wrsu_roles='<div class="col-sm-offset-2 col-lg-10"><div class="radio"><label style="white-space: nowrap!important;"><input type="radio" name="roles1" id='+id1+' value='+value+' class="wrsu_class_role1 tree login_submitvalidate"> ' + rcname[i][0] + '</label></div></div>';
                                $('#wrsu_tble_rolecreation').append(wrsu_roles);
                            }
                        }
                    }
                    var filenamein_array=value_array[0][k].wrp_filename;
                    var wrsu_folder_name=value_array[0][k].folder_id;
                    if(filenamein_array!=''){
                        filenameinarray=filenamein_array.split('/');
                        for(var j=0;j<filenameinarray.length;j++){
                            var name=wrsu_folder_name+"/"+filenameinarray[j];
                            var file_count='filecount'+j;
                            if(role=='ADMIN' || role=='SUPER ADMIN'){
                                var appendfile=' <div class="col-sm-offset-2 col-sm-10" style="padding-bottom: 5px" id='+file_count+'><a href="../LMC_LIB/download.php?filename='+name+'" class="links">'+filenameinarray[j]+'</a> <input type="button" id="Del" class="submit_enable" value="X" style="background-color:red;color:white;font-size:9;font-weight: bold;"/></div>';
                            }
                            else{
                                var appendfile=' <div class="col-sm-offset-2 col-sm-10" style="padding-bottom: 5px" id='+file_count+'><a href="../LMC_LIB/download.php?filename='+name+'" class="links">'+filenameinarray[j]+'</a></div>';
                                $('#attachprompt').hide();
                                $("#wrsu_btn_submitbutton").hide();
                            }
                            $('#exsistingfiletable').append(appendfile);
                        }
                    }
                }
            }
        });

        var removedfilename;
        //CLICK EVENT DELETE BUTTON
        $(document).on("click", "#Del", function (){
            $(this).closest("div").remove();
            var Count = $('#filetableuploads > div').length;
            if(Count==0)
            {
                $('#attachafile').text('Attach a file');
                $('#wrsu_btn_submitbutton').removeAttr('disabled');
            }
            else
            {
                $('#attachafile').text('Attach another file');
                $('#wrsu_btn_submitbutton').attr('disabled','disabled');
            }
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
        var filecnt;
        $(document).on('click', 'button.removebutton', function () {
            upload_count=upload_count-1;
            $(this).closest('div').remove();
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
                    $('#wrsu_btn_submitbutton').removeAttr("disabled");
                }
                else
                {
                    $('#wrsu_btn_submitbutton').attr("disabled", "disabled");
                }
            }
            if(rowcnt==0)
            {
                $('#attachafile').text('Attach a file');
                $('#wrsu_btn_submitbutton').removeAttr("disabled");
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
            $('#wrsu_btn_submitbutton').attr('disabled','disabled');
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

            var appendfile='<div class="col-sm-offset-2 col-sm-5" style="padding-bottom: 8px"><label class=""><input type="file" style="max-width:250px " class="fileextensionchk form-control submit_enable" id='+uploadfileid+' name='+uploadfileid+'></label><label class="inline" ><button  class="removebutton" value="-" title="Remove this row" style="background-color:red;color:white;font-size:9;font-weight: bold;"></button></label></div>';
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
// logincheck
        $(document).on('change blur','.logincheck',function(){
            var loginname=$('#wrsu_tb_loginid').val();
            var empid=DT_idradiovalue;
            if(loginname!='')
            {
                $('.preloader').show();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $('.preloader').hide();
                        var exist_flag=xmlhttp.responseText;
                        if(exist_flag!=0){
                            var msg=errmsg[5].replace('USER NAME','LOGIN ID');
                            msg=msg.replace('[NAME]',loginname);
                            show_msgbox("WORKER PROFILE SEARCH UPDATE",msg,"error",false);
                            $('#wrsu_tb_loginid').val('');
                            $('#wrsu_btn_submitbutton').attr("disabled", "disabled");
                        }
                    }
                }
            }
            var option="logincheck";
            xmlhttp.open("POST","DB_WORKER_PROFILE_SEARCH_UPDATE.php?option="+option+"&loginname="+loginname+"&empid="+empid);
            xmlhttp.send();
        });
// emailcheck
        $(document).on('change','.emailcheck',function(){
            var emailid=$('#wrsu_tb_emailid').val();
            var empid=DT_idradiovalue;
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
                                show_msgbox("WORKER PROFILE SEARCH UPDATE",errmsg[3],"error",false);
                                $('#wrsu_tb_emailid').val('');
                                $('#wrsu_btn_submitbutton').attr("disabled", "disabled");
                            }
                        }
                    }
                }
                var option="emailcheck";
                xmlhttp.open("POST","DB_WORKER_PROFILE_SEARCH_UPDATE.php?option="+option+"&emailid="+emailid+"&empid="+empid);
                xmlhttp.send();
            }
        });
// worknocheck
        $(document).on('change','.worknocheck',function(){
            var wrkerno=$('#wrsu_tb_number').val();
            var empid=DT_idradiovalue;
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
                            show_msgbox("WORKER PROFILE SEARCH UPDATE",msg,"error",false);
                            $('#wrsu_tb_number').val('');
                            $('#wrsu_btn_submitbutton').attr("disabled", "disabled");
                        }
                    }
                }
            }
            var option="worknocheck";
            xmlhttp.open("POST","DB_WORKER_PROFILE_SEARCH_UPDATE.php?option="+option+"&workerno="+wrkerno+"&empid="+empid);
            xmlhttp.send();
        });

        var finalrow;
        var final_row;
        // button validation
        $(document).on("change blur click",'.submit_enable',function(){
            var ex_div=$('#exsistingfiletable > div').length;
            var fileuploadCount = $('#filetableuploads > div').length;
            if(ex_div!=0 || fileuploadCount!=0)
            {
                final_row=$('#temptextbox').val();
                var count=0;
                for(var j=1;j<=final_row;j++)
                {
                    var data= $('#upload_filename'+j).val();
                    if(data!='' && data!=undefined && data!=null)
                    {
                        count++;
                    }
                }
                if(fileuploadCount==count)
                {
                    $('#wrsu_btn_submitbutton').removeAttr("disabled");
                }
                else
                {
                    $('#wrsu_btn_submitbutton').attr("disabled", "disabled");
                }
            }
        });

        $(document).on('change blur','.submitenable',function(){
            var empname=$('#wrsu_tb_name').val();
            var login=$('#wrsu_tb_loginid').val();
            var paswrd=$('#wrsu_tb_pword').val();
            var empnumber=$('#wrsu_tb_number').val();
            var nricno=$('#wrsu_tb_nric').val();
            var contactno=$('#wrsu_tb_permobile').val();
            var emailadd=$('#wrsu_tb_emailid').val();
            var address=$('#wrsu_ta_address').val();
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
            if(rowCount==count && empname!='' && login!='' && paswrd!='' && empnumber!='' && nricno!='' && role_id==true
                && emailadd!='' && address!='' && contactno!='' && email_flag==0 && contact_flag==0 && pass_flag==0)
            {
                $('#wrsu_btn_submitbutton').removeAttr('disabled');
            }
            else
            {
                $('#wrsu_btn_submitbutton').attr('disabled','disabled');
            }

        });
        var upload_count=0;
        $(document).on("click",'#wrsu_btn_submitbutton', function (){
            $('.preloader').show();
            var formElement = document.getElementById("wrsu_searchupdate");
            var rolechecked=$("input[name=roles1]:checked" ).val();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var wrkrname= $('#wrsu_tb_name').val();
                    var values=xmlhttp.responseText;
                    if(values==1){
                        var msg=errmsg[4].replace('[NAME]',wrkrname);
                        show_msgbox("WORKER PROFILE SEARCH UPDATE",msg,"success",false);
                        removedfilename='undefined';
                        $('#update_form').hide();
                        $('#wrsu_btn_submitbutton').attr("disabled", "disabled").hide();
                        $('#wrsu_searchupdatebtn').hide();
                        $("#filetableuploads").empty();
                        $('#attachafile').text('Attach a file');
                        $('#exsistingfiletable').empty();
                        $("#wrsu_searchupdate").find('input:text, input:password, input:file, select, textarea').val('');
                        $("#wrsu_searchupdate").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
                        upload_count=0;
                        $('#temptextbox').val('');
                        commondata();
                        loadtable();
                        $('#wrsu_tb_emailid').removeClass("invalid");
                        $('#wrsu_tb_pword').removeClass("invalid");
                        $('#wrsu_tb_permobile').removeClass("invalid");
                        DT_idradiovalue='';
                        email_flag=0;
                        contact_flag=0;
                        pass_flag=0;
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
            xmlhttp.open("POST","DB_WORKER_PROFILE_SEARCH_UPDATE.php?option="+option+"&upload_count="+finalrow+"&oldfilename="+removedfilename+"&rolechecked="+rolechecked);
            xmlhttp.send(new FormData(formElement));
        });
    });
</script>
</head>
<body>
<form id="wrsu_searchupdate" name="wrsu_searchupdate" class="form-horizontal">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">WORKER PROFILE SEARCH AND UPDATE</h2>
            </div>
            <div class="panel-body">
                <div class="table-responsive" id="DT_div_tablecontainer" hidden>
                    <section>
                    </section>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <div class="input-group">
                            <button type="button" id="wrsu_searchbtn" class="btn btn-info" disabled>SEARCH</button>
                        </div>
                    </div>
                </div>

                <div id="update_form" hidden>
                    <fieldset>
                        <div class="form-group">
                            <label id="wrsu_lbl_name" class=" col-sm-2">WORKER NAME<em>*</em></label>
                            <div class="col-sm-3"> <input type="text" name="wrsu_tb_name" id="wrsu_tb_name" placeholder="Worker Name" maxlength="40" class="autosizealph form-control submitenable"/></div>
                            <input type="hidden" name="wrsu_tb_rowid" id="wrsu_tb_rowid"/>
                        </div>
                        <div class="form-group">
                            <label id="wrsu_lbl_number" class=" col-sm-2">WORKER NUMBER<em>*</em></label>
                            <div class="col-sm-3"> <input type="text" name="wrsu_tb_number" id="wrsu_tb_number" placeholder="Worker Number" maxlength="10" class="alphanumeric form-control submitenable worknocheck"/></div>
                        </div>
                        <div class="form-group">
                            <label id="wrsu_lbl_loginid" class=" col-sm-2" >LOGIN ID<em>*</em></label>
                            <div class="col-sm-3"><input type="text" name="wrsu_tb_loginid" id="wrsu_tb_loginid" placeholder="Login Id" maxlength="40" class="wrsu_email_validate form-control submitenable logincheck"/></div>
                            <label id="wrsu_lbl_email_errupd" class="errormsg  col-sm-2" ></label>
                        </div>
                        <div class="form-group">
                            <label name="wrsu_lbl_pword" id="wrsu_lbl_pword" class="col-sm-2">PASSWORD<em>*</em></label>
                            <div class="col-sm-3"><input type="text" name="wrsu_tb_pword" id="wrsu_tb_pword" class="chk_password form-control submitenable" maxlength="20" placeholder="Password"/></div>
                            <label id="wrsu_lbl_passwrd_errupd" class="errormsg  col-sm-2"></label>
                        </div>
                        <div class="form-group" id="wrsu_tble_rolecreation">
                        </div>
                        <div class="form-group">
                            <label name="wrsu_lbl_emailid" id="wrsu_lbl_emailid" class="col-sm-2">EMAIL ID<em>*</em></label>
                            <div class="col-sm-3"><input type="text" name="wrsu_tb_emailid" id="wrsu_tb_emailid" maxlength='50' placeholder="Email Id" class="form-control emailcheck submitenable"></div>
                            <div><label id="wrsu_lbl_email_error" class="errormsg"></label></div>
                        </div>
                        <div class="form-group">
                            <label name="wrsu_lbl_permobile" id="wrsu_lbl_permobile" class="col-sm-2">CONTACT NO<em>*</em></label>
                            <div class="col-sm-3"><input type="text" name="wrsu_tb_permobile" id="wrsu_tb_permobile" placeholder="Contact No" maxlength='8' class="mobileno submitenable valid numonlynozero form-control " style="width:120px" ></div>
                            <label id="wrsu_lbl_validnumber" name="wrsu_lbl_validnumber" class="errormsg"></label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2">NRIC NO<em>*</em></label>
                            <div class="col-sm-3"><input type="text"  name="wrsu_tb_nric" id="wrsu_tb_nric" maxlength="10" class="alphanumeric form-control submitenable" placeholder="NRIC No" style="width:120px"/></div>
                            <label id="wrsu_lbl_passwrd_errupd" class="errormsg  col-sm-2"></label>
                        </div>
                        <div class="form-group">
                            <label name="wrsu_lbl_address" id="wrsu_lbl_address" class="col-sm-2">ADDRESS<em>*</em></label>
                            <div class="col-sm-10"> <textarea  name="wrsu_ta_address" id="wrsu_ta_address" placeholder="Address" maxlength="300" class="maxlength textareaupd submitenable form-control"></textarea>
                            </div>
                        </div>
                        <div>
                            <div id="exsistingfiletable" class="form-group"></div>
                            <div><input type="hidden" id="temptextbox" name="temptextbox"></div>
                            <div ID="filetableuploads" class="form-group row submitenable">
                            </div>
                        </div>
                        <div class="form-group">
                            <div id="attachprompt" class="col-sm-offset-2 col-sm-2"><img width="15" height="15" src="../image/paperclip.gif" border="0">
                                <a href="javascript:_addAttachmentFields('attachmentarea')" id="attachafile">Attach a file</a>
                            </div>
                        </div>
                        <div class="col-sm-offset-10 col-sm-2">
                            <input class="btn btn-lg btn-info" type="button"  id="wrsu_btn_submitbutton" name="SAVE" value="UPDATE" disabled/>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>