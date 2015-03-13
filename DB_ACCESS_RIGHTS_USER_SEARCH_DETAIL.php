<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************USER SEARCH DETAILS*********************************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:26/02/2015 ED:26/02/2015,TRACKER NO:1
//*********************************************************************************************************//
//error_reporting(0);

    include "CONNECTION.php";
    //GETTING ERR MSG
$errormessages=array();
$errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM LMC_ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN (15,99)");
while($row=mysqli_fetch_array($errormsg)){
    $errormessages[]=$row["EMC_DATA"];
}
//FETCHING USER LOGIN DETAILS RECORDS
    $date= mysqli_query($con,"SELECT AE.EMPLOYEE_NAME,RC.RC_NAME,UA.UA_REC_VER,URC1.URC_DATA,UA.UA_REASON,UA.UA_USERSTAMP,DATE_FORMAT(UA.UA_JOIN_DATE,'%d-%m-%Y') AS UA_JOIN_DATE,DATE_FORMAT(UA.UA_END_DATE,'%d-%m-%Y') AS UA_END_DATE,DATE_FORMAT(CONVERT_TZ(UA.UA_TIMESTAMP,'+00:00','+08:00'),'%d-%m-%Y %T') AS UA_TIMESTAMP FROM LMC_USER_LOGIN_DETAILS ULD,LMC_ROLE_CREATION RC,LMC_USER_ACCESS UA,LMC_USER_RIGHTS_CONFIGURATION URC,LMC_USER_RIGHTS_CONFIGURATION URC1,VW_TS_ALL_EMPLOYEE_DETAILS AE WHERE UA.UA_EMP_TYPE=URC1.URC_ID AND URC.URC_ID=RC.URC_ID AND ULD.ULD_ID=UA.ULD_ID AND UA.RC_ID=RC.RC_ID AND ULD.ULD_ID=AE.ULD_ID ORDER BY EMPLOYEE_NAME");
//SELECT AE.EMPLOYEE_NAME,RC.RC_NAME,UA.UA_REC_VER,URC1.URC_DATA,UA.UA_REASON,UA.UA_USERSTAMP,DATE_FORMAT(UA.UA_JOIN_DATE,'%d-%m-%Y') AS UA_JOIN_DATE,DATE_FORMAT(UA.UA_END_DATE,'%d-%m-%Y') AS UA_END_DATE,DATE_FORMAT(CONVERT_TZ(UA.UA_TIMESTAMP,'+00:00','+08:00'),'%d-%m-%Y %T') AS UA_TIMESTAMP FROM USER_LOGIN_DETAILS ULD,ROLE_CREATION RC,USER_ACCESS UA,USER_RIGHTS_CONFIGURATION URC,USER_RIGHTS_CONFIGURATION URC1,VW_TS_ALL_EMPLOYEE_DETAILS AE WHERE UA.UA_EMP_TYPE=URC1.URC_ID AND URC.URC_ID=RC.URC_ID AND ULD.ULD_ID=UA.ULD_ID AND UA.RC_ID=RC.RC_ID AND ULD.ULD_ID=AE.ULD_ID ORDER BY EMPLOYEE_NAME");
    $ure_values=array();
    $final_values=array();
    while($row=mysqli_fetch_array($date)){
        $USD_SRC_loginid=$row["EMPLOYEE_NAME"];
        $USD_SRC_rcid=$row["RC_NAME"];
        $USD_SRC_recver=$row["UA_REC_VER"];
        $USD_SRC_joindate=$row["UA_JOIN_DATE"];
        $USD_SRC_enddate=$row["UA_END_DATE"];
        $USD_SRC_reason=$row["UA_REASON"];
        $USD_SRC_emptypes=$row["URC_DATA"];
        $USD_SRC_userstamp=$row["UA_USERSTAMP"];
        $USD_SRC_timestamp=$row["UA_TIMESTAMP"];
        $final_values=array('loginid' =>$USD_SRC_loginid,'rcid' =>$USD_SRC_rcid,'recordver'=>$USD_SRC_recver,'joindate'=>$USD_SRC_joindate,'terminationdate'=>$USD_SRC_enddate,'reasonoftermination'=>$USD_SRC_reason,'emptypes'=>$USD_SRC_emptypes,'userstamp'=>$USD_SRC_userstamp,'timestamp'=>$USD_SRC_timestamp);
        $ure_values[]=$final_values;
    }
    $finalvalue=array($ure_values,$errormessages);
    echo JSON_ENCODE($finalvalue);

?>