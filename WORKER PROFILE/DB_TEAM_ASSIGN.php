<?php
error_reporting(0);
include "../LMC_LIB/CONNECTION.php";
include "../LMC_LIB/GET_USERSTAMP.php";
include "../LMC_LIB/COMMON.php";
$userstamp_id=mysqli_query($con,"SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS  WHERE ULD_USERNAME='$UserStamp'");
if($row=mysqli_fetch_array($userstamp_id))
{
    $uldid=$row["ULD_ID"];
}
if($_REQUEST['option']=='INITIAL_DATA')
{
// POSITION
    $position=mysqli_query($con,"SELECT WD_ID,WD_DESIGNATION FROM LMC_WORKER_DESIGNATION ORDER BY WD_DESIGNATION ASC");
    while($row=mysqli_fetch_array($position)){
        $employeepostion[]=array($row['WD_DESIGNATION'],$row['WD_ID']);
    }
//TEAM NAME
    $teamname=mysqli_query($con,"SELECT TC_ID,TEAM_NAME FROM LMC_TEAM_CREATION ORDER BY TEAM_NAME ASC");
    while($row=mysqli_fetch_array($teamname)){
        $empteamname[]=array($row['TEAM_NAME'],$row['TC_ID']);
    }
//ERROR MESSAGE
    $errormsg=get_error_msg('149,150,153,154,155');
//NOT ASSIGN
    $empnotassign=mysqli_query($con,"SELECT DISTINCT ULD_ID,ULD_WORKER_NAME FROM LMC_USER_LOGIN_DETAILS WHERE ULD_ID NOT IN (SELECT ULD_ID FROM LMC_EMPLOYEE_TEAM_DETAILS ) ORDER BY ULD_WORKER_NAME");
    $numrow=mysqli_num_rows($empnotassign);
    if($numrow>0)
    {
    while($row=mysqli_fetch_array($empnotassign)){
        $data[]=array($row['ULD_ID'],$row['ULD_WORKER_NAME']);
    }
    $values=array($data,$empteamname,$employeepostion,$errormsg);
    }
    else
    {
    $values=array($numrow,$empteamname,$employeepostion,$errormsg);
    }
    echo json_encode($values);
}
else if($_REQUEST['option']=='DATATABLE')
{
    //EMPLOYEE_NAME
    $dtvalues=mysqli_query($con,"SELECT L.ETD_ID,L.ULD_ID,ULD.ULD_WORKER_NAME,L1.TEAM_NAME,L2.WD_DESIGNATION FROM LMC_EMPLOYEE_TEAM_DETAILS L INNER JOIN LMC_USER_LOGIN_DETAILS ULD ON ULD.ULD_ID=L.ULD_ID INNER JOIN LMC_TEAM_CREATION L1 ON L.TC_ID = L1.TC_ID INNER JOIN LMC_WORKER_DESIGNATION L2 ON L2.WD_ID = L.WD_ID ORDER BY ULD.ULD_WORKER_NAME ASC");
    while($row=mysqli_fetch_array($dtvalues)){
        $datatablevalues[]=array($row['ETD_ID'],$row["ULD_ID"],$row['ULD_WORKER_NAME'],$row['TEAM_NAME'],$row['WD_DESIGNATION']);
    }
    echo json_encode($datatablevalues);
}
else if($_REQUEST['option']=='SAVE')
{
    $employeeid=$_REQUEST['empname'];
    $tcid=$_REQUEST['teamname'];
    $wdid=$_REQUEST['postion'];
    $inserquery="INSERT INTO LMC_EMPLOYEE_TEAM_DETAILS(ULD_ID,TC_ID,WD_ID,ETD_USERSTAMP_ID)  VALUES ($employeeid,$tcid,$wdid,$uldid)";
    if (!mysqli_query($con,$inserquery)) {
        die('Error: ' . mysqli_error($con));
        $saveflag=0;
    }
    else{
        $saveflag=1;
    }
    echo $saveflag;
}
elseif($_REQUEST['option']=='teamupdate')
{
    $tcid=$_REQUEST['tcid'];
    $rowid=$_REQUEST['rowid'];
    $updatequery="UPDATE LMC_EMPLOYEE_TEAM_DETAILS SET TC_ID=$tcid,ETD_USERSTAMP_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP') WHERE ETD_ID=$rowid";
    if (!mysqli_query($con,$updatequery)) {
        die('Error: ' . mysqli_error($con));
        $updateflag=0;
    }
    else{
        $updateflag=1;
    }
    echo $updateflag;
}
elseif($_REQUEST['option']=='positionupdate')
{
    $posid=$_REQUEST['posid'];
    $rowid=$_REQUEST['rowid'];
    $updatequery="UPDATE LMC_EMPLOYEE_TEAM_DETAILS SET WD_ID=$posid,ETD_USERSTAMP_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP') WHERE ETD_ID=$rowid";
    if (!mysqli_query($con,$updatequery)) {
        die('Error: ' . mysqli_error($con));
        $updateflag=0;
    }
    else{
        $updateflag=1;
    }
    echo $updateflag;
}
