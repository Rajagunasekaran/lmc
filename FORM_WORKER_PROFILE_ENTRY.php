<?php
include "NEW_MENU.php"
?>
<script>
    $(document).ready(function(){
        var upload_count=0;
        //DATE PICKER FUNCTION
        $(".date-picker").datepicker({
            dateFormat:"dd-mm-yy",
            changeYear: true,
            changeMonth: true
        });
        //reomve file upload row
        $(document).on('click', 'button.removebutton', function () {
            upload_count=upload_count-1;
            $(this).closest('div').remove();
//            rowCount_check=$('#filetableuploads > div').length;
            var rowCount = $('#filetableuploads > div').length;
            if(rowCount!=0)
            {
                $('#attachafile').text('Attach another file');
                $("#WP_btn_submitbutton").removeAttr("disabled");
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
            for(var i=0;i<25;i++)
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
            var appendfile='<div class="col-sm-offset-2 col-sm-5"><label class=""><input type="file" style="max-width:250px " class="fileextensionchk form-control" id='+uploadfileid+' name='+uploadfileid+'></label><label class="inline" ><button  class="removebutton" value="-" title="Remove this row" style="background-color:red;color:white;font-size:10;font-weight: bold;"></button></label></div>';
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
            }
        });




var empname=[];
        var errmsg=[];

        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var value_array=JSON.parse(xmlhttp.responseText);
                $('.preloader').hide();
                empname=value_array[0];
                errmsg=value_array[1];
                //EMPNAME
                var employeename='<option>SELECT</option>';
                for (var i=0;i<empname.length;i++) {
                    employeename += '<option value="' + empname[i][1] + '">' + empname[i][0] + '</option>';
                }
                $('#WP_lb_selectempname').html(employeename);
            }
        }
        var option="COMMON_DATA";
        xmlhttp.open("POST","DB_WORKER_PROFILE_ENTRY.php?option="+option);
        xmlhttp.send();



var finalrow;
        // button validation
        $(document).on('change blur','#WR_entry_form',function(){
            var empname=$('#WP_lb_selectempname').val();
            var date=$('#WP_tb_date').val();
            var rowCount=$("#filetableuploads > div").length;
            finalrow=$('#temptextbox').val();
            var count=0;
            if(finalrow==0)
            {
                $('#WP_btn_submitbutton').attr('disabled','disabled');
            }
            else{
                for(var j=1;j<=finalrow;j++)
                {
                    var data= $('#upload_filename'+j).val();
                    if(data!='' && data!=undefined && data!=null)
                    {
                        count++;
                    }
                }
                if(rowCount==count && date!='' && empname!='SELECT')
                {
                    $('#WP_btn_submitbutton').removeAttr('disabled');
                }
                else
                {
                    $('#WP_btn_submitbutton').attr('disabled','disabled');
                }
            }
        });

        $('.submit_enable').change(function(){
            var empname=$('#WP_lb_selectempname').val();
            var name=$('#WP_lb_selectempname :selected').text();
            var date=$('#WP_tb_date').val();
            if(empname!='SELECT' && date!='')
            {
                $('.preloader').show();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $('.preloader').hide();
                        var exist_flag=xmlhttp.responseText;
                       if(exist_flag!=0){
                           var msg=errmsg[3].replace('[CATEGORY]',name);
                           msg=msg.replace('CATEGORY','');
                           msg=msg.replace('[DATE]',date);
                           show_msgbox("DOCUMENT MANAGEMENT ENTRY",msg,"error",false)

                       }

                    }
                }
            }
            var option="allready_exists";
            xmlhttp.open("POST","DB_WORKER_PROFILE_ENTRY.php?option="+option+"&empname="+empname+"&date="+date);
            xmlhttp.send();
        });

        $(document).on("click",'#WP_btn_submitbutton', function (){
            $('.preloader').show();
            var formElement = document.getElementById("WR_entry_form");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var values=JSON.parse(xmlhttp.responseText);

if(values==1){

    show_msgbox("DOCUMENT MANAGEMENT ENTRY",errmsg[1],"success",false)
    $('#WP_lb_selectempname').prop('selectedIndex',0);

$('#WP_tb_date').val('');
    $("#filetableuploads").empty();
    $('#attachafile').text('Attach a file');
    $("#WP_btn_submitbutton").attr("disabled", "disabled");
}
                    else{

    show_msgbox("DOCUMENT MANAGEMENT ENTRY",errmsg[2],"success",false)

}

                }

            }
            var option="save";
            xmlhttp.open("POST","DB_WORKER_PROFILE_ENTRY.php?option="+option+"&upload_count="+finalrow);
            xmlhttp.send(new FormData(formElement));


        });



    });
</script>
<!--BODY TAG START-->
<body>
<div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
<div class="container">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h2 class="panel-title">DOCUMENT MANAGEMENT ENTRY</h2>
        </div>
        <div class="panel-body">
            <form id="WR_entry_form" name="WR_entry_form" class="form-horizontal" role="form">

                <div class="form-group">
                    <label id="WP_lbl_selectempname" class="col-sm-2" >EMPLOYEE NAME<em>*</em></label>
                    <div class="col-sm-3"><select id='WP_lb_selectempname' name="WP_lb_selectempname" title="LOGIN ID" maxlength="40" placeholder="Employee Name" class="form-control submit_enable" >
                            <option value='SELECT' selected="selected"> SELECT</option>
                        </select></div></div>


                    <div class="form-group">
                        <label id="URSRC_lbl_date" class="col-sm-2">DATE<em>*</em></label>
                        <div class="col-sm-10"><input type="text" name="WP_tb_date" placeholder="Date" id="WP_tb_date" class="date-picker submit_enable datemandtry form-control" style="width:110px;" hidden  /></div>
                    </div>

                <div>
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

                </form>

            </div>

        </div>
    </div>
</body>
</html>
