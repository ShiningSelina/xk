<?php
require("db_config.php");
$connectionOptions = array(
    "Database" => $dbname,
    "Uid" => $user,
    "PWD" => $pass
);
$conn = sqlsrv_connect($servername, $connectionOptions);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>学生</title>
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
    <script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/iconic/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="../vendor/jquery/pagination.css" />
    <script type="text/javascript" src="../vendor/jquery/jquery.pagination.js"></script>
    <link rel="stylesheet" href="../css/reset.css" />
    <link rel="stylesheet" href="../css/common.css" />
    <link rel="stylesheet" href="../css/highlight.min.css" />
    <link rel="stylesheet" href="../js/highlight.min.js" />
</head>
<body>
<div class="container" style="margin: 0">
    <div class="row">
        <div class="col-md-1" style="margin-top: 0.5%">
            <img src="../images/fd.jpg" height="70px" width="70px">
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-5" style="margin-top: 2%">
            <div class="input-group">
                <input type="text" class="form-control input-lg" placeholder="键入搜索课程"><span class="input-group-addon btn btn-primary">搜索</span>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-2" style="margin-top: 1%;background-color: purple;height: 57ch;border-top-right-radius:  40px">
            <div style="text-align: left;margin-left: 20px" class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <h4 class="panel-title">
                        <p id="p1" style="color:white;font-size: 18px;cursor: pointer;padding-top: 60px" data-toggle="collapse" data-parent="#accordion">
                            <i class="fa fa-plus-square" aria-hidden="true"></i> 选课
                        </p>
                    </h4>
                </div>
                <div class="panel panel-default">
                    <h4 class="panel-title">
                        <p id="p2" style="color:white;font-size: 18px;cursor: pointer;padding-top: 30px" data-toggle="collapse" data-parent="#accordion">
                            <i class="fa fa-calendar" aria-hidden="true"></i> 我的课程<span id="totalMessage" style="background-color: gold" class="badge pull-right"></span>
                        </p>
                    </h4>
                </div>
                <div class="panel panel-default">
                    <h4 class="panel-title">
                        <p id="p3" style="color:white;font-size:18px;cursor: pointer;padding-top: 30px" data-toggle="collapse" data-parent="#accordion">
                            <i class="fa fa-address-card" aria-hidden="true"></i> 我的
                        </p>
                    </h4>
                </div>

                <div class="panel panel-default">
                    <h4 class="panel-title">
                        <p id="p4" style="color:white;font-size: 18px;cursor: pointer;padding-top: 30px" data-toggle="collapse" data-parent="#accordion">
                            <i class="fa fa-times" aria-hidden="true"></i> 登出
                        </p>
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-10" style="margin-right:-100px">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>课程代码</th>
                    <th>课程名称</th>
                    <th>开课系所</th>
                    <th>学分</th>
                    <th>教师</th>
                    <th>已选/上限</th>
                    <th>课程安排</th>
                    <th>考试安排</th>
                    <th>选课</th>
                    <th>选课事务申请</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $tsql= "SELECT * FROM section s inner JOIN course c on s.course_id=c.course_id where year=2019 and semester='fall'";
                $getResults= sqlsrv_query($conn, $tsql);

                if ($getResults == FALSE)
                    print_r(sqlsrv_errors());
                while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
                    $course_name=iconv("GBK","UTF-8",$row['course_name']);
                    $dept_name=iconv("GBK","UTF-8",$row['dept_name']);
                    echo "<tr><td>{$row['course_id']}</td><td>{$course_name}</td><td>{$dept_name}</td><td>{$row['credit']}</td>";
                    $tsql1= "SELECT * FROM (teaches t inner JOIN section s on s.course_id=t.course_id and s.semester=t.semester and s.year=t.year and s.section_id=t.section_id) inner join instructor i on t.instructor_id=i.instructor_id where s.year=2019 and s.semester='fall' and s.section_id='{$row['section_id']}' and s.course_id='{$row['course_id']}'";
                    $getResults1= sqlsrv_query($conn, $tsql1);
                    echo "<td>";
                    while ($row1 = sqlsrv_fetch_array($getResults1, SQLSRV_FETCH_ASSOC)) {
                        $instructor_name=iconv("GBK","UTF-8",$row1['instructor_name']);
                        echo $instructor_name." ";
                    }
                    echo "</td>";
                    sqlsrv_free_stmt($getResults1);
                    echo "<td>{$row['take_num']}/{$row['sec_capacity']}</td>";
                    $tsql2= "SELECT * FROM section s inner join time_slot t on s.time_slot_id=t.time_slot_id where year=2019 and semester='fall' and section_id='{$row['section_id']}' and course_id='{$row['course_id']}'";
                    $getResults2= sqlsrv_query($conn, $tsql2);
                    echo "<td>";
                    while ($row2 = sqlsrv_fetch_array($getResults2, SQLSRV_FETCH_ASSOC)) {
                        //$re=$row2['day']." ".$row2['start_time']."-".$row2['end_time'];
                        echo $row2['day'].". ".$row2['start_time']->format('H:i')."-".$row2['end_time']->format('H:i');
                        echo "<br>";
                    }
                    echo "</td>";
                    sqlsrv_free_stmt($getResults2);

                    $tsql3= "SELECT * FROM exam e inner join time_slot t on e.time_slot_id=t.time_slot_id where year=2019 and semester='fall' and section_id='{$row['section_id']}' and course_id='{$row['course_id']}'";
                    $getResults3= sqlsrv_query($conn, $tsql3);
                    echo "<td>";
                    while ($row3 = sqlsrv_fetch_array($getResults3, SQLSRV_FETCH_ASSOC)) {
                        $exam_name=iconv("GBK","UTF-8",$row3['exam_name']);
                        echo $exam_name."<br>".$row3["day"]." ".$row3['start_time']->format('H:i')."-".$row3['end_time']->format('H:i');
                    }
                    echo "</td><td><button type='button' onclick=\"xk(".$row['section_id'].",'".$row['semester']."',".$row['year'].",'".$row['course_id']."')\" class='btn btn-default'>选课</button></td><td><button type='button' class='btn btn-default'>申请</button></td>";
                    sqlsrv_free_stmt($getResults3);
                }
                sqlsrv_free_stmt($getResults);
                ?>
                </tbody>
            </table>

            <div class="m-style M-box"></div>
            <p class="tips">当前是第<span class="now"></span>页</p>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('pre code').each(function (i, block) {
            hljs.highlightBlock(block);
        });
        $('.M-box').pagination({
            callback: function (api) {
                $('.now').text(api.getCurrent());
            }
        }, function (api) {
            $('.now').text(api.getCurrent());
        });

    });
    function xk(section_id,semester,year,course_id) {
        $.ajax({
            type: "post",
            data: {
                "section_id": section_id,
                "semester": semester,
                "year": year,
                "course_id": course_id
            },
            url: "xk.php",
            success: function (response) {
                if (response.toString() === "1") {
                    alert("选课成功！");
                }else if (response.toString() === "2"){//登录失败给出提示
                    alert("您退过该课程，无法选课！");
                }else{
                    alert(response);
                }
            }
        });
    }
</script>
</body>
</html>