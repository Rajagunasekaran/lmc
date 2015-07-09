<?php
/**
 * Created by PhpStorm.
 * User: RAJA
 * Date: 24-06-2015
 * Time: 11:24 AM
 */
include "../FOLDERMENU.php";
?>
<html>
<head>
    <style type="text/css">
        .ui-datepicker-calendar {
            display: none;
        }
    </style>
    <script type="text/javascript">
        // function for graphics chart
        function drawChart(twodim,chart_errormsg,color) {
            var container = document.getElementById('chart_div');
            var chart = new google.visualization.Timeline(container);
            var options = {
                timeline: {
                    groupByRowLabel: true
                },
                title: chart_errormsg,
                width:'100%',
                height:'100%',
                colors: color
            };
            var data = new google.visualization.DataTable();
            data.addColumn({ type: 'string', id: 'Position' });
            data.addColumn({ type: 'string', id: 'Name' });
            data.addColumn({ type: 'date', id: 'Start' });
            data.addColumn({ type: 'date', id: 'End' });
            data.addRows(twodim);
            chart.draw(data,options);
        }
        google.setOnLoadCallback(drawChart);
    </script>
    <script>
        $(document).ready(function(){
            var errormsg=[];
            var chart_periodmonthBefore='';
            var chart_periodyearBefore='';
            var chart_periodfromdate='';
            var chart_todate='';
            var chart_periodtodate='';
            var chart_monthdate='';
            var chart_db_dataId=null;
            $.ajax({
                type: "POST",
                url: "DB_REPORT_CHARTS.php",
                data: 'option=Get_initialdata',
                success: function(msg){
                    $('.preloader').hide();
                    var msg_alert=JSON.parse(msg);
                    errormsg=msg_alert[0];
                }
            });
        // date picker
            $('.date-picker').datepicker({
                changeMonth: true, changeYear: true,showButtonPanel: true,dateFormat: 'MM-yy',maxDate: new Date()+'2Y',
                onClose: function(dateText, inst) {
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).datepicker('setDate', new Date(year, month, 1));
                    if($(this).attr('id')=='chart_periodfrom'){
                        chart_periodmonthBefore=parseInt(month)+1;
                        chart_periodyearBefore=year;
                    }
                    chart_db_dataId = $('input:radio[name=chart_rd_period]:checked').attr('id');
                    if(chart_db_dataId=='chart_rd_month'){
                        var chart_flag_daterange='chart_flag_month';
                    }
                    else if(chart_db_dataId=='chart_rd_range'){
                        var chart_flag_daterange='chart_flag_range';
                    }
                    chart_periodfromdate=$("#chart_periodfrom").val();
                    chart_periodtodate=$("#chart_periodto").val();
                    chart_monthdate=$("#chart_month").val();
                    if((chart_periodfromdate!='')&&(chart_periodfromdate!=undefined)&&(chart_periodtodate!=undefined)&&(chart_periodtodate!='')){
                        CHART_func_inputdata(chart_periodfromdate,chart_periodtodate,chart_flag_daterange);
                    }
                    if((chart_monthdate!='')&&(chart_monthdate!=undefined)){
                        CHART_func_inputdata(chart_monthdate,chart_monthdate,chart_flag_daterange);
                    }
                },
                beforeShow : function(selected) {
                    $("#chart_periodto").datepicker("option","minDate", new Date(chart_periodyearBefore, chart_periodmonthBefore, 1));
                }
            });
            var cur_date = new Date();
            var cur_year=cur_date.getFullYear()-2;
            var mindate =  new Date(cur_year,0,1);
            $('#chart_db_month').datepicker("option","minDate",mindate);
        // clear form
            $(document).on('change','.chartdatepicker,.ui-datepicker-title',function(){
                $("#chart_errmsgdiv,#chart_div").empty();
                $('#chart_div').width('100%');$('#chart_div').height('0%');
            });
            $(document).on('click','.ui-datepicker-prev,.ui-datepicker-next',function(){
                $("#chart_errmsgdiv,#chart_div").empty();
                $('#chart_div').width('100%');$('#chart_div').height('0%');
            });
        // radio select for period search type
            $(document).on('click','.period_range_month',function(){
                $("html, body").animate({ scrollTop:$(document).height()}, "slow");
                $('#chart_div').width('100%');$('#chart_div').height('0%');
                $("#chart_errmsgdiv,#chart_div").empty();
                var chart_monperrange=$(this).attr('id');
                if(chart_monperrange=='chart_rd_month'){
                    $('#chart_permonth').show();
                    $('#chart_periodfromto').hide();
                    $('#chart_periodfrom').val('');
                    $('#chart_periodto').val('');
                    $('#chart_month').val('');
                }
                else if(chart_monperrange=='chart_rd_range'){
                    $('#chart_periodfromto').show();
                    $('#chart_permonth').hide();
                    $('#chart_periodfrom').val('');
                    $('#chart_periodto').val('');
                    $('#chart_month').val('');
                }
            });
        // sp input and response
            function CHART_func_inputdata(fromdate,todate,flag){
                $('.preloader').show();
                $.ajax({
                    type: "POST",
                    url: "DB_REPORT_CHARTS.php",
                    data:"option=Chart_input&fromdate="+fromdate+"&todate="+todate+"&flag="+flag,
                    success: function(data){
                        $('.preloader').hide();
                        var value_array=JSON.parse(data);
                        CHART_success_chart(value_array,fromdate,todate,flag);
                    },
                    error:function(data){
                        var errordata=(JSON.stringify(data));
                        show_msgbox("CHART",errordata,'error',false);
                    }
                });
            }
            function CHART_success_chart(chart_array,fromdate,todate,flag){
                $("#chart_errmsgdiv,#chart_div").empty();
                var two_dim=[];
                var color_arr=[];
                var twodim=chart_array;
                if(twodim.length<1){
                    $("#chart_errmsgdiv").text(errormsg[0]);
                    $('#chart_div').width('100%');$('#chart_div').height('0%');
                }
                else if(twodim.length>1) {
                    if(flag=='chart_flag_range' && fromdate!='' && todate!='') {
                        var chart_msg = errormsg[1].replace('[SDATE]', fromdate);
                        chart_msg = chart_msg.replace('[EDATE]', todate);
                    }
                    else if(flag=='chart_flag_month' && fromdate!=''){
                        var chart_msg = errormsg[2].replace('[MONTH]', fromdate);
                    }
                    for (var y = 0; y < twodim.length; y++) {
                        twodim[y][2] = new Date(Date.parse(twodim[y][2]));
                        twodim[y][3] = new Date(Date.parse(twodim[y][3]));
                        two_dim.push([twodim[y][0],twodim[y][1],twodim[y][2],twodim[y][3]]);
                        color_arr.push(twodim[y][4]);
                    }
                    $('#chart_div').width('100%');$('#chart_div').height('100%');
                    function resizeHandler() {
                        drawChart(two_dim, chart_msg,color_arr);
                        $("html, body").animate({scrollTop: $(document).height()}, "slow");
                    }
                    resizeHandler();
                    if (window.addEventListener) {
                        window.addEventListener('resize', resizeHandler);
                    }
                    else if (window.attachEvent) {
                        window.attachEvent('onresize', resizeHandler);
                    }
                }
            }
        });
    </script>
