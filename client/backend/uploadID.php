<?php

require('../../global/init.php');

$order_num = $_REQUEST['orderNum'];
$phone = $_REQUEST['phoneNum'];
$id_num = $_REQUEST['idNum'];

$con = DatabaseConn::getConn();
mysqli_query($con, "set names 'utf8'");

$sql = "select id,batch_id from tb_akt_order where order_num = '$order_num' and receiver_tel = '$phone' ";
$rs = mysqli_query($con, $sql);
if ($row = mysqli_fetch_object($rs)) {
    $batchId = $row->batch_id;
    saveFile($batchId);
} else {
    echo json_encode(array('status' => false, 'msg' => '找不到订单号或者手机号'));
    return;
}

function checkType($fileType) {
    if (($fileType == "image/gif") || ($fileType == "image/jpeg") || ($fileType == "image/jpg") || ($fileType == "image/pjpeg") || ($fileType == "image/x-png") || ($fileType == "image/png")) {
        return true;
    } else {
        return false;
    }
}

function saveFile($batchId) {
    $order_num = $_REQUEST['orderNum'];
    $phone = $_REQUEST['phoneNum'];
    $id_num = $_REQUEST['idNum'];
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    $temp = explode(".", $_FILES["file"]["name"][0]);
    $temp2 = explode(".", $_FILES["file"]["name"][1]);
    $extension = end($temp);
    $extension2 = end($temp2);
    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");


    if (checkType($_FILES["file"]["type"][0]) && checkType($_FILES["file"]["type"][1]) && ($_FILES["file"]["size"][0] < 5 * 1024 * 1024 ) && ($_FILES["file"]["size"][1] < 5 * 1024 * 1024 )) {

        if ($_FILES["file"]["error"][0] > 0 || $_FILES["file"]["error"][1] > 0) {
            echo json_encode(array('status' => false, 'msg' => '文件上传错误，错误代码为' . $_FILES["file"]["error"][0] . $_FILES["file"]["error"][1]));
            return;
        } else {

            $folderName = "../../uploadID/" . $batchId;

            if (!file_exists($folderName)) {
                 mkdir($folderName);
            }

            $fileName = $order_num . '_1.' . $extension;
            $fileName2 = $order_num . '_2.' . $extension2;
            move_uploaded_file($_FILES["file"]["tmp_name"][0], $folderName . "/" . $fileName);
            move_uploaded_file($_FILES["file"]["tmp_name"][1], $folderName . "/" . $fileName2);

            $sql = "update tb_akt_order set certificate='$id_num', pic_name='$fileName',pic_name2='$fileName2' where order_num = '$order_num'";
            $rs = mysqli_query($con, $sql);
            if ($rs) {
                
            } else {
                echo json_encode(array('status' => false, 'msg' => '数据库错误，37'));
                return;
            }

            echo json_encode(array('status' => true));
        }
    } else {
        echo json_encode(array('status' => false, 'msg' => '文件大小或者格式不正确'));
    }
}
?>

