<?php

require('../../global/init.php');
addFeedback();
        function addFeedback() {
        
            $name= $_REQUEST['name'];
            $tel = $_REQUEST['tel'];
            $email = $_REQUEST['email'];
            $content = $_REQUEST['content'];
            $orderNum = $_REQUEST['order_num'];
            
            if(IsNullOrEmptyString($content)) {
                  echo json_encode(array('msg'=>'内容不能为空'));
                  return;
            }   
            
            $con = DatabaseConn::getConn();
            mysqli_query($con,"set names 'utf8'");            
            $sql = "insert into tb_feedback  (name,tel,email,contents,order_num) values('$name','$tel','$email','$content','$orderNum')";
            $result = mysqli_query($con,$sql);
            if ($result){
                    echo json_encode(array('success'=>true));
            } else {
                    echo json_encode(array('msg'=>'数据库错误'));
            }
        }
?>
