<?php require "../connect.php";
//$vn = '611206145331';
$vn = null;

if (isset($_GET["vn"])) {
    $vn = $_GET["vn"];
}
if (isset($_POST['barcode'])) {
    $vn = $_POST['barcode'];
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
    <meta http-equiv="refresh" content="300" />
    <meta name="author" content="">
    <link rel="icon" href="../favicon.ico">

    <title>Smart Queue</title>

    <!-- Bootstrap core CSS -->
    <link href="../dist/bootstrap3/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../dist/FontAwesome/css/all.css">
    <link rel="stylesheet" href="style.css">
    <!-- <link href="https://fonts.googleapis.com/css?family=Niramit" rel="stylesheet"> -->
    <!-- Custom styles for this template -->



</head>

<body>

    <div class="container register">
        <!-- <div id="title">
            <h1>กรุณานำใบสลิปคิวสแกนด้านหน้าเครื่อง</h1>
        </div> -->
        <div class="row">
            <div class="col-md-3 register-left">

                <img src="../dist/img/logoMOPH.png" class="center" width="50%" style="padding-bottom:15px" />

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
where q.vn = '$vn' and v.vstdate = CURDATE()
group by q.vn";
$query = mysqli_query($objCon, $sql);
$result = mysqli_fetch_array($query, MYSQLI_ASSOC);

?>

            </div>
            <div class="col-md-9 register-right" id="toggleText" style="display: block">
                <div class="row">
                    <div class="row">
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-4">
                            <h2 class="text-center">HN :
                                <?=$result['hn']?>
                            </h2>
                            <div><?php
if (empty($result['image'])) {
    echo '<img src="../dist/img/avatar.png" class="img-thumbnail center">';
} else {
    echo '<img class="img-thumbnail center" image" src="data:image/jpeg;base64,' . base64_encode($result['image']) . '"/>';
}?></div>

                        </div>

                    </div>
                    <div class="row">
                        <h1 class="text-center">
                            <?=$result['fullname']?>
                        </h1>
                        <div>
                            <h2 class="text-center">จุดรับบริการ :
                                <?=$result['department']?>
                            </h2>
                        </div>
                        <?php
function DateThai($strDate)
{
    if (empty($strDate)){
        return "";
    }else {
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
?>
                        <h4 class="text-center">วันเวลาเริ่มรับบริการ :
                            <?=DateThai($result['time_visit']);?>
                        </h4>
                    </div>

                </div>
                <div class="row" style="margin-top: -10%;">
                    <div class="col-md-12 register-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="well">

                                    <h1 class="text-center" style="font-weight:bold">เลขคิว :
                                        <?=$result['depq']?>
                                    </h1>
                                    <?php

$depcode = $result['cur_dep'];

function convertToHoursMins($sumtime)
{
    if ($sumtime < 1) {
        return "<h2 class='text-center'>-</h2>";
    }
    $hours = floor($sumtime / 60);
    $minutes = ($sumtime % 60);
    $mod = fmod($sumtime, 60);

    if ($hours >= 1 && $mod == 0) {
        return "<h2 class='text-center'>".$hours . " ช.ม.</h2>";
    }
    if ($hours >= 1) {
        return "<h2 class='text-center'> " . $hours . " ช.ม. " . $minutes . " น.</h2>";
    } else {
        return "<h2 class='text-center'>".$minutes . " นาที</h2>";
    }

    return "<h2 class='text-center'>-</h2>";
}
//---------------------------------------------------------------------------------------------------
// $sqlminute = "SELECT time_wait,time_start,dep_station from kskdepartment where depcode = '$depcode'";
// $queryminute = mysqli_query($objCon, $sqlminute);
// $resultminute = mysqli_fetch_array($queryminute, MYSQLI_ASSOC);

// $time = $resultminute["time_wait"]; //เวลาต่อคนจาก ksk
// $workstart = $resultminute["time_start"]; //เวลาเริ่มทำงาน
// date_default_timezone_set("Asia/Bangkok"); //แปลงเวลาเป็น +7
// $now = date("H:i:s"); //เวลาปัจจุบัน
// $dep = $resultminute["dep_station"];  //จำนวนช่องบริการ

// $dateDiff = intval((strtotime($workstart) - strtotime($now)) / 60); //เวลาปัจจุบัน-เริ่มทำงาน

// if ($dateDiff < 0) {
//     $dateDiff = 0;
// } //ถ้าติดลบให้เท่ากับ 0

// 
// $counter2 = $waitTable / $dep; //
// $sumtime = ((round($counter2) + $called)) * $time + $dateDiff;

//---------------------------------------------------------------------------------------------------
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
$rxSql = "select * from ovst_queue_server_dep where dep_visit = 'drug' and vn = $vn";
$rxQuery = mysqli_query($objCon, $rxSql);
$rxResult = mysqli_fetch_array($rxQuery, MYSQLI_ASSOC);
if ($rxResult['status'] == '2'){
    echo "<h2 class='text-center' style='color:red;'>เรียกรับยาแล้ว</h2>";
}else {
    echo "";
}
?>


                                </div>

                            </div>
                            <div class="col-md-6">
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
                                </div>
                            </div>

                        </div>


                    </div>

                </div>

            </div>

        </div>

        <footer class="footer">

            <div class="fixed-bottom" style="vertical-align: bottom;">
                <form method="POST">
                    <div class="input-group">

                        <input class="form-control" id='barcode' name="barcode" type="text" placeholder="" autocomplete="off">
                        <span class="input-group-addon" id="basic-addon1"><a id="displayText" href="javascript:toggle();"><i
                                    class="fa fa-barcode"></i></a> </span>
                    </div>
                </form>

            </div>

        </footer>

        <!--container-->


        <!-- Bootstrap core JavaScript
    ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="../dist/jquery.min.js"></script>
        <script src="../dist/bootstrap3/bootstrap.min.js"></script>
        <script>
            $(function () {
                //barcode prompt
                $('#barcode').focus();

            });


        </script>
        <?php
//$barcode = $_POST['barcode'];
//echo $barcode;


?>
</body>

</html>