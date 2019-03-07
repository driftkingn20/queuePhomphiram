<?php
require "../db.php";

$q = $_GET['q'];
if ($q == 's99999') {
    exit;
}

$sql = "SELECT q.fullname,q.name FROM ovst_queue_server q WHERE depq = '$q' and q.date_visit = CURDATE() AND stationno is not null LIMIT 1";

$result = mysqli_query($objCon, $sql);
$total = mysqli_num_rows($result);
if ($total < 1) {
    echo "-";
    exit;
}

$rows = mysqli_fetch_array($result, MYSQLI_ASSOC);

$numstring = mb_strlen($rows['fullname']);

if ($numstring < 30) {
    echo $rows['fullname'];
} else {
    echo $rows['name'];
}
