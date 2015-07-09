<?php
error_reporting(0);
set_time_limit(0);
include "../LMC_LIB/CONNECTION.php";
include "../LMC_LIB/GET_USERSTAMP.php";
include "../LMC_LIB/COMMON.php";
require_once('../mpdf571/mpdf571/mpdf.php');
require_once("../PHPMailer/class.phpmailer.php");
require_once("../PHPMailer/class.smtp.php");
require_once('../PHPExcel/Classes/PHPExcel.php');
$dir=dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR;
date_default_timezone_set('Asia/Singapore');
$parentfolder=get_parentfolder_id();
chmod($parentfolder,0777);
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
// Set document propertiesz
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
    ->setLastModifiedBy("Maarten Balliauw")
    ->setTitle("Office 2007 XLSX Test Document")
    ->setSubject("Office 2007 XLSX Test Document")
    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Test result file");
// for exl logo
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('PHPExcel logo');
$objDrawing->setDescription('PHPExcel logo');
$objDrawing->setPath('../image/LOGO.png');
$objDrawing->setWidthAndHeight(200,100);
$objDrawing->setResizeProportional(true);
$objDrawing->setCoordinates('E1');
$objDrawing->setOffsetX(6);
$objDrawing->setOffsetY(8);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
/** Create a new PHPExcel object 1.0 */
$activeempname=mysqli_query($con,"SELECT ULD_ID,ULD_WORKER_NAME FROM LMC_USER_LOGIN_DETAILS  WHERE ULD_USERNAME='$UserStamp'");
if($row=mysqli_fetch_array($activeempname))
{
    $activeemp=$row["ULD_ID"];
}
// COMMOM DATA
if($_REQUEST['option']=='COMMON_DATA')
{
//MEETING TOPIC
    $topic=mysqli_query($con,"SELECT MT_TOPIC FROM LMC_MEETING_TOPIC ORDER BY MT_TOPIC ASC");
    while($row=mysqli_fetch_array($topic)){
        $topicname[]=$row["MT_TOPIC"];
    }
//TEAM CREATION
    $team=mysqli_query($con,"SELECT TC.TEAM_NAME FROM LMC_EMPLOYEE_TEAM_DETAILS ED JOIN LMC_TEAM_CREATION TC WHERE TC.TC_ID = ED.TC_ID AND ED.ULD_ID=$activeemp");
    if($row=mysqli_fetch_array($team)){
        $teamname=$row["TEAM_NAME"];
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
    $empname=mysqli_query($con,"SELECT ULD_ID,ULD_WORKER_NAME FROM LMC_USER_LOGIN_DETAILS ORDER BY ULD_WORKER_NAME ASC ");
    while($row=mysqli_fetch_array($empname)){
        $employeename[]=array($row["ULD_WORKER_NAME"],$row['ULD_ID']);
    }
    //MACHINERY EQUIPEMENT/TRANSFER ITEM
    $mtitem=mysqli_query($con,"select MI_ITEM from LMC_MACHINERY_ITEM ORDER BY MI_ITEM ASC");
    while($row=mysqli_fetch_array($mtitem)){
        $mtransferitem[]=$row["MI_ITEM"];
    }
    // CONTRACT NOs
    $contract_no=mysqli_query($con,"SELECT CLD_ID,CLD_CONTRACT_NO FROM LMC_CONTRACT_DETAILS WHERE LCS_ID = 1 AND TC_ID=(SELECT TC_ID FROM LMC_TEAM_CREATION WHERE TEAM_NAME='$teamname')");
    while($row=mysqli_fetch_array($contract_no)){
        $contractnos[]=array('id'=>$row["CLD_ID"],'no'=>$row["CLD_CONTRACT_NO"]);
    }
    //ERRPOR MESSAGE
    $errormsg=get_error_msg('3,6,7,21,143,144,145,147,148,151,152,156,157,166');
    $values=array($teamname,$machinerytype,$fittingitems,$materialitem,$joptype,$errormsg,$employeename,$topicname,$UserStamp,$mtransferitem,$contractnos);
    echo json_encode($values);
}
if($_REQUEST['option']=="get_itemnos"){
    $contct_no=$_REQUEST['contct_no'];
    // ITEM CODE NAME
    $itemno=array();
    $itemdtl=mysqli_query($con,"SELECT LID_ID,LID_ITEM_NO,LID_DESCRIPTION FROM LMC_INVENTORY_ITEM_DETAILS IID,LMC_CONTRACT_DETAILS LCD WHERE IID.CLD_ID=LCD.CLD_ID AND IID.CLD_ID=$contct_no ORDER BY LID_ITEM_NO");
    while($row=mysqli_fetch_array($itemdtl)){
        $itemno[]=array('id'=>$row["LID_ID"],'no'=>$row["LID_ITEM_NO"],'name'=>$row["LID_DESCRIPTION"]);
    }
    echo json_encode($itemno);
}
if($_REQUEST['option']=="checktopic"){
    $topicname=$_GET['topic_name'];
    $sql="SELECT * FROM LMC_MEETING_TOPIC where MT_TOPIC='$topicname'";
    $sql_result= mysqli_query($con,$sql);
    $row=mysqli_num_rows($sql_result);
    $x=$row;
    if($x > 0)
    {
        $topic_flag=1;
    }
    else{
        $topic_flag=0;
    }
    echo ($topic_flag);
}
// REPORT SUBMISSION ENTRY FORM
elseif($_REQUEST['Option']=='InputForm')
{
    //TEAM REPORT ELEMENTS
    $teamlocation=$_POST["tr_txt_location"];
    $contractid=$_POST["tr_lb_contractno"];
    $teamname=$_POST['tr_tb_team'];
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
    // CONTRACT NOs
    $contract_no=mysqli_query($con,"SELECT CLD_CONTRACT_NO FROM LMC_CONTRACT_DETAILS WHERE CLD_ID = $contractid");
    while($row=mysqli_fetch_array($contract_no)){
        $contractno= $row["CLD_CONTRACT_NO"];
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
    $meeting=$_POST['MeetingDetails'];
    $stockusage=$_POST['StockDetails'];
    $material=$_POST["MaterialDetails"];
    $fitting=$_POST["FittingDetails"];
    $equipment=$_POST["EquipmentDetails"];
    $rental=$_POST["RentalDetails"];
    $mechinery=$_POST["MechineryUsageDetails"];
    $mech_eqp_transfer=$_POST["MechEqptransfer"];
    $SV_details=$_POST["SiteVisit"];
    $EmployeeReport=$_POST["EmployeeDetails"];
    $imagedata=$_POST['imgData'];
    $uploadpath;
    if($imagedata!='' && $reportdate!='' && $EmployeeReport[0] && $teamname!=''){
        $daterep=str_replace('-','',$reportdate);
//        $imgfilename=$EmployeeReport[0].'_'.$daterep.'_'.date('His').'.png';
        $imgfilename=$EmployeeReport[0].'_'.$daterep.'_'.date('His').'.txt';
        $userfolderid=get_emp_folderid($EmployeeReport[0]);
        chmod($userfolderid,0777);
        $path=$dir.$parentfolder.DIRECTORY_SEPARATOR.$userfolderid.DIRECTORY_SEPARATOR;
        if ( ! is_writable($path))
        {
            $writable=0;
        } else {

            $writable=1;
        }
        if(is_dir($path)){
            $dirflag=1;
        }
        else{
            $dirflag=0;
        }
        if($dirflag==1 && $writable==1){
            $uploadpath=$path.$imgfilename;
            try{
//                $data=str_replace('data:image/png;base64,','',$imagedata);
//                $data = str_replace(' ','+',$data);
//                $data = base64_decode($data);
                $success = file_put_contents($uploadpath, $imagedata);
                $imgflag=1;
            }
            catch(Exception $e){
                $imgflag=0;
                unlink($uploadpath);
                print $e->getMessage();
            }
        }
        else{
            $imgflag=0;
            $imgfilename='';
        }
    }
    elseif($imagedata==''){
        $imgflag=1;
        $imgfilename='';
    }
    if($weather!=''){
        $weathertime=$weather.' ('.$weatherfrom.' TO '.$weatherto.')';
    }
    else{
        $weathertime='';
    }
//TEAM REPORT DETAILS
    $jobname=mysqli_query($con,"SELECT GROUP_CONCAT(TOJ_JOB SEPARATOR ' / ') AS JOB FROM LMC_TYPE_OF_JOB WHERE TOJ_ID IN($typeofjob)");
    while($row=mysqli_fetch_array($jobname)){
        $jobnames=$row["JOB"];
    }
//TEAM REPORT
    $teamreporttable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption style="caption-side: left;font-weight: bold;">TEAM REPORT</caption>
   <tr><td width="100" style= "padding-left: 10px;color:#fff; background-color:#498af3;font-weight: bold;" height=20px>LOCATION</td><td width="360" style="padding-left: 10px;">'.$teamlocation.'</td><td width="140" style="color:#fff; background-color:#498af3;padding-left: 10px;font-weight: bold;" height=20px>CONTRACT NO</td><td  width="250" style="padding-left: 10px;">'.$contractno.'</td><td width="150" style="padding-left: 10px;">'.$teamname.'</td></tr></table>
   <table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><tr><td width="250" style="padding-left: 10px;color:#fff; background-color:#498af3;font-weight: bold;" height=20px>DATE</td><td style="padding-left: 10px;"width="250">'.$reportdate.'</td><td width="250" style="color:#fff; background-color:#498af3;padding-left: 10px;font-weight: bold;" height=20px>WEATHER</td><td style="padding-left: 10px;" width="250">'.$weathertime.'</td></tr>
   <tr><td width="250" style="color:#fff; background-color:#498af3; padding-left: 10px;font-weight: bold;" height=20px>REACH SITE</td><td style="padding-left: 10px;" width="250">'.$reachsite.'</td><td width="250" style="color:#fff; background-color:#498af3; padding-left: 10px;font-weight: bold;" height=20px>LEAVE SITE</td><td style="padding-left: 10px;" width="250">'.$leavesite.'</td></tr>
   <tr><td width="250" colspan="1" style="color:#498af3;padding-left: 10px;color:#fff; background-color:#498af3;font-weight: bold;" height=20px>TYPE OF JOB</td><td style="padding-left: 10px;" width="250" colspan="3">'.$jobnames.'</td></tr>
   </table>';
// final table start
    $reportheadername='TIME SHEET REPORT FOR '.$EmployeeReport[5].' ON '.date('d-m-Y',strtotime($reportdate));
    $finaltable= '<html><body><table><tr><td style="text-align: center;"><div><img id=imaglogo src="../image/LOGO.png"/></div></td></tr><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">' .$reportheadername.'</div></h2></td></tr><br><tr><td>'.$teamreporttable.'</td></tr>';

// FOR EXCEL
    $sheettitle="TIME SHEET REPORT FOR ".$EmployeeReport[5].' ON '.date('d-m-Y',strtotime($reportdate));
    $objPHPExcel->getActiveSheet()->setTitle('REPORT SUBMISSION ENTRY')->setCellValue('A1', $sheettitle)->setCellValue('A2', 'TEAM REPORT')
        ->setCellValue('A3', 'LOCATION')->setCellValue('B3',$teamlocation)->setCellValue('C3', 'CONTRACT NO')->setCellValue('D3', $contractno)->setCellValue('F3', 'TEAM')->setCellValue('G3', $teamname)
        ->setCellValue('A4', 'DATE') ->setCellValue('B4', $reportdate) ->setCellValue('C4','WEATHER')->setCellValue('D4',$weathertime)
        ->setCellValue('A5', 'REACH SITE')->setCellValue('B5', $reachsite)->setCellValue('C5', 'LEAVE SITE')->setCellValue('D5', $leavesite)->setCellValue('A6', 'TYPE OF JOB')->setCellValue('B6',$jobnames);
    $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
    $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('A3:G6')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
    $objPHPExcel->getActiveSheet()->freezePane('A2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D3:E3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D4:G4');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D5:G5');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B6:G6');
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A3:A6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('C3:C5')->getFont()->setBold(true);
//    $objPHPExcel->getActiveSheet()->getStyle('A:E')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('A2:A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('A3:A6')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('A3:A6')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('C3:C5')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('C3:C5')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('F3')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $maxrowNo=8;
    $MSrowNumber='';
    $JErowNumber='';
    $SVrowNumber='';
    $METrowNumber='';
    $MUrowNumber='';
    $RMrowNumber='';
    $EUrowNumber='';
    $FUrowNumber='';
    $MIUrowNumber='';
    $SSUrowNumber='';
//MEETING SECTION
    $MS_topic;$MS_remarks;
    if($meeting!='null')
    {
        $MSrowNumber = $maxrowNo;
        $maxrowNo=$maxrowNo+count($meeting)+3;
        $meetingtable='<br><table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">MEETING</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3"  align="center" ><td height=20px width="400" align="center" style="color:white;" nowrap><b>TOPIC</b></td><td height=20px align="center" style="color:white;" nowrap><b>REMARKS</b></td></tr>';

        $objPHPExcel->getActiveSheet()->getStyle('A'.$MSrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MSrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$MSrowNumber,'MEETING');
        $MSrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($MSrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MSrowNumber.':G'.$MSrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$MSrowNumber.':B'.$MSrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$MSrowNumber,'TOPIC');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C'.$MSrowNumber.':G'.$MSrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$MSrowNumber,'REMARKS');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MSrowNumber.':G'.$MSrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MSrowNumber.':G'.$MSrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MSrowNumber.':G'.$MSrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $MSrowNumber++;
        for($i=0;$i<count($meeting);$i++)
        {
            $meetingtable=$meetingtable."<tr><td height=20px nowrap style='padding-left: 10px;'>".$meeting[$i][0]."</td><td height=20px nowrap style='padding-left: 10px;'>".$meeting[$i][1]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('A'.$MSrowNumber.':G'.$MSrowNumber)->applyFromArray($styleArray);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$MSrowNumber.':B'.$MSrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$MSrowNumber,$meeting[$i][0]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C'.$MSrowNumber.':G'.$MSrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$MSrowNumber,$meeting[$i][1]);
            if($i==0)
            {
                $MS_topic=$meeting[$i][0]; $MS_remarks=$meeting[$i][1];
            }
            else
            {
                $MS_topic=$MS_topic.'^'.$meeting[$i][0]; $MS_remarks=$MS_remarks.'^'.$meeting[$i][1];
            }
            $MSrowNumber++;
        }
        $meetingtable=$meetingtable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$meetingtable.'</td></tr>';
    }
//JOB DONE DETAILS
    $jobdonetable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption style="caption-side: left;font-weight: bold;">JOB DONE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/>
   <tr><td width="250" style="color:#fff; background-color:#498af3;padding-left: 10px;font-weight: bold;" height=20px>PIPELAID</td><td style="text-align:center;" width="250" colspan=2>ROAD</td><td width="250" colspan=2 style="text-align:center;">CONC</td><td width="250" colspan=2 style="text-align:center;">TRUF</td></tr>
   <tr><td style="color:#fff; background-color:#498af3;padding-left: 10px;font-weight: bold; " height=20px>SIZE/LENGTH</td><td style="padding-left: 10px;">'.$roadm.'</td><td style="padding-left: 10px;">'.$roadmm.'</td><td style="padding-left: 10px;">'.$concm.'</td><td style="padding-left: 10px;">'.$concmm.'</td><td style="padding-left: 10px;">'.$turfm.'</td><td style="padding-left: 10px;">'.$turfmm.'</td>
   <tr><td style="color:#fff; background-color:#498af3; padding-left: 10px;font-weight: bold;" height=20px>PIPE TESTING</td><td colspan="2" style="color:#fff; background-color:#498af3;font-weight: bold;text-align:center;" height=20px>START(PRESSURE)</td><td colspan="2" style="color:#fff; background-color:#498af3;font-weight: bold;text-align:center;" height=20px>END(PRESSURE)</td><td colspan="2" style="color:#fff; background-color:#498af3;font-weight: bold;text-align:center;" height=20px>REMARK</td></tr>
   <tr><td style="padding-left: 10px;">'.$pipetesting.'</td><td colspan="2" style="text-align:center;">'.$pressurestart.'</td><td style="text-align:center;"colspan="2">'.$pressureend.'</td><td colspan="2" style="padding-left: 10px;">'.$teamremarks.'</td></tr>
   </table>';

//EMPLOYEE TABLE
    $employeetable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">EMPLOYEE DETAILS</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center" height=20px><td height=20px align="center" style="color:white;" width="350" nowrap><b>EMPLOYEE NAME</b></td><td height=20px align="center" style="color:white;" width="100" nowrap><b>START</b></td><td height=20px align="center" style="color:white;" width="100"nowrap><b>END</b></td><td height=20px align="center" style="color:white;" width="100" nowrap><b>OT</b></td><td height=20px align="center" style="color:white;" width="350" nowrap><b>REMARKS</b></td></tr>';
    $employeetable=$employeetable."<tr style='padding-left: 10px;' height=20px ><td height=20px nowrap style='padding-left: 10px;'>".$EmployeeReport[5]."</td><td height=20px nowrap style='text-align:center;'>".$EmployeeReport[1]."</td><td height=20px nowrap style='text-align:center;'>".$EmployeeReport[2]."</td><td height=20px nowrap style='text-align:center;'>".$EmployeeReport[3]."</td><td height=20px nowrap style='padding-left: 10px;'>".$EmployeeReport[4]."</td></tr></table>";
    $finaltable=$finaltable.'<br><br><tr><td>'.$jobdonetable.'</td></tr><br><br><tr><td>'.$employeetable.'</td></tr>';

    $JErowNumber = $maxrowNo;
    if($maxrowNo>8){
        $maxrowNo=$maxrowNo+10;
    }
    else if($maxrowNo==8){
        $maxrowNo=18;
    }
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$JErowNumber,'JOB DONE');$JErowNumber++;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$JErowNumber.':C'.$JErowNumber);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$JErowNumber.':E'.$JErowNumber);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$JErowNumber.':G'.$JErowNumber);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('B'.$JErowNumber.':G'.$JErowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$JErowNumber,'PIPE LAID')->setCellValue('B'.$JErowNumber,'ROAD')->setCellValue('D'.$JErowNumber,'CONC')->setCellValue('F'.$JErowNumber,'TURF');$JErowNumber++;
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$JErowNumber,'SIZE / LENGTH')->setCellValue('B'.$JErowNumber,$roadm)->setCellValue('C'.$JErowNumber,$roadmm)->setCellValue('D'.$JErowNumber,$concm)->setCellValue('E'.$JErowNumber,$concmm)->setCellValue('F'.$JErowNumber,$turfm)->setCellValue('G'.$JErowNumber,$turfmm);$JErowNumber++;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$JErowNumber.':C'.$JErowNumber);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$JErowNumber.':E'.$JErowNumber);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$JErowNumber.':G'.$JErowNumber);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('B'.$JErowNumber.':G'.$JErowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$JErowNumber,'PIPE TESTING')->setCellValue('B'.$JErowNumber,'START (PRESSURE)')->setCellValue('D'.$JErowNumber,'END (PRESSURE)')->setCellValue('F'.$JErowNumber,'REMARKS');$JErowNumber++;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$JErowNumber.':C'.$JErowNumber);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$JErowNumber.':E'.$JErowNumber);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$JErowNumber.':G'.$JErowNumber);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('B'.$JErowNumber.':E'.$JErowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$JErowNumber,$pipetesting)->setCellValue('B'.$JErowNumber,$pressurestart)->setCellValue('D'.$JErowNumber,$pressureend)->setCellValue('F'.$JErowNumber,$teamremarks);$JErowNumber++;$JErowNumber++;

    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$JErowNumber,'EMPLOYEE REPORT');$JErowNumber++;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$JErowNumber.':E'.$JErowNumber);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$JErowNumber.':G'.$JErowNumber);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$JErowNumber,'NAME')->setCellValue('B'.$JErowNumber,'START')->setCellValue('C'.$JErowNumber,'END')->setCellValue('D'.$JErowNumber,'OT')->setCellValue('F'.$JErowNumber,'REMARKS');$JErowNumber++;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$JErowNumber.':E'.$JErowNumber);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$JErowNumber.':G'.$JErowNumber);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('B'.$JErowNumber.':E'.$JErowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$JErowNumber,$EmployeeReport[5])->setCellValue('B'.$JErowNumber,$EmployeeReport[1])->setCellValue('C'.$JErowNumber,$EmployeeReport[2])->setCellValue('D'.$JErowNumber,$EmployeeReport[3])->setCellValue('F'.$JErowNumber,$EmployeeReport[4]);

