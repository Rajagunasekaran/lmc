<?php
error_reporting(0);
require_once("../PHPMailer/class.phpmailer.php");
require_once("../PHPMailer/class.smtp.php");
include "../LMC_LIB/CONNECTION.php";
include "../LMC_LIB/COMMON.php";
date_default_timezone_set('Asia/Singapore');
$dir=dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR;
$activeempname=mysqli_query($con,"SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$UserStamp'");
if($row=mysqli_fetch_array($activeempname))
{
    $activeemp=$row["ULD_ID"];
}
if($_REQUEST['option']=='COMMON_DATA')
{
    $rcname_result=mysqli_query($con,"SELECT * FROM LMC_ROLE_CREATION WHERE RC_NAME!='SUPER ADMIN' ORDER BY RC_NAME");
    while($row=mysqli_fetch_array($rcname_result)){
        $rcname_array[]=array($row["RC_NAME"],$row["RC_ID"]);
    }
    $errormsg=get_error_msg('37,40,42,45,48,95,133,139,141,142,146');
    $values=array($rcname_array,$errormsg);
    echo json_encode($values);
}
if($_REQUEST['option']=='logincheck'){
    $loginid=$_REQUEST['loginname'];
    $existquery=mysqli_query($con,"SELECT * FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$loginid'");
    $row=mysqli_num_rows($existquery);
    echo $row;
}
if($_REQUEST['option']=='emailcheck'){
    $emilid=$_REQUEST['emailid'];
    $existmailquery=mysqli_query($con,"SELECT * FROM LMC_USER_LOGIN_DETAILS WHERE ULD_EMAIL_ID='$emilid'");
    $row=mysqli_num_rows($existmailquery);
    echo $row;
}
if($_REQUEST['option']=='worknocheck'){
    $workerno=$_REQUEST['workerno'];
    $existmailquery=mysqli_query($con,"SELECT * FROM LMC_USER_LOGIN_DETAILS WHERE ULD_WORKER_NO='$workerno'");
    $row=mysqli_num_rows($existmailquery);
    echo $row;
}
if($_REQUEST['option']=='save'){

    $empname=$_POST['wr_tb_name'];
    $empnum=$_POST['wr_tb_number'];
    $logid=$_POST['wr_tb_loginid'];
    $paswd=$_POST['wr_tb_pword'];
    $paswd = $con->real_escape_string($paswd);
    $password= base64_encode($paswd);
    $role=$_REQUEST['rolechecked'];
    $uploadcount=$_REQUEST['upload_count'];
    $address1=$_POST['wr_ta_address'];
    $address= $con->real_escape_string($address1);
    $nricno=$_POST["wr_tb_nric"];
    $emailid=$_POST['wr_tb_emailid'];
    $contactno=$_POST['wr_tb_permobile'];
// for img folder
    $currentdate=date("d-m-Y");
    $currentdate=str_replace('-','',$currentdate);
    $subfoldername=$empname.'_'.$currentdate.'_'.date('His');
    $parent_image_folder_name=get_parentfolder_id();
    $img_folder_name=$dir.$parent_image_folder_name.DIRECTORY_SEPARATOR.$subfoldername.DIRECTORY_SEPARATOR;
    $detele_foldername=$dir.$parent_image_folder_name.DIRECTORY_SEPARATOR.$subfoldername;
    if (!file_exists($img_folder_name)) {
        $makedirimage=mkdir($img_folder_name);
        chmod($makedirimage,0777);
}
// for doc folder
    $parent_attach_folder_name=get_docfolder_id();
    $attach_sub_folder_name=$empname.'_'.$currentdate.'_'.date('His');
    $attch_file_folder=$dir.$parent_attach_folder_name.DIRECTORY_SEPARATOR.$attach_sub_folder_name.DIRECTORY_SEPARATOR;
    if (!file_exists($attch_file_folder)) {
        $makedirfile=mkdir($attch_file_folder);
        chmod($makedirfile,0777);
    }
    for($x=1;$x<=$uploadcount;$x++)
    {
        if($_FILES['upload_filename'.$x]['name']!=''){
            $attach_file_name=$empname.'_'.$currentdate.'_'.date('His').'_'.$_FILES['upload_filename'.$x]['name'];
            move_uploaded_file($_FILES['upload_filename'.$x]['tmp_name'],$attch_file_folder.$attach_file_name);
            $upload_file_array[]=$attach_file_name;//$_FILES['upload_filename'.$x]['name'];
        }
    }
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
    $uploadfile=$con->real_escape_string($upload_filename);
    $con->autocommit(false);
    if($upload_filename==''){
        $upload_filename='null';
        $insert_query="INSERT INTO LMC_USER_LOGIN_DETAILS(ULD_USERNAME,ULD_PASSWORD,RC_ID,ULD_WORKER_NAME,ULD_WORKER_NO,ULD_IMAGE_FOLDER_ID,ULD_DOC_FOLDER_ID,ULD_DOC_FILE_NAME,ULD_EMAIL_ID,ULD_ADDRESS,ULD_NRIC_NO,ULD_MOBILE_NUMBER,ULD_USERSTAMP)
        VALUES ('$logid','$password','$role','$empname','$empnum','$subfoldername','$attach_sub_folder_name',$upload_filename,'$emailid','$address','$nricno','$contactno',$activeemp)";
    }
    else{
        $insert_query="INSERT INTO LMC_USER_LOGIN_DETAILS(ULD_USERNAME,ULD_PASSWORD,RC_ID,ULD_WORKER_NAME,ULD_WORKER_NO,ULD_IMAGE_FOLDER_ID,ULD_DOC_FOLDER_ID,ULD_DOC_FILE_NAME,ULD_EMAIL_ID,ULD_ADDRESS,ULD_NRIC_NO,ULD_MOBILE_NUMBER,ULD_USERSTAMP)
        VALUES ('$logid','$password','$role','$empname','$empnum','$subfoldername','$attach_sub_folder_name','$uploadfile','$emailid','$address','$nricno','$contactno',$activeemp)";
    }
    if (!mysqli_query($con,$insert_query)) {
        $flag=0;
        $delete_attchfile = $attch_file_folder;
        foreach(glob($delete_attchfile.'*.*') as $v){
            unlink($v);
        }
        rmdir($delete_attchfile);
        rmdir($detele_foldername);
        die('Error: ' . mysqli_error($con));
    }
    else{
        $flag=1;
    }
    if($flag==1)
    {
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

        $select_template="SELECT * FROM LMC_EMAIL_TEMPLATE_DETAILS WHERE ET_ID=1";
        $select_template_rs=mysqli_query($con,$select_template);
        if($row=mysqli_fetch_array($select_template_rs)){
            $mail_subject=$row["ETD_EMAIL_SUBJECT"];
            $body=$row["ETD_EMAIL_BODY"];
        }

        $email_body;
        $body_msg =explode("^", $body);
        $length=count($body_msg);
        for($i=0;$i<$length;$i++){
            $email_body.=$body_msg[$i].'<br><br>';
        }
        $final_message = str_replace("[LOGINID]", $empname.'</b>', $email_body);

        $select_template="SELECT * FROM LMC_EMAIL_TEMPLATE_DETAILS WHERE ET_ID=2";
        $select_template_rs=mysqli_query($con,$select_template);
        if($row=mysqli_fetch_array($select_template_rs)){
            $mail_subject1=$row["ETD_EMAIL_SUBJECT"];
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
        $replace= array( "PERSONAL DETAILS:","[UNAME]","[PWD]","[WNAME]","[WNO]","[NRICNO]","[EMAILID]","[MOBNO]","[ADDRESS]");
        $str_replaced  = array('<b>'."PERSONAL DETAILS:".'</b>',$logid,$paswd,$empname,$empnum,$nricno,$emailid,$contactno,$comment_msg_add);
        $newphrase = str_replace($replace, $str_replaced, $emp_email_body);
        $final_message=$final_message.'<br>'.$newphrase;
        $mail = new PHPMailer();
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
    else if($flag==0){
        $delete_attchfile = $attch_file_folder;
        foreach(glob($delete_attchfile.'*.*') as $v){
            unlink($v);
        }
        rmdir($delete_attchfile);
        rmdir($detele_foldername);
    }
    echo $flag;
}