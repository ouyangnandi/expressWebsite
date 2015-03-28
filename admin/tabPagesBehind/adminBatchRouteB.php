<?php

require('../../global/init.php');

$action = $_GET['action'];
if ($action == 1) {
    getBatchRoute();
} else if ($action == 2) {
    editBatchRoute();
} else if ($action == 3) {
    delBatchRoute();
}

function   editBatchRoute() {
    
    session_start();
    $admin = $_SESSION['username'];
    $id = $_REQUEST['routeId'];
    $desc = $_REQUEST['batch_route_fm_desc'];
    $area = $_REQUEST['batch_route_fm_area'];
    $updated_time = $_REQUEST['batch_route_date'];
    
    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    $sql = "update tb_route set description='$desc', area='$area' ,updated_date='$updated_time' ,updated_admin='$admin' where id=$id";
    $result = mysqli_query($con, $sql);
    
    if ($result) {
        $result = mysqli_query($con, $sql);
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('msg' => '数据库错误'));
    }
    
}

function delBatchRoute(){
    
    $id = $_REQUEST['id'];
    $batchId = $_REQUEST['route_batch_id'];
    $sql = "delete from tb_route where id=$id";
    $sql2 = "update tb_batch set route_num=route_num-1 where id=$batchId";
    $con = DatabaseConn::getConn();
    $result = mysqli_query($con, $sql);
    
    if (!$result) {
        echo json_encode(array('msg' => '数据库错误，47'));
        return;
    }
    
    $result = mysqli_query($con, $sql2);
    
    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('msg' => '数据库错误,56'));
    }
}



function getBatchRoute() {

    $con = DatabaseConn::getConn();
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $searchBatch = isset($_POST['searchBatch']) ? ($_POST['searchBatch']) : '';
    $searchTimeFrom = isset($_POST['searchTimeFrom']) ? ($_POST['searchTimeFrom']) : '';
    $searchTimeTo = isset($_POST['searchTimeTo']) ? ($_POST['searchTimeTo']) : '';
    $offset = ($page - 1) * $rows;
    $result = array();
    $where = "(batch_name like '%$searchBatch%' or CAST(batch_id as CHAR) like '%$searchBatch%') ";

    if ($searchTimeFrom != "") {
        $where = $where . " AND updated_date > '$searchTimeFrom' ";
    }

    if ($searchTimeTo != "") {
        $where = $where . " AND updated_date < '$searchTimeTo' ";
    }


    mysqli_query($con, "set names 'utf8'");
    $rs = mysqli_query($con, "select count(*) from tb_route tb where " . $where);
    $row = mysqli_fetch_row($rs);
    $result["total"] = $row[0];
    $sql = "select * from tb_route where " . $where . " order by id desc limit $offset,$rows";
    $rs = mysqli_query($con, $sql);
    $items = array();
    while ($row = mysqli_fetch_object($rs)) {
        array_push($items, $row);
    }

    $result["rows"] = $items;

    echo json_encode($result);
}
