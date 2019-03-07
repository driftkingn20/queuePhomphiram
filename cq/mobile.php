<?php

require "../db.php";
$s_getname = "SELECT hospitalname as hname FROM opdconfig;";
$q_getname = mysqli_query($objCon, $s_getname);
$r_getname = mysqli_fetch_array($q_getname, MYSQLI_ASSOC);

$vn = '';

if (isset($_GET["vn"])) {
    $vn = $_GET["vn"];
}

function DateThai($strDate)
{
    if (empty($strDate)) {
        return "";
    } else {
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strHour = date("H", strtotime($strDate));
        $strMinute = date("i", strtotime($strDate));
        $strSeconds = date("s", strtotime($strDate));
        $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        $strMonthThai = $strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear เวลา $strHour:$strMinute น.";
    }

}
function convertToHoursMins($sumtime)
{
    if ($sumtime < 1) {
        return "<h2 class='text-center'>-</h2>";
    }
    $hours = floor($sumtime / 60);
    $minutes = ($sumtime % 60);
    $mod = fmod($sumtime, 60);

    if ($hours >= 1 && $mod == 0) {
        return "<h2 class='text-center'>" . $hours . " ช.ม.</h2>";
    }
    if ($hours >= 1) {
        return "<h2 class='text-center'> " . $hours . " ช.ม. " . $minutes . " น.</h2>";
    } else {
        return "<h2 class='text-center'>" . $minutes . " นาที</h2>";
    }

    return "<h2 class='text-center'>-</h2>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta http-equiv="refresh" content="30" />
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?=$r_getname['hname'];?></title>

    <link rel="stylesheet" href="../dist/bootstrap3/bootstrap.min.css">
    <link rel="stylesheet" href="mobilestyle.css">

</head>

<body style="font-family: 'THSarabunNew', sans-serif;">

    <nav class="navbar navbar-inverse navbar-fixed-top" style="background-color:#00695C;">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#" style="color:white;"><?=$r_getname['hname'];?></a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="../../booking/">ระบบจองคิวออนไลน์</a></li>

                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </nav>
    <?php
$sql = "SELECT q.vn,q.hn,v.vstdate,q.time_visit,q.depq,q.status,mod(q.status,2) in_dep,q.stationno,q.fullname, qd.dep_visit,qd.time_visit time_visit_dep,qd.time_finish
,(SELECT
	count(s.vn)
FROM
	ovst_queue_server s
WHERE
	s.dep = q.dep
	AND s.date_visit = CURDATE( )
	AND s.stationno IS NULL
	AND s.time_visit > q.time_visit
	AND MOD ( s.STATUS, 2 ) = 1
) qtotal,q.wait_dep as wait,

if(position('N' in group_concat(l.confirm_report))>0,0,if(position('Y' in group_concat(l.confirm_report))>0,1,null)) lab,
if(position('N' in group_concat(x.confirm_all))>0,0,if(position('Y' in group_concat(x.confirm_all))>0,1,null))  xray,

v.cur_dep,k.department,i.image
FROM  ovst_queue_server q
inner join ovst v on q.vn = v.vn
left join ovst_queue_server_dep qd on q.vn=qd.vn
left join lab_head l on q.vn = l.vn
left join xray_head x on q.vn = x.vn
left join kskdepartment k on k.depcode=v.cur_dep
left join patient_image i on i.hn = q.hn
where q.vn = '$vn' and v.vstdate = CURDATE() and q.dep <> '999'
group by q.vn";
$query = mysqli_query($objCon, $sql);
$result = mysqli_fetch_array($query, MYSQLI_ASSOC);
$hn = $result['hn'];

$db_2 = array(
    "host" => "192.168.0.254:3306", // กำหนด host
    "user" => "sa", // กำหนดชื่อ user
    "pass" => "+mysqlplkdbpxsoh@+", // กำหนดรหัสผ่าน
    "dbname" => "hosimage", // กำหนดชื่อฐานข้อมูล
    "charset" => "utf8", // กำหนด charset
);
$conn = @new mysqli($db_2["host"], $db_2["user"], $db_2["pass"], $db_2["dbname"]);
$s = "SELECT image from patient_image where hn = '$hn'";
$res = mysqli_query($conn, $s);
$row = mysqli_fetch_array($res, MYSQLI_ASSOC);
?>
    <div class="container">

        <div class="starter-template">
            <div class="contenido">
                <div class="ticket">
                    <div class="hqr">
                        <div class="column left-one"></div>
                        <div class="column center">
                            <?php
if (empty($row['image'])) {
    echo '<img src="../dist/img/avatar.png" class="img-thumbnail center">';
} else {
    echo '<img class="img-thumbnail center" image" src="data:image/jpeg;base64,' . base64_encode($row['image']) . '"/>';
}?>
                        </div>
                        <div class="column right-one"></div>
                    </div>
                </div>
                <div class="details">

                    <div class="tdata name">
                        <h2 class="text-center">HN :
                            <?php if ($result) {
    echo $result['hn'];
} else {
    echo "ไม่พบข้อมูลบริการ/กลับบ้านแล้ว";
}

?>
                        </h2>
                    </div>

                    <div class="tdata">
                        <h1 class="text-center">
                            <?=$result['fullname']?>
                        </h1>
                    </div>

                    <div class="tdata">
                        <h2 class="text-center">จุดรับบริการ :
                            <?=$result['department']?>
                        </h2>
                    </div>

                    <div class="masinfo">
                        <div class="left">
                            <div class="tinfo">
                                <span class="label label-primary">วันเวลาเริ่มรับบริการ</span>

                            </div>
                            <div class="tdata nesp">
                                <h3 class="text-center">
                                    <?=DateThai($result['time_visit']);?>
                                </h3>
                            </div>
                        </div>
                        <div class="right">
                            <div class="tinfo">
                                <span class="label label-warning">หมายเลข</span>
                            </div>
                            <div class="tdata nesp">
                                <h2 class="text-center" style="font-weight: bolder;">
                                    <?=$result['depq']?>
                                </h2>
                            </div>
                        </div>
                    </div>



                    <div class="details">


                        <div class="tdata name">
                            <div class="alert alert-info" role="alert">
                                <?php
$depcode = $result['cur_dep'];
$sumtime = $result["wait"]; //ดึงมาจากตาราง
$called = $result["qtotal"]; //จำนวนคิวที่รอ

if ($result['in_dep'] == '1' && $result['stationno'] == '99') {
    echo "<h2 class='text-center' style='color:red;'>รับบริการเรียบร้อยแล้ว</h2>";
} else

if ($result['in_dep'] == '1' && $result['stationno'] == '') {
    echo "<h2 class='text-center' style='color:green;font-weight:bold'>รอรับบริการ</h2>";
    echo "<h2 class='text-center'>รออีก : " . $called . "คิว</h2>";
    echo convertToHoursMins($sumtime);

} else
if ($result['in_dep'] == '1' && !empty($result['stationno']) && $result['stationno'] != '99') {
    echo "<h2 class='text-center' style='color:green;font-weight:bold'>รอรับบริการ</h2>";
    echo "<h2 class='text-center'>รออีก : " . $called . "คิว</h2>";
    echo convertToHoursMins($sumtime);
} else

if ($result['in_dep'] == '0' && $result['stationno'] == '') {
    echo "<h2 class='text-center' style='color:green;font-weight:bold'>รอรับบริการ</h2>";
    echo "<h2 class='text-center'>รออีก : " . $called . "คิว</h2>";
    echo convertToHoursMins($sumtime);
} else
if ($result['in_dep'] == '0' && !empty($result['stationno'])) {
    echo "<h2 class='text-center' style='color:green;font-weight:bold'>รอรับบริการ</h2>";
    echo "<h2 class='text-center'>รออีก : " . $called . "คิว</h2>";
    echo convertToHoursMins($sumtime);
}

?>
                            </div><!--  alert -->
                            <div class="tdata name">
                                <div class="well">
                                    <h3>สถานะแล็ป</h3>
                                    <?php

if ($result['lab'] == '0') {
    echo "<div  style='padding: 13px 10px;background-color: #f47a41; color:#fff;width: 100%;font-size: 20px'>รอผล</div>";
} else

if ($result['lab'] == '1') {
    echo "<div  style='padding: 13px 10px;background-color: green; color:#fff;width: 100%;font-size: 20px'>เสร็จแล้ว</div>";
} else {
    echo "<div  style='padding: 13px 10px;background-color: green; color:#fff;width: 100%;font-size: 20px'>ไม่มีแล็ป</div>";
}

?>

                                    <h3>สถานะเอ็กซ์เรย์</h3>
                                    <?php

if ($result['xray'] == '0') {
    echo "<div  style='padding: 13px 10px;background-color: #f47a41; color:#fff;width: 100%;font-size: 20px'>รอผล</div>";
} else

if ($result['xray'] == '1') {
    echo "<div  style='padding: 13px 10px;background-color: green; color:#fff;width: 100%;font-size: 20px'>เสร็จแล้ว</div>";
} else {
    echo "<div  style='padding: 13px 10px;background-color: green; color:#fff;width: 100%;font-size: 20px'>ไม่มีเอ็กซ์เรย์</div>";
}

?>
<h3>สถานะรับยา</h3>
                                    <?php
$rxSql = "select * from ovst_queue_server_dep where dep_visit = 'drug' and vn = $vn";
$rxQuery = mysqli_query($objCon, $rxSql);
$rxResult = mysqli_fetch_array($rxQuery, MYSQLI_ASSOC);

if ($rxResult['status'] == '2') {
    echo "<div  style='padding: 13px 10px;background-color: red; color:#fff;width: 100%;font-size: 20px'>เรียกรับยาแล้ว</div>";
} else {
    echo "<div  style='padding: 13px 10px;background-color: green; color:#fff;width: 100%;font-size: 20px'>รอเรียก/ไม่มียา</div>";
}
?>
                                </div>
                            </div>
                        </div>
                        <div class="link">
                        <button type="button"  class="btn btn-danger  btn-lg" onclick="location.reload(true);">อัพเดทข้อมูล</button>
                    </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../dist/jquery.min.js"></script>
    <script src="../dist/bootstrap3/bootstrap.min.js"></script>

</body>

</html>