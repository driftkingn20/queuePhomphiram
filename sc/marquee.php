<?php
require "../db.php";

$sql = "SELECT dep_inform FROM kskdepartment where depcode = '002'";

$query = mysqli_query($objCon, $sql);
$result = mysqli_fetch_array($query, MYSQLI_ASSOC);
echo $result['dep_inform'];
