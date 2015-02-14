<?php
////function db_connection(){
//mysqli_report(MYSQLI_REPORT_STRICT);
////try{
////$con=mysqli_connect("192.168.1.138","TST","TST");//database connection
//$con=mysqli_connect("192.168.1.102","root","");
//
//if (mysqli_connect_errno()) {
//    echo "Failed to connect to mysqli: " . mysqli_connect_error();
//}
//$con=mysqli_connect("192.168.1.126","root","","alliance_ts_sqlteam");
//$con=mysqli_connect("localhost","root","","tsnew");
//    return mysqli_connect("192.168.1.115","root","","TIMESHEET");

//}

$con = new mysqli(null,
    'DIV', // username
    'DIV',     // password
    'TS_UAT',
    null,
    '/cloudsql/ei-html-ssomens:eihtmlssomens');
?>