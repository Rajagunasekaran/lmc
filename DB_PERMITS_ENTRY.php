<?php
error_reporting(0);
include "CONNECTION.php";
include "GET_USERSTAMP.php";
include "COMMON.php";
require_once('mpdf571/mpdf571/mpdf.php');
require 'PHPMailer-master/PHPMailerAutoload.php';
require_once('PHPExcel/Classes/PHPExcel.php');
$dir=dirname(__FILE__).DIRECTORY_SEPARATOR;
date_default_timezone_set('Asia/Singapore');
$parentfolder=get_parentfolder_id();
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
$activeempname=mysqli_query($con,"SELECT EMP_ID,CONCAT(EMP_FIRST_NAME,' ',EMP_LAST_NAME) AS ACTIVE_EMPLOYEE_NAME FROM LMC_EMPLOYEE_DETAILS E,LMC_USER_LOGIN_DETAILS U WHERE E.ULD_ID=U.ULD_ID AND U.ULD_USERNAME='$UserStamp'");
if($row=mysqli_fetch_array($activeempname))
{
    $activeemp=$row["EMP_ID"];
}
if($_REQUEST['option']=='COMMON_DATA')
{
//TEAM CREATION
    $team=mysqli_query($con,"select TEAM_NAME from LMC_TEAM_CREATION  where ULD_ID='$activeemp'");
    while($row=mysqli_fetch_array($team)){
        $teamname[]=$row["TEAM_NAME"];
    }
//MACHINERY TYPE
    $machtype=mysqli_query($con,"select MCU_MACHINERY_TYPE from LMC_MACHINERY_USAGE ORDER BY MCU_MACHINERY_TYPE ASC");
    while($row=mysqli_fetch_array($machtype)){
        $machinerytype[]=$row["MCU_MACHINERY_TYPE"];
    }
//FITTINGS ITEM
    $fittingitem=mysqli_query($con,"select FU_ITEMS from LMC_FITTING_USAGE ORDER BY FU_ITEMS ASC");
    while($row=mysqli_fetch_array($fittingitem)){
        $fittingitems[]=$row["FU_ITEMS"];
    }
//MATERIAL ITEM
    $matitem=mysqli_query($con,"select MU_ITEMS from LMC_MATERIAL_USAGE ORDER BY MU_ITEMS ASC");
    while($row=mysqli_fetch_array($matitem)){
        $materialitem[]=$row["MU_ITEMS"];
    }
    //TYPE OF JOB
    $typeofjob=mysqli_query($con,"select TOJ_JOB,TOJ_ID from LMC_TYPE_OF_JOB ORDER BY TOJ_JOB ASC ");
    while($row=mysqli_fetch_array($typeofjob)){
        $joptype[]=array($row["TOJ_JOB"],$row['TOJ_ID']);
    }
    //EMPLOYEE NAME
    $empname=mysqli_query($con,"SELECT EMP_ID,CONCAT(EMP_FIRST_NAME,' ',EMP_LAST_NAME) AS EMPNAME FROM LMC_EMPLOYEE_DETAILS ORDER BY EMPNAME ASC ");
    while($row=mysqli_fetch_array($empname)){
        $employeename[]=array($row["EMPNAME"],$row['EMP_ID']);
    }
    //ERRPOR MESSAGE
    $errormsg=get_error_msg('3,6,7,21,143');
    $values=array($teamname,$machinerytype,$fittingitems,$materialitem,$joptype,$errormsg,$employeename);
    echo JSON_encode($values);

}
elseif($_REQUEST['Option']=='InputForm')
{
    //TEAM REPORT ELEMENTS
    $teamlocation=$_POST["tr_txt_location"];
    $contractno=$_POST["tr_txt_contractno"];
    $teamname=$_POST['tr_lb_team'];
    $reportdate=$_POST['tr_txt_date'];
    $weather=$_POST['tr_txt_weather'];
    $weatherfrom=$_POST['tr_txt_wftime'];
    $weatherto=$_POST['tr_txt_wttime'];
    $reachsite=$_POST['tr_txt_reachsite'];
    $leavesite=$_POST['tr_txt_leavesite'];
    $joptype=$_POST['jobtype'];
    $reportdate = date('Y-m-d',strtotime($reportdate));
    $typeofjob;
    for($i=0;$i<count($joptype);$i++){
        if($i==0){
            $typeofjob=$joptype[$i];
        }
        else{
            $typeofjob=$typeofjob .",".$joptype[$i];
        }
    }
//JOB DONE ELEMENTS
    $pipelaidroad=$_POST['jd_chk_road'];
    $pipelaidconc=$_POST['jd_chk_contc'];
    $pipelaidturf=$_POST['jd_chk_truf'];
    $pipetesting=$_POST['jd_txt_testing'];
    $pressurestart=$_POST['jd_txt_start'];
    $pressureend=$_POST['jd_txt_end'];
    $teamremarks=$_POST['jd_ta_remark'];
    $roadm=$_POST['jd_chk_roadm'];
    $roadmm=$_POST['jd_chk_roadmm'];
    $concm=$_POST['jd_chk_concm'];
    $concmm=$_POST['jd_chk_concmm'];
    $turfm=$_POST['jd_chk_trufm'];
    $turfmm=$_POST['jd_chk_trufmm'];

    if($pipelaidroad=='on')
    {
        $pipelaidroad='ROAD';
        $roadm;
        $roadmm;
    }

    if($pipelaidconc=='on')
    {
        $pipelaidconc='CONC';
        $concm;
        $concmm;
    }
    if($pipelaidturf=='on')
    {
        $pipelaidturf='TURF';
        $turfm;
        $turfmm;
    }

    $pipelaid=$pipelaidroad.'^'.$pipelaidconc.'^'.$pipelaidturf;
    $size=  $roadm.'^'. $concm.'^'.$turfm;
    $length=  $roadmm.'^'.$concmm.'^'.$turfmm;


    $material=$_POST["MaterialDetails"];
    $fitting=$_POST["FittingDetails"];
    $equipment=$_POST["EquipmentDetails"];
    $rental=$_POST["RentalDetails"];
    $mechinery=$_POST["MechineryUsageDetails"];
    $mech_eqp_transfer=$_POST["MechEqptransfer"];
    $SV_details=$_POST["SiteVisit"];
    $EmployeeReport=$_POST["EmployeeDetails"];
    $imagedata=$_POST['imgData'];
    $docfilename=$_POST['filename'];
    if($imagedata!='' && $reportdate!='' && $EmployeeReport[0]!='' && $teamname!=''){
        $daterep=str_replace('-','',$reportdate);
        $imgfilename=$EmployeeReport[0].'_'.$daterep.'_'.date('His').'.png';
        $userfolderid=get_emp_folderid($EmployeeReport[0]);
        $uploadpath=$dir.$parentfolder.DIRECTORY_SEPARATOR.$userfolderid.DIRECTORY_SEPARATOR.$imgfilename;
        try{
            $data=str_replace('data:image/png;base64,','',$imagedata);
            $data = str_replace(' ','+',$data);
            $data = base64_decode($data);
            $success = file_put_contents($uploadpath, $data);
            $imgflag=1;
        }
        catch(Exception $e){
            print $e->getMessage();
            unlink($uploadpath);
            $imgflag=0;
        }
    }
    elseif($imagedata=='' || $reportdate=='' || $EmployeeReport[0]=='' || $teamname==''){
        $imgflag=0;
    }
    if($weather!=''){
        $weathertime=$weather.' ('.$weatherfrom.' TO '.$weatherto.')';
    }
    else{
        $weathertime='';
    }
//TEAM REPORT DETAILS
    $jobname=mysqli_query($con,"SELECT GROUP_CONCAT(TOJ_JOB) AS JOB FROM lmc_TYPE_OF_job WHERE TOJ_ID IN($typeofjob)");
    while($row=mysqli_fetch_array($jobname)){
        $jobnames=$row["JOB"];
    }
    $teamreporttable='<table width=1000 colspan=3px cellpadding=3px  border="0"><caption style="caption-side: left;font-weight: bold;">TEAM REPORT</caption>
   <tr><td width="100" style= "border: 1px solid black;color:#fff; background-color:#498af3;font-weight: bold;" height=25px>LOCATION</td><td width="360">'.$teamlocation.'</td><td width="130" style="color:#fff; background-color:#498af3;border: 1px solid black;font-weight: bold;" height=25px>CONTRACT NO</td><td  width="250">'.$contractno.'</td><td width="150">'.$teamname.'</td></tr></table>
   <table width=1000 colspan=3px cellpadding=3px  border="0"><tr><td width="250" style="border: 1px solid black;color:#fff; background-color:#498af3;font-weight: bold;" height=25px>DATE</td><td style="border: 1px solid black;"width="250">'.$reportdate.'</td><td width="250" style="color:#fff; background-color:#498af3;border: 1px solid black;font-weight: bold;" height=25px>WEATHER</td><td style="border: 1px solid black;" width="250">'.$weathertime.'</td></tr>
   <tr><td width="250" style="color:#fff; background-color:#498af3; border: 1px solid black;font-weight: bold;" height=25px>REACH SITE</td><td style="border: 1px solid black;" width="250">'.$reachsite.'</td><td width="250" style="color:#fff; background-color:#498af3; border: 1px solid black;font-weight: bold;" height=25px>LEAVE SITE</td><td style="border: 1px solid black;" width="250">'.$leavesite.'</td></tr>
   <tr><td width="250" colspan="1" style="color:#498af3;border: 1px solid black;color:#fff; background-color:#498af3;font-weight: bold;" height=25px>TYPE OF JOB</td><td style="border: 1px solid black;" width="250" colspan="3">'.$jobnames.'</td></tr>
   </table>';

//JOB DONE DETAILS
    $jobdonetable='<br><table width=1000 colspan=3px cellpadding=3px  border="0"><caption style="caption-side: left;font-weight: bold;">JOB DONE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/>
   <tr><td width="250" style="color:#fff; background-color:#498af3;border: 1px solid black;font-weight: bold;" height=25px>PIPELAID</td><td style="border: 1px solid black;text-align:center;" width="250" colspan=2>ROAD</td><td width="250" colspan=2 style="border: 1px solid black;text-align:center;">CONC</td><td width="250" colspan=2 style="border: 1px solid black;text-align:center;">TRUF</td></tr>
   <tr><td style="color:#fff; background-color:#498af3;border: 1px solid black;font-weight: bold; " height=25px>SIZE/LENGTH</td><td style="border: 1px solid black;">'.$roadm.'</td><td style="border: 1px solid black;">'.$roadmm.'</td><td style="border: 1px solid black;">'.$concm.'</td><td style="border: 1px solid black;">'.$concmm.'</td><td style="border: 1px solid black;">'.$turfm.'</td><td style="border: 1px solid black;">'.$turfmm.'</td>
   <tr><td style="color:#fff; background-color:#498af3; border: 1px solid black;font-weight: bold;" height=25px>PIPE TESTING</td><td colspan="2" style="color:#fff; background-color:#498af3;border: 1px solid black;font-weight: bold;text-align:center;" height=25px>START(PRESSURE)</td><td colspan="2" style="color:#fff; background-color:#498af3;border: 1px solid black;font-weight: bold;text-align:center;" height=25px>END(PRESSURE)</td><td colspan="2" style="color:#fff; background-color:#498af3;border: 1px solid black;font-weight: bold;text-align:center;" height=25px>REMARK</td></tr>
   <tr><td style="border: 1px solid black;">'.$pipetesting.'</td><td colspan="2" style="border: 1px solid black;">'.$pressurestart.'</td><td style="border: 1px solid black;"colspan="2">'.$pressureend.'</td><td colspan="2" style="border: 1px solid black;">'.$teamremarks.'</td></tr>
   </table>';

//EMPLOYEE TABLE
    $employeetable='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;">EMPLOYEE DETAILS</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>EMPLOYEE NAME</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>START</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>END</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>OT</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>REMARKS</b></td></tr></th>';
    $employeetable=$employeetable."<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$EmployeeReport[5]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$EmployeeReport[1]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$EmployeeReport[2]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$EmployeeReport[3]."</td><td nowrap style='border: 1px solid black;'>".$EmployeeReport[4]."</td></tr></table>";
// final table start
    $reportheadername='TIME SHEET REPORT FOR '.$EmployeeReport[5].' ON '.date('d-m-Y',strtotime($reportdate));
    $finaltable='<html><body><table><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">'.$reportheadername.'</div></h2></td></tr><br><tr><td>'.$teamreporttable.'</td></tr><br><br><tr><td>'.$jobdonetable.'</td></tr><br><br><tr><td>'.$employeetable.'</td></tr>';

// FOR EXCEL
    $sheettitle='LIH MING CONSTRUCTION PTE LTD
TIME SHEET REPORT FOR '.$EmployeeReport[5].' ON '.date('d-m-Y',strtotime($reportdate));
    $objPHPExcel->getActiveSheet()->setTitle('LMC TS ENTRY REPORT')->setCellValue('A1', $sheettitle)->setCellValue('a2', 'TEAM REPORT')->setCellValue('a8', 'JOB DONE')->setCellValue('a14', 'EMPLOYEE REPORT')->setCellValue('a3', 'LOCATION')->setCellValue('b3',$teamlocation)->setCellValue('c3', 'CONTRACT NO')->setCellValue('d3', $contractno)->setCellValue('e3', 'TEAM')->setCellValue('f3', $teamname)
        ->setCellValue('A4', 'DATE') ->setCellValue('B4', $reportdate) ->setCellValue('C4','WEATHER')->setCellValue('D4',$weathertime)
        ->setCellValue('A5', 'REACH SITE')->setCellValue('B5', $reachsite)->setCellValue('C5', 'LEAVE SITE')->setCellValue('D5', $leavesite)->setCellValue('A6', 'TYPE OF JOB')->setCellValue('B6',$jobnames)
        ->setCellValue('A9','PIPE LAID')->setCellValue('B9','ROAD')->setCellValue('D9','CONC')->setCellValue('F9','TURF')
        ->setCellValue('A10','SIZE / LENGTH')->setCellValue('B10',$roadm)->setCellValue('C10',$roadmm)->setCellValue('D10',$concm)->setCellValue('E10',$concmm)->setCellValue('F10',$turfm)->setCellValue('G10',$turfmm)
        ->setCellValue('A11','PIPE TESTING')->setCellValue('B11','START ( PRESSURE )')->setCellValue('D11','END ( PRESSURE )')->setCellValue('F11','REMARKS')
        ->setCellValue('A12',$pipetesting)->setCellValue('B12',$pressurestart)->setCellValue('D12',$pressureend)->setCellValue('F12',$teamremarks)
        ->setCellValue('A15','NAME')->setCellValue('B15','START')->setCellValue('C15','END')->setCellValue('D15','OT')->setCellValue('F15','REMARKS')
        ->setCellValue('A16',$EmployeeReport[5])->setCellValue('B16',$EmployeeReport[1])->setCellValue('C16',$EmployeeReport[2])->setCellValue('D16',$EmployeeReport[3])->setCellValue('F16',$EmployeeReport[4]);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D4:F4');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D5:F5');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E4:F4');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E5:F5');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B6:F6');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B9:C9');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D9:E9');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F9:G9');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B11:C11');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D11:E11');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F11:G11');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B12:C12');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D12:E12');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F12:G12');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D15:E15');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F15:G15');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D16:E16');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F16:G16');
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(35);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A3:A10')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('C3:C5')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A11:G11')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A14:G15')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B12:E12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('B11:G11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A:E')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A9:G9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A9:G9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('A1:A11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('A15:G15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('B16:E16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A3:A6')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('A3:A6')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('C3:C5')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('C3:C5')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('E3')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('A9:A11')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('A9:A11')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('B11:G11')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('B11:G11')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('A15:G15')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('A15:G15')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $maxrowNo=18;
    $rowNumber='';
    $METrowNumber='';
    $MUrowNumber='';
    $RMrowNumber='';
    $EUrowNumber='';
    $FUrowNumber='';
    $MIUrowNumber='';
//Site Visit
    $SV_designation;$SV_name;$SV_start;$SV_end;$SV_remarks;
    if($SV_details!='null')
    {
        $rowNumber = $maxrowNo;
        $maxrowNo=$maxrowNo+count($SV_details)+3;
        $sitevisittable='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;">SITE VISIT</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>DESIGNATION</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>NAME</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>START</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>END</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>REMARKS</b></td></tr></th>';

        $objPHPExcel->getActiveSheet()->getStyle('A'.$rowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowNumber,'SITE VISIT');
        $rowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($rowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowNumber,'DESIGNATION');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowNumber.':C'.$rowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowNumber,'NAME');
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowNumber,'START (Time)');
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowNumber,'END (Time)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$rowNumber.':G'.$rowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowNumber,'REMARKS');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$rowNumber.':G'.$rowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$rowNumber.':G'.$rowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$rowNumber.':G'.$rowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $rowNumber++;
        for($i=0;$i<count($SV_details);$i++)
        {
            $sitevisittable=$sitevisittable."<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$SV_details[$i][0]."</td><td nowrap style='border: 1px solid black;'>".$SV_details[$i][1]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$SV_details[$i][2]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$SV_details[$i][3]."</td><td nowrap style='border: 1px solid black;'>".$SV_details[$i][4]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('C'.$rowNumber.':E'.$rowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowNumber,$SV_details[$i][0]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowNumber.':C'.$rowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowNumber,$SV_details[$i][1]);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowNumber,$SV_details[$i][2]);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowNumber,$SV_details[$i][3]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$rowNumber.':G'.$rowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowNumber,$SV_details[$i][4]);
            if($i==0)
            {
                $SV_designation=$SV_details[$i][0]; $SV_name=$SV_details[$i][1]; $SV_start=$SV_details[$i][2];$SV_end=$SV_details[$i][3];$SV_remarks=$SV_details[$i][4];
            }
            else
            {
                $SV_designation=$SV_designation.'^'.$SV_details[$i][0]; $SV_name=$SV_name.'^'.$SV_details[$i][1]; $SV_start=$SV_start.'^'.$SV_details[$i][2]; $SV_end=$SV_end.'^'.$SV_details[$i][3]; $SV_remarks=$SV_remarks.'^'.$SV_details[$i][4];
            }
            $rowNumber++;
        }

        $sitevisittable=$sitevisittable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$sitevisittable.'</td></tr>';
    }

