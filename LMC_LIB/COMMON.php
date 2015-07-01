<?php
error_reporting(0);
include "CONNECTION.php";
include "GET_USERSTAMP.php";
$USERSTAMP=$UserStamp;
date_default_timezone_set('Asia/Singapore');
global $con;

//GET SINGLE EMP_NAME
function get_empname(){
    global $USERSTAMP;
    global $con;
    $uld_id=mysqli_query($con,"select EMPLOYEE_NAME from VW_TS_ALL_EMPLOYEE_DETAILS where ULD_LOGINID='$USERSTAMP' AND ULD_ID=(select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP')");
    while($row=mysqli_fetch_array($uld_id)){
        $ure_empname=$row["EMPLOYEE_NAME"];
    }
    return $ure_empname;
}
//GET ULD_ID
function get_uldid(){
    global $USERSTAMP;
    global $con;
    $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
    while($row=mysqli_fetch_array($uld_id)){
        $ure_uld_id=$row["ULD_ID"];
    }
    return $ure_uld_id;
}
//GET JOIN DATE FOR SELECTING LOGIN ID;
function get_joindate($ure_uld_id){
    global $con;
    $min_date=mysqli_query($con,"SELECT UA_JOIN_DATE FROM LMC_USER_ACCESS where ULD_ID='$ure_uld_id' AND UA_TERMINATE IS NULL");
    while($row=mysqli_fetch_array($min_date)){
        $mindate_array=$row["UA_JOIN_DATE"];
        $min_date = date('d-m-Y',strtotime($mindate_array));
    }
    return  $min_date;
}
function get_parentfolder_id(){
    global $con;
    $parentid=mysqli_query($con,"SELECT URC_DATA FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=5");
    while($row=mysqli_fetch_array($parentid)){
        $parentfolder_id=$row["URC_DATA"];
    }
    return  $parentfolder_id;
}
function get_docfolder_id(){
    global $con;
    $docid=mysqli_query($con,"SELECT URC_DATA FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=6");
    while($row=mysqli_fetch_array($docid)){
        $docfolder_id=$row["URC_DATA"];
    }
    return  $docfolder_id;
}
function get_reportdocfolder_id(){
    global $con;
    $docparentid=mysqli_query($con,"SELECT URC_DATA FROM LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=15");
    while($row=mysqli_fetch_array($docparentid)){
        $reportdocfolder=$row["URC_DATA"];
    }
    return  $reportdocfolder;
}
function get_emp_folderid($ULD_ID){
    global $con;
    $select_folderid=mysqli_query($con,"SELECT ULD_IMAGE_FOLDER_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_ID='$ULD_ID'");
    if($row=mysqli_fetch_array($select_folderid)){
        $folder_id=$row["ULD_IMAGE_FOLDER_ID"];
    }
    return $folder_id;
}

