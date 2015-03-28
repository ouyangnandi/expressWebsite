<?php

require('../../global/init.php');

$action = $_GET['action'];

if ($action == 1) {
    getCard();
} else if ($action == 2) {
    addCard();
} else if ($action == 3) {
    delCard();
} else if ($action == 4) {
    checkCard();
} else if ($action == 5) {
    bindUser();
} else if ($action == 6) {
    transferCardType();
} else if ($action == 7) {
    getCardTypeInfo();
}

function getCard() {
    $cardStatus = array();
    $cardStatus[0] = "未激活";
    $cardStatus[1] = "已激活";
    $cardStatus[2] = "作废";

    $cardTypeMap = getCardTypeName();
    $con = DatabaseConn::getConn();
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $searchCard = isset($_POST['searchCard']) ? ($_POST['searchCard']) : '';
    $offset = ($page - 1) * $rows;
    $result = array();
    $where = "tb_card.card_num like '%$searchCard%' or CAST(user_id as CHAR) like '%$searchCard%'";

    mysqli_query($con, "set names 'utf8'");
    $rs = mysqli_query($con, "select count(*) from tb_card where " . $where);
    $row = mysqli_fetch_row($rs);
    $result["total"] = $row[0];
    $rs = mysqli_query($con, "select tb_card.*,tb_user.credits,tb_user.username from tb_card left outer join tb_user on tb_card.user_id = tb_user.id where " . $where . " order by tb_card.id desc limit $offset,$rows");
    $items = array();
    while ($row = mysqli_fetch_object($rs)) {
       $row->card_type = calculateCardType($row->credits,$cardTypeMap);
        $row->status = ltrim($row->status, '0');
        if ($row->status == "") {
            $row->status = "0";
        }

        $row->status = $cardStatus[$row->status];
        array_push($items, $row);
    }
    $result["rows"] = $items;

    echo json_encode($result);
}

function calculateCardType($credits,$map) {
    foreach ($map as $key => $value) { 
       if($value <= $credits ) {
           return $key;
       }
    }
    return "无类型";
}

function getCardTypeName() {
    $cardTypeMap = array();
    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    $rs = mysqli_query($con, "select * from tb_card_type order by credit_benchmark desc");
    while ($row = mysqli_fetch_object($rs)) {
        $cardTypeMap[$row->name] = $row->credit_benchmark;
    }
    return $cardTypeMap;
}

function checkCard() {
    $userName = $_REQUEST['card_user_name'];
    $cardNum = $_REQUEST['card_num'];

    if (!ctype_digit($cardNum)) {
        echo json_encode(array('msg' => '卡号必须是整数'));
        return;
    }

    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");

    $sql = "select * from tb_card where card_num = '" . $cardNum . "'";
    $rs = mysqli_query($con, $sql);

    if ($row = mysqli_fetch_object($rs)) {
        echo json_encode(array('msg' => '此卡号已经存在'));
        return;
    }
    if (!IsNullOrEmptyString($userName)) {

        $sql = "select id from tb_user where username='" . $userName . "' and status <> 3";
        $rs = mysqli_query($con, $sql);
        if ($row = mysqli_fetch_object($rs)) {
            $userId = $row->id;
        } else {
            echo json_encode(array('msg' => '用户不存在'));
            return;
        }

        $sql = "select 1 from tb_card where user_id = " . $userId . " and status = 1";
        $rs = mysqli_query($con, $sql);
        $row_cnt = mysqli_num_rows($rs);
        if ($row_cnt > 0) {
            echo json_encode(array('msg' => "1")); //废除旧卡
            return;
        }

        $sql = "select 1 from tb_user where user_id = " . $userId;
        $rs = mysqli_query($con, $sql);
        $row_cnt = mysqli_num_rows($rs);
        if ($row_cnt > 0) {
            echo json_encode(array('msg' => "3")); //绑定已经存在用户
            return;
        }
    }

    echo json_encode(array('success' => true));
}

