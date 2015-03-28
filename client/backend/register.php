<?php

require('../../global/init.php');

 $action =  $_GET['action'];

if ($action == 3) {
   verifyCaptureCode();
} else if ($action == 4) {
    register();
}

function verifyCaptureCode(){
    session_start(); 
 
  $code = isset($_POST['code']) ? $_POST['code'] : '';
  
   if(empty($_SESSION['4_letters_code'] ) ||
        strcasecmp($_SESSION['4_letters_code'],$code) != 0)
    { 
             echo json_encode(array('status'=>'false'));
               return;
    }else{
         echo json_encode(array('status'=>'true'));
               return;
    }
}

function register(){
       session_start();
      $con = DatabaseConn::getConn();
      mysqli_query($con,"set names 'utf8'");
      $email = isset($_POST['email']) ?$_POST['email'] : '';
      $username = isset($_POST['username']) ? $_POST['username'] : '';
      
    if(inject_check($username)||inject_check( $email)) {
             echo json_encode(array('status' => '2'));
          return;
    }
      
      
      $pass = isset($_POST['pass']) ? $_POST['pass'] : '';
      $sql = "select * from tb_user where email = '".$email ."' and status <> 3"; 
      $rs = mysqli_query($con,$sql);
            
      if($row = mysqli_fetch_object($rs)){
               echo json_encode(array('status'=>'1'));
               return;
       }
       
      $sql = "select * from tb_user where username = '".$username ."' and status <> 3"; 
      $rs = mysqli_query($con,$sql);
            
      if($row = mysqli_fetch_object($rs)){
               echo json_encode(array('status'=>'2'));
               return;
       }

       $password = md5($pass);
       $sql ="insert into tb_user (username,password,status,email,created_date,active_admin) values('$username','$password',0,'$email',now(),'用户创建')";
       $result = mysqli_query($con,$sql);
       
       if ($result){
           $_SESSION['username'] = $username;
                    echo json_encode(array('status'=>'3'));
            } else {
                    echo json_encode(array('msg'=>'数据库错误'));
       }
}

?>
