<?php

require('../../global/init.php');
$action = $_GET["action"];
//if (checkCode()) {
//    header('Location: ../adminLogin.php?status=2');
//} else {
//    if ($action == 1) {
//        login();
//    } else {
//        logout();
//    }
//}

  if ($action == 1) {
        login();
        return;
    } else {
        logout();
        return;
    }

function checkCode() {
    session_start();
    $code = isset($_POST['code']) ? $_POST['code'] : '';
    if (empty($_SESSION['4_letters_code']) ||
            strcasecmp($_SESSION['4_letters_code'], $code) != 0) {
        return true;
    } else {
        return false;
    }
}

function login() {

    $username = $_POST["username"];
    $password = $_POST["password"];

    if (inject_check($username) || inject_check($password)) {
        header('Location: ../adminLogin.php?status=1');
    }

    $password = md5($password);
    $sql = "SELECT 1 FROM tb_admin WHERE username = " . "'$username'" . " and password = " . "'$password'";

    $con = DatabaseConn::getConn();
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) != 0) {
         session_start();
        $_SESSION['userAdmin'] = "$username";
         $_SESSION['username'] = "$username";
        header('Location: ../adminMain.php');
    } else {
        header('Location: ../adminLogin.php?status=1');
    }
}

function logout() {
    session_start();
    session_unset();
    session_destroy();
    header('Location: ../../index.html');
}

?>

