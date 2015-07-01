<?php
include "../FOLDERMENU.php";
?>
<html>
<head>
<script>
var ENT_upload_count=0;
$(document).ready(function(){
    $('.preloader').show();
    $(".date-picker").datepicker({
        dateFormat:"dd-mm-yy",
        changeYear: true,
        changeMonth: true
    });
    $(".date-picker").on("change", function () {
        var id = $(this).attr("id");
        var val = $("label[for='" + id + "']").text();
        $("#msg").text(val + " changed");
    });
//DATEPICKER MINDATE
    var min_mindate=new Date();
    var min_month=min_mindate.getMonth()-3;
    var min_year=min_mindate.getFullYear();
    var min_date=min_mindate.getDate();
    var mindate = new Date(min_year,min_month,min_date);
    var report_mindate=new Date(Date.parse(mindate));
    $('.date-picker').datepicker("option","minDate",report_mindate);
//DATEPICKER MAXDATE
    var max_maxdate=new Date();
    var max_month=max_maxdate.getMonth()+1;
    var max_year=max_maxdate.getFullYear();
    var max_date=max_maxdate.getDate();
    var maxdate = new Date(max_year,max_month,max_date);
    var report_maxdate=new Date(Date.parse(maxdate));
    $('.date-picker').datepicker("option","maxDate",report_maxdate);

    var categories=[];
    var errormessage=[];
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            var value_array=JSON.parse(xmlhttp.responseText);
            $('.preloader').hide();
            categories=value_array[0];
            errormessage=value_array[1];
            //category
            var category='<option>SELECT</option>';
            for (var i=0;i<categories.length;i++) {
                category += '<option value="' + categories[i] + '">' + categories[i] + '</option>';
            }
            $('#doc_lb_category').html(category);
        }
    }
    var option="COMMON_DATA";
    xmlhttp.open("GET","DB_DOCUMENTATION_ENTRY.php?option="+option);
    xmlhttp.send();

    $("#ENT_filetableuploads div").remove();
    $('#ENT_attachafile').text('Attach a file');
    //remove file upload row
    var filecnt;
    $(document).on('click', 'button.removebutton', function () {
        $(this).closest('div').remove();
        ENT_upload_count=ENT_upload_count-1;
        var rowcnt = $('#ENT_filetableuploads > div').length;
        if(rowcnt!=0)
        {
            $('#ENT_attachafile').text('Attach another file');
            filecnt=$('#temptextbox').val();
            var count=0;
            for(var j=1;j<=filecnt;j++)
            {
                var data= $('#ENT_upload_filename'+j).val();
                if(data!='' && data!=undefined && data!=null)
                {
                    count++;
                }
            }
            if(rowcnt==count)
            {
                $('#docupload').removeAttr("disabled");
            }
            else
            {
                $('#docupload').attr("disabled", "disabled");
            }
        }
        if(rowcnt==0)
        {
            $('#ENT_attachafile').text('Attach a file');
            $('#docupload').attr("disabled", "disabled");
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
            show_msgbox("DOCUMENTATION ENTRY",errormessage[0],"error",false)
            reset_field($('#'+fileid));
            $('#docupload').attr('disabled','disabled');
        }
    });
    //file upload reset
    function reset_field(e) {
        e.wrap('<form>').parent('form').trigger('reset');
        e.unwrap();
    }
    //add file upload row
    $(document).on("click",'#ENT_attachprompt', function (){
        var tablerowCount = $('#ENT_filetableuploads > div').length;
        if(tablerowCount==0)
        {
            var row_count=parseInt(tablerowCount)+1;
            var uploadfileid="ENT_upload_filename"+row_count;
            $('#temptextbox').val(row_count);
        }
        else
        {
            var rowvalue=$('#temptextbox').val();
            var rowcount=parseInt(rowvalue)+1;
            uploadfileid="ENT_upload_filename"+rowcount;
            $('#temptextbox').val(rowcount);
        }
        var appendfile='<div class="col-sm-10" style="padding-bottom: 8px"><label class="inline"><input type="file" style="max-width:250px " class="fileextensionchk form-control" id='+uploadfileid+' name='+uploadfileid+'></label><label class="inline" ><button  class="removebutton" value="-" title="Remove this row" style="background-color:red;color:white;font-size:9;font-weight: bold;"></button></label></div>';
        $('#ENT_filetableuploads').append(appendfile);
        var rowCount =$("#ENT_filetableuploads > div").length
        ENT_upload_count++;
        if(rowCount!=0)
        {
            $('#ENT_attachafile').text('Attach another file');
        }
        else
        {
            $('#ENT_attachafile').text('Attach a file');
        }
    });
    function form_clear(){
        $('#doc_date').val('');
        $('#doc_lb_category').val('SELECT').show();
        $("#ENT_filetableuploads div").remove();
        $('#ENT_attachafile').text('Attach a file');
    }

