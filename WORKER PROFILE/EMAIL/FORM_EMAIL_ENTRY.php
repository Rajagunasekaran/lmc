<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMAIL ENTRY*********************************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:27/10/2014 ED:28/10/2014,TRACKER NO:1
//*********************************************************************************************************//
<?php
include "../../SUBFOLDERMENU.php";
?>
<!--SCRIPT TAG START-->
<script>
    //READY FUNCTION START
    $(document).ready(function(){
//        $('.preloader').show();
        var ET_ENTRY_chknull_input="";
        var ET_ENTRY_errormsg=[];
        //START FUNCTION FOR EMAIL TEMPLATE ERROR MESSAGE
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                $('#RPT').hide();
                $('#AE').hide();
                var value_array=JSON.parse(xmlhttp.responseText);
                ET_ENTRY_errormsg=value_array[0];
            }
        }
        var option="EMAIL_TEMPLATE_ENTRY";
        xmlhttp.open("GET","../../LMC_LIB/COMMON.php?option="+option);
        xmlhttp.send();
        //END FUNCTION FOR EMAIL TEMPLATE ERROR MESSAGE
        //JQUERY LIB VALIDATION START
        $("#ET_ENTRY_tb_scriptname").doValidation({rule:'general',prop:{autosize:true}});
        $('textarea').autogrow({onInitialize: true});
        //JQUERY LIB VALIDATION END
        //KEY PRESS FUNCTION START
        var ET_ENTRY_max=3000;
        $('.maxlength').keypress(function(e)
        {
            if(e.which < 0x20)
            {
                return;
            }
            if(this.value.length==ET_ENTRY_max)
            {
                e.preventDefault();
            }
            else if(this.value.length > ET_ENTRY_max)
            {
                this.value=this.value.substring(0,ET_ENTRY_max);
            }
        });
//KEY PRESS FUNCTION END
        //CHANGE FUNCTION FOR VALIDATION
        $("#ET_ENTRY_form_template").change(function(){
            $("#ET_ENTRY_hidden_chkvalid").val("")//SET VALIDATION FUNCTION VALUE
            ET_ENTRY_checkscriptname()
        });
        //CHANGE FUNCTION FOR VALIDATION
        $("#ET_ENTRY_tb_scriptname").blur(function(){
            $("#ET_ENTRY_hidden_chkvalid").val("")//SET VALIDATION FUNCTION VALUE
            ET_ENTRY_checkscriptname()
        });
        //BLUR FUNCTION FOR TRIM SUBJECT
        $("#ET_ENTRY_ta_subject").blur(function(){
            $('.preloader').hide();
            $('#ET_ENTRY_ta_subject').val($('#ET_ENTRY_ta_subject').val().toUpperCase())
            var trimfunc=($('#ET_ENTRY_ta_subject').val()).trim()
            $('#ET_ENTRY_ta_subject').val(trimfunc)
        });
//BLUR FUNCTION FOR TRIM BODY
        $("#ET_ENTRY_ta_body").blur(function(){
            $('.preloader').hide();
            $('#ET_ENTRY_ta_body').val($('#ET_ENTRY_ta_body').val().toUpperCase())
            var trimfunc=($('#ET_ENTRY_ta_body').val()).trim()
            $('#ET_ENTRY_ta_body').val(trimfunc)
        });
        //EMAIL TEMPLATE  SUBIT BUTTON VALIDATION
        function ET_ENTRY_checkscriptname()
        {
            var ET_ENTRY_scriptnametxt=$('#ET_ENTRY_tb_scriptname').val();
            var ET_ENTRY_subjecttxt=$('#ET_ENTRY_ta_subject').val();
            var ET_ENTRY_bodytxt=$('#ET_ENTRY_ta_body').val();
            if((ET_ENTRY_scriptnametxt.trim()=="") ||(ET_ENTRY_subjecttxt.trim()=="") || (ET_ENTRY_bodytxt.trim()==""))
            {
                $("#ET_ENTRY_btn_save").attr("disabled", "disabled");
                ET_ENTRY_chknull_input=false;
            }
            else
            {
                ET_ENTRY_chknull_input=true;
            }
            var ET_ENTRY_scriptname=$('#ET_ENTRY_tb_scriptname').val();
            if(ET_ENTRY_scriptname!="")
            {
                ET_ENTRY_already_result()
            }
//SUCCESS FUNCTION FOR ALREADY EXIST FOR SCRIPT NAME
            function ET_ENTRY_already_result()
            {
                var ET_ENTRY_scriptname=$('#ET_ENTRY_tb_scriptname').val();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $('.preloader').hide();
                        var ET_ENTRY_response=xmlhttp.responseText;
                        var ET_ENTRY_chkinput=ET_ENTRY_response;
                        if(ET_ENTRY_chkinput==0)
                        {
                            $('#ET_ENTRY_lbl_validid').hide();
                            $("#ET_ENTRY_tb_scriptname").removeClass('invalid');
                        }
                        if(ET_ENTRY_chkinput==0&&ET_ENTRY_chknull_input==true)
                        {
                            if($("#ET_ENTRY_hidden_chkvalid").val()=="")
                            {
                                $('#ET_ENTRY_lbl_validid').hide();
                                $("#ET_ENTRY_btn_save").removeAttr("disabled");
                            }
                            else
                            {
                                ET_ENTRY_save_resultsuccess()
                                $("#ET_ENTRY_hidden_chkvalid").val("");
                            }
                        }
                        else if(ET_ENTRY_chkinput==1)
                        {
                            $('.preloader').hide();
                            $('#ET_ENTRY_lbl_validid').show();
                            $('#ET_ENTRY_lbl_validid').text(ET_ENTRY_errormsg[2]);
                            $("#ET_ENTRY_tb_scriptname").addClass('invalid');
                            $("#ET_ENTRY_btn_save").attr("disabled", "disabled");
                        }
                    }
                }
                var choice='ET_ENTRY_already_result';
                xmlhttp.open("GET","DB_EMAIL_ENTRY.php?ET_ENTRY_scriptname="+ET_ENTRY_scriptname+"&option="+choice,true);
                xmlhttp.send();
            }
        }
        //CLICK EVENT FOR SAVE BUTTON
        $('#ET_ENTRY_btn_save').click(function()
        {
            $('.preloader').show();
            $("#ET_ENTRY_hidden_chkvalid").val("SAVE")//SET SAVE FUNCTION VALUE
            var ET_ENTRY_scriptname=$('#ET_ENTRY_tb_scriptname').val();
            if($('#ET_ENTRY_form_template')!="")
            {
                ET_ENTRY_checkscriptname()
            }
        });
        //SUCCESS FUNCTIOIN FOR SAVE
        function ET_ENTRY_save_resultsuccess()
        {
            $('.preloader').show();
            var formElement = document.getElementById("ET_ENTRY_form_template");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var ET_ENTRY_response=xmlhttp.responseText;
                    if(ET_ENTRY_response==1)
                    {
                        $("#ET_ENTRY_btn_save").attr("disabled","disabled");
                        //MESSAGE BOX FOR SAVED SUCCESS
                        show_msgbox("EMAIL ENTRY",ET_ENTRY_errormsg[1],"success",false)
//                        show_msgbox("EMAIL TEMPLATE ENTRY",ET_ENTRY_errormsg[1],150,500,false)
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMAIL TEMPLATE ENTRY",msgcontent:ET_ENTRY_errormsg[1],position:{top:150,left:500}}});
                        $("#ET_ENTRY_hidden_chkvalid").val("");
                        ET_ENTRY_email_template_rset();
                    }
                    else
                    {
                        //MESSAGE BOX FOR NOT SAVED
                        show_msgbox("EMAIL ENTRY",ET_ENTRY_errormsg[0],"error",false)
//                        show_msgbox("EMAIL TEMPLATE ENTRY",ET_ENTRY_errormsg[0],150,500,false)
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMAIL TEMPLATE ENTRY",msgcontent:ET_ENTRY_errormsg[0],position:{top:150,left:500}}});
                    }
                    $('.preloader').hide();
                }
            }
            var choice="ET_ENTRY_insert"
            xmlhttp.open("POST","DB_EMAIL_ENTRY.php?option="+choice,true);
            xmlhttp.send(new FormData(formElement));
        }
