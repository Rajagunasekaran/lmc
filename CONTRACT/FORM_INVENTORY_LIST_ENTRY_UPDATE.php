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
        $(document).on("keyup",'.alphanumeric',function() {
            if (this.value.match(/[^a-zA-Z0-9\_\-\ \.\,\/]/g)) {
                this.value = this.value.replace(/[^a-zA-Z0-9\_\-\ \.\,\/]/g, '');
            }
        });
        $("textarea").autogrow({vertical: true, horizontal: true});
        $('.preloader').show();
        var errormessage=[];
        var option="get_item_details";
        var itemdetail=[];var rowid='';
        var items=[];var contractno=[];var unitofmeasure=[];
        $.ajax({
            type: "POST",
            url: "DB_INVENTORY_LIST_ENTRY_UPDATE.php",
            data: {'option':option},
            success: function(itemdtl){
                itemdetail=JSON.parse(itemdtl);
                items=itemdetail[0];
                errormessage=itemdetail[1];
                contractno=itemdetail[2];
                unitofmeasure=itemdetail[3];
                load_contractno_uom(contractno,unitofmeasure);
                if(items!=null && items!=''){
                    data_table(items);
                }
                else if(items==null || items==''){
                    var errdata=errormessage[3].replace('USER','ITEM');
                    $('.preloader').hide();
                    show_msgbox("INVENTORY LIST ENTRY / UPDATE",errdata,"error",false);
                }
                else{
                    $('.preloader').hide();
                    show_msgbox("INVENTORY LIST ENTRY / UPDATE",items,"error",false);
                }
            }
        });
        var table;
        function data_table(items){
            if(items.length>0) {
                var ILEU_table_header = '<table style="width:1500px" id="ILEU_tbl_htmltable" border="1"  cellspacing="0" class="srcresult"><thead bgcolor="#6495ed" style="color:white"><tr><th style="width:30px;text-align: center" nowrap>EDIT</th><th style="width:100px;text-align: center" nowrap>ITEM NO</th><th style="width:300px;text-align: center" nowrap>DESCRIPTION</th><th style="width:100px;text-align: center" nowrap>CONTRACT NO</th><th style="width:90px;text-align: center" nowrap>COST</th><th style="width:100px;text-align: center" nowrap>INTERNAL COST</th><th style="width:100px;text-align: center" nowrap>PERCENTAGE LEVEL</th><th style="width:100px;text-align: center" nowrap>COST AFTER DISCOUNT</th><th style="width:100px;text-align: center" nowrap>UNIT OF MEASURE</th><th style="width:90px;text-align: center" nowrap>QTY SOLD</th><th style="text-align: center" class="uk-timestp-column">TIMESTAMP</th></tr></thead><tbody>';
                for (var i = 0; i < items.length; i++) {
                    ILEU_table_header += '<tr><td style="text-align:center;"><div><span style="display: block;color:green" class="glyphicon glyphicon-edit edit" id="'+i+'_'+items[i][0]+'"></span></div></td>';
                    for (var j = 1; j < items[i].length; j++) {
                        if (j >= 1 && j <= 10 && j != 2) {
                            if(items[i][j]!=null){
                                ILEU_table_header += '<td style="text-align: center" nowrap>' + items[i][j] + '</td>';
                            }else{
                                ILEU_table_header += '<td></td>';
                            }
                        }
                        else {
                            if(items[i][j]!=null){
                                ILEU_table_header += '<td>' + items[i][j] + '</td>';
                            }else{
                                ILEU_table_header += '<td></td>';
                            }
                        }
                    }
                    ILEU_table_header += '</tr>';
                }
                ILEU_table_header += '</tbody></table>';
                $('section').html(ILEU_table_header);
                table=$('#ILEU_tbl_htmltable').DataTable({
                    "aaSorting": [],
                    "pageLength": 10,
                    "sPaginationType": "full_numbers",
                    "aoColumnDefs" : [
                        { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                });
                sorting();
                $('.preloader').hide();
            }
            else{
                var errdata=errormessage[3].replace('USER','ITEM');
                $('.preloader').hide();
                show_msgbox("INVENTORY LIST ENTRY / UPDATE",errdata,"error",false);
            }
        }
        function load_contractno_uom(contractno,measures){
            if(contractno.length>0){
                var contract='<option>SELECT</option>';
                for (var i=0;i<contractno.length;i++) {
                    contract += '<option value="' + contractno[i] + '">' + contractno[i] + '</option>';
                }
                $('#ILEU_contractno').html(contract);
            }
            if(measures.length>0){
                var units='<option>SELECT</option>';
                for (var i=0;i<measures.length;i++) {
                    units += '<option value="' + measures[i] + '">' + measures[i] + '</option>';
                }
                $('#ILEU_uom').html(units);
            }
        }
        function clearform(){
            $('#ILEU_btn_additem').show();
            $('#inv_entryform').hide();
            $('#ILEU_btn').hide();
            $('#ILEU_btn_save').val('SAVE');
            $("#inv_entryform").find('input:text,textarea').val('');
            $("#inv_entryform").find('select').val('SELECT');
            $("#inv_entryform").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
            rowid='';
            $('#ILEU_itemdesc').height(20);
        }
        $(document).on('click','#ILEU_btn_additem',function(){
            $('#ILEU_btn_additem').hide();
            $('#inv_entryform').show();
            $('#ILEU_btn').show();
            $('#ILEU_btn_save').val('SAVE');
            $('#ILEU_itemdesc').height(20);
        });
        $(document).on('click','#ILEU_btn_cancel',function(){
            clearform();
        });
        $(document).on('change blur','#reportaccident',function(){
            if($('#ILEU_itemno').val()!='' && $('#ILEU_itemdesc').val()!='' && $('#ILEU_contractno').val()!='SELECT' && $('#ILEU_uom').val()!='SELECT'){
                $('#ILEU_btn_save').removeAttr('disabled');
            }
            else{
                $('#ILEU_btn_save').attr('disabled','disabled');
            }
        });
        $(document).on('click','#ILEU_btn_save',function(){
            $('#ILEU_btn_additem').hide();
            $('#ILEU_btn').show();
            $('.preloader').show();
            var btnval=$('#ILEU_btn_save').val();
            var formelement=$('#reportaccident').serialize();
            $.ajax({
                type: "POST",
                url: "DB_INVENTORY_LIST_ENTRY_UPDATE.php",
                data: formelement+'&option=save_item_details&btn_val='+btnval+'&row_id='+rowid,
                success: function(itemsave){
                    var itemdsave=JSON.parse(itemsave);
                    items=itemdsave[0];
                    var flagvalue=itemdsave[1];
                    if(btnval=='SAVE'){
                        if(flagvalue==1){
                            var errdata=errormessage[0].replace('REPORT','ITEM');
                            data_table(items);
                            show_msgbox("INVENTORY LIST ENTRY / UPDATE",errdata,"success",false);
                            clearform();
                        }
                        else if(flagvalue==0){
                            var errdata=errormessage[2].replace('REPORT','ITEM');
                            $('.preloader').hide();
                            show_msgbox("INVENTORY LIST ENTRY / UPDATE",errdata,"error",false);
                        }
                        else{
                            $('.preloader').hide();
                            show_msgbox("INVENTORY LIST ENTRY / UPDATE",flagvalue,"error",false);
                        }
                    }
                    else if(btnval=='UPDATE'){
                        if (flagvalue == 1) {
                            var errdata = errormessage[1].replace('REPORT', 'ITEM');
                            data_table(items);
                            show_msgbox("INVENTORY LIST ENTRY / UPDATE", errdata, "success", false);
                            clearform();
                        }
                        else if (flagvalue == 0) {
                            var errdata = errormessage[4].replace('REPORT', 'ITEM');
                            $('.preloader').hide();
                            show_msgbox("INVENTORY LIST ENTRY / UPDATE", errdata, "error", false);
                        }
                        else {
                            $('.preloader').hide();
                            show_msgbox("INVENTORY LIST ENTRY / UPDATE", flagvalue, "error", false);
                        }
                    }
                }
            });
        });
        $(document).on('click','.edit',function(){
            $('.preloader').show();
            $('#inv_entryform').hide();
            $('#ILEU_btn_additem').hide();
            $('#ILEU_btn_save').val('UPDATE');
            $('#ILEU_btn').show();
            var rowidslpit=this.id.split('_');
            rowid=rowidslpit[1];
            var tds = table.row(rowidslpit[0]).data();
            $('#ILEU_itemno').val(tds[1]);
            $('#ILEU_itemdesc').val(tds[2]);
            $('#ILEU_contractno').val(tds[3]);
            $('#ILEU_cost').val(tds[4]);
            $('#ILEU_internalcost').val(tds[5]);
            $('#ILEU_percentlevel').val(tds[6]);
            $('#ILEU_costdiscount').val(tds[7]);
            $('#ILEU_uom').val(tds[8]);
            $('#ILEU_quantity').val(tds[9]);
            $('#ILEU_infoupdate').val(tds[10]);
            $('.preloader').hide();
            $('#inv_entryform').show();
        });

    });
