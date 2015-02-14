<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************REPORT MAIL TRIGGER *************************************//
//DONE BY:SAFI
//VER 0.07,SD:06/01/2015 ED:06/01/2015,TRACKER NO:74,DESC:ADDED GEOLOCATION AND LOGINID CHANGED AS EMPLOYEE NAME
//DONE BY:RAJA
//VER 0.06,SD:30/12/2014 ED:30/12/2014,TRACKER NO:74,DESC:joined the employee check in/out table with report
//DONE BY:LALITHA
//VER 0.05,SD:05/12/2014 ED:05/12/2014,TRACKER NO:74,DESC:Showned AM nd PM fr Onduty/Absent in Reason
//VER 0.04,SD:24/11/2014 ED:24/11/2014,TRACKER NO:74,DESC:Implemented If reason means updated Onduty/Absent with checked condition) nd changed query also(selected am/pm session,absent flg)
//VER 0.03,SD:22/11/2014 ED:22/11/2014,TRACKER NO:74,DESC:Updated date concat with subject in mail option
//VER 0.02,SD:18/11/2014 ED:20/11/2014,TRACKER NO:74,DESC:Updated Showned permission details in flex tble nd changed flxtbl query,Updated to showned point by point line fr report nd reason,Removed unwanted br tags,Updated hrs fr permission
//DONE BY:SAFIYULLAH
//VER 0.01-INITIAL VERSION, SD:31/10/2014 ED:31/10/2014,TRACKER NO:74
//*********************************************************************************************************//-->
<?php
require_once 'google/appengine/api/mail/Message.php';
use google\appengine\api\mail\Message;
include "COMMON_FUNCTIONS.php";
include "CONNECTION.php";
$currentdate=date("Y-m-d",strtotime("-1 days"));//yesterday DATE
$select_admin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='ADMIN'";
$select_sadmin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='SUPER ADMIN'";
$admin_rs=mysqli_query($con,$select_admin);
$sadmin_rs=mysqli_query($con,$select_sadmin);
$select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=4";
$select_template_rs=mysqli_query($con,$select_template);
if($row=mysqli_fetch_array($select_template_rs)){
    $mail_subject=$row["ETD_EMAIL_SUBJECT"];
    $body=$row["ETD_EMAIL_BODY"];
}
if($row=mysqli_fetch_array($admin_rs)){
    $admin=$row["ULD_LOGINID"];//get admin
}
if($row=mysqli_fetch_array($sadmin_rs)){
    $sadmin=$row["ULD_LOGINID"];//get super admin
}
$admin_name = substr($admin, 0, strpos($admin, '.'));
$sadmin_name = substr($sadmin, 0, strpos($sadmin, '.'));
$spladminname=$admin_name.'/'.$sadmin_name;
$spladminname=strtoupper($spladminname);
$sub=str_replace("[SADMIN]","$spladminname",$body);
$sub=str_replace("[DATE]",date("d-m-Y",strtotime("-1 days")),$sub);
$message='<html><body>'.'<br>'.'<h> '.$sub.'</h>'.'<br>'.'<br>'.'<table border=1  width=2200 ><thead  bgcolor=#6495ed style=color:white><tr  align="center"  height=2px ><td width=260><b>EMPLOYEE NAME</b></td><td width=1000><b>REPORT</b></td><td width=25 ><b>CLOCK  IN TIME</b></td><td width=260><b>CLOCK IN LOCATION</b></td> <td width=25 ><b>CLOCK OUT TIME</b></td><td width=260><b>CLOCK OUT LOCATION</b></td><td><b>REPORT LOCATION</b></td><td width=260><b>USERSTAMP</b></td><td width=150 nowrap><b>TIMESTAMP</b></td></tr></thead>';
$query="SELECT DISTINCT  EMP.EMPLOYEE_NAME AS EMPLOYEE_NAME, ECIOD.ECIOD_CHECK_IN_TIME, CIORL_IN.CIORL_LOCATION as ECIOD_CHECK_IN_LOCATION,ECIOD.ECIOD_CHECK_OUT_TIME, CIORL_OUT.CIORL_LOCATION as ECIOD_CHECK_OUT_LOCATION,AC.AC_DATA,A.UARD_REPORT,A.UARD_REASON,A.ABSENT_FLAG,G.AC_DATA AS UARD_AM_SESSION,H.AC_DATA AS UARD_PM_SESSION,B.ULD_LOGINID,R_LOCATION.CIORL_LOCATION AS REPORT_LOCATION,
        C.ULD_LOGINID AS USERSTAMP,DATE_FORMAT(CONVERT_TZ(A.UARD_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') AS UARD_TIMESTAMP FROM USER_ADMIN_REPORT_DETAILS A INNER JOIN USER_LOGIN_DETAILS B ON A.ULD_ID=B.ULD_ID   JOIN VW_TS_ALL_EMPLOYEE_DETAILS EMP on EMP.ULD_ID=A.ULD_ID INNER JOIN USER_LOGIN_DETAILS C ON A.UARD_USERSTAMP_ID=C.ULD_ID
        LEFT JOIN EMPLOYEE_CHECK_IN_OUT_DETAILS ECIOD ON ECIOD.ULD_ID = B.ULD_ID AND A.ULD_ID = ECIOD.ULD_ID AND A.UARD_DATE=ECIOD.ECIOD_DATE INNER JOIN USER_ACCESS D LEFT JOIN ATTENDANCE_CONFIGURATION AC ON A.UARD_PERMISSION=AC.AC_ID LEFT JOIN ATTENDANCE_CONFIGURATION G ON G.AC_ID=A.UARD_AM_SESSION LEFT JOIN CLOCK_IN_OUT_REPORT_LOCATION CIORL_IN ON ECIOD.ECIOD_CHECK_IN_LOCATION=CIORL_IN.CIORL_ID LEFT JOIN CLOCK_IN_OUT_REPORT_LOCATION CIORL_OUT ON ECIOD.ECIOD_CHECK_OUT_LOCATION=CIORL_OUT.CIORL_ID LEFT JOIN CLOCK_IN_OUT_REPORT_LOCATION R_LOCATION ON A.CIORL_ID = R_LOCATION.CIORL_ID
        LEFT JOIN ATTENDANCE_CONFIGURATION H ON H.AC_ID=A.UARD_PM_SESSION WHERE A.UARD_DATE='$currentdate' AND D.UA_TERMINATE IS NULL ORDER BY ULD_LOGINID ";



$sql=mysqli_query($con,$query);
$row=mysqli_num_rows($sql);

$x=$row;
if($x>0){
    while($row=mysqli_fetch_array($sql)){
        $adm_reprt=$row["UARD_REPORT"];
        $adm_userstamp=$row["USERSTAMP"];
        $adm_timestamp=$row["UARD_TIMESTAMP"];
        $adm_loginid=$row["EMPLOYEE_NAME"];
        $ure_reason_txt=$row["UARD_REASON"];
        $adm_permission=$row["AC_DATA"];
        $adm_absentflag=$row["ABSENT_FLAG"];
        $adm_morningsession=$row["UARD_AM_SESSION"];
        $adm_afternoonsession=$row["UARD_PM_SESSION"];
        $checkintime=$row["ECIOD_CHECK_IN_TIME"];
        $checkinlocation=$row["ECIOD_CHECK_IN_LOCATION"];
        $checkouttime=$row["ECIOD_CHECK_OUT_TIME"];
        $checkoutlocation=$row["ECIOD_CHECK_OUT_LOCATION"];
        $report_location=$row["REPORT_LOCATION"];
        //STRING REPLACED
        if($adm_reprt!=null){
            $adm_report='';
            $body_msg =explode("\n", $adm_reprt);
            $length=count($body_msg);
            for($i=0;$i<$length;$i++){
                $adm_report.=$body_msg[$i].'<br>';
            }
        }
        else{
            $adm_report=null;
        }
        if($ure_reason_txt!=null){
            $adm_reason='';
            $URE_reason_msg =explode("\n", $ure_reason_txt);
            $length=count($URE_reason_msg);
            for($i=0;$i<=$length;$i++){
                $adm_reason.=$URE_reason_msg[$i].'<br>';
            }
        }
        else{
            $adm_reason=null;
        }
        if($adm_report==null){
            $final_report=$adm_morningsession.' - REASON'.':'.$adm_reason;

        }
        else if($adm_reason==null){

            if($adm_permission!=null)
            {
                $final_report=$adm_report.'<br>'.'PERMISSION:'.$adm_permission.'hrs';
            }
            else
            {
                $final_report=$adm_report;
            }
        }
        else{
            if($adm_morningsession=='PRESENT'){
                $ure_after_mrg=$adm_afternoonsession.'(PM)';
            }
            else
            {
                $ure_after_mrg=$adm_morningsession.'(AM)';
            }
            if($adm_permission!=null){

                $final_report=$adm_report.'<br>'.$ure_after_mrg.' - REASON'.':'.$adm_reason.'PERMISSION:'.$adm_permission.'hrs';
            }
            else{
                $final_report=$adm_report.'<br>'.$ure_after_mrg.' - REASON'.':'.$adm_reason;
            }
        }
        $message=$message. "<tr><td width=260>".$adm_loginid."</td><td >".$final_report."</td><td align='center' width=80>".$checkintime."</td><td width=260 nowrap>".$checkinlocation."</td><td align='center' width=90>".$checkouttime."</td><td width=260 nowrap>".$checkoutlocation."</td><td width=260 nowrap>".$report_location."</td><td align='center' width=260>".$adm_userstamp."</td><td align='center' width=150 nowrap>".$adm_timestamp."</td></tr>";
    }
    $message=$message."</table></body></html>";
    $REP_subject_date=$mail_subject.' - '.date("d/m/Y",strtotime("-1 days"));
    $mail_options = [
        "sender" => $admin,
        "to" => $admin,
        "cc"=>$sadmin,
        "subject" => $REP_subject_date,
        "htmlBody" => $message
    ];
    try {
        $message = new Message($mail_options);
        $message->send();
    } catch (\InvalidArgumentException $e) {
        echo $e;
    }
}