//Site Visit
    $SV_designation;$SV_name;$SV_start;$SV_end;$SV_remarks;
    if($SV_details!='null')
    {
        $SVrowNumber = $maxrowNo;
        $maxrowNo=$maxrowNo+count($SV_details)+3;
        $sitevisittable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">SITE VISIT</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center" height=20px><td height=20px align="center" style="color:white;" nowrap><b>DESIGNATION</b></td><td height=20px align="center" style="color:white;" nowrap><b>NAME</b></td><td height=20px align="center" style="color:white;" nowrap><b>START</b></td><td height=20px align="center" style="color:white;" nowrap><b>END</b></td><td height=20px align="center" style="color:white;" width="250" nowrap><b>REMARKS</b></td></tr>';

        $objPHPExcel->getActiveSheet()->getStyle('A'.$SVrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$SVrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$SVrowNumber,'SITE VISIT');
        $SVrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($SVrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$SVrowNumber.':G'.$SVrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$SVrowNumber,'DESIGNATION');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$SVrowNumber.':C'.$SVrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$SVrowNumber,'NAME');
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$SVrowNumber,'START (Time)');
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$SVrowNumber,'END (Time)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$SVrowNumber.':G'.$SVrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$SVrowNumber,'REMARKS');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$SVrowNumber.':G'.$SVrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$SVrowNumber.':G'.$SVrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$SVrowNumber.':G'.$SVrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $SVrowNumber++;
        for($i=0;$i<count($SV_details);$i++)
        {
            $sitevisittable=$sitevisittable."<tr style='padding-left: 10px;' height=20px ><td height=20px nowrap style='padding-left: 10px;'>".$SV_details[$i][0]."</td><td height=20px nowrap style='padding-left: 10px;'>".$SV_details[$i][1]."</td><td height=20px nowrap style='text-align:center;'>".$SV_details[$i][2]."</td><td height=20px nowrap style='text-align:center;'>".$SV_details[$i][3]."</td><td height=20px nowrap style='padding-left: 10px;'>".$SV_details[$i][4]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('C'.$SVrowNumber.':E'.$SVrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$SVrowNumber.':G'.$SVrowNumber)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$SVrowNumber,$SV_details[$i][0]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$SVrowNumber.':C'.$SVrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$SVrowNumber,$SV_details[$i][1]);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$SVrowNumber,$SV_details[$i][2]);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$SVrowNumber,$SV_details[$i][3]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$SVrowNumber.':G'.$SVrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$SVrowNumber,$SV_details[$i][4]);
            if($i==0)
            {
                $SV_designation=$SV_details[$i][0]; $SV_name=$SV_details[$i][1]; $SV_start=$SV_details[$i][2];$SV_end=$SV_details[$i][3];$SV_remarks=$SV_details[$i][4];
            }
            else
            {
                $SV_designation=$SV_designation.'^'.$SV_details[$i][0]; $SV_name=$SV_name.'^'.$SV_details[$i][1]; $SV_start=$SV_start.'^'.$SV_details[$i][2]; $SV_end=$SV_end.'^'.$SV_details[$i][3]; $SV_remarks=$SV_remarks.'^'.$SV_details[$i][4];
            }
            $SVrowNumber++;
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
        $machineryequipmenttable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">MACHINERY/EQUIPMENT TRANSFER</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center" height=20px><td height=20px align="center" style="color:white;" nowrap><b>FROM(LORRY NO)</b></td><td height=20px align="center" style="color:white;" nowrap><b>ITEM</b></td><td height=20px align="center" style="color:white;" nowrap><b>TO(LORRY NO)</b></td><td height=20px align="center" style="color:white;" width="250" nowrap><b>REMARKS</b></td></tr>';

        $objPHPExcel->getActiveSheet()->getStyle('A'.$METrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$METrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$METrowNumber,'MACHINERY / EQUIPMENT TRANSFER');
        $METrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($METrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$METrowNumber.':G'.$METrowNumber)->applyFromArray($styleArray);
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
            $machineryequipmenttable=$machineryequipmenttable."<tr style='padding-left: 10px;' height=20px ><td height=20px nowrap style='padding-left: 10px;'>".$mech_eqp_transfer[$i][0]."</td><td height=20px nowrap style='padding-left: 10px;'>".$mech_eqp_transfer[$i][1]."</td><td height=20px nowrap style='padding-left: 10px;'>".$mech_eqp_transfer[$i][2]."</td><td height=20px nowrap style='padding-left: 10px;'>".$mech_eqp_transfer[$i][3]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('A'.$METrowNumber.':G'.$METrowNumber)->applyFromArray($styleArray);
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
        $machineryusagetable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">MACHINERY USAGE</caption>  <sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center" height=20px><td height=20px align="center" style="color:white;" nowrap><b>MACHINERY TYPE</b></td><td height=20px align="center" style="color:white;" nowrap><b>START</b></td><td height=20px align="center" style="color:white;" nowrap><b>END</b></td><td height=20px align="center" style="color:white;" width="250" nowrap><b>REMARKS</b></td></tr>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MUrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$MUrowNumber,'MACHINERY USAGE');
        $MUrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($MUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MUrowNumber.':G'.$MUrowNumber)->applyFromArray($styleArray);
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
            $machineryusagetable=$machineryusagetable."<tr style='padding-left: 10px;' height=20px ><td height=20px nowrap style='padding-left: 10px;'>".$mechinery[$i][0]."</td><td height=20px nowrap style='text-align:center;'>".$mechinery[$i][1]."</td><td height=20px nowrap style='text-align:center;'>".$mechinery[$i][2]."</td><td height=20px nowrap style='padding-left: 10px;'>".$mechinery[$i][3]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('B'.$MUrowNumber.':E'.$MUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$MUrowNumber.':G'.$MUrowNumber)->applyFromArray($styleArray);
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
        $rentaltable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">RENTAL MACHINERY</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center" height=20px><td height=20px align="center" style="color:white;" nowrap><b>LORRY NUMBER</b></td><td height=20px align="center" style="color:white;" nowrap><b>THROW EARTH(STORE)</b></td><td height=20px align="center" style="color:white;" nowrap><b>THROW EARTH(OUTSIDE)</b></td><td height=20px align="center" style="color:white;" nowrap><b>START</b></td><td height=20px align="center" style="color:white;" nowrap><b>END</b></td><td height=20px align="center" style="color:white;" width="200" nowrap><b>REMARKS</b></td></tr>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$RMrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$RMrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$RMrowNumber,'RENTAL MACHINERY');
        $RMrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($RMrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$RMrowNumber.':G'.$RMrowNumber)->applyFromArray($styleArray);
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
            $rentaltable=$rentaltable."<tr style='padding-left: 10px;' height=20px ><td height=20px nowrap style='padding-left: 10px;'>".$rental[$i][0]."</td><td height=20px nowrap style='text-align:center;'>".$rental[$i][1]."</td><td height=20px nowrap style='text-align:center;'>".$rental[$i][2]."</td><td height=20px nowrap style='text-align:center;'>".$rental[$i][3]."</td><td height=20px nowrap style='text-align:center;'>".$rental[$i][4]."</td><td height=20px nowrap style='padding-left: 10px;'>".$rental[$i][5]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('B'.$RMrowNumber.':E'.$RMrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$RMrowNumber.':G'.$RMrowNumber)->applyFromArray($styleArray);
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
        $equipmenttable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">EQUIPMENT USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center" height=20px><td height=20px align="center" style="color:white;" nowrap><b>AIR-COMPRESSOR</b></td><td height=20px align="center" style="color:white;" nowrap><b>LORRYNO(TRANSPORT)</b></td><td height=20px align="center" style="color:white;" nowrap><b>START</b></td><td height=20px align="center" style="color:white;" nowrap><b>END</b></td><td height=20px align="center" style="color:white;" width="200" nowrap><b>REMARKS</b></td></tr>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$EUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$EUrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$EUrowNumber,'EQUIPMENT USAGE');
        $EUrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($EUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$EUrowNumber.':G'.$EUrowNumber)->applyFromArray($styleArray);
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
            $equipmenttable=$equipmenttable."<tr style='padding-left: 10px;' height=20px ><td height=20px nowrap style='padding-left: 10px;'>".$equipment[$i][0]."</td><td height=20px nowrap style='padding-left: 10px;'>".$equipment[$i][1]."</td><td height=20px nowrap style='text-align:center;'>".$equipment[$i][2]."</td><td height=20px nowrap style='text-align:center;'>".$equipment[$i][3]."</td><td height=20px nowrap style='padding-left: 10px;'>".$equipment[$i][4]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('C'.$EUrowNumber.':E'.$EUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$EUrowNumber.':G'.$EUrowNumber)->applyFromArray($styleArray);
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
        $fittingtable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">FITTING USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center" height=20px><td height=20px align="center" style="color:white;" nowrap><b>ITEMS</b></td><td height=20px align="center" style="color:white;" nowrap><b>SIZE</b></td><td height=20px align="center" style="color:white;" nowrap><b>QUANTITY</b></td><td height=20px align="center" style="color:white;" width="240" nowrap><b>REMARKS</b></td></tr>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$FUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$FUrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$FUrowNumber,'FITTINGS USAGE');
        $FUrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($FUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$FUrowNumber.':G'.$FUrowNumber)->applyFromArray($styleArray);
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
            $fittingtable=$fittingtable."<tr style='padding-left: 10px;' height=20px ><td height=20px nowrap style='padding-left: 10px;'>".$fitting[$i][0]."</td><td height=20px nowrap style='text-align:center;'>".$fitting[$i][1]."</td><td height=20px nowrap style='text-align:center;'>".$fitting[$i][2]."</td><td height=20px nowrap style='padding-left: 10px;'>".$fitting[$i][3]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('B'.$FUrowNumber.':C'.$FUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$FUrowNumber.':G'.$FUrowNumber)->applyFromArray($styleArray);
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
        $maxrowNo=$maxrowNo+count($material)+3;
        $materialusagetable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">MATERIAL USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center" height=20px><td height=20px align="center" style="color:white;" nowrap><b>ITEMS</b></td><td height=20px align="center" style="color:white;" nowrap><b>RECEIPT NO</b></td><td height=20px align="center" style="color:white;" nowrap><b>QUANTITY</b></td></tr>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MIUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MIUrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$MIUrowNumber,'MATERIAL USAGE');
        $MIUrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($MIUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MIUrowNumber.':G'.$MIUrowNumber)->applyFromArray($styleArray);
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
            $materialusagetable=$materialusagetable."<tr style='padding-left: 10px;' height=20px ><td height=20px nowrap style='padding-left: 10px;'>".$material[$i][0]."</td><td height=20px nowrap style='text-align:center;'>".$material[$i][1]."</td><td height=20px nowrap style='text-align:center;'>".$material[$i][2]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('B'.$MIUrowNumber.':G'.$MIUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$MIUrowNumber.':G'.$MIUrowNumber)->applyFromArray($styleArray);
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
//Site Stock Usage //
    $stockitemno;$stockitemname;$stockqty;
    if($stockusage!='null')
    {
        $SSUrowNumber=$maxrowNo;
        $stockusagetable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">SITE STOCK USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center" height=20px><td height=20px align="center" style="color:white;" nowrap><b>ITEM NO</b></td><td height=20px align="center" style="color:white;" nowrap><b>ITEM NAME</b></td><td height=20px align="center" style="color:white;" nowrap><b>QUANTITY</b></td></tr>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$SSUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$SSUrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$SSUrowNumber,'SITE STOCK USAGE');
        $SSUrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($SSUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$SSUrowNumber.':G'.$SSUrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$SSUrowNumber,'ITEM NO');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$SSUrowNumber.':C'.$SSUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$SSUrowNumber,'ITEM NAME');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$SSUrowNumber.':G'.$SSUrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$SSUrowNumber,'QUANTITY');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$SSUrowNumber.':G'.$SSUrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$SSUrowNumber.':G'.$SSUrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$SSUrowNumber.':G'.$SSUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $SSUrowNumber++;
        for($i=0;$i<count($stockusage);$i++)
        {
            $stockusagetable=$stockusagetable."<tr style='padding-left: 10px;' height=20px ><td height=20px nowrap style='padding-left: 10px;text-align:center;'>".$stockusage[$i][0]."</td><td height=20px nowrap>".$stockusage[$i][1]."</td><td height=20px nowrap style='text-align:center;'>".$stockusage[$i][2]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('B'.$SSUrowNumber.':G'.$SSUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$SSUrowNumber.':G'.$SSUrowNumber)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$SSUrowNumber,$stockusage[$i][0]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$SSUrowNumber.':C'.$SSUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$SSUrowNumber,$stockusage[$i][1]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$SSUrowNumber.':G'.$SSUrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$SSUrowNumber,$stockusage[$i][2]);
            if($i==0)
            {
                $stockitemno=$stockusage[$i][0]; $stockitemname=$stockusage[$i][1];$stockqty=$stockusage[$i][2];
            }
            else
            {
                $stockitemno=$stockitemno.'^'.$stockusage[$i][0]; $stockitemname=$stockitemname.'^'.$stockusage[$i][1];$stockqty=$stockqty.'^'.$stockusage[$i][2];
            }
            $SSUrowNumber++;
        }
        $stockusagetable=$stockusagetable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$stockusagetable.'</td></tr>';
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
    $fileimagecontent=file_get_contents($uploadpath);
    $fileimgeurl=explode("DrawToolImageurl:",$fileimagecontent);
    if($fileimgeurl[1]!=''&&$fileimgeurl[1]!=undefined&&$fileimgeurl[1]!=null)
    {
    $finaltable=$finaltable.'<br><br><tr><td><b>REPORT IMAGE</b><br><br><img id=image src="'.$fileimgeurl[1].'"/></td></tr></table></body></html>';
    }
    else
    {
        $finaltable=$finaltable.'</table></body></html>';
    }
