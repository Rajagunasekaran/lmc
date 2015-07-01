<?php
error_reporting(0);
if(isset($_REQUEST)) {
    include "../../LMC_LIB/CONNECTION.php";
    include "../../LMC_LIB/COMMON.php";
    include "../../LMC_LIB/GET_USERSTAMP.php";
    $USERSTAMP = $UserStamp;
    global $con;
//FUNCTION TO FETCHING EMAIL TEMPLATE SCRIPT NAME,ERROR MESSAGE FROM SQL TABLE
    if ($_REQUEST['option'] == "EP_ENTRY_getdomain_err") {
        $EP_ENTRY_profile_array=[];
        $select_data=mysqli_query($con,"SELECT EP_ID,EP_EMAIL_DOMAIN FROM LMC_EMAIL_PROFILE WHERE EP_NON_IP_FLAG is null ORDER BY EP_EMAIL_DOMAIN ASC");
        while($row=mysqli_fetch_array($select_data)){
            $ep_id=$row['EP_ID'];
            $ep_profilename=$row['EP_EMAIL_DOMAIN'];
            $EP_ENTRY_script_object=array('EP_ENTRY_profile_names_id'=>$ep_id,'EP_ENTRY_profile_names' =>$ep_profilename);
            $EP_ENTRY_profile_array[]=$EP_ENTRY_script_object;
        }
        // GET ERR MSG
        $EP_SRC_UPD_DEL_errmsg = get_error_msg('7,40,158,159');
        $finalvalues=array("EP_ENTRY_profilenamedataid"=>$EP_ENTRY_profile_array,"EP_ENTRY_errormsg"=>$EP_SRC_UPD_DEL_errmsg);
        echo JSON_ENCODE($finalvalues);
    }
    if($_REQUEST['option']=="EP_ENTRY_already")
    {
        $profilename=$_REQUEST['EP_ENTRY_listboxname'];
        $EP_ENTRY_email=$_REQUEST['EP_ENTRY_email'];
        $EP_ENTRY_alreadyemailid=mysqli_query($con,"SELECT * FROM LMC_EMAIL_LIST WHERE EL_EMAIL_ID='$EP_ENTRY_email' AND EP_ID=(SELECT  EP_ID FROM LMC_EMAIL_PROFILE WHERE EP_EMAIL_DOMAIN='$profilename')");
        $row=mysqli_num_rows($EP_ENTRY_alreadyemailid);
        if($row>0)
        {
            $EP_ENTRY_chkmail_flag=1;
        }
        else{
            $EP_ENTRY_chkmail_flag=0;
        }
        echo $EP_ENTRY_chkmail_flag;
    }
    //FUNCTION FOR TO SAVE THE EMAIL ID
    if($_REQUEST['option']=='EP_ENTRY_save')
    {
        $EP_ENTRY_profilenameid=$_REQUEST['EP_ENTRY_profilenameid'];
        $EP_ENTRY_emailid=$_REQUEST['EP_ENTRY_emailid'];
        $EP_ENTRY_primaryid_before=EP_ENTRY_getmaxprimaryid();
        $EP_ENTRY_insertemailid=mysqli_query($con,"INSERT INTO LMC_EMAIL_LIST(EP_ID,EL_EMAIL_ID,ULD_ID)VALUES('$EP_ENTRY_profilenameid','$EP_ENTRY_emailid',(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$UserStamp'))");
        $EP_ENTRY_primaryid_after=EP_ENTRY_getmaxprimaryid();
        if(intval($EP_ENTRY_primaryid_before)<intval($EP_ENTRY_primaryid_after))
        {
            echo 1;
        }
        else{
            echo 0;
        }
    }
    if($_REQUEST['option']=='EP_SRC_UPD_DEL_searchoption')
    {
        $EP_SRC_UPD_DEL_emaillistprofile="SELECT EP.EP_ID,EP.EP_EMAIL_DOMAIN,EL.EL_ID,EP.EP_NON_IP_FLAG FROM LMC_EMAIL_PROFILE EP,LMC_EMAIL_LIST EL WHERE EL.EP_ID=EP.EP_ID ORDER BY EP_EMAIL_DOMAIN ASC";
        $EP_SRC_UPD_DEL_emailresult=mysqli_query($con,$EP_SRC_UPD_DEL_emaillistprofile);
        while($row=mysqli_fetch_array($EP_SRC_UPD_DEL_emailresult))
        {
            $EP_SRC_UPD_DEL_profilename_id=$row['EP_ID'];
            $EP_SRC_UPD_DEL_profilename_data=$row['EP_EMAIL_DOMAIN'];
            $EP_SRC_UPD_DEL_listname_id=$row['EL_ID'];
            $EP_SRC_UPD_DEL_profilename_flag=$row['EP_NON_IP_FLAG'];
            $EP_SRC_UPD_DEL_nameid_object=array("EP_SRC_UPD_DEL_profilenames_id"=>$EP_SRC_UPD_DEL_profilename_id,"EP_SRC_UPD_DEL_profilenames_data"=>$EP_SRC_UPD_DEL_profilename_data,"EP_SRC_UPD_DEL_listnames_id"=>$EP_SRC_UPD_DEL_listname_id,"EP_SRC_UPD_DEL_profilenames_flag"=>$EP_SRC_UPD_DEL_profilename_flag);
           $EP_SRC_UPD_DEL_email_array[]=$EP_SRC_UPD_DEL_nameid_object;
    }
        $EP_SRC_UPD_DEL_errorMsg_array=[];
        $EP_SRC_UPD_DEL_errorMsg_array=get_error_msg('8,17,40,159,160,161,162,163,164,165');
    $EP_SRC_UPD_DEL_email_arrayresult=array("EP_SRC_UPD_DEL_profilelistdataid"=>$EP_SRC_UPD_DEL_email_array,"EP_SRC_UPD_DEL_errormsg"=>$EP_SRC_UPD_DEL_errorMsg_array);
    echo json_encode($EP_SRC_UPD_DEL_email_arrayresult);
    }

}
//FUNCTION TO CHECK WHETHER THE DATA INSERTED OR NOT
function EP_ENTRY_getmaxprimaryid()
{
    global $con;
    $EP_ENTRY_select="SELECT MAX(EL_ID) AS PRIMARY_ID FROM LMC_EMAIL_LIST";
    $EP_ENTRY_rs_primaryid=mysqli_query($con,$EP_ENTRY_select);
    while($row=mysqli_fetch_array($EP_ENTRY_rs_primaryid)){
        $EP_ENTRY_primaryid=$row['PRIMARY_ID'];
    }
    return $EP_ENTRY_primaryid;
}