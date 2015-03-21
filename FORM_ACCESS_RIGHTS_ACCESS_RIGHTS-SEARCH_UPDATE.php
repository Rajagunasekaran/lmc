<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************ACCESS_RIGHTS_SEARCH_UPDATE*********************************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:02/03/2015 ED:03/03/2015,TRACKER NO:79 DESC:Added gender field,changed validation nd query,new sp tested via form,changed mail part also
//*********************************************************************************************************//-->
<?php
include "NEW_MENU.php";

?>
<!--SCRIPT TAG START-->
<!DOCTYPE html>
<html lang="en">
<script>
var upload_count=0;
//START DOCUMENT READY FUNCTION
$(document).ready(function(){

    $('#URSRC_lb_selectteam').hide();
//    $('.preloader').show();
    $('#filetableuploads').html('');
    //reomve file upload row
    $(document).on('click', 'button.removebutton', function () {

        $(this).closest('div').remove();
        var rowCount = $('#filetableuploads > div').length;
        if(rowCount!=0)
        {
            $('#attachafile').text('Attach another file');
        }
        else
        {
            $('#attachafile').text('Attach a file');
        }
        return false;
    });
    //file extension validation
    $(document).on("change",'.fileextensionchk', function (){
        for(var i=0;i<25;i++)
        {
            var data= $('#upload_filename'+i).val();
            var datasplit=data.split('.');
            var ext=datasplit[1].toUpperCase();
            if(ext=='PDF'|| ext=='JPG'|| ext=='PNG' || ext=='JPEG' || data==undefined || data=="")
            {
                loginbuttonvalidation();
            }
            else
            {
                show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[28],"error",false);
                reset_field($('#upload_filename'+i));
            }
        }
    });
    //file upload reset
    function reset_field(e) {
        e.wrap('<form>').parent('form').trigger('reset');
        e.unwrap();
    }
    //add file upload row
    $(document).on("click",'#attachprompt', function (){
        if($('#filetableuploads > div').length==0){
            $('#filetableuploads > div').html('');

        }

//        $('#attachafile').text('Attach another file');
        var tablerowCount = $('#filetableuploads > div').length;
        var uploadfileid="upload_filename"+tablerowCount;
        var appendfile='<div class="col-sm-offset-2 col-sm-10"><label class=""><input type="file" style="max-width:250px " class="fileextensionchk form-control" id='+uploadfileid+' name='+uploadfileid+'></label><label class="inline" ><button  class="removebutton" value="-" title="Remove this row" style="background-color:red;color:white;font-size:10;font-weight: bold;"></button></label></div>';
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
    var URSRC_menuname=[];
    var URSRC_submenu=[];
    var URSRC_subsubmenu=[];
    var URSRC_checked_mpid=[];
    var sub_menu1_id=[];
    var URSRC_basicradio_value;
    $('#URSRC_btn_login_submitbutton').hide();
    $('#URSRC_btn_submitbutton').hide();
    var URSRC_multi_array=[];
    var URSRC_rolecreation_array=[];
    var URSRC_basicrole_profile_array=[];
    var URSRC_userrigths_array=[];
    var URSRC_errorAarray=[];
    var URSRC_emptype_array=[];
    var URSRC_comp_sdate;
    var URSRC_team_array=[];
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $('.preloader').hide();
            $('#RPT').hide();
            $('#AE').hide();
            var value_array=JSON.parse(xmlhttp.responseText);
            URSRC_rolecreation_array=value_array[0];
            URSRC_userrigths_array=value_array[1];
            URSRC_basicrole_profile_array=value_array[3];
            URSRC_errorAarray=value_array[4];
            URSRC_emptype_array=value_array[5];
            URSRC_comp_sdate=value_array[6];
            URSRC_team_array=value_array[7];
            var emp_type='<option value="SELECT">SELECT</option>';
            for(var k=0;k<URSRC_emptype_array.length;k++){
                emp_type += '<option value="' + URSRC_emptype_array[k] + '">' + URSRC_emptype_array[k] + '</option>';
            }
            $('#URSRC_lb_selectemptype').html(emp_type);
            var team='<option value="SELECT">SELECT</option>';
            if(URSRC_team_array.length!=0){
                var team='<option value="SELECT">SELECT</option>';
                for(var k=0;k<URSRC_team_array.length;k++){
                    team += '<option value="' + URSRC_team_array[k] + '">' + URSRC_team_array[k] + '</option>';
                }
                $('#URSRC_lb_selectteam').html(team);
            }
            else{
                $('#URSRC_lb_selectteam').replaceWith('<input type="text"  name="URSRC_lb_selectteam" id="URSRC_lb_selectteam" class="login_submitvalidate form-control upper check_team" /><label id="URSRC_lbl_team_err" class="errormsg"></label>');
                $('#URSRC_btn_add').hide();
            }
            var URSRC_basicrole_radio='<label class=" col-sm-2" style="white-space: nowrap!important;">SELECT BASIC ROLE</label>'
            var URSRC_basicroleprofile_radio='<label class=" col-sm-2" style="white-space: nowrap!important;">SELECT BASIC ROLE <em>*</em></label>'
            for(var j=0;j<URSRC_basicrole_profile_array.length;j++){
                var basic_roleprofile_value=URSRC_basicrole_profile_array[j].replace(" ","_")
                URSRC_basicroleprofile_radio+='<div class="col-sm-offset-2 col-sm-10"><label class=" col-sm-2" style="white-space: nowrap!important;"><input type="checkbox" name="URSRC_cb_basicroles1[]" id='+basic_roleprofile_value+' value='+basic_roleprofile_value+' class="URSRC_class_basicroles_chk tree"/>'+URSRC_basicrole_profile_array[j]+'</label></div>';
            }
            $('#URSRC_tble_basicroles_chk').html(URSRC_basicroleprofile_radio);
            //BASIC ROLE ENTRY
            if(URSRC_userrigths_array.length!=0){
                var URSRC_role_radio='<label class=" col-sm-2" style="white-space: nowrap!important;">SELECT ROLE ACCESS</label>'
                var URSRC_basicrole_radio='<label class=" col-sm-2" style="white-space: nowrap!important;">SELECT BASIC ROLE</label>'
                for (var i = 0; i < URSRC_userrigths_array.length; i++) {
                    var id="URSRC_tble_table"+i
                    var id1="URSRC_userrigths_array"+i;
                    var value=URSRC_userrigths_array[i].replace(" ","_")
                    URSRC_role_radio+='<div class="col-sm-offset-2 col-sm-10"><label style="white-space: nowrap!important;"><input type="radio" name="basicroles" id='+id1+' value='+value+' class="URSRC_class_basicroles"  />' + URSRC_userrigths_array[i] + '</label></div>';
                    URSRC_basicrole_radio+='<div class="col-sm-offset-2 col-sm-10"><label  style="white-space: nowrap!important;"><input type="radio" name="URSRC_radio_basicroles1" id='+value+i+' value='+value+' class="URSRC_class_basic"/>'+URSRC_userrigths_array[i]+'</label></div>';
                }
                $('#URSRC_tble_roles').html(URSRC_role_radio);
                $('#URSRC_tble_basicroles').html(URSRC_basicrole_radio);
            }
            else
            {
                var msg=URSRC_errorAarray[12].replace("[USERID]",UserStamp);
                $('#URSRC_form_user_rights').replaceWith('<p><label class="errormsg">'+msg+'</label></p>');
            }
            $(".title_alpha").prop("title",URSRC_errorAarray[0]);
            $(".title_nos").prop("title",URSRC_errorAarray[1]);
        }
    }
    var option="ACCESS_RIGHTS_SEARCH_UPDATE";
    xmlhttp.open("GET","COMMON.php?option="+option);
    xmlhttp.send();
    //END BASIC ROLECREATION
    //DATE PICKER FUNCTION
    $('.datepicker').datepicker({
        dateFormat:"dd-mm-yy",changeYear:true,changeMonth:true
    });
    //MAX DATE SETTING
    $( '.datepicker' ).datepicker( "option", "maxDate", new Date() );
    //SET DOB DATEPICKER
    var EMP_ENTRY_d = new Date();
    var EMP_ENTRY_year = EMP_ENTRY_d.getFullYear() - 18;
    EMP_ENTRY_d.setFullYear(EMP_ENTRY_year);
    $('#URSRC_tb_dob').datepicker(
        {
            dateFormat: 'dd-mm-yy',
            changeYear: true,
            changeMonth: true,
            yearRange: '1920:' + EMP_ENTRY_year + '',
            defaultDate: EMP_ENTRY_d
        });
    var pass_changedmonth=new Date(EMP_ENTRY_d.setFullYear(EMP_ENTRY_year));
    $('#URSRC_tb_dob').datepicker("option","maxDate",pass_changedmonth);
//END DATE PICKER FUNCTION
    //DO VALIDATION START
    $(".alphanumericdot").doValidation({rule:'alphanumeric',prop:{allowdot:true}});
    $('.autosize').doValidation({rule:'general',prop:{autosize:true}});
    $('#URSRC_tb_customrole').doValidation({rule:'alphanumeric',prop:{whitespace:true,autosize:true,uppercase:true}});
    $(".autosizealph").doValidation({rule:'alphabets',prop:{whitespace:true,autosize:true}});
    $("#URSRC_ENTRY_tb_mobile").doValidation({rule:'numbersonly',prop:{realpart:10,leadzero:true}});
    $("#URSRC_ENTRY_tb_permobile").doValidation({rule:'numbersonly',prop:{realpart:10,leadzero:true}});
    $('textarea').autogrow({onInitialize: true});
    $(".mobileno").doValidation({rule:'numbersonly',prop:{realpart:8,leadzero:true}});
    $(".accntno").doValidation({rule:'numbersonly',prop:{leadzero:true}});
    $(".alphanumeric").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:false,autosize:true}});
    $(".alphanumericuppercse").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:true,autosize:true}});
    //DO VALIDATION END
    //BASIC ROLE MENU CREATION CLICK FUNCTION
    $(document).on('click','#URSRC_radio_basicrolemenucreation',function(){
        flag=0;
        $('#URSRC_lbl_header').text("BASIC ROLE MENU CREATION").show();
        $('#URSRC_tble_basicroles').show();
        $('#URSRC_lbl_nodetails_err').hide()
        $('#URSRC_tble_basicrolemenucreation').show();
        $('#URSRC_lbl_login_role').hide();
        $('#URSRC_lbl_joindate').hide().val("");
        $('#URSRC_tb_joindate').hide().val("");
        $('#URSRC_tb_loginid').hide();
        $('#URSRC_lbl_loginid').hide();
        $('#URSRC_btn_submitbutton').val("CREATE").hide()
        $('#URSRC_tble_rolesearch').hide();
        $('#URSRC_btn_login_submitbutton').attr("disabled","disabled").hide();
        $('#URSRC_tb_customrole').val("");
        $('#URSRC_tble_rolecreation').empty().hide();
        $('#URSRC_tble_role').hide();
        $('#URSRC_tble_login').hide();
        $('#URSRC_lbl_basicrole_err').hide()
        $('#URSRC_tble_menu').hide();
        $('#URSRC_tble_folder').hide();
        $('#URSRC_tble_roles').hide()
        $('#URSRC_tble_basicroles_chk ').hide()
        $('input:radio[name=URSRC_radio_basicroles1]').attr('checked',false);
        $('input[name=URSRC_cb_basicroles1]').attr('checked',false);
        $('#URSRC_table_employeetbl').hide();
        $('#URSRC_table_others').hide();
        $('#URSRC_submitupdate').hide();
        $('#URSRC_lbl_role_err').hide();
        $('#URSRC_lbl_validnumber').hide();
        $('#URSRC_lbl_validnumber1').hide();
        $("#filetableuploads").empty();
        $('#exsistingfiletable').empty();
        $('#URSRC_lbl_team_err').hide();
        $('#attachafile').text('Attach a file');
    });
    //BASIC ROLE MENU SEARCH/UPDATE CLICK FUNCTION
    $('#URSRC_radio_basicrolemenusearchupdate').click(function(){
        flag=0;
        $('#URSRC_lbl_header').text("BASIC ROLE MENU SEARCH UPDATE").show()
        $('#URSRC_lbl_login_role').hide();
        $('#URSRC_lbl_joindate').hide().val("");
        $('#URSRC_lbl_nodetails_err').hide()
        $('#URSRC_tb_joindate').hide().val("");
        $('#URSRC_lbl_basicrole_err').hide()
        $('#URSRC_tb_loginid').hide();
        $('#URSRC_lbl_loginid').hide();
        $('#URSRC_lbl_basicrole_err').hide()
        $('#URSRC_btn_submitbutton').val("UPDATE").hide()
        $('#URSRC_tble_rolesearch').hide();
        $('#URSRC_btn_login_submitbutton').attr("disabled","disabled").hide();
        $('#URSRC_tb_customrole').val("");
        $('#URSRC_tble_rolecreation').hide();
        $('#URSRC_tble_role').hide();
        $('#URSRC_tble_roles').hide()
        $('#URSRC_tble_login').hide();
        $("#URSRC_lbl_email_error").hide();
        $('#URSRC_tble_menu').hide();
        $('#URSRC_tble_folder').hide();
        $('#URSRC_tble_basicroles').show();
        $('#URSRC_tble_basicrolemenucreation').show();
        $('#URSRC_tble_basicroles_chk ').hide()
        $('input[name=URSRC_cb_basicroles1]').prop('checked',false);
        $('input:radio[name=URSRC_radio_basicroles1]').attr('checked',false);
        $('#URSRC_table_employeetbl').hide();
        $('#URSRC_table_others').hide();
        $('#URSRC_submitupdate').hide();
        $('#URSRC_lbl_role_err').hide();
        $('#URSRC_lbl_validnumber').hide();
        $('#URSRC_lbl_validnumber1').hide();
        $("#filetableuploads").empty();
        $('#exsistingfiletable').empty();
        $('#URSRC_lbl_team_err').hide();
        $('#attachafile').text('Attach a file');
    });
    //ROLE CREATION CLICK FUNCTION
    $(document).on('click','#URSRC_radio_rolecreation',function(){
        flag=0;
        $('#URSRC_lbl_joindate').hide();
        $('#URSRC_tb_joindate').hide();
        $("#URSRC_lbl_email_error").hide();
        $('#URSRC_lbl_header').text("ROLE CREATION").show()
        $('#URSRC_submitupdate').hide();
        $('#URSRC_tble_search').hide();
        $('#URSRC_tble_login').hide();
        $('#URSRC_lbl_nodetails_err').hide()
        $('#URSRC_tb_loginid').val("");
        $('#URSRC_tble_role').show();
        $('#URSRC_table_employeetbl').hide();
        $('#URSRC_lbl_confirmpasswrd_errupd').hide();
        $('#URSRC_lbl_passwrd_errupd').hide();
        $('#URSRC_tble_rolesearch').hide();
        $('#URSRC_tble_menu').hide();
        $('#URSRC_tble_folder').hide();
        $('#URSRC_tb_joindate').val("");
        $('#URSRC_lbl_basicrole_err').hide()
        $('#URSRC_btn_submitbutton').val("CREATE").hide();
        $('#URSRC_btn_login_submitbutton').hide();
        $('#URSRC_tble_basicroles').hide();
        $('#URSRC_tble_rolecreation').hide();
        $('#URSRC_tble_basicrolemenucreation').hide();
        $('#URSRC_tble_basicroles_chk ').hide()
        $('input:[name=URSRC_cb_basicroles1]').prop('checked',false);
        $('input:radio[name=URSRC_radio_basicroles1]').attr('checked',false);
        $('#URSRC_table_employeetbl').hide();
        $('#URSRC_table_others').hide();
        $('#URSRC_lbl_validnumber').hide();
        $('#URSRC_lbl_validnumber1').hide();
        $("#filetableuploads").empty();
        $('#URSRC_lbl_team_err').hide();
        $('#exsistingfiletable').empty();
        $('#attachafile').text('Attach a file');

    });
    var basicmenurolesresult=[];
    //WEHN BASIC ROLE CLICK IN BASIC MENU CREATION AND SEARCH/UPDATE FORM
    $(document).on("click",'.URSRC_class_basic', function (){
        $('.preloader').show();
        $('#URSRC_btn_submitbutton').hide();
        $('input[type=checkbox]').attr('checked', false);
        URSRC_basicradio_value=$(this).val();
        var role=$(this).val()
        role=role.replace("_"," ")
        //GOOGLE URSRC_check_basicrole
        var formElement = document.getElementById("URSRC_userrightsform");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var msg_alert=xmlhttp.responseText;
                if(msg_alert==1)
                {
                    $('.preloader').hide();
                    if($("input[name=URSRC_mainradiobutton]:checked").val()=="BASIC ROLE MENU CREATION"){
                        $('#URSRC_lbl_basicrole_err').hide();
                        $('#URSRC_tble_basicroles_chk').show();
                        //GOOGLE URSRC_loadmenu_basicrole(basic_role_menus)
                        URSRC_loadmenu_basicrole()
                    }
                    else{
                        $('.preloader').hide();
                        var msg=URSRC_errorAarray[16].toString().replace("[NAME]",$("input[name=URSRC_radio_basicroles1]:checked").val())
                        $('#URSRC_lbl_basicrole_err').text(msg).show();
                        $('#URSRC_tble_basicroles_chk').hide()
                        $('#URSRC_tble_menu').hide();
                        $('#URSRC_tble_folder').hide();
                        $('#URSRC_btn_submitbutton').attr("disabled","disabled").hide()
                    }
                }
                else{
                    if($("input[name=URSRC_mainradiobutton]:checked").val()=="BASIC ROLE MENU CREATION")
                    {
                        $('#URSRC_lbl_basicrole_err').text(URSRC_errorAarray[13]).show()
                        $('.preloader').hide();
                        $('#URSRC_tble_basicroles_chk').hide()
                        $('#URSRC_tble_menu').hide();
                        $('#URSRC_tble_folder').hide();
                    }
                    else{
                        $('input[name=URSRC_cb_basicroles1]').attr('checked',false);
                        var role=URSRC_basicradio_value
                        role=role.replace("_"," ")
                        $('#URSRC_lbl_basicrole_err').hide()
                        URSRC_loadbasicrole_menus(role)
                    }
                }
            }
        }
        var choice='URSRC_check_basicrolemenu';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.php?URSRC_basicradio_value="+URSRC_basicradio_value+"&option="+choice,true);
        xmlhttp.send(new FormData(formElement));
    });
    //BASIC ROLE CREATION FOR TRUE/FALSE
    function URSRC_loadmenu_basicrole()
    {
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var values_array=JSON.parse(xmlhttp.responseText);
                $('#URSRC_tble_basicroles_chk').show();
                URSRC_tree_view(values_array,'')
            }
        }
        var choice="URSRC_tree_view"
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.php?radio_value="+URSRC_basicradio_value+"&option="+choice,true);
        xmlhttp.send();
    }
    var basicmenurolesresult=[];
    //SUCCESS FUNCTION FOR BASIC ROLE MENUS
    function URSRC_loadbasicrole_menus(role)
    {
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                basicmenurolesresult=JSON.parse(xmlhttp.responseText);
                var URSRC_full_array=basicmenurolesresult[2];
                var URSRC_checked_mpid=basicmenurolesresult[0];
                var URSRC_basicrole_profile=basicmenurolesresult[1];
                //Funcion to load selected basic menu and roles for basic menu
                for(var j=0;j<URSRC_basicrole_profile_array.length;j++){
                    for(var i=0;i<URSRC_basicrole_profile.length;i++){
                        if(URSRC_basicrole_profile[i]==URSRC_basicrole_profile_array[j]){
                            var checkbox=URSRC_basicrole_profile[i].replace(" ","_")
                            $("#" + checkbox).prop( "checked", true );
                        }
                    }
                }
                $('#URSRC_tble_basicroles_chk').show()
                $('#URSRC_tble_basicrolemenusearch').show()
                URSRC_tree_view(URSRC_full_array,URSRC_checked_mpid);
            }
        }
        var choice="URSRC_loadbasicrole_menu"
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.php?URSRC_basicradio_value="+URSRC_basicradio_value+"&option="+choice,true);
        xmlhttp.send();
    }
    //CUSTOM ROLE CHANGE FUNCTION
    //FUNCTION TO CHECK CUSTOM ROLE ALREADY EXISTS
    $(document).on('blur','#URSRC_tb_customrole',function(){
        var URSRC_roleidval=$(this).val();
        if(URSRC_roleidval!=''){
            $('.preloader').show();
            $('#URSRC_tble_roles').hide()
            $('#URSRC_tble_menu').hide();
            $('#URSRC_tble_folder').hide();
            $('input:radio[name=basicroles]').attr('checked',false);
            $('#URSRC_btn_submitbutton').hide()
            $('#URSRC_lbl_role_err').hide();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var msgalert=JSON.parse(xmlhttp.responseText);
                    if(msgalert==0)
                    {
                        $('#URSRC_tble_roles').show();
                        $('#URSRC_lbl_role_err').hide()
                    }
                    else{
                        var msg=URSRC_errorAarray[5].replace('[NAME]',$('#URSRC_tb_customrole').val())
                        $('#URSRC_btn_submitbutton').attr("disabled","disabled")
                        $('#URSRC_lbl_role_err').text(msg).show()
                    }
                }
            }
            var choice='URSRC_check_role_id';
            xmlhttp.open("GET","DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.php?URSRC_roleidval="+URSRC_roleidval+"&option="+choice,true);
            xmlhttp.send();
        }
    });
    //LOGIN CREATION CLICK FUNCTION
    $('#URSRC_radio_logincreation').click(function(){

        flag=0;
        exist_flag=1;
        error_valid='valid';
        error_ext='valid';
        email_flag=0;
        $('#URSRC_lbl_header').text("LOGIN CREATION").show()
        $('#URSRC_tb_customrole').val("");
        $('#URSRC_lbl_nodetails_err').hide()
        $("#URSRC_lbl_email_error").hide();
        $('#URSRC_btn_submitbutton').val("UPDATE").hide()
        $("#URSRC_btn_update").html('')
        $('#URSRC_tble_rolesearch').hide();
        $('#URSRC_lbl_emptype').hide();
        $('#URSRC_lbl_selectloginid').hide();
        $('#URSRC_lbl_loginidupd').hide();
        $('#URSRC_tb_loginidupd').hide();
        $('#URSRC_lb_selectemptype').prop('selectedIndex',0).hide();
        $('#URSRC_lb_selectteam').prop('selectedIndex',0).hide();
        $('#URSRC_lbl_login_role').hide();
        $('#URSRC_lbl_joindate').hide();
        $('#URSRC_lbl_loginid').show();
        $('#URSRC_tb_joindate').hide();
        $('#URSRC_lbl_basicrole_err').hide()
        $('#URSRC_lb_selectloginid').hide();
        $('input:radio[name=basicroles]').attr('checked',false);
        $('#URSRC_tble_role').hide()
        $('#URSRC_tble_roles').hide()
        $('#URSRC_tble_menu').hide();
        $('#URSRC_tble_folder').hide();
        $('#URSRC_lbl_confirmpasswrd_errupd').hide();
        $('#URSRC_lbl_passwrd_errupd').hide();
        $('#URSRC_tble_login').show();
        $('#URSRC_tb_loginid').val('').show();
        $("#URSRC_lbl_email_err").hide()
        $("#URSRC_lbl_email_errupd").hide()
        $('#URSRC_tble_rolesearch').hide();
        $('#URSRC_tb_loginidupd').removeClass("invalid")
        $('#URSRC_tble_rolecreation').hide();
        $('#URSRC_tb_loginid').removeClass("invalid")
        $('#URSRC_lbl_nologin_err').hide()
        $('#URSRC_btn_login_submitbutton').attr("disabled","disabled").hide();
        $('#URSRC_tble_basicroles').hide();
        $('#URSRC_tble_basicrolemenucreation').hide();
        $('#URSRC_tble_basicroles_chk ').hide()
        $('input[name=URSRC_cb_basicroles1]').attr('checked',false);
        $('input:radio[name=URSRC_radio_basicroles1]').attr('checked',false);
        var PE_startdate=(URSRC_comp_sdate).split('-');
        var day=PE_startdate[0];
        var month=PE_startdate[1];
        var year=PE_startdate[2];
        PE_startdate=new Date(year,month-1,day);
        $('#URSRC_tb_joindate').datepicker("option","minDate",PE_startdate);
        $('#URSRC_tb_joindate').val('');
        $('#URSRC_table_employeetbl').hide();
        $('#URSRC_table_others').hide();
        $('#URSRC_tb_firstname').val('');
        $('#URSRC_tb_lastname').val('');
        $("input[name=URSRC_rd_gender]:checked").attr('checked',false);
        $('#URSRC_tb_dob').val('');
        $('#URSRC_tb_designation').val('');
        $('#URSRC_tb_permobile').val('');
        $('#URSRC_tb_kinname').val('');
        $('#URSRC_tb_relationhd').val('');
        $('#URSRC_tb_mobile').val('');
        $('#URSRC_tb_bnkname').val('');
        $('#URSRC_tb_brnchname').val('');
        $('#URSRC_tb_accntname').val('');
        $('#URSRC_tb_accntno').val('');
        $('#URSRC_tb_ifsccode').val('');
        $('#URSRC_tb_accntyp').val('');
        $('#URSRC_ta_brnchaddr').val('');
        $('#URSRC_ta_comments').val('');
        $('#URSRC_ta_address').val('');
        $('#URSRC_tb_emailid').val('');
        $('#URSRC_tb_emailid').removeClass('invalid');
        $('#URSRC_lbl_role_err').hide();
        $('#URSRC_lbl_validnumber').hide();
        $('#URSRC_lbl_validnumber1').hide();
        $('#URSRC_lbl_pword').hide();
        $('#URSRC_tb_pword').val('').hide();
        $('#URSRC_lbl_cpword').hide();
        $('#URSRC_tb_cpword').val('').hide();
        $("#filetableuploads").empty();
        $('#exsistingfiletable').empty();
        $('#URSRC_lbl_selectteam').hide();
        $('#URSRC_lb_selectteam').hide();
        $('#URSRC_lbl_nric').hide();
        $('#URSRC_tb_nric').val('').hide();
        $('#URSRC_btn_add').hide();
        $('#URSRC_lbl_team_err').hide();
        $('#attachafile').text('Attach a file');
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                $('.preloader',window.parent.document).hide();
                URSRC_team_array=JSON.parse(xmlhttp.responseText);
                $('#URSRC_lb_selectteam').replaceWith('<select id="URSRC_lb_selectteam" name="URSRC_lb_selectteam"  maxlength="40" class="login_submitvalidate form-control upper" hidden  ></select>')
                var team='<option value="SELECT">SELECT</option>';
                for(var k=0;k<URSRC_team_array.length;k++){
                    team += '<option value="' + URSRC_team_array[k] + '">' + URSRC_team_array[k] + '</option>';
                }
                $('#URSRC_lb_selectteam').html(team);
                $(this).val('ADD');
                $('#URSRC_lb_selectteam').hide();
            }
        }
        var choice="get_team"
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.php?option="+choice,true);
        xmlhttp.send();
    });
    var error_valid='valid';
    var error_ext='valid';
    var flag=0;
    var exist_flag=1;
    var email_flag=0;
    $(document).on("blur change",'#URSRC_tb_emailid', function (){

        var emailid=($('#URSRC_tb_emailid').val().toLowerCase());
        $('#URSRC_tb_emailid').val(emailid)
                var atpos=emailid.indexOf("@");
        var dotpos=emailid.lastIndexOf(".");
                    if ((atpos<1 || dotpos<atpos+2 || dotpos+2>=emailid.length)||(/^[@a-zA-Z0-9-\\.]*$/.test(emailid) == false))
            {
                        email_flag=1;
                $("#URSRC_lbl_email_error").text(URSRC_errorAarray[2]).show();
                $('#URSRC_tb_emailid').addClass("invalid")

            }
        else{
                        email_flag=0;
                        $("#URSRC_lbl_email_error").hide();
                        $('#URSRC_tb_emailid').removeClass("invalid")

                            }



    });


    $('.URSRC_email_validate').blur(function(){
//        $('.preloader').show();
        var URSRC_radio_button_select_value=$("input[name=URSRC_mainradiobutton]:checked").val();
        var URSRC_login_id=$(this).val().toLowerCase();
        var old_loginid=$('#URSRC_lb_selectloginid').val();
        if(URSRC_radio_button_select_value=='LOGIN SEARCH UPDATE'){
            if(old_loginid!=URSRC_login_id){

                URSRC_login_validate(URSRC_login_id);
            }
            else{
                error_valid='valid';
                exist_flag=1;
                $("#URSRC_lbl_email_errupd").hide();
                $('#URSRC_tb_loginidupd').removeClass("invalid")
                $('#URSRC_lbl_email_errupd').hide();
                error_ext='valid';
                var newloginid=($('#URSRC_tb_loginidupd').val().toLowerCase());
                $('#URSRC_tb_loginidupd').val(newloginid)
                loginbuttonvalidation();
            }
        }
        else{
            URSRC_login_validate(URSRC_login_id);
        }
    });
    //VALIDATION
    function URSRC_login_validate(URSRC_login_id){
        var URSRC_radio_button_select_value=$("input[name=URSRC_mainradiobutton]:checked").val();
        $('.URSRC_resizefunction').prop("size","20");
        var URSRC_login_id=URSRC_login_id;//$(this).val();

        if(URSRC_login_id.length>0)
        {
            error_valid='valid';
            if(URSRC_radio_button_select_value=="LOGIN CREATION"){
                $("#URSRC_lbl_email_err").hide();
                $('#URSRC_tb_loginid').removeClass("invalid")
                $('#URSRC_tb_loginid').val($('#URSRC_tb_loginid').val().toLowerCase())
                URSRC_login_id=$('#URSRC_tb_loginid').val();
            }
            else{
                error_valid='valid';
                $("#URSRC_lbl_email_errupd").hide();
                $('#URSRC_tb_loginidupd').removeClass("invalid")
                $('#URSRC_tb_loginidupd').val($('#URSRC_tb_loginidupd').val().toLowerCase())
                URSRC_login_id=$('#URSRC_tb_loginidupd').val();
            }
            URSRC_login_id=URSRC_login_id;//$(this).val();
            if(URSRC_login_id!=''){
                error_ext='valid';
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                            $('.preloader',window.parent.document).hide();
//                            $('.preloader').hide();

                        var msgalert=JSON.parse(xmlhttp.responseText);
                        var LoginId_exist=msgalert[0];
                        var URSRC_role_array=msgalert[1];
                        if(LoginId_exist==0)
                        {
                            exist_flag=1;
                            if(URSRC_radio_button_select_value=="LOGIN CREATION")
                            {
//
                                if(flag==0){
                                    $('#URSRC_tble_rolecreation').empty();
                                    var URSRC_roles=''
                                    for (var i = 0; i < URSRC_role_array.length; i++){
                                        var value=URSRC_role_array[i].replace(" ","_")
                                        var id1="URSRC_role_array"+i;
                                        if(i==0){
                                            var URSRC_roles='<label class=" col-sm-2 " style="white-space: nowrap!important;">SELECT ROLE ACCESS<em>*</em></label>'
                                            URSRC_roles+= '<div class=" col-sm-offset-2 col-sm-10"><label  style="white-space: nowrap!important;"><input type="radio" name="roles1" id='+id1+' value='+value+' class="URSRC_class_role1 tree login_submitvalidate"   />' + URSRC_role_array[i] + '</lable></div>';
                                            $('#URSRC_tble_rolecreation').append(URSRC_roles);
                                        }
                                        else{
                                            URSRC_roles= '<div class="col-sm-offset-2 col-sm-10 "><label  style="white-space: nowrap!important;"><input type="radio" name="roles1" id='+id1+' value='+value+' class="URSRC_class_role1 tree login_submitvalidate"   />' + URSRC_role_array[i] + '</lable></div>';
                                            $('#URSRC_tble_rolecreation').append(URSRC_roles);
                                        }
                                    }
                                }
                                $('#URSRC_lbl_login_role').show();
                                $('#URSRC_tble_rolecreation').show();
                                $('#URSRC_lbl_joindate').show();
                                $('#URSRC_lbl_pword').show();
                                $('#URSRC_tb_pword').show();
                                $('#URSRC_lbl_cpword').show();
                                $('#URSRC_tb_cpword').show();
                                $('#URSRC_tb_joindate').show();
                                $('#URSRC_lbl_emptype').show();
                                $('#URSRC_lb_selectemptype').show();
                                $('#URSRC_table_employeetbl').show();
                                $('#URSRC_table_others').show();
                                $('#URSRC_lbl_selectteam').show();
                                $('#URSRC_lb_selectteam').show();
                                $('#URSRC_lbl_nric').show();
                                $('#URSRC_tb_nric').show();
                                if(URSRC_team_array.length!=0){
                                    $('#URSRC_btn_add').show();
                                }
                                $('#URSRC_btn_login_submitbutton').val("CREATE").show();
                            }
                            else{
                                $('#URSRC_lbl_login_role').show();
                                $('#URSRC_tble_rolecreation').show();
                                $('#URSRC_lbl_emptype').show();
                                $('#URSRC_lbl_joindate').show();
                                $('#URSRC_tb_joindate').show();
                                $('#URSRC_lb_selectemptype').show();
                                $('#URSRC_submitupdate').show()
                                $('#URSRC_lbl_pword').hide();
                                $('#URSRC_tb_pword').hide();
                                $('#URSRC_lbl_cpword').hide();
                                $('#URSRC_tb_cpword').hide();
                            }
                            flag++;
                        }
                        else{
                            exist_flag=0;
                            var msg=URSRC_errorAarray[10].toString().replace("[NAME]",$('#URSRC_tb_loginid').val())
                            if(URSRC_radio_button_select_value=="LOGIN CREATION"){
                                $('#URSRC_lbl_email_err').text(msg).show();
                            }
                            else{
                                var msg=URSRC_errorAarray[10].toString().replace("[NAME]",$('#URSRC_tb_loginidupd').val())
                                $('#URSRC_lbl_email_errupd').text(msg).show();
                                $('#URSRC_submitupdate').attr("disabled","disabled").show();
                            }
                        }
                        loginbuttonvalidation();
                    }
                }
                var choice='check_login_id';
                xmlhttp.open("GET","DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.php?URSRC_login_id="+URSRC_login_id+"&option="+choice,true);
                xmlhttp.send();
            }
            else{
                error_ext='invalid';
//                    $('.preloader',window.parent.document).hide();
                if(URSRC_radio_button_select_value=="LOGIN CREATION"){
                    $('#URSRC_tble_rolecreation').show()
                    $('#URSRC_lbl_email_err').text(URSRC_errorAarray[17]).show()
                    $('#URSRC_tb_loginid').addClass("invalid");
                }
                else{
                    $('#URSRC_lbl_email_errupd').text(URSRC_errorAarray[17]).show()
                    $('#URSRC_tb_loginidupd').addClass("invalid");
                }
            }

            loginbuttonvalidation();
        }
        else{
//            $('.preloader',window.parent.document).hide();
            if(URSRC_radio_button_select_value=="LOGIN CREATION"){
                $('#URSRC_tble_rolecreation').empty().hide();
                $('#URSRC_lbl_joindate').hide();
                $('#URSRC_tb_joindate').val("").hide();
                $("#URSRC_lbl_email_err").hide();
                $('#URSRC_tb_loginid').removeClass("invalid")
                $('#URSRC_tb_emailid').removeClass("invalid");
                $('#URSRC_lbl_emptype').hide();
                $('#URSRC_table_employeetbl').hide();
                $('#URSRC_lbl_pword').hide();
                $('#URSRC_tb_pword').val('').hide();
                $("#URSRC_lbl_email_error").hide();
                $('#URSRC_lbl_cpword').hide();
                $('#URSRC_tb_cpword').val('').hide();
                $('#URSRC_table_others').hide();
                $('#URSRC_lb_selectemptype').hide();
                $('#URSRC_btn_login_submitbutton').hide();
                $('#URSRC_lbl_selectteam').hide();
                $('#URSRC_lb_selectteam').hide();
                $('#URSRC_lbl_nric').hide();
                $('#URSRC_tb_nric').hide();
                $('#URSRC_btn_add').hide();
                flag=0;
            }
            else{
                $('#URSRC_submitupdate').attr("disabled","disabled");
            }
        }
    }
