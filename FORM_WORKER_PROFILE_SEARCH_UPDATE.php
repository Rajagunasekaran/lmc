<?php
include "NEW_MENU.php";
?>
<script>
    $(document).ready(function(){
        $('#DT_srch_filename').hide();
        $('#DT_srch_fromdte').hide();
        $('#DT_srch_todte').hide();
        $('#DT_srch_ctgry').hide();
        $('.input-group-addon').hide();
        $('#DT_searchbtn').hide();
        $('#DT_searchupdatebtn').hide();
        //DATE PICKER FUNCTION
        $(".date-picker").datepicker({
            dateFormat:"dd-mm-yy",
            changeYear: true,
            changeMonth: true
        });
        commondata()
//LOAD COMMON DATA
        var arrayfilename=[];
        var errmsg=[];
        var role;
        function commondata(){
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var value_array=JSON.parse(xmlhttp.responseText);
                $('.preloader').hide();
                 arrayfilename=value_array[0];
                 errmsg=value_array[1];
                role=value_array[2];
            }
        }
        var option="COMMON_DATA";
        xmlhttp.open("GET","DB_WORKER_PROFILE_SEARCH_UPDATE.php?option="+option);
        xmlhttp.send();
        }
//FUNCTION TO HIGHLIGHT SEARCH TEXT
        function TH_view_highlightSearchText() {
            $.ui.autocomplete.prototype._renderItem = function( ul, item) {
                var re = new RegExp(this.term, "i") ;
                var t = item.label.replace(re,"<span class=autotxt>" + this.term + "</span>");//higlight color,class shld be same as here
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + t + "</a>" )
                    .appendTo( ul );
            }
        };
//FUNCTION TO AUTOCOMPLETE SEARCH TEXT
        var TH_employeeid=[];
        var TH_employee_idflag;
        $("#DT_srch_filename").keypress(function(){
            TH_employee_idflag=0;
            $('#TH_lbl_heading').hide();
            $('#TH_tble_flextble').hide();
            $('#TH_div_flexdata_result').hide();
            $('#TH_lbl_nodata').hide();
            $('#TH_btn_pdf').hide();
            TH_view_highlightSearchText();
            $("#DT_srch_filename").autocomplete({
                source: arrayfilename,
                select:TH_AutoCompleteSelectHandler
            });
        });
// FUNCTION FOR AUTOCOMPLETESELECTHANDLER
        function TH_AutoCompleteSelectHandler(event, ui) {
            TH_employee_idflag=1;
            $('#TH_lbl_notmatch').hide();
            $('#TH_btn_search').removeAttr("disabled");
        }

        // CHANGE EVENT FOR EMPLOYEEID TEXT BOX
        $(document).on('change blur','#DT_srch_filename',function(){
            $('#TH_lbl_notmatch').hide();
            var filename_value=$('#DT_srch_filename').val();
            if(TH_employee_idflag==1 && filename_value!='')
            {
                $('#TH_lbl_notmatch').hide();
                $('#DT_searchbtn').removeAttr("disabled");
                $('#update_form').hide();
                $('#DT_searchbtn').show();
                $('#DT_searchupdatebtn').hide();
            }
            else if(filename_value=='')
            {
                $('#TH_lbl_notmatch').hide();
                $('#TH_btn_search').attr("disabled","disabled");
                $('#update_form').hide();
                $('#DT_searchbtn').show();
                $('#DT_searchupdatebtn').hide();
            }
            else
            {
//                $('#TH_lbl_notmatch').text(TH_err_msg[0]).show();
                $('#DT_searchbtn').removeAttr("disabled");
                $('#TH_btn_search').attr("disabled","disabled");
                $('#update_form').hide();
                $('#DT_searchbtn').show();
                $('#DT_searchupdatebtn').hide();
            }
        });

