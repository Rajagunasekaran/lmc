<!--*********************************************GLOBAL DECLARATION******************************************-->
<!--*********************************************************************************************************//-->
<!--//*******************************************FILE DESCRIPTION*********************************************//
//****************************************CONFIGURATION SEARCH/UPDATE/DELETE*************************************************//
//DONE BY:LALITHA
//VER 0.01-SD:06/01/2015 ED:06/01/2015,TRACKER NO:74,IMPLEMENTED PRELOADER POSITION,CHANGED LOGIN ID INTO EMPLOYEE NAME
//*********************************************************************************************************//
<?PHP
include "NEW_MENU.php"
?>
<!--SCRIPT TAG START-->
<script>
//DOCUMENT READY FUNCTION START
$(document).ready(function(){
//    $('.preloader').show();
    var pre_tds;
    var CONFIG_SRCH_UPD_errmsg=[];
    var CONFIG_SRCH_UPD_mod_opt='<option>SELECT</option>';
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $('.preloader', window.parent.document).hide();
            var CONFIG_SRCH_UPD_values=JSON.parse(xmlhttp.responseText);
            CONFIG_SRCH_UPD_errmsg=CONFIG_SRCH_UPD_values[0];
            var CONFIG_SRCH_UPD_typ_opt='<option value="SELECT">SELECT</option>';
            for (var i=0;i<CONFIG_SRCH_UPD_values[1].length;i++) {
                CONFIG_SRCH_UPD_mod_opt += '<option value="' + CONFIG_SRCH_UPD_values[1][i][0] + '">' + CONFIG_SRCH_UPD_values[1][i][1] + '</option>';
            }
            $('#CONFIG_SRCH_UPD_lb_module').html(CONFIG_SRCH_UPD_mod_opt);
        }}
    var OPTION="CONFIG_SRCH_UPD_load_mod";
    xmlhttp.open("GET","DB_CONFIGURATION_SEARCH_UPDATE_DELETE.php?option="+OPTION,true);
    xmlhttp.send(new FormData());
    //CHANGE EVENT FOR MODULE CONFIG
    $(document).on('change','#CONFIG_SRCH_UPD_lb_module',function(){
        $('#CONFIG_SRCH_UPD_err_flex').hide();
        $('#CONFIG_SRCH_UPD_tr_data,#CONFIG_SRCH_UPD_tr_btn,#CONFIG_SRCH_UPD_tr_type,section').empty();
        $('#CONFIG_SRCH_UPD_div_errMod').hide();
        var CONFIG_SRCH_UPD_typ_opt='<option value="SELECT">SELECT</option>';
        var formElement = document.getElementById("CONFIG_SRCH_UPD_form");
        if($(this).val()!='SELECT'){
            $('.preloader').show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var CONFIG_SRCH_UPD_values=JSON.parse(xmlhttp.responseText);
                    if(CONFIG_SRCH_UPD_values.length==0){
                        $('#CONFIG_SRCH_UPD_div_errMod').show();
                        $('#CONFIG_SRCH_UPD_div_errMod').text(CONFIG_SRCH_UPD_errmsg[5].replace('[TYPE]',$("#CONFIG_SRCH_UPD_lb_module option:selected").text()));
                    }else{
                        $('#CONFIG_SRCH_UPD_div_errMod').hide();
                        for (var i=0;i<CONFIG_SRCH_UPD_values.length;i++) {
                            CONFIG_SRCH_UPD_typ_opt += '<option value="' + CONFIG_SRCH_UPD_values[i][0] + '">' + CONFIG_SRCH_UPD_values[i][1] + '</option>';
                        }
                        $('#CONFIG_SRCH_UPD_tr_type').append('<div class="form-group row" ><label  class="col-sm-2 control-label">TYPE<em>*</em></label><div class="col-sm-10"><select id="CONFIG_SRCH_UPD_lb_type" name="CONFIG_SRCH_UPD_lb_type"  class="form-control" style="width:305px"></select></div></div>')
                        $('#CONFIG_SRCH_UPD_lb_type').html(CONFIG_SRCH_UPD_typ_opt);
                    }
                }
            }
            var OPTION="CONFIG_SRCH_UPD_load_type";
            var CONFIG_SRCH_UPD_data=$(this).val();
            xmlhttp.open("GET","DB_CONFIGURATION_SEARCH_UPDATE_DELETE.php?option="+OPTION+"&module="+CONFIG_SRCH_UPD_data,true);
            xmlhttp.send(new FormData());
        }
    });
