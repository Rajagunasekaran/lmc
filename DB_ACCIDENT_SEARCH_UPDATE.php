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
if($_REQUEST['option']=='COMMON_DATA')
{
    //EMPLOYEE NAME
    $empname=mysqli_query($con,"SELECT ARD_ID,ARD_NAME FROM LMC_ACCIDENT_REPORT_DETAILS ORDER BY ARD_NAME ASC ");
    while($row=mysqli_fetch_array($empname)){
        $employeename[]=array($row["ARD_NAME"],$row['ARD_ID']);
    }
    //ERRPOR MESSAGE
    $errormsg=get_error_msg('4,6,16,17,143');
    $values=array($employeename,$errormsg);
    echo JSON_encode($values);
}
//FETCHING FLEX TABLE DATA
elseif($_REQUEST['option']=='FETCH_DATA')
{
    $emp=$_REQUEST['emp'];
    $fromdate=date('Y-m-d',strtotime($_REQUEST['fromdate']));
    $todate=date('Y-m-d',strtotime($_REQUEST['todate']));
    //EMPLOYEE DETAILS
    $empdetails=mysqli_query($con,"SELECT ARD.ARD_ID,ARD.ARD_TIME,ARD.ARD_NAME,DATE_FORMAT(ARD.ARD_DATE,'%d-%m-%Y') AS ARD_DATE,ARD.ARD_PLACE,ARD.ARD_TYPE_OF_INJURY,ARD.ARD_NATURE_OF_INJURY,ARD.ARD_LOCATION,ARD.ARD_DESCRIPTION,ULD.ULD_USERNAME,DATE_FORMAT(CONVERT_TZ(ARD.ARD_TIMESTAMP,'+00:00','+08:00'),'%d-%m-%Y %T') AS ARD_TIMESTAMP,ARD.ARD_INJURED_PART,ARD.ARD_MACHINERY_TYPE,ARD.ARD_LM_NO,ARD.ARD_OPERATOR_NAME,ARD.ARD_DESCRIPTION,ARD.ARD_AGE,ARD.ARD_ADDRESS,ARD.ARD_NRIC_NO,ARD.ARD_FIN_NO,ARD.ARD_WORK_PERMIT_NO,ARD.ARD_PASSPORT_NO,ARD.ARD_NATIONALITY,ARD.ARD_SEX,DATE_FORMAT(ARD.ARD_DOB,'%d-%m-%Y') AS ARD_DOB,ARD.ARD_MARTIAL_STATUS,ARD.ARD_DESIGNATION,ARD.ARD_LENGTH_OF_SERVICE,ARD.ARD_WORK_COMMENCEMENT FROM lmc_accident_report_details ARD,LMC_USER_LOGIN_DETAILS ULD WHERE ARD.ULD_ID=ULD.ULD_ID AND ARD.ARD_DATE BETWEEN '$fromdate' AND '$todate' ORDER BY ARD_DATE");
    while($row=mysqli_fetch_array($empdetails))
    {
        $employeedetails[]=array($row['ARD_NAME'],$row["ARD_DATE"],$row['ARD_PLACE'],$row['ARD_TYPE_OF_INJURY'],$row['ARD_NATURE_OF_INJURY'],$row['ARD_LOCATION'],$row['ARD_DESCRIPTION'],$row['ULD_USERNAME'],$row['ARD_TIMESTAMP'],$row['ARD_ID'],$row['ARD_INJURED_PART'],$row['ARD_TIME'],$row['ARD_MACHINERY_TYPE'],$row['ARD_LM_NO'],$row['ARD_OPERATOR_NAME'],$row['ARD_DESCRIPTION'],$row['ARD_AGE'],$row['ARD_ADDRESS'],$row['ARD_NRIC_NO'],$row['ARD_FIN_NO'],$row['ARD_WORK_PERMIT_NO'],$row['ARD_PASSPORT_NO'],$row['ARD_NATIONALITY'],$row['ARD_SEX'],$row['ARD_DOB'],$row['ARD_MARTIAL_STATUS'],$row['ARD_DESIGNATION'],$row['ARD_LENGTH_OF_SERVICE'],$row['ARD_WORK_COMMENCEMENT']);
    }
    $values=array($employeedetails);
    echo JSON_encode($values);
}
//FETCHING UPDATE DATAS
elseif($_REQUEST['option']=='UPDATE_SEARCH')
{
    $trdid=$_REQUEST['trdid'];
    $ET_SRC_UPD_DEL_flextbl= mysqli_query($con,"SELECT ARD.ARD_ID,ARD.ARD_NAME,ARD.ARD_DATE,ARD.ARD_PLACE,ARD.ARD_TYPE_OF_INJURY,ARD.ARD_NATURE_OF_INJURY,ARD.ARD_TIME,ARD.ARD_LOCATION,ARD.ARD_INJURED_PART,ARD.ARD_MACHINERY_TYPE,ARD.ARD_LM_NO,ARD.ARD_OPERATOR_NAME,ARD.ARD_AGE,ARD.ARD_ADDRESS,ARD.ARD_NRIC_NO,ARD.ARD_FIN_NO,ARD.ARD_WORK_PERMIT_NO,ARD.ARD_PASSPORT_NO,ARD.ARD_NATIONALITY,ARD.ARD_SEX,ARD.ARD_DOB,ARD.ARD_MARTIAL_STATUS,ARD.ARD_DESIGNATION,ARD.ARD_LENGTH_OF_SERVICE,ARD.ARD_WORK_COMMENCEMENT,ARD.ARD_DESCRIPTION,ULD.ULD_USERNAME,ARD.ARD_TIMESTAMP FROM lmc_accident_report_details ARD,LMC_USER_LOGIN_DETAILS ULD  WHERE  ARD.ARD_ID='$trdid'");
    $ET_SRC_UPD_DEL_values=array();
    while($row=mysqli_fetch_array($ET_SRC_UPD_DEL_flextbl)){
        $ASU_SRC_UPD_ardid=$row["ARD_ID"];
        $ASU_SRC_UPD_name=$row["ARD_NAME"];
        $ASU_SRC_UPD_date=$row["ARD_DATE"];
        $ASU_SRC_UPD_place=$row["ARD_PLACE"];
        if($ASU_SRC_UPD_place!=null){
            $URE_placetxt='';
            $URE_reason_msg =explode("\n", $ASU_SRC_UPD_place);
            $length=count($URE_reason_msg);
            for($i=0;$i<=$length;$i++){
                $URE_placetxt.=$URE_reason_msg[$i];
                if($i!=$length)
                    $URE_placetxt.='<br>';
            }
        }
        else{
            $URE_placetxt=null;
        }
        $ASU_SRC_UPD_injury=$row["ARD_TYPE_OF_INJURY"];
        $ASU_SRC_UPD_natureinjury=$row["ARD_NATURE_OF_INJURY"];
        $ASU_SRC_UPD_time=$row["ARD_TIME"];
        $ASU_SRC_UPD_location=$row["ARD_LOCATION"];
        $ASU_SRC_UPD_injuredpart=$row["ARD_INJURED_PART"];
        $ASU_SRC_UPD_machinery=$row["ARD_MACHINERY_TYPE"];
        $ASU_SRC_UPD_lmno=$row["ARD_LM_NO"];
        $ASU_SRC_UPD_opertrname=$row["ARD_OPERATOR_NAME"];
        $ASU_SRC_UPD_age=$row["ARD_AGE"];
        $ASU_SRC_UPD_address=$row["ARD_ADDRESS"];
        if($ASU_SRC_UPD_address!=null){
            $URE_addresstxt='';
            $URE_reason_msg =explode("\n", $ASU_SRC_UPD_address);
            $length=count($URE_reason_msg);
            for($i=0;$i<=$length;$i++){
                $URE_addresstxt.=$URE_reason_msg[$i];
                if($i!=$length)
                    $URE_addresstxt.='<br>';
            }
        }
        else{
            $URE_addresstxt=null;
        }
        $ASU_SRC_UPD_nric=$row["ARD_NRIC_NO"];
        $ASU_SRC_UPD_finno=$row["ARD_FIN_NO"];
        $ASU_SRC_UPD_permitno=$row["ARD_WORK_PERMIT_NO"];
        $ASU_SRC_UPD_pasportno=$row["ARD_PASSPORT_NO"];
        $ASU_SRC_UPD_nationality=$row["ARD_NATIONALITY"];
        $ASU_SRC_UPD_sex=$row["ARD_SEX"];
        $ASU_SRC_UPD_dob=$row["ARD_DOB"];
        $ASU_SRC_UPD_martial=$row["ARD_MARTIAL_STATUS"];
        $ASU_SRC_UPD_designation=$row["ARD_DESIGNATION"];
        $ASU_SRC_UPD_service=$row["ARD_LENGTH_OF_SERVICE"];
        $ASU_SRC_UPD_commencement=$row["ARD_WORK_COMMENCEMENT"];
        $ASU_SRC_UPD_description=$row["ARD_DESCRIPTION"];
        if($ASU_SRC_UPD_description!=null){
            $URE_reasontxt='';
            $URE_reason_msg =explode("\n", $ASU_SRC_UPD_description);
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
        $ASU_SRC_UPD_uldid=$row['ULD_ID'];
        $ASU_SRC_UPD_timestamp=$row['ARD_TIMESTAMP'];
        $final_values=array('ASU_SRC_UPD_ardid'=>$ASU_SRC_UPD_ardid,'ASU_SRC_UPD_date' =>$ASU_SRC_UPD_date,'ASU_SRC_UPD_place' =>$ASU_SRC_UPD_place,'ASU_SRC_UPD_time' =>$ASU_SRC_UPD_time,'ASU_SRC_UPD_location' =>$ASU_SRC_UPD_location,'ASU_SRC_UPD_injury' =>$ASU_SRC_UPD_injury,'ASU_SRC_UPD_natureinjury' =>$ASU_SRC_UPD_natureinjury,'ASU_SRC_UPD_machinery' =>$ASU_SRC_UPD_machinery,'ASU_SRC_UPD_lmno' =>$ASU_SRC_UPD_lmno,'ASU_SRC_UPD_opertrname' =>$ASU_SRC_UPD_opertrname,'ASU_SRC_UPD_description' =>$ASU_SRC_UPD_description,'ASU_SRC_UPD_name' =>$ASU_SRC_UPD_name,'ASU_SRC_UPD_age' =>$ASU_SRC_UPD_age,'ASU_SRC_UPD_address' =>$ASU_SRC_UPD_address,'ASU_SRC_UPD_nric' =>$ASU_SRC_UPD_nric,'ASU_SRC_UPD_finno' =>$ASU_SRC_UPD_finno,'ASU_SRC_UPD_permitno' =>$ASU_SRC_UPD_permitno,'ASU_SRC_UPD_pasportno' =>$ASU_SRC_UPD_pasportno,'ASU_SRC_UPD_nationality' =>$ASU_SRC_UPD_nationality,'ASU_SRC_UPD_sex' =>$ASU_SRC_UPD_sex,'ASU_SRC_UPD_dob' =>$ASU_SRC_UPD_dob,'ASU_SRC_UPD_martial' =>$ASU_SRC_UPD_martial,'ASU_SRC_UPD_designation' =>$ASU_SRC_UPD_designation,'ASU_SRC_UPD_service' =>$ASU_SRC_UPD_service,'ASU_SRC_UPD_commencement' =>$URE_reasontxt,'ET_SRC_UPD_DEL_userstamp'=>$ET_SRC_UPD_DEL_userstamp,'ET_SRC_UPD_DEL_timestamp'=>$ET_SRC_UPD_DEL_timestamp);
        $ET_SRC_UPD_DEL_values[]=$final_values;
    }
    echo JSON_ENCODE($ET_SRC_UPD_DEL_values);
}
//FUNCTION FOR VIEW PDF OPTION
elseif($_REQUEST['option']=='VIEW_PDF_FETCH')
{
    $emp=$_REQUEST['pdfid'];
    $fromdate=date('Y-m-d',strtotime($_REQUEST['fromdate']));
    $todate=date('Y-m-d',strtotime($_REQUEST['todate']));
    //EMPLOYEE DETAILS
    $empdetails=mysqli_query($con,"SELECT ARD.ARD_ID,ARD.ARD_TIME,ARD.ARD_NAME,DATE_FORMAT(ARD.ARD_DATE,'%d-%m-%Y') AS ARD_DATE,ARD.ARD_PLACE,ARD.ARD_TYPE_OF_INJURY,ARD.ARD_NATURE_OF_INJURY,ARD.ARD_LOCATION,ARD.ARD_DESCRIPTION,ULD.ULD_USERNAME,ARD.ARD_TIMESTAMP,ARD.ARD_INJURED_PART,ARD.ARD_MACHINERY_TYPE,ARD.ARD_LM_NO,ARD.ARD_OPERATOR_NAME,ARD.ARD_DESCRIPTION,ARD.ARD_AGE,ARD.ARD_ADDRESS,ARD.ARD_NRIC_NO,ARD.ARD_FIN_NO,ARD.ARD_WORK_PERMIT_NO,ARD.ARD_PASSPORT_NO,ARD.ARD_NATIONALITY,ARD.ARD_SEX,DATE_FORMAT(ARD.ARD_DOB,'%d-%m-%Y') AS ARD_DOB,ARD.ARD_MARTIAL_STATUS,ARD.ARD_DESIGNATION,ARD.ARD_LENGTH_OF_SERVICE,ARD.ARD_WORK_COMMENCEMENT FROM lmc_accident_report_details ARD,LMC_USER_LOGIN_DETAILS ULD WHERE ARD.ULD_ID=ULD.ULD_ID AND ARD.ARD_DATE BETWEEN '$fromdate' AND '$todate' AND ARD.ARD_ID='$emp'");
    while($row=mysqli_fetch_array($empdetails))
    {
        $employeedetails[]=array($row['ARD_NAME'],$row["ARD_DATE"],$row['ARD_PLACE'],$row['ARD_TYPE_OF_INJURY'],$row['ARD_NATURE_OF_INJURY'],$row['ARD_LOCATION'],$row['ARD_DESCRIPTION'],$row['ULD_USERNAME'],$row['ARD_TIMESTAMP'],$row['ARD_ID'],$row['ARD_INJURED_PART'],$row['ARD_TIME'],$row['ARD_MACHINERY_TYPE'],$row['ARD_LM_NO'],$row['ARD_OPERATOR_NAME'],$row['ARD_DESCRIPTION'],$row['ARD_AGE'],$row['ARD_ADDRESS'],$row['ARD_NRIC_NO'],$row['ARD_FIN_NO'],$row['ARD_WORK_PERMIT_NO'],$row['ARD_PASSPORT_NO'],$row['ARD_NATIONALITY'],$row['ARD_SEX'],$row['ARD_DOB'],$row['ARD_MARTIAL_STATUS'],$row['ARD_DESIGNATION'],$row['ARD_LENGTH_OF_SERVICE'],$row['ARD_WORK_COMMENCEMENT']);
    }
//PARTICULARS OF ACCIDENT
//    $accidentreporttable='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;">PARTICULARS OF ACCIDENT</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>DATE OF ACCIDENT</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>TIME OF ACCIDENT</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>PLACE OF ACCIDENT</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>LOCATION OF ACCIDENT</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>TYPE OF INJURY</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>NATURE OF INJURY</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>PARTS OF BODY INJURED</b></td></tr></th>';
//    $accidentreporttable=$accidentreporttable."<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$employeedetails[0][1]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$employeedetails[0][11]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$employeedetails[0][2]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$employeedetails[0][5]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$employeedetails[0][3]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$employeedetails[0][14]."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$employeedetails[0][10]."</td></tr></table>";
// TEAM REPORT
    if($employeedetails[0][2]!=null){
        $URE_placeaccidtxt='';
        $URE_reason_msg =explode("\n", $employeedetails[0][2]);
        $length=count($URE_reason_msg);
        for($i=0;$i<=$length;$i++){
            $URE_placeaccidtxt.=$URE_reason_msg[$i];
            if($i!=$length)
                $URE_placeaccidtxt.='<br>';
        }
    }
    else{
        $URE_placeaccidtxt=null;
    }

 //ACCIDENT REPORT TABLE
    $accidentreporttable='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;">
<caption style="caption-side: left;font-weight: bold;">PARTICULARS OF ACCIDENT</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/>
<tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>DATE OF ACCIDENT</td><td style=width="250">'.$employeedetails[0][1].'</td>
<td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>TIME OF ACCIDENT</td><td style=width="250">'.$employeedetails[0][11].' </td>
</tr><tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>PLACE OF ACCIDENT</td><td width="250">'.$URE_placeaccidtxt.'</td>
<td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>LOCATION OF ACCIDENT</td><td width="250">'.$employeedetails[0][5].'</td>
</tr><tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>TYPE OF INJURY</td><td style=width="250" colspan=3>'.$employeedetails[0][3].'</td></tr>
<tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>NATURE OF INJURY</td><td width="250">'.$employeedetails[0][14].'</td>
<td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>PARTS OF BODY INJURED</td><td width="250">'.$employeedetails[0][10].'</td>
</tr></table>';

////MACHINERY INVOLVED(IF ANY) TABLE
    $machineryreporttable='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;"><caption style="caption-side: left;font-weight: bold;">MACHINERY INVOLVED(IF ANY)</caption><tr><td width="250"  style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>TYPE OF MACHINERY</td><td width="250">'.$employeedetails[0][12].'</td>
    <td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>LM NO</td><td width="250">'.$employeedetails[0][13].' </td></tr><tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>NAME OF OPERATOR</td><td width="250" colspan=3>'.$employeedetails[0][14].'</td></tr></table>';


//PARTICULARS OF INJURED TABLE

   if($employeedetails[0][17]!=null){
        $URE_addresstxt='';
        $URE_reason_msg =explode("\n", $employeedetails[0][17]);
        $length=count($URE_reason_msg);
        for($i=0;$i<=$length;$i++){
            $URE_addresstxt.=$URE_reason_msg[$i];
            if($i!=$length)
                $URE_addresstxt.='<br>';
        }
    }
    else{
        $URE_addresstxt=null;
    }
    $injuredreporttable='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;"><caption style="caption-side: left;font-weight: bold;">PARTICULARS OF INJURED</caption>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>NAME</td><td width="250">'.$employeedetails[0][0].'</td><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px >AGE</td><td width="250">'.$employeedetails[0][16].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>ADDRESS OF INJURED</td><td width="250" colspan=3>'.$URE_addresstxt.'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px >NRIC NO</td><td width="250">'.$employeedetails[0][18].'</td><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px >FIN NO</td><td width="250">'.$employeedetails[0][19].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px >WORK PERMIT NO</td><td width="250">'.$employeedetails[0][20].'</td><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>PASSPORT NO</td><td width="250">'.$employeedetails[0][21].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px >NATIONALITY</td><td width="250">'.$employeedetails[0][22].'</td><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>SEX</td><td width="250">'.$employeedetails[0][23].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>DATE OF BIRTH</td><td width="250">'.$employeedetails[0][24].'</td><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>MARITAL STATUS</td><td width="250">'.$employeedetails[0][25].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px >DESIGNATION</td><td width="250">'.$employeedetails[0][26].'</td><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>LENGTH OF SERVICE</td><td width="250">'.$employeedetails[0][27].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px colspan=3>WAS BRIEFLY CARRIED OUT BEFORE WORK COMMENCEMENT</td><td>'.$employeedetails[0][28].'</td></tr>
               </table>';

//DESCRIPTION OF ACCIDENT TABLE
   if($employeedetails[0][6]!=null){
        $URE_reasontxt='';
        $URE_reason_msg =explode("\n", $employeedetails[0][6]);
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

  //DESCRIPTION TABLE
   $descriptionreporttable='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;"><caption style="caption-side: left;font-weight: bold;">DESCRIPTION OF ACCIDENT</caption><tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>DESCRIPTION OF ACCIDENT</td></tr>
        <tr><td width="250">'.$URE_reasontxt.'</td></tr></table>';

    // final table start
    if(($employeedetails[0][12]!='')&&($employeedetails[0][13]!='')&&($employeedetails[0][14]!='')){
        $reportheadername='INCIDENT INVESTIGATION REPORT FOR '.$employeedetails[0][0];
        $finaltable='<html><body><table><tr><td style="text-align: center;"><div><img id=imglogo src="image/LOGO.png"/></div></td></tr><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">'.$reportheadername.'</div></h2></td></tr><br><br><br><tr><td>'.$accidentreporttable.'</td></tr><br><br><tr><td>'.$machineryreporttable.'</td></tr><br><br><tr><td>'.$injuredreporttable.'</td></tr><br><br><tr><td>'.$descriptionreporttable.'</td></tr></table></body></html>';
    }
    else{
        $reportheadername='INCIDENT INVESTIGATION REPORT FOR '.$employeedetails[0][0];
        $finaltable='<html><body><table><tr><td style="text-align: center;"><div><img id=imglogo src="image/LOGO.png"/></div></td></tr><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">'.$reportheadername.'</div></h2></td></tr><br><br><br><tr><td>'.$accidentreporttable.'</td></tr><br><br><br><tr><td>'.$injuredreporttable.'</td></tr><br><br><br><tr><td>'.$descriptionreporttable.'</td></tr></table></body></html>';
    }
  //MPDF FUNCTION
    $dir1 = 'Phpfiles/';
    foreach(glob($dir1.'*.*') as $v){
        unlink($v);
    }
    $mpdf=new mPDF('utf-8','A4');
    $mpdf->debug=true;
//    $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">LIH MING CONSTRUCTION PTE LTD</div></h3>', 'O', true);
    $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
    $mpdf->WriteHTML($finaltable);
    $reportpdf=$mpdf->Output('Phpfiles/'.$reportheadername.'.pdf','f');
    echo 'Phpfiles/'.$reportheadername.'.pdf';

}
//FUNCTION FOR UPDATE PART
elseif($_REQUEST['option']=='SAVE'){
    $ASU_SRC_UPD_ardid=$_POST["radioid"];
    $dateofaccident=$_POST['acc_tb_dateofaccident'];
    $timeofaccident=$_POST['acc_tb_timeofaccident'];
    $placeofaccident=$_POST['acc_tb_placeofacc'];
    $locationofaccident=$_POST['acc_tb_locofacc'];
    $typeofinjury=$_POST['acc_tb_typeofinju'];
    $natureofinjury=$_POST['acc_tb_natureofinju'];
    $partsofinjured=$_POST['acc_tb_partsofbody'];
    $typeofmachinery=$_POST['acc_tb_typeofmachinery'];
    $lmno=$_POST['acc_tb_lmno'];
    $nameofoperator=$_POST['acc_tb_nameofoperator'];
    $name=$_POST['acc_tb_name'];
    $age=$_POST['acc_tb_age'];
    $addrssofinjured=$_POST['acc_ta_adrs'];
    $addrssofinjured=$con->real_escape_string($addrssofinjured);
    $nricno=$_POST['acc_tb_nric'];
    $finno=$_POST['acc_tb_fin'];
    $workspermit=$_POST['acc_tb_workpermit'];
    $passportno=$_POST['acc_tb_passportno'];
    $nationality=$_POST['acc_tb_nationality'];
    $gender=$_POST['sex'];
    $dob=$_POST['acc_tb_dob'];
    $dob = date('Y-m-d',strtotime($dob));
    $maritalstatus=$_POST['acc_tb_maritalstatus'];
    $designation=$_POST['acc_tb_des'];
    $lengthofservice=$_POST['acc_tb_length'];
    $commens=$_POST['work'];
    $description=$con->real_escape_string($_POST['acc_ta_description']);
    $dateofaccident = date('Y-m-d',strtotime($dateofaccident));
    if($gender=='male')
    {
        $gender='Male';
    }
    elseif($gender=='female')
    {
        $gender='Female';
    }
    if($commens=='yes')
    {
        $commens='Yes';
    }
    elseif($commens=='no')
    {
        $commens='No';
    }
//    echo "CALL SP_INSERT_UPDATE_ACCIDENT_DETAILS(2,'$ASU_SRC_UPD_ardid','$dateofaccident','$placeofaccident','$typeofinjury','$natureofinjury','$timeofaccident','$locationofaccident',
//    '$partsofinjured','$typeofmachinery','$lmno','$nameofoperator','$name',$age,'$addrssofinjured','$nricno','$finno',$workspermit,'$passportno',
//    '$nationality','$gender','$dob','$maritalstatus','$designation','$lengthofservice','$commens','$description','$UserStamp',@SUCCESS_FLAG)";
//
    $sqlquery="CALL SP_INSERT_UPDATE_ACCIDENT_DETAILS(2,'$ASU_SRC_UPD_ardid','$dateofaccident','$placeofaccident','$typeofinjury','$natureofinjury','$timeofaccident','$locationofaccident',
    '$partsofinjured','$typeofmachinery','$lmno','$nameofoperator','$name',$age,'$addrssofinjured','$nricno','$finno',$workspermit,'$passportno',
    '$nationality','$gender','$dob','$maritalstatus','$designation','$lengthofservice','$commens','$description','$UserStamp',@SUCCESS_FLAG)";
    $result = $con->query($sqlquery);
    if(!$result){
        die("CALL failed: (" . $con->errno . ") " . $con->error);
    }
    $select = $con->query('SELECT @SUCCESS_FLAG');
    $result = $select->fetch_assoc();
    $flag= $result['@SUCCESS_FLAG'];
    echo $flag;
}

?>
