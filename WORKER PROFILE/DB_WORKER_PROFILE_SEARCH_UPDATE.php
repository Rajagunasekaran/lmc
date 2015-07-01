<?php
error_reporting(0);
include("../PHPMailer/class.phpmailer.php");
include("../PHPMailer/class.smtp.php");
include "../LMC_LIB/CONNECTION.php";
include "../LMC_LIB/COMMON.php";
$dir=dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR;
date_default_timezone_set('Asia/Singapore');
$activeempname=mysqli_query($con,"SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$UserStamp'");
if($row=mysqli_fetch_array($activeempname))
{
    $activeemp=$row["ULD_ID"];
}

if($_REQUEST['option']=='COMMON_DATA')
{
//FILE NAME
    $filename=mysqli_query($con,"SELECT ULD_DOC_FILE_NAME FROM LMC_USER_LOGIN_DETAILS ORDER BY ULD_DOC_FILE_NAME ASC");
    $attachedfilename=array();
    $final_filearry=array();
    while($row=mysqli_fetch_array($filename)){
        $attachedfilename=array();
        $attachedfilename=explode('/',$row["ULD_DOC_FILE_NAME"]);
        for($i=0;$i<count($attachedfilename);$i++){
            $final_filearry[]=$attachedfilename[$i];
        }
        $attachedfileid=$row['ULD_ID'];
    }
    $errormsg=get_error_msg('37,40,41,42,46,48,56,83,133,139,141,142,146');

    $select_role=mysqli_query($con,"select URC_DATA from VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS where ULD_USERNAME='$UserStamp'");
    if($row=mysqli_fetch_array($select_role)){
        $rolename=$row["URC_DATA"];
    }
    $select_name=mysqli_query($con,"SELECT ULD_ID,ULD_WORKER_NAME FROM LMC_USER_LOGIN_DETAILS ORDER BY ULD_WORKER_NAME ASC");
    while($row=mysqli_fetch_array($select_name)){
        $empname[]=array($row["ULD_ID"],$row["ULD_WORKER_NAME"]);
    }
    $rcname_result=mysqli_query($con,"SELECT * FROM LMC_ROLE_CREATION ORDER BY RC_NAME");
    while($row=mysqli_fetch_array($rcname_result)){
        $rcname_array[]=array($row["RC_NAME"],$row["RC_ID"]);
    }
    $final_values=array($final_filearry,$errormsg,$rolename,$empname,$rcname_array);
    echo json_encode($final_values);
}