// final table end
    $teamremarks=$con->real_escape_string($teamremarks);
    $SV_remarks=$con->real_escape_string($SV_remarks);
    $mech_remark=$con->real_escape_string($mech_remark);
    $mechineryremark=$con->real_escape_string($mechineryremark);
    $fittingremark=$con->real_escape_string($fittingremark);
    $rental_remark=$con->real_escape_string($rental_remark);
    $equipmentremark=$con->real_escape_string($equipmentremark);
    $MS_remarks=$con->real_escape_string($MS_remarks);
    $Employeeremark=$con->real_escape_string($EmployeeReport[4]);
//Save Part
    if($imgflag==1){
        $callquery="CALL SP_LMC_REPORT_ENTRY_UPDATE_DELETE(1,'$teamname','$EmployeeReport[0]',
        '$reportdate','$teamlocation','$contractid','$reachsite','$leavesite','$typeofjob','$weather',
        '$weatherfrom','$weatherto','$pipetesting','$pressurestart','$pressureend','$teamremarks','$imgfilename',
        '$pipelaid','$size','$length','$EmployeeReport[1]','$EmployeeReport[2]','$EmployeeReport[3]','$Employeeremark',
        ' ','$SV_name','$SV_designation','$SV_start','$SV_end','$SV_remarks',
        ' ','$mech_from','$mech_to','$mech_item','$mech_remark',
        ' ','$mechinerytype','$mechinerystart','$mechineryend','$mechineryremark',
        ' ','$fittingitems','$fittingsize','$fittingqty','$fittingremark',
        ' ','$materialitems','$materialreceipt','$materialqty',
        ' ','$rental_lorryno','$rental_store', '$rental_outside','$rental_start','$rental_end','$rental_remark',
        ' ','$equipmentcompressor','$equipmentlorryno','$equipmentstart','$equipmentend','$equipmentremark',
        ' ','$MS_topic','$MS_remarks',' ','$stockitemno','$stockqty','$UserStamp',@SUCCESS_MESSAGE)";
