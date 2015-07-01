<?php
include "../FOLDERMENU.php";
?>
<script>

    $(document).ready(function(){
        $(".titlecase").Setcase({caseValue : 'title'});
        var errormessage=[];
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
               $('#RPT').hide();
               $('#AE').hide();
                var value_array=JSON.parse(xmlhttp.responseText);
                errormessage=value_array[0];
            }
        }
        var option="COMMON_DATA";
        xmlhttp.open("GET","DB_ACCIDENT_ENTRY.php?option="+option);
        xmlhttp.send();

    // time and date picker
        $('.time-picker').datetimepicker({
            format:'H:mm'
        });
        $(".date-picker").datepicker({
            dateFormat:"dd-mm-yy",
            changeYear: true,
            changeMonth: true
        });
    //set max length
        $(".txtlen").prop("maxlength", 50);
        $(".passno").prop("maxlength", 15);
        $(".time-picker").prop("maxlength", 5);
        $(".len").prop("maxlength", 10);
        $(".charlen").prop("maxlength",25);
    // numbers only
        $('.decimal').keyup(function(){
            var val = $(this).val();
            if(isNaN(val)){
                val = val.replace(/[^0-9\.]/g,'');
                if(val.split('.').length>2)
                    val =val.replace(/\.+$/,"");
            }
            $(this).val(val);
        });
        $( "textarea" ).autogrow( { vertical : true, horizontal : true } );
		 // text only
       $(".autosizealph").doValidation({rule:'alphabets',prop:{whitespace:true,autosize:true}});
    //DATEPICKER MINDATE
        var min_mindate=new Date();
        var min_month=min_mindate.getMonth()-3;
        var min_year=min_mindate.getFullYear();
        var min_date=min_mindate.getDate();
        var mindate = new Date(min_year,min_month,min_date);
        var report_mindate=new Date(Date.parse(mindate));
        $('#acc_tb_dateofaccident').datepicker("option","minDate",report_mindate);
    //DATEPICKER MAXDATE
        var max_maxdate=new Date();
        var max_month=max_maxdate.getMonth();
        var max_year=max_maxdate.getFullYear();
        var max_date=max_maxdate.getDate();
        var maxdate = new Date(max_year,max_month,max_date);
        var report_maxdate=new Date(Date.parse(maxdate));
        $('#acc_tb_dateofaccident').datepicker("option","maxDate",report_maxdate);
    //SET DOB DATEPICKER
        var EMP_ENTRY_d = new Date();
        var EMP_ENTRY_year = EMP_ENTRY_d.getFullYear() - 18;
        EMP_ENTRY_d.setFullYear(EMP_ENTRY_year);
        $('#acc_tb_dob').datepicker(
            {
                dateFormat: 'dd-mm-yy',
                changeYear: true,
                changeMonth: true,
                yearRange: "1920:" + EMP_ENTRY_year,
                defaultDate: EMP_ENTRY_d
            });
        var pass_changedmonth=new Date(EMP_ENTRY_d.setFullYear(EMP_ENTRY_year));
        $('#acc_tb_dob').datepicker("option","maxDate",pass_changedmonth);
    //END DATE PICKER FUNCTION
    //  CLICK EVENT FOR BUTTON SAVE
        $('#acc_btn_save').click(function(){
            $('.preloader').show();
            var formelement =$('#reportaccident').serialize();
            var option="SAVE";
            $.ajax({
                type: "POST",
                url: "DB_ACCIDENT_ENTRY.php",
                data: formelement+"&option="+option,
                success: function(msg){
                    $('.preloader').hide();
                    var msg_alert=msg;
                    if(msg_alert==1){
                        show_msgbox("ACCIDENT ENTRY",errormessage[0],"success",false);
                        $("#reportaccident").find('input:text, input:password, input:file, select, textarea').val('');
                        $("#reportaccident").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
                        $('#acc_btn_save').attr('disabled','disabled');
                        $('#acc_ta_adrs').height('22');
                        $('#acc_ta_description').height('214');

                    }
                    else if(msg_alert==0)
                    {
                        show_msgbox("ACCIDENT ENTRY",errormessage[1],"error",false)
                    }
                    else
                    {
                        show_msgbox("ACCIDENT ENTRY",msg_alert,"error",false)
                    }
                }
            });
        });
        //FINAL SUBMIT BUTTON VALIDATION
        $(document).on('change blur','#reportaccident',function(){
            var dateofaccident=$('#acc_tb_dateofaccident').val();
            var timeofaccident=$('#acc_tb_timeofaccident').val();
            var placeofaccident=$('#acc_tb_placeofacc').val();
            var locationofaccident=$('#acc_tb_locofacc').val();
            var typeofinjury=$('#acc_tb_typeofinju').val();
            var natureofinjury=$('#acc_tb_natureofinju').val();
            var partsofinjured=$('#acc_tb_partsofbody').val();
            var name=$('#acc_tb_name').val();
            var age=$('#acc_tb_age').val();
            var addrssofinjured= $("#acc_ta_adrs").val();
            var nricno=$("#acc_tb_nric").val();
            var finno=$("#acc_tb_fin").val();
            var workspermit=$("#acc_tb_workpermit").val();
            var passportno=$('#acc_tb_passportno').val();
            var nationality=$('#acc_tb_nationality').val();
            var dob=$('#acc_tb_dob').val();
            var maritalstatus=$('#acc_tb_maritalstatus').val();
            var designation=$('#acc_tb_des').val();
            var lengthofservice=$('#acc_tb_length').val();
            var commensy=$("input[name=work]:checked").val()=="yes";
            var commensn=$("input[name=work]:checked").val()=="no";
            var description=$('#acc_ta_description').val();
            var genderm=$("input[name=sex]:checked").val()=="male";
            var genderf=$("input[name=sex]:checked").val()=="female";

            if((dateofaccident!='')&&(timeofaccident!='') && (placeofaccident!='') && (locationofaccident!='')  && (typeofinjury!='') && (natureofinjury!='') && (partsofinjured!='')
                && (name!='') && (age!='') && (addrssofinjured!='') && (nricno!='') && (finno!='') && (workspermit!='') && (passportno!='')
                && (nationality!='') && (dob!='') && (maritalstatus!='') && (designation!='') && (lengthofservice!='') && (description!=''))
            {
                if(((genderf==true)|| (genderm==true)) && ((commensy==true) || (commensn==true)))
                {
                    $('#acc_btn_save').removeAttr('disabled');
                }
            }
            else
            {
                $('#acc_btn_save').attr('disabled','disabled');
            }
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
                <h2 class="panel-title">INCIDENT INVESTIGATION REPORT</h2>
            </div>
            <div class="panel-body">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">PARTICULARS OF ACCIDENT</h3>
                    </div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row form-group">
                                <div class="col-md-2">
                                   <label>DATE OF ACCIDENT<em>*</em></label>
                                   <div class="input-group">
                                       <input type="text" class="form-control date-picker datemandtry" id="acc_tb_dateofaccident" name="acc_tb_dateofaccident" placeholder="Date">
                                       <label for="acc_tb_dateofaccident" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                                   </div>
                                </div>
                                <div class="col-md-2">
                                    <label>TIME OF ACCIDENT<em>*</em></label>
                                    <input type="text" class="form-control time-picker" id="acc_tb_timeofaccident" name="acc_tb_timeofaccident" placeholder="Time of Accident">
                                </div>

                                <div class="col-md-4">
                                    <label>PLACE OF ACCIDENT<em>*</em></label>
                                    <input type="text" class="form-control txtlen titlecase" id="acc_tb_placeofacc"  name="acc_tb_placeofacc" placeholder="Place of Accident">
                                </div>
                                <div class="col-md-4">
                                    <label>LOCATION OF ACCIDENT<em>*</em></label>
                                    <input type="text" class="form-control txtlen titlecase" id="acc_tb_locofacc"  name="acc_tb_locofacc" placeholder="Location of Accident">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label>TYPE OF INJURY<em>*</em></label>
                                    <input type="text" class="form-control txtlen titlecase" id="acc_tb_typeofinju" name="acc_tb_typeofinju" placeholder="Type of Injury">
                                </div>
                                <div class="col-md-4">
                                    <label>NATURE OF INJURY<em>*</em></label>
                                    <input type="text" class="form-control txtlen titlecase" id="acc_tb_natureofinju" name="acc_tb_natureofinju" placeholder="Nature of Injury">
                                </div>

                                <div class="col-md-4">
                                    <label>PARTS OF BODY INJURED<em>*</em></label>
                                    <input type="text" class="form-control txtlen titlecase" id="acc_tb_partsofbody"  name="acc_tb_partsofbody" placeholder="Parts of Body Injured">
                                </div>

                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">MACHINERY INVOLVED(IF ANY)</h3>
                    </div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label>TYPE OF MACHINERY</label>
                                    <input type="text" class="form-control txtlen titlecase" id="acc_tb_typeofmachinery" name="acc_tb_typeofmachinery" placeholder="Type of Machinery">
                                </div>
                                <div class="col-md-4">
                                    <label>LM NO</label>
                                    <input type="text" class="form-control charlen" id="acc_tb_lmno" name="acc_tb_lmno" placeholder="LM No">
                                </div>

                                <div class="col-md-4">
                                    <label>NAME OF OPERATOR</label>
                                    <input type="text" class="form-control txtlen autosizealph" id="acc_tb_nameofoperator"  name="acc_tb_nameofoperator" placeholder="Name of Operator">
                                </div>

                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">PARTICULARS OF INJURED</h3>
                    </div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label>NAME<em>*</em></label>
                                    <input type="text" class="form-control txtlen autosizealph" id="acc_tb_name" name="acc_tb_name" placeholder="Name">
                                </div>
                                <div class="col-md-2">
                                    <label>AGE<em>*</em></label>
                                    <input type="text" class="form-control decimal" maxlength="2" id="acc_tb_age" name="acc_tb_age" placeholder="Age">
                                </div>

                                <div class="col-md-4">
                                    <label>ADDRESS OF INJURED<em>*</em></label>
                                    <textarea class="form-control titlecase" id="acc_ta_adrs" rows="1" name="acc_ta_adrs" maxlength="200" placeholder="Address"></textarea>
                                </div>
                                <div class="col-md-3">
                                    <label>NRIC NO<em>*</em></label>
                                    <input type="text" class="form-control len" id="acc_tb_nric" name="acc_tb_nric" placeholder="NRIC No">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label>FIN NO<em>*</em></label>
                                    <input type="text" class="form-control len" id="acc_tb_fin" name="acc_tb_fin" placeholder="FIN No">
                                </div>
                                <div class="col-md-3">
                                    <label>WORK PERMIT NO<em>*</em></label>
                                    <input type="text" class="form-control decimal passno" id="acc_tb_workpermit" name="acc_tb_workpermit" placeholder="Work Permit No">
                                </div>
                                <div class="col-md-3">
                                    <label>PASSPORT NO<em>*</em></label>
                                    <input type="text" class="form-control passno" id="acc_tb_passportno" name="acc_tb_passportno" placeholder="Passport No">
                                </div>

                                <div class="col-md-3">
                                    <label>NATIONALITY<em>*</em></label>
                                    <input type="text" class="form-control charlen autosizealph titlecase" id="acc_tb_nationality" name="acc_tb_nationality" placeholder="Nationality">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label>SEX<em>*</em></label>
                                    <div class="radio">
                                        <label class="checkbox-inline no_indent"> <input type="radio" name="sex" id="male" value="male"> Male </label>
                                        <label class="checkbox-inline no_indent"> <input type="radio" name="sex" id="female" value="female"> Female </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label>DATE OF BIRTH<em>*</em></label>
                                   <div class="input-group">
                                       <input type="text" class="form-control date-picker datemandtry" id="acc_tb_dob" name="acc_tb_dob" placeholder="Date of Birth">
                                       <label for="acc_tb_dob" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                                   </div>
                                </div>
                                <div class="col-md-3">
                                    <label>MARITAL STATUS<em>*</em></label>
                                    <input type="text" class="form-control charlen titlecase" id="acc_tb_maritalstatus" name="acc_tb_maritalstatus" placeholder="Marital Status">
                                </div>
                                <div class="col-md-4">
                                    <label>DESIGNATION<em>*</em></label>
                                    <input type="text" class="form-control txtlen titlecase" id="acc_tb_des" name="acc_tb_des" placeholder="Designation">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label>LENGTH OF SERVICE<em>*</em></label>
                                    <input type="text" class="form-control txtlen" id="acc_tb_length" name="acc_tb_length" placeholder="Length of Service">
                                </div>
                                <div class="col-md-6">
                                    <label>WAS BRIEFLY CARRIED OUT BEFORE WORK COMMENCEMENT<em>*</em></label>
                                    <div class="radio no_indent">
                                        <label class="checkbox-inline no_indent"> <input type="radio" name="work" id="yes" value="yes"> Yes </label>
                                        <label class="checkbox-inline no_indent"> <input type="radio" name="work" id="no" value="no"> No </label>
                                    </div>
                                </div>
                            </div>

                        </fieldset>
                    </div>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">DESCRIPTION OF ACCIDENT</h3>
                    </div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row form-group">
                                <div class="col-md-10">
                                    <label>DESCRIPTION OF ACCIDENT<em>*</em></label>
                                    <textarea class="form-control" id="acc_ta_description" rows="10" maxlength="3000" name="acc_ta_description" placeholder="Description"></textarea>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="col-lg-offset-10">
                    <a class="btn btn-primary btn-lg" type="button" id="acc_btn_save" name="acc_btn_save" disabled>SUBMIT</a>
                </div>
            </div>
            <div class="form-group-sm">
                <ul class="nav-pills">
                    <li class="pull-right"><a href="#top">Back to top</a></li>
                </ul>
            </div>
        </div>
    </div>
</form>
</body>
</html>​
