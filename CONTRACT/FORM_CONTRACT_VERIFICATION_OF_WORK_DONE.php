<?php
include "../FOLDERMENU.php";
?>
<style>
    .textarea{
        resize: none;overflow: hidden;
    }
</style>
<script>
    $(document).ready(function() {
        $("#additem :input").attr("disabled", "disabled");
        $( "textarea" ).autogrow( { vertical : true, horizontal : true } );
        $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:5,imaginary:2}});
        $(".numonly").doValidation({rule:'numbersonly',prop:{realpart:5}});
        $(".date-picker").datepicker({
            dateFormat: "dd-mm-yy",
            changeYear: true,
            changeMonth: true
        });
        $('.preloader').show();
        $.ajax({
            type: "POST",
            url: "DB_CONTRACT_VERIFCATION_OF_WORK_DONE.php",
            data: {"option": "INITIAL_DATA"},
            success: function (res) {
                $('.preloader').hide();
                var response = JSON.parse(res);
                var referenceno = response[0];
                var ref_no = '<option>SELECT</option>';
                for (var i = 0; i < referenceno.length; i++) {
                    ref_no += '<option value="' + referenceno[i] + '">' + "LM/"+referenceno[i] + '</option>';
                }
                $('#VWD_lb_referenceno').html(ref_no);
            },
            error: function (data) {
                alert('error in getting' + JSON.stringify(data));
            }
        });
        $('#VWD_lb_referenceno').change(function(){
            var refno=$(this).val();
            if(refno!='SELECT') {
                $('.preloader').show();
                $.ajax({
                    type: "POST",
                    url: "DB_CONTRACT_VERIFCATION_OF_WORK_DONE.php",
                    data: {"option": "Referencenosearch", "refno": refno},
                    success: function (res) {
                        $('.preloader').hide();
                       var response = JSON.parse(res);
                        $('#VWD_location').val(response.location)
                        $('#VWD_referenceno').val("LM/"+refno)
                        $('#VWD_contractno').val(response.contractno)
                        $('#VWD_workorderno').val(response.workorderno)
                        $('#VWD_officerincharge').val(response.oic)
                        $('#VWD_datecreated').val(response.dateentered)
                        $('#VWD_wrkcompleted').val(response.datecompleted)
                        $('#VWD_dateverification').val(response.dateverification)

                    },
                    error: function (data) {
                        alert('error in getting' + JSON.stringify(data));
                    }
                });
            }
            else
            {
                $('#VWD_location').val('')
                $('#VWD_referenceno').val('')
                $('#VWD_contractno').val('')
                $('#VWD_workorderno').val('')
                $('#VWD_officerincharge').val('')
                $('#VWD_datecreated').val('')
                $('#VWD_wrkcompleted').val('')
                $('#VWD_dateverification').val('')
            }
        });
        $(document).on("change blur",'#initialdiv', function (){
            if($('#VWD_location').val()!='' && $('#VWD_referenceno').val()!='' && $('#VWD_contractno').val()!='' && $('#VWD_workorderno').val()!='' && $('#VWD_officerincharge').val()!='' && $('#VWD_datecreated').val()!='' && $('#VWD_wrkcompleted').val()!='' && $('#VWD_dateverification').val()!=''){
               $('#VWD_add').removeAttr('disabled');
            }
            else
            {
                $('#VWD_add').att('disabled','disabled');
            }
        });

        $('#VWD_add').click(function(){
            $("#additem :input").removeAttr("disabled");
            $('#CC_search').attr('disabled','disabled');
            $('#CC_enter').attr('disabled','disabled');
        });
        var itemno;
        $('#CC_itemno').blur(function(){
            itemno=$(this).val();
            if(itemno!='')
            {
                $('#CC_search').removeAttr('disabled');
            }
            else{
                $('#CC_search').attr('disabled','disabled');
            }
        });
        $('#CC_search').click(function(){
                $('.preloader').show();
                $.ajax({
                    type: "POST",
                    url: "DB_CONTRACT_VERIFCATION_OF_WORK_DONE.php",
                    data: {"option": "itemnosearch", "itemno": itemno},
                    success: function (res) {
                        alert(res)
                        $('.preloader').hide();
                        var response = JSON.parse(res);
                    },
                    error: function (data) {
                        alert('error in getting' + JSON.stringify(data));
                    }
            });

        });
        //CLICK EVENT FOR ENTER BTN IN ITEM SEARCH
        $('#CC_enter').click(function(){
            var itemno=$('#CC_itemno').val();
            var quantity=$('#CC_quantity').val();
            var cost=$('#CC_cost').val();
            var description=$('#CC_descrption').val();
            var unitprice=$('#CC_unitprice').val();
            var remark=$('#CC_remark').val();
            var amount=$('#CC_amount').val();
            if($('#CC_internalused').is(':checked'))
            {
                var internalonly='True';
            }
            else
            {
                var internalonly='False';
            }
                var VWD_tablerowcount=$('#tbl_workdone tr').length;
                var VWD_trrowid=VWD_tablerowcount;
                if(VWD_tablerowcount>1){
                    var VWD_lastid=$('#tbl_workdone tr:last').attr('id');
                    var splittrid=VWD_lastid.split('tr_');
                    VWD_trrowid=parseInt(splittrid[1])+1;
                    var VWD_row_id="VWD_tr_"+VWD_trrowid;
                }
                var appendrow='<tr class="" id='+VWD_row_id+'><td>'+itemno+'</td><td style="max-width: 350px">'+description+'</td><td style="max-width: 150px;">'+quantity+'</td><td>'+unitprice+'</td><td>'+cost+'</td><td>'+unitprice+'</td><td>'+amount+'</td><td>'+internalonly+'</td><td>'+remark+'</td></tr>';
                $('#tbl_workdone tr:last').after(appendrow);
        });
    });
