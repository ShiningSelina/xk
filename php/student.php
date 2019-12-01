<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>学生</title>
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/student.css">
    <script src="../vendor/bootstrap/js/bootstrap.js"></script>
    <script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/iconic/css/material-design-iconic-font.min.css">
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

        <div class="col-md-10">
            <?php
            require("db_config.php");
            $connectionOptions = array(
                "Database" => $dbname,
                "Uid" => $user,
                "PWD" => $pass
            );
            $username=$_POST['username'];
            $password=$_POST['password'];//密码
            $conn = sqlsrv_connect($servername, $connectionOptions);
            $tsql= "SELECT * FROM sectio";
            $getResults= sqlsrv_query($conn, $tsql);

            if ($getResults == FALSE)
                echo (sqlsrv_errors());
            while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
                    echo $row["course_id"];
            }
            sqlsrv_free_stmt($getResults);
            ?>
        </div>
    </div>
</div>
</body>
</html>