</script>
</head>
<body>
<form id="reportaccident" class="form-horizontal">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">INVENTORY LIST ENTRY / UPDATE</h2>
            </div>
            <div class="panel-body">
                <fieldset>
                    <div id="inv_entryform" hidden>
                        <div class="form-group">
                            <label class="col-md-3">ITEM NO <em>*</em></label>
                            <div class="col-lg-2"><input type="text" class="form-control alphanumeric" id="ILEU_itemno" maxlength="10" name="ILEU_itemno" placeholder="Item No"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">ITEM DESCRIPTION <em>*</em></label>
                            <div class="col-lg-4"><textarea class="form-control" id="ILEU_itemdesc" name="ILEU_itemdesc" rows="1" maxlength="100" placeholder="Description"></textarea></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">CONTRACT NO <em>*</em></label>
                            <div class="col-lg-2"><select class="form-control" id="ILEU_contractno" name="ILEU_contractno"><option>SELECT</option></select></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">COST </label>
                            <div class="col-lg-2"><input type="text" class="form-control amountonly" id="ILEU_cost" name="ILEU_cost" placeholder="0.00"></div>
                            <label class="col-md-2">INTERNAL COST </label>
                            <div class="col-lg-2"><input type="text" class="form-control amountonly" id="ILEU_internalcost" name="ILEU_internalcost" placeholder="0.00"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">PERCENTAGE LEVEL </label>
                            <div class="col-lg-2"><input type="text" class="form-control percentage" id="ILEU_percentlevel" name="ILEU_percentlevel" placeholder="Percentage"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">COST AFTER DISCOUNT </label>
                            <div class="col-lg-2"><input type="text" class="form-control amountonly" id="ILEU_costdiscount" name="ILEU_costdiscount" placeholder="0.00"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">UNIT OF MEASURE <em>*</em></label>
                            <div class="col-lg-2"><select class="form-control" id="ILEU_uom" name="ILEU_uom"><option>SELECT</option></select></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">QTY SOLD </label>
                            <div class="col-lg-2"><input type="text" class="form-control inetger" id="ILEU_quantity" name="ILEU_quantity" placeholder="Quantity"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">LAST INFO UPDATE </label>
                            <div class="col-lg-2"><input type="text" class="form-control" id="ILEU_infoupdate" name="ILEU_infoupdate" placeholder="Timestamp" disabled></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-2" style="padding-bottom:15px">
                            <input type="button" class="btn btn-info" name="ILEU_btn_additem" id="ILEU_btn_additem" value="ADD NEW">
                        </div>
                        <div class="col-lg-offset-10" style="padding-left:15px" id="ILEU_btn" hidden>
                            <input type="button" class="btn btn-info" name="ILEU_btn_save" id="ILEU_btn_save" value="SAVE" disabled>
                            <input type="button" class="btn btn-info" name="ILEU_btn_cancel" id="ILEU_btn_cancel" value="CANCEL">
                        </div>
                    </div>
                </fieldset>
                <div class="table-responsive" id="ILEU_htmltable" >
                    <section>
                    </section>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>​
