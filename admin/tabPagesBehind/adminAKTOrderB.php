<?php

require('../../global/init.php');

$action = $_GET['action'];

if ($action == 1) {
    getAKTOrder();
} else if ($action == 2) {
    addAKTOrder();
} else if ($action == 3) {
    delAKTOrder();
} else if ($action == 4) {
    editAktOrder();
}

function getAKTOrder() {

    $con = DatabaseConn::getConn();
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $searchOrder = isset($_POST['searchOrder']) ? $_POST['searchOrder'] : '';
    $searchTimeFrom = isset($_POST['searchTimeFrom']) ? $_POST['searchTimeFrom'] : '';
    $searchTimeTo = isset($_POST['searchTimeTo']) ? $_POST['searchTimeTo'] : '';
    $offset = ($page - 1) * $rows;
    $result = array();
    $where = "(order_num like '%$searchOrder%' or CAST(user_id as CHAR) like '%$searchOrder%' or batch_name  like '%$searchOrder%') ";

    if ($searchTimeFrom != "") {
        $where = $where . "AND tb_akt_order.created_date > '$searchTimeFrom' ";
    }

    if ($searchTimeTo != "") {
        $where = $where . "AND tb_akt_order.created_date < '$searchTimeTo' ";
    }
    mysqli_query($con, "set names 'utf8'");
    $rs = mysqli_query($con, "select count(*) from tb_akt_order where " . $where);
    $row = mysqli_fetch_row($rs);
    $result["total"] = $row[0];
    $rs = mysqli_query($con, "select tb_akt_order.*,tb_user.username as username from tb_akt_order left outer join tb_user on tb_akt_order.user_id = tb_user.id  where " . $where . "order by tb_akt_order.id desc limit $offset,$rows ");
    $items = array();
    while ($row = mysqli_fetch_object($rs)) {
        if ($row->user_id == "0") {
            $row->user_id = "";
        }

        if ($row->status == "0") {
            $row->status = "未转单";
        } else if ($row->status == "1") {
            $row->status = "已转单";
        } else {
            $row->status = "新订单号已经删除";
        }
        
        $row->batch_name_seq =  $row->batch_name . $row->batch_seq;

        array_push($items, $row);
    }
    $result["rows"] = $items;

    echo json_encode($result);
}