//BUTTON VALIDATION FOR LOGIN CREATION
    function loginbuttonvalidation(){
        var URSRC_radio_button_select_value=$("input[name=URSRC_mainradiobutton]:checked").val();
        if(URSRC_radio_button_select_value=="LOGIN CREATION")
        {
            var login_id=$('#URSRC_tb_loginid').val();
            var role_id=$("input[name=roles1]").is(":checked")
            var gender=$("input[name=URSRC_rd_gender]").is(":checked")
            var URE_male=$("input[name=URSRC_rd_gender]:checked").val()=="MALE";
            var URE_female=$("input[name=URSRC_rd_gender]:checked").val()=="FEMALE";
            var join_date=$('#URSRC_tb_joindate').val();
            var emp_type=$('#URSRC_lb_selectemptype').val();
            var URSRC_Firstname= $("#URSRC_tb_firstname").val();
            var URSRC_Lastname =$("#URSRC_tb_lastname").val();
            var URSRC_tb_dob=$('#URSRC_tb_dob').val();
            var URSRC_empdesig =$("#URSRC_tb_designation").val();
            var URSRC_Mobileno = $("#URSRC_tb_permobile").val();
            var URSRC_kinname = $("#URSRC_tb_kinname").val();
            var URSRC_relationhd = $("#URSRC_tb_relationhd").val();
            var URSRC_mobile= $("#URSRC_tb_mobile").val();
            var URSRC_bnkname =$("#URSRC_tb_bnkname").val();
            var URSRC_tb_brnname=$('#URSRC_tb_brnchname').val();
            var URSRC_accname =$("#URSRC_tb_accntname").val();
            var URSRC_acctno = $("#URSRC_tb_accntno").val();
            var URSRC_ifsc = $("#URSRC_tb_ifsccode").val();
            var URSRC_accttyp = $("#URSRC_tb_accntyp").val();
            var URSRC_brnchaddr= $("#URSRC_ta_brnchaddr").val();
            var URSRC_address=$('#URSRC_ta_address').val();
            var URSRC_team=$('#URSRC_lb_selectteam').val();
            var URSRC_emailid=$('#URSRC_tb_emailid').val();
            if((URSRC_emailid!='')&&(email_flag==0)&&((URSRC_team!='SELECT' && $('#URSRC_btn_add').val()=='ADD')||(URSRC_team!=''&& $('#URSRC_btn_add').val()=='CLEAR'))&&(login_id!="SELECT")&&(URSRC_address!='')&&(pass_flag!=0)&&(incorrectflag!=0)&&(login_id!="")&&(exist_flag==1)&&(error_ext=='valid')&&(error_valid=='valid')&&(role_id!=false)&&(join_date!="")&& (emp_type!="SELECT")&& (URSRC_Firstname!='') && (URSRC_Lastname!='' ) && (URSRC_tb_dob!='' ) &&((URE_male==true) || (URE_female==true)) && (URSRC_empdesig!='' )&&( URSRC_Mobileno!='' && (parseInt($('#URSRC_tb_permobile').val())!=0)) && (URSRC_kinname!='')&& (URSRC_relationhd!='' )&& (URSRC_Mobileno.length>=8)&&(URSRC_mobile.length>=8 )&&(URSRC_brnchaddr!="")&&(URSRC_accttyp!="")&&(URSRC_ifsc!="")&&(URSRC_acctno!="")&&(URSRC_accname!="")&&(URSRC_tb_brnname!="")&&(URSRC_bnkname!=""))
            {
                $("#URSRC_btn_login_submitbutton").removeAttr("disabled");

            }
            else{
                $('#URSRC_btn_login_submitbutton').attr("disabled","disabled");
            }
        }
        else
        {
            var login_id=$('#URSRC_lb_selectloginid').val();
            var role_id=$("input[name=roles1]").is(":checked")
            var gender=$("input[name=URSRC_rd_gender]").is(":checked")
            var URE_male=$("input[name=URSRC_rd_gender]:checked").val()=="MALE";
            var URE_female=$("input[name=URSRC_rd_gender]:checked").val()=="FEMALE";
            var updatedloginid=$('#URSRC_tb_loginidupd').val();
            var join_date=$('#URSRC_tb_joindate').val();
            var emp_type=$('#URSRC_lb_selectemptype').val();
            var URSRC_Firstname= $("#URSRC_tb_firstname").val();
            var URSRC_Lastname =$("#URSRC_tb_lastname").val();
            var URSRC_tb_dob=$('#URSRC_tb_dob').val();
            var URSRC_empdesig =$("#URSRC_tb_designation").val();
            var URSRC_Mobileno = $("#URSRC_tb_permobile").val();
            var URSRC_kinname = $("#URSRC_tb_kinname").val();
            var URSRC_relationhd = $("#URSRC_tb_relationhd").val();
            var URSRC_mobile= $("#URSRC_tb_mobile").val();
            var URSRC_bnkname =$("#URSRC_tb_bnkname").val();
            var URSRC_tb_brnname=$('#URSRC_tb_brnchname').val();
            var URSRC_accname =$("#URSRC_tb_accntname").val();
            var URSRC_acctno = $("#URSRC_tb_accntno").val();
            var URSRC_ifsc = $("#URSRC_tb_ifsccode").val();
            var URSRC_accttyp = $("#URSRC_tb_accntyp").val();
            var URSRC_brnchaddr= $("#URSRC_ta_brnchaddr").val();
            var URSRC_address=$('#URSRC_ta_address').val();
            var URSRC_team=$('#URSRC_lb_selectteam').val();
            var URSRC_emailid=$('#URSRC_tb_emailid').val();
            if((URSRC_emailid!='')&&(email_flag==0)&&((URSRC_team!='SELECT' && $('#URSRC_btn_add').val()=='ADD')||(URSRC_team!=''&& $('#URSRC_btn_add').val()=='CLEAR'))&&(login_id!="SELECT")&&(URSRC_address!='')&&(exist_flag==1)&&(error_ext=='valid')&&(error_valid=='valid')&&(updatedloginid!='')&&(role_id!=false)&&(join_date!="")&& (emp_type!="SELECT")&& (URSRC_Firstname!='') && (URSRC_Lastname!='' ) && (URSRC_tb_dob!='' ) &&((URE_male==true) || (URE_female==true)) && (URSRC_empdesig!='' )&&( URSRC_Mobileno!='' && (parseInt($('#URSRC_tb_permobile').val())!=0)) && (URSRC_kinname!='')&& (URSRC_relationhd!='' )&& (URSRC_Mobileno.length>=8)&&(URSRC_mobile.length>=8 )&&(URSRC_brnchaddr!="")&&(URSRC_accttyp!="")&&(URSRC_ifsc!="")&&(URSRC_acctno!="")&&(URSRC_accname!="")&&(URSRC_tb_brnname!="")&&(URSRC_bnkname!="")){
                $("#URSRC_submitupdate").removeAttr("disabled")
            }
            else{
                $('#URSRC_submitupdate').attr("disabled","disabled");
            }
        }
    }
    //Login Submit button validation
    $(document).on("blur change",'.login_submitvalidate ', function (){
        loginbuttonvalidation();
    });
    //BLUR FUNCTION FOR MOBILE NUMBER VALIDATION
    $(document).on('blur','.valid',function(){
        var URSRC_Mobileno=$(this).attr("id");
        var URSRC_Mobilenoval=$(this).val();
        if(URSRC_Mobilenoval.length==8)
        {
            if(URSRC_Mobileno=='URSRC_tb_permobile')
                $('#URSRC_lbl_validnumber').hide();
            else
                $('#URSRC_lbl_validnumber1').hide();
        }
        else
        {
            if(URSRC_Mobileno=='URSRC_tb_permobile')
                $('#URSRC_lbl_validnumber').text(URSRC_errorAarray[24]).show();
            else
                $('#URSRC_lbl_validnumber1').text(URSRC_errorAarray[24]).show();
        }
    });
    //LOGIN SEARCH/UPDATE CLICK FUNCTION
    $('#URSRC_radio_loginsearchupdate').click(function(){
        $('.preloader').show();
//        $('.preloader',window.parent.document).show();
        flag=0;
        exist_flag=1;
        error_valid='valid';
        error_ext='valid';
        email_flag=0;
        var radio_value_loginidsearch=$(this).val();
        $('#URSRC_lbl_header').text("LOGIN SEARCH/UPDATE").show()
        $('#URSRC_tb_loginid').val("");
        $('#URSRC_btn_login_submitbutton').val("UPDATE").hide();
        $('#URSRC_lbl_nodetails_err').hide()
        $('input:radio[name=basicroles]').attr('checked',false);
        $('#URSRC_tble_role').hide()
        $("#URSRC_lbl_email_error").hide();
        $('#URSRC_tble_roles').hide()
        $('#URSRC_lbl_emptype').hide();
        $('#URSRC_lb_selectemptype').prop('selectedIndex',0).hide();
        $('#URSRC_lb_selectteam').prop('selectedIndex',0).hide();
        $('#URSRC_btn_submitbutton').val("UPDATE").hide();
        $('#URSRC_lbl_login_role').hide();
        $('#URSRC_lbl_basicrole_err').hide()
        $('#URSRC_lbl_joindate').hide().val("");
        $('#URSRC_tb_joindate').hide().val("");
        $('#URSRC_tb_loginid').hide();
        $('#URSRC_lbl_loginid').hide();
        $('#URSRC_tb_loginidupd').hide();
        $('#URSRC_tble_rolesearch').hide();
        $('#URSRC_tb_loginid').removeClass("invalid")
        $('#URSRC_tb_loginidupd').removeClass("invalid")
        $('#URSRC_tb_emailid').removeClass("invalid");
        $('#URSRC_tble_menu').hide();
        $("#URSRC_lbl_email_err").hide()
        $("#URSRC_lbl_email_errupd").hide()
        $('#URSRC_lbl_loginidupd').hide();
        $('#URSRC_tble_folder').hide();
        $('#URSRC_submitupdate').hide();
        $('#URSRC_lbl_confirmpasswrd_errupd').hide();
        $('#URSRC_lbl_passwrd_errupd').hide();
        $('#URSRC_btn_login_submitbutton').attr("disabled","disabled").hide();
        $('#URSRC_tb_customrole').val("");
        $('#URSRC_tble_rolecreation').empty().hide();
        $('#URSRC_tble_basicroles').hide();
        $('#URSRC_table_employeetbl').hide();
        $('#URSRC_table_others').hide();
        $('#URSRC_tble_basicrolemenucreation').hide();
        $('#URSRC_tble_basicroles_chk ').hide()
        $('input[name=URSRC_cb_basicroles1]').attr('checked',false);
        $('input:radio[name=URSRC_radio_basicroles1]').attr('checked',false);
        $('#URSRC_lbl_role_err').hide();
        $('#URSRC_lbl_validnumber').hide();
        $('#URSRC_lbl_validnumber1').hide();
        $("#filetableuploads").empty();
        $('#exsistingfiletable').empty();
        $('#attachafile').text('Attach a file');
        $('#URSRC_lbl_pword').val('').hide();
        $('#URSRC_tb_pword').val('').hide();
        $('#URSRC_lbl_cpword').val('').hide();
        $('#URSRC_tb_cpword').val('').hide();
        $('#URSRC_btn_add').hide();
        $('#URSRC_lbl_selectteam').hide();
        $('#URSRC_lb_selectteam').hide();
        $('#URSRC_lbl_team_err').hide();
        $('#URSRC_lbl_nric').hide();
        $('#URSRC_tb_nric').hide();
        $("#URSRC_lbl_email_error").hide();
        var PE_startdate=(URSRC_comp_sdate).split('-');
        var day=PE_startdate[0];
        var month=PE_startdate[1];
        var year=PE_startdate[2];
        PE_startdate=new Date(year,month-1,day);
        $('#URSRC_tb_joindate').datepicker("option","minDate",PE_startdate);
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var final_array=JSON.parse(xmlhttp.responseText);
                var loginid_array=[];
                loginid_array=final_array[0];
                if(loginid_array.length!=0){
                    var URSRC_loginid_options='<option>SELECT</option>'
                    for(var l=0;l<loginid_array.length;l++){
                        URSRC_loginid_options+= '<option value="' + loginid_array[l][2] + '">' + loginid_array[l][0]+ '</option>';
                    }
                    $('#URSRC_lb_selectloginid').html(URSRC_loginid_options);
                    $('#URSRC_lb_selectloginid').show().prop('selectedIndex',0);
                    $('#URSRC_tble_login').show();
                    $('#URSRC_lbl_selectloginid').show();
                }
                else{
                    $('#URSRC_tble_login').show();
                    $('#URSRC_lbl_selectloginid').hide();
                    $('#URSRC_lb_selectloginid').hide()
                    $('#URSRC_lbl_nologin_err').text(URSRC_errorAarray[3]).show();
                }
                URSRC_team_array=final_array[1];
                $('#URSRC_lb_selectteam').replaceWith('<select id="URSRC_lb_selectteam" name="URSRC_lb_selectteam"  maxlength="25" class="login_submitvalidate form-control upper" hidden  ></select>')
                var team='<option value="SELECT">SELECT</option>';
                for(var k=0;k<URSRC_team_array.length;k++){
                    team += '<option value="' + URSRC_team_array[k] + '">' + URSRC_team_array[k] + '</option>';
                }
                $('#URSRC_lb_selectteam').html(team);
                $(this).val('ADD');
                $('#URSRC_lb_selectteam').hide();
            }
        }
        var choice="login_db"
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.php?option="+choice,true);
        xmlhttp.send();
    });
    var URSRC_filename;
    //LOGIN SEARCH ND UPDATE FOR LOGIN ID CHANGE FUNCTION
    $('#URSRC_lb_selectloginid').change(function(){
        $("#filetableuploads").empty();
        $('#attachafile').text('Attach a file');
        $('#URSRC_rd_male').attr('checked',false);
        $('#exsistingfiletable').empty();
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);//worked
$('.preloader').show();
        exist_flag=1;
        error_valid='valid';
        error_ext='valid';
        $("#URSRC_btn_update").html('')
        var URSRC_login_id=$(this).val();
        var len=URSRC_login_id.length;
        $('#URSRC_lbl_email_errupd').hide();
        $('#URSRC_lbl_loginidupd').hide();
        $('#URSRC_tb_loginidupd').hide();
        $("#URSRC_lbl_email_error").hide();
        if(URSRC_login_id!="SELECT"){
            URSRC_UPD_btn_update();
            $('#URSRC_tble_rolecreation').show();
            $('#URSRC_lbl_loginidupd').show();
            $('#URSRC_tb_loginidupd').val(URSRC_login_id).show().prop("size",len);
            $('#URSRC_tble_rolecreation').empty();
            $('#URSRC_btn_login_submitbutton').attr("disabled","disabled");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var values_array=JSON.parse(xmlhttp.responseText);
                    var join_date=values_array[0][0].joindate;
                    var rc_name=values_array[0][0].rcname;
                    var emp_type=values_array[0][0].emp_type;
                    var firstname=values_array[0][0].firstname;
                    var lastname=values_array[0][0].lastname;
                    var dob=values_array[0][0].dob;
                    var gender=values_array[0][0].gender;
                    if(gender=='MALE')
                    {
                        $('#URSRC_rd_male').attr('checked',true);
                    }
                    if(gender=='FEMALE')
                    {
                        $('#URSRC_rd_female').attr('checked',true);
                    }
                    var designation=values_array[0][0].designation;
                    var mobile=values_array[0][0].mobile;
                    var kinname=values_array[0][0].kinname;
                    var relationhood=values_array[0][0].relationhood;
                    var altmobile=values_array[0][0].altmobile;
                    var bankname=values_array[0][0].bankname;
                    var branchname=values_array[0][0].branchname;
                    var accountname=values_array[0][0].accountname;
                    var accountno=values_array[0][0].accountno;
                    var ifsccode=values_array[0][0].ifsccode;
                    var accountype=values_array[0][0].accountype;
                    var branchaddr=values_array[0][0].branchaddress;
                    var URSRC_role1=values_array[0][1];
                    var comment=values_array[0][0].comment;
                    var team=values_array[0][0].team_name;
                    var URSRC_nricno=values_array[0][0].URSRC_nricno;
                    var URSRC_address=values_array[0][0].URSRC_address;
                    URSRC_filename=values_array[0][0].URSRC_filename;
                    var URSRC_folder_name=values_array[0][0].URSRC_folderid;
                    var URSRC_emailid=values_array[0][0].URSRC_emailid;
                    if(URSRC_filename!=null){
                        var filenameinarray=URSRC_filename.split('/');
                        for(var j=0;j<filenameinarray.length;j++){
                            var name=URSRC_folder_name+"/"+filenameinarray[j];
                            var appendfile=' <div class="col-sm-offset-2 col-sm-10"><a href="download.php?filename='+name+'" class="links">'+filenameinarray[j]+'</a></div></br>';

                            $('#exsistingfiletable').append(appendfile);
                        }
                    }
                    //UPDATE FORM
                    for (var i = 0; i < URSRC_role1.length; i++) {
                        var value=URSRC_role1[i].replace(" ","_");
                        var id1="URSRC_role_array"+i;
                        if(URSRC_role1[i]==rc_name){
                            if(i==0)
                            {
                                var URSRC_roles='<label class=" control-label srctitle  col-sm-2" style="white-space: nowrap!important;">SELECT ROLE ACCESS</label>';
                                URSRC_roles+= '<div class="col-sm-offset-2 col-sm-10"><label  style="white-space: nowrap!important;"><input type="radio" name="roles1" id='+id1+' value='+value+' class="login_submitvalidate" checked  />' + URSRC_role1[i] + '</lable></div>';
                                $('#URSRC_tble_rolecreation').append(URSRC_roles);
                            }
                            else
                            {
                                URSRC_roles= '<div class="col-sm-offset-2 col-sm-10"><label  style="white-space: nowrap!important;"><input type="radio" name="roles1" id='+id1+' value='+value+' class="login_submitvalidate" checked  />' + URSRC_role1[i] + '</lable></div>';
                                $('#URSRC_tble_rolecreation').append(URSRC_roles);
                            }
                        }
                        else
                        {
                            if(i==0)
                            {
                                var URSRC_roles='<label class=" control-label col-sm-2" style="white-space: nowrap!important;">SELECT ROLE ACCESS<em>*</em></label>';
                                URSRC_roles+= '<div class="col-sm-offset-2 col-sm-10"><label  style="white-space: nowrap!important;"><input type="radio" name="roles1" id='+id1+' value='+value+' class="login_submitvalidate"   />' + URSRC_role1[i] + '</lable></div>';
                                $('#URSRC_tble_rolecreation').append(URSRC_roles);
                            }
                            else
                            {
                                URSRC_roles = '<div class="col-sm-offset-2 col-sm-10"><label  style="white-space: nowrap!important;"><input type="radio" name="roles1" id='+id1+' value='+value+' class="login_submitvalidate"   />' + URSRC_role1[i] + '</lable></div>';
                                $('#URSRC_tble_rolecreation').append(URSRC_roles);
                            }
                        }
                    }
                    $('#URSRC_lbl_login_role').show();
                    $('#URSRC_lbl_joindate').show();
                    $('#URSRC_tb_joindate').val(join_date).show();
                    $('#URSRC_lbl_emptype').show();
                    $('#URSRC_lb_selectemptype').val(emp_type).show();
                    $('#URSRC_table_employeetbl').show();
                    $('#URSRC_table_others').show();
                    $('#URSRC_lbl_pword').val('').hide();
                    $('#URSRC_tb_pword').val('').hide();
                    $('#URSRC_lbl_cpword').val('').hide();
                    $('#URSRC_tb_cpword').val('').hide();
                    $('#URSRC_lbl_selectteam').show();
                    $('#URSRC_lb_selectteam').val(team).show();
                    $('#URSRC_lbl_nric').show();
                    $('#URSRC_tb_nric').val(URSRC_nricno).show();
                    $('#URSRC_btn_add').show();
                    var emp_firstname=firstname.length;
                    $('#URSRC_tb_firstname').val(firstname);//.css("width",emp_firstname*11)//.attr("size",emp_firstname+3);
                    var emp_lastname=lastname.length;
                    $('#URSRC_tb_lastname').val(lastname).attr("size",emp_lastname+3);
                    $('#URSRC_tb_dob').val(dob);
                    var emp_designation=designation.length;
                    $('#URSRC_tb_designation').val(designation).attr("size",emp_designation+4);
                    $('#URSRC_tb_permobile').val(mobile);
                    var emp_kinname=kinname.length;
                    $('#URSRC_tb_kinname').val(kinname).attr("size",emp_kinname+1);
                    var emp_relationhood=relationhood.length;
                    $('#URSRC_tb_relationhd').val(relationhood).attr("size",emp_relationhood+1);
                    $('#URSRC_tb_mobile').val(altmobile);
                    var emp_bankname=bankname.length;
                    $('#URSRC_tb_bnkname').val(bankname).attr("size",emp_bankname+2);
                    var emp_branchname=branchname.length;
                    $('#URSRC_tb_brnchname').val(branchname).attr("size",emp_branchname+3);
                    var emp_accountname=accountname.length;
                    $('#URSRC_tb_accntname').val(accountname).attr("size",emp_accountname+2);
                    var emp_accountno=accountno.length;
                    $('#URSRC_tb_accntno').val(accountno).attr("size",emp_accountno+2);
                    var emp_ifsccode=ifsccode.length;
                    $('#URSRC_tb_ifsccode').val(ifsccode).attr("size",emp_ifsccode+2);
                    var emp_accountype=accountype.length;
                    $('#URSRC_tb_accntyp').val(accountype).attr("size",emp_accountype+2);
                    $('#URSRC_ta_brnchaddr').val(branchaddr);
                    if(comment!=null)
                    {
                        $('#URSRC_ta_comments').val(comment).show();
                    }
                    else
                    {
                        $('#URSRC_ta_comments').val('').show();
                    }
                    $('#URSRC_ta_address').val(URSRC_address);
                    $('#URSRC_tb_emailid').val(URSRC_emailid).show();

                }
            }
            var choice="loginfetch"
            xmlhttp.open("GET","DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.php?URSRC_login_id="+URSRC_login_id+"&option="+choice,true);
            xmlhttp.send();
        }
        else{
            $('.preloader').hide();
            $('#URSRC_tble_rolecreation').hide();
            $('#URSRC_tble_rolecreation').empty();
            $('#URSRC_lbl_joindate').hide();
            $('#URSRC_tb_joindate').hide().val("");
            $('#URSRC_lbl_login_role').hide()
            $('#URSRC_lbl_loginidupd').hide();
            $('#URSRC_lbl_emptype').hide();
            $('#URSRC_lb_selectemptype').val('').hide();
            $('#URSRC_tb_loginidupd').hide();
            $('#URSRC_lbl_loginidupd').hide();
            $('#URSRC_btn_login_submitbutton').attr("disabled","disabled");
            $('#URSRC_table_employeetbl').hide();
            $('#URSRC_table_others').hide();
            $('#URSRC_lbl_email_errupd').hide();
            $('#URSRC_lbl_pword').hide();
            $('#URSRC_tb_pword').val('').hide();
            $('#URSRC_lbl_cpword').hide();
            $('#URSRC_tb_cpword').val('').hide();
            $('#URSRC_lbl_selectteam').hide();
            $('#URSRC_btn_add').hide();
            $('#URSRC_lb_selectteam').prop('selectedIndex',0).hide();
        }
    });
    function URSRC_UPD_btn_update(){
        $('<tr><td align="left"><input type="button"  class="btn btn-info" name="URSRC_submitupdate" id="URSRC_submitupdate"  value="UPDATE" disabled></td></tr>').appendTo($("#URSRC_btn_update"));
    }
    //VALIDATION FOR UPDATE BUTTON LOGIN SEARCH ND UPDATE
    $(document).on("click",'#URSRC_submitupdate ', function (){
$('.preloader').show();
        var URSRC_radio_button_select_value=$("input[name=URSRC_mainradiobutton]:checked").val();
        var formElement = document.getElementById("URSRC_userrightsform");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var msg_alert=JSON.parse(xmlhttp.responseText);
                var success_flag=msg_alert[0];
                var name=$('#URSRC_tb_loginid').val();
                $('#URSRC_tb_joindate').hide();
                $('#URSRC_lbl_joindate').hide()
                $('#URSRC_lbl_emptype').hide();
                $('#URSRC_lb_selectemptype').prop('selectedIndex',0).hide();
                $('#URSRC_tble_rolecreation').hide();
                $('#URSRC_tb_loginidupd').hide();
                $('#URSRC_lbl_loginidupd').hide();
                $('#URSRC_lb_selectloginid').prop('selectedIndex',0);
                $('#URSRC_btn_login_submitbutton').hide()
                var msg=URSRC_errorAarray[8].replace("[NAME]",name)
                if((success_flag==1))
                {
                    show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",msg,"success",false)
                    $('#URSRC_submitupdate').hide();
                    $('#URSRC_lb_selectloginid').hide();
                    $('#URSRC_lbl_loginid').hide();
                    $('#URSRC_lbl_joindate').hide();
                    $('#URSRC_tb_joindate').hide();
                    $('#URSRC_tble_rolecreation').hide();
                    $('#URSRC_lbl_header').hide()
                    $('input:radio[name=URSRC_mainradiobutton]').attr('checked',false);
                    $('#URSRC_table_employeetbl').hide();
                    $('#URSRC_lbl_selectloginid').hide();
                    $("#filetableuploads").empty();
                    $('#exsistingfiletable').empty();
                    $('#URSRC_lb_selectteam').prop('selectedIndex',0).hide();
                    $('#URSRC_lb_selectteam').val("");
                    $('#URSRC_lbl_selectteam').hide();
                    $('#URSRC_tb_nric').val('');
                    $('#URSRC_ta_address').val('');
                    $('#URSRC_btn_add').hide();
                    $('#attachafile').text('Attach a file');
                }
                if(success_flag==0){
                    show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[18],"error",false)
                    $('#URSRC_submitupdate').hide();
                    $('#URSRC_lb_selectloginid').hide();
                    $('#URSRC_lbl_loginid').hide();
                    $('#URSRC_lbl_joindate').hide();
                    $('#URSRC_tb_joindate').hide();
                    $('#URSRC_tble_rolecreation').hide();
                    $('#URSRC_lbl_header').hide()
                    $('input:radio[name=URSRC_mainradiobutton]').attr('checked',false);
                    $('#URSRC_table_employeetbl').hide();
                    $('#URSRC_lbl_selectloginid').hide();
                }

            }
        }
        var choice="loginupdate"
        xmlhttp.open("POST","DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.php?option="+choice+"&upload_count="+upload_count+"&URSRC_filename="+URSRC_filename,true);
        xmlhttp.send(new FormData(formElement));
    });
    //VALIDATION FOR CREATE BUTTON FOR LOGIN CREATION ENTRY
    $(document).on("click",'#URSRC_btn_login_submitbutton ', function (){
        $('.preloader').show();
        var radio_checked=$("input[name=roles1]:checked" ).val()
        var radio_gender=$("input[name=URSRC_rd_gender]:checked" ).val()
        var formElement = document.getElementById("URSRC_userrightsform");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var msg_alert=JSON.parse(xmlhttp.responseText);
                var success_flag=msg_alert[0];
                var name=$('#URSRC_tb_loginid').val();
                var msg=URSRC_errorAarray[7].replace("[NAME]",name)
                var finalmsg=msg.replace("[NAME]",name)
                $('#URSRC_lbl_header').hide();
                if((success_flag==1))
                {
                    show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",finalmsg,"success",false)
                    $('#URSRC_tble_login').hide();
                    $('#URSRC_tble_rolesearch').hide();
                    $('#URSRC_tb_joindate').val("");
                    $('#URSRC_table_employeetbl').hide();
                    $('#URSRC_lbl_joindate').hide();
                    $('#URSRC_tb_joindate').hide();
                    $('#URSRC_tb_loginid').hide();
                    $('#URSRC_tble_rolecreation').hide();
                    $('#URSRC_tb_loginid').hide();
                    $('#URSRC_lbl_loginid').hide();
                    $('#URSRC_lbl_header').hide();
                    $('input:radio[name=URSRC_mainradiobutton]').attr('checked',false);
                    $('#URSRC_btn_login_submitbutton').hide();
                    $('#URSRC_btn_submitbutton').hide();
                    $('#URSRC_tb_loginid').prop("size","20");
                    $('#URSRC_tb_loginid').val('');
                    $("#URSRC_btn_login_submitbutton").attr("disabled", "disabled");
                    $('#URSRC_tb_joindate').val("");
                    $('#URSRC_tb_loginid').val("");
                    $('input[name=URSRC_mainradiobutton]:checked').attr('checked',false);
                    $("#filetableuploads").empty();
                    $('#exsistingfiletable').empty();
                    $('#URSRC_lb_selectteam').prop('selectedIndex',0).hide();
                    $('#URSRC_lb_selectteam').val("");
                    $('#URSRC_tb_nric').val('');
                    $('#URSRC_ta_address').val('');
                    $('#attachafile').text('Attach a file');
                }
                if(success_flag==0)
                {
                    var name=$('#URSRC_tb_loginid').val();
                    var msg=URSRC_errorAarray[27].replace("[NAME]",name)
                    var finalmsg=msg.replace("[NAME]",name)
                    show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",finalmsg,"error",false)
                }
            }
        }
        var choice="loginsave"
        xmlhttp.open("POST","DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.php?radio_checked="+radio_checked+"&option="+choice+"&radio_gender="+radio_gender+"&upload_count="+upload_count,true);
        xmlhttp.send(new FormData(formElement));
    });
    //FUNCTION TO CLICK BASIC ROLE
    $(document).on("click",'.URSRC_class_basicroles', function (){
        $('.preloader').show();
        var radio_value=$(this).val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var values_array=JSON.parse(xmlhttp.responseText);
                URSRC_menuname=values_array[0];
                URSRC_submenu=values_array[1];
                URSRC_subsubmenu=values_array[2];
                URSRC_tree_view(values_array,'')
            }
        }
        var choice="URSRC_tree_view"
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.php?radio_value="+radio_value+"&option="+choice,true);
        xmlhttp.send();
    });
    //COMMON TREE VIEW FUNCTION
    function URSRC_tree_view(values_array,URSRC_checked_mpid){
        $('.preloader').hide();
        $('#URSRC_btn_submitbutton').attr("disabled","disabled");
        $('#URSRC_tble_menu').replaceWith('<table id="URSRC_tble_menu"></table>')
        var count=0;
        var menus=[];
        URSRC_menuname=values_array[0];
        URSRC_submenu=values_array[1];
        URSRC_subsubmenu=values_array[2];
        var URSRC_main_menu=URSRC_menuname
        var URSRC_sub_menu=URSRC_submenu
        var URSRC_sub_menu1=URSRC_subsubmenu
        var URSRC_menu1='<tr><td><label>MENU<em>*</em></label></td></tr>'
        $('#URSRC_tble_menu').append(URSRC_menu1);
        var URSRC_menu=''
        for(var i=0;i<URSRC_main_menu.length;i++)
        {
            var URSRC_submenu_table_id="URSRC_tble_submenu"+i;
            var URSRC_menu_button_id="menu"+"_"+i;
            var URSRC_submenu_div_id="sub"+i
            var menu_value=URSRC_main_menu[i].replace(/ /g,"&");
            var id_menu=i+'m'
            var mainmenuid=i;
            URSRC_menu= '<div ><ul style="list-style: none;" ><li style="list-style: none;" ><input value="+" type="button"  id='+URSRC_menu_button_id+' height="1" width="1" class="exp" /><input type="checkbox" name="menu" id='+id_menu+' value='+menu_value+' level="parent" class="tree URSRC_submit_validate Parent"  />' + URSRC_main_menu[i] + '</td></tr>';
            URSRC_menu+='<div id='+URSRC_submenu_div_id+' hidden ><div id='+URSRC_submenu_table_id+' class="URSRC_class_submenu"  ></div></tr></div></li></ul></div>';
            $('#URSRC_tble_menu').append(URSRC_menu);
            var URSRC_submenu='';
            for(var j=0;j<URSRC_sub_menu.length;j++)
            {
                if(i==j)
                {
                    var submenulength=URSRC_sub_menu[j].length;
                    for(var k=0;k<URSRC_sub_menu[j].length;k++)
                    {
                        var URSRC_submenu1_table_id="URSRC_tble_submenu1"+k+j;
                        var URSRC_submenu_button_id="sub_menu"+"_"+k+j;
                        var URSRC_submenu1_div_id="sub1"+k+j;
                        var sub_menu_value1=URSRC_sub_menu[j][k];
                        var sub_menu_values=sub_menu_value1[1];
                        var sub_menu_id=sub_menu_value1[0];
                        sub_menu_values[1]=sub_menu_values[1];
                        var submenuids="USR_SITE_submenus-"+mainmenuid+'-'+submenulength+'-'+k;//+'-'+sub_menu_id;
                        var idsubmenu=k+j
                        if(URSRC_sub_menu1[count].length>0)
                        {
                            URSRC_submenu = '<div ><ul style="list-style: none;"><li style="list-style: none;" ><tr ><td>&nbsp;&nbsp;&nbsp;<input value="+" type="button"  id='+URSRC_submenu_button_id+' height="1" width="1" class="exp1" /><input type="checkbox" name="Sub_menu[]" id='+submenuids+' value='+sub_menu_id+'&&'+' level="child" class="tree submenucheck URSRC_submit_validate Child"  />' + sub_menu_values + '</td></tr>';
                            URSRC_submenu+='<div id='+URSRC_submenu1_div_id+'  ><tr><td><table id='+URSRC_submenu1_table_id+' hidden ></div></tr></div></li></ul></div>';//CHANGED BEC THS LINE USED FOR ONLY IN SUBSUB MENU VAL
                        }
                        else
                        {
                            URSRC_submenu = '<div ><ul style="list-style: none;"><li style="list-style: none;" ><tr ><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="Sub_menu[]" id='+submenuids+' value='+sub_menu_id+' class="tree submenucheck URSRC_submit_validate" level="child" />' + sub_menu_values + '</td><td><input type="hidden" ></tr>';
                        }
                        $('#'+"URSRC_tble_submenu"+i).append(URSRC_submenu);
                        if(URSRC_checked_mpid.length>0)
                        {
                            for(var m1=0;m1<URSRC_checked_mpid.length;m1++){
                                if(sub_menu_id==URSRC_checked_mpid[m1]){
                                    $('#'+submenuids).prop("checked", true)
                                    $('#'+id_menu).prop("checked", true)
                                }
                            }
                        }
                        var URSRC_submenu1='';
                        var subsubmenucount=URSRC_sub_menu1[count].length;
                        for(var m=0;m<URSRC_sub_menu1[count].length;m++)
                        {
                            var sub_menu1_value1=URSRC_sub_menu1[count][m];
                            var sub_menu1_values=sub_menu1_value1[1];
                            sub_menu1_values[1]=sub_menu1_values[1];
                            var sub_menu1_id=sub_menu1_value1[0];
                            var idsubmenu1=count+m+'s1'
                            var subsubmenuid='USR_SITE_submenuchk-'+mainmenuid+'-'+submenulength+'-'+k+'-'+sub_menu_id+'-'+m+'-'+subsubmenucount;//+'-'+sub_menu1_id;//
                            URSRC_submenu1 = '<div ><ul style="list-style: none;"><li style="list-style: none;" ><tr ><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="Sub_menu1[]" id='+subsubmenuid+' value='+sub_menu1_id+' class="tree subsubmenuchk URSRC_submit_validate" level="child1" />' +sub_menu1_values + '</td><td><input type="hidden" ></tr></li></ul></div>';
                            $('#'+"URSRC_tble_submenu1"+k+j).append(URSRC_submenu1);
                            if(URSRC_checked_mpid.length>0)
                            {
                                for(var m1=0;m1<URSRC_checked_mpid.length;m1++){
                                    if(sub_menu1_id==URSRC_checked_mpid[m1]){
                                        $('#'+subsubmenuid).prop("checked", true)
                                        $('#'+submenuids).prop("checked", true)
                                        $('#'+id_menu).prop("checked", true)
                                    }
                                }
                            }
                        }
                        count++;
                    }
                }
            }
        }
//        $('#URSRC_tble_menu').show();
        $('#URSRC_btn_submitbutton').show()
    }
    //TREE VIEW EXPANDING
    $(document).on("click",'.exp,.collapse', function (){
        var button_id=$(this).attr("id")
        var btnid=button_id.split("_");
        var menu_btnid=btnid[1]
        if($(this).val()=='+'){
            $(this).val('-');
//            $(this).replaceWith('<input type="button"   value="-" id='+button_id+'  height="3" width="3" class="collapse" />');
            if(btnid[0]=='folder'){
                $('#subf'+menu_btnid).toggle("fold",100);
            }
            else{
                $('#sub'+menu_btnid).toggle("fold",100);
            }
        }
        else
        {
            if(btnid[0]=='folder'){
                $('#subf'+menu_btnid).toggle("fold",100);
            }
            else{
                $('#sub'+menu_btnid).toggle("fold",100);
            }
            $(this).replaceWith('<input type="button"   value="+" id='+button_id+'  height="1" width="1" class="exp" />');
        }
    });
    //TREE VIEW EXPANDING
    $(document).on("click",'.exp1,.collapse1', function (){
        var sub_buttonid=$(this).attr("id")
        var btnid=sub_buttonid.split("_");
        var menu_btnid=btnid[2]
        if($(this).val()=='+'){
            $(this).replaceWith('<input type="button"   value="-" id='+sub_buttonid+'  height="1" width="1" class="collapse1" />');
            $('#URSRC_tble_submenu1'+menu_btnid).toggle("fold",100);
        }
        else
        {
            $('#URSRC_tble_submenu1'+menu_btnid).toggle("fold",100);
            $(this).replaceWith('<input type="button"   value="+" id='+sub_buttonid+'  height="3" width="3" class="exp1" />');
        }
    });
    //CHECKED ALL MAIN MENU CHECK BOX
    var URSRC_mainmenu_value;
    $(document).on("change blur",'.tree ', function (){
        var val = $(this).attr("checked");
        URSRC_mainmenu_value=$(this).val()
        $(this).parent().find("input:checkbox").each(function() {
            if (val) {
                $(this).attr("checked", "checked");
            } else {
                $(this).removeAttr("checked");
                $(this).parents('ul').each(function(){
                    $(this).prev('input:checkbox').removeAttr("checked");
                });
            }
        });
        URSRC_submit_validate()
    });
    //VALIDATION FORTREE VIEW CREATE BUTTON
    function URSRC_submit_validate(){
        var basicrole_profile_checked = $('input[name="URSRC_cb_basicroles1[]"]:checked').length;//$("input[id=checkbox]").is(":checked");
        var URSRC_radio_button_select_value=$("input[name=URSRC_mainradiobutton]:checked").val();
        if((URSRC_radio_button_select_value=="ROLE CREATION")||(URSRC_radio_button_select_value=="ROLE SEARCH UPDATE")){
            var Submenu1_checked=$('input[name="Sub_menu1[]"]:checked').length
            var Submenu_checked=$('input[name="Sub_menu[]"]:checked').length
            if(Submenu1_checked>0||Submenu_checked>0){
                $('#URSRC_btn_submitbutton').removeAttr('disabled')
            }
            else
            {
                $('#URSRC_btn_submitbutton').attr("disabled","disabled");
            }
        }
        else{
            var Submenu1_checked=$('input[name="Sub_menu1[]"]:checked').length
            var Submenu_checked=$('input[name="Sub_menu[]"]:checked').length
            if((Submenu1_checked>0)&&(basicrole_profile_checked>0)||(Submenu_checked>0)&&(basicrole_profile_checked>0)){
                $('#URSRC_btn_submitbutton').removeAttr('disabled')
            }
            else
            {
                $('#URSRC_btn_submitbutton').attr("disabled","disabled");////remove the comments
            }
        }
    }
    //VALIDATION FOR SUB MENU CHECK BOX CLICKING
    $(document).on('click','.submenucheck',function(){
        var URSRC_checkbox_id=$(this).attr("id");
        var URSRC_checkbox_id_split=URSRC_checkbox_id.split('-');
        var count=0;
        for(var g=0;g<URSRC_checkbox_id_split[2];g++)
        {
            var checked1='USR_SITE_submenus-'+URSRC_checkbox_id_split[1]+'-'+URSRC_checkbox_id_split[2]+'-'+g;
            var checked=$('#'+checked1).attr("checked");
            if(checked)
            {
                count++;
            }
        }
        if(count!=0)
        {
            $('#'+URSRC_checkbox_id_split[1]+'m').prop('checked',true);
        }
        else
        {
            $('#'+URSRC_checkbox_id_split[1]+'m').prop('checked',false);
        }
    });
    //VALIDATION FOR SUB SUB MENU CHECK BOX CLICKING
    $(document).on('click','.subsubmenuchk',function(){
        var URSRC_checkbox_id=$(this).attr("id");
        var URSRC_checkbox_id_idsplit=URSRC_checkbox_id.split('-');
        var count=0;
        for(var i=0;i<URSRC_checkbox_id_idsplit[6];i++)
        {
            var chkboxid=URSRC_checkbox_id_idsplit[0]+'-'+URSRC_checkbox_id_idsplit[1]+'-'+URSRC_checkbox_id_idsplit[2]+'-'+URSRC_checkbox_id_idsplit[3]+'-'+URSRC_checkbox_id_idsplit[4]+'-'+i+'-'+URSRC_checkbox_id_idsplit[6];
            var checked=$('#'+chkboxid).attr("checked");
            if(checked)
            {
                count++;
            }
        }
        if(count!=0)
        {
            $('#USR_SITE_submenus-'+URSRC_checkbox_id_idsplit[1]+'-'+URSRC_checkbox_id_idsplit[2]+'-'+URSRC_checkbox_id_idsplit[3]).prop('checked',true);
        }
        else
        {
            $('#USR_SITE_submenus-'+URSRC_checkbox_id_idsplit[1]+'-'+URSRC_checkbox_id_idsplit[2]+'-'+URSRC_checkbox_id_idsplit[3]).prop('checked',false);
        }
        var submenucount=0;
        for(var j=0;j<URSRC_checkbox_id_idsplit[2];j++)
        {
            var subchkid=URSRC_checkbox_id_idsplit[1]+'-'+URSRC_checkbox_id_idsplit[2]+'-'+j;
            var submenuchecked=$('#USR_SITE_submenus-'+subchkid).attr("checked");
            if(submenuchecked)
            {
                submenucount++;
            }
        }
        if(submenucount!=0)
        {
            $('#'+URSRC_checkbox_id_idsplit[1]+'m').prop('checked',true);
        }
        else
        {
            $('#'+URSRC_checkbox_id_idsplit[1]+'m').prop('checked',false);
        }
    });
    //Basic Role/Search&update/Role Creation and Update  button click
    $(document).on('click','#URSRC_btn_submitbutton',function(){
        $('.preloader').show();
        var URSRC_radio_button_select_value=$("input[name=URSRC_mainradiobutton]:checked").val();
        //ROLE CREATION SAVE PART
        var formElement = document.getElementById("URSRC_userrightsform");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var msg_alert=xmlhttp.responseText;
                if(URSRC_radio_button_select_value=="ROLE CREATION"){
                    $('#URSRC_tble_menu').hide();
                    $('#URSRC_tble_folder').hide();
                    $('#URSRC_tble_roles').hide();
                    $('#URSRC_btn_submitbutton').hide();
                    $('#URSRC_tble_role').hide();
                    $('#URSRC_tb_customrole').val("");
                    $('input[name=URSRC_mainradiobutton]:checked').attr('checked',false);
                    $('#URSRC_lbl_header').hide();
                    if(msg_alert==1)
                    {
                        show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[6],"success",false)
                        $('#URSRC_tble_menu').hide();
                        $('#URSRC_tble_folder').hide();
                        $('#URSRC_tble_roles').hide();
                        $('#URSRC_btn_submitbutton').hide();
                        $('#URSRC_tble_role').hide();
                        $('#URSRC_tb_customrole').val("");
                        $('input[name=URSRC_mainradiobutton]:checked').attr('checked',false);
                        $('#URSRC_lbl_header').hide();
                    }
                    else
                    {
                        show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[18],"error",false)
                    }
                }
                if(URSRC_radio_button_select_value=="ROLE SEARCH UPDATE"){
                    $('#URSRC_tble_menu').hide();
                    $('#URSRC_tble_folder').hide();
                    $('#URSRC_rolesearch_roles').empty()
                    $('#URSRC_tble_rolecreation').hide()
                    $('#URSRC_btn_submitbutton').hide();
                    $('#URSRC_lb_selectrole').prop('selectedIndex',0);
                    if(msg_alert==1)
                    {
                        show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[9],"success",false)
                        $('#URSRC_tble_menu').hide();
                        $('#URSRC_tble_folder').hide();
                        $('#URSRC_tble_roles').hide();
                        $('#URSRC_btn_submitbutton').hide();
                        $('#URSRC_tble_role').hide();
                        $('#URSRC_tb_customrole').val("");
                        $('input[name=URSRC_mainradiobutton]:checked').attr('checked',false);
                        $('#URSRC_lbl_header').hide();
                        $('#URSRC_lb_selectrole').hide();
                        $('#URSRC_lbl_selectrole').hide()
                    }
                    else
                    {
                        show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[18],"error",false)
                    }
                }
                if(URSRC_radio_button_select_value=="BASIC ROLE MENU CREATION"){
                    $('input:radio[name=URSRC_radio_basicroles1]').attr('checked',false);
                    $('input:radio[name=URSRC_cb_basicroles1]').attr('checked',false);
                    $('#URSRC_tble_menu').hide();
                    $('#URSRC_tble_folder').hide();
                    $('#URSRC_btn_submitbutton').hide();
                    $('#URSRC_tble_basicroles').hide();
                    $('#URSRC_tble_basicroles_chk').hide();
                    $('input[name=URSRC_mainradiobutton]:checked').attr('checked',false);
                    $('#URSRC_lbl_header').hide();
                    if(msg_alert==1)
                    {
                        show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[14],"success",false)
                    }
                    else
                    {
                        show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[18],"error",false)
                    }
                }
                if(URSRC_radio_button_select_value=="BASIC ROLE MENU SEARCH UPDATE"){
                    $('input:radio[name=URSRC_radio_basicroles1]').attr('checked',false);
                    $('input:radio[name=URSRC_cb_basicroles1]').attr('checked',false);
                    $('#URSRC_tble_basicroles_chk').hide();
                    $('#URSRC_tble_menu').hide();
                    $('#URSRC_tble_folder').hide();
                    $('#URSRC_btn_submitbutton').hide();
                    $('#URSRC_lbl_header').text("BASIC ROLE MENU SEARCH UPDATE").hide();
                    $('#URSRC_tble_basicroles').hide();
                    $('input[name=URSRC_mainradiobutton]:checked').attr('checked',false);
                    if(msg_alert==1){
                        show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[15],"success",false)
                    }
                    else{
                        show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[18],"success",false)
                    }
                }
            }
        }
        var choice="rolecreationsave"
        xmlhttp.open("POST","DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.php?URSRC_radio_button_select_value="+URSRC_radio_button_select_value+"&option="+choice,true);
        xmlhttp.send(new FormData(formElement));
    });
    //ROLE SEARCH/UPDATE CLICK
    $(document).on('click','#URSRC_radio_rolesearchupdate',function(){
        flag=0;
        var radio_value_rolesearch=$(this).val();
        $('#URSRC_lbl_header').text("ROLE SEARCH/UPDATE").show()
        $('.preloader').show();
        $('#URSRC_btn_submitbutton').val('UPDATE').hide();
        $('#URSRC_tble_role').hide();
        $('#URSRC_tble_menu').hide();
        $('#URSRC_tble_folder').hide();
        $('#URSRC_lbl_nodetails_err').hide()
        $('#URSRC_tble_login').hide()
        $('#URSRC_tble_roles').hide();
        $('#URSRC_tb_customrole').val("");
        $('#URSRC_tble_search').hide();
        $('#URSRC_lbl_basicrole_err').hide()
        $('#URSRC_table_employeetbl').hide();
        $('#URSRC_btn_login_submitbutton').hide();
        $('#URSRC_tble_login').hide();
        $('#URSRC_tb_loginid').val("");
        $('#URSRC_tb_joindate').val("");
        $('#URSRC_tble_rolecreation').empty().hide();
        $('input:radio[name=basicroles]').attr('checked',false);
        $('#URSRC_tble_basicroles').hide();
        $('#URSRC_tble_basicrolemenucreation').hide();
        $('#URSRC_tble_basicroles_chk ').hide()
        $('input[name=URSRC_cb_basicroles1]').attr('checked',false);
        $('input:radio[name=URSRC_radio_basicroles1]').attr('checked',false);
        $('#URSRC_table_employeetbl').hide();
        $('#URSRC_table_others').hide();
        $('#URSRC_lbl_joindate').hide().val("");
        $('#URSRC_tb_joindate').hide().val("");
        $('#URSRC_lbl_role_err').hide();
        $('#URSRC_lbl_validnumber').hide();
        $('#URSRC_lbl_validnumber1').hide();
        //REQUEST SENDING
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var values_array_rcname=JSON.parse(xmlhttp.responseText);
                $('.preloader').hide();
                if(values_array_rcname.length!=0){
                    var URSRC_customerole_options='<option>SELECT</option>'
                    for(var l=0;l<values_array_rcname.length;l++){
                        URSRC_customerole_options+= '<option value="' + values_array_rcname[l] + '">' + values_array_rcname[l]+ '</option>';
                    }
                    $('#URSRC_lb_selectrole').html(URSRC_customerole_options);
                    $('#URSRC_tble_rolesearch').show();
                    $('#URSRC_lbl_selectrole').show()
                    $('#URSRC_lb_selectrole').show()
                    $('#URSRC_rolesearch_roles').hide()
                    $('#URSRC_lbl_norole_err').hide();
                    $('#URSRC_submitupdate').hide();
                }
                else{
                    $('#URSRC_lbl_norole_err').text(URSRC_errorAarray[11]).show();
                    $('#URSRC_tble_rolesearch').show();
                    $('#URSRC_lb_selectrole').hide();
                    $('#URSRC_rolesearch_roles').hide()
                    $('#URSRC_lbl_selectrole').hide()
                }
            }
        }
        var choice="ACCESS_RIGHTS_SEARCH_UPDATE_BASICROLE"
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.php?option="+choice,true);
        xmlhttp.send();
    });
    var values_array_rcname=[];
