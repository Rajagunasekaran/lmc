<!--*********************************************GLOBAL DECLARATION******************************************-->
<!--*********************************************************************************************************//-->
<!--//*******************************************FILE DESCRIPTION*********************************************//
//****************************************CONFIGURATION ENTRY*************************************************//
//DONE BY:LALITHA
//VER 0.01-SD:06/01/2015 ED:06/01/2015,TRACKER NO:1
//*********************************************************************************************************//
<?PHP
include "NEW_MENU.php"
?>

<!--SCRIPT TAG START-->
<script>
    //DOCUMENT READY FUNCTION START
    $(document).ready(function(){
//        $('.preloader').show();
        var CONFIG_ENTRY_errmsg=[];
        $(document).on("keyup",'.upper',function() {
            if (this.value.match(/[^a-zA-Z0-9\-]/g)) {
                this.value = this.value.replace(/[^a-zA-Z0-9\-]/g, '');
            }
            $('#CONFIG_ENTRY_tb_data').val($('#CONFIG_ENTRY_tb_data').val().toUpperCase())
        });
        //CHANGE EVENT FOR MODULE CONFIG

            var CONFIG_ENTRY_typ_opt='<option value="SELECT">SELECT</option>';
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $('.preloader').hide();
                        var values=JSON.parse(xmlhttp.responseText);
                        var CONFIG_ENTRY_values=values[0];
                        CONFIG_ENTRY_errmsg=values[1];
//                        if(CONFIG_ENTRY_values.length==0){
//                            $('#CONFIG_ENTRY_div_errMod').show();
//                            $('#CONFIG_ENTRY_div_errMod').text(CONFIG_ENTRY_errmsg[1].replace('[TYPE]',$("#CONFIG_ENTRY_lb_module option:selected").text()));
//                        }else{
//                            $('#CONFIG_ENTRY_div_errMod').hide();
                            for (var i=0;i<CONFIG_ENTRY_values.length;i++) {
                                CONFIG_ENTRY_typ_opt += '<option value="' + CONFIG_ENTRY_values[i][0] + '">' + CONFIG_ENTRY_values[i][1] + '</option>';
                            }
                            $('#CONFIG_ENTRY_tr_type').append('<label class="col-sm-2 control-label">SELECT SETTINGS<em>*</em></label> <div class="col-sm-3"><select id="CONFIG_ENTRY_lb_type" name="CONFIG_ENTRY_lb_type" class="form-control"></select></div>');
                            $('#CONFIG_ENTRY_lb_type').html(CONFIG_ENTRY_typ_opt);
                        }
                    }
                var OPTION="CONFIG_ENTRY_load_type";
                xmlhttp.open("GET","DB_CONFIGURATION_ENTRY.php?option="+OPTION,true);
                xmlhttp.send();
