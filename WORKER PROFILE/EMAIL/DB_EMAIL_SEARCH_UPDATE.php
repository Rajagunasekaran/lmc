<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMAIL TEMPLATE SEARCH/UPDATE*********************************************//
//DONE BY:RAJA
//VER 0.02-IMPLEMENTED INLINE EDITOR, SD:22/06/2015 ED:22/06/2015
////DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:26/02/2015 ED:26/02/2015,TRACKER NO:99
//*********************************************************************************************************//
error_reporting(0);
if(isset($_REQUEST)){
    include "../../LMC_LIB/CONNECTION.php";
    include "../../LMC_LIB/COMMON.php";
    include "../../LMC_LIB/GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
    //FUNCTION TO FETCHING EMAIL TEMPLATE SCRIPT NAME,ERROR MESSAGE FROM SQL TABLE
    if($_REQUEST['option']=="INITIAL_DATAS"){
        // GET ERR MSG
        $ET_SRC_UPD_DEL_errmsg=get_error_msg('56,87,88,89');
        $ET_SRC_UPD_DEL_emltemp = mysqli_query($con,"SELECT ET_ID,ET_EMAIL_SCRIPT FROM LMC_EMAIL_TEMPLATE ORDER BY ET_EMAIL_SCRIPT");
        $ET_SRC_UPD_DEL_script_object=array();
        while($row=mysqli_fetch_array($ET_SRC_UPD_DEL_emltemp)){
            $ET_SRC_UPD_DEL_script_object[]=array($row["ET_EMAIL_SCRIPT"],$row["ET_ID"]);
        }
        $ET_SRC_UPD_DEL_final_values=array($ET_SRC_UPD_DEL_script_object,$ET_SRC_UPD_DEL_errmsg);
        echo JSON_ENCODE($ET_SRC_UPD_DEL_final_values);
    }
    //FUNCTION FOR SHOW THE DATA IN TABLE
    if($_REQUEST['option']=="EMAIL_TEMPLATE_DETAILS"){
        $ET_SRC_UPD_DEL_scriptname=$_POST['ET_SRC_UPD_DEL_lb_scriptname'];
        $ET_SRC_UPD_DEL_flextbl= mysqli_query($con,"SELECT DATE_FORMAT(CONVERT_TZ(ETD.ETD_TIMESTAMP,'+00:00','+08:00'),'%d-%m-%Y %T') AS TIMESTAMP,ETD.ETD_EMAIL_SUBJECT,ETD.ETD_EMAIL_BODY,ULD.ULD_USERNAME,ETD.ETD_ID FROM LMC_EMAIL_TEMPLATE_DETAILS ETD,LMC_USER_LOGIN_DETAILS ULD WHERE ETD.ULD_ID=ULD.ULD_ID AND ETD.ET_ID='$ET_SRC_UPD_DEL_scriptname'");
        $ET_SRC_UPD_DEL_values=array();
        while($row=mysqli_fetch_array($ET_SRC_UPD_DEL_flextbl)){
            $ET_SRC_UPD_DEL_subject=$row["ETD_EMAIL_SUBJECT"];
            $ET_SRC_UPD_DEL_body=$row["ETD_EMAIL_BODY"];
            $ET_SRC_UPD_DEL_userstamp=$row["ULD_USERNAME"];
            $ET_SRC_UPD_DEL_timestamp=$row["TIMESTAMP"];
            $ET_SRC_UPD_DEL_el_id=$row['ETD_ID'];
            $final_values=(object) ['id'=>$ET_SRC_UPD_DEL_el_id,'ET_SRC_UPD_DEL_subject' =>$ET_SRC_UPD_DEL_subject,'ET_SRC_UPD_DEL_body' =>$ET_SRC_UPD_DEL_body,'ET_SRC_UPD_DEL_userstamp'=>$ET_SRC_UPD_DEL_userstamp,'ET_SRC_UPD_DEL_timestamp'=>$ET_SRC_UPD_DEL_timestamp];
            $ET_SRC_UPD_DEL_values[]=$final_values;
        }
        echo JSON_ENCODE($ET_SRC_UPD_DEL_values);
    }
    //UPDATE DATA FOR EMAIL TEMPLATE TABLE
    if($_REQUEST['option']=="EMAIL_TEMPLATE_UPDATE"){
        $ET_SRC_UPD_DEL_el_id=$_REQUEST['ET_SRC_UPD_DEL_rd_flxtbl'];
        $ET_SRC_UPD_DEL_subject=$_REQUEST['ET_SRC_UPD_DEL_ta_updsubject'];
        $ET_SRC_UPD_DEL_subject= $con->real_escape_string($ET_SRC_UPD_DEL_subject);
        $ET_SRC_UPD_DEL_body=$_REQUEST['ET_SRC_UPD_DEL_ta_updbody'];
        $ET_SRC_UPD_DEL_body= $con->real_escape_string($ET_SRC_UPD_DEL_body);
        $sql="UPDATE LMC_EMAIL_TEMPLATE_DETAILS SET ETD_EMAIL_SUBJECT='$ET_SRC_UPD_DEL_subject',ETD_EMAIL_BODY='$ET_SRC_UPD_DEL_body',ULD_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP') WHERE ETD_ID='$ET_SRC_UPD_DEL_el_id' ";
        if (!mysqli_query($con,$sql)) {
            $flag=0;
            die('Error: ' . mysqli_error($con));
        }
        else{
            $flag=1;
        }
        echo $flag;
    }
}
?>