//ROLE CHANGE FUNCTION FOR ROLE SEARCH AND UPDATE
    $('#URSRC_lb_selectrole').change(function(){
        var URSRC_lbrole_srchndupdate=$('#URSRC_lb_selectrole').val();
        if($(this).val()!='SELECT'){
            $('.preloader').show();
            //FUNCTION TO LOAD SELECTED ROLE DETAILS
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    values_array_rcname=JSON.parse(xmlhttp.responseText);
                    var URSRC_lb_radiovalrolesearch=values_array_rcname[0];
                    URSRC_checked_mpid=values_array_rcname[1];
                    var URSRC_menu_fullarray=values_array_rcname[2];
                    var URSRC_role_radio='<label class="col-sm-3" style="white-space: nowrap!important;">SELECT A ROLE ACCESS</label>'
                    $('#URSRC_rolesearch_roles').html(URSRC_role_radio);
                    for (var i = 0; i < URSRC_userrigths_array.length; i++) {
                        var id1="URSRC_userrigths_array"+i;
                        var value=URSRC_userrigths_array[i].replace(" ","_")
                        if(URSRC_userrigths_array[i]==URSRC_lb_radiovalrolesearch){
                            URSRC_role_radio+='<div class=" col-sm-offset-2 col-sm-10"><label  style="white-space: nowrap!important;"><input type="radio" name="basicroles" id='+id1+' value='+value+' class=" URSRC_class_basicroles"  checked  />' + URSRC_userrigths_array[i] + '</lable></div>';
                        }
                        else{
                            URSRC_role_radio+='<div class=" col-sm-offset-2 col-sm-10"><label  style="white-space: nowrap!important;"><input type="radio" name="basicroles" id='+id1+' value='+value+' class=" URSRC_class_basicroles"   />' + URSRC_userrigths_array[i] + '</lable></div>';
                        }
                    }
                    $('#URSRC_rolesearch_roles').html(URSRC_role_radio);
                    $('#URSRC_rolesearch_roles').show();
                    if(URSRC_menu_fullarray[2].length!=0){
                        $('#URSRC_lbl_nodetails_err').hide()
                        URSRC_tree_view(URSRC_menu_fullarray,URSRC_checked_mpid)
                    }
                    else{
                    }
                }
            }
            var choice="URSRC_tree_views"
            xmlhttp.open("GET","DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.php?URSRC_lbrole_srchndupdate="+URSRC_lbrole_srchndupdate+"&option="+choice,true);
            xmlhttp.send();
        }
        $('#URSRC_tble_menu').hide();
        $('#URSRC_tble_folder').hide();
        $('#URSRC_rolesearch_roles').empty();
        $('#URSRC_btn_submitbutton').hide();
    });
    var pass_flag=0;
    $('.chk_password').change(function(){

        var URSRC_pass_length=($('#URSRC_tb_pword').val()).length;
        if(URSRC_pass_length<8){

            $('#URSRC_lbl_passwrd_errupd').text(URSRC_errorAarray[30]).show();
            pass_flag=0;
            loginbuttonvalidation();
        }
        else{
            pass_flag=1;
            $('#URSRC_lbl_passwrd_errupd').hide();
            loginbuttonvalidation();
        }
    });
    var incorrectflag=0;
    //CHANGE EVENT FOR CONFIRM PASSWORD
    $(document).on("change",'#URSRC_tb_cpword,.chk_password', function (){
        var password=$('#URSRC_tb_pword').val();
        var confirmpassword=$('#URSRC_tb_cpword').val();
        if(confirmpassword!=''){
            if(password!=confirmpassword)
            {
                $('#URSRC_lbl_confirmpasswrd_errupd').text(URSRC_errorAarray[29]).show();
                incorrectflag=0;
                loginbuttonvalidation();
            }
            else
            {
                $('#URSRC_lbl_confirmpasswrd_errupd').hide();
                incorrectflag=1;
                loginbuttonvalidation();
            }
        }
    });

    $('#URSRC_btn_submitbutton').hide();
    $(document).on("keyup",'.upper',function() {
        if (this.value.match(/[^a-zA-Z0-9\-]/g)) {
            this.value = this.value.replace(/[^a-zA-Z0-9\-]/g, '');
        }
        $('#URSRC_lb_selectteam').val($('#URSRC_lb_selectteam').val().toUpperCase())
    });


    var team_flag=0;
    $(document).on("change",'.check_team',function() {
        var team_name=$('#URSRC_lb_selectteam').val();

        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader',window.parent.document).hide();
                var msgalert=JSON.parse(xmlhttp.responseText);

                if(msgalert==0)
                {
                    $('#URSRC_lbl_team_err').hide()
                    team_flag=1;
                }
                else{
                    var msg=URSRC_errorAarray[5].replace('[NAME]',$('#URSRC_lb_selectteam').val());
                    msg=msg.replace("ROLE","TEAM");
                    $('#URSRC_lbl_team_err').text(msg).show()
                    team_flag=0;
                }
            }
        }
        var choice='URSRC_check_team';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.php?URSRC_team_name="+team_name+"&option="+choice,true);
        xmlhttp.send();


    });

    $('#URSRC_btn_add').click(function(){

        var URSRC_btn_value=$(this).val();
        if(URSRC_btn_value=='ADD'){
            $('#URSRC_lb_selectteam').replaceWith('<input type="text"  name="URSRC_lb_selectteam" id="URSRC_lb_selectteam" maxlength="25" class="login_submitvalidate form-control upper check_team" /><label id="URSRC_lbl_team_err" class="errormsg"></label>');
            $(this).val('CLEAR');
        }
        else{
            $('#URSRC_lb_selectteam').replaceWith('<select id="URSRC_lb_selectteam" name="URSRC_lb_selectteam"  maxlength="25" class="login_submitvalidate form-control upper" hidden  ></select>')
            var team='<option value="SELECT">SELECT</option>';
            for(var k=0;k<URSRC_team_array.length;k++){
                team += '<option value="' + URSRC_team_array[k] + '">' + URSRC_team_array[k] + '</option>';
            }
            $('#URSRC_lb_selectteam').html(team);
            $(this).val('ADD');

        }
    });
});
//END DOCUMENT READY FUNCTION
</script
    <!--SCRIPT TAG END-->
    <!--BODY TAG START-->