//        //CLICK EVENT FUCNTION FOR RESET
        $('#ET_ENTRY_btn_reset').click(function()
        {
            ET_ENTRY_email_template_rset()
        });
        //CLEAR ALL FIELDS
        function ET_ENTRY_email_template_rset()
        {
            $("#ET_ENTRY_form_template")[0].reset();
            $("#ET_ENTRY_tb_scriptname").removeClass('invalid');
            $('#ET_ENTRY_lbl_validid').hide();
            $("#ET_ENTRY_btn_save").attr("disabled", "disabled");
            $('#ET_ENTRY_tb_scriptname').prop("size","20");
            $('#ET_ENTRY_ta_subject').css('height', 176);
            $('#ET_ENTRY_ta_body').css('height', 176);
        }
    });
    <!--SCRIPT TAG END-->
</script>
<!--BODY TAG START-->
<body>
<div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
<div class="container">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h2 class="panel-title">EMAIL ENTRY</h2>
        </div>
        <div class="panel-body">
            <form id="ET_ENTRY_form_template" name="ET_ENTRY_form_template" class="form-horizontal" role="form">
                <div class="form-group">
                    <label name="ET_ENTRY_lbl_scriptname" id="ET_ENTRY_lbl_scriptname" class="control-label col-sm-3">EMAIL TEMPLATE NAME<em>*</em></label>
                    <div class="col-sm-4">
                        <input  type="text" name="ET_ENTRY_tb_scriptname" id="ET_ENTRY_tb_scriptname" placeholder="Email Template Name" class="autosize form-control" >
                        <label id="ET_ENTRY_lbl_validid" name="ET_ENTRY_lbl_validid" class="errormsg" disabled=""></label>
                    </div>
                </div>
                <div class="form-group">
                    <label name="ET_ENTRY_lbl_subject" id="ET_ENTRY_lbl_subject" class="control-label col-sm-3">SUBJECT<em>*</em></label>
                    <div class="col-sm-5">
                        <textarea class="form-control maxlength" rows="8" title="Email Template Subject" name="ET_ENTRY_ta_subject" id="ET_ENTRY_ta_subject" placeholder="Subject"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label name="ET_ENTRY_lbl_body" id="ET_ENTRY_lbl_body" class="control-label col-sm-3">BODY<em>*</em></label>
                    <div class="col-sm-5">
                        <textarea class="form-control maxlength" rows="8" title="Email Template Body" name="ET_ENTRY_ta_body" id="ET_ENTRY_ta_body" placeholder="Body"></textarea>
                    </div>
                </div>
                <div class="col-lg-offset-10">
                    <input type="button" class="btn  btn-info" name="ET_ENTRY_btn_save" id="ET_ENTRY_btn_save" value="SAVE" disabled>
                    <input type="button" class="btn  btn-info" name="ET_ENTRY_btn_reset" id="ET_ENTRY_btn_reset" value="RESET">
                    <input type=hidden id="ET_ENTRY_hidden_chkvalid">
                </div>
            </form>
        </div>
    </div>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->