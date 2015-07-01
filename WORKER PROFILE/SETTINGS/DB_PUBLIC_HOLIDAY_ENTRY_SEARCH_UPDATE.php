<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMPLOYEE DETAIL ENTRY*********************************************//
//DONE BY:LALITHA
//VER 0.04-SD:17/12/2014 ED:18/12/2014,TRACKER NO:74,Checked conditn nd put err msgs,Added uld nd timestmp fields
//VER 0.03-SD:01/12/2014 ED:01/12/2014,TRACKER NO:74,Changed Preloder funct
//VER 0.02-SD:28/11/2014 ED:28/11/2014,TRACKER NO:74,Updated Validation,Err msg,Reset Function,Checked condition of alrdy ext nd valid id in save part,Aftr saved reset fn called
//DONE BY:SAFI
//VER 0.01-INITIAL VERSION, SD:02/10/2014 ED:06/10/2014,TRACKER NO:74,Designed Form,Get data from ss nd insert in db part
//*********************************************************************************************************//
error_reporting(0);
include '../../LMC_LIB/CONNECTION.php';
include "../../LMC_LIB/GET_USERSTAMP.php";
include "../../LMC_LIB/COMMON.php";
$USERSTAMP=$UserStamp;
//SAVE PART FOR PUBLIC HOLIDAY ENTRY FORM
if($_REQUEST['option']=="ph_save"){
    $ph_ssid=$_POST['PH_ENTRY_tb_ss'];
    $ph_gid=$_POST['PH_ENTRY_tb_gid'];
    $feed='https://docs.google.com/spreadsheets/d/'.$ph_ssid.'/export?gid='.$ph_gid.'&format=csv';
// Arrays we'll use later
    $keys = array();
    $newArray = array();
// Function to convert CSV into associative array
    function csvToArray($file, $delimiter) {
        if (($handle = fopen($file, 'r')) !== FALSE) {
            $i = 0;
            while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) {
                for ($j = 0; $j < count($lineArray); $j++) {
                    $arr[$i][$j] = $lineArray[$j];
                }
                $i++;
            }
            fclose($handle);
        }
        return $arr;
    }
// Do it
    $data = csvToArray($feed, ',');
// Set number of elements (minus 1 because we shift off the first row)
    $count = count($data) - 1;
//Use first row for names
    $labels = array_shift($data);
    foreach ($labels as $label) {
        $keys[] = $label;
    }
// Add Ids, just in case we want them later
    $keys[] = 'id';
    for ($i = 0; $i < $count; $i++) {
        $data[$i][] = $i;
    }
// Bring it all together
    for ($j = 0; $j < $count; $j++) {
        $d = array_combine($keys, $data[$j]);
        $newArray[$j] = $d;
    }
// Print it out as JSON
    $newArray=json_encode($newArray);
    $return_value = json_decode($newArray, true);

