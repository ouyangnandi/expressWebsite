<?php require ('tabPagesHeader.php') ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="../../css/themes/default/easyui.css"/>
        <link rel="stylesheet" type="text/css" href="../../css/themes/icon.css"/>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.easyui-cn.js" charset="utf-8"></script>
    </head>
    <body>
        <div id="toolBar">
            <div >
                <span title="Alt+Z">    <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="delFeedback()">删除留言</a></span>
                <span title="Alt+S">    <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="replyFeedback()">回复留言</a></span>
            </div>
        </div>
        <div style="height:350px;overflow: scroll">
            <table class="easyui-datagrid" toolbar="#toolbar" pagination="true"  singleSelect="true"  fitColumns="true"
                   url="../admin/tabPagesBehind/adminFeedbackB.php?action=1" method="post" rownumbers="true" id="feedbackTable" 

                   data-options="
                   onLoadSuccess:function(){  
                   $('.datagrid-row').each(
                   function(index){

                   if($(this).attr('datagrid-row-index')%2==1) {
                   $(this).addClass('oddRow');

                   $(this).mouseout(function(){  
                   $(this).addClass('oddRow');
                   }); 

                   $(this).mouseover(
                   function(){
                   $(this).removeClass('oddRow');
                   });   
                   }
                   }); 
                   },
                   onSelect:function(index, row) { 

                   $('#feedback_name').text(row.name);
                   $('#feedback_order_num').text(row.order_num);
                   $('#feedback_email').text(row.email);
                   $('#feedback_tel').text(row.tel);
                   $('#feedback_contents').text(row.contents);
                   }

                   ">
                <thead>
                    <tr>
                        <th data-options="field:'name',width:80">姓名</th>
                        <th data-options="field:'tel',width:100">电话</th>
                        <th data-options="field:'email',width:100">邮箱</th>
                        <th data-options="field:'order_num',width:100">订单号</th>
                        <th data-options="field:'contents',width:100">内容</th>
                        <th data-options="field:'created_date',width:80">创建时间</th>
                        <th data-options="field:'admin',width:100">操作人</th>
                        <th data-options="field:'reply_date',width:100">回复时间</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div>
            <div style="margin:10px;">
                <div style="width:24%;display:inline-block;float:left" ><span style="color:#8A2BE2">姓名：</span><span id="feedback_name" ></span></div>
                <div style="width:24%;display:inline-block;float:left"  ><span style="color:#8A2BE2">订单号：</span><span id="feedback_order_num" ></span></div>
                <div style="width:24%;display:inline-block;float:left"  ><span style="color:#8A2BE2">邮件：</span><span id="feedback_email"></span></div>
                <div style="width:24%;display:inline-block;"  ><span style="color:#8A2BE2">电话：</span><span id="feedback_tel"></span></div>
                <hr/>
            </div>

            <div style="margin: 10px">
                <span style="color:#8A2BE2">  内容：</span>
                <span id="feedback_contents">
                </span>
            </div>

        </div>


        <div id="feedback_dlg" class="easyui-dialog" style="width:900px;height:480px;padding:10px 20px"
             closed="true" buttons="#feedback_dlg_buttons">
            <form id="feedback_fm" method="post" >

                <div class="line"><div class="inputTitle">标题:</div><input style="width:769px" class="easyui-validatebox textbox" type="text" name="feedback_dlg_subject"  id = "feedback_dlg_subject" data-options="
                                                                          required:true,
                                                                          missingMessage:'标题不能为空'"></input></div>

                <div class="line"><div class="inputTitle">收件人:</div><input style="width:769px" class="easyui-validatebox textbox" type="text" name="feedback_dlg_receiver"  id = "feedback_dlg_receiver" data-options="
                                                                           required:true,
                                                                           missingMessage:'收件人不能为空'"></input></div>

                <div class="line">
                    <div>内容：</div>
                </div>
                <div style="text-align:center;">
                <textarea style="width:97%;" rows="15" id="feedback_dlg_content" name="feedback_dlg_content" class="easyui-validatebox" data-options="
                          required:true,
                          missingMessage:'内容不能为空'"></textarea>
                </div>


                <input type="hidden" name="feedback_dlg_id"  id = "feedback_dlg_id"></input>

            </form>
        </div> 

        <div id="feedback_dlg_buttons" style="text-align: center">
            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="reply()">确认</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#feedback_dlg').dialog('close');
                    dialogId = null;">取消</a>
        </div>    



        <script type="text/javascript">

            $('a').click(function() {
                checkTimeout();
            });

            function delFeedback() {
                var row = $('#feedbackTable').datagrid('getSelected');
                if (row) {
                    $.messager.confirm('Confirm', '您确定要删除这条留言吗?', function(r) {
                        if (r) {
                            $.post('../admin/tabPagesBehind/adminFeedbackB.php?action=3', {id: row.id}, function(result) {
                                if (result.success) {
                                    $('#feedbackTable').datagrid('reload');	// reload the user data
                                } else {
                                    $.messager.alert('错误', result.msg);
                                }
                            }, 'json');
                        }
                    });
                }
                else {
                    $.messager.alert('错误', "请您选择一行");
                }
            }


            function replyFeedback() {
                var row = $('#feedbackTable').datagrid('getSelected');
                if (row) {
                    $('#feedback_dlg').dialog({
                        title: '回复邮件',
                        modal: true
                    });
                    $('#feedback_fm').form('clear');
                    $("#feedback_dlg_receiver").val(row.email);
                    $("#feedback_dlg_id").val(row.id);
                    $('#feedback_dlg').dialog('open');

                    dialogId = "feedback_dlg";
                    saveFunction = "reply";


                }
                else {
                    $.messager.alert('错误', "请您选择一行");
                }
            }

            function reply() {
                $('#feedback_fm').form('submit', {
                    url: "../admin/tabPagesBehind/adminFeedbackB.php?action=2",
                    onSubmit: function() {
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');
                        if (result.success) {
                            $('#feedback_dlg').dialog('close');		// close the dialog
                            $('#feedbackTable').datagrid('reload');	// reload the user data
                        } else {
                            $.messager.alert('错误', result.msg);
                        }
                    }
                });
            }


        </script>
    </body>
</html>