//CHANGE EVENT FOR SEARCH BY LIST BOX
        $('#DT_srch_by').change(function(){
            var listvalue=$('#DT_srch_by').val();
            if(listvalue=='SELECT')
            {
                $('#srch_filename').hide();
                $('#DT_srch_filename').val("").hide();
                $('#srch_startdate').hide();
                $('#DT_srch_fromdte').val("").hide();
                $('#srch_todate').hide();
                $('#DT_srch_todte').val('').hide();
                $('#DT_div_tablecontainer').hide();
                $('.input-group-addon').hide();
                $('#update_form').hide();
                $('#DT_searchbtn').hide();
                $('#DT_searchupdatebtn').hide();
            }
            else if(listvalue=='FILENAME')
            {
                $('#srch_filename').show();
                $('#DT_srch_filename').val('').show();
                $('#srch_startdate').hide();
                $('#DT_srch_fromdte').val('').hide();
                $('#srch_todate').hide();
                $('#DT_srch_todte').val('').hide();
                $('#DT_div_tablecontainer').hide();
                $('.input-group-addon').hide();
                $('#update_form').hide();
                $('#DT_searchbtn').show().attr("disabled","disabled");
                $('#DT_searchupdatebtn').hide();

            }
            else if(listvalue=='DATERANGE')
            {
                $('#srch_filename').hide();
                $('#DT_srch_filename').val("").hide();
                $('#srch_startdate').show();
                $('#DT_srch_fromdte').val('').show();
                $('#srch_todate').show();
                $('#DT_srch_todte').val('').show();
                $('#DT_div_tablecontainer').hide();
                $('.input-group-addon').show();
                $('#update_form').hide();
                $('#DT_searchbtn').show().attr("disabled","disabled");
                $('#DT_searchupdatebtn').hide();

            }


        });

        $(document).on('change','.srchbtnenable ',function(){
            $('#DT_div_tablecontainer').hide();
            $('#DT_searchupdatebtn').hide();
            $('#update_form').hide();
            var startdate=$('#DT_srch_fromdte').val();
            var enddate=$('#DT_srch_todte').val();
            if((startdate!='')&&(enddate!='')){
                $('#DT_searchbtn').removeAttr("disabled");


            }


        });
//START DATE VALIDATION
        $(document).on('change','#DT_srch_fromdte',function(){
            var DT_startdate = $('#DT_srch_fromdte').datepicker('getDate');
            var date = new Date( Date.parse( DT_startdate ));
            date.setDate( date.getDate()  );
            var DT_todate = date.toDateString();
            DT_todate = new Date( Date.parse( DT_startdate ));
            $('#DT_srch_todte').datepicker("option","minDate",DT_todate);
        });

//CLICK EVENT FOR SEARCH BUTTON
        var values_array=[];
        $('#DT_searchbtn').click(function(){
            $('.preloader').show();
            showTable();

        });
        function showTable(){
            $('#exsistingfiletable').empty();
            var filename=$('#DT_srch_filename').val();
            var startdate=$('#DT_srch_fromdte').val();
            var enddate=$('#DT_srch_todte').val();
            var search_category=$('#DT_srch_by').val();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var value_array=JSON.parse(xmlhttp.responseText);
                   values_array=value_array[0];

                    if(values_array.length!=0){
                    var USU_table_header='<table id="WP_SRC_table" border="1"  cellspacing="0" class="srcresult" ><thead  bgcolor="#6495ed" style="color:white"><tr><th></th><th nowrap>EMPLOYEE NAME</th><th style="width:10px;"  class="uk-date-column">DATE</th><th>DOCUMENT FILE NAME</th><th>USERSTAMP</th><th style="width:150px;" class="uk-timestp-column" nowrap>TIMESTAMP</th></tr></thead><tbody>'
                    for(var j=0;j<values_array.length;j++){
                        var WP_SRC_empname=values_array[j].empname;
                        var WP_SRC_date=values_array[j].date;
                        var WP_SRC_filename=values_array[j].ET_SRC_filename;
                        var WP_rowid=values_array[j].WP_rowid;
                        var WP_userstamp=values_array[j].Userstamp;
                            var WP_timestamp=values_array[j].timestamp;


                            USU_table_header+='<tr> <td><input type="radio" name="WP_rd_flxtbl" class="WP_class_radio" id='+WP_rowid+'  value='+WP_rowid+'></td><td nowrap>'+WP_SRC_empname+'</td><td align="center" nowrap>'+WP_SRC_date+'</td><td >'+WP_SRC_filename+'</td><td>'+WP_userstamp+'</td><td>'+WP_timestamp+'</td></tr>';

                    }
                    USU_table_header+='</tbody></table>';
                    $('section').html(USU_table_header);
                    $('#WP_SRC_table').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers",
                        "aoColumnDefs" : [
                            { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                    });
                      $('#DT_div_tablecontainer').show();

                }
                    else{

                        show_msgbox("DOCUMENT MANAGEMENT SEARCH UPDATE",'NO DATA AVAILABLE',"success",false)
                        $('#DT_div_tablecontainer').hide();

                    }



                }
            }
            var option="search_data";
            xmlhttp.open("GET","DB_WORKER_PROFILE_SEARCH_UPDATE.php?option="+option+"&filename="+filename+"&startdate="+startdate+"&enddate="+enddate);
            xmlhttp.send();

        }

        $(document).on('click','.WP_class_radio',function(){
            $('#update_form').hide();
            $('#DT_searchupdatebtn').show();
            $("#DT_searchupdatebtn").removeAttr("disabled","disabled").show();
            $('#WP_btn_submitbutton').attr('disabled','disabled');
            $('#temptextbox').val('');
        });
