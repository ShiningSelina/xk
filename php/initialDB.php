<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/11/30
 * Time: 15:59
 */
$serverName = "xk.database.chinacloudapi.cn"; //数据库服务器地址
$uid = "shilin"; //数据库用户名
$pwd = "ROOTroot1"; //数据库密码
$connectionInfo = array("UID"=>$uid, "PWD"=>$pwd, "Database"=>'xk2');
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn === false){
    echo "连接数据库失败！";
    // die( print_r( sqlsrv_errors(), true));
}
echo $conn;
require("../PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
function excelToArray($filename){
    try {
        $objPHPExcelReader = PHPExcel_IOFactory::load($filename);
    } catch (PHPExcel_Reader_Exception $e) {
        echo $e->getMessage();
    }
    try {
        $sheet = $objPHPExcelReader->getSheet(0);
    } catch (PHPExcel_Exception $e) {
        echo $e->getMessage();
    }
    $highestRow = $sheet->getHighestRow();           // 取得总行数
    $highestColumn = $sheet->getHighestColumn();     // 取得总列数
    $res_arr = array();
    for ($row = 1; $row <= $highestRow; $row++) {
        $val = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];
        $res_arr[$row] = $val;
    }
    return $res_arr;
}
$departmentArr = excelToArray('../data/department.xlsx');
// $studentArr = excelToArray('../data/student.xlsx');
// $classroomArr = excelToArray('../data/classroom.xlsx');
// $courseArr = excelToArray('../data/classroom.xlsx');
// $examArr = excelToArray('../data/exam.xlsx');
// $instructorArr = excelToArray('../data/instructor.xlsx');
// $sectionArr = excelToArray('../data/section.xlsx');
// $takesArr = excelToArray('../data/takes.xlsx');
// $teachesArr = excelToArray('../data/teaches.xlsx');
// $timeSlotArr = excelToArray('../data/time_slot.xlsx');
// $userArr = excelToArray('../data/user.xlsx');
print_r($departmentArr);
$totalRow = count($departmentArr);
$totalCol = count($departmentArr[1]);
echo $totalRow;
echo $totalCol;
for($row = 1;$row < $totalRow;$row ++){
    for ($col = 0;$col < $totalCol;$col ++){
        print_r($departmentArr[$row][$col]);
    }
}
function insertDB($dataArr,$dbname){
    $totalRow = count($dataArr);
    $totalCol = count($dataArr[1]);
    // echo $totalRow;
    for($row = 2;$row < $totalRow;$row ++){
        // for ($col = 0;$col < $totalCol;$col ++){

        $sql = "insert into {$dbname} ({$dataArr[1][0]}, {$dataArr[1][1]}) values ('{$dataArr[$row][0]}', '{$dataArr[$row][1]}')";
        echo $sql;
        sqlsrv_query($conn,$sql);
        // }
    }
}
$dbname = 'department';
$name = $departmentArr[2][0];
$cam = $departmentArr[2][1];
insertDB($departmentArr,'department');
// $sql = "insert into department values (N'中国语言文学系', N'邯郸校区')";
// echo $sql;

$tsql= "SELECT * FROM department";
$getResults= sqlsrv_query($conn, $tsql);

while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
    echo $row["dept_ment"];
    echo $row["campus"];
}