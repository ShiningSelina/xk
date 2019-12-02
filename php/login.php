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
$tsql= "SELECT * FROM users where username = '$username'";
$getResults= sqlsrv_query($conn, $tsql);

if ($getResults == FALSE)
    echo (sqlsrv_errors());
while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
    if (trim($row['password'])===$password) {//对比密码看是否正确
        setcookie("username",$row['username']);
        echo "1";
        return;
    }
}
echo "0";
sqlsrv_free_stmt($getResults);