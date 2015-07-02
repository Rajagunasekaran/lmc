<?php
include "../../SUBFOLDERMENU.php";
?>
<html>
<head>
    <script>
        var ErrorControl={EmailId:'Invalid'}
        var EP_ENTRY_listboxname;
        var EP_SRC_UPD_DEL_result_array=[];
        var EP_SRC_UPD_DEL_name;
        var EP_SRC_UPD_DEL_emailid_id='';
        var EP_SRC_UPD_DEL_sucsval=0;
        $(document).ready(function() {
            $('#EP_ENTRY_lb_profilename').hide();
            $('#EP_ENTRY_btn_save').hide();
            $('#EP_ENTRY_btn_reset').hide();
            $(document).on('click','.PE_rd_selectform',function(){
                $('.preloader').show();
                var radioval=$(this).val();
                if(radioval=="EMAIL ENTRY")
                {
                    $('#divemailprofileentry').show();
                    $('#divupdateform').hide();
                    $('#EP_SRC_UPD_DEL_div_header').hide();
                    $('#EP_SRC_UPD_DEL_div_headernodata').hide();
                    $('#EP_SRC_UPD_DEL_tble_htmltable').hide();
                    $('#EP_ENTRY_lbl_validid').hide();
                    $('#emailid').hide();
                    $('#EP_ENTRY_btn_save').hide();
                    $('#EP_ENTRY_btn_reset').hide();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $('.preloader').hide();
                            var value_array=JSON.parse(xmlhttp.responseText);
                           EP_ENTRY_getdomain_errresult(value_array)
                        }
                    }
                    var option="EP_ENTRY_getdomain_err";
                    xmlhttp.open("GET","DB_EMAIL_PROFILE_ENTRY_SEARCH_UPDATE_DELETE.php?option="+option);
                    xmlhttp.send();
                }
                else
                {
                    $('#divemailprofileentry').hide();
                    $('#EP_SRC_UPD_DEL_div_header').hide();
                    $('#EP_SRC_UPD_DEL_div_headernodata').hide();
                    $('#EP_SRC_UPD_DEL_tble_htmltable').hide();
                    $('#emailid').hide();
                    $('#divupdateform').show();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $('.preloader').hide();
                            var value_array=JSON.parse(xmlhttp.responseText);
                            EP_SRC_UPD_DEL_searchoptionresult(value_array)
                        }
                    }
                    var option="EP_SRC_UPD_DEL_searchoption";
                    xmlhttp.open("GET","DB_EMAIL_PROFILE_ENTRY_SEARCH_UPDATE_DELETE.php?option="+option);
                    xmlhttp.send();
                }
            });
            var EP_ENTRY_emailarr_profilename=[];
            var EP_ENTRY_errorMsg_array=[];
            //SUCCESS FUNCTION FOR EMAIL PROFILE NAME,ERROR MESSAGE
            function EP_ENTRY_getdomain_errresult(EP_ENTRY_getdomain_errresult_response)
            {
                EP_ENTRY_emailarr_profilename=EP_ENTRY_getdomain_errresult_response.EP_ENTRY_profilenamedataid;
                EP_ENTRY_errorMsg_array=EP_ENTRY_getdomain_errresult_response.EP_ENTRY_errormsg;
                var EP_ENTRY_emailarray_profilename ='<option>SELECT</option>';
                for (var i = 0; i < EP_ENTRY_emailarr_profilename.length; i++)
                {
                    EP_ENTRY_emailarray_profilename += '<option value="' + EP_ENTRY_emailarr_profilename[i].EP_ENTRY_profile_names_id + '">' + EP_ENTRY_emailarr_profilename[i].EP_ENTRY_profile_names + '</option>';
                }
                $('#profilename').show();
                $('#EP_ENTRY_lb_profilename').html(EP_ENTRY_emailarray_profilename).show();
             }
            $('#EP_ENTRY_tb_emailid').doValidation({rule:'email',prop:{uppercase:false,autosize:true}});
            //CHANGE EVENT FOR PROFILE
            $('#EP_ENTRY_lb_profilename').change(function(){
                $(".preloader").show();
                EP_ENTRY_listboxname=$('#EP_ENTRY_lb_profilename').find('option:selected').text();
                var EP_ENTRY_profilename=$(this).val();
                $('#EP_ENTRY_tb_emailid').prop("size","20");
                $("#EP_ENTRY_btn_save").attr("disabled","disabled");
                if(EP_ENTRY_profilename=='SELECT')
                {
                    $(".preloader").hide();
                    $('#EP_ENTRY_lbl_emailid').hide();
                    $('#EP_ENTRY_tb_emailid').hide();
                    $('#emailid').hide();
                    $('#EP_ENTRY_btn_save').hide();
                    $('#EP_ENTRY_btn_reset').hide();
                    $('#EP_ENTRY_lbl_validid').hide();
                }
                else
                {
                    $(".preloader").hide();
                    $('#EP_ENTRY_tb_emailid').val('');
                    $('#emailid').show();
                    $('#EP_ENTRY_lbl_emailid').show();
                    $('#EP_ENTRY_tb_emailid').show();
                    $('#EP_ENTRY_btn_save').show();
                    $('#EP_ENTRY_btn_reset').show();
                    $("#EP_ENTRY_tb_emailid").removeClass('invalid');
                    $('#EP_ENTRY_lbl_validid').hide();
                    var EP_ENTRY_id;
                    for(var k=0;k<EP_ENTRY_emailarr_profilename.length;k++)
                    {
                        if(EP_ENTRY_emailarr_profilename[k].EP_ENTRY_profile_names==EP_ENTRY_listboxname)
                        {
                            EP_SRC_UPD_DEL_id=(EP_ENTRY_emailarr_profilename[k].EP_ENTRY_profile_names_id);
                        }
                    }
                }
            });
            //BLUR FUNCTION FOR VALIDATION
            $("#EP_ENTRY_tb_emailid").blur(function(){
                $("#EP_ENTRY_hidden_chkvalid").val("")//SET VALIDATION FUNCTION VALUE
                EP_ENTRY_checkmailid()
            });
            //EMAIL SUBMIT BUTTON VALIDATION
            function EP_ENTRY_checkmailid() {
                var EP_ENTRY_email = $("#EP_ENTRY_tb_emailid").val();
                if (EP_ENTRY_email.length == 0 || EP_ENTRY_listboxname == 'SELECT') {
                    $("#EP_ENTRY_btn_save").attr("disabled", "disabled");
                    $('#EP_ENTRY_lbl_validid').hide();
                    $("#EP_ENTRY_tb_emailid").removeClass('invalid');
                }
                else {
                    var EP_ENTRY_validtype = ErrorControl.EmailId;
                    if (EP_ENTRY_validtype == 'Valid') {
                        $(".preloader").show();
                        $('#EP_ENTRY_lbl_validid').hide();
                        $("#EP_ENTRY_tb_emailid").removeClass('invalid');
                        $('#EP_ENTRY_tb_emailid').val($('#EP_ENTRY_tb_emailid').val().toLowerCase());
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange = function () {
                            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                $('.preloader').hide();
                                var response= xmlhttp.responseText;
                                EP_ENTRY_already_result(response)
                            }
                        }
                        var option = "EP_ENTRY_already";
                        xmlhttp.open("GET", "DB_EMAIL_PROFILE_ENTRY_SEARCH_UPDATE_DELETE.php?option=" + option + "&EP_ENTRY_listboxname=" + EP_ENTRY_listboxname + "&EP_ENTRY_email=" + EP_ENTRY_email);
                        xmlhttp.send();

                    }
                    else {
                        $('#EP_ENTRY_lbl_validid').text(EP_ENTRY_errorMsg_array[1]).show();
                        $("#EP_ENTRY_tb_emailid").addClass('invalid');
                        $("#EP_ENTRY_btn_save").attr("disabled", "disabled");
                    }
                }
            }
            //SUCCESS FUNCTION FOR ALREADY EXIST FOR EMAIL ID
            function EP_ENTRY_already_result(EP_ENTRY_response)
            {
                $(".preloader").hide();
                var EP_ENTRY_profilenameid=$('#EP_ENTRY_lb_profilename').val();
                var EP_ENTRY_emailid=$('#EP_ENTRY_tb_emailid').val();
                if(EP_ENTRY_response==0)
                {
                    if($("#EP_ENTRY_hidden_chkvalid").val()=="" && EP_ENTRY_emailid!='')
                    {
                        $("#EP_ENTRY_btn_save").removeAttr("disabled");
                    }
                    else
                    {
                        $(".preloader").show();
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange = function () {
                            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                $('.preloader').hide();
                                var response= xmlhttp.responseText;
                                EP_ENTRY_save_result(response)
                            }
                        }
                        var option = "EP_ENTRY_save";
                        xmlhttp.open("GET", "DB_EMAIL_PROFILE_ENTRY_SEARCH_UPDATE_DELETE.php?option=" + option + "&EP_ENTRY_profilenameid=" + EP_ENTRY_profilenameid + "&EP_ENTRY_emailid=" + EP_ENTRY_emailid);
                        xmlhttp.send();
                    }
                }
                else
                {
                    $(".preloader").hide();
                    var EP_ENTRY_email_errmsg=EP_ENTRY_errorMsg_array[3].replace('[PROFILE]',EP_ENTRY_listboxname);
                    $('#EP_ENTRY_lbl_validid').show();
                    $('#EP_ENTRY_lbl_validid').text(EP_ENTRY_email_errmsg);
                    $("#EP_ENTRY_tb_emailid").addClass('invalid');
                    $("#EP_ENTRY_btn_save").attr("disabled","disabled");
                }
            }
            //SUCCESS FUNCTION FOR SAVE
            function EP_ENTRY_save_result(EP_ENTRY_response)
            {
                $(".preloader").hide();
                if((EP_ENTRY_response!=0)&&(EP_ENTRY_response!=undefined))
                {
                    $('#EP_ENTRY_lbl_emailid').hide();
                    $('#EP_ENTRY_tb_emailid').hide();
                    $('#EP_ENTRY_btn_save').attr("disabled","disabled").hide();
                    $('#EP_ENTRY_btn_reset').hide();
                    var EP_ENTRY_email_errmsg=EP_ENTRY_errorMsg_array[2].replace('[PROFILE]',EP_ENTRY_listboxname);
//MESSAGE BOX FOR SAVED SUCCESS
                    show_msgbox("EMAIL PROFILE ENTRY/UPDATE/DELETE",EP_ENTRY_email_errmsg,"error",false);
                    $('#EP_ENTRY_lb_profilename').val('SELECT');
                    $('#EP_ENTRY_tb_emailid').prop("size","20");
                    $('#EP_ENTRY_lbl_validid').hide();
                }
                else
                {
//MESSAGE BOX FOR NOT SAVED SUCCESS
                    show_msgbox("EMAIL PROFILE ENTRY/UPDATE/DELETE",EP_ENTRY_errorMsg_array[0],"error",false);
                }
            }
            //CLICK FUNCTION FOR SAVE BUTTON
            $("#EP_ENTRY_btn_save").click(function(){
                $(".preloader").show();
                $("#EP_ENTRY_hidden_chkvalid").val("SAVE")//SET SAVE FUNCTION VALUE
                var EP_ENTRY_emailid=$('#EP_ENTRY_tb_emailid').val();
                if(EP_ENTRY_emailid!="")
                {
                    EP_ENTRY_checkmailid()
                }
            });
            //CLICK FUNCTION FOR RESET BUTTON
            $('#EP_ENTRY_btn_reset').click(function()
            {
                $("#divemailprofileentry").find('input:text, input:password, input:file,textarea').val('');
                $("#divemailprofileentry").find('select').val('SELECT');
                $("#divemailprofileentry").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
                $('#EP_ENTRY_lbl_emailid').hide();
                $('#EP_ENTRY_tb_emailid').hide();
                $('#EP_ENTRY_btn_save').hide();
                $('#EP_ENTRY_btn_reset').hide();
                $('#EP_ENTRY_lbl_validid').hide();
                $("#EP_ENTRY_tb_emailid").removeClass('invalid');
                $("#EP_ENTRY_btn_save").attr("disabled","disabled");
                $('#EP_ENTRY_tb_emailid').prop("size","20");
            });
