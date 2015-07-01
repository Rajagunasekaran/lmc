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
            var errormessage=[];
            var option="get_item_no";
            var itemdetail=[]; var item_no=[];
            var item_name='';
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
                        }
                        $('#ISS_lb_itemno').html(itemno);
                        $('.preloader').hide();
                    }
                    else{
                        $('.preloader').hide();
                        show_msgbox("INVENTORY SITE STOCK",errormessage[3],"error",false);
                    }
                }
            });
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
                            show_msgbox("INVENTORY SITE STOCK",errormessage[3],"error",false);
                        }
                    }
                });
            });

            function clearform(){
                $('#inv_sitestockdiv').hide();
                $('#ISS_btn').hide();
                $("#inv_sitestockdiv").find('input:text,textarea').val('');
                $("#inv_sitestockdiv").find('select').val('SELECT');
                $("#inv_sitestockdiv").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
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
                    <div id="inv_sitestockdiv">
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
                            <div class="col-lg-4"><input type="text" class="form-control" id="ISS_tb_itemname" name="ISS_tb_itemname" placeholder="Item Name" disabled></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">WEEKLY OPENING BALANCE <em>*</em></label>
                            <div class="col-lg-2"><input type="text" class="form-control" id="ISS_tb_contractno" name="ISS_tb_contractno" placeholder="Opening Balance"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">ADD NEW STOCK <em>*</em></label>
                            <div class="col-lg-2"><input type="text" class="form-control percentage" id="ISS_tb_percentlevel" name="ISS_tb_percentlevel" placeholder="Add New Stock"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">ITEM DRAWN <em>*</em></label>
                            <div class="col-lg-2"><input type="text" class="form-control amountonly" id="ISS_tb_costdiscount" name="ISS_tb_costdiscount" placeholder="Item Drawn"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">ITEM RETURNED <em>*</em></label>
                            <div class="col-lg-2"><input type="text" class="form-control amountonly" id="ISS_tb_costdiscount" name="ISS_tb_costdiscount" placeholder="Item Returned"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">SITE USED <em>*</em></label>
                            <div class="col-lg-2"><input type="text" class="form-control inetger" id="ISS_tb_costdiscount" name="ISS_tb_costdiscount" placeholder="Site Used"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">SITE STOCK <em>*</em></label>
                            <div class="col-lg-2"><input type="text" class="form-control" id="ISS_tb_infoupdate" name="ISS_tb_infoupdate" placeholder="Site Stock" ></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">SOLD <em>*</em></label>
                            <div class="col-lg-2"><input type="text" class="form-control" id="ISS_tb_infoupdate" name="ISS_tb_infoupdate" placeholder="Sold" ></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">BALANCE STOCK <em>*</em></label>
                            <div class="col-lg-2"><input type="text" class="form-control" id="ISS_tb_infoupdate" name="ISS_tb_infoupdate" placeholder="Balance Stock" ></div>
                        </div>
                        <div class="form-group" id="ISS_btn">
                            <div class="col-lg-offset-10">
                                <input type="button" class="btn btn-info" name="ISS_btn_save" id="ISS_btn_save" value="SAVE" disabled>
                                <input type="button" class="btn btn-info" name="ISS_btn_cancel" id="ISS_btn_cancel" value="CANCEL">
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="table-responsive" id="ISS_htmltable" >
                    <section>

                    </section>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>​