//FUNCTION FOR FETCHING DATA FOR FLEX TABLE
    function CONFIG_SRCH_UPD_fetch_configdata(){
        var formElement = document.getElementById("CONFIG_SRCH_UPD_form");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var CONFIG_SRCH_UPD_values=JSON.parse(xmlhttp.responseText);
                $('section').html(CONFIG_SRCH_UPD_values);
                var oTable= $('#CONFIG_SRCH_UPD_tble_config').DataTable( {
                    "aaSorting": [],
                    "pageLength": 10,
                    "sPaginationType":"full_numbers"
                });
                if(oTable.rows().data().length==0){
                    $('#CONFIG_SRCH_UPD_err_flex').text(CONFIG_SRCH_UPD_errmsg[6].replace('[TYPE]',$("#CONFIG_SRCH_UPD_lb_type option:selected").text())).show();
                    $('section').html('');
                    $('#CONFIG_SRCH_UPD_tble_config').hide();
                }
                else{
                    $('#CONFIG_SRCH_UPD_div_errmsg').removeClass('errormsg').addClass('srctitle');
                    $('#CONFIG_SRCH_UPD_div_errmsg').text(CONFIG_SRCH_UPD_errmsg[3].replace('[TYPE]',$("#CONFIG_SRCH_UPD_lb_type option:selected").text()));
                }
                if(CONFIG_flag_upd==1){
                    var errmsg=CONFIG_SRCH_UPD_errmsg[4].replace('[MODULE NAME]',$("#CONFIG_SRCH_UPD_lb_module option:selected").text());
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"CONFIGURATION ENTRY",msgcontent:errmsg,position:{top:150,left:530}}});}

            }}
        var OPTION="CONFIG_SRCH_UPD_load_data";
        xmlhttp.open("POST","DB_CONFIGURATION_SEARCH_UPDATE_DELETE.php?option="+OPTION,true);
        xmlhttp.send(new FormData(formElement));
    }
    //CHANGE EVENT FOR TYPE CONFIG
    $(document).on('change','#CONFIG_SRCH_UPD_lb_type',function(){
        CONFIG_flag_upd=0;
        $('section').html('');
        $('#CONFIG_SRCH_UPD_tble_config').hide();
        $('#CONFIG_SRCH_UPD_err_flex').hide();
        if($(this).val()!='SELECT'){
            $('.preloader').show();
            CONFIG_SRCH_UPD_fetch_configdata();
        }
    });
    $(document).on("keyup",'.upper',function() {
        if (this.value.match(/[^a-zA-Z0-9\-]/g)) {
            this.value = this.value.replace(/[^a-zA-Z0-9\-]/g, '');
        }
        $('#CONFIG_SRCH_UPD_tb_data').val($('#CONFIG_SRCH_UPD_tb_data').val().toUpperCase())
    });
