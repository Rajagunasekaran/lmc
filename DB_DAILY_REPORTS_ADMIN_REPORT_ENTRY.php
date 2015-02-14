<?php
error_reporting(0);
set_include_path( get_include_path() . PATH_SEPARATOR . 'google-api-php-client-master/src' );
require_once 'google-api-php-client-master/src/Google/Client.php';
require_once 'google-api-php-client-master/src/Google/Service/Drive.php';
include 'google-api-php-client-master/src/Google/Service/Calendar.php';
include "CONNECTION.php";
include "GET_USERSTAMP.php";
include "COMMON.php";
include "CONFIG.php";
date_default_timezone_set('Asia/Singapore');
$USERSTAMP=$UserStamp;
$bucket_id=get_appbucket_id();
$driveparentid=get_parentfolder_id();
if($_REQUEST["option"]=="login_id"){
    $ADM_uld_id=$_REQUEST['login_id'];

    $min_date=mysqli_query($con,"SELECT UA_JOIN_DATE FROM USER_ACCESS where ULD_ID='$ADM_uld_id' AND UA_TERMINATE IS NULL");
    while($row=mysqli_fetch_array($min_date)){
        $mindate_array=$row["UA_JOIN_DATE"];
        $min_date = date('d-m-Y',strtotime($mindate_array));
    }
    $get_project_array=get_projectentry($ADM_uld_id);
    $finalvalue=array($min_date,$get_project_array);
    echo JSON_ENCODE($finalvalue);
}
if($_REQUEST["option"]=="LOGINID"){
    $ADM_uld_id=$_REQUEST['login_id'];

    $min_date=mysqli_query($con,"SELECT UA_JOIN_DATE FROM USER_ACCESS where ULD_ID='$ADM_uld_id' AND UA_TERMINATE IS NULL");
    while($row=mysqli_fetch_array($min_date)){
        $mindate_array=$row["UA_JOIN_DATE"];
        $min_date = date('d-m-Y',strtotime($mindate_array));
    }
    echo $min_date;
}
if($_REQUEST["option"]=="DATE")
{
    $date=$_REQUEST['date_change'];
    $ADM_uld_id=$_REQUEST['login_id'];
    $ADM_date=date('Y-m-d',strtotime($date));

    $sql="SELECT * FROM USER_ADMIN_REPORT_DETAILS WHERE ULD_ID='$ADM_uld_id' AND UARD_DATE='$ADM_date'";
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
    echo $flag;
}
if($_POST["choice"]=="SINGLE DAY ENTRY")
{
    if(isset($_POST["option"])){
        $date = $_POST['ARE_tb_date'];
        $seconddate="null";
        $attendance=$_POST['ARE_lb_attendance'];
        $perm_time=$_POST['ARE_lb_timing'];
        $reason=$_POST['ARE_ta_reason'];
        $report=$_POST['ARE_ta_report'];
        $bandwidth=$_POST['ARE_tb_band'];
        $ampm=$_POST['ARE_lb_ampm'];
        $project=$_POST['checkbox'];
        $ADM_uld_id=$_POST['ARE_lb_loginid'];
        $finaldate = date('Y-m-d',strtotime($date));
    }
    $imagedata=$_POST['string'];
    $drive = new Google_Client();
    $drive->setClientId($ClientId);
    $drive->setClientSecret($ClientSecret);
    $drive->setRedirectUri($RedirectUri);
    $drive->setScopes(array($DriveScopes,$CalenderScopes));
    $drive->setAccessType('online');
    $authUrl = $drive->createAuthUrl();
    $refresh_token= $Refresh_Token;
    $drive->refreshToken($refresh_token);
    $service = new Google_Service_Drive($drive);
    if(($attendance=="1") || (($attendance=="0") && (($ampm=="AM") || ($ampm=="PM"))) || (($attendance=="OD") && (($ampm=="AM") || ($ampm=="PM")))){
        $daterep=str_replace('-','',$date);
        $filename=$ADM_uld_id.'_'.$daterep.'_'.date('His');
        $filedesc='ADMIN REPORT ENTRY PAINT IMAGE';
        $uploadimgname=$bucket_id.'images/'.$filename.'.png';
        $data=str_replace('data:image/png;base64,','',$imagedata);
        $data = str_replace(' ','+',$data);
        $data = base64_decode($data);
        $options = ['gs' => ['Content-Type' => 'image/png']];
        $ctx = stream_context_create($options);
        file_put_contents($uploadimgname, $data, 0, $ctx);
        $filesid=insertFile($service,$filename,$filedesc,$driveparentid,'image/png',$uploadimgname);
    }
    elseif((($attendance=="0") && ($ampm=="FULLDAY")) || (($attendance=="OD") && ($ampm=="FULLDAY"))){
        $filesid='';
    }
    $length=count($project);
    $projectid;
    for($i=0;$i<$length;$i++){
        if($i==0){
            $projectid=$project[$i];
        }
        else{
            $projectid=$projectid .",".$project[$i];
        }
    }
    $projectid;
    if($perm_time=='SELECT')
    {
        $perm_time='';
    }
    else
    {
        $perm_time=$perm_time;
    }
    $urc_id=mysqli_query($con,"SELECT URC_ID FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE ULD_LOGINID='$USERSTAMP'");
    while($row=mysqli_fetch_array($urc_id)){
        $ADM_urc_id=$row["URC_ID"];
    }
    $userstamp_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
    while($row=mysqli_fetch_array($userstamp_id)){
        $ADM_userstamp_id=$row["ULD_ID"];
    }
    $uld_id=mysqli_query($con,"select ULD_LOGINID from USER_LOGIN_DETAILS where ULD_ID='$ADM_uld_id'");
    while($row=mysqli_fetch_array($uld_id)){
        $login_id=$row["ULD_LOGINID"];
    }
    $present=mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID='1'");
    while($row=mysqli_fetch_array($present)){
        $ADM_present_data=$row["AC_DATA"];
    }
    $absent=mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID='2'");
    while($row=mysqli_fetch_array($absent)){
        $ADM_absent_data=$row["AC_DATA"];
    }
    $onduty=mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID='3'");
    while($row=mysqli_fetch_array($onduty)){
        $ADM_onduty_data=$row["AC_DATA"];
    }
// for present radio button
    if($attendance=="1")
    {
        $report;
        $uard_morning_session=$ADM_present_data;
        $uard_afternoon_session =$ADM_present_data;
        $projectid;
        $reason='';
        $filesid;
    }

//  for onduty radio button
    if($attendance=="OD")
    {
        if($ampm=="AM")
        {
            $uard_morning_session =$ADM_onduty_data;
            $uard_afternoon_session =$ADM_present_data;
            $reason;
            $projectid;
            $report;
            $filesid;
        }
        elseif($ampm=="PM")
        {
            $uard_morning_session =$ADM_present_data;
            $uard_afternoon_session =$ADM_onduty_data;
            $reason;
            $projectid;
            $report;
            $filesid;
        }
        elseif($ampm=="FULLDAY")
        {

            $reason;
            $uard_morning_session=$ADM_onduty_data;
            $uard_afternoon_session =$ADM_onduty_data;
            $report='';
            $filesid='';
            $projectid='';
        }

    }
// for absent radio button
    if($attendance=="0")
    {
        if($ampm=="AM")
        {
            $uard_morning_session =$ADM_absent_data;
            $uard_afternoon_session =$ADM_present_data;
            $reason;
            $projectid;
            $report;
            $filesid;
        }
        elseif($ampm=="PM")
        {
            $uard_morning_session =$ADM_present_data;
            $uard_afternoon_session =$ADM_absent_data;
            $reason;
            $projectid;
            $report;
            $filesid;
        }
        elseif($ampm=="FULLDAY")
        {

            $reason;
            $uard_morning_session=$ADM_absent_data;
            $uard_afternoon_session =$ADM_absent_data;
            $report='';
            $filesid='';
            $projectid='';
        }
    }
    if($attendance=="1")
    {
        $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =5 AND CGN_ID='5'");
        while($row=mysqli_fetch_array($attend)){
            $ADM_attendance=$row["AC_DATA"];
        }
    }
    if(($attendance=="0") && (($ampm=="AM") || ($ampm=="PM")))
    {
        $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =4 AND CGN_ID='5'");
        while($row=mysqli_fetch_array($attend)){
            $ADM_attendance=$row["AC_DATA"];
        }
    }
    elseif(($attendance=="0") && ($ampm=="FULLDAY"))
    {
        $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =6 AND CGN_ID='5'");
        while($row=mysqli_fetch_array($attend)){
            $ADM_attendance=$row["AC_DATA"];
        }
    }
    if(($attendance=="OD") && (($ampm=="AM") || ($ampm=="PM")))
    {
        $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =8 AND CGN_ID='5'");
        while($row=mysqli_fetch_array($attend)){
            $ADM_attendance=$row["AC_DATA"];
        }
    }
    elseif(($attendance=="OD") && ($ampm=="FULLDAY"))
    {
        $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =7 AND CGN_ID='5'");
        while($row=mysqli_fetch_array($attend)){
            $ADM_attendance=$row["AC_DATA"];
        }
    }
    $report= $con->real_escape_string($report);
    $reason= $con->real_escape_string($reason);
    $result = $con->query("CALL SP_TS_DAILY_REPORT_INSERT('$report','$reason','$finaldate',$seconddate,$ADM_urc_id,'$login_id','$perm_time','$ADM_attendance','$projectid','$uard_morning_session','$uard_afternoon_session','$filesid','$USERSTAMP',@success_flag)");
    if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
    $select = $con->query('SELECT @success_flag');
    $result = $select->fetch_assoc();
    $flag= $result['@success_flag'];
    if($flag!=1 && ($filesid!='' || $filesid!=null))
    {
        $file = $service->files->get($filesid);
        $deletUrl = $file->getTitle();
        $delpath= $bucket_id.'images/'.$deletUrl.'.png';
        unlink($delpath);
        delete_file($service,$filesid);
    }
    echo $flag;
}
if($_POST["choice"]=="MULTIPLE DAY ENTRY")
{
    if(isset($_POST["option"])){
        $firstdate = $_POST['ARE_tb_sdate'];
        $seconddate=$_POST['ARE_tb_edate'];
        $attendance=$_POST['ARE_lb_attdnce'];
        $perm_time='';
        $reason=$_POST['ARE_ta_reason'];
        $report='';
        $project='';
        $filesid='';
        $ADM_uld_id=$_POST['ARE_lb_lgnid'];
        $first_date = date('Y-m-d',strtotime($firstdate));
        $second_date = date('Y-m-d',strtotime($seconddate));
    }
//    $imagedata=$_POST['string'];
//    $sdaterep=str_replace('-','',$firstdate);
//    $edaterep=str_replace('-','',$seconddate);
//    if($ADM_uld_id=='SELECT'){
//        $ADM_uld_id='ALL';
//    }
//    else{
//        $ADM_uld_id=$ADM_uld_id;
//    }
//    $filename=$ADM_uld_id.'_'.$sdaterep.'_'.$edaterep.'_'.date('His');
//    $filedesc='ADMIN REPORT MULTIPLE DAY ENTRY PAINT IMAGE';
//    $uploadimgname=$bucket_id.'images/'.$filename.'.png';
//    $data=str_replace('data:image/png;base64,','',$imagedata);
//    $data = str_replace(' ','+',$data);
//    $data = base64_decode($data);
//    $options = ['gs' => ['Content-Type' => 'image/png']];
//    $ctx = stream_context_create($options);
//    file_put_contents($uploadimgname, $data, 0, $ctx);
//    $drive = new Google_Client();
//    $drive->setClientId($ClientId);
//    $drive->setClientSecret($ClientSecret);
//    $drive->setRedirectUri($RedirectUri);
//    $drive->setScopes(array($DriveScopes,$CalenderScopes));
//    $drive->setAccessType('online');
//    $authUrl = $drive->createAuthUrl();
//    $refresh_token= $Refresh_Token;
//    $drive->refreshToken($refresh_token);
//    $service = new Google_Service_Drive($drive);
//    $filesid=insertFile($service,$filename,$filedesc,'0Bzvv-O9jT9r_YXk3Uld5eXdOTE0','image/png',$uploadimgname);
    if($ADM_uld_id=='SELECT')
    {
        $ADM_uld_id='';
    }
    else
    {
        $ADM_uld_id=$ADM_uld_id;
    }
    $urc_id=mysqli_query($con,"SELECT URC_ID FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE ULD_LOGINID='$USERSTAMP'");
    while($row=mysqli_fetch_array($urc_id)){
        $ADM_urc_id=$row["URC_ID"];
    }
    $userstamp_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
    while($row=mysqli_fetch_array($userstamp_id)){
        $ADM_userstamp_id=$row["ULD_ID"];
    }
    $uld_id=mysqli_query($con,"select ULD_LOGINID from USER_LOGIN_DETAILS where ULD_ID='$ADM_uld_id'");
    while($row=mysqli_fetch_array($uld_id)){
        $login_id=$row["ULD_LOGINID"];
    }
    $absent=mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID='2'");
    while($row=mysqli_fetch_array($absent)){
        $ADM_absent_data=$row["AC_DATA"];
    }
    $onduty=mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID='3'");
    while($row=mysqli_fetch_array($onduty)){
        $ADM_onduty_data=$row["AC_DATA"];
    }
    if($attendance=="OD")
    {
        $reason;
        $uard_morning_session=$ADM_onduty_data;
        $uard_afternoon_session =$ADM_onduty_data;
        $report='';
        $filesid='';
        $projectid='';
    }

    if($attendance=="0")
    {
        $reason;
        $uard_morning_session=$ADM_absent_data;
        $uard_afternoon_session =$ADM_absent_data;
        $report='';
        $filesid='';
        $projectid='';
    }
    if($attendance=="0")
    {
        $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =6 AND CGN_ID='5'");
        while($row=mysqli_fetch_array($attend)){
            $ADM_attendance=$row["AC_DATA"];
        }
    }
    if($attendance=="OD")
    {
        $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =7 AND CGN_ID='5'");
        while($row=mysqli_fetch_array($attend)){
            $ADM_attendance=$row["AC_DATA"];
        }
    }

    $report= $con->real_escape_string($report);
    $reason= $con->real_escape_string($reason);

    $result = $con->query("CALL SP_TS_DAILY_REPORT_INSERT('$report','$reason','$first_date','$second_date',$ADM_urc_id,'$login_id','$perm_time','$ADM_attendance','$projectid','$uard_morning_session','$uard_afternoon_session','$filesid','$USERSTAMP',@success_flag)");
    if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
    $select = $con->query('SELECT @success_flag');
    $result = $select->fetch_assoc();
    $flag= $result['@success_flag'];

    echo $flag;
}
if($_REQUEST["option"]=="ODDATE")
{
    $date=$_REQUEST['date_change'];
    $ADM_date=date('Y-m-d',strtotime($date));
    $sql="SELECT * FROM ONDUTY_ENTRY_DETAILS WHERE OED_DATE='$ADM_date'";
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
    echo $flag;
}
if($_REQUEST["option"]=="ONDUTY REPORT ENTRY")
{
    $ondutydate=$_POST['ARE_tb_dte'];
    $ondutydes=$_POST['ARE_ta_des'];
    $oddate = date('Y-m-d',strtotime($ondutydate));
    $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
    while($row=mysqli_fetch_array($uld_id)){
        $ADM_uld_id=$row["ULD_ID"];
    }
    $ondutydes= $con->real_escape_string($ondutydes);

    $sql="INSERT INTO ONDUTY_ENTRY_DETAILS (OED_DATE,OED_DESCRIPTION,ULD_ID) VALUES ('$oddate','$ondutydes','$ADM_uld_id')";
    if (!mysqli_query($con,$sql)) {
        die('Error: ' . mysqli_error($con));

        $flag="Record not saved";
    }
    else
    {
        $msg= mysqli_query($con,"select EMC_DATA from ERROR_MESSAGE_CONFIGURATION where EMC_ID='3'");
        while($row=mysqli_fetch_array($msg)){
            $flag_msg=$row["EMC_DATA"];
        }
    }
    $flag= $flag_msg;
    echo $flag;
}
if($_REQUEST['option']=='BETWEEN DATE')
{
    $fdate=$_REQUEST['fromdate'];
    $tdate=$_REQUEST['todate'];
    $ADM_uld_id=$_REQUEST['loginid'];
    $fromdate = date('Y-m-d',strtotime($fdate));
    $todate = date('Y-m-d',strtotime($tdate));
    $ure_date_array=array();
    if($ADM_uld_id!='SELECT')
    {
        $sql= mysqli_query($con,"SELECT DISTINCT DATE_FORMAT(UARD_DATE,'%d-%m-%Y') AS UARD_DATE FROM USER_ADMIN_REPORT_DETAILS WHERE UARD_DATE BETWEEN '$fromdate' AND '$todate' AND ULD_ID=$ADM_uld_id ORDER BY UARD_DATE");
        while($row=mysqli_fetch_array($sql)){
            $ure_date_array[]=$row["UARD_DATE"];
        }
    }
    else
    {
        $sql= mysqli_query($con,"SELECT DISTINCT DATE_FORMAT(UARD_DATE,'%d-%m-%Y') AS UARD_DATE FROM USER_ADMIN_REPORT_DETAILS WHERE UARD_DATE BETWEEN '$fromdate' AND '$todate'  ORDER BY UARD_DATE");
        while($row=mysqli_fetch_array($sql)){
            $ure_date_array[]=$row["UARD_DATE"];
        }
    }
    echo JSON_ENCODE($ure_date_array);
}
if($_REQUEST['option']=='ALLEMPDATE')
{
    $mindate=mysqli_query($con,"SELECT MIN(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS ORDER BY UARD_DATE");
    while($row=mysqli_fetch_array($mindate)){
        $allmindate=$row["UARD_DATE"];
    }
    $allmindate = date('d-m-Y',strtotime($allmindate));
    echo $allmindate;
}
if($_REQUEST['option']=='PRESENT')
{
    $emptyflag=0;
    $rprtdate=$_REQUEST['reportdate'];
    $ure_uld_id=$_REQUEST['loginid'];
    $rprtdate = date('Y-m-d',strtotime($rprtdate));
    $uld_id=mysqli_query($con,"select ULD_LOGINID from USER_LOGIN_DETAILS where ULD_ID='$ure_uld_id'");
    while($row=mysqli_fetch_array($uld_id)){
        $logind=$row["ULD_LOGINID"];
    }
    if($logind==$USERSTAMP)
    {
        $sql="SELECT * FROM EMPLOYEE_CHECK_IN_OUT_DETAILS WHERE ULD_ID='$ure_uld_id' AND ECIOD_DATE='$rprtdate'";
        $sql_result= mysqli_query($con,$sql);
        $row=mysqli_num_rows($sql_result);
        if($row>0)
        {
            $flag=0;//true
        }
        else
        {
            $flag=1;//false
        }
    }
    else
    {
        $emptyflag=1;
    }
    $value=array($flag,$emptyflag);
    echo json_encode($value);
}
if($_REQUEST['option']=='HALFDAYABSENT')
{
    $emptyflag=0;
    $rprtdate=$_REQUEST['reportdate'];
    $ure_uld_id=$_REQUEST['logind'];
    $rprtdate = date('Y-m-d',strtotime($rprtdate));
    $uld_id=mysqli_query($con,"select ULD_LOGINID from USER_LOGIN_DETAILS where ULD_ID='$ure_uld_id'");
    while($row=mysqli_fetch_array($uld_id)){
        $logind=$row["ULD_LOGINID"];
    }
    if($logind==$USERSTAMP)
    {
        $sql="SELECT * FROM EMPLOYEE_CHECK_IN_OUT_DETAILS WHERE ULD_ID='$ure_uld_id' AND ECIOD_DATE='$rprtdate'";
        $sql_result= mysqli_query($con,$sql);
        $row=mysqli_num_rows($sql_result);
        if($row>0)
        {
            $flag=0;//true
        }
        else
        {
            $flag=1;//false
        }
    }
    else
    {
        $emptyflag=1;
    }
    $value=array($flag,$emptyflag);
    echo json_encode($value);
}
function insertFile($service, $title, $description, $parentId,$mimeType,$uploadfilename)
{
    $file = new Google_Service_Drive_DriveFile();
    $file->setTitle($title);
    $file->setDescription($description);
    $file->setMimeType($mimeType);
    if ($parentId != null) {
        $parent = new Google_Service_Drive_ParentReference();
        $parent->setId($parentId);
        $file->setParents(array($parent));
    }
    try
    {
        $data =file_get_contents($uploadfilename);
        $createdFile = $service->files->insert($file, array(
            'data' => $data,
            'mimeType' => $mimeType,
            'uploadType' => 'media',
        ));

        $fileid = $createdFile->getId();
    }
    catch (Exception $e)
    {
        echo "An error occurred: " . $e->getMessage();
    }
    return $fileid;
}
function delete_file($service,$fileid){

    try {
        $f=$service->files->delete($fileid);
    } catch (Exception $e) {
        $f= "An error occurred: " . $e->getMessage();
    }
    return $f;
}
?>