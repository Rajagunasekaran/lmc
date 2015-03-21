<?php
include "CONNECTION.php";
include "COMMON.php";
if($_REQUEST['option']=='COMMON_DATA')
{


//FILE NAME
    $filename=mysqli_query($con,"SELECT EAD_DOC_FILE_NAME FROM LMC_EMPLOYEE_ATTACHMENT_DETAILS ORDER BY EAD_DOC_FILE_NAME ASC");
    $attachedfilename=array();
    $final_filearry=array();
    while($row=mysqli_fetch_array($filename)){
        $attachedfilename=array();
        $attachedfilename=explode('/',$row["EAD_DOC_FILE_NAME"]);
        for($i=0;$i<count($attachedfilename);$i++){
            $final_filearry[]=$attachedfilename[$i];
        }
        $attachedfileid=$row['EAD_ID'];
    }

    $errormsg=get_error_msg('133,141,142');

    $select_role=mysqli_query($con,"select URC_DATA from vw_access_rights_terminate_loginid where ULD_USERNAME='$UserStamp'");

    if($row=mysqli_fetch_array($select_role)){
        $rolename=$row["URC_DATA"];
    }

    $final_values=array($final_filearry,$errormsg,$rolename);
        echo json_encode($final_values);


}

if($_REQUEST['option']=='search_data'){



//    $select_data=mysqli_query($con,"SELECT * FROM lmc_employee_attachment_details WHERE EAD_DOC_FILE_NAME LIKE '%LMC TEST_17032015_122402_SSOMENS LOGO.png%' OR EAD_DOC_FILE_NAME LIKE 'LMC TEST_17032015_122402_SSOMENS LOGO.png%' OR EAD_DOC_FILE_NAME LIKE '%LMC TEST_17032015_122402_SSOMENS LOGO.png'");

$startdate=$_REQUEST['startdate'];
    $enddate=$_REQUEST['enddate'];
$filename=$_REQUEST['filename'];

    $select_empid="select EMP_ID from lmc_employee_attachment_details where ULD_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$UserStamp')";
    $sql_result= mysqli_query($con,$select_empid);
    if($row=mysqli_fetch_array($sql_result)){
        $EMP_ID=$row["EMP_ID"];
    }

    if($startdate!=''&&$enddate!=''){

        $WP_startdate = date('Y-m-d',strtotime($startdate));
        $WP_enddate = date('Y-m-d',strtotime($enddate));

$select_data=mysqli_query($con,"SELECT ED.EMP_DOC_FOLDER_ID,EAD.EAD_ID,CONCAT(ED.EMP_FIRST_NAME,' ',ED.EMP_LAST_NAME) AS EMPNAME,DATE_FORMAT(EAD.EAD_DATE,'%d-%m-%Y') AS EAD_DATE,EAD.EAD_DOC_FILE_NAME,DATE_FORMAT(EAD.EAD_TIMESTAMP,'%d-%m-%Y %T') AS EAD_TIMESTAMP,ULD.ULD_USERNAME FROM lmc_employee_attachment_details EAD join lmc_employee_details ED,LMC_USER_LOGIN_DETAILS ULD WHERE EAD_DATE between '$WP_startdate' and '$WP_enddate' and EAD.EMP_ID = ED.EMP_ID and ULD.ULD_ID=EAD.ULD_ID ");
    }
    else if($filename!=''){

        $select_data=mysqli_query($con,"SELECT ED.EMP_DOC_FOLDER_ID ,EAD.EAD_ID,CONCAT(ED.EMP_FIRST_NAME,' ',ED.EMP_LAST_NAME) AS EMPNAME,DATE_FORMAT(EAD.EAD_DATE,'%d-%m-%Y') AS EAD_DATE,EAD.EAD_DOC_FILE_NAME,DATE_FORMAT(EAD.EAD_TIMESTAMP,'%d-%m-%Y %T ') AS EAD_TIMESTAMP,ULD.ULD_USERNAME FROM lmc_employee_attachment_details EAD join lmc_employee_details ED,LMC_USER_LOGIN_DETAILS ULD WHERE EAD_DOC_FILE_NAME LIKE '%$filename%'  and EAD.EMP_ID = ED.EMP_ID and ULD.ULD_ID=EAD.ULD_ID ");

    }

        $ure_values=array();
        $final_values=array();
        while($row=mysqli_fetch_array($select_data)){

            $ET_SRC_name=$row["EMPNAME"];
            $ET_SRC_date=$row["EAD_DATE"];
            $ET_SRC_filename=$row["EAD_DOC_FILE_NAME"];
            $WP_rowid=$row['EAD_ID'];
            $WP_userstamp=$row['ULD_USERNAME'];
            $WP_timestamp=$row['EAD_TIMESTAMP'];
            $WP_folderid=$row['EMP_DOC_FOLDER_ID'];
            $final_values=array('folder_id'=>$WP_folderid,'empname' =>$ET_SRC_name,'date' =>$ET_SRC_date,'ET_SRC_filename'=>$ET_SRC_filename,'WP_rowid'=>$WP_rowid,'Userstamp'=>$WP_userstamp,'timestamp'=>$WP_timestamp);
            $ure_values[]=$final_values;

    }
        $finalvalue=array($ure_values);
        echo JSON_ENCODE($finalvalue);



}

