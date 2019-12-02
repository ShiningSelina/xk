<?php
require("db_config.php");
$connectionOptions = array(
    "Database" => $dbname,
    "Uid" => $user,
    "PWD" => $pass
);
$section_id=$_POST['section_id'];
$semester=$_POST["semester"];
$course_id=$_POST['course_id'];
$year=$_POST['year'];
$user_id=$_COOKIE["username"];
$conn = sqlsrv_connect($servername, $connectionOptions);
$tsql= "SELECT count(*) FROM dropSec where student_id = '{$user_id}' and section_id='{$section_id}' and semester='{$semester}' and year={$year} and course_id='{$course_id}'";
$getResults= sqlsrv_query($conn, $tsql);
while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
    foreach($row as $value){
        if($value===0){
            $tsql1= "INSERT INTO takes (student_id, section_id, course_id, semester, year) values ('{$user_id}', '{$section_id}', '{$course_id}', '{$semester}', {$year})";
            $getResults1= sqlsrv_query($conn, $tsql1);
            if ($getResults1 == FALSE) {
                echo "0";
            }else{
                $tsql2= "update section set take_num=take_num+1 where section_id='{$section_id}' and semester='{$semester}' and year={$year} and course_id='{$course_id}'";
                $getResults2= sqlsrv_query($conn, $tsql2);
                echo "1";
            }
        }else{
            echo "2";
        }
        sqlsrv_free_stmt($getResults1);
    }
}
sqlsrv_free_stmt($getResults);