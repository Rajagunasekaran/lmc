<?php
error_reporting(0);
set_include_path( get_include_path() . PATH_SEPARATOR . 'google-api-php-client-master/src' );
require_once 'google-api-php-client-master/src/Google/Client.php';
require_once 'google-api-php-client-master/src/Google/Service/Drive.php';
include 'google-api-php-client-master/src/Google/Service/Calendar.php';
include "CONNECTION.php";
include "GET_USERSTAMP.php";
include "CONFIG.php";
include "COMMON.php";
date_default_timezone_set('Asia/Singapore');
$USERSTAMP=$UserStamp;
$bucket_id=get_appbucket_id();
if($_REQUEST["option"]=="DATE")
{
    $date=$_REQUEST['date_change'];
    $ure_date=date('Y-m-d',strtotime($date));
    $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
    while($row=mysqli_fetch_array($uld_id)){
        $ure_uld_id=$row["ULD_ID"];
    }
    $sql="SELECT * FROM USER_ADMIN_REPORT_DETAILS WHERE ULD_ID='$ure_uld_id' AND UARD_DATE='$ure_date'";
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
if($_POST["option"]=="SINGLE DAY ENTRY")
{
    if(isset($_POST["option"])){
        $date = $_POST['URE_tb_date'];
        $seconddate="null";
        $attendance=$_POST['URE_lb_attendance'];
        $per_checbx=$_POST['permission'];
        $perm_time=$_POST['URE_lb_timing'];
        $session=$_POST['URE_lbl_session'];
        $project=$_POST['URE_selproject'];
        $reason=$_POST['URE_ta_reason'];
        $report=$_POST['URE_ta_report'];
        $bandwidth=$_POST['URE_tb_band'];
        $ampm=$_POST['URE_lb_ampm'];
        $project=$_POST['checkbox'];
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
        $logname=mysqli_query($con,"SELECT ULD_ID FROM VW_TS_ALL_EMPLOYEE_DETAILS WHERE ULD_LOGINID='$USERSTAMP'");
        while($row=mysqli_fetch_array($logname)){
            $loginid=$row["ULD_ID"];
        }
        $driveparentid=get_emp_folderid($loginid);
        $daterep=str_replace('-','',$date);
        $filename=$loginid.'_'.$daterep.'_'.date('His');
        $filedesc='USER REPORT ENTRY PAINT IMAGE';
        $uploadimgname=$bucket_id.'images/'.$filename.'.png';
        $data=str_replace('data:image/png;base64,','',$imagedata);
        $data = str_replace(' ','+',$data);
        $data = base64_decode($data);
        $options = ['gs' => ['Content-Type' => 'image/png']];
        $ctx = stream_context_create($options);
        file_put_contents($uploadimgname, $data, 0, $ctx);
        $finalvalue=insertFile($service,$filename,$filedesc,$driveparentid,'image/png',$uploadimgname);
        $filesid=$finalvalue[0];
        $fileflg=$finalvalue[1];
    }
    elseif((($attendance=="0") && ($ampm=="FULLDAY")) || (($attendance=="OD") && ($ampm=="FULLDAY"))){
        $filesid='';
        $fileflg=1;
    }
    if($perm_time=='SELECT')
    {
        $perm_time='';
    }
    else
    {
        $perm_time=$perm_time;
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
    $urc_id=mysqli_query($con,"SELECT URC_ID FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE ULD_LOGINID='$USERSTAMP'");
    while($row=mysqli_fetch_array($urc_id)){
        $ure_urc_id=$row["URC_ID"];
    }
    $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
    while($row=mysqli_fetch_array($uld_id)){
        $ure_uld_id=$row["ULD_ID"];
    }
    $present=mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID='1'");
    while($row=mysqli_fetch_array($present)){
        $ure_present_data=$row["AC_DATA"];
    }
    $absent=mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID='2'");
    while($row=mysqli_fetch_array($absent)){
        $ure_absent_data=$row["AC_DATA"];
    }
    $onduty=mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID='3'");
    while($row=mysqli_fetch_array($onduty)){
        $ure_onduty_data=$row["AC_DATA"];
    }
// for present radio button
    if($attendance=="1")
    {
        $report;
        $uard_morning_session=$ure_present_data;
        $uard_afternoon_session =$ure_present_data;
        $projectid;
        $reason='';
        $filesid;
    }
//  for onduty radio button
    if($attendance=="OD")
    {
        if($ampm=="AM")
        {
            $uard_morning_session =$ure_onduty_data;
            $uard_afternoon_session =$ure_present_data;
            $reason;
            $projectid;
            $report;
            $filesid;
        }
        elseif($ampm=="PM")
        {
            $uard_morning_session =$ure_present_data;
            $uard_afternoon_session =$ure_onduty_data;
            $reason;
            $projectid;
            $report;
            $filesid;
        }
        elseif($ampm=="FULLDAY")
        {

            $reason;
            $uard_morning_session=$ure_onduty_data;
            $uard_afternoon_session =$ure_onduty_data;
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
            $uard_morning_session =$ure_absent_data;
            $uard_afternoon_session =$ure_present_data;
            $reason;
            $projectid;
            $report;
            $filesid;
        }
        elseif($ampm=="PM")
        {
            $uard_morning_session =$ure_present_data;
            $uard_afternoon_session =$ure_absent_data;
            $reason;
            $projectid;
            $report;
            $filesid;
        }
        elseif($ampm=="FULLDAY")
        {

            $reason;
            $uard_morning_session=$ure_absent_data;
            $uard_afternoon_session =$ure_absent_data;
            $report='';
            $filesid='';
            $projectid='';
        }
    }
    if($attendance=="1")
    {
        $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =5 AND CGN_ID='5'");
        while($row=mysqli_fetch_array($attend)){
            $ure_attendance=$row["AC_DATA"];
        }
    }
    if(($attendance=="0") && (($ampm=="AM") || ($ampm=="PM")))
    {
        $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =4 AND CGN_ID='5'");
        while($row=mysqli_fetch_array($attend)){
            $ure_attendance=$row["AC_DATA"];
        }
    }
    elseif(($attendance=="0") && ($ampm=="FULLDAY"))
    {
        $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =6 AND CGN_ID='5'");
        while($row=mysqli_fetch_array($attend)){
            $ure_attendance=$row["AC_DATA"];
        }
    }
    if(($attendance=="OD") && (($ampm=="AM") || ($ampm=="PM")))
    {
        $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =8 AND CGN_ID='5'");
        while($row=mysqli_fetch_array($attend)){
            $ure_attendance=$row["AC_DATA"];
        }
    }
    elseif(($attendance=="OD") && ($ampm=="FULLDAY"))
    {
        $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =7 AND CGN_ID='5'");
        while($row=mysqli_fetch_array($attend)){
            $ure_attendance=$row["AC_DATA"];
        }
    }
    $report= $con->real_escape_string($report);
    $reason= $con->real_escape_string($reason);
    if($fileflg!=0){
    $result = $con->query("CALL SP_TS_DAILY_REPORT_INSERT('$report','$reason','$finaldate',$seconddate,$ure_urc_id,'$USERSTAMP','$perm_time','$ure_attendance','$projectid','$uard_morning_session','$uard_afternoon_session','$filesid','$USERSTAMP',@success_flag)");
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
        $flagarry=[$flag];
    }else{
        $retnflag=0;
        $flagarry=[$retnflag,$driveparentid];
    }
    echo json_encode($flagarry);
}
if($_POST["option"]=="MULTIPLE DAY ENTRY")
{
    $firstdate = $_POST['URE_ta_fromdate'];
    $seconddate=$_POST['URE_ta_todate'];
    $attendance=$_POST['URE_lb_attdnce'];
    $perm_time='';
    $project='';
    $reason=$_POST['URE_ta_reason'];
    $report='';
    $filesid='';
    $project='';
//    $imagedata=$_POST['string'];
//    $logidname=mysqli_query($con,"SELECT ULD_ID FROM VW_TS_ALL_EMPLOYEE_DETAILS WHERE ULD_LOGINID='$USERSTAMP'");
//    while($row=mysqli_fetch_array($logidname)){
//        $loginname=$row["ULD_ID"];
//    }
//    $sdaterep=str_replace('-','',$firstdate);
//    $edaterep=str_replace('-','',$seconddate);
//    $filename=$loginname.'_'.$sdaterep.'_'.$edaterep.'_'.date('His');
//    $filedesc='USER REPORT MULTIPLE DAY ENTRY PAINT IMAGE';
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
    $fdate = date('Y-m-d',strtotime($firstdate));
    $tdate = date('Y-m-d',strtotime($seconddate));
    $urc_id=mysqli_query($con,"SELECT URC_ID FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE ULD_LOGINID='$USERSTAMP'");
    while($row=mysqli_fetch_array($urc_id)){
        $ure_urc_id=$row["URC_ID"];
    }
    $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
    while($row=mysqli_fetch_array($uld_id)){
        $ure_uld_id=$row["ULD_ID"];
    }
    $absent=mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID='2'");
    while($row=mysqli_fetch_array($absent)){
        $ure_absent_data=$row["AC_DATA"];
    }
    $onduty=mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID='3'");
    while($row=mysqli_fetch_array($onduty)){
        $ure_onduty_data=$row["AC_DATA"];
    }
    if($attendance=="OD")
    {
        $reason;
        $uard_morning_session=$ure_onduty_data;
        $uard_afternoon_session =$ure_onduty_data;
        $report='';
        $filesid='';
        $projectid='';

    }
    if($attendance=="0")
    {
        $reason;
        $uard_morning_session=$ure_absent_data;
        $uard_afternoon_session =$ure_absent_data;
        $report='';
        $filesid='';
        $projectid='';
    }
    if($attendance=="0")
    {
        $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =6 AND CGN_ID='5'");
        while($row=mysqli_fetch_array($attend)){
            $ure_attendance=$row["AC_DATA"];
        }
    }
    if($attendance=="OD")
    {
        $attend= mysqli_query($con,"select AC_DATA from ATTENDANCE_CONFIGURATION where AC_ID =7 AND CGN_ID='5'");
        while($row=mysqli_fetch_array($attend)){
            $ure_attendance=$row["AC_DATA"];
        }
    }


    $report= $con->real_escape_string($report);
    $reason= $con->real_escape_string($reason);
    $result = $con->query("CALL SP_TS_DAILY_REPORT_INSERT('$report','$reason','$fdate','$tdate',$ure_urc_id,'$USERSTAMP','$perm_time','$ure_attendance','$projectid','$uard_morning_session','$uard_afternoon_session','$filesid','$USERSTAMP',@success_flag)");
    if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
    $select = $con->query('SELECT @success_flag');
    $result = $select->fetch_assoc();
    $flag= $result['@success_flag'];

    echo $flag;
}
if($_REQUEST['option']=='BETWEEN DATE')
{
    $fdate=$_REQUEST['fromdate'];
    $tdate=$_REQUEST['todate'];
    $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
    while($row=mysqli_fetch_array($uld_id)){
        $ure_uld_id=$row["ULD_ID"];
    }

    $fromdate = date('Y-m-d',strtotime($fdate));
    $todate = date('Y-m-d',strtotime($tdate));
    $ure_date_array=array();
    $sql= mysqli_query($con,"SELECT DISTINCT DATE_FORMAT(UARD_DATE,'%d-%m-%Y') AS UARD_DATE FROM USER_ADMIN_REPORT_DETAILS WHERE UARD_DATE BETWEEN '$fromdate' AND '$todate' and ULD_ID='$ure_uld_id'");
    while($row=mysqli_fetch_array($sql)){
        $ure_date_array[]=$row["UARD_DATE"];
    }
    echo json_encode($ure_date_array);
}
if($_REQUEST['option']=='PRESENT')
{
    $row=get_projectentry();
    if(count($row)!=0)
    {
        $flag=1;
    }
    else
    {
        $flag=0;
    }
    echo $flag;
}
if($_REQUEST['option']=='HALFDAYABSENT')
{
    $row=get_projectentry();
    if(count($row)!=0)
    {
        $flag=1;
    }
    else
    {
        $flag=0;
    }
    echo $flag;
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
        $fileflag=1;
    }
    catch (Exception $e)
    {
        $fileflag=0;
    }
    $finalarry=[$fileid,$fileflag];
    return $finalarry;
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