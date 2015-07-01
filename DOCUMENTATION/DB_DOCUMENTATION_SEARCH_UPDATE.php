<?php
error_reporting(0);
include "../LMC_LIB/CONNECTION.php";
include "../LMC_LIB/GET_USERSTAMP.php";
include "../LMC_LIB/COMMON.php";
date_default_timezone_set('Asia/Singapore');
$dir=dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR;
$parentdocfolder=get_reportdocfolder_id();
if($_REQUEST['option']=='common_data')
{$filearray=array();
    $attachcategory=array();
    //FILE NAME
    $filename=mysqli_query($con,"SELECT RAD_DOC_FILE_NAME FROM LMC_REPORT_ATTACHMENT_DETAILS ORDER BY RAD_DOC_FILE_NAME ASC");
    while($row=mysqli_fetch_array($filename)){
        $attachedfilename=array();
        $attachedfilename=explode('/',$row["RAD_DOC_FILE_NAME"]);
        for($i=0;$i<count($attachedfilename);$i++){
            $final_filearry[]=$attachedfilename[$i];
        }
    }

//CATEGORY
    $category=mysqli_query($con,"select RDC_ID,RDC_CATEGORY from LMC_REPORT_DOCUMENT_CATEGORY  ORDER BY RDC_CATEGORY ASC");
    while($row=mysqli_fetch_array($category)){
        $attachcategory[]=array($row["RDC_CATEGORY"],$row['RDC_ID']);
    }

    $select_role=mysqli_query($con,"select URC_DATA from VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS where ULD_USERNAME='$UserStamp'");

    if($row=mysqli_fetch_array($select_role)){
        $rolename=$row["URC_DATA"];
    }

    $erromsg=get_error_msg('83,133,141,142,146');
    $values=array($final_filearry,$attachcategory,$erromsg,$rolename);
    echo json_encode($values);
}
elseif($_REQUEST['option']=='search_data')
{
    $filename=$_REQUEST['filename'];
    $startdate=$_REQUEST['startdate'];
    $enddate=$_REQUEST['enddate'];
    $category=$_REQUEST['category'];
    if($startdate!='')
    {
     $sdate=date('Y-m-d',strtotime($startdate));
    }
    else{
        $sdate='';
    }
    if($enddate!='')
    {
        $edate=date('Y-m-d',strtotime($enddate));
    }
    else{
        $edate='';
    }
   if($category=='SELECT')
   {
       $category='';
   }
    $option;
    if($filename!='')
    {
        $option=2;
    }
    elseif($category!='')
    {
        $option=1;
    }
    elseif($sdate!='' && $edate!='')
    {
        $option=3;
    }
    $callquery="CALL SP_LMC_TEMP_REPORT_ATTACHMENT_DETAILS_SEARCH($option,'$category','$filename','$sdate','$edate','$UserStamp',@FINALTABLENAME)";
    $result = $con->query($callquery);
    if(!$result){
        die("CALL failed: (" . $con->errno . ") " . $con->error);
    }
    $select = $con->query('SELECT @FINALTABLENAME');
    $result = $select->fetch_assoc();
    $temp_table= $result['@FINALTABLENAME'];
    $data=mysqli_query($con,"select RAD_ID,RAD_CATEGORY,DATE_FORMAT(RAD_DATE, '%d-%m-%Y') as RAD_DATE,RAD_DOC_FILE_NAME,RAD_USERSTAMP,DATE_FORMAT(RAD_TIMESTAMP,'%d-%m-%Y %T') AS TIMESTAMP FROM  $temp_table ORDER BY RAD_DATE ");
    while($row=mysqli_fetch_array($data)){
        $rowid=$row['RAD_ID'];
        $category=$row["RAD_CATEGORY"];
        $date=$row['RAD_DATE'];
        $filename=$row['RAD_DOC_FILE_NAME'];
        $userstamp=$row['RAD_USERSTAMP'];
        $timestamp=$row['TIMESTAMP'];
        $finalvalues[]=(object)['id'=>$rowid,'categroy'=>$category,'date'=>$date,'filename'=>$filename,'userstamp'=>$userstamp,'timestamp'=>$timestamp];
    }
    $drop_query="DROP TABLE ".$temp_table;
    mysqli_query($con,$drop_query);
    echo json_encode($finalvalues);
}
elseif($_REQUEST['option']=='update')
{

    $uploadcount=$_REQUEST['DT_upload_count'];
    $date=$_REQUEST['DT_doc_date'];
    $category=$_REQUEST['DT_doc_lb_category'];
    $uploaddate = date('Y-m-d',strtotime($date));
    $repdate=str_replace('-','',$date);
    $rowid=$_REQUEST['rowid'];
    $old_filename=$_REQUEST['removedfilename'];
    if($old_filename=='undefined')
    {
        $oldfilename=mysqli_query($con,"SELECT RAD_DOC_FILE_NAME FROM LMC_REPORT_ATTACHMENT_DETAILS WHERE RAD_ID='$rowid'");
        while($row=mysqli_fetch_array($oldfilename)){
            $old_filename=$row["RAD_DOC_FILE_NAME"];
        }
    }
    $upload_file_array=array();
    $uploadpath=$dir.$parentdocfolder.DIRECTORY_SEPARATOR;
    for($x=1;$x<=$uploadcount;$x++)
    {
        if($_FILES['DT_upload_filename'.$x]['name']!=''){
            $attach_file_name=$repdate.'_'.date('His').'_'.$_FILES['DT_upload_filename'.$x]['name'];
            if(move_uploaded_file($_FILES['DT_upload_filename'.$x]['tmp_name'],$uploadpath.$attach_file_name)) {
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
        for ($y = 0; $y < count($upload_file_array); $y++) {
            if ($upload_file_array[$y] != '') {
                if ($y == 0) {
                    $uploadfilename = $upload_file_array[$y];
                    $fileflag = 1;
                } else {
                    $uploadfilename = $uploadfilename . '/' . $upload_file_array[$y];
                    $fileflag = 1;
                }
            } else {
                $fileflag = 0;
            }
        }
    }
    if($uploadfilename!='' && $old_filename!=='')
    {
        $upload_filename=$old_filename.'/'.$uploadfilename;
    }
    if($old_filename=='')
    {
        $upload_filename=$uploadfilename;
    }
    if($uploadfilename=='')
    {
        $upload_filename=$old_filename;
    }
    if($upload_filename!=''){
        $callquery="CALL SP_LMC_REPORT_ATTACHMENT_DETAILS_INSERT_UPDATE('UPDATE','$rowid','$category','$uploaddate','$upload_filename','$UserStamp',@SUCCESSFLAG)";
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