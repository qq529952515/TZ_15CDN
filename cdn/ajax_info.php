<?php

include_once("function.php");
include_once("../config/PDO_config.php");
include_once('../function/pub_function.php');
if (!FuncClient_IsLogin()) {
    FuncClient_LocationLogin();
}

$buy_id = $_POST['buy_id'];

$ajaxPDO = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWD);


switch ($_POST['action']) {
    case 'bandwidth':
        //获取5秒前的时间戳
        $time5Ago = strtotime('-5 seconds');
//        $sqlBW    = "
//SELECT
//domain_stat_product_bandwidth.bandwidth_down,
//domain_stat_product_bandwidth.id,
//domain_stat_product_bandwidth.bandwidth_up,
//domain_stat_product_bandwidth.time
//FROM
//domain_stat_product_bandwidth
//WHERE
//domain_stat_product_bandwidth.buy_id = '$buy_id' AND
//domain_stat_product_bandwidth.time >= '$time5Ago'
//ORDER BY
//domain_stat_product_bandwidth.time DESC
//LIMIT 1
//
//";


//        $buy_id = 175;
        $time = time();

        //获取5分钟前 时间戳
        $time5Ago = strtotime('-300 seconds');


//        var_dump($time);
//        var_dump($time5Ago);
//        $timeval1 = mktime(0,0,0,date("m"),date("d"),date("Y"))-($date2*60*60*24);
//        $timeval2 = 1511249041;
//        $timeval1 = 1511232730;

//        $timeval2 = $timeval1 + 60*60*24;


//        var_dump($timeval1);
        $sqlBW = "
SELECT
domain_stat_product_bandwidth.bandwidth_down,
domain_stat_product_bandwidth.id,
domain_stat_product_bandwidth.bandwidth_up,
domain_stat_product_bandwidth.time
FROM
domain_stat_product_bandwidth
WHERE
domain_stat_product_bandwidth.buy_id = '$buy_id' AND 
time>='$time5Ago' AND time<'$time' ORDER BY time DESC
";


        $sthBW = $ajaxPDO->query($sqlBW);

        $sth = $sthBW->fetchAll(PDO::FETCH_ASSOC);

//        var_dump($sth);
//        $msgBW = $sth[0]['bandwidth_down'] + $sth[0]['bandwidth_up'];

        foreach ($sth as $k=>$v){
            $msgBW += $v['bandwidth_down'];

        }

//        var_dump($msgBW);
        $msg['msgBW'] = $msgBW;

        echo json_encode($msg);
        break;


    //本月 日流量数据折线表
    case 'Count':
        //获取今日起始时间的时间戳
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

        $sqlSum  = "
SELECT
domain_stat_product_bandwidth.down_increase,
domain_stat_product_bandwidth.up_increase,
domain_stat_product_bandwidth.RequestCount_increase
FROM
domain_stat_product_bandwidth
WHERE
domain_stat_product_bandwidth.buy_id = '$buy_id' AND
domain_stat_product_bandwidth.time >= '$beginToday' 
";
        $sthSum  = $ajaxPDO->query($sqlSum);
        $sthSum2 = $sthSum->fetchAll(PDO::FETCH_ASSOC);

//        var_dump($sthSum2);
//        echo PubFunc_KBToString($sthSum2[0]['down_increase']);

//        echo '----------------------';
        $sum    = 0;
        $sumRes = 0;
        foreach ($sthSum2 as $k => $v) {
            $sum    = $sum + $v['down_increase'];
            $sum    = $sum + $v['up_increase'];
            $sumRes = $sumRes + $v['RequestCount_increase'];
        }
        $sumToMB          = PubFunc_MBToString($sum);
        $msg['msgSum']    = $sumToMB;
        $msg['msgSumRes'] = $sumRes;

        echo json_encode($msg);
        break;
    case 'graphData':

//        //获取本月开始时间戳
//        $beginThismonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
//        $buyID          = $_SESSION['userInfo']['buy_id'];
//
//        $statPDO = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWD);
//
//        $statSql = "
//SELECT
//domain_stat_product_day.buy_id,
//domain_stat_product_day.time,
//domain_stat_product_day.RequestCount,
//domain_stat_product_day.UploadCount,
//domain_stat_product_day.DownloadCount
//FROM
//domain_stat_product_day
//WHERE
//domain_stat_product_day.buy_id = '$buyID' AND
//domain_stat_product_day.time >= '$beginThismonth'
//ORDER BY
//domain_stat_product_day.time ASC
//";
//        $statSth = $statPDO->query($statSql);
//        $statSth = $statSth->fetchAll(PDO::FETCH_ASSOC);
//
//
//        $i = 0;
//        foreach ($statSth as $k => $v) {
//
//            $data['date'][$i] = date("m月d日", $v['time']);
//            $sum              = $v['DownloadCount'] + $v['UploadCount'];
//            $data['sum'][$i]  = $sum;
//            $i++;
//        }
//
//        echo json_encode($data);


        //获取30天前时间戳
        $begin30Ago = strtotime("-30 day");
//        echo $begin30Ago;

        $buyID = $_SESSION['userInfo']['buy_id'];

        $statPDO = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWD);

        $statSql = "
SELECT
domain_stat_product_day.buy_id,
domain_stat_product_day.time,
domain_stat_product_day.RequestCount,
domain_stat_product_day.UploadCount,
domain_stat_product_day.DownloadCount
FROM
domain_stat_product_day
WHERE
domain_stat_product_day.buy_id = '$buyID' AND
domain_stat_product_day.time >= '$begin30Ago'
ORDER BY
domain_stat_product_day.time ASC
";
        $statSth = $statPDO->query($statSql);
        $statSth = $statSth->fetchAll(PDO::FETCH_ASSOC);

        $i = 0;
        foreach ($statSth as $k => $v) {

            $data[$i][0] = $v['time'];
            $data[$i][1] = $v['DownloadCount'] + $v['UploadCount'];
            $i++;
        }

        echo json_encode($data);


//        echo "<pre>";
//        echo json_encode($data);
////        print_r($data); // or var_dump()
//        echo "</pre><br>";
//
//        echo "<pre>";
//        print_r($statSth); // or var_dump()
//        echo "</pre><br>";

        break;
    default:
        echo "";
}
















