<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/11/30
 * Time: 15:59
 */
require("db_config.php");
$connectionOptions = array(
    "Database" => $dbname, // update me
    "Uid" => $user, // update me
    "PWD" => $pass // update me
);
$conn = sqlsrv_connect($servername, $connectionOptions);
//$serverName = "xk.database.chinacloudapi.cn"; //数据库服务器地址
//$uid = "shilin"; //数据库用户名
//$pwd = "ROOTroot1"; //数据库密码
//$connectionInfo = array("UID"=>$uid, "PWD"=>$pwd, "Database"=>'xk');
//$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn == false){
    echo "连接数据库失败！";
    die( print_r( sqlsrv_errors(), true));
}
require_once dirname(__FILE__).'../PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
function excelToArray($filename){
    try {
        $objPHPExcelReader = PHPExcel_IOFactory::load($filename);
    } catch (PHPExcel_Reader_Exception $e) {
        die($e->getMessage());
    }
    try {
        $sheet = $objPHPExcelReader->getSheet(0);
    } catch (PHPExcel_Exception $e) {
        die($e->getMessage());
    }
    $highestRow = $sheet->getHighestRow();           // 取得总行数
    $highestColumn = $sheet->getHighestColumn();     // 取得总列数
//    $arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M', 'N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    $res_arr = array();
    for ($row = 1; $row <= $highestRow; $row++) {
        $row_arr = array();
        for ($column = 'A'; $column <= $highestColumn; $column++) {
            $val = $sheet->getCellByColumnAndRow($column, $row)->getValue();
            $row_arr[] = $val;
        }

        $res_arr[] = $row_arr;
    }
    return $res_arr;
}
$department = excelToArray('../data/department.xlsx');
$student = excelToArray('../data/student.xlsx');
$classroom = excelToArray('../data/classroom.xlsx');
$course = excelToArray('../data/classroom.xlsx');
$exam = excelToArray('../data/exam.xlsx');
$instructor = excelToArray('../data/instructor.xlsx');
$section = excelToArray('../data/section.xlsx');
$takes = excelToArray('../data/takes.xlsx');
$teaches = excelToArray('../data/teaches.xlsx');
$timeSlot = excelToArray('../data/time_slot.xlsx');
$user = excelToArray('../data/user.xlsx');

function insertDB($dataArr,$dbname,$conn){
    $totalRow = count($dataArr);
    $totalCol = count($dataArr[0]);
    for($row = 1;$row < $totalRow;$row ++){
        for ($col = 0;$col < $totalCol;$col ++){
            $sql = "insert into '$dbname'({$dataArr[0][$col]}) values ({$dataArr[$row][$col]})";
            sqlsrv_query($conn,$sql);
        }
    }
}