<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************ACCESS_RIGHTS_TERMINATE_SEARCH_UPDATE*********************************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:20/08/2014 ED:11/09/2014,TRACKER NO:81
//*********************************************************************************************************//-->
require 'PHPMailer-master/PHPMailerAutoload.php';
//use google\appengine\api\mail\Message;
//include "CONNECTION.php";
include "COMMON.php";
include "GET_USERSTAMP.php";
//include "CONFIG.php";
function getULD_ID_from_ULD_LOGINID1($ULD_LOGINID){
    global $con;
    $query="select ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID ='".$ULD_LOGINID."'";
    $result=mysqli_query($con,$query);
    while($row=mysqli_fetch_array($result)){
        $ULD_ID=$row["ULD_ID"];
    }
    return $ULD_ID;
}
if(isset($_REQUEST))
{
    $userstamp=$UserStamp;
    if($_REQUEST['option']=='TERMINATIONLB')
    {
        $active_emp=get_active_emp_id();
        echo json_encode( $active_emp);
    }
    else if($_REQUEST['option']=='REJOINLB')
    {
        $active_nonemp=get_nonactive_emp_id();
        echo json_encode($active_nonemp);
    }
    else if($_REQUEST['option']=='SEARCHLB')
    {
        $active_nonemp=get_nonactive_emp_id();
        echo json_encode($active_nonemp);
    }
    else if($_REQUEST['option']=='FETCH')
    {
        $URT_SRC_uld_id = $_REQUEST['URT_SRC_loggin'];
        $select_recver="SELECT UA_REC_VER FROM LMC_USER_ACCESS WHERE ULD_ID =(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_ID='$URT_SRC_uld_id') AND UA_TERMINATE='X'";
        $selectrecver_rs=mysqli_query($con,$select_recver);
        $recver_array=array();
        while($row=mysqli_fetch_array($selectrecver_rs))
        {
            $recver_array[]=$row['UA_REC_VER'];
        }
        if(count($recver_array)==1){
            $query= "SELECT UA_REASON,UA_END_DATE ,UA_REC_VER FROM LMC_USER_ACCESS where ULD_ID =(select ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_ID ='".$URT_SRC_uld_id."')";
            $loginsearch_fetchingdata= mysqli_query($con, $query);
            $URT_SRC_values=array();
            if($row=mysqli_fetch_array($loginsearch_fetchingdata)){
                $URT_SRC_enddate=$row["UA_END_DATE"];
                $URT_SRC_reason=$row["UA_REASON"];
                $URT_SRC_recver=$row["UA_REC_VER"];
                $final_date = date('d-m-Y',strtotime( $URT_SRC_enddate));
                $URT_SRC_values=array('enddate'=>$final_date,'reasonn' => $URT_SRC_reason,'recver'=>$URT_SRC_recver);
            }
            echo json_encode($URT_SRC_values);
        }
        else{
            $URT_SRC_values=array('recver'=>$recver_array);
            echo json_encode($URT_SRC_values);
        }
    }
    else if($_REQUEST['option']=='FETCH DATA'){
        $loginid = $_REQUEST['URT_SRC_loggin'];
        $recver=$_REQUEST['recver'];
        $query= "SELECT UA_REASON,UA_END_DATE  FROM LMC_USER_ACCESS where ULD_ID =$loginid and UA_REC_VER='$recver'";
        $loginsearch_fetchingdata= mysqli_query($con, $query);
        $URT_SRC_values=array();
        if($row=mysqli_fetch_array($loginsearch_fetchingdata)){
            $URT_SRC_enddate=$row["UA_END_DATE"];
            $URT_SRC_reason=$row["UA_REASON"];

            $final_date = date('d-m-Y',strtotime( $URT_SRC_enddate));
            $URT_SRC_values=array('enddate'=>$final_date,'reasonn' => $URT_SRC_reason);
        }
        echo json_encode($URT_SRC_values);
    }
    else if($_REQUEST['option']=='GETDATE')
    {
        $loginid_result = $_REQUEST['URT_SRC_loggin'];
        $query= "SELECT  DATE_FORMAT(UA_JOIN_DATE,'%d-%m-%Y') as UA_JOIN_DATE  FROM LMC_USER_ACCESS where UA_REC_VER=(select MAX(UA_REC_VER) as UA_REC_VER_MAX from LMC_USER_ACCESS where ULD_ID=$loginid_result AND UA_TERMINATE IS NULL)AND ULD_ID=$loginid_result";
        $joindate_data= mysqli_query($con, $query);
        $URT_SRC_values=array();
        while($row=mysqli_fetch_array($joindate_data)){
            $mindate=$row["UA_JOIN_DATE"];
        }
        echo $mindate;
    }
    else if($_REQUEST['option']=='GETENDDATE')
    {
        $loginid_result = $_REQUEST['URT_SRC_loggin'];
        $query= "SELECT  DATE_FORMAT(UA_END_DATE,'%d-%m-%Y') as UA_END_DATE  FROM LMC_USER_ACCESS where UA_REC_VER=(select MAX(UA_REC_VER) as UA_REC_VER_MAX from LMC_USER_ACCESS where ULD_ID=$loginid_result AND UA_TERMINATE IS NOT NULL)AND ULD_ID=$loginid_result";
        $enddate_data= mysqli_query($con, $query);
        $URT_SRC_values=array();
        while($row=mysqli_fetch_array($enddate_data)){
            $mindate=$row["UA_END_DATE"];
        }
        $get_team=mysqli_query($con,"SELECT * FROM LMC_TEAM_CREATION  ");
        $get_team_array=array();
        while($row=mysqli_fetch_array($get_team)){
            $get_team_array[]=$row["TEAM_NAME"];
        }

        $sql="SELECT EMP_DOC_FOLDER_ID FROM LMC_EMPLOYEE_DETAILS WHERE ULD_ID='$login_id_result'";
        $sql_result= mysqli_query($con,$sql);
        if($row=mysqli_fetch_array($sql_result)){
            $EMPDOCFOLDER_ID=$row["EMP_DOC_FOLDER_ID"];
        }
        //EMPLOYEE DETAILS
        $login_id_result = $_REQUEST['URT_SRC_loggin'];
        $loginsearch_fetchingdata= mysqli_query($con,"SELECT DISTINCT EMP.EMP_EMAIL_ID,RC.RC_NAME,UA.UA_JOIN_DATE,URC1.URC_DATA,EMP.EMP_ID,EMP.EMP_FIRST_NAME,EMP.EMP_LAST_NAME,NRIC_NO,DATE_FORMAT(EMP.EMP_DOB,'%d-%m-%Y') AS EMP_DOB,EMP_ADDRESS,EMP.EMP_DESIGNATION,EMP_GENDER,EMP.EMP_MOBILE_NUMBER,EMP.EMP_NEXT_KIN_NAME,EMP.EMP_RELATIONHOOD,EMP.EMP_ALT_MOBILE_NO,EMP.EMP_BANK_NAME,EMP.EMP_BRANCH_NAME,EMP.EMP_ACCOUNT_NAME,EMP.EMP_ACCOUNT_NO,EMP.EMP_IFSC_CODE,EMP.EMP_ACCOUNT_TYPE,EMP.EMP_BRANCH_ADDRESS,EMP.EMP_REMARKS,ULD.ULD_USERNAME,DATE_FORMAT(CONVERT_TZ(EMP.EMP_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') AS EMP_TIMESTAMP
          FROM LMC_EMPLOYEE_DETAILS EMP ,LMC_USER_LOGIN_DETAILS ULD,LMC_USER_ACCESS UA ,LMC_USER_RIGHTS_CONFIGURATION URC,LMC_USER_RIGHTS_CONFIGURATION URC1,LMC_ROLE_CREATION RC  WHERE EMP.ULD_ID=ULD.ULD_ID AND UA.UA_EMP_TYPE=URC1.URC_ID and ULD.ULD_ID=UA.ULD_ID and URC.URC_ID=RC.URC_ID and RC.RC_ID=UA.RC_ID and ULD.ULD_ID='$login_id_result' and UA.UA_REC_VER=(select max(UA_REC_VER) from LMC_USER_ACCESS UA,LMC_USER_LOGIN_DETAILS ULD where ULD.ULD_ID=UA.ULD_ID and ULD.ULD_ID='$login_id_result' ) ORDER BY EMP.EMP_FIRST_NAME,EMP.EMP_LAST_NAME");
        while($row=mysqli_fetch_array($loginsearch_fetchingdata)){
            $URSRC_firstname=$row['EMP_FIRST_NAME'];
            $URSRC_lastname=$row['EMP_LAST_NAME'];
            $URSRC_nricno=$row['NRIC_NO'];
            $URSRC_dob=$row['EMP_DOB'];
            $URSRC_address=$row['EMP_ADDRESS'];
            $URSRC_designation=$row['EMP_DESIGNATION'];
            $URSRC_gender=$row['EMP_GENDER'];
            $URSRC_mobile=$row['EMP_MOBILE_NUMBER'];
            $URSRC_kinname=$row['EMP_NEXT_KIN_NAME'];
            $URSRC_relationhd=$row['EMP_RELATIONHOOD'];
            $URSRC_Mobileno=$row['EMP_ALT_MOBILE_NO'];
            $URSRC_bankname=$row['EMP_BANK_NAME'];
            $URSRC_brancname=$row['EMP_BRANCH_NAME'];
            $URSRC_acctname=$row['EMP_ACCOUNT_NAME'];
            $URSRC_acctno=$row['EMP_ACCOUNT_NO'];
            $URSRC_acctype=$row['EMP_ACCOUNT_TYPE'];
            $URSRC_ifsccode=$row['EMP_IFSC_CODE'];
            $URSRC_branchaddr=$row['EMP_BRANCH_ADDRESS'];
            $URSRC_comments=$row['EMP_REMARKS'];
            $URSRC_username=$row['ULD_USERNAME'];
            $EMP_DOC_FOLDER_ID=$row['EMP_DOC_FOLDER_ID'];
            $URSRC_emailid=$row['EMP_EMAIL_ID'];
            $final_values=(object)['URSRC_emailid'=>$URSRC_emailid,'firstname'=>$URSRC_firstname,'lastname'=>$URSRC_lastname,'nricno'=>$URSRC_nricno,'dob'=>$URSRC_dob,'address'=>$URSRC_address,'designation'=>$URSRC_designation,'gender'=>$URSRC_gender,'mobile'=>$URSRC_mobile,'kinname'=>$URSRC_kinname,'relationhood'=>$URSRC_relationhd,'altmobile'=>$URSRC_Mobileno,'bankname'=>$URSRC_bankname,'branchname'=>$URSRC_brancname,'accountname'=>$URSRC_acctname,'accountno'=>$URSRC_acctno,'ifsccode'=>$URSRC_ifsccode,'accountype'=>$URSRC_acctype,'branchaddress'=>$URSRC_branchaddr,'username'=>$URSRC_username,'URSRC_comments'=>$URSRC_comments];
        }
        $URSRC_values[]=array($final_values,$mindate,$get_team_array);
        echo json_encode($URSRC_values);
    }
    else if($_REQUEST['option']=='GET_VALUE'){

        $loginid = $_REQUEST['URT_SRC_loggin'];
        $date_value = $_REQUEST['date_value'];
        $final_date = date('Y-m-d',strtotime( $date_value));
        $select_data="SELECT  MAX(UARD_DATE) AS UARD_DATE from USER_ADMIN_REPORT_DETAILS WHERE UARD_DATE>'$final_date' AND ULD_ID='$loginid'";
        $select_data_rs=mysqli_query($con,$select_data);
        if($row=mysqli_fetch_array($select_data_rs)){
            $finaldate=$row['UARD_DATE'];
            if($finaldate!=''){
                $finaldate = date('d-m-Y',strtotime($finaldate));
            }
        }
        echo $finaldate;
    }
    else if($_REQUEST['option']=='UPDATE')
    {
        $reason_update=$_REQUEST['URT_SRC_ta_nreasonupdate'];
        $reason_update=$con->real_escape_string($reason_update);
        $loggin=$_REQUEST['URT_SRC_loggin'];
        $date=$_REQUEST['URT_SRC_tb_ndatepickerupdate'];
        $recver=$_REQUEST['URT_SRC_lb_recordversion'];
        $enddate = date("Y-m-d",strtotime($date));
        $sql="UPDATE LMC_USER_ACCESS SET UA_END_DATE='$enddate',UA_REASON='$reason_update',UA_USERSTAMP='$userstamp' where ULD_ID='$loggin'  AND UA_REC_VER='$recver'";
        if (!mysqli_query($con,$sql)) {
            die('Error: ' . mysqli_error($con));
            $flag=0;
        }
        else{
            $flag=1;
        }
        echo $flag;
    }
    else if($_REQUEST['option']=='REJOIN')
    {
        $loggin=$_REQUEST['URT_SRC_lb_nloginrejoin'];
        $loggin_empty='';
        $login_id=$_REQUEST['login_id'];
        $role_access = $_REQUEST['URT_SRC_radio_nrole'];
        $final_radioval=str_replace("_"," ",$role_access);
        $date=$_REQUEST['URT_SRC_tb_ndatepickerrejoin'];
        $emp_type=$_REQUEST['URSRC_lb_selectemptype'];
        $joindate = date("Y-m-d",strtotime($date));
        $URSRC_firstname=$_REQUEST['URSRC_tb_firstname'];
        $URSRC_lastname=$_REQUEST['URSRC_tb_lastname'];
        $URSRC_dob=$_REQUEST['URSRC_tb_dob'];
        $URSRC_finaldob = date('Y-m-d',strtotime($URSRC_dob));
        $URSRC_gender=$_REQUEST['URSRC_rd_gender'];
        $URSRC_nric=$_REQUEST['URSRC_tb_nric'];
        $URSRC_designation=$_REQUEST['URSRC_tb_designation'];
        $URSRC_Mobileno=$_REQUEST['URSRC_tb_permobile'];
        $URSRC_kinname=$_REQUEST['URSRC_tb_kinname'];
        $URSRC_relationhd=$_REQUEST['URSRC_tb_relationhd'];
        $URSRC_mobile=$_REQUEST['URSRC_tb_mobile'];
        $URSRC_mobile=$_REQUEST['URSRC_tb_mobile'];
        $URSRC_address=$_REQUEST['URSRC_ta_addr'];
        $URSRC_address1=$con->real_escape_string($URSRC_address);
        $URSRC_bankname=$_REQUEST['URSRC_tb_bnkname'];
        $URSRC_brancname=$_REQUEST['URSRC_tb_brnchname'];
        $URSRC_acctname=$_REQUEST['URSRC_tb_accntname'];
        $URSRC_acctno=$_REQUEST['URSRC_tb_accntno'];
        $URSRC_ifsccode=$_REQUEST['URSRC_tb_ifsccode'];
        $URSRC_acctype=$_REQUEST['URSRC_tb_accntyp'];
        $URSRC_branchaddr=$_REQUEST['URSRC_ta_brnchaddr'];
        $URSRC_branchaddr1=$con->real_escape_string($URSRC_branchaddr);
        $URSRC_laptopno=$_REQUEST['URSRC_tb_laptopno'];
        $URSRC_chrgrno=$_REQUEST['URSRC_tb_chargerno'];
        $URSRC_bag=$_REQUEST['URSRC_chk_bag'];
        $URSRC_aadharno=$_REQUEST['URSRC_tb_aadharno'];
        $URSRC_voterid=$_REQUEST['URSRC_tb_votersid'];
        $URSRC_passportno=$_REQUEST['URSRC_tb_passportno'];
        $URSRC_comments=$_REQUEST['URSRC_ta_comments'];
        $URSRC_comments=$con->real_escape_string($URSRC_comments);
        $URSRC_passwrd=$_REQUEST['URT_SRC_tb_pword'];
        $password = mysql_real_escape_string($URSRC_password);
        $password=  md5($password);
        $URSRC_uname=$_REQUEST['URT_SRC_tb_uname'];
        $URSRC_cnfrmpasswrd=$_REQUEST['URT_SRC_tb_confirmpword'];
        $URSRC_team=$_REQUEST['URSRC_lb_selectteam'];
        $loggin_folderid='';
        $loggin_imagefolderid='';
        //File upload function
//File upload function
        $sql="SELECT EMP_DOC_FOLDER_ID FROM LMC_EMPLOYEE_DETAILS WHERE ULD_ID='$login_id'";
        $sql_result= mysqli_query($con,$sql);
        if($row=mysqli_fetch_array($sql_result)){
            $EMPDOCFOLDER_ID=$row["EMP_DOC_FOLDER_ID"];
        }
        $parent_attach_folder_name=get_docfolder_id();
        $currentdate=date("d-m-Y");
        $currentdate=str_replace('-','',$currentdate);
        $attch_file_folder=$parent_attach_folder_name.DIRECTORY_SEPARATOR.$EMPDOCFOLDER_ID.DIRECTORY_SEPARATOR;
        $uploadcount=$_REQUEST['upload_count'];
        $URSRC_old_filename=$_REQUEST['upload_count'];
        $URSRC_emailid=$_POST['URSRC_tb_emailid'];
        $upload_file_array=array();

        $con->autocommit(false);
        $result = $con->query("CALL  SP_TS_LOGIN_CREATION_INSERT('2','$loggin_empty','$password','$login_id','$final_radioval','$joindate','$emp_type','$URSRC_firstname','$URSRC_lastname','$URSRC_nric','$URSRC_designation','$URSRC_gender','$URSRC_Mobileno','$URSRC_finaldob','$URSRC_team','$loggin_folderid','$URSRC_address1','$URSRC_comments','$URSRC_kinname','$URSRC_relationhd','$URSRC_mobile','$URSRC_bankname','$URSRC_brancname','$URSRC_acctname','$URSRC_acctno','$URSRC_ifsccode','$URSRC_acctype','$URSRC_branchaddr1','$loggin_imagefolderid','$userstamp','$URSRC_emailid',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag= $result['@success_flag'];
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
            //MAIL PART
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
            $replace= array("[LOGINID]", "[DES]");
            $str_replaced  = array($URSRC_firstname,'<b>'.$URSRC_designation.'</b>');
            $final_message = str_replace($replace, $str_replaced, $email_body);

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
            $comment =explode("\n", $URSRC_branchaddr);
            $commnet_length=count($comment);
            for($i=0;$i<$commnet_length;$i++){
                $comment_msg.=$comment[$i].'<br>';
            }


            $comment_address =explode("\n", $URSRC_address);
            $commnet_adds_length=count($comment_address);
            for($i=0;$i<$commnet_adds_length;$i++){
                $comment_msg_add.=$comment_address[$i].'<br>';
            }
            $replace= array( "[FNAME]","[LNAME]","[TEAMNAME]","[NRICNO]", "[DOB]","[GENDER]","[DESG]","[MOBNO]","[KINNAME]","[REL]","[ALTMOBNO]","[EMPADDRESS]","[BANKNAME]","[BRANCHNAME]","[ACCNAME]","[ACCNO]","[IFSCCODE]","[ACCTYPE]","[BANKADDRESS]","PERSONAL DETAILS:","BANK ACCOUNT DETAILS:","[EMAILID]");
            $str_replaced  = array($URSRC_firstname, $URSRC_lastname,$URSRC_team,$URSRC_nric,$URSRC_dob,$URSRC_gender,$URSRC_designation,$URSRC_Mobileno,$URSRC_kinname,$URSRC_relationhd,$URSRC_mobile,$comment_msg_add,$URSRC_bankname,$URSRC_brancname,$URSRC_acctname,$URSRC_acctno,$URSRC_ifsccode,$URSRC_acctype,$comment_msg,'<b>'."PERSONAL DETAILS:".'</b>','<b>'."BANK ACCOUNT DETAILS:".'</b>',$URSRC_emailid);
            $newphrase = str_replace($replace, $str_replaced, $emp_email_body);
            $final_message=$final_message.'<br>'.$newphrase;
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Username = $username;
            $mail->Password = $password;
            $mail->SMTPSecure = $smtpsecure;
            $mail->From = $from;
            $mail->FromName = 'LMC';
            $mail->addAddress($URSRC_emailid);
            $mail->addCC($toaddress);
            $mail->WordWrap = 50;
            $mail->isHTML(true);
            $mail->Subject = $mail_subject;
            $mail->Body = $final_message;
            $mail->send();
        }
        $flag_array=[$flag];
        $con->commit();
        echo json_encode($flag_array);
    }
    else if($_REQUEST['option']=='TERMINATE')
    {
        $reason_termin=$_POST['URT_SRC_ta_nreasontermination'];
        $reason_termin=$con->real_escape_string($reason_termin);
        $loggin=$_REQUEST['loggin'];
        $date=$_POST['URT_SRC_tb_ndatepickertermination'];
        $enddate = date("Y-m-d",strtotime($date));
        $con->autocommit(false);
        $select_loggin=mysqli_query($con,"SELECT * from VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS where ULD_ID= $loggin");
        if($row=mysqli_fetch_array($select_loggin)){
            $loginid=$row["ULD_LOGINID"];
            $emp_name=$row["EMPLOYEE_NAME"];
        }
        $result = $con->query("CALL SP_TS_LOGIN_TERMINATE_SAVE($loggin,'$enddate','$reason_termin','$userstamp',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag=$result['@success_flag'];
//        if($flag==1){
//
//            $select_fileid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=9");
//            if($row=mysqli_fetch_array($select_fileid)){
//                $ss_fileid=$row["URC_DATA"];
//            }
//            $loginid_name = strtoupper(substr($loginid, 0, strpos($loginid, '@')));
//            if(substr($loginid_name, 0, strpos($loginid_name, '.'))){
//
//                $loginid_name = strtoupper(substr($loginid_name, 0, strpos($loginid_name, '.')));
//            }
//            else{
//                $loginid_name=$loginid_name;
//            }
//            $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$loginid'");
//            while($row=mysqli_fetch_array($uld_id)){
//                $URSC_uld_id=$row["ULD_ID"];
//            }
//            $select_calenderid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=10");
//            if($row=mysqli_fetch_array($select_calenderid)){
//                $calenderid=$row["URC_DATA"];
//            }
//            $fileId=$ss_fileid;
//
//            $return_array=URSRC_calendar_create($loginid,$fileId,$emp_name,$URSC_uld_id,$enddate,$calenderid,'TERMINATE DATE','TERMINATE','','','','');
//            $ss_flag=$return_array[0];
//            $cal_flag=$return_array[1];
//            if($ss_flag==0){
//                $con->rollback();
//            }
//            if($cal_flag==0){
//                share_document($loginid,$fileId);
//                $con->rollback();
//            }
        $flag_array=[$flag,'1','1','1'];
//        }
//        else{
//            $flag_array=[$flag];
//        }
        $con->commit();
        echo json_encode($flag_array);
    }
    else if($_REQUEST['option']=="URSRC_check_team"){
        $URSRC_team=$_GET['URSRC_team_name'];
        $sql="SELECT * FROM LMC_TEAM_CREATION where TEAM_NAME='$URSRC_team'";
        $sql_result= mysqli_query($con,$sql);
        $row=mysqli_num_rows($sql_result);
        $x=$row;
        if($x > 0)
        {
            $URSRC_already_exist_flag=1;
        }
        else{
            $URSRC_already_exist_flag=0;
        }
        echo ($URSRC_already_exist_flag);
    }
}
//FUNCTION FOR CALENDAR SHARING DOCUMENT
function share_document($loggin,$fileId){
    global $con,$ClientId,$ClientSecret,$RedirectUri,$DriveScopes,$CalenderScopes,$Refresh_Token;
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
    $value=$loggin;
    $type='user';
    $role='reader';
    $email=$loggin;
    $newPermission = new Google_Service_Drive_Permission();
    $newPermission->setValue($value);
    $newPermission->setType($type);
    $newPermission->setRole($role);
    $newPermission->setEmailAddress($email);
    try {
        $service->permissions->insert($fileId, $newPermission);
        $ss_flag=1;
    } catch (Exception $e) {
        $ss_flag=0;
    }
}
//FUNCTION FOR CALENDAR CREATION
function URSRC_calendar_create($loggin,$fileId,$loginid_name,$URSC_uld_id,$finaldate,$calenderid,$status,$form,$filesarray,$URSRC_firstname,$URSRC_lastname,$folderid){
    global $con,$ClientId,$ClientSecret,$RedirectUri,$DriveScopes,$CalenderScopes,$Refresh_Token;
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
    if($form=='TERMINATE'){
        $file_arraycount=1;
        try {
            $permissions = $service->permissions->listPermissions($fileId);
            $return_value= $permissions->getItems();
        } catch (Exception $e) {
            $ss_flag=0;
        }
        foreach ($return_value as $key => $value) {
            if ($value->emailAddress==$loggin) {
                $permission_id=$value->id;
            }
        }
        if($permission_id!=''){
            try {
                $service->permissions->delete($fileId, $permission_id);
                $ss_flag=1;
            } catch (Exception $e) {
                $ss_flag=0;
            }
        }
        else{

            $ss_flag=1;
        }
    }
    else{
        $value=$loggin;
        $type='user';
        $role='reader';
        $email=$loggin;
        $newPermission = new Google_Service_Drive_Permission();
        $newPermission->setValue($value);
        $newPermission->setType($type);
        $newPermission->setRole($role);
        $newPermission->setEmailAddress($email);
        try {
            $service->permissions->insert($fileId, $newPermission);
            $ss_flag=1;
        } catch (Exception $e) {
            $ss_flag=0;
        }

        if($ss_flag==1){
            if($filesarray!='')
            {
                $file_array=array();
                $allfilearray=(explode(",",$filesarray));
                foreach ($allfilearray as $value)
                {
                    $uploadfilename=$value;
                    $drivefilename=$URSRC_firstname.' '.$URSRC_lastname.'-'.$uploadfilename;
                    $extension =(explode(".",$uploadfilename));
                    if($extension[1]=='pdf'){$mimeType='application/pdf';}
                    if($extension[1]=='jpg'){$mimeType='image/jpeg';}
                    if($extension[1]=='png'){$mimeType='image/png';}
                    $file_id_value =insertFile($service,$drivefilename,'PersonalDetails',$folderid,$mimeType,$uploadfilename);
                    if($file_id_value!=''){
                        array_push($file_array,$file_id_value);
                    }
                }
                $file_arraycount=count($file_array);
            }
            else{
                $file_arraycount=1;
            }
        }
    }
    if($ss_flag==1 && $file_arraycount>0){
        $cal = new Google_Service_Calendar($drive);
        $event = new Google_Service_Calendar_Event();
        $event->setsummary($loginid_name.'  '.$status);
        $event->setDescription($URSC_uld_id);
        $start = new Google_Service_Calendar_EventDateTime();
        $start->setDate($finaldate);//setDate('2014-11-18');
        $event->setStart($start);
        $event->setEnd($start);
        try{
            $createdEvent = $cal->events->insert($calenderid, $event);
            $cal_flag=1;
        }
        catch(Exception $e){
            $cal_flag=0;
        }
    }
    $flag_array=[$ss_flag,$cal_flag,$file_id_value,$file_array];
    return $flag_array;
}
//File Upload Function Script
function insertFile($service, $title, $description, $parentId,$mimeType,$uploadfilename)
{
    $file = new Google_Service_Drive_DriveFile();
    $file->setTitle($title);
    $file->setDescription($description);
    $file->setMimeType($mimeType);
    if ($parentId != null) {
        $parent = new Google_Service_Drive_ParentReference();
        $parent->setId($parentId);
        $file->setParents(array($parent));
    }
    try
    {
        $data =file_get_contents($uploadfilename);
        $createdFile = $service->files->insert($file, array(
            'data' => $data,
            'mimeType' => $mimeType,
            'uploadType' => 'media',
        ));

        $fileid = $createdFile->getId();

    }
    catch (Exception $e)
    {
        $file_flag=0;

    }
    return $fileid;
//    return $file_flag;

}
function URSRC_unshare_document($loggin,$fileId){

    global $con,$ClientId,$ClientSecret,$RedirectUri,$DriveScopes,$CalenderScopes,$Refresh_Token;
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


    try {
        $permissions = $service->permissions->listPermissions($fileId);
        $return_value= $permissions->getItems();
    } catch (Exception $e) {
//        print "An error occurred: " . $e->getMessage();
        $ss_flag=0;
    }
    foreach ($return_value as $key => $value) {
        if ($value->emailAddress==$loggin) {
            $permission_id=$value->id;
        }
    }
    if($permission_id!=''){
        try {
            $service->permissions->delete($fileId, $permission_id);
//        $ss_flag=1;
        } catch (Exception $e) {
//        print "An error occurred: " . $e->getMessage();
//        $ss_flag=0;
        }
    }

}