function addCard() {
    session_start();
    $admin = $_SESSION['username'];

    $cardNum = $_REQUEST['card_num'];
    $userName = $_REQUEST['card_user_name'];
    $type = $_REQUEST['type'];


    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    if (!IsNullOrEmptyString($userName)) {
        $sql = "select id from tb_user where username='" . $userName . "' and status <> 3";
        $rs = mysqli_query($con, $sql);
        if ($row = mysqli_fetch_object($rs)) {
            $userId = $row->id;
        } else {
            echo json_encode(array('msg' => '用户不存在'));
            return;
        }
    }

    if ($type == "0") { //创建新卡
        $sql = "insert into tb_card  (card_num,created_admin,created_date,status) values('$cardNum','$admin',now(),0)";
    } else if ($type == "1") { //创建新卡，同时废除另外张卡
        $sql = "update tb_card set status = 2 where user_id = $userId and status = 1";
        $result = mysqli_query($con, $sql); //废除旧卡

        $sql = "update tb_user set card_num = $cardNum where id=$userId";
        $result = mysqli_query($con, $sql); // 更新用户卡号
        // 新建卡
        $sql = "insert into tb_card  (card_num,user_id,active_date,active_admin,created_admin,created_date,status) values('$cardNum',$userId,now(),'$admin','$admin',now(),1)";
    } else if ($type == "2") { //创建新卡，同时创建新用户
        //创建新用户
        $sql = "insert into tb_user (card_num,status,created_date,active_admin) values('$cardNum',1,now(),'$admin')";
        $result = mysqli_query($con, $sql);

        //添加新卡
        $lastUserId = mysqli_insert_id($con);
        $sql = "insert into tb_card  (card_num,user_id,active_date,active_admin,created_admin,created_date,status) values('$cardNum',$lastUserId,now(),'$admin','$admin',now(),1)";
    } else if ($type == "3") {  //创建新卡，同时绑定用户
        $sql = "update tb_user set card_num = $cardNum where id=$userId";
        $result = mysqli_query($con, $sql); // 更新用户卡号
        //新建卡
        $sql = "insert into tb_card  (card_num,user_id,active_date,active_admin,created_admin,created_date,status) values('$cardNum',$userId,now(),'$admin','$admin',now(),1)";
    }

    $result = mysqli_query($con, $sql);
    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('msg' => '数据库错误'));
    }
}

function bindUser() {
    session_start();

    $admin = $_SESSION['username'];
    $cardId = $_REQUEST['bindCardId'];
    $cardNum = $_REQUEST['bindCardNum'];
    $userName = $_REQUEST['bindUserName'];

    if (IsNullOrEmptyString($userName)) {
        echo json_encode(array('msg' => '用户账号不能为空'));
        return;
    }

    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");

    $sql = "select id from tb_user where username='" . $userName . "' and status <> 3";
    $rs = mysqli_query($con, $sql);
    if ($row = mysqli_fetch_object($rs)) {
        $userId = $row->id;
    } else {
        echo json_encode(array('msg' => '用户不存在'));
        return;
    }

    $sql = "select * from tb_card where user_id = " . $userId . " and status = 1";
    $rs = mysqli_query($con, $sql);
    if ($row = mysqli_fetch_object($rs)) {

        if ($row->id == $cardId) {
            echo json_encode(array('msg' => '这个用户已经绑定了这张卡'));
            return;
        } else {
            echo json_encode(array('msg' => $row->card_type_id));
            return;
        }
    }

    $sql = "update tb_card  set user_id=$userId,status=1 ,active_date=now(),active_admin='$admin' where id=$cardId";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        echo json_encode(array('msg' => '数据库错误'));
        return;
    }

    //更新用户卡号
    $sql = "update tb_user set card_num='$cardNum',status=1 "
            . " where id=$userId";
    $result = mysqli_query($con, $sql); // 更新用户卡号
    if ($result) {
        echo json_encode(array('success' => true));
        return;
    } else {
        echo json_encode(array('msg' => '数据库错误'));
        return;
    }
}

function transferCardType() {
    session_start();
    $admin = $_SESSION['username'];
    $cardId = $_REQUEST['bindCardId'];
    $cardNum = $_REQUEST['bindCardNum'];
    $userName = $_REQUEST['bindUserName'];

    $con = DatabaseConn::getConn();

    mysqli_query($con, "set names 'utf8'");

    $sql = "select id from tb_user where username='" . $userName . "' and status <> 3";
    $rs = mysqli_query($con, $sql);
    if ($row = mysqli_fetch_object($rs)) {
        $userId = $row->id;
    } else {
        echo json_encode(array('msg' => '数据库错误'));
        return;
    }

    $sql = "update tb_card set status = 2 where user_id = $userId and status = 1";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        echo json_encode(array('msg' => '数据库错误'));
        return;
    }

    $sql = "update tb_card set status = 1 , user_id = $userId,active_date = now(),active_admin='$admin' where id = $cardId";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        echo json_encode(array('msg' => '数据库错误'));
        return;
    }

    //更新用户卡号
    $sql = "update tb_user set card_num='$cardNum',status=1 "
            . " where id=$userId";
    $result = mysqli_query($con, $sql); // 更新用户卡号
    if ($result) {
        echo json_encode(array('success' => true));
        return;
    } else {
        echo json_encode(array('msg' => '数据库错误'));
        return;
    }
}

function delCard() {

    $id = intval($_REQUEST['card_id']);
    $sql = "delete from tb_card where id=$id";
    $con = DatabaseConn::getConn();
    $result = mysqli_query($con, $sql);
    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('msg' => '数据库错误'));
    }
}

function getCardTypeInfo() {
    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    $rs = mysqli_query($con, "select id,name from tb_card_type ");
    $items = array();
    while ($row = mysqli_fetch_object($rs)) {
        array_push($items, $row);
    }
    echo json_encode($items);
}

?>
