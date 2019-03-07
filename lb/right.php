<?php
require "../db.php";

$depcode = '009';

$sql = "SELECT q.*

FROM
ovst_queue_server_dep q
WHERE
q.date_visit = CURDATE()
AND q.stationno IS NOT NULL
and q.status = '1'
and q.dep_visit = 'lab'
ORDER BY
 q.time_visit desc
";

$query2 = mysqli_query($objCon, $sql);

while ($result2 = mysqli_fetch_array($query2, MYSQLI_ASSOC)) {

    ?>

<div class="rowsanimate">
    <div class="rowlist" style="flex:1;text-align: center;"><?=$result2["depq"];?></div>
    <div class="rowlist" style="flex:1;text-align: left;"><?=$result2["fullname"];?></div>
    <div class="rowlist" style="flex:1;text-align: center;"><?=$result2["stationno"];?></div>
</div>

      <?php

}
?>