//Mechinery/Equipment Transfer
    $mech_from;$mech_item;$mech_to;$mech_remark;
    if($mech_eqp_transfer!='null')
    {
        $METrowNumber=$maxrowNo;
        $maxrowNo=$maxrowNo+count($mech_eqp_transfer)+3;
        $machineryequipmenttable='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;">MACHINERY/EQUIPMENT TRANSFER</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>FROM(LORRY NO)</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>ITEM</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>TO(LORRY NO)</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>REMARKS</b></td></tr></th>';

        $objPHPExcel->getActiveSheet()->getStyle('A'.$METrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$METrowNumber,'MACHINERY / EQUIPMENT TRANSFER');
        $METrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($METrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$METrowNumber,'FROM (LORRY NO)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$METrowNumber.':C'.$METrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$METrowNumber,'ITEM');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$METrowNumber.':E'.$METrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$METrowNumber,'TO (LORRY NO)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$METrowNumber.':G'.$METrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$METrowNumber,'REMARKS');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$METrowNumber.':G'.$METrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$METrowNumber.':G'.$METrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$METrowNumber.':G'.$METrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $METrowNumber++;
        for($i=0;$i<count($mech_eqp_transfer);$i++)
        {
            $machineryequipmenttable=$machineryequipmenttable."<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$mech_eqp_transfer[$i][0]."</td><td nowrap style='border: 1px solid black;'>".$mech_eqp_transfer[$i][1]."</td><td nowrap style='border: 1px solid black;'>".$mech_eqp_transfer[$i][2]."</td><td nowrap style='border: 1px solid black;'>".$mech_eqp_transfer[$i][3]."</td></tr>";
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$METrowNumber,$mech_eqp_transfer[$i][0]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$METrowNumber.':C'.$METrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$METrowNumber,$mech_eqp_transfer[$i][1]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$METrowNumber.':E'.$METrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$METrowNumber,$mech_eqp_transfer[$i][2]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$METrowNumber.':G'.$METrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$METrowNumber,$mech_eqp_transfer[$i][3]);
            if($i==0)
            {
                $mech_from=$mech_eqp_transfer[$i][0]; $mech_item=$mech_eqp_transfer[$i][1]; $mech_to=$mech_eqp_transfer[$i][2];$mech_remark=$mech_eqp_transfer[$i][3];
            }
            else
            {
                $mech_from=$mech_from.'^'.$mech_eqp_transfer[$i][0]; $mech_item=$mech_item.'^'.$mech_eqp_transfer[$i][1]; $mech_to=$mech_to.'^'.$mech_eqp_transfer[$i][2]; $mech_remark=$mech_remark.'^'.$mech_eqp_transfer[$i][3];
            }
            $METrowNumber++;
        }
        $machineryequipmenttable=$machineryequipmenttable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$machineryequipmenttable.'</td></tr>';
    }

//Mechinery Usage
    $mechinerytype;$mechinerystart;$mechineryend;$mechineryremark;
    if($mechinery!='null')
    {
        $MUrowNumber=$maxrowNo;
        $maxrowNo=$maxrowNo+count($mechinery)+3;
        $machineryusagetable='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;">MACHINERY USAGE</caption>  <sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>MACHINERY TYPE</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>START</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>END</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>REMARKS</b></td></tr></th>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$MUrowNumber,'MACHINERY USAGE');
        $MUrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($MUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$MUrowNumber,'MACHINERY TYPE');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$MUrowNumber.':C'.$MUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$MUrowNumber,'START (Time)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$MUrowNumber.':E'.$MUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$MUrowNumber,'END (Time)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$MUrowNumber.':G'.$MUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$MUrowNumber,'REMARKS');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MUrowNumber.':G'.$MUrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MUrowNumber.':G'.$MUrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MUrowNumber.':G'.$MUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $MUrowNumber++;
        for($i=0;$i<count($mechinery);$i++)
        {
            $machineryusagetable=$machineryusagetable."<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$mechinery[$i][0]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$mechinery[$i][1]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$mechinery[$i][2]."</td><td nowrap style='border: 1px solid black;'>".$mechinery[$i][3]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('B'.$MUrowNumber.':E'.$MUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$MUrowNumber,$mechinery[$i][0]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$MUrowNumber.':C'.$MUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$MUrowNumber,$mechinery[$i][1]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$MUrowNumber.':E'.$MUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$MUrowNumber,$mechinery[$i][2]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$MUrowNumber.':G'.$MUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$MUrowNumber,$mechinery[$i][3]);
            if($i==0)
            {
                $mechinerytype=$mechinery[$i][0]; $mechinerystart=$mechinery[$i][1]; $mechineryend=$mechinery[$i][2];$mechineryremark=$mechinery[$i][3];
            }
            else
            {
                $mechinerytype=$mechinerytype.'^'.$mechinery[$i][0]; $mechinerystart=$mechinerystart.'^'.$mechinery[$i][1]; $mechineryend=$mechineryend.'^'.$mechinery[$i][2]; $mechineryremark=$mechineryremark.'^'.$mechinery[$i][3];
            }
            $MUrowNumber++;
        }
        $machineryusagetable=$machineryusagetable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$machineryusagetable.'</td></tr>';
    }

//Rental Mechinery
    $rental_lorryno;$rental_store;$rental_outside;$rental_start;$rental_end;$rental_remark;
    if($rental!='null')
    {
        $RMrowNumber=$maxrowNo;
        $maxrowNo=$maxrowNo+count($rental)+3;
        $rentaltable='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;">RENTAL MACHINERY</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>LORRY NUMBER</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>THROW EARTH(STORE)</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>THROW EARTH(OUTSIDE)</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>START</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>END</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>REMARKS</b></td></tr></th>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$RMrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$RMrowNumber,'RENTAL MACHINERY');
        $RMrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($RMrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$RMrowNumber,'LORRY NUMBER');
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$RMrowNumber,'THROW EARTH(STORE)');
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$RMrowNumber,'THROW EARTH(OUTSIDE)');
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$RMrowNumber,'START (Time)');
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$RMrowNumber,'END (Time)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$RMrowNumber.':G'.$RMrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$RMrowNumber,'REMARKS');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$RMrowNumber.':G'.$RMrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$RMrowNumber.':G'.$RMrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$RMrowNumber.':G'.$RMrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $RMrowNumber++;
        for($i=0;$i<count($rental);$i++)
        {
            $rentaltable=$rentaltable."<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$rental[$i][0]."</td><td nowrap style='border: 1px solid black;'>".$rental[$i][1]."</td><td nowrap style='border: 1px solid black;'>".$rental[$i][2]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$rental[$i][3]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$rental[$i][4]."</td><td nowrap style='border: 1px solid black;'>".$rental[$i][5]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('B'.$RMrowNumber.':E'.$RMrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$RMrowNumber,$rental[$i][0]);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$RMrowNumber,$rental[$i][1]);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$RMrowNumber,$rental[$i][2]);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$RMrowNumber,$rental[$i][3]);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$RMrowNumber,$rental[$i][4]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$RMrowNumber.':G'.$RMrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$RMrowNumber,$rental[$i][5]);
            if($i==0)
            {
                $rental_lorryno=$rental[$i][0]; $rental_store=$rental[$i][1]; $rental_outside=$rental[$i][2];$rental_start=$rental[$i][3];$rental_end=$rental[$i][4];$rental_remark=$rental[$i][5];
            }
            else
            {
                $rental_lorryno=$rental_lorryno.'^'.$rental[$i][0]; $rental_store=$rental_store.'^'.$rental[$i][1]; $rental_outside=$rental_outside.'^'.$rental[$i][2]; $rental_start=$rental_start.'^'.$rental[$i][3]; $rental_end=$rental_end.'^'.$rental[$i][4]; $rental_remark=$rental_remark.'^'.$rental[$i][5];
            }
            $RMrowNumber++;
        }
        $rentaltable=$rentaltable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$rentaltable.'</td></tr>';
    }

