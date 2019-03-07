<?php
require "../db.php";

$sql = "SELECT dep_inform FROM kskdepartment where depcode = '009'";

$query = mysqli_query($objCon, $sql);
$result = mysqli_fetch_array($query, MYSQLI_ASSOC);
echo '<marquee><h1 style="color:white;font-weight: bolder;">' . $result['dep_inform'] . '</h1></marquee>';
