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
    <script>
        $(document).ready(function(){
            $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:5,imaginary:2}});
            $(".percentage").doValidation({rule:'numbersonly',prop:{realpart:2}});
            $(".inetger").doValidation({rule:'numbersonly',prop:{realpart:6}});
            $(".date-picker").datepicker({
                dateFormat:"dd-mm-yy",
                changeYear: true,
                changeMonth: true
            });
            //SET MINDATE FOR ENDDATE EXTENDED DATE
            $(document).on('change','#ISS_db_datesrchfrom',function(){
                var ISS_fomdate = $('#ISS_db_datesrchfrom').datepicker('getDate');
                var date = new Date( Date.parse( ISS_fomdate ));
                date.setDate( date.getDate() );
                var ISS_otdate = date.toDateString();
                ISS_otdate = new Date( Date.parse( ISS_otdate ));
                $('#ISS_db_datesrchto').datepicker("option","minDate",ISS_otdate);
            });
            var errormessage=[];
            var option="get_item_no";
            var itemdetail=[]; var item_no=[];
            var item_name=''; var item_namearray=[]; var item_noearray=[];
            $('.preloader').show();
            $.ajax({
                type: "POST",
                url: "DB_INVENTORY_SITE_STOCK.php",
                data: {'option':option},
                success: function(itemno){
                    itemdetail=JSON.parse(itemno);
                    item_no=itemdetail[0];
                    errormessage=itemdetail[1];
                    if(item_no.length>0) {
                        var itemno = '<option>SELECT</option>';
                        for (var i = 0; i < item_no.length; i++) {
                            itemno += '<option value="' + item_no[i].id + '">' + item_no[i].no + '</option>';
                            item_noearray.push(item_no[i].no);
                            item_namearray.push(item_no[i].name);
                        }
                        $('#ISS_lb_itemno').html(itemno);
                        loadsearchby_itemno_itemname(item_noearray,item_namearray);
                        $('.preloader').hide();
                    }
                    else{
                        $('.preloader').hide();
                        show_msgbox("INVENTORY SITE STOCK",errormessage[2],"error",false);
                    }
                }
            });
            function loadsearchby_itemno_itemname(itemnos,itemnames){
                if(itemnos.length>0) {
                    var item_nos = '<option>SELECT</option>';
                    for (var i = 0; i < itemnos.length; i++) {
                        item_nos += '<option value="' + itemnos[i] + '">' + itemnos[i]+ '</option>';
                    }
                    $('#ISS_lb_itemnosrch').html(item_nos);
                }
                if(itemnames.length>0) {
                    var item_names = '<option>SELECT</option>';
                    for (var i = 0; i < itemnames.length; i++) {
                        item_names += '<option value="' + itemnames[i]+ '">' + itemnames[i] + '</option>';
                    }
                    $('#ISS_lb_itemnamesrch').html(item_names);
                }
            }
            $(document).on('change','#ISS_lb_itemno',function(){
                $('.preloader').show();
                var option="get_item_name";
                var itemnum=$('#ISS_lb_itemno').val();
                $.ajax({
                    type: "POST",
                    url: "DB_INVENTORY_SITE_STOCK.php",
                    data: {'option':option,'item_no':itemnum},
                    success: function(itemname){
                        item_name=itemname;
                        if(item_name!=''&&item_name!=null){
                            $('.preloader').hide();
                            $('#ISS_tb_itemname').val(item_name);
                        }
                        else{
                            $('.preloader').hide();
                            show_msgbox("INVENTORY SITE STOCK",errormessage[2],"error",false);
                        }
                    }
                });
            });
            $(document).on('click','#ISS_btn_additem',function(){
                $('#ISS_btn_additem').hide();
                $('#inv_sitestockdiv').show();
                $('#ISS_btn').show();
                $('#ISS_btn_save').val('SAVE');
                $("#inv_searchoptdiv").hide();
                $('#inv_searchbydiv').hide();
                $("#ISS_htmltable").hide();
            });
            $(document).on('click','#ISS_btn_cancel',function(){
                clearform();
            });
            function clearform(){
                $('#ISS_btn_additem').show();
                $("#inv_searchoptdiv").show();
                $('#ISS_btn').hide();
                $('#inv_sitestockdiv').hide();
                $("#inv_sitestockdiv,#inv_searchbydiv,#inv_searchoptdiv").find('input:text,textarea').val('');
                $("#inv_sitestockdiv,#inv_searchbydiv,#inv_searchoptdiv").find('select').val('SELECT');
                $("#inv_sitestockdiv,#inv_searchbydiv,#inv_searchoptdiv").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
            }
            $(document).on('change blur','#invsitestockform',function(){
                if($('#ISS_db_date').val()!='' && $('#ISS_lb_itemno').val()!='SELECT' && $('#ISS_tb_itemname').val()!='' &&
                    $('#ISS_tb_openingbal').val()!='' && $('#ISS_tb_addnewstock').val()!='' && $('#ISS_tb_itemdrawn').val()!='' &&
                    $('#ISS_tb_itemreturn').val()!='' && $('#ISS_tb_siteused').val()!='' && $('#ISS_tb_sitestock').val()!='' &&
                    $('#ISS_tb_sold').val()!='' && $('#ISS_tb_balstock').val()!=''){
//                    $('#ISS_btn_save').removeAttr('disabled');
                    $('#ISS_btn_save').attr('disabled','disabled');
                }
                else{
                    $('#ISS_btn_save').attr('disabled','disabled');
                }
            });
        //CHANGE EVENT FOR SEARCH BY LIST BOX
            $('#ISS_lb_srchby').change(function(){
                var listvalue=$('#ISS_lb_srchby').val();
                $('#ISS_lb_itemnosrch').val('SELECT');
                $('#ISS_lb_itemnamesrch').val('SELECT');
                $('#ISS_db_datesrchfrom').val('');
                $('#ISS_db_datesrchto').val('');
                if(listvalue=='SELECT'){
                    $('#inv_searchbydiv').hide();
                    $("#ISS_htmltable").hide();
                }
                else if(listvalue=='DATE RANGE'){
                    $('#inv_searchbydiv').show();
                    $("#ISS_datesrch").show();
                    $("#ISS_itemnosrch").hide();
                    $("#ISS_itemnamesrch").hide();
                    $("#ISS_htmltable").hide();
                }
                else if(listvalue=='ITEM NO'){
                    $('#inv_searchbydiv').show();
                    $("#ISS_datesrch").hide();
                    $("#ISS_itemnosrch").show();
                    $("#ISS_itemnamesrch").hide();
                    $("#ISS_htmltable").hide();
                }
                else if(listvalue=='ITEM NAME'){
                    $('#inv_searchbydiv').show();
                    $("#ISS_datesrch").hide();
                    $("#ISS_itemnosrch").hide();
                    $("#ISS_itemnamesrch").show();
                    $("#ISS_htmltable").hide();
                }
            });
            $(document).on('change','.searchchange',function(){
                if(($('#ISS_db_datesrchfrom').val()!='' && $('#ISS_db_datesrchto').val()!='') ||
                $('#ISS_lb_itemnosrch').val()!='SELECT' || $('#ISS_lb_itemnamesrch').val()!='SELECT') {
                    $('#ISS_htmltable').hide();
                    $('.preloader').show();
                    var option = 'get_sitestock';
                    var formelement = $('#invsitestockform').serialize();
                    $.ajax({
                        type: "POST",
                        url: "DB_INVENTORY_SITE_STOCK.php",
                        data: formelement + '&option=' + option,
                        success: function (sitestock) {
                            var site_stock=[];
                            site_stock=JSON.parse(sitestock);
                            if (site_stock.length > 0) {
                                stockdata_table(site_stock);
                                $('.preloader').hide();
                            }
                            else {
                                $('.preloader').hide();
                                show_msgbox("INVENTORY SITE STOCK", errormessage[2], "error", false);
                                $('#ISS_lb_itemnosrch').val('SELECT');
                                $('#ISS_lb_itemnamesrch').val('SELECT');
                                $('#ISS_db_datesrchfrom').val('');
                                $('#ISS_db_datesrchto').val('');
                            }
                        }
                    });
                }
            });
            var table;
            function stockdata_table(sitestock){
                if(sitestock.length>0) {
                    var ISS_table_header = '<table style="width:1300px" id="ISS_tbl_htmltable" border="1"  cellspacing="0" class="srcresult"><thead bgcolor="#6495ed" style="color:white"><tr><th style="width:90px;text-align: center" nowrap class="uk-date-column">DATE</th><th style="width:90px;text-align: center" nowrap>ITEM NO</th><th style="width:300px;text-align: center" nowrap>ITEM NAME</th><th style="width:100px;text-align: center" nowrap>WEEKLY OPENING BALANCE</th><th style="width:80px;text-align: center" nowrap>ADD NEW STOCK</th><th style="width:70px;text-align: center" nowrap>DRAWN</th><th style="width:80px;text-align: center" nowrap>RETURNED</th><th style="width:80px;text-align: center" nowrap>SITE USED</th><th style="width:90px;text-align: center" nowrap>SITE STOCK</th><th style="width:50px;text-align: center" nowrap>SOLD</th><th style="width:80px;text-align: center">BALANCE STOCK</th></tr></thead><tbody>';
                    for (var i = 0; i < sitestock.length; i++) {
                        ISS_table_header += '<tr>';//<td style="text-align:center;"><div><span style="display: block;color:green" class="glyphicon glyphicon-edit edit" id="'+i+'_'+sitestock[i][0]+'"></span></div></td>';
                        for (var j = 1; j < sitestock[i].length; j++) {
                            if (j >= 1 && j <= 11 && j != 3) {
                                if(sitestock[i][j]!=null){
                                    ISS_table_header += '<td style="text-align: center" nowrap>' + sitestock[i][j] + '</td>';
                                }else{
                                    ISS_table_header += '<td></td>';
                                }
                            }
                            else {
                                if(sitestock[i][j]!=null){
                                    ISS_table_header += '<td>' + sitestock[i][j] + '</td>';
                                }else{
                                    ISS_table_header += '<td></td>';
                                }
                            }
                        }
                        ISS_table_header += '</tr>';
                    }
                    ISS_table_header += '</tbody></table>';
                    $('section').html(ISS_table_header);
                    table=$('#ISS_tbl_htmltable').DataTable({
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType": "full_numbers",
                        "aoColumnDefs" : [
                            { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                    });
                    sorting();
                    $('.preloader').hide();
                    $('#ISS_htmltable').show();
                }
                else{
                    $('.preloader').hide();
                    $('#ISS_htmltable').hide();
                    show_msgbox("INVENTORY SITE STOCK",errormessage[3],"error",false);
                }
            }

        });
    </script>
