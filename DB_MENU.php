<?php
//error_reporting(0);
include "LMC_LIB/GET_USERSTAMP.php";
include "LMC_LIB/CONNECTION.php";
include "LMC_LIB/COMMON.php";
$USERSTAMP=$UserStamp;
if($_REQUEST['option']=="MENU")
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    try{

        $err_msg=get_error_msg('61,119,124');

        $select_loginid_role=mysqli_query($con,"SELECT URC_DATA from VW_ACCESS_RIGHTS_TERMINATE_LOGINID where ULD_USERNAME='$USERSTAMP' ");
        $login_id_role;
        while($row=mysqli_fetch_array($select_loginid_role)){
            $login_id_role=$row["URC_DATA"];
        }

        $main_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM LMC_USER_LOGIN_DETAILS ULD,LMC_USER_MENU_DETAILS UMP,LMC_MENU_PROFILE MP where ULD_USERNAME='$UserStamp' and ULD.RC_ID=UMP.RC_ID and MP.MP_ID=UMP.MP_ID AND ULD.ULD_TERMINATE_FLAG IS NULL ORDER BY MP.MP_ID ASC");

        $ure_values=array();
        $URSC_Main_menu_array=array();
        $i=0;
        while($row=mysqli_fetch_array($main_menu_data)){
            $URSC_Main_menu_array[]=$row["MP_MNAME"];

            $sub_menu_data= mysqli_query($con,"SELECT DISTINCT  MP_MSUB from LMC_USER_LOGIN_DETAILS ULD,LMC_USER_MENU_DETAILS UMP,LMC_MENU_PROFILE MP where ULD_USERNAME='$UserStamp'  and ULD.RC_ID=UMP.RC_ID and MP.MP_ID=UMP.MP_ID and MP.MP_MNAME='$URSC_Main_menu_array[$i]' ORDER BY MP_MSUB ASC");
            $URSC_sub_menu_row=array();
            $URSC_sub_sub_menu_row_col=array();
            $URSC_sub_sub_menu_row_col_data=array();
            $j=0;
            while($row=mysqli_fetch_array($sub_menu_data))  {
                $file_name=array();
//                if($row["MP_MSUB"]==null||$row["MP_MSUB"]==""){
//                    $file_name[]=$row["MP_MFILENAME"];
//                    continue;
//                }

//                $file_name[]=$row["MP_MFILENAME"];


                $URSC_sub_menu_row[]=$row["MP_MSUB"];
                if($row["MP_MSUB"]==null)
                {
                    $sub_menu_data1= mysqli_query($con,"SELECT DISTINCT  MP_MFILENAME from LMC_USER_LOGIN_DETAILS ULD,LMC_USER_MENU_DETAILS UMP,LMC_MENU_PROFILE MP where ULD_USERNAME='$UserStamp'  and ULD.RC_ID=UMP.RC_ID and MP.MP_ID=UMP.MP_ID and MP.MP_MNAME='$URSC_Main_menu_array[$i]' ORDER BY MP_MSUB ASC");
                    while($row=mysqli_fetch_array($sub_menu_data1))  {
                        $file_name=array();
                        $file_name[]=$row["MP_MFILENAME"];
                }
                }


                $sub_sub_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MSUBMENU,MP_MFILENAME,MP_SCRIPT_FLAG FROM LMC_USER_LOGIN_DETAILS ULD,LMC_USER_MENU_DETAILS UMP,LMC_MENU_PROFILE MP where ULD_USERNAME='$UserStamp'  and ULD.RC_ID=UMP.RC_ID and MP.MP_ID=UMP.MP_ID and MP.MP_MNAME='$URSC_Main_menu_array[$i]' AND MP_MSUB='$URSC_sub_menu_row[$j]'  ORDER BY MP_MSUBMENU ASC");
                $URSC_sub_sub_menu_row_data=array();
                $script_flag=array();

                while($row=mysqli_fetch_array($sub_sub_menu_data)){

                    $script_flag[]=$row["MP_SCRIPT_FLAG"];
                    $file_name[]=$row["MP_MFILENAME"];
                    if($row["MP_MSUBMENU"]==null||$row["MP_MSUBMENU"]=="")continue;
                    $URSC_sub_sub_menu_row_data[]=$row["MP_MSUBMENU"];

                }
                $URSC_script_flag[]=$script_flag;
                $URSRC_filename[]=array_unique($file_name);
                $URSC_sub_sub_menu_data_array[]=$URSC_sub_sub_menu_row_data;
                $j++;
            }

            $URSC_sub_menu_array[]=$URSC_sub_menu_row;

            $i++;
        }

        if(count($URSC_Main_menu_array)!=0){
            $final_values=array($URSC_Main_menu_array, $URSC_sub_menu_array,$URSC_sub_sub_menu_data_array,$URSC_script_flag,$URSRC_filename,$login_id_role);    // $final = array($URSC_sub_menu_array,$URSC_sub_sub_menu_array,$URSC_sub_sub_menu_data_array);
        }
        else{

            $final_values=array($URSC_Main_menu_array,$err_msg);
        }


        echo JSON_ENCODE($final_values);
    }
    catch (mysqli_sql_exception $e) {


        echo $e->getMessage();

    }
}

?>