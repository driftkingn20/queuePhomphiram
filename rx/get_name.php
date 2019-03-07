<?php
require "../db.php";

$q = $_GET['q'];
if ($q == 's99999') {
    exit;
}

$sql = "SELECT q.fullname FROM ovst_queue_server_dep q WHERE depq = '$q' and q.date_visit = CURDATE() LIMIT 1";

$result = mysqli_query($objCon, $sql);
$total = mysqli_num_rows($result);
if ($total < 1) {
    echo "-";
    exit;
}

$rows = mysqli_fetch_array($result, MYSQLI_ASSOC);

echo $rows['fullname'];
