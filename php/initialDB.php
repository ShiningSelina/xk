<?php
require("db_config.php");
require("../PHPExcel-1.8/Classes/PHPExcel/Shared/Date.php");
$connectionOptions = array(
    "Database" => $dbname, // update me
    "Uid" => $user, // update me
    "PWD" => $pass // update me
);
$conn = sqlsrv_connect($servername, $connectionOptions);
if( $conn == false){

    echo "连接数据库失败！";
    // die( print_r( sqlsrv_errors(), true));
}
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
    $highestRow = $sheet->getHighestDataRow();           // 取得总行数
    $highestColumn = $sheet->getHighestDataColumn();     // 取得总列数
    $res_arr = array();
    for ($row = 1; $row <= $highestRow; $row++) {
        $val = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];
        $res_arr[] = $val;
    }
    return $res_arr;
}
$departmentArr = excelToArray('../data/department.xlsx');
$studentArr = excelToArray('../data/student.xlsx');
$classroomArr = excelToArray('../data/classroom.xlsx');
$courseArr = excelToArray('../data/course.xlsx');
$examArr = excelToArray('../data/exam.xlsx');
$instructorArr = excelToArray('../data/instructor.xlsx');
$sectionArr = excelToArray('../data/section.xlsx');
$takesArr = excelToArray('../data/takes.xlsx');
$teachesArr = excelToArray('../data/teaches.xlsx');
$timeSlotArr = excelToArray('../data/time_slot.xlsx');
$usersArr = excelToArray('../data/users.xlsx');
$totalRow = count($timeSlotArr);

for ($row = 1;$row <= $totalRow;$row ++){
    $timeSlotArr[$row][2] = gmdate("H:i",PHPExcel_Shared_Date::ExcelToPHP($timeSlotArr[$row][2]));
    $timeSlotArr[$row][3] = gmdate("H:i",PHPExcel_Shared_Date::ExcelToPHP($timeSlotArr[$row][3]));
    if(is_numeric($timeSlotArr[$row][1]))
        $timeSlotArr[$row][1] = gmdate("Y/m/d",PHPExcel_Shared_Date::ExcelToPHP($timeSlotArr[$row][1]));
}
function checkSectionConflict($sectionArr){
    $arr = array();
    $totalRow = count($sectionArr);
    $totalCol = count($sectionArr[1]);

    for($row = 1;$row < $totalRow;$row ++){

        if ($sectionArr[$row][0] != 0){
            for ($nrow = $row + 1;$nrow <= $totalRow;$nrow++){
                if(($sectionArr[$row][4] === $sectionArr[$nrow][4] && $sectionArr[$row][5] === $sectionArr[$nrow][5] && $sectionArr[$row][6] === $sectionArr[$nrow][6])){
                    $sectionArr[$nrow][0] = 0;
                    echo "课程{$sectionArr[$nrow][1]}和课程{$sectionArr[$row][1]}冲突，上课时间和上课地点相同，课程{$sectionArr[$nrow][1]}无法插入开课数据库";
                    echo "<br>";
                }
                if($sectionArr[$row][6] === $sectionArr[$nrow][6] && $sectionArr[$row][9] === $sectionArr[$nrow][9]){
                    $sectionArr[$nrow][0] = 0;
                    echo "课程{$sectionArr[$nrow][1]}和课程{$sectionArr[$row][1]}冲突，上课时间和任课教师相同, 课程{$sectionArr[$nrow][1]}无法插入开课数据库";
                    echo "<br>";
                }
            }
        }
    }
    for ($row = 0; $row < $totalRow; $row++) {
        $row_arr = array();
        if($sectionArr[$row][0] != 0 || $row === 0) {
            for ($column = 0; $column < $totalCol - 1; $column++) {
                $val = $sectionArr[$row][$column];
                $row_arr[] = $val;
            }
            $arr[] = $row_arr;
        }
    }
    return $arr;
}
$checkSectionArr = checkSectionConflict($sectionArr);

function insertDB($dataArr,$dbname,$conn){
    $totalRow = count($dataArr);
    $totalCol = count($dataArr[1]);
    $numericType = array('year','total_credit','credit','take_num','room_capacity','sec_capacity');
    for($row = 1;$row < $totalRow;$row ++){
        $site = '';
        $value = '';
        // $param = '';
        for ($col = 0;$col < $totalCol;$col ++){
            $site .= $dataArr[0][$col];
            // echo $site."<br>";
            if(in_array($dataArr[0][$col],$numericType))
                $value .= $text=iconv("UTF-8","GBK",$dataArr[$row][$col]);
            else{
                $value .= $text=iconv("UTF-8","GBK","'{$dataArr[$row][$col]}'");
                // $param .= '?' ;
            }
            if($col != $totalCol - 1){
                $site .= ',';
                $value .= ',';
            }

        }
        // echo $value."<br>";
        $sql = "insert into {$dbname} ({$site}) values ({$value})";
        // echo iconv("GBK","UTF-8",$sql);
        $result = sqlsrv_query($conn,$sql);
    }
}