//Equipment Usage
    $equipmentcompressor;$equipmentlorryno;$equipmentstart;$equipmentend;$equipmentremark;
    if($equipment!='null')
    {
        $EUrowNumber=$maxrowNo;
        $maxrowNo=$maxrowNo+count($equipment)+3;
        $equipmenttable='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;">EQUIPMENT USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>AIR-COMPRESSOR</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>LORRYNO(TRANSPORT)</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>START</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>END</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>REMARKS</b></td></tr></th>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$EUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$EUrowNumber,'EQUIPMENT USAGE');
        $EUrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($EUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$EUrowNumber,'AIR-COMPRESSOR');
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$EUrowNumber,'LORRY NO(TRANSPORT)');
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$EUrowNumber,'START (Time)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$EUrowNumber.':E'.$EUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$EUrowNumber,'END (Time)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$EUrowNumber.':G'.$EUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$EUrowNumber,'REMARKS');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$EUrowNumber.':G'.$EUrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$EUrowNumber.':G'.$EUrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$EUrowNumber.':G'.$EUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $EUrowNumber++;
        for($i=0;$i<count($equipment);$i++)
        {
            $equipmenttable=$equipmenttable."<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$equipment[$i][0]."</td><td nowrap style='border: 1px solid black;'>".$equipment[$i][1]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$equipment[$i][2]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$equipment[$i][3]."</td><td nowrap style='border: 1px solid black;'>".$equipment[$i][4]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('C'.$EUrowNumber.':E'.$EUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$EUrowNumber,$equipment[$i][0]);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$EUrowNumber,$equipment[$i][1]);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$EUrowNumber,$equipment[$i][2]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$EUrowNumber.':E'.$EUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$EUrowNumber,$equipment[$i][3]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$EUrowNumber.':G'.$EUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$EUrowNumber,$equipment[$i][4]);
            if($i==0)
            {
                $equipmentcompressor=$equipment[$i][0]; $equipmentlorryno=$equipment[$i][1]; $equipmentstart=$equipment[$i][2];$equipmentend=$equipment[$i][3];$equipmentremark=$equipment[$i][4];
            }
            else
            {
                $equipmentcompressor=$equipmentcompressor.'^'.$equipment[$i][0]; $equipmentlorryno=$equipmentlorryno.'^'.$equipment[$i][1]; $equipmentstart=$equipmentstart.'^'.$equipment[$i][2]; $equipmentend=$equipmentend.'^'.$equipment[$i][3]; $equipmentremark=$equipmentremark.'^'.$equipment[$i][4];
            }
            $EUrowNumber++;
        }
        $equipmenttable=$equipmenttable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$equipmenttable.'</td></tr>';
    }

//Fitting  Usage //
    $fittingitems;$fittingsize;$fittingqty;$fittingremark;
    if($fitting!='null')
    {
        $FUrowNumber=$maxrowNo;
        $maxrowNo=$maxrowNo+count($fitting)+3;
        $fittingtable='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;">FITTING USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>ITEMS</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>SIZE</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>QUANTITY</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>REMARKS</b></td></tr></th>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$FUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$FUrowNumber,'FITTINGS USAGE');
        $FUrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($FUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$FUrowNumber,'ITEMS');
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$FUrowNumber,'SIZE');
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$FUrowNumber,'QUANTITY');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$FUrowNumber.':G'.$FUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$FUrowNumber,'REMARKS');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$FUrowNumber.':G'.$FUrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$FUrowNumber.':G'.$FUrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$FUrowNumber.':G'.$FUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $FUrowNumber++;
        for($i=0;$i<count($fitting);$i++)
        {
            $fittingtable=$fittingtable."<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$fitting[$i][0]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$fitting[$i][1]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$fitting[$i][2]."</td><td nowrap style='border: 1px solid black;'>".$fitting[$i][3]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('B'.$FUrowNumber.':C'.$FUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$FUrowNumber,$fitting[$i][0]);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$FUrowNumber,$fitting[$i][1]);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$FUrowNumber,$fitting[$i][2]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$FUrowNumber.':G'.$FUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$FUrowNumber,$fitting[$i][3]);
            if($i==0)
            {
                $fittingitems=$fitting[$i][0]; $fittingsize=$fitting[$i][1]; $fittingqty=$fitting[$i][2];$fittingremark=$fitting[$i][3];
            }
            else
            {
                $fittingitems=$fittingitems.'^'.$fitting[$i][0]; $fittingsize=$fittingsize.'^'.$fitting[$i][1]; $fittingqty=$fittingqty.'^'.$fitting[$i][2]; $fittingremark=$fittingremark.'^'.$fitting[$i][3];
            }
            $FUrowNumber++;
        }
        $fittingtable=$fittingtable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$fittingtable.'</td></tr>';
    }

//Material Usage //
    $materialitems;$materialreceipt;$materialqty;
    if($material!='null')
    {
        $MIUrowNumber=$maxrowNo;
        $materialusagetable='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;">MATERIAL USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>ITEMS</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>RECEIPT NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>QUANTITY</b></td></tr></th>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MIUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$MIUrowNumber,'MATERIAL USAGE');
        $MIUrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($MIUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$MIUrowNumber,'ITEMS');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$MIUrowNumber.':C'.$MIUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$MIUrowNumber,'RECEIPT NO');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$MIUrowNumber.':G'.$MIUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$MIUrowNumber,'Qty (KG/BAGS/LTR/PCS)');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MIUrowNumber.':G'.$MIUrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MIUrowNumber.':G'.$MIUrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MIUrowNumber.':G'.$MIUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $MIUrowNumber++;
        for($i=0;$i<count($material);$i++)
        {
            $materialusagetable=$materialusagetable."<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$material[$i][0]."</td><td nowrap style='border: 1px solid black;'>".$material[$i][1]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$material[$i][2]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('D'.$MIUrowNumber.':G'.$MIUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$MIUrowNumber,$material[$i][0]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$MIUrowNumber.':C'.$MIUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$MIUrowNumber,$material[$i][1]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$MIUrowNumber.':G'.$MIUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$MIUrowNumber,$material[$i][2]);
            if($i==0)
            {
                $materialitems=$material[$i][0]; $materialreceipt=$material[$i][1];$materialqty=$material[$i][2];
            }
            else
            {
                $materialitems=$materialitems.'^'.$material[$i][0]; $materialreceipt=$materialreceipt.'^'.$material[$i][1];$materialqty=$materialqty.'^'.$material[$i][2];
            }
            $MIUrowNumber++;
        }
        $materialusagetable=$materialusagetable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$materialusagetable.'</td></tr>';
    }
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="simple.xls"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
// get the exl content
    @ob_start();
    $objWriter =  PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
    $objWriter->save('php://output');
    $entry_exldata = ob_get_contents();
    ob_end_clean();

    $finaltable=$finaltable.'<br><br><tr><td>REPORT IMAGE<br><br><img id=image src="'.$uploadpath.'"/></td></tr></table></body></html>';