<body>
<div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
<div class="container">
<div class="panel panel-info">
<div class="panel-heading">
    <h2 class="panel-title">ACCESS RIGHTS:SEARCH / UPDATE</h2>
</div>
<div class="panel-body">
<form id="URSRC_userrightsform" name="URSRC_userrightsform" class="form-horizontal" role="form">

<div id="URSRC_tble_main">
    <div class="form-group">
        <label name="USU_lbl_strtdte" id="USU_lbl_strtdte" class="srctitle  col-sm-2">SELECT A OPTION
        </label>
    </div>
    <div class="form-group row">
        <div class="radio">
            <label class="col-sm-2" name="URSRC_lbl_basicrolemenucreation" id="URSRC_lbl_basicrolemenucreation" style="white-space: nowrap!important;">
                &nbsp;&nbsp;&nbsp;&nbsp;<input  type='radio' name='URSRC_mainradiobutton' id='URSRC_radio_basicrolemenucreation' value='BASIC ROLE MENU CREATION'>
                BASIC ROLE MENU CREATION
            </label>
        </div>

    </div>
    <div class="form-group row">
        <div class="radio">
            <label class="col-sm-2" name="URSRC_lbl_basicrolemenusearchupdate" id="URSRC_lbl_basicrolemenusearchupdate" style="white-space: nowrap!important;">
                &nbsp;&nbsp;&nbsp;&nbsp;<input  type='radio' name='URSRC_mainradiobutton' id='URSRC_radio_basicrolemenusearchupdate' value='BASIC ROLE MENU SEARCH UPDATE'>
                BASIC ROLE MENU SEARCH / UPDATE
            </label>
        </div>
    </div>
    <div class="form-group row">
        <div class="radio">
            <label class="col-sm-2" name="URSRC_lbl_rolecreation" id="URSRC_lbl_rolecreation" style="white-space: nowrap!important;">
                &nbsp;&nbsp;&nbsp;&nbsp;<input  type='radio' name='URSRC_mainradiobutton' id='URSRC_radio_rolecreation' value='ROLE CREATION'>
                ROLE CREATION
            </label>
        </div>
    </div>
    <div class="form-group row">
        <div class="radio">
            <label class="col-sm-2" name="URSRC_lbl_rolesearchupdate" id="URSRC_lbl_rolesearchupdate" style="white-space: nowrap!important;">
                &nbsp;&nbsp;&nbsp;&nbsp;<input   type='radio' name='URSRC_mainradiobutton' id='URSRC_radio_rolesearchupdate' value='ROLE SEARCH UPDATE'>
                ROLE SEARCH / UPDATE
            </label>
        </div>
    </div>
    <div class="form-group row">
        <div class="radio">
            <label class="col-sm-2" name="URSRC_lbl_logincreation" id="URSRC_lbl_logincreation" style="white-space: nowrap!important;">
                &nbsp;&nbsp;&nbsp;&nbsp;<input   type='radio' name='URSRC_mainradiobutton' id='URSRC_radio_logincreation' value='LOGIN CREATION'>
                LOGIN CREATION
            </label>
        </div>
    </div>
    <div class="form-group row">
        <div class="radio">
            <label class="col-sm-2" name="URSRC_lbl_loginsearchupdate" id="URSRC_lbl_loginsearchupdate" style="white-space: nowrap!important;">
                &nbsp;&nbsp;&nbsp;&nbsp;<input   type='radio' name='URSRC_mainradiobutton' id='URSRC_radio_loginsearchupdate' value='LOGIN SEARCH UPDATE'>
                LOGIN SEARCH / UPDATE
            </label>
        </div>
    </div>
    <div class="form-group">
        <label id="URSRC_lbl_header" class="srctitle col-sm-2" style="white-space: nowrap!important;"></label>
    </div >
