<?php
error_reporting(0);
include "CONNECTION.php";
include "GET_USERSTAMP.php";
include "COMMON.php";
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
    $sqlquery="CALL SP_INSERT_UPDATE_ACCIDENT_DETAILS(1,'','$dateofaccident','$placeofaccident','$typeofinjury','$natureofinjury','$timeofaccident','$locationofaccident',
    '$partsofinjured','$typeofmachinery','$lmno','$nameofoperator','$name',$age,'$addrssofinjured','$nricno','$finno',$workspermit,'$passportno',
    '$nationality','$gender','$dateofbirth','$maritalstatus','$designation','$lengthofservice','$commens','$description','$UserStamp',@SUCCESS_FLAG)";
    $result = $con->query($sqlquery);
    if(!$result){
        die("CALL failed: (" . $con->errno . ") " . $con->error);
    }
    $select = $con->query('SELECT @SUCCESS_FLAG');
    $result = $select->fetch_assoc();
    $flag= $result['@SUCCESS_FLAG'];
    echo $flag;
}