if($_REQUEST['option']=='UPDATE'){


    $id=$_REQUEST['id'];
    $oldfilename=$_REQUEST['oldfilename'];
    $select_empid="select EMP_ID from lmc_employee_attachment_details where EAD_ID='$id'";
    $sql_result= mysqli_query($con,$select_empid);
    if($row=mysqli_fetch_array($sql_result)){
        $EMP_ID=$row["EMP_ID"];
    }
    $sql="SELECT EMP_DOC_FOLDER_ID FROM LMC_EMPLOYEE_DETAILS WHERE EMP_ID='$EMP_ID'";
    $sql_result= mysqli_query($con,$sql);
    if($row=mysqli_fetch_array($sql_result)){
        $EMPDOCFOLDER_ID=$row["EMP_DOC_FOLDER_ID"];
    }

    $sql_firstname="SELECT EMP_FIRST_NAME FROM LMC_EMPLOYEE_DETAILS WHERE EMP_ID='$EMP_ID'";
    $sql_result= mysqli_query($con,$sql_firstname);
    if($row=mysqli_fetch_array($sql_result)){
        $emp_first_name=$row["EMP_FIRST_NAME"];
    }
    if($oldfilename=='undefined')
    {
    $sql_filename="SELECT EAD_DOC_FILE_NAME FROM lmc_employee_attachment_details WHERE EAD_ID='$id'";
    $sql_result= mysqli_query($con,$sql_filename);
    if($row=mysqli_fetch_array($sql_result)){
        $oldfilename=$row["EAD_DOC_FILE_NAME"];
    }
    }
    $parent_attach_folder_name=get_docfolder_id();
    $currentdate=date("d-m-Y");
    $currentdate=str_replace('-','',$currentdate);
    $attch_file_folder=$parent_attach_folder_name.DIRECTORY_SEPARATOR.$EMPDOCFOLDER_ID.DIRECTORY_SEPARATOR;
    $uploadcount=$_REQUEST['upload_count'];

    for($x=1;$x<=$uploadcount;$x++)
    {
        if($_FILES['upload_filename'.$x]['name']!=''){
            $attach_file_name=$emp_first_name.'_'.$currentdate.'_'.date('His').'_'.$_FILES['upload_filename'.$x]['name'];


            move_uploaded_file($_FILES['upload_filename'.$x]['tmp_name'],$attch_file_folder.$attach_file_name);
            $upload_file_array[]=$attach_file_name;//$_FILES['upload_filename'.$x]['name'];
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

//    $upload_filename=$filename.'/'.$upload_filename;
    if($oldfilename!='' && $upload_filename!='' ){
    $upload_filename=$oldfilename.'/'.$upload_filename;
    }
    elseif($upload_filename==''){
        $upload_filename=$oldfilename;

    }
    elseif($oldfilename==''){


        $upload_filename=$upload_filename;

    }

    $update_query="UPDATE  LMC_EMPLOYEE_ATTACHMENT_DETAILS set EAD_DOC_FILE_NAME='$upload_filename' ,ULD_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$UserStamp') where EAD_ID='$id'";
    if (!mysqli_query($con,$update_query)) {
        die('Error: ' . mysqli_error($con));
        $flag=0;
    }
    else{
        $flag=1;
    }
    echo $flag;



}