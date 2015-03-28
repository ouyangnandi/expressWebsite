<?php

require('../../global/init.php');

$action = $_GET['action'];


if ($action == 1) {
    getBasicInfo();
} else if ($action == 2) {
    search();
}

function getBasicInfo() {
    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    session_start();
    $username = $_SESSION['username'];
    $sql = "select id, email,card_num,credits,created_date from tb_user where username = '$username' and status <> 3";

    $rs = mysqli_query($con, $sql);
    if ($row = mysqli_fetch_object($rs)) {

        $email = $row->email;
        $card_num = $row->card_num;
        $credits = $row->credits;
        $created_date = $row->created_date;
        $userId = $row->id;
        
        $items = array();
        $sql = "select id,order_num,created_date from tb_akt_order where user_id  = " . $userId;
        $rs = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_object($rs)) {
            array_push($items, $row);
        }
        echo json_encode(array('status' => 1, 'username' => $username, 'email' => $email, 'card_num' =>  $card_num, 'credits' =>  $credits, 'created_date' => $created_date,"info" => $items));
        return;
    } else {
        echo json_encode(array('status' => '2'));
        return;
    }
}

function search() {
    session_start();
    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    $username = $_SESSION['username'];

    $searchOrder = isset($_POST['searchOrder']) ? ($_POST['searchOrder']) : '';
    $searchTimeFrom = isset($_POST['searchTimeFrom']) ? ($_POST['searchTimeFrom']) : '';
    $searchTimeTo = isset($_POST['searchTimeTo']) ? ($_POST['searchTimeTo']) : '';

    $where = " (order_num like '%$searchOrder%') ";
    if ($searchTimeFrom != "") {
        $where = $where . " AND tb_akt_order.created_date > '$searchTimeFrom' ";
    }

    if ($searchTimeTo != "") {
        $where = $where . " AND tb_akt_order.created_date < '$searchTimeTo' ";
    }

    $sql = "select id  from tb_user where username = '$username' and status <> 3";
    $rs = mysqli_query($con, $sql);

    if ($row = mysqli_fetch_object($rs)) {
        $userId = $row->id;
    } else {
        echo json_encode(array('status' => '2'));
        return;
    }
    $items = array();
    $sql = "select id,order_num,created_date from tb_akt_order where user_id  = " . $userId . " and " . $where;
    $rs = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_object($rs)) {
        array_push($items, $row);
    }

    echo json_encode(array('status' => '1', "info" => $items));
}

?>