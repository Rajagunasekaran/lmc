<?php
error_reporting(0);
include "../LMC_LIB/CONNECTION.php";
include "../LMC_LIB/GET_USERSTAMP.php";
include "../LMC_LIB/COMMON.php";
$dir=dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR;
$userstamp_id=mysqli_query($con,"SELECT ULD_ID FROM LMC_USER_LOGIN_DETAILS  WHERE ULD_USERNAME='$UserStamp'");
if($row=mysqli_fetch_array($userstamp_id))
{
    $uldid=$row["ULD_ID"];
}
if($_REQUEST['option']=='INITIAL_DATA') {
//REFERENCE NO
    $refno = mysqli_query($con, "SELECT LML_REF_NO FROM LMC_MAINTAIN_LOCATION_DETAILS ORDER BY LML_REF_NO ASC");
    while ($row = mysqli_fetch_array($refno)) {
        $refereno[] = $row['LML_REF_NO'];
    }

    $values = array($refereno);
    echo json_encode($values);
}
if($_REQUEST['option']=="Referencenosearch")
{
    $referenceno=$_REQUEST['refno'];
    $selectquery=mysqli_query($con,"SELECT A.*,B.CLD_CONTRACT_NO,B.CLD_INCHARGE_PERSON FROM LMC_MAINTAIN_LOCATION_DETAILS A, LMC_CONTRACT_DETAILS B  WHERE A.CLD_ID=B.CLD_ID AND LML_REF_NO='$referenceno'");
    while($row=mysqli_fetch_array($selectquery))
    {
        $location=$row['LML_LOCATION'];
        $referenceno=$row['LML_REF_NO'];
        $contractno=$row['CLD_CONTRACT_NO'];
        $workorderno=$row['LML_WORKER_ORDER_NO'];
        $oic=$row['CLD_INCHARGE_PERSON'];
        $datecreated=$row['LML_DATE_OF_ENTERED'];
        $datecreated=date('d-m-Y',strtotime($datecreated));
        $datecompleted=$row['LML_DATE_OF_COMPLETED'];
        $datecompleted=date('d-m-Y',strtotime($datecompleted));
        $dateverification=$row['LML_VERIFICATION_DATE'];
        $dateverification=date('d-m-Y',strtotime($dateverification));
        $fetchedvalues=(object)['location'=>$location,'refernceno'=>$referenceno,'contractno'=>$contractno,'workorderno'=>$workorderno,'oic'=>$oic,'dateentered'=>$datecreated,'datecompleted'=>$datecompleted,'dateverification'=>$dateverification];
    }
echo json_encode($fetchedvalues);
}
if($_REQUEST['option']=='itemnosearch')
{
    $itemno=$_REQUEST['itemno'];
}
