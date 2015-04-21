<?php
include "NEW_MENU.php";
?>
<script>
var DTS_upload_count=0;
$(document).ready(function(){
    $('#DTS_srch_filename').hide();
    $('#DTS_srch_fromdte').hide();
    $('#DTS_srch_todte').hide();
    $('#DTS_srch_ctgry').hide();
    $('#DTS_searchbtn').hide();
    $('#DTS_radiosearchbtn').hide();
    $('.addon').hide();
    $('#DTS_srch_upt').hide();
    commondata()
    //DATE PICKER FUNCTION
    $(".date-picker").datepicker({
        dateFormat:"dd-mm-yy",
        changeYear: true,
        changeMonDT: true
    });
    var arrayfilename=[];
    var error_message=[];
    var rolename;
//LOAD COMMON DATA
    function commondata(){
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var value_array=JSON.parse(xmlhttp.responseText);
                $('.preloader').hide();
                arrayfilename=value_array[0];
                var category=value_array[1];
                error_message=value_array[2];
                rolename=value_array[3];
                //CATEFORY
                var ctegry='<option>SELECT</option>';
                for (var i=0;i<category.length;i++) {
                    ctegry += '<option value="' + category[i][0] + '">' + category[i][0] + '</option>';
                }
                $('#DTS_srch_ctgry').html(ctegry);
//                $('#DTS_doc_lb_category').html(ctegry)
            }
        }
        var option="common_data";
        xmlhttp.open("GET","DB_DOCUMENTATION_SEARCH_UPDATE.php?option="+option);
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
    var TH_employee_idflag;
    $("#DTS_srch_filename").keypress(function(){
        TH_employee_idflag=0;
        TH_view_highlightSearchText();
        $("#DTS_srch_filename").autocomplete({
            source: arrayfilename,
            select: TH_AutoCompleteSelectHandler
        });
    });
// FUNCTION FOR AUTOCOMPLETESELECTHANDLER
    function TH_AutoCompleteSelectHandler(event,ui) {
        TH_employee_idflag=1;
    }
//CHANGE EVENT FOR SEARCH BY LIST BOX
    $('#DTS_srch_by').change(function(){
        var listvalue=$('#DTS_srch_by').val();
        $('#form_search').hide();
        $('#DTS_radiosearchbtn').hide();
        $('#DTS_exsistingfiletable').empty();
        $('#DTS_filetableuploads').empty();
        $('#DTS_attachafile').text('Attach a file');
        if(listvalue=='SELECT')
        {
            $('#srch_filename').hide();
            $('#DTS_srch_filename').val('').hide();
            $('#srch_startdate').hide();
            $('#DTS_srch_fromdte').val('').hide();
            $('#srch_todate').hide();
            $('#DTS_srch_todte').val('').hide();
            $('#srch_category').hide();
            $('#DTS_srch_ctgry').hide();
            $('.addon').hide();
            $('#DTS_searchbtn').hide();
            $('#DTS_div_tablecontainer').hide();
        }
        else if(listvalue=='FILE NAME')
        {
            $('#srch_filename').show();
            $('#DTS_srch_filename').val('').show();
            $('#srch_startdate').hide();
            $('#DTS_srch_fromdte').val('').hide();
            $('#srch_todate').hide();
            $('#DTS_srch_todte').val('').hide();
            $('#srch_category').hide();
            $('#DTS_srch_ctgry').hide();
            $('.addon').hide();
            $('#DTS_searchbtn').attr('disabled','disabled').show();
            $('#DTS_div_tablecontainer').hide();
        }
        else if(listvalue=='DATE RANGE')
        {
            $('#srch_filename').hide();
            $('#DTS_srch_filename').val('').hide();
            $('#srch_startdate').show();
            $('#DTS_srch_fromdte').val('').show();
            $('#srch_todate').show();
            $('#DTS_srch_todte').val('').show();
            $('#srch_category').hide();
            $('#DTS_srch_ctgry').hide();
            $('.addon').show();
            $('#DTS_searchbtn').attr('disabled','disabled').show();
            $('#DTS_div_tablecontainer').hide();
        }
        else if(listvalue=='CATEGORY')
        {
            $('#srch_filename').hide();
            $('#DTS_srch_filename').val('').hide();
            $('#srch_startdate').hide();
            $('#DTS_srch_fromdte').val('').hide();
            $('#srch_todate').hide();
            $('#DTS_srch_todte').val('').hide();
            $('#srch_category').show();
            $('#DTS_srch_ctgry').show();
            $('.addon').hide();
            $('#DTS_searchbtn').attr('disabled','disabled').show();
            $('#DTS_div_tablecontainer').hide();
        }
    });
//START DATE VALIDATION
    $(document).on('change','#DTS_srch_fromdte',function(){
        var DTS_startdate = $('#DTS_srch_fromdte').datepicker('getDate');
        var date = new Date( Date.parse( DTS_startdate ));
        date.setDate( date.getDate()  );
        var DTS_todate = date.toDateString();
        DTS_todate = new Date( Date.parse( DTS_startdate ));
        $('#DTS_srch_todte').datepicker("option","minDate",DTS_todate);
    });

// CHANGE EVENT FOR FILENAME TEXT  BOX
    $(document).on("change blur",'#DTS_srch_filename', function (){
        $('#DTS_div_tablecontainer').hide();
        $('#form_search').hide();
        $('#DTS_exsistingfiletable').empty();
        $('#DTS_filetableuploads').empty();
        $('#DTS_radiosearchbtn').hide();
        if($('#DTS_srch_filename').val()!='')
        {
            $('#DTS_searchbtn').removeAttr('disabled');
        }
        else
        {
            $('#DTS_searchbtn').attr('disabled','disabled');
        }
    });

//CHANGE EVENT FOR START & END DATE
    $('.srchbtnenable').change(function(){
        $('#DTS_div_tablecontainer').hide();
        $('#form_search').hide();
        $('#DTS_exsistingfiletable').empty();
        $('#DTS_filetableuploads').empty();
        $('#DTS_radiosearchbtn').hide();
        var startdate=$('#DTS_srch_fromdte').val();
        var enddate=$('#DTS_srch_todte').val();
        if(startdate!='' && enddate!='')
        {
            $('#DTS_searchbtn').removeAttr('disabled');
        }
        else{
            $('#DTS_searchbtn').attr('disabled','disabled');
        }
    });

//CHANGE EVENT FOR CATEGORY LIST BOX
    $('#DTS_srch_ctgry').change(function(){
        $('#DTS_div_tablecontainer').hide();
        $('#DTS_exsistingfiletable').empty();
        $('#DTS_filetableuploads').empty();
        $('#form_search').hide();
        $('#DTS_radiosearchbtn').hide();
        if($('#DTS_srch_ctgry').val()!='SELECT')
        {
            $('#DTS_searchbtn').removeAttr('disabled');
        }
        else
        {
            $('#DTS_searchbtn').attr('disabled','disabled');
        }
    });
//CLICK EVENT FOR SEARCH BUTTON
    $('#DTS_searchbtn').click(function(){
        $('.preloader').show();
        DTS_table();
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
    });
    var value_array=[];
    function DTS_table(){
        var filename=$('#DTS_srch_filename').val();
        var startdate=$('#DTS_srch_fromdte').val();
        var enddate=$('#DTS_srch_todte').val();
        var category=$('#DTS_srch_ctgry').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                value_array=JSON.parse(xmlhttp.responseText);
                if(value_array!=null){
                    var DTS_UPD_table_header='<table id="DTS_tbl_htmltable" border="1"  cellspacing="0" class="srcresult"><thead  bgcolor="#6495ed" style="color:white"><tr><th></th><th>CATEGORY</th><th>DATE</th><th style="text-align:center;">FILENAME</th><th>USERSTAMP</th><th>TIMESTAMP</th></tr></thead><tbody>'
                    for(var j=0;j<value_array.length;j++){
                        var id=value_array[j].id;
                        var category=value_array[j].categroy;
                        var date=value_array[j].date;
                        var filename=value_array[j].filename;
                        var userstamp=value_array[j].userstamp;
                        var timestamp=value_array[j].timestamp;
                        DTS_UPD_table_header+='<tr><td><input type="radio" name="DTS_UPD_rd_flxtbl" class="DTS_UPD_class_radio" id='+id+'  value='+id+'></td><td>'+category+'</td><td nowrap>'+date+'</td><td> '+filename+'</td><td >'+userstamp+'</td><td nowrap>'+timestamp+'</td></tr>';
                    }
                    DTS_UPD_table_header+='</tbody></table>';
                    $('section').html(DTS_UPD_table_header);
                    $('#DTS_tbl_htmltable').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers"
                    });
                    $('#DTS_searchbtn').attr('disabled','disabled');
                    $('#DTS_div_tablecontainer').show();
                }
                else
                {
                    show_msgbox("DOCUMENTATION SEARCH",error_message[0],"error",false)
                    $('#DTS_div_tablecontainer').hide();
                    $('#DTS_radiosearchbtn').hide();
                }
            }
        }
        var option="search_data";
        xmlhttp.open("GET","DB_DOCUMENTATION_SEARCH_UPDATE.php?option="+option+"&filename="+filename+"&startdate="+startdate+"&enddate="+enddate+"&category="+category);
        xmlhttp.send();
    }