var filenameinarray=[];
        $(document).on('click','#DT_searchupdatebtn',function(){
            $('#exsistingfiletable').empty();
            $('#filetableuploads').empty();
            $('#attachafile').text('Attach a file');
            $("html, body").animate({ scrollTop: $(document).height() }, "fast");
            $('#update_form').show();

            $('#WP_btn_submitbutton').attr('disabled','disabled');
            var WP_SRC_UPD_idradiovalue=$('input:radio[name=WP_rd_flxtbl]:checked').attr('id');
            for(var j=0;j<values_array.length;j++){
                if(values_array[j].WP_rowid==WP_SRC_UPD_idradiovalue)
                {

                $("#WP_lb_selectempname").val(values_array[j].empname) ;
                    $('#WP_tb_date').val(values_array[j].date);
                    var WP_folder_name=values_array[j].folder_id;
                    var filenamein_array=values_array[j].ET_SRC_filename;
                }
            }
             filenameinarray=filenamein_array.split('/');

            for(var j=0;j<filenameinarray.length;j++){
                            var name=WP_folder_name+"/"+filenameinarray[j];
                var file_count='filecount'+j;
                if(role=='ADMIN'){
                            var appendfile=' <div class="col-sm-offset-2 col-sm-10" id='+file_count+'><a href="download.php?filename='+name+'" class="links">'+filenameinarray[j]+'</a><input type="button" id="Del" class="submit_enable"  value="X" style="background-color:red;color:white;font-size:10;font-weight: bold;"/></div></br>';
                }
                else{
                    var appendfile=' <div class="col-sm-offset-2 col-sm-10" id='+file_count+'><a href="download.php?filename='+name+'" class="links">'+filenameinarray[j]+'</a></div></br>';
                    $('#attachprompt').hide();
                    $("#WP_btn_submitbutton").hide();

                }

                            $('#exsistingfiletable').append(appendfile);
                    }



        });
        var removedfilename;
        //CLICK EVENT DELETE BUTTON
        $(document).on("click", "#Del", function (){
            $(this).closest("div").remove();
            var divcount=$('#exsistingfiletable > div').length;
            var oldfilename;
            if(divcount>=0){
                for(var i=0;i<=divcount;i++)
                {
                    var div_value=$('#filecount'+i).text();
                    if(div_value!='')
                    {
                        if(i==0)
                        {
                            oldfilename=div_value;
                        }
                        else
                        {
                            oldfilename =oldfilename+'/'+div_value
                        }
                    }

                }
            }
            if(oldfilename==undefined){
                removedfilename='';
            }
            else{
                removedfilename=oldfilename.replace("undefined/",'');
            }
        });
        //reomve file upload row
        $(document).on('click', 'button.removebutton', function () {
            upload_count=upload_count-1;
            $(this).closest('div').remove();
            var rowCount = $('#filetableuploads > div').length;
            if(rowCount!=0)
            {
                $('#attachafile').text('Attach another file');
                $('#WP_btn_submitbutton').removeAttr('disabled');
            }
            else
            {
                $('#attachafile').text('Attach a file');
                $('#WP_btn_submitbutton').attr('disabled','disabled');
            }
            return false;
        });
        //file extension validation
        $(document).on("change",'.fileextensionchk', function (){
            for(var i=1;i<25;i++)
            {
                var data= $('#upload_filename'+i).val();
                var datasplit=data.split('.');
                var ext=datasplit[1].toUpperCase();
                if(ext=='PDF'|| ext=='JPG'|| ext=='PNG' || ext=='JPEG' || data==undefined || data=="")
                {
//                    loginbuttonvalidation();
                }
                else
                {
                    show_msgbox("WORKER PROFILE ENTRY",errmsg[0],"error",false);
                    reset_field($('#upload_filename'+i));
                }
            }
        });
        //file upload reset
        function reset_field(e) {
            e.wrap('<form>').parent('form').trigger('reset');
            e.unwrap();
        }
        //add file upload row
        $(document).on("click",'#attachprompt', function (){
            var tablerowCount = $('#filetableuploads > div').length;
            if(tablerowCount==0)
            {
                var row_count=parseInt(tablerowCount)+1;
                var uploadfileid="upload_filename"+row_count;
                $('#temptextbox').val(row_count);
            }
            else
            {
                var rowvalue=$('#temptextbox').val();
                var rowcount=parseInt(rowvalue)+1;
                uploadfileid="upload_filename"+rowcount;
                $('#temptextbox').val(rowcount);
            }

            var appendfile='<div class="col-sm-offset-2 col-sm-5"><label class=""><input type="file" style="max-width:250px " class="fileextensionchk form-control submit_enable" id='+uploadfileid+' name='+uploadfileid+'></label><label class="inline" ><button  class="removebutton" value="-" title="Remove this row" style="background-color:red;color:white;font-size:10;font-weight: bold;"></button></label></div>';
            $('#filetableuploads').append(appendfile);
            upload_count++;
            var rowCount =$("#filetableuploads > div").length// $('#filetableuploads tr').length;//
            if(rowCount!=0)
            {
                $('#attachafile').text('Attach another file');
            }
            else
            {
                $('#attachafile').text('Attach a file');
//                $('#WP_btn_submitbutton').attr('disabled','disabled');
            }
        });

        $(document).on("change blur click",'.submit_enable',function(){

            var empname=$('#WP_lb_selectempname').val();
            var date=$('#WP_tb_date').val();
           var ex_div=$('#exsistingfiletable > div').length;
            var fileuploadCount = $('#filetableuploads > div').length;
            if(ex_div!=0 || fileuploadCount!=0){
                $("#WP_btn_submitbutton").removeAttr("disabled");
            }
            else{
                $("#WP_btn_submitbutton").attr("disabled", "disabled");
            }
        });
        var upload_count=0;

        $(document).on("click",'#WP_btn_submitbutton', function (){
            $('.preloader').show();
            var formElement = document.getElementById("WP_searchupdate");
            var radioid=$("input[name='WP_rd_flxtbl']:checked").val();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var values=JSON.parse(xmlhttp.responseText);
                    if(values==1){

                        show_msgbox("DOCUMENT MANAGEMENT SEARCH UPDATE",errmsg[1],"success",false)
                        $('#WP_lb_selectempname').prop('selectedIndex',0);
                        $('#WP_tb_date').val('');
                        $('#update_form').hide();
                        $('#DT_searchupdatebtn').hide();
                        $("#filetableuploads").empty();
                        $('#attachafile').text('Attach a file');
                        $('#exsistingfiletable').empty();
                        $("#WP_btn_submitbutton").attr("disabled", "disabled");
                        upload_count=0;
                        showTable();
                        $('#temptextbox').val('');
                        commondata()
                    }
                    else{

                        show_msgbox("DOCUMENT MANAGEMENT SEARCH UPDATE",errmsg[2],"error",false)

                    }

                }

            }
            var option="UPDATE";
            xmlhttp.open("POST","DB_WORKER_PROFILE_SEARCH_UPDATE.php?option="+option+"&upload_count="+finalrow+"&id="+radioid+"&oldfilename="+removedfilename);
            xmlhttp.send(new FormData(formElement));
        });



        var finalrow;
        // button validation
        $(document).on('change blur','#WP_searchupdate',function(){
            var rowCount=$("#filetableuploads > div").length;
            finalrow=$('#temptextbox').val();
            var count=0;
            for(var j=1;j<=finalrow;j++)
            {
                var data= $('#upload_filename'+j).val();
                if(data!='' && data!=undefined && data!=null)
                {
                    count++;
                }
            }
            if(rowCount==count)
            {
                $('#WP_btn_submitbutton').removeAttr('disabled');
            }
            else
            {
                $('#WP_btn_submitbutton').attr('disabled','disabled');
            }

        });



    });
