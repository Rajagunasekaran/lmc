<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMAIL TEMPLATE SEARCH/UPDATE*********************************************//
//DONE BY:RAJA
//VER 0.02-IMPLEMENTED INLINE EDITOR, SD:22/06/2015 ED:22/06/2015
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:22/02/2015 ED:22/02/2015,TRACKER NO:1
//*********************************************************************************************************//
<?php
include "../../SUBFOLDERMENU.php";
?>
<!--SCRIPT TAG START-->
<script>
//GLOBAL DECLARATION
var ET_SRC_UPD_DEL_result_array=[];
var ET_SRC_UPD_DEL_name;
var ET_SRC_UPD_DEL_sucsval=0,ET_SRC_UPD_DEL_emailsubject="",ET_SRC_UPD_DEL_emailbody="";
var ET_SRC_UPD_DEL_errorMsg_array=[];
var ET_SRC_UPD_DEL_emailtemplate_list=[];
//READY FUNCTION START
$(document).ready(function(){
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $('.preloader').hide();
            $('#RPT').hide();
            $('#AE').hide();
            var value_array=JSON.parse(xmlhttp.responseText);
            ET_SRC_UPD_DEL_emailtemplate_list=value_array[0];
            ET_SRC_UPD_DEL_errorMsg_array=value_array[1];
            if(ET_SRC_UPD_DEL_emailtemplate_list.length!=0){
                var ET_SRC_UPD_DEL_emltemp_list='<option>SELECT</option>';
                for (var i=0;i<ET_SRC_UPD_DEL_emailtemplate_list.length;i++) {
                    ET_SRC_UPD_DEL_emltemp_list += '<option value="' + ET_SRC_UPD_DEL_emailtemplate_list[i][1] + '">' + ET_SRC_UPD_DEL_emailtemplate_list[i][0] + '</option>';
                }
                $('#ET_SRC_UPD_DEL_lb_scriptname').html(ET_SRC_UPD_DEL_emltemp_list);
                $('#ET_SRC_UPD_DEL_lbl_scriptname').show();
                $('#ET_SRC_UPD_DEL_lb_scriptname').show();
            }
            else
            {
                $('#ET_SRC_UPD_DEL_div_headernodata').text("NO DATA AVAILABLE").show();
                $('#ET_SRC_UPD_DEL_lbl_scriptname').hide();
                $('#ET_SRC_UPD_DEL_lb_scriptname').hide();
            }
        }
    }
    var option="INITIAL_DATAS";
    xmlhttp.open("GET","DB_EMAIL_SEARCH_UPDATE.php?option="+option);
    xmlhttp.send();
    //KEY PRESS FUNCTION END
    var ET_SRC_UPD_DEL_emailsubject;
    var ET_SRC_UPD_DEL_emailbody;
    var ET_SRC_UPD_DEL_userstamp;
    var ET_SRC_UPD_DEL_timestmp;
    var id;
    var values_array=[];
    //CHANGE FUNCTION FOR SCRIPTNAME
    $('#ET_SRC_UPD_DEL_lb_scriptname').change(function()
    {
        $('.preloader').show();
        $('#ET_SRC_UPD_DEL_div_headernodata').hide();
        ET_SRC_UPD_DEL_name=$('#ET_SRC_UPD_DEL_lb_scriptname').find('option:selected').text();
        $('#ET_SRC_UPD_DEL_div_header').hide();
        var ET_SRC_UPD_DEL_scriptname = $("#ET_SRC_UPD_DEL_lb_scriptname").val();
        if(ET_SRC_UPD_DEL_scriptname=='SELECT')
        {
            $('.preloader').hide();
            $('#ET_SRC_UPD_DEL_tble_htmltable').hide();
            $('#ET_SRC_UPD_DEL_div_header').hide();
            $('#ET_SRC_UPD_DEL_div_headernodata').hide();
        }
        else
        {
            $('#ET_SRC_UPD_DEL_div_table').show();
            $('#ET_SRC_UPD_DEL_tble_htmltable').hide();
            ET_SRC_UPD_DEL_srch_result();
        }
    });
    //RESPONSE FUNCTION FOR FLEXTABLE SHOWING
    function ET_SRC_UPD_DEL_srch_result(){
        var ET_SRC_UPD_DEL_table_header='';
        var formElement = document.getElementById("ET_SRC_UPD_DEL_form_emailtemplate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                ET_SRC_UPD_DEL_table_header='<table id="ET_tble_htmltable" border="1" cellspacing="0" class="srcresult"><thead><th style="width:400px;text-align: center" nowrap>EMAIL SUBJECT</th><th style="width:460px;text-align: center" nowrap>EMAIL BODY</th><th style="width:120px;text-align: center" nowrap>USERSTAMP</th><th style="width:120px;text-align: center" nowrap>TIMESTAMP</th></thead><tbody>';
                values_array=JSON.parse(xmlhttp.responseText);
                if(values_array.length!=0)
                {
                    var ET_SRC_UPD_DEL_errmsg =ET_SRC_UPD_DEL_errorMsg_array[3].replace('[SCRIPT]',ET_SRC_UPD_DEL_name);
                    $('#ET_SRC_UPD_DEL_div_header').text(ET_SRC_UPD_DEL_errmsg).show();
                    $('#ET_SRC_UPD_DEL_div_flexdata_result').show();
                    for(var j=0;j<values_array.length;j++){
                        ET_SRC_UPD_DEL_emailsubject=values_array[j].ET_SRC_UPD_DEL_subject;
                        ET_SRC_UPD_DEL_emailsubject=unescapeHTML(ET_SRC_UPD_DEL_emailsubject);
                        ET_SRC_UPD_DEL_emailbody=values_array[j].ET_SRC_UPD_DEL_body;
                        ET_SRC_UPD_DEL_emailbody=unescapeHTML(ET_SRC_UPD_DEL_emailbody);
                        ET_SRC_UPD_DEL_userstamp=values_array[j].ET_SRC_UPD_DEL_userstamp;
                        ET_SRC_UPD_DEL_timestmp=values_array[j].ET_SRC_UPD_DEL_timestamp;
                        id=values_array[j].id;
                        ET_SRC_UPD_DEL_table_header+='<tr><td id="subject_'+id+'" class="ET_SRC_UPD_DEL_radio">'+ET_SRC_UPD_DEL_emailsubject+'</td><td id="body_'+id+'" class="ET_SRC_UPD_DEL_radio">'+ET_SRC_UPD_DEL_emailbody+'</td><td>'+ET_SRC_UPD_DEL_userstamp+'</td><td style=";text-align: center" nowrap>'+ET_SRC_UPD_DEL_timestmp+'</td></tr></tdody></table>';
                        $('section').empty();
                        $('section').append(ET_SRC_UPD_DEL_table_header);
                        $('#ET_SRC_UPD_DEL_tble_htmltable').show();
                    }
                    $('.preloader').hide();
                }
                else
                {
                    $('#ET_SRC_UPD_DEL_div_header').hide();
                    $('#ET_SRC_UPD_DEL_div_table').hide();
                    $('#ET_SRC_UPD_DEL_div_headernodata').text(ET_SRC_UPD_DEL_errorMsg_array[1]).show();
                    $('#ET_SRC_UPD_DEL_tble_htmltable').hide();
                    $('.preloader').hide();
                }
            }
        }
        var choice="EMAIL_TEMPLATE_DETAILS";
        xmlhttp.open("POST","DB_EMAIL_SEARCH_UPDATE.php?&option="+choice,true);
        xmlhttp.send(new FormData(formElement));
    }
    //RADIO CLICK FUNCTION
    var previous_id;var tdvalue;var primcid;
    var emailsub=''; var emailbody='';
    $(document).on('click','.ET_SRC_UPD_DEL_radio',function(){
        $('#ET_tble_htmltable tr').each(function(){
            var $tds=($(this).find('td'));
            emailsub=$tds.eq(0).text();
            emailbody=$tds.eq(1).text();
        });
        if(previous_id!=undefined){
            $('#'+previous_id).replaceWith("<td class='ET_SRC_UPD_DEL_radio' id='"+previous_id+"' >"+tdvalue+"</td>");
        }
        var cid = $(this).attr('id');
        previous_id=cid;
        var splittedcid=cid.split('_');
        var rowcid=splittedcid[0];
        primcid=splittedcid[1];
        tdvalue=$(this).text();
        if(rowcid=='subject')//subject
        {
            $('#'+cid).replaceWith('<td id="'+previous_id+'"><textarea class="form-control validation uppercase maxlength class_emailupdate" name="ET_SRC_UPD_DEL_ta_updsubject" title="Email Template Subject" id="ET_SRC_UPD_DEL_ta_updsubject" placeholder="Subject">'+tdvalue+'</textarea>');
        }
        else if(rowcid=='body')//body
        {
            $('#'+cid).replaceWith('<td id="'+previous_id+'"><textarea class="form-control validation uppercase maxlength class_emailupdate" name="ET_SRC_UPD_DEL_ta_updbody" title="Email Template Body" id="ET_SRC_UPD_DEL_ta_updbody" placeholder="Body">'+tdvalue+'</textarea>');
        }
        $("html, body").animate({ scrollTop: $(document).height() }, "fast");
        $('.uppercase').doValidation({rule:'general',prop:{uppercase:true}});
        $('textarea').autogrow({onInitialize: true});
        //KEY PRESS FUNCTION START
        var ET_SRC_UPD_DEL_max=3000;
        $('.maxlength').keypress(function(e)
        {
            if(e.which < 0x20)
            {
                return;
            }
            if(this.value.length==ET_SRC_UPD_DEL_max)
            {
                e.preventDefault();
            }
            else if(this.value.length > ET_SRC_UPD_DEL_max)
            {
                this.value=this.value.substring(0,ET_SRC_UPD_DEL_max);
            }
        });
    });
    //FUNCTION FOR DECODE THE SPECIAL CHARCTERS
    function unescapeHTML(p_string)
    {
        if ((typeof p_string === "string") && (new RegExp(/&amp;|&lt;|&gt;|&quot;|&#39;/).test(p_string)))
        {
            return p_string.replace(/&amp;/g, "&").replace(/&lt/g, "<").replace(/&gt;/g, ">").replace(/&quot;/g, "\"").replace(/&#39;/g, "'");
        }
        return p_string;
    }
    //CLICK EVENT FUCNTION FOR UPDATE
    $(document).on('blur','.class_emailupdate',function(){
        var ET_SRC_UPD_DEL_scriptname=$('#ET_SRC_UPD_DEL_lb_scriptname').find('option:selected').text();
        if($('#subject_'+primcid).hasClass("ET_SRC_UPD_DEL_radio")==true){
            var ET_SRC_UPD_DEL_datasubject=$('#subject_'+primcid).text();
        }
        else{
            var ET_SRC_UPD_DEL_datasubject=$("#ET_SRC_UPD_DEL_ta_updsubject").val();
        }
        if($('#body_'+primcid).hasClass("ET_SRC_UPD_DEL_radio")==true){
            var ET_SRC_UPD_DEL_databody=$('#body_'+primcid).text();
        }
        else{
            var ET_SRC_UPD_DEL_databody=$("#ET_SRC_UPD_DEL_ta_updbody").val();
        }
        if((($(this).attr('id')=='ET_SRC_UPD_DEL_ta_updsubject')&&($(this).val()!='')&&(($(this).val()).trim()!=emailsub)) || (($(this).attr('id')=='ET_SRC_UPD_DEL_ta_updbody')&&($(this).val()!='')&&(($(this).val()).trim()!=emailbody))){
            $('.preloader').show();
            var choice = "EMAIL_TEMPLATE_UPDATE";
            $.ajax({
                type: "POST",
                url: "DB_EMAIL_SEARCH_UPDATE.php",
                data: {'option':choice,'ET_SRC_UPD_DEL_rd_flxtbl': primcid,'ET_SRC_UPD_DEL_ta_updsubject':ET_SRC_UPD_DEL_datasubject,'ET_SRC_UPD_DEL_ta_updbody':ET_SRC_UPD_DEL_databody},
                success: function(msg){
                    var msg_alert=(msg);
                    if (msg_alert == 1) {
                        var ET_SRC_UPD_DEL_scriptname = $('#ET_SRC_UPD_DEL_lb_scriptname').val();
                        ET_SRC_UPD_DEL_srch_result();
                        previous_id = undefined;
                        show_msgbox("EMAIL SEARCH/UPDATE", ET_SRC_UPD_DEL_errorMsg_array[2], "success", false);
                    }
                    else {
                        $('.preloader').hide();
                        show_msgbox("EMAIL SEARCH/UPDATE", ET_SRC_UPD_DEL_errorMsg_array[0], "error", false);
                    }
                }
            });
        }
    });
});
<!--SCRIPT TAG END-->
</script>
<!--BODY TAG START-->
<body>
<div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
<div class="container">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h2 class="panel-title">EMAIL SEARCH/UPDATE</h2>
        </div>
        <form id="ET_SRC_UPD_DEL_form_emailtemplate" name="ET_SRC_UPD_DEL_form_emailtemplate" class="form-horizontal" role="form">
            <div class="panel-body">
                <div class="form-group row" >
                    <label name="ET_SRC_UPD_DEL_lbl_scriptname" id="ET_SRC_UPD_DEL_lbl_scriptname" class="col-sm-3 control-label">EMAIL TEMPLATE NAME<em>*</em></label>
                    <div class="col-sm-4">
                        <select name="ET_SRC_UPD_DEL_lb_scriptname" id="ET_SRC_UPD_DEL_lb_scriptname" class="form-control">
                            <option>SELECT</option>
                        </select>
                    </div>
                </div>
                <div class="srctitle" name="ET_SRC_UPD_DEL_div_header" id="ET_SRC_UPD_DEL_div_header"></div><br>
                <div class="errormsg" name="ET_SRC_UPD_DEL_div_headernodata" id="ET_SRC_UPD_DEL_div_headernodata"></div>
                <div class="table-responsive" id="ET_SRC_UPD_DEL_tble_htmltable">
                    <section>
                    </section>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->