//        echo $callquery;exit;
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
        $select_emailtemp=mysqli_query($con,"SELECT ETD_EMAIL_SUBJECT, ETD_EMAIL_BODY FROM LMC_EMAIL_TEMPLATE_DETAILS where ETD_ID=3");
        if($row=mysqli_fetch_array($select_emailtemp)){
            $sub=$row["ETD_EMAIL_SUBJECT"];
            $msgbody=$row["ETD_EMAIL_BODY"];
        }
        $replace= array("[UNAME]","[DATE]");
        $str_replaced  = array($EmployeeReport[5],date('d-m-Y',strtotime($reportdate)));
        $emailbody = str_replace($replace, $str_replaced, $msgbody);
        // pdf attachment name
        $reportfilename='TIME SHEET REPORT FOR '.$EmployeeReport[5].' ON '.date('d-m-Y',strtotime($reportdate)).'.pdf';
        // excel attachment name
        $xlreportfilename='TIME SHEET REPORT FOR '.$EmployeeReport[5].' ON '.date('d-m-Y',strtotime($reportdate)).'.xls';
        Mail_part($sub,$emailbody,$finaltable,$reportfilename,$entry_exldata,$xlreportfilename);
    }
    $flagvalues=array($flag,$dirflag,$writable);
    echo json_encode($flagvalues);
}
// REPORT SUBMISSION ENTRY FORM - EMPLOYEE'S DATA
elseif($_REQUEST['option']=='EMPLOYEE_NAME')
{
    $teamname=$_REQUEST['teamname'];
    $date=date('Y-m-d',strtotime($_REQUEST['date']));

    $reportdetails=mysqli_query($con,"SELECT ULD_ID,TERD_START_TIME,TERD_END_TIME,TERD_OT,TERD_REMARK FROM lmc_team_employee_report_details WHERE TRD_ID=(SELECT TRD_ID FROM LMC_TEAM_REPORT_DETAILS WHERE TC_ID=(SELECT TC_ID FROM LMC_TEAM_CREATION WHERE TEAM_NAME='$teamname')AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($reportdetails)){
        $report_details[]=array($row["ULD_ID"],$row["TERD_START_TIME"],$row["TERD_END_TIME"],$row["TERD_OT"],$row["TERD_REMARK"]);
    }
    //EMPLOYEE NAME
    $empname=mysqli_query($con,"SELECT DISTINCT ULD.ULD_WORKER_NAME,ULD.ULD_ID FROM LMC_USER_LOGIN_DETAILS ULD JOIN LMC_EMPLOYEE_TEAM_DETAILS EMP ON ULD.ULD_ID=EMP.ULD_ID WHERE EMP.TC_ID=(select distinct TC_ID  from LMC_TEAM_CREATION where TEAM_NAME='$teamname')");
    while($row=mysqli_fetch_array($empname)){
        $employeename[]=array($row["ULD_WORKER_NAME"],$row["ULD_ID"]);
    }
    //CURRENT EMPLOYEE NAME
    $activeempname=mysqli_query($con,"SELECT ULD_ID,ULD_WORKER_NAME FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$UserStamp'");
    if($row=mysqli_fetch_array($activeempname))
    {
        $activeemp_name[]=array($row["ULD_ID"]);
    }
    $sql="SELECT * FROM LMC_TEAM_REPORT_DETAILS WHERE TRD_DATE='$date' AND TC_ID=(SELECT TC_ID FROM LMC_TEAM_CREATION WHERE TEAM_NAME='$teamname') AND ULD_ID='$activeemp'";
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
    echo json_encode($values);
}
// REPORT SUBMISSION SEARCH FORM DATA
elseif($_REQUEST['option']=='SEARCH_DATA')
{
    $team=$_REQUEST['team'];
    $date=date('Y-m-d',strtotime($_REQUEST['date']));
    //TEAM REPORT DETAILS
    $teamreport_details=mysqli_query($con,"SELECT DATE_FORMAT(TRD_DATE,'%d-%m-%Y') AS TRD_DATE,TRD_LOCATION,CLD_CONTRACT_NO,T1.TEAM_NAME,DATE_FORMAT(TRD_REACH_SITE,'%H:%i' ) AS REACHSITE,DATE_FORMAT(TRD_LEAVE_SITE,'%H:%i' ) AS LEAVESITE,TOJ_ID,DATE_FORMAT(TRD_WEATHER_FROM_TIME,'%H:%i' ) AS WEATHERFROM,DATE_FORMAT(TRD_WEATHER_TO_TIME,'%H:%i' ) AS WEATHERTO,TRD_PIPE_TESTING,TRD_START_PRESSURE,TRD_END_PRESSURE,TRD_REMARK,TRD_WEATHER_REASON,TRD_IMG_FILE_NAME
FROM LMC_TEAM_REPORT_DETAILS L,LMC_TEAM_CREATION T1,LMC_CONTRACT_DETAILS LCD WHERE L.TC_ID=T1.TC_ID AND L.TRD_CONTRACT_NO=LCD.CLD_ID AND TRD_DATE='$date' AND L.ULD_ID='$activeemp'");
    while($row=mysqli_fetch_array($teamreport_details))
    {
        $jobid=$row["TOJ_ID"];
        $filname=$row['TRD_IMG_FILE_NAME'];
        $userfolderid=get_emp_folderid($activeemp);
        if($filname!=null || $filname!=''){
            $upload_dir = $dir.$parentfolder.DIRECTORY_SEPARATOR.$userfolderid.DIRECTORY_SEPARATOR;
            $path = $upload_dir.$filname;
            $fileimagecontent=file_get_contents($path);
            $fileimgeurl=explode("DrawToolImageurl:",$fileimagecontent);
//            $type = pathinfo($path, PATHINFO_EXTENSION);
//            $data = file_get_contents($path);
            $base64 = $fileimgeurl[1];//'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        $team_report_details[]=array($row["TRD_DATE"],$row["TRD_LOCATION"],$row["CLD_CONTRACT_NO"],$row["TEAM_NAME"],$row["REACHSITE"],$row["LEAVESITE"],$row["TOJ_ID"],$row["WEATHERFROM"],$row["WEATHERTO"],$row["TRD_PIPE_TESTING"],$row["TRD_START_PRESSURE"],$row["TRD_END_PRESSURE"],$row["TRD_REMARK"],$row["TRD_WEATHER_REASON"]);
    }
    //JOB ID DETAILS
    $jobdetails=mysqli_query($con,"SELECT GROUP_CONCAT(TOJ_JOB) AS JOB FROM LMC_TYPE_OF_JOB WHERE TOJ_ID IN($jobid)");
    if($row=mysqli_fetch_array($jobdetails))
    {
        $job_details=$row["JOB"];
    }
    //EMPLOYEE DETAILS
    $empdetails=mysqli_query($con,"SELECT L.ULD_ID,L.ULD_WORKER_NAME,DATE_FORMAT(L1.TERD_START_TIME,'%H:%i' ) AS STARTTIME,DATE_FORMAT(L1.TERD_END_TIME,'%H:%i' ) AS ENDTIME,L1.TERD_OT,L1.TERD_REMARK FROM LMC_USER_LOGIN_DETAILS L INNER JOIN LMC_TEAM_EMPLOYEE_REPORT_DETAILS L1  ON L.ULD_ID=L1.ULD_ID AND TRD_ID IN (SELECT TRD_ID FROM LMC_TEAM_REPORT_DETAILS WHERE TRD_DATE='$date') and L.ULD_ID='$activeemp'");
    while($row=mysqli_fetch_array($empdetails))
    {
        $employeedetails[]=array($row["ULD_ID"],$row["ULD_WORKER_NAME"],$row["STARTTIME"],$row["ENDTIME"],$row["TERD_OT"],$row["TERD_REMARK"]);
    }
    //SITE VISIT DETAILS
    $sitevisitdetails=mysqli_query($con,"SELECT SVD_ID,SVD_NAME,SVD_DESIGNATION,DATE_FORMAT(SVD_START_TIME,'%H:%i' ) AS SVDSTARTTIME,DATE_FORMAT(SVD_END_TIME,'%H:%i' ) AS SVDENDTIME,SVD_REMARK FROM LMC_SITE_VISIT_DETAILS WHERE TRD_ID=(SELECT TRD_ID FROM LMC_TEAM_REPORT_DETAILS WHERE ULD_ID='$activeemp' AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($sitevisitdetails))
    {
        $sitevisit_details[]=array($row["SVD_ID"],$row["SVD_NAME"],$row["SVD_DESIGNATION"],$row["SVDSTARTTIME"],$row["SVDENDTIME"],$row["SVD_REMARK"]);
    }
    //MACHINERY_EQUIPMENT DETAILS
    $mech_equip_details=mysqli_query($con,"SELECT MET_ID,MET_FROM_LORRY_NO,MET_TO_LORRY_NO,MI_ITEM,MET_REMARK FROM LMC_MACHINERY_EQUIPMENT_TRANSFER MET,LMC_MACHINERY_ITEM MI WHERE MET.MI_ID = MI.MI_ID AND TRD_ID=(SELECT TRD_ID FROM LMC_TEAM_REPORT_DETAILS WHERE ULD_ID='$activeemp' AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($mech_equip_details))
    {
        $mechequip_details[]=array($row["MET_ID"],$row["MET_FROM_LORRY_NO"],$row["MET_TO_LORRY_NO"],$row["MI_ITEM"],$row["MET_REMARK"]);
    }
    //MACHINERY USAGE DETAILS
    $machineryusage_details=mysqli_query($con,"SELECT MAC_ID,MCU_MACHINERY_TYPE,DATE_FORMAT(MAC_START_TIME,'%H:%i' ) AS MACSTARTTIME,DATE_FORMAT(MAC_END_TIME,'%H:%i' ) AS MACENDTIME,MAC_REMARK FROM LMC_MACHINERY_USAGE_DETAILS LMUD,LMC_MACHINERY_USAGE LMU WHERE LMUD.MCU_ID=LMU.MCU_ID AND TRD_ID=(SELECT TRD_ID FROM LMC_TEAM_REPORT_DETAILS WHERE ULD_ID='$activeemp' AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($machineryusage_details))
    {
        $machinery_usage_details[]=array($row["MAC_ID"],$row["MCU_MACHINERY_TYPE"],$row["MACSTARTTIME"],$row["MACENDTIME"],$row["MAC_REMARK"]);
    }
    //RENTAL MACHINERY USAGE DETAILS
    $rental_machinery_details=mysqli_query($con,"SELECT RMD_ID,RMD_LORRY_NO,RMD_THROWEARTH_STORE,RMD_THROWEARTH_OUTSIDE,DATE_FORMAT(RMD_START_TIME,'%H:%i' ) AS RMDSTARTTIME,DATE_FORMAT(RMD_END_TIME,'%H:%i' ) AS RMDENDTIME,RMD_REMARK FROM LMC_RENTAL_MACHINERY_DETAILS WHERE TRD_ID=(SELECT TRD_ID FROM LMC_TEAM_REPORT_DETAILS WHERE ULD_ID='$activeemp' AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($rental_machinery_details))
    {
        $rentalmachinery_details[]=array($row["RMD_ID"],$row["RMD_LORRY_NO"],$row["RMD_THROWEARTH_STORE"],$row["RMD_THROWEARTH_OUTSIDE"],$row["RMDSTARTTIME"],$row["RMDENDTIME"],$row["RMD_REMARK"]);
    }
    //EQUIPMENT USAGE DETAILS
    $equipment_usage_details=mysqli_query($con,"SELECT EUD_ID,EUD_EQUIPMENT,EUD_LORRY_NO,DATE_FORMAT(EUD_START_TIME,'%H:%i' ) AS EUDSTARTTIME,DATE_FORMAT(EUD_END_TIME,'%H:%i' ) AS EUDENDTIME,EUD_REMARK FROM LMC_EQUIPMENT_USAGE_DETAILS WHERE TRD_ID=(SELECT TRD_ID FROM LMC_TEAM_REPORT_DETAILS WHERE ULD_ID='$activeemp' AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($equipment_usage_details))
    {
        $equipmentusage_details[]=array($row["EUD_ID"],$row["EUD_EQUIPMENT"],$row["EUD_LORRY_NO"],$row["EUDSTARTTIME"],$row["EUDENDTIME"],$row["EUD_REMARK"]);
    }
    //FITTING USAGE DETAILS
    $fitting_usage_details=mysqli_query($con,"SELECT FUD_ID,FU_ITEMS,FUD_SIZE,FUD_QUANTITY,FUD_REMARK FROM LMC_FITTING_USAGE_DETAILS LFUD,LMC_FITTING_USAGE LFU WHERE LFUD.FU_ID=LFU.FU_ID AND TRD_ID=(SELECT TRD_ID FROM LMC_TEAM_REPORT_DETAILS WHERE ULD_ID='$activeemp' AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($fitting_usage_details))
    {
        $fittingusage_details[]=array($row["FUD_ID"],$row["FU_ITEMS"],$row["FUD_SIZE"],$row["FUD_QUANTITY"],$row["FUD_REMARK"]);
    }
    //MATERIAL USAGE DETAILS
    $material_usage_details=mysqli_query($con,"SELECT MUD_ID,MU_ITEMS,MUD_RECEIPT_NO,MUD_QUANTITY FROM LMC_MATERIAL_USAGE_DETAILS LMUD,LMC_MATERIAL_USAGE LMU WHERE LMUD.MU_ID=LMU.MU_ID AND TRD_ID=(SELECT TRD_ID FROM LMC_TEAM_REPORT_DETAILS WHERE ULD_ID='$activeemp' AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($material_usage_details))
    {
        $materialusage_details[]=array($row["MUD_ID"],$row["MU_ITEMS"],$row["MUD_RECEIPT_NO"],$row["MUD_QUANTITY"]);
    }
    //MEETING DETAILS
    $meetingdetails=mysqli_query($con,"SELECT MD_ID,MT_TOPIC,MD_REMARKS FROM LMC_MEETING_DETAILS MD, LMC_MEETING_TOPIC MT WHERE MD.MT_ID=MT.MT_ID AND TRD_ID=(SELECT TRD_ID FROM LMC_TEAM_REPORT_DETAILS WHERE ULD_ID='$activeemp' AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($meetingdetails)){
        $meeting_details[]=array($row["MD_ID"],$row["MT_TOPIC"],$row['MD_REMARKS']);
    }
    //SITE STOCK USAGE DETAILS
    $stockdetails=mysqli_query($con,"SELECT LISU_ID,L2.LID_ITEM_NO,L2.LID_DESCRIPTION,L1.LISU_QUANTITY FROM LMC_INVENTORY_STOCK_USED L1,LMC_INVENTORY_ITEM_DETAILS L2 WHERE L1.LID_ID=L2.LID_ID AND L1.TRD_ID=(SELECT TRD_ID FROM LMC_TEAM_REPORT_DETAILS WHERE ULD_ID='$activeemp' AND TRD_DATE='$date')");
    while($row=mysqli_fetch_array($stockdetails)){
        $stock_details[]=array($row["LISU_ID"],$row["LID_ITEM_NO"],$row["LID_DESCRIPTION"],$row['LISU_QUANTITY']);
    }
    //TEAM JOB
    $typeofjob=mysqli_query($con,"select TOJ_JOB,TOJ_ID from LMC_TYPE_OF_JOB");
    while($row=mysqli_fetch_array($typeofjob)){
        $joptype[]=array($row["TOJ_JOB"],$row['TOJ_ID']);
    }
    //CURRENT EMPLOYEE NAME
    $activeempname=mysqli_query($con,"SELECT ULD_ID,ULD_WORKER_NAME FROM LMC_USER_LOGIN_DETAILS  WHERE ULD_USERNAME='$UserStamp'");
    if($row=mysqli_fetch_array($activeempname))
    {
        $activeemp_name[]=array($row["ULD_ID"]);
    }
    //JOB DONE
    $jobdonedetails=mysqli_query($con,"SELECT GROUP_CONCAT(IF(TJ_PIPE_LAID IS NULL,'',TJ_PIPE_LAID)) as PIPELAID,GROUP_CONCAT(IF (TJ_SIZE IS NULL,'',TJ_SIZE)) AS SIZE,GROUP_CONCAT(IF(TJ_LENGTH IS NULL,'',TJ_LENGTH)) AS LENGTH FROM LMC_TEAM_JOB WHERE TRD_ID=(SELECT TRD_ID FROM LMC_TEAM_REPORT_DETAILS WHERE ULD_ID='$activeemp' AND TRD_DATE='$date')");
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
    $values=array($employeedetails,$sitevisit_details,$mechequip_details,$machinery_usage_details,$rentalmachinery_details,$equipmentusage_details,$fittingusage_details,$materialusage_details,$team_report_details,$job_details,$joptype,$activeemp_name,$jobdone_pipelaid,$jobdone_size,$jobdone_length,$base64,$errormsg,$imagefoldderid,$meeting_details,$stock_details);
    echo json_encode($values);
}
// REPORT SUBMISSION UPDATE FORM - DT
elseif($_REQUEST['option']=='UPDATE_SEARCH_DATA')
{
    $emp=$_REQUEST['emp'];
    $fromdate=date('Y-m-d',strtotime($_REQUEST['fromdate']));
    $todate=date('Y-m-d',strtotime($_REQUEST['todate']));
    //EMPLOYEE DETAILS
    $empdetails=mysqli_query($con," SELECT L1.ULD_ID,DATE_FORMAT(TRD.TRD_DATE,'%d-%m-%Y') AS TRD_DATE,DATE_FORMAT(L1.TERD_START_TIME,'%H:%i' ) AS STARTTIME,DATE_FORMAT(L1.TERD_END_TIME,'%H:%i' ) AS ENDTIME,L1.TRD_ID,L1.TERD_OT,L1.TERD_REMARK,ULD.ULD_USERNAME,DATE_FORMAT(L1.TERD_TIMESTAMP,'%d-%m-%Y %T') AS TERD_TIMESTAMP FROM LMC_TEAM_EMPLOYEE_REPORT_DETAILS L1,LMC_TEAM_REPORT_DETAILS TRD,LMC_USER_LOGIN_DETAILS ULD WHERE TRD.TRD_DATE BETWEEN '$fromdate' AND '$todate' AND TRD.ULD_ID='$emp' AND L1.TRD_ID=TRD.TRD_ID AND TRD.ULD_ID=ULD.ULD_ID ORDER BY TRD.TRD_DATE ASC ");
    while($row=mysqli_fetch_array($empdetails))
    {
        $employeedetails[]=array($row['ULD_ID'],$row["TRD_DATE"],$row["STARTTIME"],$row["ENDTIME"],$row["TRD_ID"],$row["TERD_OT"],$row['TERD_REMARK'],$row['ULD_USERNAME'],$row['TERD_TIMESTAMP']);
    }
    //ERRPOR MESSAGE
    $errormsg=get_error_msg('4,17,21,83,133,143,144');
    $values=array($employeedetails,$errormsg);
    echo json_encode($values);
}
// REPORT SUBMISSION UPDATE FORM DATA
elseif($_REQUEST['option']=='UPDATE_SEARCH')
{
    $trdid=$_REQUEST['trdid'];
    $empid=$_REQUEST['selectedemp'];
    $btn=$_REQUEST['btn'];
    $itemno=array();
    //TEAM REPORT DETAILS
    $teamreport_details=mysqli_query($con,"SELECT DATE_FORMAT(TRD_DATE,'%d-%m-%Y') AS TRD_DATE,TRD_LOCATION,CLD_CONTRACT_NO,TRD_CONTRACT_NO,T1.TEAM_NAME,DATE_FORMAT(TRD_REACH_SITE,'%H:%i' ) AS REACHSITE,DATE_FORMAT(TRD_LEAVE_SITE,'%H:%i' ) AS LEAVESITE,TOJ_ID,DATE_FORMAT(TRD_WEATHER_FROM_TIME,'%H:%i' ) AS WEATHERFROM,DATE_FORMAT(TRD_WEATHER_TO_TIME,'%H:%i' ) AS WEATHERTO,TRD_PIPE_TESTING,TRD_START_PRESSURE,TRD_END_PRESSURE,TRD_REMARK,TRD_WEATHER_REASON,TRD_IMG_FILE_NAME
FROM LMC_TEAM_REPORT_DETAILS L,LMC_TEAM_CREATION T1,LMC_CONTRACT_DETAILS LCD WHERE L.TC_ID=T1.TC_ID AND L.TRD_CONTRACT_NO=LCD.CLD_ID AND TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($teamreport_details))
    {
        $jobid=$row["TOJ_ID"];
        $filname=$row['TRD_IMG_FILE_NAME'];
        $userfolderid=get_emp_folderid($empid);
        if($filname!=null || $filname!=''){
            $upload_dir = $dir.$parentfolder.DIRECTORY_SEPARATOR.$userfolderid.DIRECTORY_SEPARATOR;
            $path = $upload_dir.$filname;
            $fileimagecontent=file_get_contents($path);
            $fileimgeurl=explode("DrawToolImageurl:",$fileimagecontent);
//            $type = pathinfo($path, PATHINFO_EXTENSION);
//            $data = file_get_contents($path);
            $base64 = $fileimgeurl;//[1];//'data:image/' . $type . ';base64,' . base64_encode($data);
        }
//        echo $base64;
        $team_report_details[]=array($row["TRD_DATE"],$row["TRD_LOCATION"],$row["TRD_CONTRACT_NO"],$row["TEAM_NAME"],$row["REACHSITE"],$row["LEAVESITE"],$row["TOJ_ID"],$row["WEATHERFROM"],$row["WEATHERTO"],$row["TRD_PIPE_TESTING"],$row["TRD_START_PRESSURE"],$row["TRD_END_PRESSURE"],$row["TRD_REMARK"],$row["TRD_WEATHER_REASON"]);
        $team_report_details1=array($row["TRD_DATE"],$row["TRD_LOCATION"],$row["CLD_CONTRACT_NO"],$row["TEAM_NAME"],$row["REACHSITE"],$row["LEAVESITE"],$row["TOJ_ID"],$row["WEATHERFROM"],$row["WEATHERTO"],$row["TRD_PIPE_TESTING"],$row["TRD_START_PRESSURE"],$row["TRD_END_PRESSURE"],$row["TRD_REMARK"],$row["TRD_WEATHER_REASON"]);
        // ITEM CODE NAME
        $contactid=$row["TRD_CONTRACT_NO"];
        $itemdtl=mysqli_query($con,"SELECT LID_ID,LID_ITEM_NO,LID_DESCRIPTION FROM LMC_INVENTORY_ITEM_DETAILS IID,LMC_CONTRACT_DETAILS LCD WHERE IID.CLD_ID=LCD.CLD_ID AND IID.CLD_ID=$contactid ORDER BY LID_ITEM_NO");
        while($row=mysqli_fetch_array($itemdtl)){
            $itemno[]=array('id'=>$row["LID_ID"],'no'=>$row["LID_ITEM_NO"],'name'=>$row["LID_DESCRIPTION"]);
        }
    }
    //JOB ID DETAILS
    $jobdetails=mysqli_query($con,"SELECT GROUP_CONCAT(TOJ_JOB) AS JOB FROM LMC_TYPE_OF_JOB WHERE TOJ_ID IN($jobid)");
    if($row=mysqli_fetch_array($jobdetails))
    {
        $job_details=$row["JOB"];
    }
    //EMPLOYEE DETAILS
    $empdetails=mysqli_query($con,"SELECT L.ULD_ID,L.ULD_WORKER_NAME,DATE_FORMAT(L1.TERD_START_TIME,'%H:%i' ) AS STARTTIME,DATE_FORMAT(L1.TERD_END_TIME,'%H:%i' ) AS ENDTIME,L1.TERD_OT,L1.TERD_REMARK FROM LMC_USER_LOGIN_DETAILS L INNER JOIN LMC_TEAM_EMPLOYEE_REPORT_DETAILS L1  ON L.ULD_ID=L1.ULD_ID AND TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($empdetails))
    {
        $employeedetails[]=array($row["ULD_ID"],$row["ULD_WORKER_NAME"],$row["STARTTIME"],$row["ENDTIME"],$row["TERD_OT"],$row["TERD_REMARK"]);
    }
    //SITE VISIT DETAILS
    $sitevisitdetails=mysqli_query($con,"SELECT SVD_ID,SVD_NAME,SVD_DESIGNATION,DATE_FORMAT(SVD_START_TIME,'%H:%i' ) AS SVDSTARTTIME,DATE_FORMAT(SVD_END_TIME,'%H:%i' ) AS SVDENDTIME,SVD_REMARK FROM LMC_SITE_VISIT_DETAILS WHERE TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($sitevisitdetails))
    {
        $sitevisit_details[]=array($row["SVD_ID"],$row["SVD_DESIGNATION"],$row["SVD_NAME"],$row["SVDSTARTTIME"],$row["SVDENDTIME"],$row["SVD_REMARK"]);
    }
    //MACHINERY_EQUIPMENT DETAILS
    $mech_equip_details=mysqli_query($con,"SELECT MET_ID,MET_FROM_LORRY_NO,MET_TO_LORRY_NO,MI_ITEM,MET_REMARK FROM LMC_MACHINERY_EQUIPMENT_TRANSFER MET,LMC_MACHINERY_ITEM MI WHERE MET.MI_ID = MI.MI_ID AND TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($mech_equip_details))
    {
        $mechequip_details[]=array($row["MET_ID"],$row["MET_FROM_LORRY_NO"],$row["MET_TO_LORRY_NO"],$row["MI_ITEM"],$row["MET_REMARK"]);
    }
    //MACHINERY USAGE DETAILS
    $machineryusage_details=mysqli_query($con,"SELECT MAC_ID,MCU_MACHINERY_TYPE,DATE_FORMAT(MAC_START_TIME,'%H:%i' ) AS MACSTARTTIME,DATE_FORMAT(MAC_END_TIME,'%H:%i' ) AS MACENDTIME,MAC_REMARK FROM LMC_MACHINERY_USAGE_DETAILS LMUD,LMC_MACHINERY_USAGE LMU WHERE LMUD.MCU_ID=LMU.MCU_ID AND TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($machineryusage_details))
    {
        $machinery_usage_details[]=array($row["MAC_ID"],$row["MCU_MACHINERY_TYPE"],$row["MACSTARTTIME"],$row["MACENDTIME"],$row["MAC_REMARK"]);
    }
    //RENTAL MACHINERY USAGE DETAILS
    $rental_machinery_details=mysqli_query($con,"SELECT RMD_ID,RMD_LORRY_NO,RMD_THROWEARTH_STORE,RMD_THROWEARTH_OUTSIDE,DATE_FORMAT(RMD_START_TIME,'%H:%i' ) AS RMDSTARTTIME,DATE_FORMAT(RMD_END_TIME,'%H:%i' ) AS RMDENDTIME,RMD_REMARK FROM LMC_RENTAL_MACHINERY_DETAILS WHERE TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($rental_machinery_details))
    {
        $rentalmachinery_details[]=array($row["RMD_ID"],$row["RMD_LORRY_NO"],$row["RMD_THROWEARTH_STORE"],$row["RMD_THROWEARTH_OUTSIDE"],$row["RMDSTARTTIME"],$row["RMDENDTIME"],$row["RMD_REMARK"]);
    }
    //EQUIPMENT USAGE DETAILS
    $equipment_usage_details=mysqli_query($con,"SELECT EUD_ID,EUD_EQUIPMENT,EUD_LORRY_NO,DATE_FORMAT(EUD_START_TIME,'%H:%i' ) AS EUDSTARTTIME,DATE_FORMAT(EUD_END_TIME,'%H:%i' ) AS EUDENDTIME,EUD_REMARK FROM LMC_EQUIPMENT_USAGE_DETAILS WHERE TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($equipment_usage_details))
    {
        $equipmentusage_details[]=array($row["EUD_ID"],$row["EUD_EQUIPMENT"],$row["EUD_LORRY_NO"],$row["EUDSTARTTIME"],$row["EUDENDTIME"],$row["EUD_REMARK"]);
    }
    //FITTING USAGE DETAILS
    $fitting_usage_details=mysqli_query($con,"SELECT FUD_ID,FU_ITEMS,FUD_SIZE,FUD_QUANTITY,FUD_REMARK FROM LMC_FITTING_USAGE_DETAILS LFUD,LMC_FITTING_USAGE LFU WHERE LFUD.FU_ID=LFU.FU_ID AND TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($fitting_usage_details))
    {
        $fittingusage_details[]=array($row["FUD_ID"],$row["FU_ITEMS"],$row["FUD_SIZE"],$row["FUD_QUANTITY"],$row["FUD_REMARK"]);
    }
    //MATERIAL USAGE DETAILS
    $material_usage_details=mysqli_query($con,"SELECT MUD_ID,MU_ITEMS,MUD_RECEIPT_NO,MUD_QUANTITY FROM LMC_MATERIAL_USAGE_DETAILS LMUD,LMC_MATERIAL_USAGE LMU WHERE LMUD.MU_ID=LMU.MU_ID AND TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($material_usage_details))
    {
        $materialusage_details[]=array($row["MUD_ID"],$row["MU_ITEMS"],$row["MUD_RECEIPT_NO"],$row["MUD_QUANTITY"]);
    }
    //MEETING DETAILS
    $meetingdetails=mysqli_query($con,"SELECT MD_ID,MT_TOPIC,MD_REMARKS FROM LMC_MEETING_DETAILS MD, LMC_MEETING_TOPIC MT WHERE MD.MT_ID=MT.MT_ID AND TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($meetingdetails)){
        $meeting_details[]=array($row["MD_ID"],$row["MT_TOPIC"],$row['MD_REMARKS']);
    }
    //SITE STOCK USAGE DETAILS
    $stockdetails=mysqli_query($con,"SELECT LISU_ID,L2.LID_ITEM_NO,L2.LID_DESCRIPTION,L1.LISU_QUANTITY FROM LMC_INVENTORY_STOCK_USED L1,LMC_INVENTORY_ITEM_DETAILS L2 WHERE L1.LID_ID=L2.LID_ID AND L1.TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($stockdetails)){
        $stock_details[]=array($row["LISU_ID"],$row["LID_ITEM_NO"],$row["LID_DESCRIPTION"],$row['LISU_QUANTITY']);
    }
    //TEAM JOB
    $typeofjob=mysqli_query($con,"select TOJ_JOB,TOJ_ID from LMC_TYPE_OF_JOB");
    while($row=mysqli_fetch_array($typeofjob)){
        $joptype[]=array($row["TOJ_JOB"],$row['TOJ_ID']);
    }
    //CURRENT EMPLOYEE NAME
    $activeempname=mysqli_query($con,"SELECT ULD_ID,ULD_WORKER_NAME FROM LMC_USER_LOGIN_DETAILS  WHERE ULD_ID='$empid'");
    if($row=mysqli_fetch_array($activeempname))
    {
        $activeemp_name[]=array($row["ULD_ID"]);
    }
    //JOB DONE
    $jobdonedetails=mysqli_query($con,"SELECT GROUP_CONCAT(IF(TJ_PIPE_LAID IS NULL,'',TJ_PIPE_LAID)) as PIPELAID,GROUP_CONCAT(IF (TJ_SIZE IS NULL,'',TJ_SIZE)) AS SIZE,GROUP_CONCAT(IF(TJ_LENGTH IS NULL,'',TJ_LENGTH)) AS LENGTH FROM LMC_TEAM_JOB WHERE TRD_ID='$trdid'");
    while($row=mysqli_fetch_array($jobdonedetails)){
        $jobdone_pipelaid[]=$row["PIPELAID"];
        $jobdone_size[]=$row['SIZE'];
        $jobdone_length[]=$row["LENGTH"];
    }
    //ERRPOR MESSAGE
    $errormsg=get_error_msg('4,17,21,83,133,143,144,145,147,148,152,156,157');
    $folderid=mysqli_query($con,"SELECT EMP_IMAGE_FOLDER_ID FROM LMC_EMPLOYEE_DETAILS WHERE EMP_ID='$empid'");
    if($row=mysqli_fetch_array($folderid))
    {
        $imagefoldderid=$row['EMP_IMAGE_FOLDER_ID'];
    }
    if($btn=='VIEW_PDF'){
        $jobdone_size =explode(",",$jobdone_size[0]);
        $jobdone_length=explode(",",$jobdone_length[0]);
        if($team_report_details1[13]!=''){
            $weathertime=$team_report_details1[13].' ('.$team_report_details1[7].' TO '.$team_report_details1[8].')';
        }
        else{
            $weathertime='';
        }
    // TEAM REPORT
        $teamreporttable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption style="caption-side: left;font-weight: bold;">TEAM REPORT</caption>
        <tr><td width="100" style= "padding-left: 10px;color:#fff; background-color:#498af3;font-weight: bold;" height=20px>LOCATION</td><td width="360" style="padding-left: 10px;">'.$team_report_details1[1].'</td><td width="140" style="color:#fff; background-color:#498af3;padding-left: 10px;font-weight: bold;" height=20px>CONTRACT NO</td><td  width="250" style="padding-left: 10px;">'.$team_report_details1[2].'</td><td width="150" style="padding-left: 10px;">'.$team_report_details1[3].'</td></tr>
        </table>
        <table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
        <tr><td width="250" style="padding-left: 10px;color:#fff; background-color:#498af3;font-weight: bold;" height=20px>DATE</td><td style="padding-left: 10px;"width="250">'.$team_report_details1[0].'</td><td width="250" style="color:#fff; background-color:#498af3;padding-left: 10px;font-weight: bold;" height=20px>WEATHER</td><td style="padding-left: 10px;" width="250">'.$weathertime.'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3; padding-left: 10px;font-weight: bold;" height=20px>REACH SITE</td><td style="padding-left: 10px;" width="250">'.$team_report_details1[4].'</td><td width="250" style="color:#fff; background-color:#498af3; padding-left: 10px;font-weight: bold;" height=20px>LEAVE SITE</td><td style="padding-left: 10px;" width="250">'.$team_report_details1[5].'</td></tr>
        <tr><td width="250" colspan="1" style="color:#498af3;padding-left: 10px;color:#fff; background-color:#498af3;font-weight: bold;" height=20px>TYPE OF JOB</td><td style="padding-left: 10px;" width="250" colspan="3">'.$job_details.'</td></tr>
        </table>';
    // final table start
        $reportheadername='TIME SHEET REPORT FOR '.$employeedetails[0][1].' ON '.date('d-m-Y',strtotime($team_report_details1[0]));
        $finaltable= '<html><body><table><tr><td style="text-align: center;"><div><img id=imglogo src="../image/LOGO.png"/></div></td></tr><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">' .$reportheadername.'</div></h2></td></tr><br><tr><td>'.$teamreporttable.'</td></tr>';

    // MEETING DETAILS
        if(count($meeting_details)!=0)
        {
            $meetingtable='<br><table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">MEETING</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center"><td align="center" width="400" style="color:white;"height=20px nowrap><b>TOPIC</b></td><td height=20px align="center" style="color:white;" nowrap><b>REMARKS</b></td></tr>';
            for($i=0;$i<count($meeting_details);$i++)
            {
                $meetingtable=$meetingtable."<tr style='padding-left: 10px;'><td nowrap style='padding-left: 10px;' height=20px>".$meeting_details[$i][1]."</td><td nowrap style='padding-left: 10px;' height=20px>".$meeting_details[$i][2]."</td></tr>";
            }
            $meetingtable=$meetingtable.'</table>';
            $finaltable=$finaltable.'<br><br><tr><td>'.$meetingtable.'</td></tr>';
        }
    // JOB DONE DETAILS
        $jobdonetable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption style="caption-side: left;font-weight: bold;">JOB DONE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/>
        <tr><td width="250" style="color:#fff; background-color:#498af3;padding-left: 10px;font-weight: bold;" height=20px>PIPELAID</td><td style="text-align:center;" width="250" colspan=2>ROAD</td><td width="250" colspan=2 style="text-align:center;">CONC</td><td width="250" colspan=2 style="text-align:center;">TRUF</td></tr>
        <tr><td style="color:#fff; background-color:#498af3;padding-left: 10px;font-weight: bold; " height=20px>SIZE/LENGTH</td><td style="padding-left: 10px;">'.$jobdone_size[0].'</td><td style="padding-left: 10px;">'.$jobdone_length[0].'</td><td style="padding-left: 10px;">'.$jobdone_size[1].'</td><td style="padding-left: 10px;">'.$jobdone_length[1].'</td><td style="padding-left: 10px;">'.$jobdone_size[2].'</td><td style="padding-left: 10px;">'.$jobdone_length[2].'</td></tr>
        <tr><td style="color:#fff; background-color:#498af3; padding-left: 10px;font-weight: bold;" height=20px>PIPE TESTING</td><td colspan="2" style="color:#fff; background-color:#498af3;font-weight: bold;text-align:center;" height=20px>START(PRESSURE)</td><td colspan="2" style="color:#fff; background-color:#498af3;font-weight: bold;text-align:center;" height=20px>END(PRESSURE)</td><td colspan="2" style="color:#fff; background-color:#498af3;font-weight: bold;text-align:center;" height=20px>REMARK</td></tr>
        <tr><td style="padding-left: 10px;">'.$team_report_details1[9].'</td><td colspan="2" style="text-align:center;">'.$team_report_details1[10].'</td><td style="text-align:center;"colspan="2">'.$team_report_details1[11].'</td><td colspan="2" style="padding-left: 10px;">'.$team_report_details1[12].'</td></tr>
        </table>';

    // EMPLOYEE TABLE
        $employeetable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">EMPLOYEE DETAILS</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center"><td align="center" style="color:white;" height=20px width="350" nowrap><b>EMPLOYEE NAME</b></td><td align="center" style="color:white;" height=20px nowrap width="100"><b>START</b></td><td width="100" align="center" style="color:white;" height=20px nowrap><b>END</b></td><td width="100" align="center" height=20px style="color:white;" nowrap><b>OT</b></td><td height=20px align="center" style="color:white;" height=20px width="350" nowrap><b>REMARKS</b></td></tr>';
        $employeetable=$employeetable."<tr style='padding-left: 10px;'><td height=20px nowrap style='padding-left: 10px;'>".$employeedetails[0][1]."</td><td height=20px nowrap style='text-align:center;'>".$employeedetails[0][2]."</td><td height=20px nowrap style='text-align:center;'>".$employeedetails[0][3]."</td><td height=20px nowrap style='text-align:center;'>".$employeedetails[0][4]."</td><td height=20px nowrap style='padding-left: 10px;'>".$employeedetails[0][5]."</td></tr></table>";
        $finaltable=$finaltable.'<br><br><tr><td>'.$jobdonetable.'</td></tr><br><br><tr><td>'.$employeetable.'</td></tr>';
    // Site Visit
        if(count($sitevisit_details)!=0)
        {
            $sitevisittable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">SITE VISIT</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center"><td height=20px align="center" style="color:white;" nowrap><b>DESIGNATION</b></td><td height=20px align="center" style="color:white;" nowrap><b>NAME</b></td><td height=20px align="center" style="color:white;" nowrap><b>START</b></td><td height=20px align="center" style="color:white;" nowrap><b>END</b></td><td height=20px align="center" style="color:white;" width="250" nowrap><b>REMARKS</b></td></tr>';
            for($i=0;$i<count($sitevisit_details);$i++)
            {
                $sitevisittable=$sitevisittable."<tr style='padding-left: 10px;' ><td height=20px nowrap style='padding-left: 10px;'>".$sitevisit_details[$i][1]."</td><td height=20px nowrap style='padding-left: 10px;'>".$sitevisit_details[$i][2]."</td><td height=20px nowrap style='text-align:center;'>".$sitevisit_details[$i][3]."</td><td height=20px nowrap style='text-align:center;'>".$sitevisit_details[$i][4]."</td><td height=20px nowrap style='padding-left: 10px;'>".$sitevisit_details[$i][5]."</td></tr>";
            }
            $sitevisittable=$sitevisittable.'</table>';
            $finaltable=$finaltable.'<br><br><tr><td>'.$sitevisittable.'</td></tr>';
        }
    // machinary equip trans
        if(count($mechequip_details)!=0)
        {
            $machineryequipmenttable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">MACHINERY/EQUIPMENT TRANSFER</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center" ><td height=20px align="center" style="color:white;" nowrap><b>FROM(LORRY NO)</b></td><td height=20px align="center" style="color:white;" nowrap><b>ITEM</b></td><td height=20px align="center" style="color:white;" nowrap><b>TO(LORRY NO)</b></td><td height=20px align="center" style="color:white;" width="250" nowrap><b>REMARKS</b></td></tr>';
            for($i=0;$i<count($mechequip_details);$i++)
            {
                $machineryequipmenttable=$machineryequipmenttable."<tr style='padding-left: 10px;' ><td height=20px nowrap style='padding-left: 10px;'>".$mechequip_details[$i][1]."</td><td height=20px nowrap style='padding-left: 10px;'>".$mechequip_details[$i][2]."</td><td height=20px nowrap style='padding-left: 10px;'>".$mechequip_details[$i][3]."</td><td height=20px nowrap style='padding-left: 10px;'>".$mechequip_details[$i][4]."</td></tr>";
            }
            $machineryequipmenttable=$machineryequipmenttable.'</table>';
            $finaltable=$finaltable.'<br><br><tr><td>'.$machineryequipmenttable.'</td></tr>';
        }
    // machinary usage
        if(count($machinery_usage_details)!=0)
        {
            $machineryusagetable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">MACHINERY USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center" ><td height=20px align="center" style="color:white;" nowrap><b>MACHINERY TYPE</b></td><td height=20px align="center" style="color:white;" nowrap><b>START</b></td><td height=20px align="center" style="color:white;" nowrap><b>END</b></td><td height=20px align="center" style="color:white;" width="250" nowrap><b>REMARKS</b></td></tr>';
            for($i=0;$i<count($machinery_usage_details);$i++)
            {
                $machineryusagetable=$machineryusagetable."<tr style='padding-left: 10px;' ><td height=20px nowrap style='padding-left: 10px;'>".$machinery_usage_details[$i][1]."</td><td height=20px nowrap style='text-align:center;'>".$machinery_usage_details[$i][2]."</td><td height=20px nowrap style='text-align:center;'>".$machinery_usage_details[$i][3]."</td><td height=20px nowrap style='padding-left: 10px;'>".$machinery_usage_details[$i][4]."</td></tr>";
            }
            $machineryusagetable=$machineryusagetable.'</table>';
            $finaltable=$finaltable.'<br><br><tr><td>'.$machineryusagetable.'</td></tr>';
        }
    // rental details
        if(count($rentalmachinery_details)!=0)
        {
            $rentaltable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">RENTAL MACHINERY</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center" ><td height=20px align="center" style="color:white;" nowrap><b>LORRY NUMBER</b></td><td height=20px align="center" style="color:white;" nowrap><b>THROW EARTH(STORE)</b></td><td height=20px align="center" style="color:white;" nowrap><b>THROW EARTH(OUTSIDE)</b></td><td height=20px align="center" style="color:white;" nowrap><b>START</b></td><td height=20px align="center" style="color:white;" nowrap><b>END</b></td><td height=20px align="center" style="color:white;" width="200" nowrap><b>REMARKS</b></td></tr>';
            for($i=0;$i<count($rentalmachinery_details);$i++)
            {
                $rentaltable=$rentaltable."<tr style='padding-left: 10px;' ><td height=20px nowrap style='padding-left: 10px;'>".$rentalmachinery_details[$i][1]."</td><td height=20px nowrap style='text-align:center;'>".$rentalmachinery_details[$i][2]."</td><td height=20px nowrap style='text-align:center;'>".$rentalmachinery_details[$i][3]."</td><td height=20px nowrap style='text-align:center;'>".$rentalmachinery_details[$i][4]."</td><td height=20px nowrap style='text-align:center;'>".$rentalmachinery_details[$i][5]."</td><td height=20px nowrap style='padding-left: 10px;'>".$rentalmachinery_details[$i][6]."</td></tr>";
            }
            $rentaltable=$rentaltable.'</table>';
            $finaltable=$finaltable.'<br><br><tr><td>'.$rentaltable.'</td></tr>';
        }
    // equipment usage
        if(count($equipmentusage_details)!=0)
        {
            $equipmenttable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">EQUIPMENT USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center" ><td height=20px align="center" style="color:white;" nowrap><b>AIR-COMPRESSOR</b></td><td height=20px align="center" style="color:white;" nowrap><b>LORRYNO(TRANSPORT)</b></td><td height=20px align="center" style="color:white;" nowrap><b>START</b></td><td height=20px align="center" style="color:white;" nowrap><b>END</b></td><td height=20px align="center" style="color:white;" width="250" nowrap><b>REMARKS</b></td></tr>';
            for($i=0;$i<count($equipmentusage_details);$i++)
            {
                $equipmenttable=$equipmenttable."<tr style='padding-left: 10px;' ><td height=20px nowrap style='padding-left: 10px;'>".$equipmentusage_details[$i][1]."</td><td height=20px nowrap style='padding-left: 10px;'>".$equipmentusage_details[$i][2]."</td><td height=20px nowrap style='text-align:center;'>".$equipmentusage_details[$i][3]."</td><td height=20px nowrap style='text-align:center;'>".$equipmentusage_details[$i][4]."</td><td height=20px nowrap style='padding-left: 10px;'>".$equipmentusage_details[$i][5]."</td></tr>";
            }
            $equipmenttable=$equipmenttable.'</table>';
            $finaltable=$finaltable.'<br><br><tr><td>'.$equipmenttable.'</td></tr>';
        }
    // fitting usage
        if(count($fittingusage_details)!=0)
        {
            $fittingtable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">FITTING USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center" ><td height=20px align="center" style="color:white;" nowrap><b>ITEMS</b></td><td height=20px align="center" style="color:white;" nowrap><b>SIZE</b></td><td height=20px align="center" style="color:white;" nowrap><b>QUANTITY</b></td><td height=20px align="center" style="color:white;" width="300" nowrap><b>REMARKS</b></td></tr>';
            for($i=0;$i<count($fittingusage_details);$i++)
            {
                $fittingtable=$fittingtable."<tr style='padding-left: 10px;' ><td height=20px nowrap style='padding-left: 10px;'>".$fittingusage_details[$i][1]."</td><td height=20px nowrap style='text-align:center;'>".$fittingusage_details[$i][2]."</td><td height=20px nowrap style='text-align:center;'>".$fittingusage_details[$i][3]."</td><td height=20px nowrap style='padding-left: 10px;'>".$fittingusage_details[$i][4]."</td></tr>";
            }
            $fittingtable=$fittingtable.'</table>';
            $finaltable=$finaltable.'<br><br><tr><td>'.$fittingtable.'</td></tr>';
        }
    // Material Usage //
        if(count($materialusage_details)!=0)
        {
            $materialusagetable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">MATERIAL USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center" ><td height=20px align="center" style="color:white;" nowrap><b>ITEMS</b></td><td height=20px align="center" style="color:white;" nowrap><b>RECEIPT NO</b></td><td height=20px align="center" style="color:white;" nowrap><b>QUANTITY</b></td></tr>';
            for($i=0;$i<count($materialusage_details);$i++)
            {
                $materialusagetable=$materialusagetable."<tr style='padding-left: 10px;' ><td height=20px nowrap style='padding-left: 10px;'>".$materialusage_details[$i][1]."</td><td height=20px nowrap style='padding-left: 10px;text-align:center;'>".$materialusage_details[$i][2]."</td><td height=20px nowrap style='text-align:center;'>".$materialusage_details[$i][3]."</td></tr>";
            }
            $materialusagetable=$materialusagetable.'</table>';
            $finaltable=$finaltable.'<br><br><tr><td>'.$materialusagetable.'</td></tr>';
        }
    // stock Usage //
        if(count($stock_details)!=0)
        {
            $stockusagetable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">SITE STOCK USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center" ><td height=20px align="center" style="color:white;" nowrap><b>ITEM NO</b></td><td height=20px align="center" style="color:white;" nowrap><b>ITEM NAME</b></td><td height=20px align="center" style="color:white;" nowrap><b>QUANTITY</b></td></tr>';
            for($i=0;$i<count($stock_details);$i++)
            {
                $stockusagetable=$stockusagetable."<tr style='padding-left: 10px;' ><td height=20px nowrap style='padding-left: 10px;text-align:center;'>".$stock_details[$i][1]."</td><td height=20px nowrap style='padding-left: 10px;'>".$stock_details[$i][2]."</td><td height=20px nowrap style='text-align:center;'>".$stock_details[$i][3]."</td></tr>";
            }
            $stockusagetable=$stockusagetable.'</table>';
            $finaltable=$finaltable.'<br><br><tr><td>'.$stockusagetable.'</td></tr>';
        }
    // image
        if($base64[1]!=''&&$base64[1]!=undefined&&$base64[1]!=null)
        {
            $finaltable=$finaltable.'<br><br><tr><td><b>REPORT IMAGE</b><br><br><img id=image src="'.$base64[1].'"/></td></tr></table></body></html>';
        }
        else{
            $finaltable=$finaltable.'</table></body></html>';
        }
        $dir1 = $dir.'/Phpfiles/';
        foreach(glob($dir1.'*.*') as $v){
            unlink($v);
        }
        $mpdf=new mPDF('utf-8','A4');
        $mpdf->debug=true;
//        $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">LIH MING CONSTRUCTION PTE LTD</div></h3>', 'O', true);
//        $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;"><img id=image src="image/LOGO.png"/></div></h3>', 'O', true);
        $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
        $mpdf->WriteHTML($finaltable);
        $reportpdf=$mpdf->Output('../Phpfiles/'.$reportheadername.'.pdf','f');
        echo '../Phpfiles/'.$reportheadername.'.pdf';
    }
    else{
        $values=array($employeedetails,$sitevisit_details,$mechequip_details,$machinery_usage_details,$rentalmachinery_details,$equipmentusage_details,$fittingusage_details,$materialusage_details,$team_report_details,$job_details,$joptype,$activeemp_name,$jobdone_pipelaid,$jobdone_size,$jobdone_length,$base64,$errormsg,$imagefoldderid,$meeting_details,$stock_details,$itemno);
        echo json_encode($values);
    }
}
// REPORT SUBMISSION UPDATE FORM
elseif($_REQUEST['Option']=='UpdateForm')
{
    $teamlocation=$_POST["SRC_tr_txt_location"];
    $contractid=$_POST["SRC_tr_lb_contractno"];
    $teamname=$_POST['SRC_tr_tb_team'];
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
    // CONTRACT NOs
    $contract_no=mysqli_query($con,"SELECT CLD_CONTRACT_NO FROM LMC_CONTRACT_DETAILS WHERE CLD_ID = $contractid");
    while($row=mysqli_fetch_array($contract_no)){
        $contractno= $row["CLD_CONTRACT_NO"];
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

    $stockusage=$_POST["SRC_StockDetails"];
    $material=$_POST["SRC_MaterialDetails"];
    $fitting=$_POST["SRC_FittingDetails"];
    $equipment=$_POST["SRC_EquipmentDetails"];
    $rental=$_POST["SRC_RentalDetails"];
    $mechinery=$_POST["SRC_MechineryUsageDetails"];
    $mech_eqp_transfer=$_POST["SRC_MechEqptransfer"];
    $SV_details=$_POST["SRC_SiteVisit"];
    $meeting=$_POST["SRC_MeetingDetails"];
    $EmployeeReport=$_POST["SRC_EmployeeDetails"];
    $imagedata=$_POST['imgData'];
//    echo "SELECT TRD_IMG_FILE_NAME FROM LMC_TEAM_REPORT_DETAILS WHERE TRD_DATE='$reportdate' AND EMP_ID='$activeemp'";
    $oldimgfileid=mysqli_query($con,"SELECT TRD_IMG_FILE_NAME FROM LMC_TEAM_REPORT_DETAILS WHERE TRD_DATE='$reportdate' AND ULD_ID='$activeemp'");
    if($row=mysqli_fetch_array($oldimgfileid))
    {
        $old_imgfileid=$row['TRD_IMG_FILE_NAME'];
    }
    //End of File Uploads
    $userfolderid;
    $uploadpath;
    if($imagedata!='' && $reportdate!='' && $EmployeeReport[0]!='' && $teamname!=''){
        $daterep=str_replace('-','',$reportdate);
//        $imgfilename=$EmployeeReport[0].'_'.$daterep.'_'.date('His').'.png';
        $imgfilename=$EmployeeReport[0].'_'.$daterep.'_'.date('His').'.txt';

        $userfolderid=get_emp_folderid($EmployeeReport[0]);
        chmod($userfolderid,0777);
        $path=$dir.$parentfolder.DIRECTORY_SEPARATOR.$userfolderid.DIRECTORY_SEPARATOR;
//        $path='LMC_REPORT_IMAGE'.DIRECTORY_SEPARATOR.'RENU_01042015_180754'.DIRECTORY_SEPARATOR;
        if ( ! is_writable($path))
        {
            $writable=0;
        } else {

            $writable=1;
        }
        if(is_dir($path)){
            $dirflag=1;
        }
        else{
            $dirflag=0;
        }
        if($dirflag==1 && $writable==1){
            $uploadpath=$path.$imgfilename;
            try{
//                $data=str_replace('data:image/png;base64,','',$imagedata);
//                $data = str_replace(' ','+',$data);
//                $data = base64_decode($data);
                $success = file_put_contents($uploadpath, $imagedata);//file_put_contents($uploadpath, $data);
                $imgflag=1;
            }
            catch(Exception $e){
                $imgflag=0;
                unlink($uploadpath);
                print $e->getMessage();
            }
        }
        else{
            $imgflag=0;
            $imgfilename='';
        }
    }
    elseif($imagedata==''){
        $imgflag=1;
        $imgfilename='';
    }
    if($weather!=''){
        $weathertime=$weather.' ('.$weatherfrom.' TO '.$weatherto.')';
    }
    else{
        $weathertime='';
    }
    //TEAM REPORT DETAILS
    $emp_name=mysqli_query($con,"SELECT ULD_WORKER_NAME FROM LMC_USER_LOGIN_DETAILS WHERE ULD_ID=$EmployeeReport[0]");
    while($row=mysqli_fetch_array($emp_name)){
        $empnames=$row["ULD_WORKER_NAME"];
    }
    $jobname=mysqli_query($con,"SELECT GROUP_CONCAT(TOJ_JOB SEPARATOR ' / ') AS JOB FROM LMC_TYPE_OF_JOB WHERE TOJ_ID IN($typeofjob)");
    while($row=mysqli_fetch_array($jobname)){
        $jobnames=$row["JOB"];
    }
//TEAM REPORT
    $teamreporttable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption style="caption-side: left;font-weight: bold;">TEAM REPORT</caption>
   <tr><td width="100" style= "padding-left: 10px;color:#fff; background-color:#498af3;font-weight: bold;" height=20px>LOCATION</td><td width="360" style="padding-left: 10px;">'.$teamlocation.'</td><td width="140" style="color:#fff; background-color:#498af3;padding-left: 10px;font-weight: bold;" height=20px>CONTRACT NO</td><td  width="250" style="padding-left: 10px;">'.$contractno.'</td><td width="150" style="padding-left: 10px;">'.$teamname.'</td></tr></table>
   <table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><tr><td width="250" style="padding-left: 10px;color:#fff; background-color:#498af3;font-weight: bold;" height=20px>DATE</td><td style="padding-left: 10px;"width="250">'.$reportdate.'</td><td width="250" style="color:#fff; background-color:#498af3;padding-left: 10px;font-weight: bold;" height=20px>WEATHER</td><td style="padding-left: 10px;" width="250">'.$weathertime.'</td></tr>
   <tr><td width="250" style="color:#fff; background-color:#498af3; padding-left: 10px;font-weight: bold;" height=20px>REACH SITE</td><td style="padding-left: 10px;" width="250">'.$reachsite.'</td><td width="250" style="color:#fff; background-color:#498af3; padding-left: 10px;font-weight: bold;" height=20px>LEAVE SITE</td><td style="padding-left: 10px;" width="250">'.$leavesite.'</td></tr>
   <tr><td width="250" colspan="1" style="color:#498af3;padding-left: 10px;color:#fff; background-color:#498af3;font-weight: bold;" height=20px>TYPE OF JOB</td><td style="padding-left: 10px;" width="250" colspan="3">'.$jobnames.'</td></tr>
   </table>';
// final table start
    $reportheadername='TIME SHEET UPDATED REPORT FOR '.$empnames;
    $finaltable= '<html><body><table><tr><td style="text-align: center;"><div><img id=imglogo src="../image/LOGO.png"/></div></td></tr><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">' .$reportheadername.'</div></h2></td></tr><br><tr><td>'.$teamreporttable.'</td></tr>';

// FOR EXCEL
    $sheettitle="TIME SHEET UPDATED REPORT FOR ".$empnames;
    $objPHPExcel->getActiveSheet()->setTitle('REPORT SUBMISSION UPDATE')->setCellValue('A1', $sheettitle)->setCellValue('A2', 'TEAM REPORT')
        ->setCellValue('A3', 'LOCATION')->setCellValue('B3',$teamlocation)->setCellValue('C3', 'CONTRACT NO')->setCellValue('D3', $contractno)->setCellValue('F3', 'TEAM')->setCellValue('G3', $teamname)
        ->setCellValue('A4', 'DATE') ->setCellValue('B4', $reportdate) ->setCellValue('C4','WEATHER')->setCellValue('D4',$weathertime)
        ->setCellValue('A5', 'REACH SITE')->setCellValue('B5', $reachsite)->setCellValue('C5', 'LEAVE SITE')->setCellValue('D5', $leavesite)->setCellValue('A6', 'TYPE OF JOB')->setCellValue('B6',$jobnames);
    $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
    $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('A3:G6')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->freezePane('A2');
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D3:E3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D4:G4');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D5:G5');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B6:G6');
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A3:A6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('C3:C5')->getFont()->setBold(true);
//    $objPHPExcel->getActiveSheet()->getStyle('A:E')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('A2:A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('A3:A6')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('A3:A6')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('C3:C5')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('C3:C5')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('F3')->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $maxrowNo=8;
    $MSrowNumber='';
    $JErowNumber='';
    $SVrowNumber='';
    $METrowNumber='';
    $MUrowNumber='';
    $RMrowNumber='';
    $EUrowNumber='';
    $FUrowNumber='';
    $MIUrowNumber='';
    $SSUrowNumber='';
//Meeting Details
    $MS_id;$MS_topic;$MS_remarks;
    if($meeting!='null')
    {
        $MSrowNumber = $maxrowNo;
        $maxrowNo=$maxrowNo+count($meeting)+3;
        $meetingtable='<br><table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">MEETING</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3"  align="center" ><td height=20px width="400" align="center" style="color:white;" width="400" nowrap><b>TOPIC</b></td><td height=20px align="center" style="color:white;" nowrap><b>REMARKS</b></td></tr>';

        $objPHPExcel->getActiveSheet()->getStyle('A'.$MSrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MSrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$MSrowNumber,'MEETING');
        $MSrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($MSrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MSrowNumber.':G'.$MSrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$MSrowNumber.':B'.$MSrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$MSrowNumber,'TOPIC');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C'.$MSrowNumber.':G'.$MSrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$MSrowNumber,'REMARKS');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MSrowNumber.':G'.$MSrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MSrowNumber.':G'.$MSrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MSrowNumber.':G'.$MSrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $MSrowNumber++;
        for($i=0;$i<count($meeting);$i++)
        {
            $meetingtable=$meetingtable."<tr style='padding-left: 10px;' ><td height=20px nowrap style='padding-left: 10px;'>".$meeting[$i][1]."</td><td height=20px nowrap style='padding-left: 10px;'>".$meeting[$i][2]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('A'.$MSrowNumber.':G'.$MSrowNumber)->applyFromArray($styleArray);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$MSrowNumber.':B'.$MSrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$MSrowNumber,$meeting[$i][1]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C'.$MSrowNumber.':G'.$MSrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$MSrowNumber,$meeting[$i][2]);
            if($i==0)
            {
                $MS_id=$meeting[$i][0];$MS_topic=$meeting[$i][1]; $MS_remarks=$meeting[$i][2];
            }
            else
            {
                if($meeting[$i][0]=='' && $meeting[$i][1]!='')
                {
                    $MS_id=$MS_id.','.$meeting[$i][0].' ';
                }
                else
                {
                    $MS_id=$MS_id.','.$meeting[$i][0];
                }
                $MS_topic=$MS_topic.'^'.$meeting[$i][1]; $MS_remarks=$MS_remarks.'^'.$meeting[$i][2];
            }
            $MSrowNumber++;
        }
        $meetingtable=$meetingtable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$meetingtable.'</td></tr>';
    }
//JOB DONE DETAILS
    $jobdonetable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption style="caption-side: left;font-weight: bold;">JOB DONE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/>
   <tr><td width="250" style="color:#fff; background-color:#498af3;padding-left: 10px;font-weight: bold;" height=20px>PIPELAID</td><td style="text-align:center;" width="250" colspan=2>ROAD</td><td width="250" colspan=2 style="text-align:center;">CONC</td><td width="250" colspan=2 style="text-align:center;">TRUF</td></tr>
   <tr><td style="color:#fff; background-color:#498af3;padding-left: 10px;font-weight: bold; " height=20px>SIZE/LENGTH</td><td style="padding-left: 10px;">'.$roadm.'</td><td style="padding-left: 10px;">'.$roadmm.'</td><td style="padding-left: 10px;">'.$concm.'</td><td style="padding-left: 10px;">'.$concmm.'</td><td style="padding-left: 10px;">'.$turfm.'</td><td style="padding-left: 10px;">'.$turfmm.'</td>
   <tr><td style="color:#fff; background-color:#498af3; padding-left: 10px;font-weight: bold;" height=20px>PIPE TESTING</td><td colspan="2" style="color:#fff; background-color:#498af3;font-weight: bold;text-align:center;" height=20px>START(PRESSURE)</td><td colspan="2" style="color:#fff; background-color:#498af3;font-weight: bold;text-align:center;" height=20px>END(PRESSURE)</td><td colspan="2" style="color:#fff; background-color:#498af3;font-weight: bold;text-align:center;" height=20px>REMARK</td></tr>
   <tr><td style="padding-left: 10px;">'.$pipetesting.'</td><td colspan="2" style="text-align:center;">'.$pressurestart.'</td><td style="text-align:center;"colspan="2">'.$pressureend.'</td><td colspan="2" style="padding-left: 10px;">'.$teamremarks.'</td></tr>
   </table>';
//EMPLOYEE TABLE
    $employeetable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">EMPLOYEE DETAILS</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center"><td height=20px align="center" style="color:white;"  width="350" nowrap><b>EMPLOYEE NAME</b></td><td height=20px align="center" style="color:white;" width="100" nowrap><b>START</b></td><td height=20px align="center" style="color:white;" width="100" nowrap><b>END</b></td><td height=20px align="center" style="color:white;" width="100" nowrap><b>OT</b></td><td height=20px align="center" style="color:white;" width="350" nowrap><b>REMARKS</b></td></tr>';
    $employeetable=$employeetable."<tr style='padding-left: 10px;' ><td height=20px nowrap style='padding-left: 10px;'>".$empnames."</td><td height=20px nowrap style='text-align:center;'>".$EmployeeReport[1]."</td><td height=20px nowrap style='text-align:center;'>".$EmployeeReport[2]."</td><td height=20px nowrap style='text-align:center;'>".$EmployeeReport[3]."</td><td height=20px nowrap style='padding-left: 10px;'>".$EmployeeReport[4]."</td></tr></table>";
    $finaltable=$finaltable.'<br><br><tr><td>'.$jobdonetable.'</td></tr><br><br><tr><td>'.$employeetable.'</td></tr>';

    $JErowNumber = $maxrowNo;
    if($maxrowNo>8){
        $maxrowNo=$maxrowNo+10;
    }
    else if($maxrowNo==8){
        $maxrowNo=18;
    }
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$JErowNumber,'JOB DONE');$JErowNumber++;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$JErowNumber.':C'.$JErowNumber);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$JErowNumber.':E'.$JErowNumber);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$JErowNumber.':G'.$JErowNumber);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('B'.$JErowNumber.':G'.$JErowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$JErowNumber,'PIPE LAID')->setCellValue('B'.$JErowNumber,'ROAD')->setCellValue('D'.$JErowNumber,'CONC')->setCellValue('F'.$JErowNumber,'TURF');$JErowNumber++;
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$JErowNumber,'SIZE / LENGTH')->setCellValue('B'.$JErowNumber,$roadm)->setCellValue('C'.$JErowNumber,$roadmm)->setCellValue('D'.$JErowNumber,$concm)->setCellValue('E'.$JErowNumber,$concmm)->setCellValue('F'.$JErowNumber,$turfm)->setCellValue('G'.$JErowNumber,$turfmm);$JErowNumber++;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$JErowNumber.':C'.$JErowNumber);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$JErowNumber.':E'.$JErowNumber);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$JErowNumber.':G'.$JErowNumber);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('B'.$JErowNumber.':G'.$JErowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$JErowNumber,'PIPE TESTING')->setCellValue('B'.$JErowNumber,'START (PRESSURE)')->setCellValue('D'.$JErowNumber,'END (PRESSURE)')->setCellValue('F'.$JErowNumber,'REMARKS');$JErowNumber++;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$JErowNumber.':C'.$JErowNumber);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$JErowNumber.':E'.$JErowNumber);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$JErowNumber.':G'.$JErowNumber);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('B'.$JErowNumber.':E'.$JErowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$JErowNumber,$pipetesting)->setCellValue('B'.$JErowNumber,$pressurestart)->setCellValue('D'.$JErowNumber,$pressureend)->setCellValue('F'.$JErowNumber,$teamremarks);$JErowNumber++;$JErowNumber++;
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$JErowNumber,'EMPLOYEE REPORT');$JErowNumber++;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$JErowNumber.':E'.$JErowNumber);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$JErowNumber.':G'.$JErowNumber);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->getFont()->getColor()->setRGB('FFFAFA');
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$JErowNumber,'NAME')->setCellValue('B'.$JErowNumber,'START')->setCellValue('C'.$JErowNumber,'END')->setCellValue('D'.$JErowNumber,'OT')->setCellValue('F'.$JErowNumber,'REMARKS');$JErowNumber++;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$JErowNumber.':E'.$JErowNumber);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$JErowNumber.':G'.$JErowNumber);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$JErowNumber.':G'.$JErowNumber)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('B'.$JErowNumber.':E'.$JErowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$JErowNumber,$empnames)->setCellValue('B'.$JErowNumber,$EmployeeReport[1])->setCellValue('C'.$JErowNumber,$EmployeeReport[2])->setCellValue('D'.$JErowNumber,$EmployeeReport[3])->setCellValue('F'.$JErowNumber,$EmployeeReport[4]);

