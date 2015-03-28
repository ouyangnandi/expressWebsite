

<?php

require('../../global/init.php');


$orderNum = $_REQUEST["orderNum"];


if (!isLongInteger($orderNum)) {
    echo json_encode(array('status' => 3, 'msg' => '订单号格式不对'));
    return;
}
$con = DatabaseConn::getConn();
mysqli_query($con, "set names 'utf8'");

$sql = "select status,batch_id,order_route_info,order_route_date,order_route_area from tb_akt_order where order_num=" . $orderNum;
$rs = mysqli_query($con, $sql);
if ($row = mysqli_fetch_object($rs)) {
    if ($row->status == 0) {
        //获得批次路线
        $array = getBatchRoute($row->batch_id);

        if (isset($row->order_route_info) && $row->order_route_info <> "") {
            $context = $row->order_route_info;
            $date = date("Y-m-d", strtotime($row->order_route_date));
            $dateTime = date("Y-m-d H:i:s", strtotime($row->order_route_date));
            $tempTime = date("H:i:s", strtotime($row->order_route_date));
            $city = $row->order_route_area;

            if (array_key_exists($date, $array)) {
                $array[$date]->addItem($dateTime, $tempTime, $context, $city);
                $array[$date]->sort();
            } else {

                $route = new Route($date);
                $route->addItem($dateTime, $tempTime, $context, $city);
                $array[$date] = $route;
            }
        }
        echo urldecode(json_encode(array('status' => 0, 'msg' => $array)));
        return;
    } else if ($row->status == "1") {
        $data = getRouteInfo($orderNum);
        if ($data == 101) {
            echo urldecode(json_encode(array('status' => 2, 'msg' => " 网络不稳定导致查询错误，请您重新查询几次，如果问题仍然出现，请联系本公司")));
        } else if ($data == 103) {
            echo urldecode(json_encode(array('status' => 2, 'msg' => "中国快递单号格式错误，请联系本公司")));
        } else if ($data == 104) {
               echo urldecode(json_encode(array('status' => 2, 'msg' => "中国快递公司错误，请联系本公司")));
        } else {
            $orderExtraInfo = getChinaOrderCompany($orderNum);
            echo urldecode(json_encode(array('status' => 0, 'msg' => $data,'newOrderCompany'=>$orderExtraInfo[0],'newOrderNumber'=>$orderExtraInfo[1])));
        }
        return;
    } else {
        echo json_encode(array('status' => 1, 'msg' => '订单号不存在'));
        return;
    }
} else {
    echo json_encode(array('status' => 1, 'msg' => '订单号不存在'));
    return;
}

function getChinaOrderCompany($orderNum) {
    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    $sql = "SELECT name,new_order_num"
            . " FROM tb_akt_order "
            . "INNER JOIN tb_new_order ON tb_akt_order.id=tb_new_order.akt_order_id "
            . "INNER JOIN tb_china_firm on tb_new_order.new_order_company = tb_china_firm.code"
            . " WHERE order_num=" . $orderNum;
    $rs = mysqli_query($con, $sql);
    $row = mysqli_fetch_row($rs);
    $orderInfo = array();
   $orderInfo[0] =  $row[0];
     $orderInfo[1] =  $row[1];
   
    return $orderInfo;
}

