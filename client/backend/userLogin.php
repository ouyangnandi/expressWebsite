<?php

require('../../global/init.php');
require('../../global/class.phpmailer.php');
$action = $_GET['action'];

if ($action == 1) {
    logout();
} else if ($action == 2) {
    checkSession();
} else if ($action == 3) {
    verifyCaptureCode();
} else if ($action == 4) {
    login();
} else if ($action == 5) {
    lostPassword();
}

function verifyCaptureCode() {
    session_start();

    $code = isset($_POST['code']) ? $_POST['code'] : '';

    if (empty($_SESSION['4_letters_code']) ||
            strcasecmp($_SESSION['4_letters_code'], $code) != 0) {
        echo json_encode(array('status' => 'false'));
        return;
    } else {
        echo json_encode(array('status' => 'true'));
        return;
    }
}

function checkSession() {
    session_start();
    if (isset($_SESSION['username'])) {
        echo json_encode(array('status' => 'true'));
    } else {
        echo json_encode(array('status' => 'false'));
    }
}

function login() {
    session_start();

    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $pass = isset($_POST['pass']) ? $_POST['pass'] : '';
    $password = md5($pass);
    if(inject_check($username)) {
          echo json_encode(array('status' => '1'));
          return;
    }
    $sql = "select * from tb_user where username =  '$username' and password =  '$password' and status <> 3";
    $rs = mysqli_query($con, $sql);

    if ($row = mysqli_fetch_object($rs)) {
      
        $_SESSION['username'] = $username;
        echo json_encode(array('status' => '2'));
        return;
    } else {
       
        echo json_encode(array('status' => '1'));
        return;
    }
}

function lostPassword() {

    $username = isset($_POST['fUsername']) ? $_POST['fUsername'] : '';
    $email = isset($_POST['fEmail']) ? $_POST['fEmail'] : '';
    
        if(inject_check($username)||inject_check( $email)) {
             echo json_encode(array('status' => '2'));
          return;
    }
    
    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    $sql = "select * from tb_user where username = '$username' and email = '$email' and status <> 3";
    $rs = mysqli_query($con, $sql);

    if ($row = mysqli_fetch_object($rs)) {
      

        $pass = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
        $md5Pass = md5($pass);
        $sql = "update tb_user set password='$md5Pass' where username='$username' and status <> 3";
        $result = mysqli_query($con, $sql);
        if ($result) {
            
        } else {
            echo json_encode(array('status' => '3'));
            return;
        }
      
        $title = "新密码";
        $contents = $username . " 您好！ 您的密码是  '$pass',请您立即登录 www.canexpress.com.au 修改密码";
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
            echo json_encode(array('status' => '1'));
            return;
        } else {
            echo json_encode(array('status' => '4'));
            return;
        }
    } else {
     
        echo json_encode(array('status' => '2'));
        return;
    }
}

function logout() {
    session_start();
    unset($_SESSION['username']);
}
?>