//Site Visit
    $SV_ID; $SV_designation;$SV_name;$SV_start;$SV_end;$SV_remarks;
    if($SV_details!='null')
    {
        $SVrowNumber = $maxrowNo;
        $maxrowNo=$maxrowNo+count($SV_details)+3;
        $sitevisittable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">SITE VISIT</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center"><td height=20px align="center" style="color:white;" nowrap><b>DESIGNATION</b></td><td height=20px align="center" style="color:white;" nowrap><b>NAME</b></td><td height=20px align="center" style="color:white;" nowrap><b>START</b></td><td height=20px align="center" style="color:white;" nowrap><b>END</b></td><td height=20px align="center" style="color:white;" width="250" nowrap><b>REMARKS</b></td></tr>';

        $objPHPExcel->getActiveSheet()->getStyle('A'.$SVrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$SVrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$SVrowNumber,'SITE VISIT');
        $SVrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($SVrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$SVrowNumber.':G'.$SVrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$SVrowNumber,'DESIGNATION');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$SVrowNumber.':C'.$SVrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$SVrowNumber,'NAME');
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$SVrowNumber,'START (Time)');
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$SVrowNumber,'END (Time)');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$SVrowNumber.':G'.$SVrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$SVrowNumber,'REMARKS');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$SVrowNumber.':G'.$SVrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$SVrowNumber.':G'.$SVrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$SVrowNumber.':G'.$SVrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $SVrowNumber++;
        for($i=0;$i<count($SV_details);$i++)
        {
            $sitevisittable=$sitevisittable."<tr style='padding-left: 10px;' ><td height=20px nowrap style='padding-left: 10px;'>".$SV_details[$i][1]."</td><td height=20px nowrap style='padding-left: 10px;'>".$SV_details[$i][2]."</td><td height=20px nowrap style='text-align:center;'>".$SV_details[$i][3]."</td><td height=20px nowrap style='text-align:center;'>".$SV_details[$i][4]."</td><td height=20px nowrap style='padding-left: 10px;'>".$SV_details[$i][5]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('C'.$SVrowNumber.':E'.$SVrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$SVrowNumber.':G'.$SVrowNumber)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$SVrowNumber,$SV_details[$i][1]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$SVrowNumber.':C'.$SVrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$SVrowNumber,$SV_details[$i][2]);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$SVrowNumber,$SV_details[$i][3]);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$SVrowNumber,$SV_details[$i][4]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$SVrowNumber.':G'.$SVrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$SVrowNumber,$SV_details[$i][5]);
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
            $SVrowNumber++;
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
        $machineryequipmenttable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">MACHINERY/EQUIPMENT TRANSFER</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center"><td height=20px align="center" style="color:white;" nowrap><b>FROM(LORRY NO)</b></td><td height=20px align="center" style="color:white;" nowrap><b>ITEM</b></td><td height=20px align="center" style="color:white;" nowrap><b>TO(LORRY NO)</b></td><td height=20px align="center" style="color:white;" width="250"  nowrap><b>REMARKS</b></td></tr>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$METrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$METrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$METrowNumber,'MACHINERY / EQUIPMENT TRANSFER');
        $METrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($METrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$METrowNumber.':G'.$METrowNumber)->applyFromArray($styleArray);
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
            $machineryequipmenttable=$machineryequipmenttable."<tr style='padding-left: 10px;' ><td height=20px nowrap style='padding-left: 10px;'>".$mech_eqp_transfer[$i][1]."</td><td height=20px nowrap style='padding-left: 10px;'>".$mech_eqp_transfer[$i][2]."</td><td height=20px nowrap style='padding-left: 10px;'>".$mech_eqp_transfer[$i][3]."</td><td height=20px nowrap style='padding-left: 10px;'>".$mech_eqp_transfer[$i][4]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('A'.$METrowNumber.':G'.$METrowNumber)->applyFromArray($styleArray);
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
        $machineryusagetable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">MACHINERY USAGE</caption>  <sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center"><td height=20px align="center" style="color:white;" nowrap><b>MACHINERY TYPE</b></td><td height=20px align="center" style="color:white;" nowrap><b>START</b></td><td height=20px align="center" style="color:white;" nowrap><b>END</b></td><td height=20px align="center" style="color:white;" width="250" nowrap><b>REMARKS</b></td></tr>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MUrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$MUrowNumber,'MACHINERY USAGE');
        $MUrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($MUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MUrowNumber.':G'.$MUrowNumber)->applyFromArray($styleArray);
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
            $machineryusagetable=$machineryusagetable."<tr style='padding-left: 10px;' ><td height=20px nowrap style='padding-left: 10px;'>".$mechinery[$i][1]."</td><td height=20px nowrap style='text-align:center;'>".$mechinery[$i][2]."</td><td height=20px nowrap style='text-align:center;'>".$mechinery[$i][3]."</td><td height=20px nowrap style='padding-left: 10px;'>".$mechinery[$i][4]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('B'.$MUrowNumber.':E'.$MUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$MUrowNumber.':G'.$MUrowNumber)->applyFromArray($styleArray);
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
        $rentaltable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">RENTAL MACHINERY</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center"><td height=20px align="center" style="color:white;" nowrap><b>LORRY NUMBER</b></td><td height=20px align="center" style="color:white;" nowrap><b>THROW EARTH(STORE)</b></td><td height=20px align="center" style="color:white;" nowrap><b>THROW EARTH(OUTSIDE)</b></td><td height=20px align="center" style="color:white;" nowrap><b>START</b></td><td height=20px align="center" style="color:white;" nowrap><b>END</b></td><td height=20px align="center" style="color:white;" width="200" nowrap><b>REMARKS</b></td></tr>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$RMrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$RMrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$RMrowNumber,'RENTAL MACHINERY');
        $RMrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($RMrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$RMrowNumber.':G'.$RMrowNumber)->applyFromArray($styleArray);
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
            $rentaltable=$rentaltable."<tr style='padding-left: 10px;' ><td height=20px nowrap style='padding-left: 10px;'>".$rental[$i][1]."</td><td height=20px nowrap style='text-align:center;'>".$rental[$i][2]."</td><td height=20px nowrap style='text-align:center;'>".$rental[$i][3]."</td><td height=20px nowrap style='text-align:center;'>".$rental[$i][4]."</td><td height=20px nowrap style='text-align:center;'>".$rental[$i][5]."</td><td height=20px nowrap style='padding-left: 10px;'>".$rental[$i][6]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('B'.$RMrowNumber.':E'.$RMrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$RMrowNumber.':G'.$RMrowNumber)->applyFromArray($styleArray);
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
        $equipmenttable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">EQUIPMENT USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center"><td height=20px align="center" style="color:white;" nowrap><b>AIR-COMPRESSOR</b></td><td height=20px align="center" style="color:white;" nowrap><b>LORRYNO(TRANSPORT)</b></td><td height=20px align="center" style="color:white;" nowrap><b>START</b></td><td height=20px align="center" style="color:white;" nowrap><b>END</b></td><td height=20px align="center" style="color:white;" width="200" nowrap><b>REMARKS</b></td></tr>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$EUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$EUrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$EUrowNumber,'EQUIPMENT USAGE');
        $EUrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($EUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$EUrowNumber.':G'.$EUrowNumber)->applyFromArray($styleArray);
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
            $equipmenttable=$equipmenttable."<tr style='padding-left: 10px;' ><td height=20px nowrap style='padding-left: 10px;'>".$equipment[$i][1]."</td><td height=20px nowrap style='padding-left: 10px;'>".$equipment[$i][2]."</td><td height=20px nowrap style='text-align:center;'>".$equipment[$i][3]."</td><td height=20px nowrap style='text-align:center;'>".$equipment[$i][4]."</td><td height=20px nowrap style='padding-left: 10px;'>".$equipment[$i][5]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('C'.$EUrowNumber.':E'.$EUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$EUrowNumber.':G'.$EUrowNumber)->applyFromArray($styleArray);
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
        $fittingtable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">FITTING USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center"><td height=20px align="center" style="color:white;" nowrap><b>ITEMS</b></td><td height=20px align="center" style="color:white;" nowrap><b>SIZE</b></td><td height=20px align="center" style="color:white;" nowrap><b>QUANTITY</b></td><td height=20px align="center" style="color:white;" width="240" nowrap><b>REMARKS</b></td></tr>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$FUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$FUrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$FUrowNumber,'FITTINGS USAGE');
        $FUrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($FUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$FUrowNumber.':G'.$FUrowNumber)->applyFromArray($styleArray);
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
            $fittingtable=$fittingtable."<tr style='padding-left: 10px;' ><td height=20px nowrap style='padding-left: 10px;'>".$fitting[$i][1]."</td><td height=20px nowrap style='text-align:center;'>".$fitting[$i][2]."</td><td height=20px nowrap style='text-align:center;'>".$fitting[$i][3]."</td><td height=20px nowrap style='padding-left: 10px;'>".$fitting[$i][4]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('B'.$FUrowNumber.':C'.$FUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$FUrowNumber.':G'.$FUrowNumber)->applyFromArray($styleArray);
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
        $maxrowNo=$maxrowNo+count($material)+3;
        $materialusagetable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">MATERIAL USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center"><td height=20px align="center" style="color:white;" nowrap><b>ITEMS</b></td><td height=20px align="center" style="color:white;" nowrap><b>RECEIPT NO</b></td><td height=20px align="center" style="color:white;" nowrap><b>QUANTITY</b></td></tr>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MIUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MIUrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$MIUrowNumber,'MATERIAL USAGE');
        $MIUrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($MIUrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$MIUrowNumber.':G'.$MIUrowNumber)->applyFromArray($styleArray);
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
            $materialusagetable=$materialusagetable."<tr style='padding-left: 10px;' ><td height=20px nowrap style='padding-left: 10px;'>".$material[$i][1]."</td><td height=20px nowrap style='text-align:center;'>".$material[$i][2]."</td><td height=20px nowrap style='text-align:center;'>".$material[$i][3]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('B'.$MIUrowNumber.':G'.$MIUrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$MIUrowNumber.':G'.$MIUrowNumber)->applyFromArray($styleArray);
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
//Site Stock Usage //
    $stock_id;$stockitemno;$stockitemname;$stockqty;
    if($stockusage!='null')
    {
        $ISSrowNumber=$maxrowNo;
        $stockusagetable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;">SITE STOCK USAGE</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center"><td height=20px align="center" style="color:white;" nowrap><b>ITEM NO</b></td><td height=20px align="center" style="color:white;" nowrap><b>ITEM NAME</b></td><td height=20px align="center" style="color:white;" nowrap><b>QUANTITY</b></td></tr>';
        $objPHPExcel->getActiveSheet()->getStyle('A'.$ISSrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$ISSrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$ISSrowNumber,'SITE STOCK USAGE');
        $ISSrowNumber++;
        $objPHPExcel->getActiveSheet()->getStyle($ISSrowNumber)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$ISSrowNumber.':G'.$ISSrowNumber)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$ISSrowNumber,'ITEM NO');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$ISSrowNumber.':C'.$ISSrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$ISSrowNumber,'ITEM NAME');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$ISSrowNumber.':G'.$ISSrowNumber);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$ISSrowNumber,'QUANTITY');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$ISSrowNumber.':G'.$ISSrowNumber)->getFont()->getColor()->setRGB('FFFAFA');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$ISSrowNumber.':G'.$ISSrowNumber)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '#498af3'))));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$ISSrowNumber.':G'.$ISSrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $ISSrowNumber++;
        for($i=0;$i<count($stockusage);$i++)
        {
            $stockusagetable=$stockusagetable."<tr style='padding-left: 10px;'><td height=20px nowrap style='padding-left: 10px;text-align:center;'>".$stockusage[$i][1]."</td><td height=20px nowrap>".$stockusage[$i][2]."</td><td height=20px nowrap style='text-align:center;'>".$stockusage[$i][3]."</td></tr>";
            $objPHPExcel->getActiveSheet()->getStyle('B'.$ISSrowNumber.':G'.$ISSrowNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$ISSrowNumber.':G'.$ISSrowNumber)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$ISSrowNumber,$stockusage[$i][1]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$ISSrowNumber.':C'.$ISSrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$ISSrowNumber,$stockusage[$i][2]);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$ISSrowNumber.':G'.$ISSrowNumber);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$ISSrowNumber,$stockusage[$i][3]);
            if($i==0)
            {
                $stock_id=$stockusage[$i][0];$stockitemno=$stockusage[$i][1]; $stockitemname=$stockusage[$i][2];$stockqty=$stockusage[$i][3];
            }
            else
            {
                if($stockusage[$i][0]=='' && $stockusage[$i][1]!='' && $stockusage[$i][3]!='')
                {
                    $stock_id=$stock_id.','.$stockusage[$i][0].' ';
                }
                else
                {
                    $stock_id=$stock_id.','.$stockusage[$i][0];
                }
                $stockitemno=$stockitemno.'^'.$stockusage[$i][1]; $stockitemname=$stockitemname.'^'.$stockusage[$i][2];$stockqty=$stockqty.'^'.$stockusage[$i][3];
            }
            $ISSrowNumber++;
        }
        $stockusagetable=$stockusagetable.'</table>';
        $finaltable=$finaltable.'<br><br><tr><td>'.$stockusagetable.'</td></tr>';
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
    $fileimagecontent=file_get_contents($uploadpath);
    $fileimgeurl=explode("DrawToolImageurl:",$fileimagecontent);
    if($fileimgeurl[1]!=''&&$fileimgeurl[1]!=undefined&&$fileimgeurl[1]!=null)
    {
    $finaltable=$finaltable.'<br><br><tr><td><b>REPORT IMAGE</b><br><br><img id=image src="'.$fileimgeurl[1].'"/></td></tr></table></body></html>';
    }
    else
    {
        $finaltable=$finaltable.'</table></body></html>';
    }