function getRouteInfo($orderNum) {
    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    $sql = "SELECT batch_id,status,new_order_num,new_order_company,order_route_info,order_route_date,order_route_area "
            . "FROM tb_akt_order INNER JOIN tb_new_order ON tb_akt_order.id=tb_new_order.akt_order_id WHERE order_num=" . $orderNum;

    $rs = mysqli_query($con, $sql);
    if ($row = mysqli_fetch_object($rs)) {

//                if ($row->new_order_company == "shunfeng") {
//                    $array1 = queryRouteInfoSF($row->new_order_num);
//                } else
        if ($row->new_order_company == "ems" || $row->new_order_company == "shunfeng") {
            $array1 = queryRouteInfoAicha($row->new_order_num, $row->new_order_company);
        } else {
            $array1 = queryRouteInfo100($row->new_order_num, $row->new_order_company);
        }

        if (!is_array($array1)) {
            if ($array1 == "102") {
                $array1 = array();
            } else {
                return intval($array1);
            }
        }

        $array2 = getBatchRoute($row->batch_id);
        $array = array_merge($array1, $array2);

        if (isset($row->order_route_info) && $row->order_route_info <> "") {
            $context = $row->order_route_info;
            $date = date("Y-m-d", strtotime($row->order_route_date));
            $dateTime = date("Y-m-d H:i:s", strtotime($row->order_route_date));
            $tempTime = date("H:i:s", strtotime($row->order_route_date));
            $city = $row->order_route_area;

            if (array_key_exists($date, $array)) {
                $array[$date]->addItem($dateTime, $tempTime, $context, $city);
                $array[$date]->sort();
            } else {
                $route = new Route($date);
                $route->addItem($dateTime, $tempTime, $context, $city);
                $array[$date] = $route;
            }
        }

        ksort($array);
        return $array;
    } else {
        return 101;
    }
}

function isLongInteger($val) {
    $regex = "/^[0-9]+$/i";
    $matches = array();
    if (preg_match($regex, $val, $matches)) {
        return true;
    }
    return false;
}

function getBatchRoute($batchId) {
    $con = DatabaseConn::getConn();
    $sql = "select updated_date,description,area from tb_route where batch_id = " . $batchId;
    mysqli_query($con, "set names 'utf8'");
    $rs = mysqli_query($con, $sql);
    $routes = array();

    while ($row = mysqli_fetch_object($rs)) {

        $context = $row->description;
        $date = date("Y-m-d", strtotime($row->updated_date));
        $dateTime = date("Y-m-d H:i:s", strtotime($row->updated_date));
        $tempTime = date("H:i:s", strtotime($row->updated_date));
        $city = $row->area;

        if (array_key_exists($date, $routes)) {
            $routes[$date]->addItem($dateTime, $tempTime, $context, $city);
            $routes[$date]->sort();
        } else {
            $route = new Route($date);
            $route->addItem($dateTime, $tempTime, $context, $city);
            $routes[$date] = $route;
        }
    }
    return $routes;
}

function queryRouteInfo100($newOrderId, $newOrderCompany) {
    $typeCom = $newOrderCompany;
    $typeNu = $newOrderId;

    $AppKey = 'fc4d6bb877001327'; //请将XXXXXX替换成您在http://kuaidi100.com/app/reg.html申请到的KEY
    $url = 'http://api.kuaidi100.com/api?id=' . $AppKey . '&com=' . $typeCom . '&nu=' . $typeNu . '&show=0&muti=1&order=asc';

    //优先使用curl模式发送数据
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    $get_content = curl_exec($curl);

    $errmsg = curl_error($curl);
    if ($errmsg) {
        return "101";
    }

    curl_close($curl);

    //将JSON格式数据进行解码，解码后不是JSON数据格式，不可用echo直接输出 
    $arr = json_decode($get_content, true);

    if ($arr["status"] == "1") {
        
    } else if ($arr["status"] == "0") {
        return "102";
    } else {
        return "101";
    }
    $routes = array();

    foreach ($arr["data"] as $key => $value) {

        $context = $value["context"];
        $date = date("Y-m-d", strtotime($value["time"]));
        $dateTime = date("Y-m-d H:i:s", strtotime($value["time"]));
        $tempTime = date("H:i:s", strtotime($value["time"]));
        $city = getCity($context);

        if (array_key_exists($date, $routes)) {
            $routes[$date]->addItem($dateTime, $tempTime, $context, $city);
            $routes[$date]->sort();
        } else {
            $route = new Route($date);
            $route->addItem($dateTime, $tempTime, $context, $city);
            $routes[$date] = $route;
        }
    }

    return $routes;
}