function addAKTOrder() {
    session_start();
    $AKTOrder_order_num = $_REQUEST['AKTOrder_order_num'];
    $AKTOrder_batch_name = $_REQUEST['AKTOrder_batch_name'];
    $AKTOrder_batch_id = $_REQUEST['AKTOrder_batch_id'];
    $AKTOrder_batch_seq = $_REQUEST['AKTOrder_batch_seq'];
    $AKTOrder_user_name = $_REQUEST['AKTOrder_user_name'];
    $AKTOrder_createdAdmin = $_SESSION['username'];

    $AKTOrder_category = $_REQUEST['AKTOrder_category'];
    $AKTOrder_amount = $_REQUEST['AKTOrder_amount'];
    $AKTOrder_weight = $_REQUEST['AKTOrder_weight'];
    $AKTOrder_certificate = $_REQUEST['AKTOrder_certificate'];

    $AKTOrder_sender_name = $_REQUEST['AKTOrder_sender_name'];
    $AKTOrder_sender_email = $_REQUEST['AKTOrder_sender_email'];
    $AKTOrder_sender_tel_area = $_REQUEST['AKTOrder_sender_tel_area'];
    $AKTOrder_sender_tel = $_REQUEST['AKTOrder_sender_tel'];
    $AKTOrder_sender_addr = $_REQUEST['AKTOrder_sender_addr'];

    $AKTOrder_receiver_name = $_REQUEST['AKTOrder_receiver_name'];
    $AKTOrder_receiver_tel_area = $_REQUEST['AKTOrder_receiver_tel_area'];
    $AKTOrder_receiver_tel = $_REQUEST['AKTOrder_receiver_tel'];
    $AKTOrder_receiver_email = $_REQUEST['AKTOrder_receiver_email'];
    $AKTOrder_receiver_addr = $_REQUEST['AKTOrder_receiver_addr'];

    $AKTOrder_order_route_info = $_REQUEST['AKTOrder_order_route_info'];
    $AKTOrder_order_route_date = $_REQUEST['AKTOrder_order_route_date'];
    $AKTOrder_order_route_area = $_REQUEST['AKTOrder_order_route_area'];

    $AKTOrder_remarks = $_REQUEST['AKTOrder_remarks'];

    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    $sql = "select 1 from tb_akt_order where order_num ='$AKTOrder_order_num'";
    $rs = mysqli_query($con, $sql);
    if ($row = mysqli_fetch_row($rs)) {
        echo json_encode(array('msg' => '订单号已经存在'));
        return;
    }
    
    if ($AKTOrder_user_name != "") {
        $sql = "select id from tb_user where username = '$AKTOrder_user_name' and status <> 3";
        $rs = mysqli_query($con, $sql);
        if ($row = mysqli_fetch_row($rs)) {
            
            $AKTOrder_user_id = $row[0];
            //添加用户积分
            if ($AKTOrder_weight != "") {
                $sql = "update tb_user set credits = credits + $AKTOrder_weight where id =$AKTOrder_user_id";
                $rs = mysqli_query($con, $sql);
                
                if(!$rs) {
                    echo json_encode(array('msg' => '数据库错误115'));
                     return;
                }
            }
        } else {
            echo json_encode(array('msg' => '会员账号不存在'));
            return;
        }
    }
    else{
        $AKTOrder_user_id = 0;
    }

    $lastBatchId = "";
    $batchAmount = "";

    //新建 batch
    if ($AKTOrder_batch_id == "") {
        $sql = "insert into tb_batch (name,amount,created_date,created_admin) values('$AKTOrder_batch_name',1,now(), '$AKTOrder_createdAdmin')";
        $result = mysqli_query($con, $sql);
        if (!$result) {
            echo json_encode(array('msg' => '数据库错误136'));
            return;
        }
        $lastBatchId = mysqli_insert_id($con);
    } else { // 给Batch的订单量添加1
        $sql = "select amount from tb_batch where id = $AKTOrder_batch_id ";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_row($result);
        $batchAmount = $row[0];

        $batchAmount = $batchAmount + 1;

        $sql = "update  tb_batch set amount=$batchAmount where id = $AKTOrder_batch_id ";
        $result = mysqli_query($con, $sql);

        if (!$result) {
            echo json_encode(array('msg' => '数据库错误152'));
            return;
        }

        $lastBatchId = $AKTOrder_batch_id;
    }

    // 积分添加，重量添加

    if ($AKTOrder_amount == "") {
        $AKTOrder_amount = 0;
    }

    if ($AKTOrder_weight == "") {
        $AKTOrder_weight = 0;
    }

    $sql = "insert into tb_akt_order (amount,"
            . "batch_id,"
            . "batch_name,"
            . "batch_seq,"
            . "category,"
            . "certificate,"
            . "created_admin,"
            . "created_date,"
            . "order_num,"
            . "order_route_date,"
            . "order_route_info,"
            . "order_route_area,"
            . "receiver_addr,"
            . "receiver_email,"
            . "receiver_name,"
            . "receiver_tel_area,"
            . "receiver_tel,"
            . "remarks,"
            . "sender_addr,"
            . "sender_email,"
            . "sender_name,"
            . "sender_tel_area,"
            . "sender_tel,"
            . "status,"
            . "user_id,"
            . "weight"
            . ")values("
            . "$AKTOrder_amount,"
            . "$lastBatchId,"
            . "'$AKTOrder_batch_name',"
            . "$AKTOrder_batch_seq,"
            . "'$AKTOrder_category',"
            . "'$AKTOrder_certificate',"
            . "'$AKTOrder_createdAdmin',"
            . "now(),"
            . "'$AKTOrder_order_num',"
            . "STR_TO_DATE('$AKTOrder_order_route_date', '%Y-%m-%d %H:%i:%s'),"
            . "'$AKTOrder_order_route_info',"
            . "'$AKTOrder_order_route_area',"
            . "'$AKTOrder_receiver_addr',"
            . "'$AKTOrder_receiver_email',"
            . "'$AKTOrder_receiver_name',"
            . "'$AKTOrder_receiver_tel_area',"
            . "'$AKTOrder_receiver_tel',"
            . "'$AKTOrder_remarks',"
            . "'$AKTOrder_sender_addr',"
            . "'$AKTOrder_sender_email',"
            . "'$AKTOrder_sender_name',"
            . "'$AKTOrder_sender_tel_area',"
            . "'$AKTOrder_sender_tel',"
            . "0,"
            . "'$AKTOrder_user_id',"
            . "'$AKTOrder_weight'"
            . ")";
    $rs = mysqli_query($con, $sql);
      
    if ($rs) {
        echo json_encode(array('status' => 0,'lastBatchId'=>$lastBatchId ));
    } else {
        
        $error = $con->error;
        $batchAmount--;
        $sql = "update  tb_batch set amount=$batchAmount where id = $AKTOrder_batch_id ";
        mysqli_query($con, $sql);

        echo json_encode(array('msg' => '数据库错误232 ' .$error ));
    }
}

