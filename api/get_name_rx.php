<?php
require "../db.php";

$q = $_GET['q'];

$sql = "SELECT
fullname
FROM
ovst_queue_server_dep
WHERE
date_visit = CURDATE() and depq = '$q'";

$res = mysqli_query($objCon, $sql);
$numrow = mysqli_num_rows($res);
if ($numrow > 0) {

    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
    $name = $row['fullname'];

// $khun = mb_substr($name, 0, 3);
    $split = explode(" ", $name);
    $fname = $split[0];
//echo $fname;
    $lname = $split[1];
    //echo " นามสกุล" . $lname;

    $arr = array($fname, $lname);
    $new_name = " '$arr[0] '$arr[1]";
    echo $new_name;
} else {
    echo "";
}
