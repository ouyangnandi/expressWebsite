<?php
 require('../../global/class.phpmailer.php');
// require('../../global/init.php');
 
$name = isset($_POST['name']) ? $_POST['name']: ''; 
$phoneNum = isset($_POST['phoneNum']) ? $_POST['phoneNum']: '';
$clientAddress = isset($_POST['address']) ? $_POST['address']: '';
$message = isset($_POST['message']) ? $_POST['message']: '';
$time = isset($_POST['time']) ? $_POST['time']: '';
$type = isset($_POST['type']) ? $_POST['type']: '';
$weight = isset($_POST['weight']) ? $_POST['weight']: '';


$emailBody = "有新的订单信息".  "\n".
        "客户姓名：" . $name . "\n".
        "客户电话：" . $phoneNum . "\n".
        "客户地址：" . $clientAddress . "\n".
        "客户留言：" . $message . "\n".
        "取货时间：" . $time . "\n".
        "货物类型：" . $type . "\n".
        "货物重量：" . $weight . "\n".
        "请尽快联系客户。";
 
$mail = new PHPMailer(); //建立邮件发送类
$mail->IsSMTP(); // 使用SMTP方式发送
$mail->Host = "mail.canexpress.com.au"; // 您的企业邮局域名
$mail->SMTPAuth = true; // 启用SMTP验证功能
$mail->Username = "info@canexpress.com.au"; // 邮局用户名(请填写完整的email地址)
$mail->Password = "INFO@canexpress"; // 邮局密码
$mail->Charset='UTF-8';
$mail->From = "info@canexpress.com.au"; //邮件发送者email地址
$mail->FromName = "New Order";

// $con = DatabaseConn::getConn();
//$sql = "select email from tb_email_account";
//$rs = mysqli_query($con,$sql);
//while( $row = mysqli_fetch_row($rs)){
//      $mail->AddAddress($row[0], "");
// }
  $mail->AddAddress("order@canexpress.com.au", "");
//$mail->AddReplyTo("", "");

//$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
//$mail->IsHTML(true); // set email format to HTML //是否使用HTML格式

$mail->Subject = "=?utf-8?B?" . base64_encode("订单信息") . "?=";
$mail->Body = $emailBody; //邮件内容

if($mail->Send()) {
     echo json_encode(array('status'=>'0'));
}
else{
     echo json_encode(array('status'=>'1'));
}

?>