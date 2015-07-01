<?php
//include "HEADER.php";
include "../FOLDERMENU.php";
?>

<script>
$(document).ready(function(){
    var error_message=[];
    var teamname=[];
    var position=[];
    get_Values()
    function get_Values(){
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
                var values=JSON.parse(xmlhttp.responseText);
                var employeename=values[0];
                error_message=values[3];
                if(employeename!=0)
                {
                    var empname='<option>SELECT</option>';
                    for (var i=0;i<employeename.length;i++) {
                        empname += '<option value="' + employeename[i][0] + '">' + employeename[i][1] + '</option>';
                    }
                    $('#TA_emp').html(empname);
                    $('#entryform').show();
                }
                else{

                    $('#entryform').replaceWith('<p><label>'+error_message[4]+'</label></p>');
                }
                teamname=values[1];
                position=values[2];

                var tname='<option>SELECT</option>';
                for (var i=0;i<teamname.length;i++) {
                    tname += '<option value="' + teamname[i][1] + '">' + teamname[i][0] + '</option>';
                }
                $('#TA_teamname').html(tname);
                var pos='<option>SELECT</option>';
                for (var i=0;i<position.length;i++) {
                    pos += '<option value="' + position[i][1]+ '">' + position[i][0] + '</option>';
                }
                $('#TA_position').html(pos);

            }
        }
        var option='INITIAL_DATA';
        xmlhttp.open("GET","DB_TEAM_ASSIGN.php?&option="+option,true);
        xmlhttp.send();

    }
    intial()
    function intial(){
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var values_array=JSON.parse(xmlhttp.responseText);
                if(values_array!=null){
                    var SRC_UPD_table_header='<table id="team_assgn" border="1"  cellspacing="0" class="srcresult"><thead  bgcolor="#6495ed" style="color:white;text-align:center;"><tr><th style="text-align:center;">EMPLOYEE NAME</th><th style="text-align:center;">TEAM NAME</th><th style="text-align:center;">POSITION</th></tr></thead><tbody>'
                    for(var j=0;j<values_array.length;j++){
                        var etd_id=values_array[j][0];
                        var uld_id=values_array[j][1];
                        var empname=values_array[j][2];
                        var teamname=values_array[j][3];
                        var position=values_array[j][4];
                        if(teamname==null)
                        {
                            teamname='';
                        }
                        else{
                            teamname=teamname;
                        }

                        if(position==null)
                        {
                            position='';
                        }
                        else{
                            position=position;
                        }
                        SRC_UPD_table_header+='<tr id='+etd_id+'><td id='+uld_id+'>'+empname+'</td><td style="text-align:center;" class="teamedit" id=team_'+etd_id+'>'+teamname+'</td><td style="text-align:center;" class="posedit" id=pos_'+etd_id+'> '+position+'</td></tr>';
                    }
                    SRC_UPD_table_header+='</tbody></table>';
                    $('section').html(SRC_UPD_table_header);
                    $('#team_assgn').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers"
                    });
                    $('#datatablediv').show();

                }
            }
        }
        var option="DATATABLE";
        xmlhttp.open("GET","DB_TEAM_ASSIGN.php?option="+option);
        xmlhttp.send();
    }


    $('.btnenable').change(function(){
        var empname=$('#TA_emp').val();
        var teamname=$('#TA_teamname').val();
        var postion=$('#TA_position').val();
        if(empname!='SELECT' && teamname!='SELECT' && postion!='SELECT')
        {
            $('#TA_add').removeAttr('disabled');
        }
        else{
            $('#TA_add').attr('disabled','disabled');
        }
    });
    $('#TA_add').click(function(){
        $('.preloader').show();
        var empname=$('#TA_emp').val();
        var teamname=$('#TA_teamname').val();
        var postion=$('#TA_position').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var saveflag=xmlhttp.responseText;
                if(saveflag==1){
                    var msg=error_message[0]
                    var errmsg=msg.replace("(S)", "");
                    show_msgbox("TEAM ASSIGN",errmsg,"success",false);
                    get_Values();
                    intial();
                    $('#TA_emp').prop('selectedIndex',0);
                    $('#TA_teamname').prop('selectedIndex',0);
                    $('#TA_position').prop('selectedIndex',0);
                    $('#TA_add').attr('disabled','disabled');
                }
                else if(saveflag==0)
                {
                    var msg=error_message[1]
                    var errmsg=msg.replace("(S)", "");
                    show_msgbox("TEAM ASSIGN",errmsg,"error",false);
                }
                else
                {
                    show_msgbox("TEAM ASSIGN",saveflag,"error",false)
                }
            }
        }
        var option="SAVE";
        xmlhttp.open("GET","DB_TEAM_ASSIGN.php?option="+option+"&empname="+empname+"&teamname="+teamname+"&postion="+postion);
        xmlhttp.send();
    });
    var team_previous_id;
    var team_combineid;
    var team_tdvalue;
    $(document).on('click','.teamedit',function(){
        if(team_previous_id!=undefined){
            $('#'+team_previous_id).replaceWith("<td align='center' class='teamedit' id='"+team_previous_id+"' >"+team_tdvalue+"</td>");
        }
        var team_cid = $(this).attr('id');
        var id=team_cid.split('_');
        team_combineid=id[1];
        team_previous_id=team_cid;
        team_tdvalue = $(this).text();
        if(team_tdvalue!=''){
            $('#'+team_cid).replaceWith("<td align='center' class='new' id='"+team_previous_id+"'><select class='form-control teamupdate' id='edit_team'></select></td>");
            var tname='<option value="SELECT">SELECT</option>';
            for (var i=0;i<teamname.length;i++) {
                if(teamname[i][0]==team_tdvalue)
                {
                    var team_sindex=i;
                }
                tname += '<option value="' + teamname[i][1] + '">' + teamname[i][0] + '</option>';
            }
            $('#edit_team').html(tname);
        }
        $('#edit_team').prop('selectedIndex',team_sindex+1);
        if($('#pos_'+pos_combineid).hasClass('new')==true){
            $('#'+pos_previous_id).replaceWith("<td align='center' class='posedit' id='"+pos_previous_id+"' >"+pos_tdvalue+"</td>");
        }
    });
    $(document).on('change','.teamupdate',function(){
        var tcid=$(this).val();
        if(tcid!='SELECT')
        {
        $('.preloader').show();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var updateflag=xmlhttp.responseText;
                if(updateflag==1){
                    show_msgbox("TEAM ASSIGN",error_message[2],"success",false);
                    get_Values();
                    intial();
                }
                else if(updateflag==0)
                {
                    show_msgbox("TEAM ASSIGN",error_message[3],"error",false);
                }
                else
                {
                    show_msgbox("TEAM ASSIGN",updateflag,"error",false)
                }
            }
        }

        var option="teamupdate";
        xmlhttp.open("GET","DB_TEAM_ASSIGN.php?option="+option+"&rowid="+team_combineid+"&tcid="+tcid);
        xmlhttp.send();
        }
    });

    var pos_previous_id;
    var pos_combineid;
    var pos_tdvalue;
    $(document).on('click','.posedit',function(){
        if(pos_previous_id!=undefined){
            $('#'+pos_previous_id).replaceWith("<td align='center' class='posedit' id='"+pos_previous_id+"' >"+pos_tdvalue+"</td>");
        }
        var pos_cid = $(this).attr('id');
        var id=pos_cid.split('_');
        pos_combineid=id[1];
        pos_previous_id=pos_cid;
        pos_tdvalue = $(this).text().trim();
        if(pos_tdvalue!=''){
            $('#'+pos_cid).replaceWith("<td align='center' class='new' id='"+pos_previous_id+"'><select class='form-control posupdate' id='edit_pos'></select></td>");
            var empos='<option value="SELECT">SELECT</option>';
            for (var i=0;i<position.length;i++) {
                if(position[i][0]==pos_tdvalue)
                {
                    var pos_sindex=i;
                }
                empos += '<option value="' + position[i][1] + '">' + position[i][0] + '</option>';
            }
            $('#edit_pos').html(empos);
        }
        $('#edit_pos').prop('selectedIndex',pos_sindex+1);

        if($('#team_'+team_combineid).hasClass('new')==true){
           $('#'+team_previous_id).replaceWith("<td align='center' class='teamedit' id='"+team_previous_id+"' >"+team_tdvalue+"</td>");
        }
    });
    $(document).on('change','.posupdate',function(){
        var posid=$(this).val();
        if(posid!='SELECT')
        {
        $('.preloader').show();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var updateflag=xmlhttp.responseText;
                if(updateflag==1){
                    show_msgbox("TEAM ASSIGN",error_message[2],"success",false);
                    get_Values();
                    intial();
                }
                else if(updateflag==0)
                {
                    show_msgbox("TEAM ASSIGN",error_message[3],"error",false);
                }
                else
                {
                    show_msgbox("TEAM ASSIGN",updateflag,"error",false)
                }
            }
        }
        var option="positionupdate";
        xmlhttp.open("GET","DB_TEAM_ASSIGN.php?option="+option+"&rowid="+pos_combineid+"&posid="+posid);
        xmlhttp.send();
        }
    });
    $( "#entryform" ).click(function(){
        if($('#pos_'+pos_combineid).hasClass('new')==true){
            $('#'+pos_previous_id).replaceWith("<td align='center' class='posedit' id='"+pos_previous_id+"' >"+pos_tdvalue+"</td>");
        }
        if($('#team_'+team_combineid).hasClass('new')==true){
            $('#'+team_previous_id).replaceWith("<td align='center' class='teamedit' id='"+team_previous_id+"' >"+team_tdvalue+"</td>");
        }
    });
});
</script>
<body>
<form id="teamassign" class="form-horizontal">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">TEAM ASSIGN</h2>
            </div>
            <div class="panel-body">
                <div id="entryform" hidden>
                <div class="form-group">
                    <label  class="col-sm-2">EMPLOYEE NAME<em>*</em></label>
                    <div class="col-sm-3">
                        <select class="form-control btnenable" id="TA_emp" name="TA_emp" >

                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2">TEAMNAME<em>*</em></label>
                    <div class="col-sm-3">
                        <select class="form-control btnenable" id="TA_teamname" name="TA_teamname" >

                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2">POSITION<em>*</em></label>
                    <div class="col-sm-3">
                        <select class="form-control btnenable" id="TA_position" name="TA_position" >

                        </select>
                    </div>
                </div>
                <div class="">
                    <a class="btn btn-primary" type="button" id="TA_add" name="TA_add"  disabled>ADD</a>
                </div>
                </div>
                <br><br>
                <div class="table-responsive" id="datatablediv" style="max-width: 800px;">
                    <section>
                    </section>
                </div>

            </div>
        </div>
    </div>
</form>
</body>