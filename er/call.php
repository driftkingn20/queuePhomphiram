<?php
require "../db.php";

$sql = "SELECT
q.depq,q.fullname,q.dep_level,d.name er
FROM
ovst_queue_server q
left join er_regist e on e.vn = q.vn
left join er_dch_type d on d.er_dch_type = e.er_dch_type
where
 q.dep = '014' and mod(q.status,2) = 1
 AND q.date_visit = CURDATE()
 and q.stationno is not null order by q.time_visit asc limit 6";

$query = mysqli_query($objCon, $sql);
?>


    <?php
while ($result2 = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
    $level = $result2["dep_level"];
    //echo $level;
    ?>
<div class="rowsanimate">
                      <div class="rowlist" style="flex:1;text-align: center;"><?=$result2["depq"];?></div>
                      <div class="rowlist" style="flex:0.5;text-align: center;"><?php
if ($level == 0) {
        echo "<i class='far fa-circle' aria-hidden='true' style='color:green'></i>";
    } else
    if ($level == 1) {
        echo "<i class='fas fa-circle' aria-hidden='true' style='color:#35f735'></i>";
    } else
    if ($level == 2) {
        echo "<i class='fas fa-circle' aria-hidden='true' style='color:#f7cd36'></i>";
    } else
    if ($level == 3) {
        echo "<i class='fas fa-circle' aria-hidden='true' style='color:pink'></i>";
    } else
    if ($level == 4) {
        echo "<i class='fas fa-circle' aria-hidden='true' style='color:#ff2b2b'></i>";
    }

    ?></div>

                      <div class="rowlist" style="flex:2.5;text-align: left;"><?=$result2["fullname"];?></div>

                    </div>
                    <div class="rowsanimate">
                    <div class="rowlist" style="flex:4;text-align: center;"><?php
if (empty($result2["er"])) {
        echo "-";
    } else {
        echo $result2["er"];
    }
    ?></div>
</div>



        <?php
}
?>





<?php mysqli_close($objCon);?>