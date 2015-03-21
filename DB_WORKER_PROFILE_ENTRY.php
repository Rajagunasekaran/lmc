<?php
//error_reporting(0);
include "CONNECTION.php";
include "COMMON.php";
if($_REQUEST['option']=='COMMON_DATA')
{

    //EMPLOYEE NAME
    $empname=mysqli_query($con,"SELECT EMP_ID,CONCAT(EMP_FIRST_NAME,' ',EMP_LAST_NAME) AS EMPNAME FROM LMC_EMPLOYEE_DETAILS ORDER BY EMPNAME ASC ");
    while($row=mysqli_fetch_array($empname)){
        $employeename[]=array($row["EMPNAME"],$row['EMP_ID']);
    }
    $errormsg=get_error_msg('133,141,142,146');
    $values=array($employeename,$errormsg);
    echo json_encode($values);
}
if($_REQUEST['option']=='allready_exists'){

$empid=$_REQUEST['empname'];
    $date=date('Y-m-d',strtotime($_REQUEST['date']));

    $existquery=mysqli_query($con,"SELECT * FROM LMC_EMPLOYEE_ATTACHMENT_DETAILS WHERE EMP_ID='$empid' and EAD_DATE='$date'");
    $row=mysqli_num_rows($existquery);
    echo $row;



}
if($_REQUEST['option']=='save'){

    $EMP_ID=$_POST['WP_lb_selectempname'];
    $date=$_POST['WP_tb_date'];
    $WP_finaldate = date('Y-m-d',strtotime($date));
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

    if($upload_filename==''){
                $upload_filename='null';

        $insert_query="INSERT INTO LMC_EMPLOYEE_ATTACHMENT_DETAILS(EMP_ID,EAD_DATE,EAD_DOC_FILE_NAME,ULD_ID)VALUES($EMP_ID,'$WP_finaldate',$upload_filename,(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP'))";

    }
    else{

    $insert_query="INSERT INTO LMC_EMPLOYEE_ATTACHMENT_DETAILS(EMP_ID,EAD_DATE,EAD_DOC_FILE_NAME,ULD_ID)VALUES($EMP_ID,'$WP_finaldate','$upload_filename',(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP'))";
    }
    if (!mysqli_query($con,$insert_query)) {
        $flag=0;
    }
    else{
        $flag=1;
    }
    echo $flag;

}