</div>
<div id="URSRC_tble_basicrolemenucreation" hidden>
    <div class="form-group">
        <div id="URSRC_tble_basicroles" hidden ></div>
    </div>

    <div><label id="URSRC_lbl_basicrole_err" class="errormsg"></label></div>
</div>
<div id="URSRC_tble_basicrolemenusearch" hidden>

    <div id="URSRC_tble_search_basicroles" hidden ></div>

</div>
<div class="form-group">
    <div id="URSRC_tble_basicroles_chk" hidden ></div>
</div>
<div id="URSRC_tble_role" hidden>
    <label  class=" col-sm-2" >ROLE<em>*</em></label>
    <div class="form-group">
        <div class="col-sm-3"><input type="text" name="URSRC_tb_customrole" id="URSRC_tb_customrole" maxlength="15" class="autosize form-control" placeholder="ROLE" /></div>
        <label id="URSRC_lbl_role_err" class="errormsg"></label>
    </div>
    <div class="form-group">
        <div id="URSRC_tble_roles" hidden ></div>
    </div>
</div>

<div id="URSRC_tble_login" hidden>


    <div ><label id="URSRC_lbl_nologin_err" class="errormsg"></label></div>

    <div class="form-group">
        <label id="URSRC_lbl_loginid" class=" col-sm-2">USER NAME<em>*</em></label>
        <div class="col-sm-3"> <input type="text" name="URSRC_tb_loginid" id="URSRC_tb_loginid" placeholder="UserName" maxlength="40" class="alphanumericdot login_submitvalidate URSRC_email_validate form-control autosize" hidden /></div>
        <label id="URSRC_lbl_email_err" class="errormsg"></label>
    </div>

    <div class="form-group">
        <label id="URSRC_lbl_selectloginid" class="col-sm-2" >EMPLOYEE NAME<em>*</em></label>
        <div class="col-sm-3"><select id='URSRC_lb_selectloginid' name="URSRC_lb_loginid" title="LOGIN ID" maxlength="40" placeholder="Employee Name" class="form-control "    >
                <option value='SELECT' selected="selected"> SELECT</option>
            </select></div></div>

    <div class="form-group">
        <label id="URSRC_lbl_loginidupd" class=" col-sm-2" >USER NAME<em>*</em></label>
        <div class="col-sm-3"><input type="text" name="URSRC_tb_loginidupd" id="URSRC_tb_loginidupd" placeholder="UserName" class="alphanumericdot login_submitvalidate URSRC_email_validate form-control " hidden /></div>
        <label id="URSRC_lbl_email_errupd" class="errormsg  col-sm-2" ></label>
    </div>

    <div class="form-group">
        <label name="URSRC_lbl_pword" id="URSRC_lbl_pword"class="col-sm-2" hidden>PASSWORD<em>*</em></label>
        <div class="col-sm-3"><input type="password"  name="URSRC_tb_pword" id="URSRC_tb_pword" class="chk_password form-control" placeholder="PassWord" hidden /></div>
        <label id="URSRC_lbl_passwrd_errupd" class="errormsg  col-sm-2"></label>
    </div>
    <div class="form-group">
        <label name="URSRC_lbl_cpword" id="URSRC_lbl_cpword" class="col-sm-2" hidden>CONFIRM PASSWORD<em>*</em></label>
        <div class="col-sm-3"> <input type="text"  name="URSRC_tb_cpword" id="URSRC_tb_cpword" class="chk_confirm_password form-control" placeholder="Confirm PassWord"  hidden /></div>
        <label id="URSRC_lbl_confirmpasswrd_errupd" class="errormsg  col-sm-2"></label>
    </div>
    <div class="form-group">
        <label id="URSRC_lbl_emptype" class="col-sm-2" hidden>SELECT EMPLOYEE TYPE <em>*</em></label>
        <div class="col-sm-3"><select id='URSRC_lb_selectemptype' name="URSRC_lb_selectemptype"  maxlength="40" class="login_submitvalidate form-control " hidden  >
                <option value='SELECT' selected="selected"> SELECT</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label id="URSRC_lbl_selectteam" class="col-sm-2" hidden>TEAM<em>*</em></label>
        <div class="col-sm-3"><select id='URSRC_lb_selectteam' name="URSRC_lb_selectteam"  maxlength="40" class="login_submitvalidate form-control" hidden  >
                <option value='SELECT' selected="selected"> SELECT</option>
            </select>
        </div><input type="button" value="ADD" class="btn btn-info " id="URSRC_btn_add" hidden>
    </div>

    <div class="form-group">
        <div id="URSRC_tble_rolecreation" hidden></div>
    </div>


    <div id="joindate">

        <div class="form-group">
            <label id="URSRC_lbl_joindate" class="col-sm-2" hidden >SELECT A JOIN DATE<em>*</em></label>
            <div class="col-sm-10"><input type="text" name="URSRC_tb_joindate" placeholder="Join Date" id="URSRC_tb_joindate" class="datepicker login_submitvalidate datemandtry form-control" style="width:110px;" hidden  /></div>
        </div>

    </div>