//CHANGE EVENT FOR CATEGORY LIST BOX & DATE
    $('.existctegry').change(function(){
        var categoryname=$('#doc_lb_category').val();
        var date=$('#doc_date').val();
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
                    var msg=errormessage[3].toString().replace("[CATEGORY]",categoryname);
                    var errmsg=msg.toString().replace("[DATE]",date);
                    show_msgbox("DOCUMENTATION ENTRY",errmsg,"error",false)
                    $('#doc_lb_category').val('SELECT').show();
                    $('#doc_date').val('').show();
                }
            }
    }
    }
    var option="category_exists";
    xmlhttp.open("POST","DB_DOCUMENTATION_ENTRY.php?option="+option+"&categoryname="+categoryname+"&date="+date);
    xmlhttp.send();
    });

// button validation
    var finalrow;
    $(document).on('change blur','#documentform',function(){
        var uploaddate=$('#doc_date').val();
        var uploadcategory=$('#doc_lb_category').val();
        var rowCount=$("#ENT_filetableuploads > div").length;
        finalrow=$('#temptextbox').val();
        var count=0;
        if(finalrow==0)
        {
            $('#docupload').attr('disabled','disabled');
        }
        else{
        for(var j=1;j<=finalrow;j++)
        {
            var data= $('#ENT_upload_filename'+j).val();
            if(data!='' && data!=undefined && data!=null)
            {
                count++;
            }
        }
        if(rowCount==count && uploaddate!='' && uploadcategory!='SELECT')
        {
            $('#docupload').removeAttr('disabled');
        }
        else
        {
            $('#docupload').attr('disabled','disabled');
        }


        }
    });
// submit file
    $(document).on("click",'#docupload', function (){
        $('.preloader').show();
        var formElement=new FormData(document.getElementById("documentform"));
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var filename=xmlhttp.responseText;
                if(filename==1){
                    show_msgbox("DOCUMENTATION ENTRY",errormessage[1],"success",false)
                    form_clear();
                }
                else if(filename==0)
                {
                    show_msgbox("DOCUMENTATION ENTRY",errormessage[2],"error",false)
                }
                else
                {
                    show_msgbox("DOCUMENTATION ENTRY",filename,"error",false)
                }
            }
        }
        var option="tempfilname";
        xmlhttp.open("POST","DB_DOCUMENTATION_ENTRY.php?option="+option+"&ENT_upload_count="+finalrow);
        xmlhttp.send(formElement);
    });
});
</script>
</head>
<body>
<form id="documentform" name="documentform" class="form-horizontal">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">DOCUMENTATION ENTRY</h2>
            </div>
            <div class="panel-body">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">ATTACHMENTS</h3>
                    </div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row form-group">
                                <div class="col-md-4 selectContainer">
                                    <label>CATEGORY<em>*</em></label>
                                    <select class="form-control existctegry" id="doc_lb_category" name="doc_lb_category">
                                        <option>SELECT</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>DATE<em>*</em></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control date-picker datemandtry existctegry" id="doc_date" name="doc_date" placeholder="Date">
                                        <label for="doc_date" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div><input type="hidden" id="temptextbox" name="temptextbox"></div>
                                <div ID="ENT_filetableuploads" class="form-group"></div>
                            </div>
                            <div class="form-group">
                                <div id="ENT_attachprompt" class="col-sm-3"><img width="15" height="15" src="../image/paperclip.gif" border="0">
                                    <a href="javascript:_addAttachmentFields('attachmentarea')" id="ENT_attachafile">Attach a file</a>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="col-lg-offset-10">
                    <a class="btn btn-primary btn-lg" type="button" id="docupload" name="docupload" disabled >UPLOAD</a>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>