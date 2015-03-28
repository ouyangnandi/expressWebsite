<?php
 require('../../global/init.php');
 
  $action =  $_GET['action'];
         if($action==1) {
            getBatch();
        }
        else if ($action==3) {
          generateExcelInEnglish();
        }
        else if($action ==4) {
            generateExcelInChinese();
        }
        
        
        function getBatch() {
     
            $con = DatabaseConn::getConn();
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
            $searchBatch = isset($_POST['searchBatch']) ? $_POST['searchBatch']: '';
            $searchTimeFrom = isset($_POST['searchTimeFrom']) ? $_POST['searchTimeFrom'] : '';
            $searchTimeTo = isset($_POST['searchTimeTo']) ? $_POST['searchTimeTo'] : '';
            $offset = ($page-1)*$rows;
            $result = array();
            $where = "(name like '%$searchBatch%' or CAST(id as CHAR) like '%$searchBatch%') ";
            
            if($searchTimeFrom!=""){
                 $where = $where. " AND created_date > '$searchTimeFrom' ";
            }
            
            if($searchTimeTo!="") {
                $where = $where. " AND created_date < '$searchTimeTo' ";
            }
            
            
            mysqli_query($con,"set names 'utf8'");
            $rs =  mysqli_query($con,"select count(*) from tb_batch tb where ". $where);
            $row = mysqli_fetch_row($rs);
            $result["total"] = $row[0];
            $sql = "select * from tb_batch where " . $where . " order by id desc limit $offset,$rows";
            $rs = mysqli_query($con,$sql);
            $items = array();
            while($row = mysqli_fetch_object($rs)){
                
                 $row->amount  = ltrim($row->amount , '0');
                 if( $row->amount =="") {
                     $row->amount= "0";
                } 
                array_push($items, $row);
            }

            $result["rows"] = $items;

            echo json_encode($result);
        }

