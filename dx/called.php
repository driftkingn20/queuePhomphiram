<?php
require "../db.php";

$depcode = '003';

// 1
$sql = "SELECT
q.depq,q.fullname,q.`name`,t.stationno,q.*
FROM
ovst_queue_server q
inner join ovst_queue_server_time t on t.vn = q.vn
WHERE
mod(q.status,2) = 1 and t.`status` = '1'
AND q.date_visit = CURDATE()
AND q.stationno is not null
and q.dep = '$depcode'
ORDER BY
t.time_start desc limit 11";

$query = mysqli_query($objCon, $sql);
while ($result2 = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
    $level = $result2["dep_level"];
    ?>
 <div class="rowsanimate">
    <div class="rowlist" style="flex:1;text-align: center;"><?=$result2["depq"];?></div>
    <div class="rowlist" style="flex:3;text-align: center;"><?=$result2["fullname"];?></div>
</div>

<?php
}
?>