</script>
<body>
<form id="formworkdone" name="formworkdone" class="form-horizontal">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">VERIFICATION OF WORK DONE</h2>
            </div>
            <div class="panel-body">
                <div id="initialdiv">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="VWD_location">LOCATION</label>
                    <div class="col-sm-4"><input type="text"  id="VWD_location" name="VWD_location" class="form-control" placeholder="Location" readonly/></div>
                    <label class="col-sm-3 control-label" for="VWD_datecreated">DATE CREATED ORDER</label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="text" id="VWD_datecreated" name="VWD_datecreated" placeholder="Date created order" class="form-control date-picker datemandtry" readonly/>
                            <label for="VWD_datecreated" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar" ></span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="VWD_referenceno">REFERENCE NO</label>
                    <div class="col-sm-2"><select id="VWD_lb_referenceno" name="VWD_lb_referenceno" class="form-control" ></select></div>
                    <div class="col-sm-2"><input type="text"  id="VWD_referenceno" name="VWD_referenceno" placeholder="Reference No" class="form-control" readonly /></div>
                    <label class="col-sm-3 control-label" for="VWD_wrkcompleted">DATE WORK COMPLETED</label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="text" id="VWD_wrkcompleted" name="VWD_wrkcompleted" placeholder="Date Work completed" class="form-control date-picker" readonly/>
                            <label for="VWD_wrkcompleted" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="VWD_contractno">CONTRACT NO/WORK ORDER NO</label>
                    <div class="col-sm-2"><input type="text"  id="VWD_contractno" name="VWD_contractno" placeholder="Contract No" class="form-control" readonly/></div>
                    <div class="col-sm-2"><input type="text"  id="VWD_workorderno" name="VWD_workorderno" placeholder="Work Order No" class="form-control" readonly/></div>
                    <label class="col-sm-3 control-label" for="VWD_dateverification">DATE VERIFICATION</label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="text" id="VWD_dateverification" name="VWD_dateverification" placeholder="Date Verification" class="form-control date-picker datemandtry" readonly/>
                            <label for="VWD_dateverification" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="VWD_officerincharge">PGAS OFFICER-IN-CHARGER(OIC)</label>
                    <div class="col-sm-4"><input type="text"  id="VWD_officerincharge" name="VWD_officerincharge" placeholder="OIC" class="form-control"/></div>
                    <div class="col-lg-offset-10" style="padding-left:20px;">
                        <input type="button" id="VWD_add" name="CC_search" class="btn btn-info" value="ADD" disabled/>
                        <input type="button" id="VWD_delte" name="CC_search" class="btn btn-info" value="DEL"/>
                    </div>
                </div>
                </div>
                <div id="additem" style="border-style: solid; border-width:thin; border-color:#d3d3d3; padding:10px;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="CC_itemno">ITEM NO</label>
                        <div class="col-sm-3"><input type="text"  id="CC_itemno" name="CC_itemno" placeholder="Item No" class="form-control"/></div>
                        <div class="col-sm-5"><input type="button" id="CC_search" name="CC_search" class="btn btn-info" value="SEARCH"/></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="CC_quantity">QUANTITY</label>
                        <div class="col-sm-3"><input type="text" id="CC_quantity" name="CC_quantity" placeholder="Quantity" class="form-control numonly"/></div>
                        <label class="col-sm-2 control-label" for="CC_cost">COST</label>
                        <div class="col-sm-2"><input type="text" id="CC_cost" name="CC_cost" placeholder="Cost" class="form-control amountonly"/></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="CC_descrption">DESCRIPTION</label>
                        <div class="col-sm-3"><input type="text" id="CC_descrption" name="CC_descrption" placeholder="Description" class="form-control"/></div>
                        <label class="col-sm-2 control-label" for="CC_unitprice">UNIT PRICE (RATE)</label>
                        <div class="col-sm-2"><input type="text" id="CC_unitprice" name="CC_unitprice" placeholder="Unitprice (Rate)" class="form-control amountonly"/></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="CC_remark">REMARK</label>
                        <div class="col-sm-3"><input type="text" id="CC_remark" name="CC_remark" placeholder="Remark" class="form-control"/></div>
                        <label class="col-sm-2 control-label" for="CC_amount">AMOUNT</label>
                        <div class="col-sm-2"><input type="text" id="CC_amount" name="CC_amount" placeholder="Amount" class="form-control amountonly"/></div>
                        <div class="col-sm-3">
                            <div class="checkbox">
                                <label><input type="checkbox" name="CC_internalused" id="CC_internalused" value="CC_internalused" class="">INTERNAL USED ONLY</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-offset-10">
                        <input type="button" id="CC_enter" name="CC_enter" class="btn btn-info" value="ENTER"/>
                        <input type="button" id="CC_cancel" name="CC_cancel" class="btn btn-info" value="CANCEL"/>
                    </div>
                </div>
                <div class="table-responsive" style="padding:10px;" >
                    <table class="table" style="border-style: solid; border-width:thin; border-color:#d3d3d3;" id="tbl_workdone">
                        <thead>
                        <tr class="active">
                            <th>ITEM NO</th>
                            <th>DESCRIPTION</th>
                            <th>QTY</th>
                            <th>UNIT</th>
                            <th>COST</th>
                            <th>RATE</th>
                            <th>AMOUNT</th>
                            <th>IS INTERNAL ONLY</th>
                            <th>REASON</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="CC_remark1">REMARK</label>
                    <div class="col-sm-5"><textarea rows="5" id="CC_remark1" name="CC_remark1" placeholder="Remark" class="form-control"></textarea></div>
                    <label class="col-sm-2 control-label" for="CC_total">TOTAL</label>
                    <div class="col-sm-3" style="padding-bottom: 15px;"><input type id="CC_total" name="CC_total" placeholder="Total" class="form-control"/></div>
                    <label class="col-sm-2 control-label" for="CC_totalamount">TOTAL AMOUNT</label>
                    <div class="col-sm-3"><input type id="CC_totalamount" name="CC_totalamount" placeholder="Total Amount" class="form-control"/></div>
                </div>

            </div>
        </div>
    </div>
</form>
</body>