//EDIT CLICK FUNCTION FOR UPDATE FORM
    $(document).on('click','.edit',function(){
// $(this).val('UPDATE').addClass('update').removeClass('edit');$(this).next().val('CANCEL').addClass('cancel').removeClass('delete');//after
        if($(this).hasClass( "deletion" )==true)
        {
            $(this).val('UPDATE').addClass('update').removeClass('edit');$(this).next().val('CANCEL').addClass('cancel').removeClass('delete');
        }
        else
        {
            $(this).val('UPDATE').addClass('update').removeClass('edit');$(this).next().val('CANCEL').addClass('cancl');
        }
        $(this).attr("disabled","disabled");
        $('.edit').attr("disabled","disabled");
        $('.cancel').attr("disabled","disabled");
        $('.cancl').attr("disabled","disabled");
        $('.delete').attr("disabled","disabled");
        $(this).next().removeAttr("disabled","disabled");
        var edittrid=$(this).parent().parent().attr('id');
        var tds = $('#'+edittrid).children('td');
        var td=$(tds[0]).attr('id');
        pre_tds = $(tds[0]).html();
        var tdstr = '';
        var final_data_length=($(tds[0]).html()).length;
        if(($('#CONFIG_SRCH_UPD_lb_module').val()=='3') && ($('#CONFIG_SRCH_UPD_lb_type').val()!='6'))
        {
            tdstr += "<input type='text' id='CONFIG_SRCH_UPD_tb_data' name='CONFIG_SRCH_data' class='autosize form-control'  maxlength='50'  value='"+$(tds[0]).html()+"' >";
        }
       else if(($('#CONFIG_SRCH_UPD_lb_module').val()=='3')&&($('#CONFIG_SRCH_UPD_lb_type').val()=='6'))
        {
            tdstr += "<input type='text' id='CONFIG_SRCH_UPD_tb_data' name='CONFIG_SRCH_data' class='reports form-control'  maxlength='50'  value='"+$(tds[0]).html()+"'>";
        }
        else if(($('#CONFIG_SRCH_UPD_lb_module').val()=='2') &&($('#CONFIG_SRCH_UPD_lb_type').val()=='15'))
        {
            tdstr += "<input type='text' id='CONFIG_SRCH_UPD_tb_data' name='CONFIG_SRCH_data' class='form-control upper'  maxlength='25'  value='"+$(tds[0]).html()+"'>";
        }
        else if($('#CONFIG_SRCH_UPD_lb_module').val()=='2')
        {
            tdstr += "<input type='text' id='CONFIG_SRCH_UPD_tb_data' name='CONFIG_SRCH_data' class='reports form-control'  maxlength='50'  value='"+$(tds[0]).html()+"'>";
        }
        else
        {
            tdstr += "<input type='text' id='CONFIG_SRCH_UPD_tb_data' name='CONFIG_SRCH_data' class='autosize form-control'    value='"+$(tds[0]).html()+"'>";
        }
        $('#'+td).html(tdstr);
        $('#CONFIG_SRCH_UPD_tb_data').attr("size",final_data_length+3);
        $(".alphabets").doValidation({rule:'alphabets',prop:{whitespace:true,uppercase:true,autosize:true}});
        $(".autosize").doValidation({rule:'general',prop:{autosize:true,whitespace:true,uppercase:false}});
        $(".reports").doValidation({rule:'alphanumeric',prop:{autosize:true,whitespace:true,uppercase:true}});
    });
    //CLICK EVENT FOR CANCEL BUTTON
    $(document).on("click",'.cancel', function (){
        if(pre_tds!='')
        {
            $(this).val('DELETE').addClass('delete');
            $(this).prev().val('EDIT').addClass('edit').removeClass('update');
            $('.edit').removeAttr("disabled");//after
            $('.cancel').removeAttr("disabled","disabled");
            $('.cancl').removeAttr("disabled","disabled");
            $('.delete').removeAttr("disabled","disabled");//after
            var edittrid = $(this).parent().parent().attr('id');
            var tds = $('#'+edittrid).children('td');
            var td=$(tds[0]).attr('id');
            $('#'+td).html(pre_tds);
        }
        pre_tds='';
    });
    //CLICK EVENT FOR DB CANCEL BUTTON
    $(document).on("click",'.cancl', function (){
        if(pre_tds!='')
        {
            $(this).prev().val('EDIT').addClass('edit').removeClass('update');
            $('.edit').removeAttr("disabled");//after
            $('.cancel').removeAttr("disabled","disabled");
            $('.cancl').removeAttr("disabled","disabled");
            $('.delete').removeAttr("disabled","disabled");//after
            var edittrid = $(this).parent().parent().attr('id');
            var tds = $('#'+edittrid).children('td');
            var td=$(tds[0]).attr('id');
            $('#'+td).html(pre_tds);
        }
        pre_tds='';
    });
    var CONFIG_flag_upd;
    //CLICK EVENT FOR BUTTON UPDATE
    $(document).on('click','.update',function(){
        CONFIG_flag_upd=0;
        var config_type=$('#CONFIG_SRCH_UPD_lb_type').val();
        var CONFIG_id=$(this).parent().parent().attr('id');
        $('.preloader').show();
        var formElement = document.getElementById("CONFIG_SRCH_UPD_form");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var msg_alert=JSON.parse(xmlhttp.responseText)
                if(msg_alert==1 )
                {
                    $('.preloader').hide();
                    var msg=CONFIG_SRCH_UPD_errmsg[4].toString().replace("[MODULE NAME]",$("#CONFIG_SRCH_UPD_lb_type option:selected").text())
                    show_msgbox("CONFIGURATION SEARCH/UPDATE/DELETE",msg,"success",false)
                    CONFIG_SRCH_UPD_fetch_configdata();
                }
                else
                {
                    $('.preloader').hide();
                    show_msgbox("CONFIGURATION SEARCH/UPDATE/DELETE",CONFIG_SRCH_UPD_errmsg[0],"error",false)
                }
            }}
        var OPTION="CONFIG_SRCH_UPD_save";
        xmlhttp.open("POST","DB_CONFIGURATION_SEARCH_UPDATE_DELETE.php?option="+OPTION+"&CONFIG_SRCH_UPD_id="+CONFIG_id+"&CONFIG_SRCH_UPD_tb_data="+$('#CONFIG_SRCH_UPD_tb_data').val(),true);
        xmlhttp.send(new FormData(formElement));
    });
    //CHANGE FUNCTION FOR DATA
    $(document).on('blur','#CONFIG_SRCH_UPD_tb_data',function(){
        var txt_area=$(this).val().trim();
        var formElement = document.getElementById("CONFIG_SRCH_UPD_form");
        if((txt_area!='') && txt_area!=pre_tds){
            $('.preloader').show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    if(xmlhttp.responseText==1){
                        $(".update").attr("disabled","disabled");
                        show_msgbox("CONFIGURATION SEARCH/UPDATE/DELETE",CONFIG_SRCH_UPD_errmsg[8].replace('[TYPE]',$("#CONFIG_SRCH_UPD_lb_type option:selected").text()),"error",false)
                        $(this).addClass('invalid');}
                    else{
                        $(".update").removeAttr("disabled","disabled");
                        $(this).removeClass('invalid');
                    }
                }
            }
            var OPTION="CONFIG_SRCH_UPD_check_data";
            var CONFIG_SRCH_UPD_data=$(this).val();
            xmlhttp.open("POST","DB_CONFIGURATION_SEARCH_UPDATE_DELETE.php?option="+OPTION+"&CONFIG_SRCH_UPD_tb_data="+txt_area,true);
            xmlhttp.send(new FormData(formElement));
        }
        else{

            $(".update").attr("disabled","disabled");
        }
    });
    //CLICK EVENT FOR BUTTON RESET
    $(document).on('click','#CONFIG_SRCH_UPD_btn_reset',function(){
        $('#CONFIG_SRCH_UPD_tr_type,#CONFIG_SRCH_UPD_tr_data,#CONFIG_SRCH_UPD_tr_btn').empty();
        $('#CONFIG_SRCH_UPD_lb_module').val('SELECT');
    });
});
//DOCUMENT READY FUNCTION END
</script>
<!--SCRIPT TAG END-->
</head>
<!--HEAD TAG END-->
<!--BODY TAG START-->
<body>
<div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
<div class="container">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h2 class="panel-title">CONFIGURATION SEARCH/UPDATE</h2>
        </div>
        <div class="panel-body">
            <form id="CONFIG_SRCH_UPD_form" name="CONFIG_SRCH_UPD_form" class="form-horizontal" role="form">
                <div class="form-group row" >
                    <label  class="col-sm-2 control-label">MODULE NAME<em>*</em></label>
                    <div class="col-sm-10">
                        <select name="CONFIG_SRCH_UPD_lb_module" id="CONFIG_SRCH_UPD_lb_module" class="form-control" style="width:305px">
                            <option>SELECT</option>
                        </select>
                    </div>
                </div>
                <div id="CONFIG_SRCH_UPD_tr_type"></div>
                <div class="table-responsive" >
                <section></section>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->