//GET ACTIVE LOGIN ID;
function get_active_login_id(){
    global $con;
    $loginid=mysqli_query($con,"SELECT ULD_USERNAME from VW_ACCESS_RIGHTS_TERMINATE_LOGINID where URC_DATA!='SUPER ADMIN' ORDER BY ULD_LOGINID");
    $login_array=array();
    while($row=mysqli_fetch_array($loginid)){
        $login_array[]=$row["ULD_USERNAME"];
    }
    return $login_array;
}
//GET NON ACTIVE LOGIN ID
function get_nonactive_login_id(){
    global $con;
    $activenonemp=mysqli_query($con,"SELECT * from VW_ACCESS_RIGHTS_REJOIN_LOGINID ORDER BY ULD_LOGINID");
    $active_nonemp=array();
    while($row=mysqli_fetch_array($activenonemp)){
        $active_nonemp[]=$row["ULD_USERNAME"];
    }
    return $active_nonemp;
}
//GET ACTIVE EMPLOYEE ID;
function get_active_emp_id(){
    global $con;
    $loginid=mysqli_query($con,"SELECT * from VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS where URC_DATA!='SUPER ADMIN' ORDER BY EMPLOYEE_NAME");
    $active_array=array();
    while($row=mysqli_fetch_array($loginid)){
        $active_array[]=array($row["EMPLOYEE_NAME"],$row["ULD_ID"],$row['ULD_USERNAME']);
    }
    return $active_array;
}
//GET NON ACTIVE EMPLOYEE ID
function get_nonactive_emp_id(){
    global $con;
    $activenonemp=mysqli_query($con,"SELECT * from VW_TS_ALL_NON_ACTIVE_EMPLOYEE_DETAILS ORDER BY EMPLOYEE_NAME");
    $active_nonemp=array();
    while($row=mysqli_fetch_array($activenonemp)){
        $active_nonemp[]=array($row["EMPLOYEE_NAME"],$row["ULD_ID"]);
    }
    return $active_nonemp;
}
function get_company_start_date(){

    global $con;
    $comp_sdate=mysqli_query($con,"SELECT * from LMC_USER_RIGHTS_CONFIGURATION WHERE URC_ID=4");
    while($row=mysqli_fetch_array($comp_sdate)){
        $comp_startdate=$row["URC_DATA"];
    }
    $comp_startdate = date('d-m-Y',strtotime($comp_startdate));
    return $comp_startdate;
}


//GET ERROR MSG
function get_error_msg($str){
    global $con;
    $errormessage=array();
    $errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM LMC_ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN ($str)");
    while($row=mysqli_fetch_array($errormsg)){
        $errormessage[]=$row["EMC_DATA"];
    }
    return $errormessage;
}

function ongoing_contractno(){
    global $con;
    $contractnos=array();
    $contractno=mysqli_query($con,"SELECT DISTINCT CLD_CONTRACT_NO FROM LMC_CONTRACT_DETAILS WHERE LCS_ID = 1");
    while($row=mysqli_fetch_array($contractno)){
        $contractnos[]=$row["CLD_CONTRACT_NO"];
    }
    return $contractnos;
}
function unitofmeasure(){
    global $con;
    $unitofmeasure=array();
    $uom=mysqli_query($con,"SELECT DISTINCT LMU_UNIT FROM LMC_MEASURE_UNIT ORDER BY LMU_UNIT");
    while($row=mysqli_fetch_array($uom)){
        $unitofmeasure[]=$row["LMU_UNIT"];
    }
    return $unitofmeasure;
}