//CLICK EVENT FOR  RADIO BUTTON
    $(document).on('click','.DTS_UPD_class_radio',function(){
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        $('#DTS_exsistingfiletable').empty();
        $('#DTS_filetableuploads').empty();
        $('#DTS_attachafile').text('Attach a file');
        $('#DTS_radiosearchbtn').removeAttr('disabled').show();
        $('#DTS_docupload').attr('disabled','disabled');
        $('#DTS_srch_upt').hide();
        $('#form_search').hide();
        $('#temptextbox').val('');
    });
//CLICK EVENT FOR SEARCH BUTTON
    var DTS_idradiovalue;
    $(document).on('click','#DTS_radiosearchbtn',function(){
        $('#form_search').show();
        $('#DTS_exsistingfiletable').empty();
        $('#DTS_filetableuploads').empty();
        $('#DTS_attachafile').text('Attach a file');
        $('#DTS_radiosearchbtn').attr('disabled','disabled');
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        DTS_idradiovalue=$('input:radio[name=DTS_UPD_rd_flxtbl]:checked').attr('id');
        for(var j=0;j<value_array.length;j++){
            var id=value_array[j].id;
            if(id==DTS_idradiovalue)
            {
                var category=value_array[j].categroy;
                var date=value_array[j].date;
                var docfilename=value_array[j].filename;
                $('#DTS_doc_lb_category').val(category);
                $('#DTS_doc_date').val(date);
            }
        }
        var filenameinarray=docfilename.split('/');
        for(var j=0;j<filenameinarray.length;j++){
            var name=filenameinarray[j];
            var filecount="filecount"+j;
            if(rolename=='ADMIN' || rolename=='SUPER ADMIN')
            {
                var appendfile=' <div class="col-sm-offset-2 col-sm-10" style="padding-bottom: 5px" id='+filecount+'><a href="downloadpdf.php?filename='+name+'" class="links">'+filenameinarray[j]+'</a>&nbsp;&nbsp;<input type="button" id="ibtnDel"  class="updatebtn" value="X" style="background-color:red;color:white;font-size:10;font-weight: bold;"/></div>';
            }
            else
            {
                var appendfile=' <div class="col-sm-offset-2 col-sm-10" style="padding-bottom: 5px" id='+filecount+'><a href="downloadpdf.php?filename='+name+'" class="links">'+filenameinarray[j]+'</a></div>';
                $('#DTS_attachprompt').hide();
                $('#DTS_docupload').hide();
            }
            $('#DTS_exsistingfiletable').append(appendfile);
        }
    });
    var removedfilename;
    //CLICK EVENT DELETE BUTTON
    $(document).on("click", "#ibtnDel", function (){
        $(this).closest("div").remove();
        var divcount=$('#DTS_exsistingfiletable > div').length;
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
//CLICK EVENT FOR  REMOVEBUTTON
    $(document).on('click', 'button.removebutton', function () {
        $(this).closest('div').remove();
        DTS_upload_count=DTS_upload_count-1;
        var rowCount = $('#DTS_filetableuploads > div').length;
        if(rowCount!=0)
        {
            $('#DTS_attachafile').text('Attach another file');
            $('#DTS_docupload').removeAttr('disabled');
        }
        else
        {
            $('#DTS_attachafile').text('Attach a file');
            $('#DTS_docupload').attr('disabled','disabled');
        }
        return false;
    });
//file extension validation
    $(document).on("change",'.fileextensionchk', function (){
        for(var i=1;i<25;i++)
        {
            var data= $('#DTS_upload_filename'+i).val();
            var datasplit=data.split('.');
            var old_loginid=$('#URSRC_lb_selectloginid').val();
            var ext=datasplit[1].toUpperCase();
            if(ext=='PDF'|| ext=='JPG'|| ext=='PNG' || ext=='JPEG' || ext=='GIF' || data==undefined || data==""){
            }
            else{
                show_msgbox("DOCUMENTATION SEARCH",error_message[1],"error",false)
                reset_field($('#DTS_upload_filename'+i));
            }

        }
    });
//file upload reset
    function reset_field(e) {
        e.wrap('<form>').parent('form').trigger('reset');
        e.unwrap();
    }
//add file upload row
    $(document).on("click",'#DTS_attachprompt', function (){
        var tablerowCount = $('#DTS_filetableuploads > div').length;
        if(tablerowCount==0)
        {
            var row_count=parseInt(tablerowCount)+1;
            var uploadfileid="DTS_upload_filename"+row_count;
            $('#temptextbox').val(row_count);
        }
        else
        {
            var rowvalue=$('#temptextbox').val();
            var rowcount=parseInt(rowvalue)+1;
            uploadfileid="DTS_upload_filename"+rowcount;
            $('#temptextbox').val(rowcount);
        }
        var appendfile='<div class="col-sm-offset-2 col-sm-10"><label class="inline"><input type="file" style="max-width:250px " class="fileextensionchk form-control" id='+uploadfileid+' name='+uploadfileid+'></label><label class="inline" ><button  class="removebutton" value="-" title="Remove this row" style="background-color:red;color:white;font-size:10;font-weight: bold;"></button></label></div>';
        $('#DTS_filetableuploads').append(appendfile);
        var rowCount =$("#DTS_filetableuploads > div").length
        DTS_upload_count++;
        if(rowCount!=0)
        {
            $('#DTS_attachafile').text('Attach another file');
        }
        else
        {
            $('#DTS_attachafile').text('Attach a file');
        }
    });

// button validation
    var finalrow;
    $(document).on('change blur','#form_search',function(){
        var rowCount=$("#DTS_filetableuploads > div").length;
        finalrow=$('#temptextbox').val();
        var count=0;
        for(var j=1;j<=finalrow;j++)
        {
            var data= $('#DTS_upload_filename'+j).val();
            if(data!='' && data!=undefined && data!=null)
            {
                count++;
            }
        }
        if(rowCount==count)
        {
            $('#DTS_docupload').removeAttr('disabled');
        }
        else
        {
            $('#DTS_docupload').attr('disabled','disabled');
        }
    });

    // REMOVE BUTTON VALIDATION
    $(document).on('click','.updatebtn',function(){
        var existdivcount =$("#DTS_exsistingfiletable > div").length;
        var fileuploadCount = $('#DTS_filetableuploads > div').length;
        if(existdivcount!=0 || fileuploadCount!=0)
        {
            $('#DTS_docupload').removeAttr('disabled');
        }
        else{
            $('#DTS_docupload').attr('disabled','disabled');
        }
    });
//CHANGE EVENT FOR CATEGORY LIST BOX & DATE
    $('.existctegry').change(function(){
        var categoryname=$('#DTS_doc_lb_category').val();
        var date=$('#DTS_doc_date').val();
        if(categoryname!='SELECT' && date!='')
        {
            $('.preloader').show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var row=xmlhttp.responseText;
                    if(row!=0)
                    {
                        var msg=error_message[4].toString().replace("[CATEGORY]",categoryname);
                        var errmsg=msg.toString().replace("[DATE]",date);
                        show_msgbox("DOCUMENTATION SEARCH",errmsg,"error",false)
                        $('#doc_lb_category').val('SELECT').show();
                        $('#doc_date').val('').show();

                    }
                }
            }
        }
        var option="category_exists";
        xmlhttp.open("POST","DB_DOCUMENTATION_SEARCH_UPDATE.php?option="+option+"&categoryname="+categoryname+"&date="+date);
        xmlhttp.send();
    });