//final table end
    $teamremarks=$con->real_escape_string($teamremarks);
    $SV_remarks=$con->real_escape_string($SV_remarks);
    $mech_remark=$con->real_escape_string($mech_remark);
    $mechineryremark=$con->real_escape_string($mechineryremark);
    $fittingremark=$con->real_escape_string($fittingremark);
    $rental_remark=$con->real_escape_string($rental_remark);
    $equipmentremark=$con->real_escape_string($equipmentremark);
    $MS_remarks=$con->real_escape_string($MS_remarks);
    $Employeeremark=$con->real_escape_string($EmployeeReport[4]);
    //update part
    if($imgflag==1){
        $callquery="CALL SP_LMC_REPORT_ENTRY_UPDATE_DELETE(2,'$teamname','$EmployeeReport[0]','$reportdate','$teamlocation',
        '$contractid','$reachsite','$leavesite','$typeofjob','$weather','$weatherfrom','$weatherto','$pipetesting','$pressurestart',
        '$pressureend','$teamremarks','$imgfilename','$pipelaid','$size','$length',
        '$EmployeeReport[1]','$EmployeeReport[2]','$EmployeeReport[3]','$Employeeremark',
        '$SV_ID','$SV_name','$SV_designation','$SV_start','$SV_end','$SV_remarks',
        '$ME_id','$mech_from','$mech_to','$mech_item','$mech_remark',
        '$mechinery_id','$mechinerytype','$mechinerystart','$mechineryend','$mechineryremark',
        '$fitting_id','$fittingitems','$fittingsize','$fittingqty','$fittingremark',
        '$mat_id','$materialitems','$materialreceipt','$materialqty',
        '$rental_id','$rental_lorryno','$rental_store', '$rental_outside','$rental_start','$rental_end','$rental_remark',
        '$equip_id','$equipmentcompressor','$equipmentlorryno','$equipmentstart','$equipmentend','$equipmentremark',
        '$MS_id','$MS_topic','$MS_remarks','$stock_id','$stockitemno','$stockqty','$UserStamp',@SUCCESS_MESSAGE)";
