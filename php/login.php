<?php
require("db_config.php");
$connectionOptions = array(
    "Database" => $user, // update me
    "Uid" => $pass, // update me
    "PWD" => $dbname // update me
);
//Establishes the connection
$username=$_POST['username'];
$password=$_POST['password'];//密码
$conn = sqlsrv_connect($servername, $connectionOptions);
$tsql= "SELECT * FROM users ON username='$username'";
$getResults= sqlsrv_query($conn, $tsql);

if ($getResults == FALSE)
    echo (sqlsrv_errors());
while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
    if ($row["password"]===$password) {//对比密码看是否正确
        setcookie("username",$row["username"]);
        echo "1";
        return;
    }
}
sqlsrv_free_stmt($getResults);
echo "0";