// final table end
//Save Part
    if($imgflag==1){
        $callquery="CALL SP_LMC_REPORT_ENTRY_UPDATE_DELETE(1,'$teamname','$EmployeeReport[0]',
       '$reportdate','$teamlocation',$contractno,'$reachsite','$leavesite','$typeofjob','$weather',
       '$weatherfrom','$weatherto','$pipetesting','$pressurestart','$pressureend','$teamremarks','$docfilename','$imgfilename',
       '$pipelaid','$size','$length',
       '$EmployeeReport[1]','$EmployeeReport[2]','$EmployeeReport[3]','$EmployeeReport[4]',
       ' ','$SV_name','$SV_designation','$SV_start','$SV_end','$SV_remarks',
       ' ','$mech_from','$mech_to','$mech_item','$mech_remark',
       ' ','$mechinerytype','$mechinerystart','$mechineryend','$mechineryremark',
       ' ','$fittingitems','$fittingsize','$fittingqty','$fittingremark',
       ' ','$materialitems','$materialreceipt','$materialqty',
       ' ','$rental_lorryno','$rental_store', '$rental_outside','$rental_start','$rental_end','$rental_remark',
       ' ','$equipmentcompressor','$equipmentlorryno','$equipmentstart','$equipmentend','$equipmentremark','$UserStamp',@SUCCESS_MESSAGE)";
        $result = $con->query($callquery);
        if(!$result){
            unlink($uploadpath);
            die("CALL failed: (" . $con->errno . ") " . $con->error);
        }
        $select = $con->query('SELECT @SUCCESS_MESSAGE');
        $result = $select->fetch_assoc();
        $flag= $result['@SUCCESS_MESSAGE'];
        if($flag!=1 && $uploadpath!='')
        {
            unlink($uploadpath);
        }
    }
    if($flag==1){

        $select_to=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=12");
        if($row=mysqli_fetch_array($select_to)){
            $toaddress=$row["URC_DATA"];
        }
        $select_cc=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=13");
        if($row=mysqli_fetch_array($select_cc)){
            $ccaddress=$row["URC_DATA"];
        }
        $select_host=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=17");
        if($row=mysqli_fetch_array($select_host)){
            $host=$row["URC_DATA"];
        }
        $select_username=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=14");
        if($row=mysqli_fetch_array($select_username)){
            $username=$row["URC_DATA"];
        }
        $select_password=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=15");
        if($row=mysqli_fetch_array($select_password)){
            $password=$row["URC_DATA"];
        }

        $select_smtpsecure=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=16");
        if($row=mysqli_fetch_array($select_smtpsecure)){
            $smtpsecure=$row["URC_DATA"];
        }

        $select_from=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=18");
        if($row=mysqli_fetch_array($select_from)){
            $from=$row["URC_DATA"];
        }
        $select_emailtemp=mysqli_query($con,"SELECT ETD_EMAIL_SUBJECT, ETD_EMAIL_BODY FROM LMC_EMAIL_TEMPLATE_DETAILS where ETD_ID=6");
        if($row=mysqli_fetch_array($select_emailtemp)){
            $sub=$row["ETD_EMAIL_SUBJECT"];
            $msgbody=$row["ETD_EMAIL_BODY"];
        }
        $replace= array( "[SADMIN]","[UNAME]","[DATE]");
        $str_replaced  = array('', $EmployeeReport[5],date('d-m-Y',strtotime($reportdate)));
        $emailbody = str_replace($replace, $str_replaced, $msgbody);
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = $smtpsecure;
        $mail->From = $from;
        $mail->FromName = 'LMC';
        $mail->addAddress($toaddress);
        $mail->WordWrap = 50;
        $mail->isHTML(true);
        $mail->Subject =$sub;
        $mail->Body =$emailbody;
        // pdf attachment
        $reportfilename='TIME SHEET REPORT FOR '.$EmployeeReport[5].' ON '.date('d-m-Y',strtotime($reportdate)).'.pdf';
        $mpdf=new mPDF('utf-8','A4');
        $mpdf->debug=true;
        $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">LIH MING CONSTRUCTION PTE LTD</div></h3>', 'O', true);
        $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
        $mpdf->WriteHTML($finaltable);
        $reportpdf=$mpdf->Output('foo.pdf','S');
        $mail->AddStringAttachment($reportpdf,$reportfilename);
        // excel attachment
        $xlreportfilename='TIME SHEET REPORT FOR '.$EmployeeReport[5].' ON '.date('d-m-Y',strtotime($reportdate)).'.xls';
        $mail->AddStringAttachment($entry_exldata,$xlreportfilename);
        $mail->Send();
    }
    echo $flag;
}
elseif($_REQUEST['option']=='EMPLOYEE_NAME')
{
    $teamname=$_REQUEST['teamname'];
    $date=date('Y-m-d',strtotime($_REQUEST['date']));

    $reportdetails=mysqli_query($con,"SELECT EMP_ID,TERD_START_TIME,TERD_END_TIME,TERD_OT,TERD_REMARK FROM lmc_team_employee_report_details WHERE TRD_ID=(SELECT TRD_ID FROM LMC_TEAM_REPORT_DETAILS WHERE TC_ID=(SELECT TC_ID FROM LMC_TEAM_CREATION WHERE TEAM_NAME='$teamname')AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($reportdetails)){
        $report_details[]=array($row["EMP_ID"],$row["TERD_START_TIME"],$row["TERD_END_TIME"],$row["TERD_OT"],$row["TERD_REMARK"]);
    }
    //EMPLOYEE NAME
    $empname=mysqli_query($con,"select concat(EMP_FIRST_NAME,' ',EMP_LAST_NAME) as EMPLOYEE_NAME,EMP_ID from LMC_EMPLOYEE_DETAILS where TC_ID=(select distinct TC_ID  from LMC_TEAM_CREATION where TEAM_NAME='$teamname')");
    while($row=mysqli_fetch_array($empname)){
        $employeename[]=array($row["EMPLOYEE_NAME"],$row["EMP_ID"]);
    }
    //CURRENT EMPLOYEE NAME
    $activeempname=mysqli_query($con,"SELECT EMP_ID,CONCAT(EMP_FIRST_NAME,' ',EMP_LAST_NAME) AS ACTIVE_EMPLOYEE_NAME FROM LMC_EMPLOYEE_DETAILS E,LMC_USER_LOGIN_DETAILS U WHERE E.ULD_ID=U.ULD_ID AND U.ULD_USERNAME='$UserStamp'");
    if($row=mysqli_fetch_array($activeempname))
    {
        $activeemp_name[]=array($row["EMP_ID"]);
    }
    $sql="SELECT * FROM LMC_TEAM_REPORT_DETAILS WHERE TRD_DATE='$date' AND TC_ID=(SELECT TC_ID FROM LMC_TEAM_CREATION WHERE TEAM_NAME='$teamname') AND EMP_ID='$activeemp'";
    $sql_result= mysqli_query($con,$sql);
    $row=mysqli_num_rows($sql_result);
    if($row>0)
    {
        $flag=1;
    }
    else
    {
        $flag=0;
    }
    $values=array($employeename,$report_details,$activeemp_name,$flag);
    echo JSON_encode($values);
}
elseif($_REQUEST['option']=='SEARCH_DATA')
{
    $team=$_REQUEST['team'];
    $date=date('Y-m-d',strtotime($_REQUEST['date']));
    //TEAM REPORT DETAILS
    $teamreport_details=mysqli_query($con,"SELECT DATE_FORMAT(TRD_DATE,'%d-%m-%Y') AS TRD_DATE,TRD_LOCATION,TRD_CONTRACT_NO,T1.TEAM_NAME,DATE_FORMAT(TRD_REACH_SITE,'%H:%i' ) AS REACHSITE,DATE_FORMAT(TRD_LEAVE_SITE,'%H:%i' ) AS LEAVESITE,TOJ_ID,DATE_FORMAT(TRD_WEATHER_FROM_TIME,'%H:%i' ) AS WEATHERFROM,DATE_FORMAT(TRD_WEATHER_TO_TIME,'%H:%i' ) AS WEATHERTO,TRD_PIPE_TESTING,TRD_START_PRESSURE,TRD_END_PRESSURE,TRD_REMARK,TRD_WEATHER_REASON,TRD_IMG_FILE_NAME,TRD_DOC_FILE_NAME FROM LMC_TEAM_REPORT_DETAILS L,LMC_TEAM_CREATION T1 WHERE L.TC_ID=T1.TC_ID AND TRD_DATE='$date' AND L.EMP_ID='$activeemp'");
    while($row=mysqli_fetch_array($teamreport_details))
    {
        $jobid=$row["TOJ_ID"];
        $filname=$row['TRD_IMG_FILE_NAME'];
        $userfolderid=get_emp_folderid($activeemp);
        if($filname!=null || $filname!=''){
            $upload_dir = $dir.$parentfolder.DIRECTORY_SEPARATOR.$userfolderid.DIRECTORY_SEPARATOR;

            $path = $upload_dir.$filname;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        $team_report_details[]=array($row["TRD_DATE"],$row["TRD_LOCATION"],$row["TRD_CONTRACT_NO"],$row["TEAM_NAME"],$row["REACHSITE"],$row["LEAVESITE"],$row["TOJ_ID"],$row["WEATHERFROM"],$row["WEATHERTO"],$row["TRD_PIPE_TESTING"],$row["TRD_START_PRESSURE"],$row["TRD_END_PRESSURE"],$row["TRD_REMARK"],$row["TRD_WEATHER_REASON"],$row['TRD_DOC_FILE_NAME']);
    }
    //JOB ID DETAILS
    $jobdetails=mysqli_query($con,"SELECT GROUP_CONCAT(TOJ_JOB) AS JOB FROM lmc_TYPE_OF_job WHERE TOJ_ID IN($jobid)");
    if($row=mysqli_fetch_array($jobdetails))
    {
        $job_details=$row["JOB"];
    }
    //EMPLOYEE DETAILS
    $empdetails=mysqli_query($con,"SELECT L.EMP_ID,CONCAT(EMP_FIRST_NAME,' ',EMP_LAST_NAME) AS EMPNAME,DATE_FORMAT(L1.TERD_START_TIME,'%H:%i' ) AS STARTTIME,DATE_FORMAT(L1.TERD_END_TIME,'%H:%i' ) AS ENDTIME,L1.TERD_OT,L1.TERD_REMARK FROM LMC_EMPLOYEE_DETAILS L LEFT JOIN lmc_team_employee_report_details L1  ON L.EMP_ID=L1.EMP_ID AND TRD_ID IN (SELECT TRD_ID FROM LMC_TEAM_REPORT_DETAILS WHERE TRD_DATE='$date') and L.EMP_ID='$activeemp'");
    while($row=mysqli_fetch_array($empdetails))
    {
        $employeedetails[]=array($row["EMP_ID"],$row["EMPNAME"],$row["STARTTIME"],$row["ENDTIME"],$row["TERD_OT"],$row["TERD_REMARK"]);
    }
    //SITE VISIT DETAILS
    $sitevisitdetails=mysqli_query($con,"SELECT SVD_ID,SVD_NAME,SVD_DESIGNATION,DATE_FORMAT(SVD_START_TIME,'%H:%i' ) AS SVDSTARTTIME,DATE_FORMAT(SVD_END_TIME,'%H:%i' ) AS SVDENDTIME,SVD_REMARK FROM lmc_site_visit_details WHERE TRD_ID=(SELECT TRD_ID FROM lmc_team_report_details WHERE EMP_ID='$activeemp' AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($sitevisitdetails))
    {
        $sitevisit_details[]=array($row["SVD_ID"],$row["SVD_NAME"],$row["SVD_DESIGNATION"],$row["SVDSTARTTIME"],$row["SVDENDTIME"],$row["SVD_REMARK"]);
    }
    //MACHINERY_EQUIPMENT DETAILS
    $mech_equip_details=mysqli_query($con,"SELECT MET_ID,MET_FROM_LORRY_NO,MET_TO_LORRY_NO,MET_ITEM,MET_REMARK FROM LMC_MACHINERY_EQUIPMENT_TRANSFER WHERE TRD_ID=(SELECT TRD_ID FROM lmc_team_report_details WHERE EMP_ID='$activeemp' AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($mech_equip_details))
    {
        $mechequip_details[]=array($row["MET_ID"],$row["MET_FROM_LORRY_NO"],$row["MET_TO_LORRY_NO"],$row["MET_ITEM"],$row["MET_REMARK"]);
    }
    //MACHINERY USAGE DETAILS
    $machineryusage_details=mysqli_query($con,"SELECT MAC_ID,MCU_MACHINERY_TYPE,DATE_FORMAT(MAC_START_TIME,'%H:%i' ) AS MACSTARTTIME,DATE_FORMAT(MAC_END_TIME,'%H:%i' ) AS MACENDTIME,MAC_REMARK FROM lmc_machinery_usage_details LMUD,lmc_machinery_usage LMU WHERE LMUD.MCU_ID=LMU.MCU_ID AND TRD_ID=(SELECT TRD_ID FROM lmc_team_report_details WHERE EMP_ID='$activeemp' AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($machineryusage_details))
    {
        $machinery_usage_details[]=array($row["MAC_ID"],$row["MCU_MACHINERY_TYPE"],$row["MACSTARTTIME"],$row["MACENDTIME"],$row["MAC_REMARK"]);
    }
    //RENTAL MACHINERY USAGE DETAILS
    $rental_machinery_details=mysqli_query($con,"SELECT RMD_ID,RMD_LORRY_NO,RMD_THROWEARTH_STORE,RMD_THROWEARTH_OUTSIDE,DATE_FORMAT(RMD_START_TIME,'%H:%i' ) AS RMDSTARTTIME,DATE_FORMAT(RMD_END_TIME,'%H:%i' ) AS RMDENDTIME,RMD_REMARK FROM lmc_rental_machinery_details WHERE TRD_ID=(SELECT TRD_ID FROM lmc_team_report_details WHERE EMP_ID='$activeemp' AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($rental_machinery_details))
    {
        $rentalmachinery_details[]=array($row["RMD_ID"],$row["RMD_LORRY_NO"],$row["RMD_THROWEARTH_STORE"],$row["RMD_THROWEARTH_OUTSIDE"],$row["RMDSTARTTIME"],$row["RMDENDTIME"],$row["RMD_REMARK"]);
    }
    //EQUIPMENT USAGE DETAILS
    $equipment_usage_details=mysqli_query($con,"SELECT EUD_ID,EUD_EQUIPMENT,EUD_LORRY_NO,DATE_FORMAT(EUD_START_TIME,'%H:%i' ) AS EUDSTARTTIME,DATE_FORMAT(EUD_END_TIME,'%H:%i' ) AS EUDENDTIME,EUD_REMARK FROM lmc_equipment_usage_details WHERE TRD_ID=(SELECT TRD_ID FROM lmc_team_report_details WHERE EMP_ID='$activeemp' AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($equipment_usage_details))
    {
        $equipmentusage_details[]=array($row["EUD_ID"],$row["EUD_EQUIPMENT"],$row["EUD_LORRY_NO"],$row["EUDSTARTTIME"],$row["EUDENDTIME"],$row["EUD_REMARK"]);
    }
    //FITTING USAGE DETAILS
    $fitting_usage_details=mysqli_query($con,"SELECT FUD_ID,FU_ITEMS,FUD_SIZE,FUD_QUANTITY,FUD_REMARK FROM lmc_fitting_usage_details LFUD,lmc_fitting_usage LFU WHERE LFUD.FU_ID=LFU.FU_ID AND TRD_ID=(SELECT TRD_ID FROM lmc_team_report_details WHERE EMP_ID='$activeemp' AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($fitting_usage_details))
    {
        $fittingusage_details[]=array($row["FUD_ID"],$row["FU_ITEMS"],$row["FUD_SIZE"],$row["FUD_QUANTITY"],$row["FUD_REMARK"]);
    }
    //MATERIAL USAGE DETAILS
    $material_usage_details=mysqli_query($con,"SELECT MUD_ID,MU_ITEMS,MUD_RECEIPT_NO,MUD_QUANTITY FROM lmc_material_usage_details LMUD,lmc_material_usage LMU WHERE LMUD.MU_ID=LMU.MU_ID AND TRD_ID=(SELECT TRD_ID FROM lmc_team_report_details WHERE EMP_ID='$activeemp' AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($material_usage_details))
    {
        $materialusage_details[]=array($row["MUD_ID"],$row["MU_ITEMS"],$row["MUD_RECEIPT_NO"],$row["MUD_QUANTITY"]);
    }
    //TEAM JOB
    $typeofjob=mysqli_query($con,"select TOJ_JOB,TOJ_ID from LMC_TYPE_OF_JOB");
    while($row=mysqli_fetch_array($typeofjob)){
        $joptype[]=array($row["TOJ_JOB"],$row['TOJ_ID']);
    }
    //CURRENT EMPLOYEE NAME
    $activeempname=mysqli_query($con,"SELECT EMP_ID,CONCAT(EMP_FIRST_NAME,' ',EMP_LAST_NAME) AS ACTIVE_EMPLOYEE_NAME FROM LMC_EMPLOYEE_DETAILS E,LMC_USER_LOGIN_DETAILS U WHERE E.ULD_ID=U.ULD_ID AND U.ULD_USERNAME='$UserStamp'");
    if($row=mysqli_fetch_array($activeempname))
    {
        $activeemp_name[]=array($row["EMP_ID"]);
    }
    //JOB DONE
    $jobdonedetails=mysqli_query($con,"SELECT GROUP_CONCAT(TJ_PIPE_LAID) as PIPELAID,GROUP_CONCAT(TJ_SIZE) AS SIZE,GROUP_CONCAT(TJ_LENGTH) AS LENGTH FROM lmc_team_job WHERE TRD_ID=(SELECT TRD_ID FROM lmc_team_report_details WHERE EMP_ID='$activeemp' AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($jobdonedetails)){
        $jobdone_pipelaid[]=array($row["PIPELAID"]);
        $jobdone_size[]=array($row['SIZE']);
        $jobdone_length[]=array($row["LENGTH"]);
    }
    //ERRPOR MESSAGE
    $errormsg=get_error_msg('4,17,83,133,143');
    $folderid=mysqli_query($con,"SELECT EMP_IMAGE_FOLDER_ID FROM LMC_EMPLOYEE_DETAILS WHERE EMP_ID='$activeemp'");
    if($row=mysqli_fetch_array($folderid))
    {
        $imagefoldderid=$row['EMP_IMAGE_FOLDER_ID'];
    }
    $values=array($employeedetails,$sitevisit_details,$mechequip_details,$machinery_usage_details,$rentalmachinery_details,$equipmentusage_details,$fittingusage_details,$materialusage_details,$team_report_details,$job_details,$joptype,$activeemp_name,$jobdone_pipelaid,$jobdone_size,$jobdone_length,$base64,$errormsg,$imagefoldderid);
    echo JSON_encode($values);
}
elseif($_REQUEST['option']=='UPDATE_SEARCH_DATA')
{
    $emp=$_REQUEST['emp'];
    $fromdate=date('Y-m-d',strtotime($_REQUEST['fromdate']));
    $todate=date('Y-m-d',strtotime($_REQUEST['todate']));
    //EMPLOYEE DETAILS
    $empdetails=mysqli_query($con," SELECT L1.EMP_ID,DATE_FORMAT(TRD.TRD_DATE,'%d-%m-%Y') AS TRD_DATE,DATE_FORMAT(L1.TERD_START_TIME,'%H:%i' ) AS STARTTIME,DATE_FORMAT(L1.TERD_END_TIME,'%H:%i' ) AS ENDTIME,L1.TRD_ID,L1.TERD_OT,L1.TERD_REMARK,ULD.ULD_USERNAME,DATE_FORMAT(CONVERT_TZ(L1.TERD_TIMESTAMP,'+00:00','+08:00'),'%d-%m-%Y %T') AS TERD_TIMESTAMP FROM LMC_TEAM_EMPLOYEE_REPORT_DETAILS L1,LMC_TEAM_REPORT_DETAILS TRD,LMC_USER_LOGIN_DETAILS ULD WHERE TRD.TRD_DATE BETWEEN '$fromdate' AND '$todate' AND TRD.EMP_ID='$emp' AND L1.TRD_ID=TRD.TRD_ID AND TRD.ULD_ID=ULD.ULD_ID ORDER BY TRD.TRD_DATE ASC ");
    while($row=mysqli_fetch_array($empdetails))
    {
        $employeedetails[]=array($row['EMP_ID'],$row["TRD_DATE"],$row["STARTTIME"],$row["ENDTIME"],$row["TRD_ID"],$row["TERD_OT"],$row['TERD_REMARK'],$row['ULD_USERNAME'],$row['TERD_TIMESTAMP']);
    }
//ERRPOR MESSAGE
    $errormsg=get_error_msg('4,17,21,83,133,143');

    $values=array($employeedetails,$errormsg);
    echo JSON_encode($values);
}
elseif($_REQUEST['option']=='UPDATE_SEARCH')
{
    $trdid=$_REQUEST['trdid'];
    $empid=$_REQUEST['selectedemp'];
    //TEAM REPORT DETAILS
    $teamreport_details=mysqli_query($con,"SELECT DATE_FORMAT(TRD_DATE,'%d-%m-%Y') AS TRD_DATE,TRD_LOCATION,TRD_CONTRACT_NO,T1.TEAM_NAME,DATE_FORMAT(TRD_REACH_SITE,'%H:%i' ) AS REACHSITE,DATE_FORMAT(TRD_LEAVE_SITE,'%H:%i' ) AS LEAVESITE,TOJ_ID,DATE_FORMAT(TRD_WEATHER_FROM_TIME,'%H:%i' ) AS WEATHERFROM,DATE_FORMAT(TRD_WEATHER_TO_TIME,'%H:%i' ) AS WEATHERTO,TRD_PIPE_TESTING,TRD_START_PRESSURE,TRD_END_PRESSURE,TRD_REMARK,TRD_WEATHER_REASON,TRD_IMG_FILE_NAME,TRD_DOC_FILE_NAME FROM LMC_TEAM_REPORT_DETAILS L,LMC_TEAM_CREATION T1 WHERE L.TC_ID=T1.TC_ID AND TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($teamreport_details))
    {
        $jobid=$row["TOJ_ID"];
        $filname=$row['TRD_IMG_FILE_NAME'];
        $userfolderid=get_emp_folderid($activeemp);
        if($filname!=null || $filname!=''){
            $upload_dir = $dir.$parentfolder.DIRECTORY_SEPARATOR.$userfolderid.DIRECTORY_SEPARATOR;

            $path = $upload_dir.$filname;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        $team_report_details[]=array($row["TRD_DATE"],$row["TRD_LOCATION"],$row["TRD_CONTRACT_NO"],$row["TEAM_NAME"],$row["REACHSITE"],$row["LEAVESITE"],$row["TOJ_ID"],$row["WEATHERFROM"],$row["WEATHERTO"],$row["TRD_PIPE_TESTING"],$row["TRD_START_PRESSURE"],$row["TRD_END_PRESSURE"],$row["TRD_REMARK"],$row["TRD_WEATHER_REASON"],$row['TRD_DOC_FILE_NAME']);
    }
    //JOB ID DETAILS
    $jobdetails=mysqli_query($con,"SELECT GROUP_CONCAT(TOJ_JOB) AS JOB FROM lmc_TYPE_OF_job WHERE TOJ_ID IN($jobid)");
    if($row=mysqli_fetch_array($jobdetails))
    {
        $job_details=$row["JOB"];
    }
    //EMPLOYEE DETAILS
    $empdetails=mysqli_query($con,"SELECT L.EMP_ID,CONCAT(EMP_FIRST_NAME,' ',EMP_LAST_NAME) AS EMPNAME,DATE_FORMAT(L1.TERD_START_TIME,'%H:%i' ) AS STARTTIME,DATE_FORMAT(L1.TERD_END_TIME,'%H:%i' ) AS ENDTIME,L1.TERD_OT,L1.TERD_REMARK FROM LMC_EMPLOYEE_DETAILS L LEFT JOIN lmc_team_employee_report_details L1  ON L.EMP_ID=L1.EMP_ID AND TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($empdetails))
    {
        $employeedetails[]=array($row["EMP_ID"],$row["EMPNAME"],$row["STARTTIME"],$row["ENDTIME"],$row["TERD_OT"],$row["TERD_REMARK"]);
    }
    //SITE VISIT DETAILS
    $sitevisitdetails=mysqli_query($con,"SELECT SVD_ID,SVD_NAME,SVD_DESIGNATION,DATE_FORMAT(SVD_START_TIME,'%H:%i' ) AS SVDSTARTTIME,DATE_FORMAT(SVD_END_TIME,'%H:%i' ) AS SVDENDTIME,SVD_REMARK FROM lmc_site_visit_details WHERE TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($sitevisitdetails))
    {
        $sitevisit_details[]=array($row["SVD_ID"],$row["SVD_NAME"],$row["SVD_DESIGNATION"],$row["SVDSTARTTIME"],$row["SVDENDTIME"],$row["SVD_REMARK"]);
    }
    //MACHINERY_EQUIPMENT DETAILS
    $mech_equip_details=mysqli_query($con,"SELECT MET_ID,MET_FROM_LORRY_NO,MET_TO_LORRY_NO,MET_ITEM,MET_REMARK FROM LMC_MACHINERY_EQUIPMENT_TRANSFER WHERE TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($mech_equip_details))
    {
        $mechequip_details[]=array($row["MET_ID"],$row["MET_FROM_LORRY_NO"],$row["MET_TO_LORRY_NO"],$row["MET_ITEM"],$row["MET_REMARK"]);
    }
    //MACHINERY USAGE DETAILS
    $machineryusage_details=mysqli_query($con,"SELECT MAC_ID,MCU_MACHINERY_TYPE,DATE_FORMAT(MAC_START_TIME,'%H:%i' ) AS MACSTARTTIME,DATE_FORMAT(MAC_END_TIME,'%H:%i' ) AS MACENDTIME,MAC_REMARK FROM lmc_machinery_usage_details LMUD,lmc_machinery_usage LMU WHERE LMUD.MCU_ID=LMU.MCU_ID AND TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($machineryusage_details))
    {
        $machinery_usage_details[]=array($row["MAC_ID"],$row["MCU_MACHINERY_TYPE"],$row["MACSTARTTIME"],$row["MACENDTIME"],$row["MAC_REMARK"]);
    }
    //RENTAL MACHINERY USAGE DETAILS
    $rental_machinery_details=mysqli_query($con,"SELECT RMD_ID,RMD_LORRY_NO,RMD_THROWEARTH_STORE,RMD_THROWEARTH_OUTSIDE,DATE_FORMAT(RMD_START_TIME,'%H:%i' ) AS RMDSTARTTIME,DATE_FORMAT(RMD_END_TIME,'%H:%i' ) AS RMDENDTIME,RMD_REMARK FROM lmc_rental_machinery_details WHERE TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($rental_machinery_details))
    {
        $rentalmachinery_details[]=array($row["RMD_ID"],$row["RMD_LORRY_NO"],$row["RMD_THROWEARTH_STORE"],$row["RMD_THROWEARTH_OUTSIDE"],$row["RMDSTARTTIME"],$row["RMDENDTIME"],$row["RMD_REMARK"]);
    }
    //EQUIPMENT USAGE DETAILS
    $equipment_usage_details=mysqli_query($con,"SELECT EUD_ID,EUD_EQUIPMENT,EUD_LORRY_NO,DATE_FORMAT(EUD_START_TIME,'%H:%i' ) AS EUDSTARTTIME,DATE_FORMAT(EUD_END_TIME,'%H:%i' ) AS EUDENDTIME,EUD_REMARK FROM lmc_equipment_usage_details WHERE TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($equipment_usage_details))
    {
        $equipmentusage_details[]=array($row["EUD_ID"],$row["EUD_EQUIPMENT"],$row["EUD_LORRY_NO"],$row["EUDSTARTTIME"],$row["EUDENDTIME"],$row["EUD_REMARK"]);
    }
    //FITTING USAGE DETAILS
    $fitting_usage_details=mysqli_query($con,"SELECT FUD_ID,FU_ITEMS,FUD_SIZE,FUD_QUANTITY,FUD_REMARK FROM lmc_fitting_usage_details LFUD,lmc_fitting_usage LFU WHERE LFUD.FU_ID=LFU.FU_ID AND TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($fitting_usage_details))
    {
        $fittingusage_details[]=array($row["FUD_ID"],$row["FU_ITEMS"],$row["FUD_SIZE"],$row["FUD_QUANTITY"],$row["FUD_REMARK"]);
    }
    //MATERIAL USAGE DETAILS
    $material_usage_details=mysqli_query($con,"SELECT MUD_ID,MU_ITEMS,MUD_RECEIPT_NO,MUD_QUANTITY FROM lmc_material_usage_details LMUD,lmc_material_usage LMU WHERE LMUD.MU_ID=LMU.MU_ID AND TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($material_usage_details))
    {
        $materialusage_details[]=array($row["MUD_ID"],$row["MU_ITEMS"],$row["MUD_RECEIPT_NO"],$row["MUD_QUANTITY"]);
    }
    //TEAM JOB
    $typeofjob=mysqli_query($con,"select TOJ_JOB,TOJ_ID from LMC_TYPE_OF_JOB");
    while($row=mysqli_fetch_array($typeofjob)){
        $joptype[]=array($row["TOJ_JOB"],$row['TOJ_ID']);
    }
    //CURRENT EMPLOYEE NAME
    $activeempname=mysqli_query($con,"SELECT EMP_ID,CONCAT(EMP_FIRST_NAME,' ',EMP_LAST_NAME) AS ACTIVE_EMPLOYEE_NAME FROM LMC_EMPLOYEE_DETAILS E,LMC_USER_LOGIN_DETAILS U WHERE E.ULD_ID=U.ULD_ID AND U.ULD_USERNAME='$UserStamp'");
    if($row=mysqli_fetch_array($activeempname))
    {
        $activeemp_name[]=array($row["EMP_ID"]);
    }
    //JOB DONE
    $jobdonedetails=mysqli_query($con,"SELECT GROUP_CONCAT(TJ_PIPE_LAID) as PIPELAID,GROUP_CONCAT(TJ_SIZE) AS SIZE,GROUP_CONCAT(TJ_LENGTH) AS LENGTH FROM LMC_TEAM_JOB WHERE TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($jobdonedetails)){
        $jobdone_pipelaid[]=$row["PIPELAID"];
        $jobdone_size[]=$row['SIZE'];
        $jobdone_length[]=$row["LENGTH"];
    }
    //ERRPOR MESSAGE
    $errormsg=get_error_msg('4,17,21,83,133,143');
    $folderid=mysqli_query($con,"SELECT EMP_IMAGE_FOLDER_ID FROM LMC_EMPLOYEE_DETAILS WHERE EMP_ID='$empid'");
    if($row=mysqli_fetch_array($folderid))
    {
        $imagefoldderid=$row['EMP_IMAGE_FOLDER_ID'];
    }
    $values=array($employeedetails,$sitevisit_details,$mechequip_details,$machinery_usage_details,$rentalmachinery_details,$equipmentusage_details,$fittingusage_details,$materialusage_details,$team_report_details,$job_details,$joptype,$activeemp_name,$jobdone_pipelaid,$jobdone_size,$jobdone_length,$base64,$errormsg,$imagefoldderid);
    echo JSON_encode($values);
}
elseif($_REQUEST['Option']=='UpdateForm')
{
    $teamlocation=$_POST["SRC_tr_txt_location"];
    $contractno=$_POST["SRC_tr_txt_contractno"];
    $teamname=$_POST['SRC_tr_lb_team'];
    $reportdate=$_POST['SRC_tr_txt_date'];
    $weather=$_POST['SRC_tr_txt_weather'];
    $weatherfrom=$_POST['SRC_tr_txt_wftime'];
    $weatherto=$_POST['SRC_tr_txt_wttime'];
    $reachsite=$_POST['SRC_tr_txt_reachsite'];
    $leavesite=$_POST['SRC_tr_txt_leavesite'];
    $joptype=$_POST['jobtype'];

    $reportdate = date('Y-m-d',strtotime($reportdate));
    $typeofjob;
    for($i=0;$i<count($joptype);$i++){
        if($i==0){
            $typeofjob=$joptype[$i];
        }
        else{
            $typeofjob=$typeofjob .",".$joptype[$i];
        }
    }
//JOB DONE ELEMENTS
    $pipelaidroad=$_POST['SRC_jd_chk_road'];
    $pipelaidconc=$_POST['SRC_jd_chk_contc'];
    $pipelaidturf=$_POST['SRC_jd_chk_truf'];
    $pipetesting=$_POST['SRC_jd_txt_pipetesting'];
    $pressurestart=$_POST['SRC_jd_txt_start'];
    $pressureend=$_POST['SRC_jd_txt_end'];
    $teamremarks=$_POST['SRC_jd_ta_remark'];
    $roadm=$_POST['SRC_jd_chk_roadm'];
    $roadmm=$_POST['SRC_jd_chk_roadmm'];
    $concm=$_POST['SRC_jd_chk_concm'];
    $concmm=$_POST['SRC_jd_chk_concmm'];
    $turfm=$_POST['SRC_jd_chk_trufm'];
    $turfmm=$_POST['SRC_jd_chk_trufmm'];

    $pipelaid='';
    $size='';
    $length='';
    if($pipelaidroad=='on' || $pipelaidconc=='on' || $pipelaidturf=='on'){
        if($pipelaidroad=='on')
        {
            $pipelaidroad='ROAD';
            $roadm;
            $roadmm;
            if($pipelaidconc=='on' || $pipelaidturf=='on'){
                $pipelaid1= $pipelaidroad.'^';
                $size1=$roadm.'^';
                $length1=$roadmm.'^';
            }
            else{
                $pipelaid1=$pipelaidroad;
                $size1=$roadm;
                $length1=$roadmm;
            }
        }

        if($pipelaidconc=='on')
        {
            $pipelaidconc='CONC';
            $concm;
            $concmm;
            if($pipelaidroad=='on' && $pipelaidturf!='on'){
                $pipelaid2= '^'.$pipelaidconc;
                $size2='^'.$concm;
                $length2='^'.$concmm;
            }
            if($pipelaidroad!='on' && $pipelaidturf=='on'){
                $pipelaid2= $pipelaidconc.'^';
                $size2=$concm.'^';
                $length2=$concmm.'^';
            }
            if($pipelaidroad=='on' && $pipelaidturf=='on'){
                $pipelaid2= '^'.$pipelaidconc.'^';
                $size2='^'.$concm.'^';
                $length2='^'.$concmm.'^';
            }
            if($pipelaidroad!='on' && $pipelaidturf!='on') {
                $pipelaid2= $pipelaidconc;
                $size2=$concm;
                $length2=$concmm;
            }
        }

        if($pipelaidturf=='on')
        {
            $pipelaidturf='TURF';
            $turfm;
            $turfmm;
            if($pipelaidroad=='on' || $pipelaidconc=='on'){
                $pipelaid3= '^'.$pipelaidturf;
                $size3='^'.$turfm;
                $length3='^'.$turfmm;
            }
            else{
                $pipelaid3=$pipelaidturf;
                $size3=$turfm;
                $length3=$turfmm;
            }
        }
    }
    $pipelaid=$pipelaid1.$pipelaid2.$pipelaid3;
    $size=$size1.$size2.$size3;
    $length=$length1.$length2.$length3;

    $material=$_POST["SRC_MaterialDetails"];
    $fitting=$_POST["SRC_FittingDetails"];
    $equipment=$_POST["SRC_EquipmentDetails"];
    $rental=$_POST["SRC_RentalDetails"];
    $mechinery=$_POST["SRC_MechineryUsageDetails"];
    $mech_eqp_transfer=$_POST["SRC_MechEqptransfer"];
    $SV_details=$_POST["SRC_SiteVisit"];
    $EmployeeReport=$_POST["SRC_EmployeeDetails"];
    $imagedata=$_POST['imgData'];
    $uploadcount=$_POST['uploadcount'];
    //File upload function
    $newfilename=$_POST['filename'];
    $oldfilename=$_POST['oldfilename'];

    if(($newfilename!='') && ($oldfilename!='')){
        $filename=$oldfilename.'/'.$newfilename;
    }
    if(($newfilename=='') && ($oldfilename=='')){
        $filename=null;
    }
    if($oldfilename==''){
        $filename=$newfilename;
    }
    if($newfilename==''){
        $filename=$oldfilename;
    }
    $oldimgfileid=mysqli_query($con,"SELECT TRD_IMG_FILE_NAME FROM LMC_TEAM_REPORT_DETAILS WHERE TRD_DATE='$reportdate' AND EMP_ID='$activeemp'");
    if($row=mysqli_fetch_array($oldimgfileid))
    {
        $old_imgfileid=$row['TRD_IMG_FILE_NAME'];
    }
    //End of File Uploads
    $userfolderid;
    if($imagedata!='' && $reportdate!='' && $EmployeeReport[0]!='' && $teamname!=''){
        $daterep=str_replace('-','',$reportdate);
        $imgfilename=$EmployeeReport[0].'_'.$daterep.'_'.date('His').'.png';
        $userfolderid=get_emp_folderid($EmployeeReport[0]);
        $uploadpath=$dir.$parentfolder.DIRECTORY_SEPARATOR.$userfolderid.DIRECTORY_SEPARATOR.$imgfilename;
        try{
            $data=str_replace('data:image/png;base64,','',$imagedata);
            $data = str_replace(' ','+',$data);
            $data = base64_decode($data);
            $success = file_put_contents($uploadpath, $data);
            $imgflag=1;
        }
        catch(Exception $e){
            print $e->getMessage();
            unlink($uploadpath);
            $imgflag=0;
        }
    }
    elseif($imagedata=='' || $reportdate=='' || $EmployeeReport[0]=='' || $teamname==''){
        $imgflag=0;
    }
    if($weather!=''){
        $weathertime=$weather.' ('.$weatherfrom.' TO '.$weatherto.')';
    }
    else{
        $weathertime='';
    }
    //TEAM REPORT DETAILS
    $emp_name=mysqli_query($con,"SELECT CONCAT(EMP_FIRST_NAME,' ',EMP_LAST_NAME)AS EMP_NAME FROM LMC_EMPLOYEE_DETAILS WHERE EMP_ID=$EmployeeReport[0]");
    while($row=mysqli_fetch_array($emp_name)){
        $empnames=$row["EMP_NAME"];
    }
    $jobname=mysqli_query($con,"SELECT GROUP_CONCAT(TOJ_JOB) AS JOB FROM lmc_TYPE_OF_job WHERE TOJ_ID IN($typeofjob)");
    while($row=mysqli_fetch_array($jobname)){
        $jobnames=$row["JOB"];
    }
    $teamreporttable='<table width=1000 colspan=3px cellpadding=3px  border="0"><caption style="caption-side: left;font-weight: bold;">TEAM REPORT</caption>
   <tr><td width="100" style= "border: 1px solid black;color:#fff; background-color:#498af3;font-weight: bold;" height=25px>LOCATION</td><td width="360">'.$teamlocation.'</td><td width="130" style="color:#fff; background-color:#498af3;border: 1px solid black;font-weight: bold;" height=25px>CONTRACT NO</td><td  width="250">'.$contractno.'</td><td width="150">'.$teamname.'</td></tr></table>
   <table width=1000 colspan=3px cellpadding=3px  border="0"><tr><td width="250" style="border: 1px solid black;color:#fff; background-color:#498af3;font-weight: bold;" height=25px>DATE</td><td style="border: 1px solid black;"width="250">'.$reportdate.'</td><td width="250" style="color:#fff; background-color:#498af3;border: 1px solid black;font-weight: bold;" height=25px>WEATHER</td><td style="border: 1px solid black;" width="250">'.$weathertime.'</td></tr>
   <tr><td width="250" style="color:#fff; background-color:#498af3; border: 1px solid black;font-weight: bold;" height=25px>REACH SITE</td><td style="border: 1px solid black;" width="250">'.$reachsite.'</td><td width="250" style="color:#fff; background-color:#498af3; border: 1px solid black;font-weight: bold;" height=25px>LEAVE SITE</td><td style="border: 1px solid black;" width="250">'.$leavesite.'</td></tr>
   <tr><td width="250" colspan="1" style="color:#498af3;border: 1px solid black;color:#fff; background-color:#498af3;font-weight: bold;" height=25px>TYPE OF JOB</td><td style="border: 1px solid black;" width="250" colspan="3">'.$jobnames.'</td></tr>
   </table>';

//JOB DONE DETAILS
    $jobdonetable='<br><table width=1000 colspan=3px cellpadding=3px  border="0"><caption style="caption-side: left;font-weight: bold;">JOB DONE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/>
   <tr><td width="250" style="color:#fff; background-color:#498af3;border: 1px solid black;font-weight: bold;" height=25px>PIPELAID</td><td style="border: 1px solid black;text-align:center;" width="250" colspan=2>ROAD</td><td width="250" colspan=2 style="border: 1px solid black;text-align:center;">CONC</td><td width="250" colspan=2 style="border: 1px solid black;text-align:center;">TRUF</td></tr>
   <tr><td style="color:#fff; background-color:#498af3;border: 1px solid black;font-weight: bold; " height=25px>SIZE/LENGTH</td><td style="border: 1px solid black;">'.$roadm.'</td><td style="border: 1px solid black;">'.$roadmm.'</td><td style="border: 1px solid black;">'.$concm.'</td><td style="border: 1px solid black;">'.$concmm.'</td><td style="border: 1px solid black;">'.$turfm.'</td><td style="border: 1px solid black;">'.$turfmm.'</td>
   <tr><td style="color:#fff; background-color:#498af3; border: 1px solid black;font-weight: bold;" height=25px>PIPE TESTING</td><td colspan="2" style="color:#fff; background-color:#498af3;border: 1px solid black;font-weight: bold;text-align:center;" height=25px>START(PRESSURE)</td><td colspan="2" style="color:#fff; background-color:#498af3;border: 1px solid black;font-weight: bold;text-align:center;" height=25px>END(PRESSURE)</td><td colspan="2" style="color:#fff; background-color:#498af3;border: 1px solid black;font-weight: bold;text-align:center;" height=25px>REMARK</td></tr>
   <tr><td style="border: 1px solid black;">'.$pipetesting.'</td><td colspan="2" style="border: 1px solid black;">'.$pressurestart.'</td><td style="border: 1px solid black;"colspan="2">'.$pressureend.'</td><td colspan="2" style="border: 1px solid black;">'.$teamremarks.'</td></tr>
   </table>';
//EMPLOYEE TABLE
    $employeetable='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;">EMPLOYEE DETAILS</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>EMPLOYEE NAME</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>START</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>END</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>OT</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>REMARKS</b></td></tr></th>';
    $employeetable=$employeetable."<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$empnames."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$EmployeeReport[1]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$EmployeeReport[2]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$EmployeeReport[3]."</td><td nowrap style='border: 1px solid black;'>".$EmployeeReport[4]."</td></tr></table>";
// final table start
    $reportheadername='TIME SHEET UPDATED REPORT FOR '.$empnames;
    $finaltable='<html><body><table><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">'.$reportheadername.'</div></h2></td></tr><br><tr><td>'.$teamreporttable.'</td></tr><br><br><tr><td>'.$jobdonetable.'</td></tr><br><br><tr><td>'.$employeetable.'</td></tr>';

    $sheettitle='LIH MING CONSTRUCTION PTE LTD
TIME SHEET UPDATED REPORT FOR '.$empnames;
    $objPHPExcel->getActiveSheet()->setTitle('LMC TS UPDATED REPORT')->setCellValue('A1', $sheettitle)->setCellValue('a2', 'TEAM REPORT')->setCellValue('a8', 'JOB DONE')->setCellValue('a14', 'EMPLOYEE REPORT')->setCellValue('a3', 'LOCATION')->setCellValue('b3',$teamlocation)->setCellValue('c3', 'CONTRACT NO')->setCellValue('d3', $contractno)->setCellValue('e3', 'TEAM')->setCellValue('f3', $teamname)
        ->setCellValue('A4', 'DATE') ->setCellValue('B4', $reportdate) ->setCellValue('C4','WEATHER')->setCellValue('D4',$weathertime)
        ->setCellValue('A5', 'REACH SITE')->setCellValue('B5', $reachsite)->setCellValue('C5', 'LEAVE SITE')->setCellValue('D5', $leavesite)->setCellValue('A6', 'TYPE OF JOB')->setCellValue('B6',$jobnames)
        ->setCellValue('A9','PIPE LAID')->setCellValue('B9','ROAD')->setCellValue('D9','CONC')->setCellValue('F9','TURF')
        ->setCellValue('A10','SIZE / LENGTH')->setCellValue('B10',$roadm)->setCellValue('C10',$roadmm)->setCellValue('D10',$concm)->setCellValue('E10',$concmm)->setCellValue('F10',$turfm)->setCellValue('G10',$turfmm)
        ->setCellValue('A11','PIPE TESTING')->setCellValue('B11','START ( PRESSURE )')->setCellValue('D11','END ( PRESSURE )')->setCellValue('F11','REMARKS')
        ->setCellValue('A12',$pipetesting)->setCellValue('B12',$pressurestart)->setCellValue('D12',$pressureend)->setCellValue('F12',$teamremarks)
        ->setCellValue('A15','NAME')->setCellValue('B15','START')->setCellValue('C15','END')->setCellValue('D15','OT')->setCellValue('F15','REMARKS')
        ->setCellValue('A16',$empnames)->setCellValue('B16',$EmployeeReport[1])->setCellValue('C16',$EmployeeReport[2])->setCellValue('D16',$EmployeeReport[3])->setCellValue('F16',$EmployeeReport[4]);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D4:F4');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D5:F5');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E4:F4');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E5:F5');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B6:F6');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B9:C9');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D9:E9');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F9:G9');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B11:C11');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D11:E11');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F11:G11');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B12:C12');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D12:E12');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F12:G12');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D15:E15');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F15:G15');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D16:E16');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F16:G16');
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(35);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A3:A10')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('C3:C5')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A11:G11')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A14:G15')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B12:E12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('B11:G11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A:E')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A9:G9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A9:G9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('A1:A11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('A15:G15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('B16:E16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A3:A6')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('A3:A6')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('C3:C5')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('C3:C5')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('E3')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('A9:A11')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('A9:A11')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('B11:G11')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('B11:G11')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('A15:G15')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('A15:G15')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $maxrowNo=18;
    $rowNumber='';
    $METrowNumber='';
    $MUrowNumber='';
    $RMrowNumber='';
    $EUrowNumber='';
    $FUrowNumber='';
    $MIUrowNumber='';
//Site Visit
    $SV_ID; $SV_designation;$SV_name;$SV_start;$SV_end;$SV_remarks;
    if($SV_details!='null')
    {
        $rowNumber = $maxrowNo;
        $maxrowNo=$maxrowNo+count($SV_details)+3;
        $sitevisittable='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;">SITE VISIT</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>DESIGNATION</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>NAME</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>START</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>END</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>REMARKS</b></td></tr></th>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$rowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowNumber,'SITE VISIT');
        $rowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($rowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowNumber,'DESIGNATION');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowNumber.':C'.$rowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowNumber,'NAME');
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowNumber,'START (Time)');
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowNumber,'END (Time)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$rowNumber.':G'.$rowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowNumber,'REMARKS');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$rowNumber.':G'.$rowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$rowNumber.':G'.$rowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$rowNumber.':G'.$rowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $rowNumber++;
        for($i=0;$i<count($SV_details);$i++)
        {
            $sitevisittable=$sitevisittable."<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$SV_details[$i][1]."</td><td nowrap style='border: 1px solid black;'>".$SV_details[$i][2]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$SV_details[$i][3]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$SV_details[$i][4]."</td><td nowrap style='border: 1px solid black;'>".$SV_details[$i][5]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('C'.$rowNumber.':E'.$rowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowNumber,$SV_details[$i][1]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowNumber.':C'.$rowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowNumber,$SV_details[$i][2]);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowNumber,$SV_details[$i][3]);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowNumber,$SV_details[$i][4]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$rowNumber.':G'.$rowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowNumber,$SV_details[$i][5]);
            if($i==0)
            {
                $SV_ID=$SV_details[$i][0];$SV_designation=$SV_details[$i][1]; $SV_name=$SV_details[$i][2]; $SV_start=$SV_details[$i][3];$SV_end=$SV_details[$i][4];$SV_remarks=$SV_details[$i][5];
            }
            else
            {
                if($SV_details[$i][0]=='' && $SV_details[$i][1]!='')
                {
                    $SV_ID=$SV_ID.','.$SV_details[$i][0].' ';
                }
                else
                {
                    $SV_ID=$SV_ID.','.$SV_details[$i][0];
                }
                $SV_designation=$SV_designation.'^'.$SV_details[$i][1]; $SV_name=$SV_name.'^'.$SV_details[$i][2]; $SV_start=$SV_start.'^'.$SV_details[$i][3]; $SV_end=$SV_end.'^'.$SV_details[$i][4]; $SV_remarks=$SV_remarks.'^'.$SV_details[$i][5];
            }
            $rowNumber++;
        }
        $sitevisittable=$sitevisittable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$sitevisittable.'</td></tr>';
    }
//Mechinery/Equipment Transfer
    $ME_id;$mech_from;$mech_item;$mech_to;$mech_remark;
    if($mech_eqp_transfer!='null')
    {
        $METrowNumber=$maxrowNo;
        $maxrowNo=$maxrowNo+count($mech_eqp_transfer)+3;
        $machineryequipmenttable='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;">MACHINERY/EQUIPMENT TRANSFER</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>FROM(LORRY NO)</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>ITEM</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>TO(LORRY NO)</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>REMARKS</b></td></tr></th>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$METrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$METrowNumber,'MACHINERY / EQUIPMENT TRANSFER');
        $METrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($METrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$METrowNumber,'FROM (LORRY NO)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$METrowNumber.':C'.$METrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$METrowNumber,'ITEM');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$METrowNumber.':E'.$METrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$METrowNumber,'TO (LORRY NO)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$METrowNumber.':G'.$METrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$METrowNumber,'REMARKS');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$METrowNumber.':G'.$METrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$METrowNumber.':G'.$METrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$METrowNumber.':G'.$METrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $METrowNumber++;
        for($i=0;$i<count($mech_eqp_transfer);$i++)
        {
            $machineryequipmenttable=$machineryequipmenttable."<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$mech_eqp_transfer[$i][1]."</td><td nowrap style='border: 1px solid black;'>".$mech_eqp_transfer[$i][2]."</td><td nowrap style='border: 1px solid black;'>".$mech_eqp_transfer[$i][3]."</td><td nowrap style='border: 1px solid black;'>".$mech_eqp_transfer[$i][4]."</td></tr>";
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$METrowNumber,$mech_eqp_transfer[$i][1]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$METrowNumber.':C'.$METrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$METrowNumber,$mech_eqp_transfer[$i][2]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$METrowNumber.':E'.$METrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$METrowNumber,$mech_eqp_transfer[$i][3]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$METrowNumber.':G'.$METrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$METrowNumber,$mech_eqp_transfer[$i][4]);
            if($i==0)
            {
                $ME_id=$mech_eqp_transfer[$i][0];$mech_from=$mech_eqp_transfer[$i][1]; $mech_item=$mech_eqp_transfer[$i][2]; $mech_to=$mech_eqp_transfer[$i][3];$mech_remark=$mech_eqp_transfer[$i][4];
            }
            else
            {
                if($mech_eqp_transfer[$i][0]=='' && $mech_eqp_transfer[$i][1]!=1)
                {
                    $ME_id=$ME_id.','.$mech_eqp_transfer[$i][0].' ';
                }
                else
                {
                    $ME_id=$ME_id.','.$mech_eqp_transfer[$i][0];
                }
                $mech_from=$mech_from.'^'.$mech_eqp_transfer[$i][1]; $mech_item=$mech_item.'^'.$mech_eqp_transfer[$i][2]; $mech_to=$mech_to.'^'.$mech_eqp_transfer[$i][3]; $mech_remark=$mech_remark.'^'.$mech_eqp_transfer[$i][4];
            }
            $METrowNumber++;
        }
        $machineryequipmenttable=$machineryequipmenttable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$machineryequipmenttable.'</td></tr>';
    }
//Mechinery Usage
    $mechinery_id;$mechinerytype;$mechinerystart;$mechineryend;$mechineryremark;
    if($mechinery!='null')
    {
        $MUrowNumber=$maxrowNo;
        $maxrowNo=$maxrowNo+count($mechinery)+3;
        $machineryusagetable='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;">MACHINERY USAGE</caption>  <sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>MACHINERY TYPE</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>START</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>END</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>REMARKS</b></td></tr></th>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$MUrowNumber,'MACHINERY USAGE');
        $MUrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($MUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$MUrowNumber,'MACHINERY TYPE');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$MUrowNumber.':C'.$MUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$MUrowNumber,'START (Time)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$MUrowNumber.':E'.$MUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$MUrowNumber,'END (Time)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$MUrowNumber.':G'.$MUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$MUrowNumber,'REMARKS');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MUrowNumber.':G'.$MUrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MUrowNumber.':G'.$MUrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MUrowNumber.':G'.$MUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $MUrowNumber++;
        for($i=0;$i<count($mechinery);$i++)
        {
            $machineryusagetable=$machineryusagetable."<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$mechinery[$i][1]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$mechinery[$i][2]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$mechinery[$i][3]."</td><td nowrap style='border: 1px solid black;'>".$mechinery[$i][4]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('B'.$MUrowNumber.':E'.$MUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$MUrowNumber,$mechinery[$i][1]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$MUrowNumber.':C'.$MUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$MUrowNumber,$mechinery[$i][2]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$MUrowNumber.':E'.$MUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$MUrowNumber,$mechinery[$i][3]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$MUrowNumber.':G'.$MUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$MUrowNumber,$mechinery[$i][4]);
            if($i==0)
            {
                $mechinery_id=$mechinery[$i][0];$mechinerytype=$mechinery[$i][1]; $mechinerystart=$mechinery[$i][2]; $mechineryend=$mechinery[$i][3];$mechineryremark=$mechinery[$i][4];
            }
            else
            {
                if($mechinery[$i][0]=='' && $mechinery[$i][1]!='')
                {
                    $mechinery_id=$mechinery_id.','.$mechinery[$i][0].' ';
                }
                else
                {
                    $mechinery_id=$mechinery_id.','.$mechinery[$i][0];
                }
                $mechinerytype=$mechinerytype.'^'.$mechinery[$i][1]; $mechinerystart=$mechinerystart.'^'.$mechinery[$i][2]; $mechineryend=$mechineryend.'^'.$mechinery[$i][3]; $mechineryremark=$mechineryremark.'^'.$mechinery[$i][4];
            }
            $MUrowNumber++;
        }
        $machineryusagetable=$machineryusagetable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$machineryusagetable.'</td></tr>';
    }
//Rental Mechinery
    $rental_id;$rental_lorryno;$rental_store;$rental_outside;$rental_start;$rental_end;$rental_remark;
    if($rental!='null')
    {
        $RMrowNumber=$maxrowNo;
        $maxrowNo=$maxrowNo+count($rental)+3;
        $rentaltable='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;">RENTAL MACHINERY</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>LORRY NUMBER</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>THROW EARTH(STORE)</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>THROW EARTH(OUTSIDE)</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>START</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>END</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>REMARKS</b></td></tr></th>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$RMrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$RMrowNumber,'RENTAL MACHINERY');
        $RMrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($RMrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$RMrowNumber,'LORRY NUMBER');
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$RMrowNumber,'THROW EARTH(STORE)');
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$RMrowNumber,'THROW EARTH(OUTSIDE)');
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$RMrowNumber,'START (Time)');
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$RMrowNumber,'END (Time)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$RMrowNumber.':G'.$RMrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$RMrowNumber,'REMARKS');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$RMrowNumber.':G'.$RMrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$RMrowNumber.':G'.$RMrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$RMrowNumber.':G'.$RMrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $RMrowNumber++;
        for($i=0;$i<count($rental);$i++)
        {
            $rentaltable=$rentaltable."<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$rental[$i][1]."</td><td nowrap style='border: 1px solid black;'>".$rental[$i][2]."</td><td nowrap style='border: 1px solid black;'>".$rental[$i][3]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$rental[$i][4]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$rental[$i][5]."</td><td nowrap style='border: 1px solid black;'>".$rental[$i][6]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('B'.$RMrowNumber.':E'.$RMrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$RMrowNumber,$rental[$i][1]);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$RMrowNumber,$rental[$i][2]);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$RMrowNumber,$rental[$i][3]);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$RMrowNumber,$rental[$i][4]);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$RMrowNumber,$rental[$i][5]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$RMrowNumber.':G'.$RMrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$RMrowNumber,$rental[$i][6]);
            if($i==0)
            {
                $rental_id=$rental[$i][0];$rental_lorryno=$rental[$i][1]; $rental_store=$rental[$i][2]; $rental_outside=$rental[$i][3];$rental_start=$rental[$i][4];$rental_end=$rental[$i][5];$rental_remark=$rental[$i][6];
            }
            else
            {
                if($rental[$i][0]=='' && $rental[$i][1]!='')
                {
                    $rental_id=$rental_id.','.$rental[$i][0].' ';
                }
                else
                {
                    $rental_id=$rental_id.','.$rental[$i][0];
                }
                $rental_lorryno=$rental_lorryno.'^'.$rental[$i][1]; $rental_store=$rental_store.'^'.$rental[$i][2]; $rental_outside=$rental_outside.'^'.$rental[$i][3]; $rental_start=$rental_start.'^'.$rental[$i][4]; $rental_end=$rental_end.'^'.$rental[$i][5]; $rental_remark=$rental_remark.'^'.$rental[$i][6];
            }
            $RMrowNumber++;
        }
        $rentaltable=$rentaltable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$rentaltable.'</td></tr>';
    }
//Equipment Usage
    $equip_id;$equipmentcompressor;$equipmentlorryno;$equipmentstart;$equipmentend;$equipmentremark;
    if($equipment!='null')
    {
        $EUrowNumber=$maxrowNo;
        $maxrowNo=$maxrowNo+count($equipment)+3;
        $equipmenttable='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;">EQUIPMENT USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>AIR-COMPRESSOR</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>LORRYNO(TRANSPORT)</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>START</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>END</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>REMARKS</b></td></tr></th>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$EUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$EUrowNumber,'EQUIPMENT USAGE');
        $EUrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($EUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$EUrowNumber,'AIR-COMPRESSOR');
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$EUrowNumber,'LORRY NO(TRANSPORT)');
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$EUrowNumber,'START (Time)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$EUrowNumber.':E'.$EUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$EUrowNumber,'END (Time)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$EUrowNumber.':G'.$EUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$EUrowNumber,'REMARKS');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$EUrowNumber.':G'.$EUrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$EUrowNumber.':G'.$EUrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$EUrowNumber.':G'.$EUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $EUrowNumber++;
        for($i=0;$i<count($equipment);$i++)
        {
            $equipmenttable=$equipmenttable."<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$equipment[$i][1]."</td><td nowrap style='border: 1px solid black;'>".$equipment[$i][2]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$equipment[$i][3]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$equipment[$i][4]."</td><td nowrap style='border: 1px solid black;'>".$equipment[$i][5]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('C'.$EUrowNumber.':E'.$EUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$EUrowNumber,$equipment[$i][1]);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$EUrowNumber,$equipment[$i][2]);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$EUrowNumber,$equipment[$i][3]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$EUrowNumber.':E'.$EUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$EUrowNumber,$equipment[$i][4]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$EUrowNumber.':G'.$EUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$EUrowNumber,$equipment[$i][5]);
            if($i==0)
            {
                $equip_id=$equipment[$i][0];$equipmentcompressor=$equipment[$i][1]; $equipmentlorryno=$equipment[$i][2]; $equipmentstart=$equipment[$i][3];$equipmentend=$equipment[$i][4];$equipmentremark=$equipment[$i][5];
            }
            else
            {
                if($equipment[$i][0]=='' && $equipment[$i][1]!='')
                {
                    $equip_id=$equip_id.','.$equipment[$i][0].' ';
                }
                else
                {
                    $equip_id=$equip_id.','.$equipment[$i][0];
                }
                $equipmentcompressor=$equipmentcompressor.'^'.$equipment[$i][1]; $equipmentlorryno=$equipmentlorryno.'^'.$equipment[$i][2]; $equipmentstart=$equipmentstart.'^'.$equipment[$i][3]; $equipmentend=$equipmentend.'^'.$equipment[$i][4]; $equipmentremark=$equipmentremark.'^'.$equipment[$i][5];
            }
            $EUrowNumber++;
        }
        $equipmenttable=$equipmenttable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$equipmenttable.'</td></tr>';
    }
//Fitting  Usage
    $fitting_id;$fittingitems;$fittingsize;$fittingqty;$fittingremark;
    if($fitting!='null')
    {
        $FUrowNumber=$maxrowNo;
        $maxrowNo=$maxrowNo+count($fitting)+3;
        $fittingtable='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;">FITTING USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>ITEMS</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>SIZE</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>QUANTITY</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>REMARKS</b></td></tr></th>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$FUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$FUrowNumber,'FITTINGS USAGE');
        $FUrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($FUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$FUrowNumber,'ITEMS');
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$FUrowNumber,'SIZE');
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$FUrowNumber,'QUANTITY');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$FUrowNumber.':G'.$FUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$FUrowNumber,'REMARKS');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$FUrowNumber.':G'.$FUrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$FUrowNumber.':G'.$FUrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$FUrowNumber.':G'.$FUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $FUrowNumber++;
        for($i=0;$i<count($fitting);$i++)
        {
            $fittingtable=$fittingtable."<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$fitting[$i][1]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$fitting[$i][2]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$fitting[$i][3]."</td><td nowrap style='border: 1px solid black;'>".$fitting[$i][4]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('B'.$FUrowNumber.':C'.$FUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$FUrowNumber,$fitting[$i][1]);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$FUrowNumber,$fitting[$i][2]);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$FUrowNumber,$fitting[$i][3]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$FUrowNumber.':G'.$FUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$FUrowNumber,$fitting[$i][4]);
            if($i==0)
            {
                $fitting_id=$fitting[$i][0];$fittingitems=$fitting[$i][1]; $fittingsize=$fitting[$i][2]; $fittingqty=$fitting[$i][3];$fittingremark=$fitting[$i][4];
            }
            else
            {
                if($fitting[$i][0]=='' && $fitting[$i][1]!='')
                {
                    $fitting_id=$fitting_id.','.$fitting[$i][0].' ';
                }
                else
                {
                    $fitting_id=$fitting_id.','.$fitting[$i][0];
                }
                $fittingitems=$fittingitems.'^'.$fitting[$i][1]; $fittingsize=$fittingsize.'^'.$fitting[$i][2]; $fittingqty=$fittingqty.'^'.$fitting[$i][3]; $fittingremark=$fittingremark.'^'.$fitting[$i][4];
            }
            $FUrowNumber++;
        }
        $fittingtable=$fittingtable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$fittingtable.'</td></tr>';
    }