</head>
<body>
<form id="reportchart" class="form-horizontal">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">CHART</h2>
            </div>
            <div class="panel-body">
                <fieldset>
                    <div class="row form-group" id="chart_period">
                        <div class="col-md-2">
                            <label>SELECT MONTH</label>
                        </div>
                        <div class="col-md-10">
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label class="radio-inline" style="padding-top: 0px;"> <input type="radio" name="chart_rd_period" id="chart_rd_month" value="MONTH" class="period_range_month"> MONTH</label>
                                </div>
                                <div id="chart_permonth" hidden>
                                    <label class="col-sm-2">SELECT MONTH</label>
                                    <div class="col-sm-2">
                                        <div class="input-group addon" style="width: 170px">
                                            <input id="chart_month" name="chart_month" type="text" class="date-picker datemandtry form-control chartdatepicker" placeholder="Month"/>
                                            <label for="chart_month" class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label class="radio-inline" style="padding-top: 0px;"> <input type="radio" name="chart_rd_period" id="chart_rd_range" value="PERIOD RANGE" class="period_range_month"> PERIOD RANGE</label>
                                </div>
                                <div id="chart_periodfromto" hidden>
                                    <label class="col-sm-2">FROM</label>
                                    <div class="col-sm-5">
                                        <div class="input-group addon" style="width: 170px">
                                            <input id="chart_periodfrom" name="chart_periodfrom" type="text" class="date-picker datemandtry form-control chartdatepicker" placeholder="From"/>
                                            <label for="chart_periodfrom" class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></label>
                                        </div>
                                    </div>
                                    <label class="col-sm-offset-3 col-sm-2" style="padding-top: 15px">TO</label>
                                    <div class="col-sm-5" style="padding-top: 15px">
                                        <div class="input-group addon" style="width: 170px">
                                            <input id="chart_periodto" name="chart_periodto" type="text" class="date-picker datemandtry form-control chartdatepicker" placeholder="To"/>
                                            <label for="chart_periodto" class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="chart_errmsgdiv" class="col-sm-offset-2 errormsg">
                    </div>
                    <div id="chart_div" class="chart">
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</form>
</body>
</html>​
