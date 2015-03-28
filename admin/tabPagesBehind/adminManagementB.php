<?php
          require('../../global/init.php');
       
        function getAdmin() {
            $con = DatabaseConn::getConn();
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
            $username = isset($_POST['username']) ? ($_POST['username']) : '';
            $offset = ($page-1)*$rows;
            $result = array();
            $where = " role in ('普通管理员','超级管理员') and username like '%$username%'";
            
            mysqli_query($con,"set names 'utf8'");
            $rs =  mysqli_query($con,"select count(*) from tb_admin where ". $where);
            $row = mysqli_fetch_row($rs);
            $result["total"] = $row[0];
            $rs = mysqli_query($con,"select * from tb_admin where " . $where . "  order by id desc  limit $offset,$rows");
            $items = array();
            while($row = mysqli_fetch_object($rs)){
                    array_push($items, $row);
                   
            }
            $result["rows"] = $items;

            echo json_encode($result);
        }
        
 
        function addAdmin() {
            session_start();
            $username= $_REQUEST['management_username'];
            $password = $_REQUEST['management_password'];
            $confirmPass = $_REQUEST['management_confirmPass'];
            $role = $_REQUEST['management_role'];
            $createdAdmin = $_SESSION['username'];
            
            if($password <> $confirmPass) {
                  echo json_encode(array('msg'=>'两次密码输入不一致'));
                  return;
            }   
            $password = md5($password);
            $con = DatabaseConn::getConn();
            mysqli_query($con,"set names 'utf8'");
            
            $sql = "select * from tb_admin where username = '$username'";
            $rs = mysqli_query($con,$sql);
            if($row = mysqli_fetch_object($rs)){
                 echo json_encode(array('msg'=>'用户名已经存在'));
                 return;
            }
            
            $sql = "insert into tb_admin (username,password,role,created_admin  ) values('$username','$password','$role','$createdAdmin')";
            $result = mysqli_query($con,$sql);
            if ($result){
                    echo json_encode(array('success'=>true));
            } else {
                    echo json_encode(array('msg'=>'数据库错误'));
            }
        }
        
        function delAdmin(){
        
            $id = intval($_REQUEST['id']);
            $username = $_REQUEST['username'];
            
            session_start();
            if($username == $_SESSION['username']) {
                 echo json_encode(array('msg'=>'不能删除自己'));
                 return;
            }
            
            $sql = "update tb_admin set role='删除' where id=$id";
            $con = DatabaseConn::getConn();
             mysqli_query($con,"set names 'utf8'");
            $result = mysqli_query($con,$sql);
            if ($result){
                    echo json_encode(array('success'=>true));
            } else {
                    echo json_encode(array('msg'=>'数据库错误'));
            }
        }
        
        function initPass() {
            $id = intval($_REQUEST['id']);
            $pass = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
            $md5Pass = md5($pass);
            $con = DatabaseConn::getConn();
            mysqli_query($con,"set names 'utf8'");
            $sql = "update tb_admin set password='$md5Pass' where id=$id";
            $result = mysqli_query($con,$sql);
            if ($result){
                    echo json_encode(array('success'=>true,'msg'=>"您的密码是$pass"));
            } else {
                    echo json_encode(array('msg'=>'数据库错误'));
            }
        }
        
        $action =  $_GET['action'];
        
        if($action==1) {
            getAdmin();
        }
        else if ($action==2) {
            addAdmin();
        }
        else if ($action==3) {
            delAdmin();
        }
        else if ($action==4) {
            initPass();
        }
?>