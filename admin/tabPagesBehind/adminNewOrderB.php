<?php

 require('../../global/init.php');
       
        $action =  $_GET['action'];
        
        if($action==1) {
            getNewOrder();
        }
        else if ($action==2) {
            addNewOrder();
        }
        else if ($action==3) {
           delNewOrder();
        }
        else if($action ==4) {
            editNewOrder();
        }

        
        function getNewOrder() {
            
            $con = DatabaseConn::getConn();
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
            $searchNewOrder = isset($_POST['searchNewOrder']) ? ($_POST['searchNewOrder']) : '';
            $offset = ($page-1)*$rows;
            $result = array();
            $where = "new_order_num like '%$searchNewOrder%' or ta.order_num like '%$searchNewOrder%'";
            
            mysqli_query($con,"set names 'utf8'");
            $rs =  mysqli_query($con,"select count(*) from tb_new_order tn inner join tb_akt_order ta on tn.akt_order_id=ta.id where ". $where);
            $row = mysqli_fetch_row($rs);
            $result["total"] = $row[0];
            $rs = mysqli_query($con,"select tn.*,ta.order_num,tcf.name from tb_new_order tn inner join tb_akt_order ta on tn.akt_order_id=ta.id inner join tb_china_firm tcf on tcf.code = tn.new_order_company where " . $where . "  order by id desc  limit $offset,$rows");
            $items = array();
            while($row = mysqli_fetch_object($rs)){
                array_push($items, $row);
            }
            $result["rows"] = $items;

            echo json_encode($result);

        }
        
        
        function  addNewOrder() {
            session_start();
            $AKTOrderId=$_REQUEST["AKTOrder_id"];
            $NewOrderNum=$_REQUEST["New_Order_Num"];
            $NewOrderCompany=$_REQUEST["new_order_new_company"];
            $admin = $_SESSION['username'];
            
            if(IsNullOrEmptyString($NewOrderNum) ) {
                  echo json_encode(array('msg'=>'新订单号不能为空'));
                  return;
            }   
            
            $con = DatabaseConn::getConn();
            mysqli_query($con,"set names 'utf8'");
            $sql = "select 1 from tb_new_order where new_order_num ='$$NewOrderNum'";
            $rs = mysqli_query($con,$sql);
            if($row = mysqli_fetch_row($rs)){
                 echo json_encode(array('msg'=>'新订单号已经存在'));
                 return;
            }
             $sql="update tb_akt_order set status=1 where id = $AKTOrderId";
              $result = mysqli_query($con,$sql);
            
            $sql = "insert into tb_new_order (akt_order_id,new_order_num,updated_admin,new_order_company) values($AKTOrderId,'$NewOrderNum','$admin','$NewOrderCompany')";
            $result = mysqli_query($con,$sql);
            if ($result){
                    echo json_encode(array('success'=>true));
            } else {
                    echo json_encode(array('msg'=>'数据库错误'));
            }
            
        }
        
        
        function   delNewOrder() {
            $id = intval($_REQUEST['id']);
            $akt_order_num = $_REQUEST['akt_order_num'];
            $sql = "delete from tb_new_order where id=$id";
            $con = DatabaseConn::getConn();
            mysqli_query($con,"set names 'utf8'");
            $result = mysqli_query($con,$sql);
            
            $sql = "update tb_akt_order set status=3 where order_num=$akt_order_num ";
            $result = mysqli_query($con,$sql);
            if ($result){
                    echo json_encode(array('success'=>true));
            } else {
                    echo json_encode(array('msg'=>'数据库错误'));
            }
            
        }
        
        function   editNewOrder() {
            
            session_start();
            $id=$_REQUEST["newOrder_id"];
            $NewOrderNum=$_REQUEST["newOrder_order_num"];
            $NewOrderCompany=$_REQUEST["new_order_company"];
            $OriginalOrderNum=$_REQUEST["Origi_Order_Num"];
            $admin = $_SESSION['username'];
            
            if(IsNullOrEmptyString($NewOrderNum) ) {
                  echo json_encode(array('msg'=>'新订单号不能为空'));
                  return;
            }   
           $con = DatabaseConn::getConn();
           mysqli_query($con,"set names 'utf8'");
            
            if($OriginalOrderNum!=$NewOrderNum) {

                $sql = "select 1 from tb_new_order where new_order_num ='$NewOrderNum'";
                $rs = mysqli_query($con,$sql);
                if($row = mysqli_fetch_row($rs)){
                     echo json_encode(array('msg'=>'新订单号已经存在'));
                     return;
                }
            }
            
           $sql = "update tb_new_order set new_order_num='$NewOrderNum',updated_admin='$admin',new_order_company='$NewOrderCompany' where id=$id";
            $result = mysqli_query($con,$sql);
            if ($result){
                    echo json_encode(array('success'=>true));
            } else {
                    echo json_encode(array('msg'=>'数据库错误'));
            }
            
        }
        
        
        ?>