//Material Usage //
    $mat_id;$materialitems;$materialreceipt;$materialqty;
    if($material!='null')
    {
        $MIUrowNumber=$maxrowNo;
        $materialusagetable='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;">MATERIAL USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>ITEMS</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>RECEIPT NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>QUANTITY</b></td></tr></th>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MIUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$MIUrowNumber,'MATERIAL USAGE');
        $MIUrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($MIUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$MIUrowNumber,'ITEMS');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$MIUrowNumber.':C'.$MIUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$MIUrowNumber,'RECEIPT NO');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$MIUrowNumber.':G'.$MIUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$MIUrowNumber,'Qty (KG/BAGS/LTR/PCS)');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MIUrowNumber.':G'.$MIUrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MIUrowNumber.':G'.$MIUrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MIUrowNumber.':G'.$MIUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $MIUrowNumber++;
        for($i=0;$i<count($material);$i++)
        {
            $materialusagetable=$materialusagetable."<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$material[$i][1]."</td><td nowrap style='border: 1px solid black;'>".$material[$i][2]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$material[$i][3]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('D'.$MIUrowNumber.':G'.$MIUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$MIUrowNumber,$material[$i][1]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$MIUrowNumber.':C'.$MIUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$MIUrowNumber,$material[$i][2]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$MIUrowNumber.':G'.$MIUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$MIUrowNumber,$material[$i][3]);
            if($i==0)
            {
                $mat_id=$material[$i][0];$materialitems=$material[$i][1]; $materialreceipt=$material[$i][2];$materialqty=$material[$i][3];
            }
            else
            {
                if($material[$i][0]=='' && $material[$i][1]!='')
                {
                    $mat_id=$mat_id.','.$material[$i][0].' ';
                }
                else
                {
                    $mat_id=$mat_id.','.$material[$i][0];
                }
                $materialitems=$materialitems.'^'.$material[$i][1]; $materialreceipt=$materialreceipt.'^'.$material[$i][2];$materialqty=$materialqty.'^'.$material[$i][3];
            }
            $MIUrowNumber++;
        }
        $materialusagetable=$materialusagetable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$materialusagetable.'</td></tr>';
    }
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="simple.xls"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    // get the exl content
    @ob_start();
    $objWriter =  PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
    $objWriter->save('php://output');
    $update_exldata = ob_get_contents();
    ob_end_clean();

    $finaltable=$finaltable.'<br><br><tr><td>REPORT IMAGE<br><br><img id=image src="'.$uploadpath.'"/></td></tr></table></body></html>';
