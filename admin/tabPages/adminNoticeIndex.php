<?php
require ('tabPagesHeader.php');

$filename = '../../static/index_notice.html';
$handle = fopen($filename, "r"); //读取二进制文件时，需要将第二个参数设置成'rb'
//通过filesize获得文件大小，将整个文件一下子读到一个字符串中
$contents = fread($handle, filesize($filename));
fclose($handle);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>首页公告</title>
    </head>
    <body>
        <div class="center"><h2>首页公告(不能超过50个字)</h2></div>
        <div style="margin:20px 0;"></div>
        <div  style="text-align:center">

            <form id="tip_fm" method="post" >

                <div style="margin:10px">
                    <textarea style="width:98%" rows="15" id="index_notices_content" name="index_notices_content">
                        <?php echo $contents ?>

                    </textarea>
                </div>

            </form>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="easyui-linkbutton" style="width:80px;margin-right:10px" onclick="submitTips()">确认</a>

                <a href="javascript:void(0)" class="easyui-linkbutton" style="width:80px" onclick="clearTips()">清空</a>
            </div>


            <script>

             
             	new nicEditor().panelInstance('index_notices_content');
             
                function submitTips() {
                         nicEditors.findEditor('index_notices_content').saveContent();
                    $('#tip_fm').form('submit', {
                        url: '../admin/tabPagesBehind/adminNoticeIndexB.php',
                        onSubmit: function() {
                            return $(this).form('validate');
                        },
                        success: function(result) {

                            var result = eval('(' + result + ')');


                            if (result.success) {
                                $.messager.alert('首页公告提示修改成功', result.msg);

                            } else {
                                $.messager.alert('错误', result.msg);

                            }
                        }
                    });
                }

                function clearTips() {

                  
                  
                  nicEditors.findEditor('index_notices_content').setContent('');
                
            }
            </script>
    </body>
</html>