</head>
<body>
<form id="invsitestockform" class="form-horizontal">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">INVENTORY SITE STOCK</h2>
            </div>
            <div class="panel-body">
                <fieldset>
                    <div id="inv_sitestockdiv" hidden>
                        <div class="form-group">
                            <label class="col-md-3">DATE <em>*</em></label>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <input type="text" class="form-control date-picker datemandtry" id="ISS_db_date" name="ISS_db_date" placeholder="Date">
                                    <label for="ISS_db_date" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">ITEM NO <em>*</em></label>
                            <div class="col-lg-2"><select class="form-control" id="ISS_lb_itemno" name="ISS_lb_itemno"><option>SELECT</option></select></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">ITEM NAME <em>*</em></label>
                            <div class="col-lg-4"><input type="text" class="form-control" id="ISS_tb_itemname" name="ISS_tb_itemname" placeholder="Item Name" readonly></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">WEEKLY OPENING BALANCE <em>*</em></label>
                            <div class="col-lg-2"><input type="text" class="form-control inetger" id="ISS_tb_openingbal" name="ISS_tb_openingbal" placeholder="Opening Balance"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">ADD NEW STOCK <em>*</em></label>
                            <div class="col-lg-2"><input type="text" class="form-control inetger" id="ISS_tb_addnewstock" name="ISS_tb_addnewstock" placeholder="Add New Stock"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">ITEM DRAWN <em>*</em></label>
                            <div class="col-lg-2"><input type="text" class="form-control inetger" id="ISS_tb_itemdrawn" name="ISS_tb_itemdrawn" placeholder="Item Drawn"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">ITEM RETURNED <em>*</em></label>
                            <div class="col-lg-2"><input type="text" class="form-control inetger" id="ISS_tb_itemreturn" name="ISS_tb_itemreturn" placeholder="Item Returned"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">SITE USED <em>*</em></label>
                            <div class="col-lg-2"><input type="text" class="form-control inetger" id="ISS_tb_siteused" name="ISS_tb_siteused" placeholder="Site Used"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">SITE STOCK <em>*</em></label>
                            <div class="col-lg-2"><input type="text" class="form-control inetger" id="ISS_tb_sitestock" name="ISS_tb_sitestock" placeholder="Site Stock" ></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">SOLD <em>*</em></label>
                            <div class="col-lg-2"><input type="text" class="form-control inetger" id="ISS_tb_sold" name="ISS_tb_sold" placeholder="Sold" ></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">BALANCE STOCK <em>*</em></label>
                            <div class="col-lg-2"><input type="text" class="form-control inetger" id="ISS_tb_balstock" name="ISS_tb_balstock" placeholder="Balance Stock" ></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-2" style="padding-bottom:15px">
                            <input type="button" class="btn btn-info" name="ISS_btn_additem" id="ISS_btn_additem" value="ADD NEW">
                        </div>
                        <div class="col-lg-offset-10" style="padding-left:15px" id="ISS_btn" hidden>
                            <input type="button" class="btn btn-info" name="ISS_btn_save" id="ISS_btn_save" value="SAVE" disabled>
                            <input type="button" class="btn btn-info" name="ISS_btn_cancel" id="ISS_btn_cancel" value="CANCEL">
                        </div>
                    </div>
                    <div class="form-group" id="inv_searchoptdiv">
                        <label class="col-md-2">SEARCH BY</label>
                        <div class="col-lg-2">
                            <select class="form-control" id="ISS_lb_srchby" name="ISS_lb_srchby">
                                <option>SELECT</option>
                                <option>DATE RANGE</option>
                                <option>ITEM NO</option>
                                <option>ITEM NAME</option>
                            </select>
                        </div>
                    </div>
                    <div id="inv_searchbydiv" hidden>
                        <div class="form-group" id="ISS_datesrch">
                            <label class="col-md-2">DATE FROM </label>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <input type="text" class="form-control date-picker datemandtry searchchange" id="ISS_db_datesrchfrom" name="ISS_db_datesrchfrom" placeholder="Date">
                                    <label for="ISS_db_datesrchfrom" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                                </div>
                            </div>
                            <label class="col-md-1">DATE TO </label>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <input type="text" class="form-control date-picker datemandtry searchchange" id="ISS_db_datesrchto" name="ISS_db_datesrchto" placeholder="Date">
                                    <label for="ISS_db_datesrchto" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="ISS_itemnosrch" hidden>
                            <label class="col-md-2">ITEM NO </label>
                            <div class="col-lg-2"><select class="form-control searchchange" id="ISS_lb_itemnosrch" name="ISS_lb_itemnosrch"><option>SELECT</option></select></div>
                        </div>
                        <div class="form-group" id="ISS_itemnamesrch" hidden>
                            <label class="col-md-2">ITEM NAME </label>
                            <div class="col-lg-4"><select class="form-control searchchange" id="ISS_lb_itemnamesrch" name="ISS_lb_itemnamesrch"><option>SELECT</option></select></div>
                        </div>
                    </div>
                </fieldset>
                <div class="table-responsive" id="ISS_htmltable" hidden>
                    <section>
                    </section>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>​