if($_REQUEST['option']=='search_data')
{
    $rolecreation_result = mysqli_query($con,"SELECT * FROM LMC_ROLE_CREATION ORDER BY RC_NAME");
    while($row=mysqli_fetch_array($rolecreation_result)){
        $rcname_array[]=array($row["RC_NAME"],$row["RC_ID"]);
    }
    $select_data=mysqli_query($con,"SELECT DISTINCT A.ULD_ID,A.ULD_USERNAME AS USERNAME,A.ULD_PASSWORD,B.RC_NAME,A.ULD_WORKER_NAME,A.ULD_WORKER_NO,A.ULD_DOC_FOLDER_ID,A.ULD_DOC_FILE_NAME,A.ULD_EMAIL_ID,A.ULD_ADDRESS,A.ULD_NRIC_NO,A.ULD_MOBILE_NUMBER,A1.ULD_USERNAME AS USERSTAMP,DATE_FORMAT(A.ULD_TIMESTAMP,'%d-%m-%Y %T') AS ULD_TIMESTAMP
FROM LMC_USER_LOGIN_DETAILS A LEFT JOIN LMC_USER_LOGIN_DETAILS A1 ON A.ULD_USERSTAMP=A1.ULD_ID
INNER JOIN LMC_ROLE_CREATION B ON A.RC_ID=B.RC_ID GROUP BY A.ULD_WORKER_NAME ORDER BY A.ULD_WORKER_NAME");
        while($row=mysqli_fetch_array($select_data)){
            $wrp_name=$row["ULD_WORKER_NAME"];
            $wrp_no=$row["ULD_WORKER_NO"];
            $wrp_uname=$row["USERNAME"];
            $wrp_pswd=$row["ULD_PASSWORD"];
            $wrp_pswd=base64_decode($wrp_pswd);
            $wrp_rcid=$row["RC_NAME"];
            $wrp_filename=$row["ULD_DOC_FILE_NAME"];
            $wrp_rowid=$row['ULD_ID'];
            $wrp_folderid=$row['ULD_DOC_FOLDER_ID'];
            $wrp_emailid=$row['ULD_EMAIL_ID'];
            $wrp_address=$row['ULD_ADDRESS'];
            $wrp_nricno=$row['ULD_NRIC_NO'];
            $wrp_mobno=$row['ULD_MOBILE_NUMBER'];
            $wrp_userstamp=$row['USERSTAMP'];
            $wrp_timestamp=$row['ULD_TIMESTAMP'];
            $ure_values=array('folder_id'=>$wrp_folderid,'wrp_name' =>$wrp_name,'wrp_no' =>$wrp_no,'wrp_rcid' =>$wrp_rcid,'wrp_filename'=>$wrp_filename,'wrp_rowid'=>$wrp_rowid,'wrp_emailid'=>$wrp_emailid,'wrp_address'=>$wrp_address,'wrp_nricno'=>$wrp_nricno,'wrp_mobno'=>$wrp_mobno,'wrp_uname'=>$wrp_uname,'wrp_pswd'=>$wrp_pswd,'wrp_userstamp'=>$wrp_userstamp,'wrp_timestamp'=>$wrp_timestamp);
            $final_values[]=$ure_values;
        }
    $finalvalue=array($final_values,$rcname_array);
    echo JSON_ENCODE($finalvalue);
}
if($_REQUEST['option']=='emailcheck'){
    $empname=$_REQUEST['empid'];
    $emilid=$_REQUEST['emailid'];
    $existmailquery=mysqli_query($con,"SELECT * FROM LMC_USER_LOGIN_DETAILS WHERE ULD_EMAIL_ID='$emilid' AND ULD_ID!=$empname");
    $row=mysqli_num_rows($existmailquery);
    echo $row;
}
if($_REQUEST['option']=='worknocheck'){
    $empname=$_REQUEST['empid'];
    $workerno=$_REQUEST['workerno'];
    $existmailquery=mysqli_query($con,"SELECT * FROM LMC_USER_LOGIN_DETAILS WHERE ULD_WORKER_NO='$workerno' AND ULD_ID!=$empname");
    $row=mysqli_num_rows($existmailquery);
    echo $row;
}
if($_REQUEST['option']=="logincheck"){
    $empname=$_REQUEST['empid'];
    $username=$_REQUEST['loginname'];
    $sql_result= mysqli_query($con,"SELECT * FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$username' AND ULD_ID!=$empname");
    $row=mysqli_num_rows($sql_result);
    echo $row;
}
if($_REQUEST['option']=='UPDATE'){
    $oldfilename=$_REQUEST['oldfilename'];
    $emprowid=$_POST['wrsu_tb_rowid'];
    $empname=$_POST['wrsu_tb_name'];
    $empnum=$_POST['wrsu_tb_number'];
    $logid=$_POST['wrsu_tb_loginid'];
    $psword=$_POST['wrsu_tb_pword'];
    $psword = $con->real_escape_string($psword);
    $pasword=  base64_encode($psword);
    $role=$_REQUEST['rolechecked'];
    $address1=$_POST['wrsu_ta_address'];
    $address= $con->real_escape_string($address1);
    $nricno=$_POST["wrsu_tb_nric"];
    $emailid=$_POST['wrsu_tb_emailid'];
    $contactno=$_POST['wrsu_tb_permobile'];
    $selectempname=mysqli_query($con,"SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_WORKER_NAME='$empname'");
    if($row=mysqli_fetch_array($selectempname)){
        $selectemp=$row["ULD_ID"];
    }

    $sql="SELECT ULD_WORKER_NAME,ULD_DOC_FOLDER_ID,ULD_DOC_FILE_NAME FROM LMC_USER_LOGIN_DETAILS WHERE ULD_ID='$emprowid'";
    $sql_result= mysqli_query($con,$sql);
    if($row=mysqli_fetch_array($sql_result)){
        $empfolderid=$row["ULD_DOC_FOLDER_ID"];
        $workername=$row["ULD_WORKER_NAME"];
        $empfilename=$row["ULD_DOC_FILE_NAME"];
    }

    if($oldfilename=='undefined')
    {
        $sql_filename="SELECT ULD_DOC_FILE_NAME FROM LMC_USER_LOGIN_DETAILS WHERE ULD_ID='$emprowid'";
        $sql_result= mysqli_query($con,$sql_filename);
        if($row=mysqli_fetch_array($sql_result)){
            $oldfilename=$row["ULD_DOC_FILE_NAME"];
        }
    }
    $parent_attach_folder_name=get_docfolder_id();
    $currentdate=date("d-m-Y");
    $currentdate=str_replace('-','',$currentdate);
    $attch_file_folder=$dir.$parent_attach_folder_name.DIRECTORY_SEPARATOR.$empfolderid.DIRECTORY_SEPARATOR;
    $uploadcount=$_REQUEST['upload_count'];
    for($x=1;$x<=$uploadcount;$x++)
    {
        if($_FILES['upload_filename'.$x]['name']!=''){
            $attach_file_name=$workername.'_'.$currentdate.'_'.date('His').'_'.$_FILES['upload_filename'.$x]['name'];
            move_uploaded_file($_FILES['upload_filename'.$x]['tmp_name'],$attch_file_folder.$attach_file_name);
            $upload_file_array[]=$attach_file_name;//$_FILES['upload_filename'.$x]['name'];
        }
    }
//    print_r($upload_file_array);
//    exit;
    $upload_filename='';
    for($y=0;$y<count($upload_file_array);$y++){
        if($upload_file_array[$y]!=''){
            if($y==0){
                $upload_filename= $upload_file_array[$y];
            }
            else{
                $upload_filename=$upload_filename.'/'.$upload_file_array[$y];
            }
        }
    }

    if($oldfilename!='' && $upload_filename!=''){
        $upload_filename=$oldfilename.'/'.$upload_filename;
        $fileflag=1;
    }
    elseif($upload_filename=='' && $oldfilename!=''){
        $upload_filename=$oldfilename;
        $fileflag=1;
    }
    elseif($oldfilename=='' && $upload_filename!=''){
        $upload_filename=$upload_filename;
        $fileflag=1;
    }
    elseif($oldfilename=='' && $upload_filename==''){
        $upload_filename='null';
        $fileflag=0;
    }
    $uploadfile=$con->real_escape_string($upload_filename);
    $con->autocommit(false);
    if($fileflag==1){
        $update_query="UPDATE LMC_USER_LOGIN_DETAILS SET ULD_USERNAME='$logid',ULD_PASSWORD='$pasword',RC_ID='$role',ULD_WORKER_NAME='$empname',ULD_WORKER_NO='$empnum',ULD_DOC_FILE_NAME='$uploadfile',ULD_EMAIL_ID='$emailid',ULD_ADDRESS='$address',ULD_NRIC_NO='$nricno',ULD_MOBILE_NUMBER='$contactno',ULD_USERSTAMP='$activeemp' WHERE ULD_ID='$emprowid'";
    }
    elseif($fileflag==0){
        $update_query="UPDATE LMC_USER_LOGIN_DETAILS SET ULD_USERNAME='$logid',ULD_PASSWORD='$pasword',RC_ID='$role',ULD_WORKER_NAME='$empname',ULD_WORKER_NO='$empnum',ULD_DOC_FILE_NAME=$uploadfile,ULD_EMAIL_ID='$emailid',ULD_ADDRESS='$address',ULD_NRIC_NO='$nricno',ULD_MOBILE_NUMBER='$contactno',ULD_USERSTAMP='$activeemp' WHERE ULD_ID='$emprowid'";
    }
    if (!mysqli_query($con,$update_query)) {
        $flag=0;
        die('Error: ' . mysqli_error($con));
    }
    else{
        $flag=1;
    }
    if($flag==1){
        $select_cc=mysqli_query($con,"SELECT * FROM LMC_USER_LOGIN_DETAILS WHERE ULD_ID=1");
        if($row=mysqli_fetch_array($select_cc)){
            $ccaddress=$row["URC_DATA"];
        }
        $select_host=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=14");
        if($row=mysqli_fetch_array($select_host)){
            $host=$row["URC_DATA"];
        }
        $select_username=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=11");
        if($row=mysqli_fetch_array($select_username)){
            $username=$row["URC_DATA"];
        }
        $select_password=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=12");
        if($row=mysqli_fetch_array($select_password)){
            $password=$row["URC_DATA"];
        }
        $select_smtpsecure=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=13");
        if($row=mysqli_fetch_array($select_smtpsecure)){
            $smtpsecure=$row["URC_DATA"];
        }
        $select_template="SELECT * FROM LMC_EMAIL_TEMPLATE_DETAILS WHERE ET_ID=5";
        $select_template_rs=mysqli_query($con,$select_template);
        if($row=mysqli_fetch_array($select_template_rs)){
            $mail_subject=$row["ETD_EMAIL_SUBJECT"];
            $body=$row["ETD_EMAIL_BODY"];
        }

//STRING REPLACE FUNCTION
        $emp_email_body;
        $body_msg =explode("^", $body);
        $length=count($body_msg);
        for($i=0;$i<$length;$i++){
            $emp_email_body.=$body_msg[$i].'<br><br>';
        }
        $address1=htmlspecialchars($address1);
        $comment_address =explode("\n", $address1);
        $commnet_adds_length=count($comment_address);
        for($i=0;$i<$commnet_adds_length;$i++){
            $comment_msg_add.=$comment_address[$i].'<br>';
        }
        $replace= array("[USERNAME]","PERSONAL DETAILS:","[UNAME]","[PWD]","[WNAME]","[WNO]","[NRICNO]","[EMAILID]","[MOBNO]","[ADDRESS]");
        $str_replaced  = array($logid,'<b>'."PERSONAL DETAILS:".'</b>',$logid,$psword,$empname,$empnum,$nricno,$emailid,$contactno,$comment_msg_add);
        $newphrase = str_replace($replace, $str_replaced, $emp_email_body);
        $final_message=$newphrase;
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = $smtpsecure;
        $mail->Port=587;
        $mail->FromName = 'LMC';
        $mail->addAddress($emailid);
//        $mail->addCC($ccaddress);
        $mail->WordWrap = 50;
        $mail->isHTML(true);
        $mail->Subject = $mail_subject;
        $mail->Body = $final_message;
        $mail->send();
        $con->commit();
    }
    echo $flag;

}