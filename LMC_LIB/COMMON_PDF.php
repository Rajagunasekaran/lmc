<?php
require_once('mpdf571/mpdf571/mpdf.php');
include "CONNECTION.php";
include "GET_USERSTAMP.php";
$USERSTAMP=$UserStamp;
//FLAG VALUE
$flag= $_GET['flag'];
$inputValOne=$_GET['inputValOne'];
$inputValTwo=$_GET['inputValTwo'];
$inputValThree=$_GET['inputValThree'];
$inputValFour=$_GET['inputValFour'];
$inputValFive=$_GET['inputValFive'];
$arrcall=array(3=>"CALL SP_TS_AUDIT_HISTORY((select ULD_ID from LMC_USER_LOGIN_DETAILS where ULD_WORKER_NAME='$inputValOne'),'$USERSTAMP',@TEMP_TABLE)");

//ALIGNMENT CENTER OR LEFT
$arrAlignment=array(1=>array('left','center','center','center','center','left','center','left','center'),
    3=>array('left','left','center'));



//APPEND TABLE HEADER
$arrHeader=array(1=>array('EMPLOYEE NAME','ROLE','REC VER','JOIN DATE','TERMINATION DATE','REASON OF TERMINATION','EMP TYPE','USERSTAMP','TIMESTAMP'),
   3=>array('HISTORY','USERSTAMP','TIMESTAMP'));
//TABLE HEADER WIDTH
$arrHeaderWidth=array(1=>array(20,20,20,150,20,20,20,20,20),
3=>array(400,180,180));
//TABLE WIDTH

$arrTableWidth=array(1=>1500,3=>1800);
//script to execute call query
if($flag==3){
    $result = $con->query($arrcall[$flag]);
    if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
    $select = $con->query("SELECT @TEMP_TABLE");
    $result = $select->fetch_assoc();
    $temp_table= $result['@TEMP_TABLE'];
}
//APPEND QUERY
$arrQuery=array( 1=>"SELECT AE.EMPLOYEE_NAME,RC.RC_NAME,UA.UA_REC_VER,DATE_FORMAT(UA.UA_JOIN_DATE,'%d-%m-%Y') AS UA_JOIN_DATE,DATE_FORMAT(UA.UA_END_DATE,'%d-%m-%Y') AS UA_END_DATE,UA.UA_REASON,URC1.URC_DATA,UA.UA_USERSTAMP,DATE_FORMAT(CONVERT_TZ(UA.UA_TIMESTAMP,'+00:00','+08:00'),'%d-%m-%Y %T') AS UA_TIMESTAMP FROM LMC_USER_LOGIN_DETAILS ULD,LMC_ROLE_CREATION RC,LMC_USER_ACCESS UA,LMC_USER_RIGHTS_CONFIGURATION URC,LMC_USER_RIGHTS_CONFIGURATION URC1,VW_TS_ALL_EMPLOYEE_DETAILS AE WHERE UA.UA_EMP_TYPE=URC1.URC_ID AND URC.URC_ID=RC.URC_ID AND ULD.ULD_ID=UA.ULD_ID AND UA.RC_ID=RC.RC_ID AND ULD.ULD_ID=AE.ULD_ID ORDER BY EMPLOYEE_NAME",
                 3=>"SELECT EVENT_TYPE,TABLE_NAME,TH_OLD_VALUE,TH_NEW_VALUE,TH_USERSTAMP,DATE_FORMAT(TH_TIMESTAMP, '%d-%m-%Y %h:%m:%s') AS T_TIMESTAMP FROM $temp_table   ORDER BY TH_TIMESTAMP DESC ");
//start to fetch select query
$stmtExecute= mysqli_query($con,$arrQuery[$flag]);
$ure_values=array();
$final_values=array();

$appendTable="<br><br><table  border=1 style='border-collapse: collapse' width='".$arrTableWidth[$flag]."px'><sethtmlpageheader name='header' page='all' value='on' show-this-page='1'/><thead><tr>";

$arrHeaderLength = count($arrHeader[$flag]);
for($h = 0; $h < $arrHeaderLength; $h++) {
    $appendTable .="<td align='center' color='white' bgcolor='#6495ed' width='".$arrHeaderWidth[$flag][$h]."px'>".$arrHeader[$flag][$h]."</td >";
}
$appendTable .='</thead></tr>';

if($flag==3)
    $arrHeaderLength=6;

$counter = 0;
$total_rows = mysqli_num_rows($stmtExecute);
while($row=mysqli_fetch_array($stmtExecute)){
    $appendTable .='<tr>';
    for($x = 0; $x < $arrHeaderLength; $x++) {
        if(($flag == 3) &&($x == 0)){
            $appendTable .="<td >UPDATION/DELETION: ".$row[$x]."<br><br>TABLE NAME: ".$row[$x+1]."<br><br>OLD VALUE :".htmlspecialchars(str_replace(',', ' , ', $row[$x+2]))."<br><br>NEW VALUE :".htmlspecialchars($row[$x+3])."</td>";
        }else if((($flag == 3) && ($x == 4))||(($flag == 3) && ($x ==5))){
            $appendTable .="<td align='".$arrAlignment[$flag][$x-3]."'>".$row[$x]."</td>";}
        else if($flag != 3){
            $appendTable .="<td align='".$arrAlignment[$flag][$x]."'>".$row[$x]."</td>";
        }}
    $appendTable .='</tr>';
}
$appendTable .='</tbody></table>';
//DROP TEMP TABLE
$drop_query="DROP TABLE $temp_table ";
if($flag==3)
    mysqli_query($con,$drop_query);
//GENERATE PDF
$pageWidth=$arrTableWidth[$flag]/4;
$mpdf=new mPDF('utf-8', array($pageWidth,236));
$mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;">'.$_GET['title'].'</div></h3>', 'O', true);
$mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
$mpdf->WriteHTML($appendTable);
$outputpdf=$mpdf->Output($_GET['title'].'.pdf','d');
?>
<!--
1*******USER DETAIL SEARCH
3*******TICKLER HISTORY
-->