</script>



</head>
<body>

    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">DOCUMENT MANAGEMENT SEARCH UPDATE</h2>
            </div>
            <form id="WP_searchupdate" name="WP_searchupdate" class="form-horizontal">
            <div class="panel-body">
                <div class="form-group">
                    <label id="srch_by" class="col-sm-2">SEARCH BY<em>*</em></label>
                    <div class="col-sm-3">
                        <select class="form-control" id="DT_srch_by" name="DT_srch_by" >
                            <option>SELECT</option>
                            <option>FILENAME</option>
                            <option>DATERANGE</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label id="srch_filename" class="col-sm-2" hidden>FILE NAME<em>*</em></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control srchbtnenable" id="DT_srch_filename" name="DT_srch_filename" hidden />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2" id="srch_startdate" hidden>START DATE</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input id="DT_srch_fromdte" name="DT_srch_fromdte" type="text" class="date-picker datemandtry srchbtnenable form-control" placeholder="Start Date" hidden/>
                            <label for="DT_srch_fromdte" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar" hidden></span></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2" id="srch_todate" hidden>END DATE</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input id="DT_srch_todte" name="DT_srch_todte" type="text" class="date-picker datemandtry srchbtnenable form-control" placeholder="End Date" hidden/>
                            <label for="DT_srch_todte" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar" hidden></span></label>
                        </div>
                    </div>
                </div>

