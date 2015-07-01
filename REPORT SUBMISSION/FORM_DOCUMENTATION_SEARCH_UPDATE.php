<?php
include "../FOLDERMENU.php";
?>
<script>
var DT_upload_count=0;
$(document).ready(function(){
    $('#filename').hide();
    $('#daterange').hide();
    $('#category').hide();
    $('#DT_searchbtn').hide();
    $('#DT_radiosearchbtn').hide();
    $('#DT_srch_upt').hide();
    commondata();
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
            $('#DT_srch_ctgry').html(ctegry);
//                $('#DT_doc_lb_category').html(ctegry)
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
    $("#DT_srch_filename").keypress(function(){
        TH_employee_idflag=0;
        TH_view_highlightSearchText();
        $("#DT_srch_filename").autocomplete({
            source: arrayfilename,
            select: TH_AutoCompleteSelectHandler
        });
    });
// FUNCTION FOR AUTOCOMPLETESELECTHANDLER
    function TH_AutoCompleteSelectHandler(event,ui) {
        TH_employee_idflag=1;
    }
//CHANGE EVENT FOR SEARCH BY LIST BOX
    $('#DT_srch_by').change(function(){
        var listvalue=$('#DT_srch_by').val();
        $('#form_update').hide();
        $('#DT_radiosearchbtn').hide();
        $('#DT_exsistingfiletable').empty();
        $('#DT_filetableuploads').empty();
        $('#DT_attachafile').text('Attach a file');
        if(listvalue=='SELECT')
        {
            $('#filename').hide();
            $('#daterange').hide();
            $('#category').hide();
            $('#DT_srch_filename').val('');
            $('#DT_srch_fromdte').val('');
            $('#DT_srch_todte').val('');
            $('#DT_srch_ctgry').val('SELECT');
            $('#DT_searchbtn').hide();
            $('#DT_div_tablecontainer').hide();
        }
        else if(listvalue=='FILE NAME')
        {
            $('#filename').show();
            $('#daterange').hide();
            $('#category').hide();
            $('#DT_srch_filename').val('');
            $('#DT_srch_fromdte').val('');
            $('#DT_srch_todte').val('');
            $('#DT_srch_ctgry').val('SELECT');
            $('#DT_searchbtn').attr('disabled','disabled').show();
            $('#DT_div_tablecontainer').hide();
        }
        else if(listvalue=='DATE RANGE')
        {
            $('#filename').hide();
            $('#daterange').show();
            $('#category').hide();
            $('#DT_srch_filename').val('');
            $('#DT_srch_fromdte').val('');
            $('#DT_srch_todte').val('');
            $('#DT_srch_ctgry').val('SELECT');
            $('#DT_searchbtn').attr('disabled','disabled').show();
            $('#DT_div_tablecontainer').hide();
        }
        else if(listvalue=='CATEGORY')
        {
            $('#filename').hide();
            $('#daterange').hide();
            $('#category').show();
            $('#DT_srch_filename').val('');
            $('#DT_srch_fromdte').val('');
            $('#DT_srch_todte').val('');
            $('#DT_srch_ctgry').val('SELECT');
            $('#DT_searchbtn').attr('disabled','disabled').show();
            $('#DT_div_tablecontainer').hide();
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

// CHANGE EVENT FOR FILENAME TEXT  BOX
    $(document).on("change blur",'#DT_srch_filename', function (){
        $('#DT_div_tablecontainer').hide();
        $('#form_update').hide();
        $('#DT_exsistingfiletable').empty();
        $('#DT_filetableuploads').empty();
        $('#DT_radiosearchbtn').hide();
        if($('#DT_srch_filename').val()!='')
        {
            $('#DT_searchbtn').removeAttr('disabled');
        }
        else
        {
            $('#DT_searchbtn').attr('disabled','disabled');
        }
    });

//CHANGE EVENT FOR START & END DATE
    $('.srchbtnenable').change(function(){
        $('#DT_div_tablecontainer').hide();
        $('#form_update').hide();
        $('#DT_exsistingfiletable').empty();
        $('#DT_filetableuploads').empty();
        $('#DT_radiosearchbtn').hide();
        var startdate=$('#DT_srch_fromdte').val();
        var enddate=$('#DT_srch_todte').val();
        if(startdate!='' && enddate!='')
        {
            $('#DT_searchbtn').removeAttr('disabled');
        }
        else{
            $('#DT_searchbtn').attr('disabled','disabled');
        }
    });

//CHANGE EVENT FOR CATEGORY LIST BOX
    $('#DT_srch_ctgry').change(function(){
        $('#DT_div_tablecontainer').hide();
        $('#DT_exsistingfiletable').empty();
        $('#DT_filetableuploads').empty();
        $('#form_update').hide();
        $('#DT_radiosearchbtn').hide();
        if($('#DT_srch_ctgry').val()!='SELECT')
        {
            $('#DT_searchbtn').removeAttr('disabled');
        }
        else
        {
            $('#DT_searchbtn').attr('disabled','disabled');
        }
    });
//CLICK EVENT FOR SEARCH BUTTON
    $('#DT_searchbtn').click(function(){
        $('.preloader').show();
        DT_table();
    });
    var value_array=[];
    function DT_table(){
        var filename=$('#DT_srch_filename').val();
        var startdate=$('#DT_srch_fromdte').val();
        var enddate=$('#DT_srch_todte').val();
        var category=$('#DT_srch_ctgry').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                value_array=JSON.parse(xmlhttp.responseText);
                if(value_array!=null){
                    var DT_UPD_table_header='<table id="DT_tbl_htmltable" border="1"  cellspacing="0" class="srcresult"><thead  bgcolor="#6495ed" style="color:white"><tr><th></th><th style="text-align:center;">CATEGORY</th><th style="text-align:center;">DATE</th><th style="text-align:center;">FILENAME</th><th style="text-align:center;">USERSTAMP</th><th style="text-align:center;">TIMESTAMP</th></tr></thead><tbody>';
                    for(var j=0;j<value_array.length;j++){
                        var id=value_array[j].id;
                        var category=value_array[j].categroy;
                        var date=value_array[j].date;
                        var filename=value_array[j].filename;
                        var userstamp=value_array[j].userstamp;
                        var timestamp=value_array[j].timestamp;
                        DT_UPD_table_header+='<tr><td style="text-align:center;"><input type="radio" name="DT_UPD_rd_flxtbl" class="DT_UPD_class_radio" id='+id+'  value='+id+'></td><td style="width:150px" nowrap>'+category+'</td><td nowrap style="text-align:center;">'+date+'</td><td> '+filename+'</td><td nowrap>'+userstamp+'</td><td style="text-align:center;" nowrap>'+timestamp+'</td></tr>';
                    }
                    DT_UPD_table_header+='</tbody></table>';
                    $('section').html(DT_UPD_table_header);
                    $('#DT_tbl_htmltable').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers"
                    });
                    $('#DT_searchbtn').attr('disabled','disabled');
                    $('#DT_div_tablecontainer').show();
                    $('.preloader').hide();
                }
                else
                {
                    show_msgbox("DOCUMENTATION SEARCH UPDATE",error_message[0],"error",false);
                    $('#DT_div_tablecontainer').hide();
                    $('#DT_radiosearchbtn').hide();
                    $('.preloader').hide();
                }
            }
        }
        var option="search_data";
        xmlhttp.open("GET","DB_DOCUMENTATION_SEARCH_UPDATE.php?option="+option+"&filename="+filename+"&startdate="+startdate+"&enddate="+enddate+"&category="+category);
        xmlhttp.send();
    }
//CLICK EVENT FOR  RADIO BUTTON
    $(document).on('click','.DT_UPD_class_radio',function(){
        $('#DT_exsistingfiletable').empty();
        $('#DT_filetableuploads').empty();
        $('#DT_attachafile').text('Attach a file');
        $('#DT_radiosearchbtn').removeAttr('disabled').show();
        $('#DT_docupload').attr('disabled','disabled');
        $('#DT_srch_upt').hide();
        $('#form_update').hide();
        $('#temptextbox').val('');
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
    });
//CLICK EVENT FOR SEARCH BUTTON
    var DT_idradiovalue;
    $(document).on('click','#DT_radiosearchbtn',function(){
        $('#form_update').show();
        $('#DT_exsistingfiletable').empty();
        $('#DT_filetableuploads').empty();
        $('#DT_attachafile').text('Attach a file');
        $('#DT_radiosearchbtn').attr('disabled','disabled');
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        DT_idradiovalue=$('input:radio[name=DT_UPD_rd_flxtbl]:checked').attr('id');
        for(var j=0;j<value_array.length;j++){
            var id=value_array[j].id;
            if(id==DT_idradiovalue)
            {
                var category=value_array[j].categroy;
                var date=value_array[j].date;
                var docfilename=value_array[j].filename;
                $('#DT_doc_lb_category').val(category);
                $('#DT_doc_date').val(date);
            }
        }
        var filenameinarray=docfilename.split('/');
        for(var j=0;j<filenameinarray.length;j++){
            var name=filenameinarray[j];
            var filecount="filecount"+j;
            if(rolename=='ADMIN' || rolename=='SUPER ADMIN')
            {
                var appendfile=' <div class="col-sm-offset-2 col-sm-10" style="padding-bottom: 5px" id='+filecount+'><a href="../LMC_LIB/downloadpdf.php?filename='+name+'" class="links">'+filenameinarray[j]+'</a>&nbsp;&nbsp;<input type="button" id="ibtnDel"  class="updatebtn" value="X" style="background-color:red;color:white;font-size:10;font-weight: bold;"/></div>';
            }
            else
            {
                var appendfile=' <div class="col-sm-offset-2 col-sm-10" style="padding-bottom: 5px" id='+filecount+'><a href="../LMC_LIB/downloadpdf.php?filename='+name+'" class="links">'+filenameinarray[j]+'</a></div>';
                $('#DT_attachprompt').hide();
                $('#DT_docupload').hide();
            }
            $('#DT_exsistingfiletable').append(appendfile);
        }
    });
    var removedfilename;
    //CLICK EVENT DELETE BUTTON
    $(document).on("click", "#ibtnDel", function (){
        $(this).closest("div").remove();
        var Count = $('#DT_filetableuploads > div').length;
        if(Count==0)
        {
            $('#DT_attachafile').text('Attach a file');
            $('#DT_docupload').attr('disabled','disabled');
        }
        else
        {
            $('#DT_attachafile').text('Attach another file');
            $('#DT_docupload').removeAttr('disabled');
        }

        var divcount=$('#DT_exsistingfiletable > div').length;
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
    var filecnt;
    $(document).on('click', 'button.removebutton', function () {
        $(this).closest('div').remove();
        DT_upload_count=DT_upload_count-1;
        var rowcnt = $('#DT_filetableuploads > div').length;
        if(rowcnt!=0)
        {
            $('#DT_attachafile').text('Attach another file');
            filecnt=$('#temptextbox').val();
            var count=0;
            for(var j=1;j<=filecnt;j++)
            {
                var data= $('#DT_upload_filename'+j).val();
                if(data!='' && data!=undefined && data!=null)
                {
                    count++;
                }
            }
            if(rowcnt==count)
            {
                $('#DT_docupload').removeAttr("disabled");
            }
            else
            {
                $('#DT_docupload').attr("disabled", "disabled");
            }
        }
        if(rowcnt==0)
        {
            $('#DT_attachafile').text('Attach a file');
            $('#DT_docupload').attr("disabled", "disabled");
        }
        return false;
    });
//file extension validation
    $(document).on("change",'.fileextensionchk', function (){
        var fileid=$(this).attr("id");
        var data= $('#'+fileid).val();
        var datasplit=data.split('.');
        var ext=datasplit[1].toUpperCase();
        if(ext=='PDF'|| ext=='JPG'|| ext=='PNG' || ext=='JPEG' || ext=='GIF' || data==undefined || data==""){
        }
        else{
            show_msgbox("DOCUMENTATION SEARCH UPDATE",error_message[1],"error",false);
            reset_field($('#'+fileid));
            $('#DT_docupload').attr("disabled", "disabled");
        }
    });
//file upload reset
    function reset_field(e) {
        e.wrap('<form>').parent('form').trigger('reset');
        e.unwrap();
    }
//add file upload row
    $(document).on("click",'#DT_attachprompt', function (){
        var tablerowCount = $('#DT_filetableuploads > div').length;
        if(tablerowCount==0)
        {
            var row_count=parseInt(tablerowCount)+1;
            var uploadfileid="DT_upload_filename"+row_count;
            $('#temptextbox').val(row_count);
        }
        else
        {
            var rowvalue=$('#temptextbox').val();
            var rowcount=parseInt(rowvalue)+1;
            uploadfileid="DT_upload_filename"+rowcount;
            $('#temptextbox').val(rowcount);
        }
        var appendfile='<div class="col-sm-offset-2 col-sm-5" style="padding-bottom: 8px"><label class="inline"><input type="file" style="max-width:250px " class="fileextensionchk form-control" id='+uploadfileid+' name='+uploadfileid+'></label><label class="inline" ><button  class="removebutton" value="-" title="Remove this row" style="background-color:red;color:white;font-size:9;font-weight: bold;"></button></label></div>';
        $('#DT_filetableuploads').append(appendfile);
        var rowCount =$("#DT_filetableuploads > div").length;
        DT_upload_count++;
        if(rowCount!=0)
        {
            $('#DT_attachafile').text('Attach another file');
        }
        else
        {
            $('#DT_attachafile').text('Attach a file');
        }
    });

// button validation
    var finalrow;
    var final_row;
    $(document).on('change blur','#form_update',function(){
        var rowCount=$("#DT_filetableuploads > div").length;
        finalrow=$('#temptextbox').val();
        var count=0;
        for(var j=1;j<=finalrow;j++)
        {
            var data= $('#DT_upload_filename'+j).val();
            if(data!='' && data!=undefined && data!=null)
            {
                count++;
            }
        }
        if(rowCount==count)
        {
            $('#DT_docupload').removeAttr('disabled');
        }
        else
        {
            $('#DT_docupload').attr('disabled','disabled');
        }
    });

    // REMOVE BUTTON VALIDATION
    $(document).on('click','.updatebtn',function(){
        var existdivcount =$("#DT_exsistingfiletable > div").length;
        var fileuploadCount = $('#DT_filetableuploads > div').length;
        if(existdivcount!=0 || fileuploadCount!=0)
        {
            final_row=$('#temptextbox').val();
            var count=0;
            for(var j=1;j<=final_row;j++)
            {
                var data= $('#DT_upload_filename'+j).val();
                if(data!='' && data!=undefined && data!=null)
                {
                    count++;
                }
            }
            if(fileuploadCount==count)
            {
                $('#DT_docupload').removeAttr("disabled");
            }
            else
            {
                $('#DT_docupload').attr("disabled", "disabled");
            }
        }
    });
//CHANGE EVENT FOR CATEGORY LIST BOX & DATE
    $('.existctegry').change(function(){
        var categoryname=$('#DT_doc_lb_category').val();
        var date=$('#DT_doc_date').val();
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
                        show_msgbox("DOCUMENTATION SEARCH UPDATE",errmsg,"error",false)
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
//CLICK EVENT FORM UPDATE BUTTON
    $('#DT_docupload').click(function(){
        $('.preloader').show();
        var formElement=new FormData(document.getElementById("documentupdate"));
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var filename=xmlhttp.responseText;
                if(filename==1){
                    removedfilename='undefined';
                    $('#form_update').hide();
                    DT_table();
                    commondata();
                    DT_upload_count=0;
                    $('#temptextbox').val('');
                    show_msgbox("DOCUMENTATION SEARCH UPDATE",error_message[2],"success",false);
                }
                else if(filename==0)
                {
                    show_msgbox("DOCUMENTATION SEARCH UPDATE",error_message[3],"error",false)
                }
                else
                {
                    show_msgbox("DOCUMENTATION SEARCH UPDATE",filename,"error",false)
                }
            }
        }
        var option="update";
        xmlhttp.open("POST","DB_DOCUMENTATION_SEARCH_UPDATE.php?option="+option+"&DT_upload_count="+finalrow+"&rowid="+DT_idradiovalue+"&removedfilename="+removedfilename);
        xmlhttp.send(formElement);
    });
});
</script>
</head>
<body>
<form id="documentupdate" class="form-horizontal">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">DOCUMENTATION SEARCH UPDATE</h2>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label id="srch_by" class="col-sm-2">SEARCH BY<em>*</em></label>
                    <div class="col-sm-3">
                        <select class="form-control" id="DT_srch_by" name="DT_srch_by" >
                            <option>SELECT</option>
                            <option>FILE NAME</option>
                            <option>DATE RANGE</option>
                            <option>CATEGORY</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" id="filename">
                    <label id="srch_filename" class="col-sm-2">FILE NAME<em>*</em></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="DT_srch_filename" name="DT_srch_filename" hidden >
                    </div>
                </div>
                <div id="daterange">
                    <div class="form-group">
                        <label class="col-sm-2" id="srch_startdate">START DATE</label>
                        <div class="col-sm-2">
                            <div class="input-group addon">
                                <input id="DT_srch_fromdte" name="DT_srch_fromdte" type="text" class="date-picker datemandtry srchbtnenable form-control" placeholder="Start Date" hidden/>
                                <label for="DT_srch_fromdte" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar" hidden></span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2" id="srch_todate">END DATE</label>
                        <div class="col-sm-2">
                            <div class="input-group addon">
                                <input id="DT_srch_todte" name="DT_srch_todte" type="text" class="date-picker datemandtry srchbtnenable form-control" placeholder="End Date" hidden/>
                                <label for="DT_srch_todte" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar" hidden></span></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="category">
                    <label class="col-sm-2" id="srch_category">CATEGORY</label>
                    <div class="col-sm-3">
                        <select class="form-control" id="DT_srch_ctgry" name="DT_srch_ctgry" hidden>
                        </select>
                    </div>
                </div>
                <div>
                    <button type="button" id="DT_searchbtn" class="btn btn-info" disabled>SEARCH</button>
                </div>
                <br>
                <div class="table-responsive" id="DT_div_tablecontainer" hidden>
                    <section>
                    </section>
                </div>
                <div>
                    <button type="button" id="DT_radiosearchbtn" class="btn btn-info" disabled hidden>SEARCH</button>
                </div>
                <br>
                <div id="form_update" hidden>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">ATTACHMENTS</h3>
                        </div>
                        <div class="panel-body">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2">CATEGORY<em>*</em></label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control existctegry" id="DT_doc_lb_category" name="DT_doc_lb_category" readonly />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2">DATE<em>*</em></label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control  datemandtry existctegry" id="DT_doc_date" name="DT_doc_date" placeholder="Date" readonly>
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        <div ID="DT_exsistingfiletable" class="form-group row"></div>
                                        <div><input type="hidden" id="temptextbox" name="temptextbox"></div>
                                        <div ID="DT_filetableuploads" class="form-group row">

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div id="DT_attachprompt" class="col-sm-offset-2 col-sm-2"><img width="15" height="15" src="../image/paperclip.gif" border="0">
                                            <a href="javascript:_addAttachmentFields('attachmentarea')" id="DT_attachafile">Attach a file</a>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="col-lg-offset-10">
                        <a class="btn btn-primary btn-lg" type="button" id="DT_docupload" name="DT_docupload" disabled >UPDATE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</body>