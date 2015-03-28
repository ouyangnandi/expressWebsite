<?php

require('../../global/init.php');

$action = $_GET['action'];

if ($action == 1) {
    getRoute();
} else if ($action == 2) {
    addRoute();
} else if ($action == 3) {
    delRoute();
} else if ($action == 4) {
    editRoute(); 
}

function getRoute() {

    $con = DatabaseConn::getConn();
    $batchId=$_REQUEST['batchId'];
    mysqli_query($con, "set names 'utf8'");
    $rs = mysqli_query($con, "select * from tb_route where batch_id=". $batchId );
    $items = array();
    while ($row = mysqli_fetch_object($rs)) {
        array_push($items, $row);
    }
    echo json_encode($items);
}

function addRoute() {
    session_start();
    $admin = $_SESSION['username'];
    $desc = $_REQUEST['add_route_fm_desc'];
    $area = $_REQUEST['add_route_fm_area'];
    $updated_time = $_REQUEST['add_route_fm_date'];
    
    $batchIds = $_REQUEST['batchIds'];
    $batchNames = $_REQUEST['batchNames'];
    $ids = explode(',', $batchIds, -1);
    $names = explode(',', $batchNames, -1);
    
    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    
    $sql = "insert into tb_route (batch_id,batch_name,updated_admin,description,area,updated_date) values";
    $sql2= "update tb_batch set route_num= route_num+1 where id in(";
    for($i = 0;$i<count($ids);$i++) {
        if($i+1 < count($ids)) {
            $sql  = $sql."($ids[$i],'$names[$i]','$admin','$desc','$area','$updated_time'),";
            $sql2 = $sql2."$ids[$i],";
        }
        else{
            $sql = $sql."($ids[$i],'$names[$i]','$admin','$desc','$area','$updated_time')";
            $sql2 = $sql2."$ids[$i])";
        }
    }
    
    $result = mysqli_query($con, $sql);
    $result = mysqli_query($con, $sql2);  
    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('msg' => '数据库错误'));
    }

}

function editRoute() {
    session_start();
    $admin = $_SESSION['username'];
    $id = $_REQUEST['routeId'];
    $desc = $_REQUEST['route_fm_desc'];
    $area = $_REQUEST['route_fm_area'];
    $updated_time = $_REQUEST['route_fm_date'];

    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    $sql = "update tb_route set description='$desc', area='$area' ,updated_admin='$admin' ,updated_date='$updated_time' where id=$id";
    $result = mysqli_query($con, $sql);
    
    if ($result) {
        $result = mysqli_query($con, $sql);
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('msg' => '数据库错误'));
    }
}

function delRoute() {

    $id = $_REQUEST['id'];
    $batchId = $_REQUEST['route_batch_id'];
    $sql = "delete from tb_route where id=$id";
    $sql2 = "update tb_batch set route_num=route_num -1 where id=$batchId";
    $con = DatabaseConn::getConn();
    $result = mysqli_query($con, $sql);
    
    if (!$result) {
        echo json_encode(array('msg' => '数据库错误，97'));
        return;
    }
    
    $result = mysqli_query($con, $sql2);
    
    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('msg' => '数据库错误,106'));
    }
}

?>