//print_r($return_value);
    $check_valid= count($return_value);
    $successflag=0;
    if($check_valid>0)
    {
        foreach ($return_value as $key => $value) {
            $date=$value['DATE'];
            $day=$value['HOLIDAY'];
            $uld_id=$value['ULD_ID'];
            $timestamp=$value['TIMESTAMP'];
            if(($date!='')&&($day!=''))
            {
                $sql="select * from PUBLIC_HOLIDAY where PH_DATE='$date'";
                $sql_result= mysqli_query($con,$sql);
                $row=mysqli_num_rows($sql_result);
                $x=$row;
                if($x > 0)
                {
                    $PH_already_exist_flag=1;
                }
                else{
                    $PH_already_exist_flag=0;
                }
                if($PH_already_exist_flag==0)
                {
                    $ph_insert="INSERT INTO PUBLIC_HOLIDAY(PH_DESCRIPTION,PH_DATE,ULD_ID,PH_TIMESTAMP)VALUES('$day','$date','$uld_id','$timestamp')";
                    if (!mysqli_query($con,$ph_insert)) {
                        die('Error: ' . mysqli_error($con));
                        $successflag=0;
                    }
                    else{
                        $successflag=1;
                    }
                }
            }
        }
    }
    $ph_final_array=array($PH_already_exist_flag,$successflag,$check_valid);
    echo json_encode($ph_final_array);
}
//SEARCH AND UPDTE FORM STARTS
//FETCHING DATAS LOADED FRM DB FOR LISTBX
if($_REQUEST['option']=="common")
{
// GET ERR MSG
    $PH_SRC_UPD_nodate=get_error_msg('4,18,56,83,89');
    // YEAR FOR PUBLIC HOLIDAY
    $PH_SRC_UPD_yr_list = mysqli_query($con,"SELECT DISTINCT DATE_FORMAT(PH_DATE, '%Y')AS YEAR FROM PUBLIC_HOLIDAY;");
    $PH_SRC_UPD_yrlist=array();
    while($row=mysqli_fetch_array($PH_SRC_UPD_yr_list)){
        $PH_SRC_UPD_yrlist[]=array($row["YEAR"]);
    }
    $final_values=array($PH_SRC_UPD_yrlist,$PH_SRC_UPD_nodate);
    echo JSON_ENCODE($final_values);
}
if($_REQUEST['option']=="PUBLIC_HOLIDAY_DETAILS")
{
    //FETCHING USER LOGIN DETAILS RECORDS
    $PH_SRC_UPD_year=$_REQUEST["PH_SRC_UPD_lb_yr"];
    $date= mysqli_query($con,"SELECT PH.PH_ID,DATE_FORMAT(PH.PH_DATE,'%d-%m-%Y') AS PH_DATE,PH.PH_DESCRIPTION,ULD.ULD_LOGINID,DATE_FORMAT(CONVERT_TZ(PH.PH_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') AS PH_TIMESTAMP FROM PUBLIC_HOLIDAY PH ,USER_LOGIN_DETAILS ULD WHERE PH.ULD_ID=ULD.ULD_ID AND YEAR(PH.PH_DATE)='$PH_SRC_UPD_year'");
    $ure_values=array();
    while($row=mysqli_fetch_array($date)){
        $PH_SRC_UPD_date=$row["PH_DATE"];
        $PH_SRC_UPD_descr=$row["PH_DESCRIPTION"];
        $PH_SRC_UPD_id=$row['PH_ID'];
        $PH_SRC_UPD_userstamp=$row['ULD_LOGINID'];
        $PH_SRC_UPD_timestamp=$row['PH_TIMESTAMP'];
        $final_values=(object) ['id'=>$PH_SRC_UPD_id,'PH_SRC_UPD_date' =>$PH_SRC_UPD_date,'PH_SRC_UPD_descr' =>$PH_SRC_UPD_descr,'PH_SRC_UPD_userstamp' =>$PH_SRC_UPD_userstamp,'PH_SRC_UPD_timestamp' =>$PH_SRC_UPD_timestamp];
        $ure_values[]=$final_values;
    }
    $finalvalue=array($ure_values);
    echo JSON_ENCODE($finalvalue);
}
//FUNCTION FOR TO UPDATE THE EMPLOYEE DETAILS ND COMPANY DETAILS
if($_REQUEST['option']=="PROJECT_DETAILS_UPDATE"){
    $EMPSRC_UPD_DEL_rd_flxtbl=$_POST['EMPSRC_UPD_DEL_rd_flxtbl'];
    $PH_SRC_UPD_des=$_POST['PH_SRC_UPD_tb_des'];
    $PH_SRC_UPD_des=$con->real_escape_string($PH_SRC_UPD_des);
    $PH_SRC_UPD_date=$_POST['PH_SRC_UPD_tb_date'];
    $PH_SRC_UPD_date = date('Y-m-d',strtotime($PH_SRC_UPD_date));
    $sql="UPDATE PUBLIC_HOLIDAY SET PH_DATE='$PH_SRC_UPD_date',PH_DESCRIPTION='$PH_SRC_UPD_des',ULD_ID=(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP') WHERE PH_ID='$EMPSRC_UPD_DEL_rd_flxtbl' ";
    if (!mysqli_query($con,$sql)) {
        die('Error: ' . mysqli_error($con));
        $flag=0;
    }
    else{
        $flag=1;
    }
    echo $flag;
}