//final table end
    //update part
    if($imgflag==1){
    $callquery="CALL SP_LMC_REPORT_ENTRY_UPDATE_DELETE(2,'$teamname','$EmployeeReport[0]','$reportdate','$teamlocation',$contractno,'$reachsite','$leavesite','$typeofjob','$weather','$weatherfrom','$weatherto','$pipetesting','$pressurestart','$pressureend','$teamremarks','$filename','$imgfilename',
        '$pipelaid','$size','$length',
        '$EmployeeReport[1]','$EmployeeReport[2]','$EmployeeReport[3]','$EmployeeReport[4]',
        '$SV_ID','$SV_designation','$SV_name','$SV_start','$SV_end','$SV_remarks',
        '$ME_id','$mech_from','$mech_to','$mech_item','$mech_remark',
        '$mechinery_id','$mechinerytype','$mechinerystart','$mechineryend','$mechineryremark',
        '$fitting_id','$fittingitems','$fittingsize','$fittingqty','$fittingremark',
        '$mat_id','$materialitems','$materialreceipt','$materialqty',
        '$rental_id','$rental_lorryno','$rental_store', '$rental_outside','$rental_start','$rental_end','$rental_remark',
        '$equip_id','$equipmentcompressor','$equipmentlorryno','$equipmentstart','$equipmentend','$equipmentremark','$UserStamp',@SUCCESS_MESSAGE)";
        $result = $con->query($callquery);
        if(!$result){
            unlink($uploadpath);
            die("CALL failed: (" . $con->errno . ") " . $con->error);
        }
        $select = $con->query('SELECT @SUCCESS_MESSAGE');
        $result = $select->fetch_assoc();
        $flag= $result['@SUCCESS_MESSAGE'];
        if($flag!=1 && $uploadpath!='')
        {
            unlink($uploadpath);
        }
    }

    if($flag==1 && ($old_imgfileid!='' || $old_imgfileid!=null))
    {
        $deltpath = $dir.$parentfolder.DIRECTORY_SEPARATOR.$userfolderid.DIRECTORY_SEPARATOR.$old_imgfileid;
        unlink($deltpath);
    }
    if($flag==1)
    {
        $select_to=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=12");
        if($row=mysqli_fetch_array($select_to)){
            $toaddress=$row["URC_DATA"];
        }

        $select_cc=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=13");
        if($row=mysqli_fetch_array($select_cc)){
            $ccaddress=$row["URC_DATA"];
        }
        $select_host=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=17");
        if($row=mysqli_fetch_array($select_host)){
            $host=$row["URC_DATA"];
        }
        $select_username=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=14");
        if($row=mysqli_fetch_array($select_username)){
            $username=$row["URC_DATA"];
        }
        $select_password=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=15");
        if($row=mysqli_fetch_array($select_password)){
            $password=$row["URC_DATA"];
        }

        $select_smtpsecure=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=16");
        if($row=mysqli_fetch_array($select_smtpsecure)){
            $smtpsecure=$row["URC_DATA"];
        }

        $select_from=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=18");
        if($row=mysqli_fetch_array($select_from)){
            $from=$row["URC_DATA"];
        }
        $select_emailtemp=mysqli_query($con,"SELECT ETD_EMAIL_SUBJECT, ETD_EMAIL_BODY FROM LMC_EMAIL_TEMPLATE_DETAILS where ETD_ID=9");
        if($row=mysqli_fetch_array($select_emailtemp)){
            $sub=$row["ETD_EMAIL_SUBJECT"];
            $msgbody=$row["ETD_EMAIL_BODY"];
        }
        $emailbody = str_replace("[LOGINID]",$empnames, $msgbody);
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = $smtpsecure;
        $mail->From = $from;
        $mail->FromName = 'LMC';
        $mail->addAddress($toaddress);
        $mail->WordWrap = 50;
        $mail->isHTML(true);
        $mail->Subject =$sub;
        $mail->Body =$emailbody;
        // pdf  attachment
        $reportfilename='TIME SHEET UPDATED REPORT FOR '.$empnames.'.pdf';
        $mpdf=new mPDF('utf-8','A4');
        $mpdf->debug=true;
        $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">LIH MING CONSTRUCTION PTE LTD</div></h3>', 'O', true);
        $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
        $mpdf->WriteHTML($finaltable);
        $reportpdf=$mpdf->Output('foo.pdf','S');
        $mail->AddStringAttachment($reportpdf,$reportfilename);
        // excel attachment
        $xlreportfilename='TIME SHEET UPDATED REPORT FOR '.$empnames.'.xls';
        $mail->AddStringAttachment($update_exldata,$xlreportfilename);
        $mail->Send();
    }
    echo $flag;
}
elseif($_REQUEST['option']=='tempfilname')
{
    $uploadcount=$_REQUEST['ENT_upload_count'];
    $reportdate=$_POST['tr_txt_date'];
    $Employeeid=$_REQUEST['Employeeid'];
    $repdate=str_replace('-','',$reportdate);
    $upload_file_array=array();
    $userfolderid=get_emp_folderid($Employeeid);
    $uploadpath=$dir.$parentfolder.DIRECTORY_SEPARATOR.$userfolderid.DIRECTORY_SEPARATOR;
    for($x=0;$x<$uploadcount;$x++)
    {
        if($_FILES['ENT_upload_filename'.$x]['name']!=''){
            $attach_file_name=$Employeeid.'_'.$repdate.'_'.date('His').'_'.$_FILES['ENT_upload_filename'.$x]['name'];
            move_uploaded_file($_FILES['ENT_upload_filename'.$x]['tmp_name'],$uploadpath.$attach_file_name);
            $upload_file_array[]=$attach_file_name;//$_FILES['upload_filename'.$x]['name'];
        }
    }
    $upload_filename='';
    for($y=0;$y<=count($upload_file_array);$y++){
        if($upload_file_array[$y]!=''){
            if($y==0){
                $upload_filename= $upload_file_array[$y];
            }
            else{
                $upload_filename=$upload_filename.'/'.$upload_file_array[$y];
            }
        }
    }
    echo $upload_filename;
}
elseif($_REQUEST['option']=='updatetempfilname')
{
    $uploadcount=$_REQUEST['SRC_upload_count'];
    $date=$_POST['SRC_tr_txt_date'];
    $Employeeid=$_REQUEST['Employeeid'];
    $currentdate=str_replace('-','',$date);
    $upload_file_array=array();
    $userfolderid=get_emp_folderid($Employeeid);
    $uploadpath=$dir.$parentfolder.DIRECTORY_SEPARATOR.$userfolderid.DIRECTORY_SEPARATOR;
    for($x=0;$x<$uploadcount;$x++)
    {
        if($_FILES['SRC_upload_filename'.$x]['name']!=''){
            $attach_file_name=$Employeeid.'_'.$currentdate.'_'.date('His').'_'.$_FILES['SRC_upload_filename'.$x]['name'];
            move_uploaded_file($_FILES['SRC_upload_filename'.$x]['tmp_name'],$uploadpath.$attach_file_name);
            $upload_file_array[]=$attach_file_name;//$_FILES['upload_filename'.$x]['name'];
        }
    }
    $upload_filename='';
    for($y=0;$y<=count($upload_file_array);$y++){
        if($upload_file_array[$y]!=''){
            if($y==0){
                $upload_filename= $upload_file_array[$y];
            }
            else{
                $upload_filename=$upload_filename.'/'.$upload_file_array[$y];
            }
        }
    }
    echo $upload_filename;
}
?>