function queryRouteInfoSF($newOrderId) {

    $url = 'http://syt.sf-express.com/css/newmobile/queryBillInfo.action?delivery_id=' . $newOrderId;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    $get_content = curl_exec($curl);

    $errmsg = curl_error($curl);
    if ($errmsg) {
        return "101";
    }

    curl_close($curl);

    //将JSON格式数据进行解码，解码后不是JSON数据格式，不可用echo直接输出 
    $arr = json_decode($get_content, true);

    if ($arr["message"] <> "success") {
        return "101";
    }

    $routes = array();

    foreach ($arr["result"]["router"] as $key => $value) {

        $context = $value["statue_message"];
        $date = date("Y-m-d", strtotime($value["time"]));
        $dateTime = date("Y-m-d H:i:s", strtotime($value["time"]));
        $tempTime = date("H:i:s", strtotime($value["time"]));
        $city = $value["address"];

        if (array_key_exists($date, $routes)) {
            $routes[$date]->addItem($dateTime, $tempTime, $context, $city);
            $routes[$date]->sort();
        } else {
            $route = new Route($date);
            $route->addItem($dateTime, $tempTime, $context, $city);
            $routes[$date] = $route;
        }
    }

    return $routes;
}

function queryRouteInfoAicha($newOrderId, $company) {

    $id = 'f717c785310b8a2cd20d6dd4970c6201'; //API KEY
    $com = $company; //快递公司
    $nu = $newOrderId; //快递单号
    $type = 'json';
    $encode = 'utf8';

    $url = "http://api.ickd.cn/?id=104951&secret=f717c785310b8a2cd20d6dd4970c6201&com=" . $com . "&nu=" . $nu . "&type=json&encode=utf8&ver=2";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    $get_content = curl_exec($curl);

    $errmsg = curl_error($curl);
    if ($errmsg) {
        return "101";
    }
    curl_close($curl);

    //将JSON格式数据进行解码，解码后不是JSON数据格式，不可用echo直接输出 
    $arr = json_decode($get_content, true);

    if ($arr["errCode"] == "0") {
        
    } else if ($arr["errCode"] == "1") {
        return "102"; //快递单号不存在
    } else if ($arr["errCode"] == "6") {
        return "103"; //快递单号格式错误
    } else if ($arr["errCode"] == "7") {
        return "104"; //快递公司错误  
    } else {
        return "101"; //未知错误
    }
    $routes = array();

    foreach ($arr["data"] as $key => $value) {

        $context = $value["context"];
        $date = date("Y-m-d", strtotime($value["time"]));
        $dateTime = date("Y-m-d H:i:s", strtotime($value["time"]));
        $tempTime = date("H:i:s", strtotime($value["time"]));
        $city = getCity($context);

        if (array_key_exists($date, $routes)) {
            $routes[$date]->addItem($dateTime, $tempTime, $context, $city);
            $routes[$date]->sort();
        } else {
            $route = new Route($date);
            $route->addItem($dateTime, $tempTime, $context, $city);
            $routes[$date] = $route;
        }
    }

    return $routes;
}

function getCity($str) {

    $end = strpos($str, "市", 0) + 3;
    $result = mb_strcut($str, 0, $end, 'utf-8');
    $result = str_replace("(", "", $result);
    $result = str_replace("（", "", $result);
    return $result;
}

class Route {

    public $date;
    public $items;

    function Route($date) {
        $this->date = $date;
        $this->items = array();
    }

    public function addItem($dateTime, $time, $info, $city) {
        $item = new Item($dateTime, $time, $info, $city);
        $this->items[$dateTime] = $item;
    }

    public function sort() {
        ksort($this->items);
    }

}

class Item {

    public $dateTime;
    public $info;
    public $city;
    public $time;

    function Item($d, $t, $i, $c) {
        $this->dateTime = $d;
        $this->time = $t;
        $this->info = urlencode($i);
        $this->city = urlencode($c);
    }

}
?>
