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
        var CONFIG_ENTRY_mod_opt='<option value="SELECT">SELECT</option>';
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var CONFIG_ENTRY_values=JSON.parse(xmlhttp.responseText);
                CONFIG_ENTRY_errmsg=CONFIG_ENTRY_values[0];
                var CONFIG_ENTRY_typ_opt='<option value="SELECT">SELECT</option>';
                for (var i=0;i<CONFIG_ENTRY_values[1].length;i++) {
                    CONFIG_ENTRY_mod_opt += '<option value="' + CONFIG_ENTRY_values[1][i][0] + '">' + CONFIG_ENTRY_values[1][i][1] + '</option>';
                }
                $('#CONFIG_ENTRY_lb_module').html(CONFIG_ENTRY_mod_opt);
            }}
        var OPTION="CONFIG_ENTRY_load_mod";
        xmlhttp.open("GET","DB_CONFIGURATION_ENTRY.php?option="+OPTION,true);
        xmlhttp.send(new FormData());

        $(document).on("keyup",'.upper',function() {
            if (this.value.match(/[^a-zA-Z0-9\-]/g)) {
                this.value = this.value.replace(/[^a-zA-Z0-9\-]/g, '');
            }
            $('#CONFIG_ENTRY_tb_data').val($('#CONFIG_ENTRY_tb_data').val().toUpperCase())
        });
        //CHANGE EVENT FOR MODULE CONFIG
        $(document).on('change','#CONFIG_ENTRY_lb_module',function(){
            $('#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn,#CONFIG_ENTRY_tr_type').empty();
            $('#CONFIG_ENTRY_div_errMod').hide();
            var CONFIG_ENTRY_typ_opt='<option value="SELECT">SELECT</option>';
            var formElement = document.getElementById("CONFIG_ENTRY_form");
            if($(this).val()!='SELECT'){
                $('.preloader').show();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $('.preloader').hide();
                        var CONFIG_ENTRY_values=JSON.parse(xmlhttp.responseText);
                        if(CONFIG_ENTRY_values.length==0){
                            $('#CONFIG_ENTRY_div_errMod').show();
                            $('#CONFIG_ENTRY_div_errMod').text(CONFIG_ENTRY_errmsg[1].replace('[TYPE]',$("#CONFIG_ENTRY_lb_module option:selected").text()));
                        }else{
                            $('#CONFIG_ENTRY_div_errMod').hide();
                            for (var i=0;i<CONFIG_ENTRY_values.length;i++) {
                                CONFIG_ENTRY_typ_opt += '<option value="' + CONFIG_ENTRY_values[i][0] + '">' + CONFIG_ENTRY_values[i][1] + '</option>';
                            }
                            $('#CONFIG_ENTRY_tr_type').append(' <div class="form-group row" ><label class="col-sm-2 control-label">TYPE<em>*</em></label> <div class="col-sm-10"><select id="CONFIG_ENTRY_lb_type" name="CONFIG_ENTRY_lb_type"  class="form-control" style="width:305px"></select> </div></div>')
                            $('#CONFIG_ENTRY_lb_type').html(CONFIG_ENTRY_typ_opt);
                        }
                    }}
                var OPTION="CONFIG_ENTRY_load_type";
                var CONFIG_ENTRY_data=$(this).val();
                xmlhttp.open("GET","DB_CONFIGURATION_ENTRY.php?option="+OPTION+"&module="+CONFIG_ENTRY_data,true);
                xmlhttp.send(new FormData());
            }
            else
            {
                $('#CONFIG_ENTRY_div_errMod').hide();
            }
        });
        //CHANGE EVENT FOR TYPE CONFIG
        $(document).on('change','#CONFIG_ENTRY_lb_type',function(){
            $('#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn').empty();
            if($('#CONFIG_ENTRY_lb_type').val()!='SELECT')
            {
                if(($('#CONFIG_ENTRY_lb_type').val()=='18') || ($('#CONFIG_ENTRY_lb_type').val()=='16') || ($('#CONFIG_ENTRY_lb_type').val()=='6'))
                {
                    $('#CONFIG_ENTRY_tr_data').append('<div class="form-group row" ><label class="control-label col-sm-2">DATA<em>*</em></label> <div class="col-sm-3"><input type="text" id="CONFIG_ENTRY_tb_data" class="alphabets form-control"  name="CONFIG_ENTRY_tb_data" maxlength="50" placeholder="Data"></div><div id="CONFIG_ENTRY_div_errmsg" hidden class="errormsg"></div>');
                }
                else
                {
                    $('#CONFIG_ENTRY_tr_data').append('<div class="form-group row" ><label class="control-label col-sm-2">DATA<em>*</em></label> <div class="col-sm-3"><input type="text" id="CONFIG_ENTRY_tb_data" name="CONFIG_ENTRY_tb_data" class="form-control upper"   maxlength="25" placeholder="Data"></div><div id="CONFIG_ENTRY_div_errmsg" hidden class="errormsg"></div>');
                }
                $('#CONFIG_ENTRY_tr_btn').append('&nbsp;&nbsp;&nbsp;<input  type="button" id="CONFIG_ENTRY_btn_save" class="btn  btn-info btn-sm" value="SAVE" disabled>&nbsp;&nbsp;&nbsp;<input type="button" id="CONFIG_ENTRY_btn_reset" class="btn btn-info btn-sm" value="RESET">');
//                $("#CONFIG_ENTRY_tb_data").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:true,autosize:true}});
                $(".alphabets").doValidation({rule:'alphabets',prop:{whitespace:true,uppercase:true,autosize:true}});
            }
            else
            {
                $('#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn').empty();
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
                        var errmsg=CONFIG_ENTRY_errmsg[2].replace('[MODULE NAME]',$("#CONFIG_ENTRY_lb_module option:selected").text());
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
                        $('#CONFIG_ENTRY_tr_type,#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn').empty();
                    }
                    $('#CONFIG_ENTRY_lb_module').val('SELECT');
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
            $('#CONFIG_ENTRY_tr_type,#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn').empty();
            $('#CONFIG_ENTRY_lb_module').val('SELECT');
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
            <h2 class="panel-title">CONFIGURATION ENTRY</h2>
        </div>
        <div class="panel-body">
            <form id="CONFIG_ENTRY_form" name="CONFIG_ENTRY_form" class="form-horizontal" role="form">
                <div class="form-group row" >
                    <label  class="col-sm-2 control-label">SCRIPT NAME<em>*</em></label>
                    <div class="col-sm-10">
                        <select name="CONFIG_ENTRY_lb_module" id="CONFIG_ENTRY_lb_module" class="form-control" style="width:305px">
                            <option>SELECT</option>
                        </select><br><label id="CONFIG_ENTRY_div_errMod" hidden class="errormsg"></label>
                    </div>
                </div>
        <div id="CONFIG_ENTRY_tr_type"> </div>
                <div id="CONFIG_ENTRY_tr_data"></div>
                    <div id="CONFIG_ENTRY_tr_btn"></div>
    </form>
</div>
</div>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->