//            }
//            else
//            {
//                $('#CONFIG_ENTRY_div_errMod').hide();
//            }
//        });

        //CHANGE EVENT FOR TYPE CONFIG
        $(document).on('change','#CONFIG_ENTRY_lb_type',function(){
            $('#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn').empty();
            if($('#CONFIG_ENTRY_lb_type').val()!='SELECT')
            {
                if(($('#CONFIG_ENTRY_lb_type').val()=='16') || ($('#CONFIG_ENTRY_lb_type').val()=='14'))
                {
                    $('#CONFIG_ENTRY_tr_data').append('<label class="control-label col-sm-2" id="datalabel">PLEASE KEY IN THE NEW ENTRY<em>*</em> </label> <div class="col-sm-3"><input type="text" id="CONFIG_ENTRY_tb_data" class="reports form-control" name="CONFIG_ENTRY_tb_data" maxlength="50" placeholder="Data"></div><div id="CONFIG_ENTRY_div_errmsg" hidden class="errormsg">');
                }
                else
                {
                    $('#CONFIG_ENTRY_tr_data').append('<label class="control-label col-sm-2" id="datalabel">PLEASE KEY IN THE NEW ENTRY<em>*</em> </label> <div class="col-sm-3"><input type="text" id="CONFIG_ENTRY_tb_data" name="CONFIG_ENTRY_tb_data" class="form-control upper" title="Enter Data According to Type"  maxlength="25" placeholder="Data"></div><div id="CONFIG_ENTRY_div_errmsg" hidden class="errormsg">');
                }
                $('#CONFIG_ENTRY_tr_btn').append('<div class="col-sm-3 col-lg-offset-10" id="datalabel"><input type="button" id="CONFIG_ENTRY_btn_save" class="btn  btn-info" value="SAVE" disabled>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="CONFIG_ENTRY_btn_reset" class="btn btn-info" value="RESET"></div>');
//                $("#CONFIG_ENTRY_tb_data").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:true,autosize:true}});
//                $(".alphabets").doValidation({rule:'alphabets',prop:{whitespace:true,uppercase:true,autosize:true}});
                $(".reports").doValidation({rule:'alphanumeric',prop:{autosize:true,whitespace:true,uppercase:true}});
            }
            else
            {
                $('#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn').empty();
            }
            if($('#CONFIG_ENTRY_lb_type').val()=='16')
            {
                $('#CONFIG_ENTRY_tb_data').attr('title', 'DOCUMENT CATEGORY');
                $('#CONFIG_ENTRY_tb_data').attr('placeholder', '[DOCUMENT CATEGORY]');
            }
            else if($('#CONFIG_ENTRY_lb_type').val()=='14')
            {
                $('#CONFIG_ENTRY_tb_data').attr('title', 'MEETING TOPIC');
                $('#CONFIG_ENTRY_tb_data').attr('placeholder', '[MEETING TOPIC]');
            }
            else
            {
                $('#CONFIG_ENTRY_tb_data').attr('title', 'TEAM NAME');
                $('#CONFIG_ENTRY_tb_data').attr('placeholder', '[TEAM NAME]');
            }
        });

        $('#CONFIG_ENTRY_tb_data').keypress(function(event){
            $('#CONFIG_ENTRY_btn_save').removeAttr('disabled','disabled');
            if(event.keyCode == 13){
                $('#CONFIG_ENTRY_btn_save').click();
            }
        });
        //CLICK EVENT FOR BUTTON
        $(document).on('click','#CONFIG_ENTRY_btn_save',function(){
            $('.preloader').show();
            var formElement = document.getElementById("CONFIG_ENTRY_form");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var CONFIG_ENTRY_msg_alert=xmlhttp.responseText;
                    if(CONFIG_ENTRY_msg_alert==1)
                    {
                        var errmsg=CONFIG_ENTRY_errmsg[2].replace('[MODULE NAME]',$("#CONFIG_ENTRY_lb_type option:selected").text());
                        show_msgbox("CONFIGURATION ENTRY",errmsg,"success",false)
                    }
                    else if(CONFIG_ENTRY_msg_alert==0)
                    {
                        show_msgbox("CONFIGURATION ENTRY",CONFIG_ENTRY_errmsg[0],"error",false)
                    }
                    if(CONFIG_ENTRY_msg_alert==2){
                        $("#CONFIG_ENTRY_btn_save").attr("disabled","disabled");
                        $("#CONFIG_ENTRY_div_errmsg").text(CONFIG_ENTRY_errmsg[3].replace('[TYPE]',$("#CONFIG_ENTRY_lb_type option:selected").text())).show();
                    }
                    else{
                        $('#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn').empty();
                    }

                    $('#CONFIG_ENTRY_tr_type').show();
                    $('#CONFIG_ENTRY_lb_type').prop('selectedIndex',0);
                }}
            var OPTION="CONFIG_ENTRY_save";
            xmlhttp.open("POST","DB_CONFIGURATION_ENTRY.php?option="+OPTION,true);
            xmlhttp.send(new FormData(formElement));
        });
        //CHANGE FUNCTION FOR DATA
        $(document).on('change blur','#CONFIG_ENTRY_tb_data',function(){
            var formElement = document.getElementById("CONFIG_ENTRY_form");
            if($(this).val()!=''){
                $('.preloader').show();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $('.preloader').hide();
                        if(xmlhttp.responseText==1){
                            $("#CONFIG_ENTRY_div_errmsg").show();
                            $("#CONFIG_ENTRY_div_errmsg").text(CONFIG_ENTRY_errmsg[3].replace('[TYPE]',$("#CONFIG_ENTRY_lb_type option:selected").text()));}
                        else
                            $("#CONFIG_ENTRY_div_errmsg").text('');
                        if(xmlhttp.responseText==0)
                            $("#CONFIG_ENTRY_btn_save").removeAttr("disabled","disabled");
                        else
                            $("#CONFIG_ENTRY_btn_save").attr("disabled","disabled");

                    }}
                var OPTION="CONFIG_ENTRY_check_data";
                var CONFIG_ENTRY_data=$(this).val();
                xmlhttp.open("POST","DB_CONFIGURATION_ENTRY.php?option="+OPTION,true);
                xmlhttp.send(new FormData(formElement));}
            else
            {
                $("#CONFIG_ENTRY_btn_save").attr("disabled","disabled");
            }
        });

        //CLICK EVENT FOR BUTTON RESET
        $(document).on('click','#CONFIG_ENTRY_btn_reset',function(){
            $('#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn').empty();
            $('#CONFIG_ENTRY_tr_type').show();
            $('#CONFIG_ENTRY_lb_type').prop('selectedIndex',0);
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
            <h2 class="panel-title">SETTINGS ENTRY</h2>
        </div>
        <div class="panel-body">
            <form id="CONFIG_ENTRY_form" name="CONFIG_ENTRY_form" class="form-horizontal" role="form">
<!--                <div class="form-group row">-->
<!--                    <label  class="col-sm-2">LIST BOX ITEM<em>*</em></label>-->
<!--                    <div class="col-sm-3">-->
<!--                        <select name="CONFIG_ENTRY_lb_module" id="CONFIG_ENTRY_lb_module" class="form-control" style="padding-bottom: 10px;">-->
<!--                        </select><label id="CONFIG_ENTRY_div_errMod" hidden class="errormsg"></label>-->
<!--                    </div>-->
<!--                </div>-->
                <div id="CONFIG_ENTRY_tr_type" class="form-group row"> </div>
                <div id="CONFIG_ENTRY_tr_data" class="form-group row"></div>
                <div id="CONFIG_ENTRY_tr_btn"  class="form-group row"></div>
    </form>
</div>
</div>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->