<!--                <div class="col-md-2">-->
<!--                    <div class="">-->
                        <button type="button" id="DT_searchbtn" class="btn btn-info" disabled>SEARCH</button>
<!--                    </div>-->
<!--                </div>-->

            <div class="table-responsive" id="DT_div_tablecontainer" hidden>
                <section>
                </section>
            </div>
            <button type="button" id="DT_searchupdatebtn" class="btn btn-info" disabled hidden>SEARCH</button>
            <div id="update_form" hidden>

                <div class="form-group">
                    <label id="WP_lbl_selectempname" class="col-sm-2" >EMPLOYEE NAME<em>*</em></label>
                    <div class="col-sm-3"><input type="text" id='WP_lb_selectempname' name="WP_lb_selectempname" title="LOGIN ID" maxlength="40" placeholder="Employee Name" class="form-control submit_enable" readonly />

                        </div>
                    </div>


                <div class="form-group">
                    <label id="URSRC_lbl_date" class="col-sm-2">DATE<em>*</em></label>
                    <div class="col-sm-10"><input type="text" name="WP_tb_date" placeholder="Date" id="WP_tb_date" class="date-picker submit_enable datemandtry form-control" style="width:110px;" hidden readonly /></div>
                </div>

                <div>
                    <div id="exsistingfiletable"></div>
                    <div><input type="hidden" id="temptextbox" name="temptextbox"></div>
                    <div ID="filetableuploads" class="form-group row">

                    </div>

                </div>
                <div>
                    <div id="attachprompt" class="col-sm-offset-2 col-sm-2"><img width="15" height="15" src="image/paperclip.gif" border="0">
                        <a href="javascript:_addAttachmentFields('attachmentarea')" id="attachafile">Attach a file</a>
                    </div>
                </div>
                <div class="col-sm-offset-10 col-sm-2">
                <input class="btn  btn-info" type="button"  id="WP_btn_submitbutton" name="SAVE" value="UPLOAD" disabled />
                 </div>
            </div>
                </div>





</form>
</div>
</div>
</body>
</html>