if($_REQUEST["option"]=="USER_RIGHTS_TERMINATE"){
    $str='9,10,11,12,13,14,56,70,113,114,116,132,133,138,139,40';
    $errormsg_array= get_error_msg($str);
    $role_result=mysqli_query($con,"SELECT  RC_NAME,RC_ID FROM LMC_ROLE_CREATION;");
    $get_role_array=array();
    while($row=mysqli_fetch_array($role_result)){
        $get_role_array[]=array($row["RC_ID"],$row["RC_NAME"]);
    }
    $emp_type=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION where CGN_ID =6 ");
    $get_emptype_array=array();
    while($row=mysqli_fetch_array($emp_type)){
        $get_emptype_array[]=$row["URC_DATA"];
    }

    $get_team=mysqli_query($con,"SELECT * FROM LMC_TEAM_CREATION  ");
    $get_team_array=array();
    while($row=mysqli_fetch_array($get_team)){
        $get_team_array[]=$row["TEAM_NAME"];
    }

    $value_array=array($errormsg_array,$get_role_array,$get_emptype_array,$get_team_array);
    echo JSON_ENCODE($value_array);

}
function get_roles(){
    global $con;
    $rolecreation_result = mysqli_query($con,"SELECT * FROM LMC_ROLE_CREATION");
    $get_rolecreation_array=array();
    while($row=mysqli_fetch_array($rolecreation_result)){
        $get_rolecreation_array[]= $row["RC_NAME"];
    }

    return  $get_rolecreation_array;
}
if($_REQUEST["option"]=="ACCESS_RIGHTS_SEARCH_UPDATE")
{
    $str='40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,1,2,69,70,71,72,95,133,138,139';
    $URSRC_errmsg=get_error_msg($str);

    $get_rolecreation_array=get_roles();
    $project_result=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION where URC_ID in (1,2,3) ");
    $get_project_array=array();
    while($row=mysqli_fetch_array($project_result)){
        $get_project_array[]=$row["URC_DATA"];
    }
    $emp_type=mysqli_query($con,"SELECT * FROM LMC_USER_RIGHTS_CONFIGURATION where CGN_ID =6 ");
    $get_emptype_array=array();
    while($row=mysqli_fetch_array($emp_type)){
        $get_emptype_array[]=$row["URC_DATA"];
    }

    $get_team=mysqli_query($con,"SELECT * FROM LMC_TEAM_CREATION  ");
    $get_team_array=array();
    while($row=mysqli_fetch_array($get_team)){
        $get_team_array[]=$row["TEAM_NAME"];
    }



    $menuname_result=mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM LMC_MENU_PROFILE MP,LMC_USER_RIGHTS_CONFIGURATION URC");
    $get_menuname_array=array();
    while($row=mysqli_fetch_array($menuname_result)){
        $get_menuname_array[]=$row["MP_MNAME"];

    }
    //BASI ROLE

    $query= "select * from  LMC_USER_RIGHTS_CONFIGURATION URC,LMC_ROLE_CREATION RC,LMC_USER_LOGIN_DETAILS ULD,LMC_USER_ACCESS UA where ULD.ULD_ID=UA.ULD_ID and RC.RC_ID=UA.RC_ID and RC.URC_ID=URC.URC_ID and ULD.ULD_USERNAME='".$USERSTAMP."' ORDER BY URC_DATA ASC";
    $URSRC_select_basicrole_result=mysqli_query($con,$query);
    while($row=mysqli_fetch_array($URSRC_select_basicrole_result)){
        $URSRC_basicrole=$row["URC_DATA"];

    }
    $URSRC_basicroleid_array_result=mysqli_query($con,"select * from LMC_USER_RIGHTS_CONFIGURATION URC,LMC_BASIC_ROLE_PROFILE BRP where URC.URC_DATA='".$URSRC_basicrole."' and URC.URC_ID=BRP.URC_ID");
    $URSRC_basicroleid_array=array();
    while($row=mysqli_fetch_array($URSRC_basicroleid_array_result)){
        $URSRC_basicroleid_array[]=($row["BRP_BR_ID"]);
    }
    $get_URSRC_basicrole_profile_array=array();

    for($i=0;$i<count($URSRC_basicroleid_array);$i++){

        $URSRC_basicrole_profile_array_result=mysqli_query($con,"select * from LMC_USER_RIGHTS_CONFIGURATION URC,LMC_BASIC_ROLE_PROFILE BRP where  BRP.BRP_BR_ID=URC.URC_ID and BRP.BRP_BR_ID='".$URSRC_basicroleid_array[$i]."' order by URC_DATA asc ");
        while($row=mysqli_fetch_array($URSRC_basicrole_profile_array_result)){
            $get_URSRC_basicrole_profile_array[]=$row["URC_DATA"];
        }
    }
    $get_URSRC_basicrole_profile_array=array_values(array_unique($get_URSRC_basicrole_profile_array));
    $comp_startdate=get_company_start_date();

    $value_array=array($get_rolecreation_array,$get_project_array,$get_menuname_array,$get_URSRC_basicrole_profile_array,$URSRC_errmsg,$get_emptype_array,$comp_startdate,$get_team_array);
    echo JSON_ENCODE($value_array);
}

if($_REQUEST["option"]=="EMAIL_TEMPLATE_ENTRY"){
    $error='71,85,86';
    $error_array=get_error_msg($error);
    $values_array=array($error_array);
    echo JSON_ENCODE($values_array);
}

if($_REQUEST["option"]=="RESET_FORM"){
    $str='20,138,139';
    $URSRC_errmsg=get_error_msg($str);
    echo JSON_ENCODE($URSRC_errmsg);

}



?>