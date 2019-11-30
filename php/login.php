<?php
require("db_config.php");

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
$username=$_POST['username'];
$password=$_POST['password'];//密码
$result=$conn->query("SELECT * FROM users where username='$username'");
while($row=$result->fetch_assoc()){
    if ($row["password"]===$password) {//对比密码看是否正确
        setcookie("username",$row["username"]);
        echo "1";
        return;
    }

}
echo "0";