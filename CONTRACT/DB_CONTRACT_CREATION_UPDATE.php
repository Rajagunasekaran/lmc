<?php
error_reporting(0);
include "../LMC_LIB/CONNECTION.php";
include "../LMC_LIB/GET_USERSTAMP.php";
include "../LMC_LIB/COMMON.php";
$dir=dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR;
$userstamp_id=mysqli_query($con,"SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS  WHERE ULD_USERNAME='$UserStamp'");
if($row=mysqli_fetch_array($userstamp_id))
{
    $uldid=$row["ULD_ID"];
}
if($_REQUEST['option']=='INITIAL_DATA') {
//TEAM NAME
    $teamname = mysqli_query($con, "SELECT TC_ID,TEAM_NAME FROM LMC_TEAM_CREATION ORDER BY TEAM_NAME ASC");
    while ($row = mysqli_fetch_array($teamname)) {
        $empteamname[] = array($row['TEAM_NAME'], $row['TC_ID']);
    }
//STATUS
    $status = mysqli_query($con, "SELECT LCS_ID,LCS_STATUS FROM LMC_CONTRACT_STATUS ORDER BY LCS_STATUS ASC");
    while ($row = mysqli_fetch_array($status)) {
        $jobstatus[] = array($row['LCS_STATUS'], $row['LCS_ID']);
    }
    $errormsg=get_error_msg('3,4,7,17');
    $values = array($empteamname, $jobstatus,$errormsg);
    echo json_encode($values);
}
elseif($_REQUEST['option']=='contractsaveupdate')
{
    $CC_contractno=$_REQUEST['CC_contractid'];
    $CC_customername=$_REQUEST['CC_customername'];
    $CC_contactperson=$_REQUEST['CC_contactperson'];
    $CC_location=$_REQUEST['CC_location'];
    $CC_address=$_REQUEST['CC_address'];
    $CC_address=$con->real_escape_string($CC_address);
    $CC_paymentterm=$_REQUEST['CC_paymentterm'];
    $CC_nextnumber=$_REQUEST['CC_nextnumber'];
    $CC_type=$_REQUEST['CC_type'];
    if($CC_type=='SELECT'){$CC_type='';}
    $CC_createddate=$_REQUEST['CC_createddate'];
    if($CC_createddate!='')
    $CC_createddate=date('Y-m-d',strtotime($CC_createddate));
    $CC_enddate=$_REQUEST['CC_enddate'];
    if($CC_enddate!='')
    $CC_enddate=date('Y-m-d',strtotime($CC_enddate));
    $CC_extendeddate=$_REQUEST['CC_extendeddate'];
    if($CC_extendeddate!='')
    $CC_extendeddate=date('Y-m-d',strtotime($CC_extendeddate));
    $CC_telphoneno=$_REQUEST['CC_telphoneno'];
    $CC_faxno=$_REQUEST['CC_faxno'];
    $CC_email=$_REQUEST['CC_email'];
    $CC_website=$_REQUEST['CC_website'];
    $CC_inchargeperson=$_REQUEST['CC_inchargeperson'];
    $CC_hpno=$_REQUEST['CC_hpno'];
    $CC_amount=$_REQUEST['CC_amount'];
    $CC_remark1=$_REQUEST['CC_remark1'];
    $CC_remark1=$con->real_escape_string($CC_remark1);
    $CC_remark2=$_REQUEST['CC_remark2'];
    $CC_remark2=$con->real_escape_string($CC_remark2);
    $CC_team=$_REQUEST['CC_team'];
    $CC_notification=$_REQUEST['CC_notification'];
    $CC_status=$_REQUEST['CC_status'];
    $CC_machineryusage=$_REQUEST['CC_machineryusage'];
    $uploadcount=$_REQUEST['upload_count'];
    $option=$_REQUEST['buttonvalue'];
    $rowid=$_REQUEST['rowid'];
    $uploadcount=$_REQUEST['upload_count'];
    $oldfilename=$_REQUEST['removedfilename'];

    if($option=='SAVE') {
        //FOLDER CREATION FOR EACH CONTRACT
        $parent_attach_folder_name="CONTRACT_UPLOADFILES";
        $attach_sub_folder_name=$CC_contractno;
        $detele_foldername=$dir.$parent_attach_folder_name.DIRECTORY_SEPARATOR.$attach_sub_folder_name;
        $attch_file_folder=$dir.$parent_attach_folder_name.DIRECTORY_SEPARATOR.$attach_sub_folder_name.DIRECTORY_SEPARATOR;
        if (!file_exists($attch_file_folder)) {
            $makedirfile=mkdir($attch_file_folder);
            chmod($makedirfile,0777);
        }
        for($x=1;$x<=$uploadcount;$x++)
        {
            if($_FILES['upload_filename'.$x]['name']!=''){
                $attach_file_name=$_FILES['upload_filename'.$x]['name'];
                $moveResult = move_uploaded_file($_FILES['upload_filename'.$x]['tmp_name'],$attch_file_folder.$attach_file_name);
                $upload_file_array[]=$attach_file_name;
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
        if($upload_filename=='')
        {
            $uploadfile='';
        }
        else {
            $uploadfile = $con->real_escape_string($upload_filename);
        }
        //CALL QUERY FOR INSERT
        $insertcallquery = "CALL SP_CONTRACT_CREATION_UPDATE(1,'','$CC_customername','$CC_contractno','$CC_createddate','$CC_enddate','$CC_extendeddate','$CC_notification',
    '$CC_contactperson','$CC_address','$CC_paymentterm','$CC_nextnumber','$CC_type','$CC_telphoneno','$CC_faxno','$CC_email','$CC_website',
    '$CC_inchargeperson','$CC_hpno','$CC_amount','$CC_remark1','$CC_remark2','$CC_team','$CC_status','$uploadfile','$uploadfile','$UserStamp',@SUCCESS_MESSAGE)";
    }
    else{
        //GET OLD FILE NAME
        if($oldfilename=='undefined')
        {
            $sql_filename="SELECT CLD_APPROVED_FILE_ID FROM LMC_CONTRACT_DETAILS WHERE CLD_CONTRACT_NO='$CC_contractno'";
            $sql_result= mysqli_query($con,$sql_filename);
            if($row=mysqli_fetch_array($sql_result)){
                $oldfilename=$row["CLD_APPROVED_FILE_ID"];
            }
        }
        $parent_attach_folder_name="CONTRACT_UPLOADFILES";
        $attach_sub_folder_name=$CC_contractno;
        $attch_file_folder=$dir.$parent_attach_folder_name.DIRECTORY_SEPARATOR.$attach_sub_folder_name.DIRECTORY_SEPARATOR;
        $uploadcount=$_REQUEST['upload_count'];
        for($x=1;$x<=$uploadcount;$x++)
        {
            if($_FILES['upload_filename'.$x]['name']!=''){
                $attach_file_name=$_FILES['upload_filename'.$x]['name'];
                $moveResult=move_uploaded_file($_FILES['upload_filename'.$x]['tmp_name'],$attch_file_folder.$attach_file_name);
                $upload_file_array[]=$attach_file_name;
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
        if($upload_filename=='')
        {
            $uploadfile='';
        }
        else {
            $uploadfile = $con->real_escape_string($upload_filename);
        }
        //CALL QUERY FOR UPDATE
        $insertcallquery = "CALL SP_CONTRACT_CREATION_UPDATE(2,'$rowid','$CC_customername','$CC_contractno','$CC_createddate','$CC_enddate','$CC_extendeddate','$CC_notification',
    '$CC_contactperson','$CC_address','$CC_paymentterm','$CC_nextnumber','$CC_type','$CC_telphoneno','$CC_faxno','$CC_email','$CC_website',
    '$CC_inchargeperson','$CC_hpno','$CC_amount','$CC_remark1','$CC_remark2','$CC_team','$CC_status','$uploadfile','$uploadfile','$UserStamp',@SUCCESS_MESSAGE)";
    }
    $result = $con->query($insertcallquery);
    if(!$result){
        $delete_attchfile = $attch_file_folder;
        foreach(glob($delete_attchfile.'*.*') as $v){
            unlink($v);
        }
        rmdir($delete_attchfile);
        rmdir($detele_foldername);
        die("CALL failed: (" . $con->errno . ") " . $con->error);
    }
    $select = $con->query('SELECT @SUCCESS_MESSAGE');
    $result = $select->fetch_assoc();
    $flag= $result['@SUCCESS_MESSAGE'];
    echo $flag;
}
elseif($_REQUEST['option']=='search_data')
{
    $selectquery = mysqli_query($con, "SELECT A.*, B.TEAM_NAME,C.LCS_STATUS,D.ULD_USERNAME FROM LMC_CONTRACT_DETAILS A,LMC_TEAM_CREATION B,LMC_CONTRACT_STATUS C,LMC_USER_LOGIN_DETAILS D WHERE A.LCS_ID=C.LCS_ID AND A.TC_ID=B.TC_ID AND A.ULD_ID=D.ULD_ID ORDER BY A.CLD_CONTRACT_NO ASC");
    $arrayvalues=[];
    while ($row = mysqli_fetch_array($selectquery)) {
        $contractno=$row['CLD_CONTRACT_NO'];
        $customername=$row['CLD_CUSTOMER_NAME'];
        $createddate=$row['CLD_CREATED_DATE'];
        if($createddate!="0000-00-00") {
            $createddate = date('d-m-Y', strtotime($createddate));
        }
        else{$createddate='';}
        $enddate=$row['CLD_END_DATE'];
        if($enddate!="0000-00-00") {
            $enddate = date('d-m-Y', strtotime($enddate));
        }
        else{$enddate='';}
        $extendeddate=$row['CLD_EXTENDED_DATE'];
        if($extendeddate!="0000-00-00") {
            $extendeddate = date('d-m-Y', strtotime($extendeddate));
        }
        else{$extendeddate='';}
        $contactperson=$row['CLD_CONTACT_PERSON'];
        $notifications=$row['CLD_NOTIFICATIONS'];
        $address=$row['CLD_ADDRESS'];
        $paymentterm=$row['CLD_PAYMENT_TERM'];
        $nextnumber=$row['CLD_NEXT_NUMBER'];
        $type=$row['CLD_TYPE'];
        $telno=$row['CLD_TEL_NO'];
        $faxno=$row['CLD_FAX_NO'];
        $mail=$row['CLD_EMAIL'];
        $website=$row['CLD_WEBSITE'];
        $inchargeperson=$row['CLD_INCHARGE_PERSON'];
        $hpno=$row['CLD_HP_NO'];
        $amount=$row['CLD_AMOUNT'];
        $remark1=$row['CLD_REMARK1'];
        $remark2=$row['CLD_REMARK2'];
        $teamname=$row['TEAM_NAME'];
        $status=$row['LCS_STATUS'];
        $approvedfileid=$row['CLD_APPROVED_FILE_ID'];
        $completedfileid=$row['CLD_COMPLETED_FILE_ID'];
        $rowid=$row['CLD_ID'];
        $userstamp=$row['ULD_USERNAME'];
        $timestamp=$row['CLD_TIMESTAMP'];
        $timestamp=date('d-m-Y H:i:s',strtotime($timestamp));
        $arrayvalues[]=(object)['rowid'=>$rowid,'contractno'=>$contractno,'customername'=>$customername,'createddate'=>$createddate,'endate'=>$enddate,'extendeddate'=>$extendeddate,'contactperson'=>$contactperson,'notifications'=>$notifications,'address'=>$address,'paymentterm'=>$paymentterm,'nextnumber'=>$nextnumber,'type'=>$type,'telno'=>$telno,'faxno'=>$faxno,'mail'=>$mail,'website'=>$website,'inchargeperson'=>$inchargeperson,'hpno'=>$hpno,'amount'=>$amount,'remark1'=>$remark1,'remark2'=>$remark2,'teamname'=>$teamname,'status'=>$status,'approvedfile'=>$approvedfileid,'completedfile'=>$completedfileid,'userstamp'=>$userstamp,'timestamp'=>$timestamp];
    }
    echo json_encode($arrayvalues);
}