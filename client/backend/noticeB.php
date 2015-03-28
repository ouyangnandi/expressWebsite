<?php

require('../../global/init.php');
$action = $_GET['action'];
if ($action == 1) {
    getNotice();
} else {
    countPageView();
}

function getNotice() {
    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    $sql = "SELECT id,subject,content,updated_date FROM tb_notice order by id desc";
    $rs = mysqli_query($con, $sql);
    $news = array();
    while ($row = mysqli_fetch_object($rs)) {
        array_push($news, $row);
    }

    if (count($news) > 0) {
        echo urldecode(json_encode(array('status' => 0, 'data' => $news)));
        return;
    } else {
        echo urldecode(json_encode(array('status' => 1, 'data' => "")));
        return;
    }
}

function countPageView() {
    $id = $_REQUEST['notice_id'];
    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    $sql = "update tb_notice set page_view =  page_view + 1   where id =$id";
    $rs = mysqli_query($con, $sql);
}

?>