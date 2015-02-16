<?php
set_include_path( get_include_path() . PATH_SEPARATOR . 'google-api-php-client-master/src' );
require_once 'google-api-php-client-master/src/Google/Client.php';
require_once 'google-api-php-client-master/src/Google/Service/Drive.php';
require_once('mpdf571/mpdf571/mpdf.php');
require_once 'google/appengine/api/mail/Message.php';
require_once('PHPExcel/Classes/PHPExcel.php');
include 'google-api-php-client-master/src/Google/Service/Calendar.php';
include "CONNECTION.php";
include "CONFIG.php";
include "COMMON_FUNCTIONS.php";
use google\appengine\api\mail\Message;
$bucket_id=get_appbucket_id();
$currentdate=date("Y-m-d");//CURRENT DATE
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
$sub=str_replace("[DATE]",date("d-m-Y"),$sub);
// for report pdf
$message='<html><body><table width=1500 colspan=3px cellpadding=3px ><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#6495ed" align="center" height=25px ><td align="center" style="border: 1px solid black;color:white;" nowrap><b>EMPLOYEE NAME</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>DATE</b></td><td align="center" style="border: 1px solid black;color:white;width:500px"><b>REPORT</b></td><td align="center" nowrap style="border: 1px solid black;color:white;"><b>ATTENDANCE</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>PROJECT</b></td></tr></th>';
$result = $con->query("CALL SP_TS_REPORT_DETAILS('$currentdate','$admin',@temp_table)");
if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
$select = $con->query('SELECT @temp_table');
$result = $select->fetch_assoc();
$temp_table_name= $result['@temp_table'];
$query="select * from $temp_table_name";
$sql=mysqli_query($con,$query);
$row=mysqli_num_rows($sql);
$x=$row;
if($x>0){
    while($row=mysqli_fetch_array($sql)){
        $adm_reprt_date=$row["REPORT_DATE"];
        $adm_reprt=$row["REPORT"];
        $adm_loginid=$row["EMPLOYEE_NAME"];
        $ure_reason_txt=$row["REASON"];
        $adm_permission=$row["PERMISSION"];
        $ure_morningsession=$row["AM_SESSION"];
        $ure_afternoonsession=$row["PM_SESSION"];
        $ure_attendance=$row["ATTENDANCE"];
        $ure_project=$row["PROJECT_NAME"];
        $ure_attendance_id=$row["ATTENDANCE_ID"];
        $ure_session=$row["SESSION"];
    // STRING REPLACED
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
            $final_report=$ure_morningsession.' - REASON'.':'.$adm_reason;
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
            if($ure_morningsession=='PRESENT'){
                $ure_after_mrg=$ure_afternoonsession.'(PM)';
            }
            else
            {
                $ure_after_mrg=$ure_morningsession.'(AM)';
            }
            if($adm_permission!=null){
                $final_report=$adm_report.'<br>'.$ure_after_mrg.' - REASON'.':'.$adm_reason.'PERMISSION:'.$adm_permission .'hrs';
            }
            else{
                $final_report=$adm_report.'<br>'.$ure_after_mrg.' - REASON'.':'.$adm_reason;
            }
        }
        $message=$message. "<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$adm_loginid."</td><td nowrap align='center' style='border: 1px solid black;'>".$adm_reprt_date."</td><td nowrap style='border: 1px solid black;'>".$final_report."</td><td nowrap align='center' style='border: 1px solid black;'>$ure_attendance</td><td nowrap style='border: 1px solid black;'>$ure_project</td><tr>";
    }
    $message=$message."</table></body></html>";
    // for image pdf
    $html='<html><body><table><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/>';
    $query="select * from $temp_table_name";
    $sql=mysqli_query($con,$query);
    $row=mysqli_num_rows($sql);
    $x=$row;
    if($x>0){
        while($row=mysqli_fetch_array($sql)){
            $adm_loginid=$row["EMPLOYEE_NAME"];
            $adm_fileid=$row["FILEID"];
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
            if($adm_fileid!=null || $adm_fileid!=''){
                $file = $service->files->get($adm_fileid);
                $downloadUrl = $file->getTitle();
                $path= $bucket_id.'images/'.$downloadUrl.'.png';
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }elseif($adm_fileid==null){
                $base64='';
            }
            if($base64!=''){
                $html=$html.'<tr><td><label>DRAWING IMAGE OF '.$adm_loginid.'</label><br><br><img id=image src="'.$base64.'"/><br></td></tr>';
            }
        }
        $html=$html."</table></body></html>";
    // for excel
    $query="select * from $temp_table_name";
    $query1="select EMPLOYEE_NAME,REPORT_DATE,REPORT,REASON,PROJECT_NAME,ATTENDANCE,SESSION,PERMISSION from $temp_table_name";
    $sql=mysqli_query($con,$query);
    $row=mysqli_num_rows($sql);
    $x=$row;
    if($x>0){
        if ($result = mysqli_query($con,$query1) or die(mysql_error())) {
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
            /** Create a new PHPExcel object 1.0 */
            $sheettitle='ALLIANCE TIME SHEET REPORT '.date("d-m-Y");
            $objPHPExcel->getActiveSheet()->setTitle('TS REPORT-'.date("d-m-Y"))->setCellValue('A2', 'EMPLOYEE NAME')->setCellValue('B2', 'DATE')->setCellValue('C2', 'REPORT')->setCellValue('D2', 'REASON')->setCellValue('E2', 'PROJECT')->setCellValue('F2', 'ATTENDANCE')->setCellValue('G2', 'SESSION')->setCellValue('H2', 'PERMISSION')->setCellValue('A1', $sheettitle);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
            $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
            $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(18);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A:H')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getFont()->getColor()->setRGB('FFFAFA');
            $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '#498af3')
                    )
                )
            );
        }
        //add data
        /** Loop through the result set 1.0 */
        $rowNumber = 3; //start in cell 1
        while ($row = mysqli_fetch_row($result)) {
            $col = 'A'; // start at column A
            foreach($row as $cell) {
                $objPHPExcel->getActiveSheet()->setCellValue($col.$rowNumber,$cell);
                $col++;
            }
            $rowNumber++;
        }
        $drop_query="DROP TABLE $temp_table_name ";
        mysqli_query($con,$drop_query);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="simple.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        $path= $bucket_id.'excel/';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($path."ALLIANCE TS REPORT.xls");
    $drop_query="DROP TABLE $temp_table_name ";
    mysqli_query($con,$drop_query);
