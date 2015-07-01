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
    include "../../LMC_LIB/CONNECTION.php";
    include "../../LMC_LIB/COMMON.php";
    include "../../LMC_LIB/GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
    if($_REQUEST['option']=="CONFIG_SRCH_UPD_load_mod")
    {
        // GET ERR MSG
        $CONFIG_SRCH_UPD_errmsg=get_error_msg('17,60,113,125,126,127,128,129,131,132');
        echo JSON_ENCODE($CONFIG_SRCH_UPD_errmsg);
    }
    if($_REQUEST['option']=="CONFIG_SRCH_UPD_load_type")
    {
        $CONFIG_SRCH_UPD_mod=$_REQUEST['module'];
        //CONFIG TYPE LIST
        $CONFIG_SRCH_UPD_type = mysqli_query($con,"SELECT * FROM LMC_CONFIGURATION WHERE CGN_ID IN (9,10,11,12,13,14,16,17,18,19,20) ORDER BY CGN_TYPE ASC");
        $CONFIG_SRCH_UPD_arr_type=array();
        while($row=mysqli_fetch_array($CONFIG_SRCH_UPD_type)){
            $CONFIG_SRCH_UPD_arr_type[]=array($row[0],$row[2]);
        }
        $CONFIG_SRCH_UPD_errmsg=get_error_msg('17,60,113,125,126,127,128,129,131,132');
        $values=array($CONFIG_SRCH_UPD_arr_type,$CONFIG_SRCH_UPD_errmsg);
        echo JSON_ENCODE($values);
    }
    //LOAD DATA FOR FLEX TABLE
    if($_REQUEST['option']=="CONFIG_SRCH_UPD_load_data")
    {
        $CONFIG_SRCH_UPD_type=$_REQUEST['CONFIG_SRCH_UPD_lb_type'];
        if($CONFIG_SRCH_UPD_type==13)
        {
            $CONFIG_SRCH_UPD_sql_data = mysqli_query($con,"SELECT LTC.TC_ID,LTC.TEAM_NAME,ULD.ULD_USERNAME,DATE_FORMAT((LTC.TC_TIMESTAMP),'%d-%m-%Y %T') AS TC_TIMESTAMP  FROM LMC_TEAM_CREATION LTC ,LMC_USER_LOGIN_DETAILS ULD WHERE LTC.ULD_ID=ULD.ULD_ID ORDER BY LTC.TEAM_NAME");
        }
        else if($CONFIG_SRCH_UPD_type==14)
        {
            $CONFIG_SRCH_UPD_sql_data = mysqli_query($con,"SELECT LMT.MT_ID,LMT.MT_TOPIC,ULD.ULD_USERNAME,DATE_FORMAT((LMT.MT_TIMESTAMP),'%d-%m-%Y %T') AS MT_TIMESTAMP FROM LMC_MEETING_TOPIC LMT,LMC_USER_LOGIN_DETAILS ULD WHERE LMT.ULD_ID=ULD.ULD_ID ORDER BY LMT.MT_TOPIC");
        }
        else if($CONFIG_SRCH_UPD_type==16)
        {
            $CONFIG_SRCH_UPD_sql_data = mysqli_query($con,"SELECT RDC.RDC_ID,RDC.RDC_CATEGORY,ULD.ULD_USERNAME,DATE_FORMAT((RDC.RDC_TIMESTAMP),'%d-%m-%Y %T') AS RDC_TIMESTAMP FROM LMC_REPORT_DOCUMENT_CATEGORY RDC,LMC_USER_LOGIN_DETAILS ULD WHERE RDC.ULD_ID=ULD.ULD_ID ORDER BY RDC.RDC_CATEGORY");
        }
        else if($CONFIG_SRCH_UPD_type==17)
        {
            $CONFIG_SRCH_UPD_sql_data = mysqli_query($con,"SELECT MI.MI_ID,MI.MI_ITEM,ULD.ULD_USERNAME,DATE_FORMAT((MI.MI_TIMESTAMP),'%d-%m-%Y %T') AS MI_TIMESTAMP FROM LMC_MACHINERY_ITEM MI,LMC_USER_LOGIN_DETAILS ULD WHERE MI.ULD_ID=ULD.ULD_ID ORDER BY MI.MI_ITEM");
        }
        else if($CONFIG_SRCH_UPD_type==18)
        {
            $CONFIG_SRCH_UPD_sql_data = mysqli_query($con,"SELECT MUC.MCU_ID,MUC.MCU_MACHINERY_TYPE,ULD.ULD_USERNAME,DATE_FORMAT((MUC.MCU_TIMESTAMP),'%d-%m-%Y %T') AS MUC_TIMESTAMP FROM LMC_MACHINERY_USAGE MUC,LMC_USER_LOGIN_DETAILS ULD WHERE MUC.ULD_ID=ULD.ULD_ID ORDER BY MUC.MCU_MACHINERY_TYPE");
        }
        else if($CONFIG_SRCH_UPD_type==19)
        {
            $CONFIG_SRCH_UPD_sql_data = mysqli_query($con,"SELECT LFT.FU_ID,LFT.FU_ITEMS,ULD.ULD_USERNAME,DATE_FORMAT((LFT.FU_TIMESTAMP),'%d-%m-%Y %T') AS FU_TIMESTAMP FROM LMC_FITTING_USAGE LFT,LMC_USER_LOGIN_DETAILS ULD WHERE LFT.ULD_ID=ULD.ULD_ID ORDER BY LFT.FU_ITEMS");
        }
        else if($CONFIG_SRCH_UPD_type==20)
        {
            $CONFIG_SRCH_UPD_sql_data = mysqli_query($con,"SELECT MU.MU_ID,MU.MU_ITEMS,ULD.ULD_USERNAME,DATE_FORMAT((MU.MU_TIMESTAMP),'%d-%m-%Y %T') AS MU_TIMESTAMP FROM LMC_MATERIAL_USAGE MU,LMC_USER_LOGIN_DETAILS ULD WHERE MU.ULD_ID=ULD.ULD_ID ORDER BY MU.MU_ITEMS");
        }
        else{
            $CONFIG_SRCH_UPD_sql_data = mysqli_query($con, "SELECT URC_ID,URC_DATA,URC_USERSTAMP,URC_TIMESTAMP FROM LMC_USER_RIGHTS_CONFIGURATION WHERE CGN_ID='$CONFIG_SRCH_UPD_type'");
        }
        $appendTable="<br><div id='CONFIG_SRCH_UPD_div_errmsg'></div><br><table id='CONFIG_SRCH_UPD_tble_config' border=1 cellspacing='0' class='srcresult'><thead  bgcolor='#6495ed' style='color:white'><tr class='head'><th style='text-align:center;' width=350>DATA</th><th style='text-align:center;' width=150>USERSTAMP</th><th style='text-align:center;' width=130>TIMESTAMP</th></tr></thead><tbody>";
        while($row=mysqli_fetch_array($CONFIG_SRCH_UPD_sql_data)){
            $appendTable .='<tr  id='.$row[0].'><td id='.'CONFIG_'.$row[0].' class="data">'.$row[1].'</td>';
            for($x = 2; $x < 4; $x++) {
                if($x == 3){
                    $appendTable .="<td style='text-align:center;'>".$row[$x]."</td>";
                }
                else{
                    $appendTable .="<td>".$row[$x]."</td>";
                }
            }
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
        $CONFIG_SRCH_UPD_arr_config=array(4=>array("attendance_configuration","AC_ID","AC_DATA","ULD_ID","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"),5=>array("PROJECT_CONFIGURATION","PC_ID","PC_DATA","ULD_ID","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"),2=>array("REPORT_CONFIGURATION","RC_ID","RC_DATA","ULD_ID","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"),3=>array("LMC_USER_RIGHTS_CONFIGURATION","URC_ID","URC_DATA","URC_USERSTAMP","(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP')"));

        $sql1= "SELECT ".$CONFIG_SRCH_UPD_arr_config[$flag][1]." FROM ".$CONFIG_SRCH_UPD_arr_config[$flag][0]." CCN WHERE CCN.CGN_ID=(SELECT C.CGN_ID FROM CONFIGURATION C WHERE C.CGN_ID='$CONFIG_SRCH_UPD_type') AND ".$CONFIG_SRCH_UPD_arr_config[$flag][1]."='$CONFIG_SRCH_UPD_data'";
        $CONFIG_SRCH_UPD_type1 = mysqli_query($con,$sql1);
        $CONFIG_SRCH_UPD_save=0;

        //COMMON
        if($row=mysqli_fetch_array($CONFIG_SRCH_UPD_type1)){
            $CONFIG_SRCH_UPD_save= 2;
        }
        $con->autocommit(false);
        $CONFIG_SRCH_UPD_arr=array(3=>array("LMC_USER_RIGHTS_CONFIGURATION","URC_ID","URC_DATA","URC_USERSTAMP","(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP')"));
        if($flag==3){
            $sql="UPDATE ".$CONFIG_SRCH_UPD_arr_config[$flag][0]." SET ".$CONFIG_SRCH_UPD_arr_config[$flag][2]."= '$CONFIG_SRCH_UPD_data',".$CONFIG_SRCH_UPD_arr_config[$flag][3]."='$USERSTAMP' WHERE ".$CONFIG_SRCH_UPD_arr_config[$flag][1]."=".$CONFIG_SRCH_UPD_id;
        }
        else if($CONFIG_SRCH_UPD_type==13){
            $sql="UPDATE LMC_TEAM_CREATION SET TEAM_NAME='$CONFIG_SRCH_UPD_data',ULD_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP') WHERE TC_ID='$CONFIG_SRCH_UPD_id' ";
        }
        else if($CONFIG_SRCH_UPD_type==14){
            $sql="UPDATE LMC_MEETING_TOPIC SET MT_TOPIC='$CONFIG_SRCH_UPD_data',ULD_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP') WHERE MT_ID='$CONFIG_SRCH_UPD_id' ";
        }
        else if($CONFIG_SRCH_UPD_type==16){
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
    if($_REQUEST['option']=="update")
    {
        $CONFIG_ENTRY_arr_type=array(14=>array("LMC_MEETING_TOPIC","MT_TOPIC"),16=>array("LMC_REPORT_DOCUMENT_CATEGORY","RDC_CATEGORY"),13=>array("LMC_TEAM_CREATION","TEAM_NAME"),
            17=>array("LMC_MACHINERY_ITEM","MI_ITEM"),18=>array("LMC_MACHINERY_USAGE","MCU_MACHINERY_TYPE"),19=>array("LMC_FITTING_USAGE","FU_ITEMS"),20=>array("LMC_MATERIAL_USAGE","MU_ITEMS"));
        $CONFIG_SRCH_UPD_type=$_REQUEST['listboxtype'];
        $CONFIG_SRCH_UPD_data=$_REQUEST['CONFIG_SRCH_UPD_tb_data'];
        $CONFIG_SRCH_UPD_data=$con->real_escape_string($CONFIG_SRCH_UPD_data);
        $CONFIG_rowid=$_REQUEST['rowid'];
        $sql= "SELECT ".$CONFIG_ENTRY_arr_type[$CONFIG_SRCH_UPD_type][1]." FROM ".$CONFIG_ENTRY_arr_type[$CONFIG_SRCH_UPD_type][0]." CCN WHERE  ".$CONFIG_ENTRY_arr_type[$CONFIG_SRCH_UPD_type][1]."='$CONFIG_SRCH_UPD_data'";
        $sql_result= mysqli_query($con,$sql);
        $numrow=mysqli_num_rows($sql_result);
        if($numrow==0)
        {
            if($CONFIG_SRCH_UPD_type==13){
                $updatesql="UPDATE LMC_TEAM_CREATION SET TEAM_NAME='$CONFIG_SRCH_UPD_data',ULD_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP') WHERE TC_ID='$CONFIG_rowid' ";
            }
            else if($CONFIG_SRCH_UPD_type==14){
                $updatesql="UPDATE LMC_MEETING_TOPIC SET MT_TOPIC='$CONFIG_SRCH_UPD_data',ULD_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP') WHERE MT_ID='$CONFIG_rowid' ";
            }
            else if($CONFIG_SRCH_UPD_type==16){
                $updatesql="UPDATE LMC_REPORT_DOCUMENT_CATEGORY SET RDC_CATEGORY='$CONFIG_SRCH_UPD_data',ULD_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP') WHERE RDC_ID='$CONFIG_rowid' ";
            }
            else if($CONFIG_SRCH_UPD_type==17){
                $updatesql="UPDATE LMC_MACHINERY_ITEM SET MI_ITEM='$CONFIG_SRCH_UPD_data',ULD_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP') WHERE MI_ID='$CONFIG_rowid' ";
            }
            else if($CONFIG_SRCH_UPD_type==18){
                $updatesql="UPDATE LMC_MACHINERY_USAGE SET MCU_MACHINERY_TYPE='$CONFIG_SRCH_UPD_data',ULD_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP') WHERE MCU_ID='$CONFIG_rowid' ";
            }
            else if($CONFIG_SRCH_UPD_type==19){
                $updatesql="UPDATE LMC_FITTING_USAGE SET FU_ITEMS='$CONFIG_SRCH_UPD_data',ULD_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP') WHERE FU_ID='$CONFIG_rowid' ";
            }
            else if($CONFIG_SRCH_UPD_type==20){
                $updatesql="UPDATE LMC_MATERIAL_USAGE SET MU_ITEMS='$CONFIG_SRCH_UPD_data',ULD_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP') WHERE MU_ID='$CONFIG_rowid' ";
            }
            else{
                $updatesql="UPDATE LMC_USER_RIGHTS_CONFIGURATION  SET URC_DATA='$CONFIG_SRCH_UPD_data',ULD_ID=(SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$USERSTAMP') WHERE URC_ID='$CONFIG_rowid' ";
            }
            if (!mysqli_query($con,$updatesql)) {
                $updadeflag=0;
                die('Error: ' . mysqli_error($con));
            }
            else{
                $updadeflag=1;
            }
        }
        $values=array($numrow,$updadeflag);
        echo json_encode($values);
    }
}
