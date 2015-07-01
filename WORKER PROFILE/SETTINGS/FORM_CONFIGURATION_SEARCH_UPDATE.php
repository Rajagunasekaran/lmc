<!--*********************************************GLOBAL DECLARATION******************************************-->
<!--*********************************************************************************************************//-->
<!--//*******************************************FILE DESCRIPTION*********************************************//
//****************************************VIEW / UPDATE SETTINGS/DELETE*************************************************//
//DONE BY:LALITHA
//VER 0.01-SD:06/01/2015 ED:06/01/2015,TRACKER NO:74,IMPLEMENTED PRELOADER POSITION,CHANGED LOGIN ID INTO EMPLOYEE NAME
//*********************************************************************************************************//
<?PHP
include "../../SUBFOLDERMENU.php"
?>
<!--SCRIPT TAG START-->
<script>
//DOCUMENT READY FUNCTION START
$(document).ready(function(){
    var CONFIG_SRCH_UPD_errmsg=[];
    var CONFIG_SRCH_UPD_typ_opt='<option value="SELECT">SELECT</option>';
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $('.preloader').hide();
            var values=JSON.parse(xmlhttp.responseText);
            var CONFIG_SRCH_UPD_values=values[0];
            CONFIG_SRCH_UPD_errmsg=values[1];
            if(CONFIG_SRCH_UPD_values.length!=0){
                for (var i=0;i<CONFIG_SRCH_UPD_values.length;i++) {
                    CONFIG_SRCH_UPD_typ_opt += '<option value="' + CONFIG_SRCH_UPD_values[i][0] + '">' + CONFIG_SRCH_UPD_values[i][1] + '</option>';
                }
                $('#CONFIG_SRCH_UPD_tr_type').append('<div class="form-group row" ><label  class="col-sm-2 control-label">SELECT SETTINGS<em>*</em></label><div class="col-sm-10"><select id="CONFIG_SRCH_UPD_lb_type" name="CONFIG_SRCH_UPD_lb_type"  class="form-control" style="width:305px"></select></div></div>');
                $('#CONFIG_SRCH_UPD_lb_type').html(CONFIG_SRCH_UPD_typ_opt);
            }
        }
    }
    var OPTION="CONFIG_SRCH_UPD_load_type";
    xmlhttp.open("GET","DB_CONFIGURATION_SEARCH_UPDATE_DELETE.php?option="+OPTION,true);
    xmlhttp.send();
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
                    $('#CONFIG_SRCH_UPD_div_errmsg').text(CONFIG_SRCH_UPD_errmsg[3].replace('[TYPE]',' '+$("#CONFIG_SRCH_UPD_lb_type option:selected").text()));
                }
            }
        }
        var OPTION="CONFIG_SRCH_UPD_load_data";
        xmlhttp.open("POST","DB_CONFIGURATION_SEARCH_UPDATE_DELETE.php?option="+OPTION,true);
        xmlhttp.send(new FormData(formElement));
    }
    //CHANGE EVENT FOR TYPE CONFIG
    $(document).on('change','#CONFIG_SRCH_UPD_lb_type',function(){
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
    var previous_id;
    var combineid;
    var tdvalue;
    $(document).on('click','.data',function(){
        if(previous_id!=undefined){
            $('#'+previous_id).replaceWith("<td class='data' id='"+previous_id+"' >"+tdvalue+"</td>");
        }
        var cid = $(this).attr('id');
        previous_id=cid;
        var id=cid.split('_');
        combineid=id[1];
        tdvalue=$(this).text();
            if(($('#CONFIG_SRCH_UPD_lb_type').val()=='14') || ($('#CONFIG_SRCH_UPD_lb_type').val()=='16') || ($('#CONFIG_SRCH_UPD_lb_type').val()=='17') || ($('#CONFIG_SRCH_UPD_lb_type').val()=='18') || ($('#CONFIG_SRCH_UPD_lb_type').val()=='19') || ($('#CONFIG_SRCH_UPD_lb_type').val()=='20'))
            {
                $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><input type='text' id='CONFIG_SRCH_UPD_tb_data' name='CONFIG_SRCH_data' class='form-control update reports' maxlength='50' value='"+tdvalue+"'>");
            }
            else if($('#CONFIG_SRCH_UPD_lb_type').val()=='13')
            {
                $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><input type='text' id='CONFIG_SRCH_UPD_tb_data' name='CONFIG_SRCH_data' class='form-control update upper' maxlength='25' value='"+tdvalue+"'>");
            }
            else
            {
                $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><input type='text' id='CONFIG_SRCH_UPD_tb_data' name='CONFIG_SRCH_data' class='form-control update' maxlength='50' value='"+tdvalue+"'>");
            }

        $(".reports").doValidation({rule:'alphanumeric',prop:{autosize:true,whitespace:true,uppercase:true}});
        $(document).on("keyup",'.upper',function() {
            if (this.value.match(/[^a-zA-Z0-9\-]/g)) {
                this.value = this.value.replace(/[^a-zA-Z0-9\-]/g, '');
            }
            $('#CONFIG_SRCH_UPD_tb_data').val($('#CONFIG_SRCH_UPD_tb_data').val().toUpperCase());
        });
    });

    //CHANGE FUNCTION FOR DATA
    $(document).on('change','.update',function(){
        var txt_area=$("#CONFIG_SRCH_UPD_tb_data").val().trim();
        var listboxtype=$('#CONFIG_SRCH_UPD_lb_type').val();
        $('#CONFIG_SRCH_UPD_lb_data').removeClass('invalid');
        if((txt_area!='')){
            $('.preloader').show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var value=JSON.parse(xmlhttp.responseText);
                    var numrow=value[0];
                    var updateflag=value[1];
                    if(numrow>=1)
                    {
                       show_msgbox("VIEW/UPDATE SETTINGS",CONFIG_SRCH_UPD_errmsg[8].replace('[TYPE]',$("#CONFIG_SRCH_UPD_lb_type option:selected").text()),"error",false);
                        $('#CONFIG_SRCH_UPD_tb_data').addClass('invalid');
                    }
                    else if(updateflag==1)
                    {
                        var msg=CONFIG_SRCH_UPD_errmsg[4].toString().replace("[MODULE NAME]",$("#CONFIG_SRCH_UPD_lb_type option:selected").text());
                        show_msgbox("VIEW/UPDATE SETTINGS",msg,"success",false);
                        $('#CONFIG_SRCH_UPD_lb_data').removeClass('invalid');
                        CONFIG_SRCH_UPD_fetch_configdata();
                    }
                }
            }
            var OPTION="update";
            xmlhttp.open("POST","DB_CONFIGURATION_SEARCH_UPDATE_DELETE.php?option="+OPTION+"&CONFIG_SRCH_UPD_tb_data="+txt_area+"&listboxtype="+listboxtype+"&rowid="+combineid,true);
            xmlhttp.send();
        }
    });
    //CLICK EVENT FOR BUTTON RESET
    $(document).on('click','#CONFIG_SRCH_UPD_btn_reset',function(){
        $('#CONFIG_SRCH_UPD_tr_type,#CONFIG_SRCH_UPD_tr_data,#CONFIG_SRCH_UPD_tr_btn').empty();
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
            <h2 class="panel-title">VIEW/UPDATE SETTINGS</h2>
        </div>
        <div class="panel-body">
            <form id="CONFIG_SRCH_UPD_form" name="CONFIG_SRCH_UPD_form" class="form-horizontal" role="form">
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