//SENDING MAIL OPTIONS
    $imagefilename='ALLIANCE TIME SHEET IMAGE REPORT '.date("d-m-Y").'.pdf';
    $reportfilename='ALLIANCE TIME SHEET REPORT '.date("d-m-Y").'.pdf';
    $excelfilename='ALLIANCE TIME SHEET REPORT '.date("d-m-Y").'.xls';
    $imageheader='ALLIANCE TIME SHEET IMAGE REPORT '.date("d-m-Y");
    $reportheader='ALLIANCE TIME SHEET REPORT '.date("d-m-Y");
    $message1 = new Message();
    $message1->setSender($admin);
    $message1->addTo($admin);
    $message1->addCc($sadmin);
    $message1->setSubject($mail_subject);
    $message1->setHtmlBody($sub);
    $mpdf=new mPDF('utf-8',array(300,200));
    $mpdf->debug=true;
    $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">'.$reportheader.'</div></h3>', 'O', true);
    $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
    $mpdf->WriteHTML($message);
    $reportpdf=$mpdf->Output('ALLIANCE TIME SHEET REPORT ' .date("d-m-Y"). '.pdf','S');
    $message1->addAttachment($reportfilename,$reportpdf);
    $mpdf=new mPDF('utf-8','A4');
    $mpdf->debug=true;
    $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">'.$imageheader.'</div></h3>', 'O', true);
    $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
    $mpdf->WriteHTML($html);
    $imagepdf=$mpdf->Output('ALLIANCE TIME SHEET IMAGE REPORT '.date("d-m-Y").'.pdf','S');
    $message1->addAttachment($imagefilename,$imagepdf);
    $excelreport = file_get_contents($path."ALLIANCE TS REPORT.xls");
    $message1->addAttachment($excelfilename,$excelreport);
    $message1->send();
    unlink($path."ALLIANCE TS REPORT.xls");
}
}
}
?>