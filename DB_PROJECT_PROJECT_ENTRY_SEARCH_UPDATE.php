<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*********************************PROJECT ENTRY/SEARCH/UPDATE**************************************//
//DONE BY:LALTHA
//VER 0.04 SD:03/12/2014 ED:03/12/2014,TRACKER NO:74,DESC:Updated preloader funct,Removed confirmation err msg,Added no data err msg,Fixed Width
//DONE BY:safi
//ver 0.03 SD:06/011/2014 ED:07/11/2014,tracker no:74,updated autocomplte function,set date for datepicker,changed validation part
//DONE BY:SASIKALA
//VER 0.02 SD:14/10/2014 ED:16/10/2014,TRACKER NO:86,DESC:VALIDATION'S DONE
//VER 0.01-INITIAL VERSION, SD:20/09/2014 ED:13/10/2014,TRACKER NO:74 DONE BY:SHALINI
//*********************************************************************************************************//-->
error_reporting(0);
include "CONNECTION.php";
include 'GET_USERSTAMP.php';
include "COMMON.php";
$USERSTAMP=$UserStamp;
if(isset($_REQUEST))
{
    //FUNCTION FOR SAVE PART
    if($_REQUEST['option']=='SAVE')
    {
        $project_name=$_REQUEST['PE_tb_prjectname'];
        $project_des=$_REQUEST['PE_ta_prjdescrptn'];
        $project_status=$_REQUEST['PE_tb_status'];
        $sdate=$_REQUEST['PE_tb_sdate'];
        $project_sdate=date("Y-m-d",strtotime($sdate));
        $edate=$_REQUEST['PE_tb_edate'];
        $project_edate=date("Y-m-d",strtotime($edate));
        $projectid=0;
        $psid=0;
        $project_name= $con->real_escape_string($project_name);
        $project_des= $con->real_escape_string($project_des);

        $result = $con->query("CALL SP_TS_PROJECT_DETAILS_INSERT_UPDATE('$projectid','$project_name','$project_des','$psid','$project_status','$project_sdate','$project_edate','$USERSTAMP','INSERT',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag= $result['@success_flag'];
        echo $flag;
    }
    else if($_REQUEST['option']=='CHECK')
    {
        $project_name=$_REQUEST['checkproject_name'];
        $sql="SELECT * FROM PROJECT_DETAILS WHERE PD_PROJECT_NAME='$project_name'";
        $sql_result= mysqli_query($con,$sql);
        $row=mysqli_num_rows($sql_result);
        $x=$row;
        if($x > 0)
        {
            $flag=1;
        }
        else{
            $flag=0;
        }

        $select_enddate="SELECT PS_END_DATE,PS_PROJECT_DESCRIPTION FROM PROJECT_STATUS WHERE PS_REC_VER=(SELECT MAX(PS_REC_VER) FROM PROJECT_STATUS WHERE PC_ID=3 AND PD_ID=(SELECT PD_ID FROM PROJECT_DETAILS WHERE PD_PROJECT_NAME='$project_name' ))AND PD_ID=(SELECT PD_ID FROM PROJECT_DETAILS WHERE PD_PROJECT_NAME='$project_name' )";
        $enddate_result=mysqli_query($con,$select_enddate);
        while($row=mysqli_fetch_array($enddate_result)){
            $enddate=$row['PS_END_DATE'];
            $project_desc=$row['PS_PROJECT_DESCRIPTION'];
        }
        $final_value=array($flag,$enddate,$project_desc);
        echo json_encode($final_value);
    }
    else if($_REQUEST['option']=='RANDOM')
    {
        $project_name=$_REQUEST['checkproject_name'];
        $REC_VER=$_REQUEST['recver'];
        $select_psid="SELECT PS_ID FROM PROJECT_STATUS WHERE PD_ID=(SELECT PD_ID FROM PROJECT_DETAILS WHERE PD_PROJECT_NAME='$project_name')AND PS_REC_VER='$REC_VER'";

        $select_psid_result=mysqli_query($con,$select_psid);
        if($row=mysqli_fetch_array($select_psid_result)){

            $pdid=$row['PS_ID'];

        }
        $select_sql="SELECT * FROM USER_ADMIN_REPORT_DETAILS UARD,PROJECT_DETAILS PD,PROJECT_STATUS PS WHERE  ((UARD.UARD_PSID LIKE '%,$pdid,%') OR (UARD.UARD_PSID LIKE '%,$pdid') OR (UARD.UARD_PSID=$pdid))AND (PD.PD_ID=PS.PD_ID)GROUP by UARD.ULD_ID,UARD.UARD_DATE";
        $sql_result= mysqli_query($con,$select_sql);
        $row=mysqli_num_rows($sql_result);
        $x=$row;
        if($x > 0)
        {
            $flag=1;
        }
        else{
            $flag=0;
        }
        echo $flag;
    }
    else if($_REQUEST['option']=='STATUS')
    {
        $status=mysqli_query($con,"SELECT PC_DATA from PROJECT_CONFIGURATION WHERE CGN_ID=7 ");
        $pro_status=array();
        while($row=mysqli_fetch_array($status)){
            $pro_status[]=$row["PC_DATA"];
        }
        echo json_encode( $pro_status);
    }
    else if($_REQUEST['option']=='AUTO')
    {
        global $con;
        $create_view=mysqli_query($con,"CREATE OR REPLACE VIEW VW_PROJECT AS SELECT PD_ID,MAX(PS_REC_VER)AS RECVER FROM PROJECT_STATUS GROUP BY PD_ID");
        $auto=mysqli_query($con," SELECT P.PD_PROJECT_NAME FROM PROJECT_DETAILS P,PROJECT_STATUS PS,VW_PROJECT V WHERE P.PD_ID=PS.PD_ID AND V.PD_ID=PS.PD_ID AND V.RECVER=PS.PS_REC_VER AND PS.PC_ID=3" );
        $pro_auto=array();
        while($row=mysqli_fetch_array($auto)){
            $pro_auto[]=$row["PD_PROJECT_NAME"];
        }
        $erro_msg=get_error_msg('62,63,79,80,81,83,99');
        $comp_startdate=get_company_start_date();
        $values=array($pro_auto,$erro_msg,$comp_startdate);
        $drop_view=mysqli_query($con,'drop view if exists VW_PROJECT');
        echo json_encode($values);
    }
}
if(isset($_REQUEST['option']) && $_REQUEST['option']!=''){
    $actionfunction = $_REQUEST['option'];
    call_user_func($actionfunction,$_REQUEST,$con);
}
//FUNCTION FOR SHOWN FLEC TABLE
function showData($data,$con){
    $sql = "SELECT PC.PC_DATA,PC.PC_ID,PS.PS_ID,PS.PS_REC_VER,DATE_FORMAT(PS.PS_END_DATE,'%d-%m-%Y')as PS_END_DATE,DATE_FORMAT(PS.PS_START_DATE,'%d-%m-%Y') as PS_START_DATE,PD.PD_ID,PD.PD_PROJECT_NAME,PS.PS_PROJECT_DESCRIPTION,DATE_FORMAT(CONVERT_TZ(PS.PS_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T') as TIMESTAMP,ULD.ULD_LOGINID as  ULD_USERSTAMP FROM PROJECT_DETAILS PD JOIN PROJECT_STATUS PS on PD.PD_ID = PS.PD_ID JOIN PROJECT_CONFIGURATION PC on PS.PC_ID = PC.PC_ID JOIN USER_LOGIN_DETAILS ULD on PS.ULD_ID=ULD.ULD_ID ORDER BY PD_PROJECT_NAME";
    $data = $con->query($sql);
    $str='<thead  bgcolor="#6495ed" style="color:white"><tr class="head"><th  width=200>PROJECT NAME</th><th width=500 >PROJECT DESCRIPTION</th><th width=10>REC VER</th><th width=30>STATUS</th><th width=50 class="uk-date-column">START DATE</th><th width=50 class="uk-date-column">END DATE</th><th style="min-width:70px;">USERSTAMP</th><th style="min-width:100px;" nowrap class="uk-timestp-column">TIMESTAMP</th><th width=110>EDIT</th></tr></thead><tbody>';
//    $str='';
    if($data->num_rows>0){
        while( $row = $data->fetch_array(MYSQLI_ASSOC)){
            $str.="<tr id='".$row['PS_ID'].'_'.$row['PD_ID']."'><td width=200 style='font-weight:bold;'>".$row['PD_PROJECT_NAME']." </td><td width=500>".$row['PS_PROJECT_DESCRIPTION']."</td><td width=10 >".$row['PS_REC_VER']."</td><td width=30>".$row['PC_DATA']."</td><td width=60 nowrap>".$row['PS_START_DATE']."</td><td width=90 nowrap>".$row['PS_END_DATE']."</td><td width=70>".$row['ULD_USERSTAMP']."</td><td width=120 nowrap>".$row['TIMESTAMP']."</td><td width=80><input type='button' id='editbtn' class='ajaxedit btn' value='Edit'/> </td></tr>";
        }
        echo $str;
    }
    else{
        $flag=0;
        echo $flag;
    }
}
//FUNCTION FOR UPDATE PART
function updateData($data,$con){
    global $USERSTAMP;
    $pname = $con->real_escape_string($data['name']);
    $pdes = $con->real_escape_string($data['des']);
    $pstatus = $con->real_escape_string($data['sta']);
    $date = $con->real_escape_string($data['ssd']);
    $psdate = date("Y-m-d",strtotime($date));
    $date = $con->real_escape_string($data['eed']);
    $enddate = date("Y-m-d",strtotime($date));
    $PS_ID = $con->real_escape_string($data['editid']);
    $PD_ID = $con->real_escape_string($data['pdid']);
    $QUERY= "CALL SP_TS_PROJECT_DETAILS_INSERT_UPDATE('$PD_ID','$pname','$pdes','$PS_ID','$pstatus','$psdate','$enddate','$USERSTAMP','UPDATE',@success_flag)";
    $result = $con->query($QUERY);
    if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
    $select = $con->query('SELECT @success_flag');
    $result = $select->fetch_assoc();
    $flag= $result['@success_flag'];
    echo $flag;
}
//FUNCTION FOR DELETE PART
function deleteData($data,$con){
    $delid = $con->real_escape_string($data['deleteid']);
    $sql = "delete from ajaxtable where id=$delid";
    if($con->query($sql)){
        showData($data,$con);
    }
    else{
        echo "error";
    }
}
?>