<?php
/**
 * Created by PhpStorm.
 * User: SSOMENS-021
 * Date: 9/1/15
 * Time: 9:38 AM
 */
if(isset($_REQUEST)){
    include "CONNECTION.php";
    include "COMMON.php";
    include "GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
    if($_REQUEST['option']=="CONFIG_ENTRY_load_mod")
    {
        // GET ERR MSG
        $CONFIG_ENTRY_errmsg=get_error_msg('71,127,130,131');
        // CONFIGURATION LIST
//        $CONFIG_ENTRY_mod = mysqli_query($con,"SELECT  DISTINCT CP.CNP_ID,CP.CNP_DATA FROM LMC_CONFIGURATION_PROFILE CP,LMC_CONFIGURATION C WHERE CP.CNP_ID=C.CNP_ID AND (C.CGN_NON_IP_FLAG is null) ORDER BY CP.CNP_DATA");
//        $CONFIG_ENTRY_arr_mod=array();
//        while($row=mysqli_fetch_array($CONFIG_ENTRY_mod)){
//            $CONFIG_ENTRY_arr_mod[]=array($row[0],$row[1]);
//        }
//        $CONFIG_ENTRY_errmsg_modlist=array($CONFIG_ENTRY_errmsg,$CONFIG_ENTRY_arr_mod);
        echo JSON_ENCODE($CONFIG_ENTRY_errmsg);
    }
    if($_REQUEST['option']=="CONFIG_ENTRY_load_type")
    {
//        $CONFIG_ENTRY_mod=$_REQUEST['module'];
        //CONFIG TYPE LIST
        $CONFIG_ENTRY_type = mysqli_query($con,"SELECT * FROM LMC_CONFIGURATION WHERE CNP_ID='2' AND (CGN_NON_IP_FLAG is null) ORDER BY CGN_TYPE ASC");
        $CONFIG_ENTRY_arr_type=array();
        while($row=mysqli_fetch_array($CONFIG_ENTRY_type)){
            $CONFIG_ENTRY_arr_type[]=array($row[0],$row[2]);
        }
        $CONFIG_ENTRY_errmsg=get_error_msg('71,127,130,131');
        $values=array($CONFIG_ENTRY_arr_type,$CONFIG_ENTRY_errmsg);
        echo JSON_ENCODE($values);
    }
    //SAVE CODING
    if($_REQUEST['option']=="CONFIG_ENTRY_save")
    {
        $flag=$_REQUEST['CONFIG_ENTRY_lb_module'];
        $CONFIG_ENTRY_data=$_REQUEST['CONFIG_ENTRY_tb_data'];
        $CONFIG_ENTRY_data=$con->real_escape_string($CONFIG_ENTRY_data);
        $CONFIG_ENTRY_type=$_REQUEST['CONFIG_ENTRY_lb_type'];
        $CONFIG_ENTRY_arr_config=array(3=>array("LMC_USER_RIGHTS_CONFIGURATION","URC_DATA"));
        $sql1= "SELECT ".$CONFIG_ENTRY_arr_config[$flag][1]." FROM ".$CONFIG_ENTRY_arr_config[$flag][0]." CCN WHERE CCN.CGN_ID=(SELECT C.CGN_ID FROM LMC_CONFIGURATION C WHERE C.CGN_ID='$CONFIG_ENTRY_type') AND ".$CONFIG_ENTRY_arr_config[$flag][1]."='$CONFIG_ENTRY_data'";
        $CONFIG_ENTRY_type1 = mysqli_query($con,$sql1);
        $CONFIG_ENTRY_save=0;
        if($row=mysqli_fetch_array($CONFIG_ENTRY_type1)){
            $CONFIG_ENTRY_save= 2;
        }

        $con->autocommit(false);
        $CONFIG_ENTRY_arr=array(3=>array("LMC_USER_RIGHTS_CONFIGURATION","URC_DATA,URC_USERSTAMP","(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP')"));
        if($flag==3)
            $sql="INSERT INTO ".$CONFIG_ENTRY_arr[$flag][0]." (CGN_ID, ".$CONFIG_ENTRY_arr[$flag][1].") VALUES ('$CONFIG_ENTRY_type', '$CONFIG_ENTRY_data', '$USERSTAMP')";
        else if($CONFIG_ENTRY_type==13){
            $sql="INSERT INTO LMC_TEAM_CREATION(TEAM_NAME,ULD_ID)VALUES('$CONFIG_ENTRY_data',(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP'))";
        }
        else if($CONFIG_ENTRY_type==14){
            $sql="INSERT INTO LMC_MEETING_TOPIC(MT_TOPIC,ULD_ID)VALUES('$CONFIG_ENTRY_data',(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP'))";
}
        else if($CONFIG_ENTRY_type==16){
            $sql="INSERT INTO LMC_REPORT_DOCUMENT_CATEGORY(RDC_CATEGORY,ULD_ID)VALUES('$CONFIG_ENTRY_data',(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP'))";
        }
        else if($CONFIG_ENTRY_type==17){
            $sql="INSERT INTO LMC_MACHINERY_ITEM(MI_ITEM,ULD_ID)VALUES('$CONFIG_ENTRY_data',(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP'))";
        }
        else if($CONFIG_ENTRY_type==18){
            $sql="INSERT INTO LMC_MACHINERY_USAGE(MCU_MACHINERY_TYPE,ULD_ID)VALUES('$CONFIG_ENTRY_data',(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP'))";
        }
        else if($CONFIG_ENTRY_type==19){
            $sql="INSERT INTO LMC_FITTING_USAGE(FU_ITEMS,ULD_ID)VALUES('$CONFIG_ENTRY_data',(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP'))";
        }
        else if($CONFIG_ENTRY_type==20){
            $sql="INSERT INTO LMC_MATERIAL_USAGE(MU_ITEMS,ULD_ID)VALUES('$CONFIG_ENTRY_data',(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP'))";
        }
        else
            $sql="INSERT INTO ".$CONFIG_ENTRY_arr[$flag][0]." (CGN_ID, ".$CONFIG_ENTRY_arr[$flag][1].") VALUES ('$CONFIG_ENTRY_type', '$CONFIG_ENTRY_data', (SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP'))";
        if($CONFIG_ENTRY_save!=2){
            if (!mysqli_query($con,$sql)) {
                die('Error: ' . mysqli_error($con));
                $CONFIG_ENTRY_save=4;
            }
            else{
                $CONFIG_ENTRY_save=1;
            }
            $con->commit();
        }
        echo $CONFIG_ENTRY_save;
    }
    //CHECK DUPLICATE DATA
    if($_REQUEST['option']=="CONFIG_ENTRY_check_data")
    {
        $CONFIG_ENTRY_arr_config=array(3=>array("LMC_USER_RIGHTS_CONFIGURATION","URC_DATA"));
        $flag=$_REQUEST['CONFIG_ENTRY_lb_module'];
        $CONFIG_ENTRY_arr_type=array(14=>array("LMC_MEETING_TOPIC","MT_TOPIC"),16=>array("LMC_REPORT_DOCUMENT_CATEGORY","RDC_CATEGORY"),13=>array("LMC_TEAM_CREATION","TEAM_NAME"),17=>array("LMC_MACHINERY_ITEM","MI_ITEM"),18=>array("LMC_MACHINERY_USAGE","MCU_MACHINERY_TYPE"),19=>array("LMC_FITTING_USAGE","FU_ITEMS"),20=>array("LMC_MATERIAL_USAGE","MU_ITEMS"));
        $CONFIG_ENTRY_type=$_REQUEST['CONFIG_ENTRY_lb_type'];
        $CONFIG_ENTRY_data=$_REQUEST['CONFIG_ENTRY_tb_data'];
        if($flag==3){
        $sql= "SELECT ".$CONFIG_ENTRY_arr_config[$flag][1]." FROM ".$CONFIG_ENTRY_arr_config[$flag][0]." CCN WHERE CCN.CGN_ID=(SELECT C.CGN_ID FROM LMC_CONFIGURATION C WHERE C.CGN_ID='$CONFIG_ENTRY_type') AND ".$CONFIG_ENTRY_arr_config[$flag][1]."='$CONFIG_ENTRY_data'";
        }
        else{
            $sql= "SELECT ".$CONFIG_ENTRY_arr_type[$CONFIG_ENTRY_type][1]." FROM ".$CONFIG_ENTRY_arr_type[$CONFIG_ENTRY_type][0]." CCN WHERE  ".$CONFIG_ENTRY_arr_type[$CONFIG_ENTRY_type][1]."='$CONFIG_ENTRY_data'";
        }
        $CONFIG_ENTRY_type = mysqli_query($con,$sql);
        $CONFIG_ENTRY_data_flag=0;
        if($row=mysqli_fetch_array($CONFIG_ENTRY_type)){
            $CONFIG_ENTRY_data_flag=1;
        }
        echo $CONFIG_ENTRY_data_flag;
    }
}