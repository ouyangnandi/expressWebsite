<?php
require ('tabPagesHeader.php');

$filename = '../../static/orderTips.html';
$handle = fopen($filename, "r"); //读取二进制文件时，需要将第二个参数设置成'rb'
//通过filesize获得文件大小，将整个文件一下子读到一个字符串中
$contents = fread($handle, filesize($filename));
fclose($handle);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>下单提示提示</title>
    </head>
    <body>
        <div class="center"><h2>下单提示</h2></div>
        <div style="margin:20px 0;"></div>
        <div  style="text-align:center">

            <form id="order_tip_fm" method="post" >

                <div style="margin:10px">
                    <textarea style="width:98%;" rows="20" id="order_tips_content" name="order_tips_content">
<?php echo $contents ?>

                    </textarea>
                </div>

            </form>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="easyui-linkbutton" style="width:80px;margin-right:10px" onclick="submitOrderTips()">确认</a>

                <a href="javascript:void(0)" class="easyui-linkbutton" style="width:80px" onclick="clearOrderTips()">清空</a>
            </div>


            <script>

                 	new nicEditor().panelInstance('order_tips_content');
                
                
                function submitOrderTips() {
                      nicEditors.findEditor('order_tips_content').saveContent();
                    
                    $('#order_tip_fm').form('submit', {
                        url: '../admin/tabPagesBehind/adminOrderTipsB.php',
                        onSubmit: function() {
                            return $(this).form('validate');
                        },
                        success: function(result) {

                            var result = eval('(' + result + ')');


                            if (result.success) {
                                $.messager.alert('温馨首页提示修改成功', result.msg);

                            } else {
                                $.messager.alert('错误', result.msg);

                            }
                        }
                    });
                }

                function clearOrderTips() {
           nicEditors.findEditor('order_tips_content').setContent('');
         
            }
            </script>
    </body>
</html>