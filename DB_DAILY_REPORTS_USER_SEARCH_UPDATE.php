<?php
require_once 'google/appengine/api/mail/Message.php';
use google\appengine\api\mail\Message;
set_include_path( get_include_path() . PATH_SEPARATOR . 'google-api-php-client-master/src' );
require_once 'google-api-php-client-master/src/Google/Client.php';
require_once 'google-api-php-client-master/src/Google/Service/Drive.php';
include 'google-api-php-client-master/src/Google/Service/Calendar.php';
error_reporting(0);
if(isset($_REQUEST)){
    include "CONNECTION.php";
    include "GET_USERSTAMP.php";
    include "COMMON.php";
    include "CONFIG.php";
    date_default_timezone_set('Asia/Singapore');
    $timeZoneFormat=getTimezone();
    $USERSTAMP=$UserStamp;
    $bucket_id=get_appbucket_id();
    $driveparentid=get_parentfolder_id();
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
    if($_REQUEST['option']=='SEARCH')
    {
        $sdate =$_REQUEST['start_date'];
        $edate =$_REQUEST['end_date'];
        $startdate = date('Y-m-d',strtotime($sdate));//echo $startdate;
        $enddate = date('Y-m-d',strtotime($edate));//echo $enddate;
        $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
        while($row=mysqli_fetch_array($uld_id)){
            $ure_uld_id=$row["ULD_ID"];
        }
        $ure_values=array();
        $date= mysqli_query($con,"SELECT UARD_ID,UARD_REPORT,UARD_REASON,UARD_DATE,b.AC_DATA as UARD_PERMISSION, c.AC_DATA as UARD_ATTENDANCE,UARD.UARD_PSID,G.AC_DATA AS UARD_AM_SESSION,H.AC_DATA AS UARD_PM_SESSION,I.ULD_LOGINID AS ULD_ID,DATE_FORMAT(CONVERT_TZ(UARD.UARD_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T') AS UARD_TIMESTAMP,UARD.UARD_FILE_ID FROM USER_ADMIN_REPORT_DETAILS UARD
LEFT JOIN ATTENDANCE_CONFIGURATION b ON b.AC_ID=UARD.UARD_PERMISSION
left JOIN ATTENDANCE_CONFIGURATION c on c.AC_ID=UARD.UARD_ATTENDANCE
LEFT JOIN ATTENDANCE_CONFIGURATION G ON G.AC_ID=UARD.UARD_AM_SESSION
LEFT JOIN ATTENDANCE_CONFIGURATION H ON H.AC_ID=UARD.UARD_PM_SESSION
LEFT JOIN USER_LOGIN_DETAILS I ON I.ULD_ID=UARD.ULD_ID
where UARD_DATE BETWEEN '$startdate' AND '$enddate' AND UARD.ULD_ID='$ure_uld_id' ORDER BY UARD.UARD_DATE");
        while($row=mysqli_fetch_array($date)){
            $ure_id=$row["UARD_ID"];
            $ure_acdata=$row["AC_DATA"];
            $ure_date=$row["UARD_DATE"];
            $ure_date = date('d-m-Y',strtotime($ure_date));
            $ure_report=$row["UARD_REPORT"];
            $ure_userstamp=$row["ULD_ID"];
            $ure_timestamp=$row["UARD_TIMESTAMP"];
            $ure_reason=$row["UARD_REASON"];
            $ure_permission=$row["UARD_PERMISSION"];
            $ure_attendance=$row["UARD_ATTENDANCE"];
            $ure_pdid=$row["UARD_PSID"];
            $ure_morningsession=$row["UARD_AM_SESSION"];
            $ure_afternoonsession=$row["UARD_PM_SESSION"];
            $ure_fileid=$row['UARD_FILE_ID'];
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
            if($ure_fileid!=null || $ure_fileid!=''){
                $file = $service->files->get($ure_fileid);
                $downloadUrl = $file->getTitle();
                $path= $bucket_id.'images/'.$downloadUrl.'.png';
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
            if($ure_report!=null){
                $email_body='';
                $body_msg =explode("\n", $ure_report);
                $length=count($body_msg);
                for($i=0;$i<$length;$i++){
                    $email_body.=$body_msg[$i].'<br>';
                }
            }
            else{
                $email_body=null;
            }
            if($ure_reason!=null){
                $URE_reasontxt='';
                $URE_reason_msg =explode("\n", $ure_reason);
                $length=count($URE_reason_msg);
                for($i=0;$i<=$length;$i++){
                    $URE_reasontxt.=$URE_reason_msg[$i];
                    if($i!=$length)
                        $URE_reasontxt.='<br>';
                }
            }
            else{
                $URE_reasontxt=null;
            }
            $final_values=(object) ['id'=>$ure_id,'date' => $ure_date,'report' =>$email_body,'report1'=>$ure_report,'userstamp'=> $ure_userstamp,'timestamp'=>$ure_timestamp,'reason'=>$URE_reasontxt,'reason1'=>$ure_reason,'permission'=>$ure_permission,'attendance'=>$ure_attendance,'pdid'=>$ure_pdid,'morningsession'=>$ure_morningsession,'afternoonsession'=>$ure_afternoonsession,'imageurl'=>$base64];
            $ure_values[]=$final_values;
        }
        echo JSON_ENCODE($ure_values);
    }
    if($_POST['option']=='UPDATE')
    {
        if(isset($_POST["option"])){
            $date = $_POST['USRC_UPD_tb_date'];
            $id=$_POST['USRC_UPD_rd_flxtbl'];
            $attendance=$_POST['USRC_UPD_lb_attendance'];
            $perm_time=$_POST['USRC_UPD_lb_timing'];
            $session=$_POST['USRC_UPD_lbl_session'];
            $project=$_POST['USRC_UPD_selproject'];
            $reason=$_POST['USRC_UPD_ta_reason'];
            $report=$_POST['USRC_UPD_ta_report'];
            $ampm=$_POST['USRC_UPD_lb_ampm'];
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
            $daterep=str_replace('-','',$date);
            $filename=$loginid.'_'.$daterep.'_'.date('His');
            $filedesc='USER REPORT SEARCH/UPDATE PAINT IMAGE';
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
        $old_fid= mysqli_query($con,"SELECT UARD_FILE_ID FROM USER_ADMIN_REPORT_DETAILS WHERE UARD_ID=$id");
        while($row=mysqli_fetch_array($old_fid)){
            $old_fileid=$row["UARD_FILE_ID"];
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
//  for absent radio button
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
// for onduty radio button
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
        $result = $con->query("CALL SP_TS_DAILY_REPORT_SEARCH_UPDATE($id,'$report','$reason','$finaldate',$ure_urc_id,'$USERSTAMP','$perm_time','$ure_attendance','$projectid','$uard_morning_session','$uard_afternoon_session','$USERSTAMP','','$filesid',@success_flag)");
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
        if($flag==1 && ($old_fileid!='' || $old_fileid!=null))
        {
            $file = $service->files->get($old_fileid);
            $olddeletUrl = $file->getTitle();
            $deltpath= $bucket_id.'images/'.$olddeletUrl.'.png';
            unlink($deltpath);
            delete_file($service,$old_fileid);
        }
        if($flag==1){
            $select_admin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='ADMIN'";
            $select_sadmin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='SUPER ADMIN'";
            $admin_rs=mysqli_query($con,$select_admin);
            $sadmin_rs=mysqli_query($con,$select_sadmin);
            if($row=mysqli_fetch_array($admin_rs)){
                $admin=$row["ULD_LOGINID"];//get admin
            }
            if($row=mysqli_fetch_array($sadmin_rs)){
                $sadmin=$row["ULD_LOGINID"];//get super admin
            }
            $select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=7";
            $select_template_rs=mysqli_query($con,$select_template);
            if($row=mysqli_fetch_array($select_template_rs)){
                $mail_subject=$row["ETD_EMAIL_SUBJECT"];
                $body=$row["ETD_EMAIL_BODY"];
            }
            $header='<body>'.'<br>'.'<table border=1  width=2000><thead  bgcolor=#6495ed style=color:white><tr bgcolor=#498af3 align=center  height="40" ><th>EMPLOYEE NAME</th><th style="max-width:1000px; !important;" >OLD VALUE</th><th style="max-width:1000px; !important;" >NEW VALUE</th><th>USERSTAMP</th><th>TIMESTAMP</th></tr></thead>';
            $result = $con->query("CALL SP_TS_USER_ADMIN_REPORT_DETAILS_TICKLER_DATA('$ure_uld_id','$USERSTAMP',@TEMP_UARD_TICKLER_HISTORY)");
            if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
            $select = $con->query('SELECT @TEMP_UARD_TICKLER_HISTORY');
            $result = $select->fetch_assoc();
            $temp_tickler_history= $result['@TEMP_UARD_TICKLER_HISTORY'];
            $tickler_data=mysqli_query($con,"SELECT AE.EMPLOYEE_NAME,A.TH_OLD_VALUE,A.TH_NEW_VALUE,A.TH_USERSTAMP,DATE_FORMAT(CONVERT_TZ(TH_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T') AS T_TIMESTAMP FROM $temp_tickler_history A,VW_TS_ALL_EMPLOYEE_DETAILS AE WHERE  TABLE_NAME='USER_ADMIN_REPORT_DETAILS' AND A.ULD_ID = AE.ULD_ID ORDER BY TH_TIMESTAMP DESC ");
            $row=mysqli_num_rows($tickler_data);
            $x=$row;
            if($x>0){
                if($row=mysqli_fetch_array($tickler_data)){
                    $loginid=$row["EMPLOYEE_NAME"];
                    $old_value=$row["TH_OLD_VALUE"];
                    $old_value=htmlspecialchars($old_value);

                    $old_value_array=explode(",",$old_value);

                    $TH_arroldvalue='';
                    $TH_arrnewvalue='';
                    for($j=0;$j<count($old_value_array);$j++)
                    {
                        if($j==0){
                            $TH_arroldvalue=$old_value_array[$j];
                        }
                        else{
                            $TH_arroldvalue .=' , '.$old_value_array[$j];
                        }
                    }
                    $new_value=$row["TH_NEW_VALUE"];
                    $new_value=htmlspecialchars($new_value);

                    $new_value_array=explode(",",$new_value);
                    for($k=0;$k<count($new_value_array);$k++)
                    {
                        if($k==0){
                            $TH_arrnewvalue=$new_value_array[$k];
                        }
                        else{
                            $TH_arrnewvalue .=' , '.$new_value_array[$k];
                        }
                    }
                    $userstamp=$row["TH_USERSTAMP"];
                    $timestamp=$row["T_TIMESTAMP"];
                    $values=$header. "<tr><td>".$loginid."</td><td style=max-width:1000px; !important;>".$TH_arroldvalue."</td><td style=max-width:1000px; !important;>".$TH_arrnewvalue."</td><td >".$userstamp."</td><td nowrap>".$timestamp."</td></tr>";
                }
                $sub=str_replace("[LOGINID]","$loginid",$body);
                $sub=$sub.'<br>';
                $mail_options = [
                    "sender" => $admin,
                    "to" => $admin,
                    "cc" => $sadmin,
                    "subject" => $mail_subject,
                    "htmlBody" => $sub.$values
                ];
                try {
                    $message = new Message($mail_options);
                    $message->send();
                } catch (\InvalidArgumentException $e) {
                    echo $e;
                }
            }
            $drop_query="DROP TABLE $temp_tickler_history ";
            mysqli_query($con,$drop_query);
        }
        echo $flag;
    }
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