<?php

require('../../global/init.php');
require('../../global/class.phpmailer.php');

$action = $_GET['action'];

if ($action == 1) {
    getFeedback();
} else if ($action == 2) {
    replyFeedback();
} else if ($action == 3) {
    delFeedback();
}

function getFeedback() {
    $con = DatabaseConn::getConn();
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $offset = ($page - 1) * $rows;
    $result = array();

    mysqli_query($con, "set names 'utf8'");
    $rs = mysqli_query($con, "select count(*) from tb_feedback ");
    $row = mysqli_fetch_row($rs);
    $result["total"] = $row[0];
    $rs = mysqli_query($con, "select * from tb_feedback  order by id desc limit $offset,$rows");
    $items = array();
    while ($row = mysqli_fetch_object($rs)) {
        array_push($items, $row);
    }
    $result["rows"] = $items;

    echo json_encode($result);
}

function delFeedback() {

    $id = intval($_REQUEST['id']);
    $sql = "delete from tb_feedback where id=$id";
    $con = DatabaseConn::getConn();
    $result = mysqli_query($con, $sql);
    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('msg' => '数据库错误'));
    }
}

function replyFeedback() {
    $con = DatabaseConn::getConn();
    session_start();

    $admin = $_SESSION['username'];
    $id = intval($_REQUEST['feedback_dlg_id']);
    $title = $_REQUEST['feedback_dlg_subject'];
    $receiver = $_REQUEST['feedback_dlg_receiver'];
    $contents = $_REQUEST['feedback_dlg_content'];

    $mail = new PHPMailer(); //建立邮件发送类
    $mail->IsSMTP(); // 使用SMTP方式发送
    $mail->Host = "mail.canexpress.com.au"; // 您的企业邮局域名
    $mail->SMTPAuth = true; // 启用SMTP验证功能
    $mail->Username = "info@canexpress.com.au"; // 邮局用户名(请填写完整的email地址)
    $mail->Password = "INFO@canexpress"; // 邮局密码
    $mail->Charset = 'UTF-8';
    $mail->From = "info@canexpress.com.au"; //邮件发送者email地址
    $mail->FromName = "能通速递客服中心";
    $mail->AddAddress($receiver, "");
    $mail->Subject = "=?utf-8?B?" . base64_encode($title) . "?=";
    $mail->Body = $contents; //邮件内容

    if ($mail->Send()) {


        $sql = "update tb_feedback set admin='$admin',reply_date=now() where id=$id";
        $result = mysqli_query($con, $sql);
        if ($result) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('msg' => '数据库错误'));
        };
    } else {
        echo json_encode(array('msg' => '邮件发送错误'));
    }
}
?>

