<?php

          $filename = "../../static/orderTips.html";
          $tips = $_REQUEST["order_tips_content"];
          $result =  file_put_contents($filename, $tips);
          
          if($result>=0) {
            echo json_encode(array('success'=>true,'msg'=>'提示修改成功'));
          }
          else{
                echo json_encode(array('msg'=>'读取文件错误'));
          }
          
          ?>