// UPDATE FORM STARTSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS
            //START UNIQUE FUNCTION
            function unique(a) {
                var result = [];
                $.each(a, function(i, e) {
                    if ($.inArray(e, result) == -1) result.push(e);
                });
                return result;
            }
            function EP_SRC_UPD_DEL_searchoptionresult(EP_SRC_UPD_DEL_searchoptionresult_response)
            {
                EP_SRC_UPD_DEL_emailarr_profilename=EP_SRC_UPD_DEL_searchoptionresult_response.EP_SRC_UPD_DEL_profilelistdataid;
                EP_SRC_UPD_DEL_errorMsg_array=EP_SRC_UPD_DEL_searchoptionresult_response.EP_SRC_UPD_DEL_errormsg;
                var EP_SRC_UPD_DEL_namearray=[];
                for(var k = 0;k < EP_SRC_UPD_DEL_emailarr_profilename.length;k++)
                {
                    EP_SRC_UPD_DEL_namearray.push(EP_SRC_UPD_DEL_emailarr_profilename[k].EP_SRC_UPD_DEL_profilenames_data+'_'+EP_SRC_UPD_DEL_emailarr_profilename[k].EP_SRC_UPD_DEL_profilenames_id)
                }
                EP_SRC_UPD_DEL_namearray=unique(EP_SRC_UPD_DEL_namearray);
                var EP_SRC_UPD_DEL_emailarray_profilename ='<option>SELECT</option>';
                for (var i = 0;i < EP_SRC_UPD_DEL_namearray.length; i++)
                {
                    var EP_SRC_UPD_DEL_profilenameidconcat=EP_SRC_UPD_DEL_namearray[i].split("_");
                    EP_SRC_UPD_DEL_emailarray_profilename += '<option value="'+EP_SRC_UPD_DEL_profilenameidconcat[1]+'">'+EP_SRC_UPD_DEL_profilenameidconcat[0]+'</option>';
                }
                $('#EP_SRC_UPD_DEL_div_profile').show();
                $('#EP_SRC_UPD_DEL_lb_profile').html(EP_SRC_UPD_DEL_emailarray_profilename).show();
                if(EP_SRC_UPD_DEL_sucsval==2)
                {
                    $(".preloader").hide();
                //MESSAGE BOX FOR DELETE SUCCESSFULLY
                    var EP_SRC_UPD_DEL_errmsg=EP_SRC_UPD_DEL_errorMsg_array[6].replace('[PROFILE]',EP_SRC_UPD_DEL_name);
                    show_msgbox("EMAIL PROFILE ENTRY/UPDATE/DELETE",EP_SRC_UPD_DEL_errmsg,"success",false);
                }
            }
             var EP_SRC_UPD_DEL_id;
            //CHANGE EVENT FUCNTION FOR PROFILE
            $('#EP_SRC_UPD_DEL_lb_profile').change(function()
            {
                $(".preloader").show();
                EP_SRC_UPD_DEL_sucsval=0;
                EP_SRC_UPD_DEL_name=$('#EP_SRC_UPD_DEL_lb_profile').find('option:selected').text();
                $('#EP_SRC_UPD_DEL_tble_htmltable').hide();
                $('#EP_SRC_UPD_DEL_div_header').hide();
                $('#EP_SRC_UPD_DEL_div_headernodata').hide();
                var EP_SRC_UPD_DEL_profilename=$("#EP_SRC_UPD_DEL_lb_profile").val();
                if(EP_SRC_UPD_DEL_profilename=='SELECT')
                {
                    $(".preloader").hide();
                    $('#EP_SRC_UPD_DEL_tble_srchupd').hide();
                    $('#EP_SRC_UPD_DEL_tble_htmltable').hide();
                    $('#EP_SRC_UPD_DEL_div_update').hide();
                    $('#EP_SRC_UPD_DEL_div_header').hide();
                    $('#EP_SRC_UPD_DEL_div_headernodata').hide();
                }
                else
                {
                    $('#EP_SRC_UPD_DEL_tble_srchupd').hide();
                    $('#EP_SRC_UPD_DEL_div_update').hide();
                    EP_SRC_UPD_DEL_id;
                    for(var j=0;j<EP_SRC_UPD_DEL_emailarr_profilename.length;j++)
                    {
                        if(EP_SRC_UPD_DEL_emailarr_profilename[j].EP_SRC_UPD_DEL_profilenames_data==EP_SRC_UPD_DEL_name)
                        {
                            EP_SRC_UPD_DEL_id=(EP_SRC_UPD_DEL_emailarr_profilename[j].EP_SRC_UPD_DEL_profilenames_id);
                        }
                    }
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                            $('.preloader').hide();
                            var response= JSON.parse(xmlhttp.responseText);
                            EP_SRC_UPD_DEL_srch_result(response)
                        }
                    }
                    var option = "EP_SRC_UPD_DEL_srch";
                    xmlhttp.open("GET", "DB_EMAIL_PROFILE_ENTRY_SEARCH_UPDATE_DELETE.php?option=" + option + "&EP_SRC_UPD_DEL_id=" + EP_SRC_UPD_DEL_id);
                    xmlhttp.send();
                    var EP_SRC_UPD_DEL_errmsg=EP_SRC_UPD_DEL_errorMsg_array[7].replace('[PROFILE]',EP_SRC_UPD_DEL_name);
                    $('#EP_SRC_UPD_DEL_div_header').text(EP_SRC_UPD_DEL_errmsg);
                }
            });
            //SUCCESS FUNCTION FOR SELECTING DATA
            function EP_SRC_UPD_DEL_srch_result(response)
            {
                $('.preloader').hide();
                EP_SRC_UPD_DEL_result_array=response;
                //ERROR MESSAGE FOR NO DATA
                if(response.length==0)
                {
                    $('#EP_SRC_UPD_DEL_div_header').hide();
                    $('#EP_SRC_UPD_DEL_tble_htmltable').hide();
                    var EP_SRC_UPD_DEL_errmsg=EP_SRC_UPD_DEL_errorMsg_array[4].replace('[PROFILE]',EP_SRC_UPD_DEL_name);
                    $('#EP_SRC_UPD_DEL_div_headernodata').text(EP_SRC_UPD_DEL_errmsg).show();
                    $(".preloader").hide();
                }
                else
                {
                //ERROR MESSAGE FOR HAVING DATA
                    if(EP_SRC_UPD_DEL_result_array!=0)
                    {
                        EP_SRC_UPD_DEL_result_array=response
                        var EP_SRC_UPD_DEL_header='';
                        EP_SRC_UPD_DEL_header+='<table id="ET_tble_htmltable" border="1" cellspacing="0" width="700" class="srcresult"><thead><tr><th style="width:5px"></th><th style="width:30px;text-align: center">EMAIL ID</th><th style="width:20px">USERSTAMP</th><th th style="width:120px">TIMESTAMP</th></tr></thead><tbody>';
                        for(var i=0;i<EP_SRC_UPD_DEL_result_array.length;i++)
                        {
                            var EP_SRC_UPD_DEL_values=EP_SRC_UPD_DEL_result_array[i]
                            EP_SRC_UPD_DEL_header+='<tr><td style="width: 5px;"><div class="col-sm-1"><span style="display: block;color:red" class="glyphicon glyphicon-trash  emaildelete" id="delete_'+EP_SRC_UPD_DEL_values.emailno+'"></span></div></td><td id="edit_'+EP_SRC_UPD_DEL_values.emailno+'" class="emailidedit">'+EP_SRC_UPD_DEL_values.emailid+'</td><td>'+EP_SRC_UPD_DEL_values.userstamp+'</td><td>'+EP_SRC_UPD_DEL_values.timestamp+'</td></tr>';
                        }
                        EP_SRC_UPD_DEL_header+='</tbody></table>';
                        $('section').html(EP_SRC_UPD_DEL_header);
                        $('#ET_tble_htmltable').DataTable( {
                            "aaSorting": [],
                            "pageLength": 10,
                            "sPaginationType":"full_numbers"
                        });
                        $('#EP_SRC_UPD_DEL_div_header').show();
                        $('#EP_SRC_UPD_DEL_tble_htmltable').show();
                        $(".preloader").hide();
                        $('#EP_SRC_UPD_DEL_div_headernodata').hide();
                    }
                }
                if(EP_SRC_UPD_DEL_result_array.length==0)
                {
                    $(".preloader").show();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $('.preloader').hide();
                            var value_array=JSON.parse(xmlhttp.responseText);
                            EP_SRC_UPD_DEL_searchoptionresult(value_array)
                        }
                    }
                    var option="EP_SRC_UPD_DEL_searchoption";
                    xmlhttp.open("GET","DB_EMAIL_PROFILE_ENTRY_SEARCH_UPDATE_DELETE.php?option="+option);
                    xmlhttp.send();
                    $('#EP_SRC_UPD_DEL_div_header').hide();
                    $('#EP_SRC_UPD_DEL_tble_htmltable').hide();
                }
                else if(EP_SRC_UPD_DEL_sucsval==2)
                {
                    $(".preloader").hide();
                //MESSAGE BOX FOR DELETE SUCCESSFULLY
                    var EP_SRC_UPD_DEL_errmsg=EP_SRC_UPD_DEL_errorMsg_array[6].replace('[PROFILE]',EP_SRC_UPD_DEL_name);
                    show_msgbox("EMAIL PROFILE ENTRY/UPDATE/DELETE",EP_SRC_UPD_DEL_errmsg,"success",false);
                }
                if(EP_SRC_UPD_DEL_sucsval==1)
                {
                //MESSAGE BOX FOR UPDATE SUCCESSFULLY
                    var EP_SRC_UPD_DEL_errmsg=EP_SRC_UPD_DEL_errorMsg_array[5].replace('[PROFILE]',EP_SRC_UPD_DEL_name);
                    show_msgbox("EMAIL PROFILE ENTRY/UPDATE/DELETE",EP_SRC_UPD_DEL_errmsg,"success",false);
                }
            }
    var previous_id;
    var combineid;
    var tdvalue;
     $(document).on('click','.emailidedit',function(){
         if(previous_id!=undefined){
             $('#'+previous_id).replaceWith("<td align='center' class='emailidedit' id='"+previous_id+"' >"+tdvalue+"</td>");
         }
         var cid = $(this).attr('id');
         var id=cid.split('_');
         combineid=id[1];
         previous_id=cid;
         tdvalue = $(this).text();
         EP_SRC_UPD_DEL_emailid_id=$(this).text();
         $('#'+cid).replaceWith("<td align='center' class='new' id='"+previous_id+"'><input type='text' class='form-control emailidupdate uppercase autosize' id='EP_SRC_UPD_DEL_tb_updemailid' value='"+tdvalue+"'/></td>");
     });
           //BLUR FUNCTION FOR VALIDATION
            $(document).on('blur','#EP_SRC_UPD_DEL_tb_updemailid',function(){
                $("#EP_SRC_UPD_DEL_hidden_chkvalid").val("");//SET VALIDATION FUNCTION VALUE
                EP_SRC_UPD_DEL_checkmailid()
            });
            //EMAIL SUBMIT BUTTON VALIDATION
            function EP_SRC_UPD_DEL_checkmailid()
            {
                var EP_UPD_email = $("#EP_SRC_UPD_DEL_tb_updemailid").val();
                if (EP_UPD_email.length == 0) {
                    $("#EP_SRC_UPD_DEL_tb_updemailid").removeClass('invalid');
                }
                else {
                    var EP_UPD_validtype = ErrorControl.EmailId;
                    if(EP_UPD_email.substring(EP_UPD_email.indexOf("@") + 1)!="gmail.com")
                    {
                        $("#EP_SRC_UPD_DEL_tb_updemailid").addClass('invalid');
                    }
                    else
                    {
                        EP_UPD_validtype='Valid';
                        $("#EP_SRC_UPD_DEL_btn_update").attr("disabled","disabled");
                        $("#EP_SRC_UPD_DEL_tb_updemailid").removeClass('invalid');
                    }
                    if (EP_UPD_validtype != 'Invalid') {
                        if(EP_UPD_email!=tdvalue) {
                            $(".preloader").show();
                            $("#EP_SRC_UPD_DEL_tb_updemailid").removeClass('invalid');
                            $('#EP_SRC_UPD_DEL_tb_updemailid').val($('#EP_SRC_UPD_DEL_tb_updemailid').val().toLowerCase());
                            var xmlhttp = new XMLHttpRequest();
                            xmlhttp.onreadystatechange = function () {
                                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                    var response = xmlhttp.responseText;
                                    EP_SRC_UPD_DEL_update_result(response)
                                }
                            }
                            var option = "EP_Update";
                            xmlhttp.open("GET", "DB_EMAIL_PROFILE_ENTRY_SEARCH_UPDATE_DELETE.php?option=" + option + "&EP_UPD_email=" + EP_UPD_email + "&rowid=" + combineid);
                            xmlhttp.send();
                        }
                    }
                    else {
                        show_msgbox("EMAIL PROFILE ENTRY/UPDATE/DELETE",EP_SRC_UPD_DEL_errorMsg_array[2],"success",false);
                        $("#EP_SRC_UPD_DEL_tb_updemailid").addClass('invalid');
                    }
                }
            }
            function EP_SRC_UPD_DEL_update_result(response){
                if(response==1)
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange= function () {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                            $('.preloader').hide();
                            var response= JSON.parse(xmlhttp.responseText);
                            EP_SRC_UPD_DEL_srch_result(response)
                        }
                    }
                    var option = "EP_SRC_UPD_DEL_srch";
                    xmlhttp.open("GET", "DB_EMAIL_PROFILE_ENTRY_SEARCH_UPDATE_DELETE.php?option=" + option + "&EP_SRC_UPD_DEL_id=" + EP_SRC_UPD_DEL_id);
                    xmlhttp.send();
                    EP_SRC_UPD_DEL_sucsval=1;
                }
                else if(response==0)
                {
                    $(".preloader").hide();
                    show_msgbox("EMAIL PROFILE ENTRY/UPDATE/DELETE",EP_SRC_UPD_DEL_errorMsg_array[1],"success",false);
                }
                else
                {
                    $(".preloader").hide();
                    show_msgbox("EMAIL PROFILE ENTRY/UPDATE/DELETE",response,"success",false);
                }
            }
            var deleteid;
            $(document).on('click','.emaildelete',function(){
                deleteid=this.id.split('_')[1];
                show_msgbox("EMAIL PROFILE ENTRY/UPDATE/DELETE",EP_SRC_UPD_DEL_errorMsg_array[8],"success","delete");
            });
            $(document).on('click','.deleteconfirm',function(){
                $(".preloader").show();
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        var response = xmlhttp.responseText;
                        EP_SRC_UPD_DEL_delete_result(response)
                    }
                }
                var option = "EP_SRC_UPD_DEL_delete";
                xmlhttp.open("GET", "DB_EMAIL_PROFILE_ENTRY_SEARCH_UPDATE_DELETE.php?option=" + option + "&deleteid=" + deleteid );
                xmlhttp.send();
            });
            function EP_SRC_UPD_DEL_delete_result(response)
            {
                if(response==1)
                {
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange = function () {
                            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                $('.preloader').hide();
                                var response= JSON.parse(xmlhttp.responseText);
                                EP_SRC_UPD_DEL_srch_result(response)
                            }
                        }
                        var option = "EP_SRC_UPD_DEL_srch";
                        xmlhttp.open("GET", "DB_EMAIL_PROFILE_ENTRY_SEARCH_UPDATE_DELETE.php?option=" + option + "&EP_SRC_UPD_DEL_id=" + EP_SRC_UPD_DEL_id);
                        xmlhttp.send();
                        EP_SRC_UPD_DEL_sucsval=2;
                }
                else if(response==0)
                {
                    $(".preloader").hide();
                    show_msgbox("EMAIL PROFILE ENTRY/UPDATE/DELETE",EP_SRC_UPD_DEL_errorMsg_array[0],"success",false);
                }
                else
                {
                    $(".preloader").hide();
                    show_msgbox("EMAIL PROFILE ENTRY/UPDATE/DELETE",response,"success",false);
                }
            }
        });
        //READY FUNCTION END
    </script>
    <!--SCRIPT TAG END-->
    <!--BODY TAG START-->