insertDB($departmentArr,'department',$conn);
insertDB($studentArr,'student',$conn);
insertDB($usersArr,'users',$conn);
insertDB($instructorArr,'instructor',$conn);
insertDB($courseArr,'course',$conn);
insertDB($timeSlotArr,'time_slot',$conn);
insertDB($classroomArr,'classroom',$conn);
insertDB($checkSectionArr,'section',$conn);
insertDB($examArr,'exam',$conn);

function checkTakesConflict($takesArr,$conn){
    $arr = array();
    $totalRow = count($takesArr);
    $totalCol = count($takesArr[1]);
    for ($row = 1;$row < $totalRow;$row ++){
        if ($takesArr[$row][1] != 0){
            $thisRow = array();
            $secsql= "SELECT * FROM section where course_id = '{$takesArr[$row][2]}' and section_id = '{$takesArr[$row][1]}' and semester = '{$takesArr[$row][3]}' and year = {$takesArr[$row][4]}";
            $getResults= sqlsrv_query($conn, $secsql);
            if ($getResults == FALSE)
                echo (sqlsrv_errors());
            while ($secRow = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
                $thisRow[] = $secRow['time_slot_id'];
            }
            sqlsrv_free_stmt($getResults);
            for ($nrow = $row + 1;$nrow <= $totalRow;$nrow++){
                if ($takesArr[$row][0] === $takesArr[$nrow][0] && $takesArr[$nrow][1] != 0){
                    $leftRow = array();
                    $nsecsql= "SELECT * FROM section where course_id = '{$takesArr[$nrow][2]}' and section_id = '{$takesArr[$nrow][1]}' and semester = '{$takesArr[$nrow][3]}' and year = {$takesArr[$nrow][4]}";
                    $getResults= sqlsrv_query($conn, $nsecsql);
                    if ($getResults == FALSE)
                        echo (sqlsrv_errors());
                    while ($secRow = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
                        $leftRow[] = $secRow['time_slot_id'];
                    }
                    sqlsrv_free_stmt($getResults);
                    if($thisRow[0] === $leftRow[0]){
                        $takesArr[$nrow][1] = 0;
                        echo "学生{$takesArr[$row][0]}所选课程{$takesArr[$nrow][2]}和课程{$takesArr[$row][2]}上课时间冲突，课程{$takesArr[$nrow][2]}无法插入选课数据库";
                        echo "<br>";
                    }
                }
            }
            $thisRow = array();
            $examsql= "SELECT * FROM exam where course_id = '{$takesArr[$row][2]}' and section_id = '{$takesArr[$row][1]}' and semester = '{$takesArr[$row][3]}' and year = '{$takesArr[$row][4]}'";
            $getResults= sqlsrv_query($conn, $examsql);
            if ($getResults == FALSE)
                echo (sqlsrv_errors());
            while ($secRow = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
                $thisRow[] = $secRow['time_slot_id'];
            }
            sqlsrv_free_stmt($getResults);
            for ($nrow = $row + 1;$nrow <= $totalRow;$nrow++){
                if ($takesArr[$row][0] === $takesArr[$nrow][0] && $takesArr[$nrow][1] != 0){
                    $leftRow = array();
                    $nexamsql= "SELECT * FROM exam where course_id = '{$takesArr[$nrow][2]}' and section_id = '{$takesArr[$nrow][1]}' and semester = '{$takesArr[$nrow][3]}' and year = '{$takesArr[$nrow][4]}'";
                    $getResults= sqlsrv_query($conn, $nexamsql);
                    if ($getResults == FALSE)
                        echo (sqlsrv_errors());
                    while ($secRow = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
                        $leftRow[] = $secRow['time_slot_id'];
                    }
                    sqlsrv_free_stmt($getResults);
                    if($thisRow[0] === $leftRow[0]){
                        $takesArr[$nrow][1] = 0;
                        echo "学生{$takesArr[$row][0]}所选课程{$takesArr[$nrow][2]}和课程{$takesArr[$row][2]}考试时间冲突，课程{$takesArr[$nrow][2]}无法插入选课数据库";
                        echo "<br>";
                    }
                }
            }
        }
    }
    // print_r($takesArr);
    for ($row = 0; $row < $totalRow; $row++) {
        $row_arr = array();
        if($takesArr[$row][1] != 0 || $row === 0) {
            for ($column = 0; $column < $totalCol; $column++) {
                $val = $takesArr[$row][$column];
                $row_arr[] = $val;
                // echo $val;
            }
            $arr[] = $row_arr;
        }
    }
    return $arr;
}

$checkTakesArr = checkTakesConflict($takesArr,$conn);
insertDB($checkTakesArr,'takes',$conn);
insertDB($teachesArr,'teaches',$conn);