//CLICK EVENT FORM BUTTON
    $('#DTS_docupload').click(function(){
        $('.preloader').show();
        var formElement=new FormData(document.getElementById("documentsearch"));
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var filename=xmlhttp.responseText;
                if(filename==1){
                    show_msgbox("DOCUMENTATION SEARCH",error_message[2],"success",false);
                    removedfilename='undefined';
                    $('#form_search').hide();
                    DTS_table();
                    DTS_upload_count=0;
                    $('#temptextbox').val('');
                    commondata();
                }
                else if(filename==0)
                {
                    show_msgbox("DOCUMENTATION SEARCH",error_message[3],"error",false)
                }
                else
                {
                    show_msgbox("DOCUMENTATION SEARCH",filename,"error",false)
                }
            }
        }
        var option="update";
        xmlhttp.open("POST","DB_DOCUMENTATION_SEARCH_UPDATE.php?option="+option+"&DTS_upload_count="+finalrow+"&rowid="+DTS_idradiovalue+"&removedfilename="+removedfilename);
        xmlhttp.send(formElement);
    });
});
</script>
</head>
<body>
<form id="documentsearch" class="form-horizontal">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">DOCUMENTATION SEARCH</h2>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label id="srch_by" class="col-sm-2">SEARCH BY<em>*</em></label>
                    <div class="col-sm-3">
                        <select class="form-control" id="DTS_srch_by" name="DTS_srch_by" >
                            <option>SELECT</option>
                            <option>FILE NAME</option>
                            <option>DATE RANGE</option>
                            <option>CATEGORY</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" id="DTS_filename">
                    <label id="srch_filename" class="col-sm-2" hidden>FILE NAME<em>*</em></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="DTS_srch_filename" name="DTS_srch_filename" hidden >
                    </div>
                </div>
                <div id="DTS_date">
                    <div class="form-group">
                        <label class="col-sm-2" id="srch_startdate" hidden>START DATE</label>
                        <div class="col-sm-3">
                            <div class="input-group addon">
                                <input id="DTS_srch_fromdte" name="DTS_srch_fromdte" type="text" class="date-picker datemandtry srchbtnenable form-control" placeholder="Start Date" hidden/>
                                <label for="DTS_srch_fromdte" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar" hidden></span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2" id="srch_todate" hidden>END DATE</label>
                        <div class="col-sm-3">
                            <div class="input-group addon">
                                <input id="DTS_srch_todte" name="DTS_srch_todte" type="text" class="date-picker datemandtry srchbtnenable form-control" placeholder="End Date" hidden/>
                                <label for="DTS_srch_todte" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar" hidden></span></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="DTS_category">
                    <label class="col-sm-2" id="srch_category" hidden>CATEGORY</label>
                    <div class="col-sm-3">
                        <select class="form-control" id="DTS_srch_ctgry" name="DTS_srch_ctgry" hidden>
                        </select>
                    </div>
                </div>

                <div>
                    <button type="button" id="DTS_searchbtn" class="btn btn-info" disabled>SEARCH</button>
                </div>
                <br>
                <div class="table-responsive" id="DTS_div_tablecontainer" hidden>
                    <section>
                    </section>
                </div>
                <div>
                    <button type="button" id="DTS_radiosearchbtn" class="btn btn-info" disabled hidden>SEARCH</button>
                </div>
                <br>
                <div id="form_search" hidden>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">ATTACHMENTS</h3>
                        </div>
                        <div class="panel-body">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2">CATEGORY<em>*</em></label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control existctegry" id="DTS_doc_lb_category" name="DTS_doc_lb_category" readonly />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2">DATE<em>*</em></label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control  datemandtry existctegry" id="DTS_doc_date" name="DTS_doc_date" placeholder="Date" readonly>
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        <div ID="DTS_exsistingfiletable" class="form-group row">

                                        </div>
                                        <div><input type="hidden" id="temptextbox" name="temptextbox"></div>
                                        <div ID="DTS_filetableuploads" class="form-group row">

                                        </div>
                                    </div>
                                    <div>
                                        <div id="DTS_attachprompt" class="col-sm-offset-2 col-sm-10"><img width="15" height="15" src="image/paperclip.gif" border="0">
                                            <a href="javascript:_addAttachmentFields('attachmentarea')" id="DTS_attachafile">Attach a file</a>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="col-lg-offset-10">
                        <a class="btn btn-primary btn-lg" type="button" id="DTS_docupload" name="DTS_docupload" disabled >UPDATE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</body>