//        echo $callquery;exit;
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
    if($flag==1 && ($old_imgfileid!='' && $old_imgfileid!=null&&$old_imgfileid!=""))
    {
        $deltpath = $dir.$parentfolder.DIRECTORY_SEPARATOR.$userfolderid.DIRECTORY_SEPARATOR.$old_imgfileid;
        $dir.$parentfolder.DIRECTORY_SEPARATOR.$userfolderid.DIRECTORY_SEPARATOR;
        unlink($deltpath);
    }
    if($flag==1){
        $select_emailtemp=mysqli_query($con,"SELECT ETD_EMAIL_SUBJECT, ETD_EMAIL_BODY FROM LMC_EMAIL_TEMPLATE_DETAILS where ETD_ID=4");
        if($row=mysqli_fetch_array($select_emailtemp)){
            $sub=$row["ETD_EMAIL_SUBJECT"];
            $msgbody=$row["ETD_EMAIL_BODY"];
        }
        $emailbody = str_replace("[LOGINID]",$empnames, $msgbody);
        // pdf attachment name
        $reportfilename='TIME SHEET UPDATED REPORT FOR '.$empnames.'.pdf';
        // excel attachment name
        $xlreportfilename='TIME SHEET UPDATED REPORT FOR '.$empnames.'.xls';
        Mail_part($sub,$emailbody,$finaltable,$reportfilename,$update_exldata,$xlreportfilename);
    }
    $flagvalues=array($flag,$dirflag,$writable);
    echo json_encode($flagvalues);
}
// MAIL SEND PART
function Mail_part($sub,$emailbody,$finaltable,$reportfilename,$reportexldata,$xlreportfilename){
    global $con;
    try {
        $select_to = mysqli_query($con, "SELECT * FROM LMC_USER_LOGIN_DETAILS WHERE RC_ID=2");
        if ($row = mysqli_fetch_array($select_to)) {
            $toaddress = $row["ULD_EMAIL_ID"];
        }
        $select_cc = mysqli_query($con, "SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=10");
        if ($row = mysqli_fetch_array($select_cc)) {
            $ccaddress = $row["URC_DATA"];
        }
        $select_host = mysqli_query($con, "SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=14");
        if ($row = mysqli_fetch_array($select_host)) {
            $host = $row["URC_DATA"];
        }
        $select_username = mysqli_query($con, "SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=11");
        if ($row = mysqli_fetch_array($select_username)) {
            $username = $row["URC_DATA"];
        }
        $select_password = mysqli_query($con, "SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=12");
        if ($row = mysqli_fetch_array($select_password)) {
            $password = $row["URC_DATA"];
        }
        $select_smtpsecure = mysqli_query($con, "SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=13");
        if ($row = mysqli_fetch_array($select_smtpsecure)) {
            $smtpsecure = $row["URC_DATA"];
        }
        $admin_name = substr($ccaddress, 0, strpos($ccaddress, '@'));
        $sadmin_name = substr($toaddress, 0, strpos($toaddress, '@'));
        if(substr($admin_name, 0, strpos($admin_name, '.'))){
            $admin_name = strtoupper(substr($admin_name, 0, strpos($admin_name, '.')));
        }
        else{
            $admin_name=$admin_name;
        }
        if(substr($sadmin_name, 0, strpos($sadmin_name, '.'))){
            $sadmin_name = strtoupper(substr($sadmin_name, 0, strpos($sadmin_name, '.')));
        }
        else{
            $sadmin_name=$sadmin_name;
        }
        $spladminname=$admin_name.'/'.$sadmin_name;
        $spladminname=strtoupper($spladminname);
        $emailbody=str_replace('[SADMIN]',$spladminname,$emailbody);
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = $smtpsecure;
        $mail->Port = 587;
        $mail->FromName = 'LMC';
        $mail->addAddress($toaddress);
        $mail->addCC($ccaddress);
        $mail->WordWrap = 50;
        $mail->isHTML(true);
        $mail->Subject = $sub;
        $mail->Body = $emailbody;
        // pdf attachment
        $mpdf = new mPDF('utf-8', 'A4');
        $mpdf->debug = true;
        $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
        $mpdf->WriteHTML($finaltable);
        $reportpdf = $mpdf->Output('foo.pdf', 'S');
        $mail->AddStringAttachment($reportpdf, $reportfilename);
        // excel attachment
        $mail->AddStringAttachment($reportexldata, $xlreportfilename);
        $mail->send();
    }
    catch(Exception $ex){
        return $ex->getMessage();
    }
}