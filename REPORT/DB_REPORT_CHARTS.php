<?php
error_reporting(0);
include "../LMC_LIB/CONNECTION.php";
include "../LMC_LIB/GET_USERSTAMP.php";
include "../LMC_LIB/COMMON.php";
if($_REQUEST['option']=='Get_initialdata'){
    // ERRPOR MESSAGE
    $errormsg[]=get_error_msg('83,167,168');
    echo json_encode($errormsg);
}
else if($_REQUEST['option']=='Chart_input'){
    $fromdate=$_REQUEST['fromdate'];
    $todate=$_REQUEST['todate'];
    $flag=$_REQUEST['flag'];
    $chart_twodimens_arr=[];
    $contract_dtl=array();
    if($flag=='chart_flag_month'){
        $sqlquery="CALL SP_LMC_CONTRACT_CHART(1,'$fromdate',NULL,'$UserStamp',@TEMP_TABLE)";
    }
    else if($flag=='chart_flag_range'){
        $sqlquery="CALL SP_LMC_CONTRACT_CHART(2,'$fromdate','$todate','$UserStamp',@TEMP_TABLE)";
    }
    $result = $con->query($sqlquery);
    if (!$result) {
        $flag = 0;
        die("CALL failed: (" . $con->errno . ") " . $con->error);
    }
    $selecttable = $con->query('SELECT @TEMP_TABLE');
    $resulttable = $selecttable->fetch_assoc();
    $tablename = $resulttable['@TEMP_TABLE'];
    if($tablename!='') {
        $contractdtl= mysqli_query($con, "SELECT * FROM (SELECT CLDID,CONTRACT_NAME,STARTDATE,ENDDATE,'Projected Timeline' AS DATE_RANGE,'#1E90FF' AS COLORS FROM $tablename )AS A UNION ALL
    SELECT * FROM (SELECT CLDID,CONTRACT_NAME,ENDDATE,EXTENDED_DATE,'Updated Timeline' AS DATE_RANGE,'#DC143C' AS COLORS FROM $tablename where EXTENDED_DATE IS NOT NULL ORDER BY CLDID) AS B ORDER BY CLDID,STARTDATE,ENDDATE");
        while ($row = mysqli_fetch_array($contractdtl)) {
            $contract_dtl[] = array($row["CONTRACT_NAME"], $row["DATE_RANGE"], $row["STARTDATE"], $row["ENDDATE"], $row["COLORS"]);
        }
    }
    $chart_twodimens_arr=$contract_dtl;
    $droptable= mysqli_query($con, "DROP TABLE ".$tablename);
    echo json_encode($chart_twodimens_arr);
}