function editAKTOrder() {
    session_start();
    $AKTOrder_id = $_REQUEST["AKTOrder_id"];

    $AKTOrder_order_num = $_REQUEST['AKTOrder_order_num'];
    $AKTOrder_origi_order_num = $_REQUEST['AKTOrder_origi_order_num'];

    $AKTOrder_batch_seq = $_REQUEST['AKTOrder_batch_seq'];
    $AKTOrder_user_name = $_REQUEST['AKTOrder_user_name'];
    $AKTOrder_original_user_id = $_REQUEST['AKTOrder_original_user_id'];

    $AKTOrder_category = $_REQUEST['AKTOrder_category'];
    $AKTOrder_amount = $_REQUEST['AKTOrder_amount'];
    $AKTOrder_weight = $_REQUEST['AKTOrder_weight'];
    $AKTOrder_original_weight = $_REQUEST['AKTOrder_original_weight'];
    $AKTOrder_certificate = $_REQUEST['AKTOrder_certificate'];

    $AKTOrder_sender_name = $_REQUEST['AKTOrder_sender_name'];
    $AKTOrder_sender_email = $_REQUEST['AKTOrder_sender_email'];
    $AKTOrder_sender_tel_area = $_REQUEST['AKTOrder_sender_tel_area'];
    $AKTOrder_sender_tel = $_REQUEST['AKTOrder_sender_tel'];
    $AKTOrder_sender_addr = $_REQUEST['AKTOrder_sender_addr'];

    $AKTOrder_receiver_name = $_REQUEST['AKTOrder_receiver_name'];
    $AKTOrder_receiver_tel_area = $_REQUEST['AKTOrder_receiver_tel_area'];
    $AKTOrder_receiver_tel = $_REQUEST['AKTOrder_receiver_tel'];
    $AKTOrder_receiver_email = $_REQUEST['AKTOrder_receiver_email'];
    $AKTOrder_receiver_addr = $_REQUEST['AKTOrder_receiver_addr'];

    $AKTOrder_order_route_info = $_REQUEST['AKTOrder_order_route_info'];
    $AKTOrder_order_route_date = $_REQUEST['AKTOrder_order_route_date'];
    $AKTOrder_order_route_area = $_REQUEST['AKTOrder_order_route_area'];

    $AKTOrder_remarks = $_REQUEST['AKTOrder_remarks'];
    $AKTOrder_createdAdmin = $_SESSION['username'];

    $con = DatabaseConn::getConn();
    mysqli_query($con, "set names 'utf8'");
    if ($AKTOrder_origi_order_num != $AKTOrder_order_num) {
        $sql = "select 1 from tb_akt_order where order_num ='$AKTOrder_order_num'";
        $rs = mysqli_query($con, $sql);
        if ($row = mysqli_fetch_row($rs)) {
            echo json_encode(array('msg' => '订单号已经存在'));
            return;
        }
    }
    
  if (!IsNullOrEmptyString($AKTOrder_user_name)) {

        $sql = "select id from tb_user where username='" . $AKTOrder_user_name  . "' and status <> 3";
        $rs = mysqli_query($con, $sql);
        if ($row = mysqli_fetch_object($rs)) {
            $AKTOrder_user_id = $row->id;
        } else {
            echo json_encode(array('msg' => '会员不存在'));
            return;
     }
  }
  else{
      $AKTOrder_user_id  = "";
  }
    
  //未更换用户的情况
    if (isset($AKTOrder_user_id) && $AKTOrder_user_id != "" && $AKTOrder_user_id == $AKTOrder_original_user_id) {
    
            if ($AKTOrder_weight != "" && $AKTOrder_weight != $AKTOrder_original_weight) {
                $sql = "update tb_user set credits =  credits + $AKTOrder_weight - $AKTOrder_original_weight  where id =$AKTOrder_user_id";
                $rs = mysqli_query($con, $sql);
                if(!$rs) {
                       echo json_encode(array('msg' => '数据库错误304'));
                     return;
                }
            }
    }
     
    //更换用户的情况
    if ($AKTOrder_user_id != "" && $AKTOrder_user_id != $AKTOrder_original_user_id) {
      
            if ($AKTOrder_weight != "" ) {
                $sql = "update tb_user set credits =  credits + $AKTOrder_weight  where id =$AKTOrder_user_id";
                $rs = mysqli_query($con, $sql);
               
                $sql = "update tb_user set credits =  credits - $AKTOrder_original_weight  where id =$AKTOrder_original_user_id";
                $rs = mysqli_query($con, $sql);
                
                if(!$rs) {
                       echo json_encode(array('msg' => '数据库错误321'));
                     return;
                }
            }

    }
    
    //删除了用户的情况
    if ($AKTOrder_user_id =="" && $AKTOrder_original_user_id !="") {               
                $sql = "update tb_user set credits =  credits - $AKTOrder_original_weight  where id =$AKTOrder_original_user_id";
                $rs = mysqli_query($con, $sql);
                
                 if(!$rs) {
                       echo json_encode(array('msg' => '数据库错误335'));
                     return;
                }
    }


    if ($AKTOrder_amount == "") {
        $AKTOrder_amount = 0;
    }
    if ($AKTOrder_weight == "") {
        $AKTOrder_weight = 0;
    }
    if ($AKTOrder_user_id == "") {
        $AKTOrder_user_id = 0;
    }

    $sql = "update tb_akt_order set amount=$AKTOrder_amount,"
            . "batch_seq=$AKTOrder_batch_seq,"
            . "category='$AKTOrder_category',"
            . "certificate='$AKTOrder_certificate',"
            . "order_num='$AKTOrder_order_num',"
            . "created_admin='$AKTOrder_createdAdmin',"
            . "order_route_date=STR_TO_DATE('$AKTOrder_order_route_date', '%Y-%m-%d %H:%i:%s'),"
            . "order_route_info='$AKTOrder_order_route_info',"
            . "order_route_area='$AKTOrder_order_route_area',"
            . "receiver_addr='$AKTOrder_receiver_addr',"
            . "receiver_email='$AKTOrder_receiver_email',"
            . "receiver_name='$AKTOrder_receiver_name',"
            . "receiver_tel_area='$AKTOrder_receiver_tel_area',"
            . "receiver_tel='$AKTOrder_receiver_tel',"
            . "remarks='$AKTOrder_remarks',"
            . "sender_addr='$AKTOrder_sender_addr',"
            . "sender_email='$AKTOrder_sender_email',"
            . "sender_name='$AKTOrder_sender_name',"
            . "sender_tel_area='$AKTOrder_sender_tel_area',"
            . "sender_tel='$AKTOrder_sender_tel',"
            . "user_id=$AKTOrder_user_id,"
            . "weight='$AKTOrder_weight'"
            . " where id=$AKTOrder_id";
    $rs = mysqli_query($con, $sql);

    if ($rs) {
        echo json_encode(array('status' => 1));
    } else {
        echo json_encode(array('msg' => '数据库错误379'));
    }
}

function delAKTOrder() {
    $id = $_REQUEST['order_id'];
    $AKTOrder_batch_id = $_REQUEST['batch_id'];
    $AKTOrder_user_id = $_REQUEST['user_id'];
    $AKTOrder_weight = $_REQUEST['weight'];
    $sql = "delete from tb_akt_order where id=$id";
    $con = DatabaseConn::getConn();
    $result = mysqli_query($con, $sql);
    if (!$result) {
        echo json_encode(array('success' => true));
        return;
    }

    $sql = "select amount from tb_batch where id = $AKTOrder_batch_id ";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_row($result);
    $batchAmount = $row[0];
    $batchAmount = $batchAmount - 1;
    $sql = "update  tb_batch set amount=$batchAmount where id = $AKTOrder_batch_id ";
    $result = mysqli_query($con, $sql);
    
    $sql = "update tb_user set credits =  credits -  $AKTOrder_weight  where id = $AKTOrder_user_id";
    $rs = mysqli_query($con, $sql);
    
    
    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('msg' => '数据库错误411'));
    }
}

?>
