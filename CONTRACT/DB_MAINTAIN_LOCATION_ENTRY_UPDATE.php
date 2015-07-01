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
if($_REQUEST['option']=='INITIAL_DATA') {
//JOBSTATUS
    $job_status = mysqli_query($con, "SELECT LJS_ID,LJS_STATUS FROM LMC_JOB_STATUS ORDER BY LJS_STATUS ASC");
    while ($row = mysqli_fetch_array($job_status)) {
        $jobstatus[] = array($row['LJS_STATUS'], $row['LJS_ID']);
    }
//JOBSTATUS
    $veri_status = mysqli_query($con, "SELECT LVS_ID,LVS_STATUS FROM LMC_VERIFICATION_STATUS ORDER BY LVS_STATUS ASC");
    while ($row = mysqli_fetch_array($veri_status)) {
        $verificationstatus[] = array($row['LVS_STATUS'], $row['LVS_ID']);
    }
//REFERENCE NUMBER
    $refcallquery = "CALL SP_REF_NO_AUTO_GENERATE(@REFNO,@COUNT_ID)";
    $result = $con->query($refcallquery);
    $select = $con->query('SELECT @REFNO,@COUNT_ID');
    $result = $select->fetch_assoc();
    $refno= $result['@REFNO'];
    $countid= $result['@COUNT_ID'];
//CONTRACT NO
    $contractno=ongoing_contractno();
    $errormsg=get_error_msg('3,4,7,17');
    $arrayvalues=[];
    $arrayvalues=array($jobstatus,$verificationstatus,$refno,$countid,$contractno,$errormsg);
    echo json_encode($arrayvalues);
}
elseif($_REQUEST['option']=='searchdata')
{
    $selectquery=mysqli_query($con,"SELECT A.*,B.LJS_STATUS,C.LVS_STATUS,D.CLD_CONTRACT_NO,E.ULD_USERNAME FROM LMC_MAINTAIN_LOCATION_DETAILS A, LMC_JOB_STATUS B, LMC_VERIFICATION_STATUS C, LMC_CONTRACT_DETAILS D,LMC_USER_LOGIN_DETAILS E WHERE A.LJS_ID=B.LJS_ID AND A.LVS_ID = C.LVS_ID AND A.CLD_ID=D.CLD_ID AND A.ULD_ID=E.ULD_ID ORDER BY A.LML_REF_NO ASC");
    $arrayvalues=[];
    while ($row = mysqli_fetch_array($selectquery)) {
        $contractno=$row['CLD_CONTRACT_NO'];
        $location=$row['LML_LOCATION'];
        $referenceno=$row['LML_REF_NO'];
        $workorderno=$row['LML_WORKER_ORDER_NO'];
        $recipient=$row['LML_RECEIPANT'];
        $dateofentered=$row['LML_DATE_OF_ENTERED'];
        $dateofcompleted=$row['LML_DATE_OF_COMPLETED'];
        $amount=$row['LML_AMOUNT'];
        $noofworkmen=$row['LML_NO_OF_WORKMEN'];
        $workedinday=$row['LML_HRS_WORKED_IN_DAY'];
        $manhours=$row['LML_MAN_HOURS'];
        $verificationdate=$row['LML_VERIFICATION_DATE'];
        $remarks=$row['LML_REMARKS'];
        $jobstatus=$row['LJS_STATUS'];
        $verificationstatus=$row['LVS_STATUS'];
        $username=$row['ULD_USERNAME'];
        $timestamp=$row['LML_TIMESTAMP'];
        $timestamp=date('d-m-Y H:i:s',strtotime($timestamp));
        $primaykeyid=$row['LML_ID'];
        $arrayvalues[]=(object)['rowid'=>$primaykeyid,'contractno'=>$contractno,'location'=>$location,'refno'=>$referenceno,'workorderno'=>$workorderno,'receipant'=>$recipient,'dateifentered'=>$dateofentered,'dateofcompleted'=>$dateofcompleted,'amount'=>$amount,'noofworkmen'=>$noofworkmen,'workinday'=>$workedinday,'manhours'=>$manhours,'verificationdate'=>$verificationdate,'remarks'=>$remarks,'jobstatus'=>$jobstatus,'verificationstatus'=>$verificationstatus,'userstamp'=>$username,'timestamp'=>$timestamp];
    }
    echo json_encode($arrayvalues);
}
elseif($_REQUEST['option']=='locationsaveupdate')
{
    $contractno=$_REQUEST['ML_lb_contractno'];
    $refno=$_REQUEST['referencenohidden'];
    $workorderno=$_REQUEST['ML_tb_workorderno'];
    $location=$_REQUEST['ML_ta_locaiton'];
    $recipient=$_REQUEST['ML_lb_recipient'];
    if($recipient=='SELECT')
    {$recipient='';}
    $dateofentered=$_REQUEST['ML_tb_dateofentered'];
    if($dateofentered!='')
    {$dateofentered=date('Y-m-d',strtotime($dateofentered));}
    $dateofcompleted=$_REQUEST['ML_tb_dateofcompleted'];
    if($dateofcompleted!='')
    {$dateofcompleted=date('Y-m-d',strtotime($dateofcompleted));}
    $amount=$_REQUEST['ML_tb_amount'];
    $noofworkmen=$_REQUEST['ML_tb_workmen'];
    $workedinday=$_REQUEST['ML_tb_hoursworked'];
    $manhours=$_REQUEST['ML_tb_manhours'];
    $jobstatus=$_REQUEST['ML_lb_jobstatus'];
    if($jobstatus=='SELECT')
    {$jobstatus='';}
    $dateofverificaiton=$_REQUEST['ML_tb_dateofverification'];
    if($dateofcompleted!='')
    {$dateofverificaiton=date('Y-m-d',strtotime($dateofverificaiton));}
    $verificationstatus=$_REQUEST['ML_lb_verificaitonstatus'];
    if($verificationstatus=='SELECT')
    {$verificationstatus='';}
    $remarks=$_REQUEST['ML_ta_remark'];
    $remarks=$con->real_escape_string($remarks);
    $callquery="CALL SP_MAINTAIN_LOCATION_ENTRY_UPDATE('$contractno','$location','$refno','$workorderno','$recipient','$dateofentered','$dateofcompleted','$amount','$noofworkmen','$workedinday','$manhours','$jobstatus','$dateofverificaiton','$verificationstatus','$remarks','$UserStamp',@SUCCESS_MESSAGE)";
    $result = $con->query($callquery);
    if(!$result){
        die("CALL failed: (" . $con->errno . ") " . $con->error);
    }
    $select = $con->query('SELECT @SUCCESS_MESSAGE');
    $result = $select->fetch_assoc();
    $flag= $result['@SUCCESS_MESSAGE'];
    echo $flag;
}