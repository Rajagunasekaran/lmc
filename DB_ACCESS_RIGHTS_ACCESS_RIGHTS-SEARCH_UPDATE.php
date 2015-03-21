<?php
require 'PHPMailer-master/PHPMailerAutoload.php';
include "CONNECTION.php";
include "COMMON.php";
include "GET_USERSTAMP.php";
$dir=dirname(__FILE__).DIRECTORY_SEPARATOR;
error_reporting(0);
if(isset($_REQUEST)){
    $USERSTAMP=$UserStamp;

    global $con;
    //ALREADY EXISTS FUNCTION FOR LOGIN ID
    if($_REQUEST['option']=="check_login_id"){
        $loginid=$_GET['URSRC_login_id'];
        $sql="select * from LMC_USER_LOGIN_DETAILS where ULD_USERNAME='$loginid'";
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
        $rcname_result=mysqli_query($con,"SELECT * FROM LMC_ROLE_CREATION ORDER BY RC_NAME");
        $get_rcname_array=array();
        while($row=mysqli_fetch_array($rcname_result)){
            $get_rcname_array[]=$row["RC_NAME"];
        }
        $URSRC_final_array=array();
        $URSRC_role_array=array();
        $URSRC_role_array=$get_rcname_array;
        $URSRC_final_array=array($URSRC_already_exist_flag,$URSRC_role_array);
        echo json_encode($URSRC_final_array);
    }

    if($_REQUEST['option']=="URSRC_check_team"){
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



    if($_REQUEST['option']=="get_team"){


        $get_team=mysqli_query($con,"SELECT * FROM LMC_TEAM_CREATION  ");
        $get_team_array=array();
        while($row=mysqli_fetch_array($get_team)){
            $get_team_array[]=$row["TEAM_NAME"];
        }
        echo json_encode($get_team_array);
    }

//
//LOGIN CREWTION SAVE PART
    if($_REQUEST['option']=="loginsave")
    {
        $loginid=$_POST['URSRC_tb_loginid'];
        $empty_field="null";
        $emp_type=$_POST['URSRC_lb_selectemptype'];
        $role_accessradiovalue = $_REQUEST['radio_checked'];
        $final_radioval=str_replace("_"," ",$role_accessradiovalue);
        $radio_gender = $_REQUEST['radio_gender'];
        $date=$_POST['URSRC_tb_joindate'];
        $finaldate = date('Y-m-d',strtotime($date));
        $URSRC_firstname=$_POST['URSRC_tb_firstname'];
        $URSRC_lastname=$_POST['URSRC_tb_lastname'];
        $URSRC_dob=$_POST['URSRC_tb_dob'];
        $URSRC_finaldob = date('Y-m-d',strtotime($URSRC_dob));
        $URSRC_designation=$_POST['URSRC_tb_designation'];
        $URSRC_Mobileno=$_POST['URSRC_tb_permobile'];
        $URSRC_kinname=$_POST['URSRC_tb_kinname'];
        $URSRC_relationhd=$_POST['URSRC_tb_relationhd'];
        $URSRC_mobile=$_POST['URSRC_tb_mobile'];
        $URSRC_bankname=$_POST['URSRC_tb_bnkname'];
        $URSRC_brancname=$_POST['URSRC_tb_brnchname'];
        $URSRC_acctname=$_POST['URSRC_tb_accntname'];
        $URSRC_acctno=$_POST['URSRC_tb_accntno'];
        $URSRC_ifsccode=$_POST['URSRC_tb_ifsccode'];
        $URSRC_acctype=$_POST['URSRC_tb_accntyp'];
        $URSRC_emailid=$_POST['URSRC_tb_emailid'];
        $URSRC_branchaddr1=$_POST['URSRC_ta_brnchaddr'];
        $URSRC_branchaddr= $con->real_escape_string($URSRC_branchaddr1);

        $comment=$_POST['URSRC_ta_comments'];
        $comments= $con->real_escape_string($comment);

        $address=$_POST['URSRC_ta_address'];
        $address1= $con->real_escape_string($address);

        $NRICNO=$_POST["URSRC_tb_nric"];
        $URSRC_team_name=$_POST["URSRC_lb_selectteam"];
        $URSRC_password=$_POST['URSRC_tb_pword'];
        $password = mysql_real_escape_string($URSRC_password);
        $password=  md5($password);

        $daterep=str_replace('-','',$date);
        $subfoldername=$URSRC_firstname.'_'.$daterep.'_'.date('His');

        $parent_image_folder_name=get_parentfolder_id();
        $folder_name=$parent_image_folder_name.DIRECTORY_SEPARATOR.$subfoldername.DIRECTORY_SEPARATOR;
        $detele_foldername=$parent_image_folder_name.DIRECTORY_SEPARATOR.$subfoldername;
        if (!file_exists($folder_name)) {
            mkdir($folder_name, 0777);
        }

        $parent_attach_folder_name=get_docfolder_id();

        $attch_file_folder=$parent_attach_folder_name.DIRECTORY_SEPARATOR;
        $uploadcount=$_REQUEST['upload_count'];
        $upload_file_array=array();
        $currentdate=date("d-m-Y");
        $currentdate=str_replace('-','',$currentdate);
        $attach_sub_folder_name=$URSRC_firstname.'_'.$currentdate.'_'.date('His');
        $attch_file_folder=$parent_attach_folder_name.DIRECTORY_SEPARATOR.$attach_sub_folder_name.DIRECTORY_SEPARATOR;
        $attch_delete_folder=$parent_attach_folder_name.DIRECTORY_SEPARATOR.$attach_sub_folder_name;
        if (!file_exists($attch_file_folder)) {
            mkdir($attch_file_folder, 0777);
        }

        $con->autocommit(false);

        $result = $con->query("CALL SP_TS_LOGIN_CREATION_INSERT(1,'$loginid','$password',$empty_field,'$final_radioval','$finaldate','$emp_type','$URSRC_firstname','$URSRC_lastname','$NRICNO','$URSRC_designation','$radio_gender','$URSRC_Mobileno','$URSRC_finaldob','$URSRC_team_name','$attach_sub_folder_name','$address1','$comments','$URSRC_kinname','$URSRC_relationhd','$URSRC_mobile','$URSRC_bankname','$URSRC_brancname','$URSRC_acctname','$URSRC_acctno','$URSRC_ifsccode','$URSRC_acctype','$URSRC_branchaddr','$subfoldername','$USERSTAMP','$URSRC_emailid',@success_flag)");
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
            $comment =explode("\n", $URSRC_branchaddr1);
            $commnet_length=count($comment);
            for($i=0;$i<$commnet_length;$i++){
                $comment_msg.=$comment[$i].'<br>';
            }

            $comment_address =explode("\n", $address);
            $commnet_adds_length=count($comment_address);
            for($i=0;$i<$commnet_adds_length;$i++){
                $comment_msg_add.=$comment_address[$i].'<br>';
            }
            $replace= array( "[FNAME]","[LNAME]","[TEAMNAME]","[NRICNO]", "[DOB]","[GENDER]","[DESG]","[MOBNO]","[KINNAME]","[REL]","[ALTMOBNO]","[EMPADDRESS]","[BANKNAME]","[BRANCHNAME]","[ACCNAME]","[ACCNO]","[IFSCCODE]","[ACCTYPE]","[BANKADDRESS]","PERSONAL DETAILS:","BANK ACCOUNT DETAILS:","[EMAILID]");
            $str_replaced  = array($URSRC_firstname, $URSRC_lastname,$URSRC_team_name,$NRICNO,$URSRC_dob,$radio_gender,$URSRC_designation,$URSRC_Mobileno,$URSRC_kinname,$URSRC_relationhd,$URSRC_mobile,$comment_msg_add,$URSRC_bankname,$URSRC_brancname,$URSRC_acctname,$URSRC_acctno,$URSRC_ifsccode,$URSRC_acctype,$comment_msg,'<b>'."PERSONAL DETAILS:".'</b>','<b>'."BANK ACCOUNT DETAILS:".'</b>',$URSRC_emailid);
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
            $mail->Body    = $final_message;
            $mail->send();
        }
        else{
            rmdir($dir.$attch_delete_folder);
            rmdir($dir.$detele_foldername);
        }
        $flag_array=[$flag];
        $con->commit();
        echo JSON_ENCODE($flag_array);
    }
    //FETCHING LOGIN DETAILS
    if($_REQUEST['option']=="loginfetch")
    {
        $loginid_result =$_REQUEST['URSRC_login_id'];
        $loginsearch_fetchingdata= mysqli_query($con,"SELECT DISTINCT EMP.EMP_EMAIL_ID,EMP.EMP_DOC_FOLDER_ID,EMP.EMP_ADDRESS,EMP.NRIC_NO,TC.TEAM_NAME,RC.RC_NAME,UA.UA_JOIN_DATE,URC1.URC_DATA,EMP.EMP_ID,EMP.EMP_FIRST_NAME,EMP.EMP_LAST_NAME,DATE_FORMAT(EMP.EMP_DOB,'%d-%m-%Y') AS EMP_DOB,EMP.EMP_GENDER,EMP.EMP_DESIGNATION,EMP.EMP_MOBILE_NUMBER,EMP.EMP_NEXT_KIN_NAME,EMP.EMP_RELATIONHOOD,EMP.EMP_ALT_MOBILE_NO,EMP.EMP_BANK_NAME,EMP.EMP_BRANCH_NAME,EMP.EMP_ACCOUNT_NAME,EMP.EMP_ACCOUNT_NO,EMP.EMP_IFSC_CODE,EMP.EMP_ACCOUNT_TYPE,EMP.EMP_BRANCH_ADDRESS,EMP.EMP_REMARKS,ULD.ULD_USERNAME,DATE_FORMAT(CONVERT_TZ(EMP.EMP_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T') AS EMP_TIMESTAMP
FROM LMC_EMPLOYEE_DETAILS EMP ,LMC_USER_LOGIN_DETAILS ULD,LMC_USER_ACCESS UA ,LMC_USER_RIGHTS_CONFIGURATION URC,LMC_USER_RIGHTS_CONFIGURATION URC1,LMC_ROLE_CREATION RC,LMC_TEAM_CREATION TC  WHERE EMP.TC_ID=TC.TC_ID  AND EMP.ULD_ID=ULD.ULD_ID AND UA.UA_EMP_TYPE=URC1.URC_ID AND ULD.ULD_ID=UA.ULD_ID AND URC.URC_ID=RC.URC_ID AND RC.RC_ID=UA.RC_ID AND ULD_USERNAME='$loginid_result' AND UA.UA_REC_VER=(SELECT MAX(UA_REC_VER) FROM LMC_USER_ACCESS UA,LMC_USER_LOGIN_DETAILS ULD where ULD.ULD_ID=UA.ULD_ID AND ULD_USERNAME='$loginid_result' AND UA_JOIN IS NOT NULL) ORDER BY EMP.EMP_FIRST_NAME,EMP.EMP_LAST_NAME");
        $URSRC_values=array();
        $rolecreation_result = mysqli_query($con,"SELECT * FROM LMC_ROLE_CREATION");
        $get_rolecreation_array=array();
        $final_values=array();
        while($row=mysqli_fetch_array($rolecreation_result)){
            $get_rolecreation_array[]= $row["RC_NAME"];
        }
        while($row=mysqli_fetch_array($loginsearch_fetchingdata)){
            $URSRC_nricno=$row["NRIC_NO"];
            $URSRC_team_name=$row["TEAM_NAME"];
            $URSRC_joindate=$row["UA_JOIN_DATE"];
            $join_date=date('d-m-Y',strtotime($URSRC_joindate));
            $URSRC_rcname=$row["RC_NAME"];
            $URSRC_EMP_TYPE=$row['URC_DATA'];
            $URSRC_emp_gender=$row['EMP_GENDER'];
            $URSRC_firstname=$row['EMP_FIRST_NAME'];
            $URSRC_lastname=$row['EMP_LAST_NAME'];
            $URSRC_dob=$row['EMP_DOB'];
            $URSRC_designation=$row['EMP_DESIGNATION'];
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
            $URSRC_comment=$row['EMP_REMARKS'];
            $URSRC_filename=$row['UA_FILE_NAME'];
            $URSRC_ADDRESS=$row['EMP_ADDRESS'];
            $URSRC_folderid=$row['EMP_DOC_FOLDER_ID'];
            $URSRC_emailid=$row['EMP_EMAIL_ID'];
            $final_values=array('URSRC_emailid'=>$URSRC_emailid,'URSRC_folderid'=>$URSRC_folderid,'URSRC_address'=>$URSRC_ADDRESS,'URSRC_nricno'=>$URSRC_nricno,'team_name'=>$URSRC_team_name,'joindate'=>$join_date,'rcname' => $URSRC_rcname,'emp_type'=>$URSRC_EMP_TYPE,'firstname'=>$URSRC_firstname,'lastname'=>$URSRC_lastname,'dob'=>$URSRC_dob,'gender'=>$URSRC_emp_gender,'designation'=>$URSRC_designation,'mobile'=>$URSRC_mobile,'kinname'=>$URSRC_kinname,'relationhood'=>$URSRC_relationhd,'altmobile'=>$URSRC_Mobileno,'bankname'=>$URSRC_bankname,'branchname'=>$URSRC_brancname,'accountname'=>$URSRC_acctname,'accountno'=>$URSRC_acctno,'ifsccode'=>$URSRC_ifsccode,'accountype'=>$URSRC_acctype,'branchaddress'=>$URSRC_branchaddr,'comment'=>$URSRC_comment,'URSRC_filename'=>$URSRC_filename);
        }
        $URSRC_values[]=array($final_values,$get_rolecreation_array);
        echo json_encode($URSRC_values);
    }
    if($_REQUEST['option']=="login_db"){
        $final_value=array();
        $active_emp=array();
        $active_emp=get_active_emp_id();
        $get_team=mysqli_query($con,"SELECT * FROM LMC_TEAM_CREATION  ");
        $get_team_array=array();
        while($row=mysqli_fetch_array($get_team)){
            $get_team_array[]=$row["TEAM_NAME"];
        }
        $final_value=array($active_emp,$get_team_array);

        echo json_encode($final_value);

    }
//LOGIN CREATION UPATE FORM
    if($_REQUEST['option']=="loginupdate")
    {
        $rolename=$_POST['roles1'];
        $rolename=str_replace("_"," ",$rolename);
        $joindate=$_POST['URSRC_tb_joindate'];
        $emp_type=$_POST['URSRC_lb_selectemptype'];
        $loginid=$_POST['URSRC_tb_loginidupd'];
        $oldloginid=$_POST['URSRC_lb_loginid'];
        $URSRC_firstname=$_POST['URSRC_tb_firstname'];
        $URSRC_lastname=$_POST['URSRC_tb_lastname'];
        $URSRC_dob=$_POST['URSRC_tb_dob'];
        $URSRC_rd_gender=$_POST['URSRC_rd_gender'];
        $URSRC_finaldob = date('Y-m-d',strtotime($URSRC_dob));
        $URSRC_designation=$_POST['URSRC_tb_designation'];
        $URSRC_Mobileno=$_POST['URSRC_tb_permobile'];
        $URSRC_kinname=$_POST['URSRC_tb_kinname'];
        $URSRC_relationhd=$_POST['URSRC_tb_relationhd'];
        $URSRC_mobile=$_POST['URSRC_tb_mobile'];
        $URSRC_bankname=$_POST['URSRC_tb_bnkname'];
        $URSRC_brancname=$_POST['URSRC_tb_brnchname'];
        $URSRC_acctname=$_POST['URSRC_tb_accntname'];
        $URSRC_acctno=$_POST['URSRC_tb_accntno'];
        $URSRC_ifsccode=$_POST['URSRC_tb_ifsccode'];
        $URSRC_acctype=$_POST['URSRC_tb_accntyp'];
        $URSRC_branchaddr1=$_POST['URSRC_ta_brnchaddr'];
        $URSRC_branchaddr= $con->real_escape_string($URSRC_branchaddr1);
        $URSRC_comment=$_POST['URSRC_ta_comments'];
        $URSRC_comment= $con->real_escape_string($URSRC_comment);
        $address1=$_POST['URSRC_ta_address'];
        $address= $con->real_escape_string($address1);
        $NRICNO=$_POST["URSRC_tb_nric"];
        $URSRC_emailid=$_POST['URSRC_tb_emailid'];
        $URSRC_team_name=$_POST["URSRC_lb_selectteam"];
        $parent_attach_folder_name=get_docfolder_id();

        $sql="select * from LMC_USER_LOGIN_DETAILS where ULD_USERNAME='$oldloginid'";
        $sql_result= mysqli_query($con,$sql);
        if($row=mysqli_fetch_array($sql_result)){
            $ULD_id=$row["ULD_ID"];
        }


        $sql="SELECT EMP_DOC_FOLDER_ID FROM LMC_EMPLOYEE_DETAILS WHERE ULD_ID='$ULD_id'";
        $sql_result= mysqli_query($con,$sql);
        if($row=mysqli_fetch_array($sql_result)){
            $EMPDOCFOLDER_ID=$row["EMP_DOC_FOLDER_ID"];
        }
        $currentdate=date("d-m-Y");
        $currentdate=str_replace('-','',$currentdate);
        $attch_file_folder=$parent_attach_folder_name.DIRECTORY_SEPARATOR.$EMPDOCFOLDER_ID.DIRECTORY_SEPARATOR;
        $uploadcount=$_REQUEST['upload_count'];
        $URSRC_old_filename=$_REQUEST['URSRC_filename'];
        $upload_file_array=array();


        $finaldate = date('Y-m-d',strtotime($joindate));
        $con->autocommit(false);
        $result = $con->query("CALL SP_TS_LOGIN_UPDATE('$loginid',$ULD_id,'$rolename','$finaldate','$emp_type','$URSRC_firstname','$URSRC_lastname','$NRICNO','$URSRC_designation','$URSRC_rd_gender','$URSRC_Mobileno','$URSRC_finaldob','$URSRC_team_name','$address1','$URSRC_comment','$URSRC_kinname','$URSRC_relationhd','$URSRC_mobile','$URSRC_bankname','$URSRC_brancname','$URSRC_acctname','$URSRC_acctno','$URSRC_ifsccode','$URSRC_acctype','$URSRC_branchaddr','$USERSTAMP','$URSRC_emailid',@success_flag)");
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

                $select_template="SELECT * FROM LMC_EMAIL_TEMPLATE_DETAILS WHERE ET_ID=11";
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
                $comment =explode("\n", $URSRC_branchaddr1);
                $commnet_length=count($comment);
                for($i=0;$i<$commnet_length;$i++){
                    $comment_msg.=$comment[$i].'<br>';
                }

                $comment_address =explode("\n", $address1);
                $commnet_adds_length=count($comment_address);
                for($i=0;$i<$commnet_adds_length;$i++){
                    $comment_msg_add.=$comment_address[$i].'<br>';
                }
                $replace= array( "[FNAME]","[LNAME]","[TEAMNAME]","[NRICNO]", "[DOB]","[GENDER]","[DESG]","[MOBNO]","[KINNAME]","[REL]","[ALTMOBNO]","[EMPADDRESS]","[BANKNAME]","[BRANCHNAME]","[ACCNAME]","[ACCNO]","[IFSCCODE]","[ACCTYPE]","[BANKADDRESS]","PERSONAL DETAILS:","BANK ACCOUNT DETAILS:","[UNAME]","[EMAILID]","[USERNAME]");
                $str_replaced  = array($URSRC_firstname, $URSRC_lastname,$URSRC_team_name,$NRICNO,$URSRC_dob,$URSRC_rd_gender,$URSRC_designation,$URSRC_Mobileno,$URSRC_kinname,$URSRC_relationhd,$URSRC_mobile,$comment_msg_add,$URSRC_bankname,$URSRC_brancname,$URSRC_acctname,$URSRC_acctno,$URSRC_ifsccode,$URSRC_acctype,$comment_msg,'<b>'."PERSONAL DETAILS:".'</b>','<b>'."BANK ACCOUNT DETAILS:".'</b>',$loginid,$URSRC_emailid,$URSRC_firstname);
                $newphrase = str_replace($replace, $str_replaced, $emp_email_body);
                $final_message=$newphrase;
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
                $mail->Body    = $final_message;
                $mail->send();

            }

        $flag_array=[$flag];
        $con->commit();
        echo json_encode($flag_array);
    }
    //ROLE CREATION ENTRY
    if($_REQUEST['option']=="URSRC_check_role_id"){
        $URSRC_roleid=$_GET['URSRC_roleidval'];
        $sql="SELECT * FROM LMC_ROLE_CREATION where RC_NAME='$URSRC_roleid'";
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
    //TREE VIEW
    if($_REQUEST['option']=="URSRC_tree_views"){
        $role_customemrole_name = $_REQUEST['URSRC_lbrole_srchndupdate'];
        $role_customemrole_name=str_replace("_"," ",$role_customemrole_name);
        $rcname_result=mysqli_query($con,"SELECT * FROM LMC_ROLE_CREATION RC,LMC_USER_RIGHTS_CONFIGURATION URC where URC.URC_ID=RC.URC_ID and RC_NAME='".$role_customemrole_name."' ORDER BY URC_DATA ");
        while($row=mysqli_fetch_array($rcname_result)){
            $get_urcdata_array=$row["URC_DATA"];
        }
        $mpid_result=mysqli_query($con,"SELECT * FROM LMC_ROLE_CREATION RC,LMC_USER_MENU_DETAILS  UMD,LMC_MENU_PROFILE MP where MP.MP_ID=UMD.MP_ID and UMD.RC_ID=RC.RC_ID and RC_NAME='".$role_customemrole_name."' ");
        $get_mpid_array=array();
        while($row=mysqli_fetch_array($mpid_result)){
            $get_mpid_array[]=$row["MP_ID"];
        }
        $get_urcdata_array=str_replace("_"," ",$get_urcdata_array);
        $main_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM LMC_MENU_PROFILE MP,LMC_BASIC_MENU_PROFILE BMP,LMC_USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$get_urcdata_array."' ORDER BY MP_MNAME ASC ");
        $ure_values=array();
        $URSC_Main_menu_array=array();
        $i=0;
        while($row=mysqli_fetch_array($main_menu_data)){
            $URSC_Main_menu_array[]=$row["MP_MNAME"];
            $sub_menu_data= mysqli_query($con,"SELECT  MP_MSUB, MP.MP_ID FROM LMC_MENU_PROFILE MP,LMC_BASIC_MENU_PROFILE BMP,LMC_USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$get_urcdata_array."' and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND MP.MP_MSUB IS NOT NULL GROUP BY MP_MSUB ORDER BY MP.MP_MSUB ASC ");
            $URSC_sub_menu_row=array();
            $URSC_sub_sub_menu_row_col=array();
            $URSC_sub_sub_menu_row_col_data=array();
            $j=0;
            while($row=mysqli_fetch_array($sub_menu_data))  {
                $URSC_sub_menu_row[]=array($row["MP_ID"],$row["MP_MSUB"]);
                $sub_sub_menu_data= mysqli_query($con,"SELECT MP.MP_ID, MP_MSUBMENU FROM LMC_MENU_PROFILE MP,LMC_BASIC_MENU_PROFILE BMP,LMC_USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$get_urcdata_array ."' and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND  MP.MP_MSUB='".$URSC_sub_menu_row[$j][1]."' AND MP.MP_MSUBMENU IS NOT NULL  ORDER BY MP_MSUBMENU ASC" );
                $URSC_sub_sub_menu_row=array();
                $URSC_sub_sub_menu_row_data=array();
                while($row=mysqli_fetch_array($sub_sub_menu_data)){
                    $URSC_sub_sub_menu_row_data[]=array($row["MP_ID"],$row["MP_MSUBMENU"]);
                }
                $URSC_sub_sub_menu_row_col[]=$URSC_sub_sub_menu_row;
                $URSC_sub_sub_menu_data_array[]=$URSC_sub_sub_menu_row_data;
                $j++;
            }
            $URSC_sub_sub_menu_array[]=$URSC_sub_sub_menu_row_col;
            $URSC_sub_menu_array[]=$URSC_sub_menu_row;
            $i++;
        }
        $final_values=array($URSC_Main_menu_array, $URSC_sub_menu_array,$URSC_sub_sub_menu_data_array);
        $role_mpid_array=array($get_urcdata_array,$get_mpid_array,$final_values);
        echo json_encode($role_mpid_array);
    }
    //Role creation save and update & Basic role menu creation save and update
    if($_REQUEST['option']=="rolecreationsave")
    {
        $URSRC_radio_button_select_value = $_REQUEST['URSRC_mainradiobutton'];
        $URSRC_radio_button_select_value=str_replace("_"," ",$URSRC_radio_button_select_value);
        $URSRC_customrolename=$_POST['URSRC_tb_customrole'];
        $URSRC_customrolenameupd=$_POST['URSRC_lb_rolename'];
        $URSRC_basicrole=$_POST['basicroles'];
        $URSRC_basicrole=str_replace("_"," ",$URSRC_basicrole);
        $URSRC_menu=$_POST['menu'];
        $URSRC_menuid;
        $URSRC_sub_submenu=$_POST['Sub_menu1'];
        $URSRC_submenu=$_POST['Sub_menu'];
        $URSRC_sub_submenu_array=array();
        $submenu_array=array();
        $menu_array=array();
        $sub_menu_menus=array();
        $length=count($URSRC_submenu);
        $sub_menu1_length=count($URSRC_sub_submenu);
        $URSRC_checkbox_basicrole=$_POST['URSRC_cb_basicroles1'];
        $URSRC_checkbox_basicrole=str_replace("_"," ",$URSRC_checkbox_basicrole);
        $URSRC_rd_basicrole=$_POST['URSRC_radio_basicroles1'];
        $URSRC_rd_basicrole=str_replace("_"," ",$URSRC_rd_basicrole);
        $projectid;
        $id;
        $ids;
        $flag=0;
        for($i=0;$i<$length;$i++){
            if (!(preg_match('/&&/',$URSRC_submenu[$i])))
            {
                $sub_menu_menus[]=$URSRC_submenu[$i];
            }
        }
        if($sub_menu1_length!=0){
            for($j=0;$j<$sub_menu1_length;$j++){
                $sub_menu_menus[]=$URSRC_sub_submenu[$j];
            }
        }
        for($j=0;$j<count($sub_menu_menus);$j++){
            if($j==0){
                $id=$sub_menu_menus[$j];
            }
            else{
                $id=$id .",".$sub_menu_menus[$j];
            }
        }
        if($URSRC_radio_button_select_value=="ROLE CREATION"){
            $result = $con->query("CALL SP_TS_ROLE_CREATION_INSERT('$URSRC_customrolename','$URSRC_basicrole','$id','$USERSTAMP',@ROLE_CRTNINSRTFLAG)");
            if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
            $select = $con->query('SELECT @ROLE_CRTNINSRTFLAG');
            $result = $select->fetch_assoc();
            $flag= $result['@ROLE_CRTNINSRTFLAG'];
            echo $flag;
        }
        else if($URSRC_radio_button_select_value=="BASIC ROLE MENU CREATION"||$URSRC_radio_button_select_value=="BASIC ROLE MENU SEARCH UPDATE"){
            $length=count($URSRC_checkbox_basicrole);
            $URSRC_checkbox_basicrole_array;
            for($i=0;$i<$length;$i++){
                if($i==0){
                    $URSRC_checkbox_basicrole_array=$URSRC_checkbox_basicrole[$i];
                }
                else{
                    $URSRC_checkbox_basicrole_array=$URSRC_checkbox_basicrole_array .",".$URSRC_checkbox_basicrole[$i];
                }
            }
            if($URSRC_radio_button_select_value=="BASIC ROLE MENU CREATION"){
                $result = $con->query("CALL  SP_TS_USER_RIGHTS_BASIC_PROFILE_SAVE('$USERSTAMP','$URSRC_rd_basicrole','$URSRC_checkbox_basicrole_array','$id',@BASIC_PROFILESAVEFLAG)");
                if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
                $select = $con->query('SELECT @BASIC_PROFILESAVEFLAG');
                $result = $select->fetch_assoc();
                $flag= $result['@BASIC_PROFILESAVEFLAG'];
                echo $flag;
            }
            else if($URSRC_radio_button_select_value=="BASIC ROLE MENU SEARCH UPDATE"){
                $result = $con->query("CALL  SP_TS_USER_RIGHTS_BASIC_PROFILE_UPDATE('$USERSTAMP','$URSRC_rd_basicrole','$URSRC_checkbox_basicrole_array','$id',@BASIC_PRFUPDATE)");
                if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
                $select = $con->query('SELECT @BASIC_PRFUPDATE');
                $result = $select->fetch_assoc();
                $flag= $result['@BASIC_PRFUPDATE'];
                echo $flag;
            }
        }
        else{
            $result = $con->query("CALL SP_TS_ROLE_CREATION_UPDATE('$URSRC_customrolenameupd','$URSRC_basicrole','$id','$USERSTAMP',@ROLE_CREATIONUPDATE)");
            if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
            $select = $con->query('SELECT @ROLE_CREATIONUPDATE');
            $result = $select->fetch_assoc();
            $flag= $result['@ROLE_CREATIONUPDATE'];
            echo $flag;
        }
    }
    //BASIC ROLE MENU CREATION URSRC_check_basicrole
    if($_REQUEST['option']=='URSRC_check_basicrolemenu')
    {
        $role=$_REQUEST['URSRC_basicradio_value'];
        $role=str_replace("_"," ",$role);
        $URSRC_select_check_basicrole_menu=mysqli_query($con,"select * from LMC_BASIC_MENU_PROFILE BMP,LMC_USER_RIGHTS_CONFIGURATION URC where URC.URC_ID=BMP.URC_ID and URC.URC_DATA='$role'");
        $row=mysqli_num_rows($URSRC_select_check_basicrole_menu);
        $x=$row;
        if($x > 0)
        {
            $URSRC_check_basicrole_menu=0;//TRUE
        }
        else{
            $URSRC_check_basicrole_menu=1;//FALSE
        }
        echo ($URSRC_check_basicrole_menu);
    }
    //FUNCTION to get basic role menus
    if($_REQUEST['option']=="URSRC_loadbasicrole_menu"){
        $URSRC_basicrole_values_array=array();
        $URSRC_basic_roleval=$_REQUEST['URSRC_basicradio_value'];
        $URSRC_basic_roleval=str_replace("_"," ",$URSRC_basic_roleval);
        $URSRC_basicrole_menu_array=array();
        $URSRC_basicroleid_array=array();
        $URSRC_select_basicrole_menu= "select * from LMC_USER_RIGHTS_CONFIGURATION URC,LMC_BASIC_MENU_PROFILE BMP where URC.URC_ID=BMP.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval."'";
        $URSRC_basicrole_menu_rs=mysqli_query($con,$URSRC_select_basicrole_menu);
        while($row=mysqli_fetch_array($URSRC_basicrole_menu_rs)){
            $URSRC_basicrole_menu_array[]=$row["MP_ID"];
        }
        $select_basicrole_id= "select * from LMC_USER_RIGHTS_CONFIGURATION URC,LMC_BASIC_ROLE_PROFILE BRP where URC.URC_DATA='".$URSRC_basic_roleval."' and URC.URC_ID=BRP.URC_ID";
        $URSRC_basicroleid_rs=mysqli_query($con,$select_basicrole_id);
        while($row=mysqli_fetch_array($URSRC_basicroleid_rs)){
            $URSRC_basicroleid_array[]=$row["BRP_BR_ID"];
        }
        $URSRC_basicrole_array=array();

        for($i=0;$i<count($URSRC_basicroleid_array);$i++){
            $select_basicrole=mysqli_query($con,"select * from LMC_USER_RIGHTS_CONFIGURATION URC,LMC_BASIC_ROLE_PROFILE BRP where  BRP.BRP_BR_ID=URC.URC_ID and BRP.BRP_BR_ID='".$URSRC_basicroleid_array[$i]."' order by URC_DATA asc ");
            while($row=mysqli_fetch_array($select_basicrole)){
                $URSRC_basicrole_array[]=$row["URC_DATA"];
            }
        }
        //UNIQUE FUNCTION
        $URSRC_basicrole_array=array_values(array_unique($URSRC_basicrole_array));
        $fullarray=URSRC_getmenubasic_folder1();
        $value_array=array($URSRC_basicrole_menu_array,$URSRC_basicrole_array,$fullarray);
        echo JSON_ENCODE($value_array);
    }
    //FUNCTION to get role menus
    if($_REQUEST['option']=="URSRC_tree_view"){
        $menunameradiovalues = $_GET['radio_value'];
        $URSRC_basic_roleval=str_replace("_"," ",$menunameradiovalues);
        $URSRC_getmenu_folder_values=URSRC_getmenu_folder($URSRC_basic_roleval);
        echo JSON_ENCODE($URSRC_getmenu_folder_values);
    }
    //FUNCTION to get basic menus
    if($_REQUEST['option']=="URSRC_tree_view_basic"){
        $menunameradiovalues = $_GET['radio_value'];
        $URSRC_basic_roleval=str_replace("_"," ",$menunameradiovalues);
        $URSRC_getmenu_folder_values=URSRC_getmenubasic_folder1();
        echo JSON_ENCODE($URSRC_getmenu_folder_values);
    }
    //FUNCTION TO LOAD INITIAL VALUES ROLE LST bX
    if($_REQUEST['option']=="ACCESS_RIGHTS_SEARCH_UPDATE_BASICROLE"){
        $URSRC_role_array=get_roles();
        echo JSON_ENCODE($URSRC_role_array);
    }
}

//COMMON TREE SEARCH ND UPDATE FUNCTION
function URSRC_getmenu_folder($URSRC_basic_roleval){
    global $con;
    $main_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM LMC_MENU_PROFILE MP,LMC_BASIC_MENU_PROFILE BMP,LMC_USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval."' ORDER BY MP_MNAME ASC ");
    $ure_values=array();
    $URSC_Main_menu_array=array();
    $i=0;
    while($row=mysqli_fetch_array($main_menu_data)){
        $URSC_Main_menu_array[]=$row["MP_MNAME"];
        $sub_menu_data= mysqli_query($con,"SELECT  MP_MSUB, MP.MP_ID FROM LMC_MENU_PROFILE MP,LMC_BASIC_MENU_PROFILE BMP,LMC_USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval."' and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND MP.MP_MSUB IS NOT NULL GROUP BY MP_MSUB ORDER BY MP.MP_MSUB ASC ");
        $URSC_sub_menu_row=array();
        $URSC_sub_sub_menu_row_col=array();
        $URSC_sub_sub_menu_row_col_data=array();
        $j=0;
        while($row=mysqli_fetch_array($sub_menu_data))  {
            $URSC_sub_menu_row[]=array($row["MP_ID"],$row["MP_MSUB"]);
            $sub_sub_menu_data= mysqli_query($con,"SELECT MP.MP_ID, MP_MSUBMENU FROM LMC_MENU_PROFILE MP,LMC_BASIC_MENU_PROFILE BMP,LMC_USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval ."' and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND  MP.MP_MSUB='".$URSC_sub_menu_row[$j][1]."' AND MP.MP_MSUBMENU IS NOT NULL  ORDER BY MP_MSUBMENU ASC" );
            $URSC_sub_sub_menu_row=array();
            $URSC_sub_sub_menu_row_data=array();
            while($row=mysqli_fetch_array($sub_sub_menu_data)){
                $URSC_sub_sub_menu_row_data[]=array($row["MP_ID"],$row["MP_MSUBMENU"]);
            }
            $URSC_sub_sub_menu_row_col[]=$URSC_sub_sub_menu_row;
            $URSC_sub_sub_menu_data_array[]=$URSC_sub_sub_menu_row_data;
            $j++;
        }
        $URSC_sub_sub_menu_array[]=$URSC_sub_sub_menu_row_col;
        $URSC_sub_menu_array[]=$URSC_sub_menu_row;
        $i++;
    }
    $final_values=array($URSC_Main_menu_array, $URSC_sub_menu_array,$URSC_sub_sub_menu_data_array);
    return $final_values;
}

//COMMON TREE SEARCH ND UPDATE FUNCTION
function URSRC_getmenubasic_folder($URSRC_basic_roleval){
    global $con;
    $main_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM LMC_MENU_PROFILE MP,LMC_BASIC_MENU_PROFILE BMP,LMC_USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval."' ORDER BY MP_MNAME ASC ");
    $ure_values=array();
    $URSC_Main_menu_array=array();
    $i=0;
    while($row=mysqli_fetch_array($main_menu_data)){
        $URSC_Main_menu_array[]=$row["MP_MNAME"];
        $sub_menu_data= mysqli_query($con,"SELECT  MP_MSUB, MP.MP_ID FROM LMC_MENU_PROFILE MP,LMC_BASIC_MENU_PROFILE BMP,LMC_USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval."'  and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' and URC.URC_DATA='".$URSRC_basic_roleval."' AND MP.MP_MSUB IS NOT NULL GROUP BY MP_MSUB ORDER BY MP.MP_MSUB ASC ");
        $URSC_sub_menu_row=array();
        $URSC_sub_sub_menu_row_col=array();
        $URSC_sub_sub_menu_row_col_data=array();
        $j=0;
        while($row=mysqli_fetch_array($sub_menu_data))  {
            $URSC_sub_menu_row[]=array($row["MP_ID"],$row["MP_MSUB"]);
            $sub_sub_menu_data= mysqli_query($con,"SELECT MP.MP_ID, MP_MSUBMENU FROM LMC_MENU_PROFILE MP,LMC_BASIC_MENU_PROFILE BMP,LMC_USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval."' and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND  MP.MP_MSUB='".$URSC_sub_menu_row[$j][1]."' AND MP.MP_MSUBMENU IS NOT NULL  ORDER BY MP_MSUBMENU ASC" );
            $URSC_sub_sub_menu_row=array();
            $URSC_sub_sub_menu_row_data=array();
            while($row=mysqli_fetch_array($sub_sub_menu_data)){
                $URSC_sub_sub_menu_row_data[]=array($row["MP_ID"],$row["MP_MSUBMENU"]);
            }
            $URSC_sub_sub_menu_row_col[]=$URSC_sub_sub_menu_row;
            $URSC_sub_sub_menu_data_array[]=$URSC_sub_sub_menu_row_data;
            $j++;
        }
        $URSC_sub_sub_menu_array[]=$URSC_sub_sub_menu_row_col;
        $URSC_sub_menu_array[]=$URSC_sub_menu_row;
        $i++;
    }
    $final_values=array($URSC_Main_menu_array, $URSC_sub_menu_array,$URSC_sub_sub_menu_data_array);
    return $final_values;
}
function URSRC_getmenubasic_folder1(){
    global $con;
    $main_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM LMC_MENU_PROFILE MP ORDER BY MP_MNAME ASC ");
    $ure_values=array();
    $URSC_Main_menu_array=array();
    $i=0;
    while($row=mysqli_fetch_array($main_menu_data)){
        $URSC_Main_menu_array[]=$row["MP_MNAME"];
        $sub_menu_data= mysqli_query($con,"SELECT  MP_MSUB, MP.MP_ID FROM LMC_MENU_PROFILE MP WHERE MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND MP.MP_MSUB IS NOT NULL GROUP BY MP_MSUB ORDER BY MP.MP_MSUB ASC ");
        $URSC_sub_menu_row=array();
        $URSC_sub_sub_menu_row_col=array();
        $URSC_sub_sub_menu_row_col_data=array();
        $j=0;
        while($row=mysqli_fetch_array($sub_menu_data))  {
            $URSC_sub_menu_row[]=array($row["MP_ID"],$row["MP_MSUB"]);
            $sub_sub_menu_data= mysqli_query($con,"SELECT MP.MP_ID, MP_MSUBMENU FROM LMC_MENU_PROFILE MP WHERE MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND  MP.MP_MSUB='".$URSC_sub_menu_row[$j][1]."' AND MP.MP_MSUBMENU IS NOT NULL  ORDER BY MP_MSUBMENU ASC" );
            $URSC_sub_sub_menu_row=array();
            $URSC_sub_sub_menu_row_data=array();
            while($row=mysqli_fetch_array($sub_sub_menu_data)){
                $URSC_sub_sub_menu_row_data[]=array($row["MP_ID"],$row["MP_MSUBMENU"]);
            }
            $URSC_sub_sub_menu_row_col[]=$URSC_sub_sub_menu_row;
            $URSC_sub_sub_menu_data_array[]=$URSC_sub_sub_menu_row_data;
            $j++;
        }
        $URSC_sub_sub_menu_array[]=$URSC_sub_sub_menu_row_col;
        $URSC_sub_menu_array[]=$URSC_sub_menu_row;
        $i++;
    }
    $final_values=array($URSC_Main_menu_array, $URSC_sub_menu_array,$URSC_sub_sub_menu_data_array);
    return $final_values;
}
?>