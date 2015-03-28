<?php

require('../../global/init.php');
require('../../global/class.phpmailer.php');
$action = $_GET['action'];

if ($action == 1) {
    getUser();
} else if ($action == 2) {
    deliveryCard();
} else if ($action == 3) {
    delUser();
} else if ($action == 4) {
    initPass();
}

function getUser() {
    try {

        $userStatus = array();
        $userStatus[0] = "未绑定卡";
        $userStatus[1] = "已经绑定卡";

        $con = DatabaseConn::getConn();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $searchUser = isset($_POST['searchUser']) ? ($_POST['searchUser']) : '';
        $searchUserStatus = isset($_POST['searchUserStatus']) ? ($_POST['searchUserStatus']) : '';
        $offset = ($page - 1) * $rows;
        $result = array();
        $where = "(card_num like '%$searchUser%' or CAST(id as CHAR) like '%$searchUser%' or username like '%$searchUser%' or email like '%$searchUser%')";

        if ($searchUserStatus == "1" || $searchUserStatus == "0") {
            $where = $where . " and status=$searchUserStatus";
        } else {
            $where = $where . " and status <> 3";
        }

        mysqli_query($con, "set names 'utf8'");
        $rs = mysqli_query($con, "select count(*) from tb_user where " . $where);
        $row = mysqli_fetch_row($rs);
        $result["total"] = $row[0];
        $rs = mysqli_query($con, "select * from tb_user where " . $where . " order by id desc   limit $offset,$rows");
        $items = array();
        while ($row = mysqli_fetch_object($rs)) {
            $row->status = ltrim($row->status, '0');
            $row->credits = ltrim($row->credits, '0');

            if ($row->status == "") {
                $row->status = "0";
            }

            if ($row->credits == "") {
                $row->credits = "0";
            }

            if ($row->gender == "0") {
                $row->gender = "未知";
            } else if ($row->gender == "1") {
                $row->gender = "男";
            } else {
                $row->gender = "女";
            }

            $row->status = $userStatus[$row->status];
            array_push($items, $row);
        }
        $result["rows"] = $items;

        echo json_encode($result);
    } catch (Exception $e) {
        echo $e->getTraceAsString();
    }
}

function delUser() {

    $id = intval($_REQUEST['id']);
    $sql = "update tb_user set status = 3 where id=$id";
    $con = DatabaseConn::getConn();
    $result = mysqli_query($con, $sql);

    $sql = "update tb_card set status = 2 where user_id=$id ";
    $result = mysqli_query($con, $sql);
    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('msg' => '数据库错误'));
    }
}

function initPass() {
    $id = $_REQUEST['id'];
    $username = $_REQUEST['username'];
    $email = $_REQUEST['email'];
    $pass = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
    $md5Pass = md5($pass);
    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    $sql = "update tb_user set password='$md5Pass' where id=$id";
    $result = mysqli_query($con, $sql);
    if ($result) {
        
        $title = "新密码";
        $contents = $username . "您好！ 您的密码是 '$pass' 请您立即登录 www.canexpress.com.au 修改密码";
        $mail = new PHPMailer(); //建立邮件发送类
        $mail->IsSMTP(); // 使用SMTP方式发送
        $mail->Host = "mail.canexpress.com.au"; // 您的企业邮局域名
        $mail->SMTPAuth = true; // 启用SMTP验证功能
        $mail->Username = "info@canexpress.com.au"; // 邮局用户名(请填写完整的email地址)
        $mail->Password = "INFO@canexpress"; // 邮局密码
        $mail->Charset = 'UTF-8';
        $mail->From = "info@canexpress.com.au"; //邮件发送者email地址
        $mail->FromName = "能通速递客服中心";
        $mail->AddAddress($email, "");
        $mail->Subject = "=?utf-8?B?" . base64_encode($title) . "?=";
        $mail->Body = $contents; //邮件内容

        if ($mail->Send()) {
           echo json_encode(array('success' => true, 'msg' => "客户的密码是 '$pass'，已经发送到他的邮箱了"));
            return;
        } else {
            echo json_encode(array('success' => true, 'msg' => "客户的密码是 '$pass',但是发送邮箱不成功 $email"));
            return;
        }
      
    } else {
        echo json_encode(array('msg' => '数据库错误'));
    }
}

function deliveryCard() {
    session_start();

    $admin = $_SESSION['username'];
    $cardNum = $_REQUEST['deliveryCardNum'];
    $userId = $_REQUEST['deliveryUserId'];
    $actionType = $_REQUEST['deliveryAction'];

    if (IsNullOrEmptyString($userId) || !ctype_digit($userId)) {
        echo json_encode(array('msg' => '用户ID必须是整数,并且不能为空'));
        return;
    }

    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");

    //检查用户是否存在
    $sql = "select 1 from tb_user where id = " . $userId;
    $rs = mysqli_query($con, $sql);
    $row_cnt = mysqli_num_rows($rs);
    if ($row_cnt = 0) {
        echo json_encode(array('msg' => '这个用户不存在'));
        return;
    }

    //检查卡是否存在
    $sql = "select * from tb_card where card_num = " . $cardNum;
    $rs = mysqli_query($con, $sql);
    if ($row = mysqli_fetch_object($rs)) {
        if ($row->status != "0") {
            echo json_encode(array('msg' => '该卡必须为未激活状态'));
            return;
        }
    } else {
        echo json_encode(array('msg' => '这个卡号不存在'));
        return;
    }

    //作废状态
    if ($actionType == "2") {
        $sql = "update tb_card set status = 2 where user_id = $userId and status = 1";
        $result = mysqli_query($con, $sql); //废除旧卡

        if (!$result) {
            echo json_encode(array('msg' => '数据库错误'));
            return;
        }
    }
    //更新卡状态              
    $sql = "update tb_card  set user_id=$userId,status=1 ,active_date=now(),active_admin='$admin' where card_num=$cardNum";
    $result = mysqli_query($con, $sql);

    //更新用户卡号 和 状态
    $sql = "update tb_user set card_num = $cardNum and status = 1 "
            . "where id=$userId";
    $result = mysqli_query($con, $sql); // 更新用户卡号


    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('msg' => '数据库错误'));
    }
}

?>