</div>


<div id="URSRC_table_employeetbl"   hidden>

    <label class="srctitle"  name="URSRC_lbl_personnaldtls" id="URSRC_lbl_personnaldtls" class=" col-sm-2">PERSONAL DETAILS</label>


    <div class="form-group">

        <label name="URSRC_lbl_firstname" id="URSRC_lbl_firstname" class="col-sm-2">FIRST NAME <em>*</em></label>
        <div class="col-sm-3"><input type="text" name="URSRC_tb_firstname" id="URSRC_tb_firstname" maxlength='30' placeholder="First Name" class="autosizealph sizefix title_alpha login_submitvalidate form-control " ></div>
    </div>

    <div class="form-group">

        <label name="URSRC_lbl_lastname" id="URSRC_lbl_lastname" class="col-sm-2" >LAST NAME <em>*</em></label>
        <div class="col-sm-3"><input type="text" name="URSRC_tb_lastname" id="URSRC_tb_lastname" maxlength='30' placeholder="Last Name" class="autosizealph sizefix title_alpha login_submitvalidate form-control"></div>
    </div>

    <div class="form-group">

        <label name="URSRC_lbl_dob" id="URSRC_lbl_dob" class="col-sm-2">DATE OF BIRTH<em>*</em></label>
        <div class="col-sm-10"><input type="text" name="URSRC_tb_dob" id="URSRC_tb_dob" placeholder="Date Of Birth" class="datepickerdob datemandtry login_submitvalidate form-control " style="width:110px;"></div>
    </div>

    <div class="row form-group">
        <label name="URSRC_lbl_gender" id="URSRC_lbl_gender" class="col-sm-2">GENDER<em>*</em></label>

        <label class="radio-inline">
            <input type="radio" id="URSRC_rd_male"  name="URSRC_rd_gender" value="MALE" class="login_submitvalidate">MALE
        </label>
        <label class="radio-inline">
            <input type="radio" name="URSRC_rd_gender" id="URSRC_rd_female"  value="FEMALE" class="login_submitvalidate ">FEMALE
        </label>

    </div>
    <div class="form-group">
        <label name="URSRC_lbl_nric" id="URSRC_lbl_nric" class="col-sm-2">NRIC NO<em>*</em></label>
        <div class="col-sm-3"><input type="text" name="URSRC_tb_nric" id="URSRC_tb_nric" maxlength='10' placeholder="NRIC No" class="alphanumericuppercse sizefix login_submitvalidate form-control check_nric" style="width:120px"></div>
        <!--        <label id="URSRC_lbl_invalidnric" name="URSRC_lbl_invalidnric" class="errormsg"></label>-->
    </div>

    <div class="form-group">
        <label name="URSRC_lbl_designation" id="URSRC_lbl_designation" class="col-sm-2">DESIGNATION<em>*</em></label>
        <div class="col-sm-3"><input type="text" name="URSRC_tb_designation" id="URSRC_tb_designation" maxlength='50' placeholder="Designation" class="alphanumericuppercse sizefix login_submitvalidate form-control"></div>
    </div>


    <div class="form-group">
        <label name="URSRC_lbl_emailid" id="URSRC_lbl_emailid" class="col-sm-2">EMAIL ID<em>*</em></label>
        <div class="col-sm-3"><input type="text" name="URSRC_tb_emailid" id="URSRC_tb_emailid" maxlength='50' placeholder="Email Id" class="login_submitvalidate form-control"></div>
        <div><label id="URSRC_lbl_email_error" class="errormsg"></label></div>
    </div>



    <div class="form-group">

        <label name="URSRC_lbl_permobile" id="URSRC_lbl_permobile" class="col-sm-2">PERSONAL MOBILE<em>*</em></label>
        <div class="col-sm-3"><input type="text" name="URSRC_tb_permobile" id="URSRC_tb_permobile" placeholder="Personal No" maxlength='8' class="mobileno title_nos valid login_submitvalidate numonlynozero form-control " style="width:110px" ></div>
        <label id="URSRC_lbl_validnumber" name="URSRC_lbl_validnumber" class="errormsg"></label>
    </div>

    <div class="form-group">

        <label name="URSRC_lbl_kinname" id="URSRC_lbl_kinname" class="col-sm-2">NEXT KIN NAME<em>*</em></label>
        <div class="col-sm-3"><input type="text" name="URSRC_tb_kinname" id="URSRC_tb_kinname" maxlength='30' placeholder="Next Kin Name" class="autosizealph sizefix title_alpha login_submitvalidate form-control"></div>
    </div>

    <div class="form-group">

        <label name="URSRC_lbl_relationhd" id="URSRC_lbl_relationhd" class="col-sm-2">RELATION HOOD<em>*</em></label>
        <div class="col-sm-3"><input type="text" name="URSRC_tb_relationhd" id="URSRC_tb_relationhd" maxlength='30' placeholder="Relation Hood" class="autosizealph sizefix title_alpha login_submitvalidate form-control" ></div>
    </div>

    <div class="form-group">

        <label name="URSRC_lbl_mobile" id="URSRC_lbl_mobile" class="col-sm-2">MOBILE NO<em>*</em></label>
        <div class="col-sm-3"><input type="text" name="URSRC_tb_mobile" id="URSRC_tb_mobile" placeholder="Mobile No" class="mobileno title_nos valid login_submitvalidate numonlynozero form-control " maxlength='8' style="width:110px"></div>
        <label id="URSRC_lbl_validnumber1" name="URSRC_lbl_validnumber1" class="errormsg"></label>
    </div>

    <div class="form-group">
        <label name="URSRC_lbl_address" id="URSRC_lbl_address" class="col-sm-2">ADDRESS<em>*</em></label>
        <div class="col-sm-10"> <textarea  name="URSRC_ta_address" id="URSRC_ta_address" placeholder="Address" class="maxlength login_submitvalidate textareaupd form-control"></textarea>
        </div>
    </div>


    <label class="srctitle"  name="URSRC_lbl_bnkdtls" id="URSRC_lbl_bnkdtls" class=" col-sm-2">BANK DETAILS</label>

    <div class="form-group">

        <label name="URSRC_lbl_bnkname" id="URSRC_lbl_bnkname" class="col-sm-2">BANK NAME <em>*</em></label>
        <div class="col-sm-3"> <input type="text" name="URSRC_tb_bnkname" placeholder="Bank Name" id="URSRC_tb_bnkname" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate form-control" ></div>
    </div>

    <div class="form-group">

        <label name="URSRC_lbl_brnchname" id="URSRC_lbl_brnchname" class="col-sm-2">BRANCH NAME <em>*</em></label>
        <div class="col-sm-3"><input type="text" name="URSRC_tb_brnchname" placeholder="Branch Name" id="URSRC_tb_brnchname" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate form-control" ></div>
    </div>

    <div class="form-group">

        <label name="URSRC_lbl_accntname" id="URSRC_lbl_accntname" class="col-sm-2">ACCOUNT NAME <em>*</em></label>
        <div class="col-sm-3"><input type="text" name="URSRC_tb_accntname" placeholder="Account Name" id="URSRC_tb_accntname" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate form-control" ></div>
    </div>

    <div class="form-group">

        <label name="URSRC_lbl_accntno" id="URSRC_lbl_accntno" class="col-sm-2">ACCOUNT NUMBER <em>*</em></label>
        <div class="col-sm-3"><input type="text" name="URSRC_tb_accntno" placeholder="Account Number" id="URSRC_tb_accntno" maxlength='50' class=" sizefix accntno login_submitvalidate form-control" ></div>
    </div>

    <div class="form-group">

        <label name="URSRC_lbl_ifsccode" id="URSRC_lbl_ifsccode" class="col-sm-2">IFSC CODE<em>*</em></label>
        <div class="col-sm-3"><input type="text" name="URSRC_tb_ifsccode" placeholder="IFSC Code" id="URSRC_tb_ifsccode" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate form-control" ></div>
    </div>

    <div class="form-group">

        <label name="URSRC_lbl_accntyp" id="URSRC_lbl_accntyp" class="col-sm-2">ACCOUNT TYPE<em>*</em></label>
        <div class="col-sm-3"><input type="text" name="URSRC_tb_accntyp" placeholder="Account Type" id="URSRC_tb_accntyp" maxlength='15' class="alphanumericuppercse sizefix login_submitvalidate form-control " ></div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2" name="URSRC_lbl_brnchaddr" id="URSRC_lbl_brnchaddr" style="white-space: nowrap!important;">BRANCH ADDRESS<em>*</em></label>
        <div class="col-sm-10">
            <textarea  rows="4" cols="50" name="URSRC_ta_brnchaddr" placeholder="Branch Address" id="URSRC_ta_brnchaddr" class="maxlength login_submitvalidate textareaupd form-control"></textarea>
        </div>
    </div>

    <div class="form-group">
        <label name="URSRC_lbl_comments" id="URSRC_lbl_comments" class="col-sm-2">COMMENTS</label>
        <div class="col-sm-10"> <textarea  name="URSRC_ta_comments" placeholder="Comments" id="URSRC_ta_comments" class="maxlength login_submitvalidate textareaupd form-control"></textarea>
        </div>

    </div>

