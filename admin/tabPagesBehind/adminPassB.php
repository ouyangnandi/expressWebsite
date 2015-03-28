<?php
          require('../../global/init.php');
            
          
          changePass();
          
          function changePass(){
            $con = DatabaseConn::getConn();
            mysqli_query($con,"set names 'utf8'");
            session_start();
            $username = $_SESSION['username'];
            $sql = "select password from tb_admin where username = '$username'";
            $rs = mysqli_query($con,$sql);
            if ($row = mysqli_fetch_object($rs)) {
            
                $orgPass = $_POST['originPass'];
                $md5orgPass = md5($orgPass);

                if($md5orgPass <> $row->password) {
                     echo json_encode(array('msg'=>'密码不对，请重新输入密码'));
                     return;
                }
            }
            else{
                   echo json_encode(array('msg'=>'密码不对，请重新输入密码'));
                     return;
            }

            $pass = $_POST['newPass'];
            $md5Pass = md5($pass);


            $sql = "update tb_admin set password='$md5Pass' where username = '$username'";
            $result = mysqli_query($con,$sql);
            if ($result){
                    echo json_encode(array('success'=>true,'msg'=>'密码修改成功'));
            } else {
                    echo json_encode(array('msg'=>'数据库错误'));
            }
            
          }
        
?>