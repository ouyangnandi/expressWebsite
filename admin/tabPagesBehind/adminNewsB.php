<?php
      require('../../global/init.php');
       
        $action =  $_GET['action'];
        
        if($action==1) {
            getNews();
        }
        else if ($action==2) {
            addNews();
        }
        else if ($action==3) {
           delNews();
        }
        else if($action ==4) {
            editNews();
        }
      
      function getNews() {
            $con = DatabaseConn::getConn();
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
            $offset = ($page-1)*$rows;
            $result = array();
            
            mysqli_query($con,"set names 'utf8'");
            $rs =  mysqli_query($con,"select count(*) from tb_news ");
            $row = mysqli_fetch_row($rs);
            $result["total"] = $row[0];
            $rs = mysqli_query($con,"select * from tb_news   order by id desc limit $offset,$rows");
            $items = array();
            while($row = mysqli_fetch_object($rs)){
                $row->page_view  = ltrim($row->page_view, '0');
                
                if( $row->page_view =="") {
                     $row->page_view= "0";
                } 
                
                    array_push($items, $row);
            }
            $result["rows"] = $items;

            echo json_encode($result);
        }
        
 
        function addNews() {
            session_start();
            $subject= $_REQUEST['news_subject'];
            $content = $_REQUEST['news_content'];
            $admin = $_SESSION['username'];
            
            if(IsNullOrEmptyString($subject) || IsNullOrEmptyString($content)) {
                  echo json_encode(array('msg'=>'标题或者内容不能为空'));
                  return;
            }   
            
            $con = DatabaseConn::getConn();
            mysqli_query($con,"set names 'utf8'");
                        
            $sql = "insert into tb_news  (subject,content,created_admin) values('$subject','$content','$admin')";
            $result = mysqli_query($con,$sql);
            if ($result){
                    echo json_encode(array('success'=>true));
            } else {
                    echo json_encode(array('msg'=>'数据库错误'));
            }
        }
        
        function editNews() {
            session_start();
            $subject= $_REQUEST['news_subject'];
            $content = $_REQUEST['news_content'];
            $admin = $_SESSION['username'];
            $id= $_REQUEST['news_id'];
            
            if(IsNullOrEmptyString($subject) || IsNullOrEmptyString($content)) {
                  echo json_encode(array('msg'=>'标题或者内容不能为空'));
                  return;
            }   
            
            $con = DatabaseConn::getConn();
            mysqli_query($con,"set names 'utf8'");
            $sql = "update tb_news  set subject='$subject' ,content='$content',created_admin='$admin' where id=$id";
            $result = mysqli_query($con,$sql);
            if ($result){
                    echo json_encode(array('success'=>true));
            } else {
                    echo json_encode(array('msg'=>'数据库错误'));
            }
        }
        
        
        
        function delNews(){
        
            $id = intval($_REQUEST['id']);
            $sql = "delete from tb_news where id=$id";
            $con = DatabaseConn::getConn();
            $result = mysqli_query($con,$sql);
            if ($result){
                    echo json_encode(array('success'=>true));
            } else {
                    echo json_encode(array('msg'=>'数据库错误'));
            }
        }
    ?>