<!--    <div>-->
<!--        <div ID="exsistingfiletable" class="form-group row">-->
<!---->
<!--        </div>-->
<!---->
<!---->
<!--        <div ID="filetableuploads" class="form-group row">-->
<!---->
<!--        </div>-->
<!--    </div>-->
<!--    <div>-->
<!--        <div id="attachprompt" class="col-sm-offset-2 col-sm-10"><img width="15" height="15" src="image/paperclip.gif" border="0">-->
<!--            <a href="javascript:_addAttachmentFields('attachmentarea')" id="attachafile">Attach a file</a>-->
<!--        </div>-->
<!--    </div>-->
    <input class="btn btn-info" type="button"  id="URSRC_btn_login_submitbutton" name="SAVE" value="SUBMIT" disabled hidden />
</div>
<div id="URSRC_tble_rolesearch" hidden >
    <div class="form-group">
        <label id="URSRC_lbl_norole_err" class="errormsg"></label>
    </div>
    <div class="form-group">
        <label id="URSRC_lbl_selectrole" class=" col-sm-2">SELECT A ROLE<em>*</em></label>
        <div class="col-sm-3"> <select id='URSRC_lb_selectrole' name="URSRC_lb_rolename" title="ROLE" class='submitvalidate form-control' >
                <option value='SELECT' selected="selected"> SELECT</option>
            </select></div>
    </div>
    <div class="form-group">
        <div id="URSRC_rolesearch_roles"></div>
    </div>
</div>
<table id="URSRC_btn_update"></table>
<label id="URSRC_lbl_nodetails_err" class="errormsg"></label>
<div class="table-responsive">
    <table id="URSRC_tble_menu" hidden ></table>
</div>
<table id="URSRC_tble_folder" hidden></table>
<input class="btn  btn-info" type="button"  id="URSRC_btn_submitbutton" name="SAVE" value="SUBMIT"  disabled/>
</form>
</div>
</div>
</div>
<!--</div>-->
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->
