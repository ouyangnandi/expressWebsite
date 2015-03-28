<?php
      require('../../global/init.php');
       
        $action =  $_GET['action'];
        
        if($action==1) {
            getCardType();
        }
        else if ($action==2) {
            addCardType();
        }
        else if ($action==3) {
           delCardType();
        }
        else if($action ==4) {
            editCardType();
        }
      
      function getCardType() {
            $con = DatabaseConn::getConn();
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
            $offset = ($page-1)*$rows;
            $result = array();
            
            mysqli_query($con,"set names 'utf8'");
            $rs =  mysqli_query($con,"select count(*) from tb_card_type ");
            $row = mysqli_fetch_row($rs);
            $result["total"] = $row[0];
            $rs = mysqli_query($con,"select * from tb_card_type  order by id desc limit $offset,$rows");
            $items = array();
            while($row = mysqli_fetch_object($rs)){
                $row->credit_benchmark = ltrim($row->credit_benchmark, '0');
                
                if( $row->credit_benchmark =="") {
                     $row->credit_benchmark= "0";
                } 
                
                    array_push($items, $row);
            }
            $result["rows"] = $items;

            echo json_encode($result);
        }
        
 
        function addCardType() {
            session_start();
            $name= $_REQUEST['cardType_name'];
            $credit = $_REQUEST['cardType_credit'];
            $admin = $_SESSION['username'];
            
            if(!ctype_digit($credit) || ((int)$credit) <0) {
                  echo json_encode(array('msg'=>'积分必须是大于或者等于0的整数'));
                  return;
            }   
            $credit = (int)$credit;
            $con = DatabaseConn::getConn();
            mysqli_query($con,"set names 'utf8'");
            
            $sql = "select * from tb_card_type where name = '$name' or credit_benchmark = $credit";
            $rs = mysqli_query($con,$sql);
            if($row = mysqli_fetch_object($rs)){
                 echo json_encode(array('msg'=>'卡的名已经存在或者同样的积分已经存在'));
                 return;
            }
            
            $sql = "insert into tb_card_type  (name,credit_benchmark,created_admin) values('$name','$credit','$admin')";
            $result = mysqli_query($con,$sql);
            if ($result){
                    echo json_encode(array('success'=>true));
            } else {
                    echo json_encode(array('msg'=>'数据库错误'));
            }
        }
        
        function editCardType() {
            session_start();
            $name= $_REQUEST['cardType_name'];
            $credit = (int)$_REQUEST['cardType_credit'];
            $admin = $_SESSION['username'];
            $id= $_REQUEST['cardType_id'];
            
            if(!is_int($credit) || $credit <0) {
                  echo json_encode(array('msg'=>'积分必须是大于或者等于0的整数'));
                  return;
            }   
            
            $con = DatabaseConn::getConn();
            mysqli_query($con,"set names 'utf8'");
            
            $sql = "select * from tb_card_type where (name = '$name' or credit_benchmark = $credit) and id <> $id";
            $rs = mysqli_query($con,$sql);
            if($row = mysqli_fetch_object($rs)){
                 echo json_encode(array('msg'=>'卡的名已经存在或者同样的积分已经存在'));
                 return;
            }
            
            $sql = "update tb_card_type  set name='$name' ,credit_benchmark='$credit',created_admin='$admin' where id=$id";
            $result = mysqli_query($con,$sql);
            if ($result){
                    echo json_encode(array('success'=>true));
            } else {
                    echo json_encode(array('msg'=>'数据库错误'));
            }
        }
        
        
        
        function delCardType(){
        
            $id = intval($_REQUEST['id']);
            $con = DatabaseConn::getConn();

            $sql = "delete from tb_card_type where id=$id";

            $result = mysqli_query($con,$sql);
            if ($result){
                    echo json_encode(array('success'=>true));
            } else {
                    echo json_encode(array('msg'=>'数据库错误'));
            }
        }
    ?>
