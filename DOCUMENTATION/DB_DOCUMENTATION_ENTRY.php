<?php
error_reporting(0);
include "../LMC_LIB/CONNECTION.php";
include "../LMC_LIB/GET_USERSTAMP.php";
include "../LMC_LIB/COMMON.php";
$dir=dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR;
date_default_timezone_set('Asia/Singapore');
$parentdocfolder=get_reportdocfolder_id();
if($_REQUEST['option']=='COMMON_DATA')
{
    // category
    $categories=mysqli_query($con,"SELECT RDC_ID,RDC_CATEGORY FROM LMC_REPORT_DOCUMENT_CATEGORY ORDER BY RDC_CATEGORY ASC");
    while($row=mysqli_fetch_array($categories)){
        $category[]=$row["RDC_CATEGORY"];
    }
    //ERRPOR MESSAGE
    $errormsg=get_error_msg('133,141,142,146');
    $values=array($category,$errormsg);
    echo JSON_encode($values);
}
elseif($_REQUEST['option']=='tempfilname')
{
    $uploadcount=$_REQUEST['ENT_upload_count'];
    $date=$_REQUEST['doc_date'];
    $category=$_REQUEST['doc_lb_category'];
    $uploaddate = date('Y-m-d',strtotime($date));
    $repdate=str_replace('-','',$date);
    $upload_file_array=array();
    $uploadpath=$dir.$parentdocfolder.DIRECTORY_SEPARATOR;
//    echo $uploadpath;exit;
    for($x=1;$x<=$uploadcount;$x++)
    {
        if($_FILES['ENT_upload_filename'.$x]['name']!=''){
            $attach_file_name=$repdate.'_'.date('His').'_'.$_FILES['ENT_upload_filename'.$x]['name'];
            if(move_uploaded_file($_FILES['ENT_upload_filename'.$x]['tmp_name'],$uploadpath.$attach_file_name)) {
                $upload_file_array[] = $attach_file_name;
                $fileflag = 1;
            }
            else{
                $fileflag=0;
            }
        }
        else{
            $fileflag=0;
        }
    }
    if($fileflag==1) {
        $upload_filename = '';
        for ($y = 0; $y < count($upload_file_array); $y++) {
            if ($upload_file_array[$y] != '') {
                if ($y == 0) {
                    $upload_filename = $upload_file_array[$y];
                    $fileflag = 1;
                } else {
                    $upload_filename = $upload_filename . '/' . $upload_file_array[$y];
                    $fileflag = 1;
                }
            } else {
                $fileflag = 0;
            }
        }
    }
    if($fileflag==1 && $upload_filename!=''){
        $callquery="CALL SP_LMC_REPORT_ATTACHMENT_DETAILS_INSERT_UPDATE('INSERT',null,'$category','$uploaddate','$upload_filename','$UserStamp',@SUCCESSFLAG)";
        $result = $con->query($callquery);
        if(!$result){
            unlink($uploadpath);
            die("CALL failed: (" . $con->errno . ") " . $con->error);
        }
        $select = $con->query('SELECT @SUCCESSFLAG');
        $result = $select->fetch_assoc();
        $flag= $result['@SUCCESSFLAG'];
        if($flag!=1 && $uploadpath!='')
        {
            unlink($uploadpath);
        }
    }
    echo $flag;
}
elseif($_REQUEST['option']=='category_exists')
{
    $categoryname=$_REQUEST['categoryname'];
    $date=date('Y-m-d',strtotime($_REQUEST['date']));

    $existquery=mysqli_query($con,"SELECT * FROM LMC_REPORT_ATTACHMENT_DETAILS WHERE RDC_ID=(SELECT RDC_ID FROM LMC_REPORT_DOCUMENT_CATEGORY WHERE RDC_CATEGORY='$categoryname') and RAD_DATE='$date'");
    $row=mysqli_num_rows($existquery);
    echo $row;
}