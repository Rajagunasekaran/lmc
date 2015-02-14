<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************ATTENDANCE REPORT MAIL TRIGGER *************************************//
//DONE BY:RAJA
//VER 0.03-SD:09/01/2015 ED:09/01/2015, TRACKER NO:74,DESC:CHANGED LOGIN ID AS EMPLOYEE NAME
//VER 0.02-SD:01/12/2014,ED:01/12/2014,TRACKER NO:74, DESC:removed getuserstamp function.and updated script to run every first day of month,to get details of previous month.
//VER 0.01-INITIAL VERSION, SD:28/11/2014 ED:28/11/2014,TRACKER NO:74
//*********************************************************************************************************//-->
<?php
set_include_path( get_include_path() . PATH_SEPARATOR . 'google-api-php-client-master/src' );
require_once 'google-api-php-client-master/src/Google/Client.php';
require_once 'google-api-php-client-master/src/Google/Service/Drive.php';
require_once('mpdf571/mpdf571/mpdf.php');
require_once 'google/appengine/api/mail/Message.php';
use google\appengine\api\mail\Message;
include "COMMON_FUNCTIONS.php";
include "CONNECTION.php";
include "CONFIG.php";
$month_end = date('Y-m-d',strtotime('first day of this month'));
$current_date=date('Y-m-d');
if($month_end==$current_date){
    $select_admin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='ADMIN'";
    $select_sadmin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='SUPER ADMIN'";
    $admin_rs=mysqli_query($con,$select_admin);
    $sadmin_rs=mysqli_query($con,$select_sadmin);
    $select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=11";
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
    $month_year=date('F-Y',strtotime("-1 month"));
    $admin_name = substr($admin, 0, strpos($admin, '.'));
    $sadmin_name = substr($sadmin, 0, strpos($sadmin, '.'));
    $spladminname=$admin_name.'/'.$sadmin_name;
    $spladminname=strtoupper($spladminname);
    // splitting the body msg
    $email_body;
    $body_msg =explode(",", $body);
    $length=count($body_msg);
    for($i=0;$i<$length;$i++){
        $email_body.=$body_msg[$i].'<br><br>';
    }
    $mail_subject=str_replace("[MONTH]",$month_year,$mail_subject);
    $result = $con->query("CALL SP_EMPLOYEE_ATTENDANCE_CALCULATION ('$month_year','','$admin',@TEMP_USER_ABSENT_COUNT,@TOTAL_DAYS,@WORKING_DAYS)");
    if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
    $select = $con->query('SELECT @TEMP_USER_ABSENT_COUNT,@TOTAL_DAYS');
    $result = $select->fetch_assoc();
    $temp_table_name= $result['@TEMP_USER_ABSENT_COUNT'];
    $result1 = $con->query("CALL SP_TS_REPORT_COUNT_ABSENT_FLAG('$month_year','$admin',@TEMP_USER_ABSENT_COUNT)");
    if(!$result1) die("CALL failed: (" . $con->errno . ") " . $con->error);
    $select = $con->query('SELECT @TEMP_USER_ABSENT_COUNT');
    $result1 = $select->fetch_assoc();
    $temp_table_name1= $result1['@TEMP_USER_ABSENT_COUNT'];
    $total_days=$result['@TOTAL_DAYS'];
    $select_data="SELECT * FROM $temp_table_name A LEFT JOIN $temp_table_name1 B on B.UNAME=A.LOGINID ORDER BY LOGINID ASC ";
    $select_data_rs1=mysqli_query($con,$select_data);
    $select_data_rs=mysqli_query($con,$select_data);
    if($row=mysqli_fetch_array($select_data_rs1)){
        $total_working_days=$row['NO_OF_DAYS'];
    }
    // replace strings in body msg
    $replace= array("[SADMIN]", "[MONTH]","[TDAY]", "[WDAY]");
    $str_replaced  = array($spladminname,$month_year,$total_days,$total_working_days);
    $newphrase = str_replace($replace, $str_replaced, $email_body);
    $row=mysqli_num_rows($select_data_rs);
    $x=$row;
    $values_array=array();
    $message='<html><body>'.'<br>'.'<b>'.$mail_subject.'</b></h><br>'.'<br>'.'<br>'.'<h> '.$newphrase.'</h>'.'<br>'.'<table width=900 colspan=3px cellpadding=3px >'
        . '<th><tr style="color:white;" bgcolor="#6495ed" align="center" height=2px >'
        . '<td align="center" style="border: 1px solid black;color:white;"><b>EMPLOYEE NAME</b></td>'
        . '<td align="center" width=90 nowrap style="border: 1px solid black;color:white;"><b>NO OF PRESENT</b></td>'
        . '<td align="center" width=90 nowrap style="border: 1px solid black;color:white;"><b>NO OF ABSENT</b></td>'
        . '<td align="center" width=90 nowrap style="border: 1px solid black;color:white;"><b>NO OF ONDUTY</b></td>'
        . '<td align="center" width=120 nowrap style="border: 1px solid black;color:white;"><b>PERMISSION HOUR(S)</b></td>'
        . '<td align="center" width=100 nowrap style="border: 1px solid black;color:white;"><b>REPORT ENTRY MISSED</b></td>'
        . '<td align="center" width=100 nowrap style="border: 1px solid black;color:white;"><b>WORKED IN HOLIDAYS</b></td></tr></th>';

    while($row=mysqli_fetch_array($select_data_rs)){
        $absent_count=$row['ABSENT_COUNT'];
        $no_of_present=$row['NO_OF_PRESENT'];
        $no_of_absent=$row['NO_OF_ABSENT'];
        $no_of_onduty=$row['NO_OF_ONDUTY'];
        $permission=$row['PERMISSION_HRS'];
        $login_id=$row['LOGINID'];
        $work_in_holiday=$row['WORKING_IN_HOLIDAYS'];
        if($permission<0.5){
            $permission=' ';
        }
        if($no_of_onduty<0.5){
            $no_of_onduty=' ';
        }
        if($no_of_absent<0.5){
            $no_of_absent=' ';
        }
        if($no_of_present<0.5){
            $no_of_present=' ';
        }
        if($work_in_holiday<0.5){
            $work_in_holiday= ' ';
        }
        $message=$message. "<tr style='border: 1px solid black;' height=25px ><td style='border: 1px solid black;'>".$login_id."</td><td align='center' style='border: 1px solid black;'>".$no_of_present."</td><td align='center' style='border: 1px solid black;'>".$no_of_absent."</td><td align='center' style='border: 1px solid black;'>".$no_of_onduty."</td><td align='center' style='border: 1px solid black;'>".$permission."</td><td align='center' style='border: 1px solid black;'>".$absent_count."</td><td align='center' style='border: 1px solid black;'>".$work_in_holiday."</td></tr>";
    }
    $drop_query="DROP TABLE $temp_table_name ";
    $drop_query1="DROP TABLE $temp_table_name1 ";
    mysqli_query($con,$drop_query);
    mysqli_query($con,$drop_query1);
    $message=$message."</table></body></html>";
    $find='SSOMENS TIME SHEET REPORT-  '.$month_year;
    $messagepdf=str_replace($find,'',$message);
    $find1='HELLO '.$spladminname;
    $messagepdf=str_replace($find1,'<b>'.'SSOMENS TIME SHEET REPORT-  '.$month_year.'</b>',$messagepdf);
    $find2='SSOMENS TIME SHEET REPORT FOR THE FOLLOWING EMPLOYEE(S) - '.$month_year.':';
    $messagepdf=str_replace($find2,'',$messagepdf);
    ob_clean();
    $mpdf = new mPDF('utf-8',array(270,330));
    $mpdf->WriteHTML($messagepdf);
    $outputpdf=$mpdf->Output('SSOMENS TS REPORT ' .$month_year. '.pdf','s');
    ob_end_clean();
    $FILENAME='SSOMENS TS REPORT ' .$month_year. '.pdf';
    $message1 = new Message();
    $message1->setSender($admin);
    $message1->addTo($admin);
    $message1->addCc($sadmin);
    $message1->setSubject($mail_subject);
    $message1->setHtmlBody($message);
    $message1->addAttachment($FILENAME,$outputpdf);
    $message1->send();
}



