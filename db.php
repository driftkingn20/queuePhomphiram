<?php
$db_config = array(
    "host" => "192.168.0.254:3306", // กำหนด host
    "user" => "sa", // กำหนดชื่อ user
    "pass" => "+mysqlplkdbpxsoh@+", // กำหนดรหัสผ่าน
    "dbname" => "hos", // กำหนดชื่อฐานข้อมูล
    "charset" => "utf8", // กำหนด charset
);
$objCon = @new mysqli($db_config["host"], $db_config["user"], $db_config["pass"], $db_config["dbname"]);

if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    exit;
}

$objCon->set_charset("utf8");
