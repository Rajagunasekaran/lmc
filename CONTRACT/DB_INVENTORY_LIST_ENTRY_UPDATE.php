<?php
/**
 * Created by PhpStorm.
 * User: RAJA
 * Date: 24-06-2015
 * Time: 03:02 PM
 */
error_reporting(0);
include "../LMC_LIB/CONNECTION.php";
include "../LMC_LIB/GET_USERSTAMP.php";
include "../LMC_LIB/COMMON.php";
// item detail values
function inital_data(){
    global $con;
    $itemdetails=array();
    $itemdtl=mysqli_query($con,"SELECT LID.LID_ID,LID.LID_ITEM_NO,LCD.CLD_CONTRACT_NO,LID.LID_DESCRIPTION,LID.LID_COST,LID.LID_INTERNAL_COST,LID.LID_PERCENTAGE_LEVEL,LID.LID_COST_AFTER_DISCOUNT,LMU.LMU_UNIT,LID.LID_QTY_SOLD,DATE_FORMAT(LID.LID_TIMESTAMP,'%d-%m-%Y %T') AS LID_TIMESTAMP
    FROM LMC_INVENTORY_ITEM_DETAILS LID,LMC_CONTRACT_DETAILS LCD,LMC_MEASURE_UNIT LMU WHERE LID.CLD_ID = LCD.CLD_ID AND LID.LMU_ID = LMU.LMU_ID ORDER BY LID.LID_ITEM_NO");
    while($row=mysqli_fetch_array($itemdtl)){
        $itemdetails[]=array($row["LID_ID"],$row["LID_ITEM_NO"],$row['LID_DESCRIPTION'],$row['CLD_CONTRACT_NO'],$row['LID_COST'],$row['LID_INTERNAL_COST'],$row['LID_PERCENTAGE_LEVEL'],$row['LID_COST_AFTER_DISCOUNT'],$row['LMU_UNIT'],$row['LID_QTY_SOLD'],$row['LID_TIMESTAMP']);
    }
    return $itemdetails;
}
if($_REQUEST['option']=='get_item_details'){
    $itemdetail=inital_data();
    // CONTRACT NOs
    $contractnos=ongoing_contractno();
    // UNIT OF MEASURE
    $UOM=unitofmeasure();
    // ERRPOR MESSAGE
    $errormsg=get_error_msg('3,4,7,14,17');
    $values=array($itemdetail,$errormsg,$contractnos,$UOM);
    echo json_encode($values);
}
elseif($_REQUEST['option']=='save_item_details'){
    $itemno = $_REQUEST['ILEU_itemno'];
    $itemdesc = $con->real_escape_string($_REQUEST['ILEU_itemdesc']);
    $contractno = $_REQUEST['ILEU_contractno'];
    $cost = $_REQUEST['ILEU_cost'];
    $internalcost = $_REQUEST['ILEU_internalcost'];
    $percentlevel = $_REQUEST['ILEU_percentlevel'];
    $costdisc = $_REQUEST['ILEU_costdiscount'];
    $units = $_REQUEST['ILEU_uom'];
    $qtysold = $_REQUEST['ILEU_quantity'];
    $rowid = $_REQUEST['row_id'];
    if($cost!=''){
        $cost=$cost;
    }else{
        $cost="''";
    }
    if($internalcost!=''){
        $internalcost=$internalcost;
    }else{
        $internalcost="''";
    }
    if($percentlevel!=''){
        $percentlevel=$percentlevel;
    }else{
        $percentlevel="''";
    }
    if($costdisc!=''){
        $costdisc=$costdisc;
    }else{
        $costdisc="''";
    }
    if($qtysold!=''){
        $qtysold=$qtysold;
    }else{
        $qtysold="''";
    }
    if($_REQUEST['btn_val']=='SAVE') {
        $sqlquery = "CALL SP_INVENTORY_LIST_ENTRY_UPDATE(1,'','$contractno','$itemno','$itemdesc',$cost,$internalcost,$percentlevel,$costdisc,'$units',$qtysold,'$UserStamp',@SUCCESS_MESSAGE)";
    }
    elseif($_REQUEST['btn_val']=='UPDATE') {
        $sqlquery = "CALL SP_INVENTORY_LIST_ENTRY_UPDATE(2,$rowid,'$contractno','$itemno','$itemdesc',$cost,$internalcost,$percentlevel,$costdisc,'$units',$qtysold,'$UserStamp',@SUCCESS_MESSAGE)";
    }
    $result = $con->query($sqlquery);
    if (!$result) {
        $flag = 0;
        die("CALL failed: (" . $con->errno . ") " . $con->error);
    }
    $select = $con->query('SELECT @SUCCESS_MESSAGE');
    $result = $select->fetch_assoc();
    $flag = $result['@SUCCESS_MESSAGE'];
    $itemdetail=inital_data();
    $flag_array=array($itemdetail,$flag);
    echo json_encode($flag_array);
}
