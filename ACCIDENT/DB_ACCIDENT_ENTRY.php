<?php
error_reporting(0);
include "../LMC_LIB/CONNECTION.php";
include "../LMC_LIB/GET_USERSTAMP.php";
include "../LMC_LIB/COMMON.php";
require_once('../mpdf571/mpdf571/mpdf.php');
require_once("../PHPMailer/class.phpmailer.php");
require_once("../PHPMailer/class.smtp.php");
date_default_timezone_set('Asia/Singapore');
if($_REQUEST['option']=='COMMON_DATA')
{
    //ERRPOR MESSAGE
    $errormsg[]=get_error_msg('3,6,7,143');
    echo JSON_encode($errormsg);
}
elseif($_REQUEST['option']=='SAVE'){
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
    $addrssofinjured=$con->real_escape_string($_POST['acc_ta_adrs']);
    $addrssofinjured1=$_POST['acc_ta_adrs'];
    $nricno=$_POST['acc_tb_nric'];
    $finno=$_POST['acc_tb_fin'];
    $workspermit=$_POST['acc_tb_workpermit'];
    $passportno=$_POST['acc_tb_passportno'];
    $nationality=$_POST['acc_tb_nationality'];
    $gender=$_POST['sex'];
    $dob=$_POST['acc_tb_dob'];
    $maritalstatus=$_POST['acc_tb_maritalstatus'];
    $designation=$_POST['acc_tb_des'];
    $lengthofservice=$_POST['acc_tb_length'];
    $commens=$_POST['work'];
    $description=$con->real_escape_string($_POST['acc_ta_description']);
    $description1=$_POST['acc_ta_description'];
    $dateofaccident = date('Y-m-d',strtotime($dateofaccident));
    $dateofbirth = date('Y-m-d',strtotime($dob));
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
//ACCIDENT REPORT TABLE
    $accidentreporttable='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;">
    <caption style="caption-side: left;font-weight: bold;">PARTICULARS OF ACCIDENT</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/>
    <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>DATE OF ACCIDENT</td><td style=width="250">'.$dateofaccident.'</td>
    <td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>TIME OF ACCIDENT</td><td style=width="250">'.$timeofaccident.' </td>
    </tr><tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>PLACE OF ACCIDENT</td><td width="250">'.$placeofaccident.'</td>
    <td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>LOCATION OF ACCIDENT</td><td width="250">'.$locationofaccident.'</td>
    </tr><tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>TYPE OF INJURY</td><td style=width="250" colspan=3>'.$typeofinjury.'</td></tr>
    <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>NATURE OF INJURY</td><td width="250">'.$natureofinjury.'</td>
    <td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>PARTS OF BODY INJURED</td><td width="250">'.$partsofinjured.'</td>
    </tr></table>';

//MACHINERY INVOLVED(IF ANY) TABLE
    $machineryreporttable='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;"><caption style="caption-side: left;font-weight: bold;">MACHINERY INVOLVED(IF ANY)</caption><tr><td width="250"  style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>TYPE OF MACHINERY</td><td width="250">'.$typeofmachinery.'</td>
    <td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>LM NO</td><td width="250">'.$lmno.' </td></tr><tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>NAME OF OPERATOR</td><td width="250" colspan=3>'.$nameofoperator.'</td></tr></table>';

//PARTICULARS OF INJURED TABLE
    if($addrssofinjured1!=''){
        $UR_addresstxt='';
        $URE_reason_msg =explode("\n", $addrssofinjured1);
        $length=count($URE_reason_msg);
        for($i=0;$i<=$length;$i++){
            $UR_addresstxt.=$URE_reason_msg[$i];
            if($i!=$length)
                $UR_addresstxt.='<br>';
        }
    }
    else{
        $UR_addresstxt='';
    }
    $injuredreporttable='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;"><caption style="caption-side: left;font-weight: bold;">PARTICULARS OF INJURED</caption>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>NAME</td><td width="250">'.$name.'</td><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px >AGE</td><td width="250">'.$age.'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>ADDRESS OF INJURED</td><td width="250" colspan=3>'.$UR_addresstxt.'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px >NRIC NO</td><td width="250">'.$nricno.'</td><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px >FIN NO</td><td width="250">'.$finno.'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px >WORK PERMIT NO</td><td width="250">'.$workspermit.'</td><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>PASSPORT NO</td><td width="250">'.$passportno.'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px >NATIONALITY</td><td width="250">'.$nationality.'</td><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>SEX</td><td width="250">'.$gender.'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>DATE OF BIRTH</td><td width="250">'.$dateofbirth.'</td><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>MARITAL STATUS</td><td width="250">'.$maritalstatus.'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px >DESIGNATION</td><td width="250">'.$designation.'</td><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>LENGTH OF SERVICE</td><td width="250">'.$lengthofservice.'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px colspan=3>WAS BRIEFLY CARRIED OUT BEFORE WORK COMMENCEMENT</td><td>'.$commens.'</td></tr>
       </table>';

//DESCRIPTION OF ACCIDENT TABLE
    if($description1!=''){
        $UR_reasontxt='';
        $URE_reason_msg =explode("\n", $description1);
        $length=count($URE_reason_msg);
        for($i=0;$i<=$length;$i++){
            $UR_reasontxt.=$URE_reason_msg[$i];
            if($i!=$length)
                $UR_reasontxt.='<br>';
        }
    }
    else{
        $UR_reasontxt='';
    }
    $descriptionreporttable='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;"><caption style="caption-side: left;font-weight: bold;">DESCRIPTION OF ACCIDENT</caption><tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>DESCRIPTION OF ACCIDENT</td></tr>
        <tr><td width="250">'.$UR_reasontxt.'</td></tr></table>';

// FINAL TABLE
    if(($typeofmachinery!='')||($lmno!='')||($nameofoperator!='')){
        $reportheadername='INCIDENT INVESTIGATION REPORT FOR '.$name;
        $finaltable= '<html><body><table><tr><td style="text-align: center;"><div><img id=imglogo src="../image/LOGO.png"/></div></td></tr><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">' .$reportheadername.'</div></h2></td></tr><br><br><br><tr><td>'.$accidentreporttable.'</td></tr><br><br><tr><td>'.$machineryreporttable.'</td></tr><br><br><tr><td>'.$injuredreporttable.'</td></tr><br><br><tr><td>'.$descriptionreporttable.'</td></tr></table></body></html>';
    }
    else{
        $reportheadername='INCIDENT INVESTIGATION REPORT FOR '.$name;
        $finaltable= '<html><body><table><tr><td style="text-align: center;"><div><img id=imglogo src="../image/LOGO.png"/></div></td></tr><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">' .$reportheadername.'</div></h2></td></tr><br><br><br><tr><td>'.$accidentreporttable.'</td></tr><br><br><br><tr><td>'.$injuredreporttable.'</td></tr><br><br><br><tr><td>'.$descriptionreporttable.'</td></tr></table></body></html>';
    }
    $sqlquery="CALL SP_INSERT_UPDATE_ACCIDENT_DETAILS(1,'','$dateofaccident','$placeofaccident','$typeofinjury','$natureofinjury','$timeofaccident','$locationofaccident',
    '$partsofinjured','$typeofmachinery','$lmno','$nameofoperator','$name',$age,'$addrssofinjured','$nricno','$finno',$workspermit,'$passportno',
    '$nationality','$gender','$dateofbirth','$maritalstatus','$designation','$lengthofservice','$commens','$description','$UserStamp',@SUCCESS_FLAG)";
    $result = $con->query($sqlquery);
    if(!$result){
        $flag=0;
        die("CALL failed: (" . $con->errno . ") " . $con->error);
    }
    $select = $con->query('SELECT @SUCCESS_FLAG');
    $result = $select->fetch_assoc();
    $flag= $result['@SUCCESS_FLAG'];
    if($flag==1){
        $select_emailtemp=mysqli_query($con,"SELECT ETD_EMAIL_SUBJECT, ETD_EMAIL_BODY FROM LMC_EMAIL_TEMPLATE_DETAILS WHERE ETD_ID=7");
        if($row=mysqli_fetch_array($select_emailtemp)){
            $sub=$row["ETD_EMAIL_SUBJECT"];
            $msgbody=$row["ETD_EMAIL_BODY"];
        }
        $emailbody = str_replace("[UNAME]",$name, $msgbody);
        Mail_part($sub,$emailbody,$finaltable,$reportheadername);
    }
    echo $flag;
}
function Mail_part($sub,$emailbody,$finaltable,$reportfilename)
{
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
        $mail->AddStringAttachment($reportpdf, $reportfilename.'.pdf');
        $mail->send();
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
}
