<?php
/**
 * Created by PhpStorm.
 * User: SSOMENS-025
 * Date: 24-06-2015
 * Time: 04:18 PM
 */
error_reporting(0);
include "../LMC_LIB/CONNECTION.php";
include "../LMC_LIB/GET_USERSTAMP.php";
include "../LMC_LIB/COMMON.php";
function inital_data(){
    global $con;
    $itemno=array();
    $itemdtl=mysqli_query($con,"SELECT LID_ID,LID_ITEM_NO FROM LMC_INVENTORY_ITEM_DETAILS ORDER BY LID_ITEM_NO");
    while($row=mysqli_fetch_array($itemdtl)){
        $itemno[]=array('id'=>$row["LID_ID"],'no'=>$row["LID_ITEM_NO"]);
    }
    return $itemno;
}
if($_REQUEST['option']=='get_item_no'){
    $itemdetail=inital_data();
    // ERRPOR MESSAGE
    $errormsg=get_error_msg('3,7,83');
    $values=array($itemdetail,$errormsg);
    echo json_encode($values);
}
elseif($_REQUEST['option']=='get_item_name'){
    $itemnoid=$_REQUEST['item_no'];
    $itemname=array();
    $itemdtl=mysqli_query($con,"SELECT LID_DESCRIPTION FROM LMC_INVENTORY_ITEM_DETAILS WHERE LID_ID='$itemnoid'");
    if($row=mysqli_fetch_array($itemdtl)){
        $itemname=$row["LID_DESCRIPTION"];
    }
    echo $itemname;
}