</head>
<body>
<div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
<div class="container">
    <form id="EP_ENTRY_form_emailprofile" name="EP_ENTRY_form_emailprofile" class="form-horizontal content" role="form">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h2 class="panel-title">EMAIL PROFILE ENTRY/ SEARCH/ UPDATE</h2>
        </div>
        <div class="panel-body">
                <div style="padding-bottom: 15px">
                    <div class="radio">
                        <label><input type="radio" name="optradio" value="EMAIL ENTRY" class="PE_rd_selectform">ENTRY</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="optradio" value="EMAIL SEARCH/UPDATE" class="PE_rd_selectform">SEARCH/ UPDATE/ DELETE</label>
                    </div>
                </div>
            <div id="divemailprofileentry">
                <div class="form-group" id="profilename" hidden>
                    <label id="EP_ENTRY_lbl_profilename" class="col-sm-2">PROFILE NAME<em>*</em></label>
                    <div class="col-sm-4">
                        <select class="form-control  validation" name="EP_ENTRY_lb_profilename"  id="EP_ENTRY_lb_profilename">
                            <option>SELECT</option></select>
                    </div>
                </div>
                <div class="form-group" id="emailid" hidden>
                    <label name="EP_ENTRY_lbl_emailid" id="EP_ENTRY_lbl_emailid" class="col-sm-2">E-MAIL ID<em>*</em></label>
                    <div class="col-sm-4">
                        <input  type="text" name="EP_ENTRY_tb_emailid" id="EP_ENTRY_tb_emailid" class="uppercase autosize form-control" maxlength=100/>
                    </div>
                    <div style="padding-top: 10px;">
                        <label id="EP_ENTRY_lbl_validid" name="EP_ENTRY_lbl_validid" class="errormsg"></label>
                    </div>
                </div>
                <div class="col-lg-offset-2">
                <input type="button" class="btn btn-info" name="EP_ENTRY_btn_save" id="EP_ENTRY_btn_save" value="SAVE" hidden>
                <input type="button" class="btn btn-info" name="EP_ENTRY_btn_reset" id="EP_ENTRY_btn_reset" value="RESET" hidden>
                </div>
                <input type=hidden id="EP_ENTRY_hidden_chkvalid">
           </div>
            <div id="divupdateform">
                <div  id="EP_SRC_UPD_DEL_div_profile" class="form-group" hidden>
                  <label name="EP_SRC_UPD_DEL_lbl_profile" id="EP_SRC_UPD_DEL_lbl_profile" class="col-sm-2">PROFILE NAME<em>*</em></label></td>
                  <div class="col-sm-4">
                      <select name="EP_SRC_UPD_DEL_name_profile" id="EP_SRC_UPD_DEL_lb_profile" class="form-control">
                                    <option>SELECT</option>
                                </select>
                  </div>
                </div>
                <div class="srctitle" name="EP_SRC_UPD_DEL_div_header" id="EP_SRC_UPD_DEL_div_header"></div>
                <div class="errormsg" name="EP_SRC_UPD_DEL_div_headernodata" id="EP_SRC_UPD_DEL_div_headernodata"></div>
                <div class="table-responsive" id="EP_SRC_UPD_DEL_tble_htmltable" style="max-width:700px;" hidden>
                    <section>
                    </section>
                </div>
                <input type=hidden id="EP_SRC_UPD_DEL_hidden_chkvalid">
        </div>
</div>
</form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->
