<?php

require('../../global/init.php');

$action = $_GET['action'];


if ($action == 1) {
    getBasicInfo();
} else if ($action == 2) {
    update();
}

function getBasicInfo() {
    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    session_start();
    $username = $_SESSION['username'];
    $sql = "select * from tb_user where username = '$username' and status <> 3";

    $rs = mysqli_query($con, $sql);
    if ($row = mysqli_fetch_object($rs)) {
        echo json_encode(array('status' => '1', 'info' => $row));
        return;
    } else {
        echo json_encode(array('status' => '2'));
        return;
    }
}

function update() {
    session_start();
    $username = $_SESSION['username'];

    $name = $_REQUEST['name'];
    $tel = $_REQUEST['tel'];
    $firm = $_REQUEST['company'];
    $education = $_REQUEST['edu'];
    $gender = $_REQUEST['sex'];
    $interest = $_REQUEST['habit'];

    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");

    $sql = "update tb_user set name = '$name', tel='$tel',firm='$firm', education='$education',gender='$gender',interest='$interest'  where username= '$username'  and status <> 3";
    $rs = mysqli_query($con, $sql);
    if ($rs) {
        echo json_encode(array('status' => 1));
    } else {
        echo json_encode(array('status' => 2));
        return;
    }
}

?>