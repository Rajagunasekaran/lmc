<?php
//*********************************************GLOBAL DECLARATION******************************************-->
//*********************************************************************************************************//-->
//*******************************************FILE DESCRIPTION*********************************************//
//****************************************CONFIGURATION SEARCH/UPDATE/DELETE*************************************************//
//DONE BY:LALITHA
//VER 0.03-SD:07/02/2015 ED:07/02/2015,TRACKER NO:74,Updated alphabets fr project details nd Changed validation
//VER 0.02-SD:19/01/2015 ED:19/01/2015,TRACKER NO:74,Added Deletion part nd Checked sp,Added err msgs,Fixed width,Changed query fr flex tble nd Updation part,Hide the errmsgs nd dt
//DONE BY:SARADAMBAL
//VER 0.01-SD:06/01/2015 ED:06/01/2015,TRACKER NO:74,IMPLEMENTED PRELOADER POSITION,CHANGED LOGIN ID INTO EMPLOYEE NAME
//*********************************************************************************************************//
if(isset($_REQUEST)){
    include "CONNECTION.php";
    include "COMMON.php";
    include "GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
    if($_REQUEST['option']=="CONFIG_SRCH_UPD_load_mod")
    {
        // GET ERR MSG
        $CONFIG_SRCH_UPD_errmsg=get_error_msg('17,60,113,125,126,127,128,129,131,132');
        // CONFIGURATION LIST
        $CONFIG_SRCH_UPD_mod = mysqli_query($con,"SELECT  DISTINCT CP.CNP_ID,CP.CNP_DATA FROM LMC_CONFIGURATION_PROFILE CP,LMC_CONFIGURATION C WHERE CP.CNP_ID=C.CNP_ID AND (C.CGN_NON_IP_FLAG is null) ORDER BY CP.CNP_DATA");
        $CONFIG_SRCH_UPD_arr_mod=array();
        while($row=mysqli_fetch_array($CONFIG_SRCH_UPD_mod)){
            $CONFIG_SRCH_UPD_arr_mod[]=array($row[0],$row[1]);
        }
        $CONFIG_SRCH_UPD_errmsg_modlist=array($CONFIG_SRCH_UPD_errmsg,$CONFIG_SRCH_UPD_arr_mod);
        echo JSON_ENCODE($CONFIG_SRCH_UPD_errmsg_modlist);
    }
    if($_REQUEST['option']=="CONFIG_SRCH_UPD_load_type")
    {
        $CONFIG_SRCH_UPD_mod=$_REQUEST['module'];
        //CONFIG TYPE LIST
        $CONFIG_SRCH_UPD_type = mysqli_query($con,"SELECT * FROM LMC_CONFIGURATION WHERE CNP_ID='$CONFIG_SRCH_UPD_mod' AND (CGN_NON_IP_FLAG != 'XX' or CGN_NON_IP_FLAG is null)  ORDER BY CGN_TYPE ASC");
        $CONFIG_SRCH_UPD_arr_type=array();
        while($row=mysqli_fetch_array($CONFIG_SRCH_UPD_type)){
            $CONFIG_SRCH_UPD_arr_type[]=array($row[0],$row[2]);
        }
        echo JSON_ENCODE($CONFIG_SRCH_UPD_arr_type);
    }
    //LOAD DATA FOR FLEX TABLE
    if($_REQUEST['option']=="CONFIG_SRCH_UPD_load_data")
    {
        $flag=$_REQUEST['CONFIG_SRCH_UPD_lb_module'];
        $CONFIG_SRCH_UPD_data=$_REQUEST['CONFIG_SRCH_UPD_tb_data'];
        $CONFIG_SRCH_UPD_type=$_REQUEST['CONFIG_SRCH_UPD_lb_type'];
        $arrTableWidth=array(3=>900,16=>1500);
        $arrHeaderWidth=array(3=>array(300),5=>array(100));
        $CONFIG_SRCH_UPD_arr_data=array(3=>array("LMC_USER_RIGHTS_CONFIGURATION","URC_DATA","DT.URC_ID,DT.URC_DATA,DT.URC_USERSTAMP,DATE_FORMAT((DT.URC_TIMESTAMP),'%d-%m-%Y %T'),DT.URC_INITIALIZE_FLAG"));
        if($flag==3)
        {
            $CONFIG_SRCH_UPD_sql_data = mysqli_query($con, "SELECT ". $CONFIG_SRCH_UPD_arr_data[$flag][2]. " AS TIMESTAMP FROM ". $CONFIG_SRCH_UPD_arr_data[$flag][0]. " DT,LMC_CONFIGURATION C,LMC_CONFIGURATION_PROFILE CP WHERE  CP.CNP_ID='$flag' AND DT.CGN_ID=C.CGN_ID AND C.CGN_ID= '$CONFIG_SRCH_UPD_type' ORDER BY DT. ". $CONFIG_SRCH_UPD_arr_data[$flag][1]. " ASC");
        }
        else if($CONFIG_SRCH_UPD_type==15)
        {
            $CONFIG_SRCH_UPD_sql_data = mysqli_query($con,"SELECT LTC.TC_ID,LTC.TEAM_NAME,ULD.ULD_USERNAME,DATE_FORMAT((LTC.TC_TIMESTAMP),'%d-%m-%Y %T') AS TC_TIMESTAMP  FROM LMC_TEAM_CREATION LTC ,LMC_USER_LOGIN_DETAILS ULD WHERE LTC.ULD_ID=ULD.ULD_ID ORDER BY LTC.TEAM_NAME");
        }
        else if($CONFIG_SRCH_UPD_type==16)
        {
            $CONFIG_SRCH_UPD_sql_data = mysqli_query($con,"SELECT LMT.MT_ID,LMT.MT_TOPIC,ULD.ULD_USERNAME,DATE_FORMAT((LMT.MT_TIMESTAMP),'%d-%m-%Y %T') AS MT_TIMESTAMP FROM LMC_MEETING_TOPIC LMT,LMC_USER_LOGIN_DETAILS ULD WHERE LMT.ULD_ID=ULD.ULD_ID ORDER BY LMT.MT_TOPIC");
        }
        else if($CONFIG_SRCH_UPD_type==18)
        {
            $CONFIG_SRCH_UPD_sql_data = mysqli_query($con,"SELECT RDC.RDC_ID,RDC.RDC_CATEGORY,ULD.ULD_USERNAME,DATE_FORMAT((RDC.RDC_TIMESTAMP),'%d-%m-%Y %T') AS RDC_TIMESTAMP FROM LMC_REPORT_DOCUMENT_CATEGORY RDC,LMC_USER_LOGIN_DETAILS ULD WHERE RDC.ULD_ID=ULD.ULD_ID ORDER BY RDC.RDC_CATEGORY");
        }
        else{
            $CONFIG_SRCH_UPD_sql_data = mysqli_query($con, "SELECT ". $CONFIG_SRCH_UPD_arr_data[$flag][2]. " AS TIMESTAMP FROM ". $CONFIG_SRCH_UPD_arr_data[$flag][0]. " DT,LMC_CONFIGURATION C,LMC_CONFIGURATION_PROFILE CP,LMC_USER_LOGIN_DETAILS ULD WHERE  ULD.ULD_ID=DT.ULD_ID AND CP.CNP_ID='$flag' AND DT.CGN_ID=C.CGN_ID AND C.CGN_ID= '$CONFIG_SRCH_UPD_type' ORDER BY DT. ". $CONFIG_SRCH_UPD_arr_data[$flag][1]. " ASC");
        }
        $appendTable="<br><div id='CONFIG_SRCH_UPD_div_errmsg'></div><br><table id='CONFIG_SRCH_UPD_tble_config' border=1 cellspacing='0' class='srcresult' width='".$arrTableWidth[$flag]."px'><thead  bgcolor='#6495ed' style='color:white'><tr class='head'><th style='text-align:center;' width=350>DATA</th><th style='text-align:center;' width=150>USERSTAMP</th><th style='text-align:center;' width=130>TIMESTAMP</th><th style='text-align:center;' width=100>EDIT/UPDATE</th></tr></thead><tbody>";
        while($row=mysqli_fetch_array($CONFIG_SRCH_UPD_sql_data)){
            $appendTable .='<tr  id='.$row[0].'><td id='.'CONFIG_'.$row[0].'>'.$row[1].'</td>';
            for($x = 2; $x < 4; $x++) {
                $appendTable .="<td width='".$arrHeaderWidth[$flag][$x]."px'  >".$row[$x]."</td>";
            }
            if($row[4]=='X')
            {
                $deleteoption='<input type="button"  id="edit" class="edit  btn btn-info btn-sm nondelete" value="EDIT">&nbsp;&nbsp;&nbsp;<input type="button"  id="cancl" class="cancl  btn btn-info btn-sm" value="CANCEL">';
            }
            else{
                $deleteoption='<input type="button"  id="edit" class="edit  btn btn-info btn-sm deletion" value="EDIT">&nbsp;&nbsp;&nbsp;<input type="button"  id="cancel" class="cancl  btn btn-info btn-sm" value="CANCEL">';
            }
            $appendTable .='<td align="center">'.$deleteoption.'</td></tr>';
        }
        $appendTable .='</tbody></table>';
        echo JSON_ENCODE($appendTable);
    }
    //UPDATE CODING
    if($_REQUEST['option']=="CONFIG_SRCH_UPD_save")
    {
        $ff=1;
        $flag=$_REQUEST['CONFIG_SRCH_UPD_lb_module'];
        $CONFIG_SRCH_UPD_data=$_REQUEST['CONFIG_SRCH_UPD_tb_data'];
        $CONFIG_SRCH_UPD_type=$_REQUEST['CONFIG_SRCH_UPD_lb_type'];
        $CONFIG_SRCH_UPD_id=$_REQUEST['CONFIG_SRCH_UPD_id'];
//        $CONFIG_SRCH_UPD_arr_config=array(3=>array("USER_RIGHTS_CONFIGURATION","URC_DATA"));
        $CONFIG_SRCH_UPD_arr_config=array(4=>array("attendance_configuration","AC_ID","AC_DATA","ULD_ID","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"),5=>array("PROJECT_CONFIGURATION","PC_ID","PC_DATA","ULD_ID","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"),2=>array("REPORT_CONFIGURATION","RC_ID","RC_DATA","ULD_ID","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"),3=>array("LMC_USER_RIGHTS_CONFIGURATION","URC_ID","URC_DATA","URC_USERSTAMP","(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP')"));

//        echo $CONFIG_SRCH_UPD_arr_config;
        $sql1= "SELECT ".$CONFIG_SRCH_UPD_arr_config[$flag][1]." FROM ".$CONFIG_SRCH_UPD_arr_config[$flag][0]." CCN WHERE CCN.CGN_ID=(SELECT C.CGN_ID FROM CONFIGURATION C WHERE C.CGN_ID='$CONFIG_SRCH_UPD_type') AND ".$CONFIG_SRCH_UPD_arr_config[$flag][1]."='$CONFIG_SRCH_UPD_data'";
        $CONFIG_SRCH_UPD_type1 = mysqli_query($con,$sql1);
        $CONFIG_SRCH_UPD_save=0;

        //COMMON
        if($row=mysqli_fetch_array($CONFIG_SRCH_UPD_type1)){
            $CONFIG_SRCH_UPD_save= 2;
        }
        $con->autocommit(false);
//        echo "UPDATE ".$CONFIG_SRCH_UPD_arr_config[$flag][0]." SET ".$CONFIG_SRCH_UPD_arr_config[$flag][2]."= '$CONFIG_SRCH_UPD_data',".$CONFIG_SRCH_UPD_arr_config[$flag][3]."='$USERSTAMP' WHERE ".$CONFIG_SRCH_UPD_arr_config[$flag][1]."=".$CONFIG_SRCH_UPD_id;
        $CONFIG_SRCH_UPD_arr=array(3=>array("LMC_USER_RIGHTS_CONFIGURATION","URC_ID","URC_DATA","URC_USERSTAMP","(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP')"));
        if($flag==3){
            $sql="UPDATE ".$CONFIG_SRCH_UPD_arr_config[$flag][0]." SET ".$CONFIG_SRCH_UPD_arr_config[$flag][2]."= '$CONFIG_SRCH_UPD_data',".$CONFIG_SRCH_UPD_arr_config[$flag][3]."='$USERSTAMP' WHERE ".$CONFIG_SRCH_UPD_arr_config[$flag][1]."=".$CONFIG_SRCH_UPD_id;
        }
        else if($CONFIG_SRCH_UPD_type==15){
            $sql="UPDATE LMC_TEAM_CREATION SET TEAM_NAME='$CONFIG_SRCH_UPD_data',ULD_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP') WHERE TC_ID='$CONFIG_SRCH_UPD_id' ";
        }
        else if($CONFIG_SRCH_UPD_type==16){
            $sql="UPDATE LMC_MEETING_TOPIC SET MT_TOPIC='$CONFIG_SRCH_UPD_data',ULD_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP') WHERE MT_ID='$CONFIG_SRCH_UPD_id' ";
        }
        else if($CONFIG_SRCH_UPD_type==18){
            $sql="UPDATE LMC_REPORT_DOCUMENT_CATEGORY SET RDC_CATEGORY='$CONFIG_SRCH_UPD_data',ULD_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP') WHERE RDC_ID='$CONFIG_SRCH_UPD_id' ";
        }
        else{
            $sql="UPDATE ".$CONFIG_SRCH_UPD_arr[$flag][0]." SET ".$CONFIG_SRCH_UPD_arr[$flag][2]."= '$CONFIG_SRCH_UPD_data',".$CONFIG_SRCH_UPD_arr[$flag][3]."=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP') WHERE ".$CONFIG_SRCH_UPD_arr[$flag][1]."=".$CONFIG_SRCH_UPD_id;
        }
        if($ff==1){
            if($CONFIG_SRCH_UPD_save!=2){
                if (!mysqli_query($con,$sql)) {
                    die('Error: ' . mysqli_error($con));
                    $CONFIG_SRCH_UPD_save=4;
                }
                else{
                    $CONFIG_SRCH_UPD_save=1;
                }
                if($CONFIG_SRCH_UPD_type==12||$CONFIG_SRCH_UPD_type==9){
                    if (!mysqli_query($con,$sql1)) {
                        die('Error: ' . mysqli_error($con));
                    }
                }
                $con->commit();
            }
        }
        $final_array=[$CONFIG_SRCH_UPD_save];
        echo json_encode($final_array);
    }
    //CHECK DUPLICATE DATA
    if($_REQUEST['option']=="CONFIG_SRCH_UPD_check_data")
    {
        $CONFIG_SRCH_UPD_arr_config=array(3=>array("LMC_USER_RIGHTS_CONFIGURATION","URC_DATA"));
        $CONFIG_ENTRY_arr_type=array(16=>array("LMC_MEETING_TOPIC","MT_TOPIC"),18=>array("LMC_REPORT_DOCUMENT_CATEGORY","RDC_CATEGORY"),15=>array("LMC_TEAM_CREATION","TEAM_NAME"));
        $flag=$_REQUEST['CONFIG_SRCH_UPD_lb_module'];
        $CONFIG_SRCH_UPD_type=$_REQUEST['CONFIG_SRCH_UPD_lb_type'];
        $CONFIG_SRCH_UPD_data=$_REQUEST['CONFIG_SRCH_UPD_tb_data'];
       if($flag==3){
        $sql= "SELECT ".$CONFIG_SRCH_UPD_arr_config[$flag][1]." FROM ".$CONFIG_SRCH_UPD_arr_config[$flag][0]." CCN WHERE CCN.CGN_ID=(SELECT C.CGN_ID FROM LMC_CONFIGURATION C WHERE C.CGN_ID='$CONFIG_SRCH_UPD_type') AND ".$CONFIG_SRCH_UPD_arr_config[$flag][1]."='$CONFIG_SRCH_UPD_data'";
       }
       else{
           $sql= "SELECT ".$CONFIG_ENTRY_arr_type[$CONFIG_SRCH_UPD_type][1]." FROM ".$CONFIG_ENTRY_arr_type[$CONFIG_SRCH_UPD_type][0]." CCN WHERE  ".$CONFIG_ENTRY_arr_type[$CONFIG_SRCH_UPD_type][1]."='$CONFIG_SRCH_UPD_data'";
       }
           $CONFIG_SRCH_UPD_type = mysqli_query($con,$sql);
        $CONFIG_SRCH_UPD_data_flag=0;
        if($row=mysqli_fetch_array($CONFIG_SRCH_UPD_type)){
            $CONFIG_SRCH_UPD_data_flag=1;
        }
        echo $CONFIG_SRCH_UPD_data_flag;
    }
}
