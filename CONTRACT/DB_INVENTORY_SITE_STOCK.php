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
    $itemdtl=mysqli_query($con,"SELECT LID_ID,LID_ITEM_NO,LID_DESCRIPTION FROM LMC_INVENTORY_ITEM_DETAILS ORDER BY LID_ITEM_NO");
    while($row=mysqli_fetch_array($itemdtl)){
        $itemno[]=array('id'=>$row["LID_ID"],'no'=>$row["LID_ITEM_NO"],'name'=>$row["LID_DESCRIPTION"]);
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
elseif($_REQUEST['option']=='get_sitestock'){
    $fromdate=$_REQUEST['ISS_db_datesrchfrom'];
    $todate=$_REQUEST['ISS_db_datesrchto'];
    $itemno=$_REQUEST['ISS_lb_itemnosrch'];
    $itemname=$_REQUEST['ISS_lb_itemnamesrch'];
    if($fromdate!='' && $todate!=''){
        $fromdate=date('Y-m-d',strtotime($fromdate));
        $todate=date('Y-m-d',strtotime($todate));
        $sqlquery="SELECT ISS.LISD_ID,DATE_FORMAT(ISS.LISD_DATE,'%d-%m-%Y') AS LISD_DATE,FUD.LID_ITEM_NO,FUD.LID_DESCRIPTION,ISS.LISD_WKLY_OPENING_BALANCE,ISS.LISD_ADD_NEW_STOCK,ISS.LISD_DRAWN,ISS.LISD_RETURNED,ISS.LISD_SITE_USED,ISS.LISD_SITE_STOCK,ISS.LISD_SOLD,ISS.LISD_BALANCE_STOCK
FROM LMC_INVENTORY_SITE_STOCK_DETAILS ISS,LMC_INVENTORY_ITEM_DETAILS FUD WHERE ISS.LISD_DATE BETWEEN '$fromdate' AND '$todate' AND ISS.LID_ID=FUD.LID_ID ORDER BY ISS.LISD_DATE,FUD.LID_ITEM_NO,FUD.LID_DESCRIPTION";
    }
    elseif($itemno!='SELECT'){
        $sqlquery="SELECT ISS.LISD_ID,DATE_FORMAT(ISS.LISD_DATE,'%d-%m-%Y') AS LISD_DATE,FUD.LID_ITEM_NO,FUD.LID_DESCRIPTION,ISS.LISD_WKLY_OPENING_BALANCE,ISS.LISD_ADD_NEW_STOCK,ISS.LISD_DRAWN,ISS.LISD_RETURNED,ISS.LISD_SITE_USED,ISS.LISD_SITE_STOCK,ISS.LISD_SOLD,ISS.LISD_BALANCE_STOCK
FROM LMC_INVENTORY_SITE_STOCK_DETAILS ISS,LMC_INVENTORY_ITEM_DETAILS FUD WHERE FUD.LID_ITEM_NO='$itemno' AND ISS.LID_ID=FUD.LID_ID ORDER BY ISS.LISD_DATE,FUD.LID_ITEM_NO,FUD.LID_DESCRIPTION";
    }
    elseif($itemname!='SELECT'){
        $sqlquery="SELECT ISS.LISD_ID,DATE_FORMAT(ISS.LISD_DATE,'%d-%m-%Y') AS LISD_DATE,FUD.LID_ITEM_NO,FUD.LID_DESCRIPTION,ISS.LISD_WKLY_OPENING_BALANCE,ISS.LISD_ADD_NEW_STOCK,ISS.LISD_DRAWN,ISS.LISD_RETURNED,ISS.LISD_SITE_USED,ISS.LISD_SITE_STOCK,ISS.LISD_SOLD,ISS.LISD_BALANCE_STOCK
FROM LMC_INVENTORY_SITE_STOCK_DETAILS ISS,LMC_INVENTORY_ITEM_DETAILS FUD WHERE FUD.LID_DESCRIPTION='$itemname' AND ISS.LID_ID=FUD.LID_ID ORDER BY ISS.LISD_DATE,FUD.LID_ITEM_NO,FUD.LID_DESCRIPTION";
    }
    $site_stock=array();
    $sitestock=mysqli_query($con,$sqlquery);
    while($row=mysqli_fetch_array($sitestock)){
        $site_stock[]=array($row["LISD_ID"],$row["LISD_DATE"],$row["LID_ITEM_NO"],$row['LID_DESCRIPTION'],$row['LISD_WKLY_OPENING_BALANCE'],$row['LISD_ADD_NEW_STOCK'],$row['LISD_DRAWN'],$row['LISD_RETURNED'],$row['LISD_SITE_USED'],$row['LISD_SITE_STOCK'],$row['LISD_SOLD'],$row['LISD_BALANCE_STOCK']);
    }
    echo json_encode($site_stock);
}