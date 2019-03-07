<?php
require "../db.php";

$depcode = '002';

$sql = "SELECT

(
SELECT
if(count(t2.vn) > 0,1,0) called
FROM
ovst_queue_server_time t2
LEFT JOIN ovst_queue_server q2 ON q2.vn = t2.vn
WHERE
q2.date_visit = CURDATE()
and q2.dep = '$depcode'
AND MOD ( t2.STATUS, 2 ) = 1  AND t2.stationno IS not NULL
) called,q.*
from
ovst_queue_server q
inner join ovst_queue_server_time tt on tt.vn = q.vn
WHERE
tt.status = 1
AND tt.date_visit = CURDATE()
AND q.stationno IS NOT NULL
and q.dep = '$depcode'
ORDER BY
q.dep_level desc, tt.time_start desc
";

$query2 = mysqli_query($objCon, $sql);

?>

<?php
$sqlminute = "SELECT time_wait,time_start,dep_station from kskdepartment where depcode = '$depcode'";
$queryminute = mysqli_query($objCon, $sqlminute);
$resultminute = mysqli_fetch_array($queryminute, MYSQLI_ASSOC);
$time = $resultminute["time_wait"];
$workstart = $resultminute["time_start"];
date_default_timezone_set("Asia/Bangkok");
$now = date("H:i:s");
$dep = $resultminute["dep_station"];

$dateDiff = intval((strtotime($workstart) - strtotime($now)) / 60);

if ($dateDiff < 0) {
    $dateDiff = 0;
}

function convertToHoursMins($sumtime)
{
    if ($sumtime < 1) {
        return "-";
    }
    $hours = floor($sumtime / 60);
    $minutes = ($sumtime % 60);
    $mod = fmod($sumtime, 60);

    if ($hours >= 1 && $mod == 0) {
        return $hours . " ช.ม.";
    }
    if ($hours >= 1) {
        return $hours . " ชม. " . $minutes . " น.";
    } else {
        return $minutes . " นาที";
    }

    return "-";
}

$counter = 0;
// $n = 0;
// $color = '';
while ($result2 = mysqli_fetch_array($query2, MYSQLI_ASSOC)) {
    $called = $result2["called"];
    // $n++;
    // $color = $n%2==0?'skyblue':'white';
    $div = $counter / $dep;
    $x = floor($div);

    $sumtime = ((round($x) + $called)) * $time + $dateDiff;

    $vn = $result2["vn"];
    $sqlupdate = "UPDATE ovst_queue_server
  SET wait_dep='$sumtime'
  WHERE vn='$vn'";
    $queryupdate = mysqli_query($objCon, $sqlupdate);
    ?>

<div class="rowsanimate">
    <div class="rowlist" style="flex:1;text-align: center;"><?=$result2["depq"];?></div>
    <div class="rowlist" style="flex:1;text-align: center;"><?=$result2["stationno"];?></div>
</div>

      <?php
$counter++;
}
?>

