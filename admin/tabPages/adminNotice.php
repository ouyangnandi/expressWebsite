<?php require ('tabPagesHeader.php') ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <div id="toolBar">
            <div >
                <span title="Alt+A">    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newNotice()">添加公告</a></span>
                <span title="Alt+S">   <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editNotice()">编辑公告</a> </span>
                <span title="Alt+Z">   <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeNotice()">删除公告</a> </span>
            </div>
        </div>
        <div style="height:350px;overflow: scroll">
            <table class="easyui-datagrid" toolbar="#toolbar" pagination="true"  singleSelect="true"  fitColumns="true"
                   url="../admin/tabPagesBehind/adminNoticeB.php?action=1" method="post" rownumbers="true" id="noticeTable" 

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
                   autoRowHeight:false,
                   onSelect:function(index, row) { 
                   $('#notice_title').text(row.subject);
                   $('#notice_admin').text(row.created_admin);
                   $('#notice_time').text(row.updated_date);
                   $('#notice_show_content').text(row.content);
                   }
                   ">
                <thead>
                    <tr>
                        <th data-options="field:'subject',width:80">标题</th>
                        <th data-options="field:'content',width:300,fixed:true">内容</th>
                        <th data-options="field:'created_admin',width:100">更新者</th>
                        <th data-options="field:'updated_date',width:100">更新时间</th>
                        <th data-options="field:'page_view',width:100">浏览量</th>
                    </tr>
                </thead>
            </table>
        </div>

        <div>
            <div style="margin:10px;">
                <div style="width:50%;display:inline-block;float:left" ><span style="color:#8A2BE2">标题：</span> <span id="notice_title" ></span></div>
                <div style="width:24%;display:inline-block;float:left"  ><span style="color:#8A2BE2">更新者：</span><span id="notice_admin" ></span></div>
                <div style="width:24%;display:inline-block;"  ><span style="color:#8A2BE2">更新时间：</span><span id="notice_time"></span></div>

                <hr/>
            </div>

            <div style="margin: 10px">
                <span style="color:#8A2BE2">  内容：</span>
                <span id="notice_show_content">
                </span>
            </div>

        </div>





        <div id="notice_dlg" class="easyui-dialog" style="width:900px;height:580px;padding:10px 20px"
             closed="true" buttons="#notice_dlg-buttons">
            <form id="notice_fm" method="post" >

                <div class="line"><div class="inputTitle">标题:</div><input style="width:769px" class="easyui-validatebox textbox" type="text" 
                                                                          name="notice_subject"  id = "notice_subject" data-options="
                                                                          required:true,
                                                                          missingMessage:'标题不能为空'"></input></div>

                <div class="line" style="width:100%;height:250px">
                    <div>内容：</div>
                    <textarea style="width:850px;" rows="15" id="notice_content" name="notice_content"></textarea>
                </div>



                <input type="hidden" name="notice_id"  id = "notice_id"></input>

            </form>
        </div> 

        <div id="notice_dlg-buttons" style="text-align: center">
            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveNotice()">确认</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearNotice()">取消</a>
        </div>    



        <script type="text/javascript">
            var noticeUrl;
           new nicEditor().panelInstance('notice_content');
            $('a').click(function() {
                checkTimeout();
            });


            function newNotice() {
                $('#notice_dlg').dialog({
                    title: '添加公告',
                    modal: true
                });

                $('#notice_dlg').dialog('open');

                dialogId = "notice_dlg";
                saveFunction = "saveNotice";
                                 nicEditors.findEditor('notice_content').setContent('');
                $('#notice_fm').form('clear');
                noticeUrl = "../admin/tabPagesBehind/adminNoticeB.php?action=2";
            }
            ;

            function editNotice() {
                var row = $('#noticeTable').datagrid('getSelected');
                if (row) {
                    $('#notice_dlg').dialog({
                        title: '编辑公告',
                        modal: true
                    });

                    $('#notice_dlg').dialog('open');

                    dialogId = "notice_dlg";
                    saveFunction = "saveNotice";

                    $('#notice_fm').form('clear');
                    $('#notice_subject').val(row.subject);
                       nicEditors.findEditor('notice_content').setContent(row.content);
                    $('#notice_id').val(row.id);
                    rowIndex = $('#noticeTable').datagrid('getRowIndex', row);
                    noticeUrl = "../admin/tabPagesBehind/adminNoticeB.php?action=4";
                }
                else {
                    $.messager.alert('错误', "请您选择一行");
                }
            }

            function saveNotice() {
                   nicEditors.findEditor('notice_content').saveContent();
                $('#notice_fm').form('submit', {
                    url: noticeUrl,
                    onSubmit: function() {
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');
                        if (result.success) {
                            $('#notice_dlg').dialog('close');		// close the dialog
                            $('#noticeTable').datagrid('reload');	// reload the user data

                            $('#notice_title').text("");
                            $('#notice_admin').text("");
                            $('#notice_time').text("");
                            $('#notice_show_content').text("");
                            setTimeout("selectRow('#noticeTable')", 500);

                        } else {
                            $.messager.alert('错误', result.msg);
                        }

                    }
                });
            }
            
            function clearNotice(){
                nicEditors.findEditor('notice_content').setContent('');
                 $('#notice_dlg').dialog('close');
                    dialogId = null;
            }

            function removeNotice() {
                var row = $('#noticeTable').datagrid('getSelected');
                if (row) {
                    $.messager.confirm('Confirm', '您确定要删除这条公告吗?', function(r) {
                        if (r) {
                            $.post('../admin/tabPagesBehind/adminNoticeB.php?action=3', {id: row.id}, function(result) {
                                if (result.success) {
                                    $('#noticeTable').datagrid('reload');	// reload the user data


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
        </script>
    </script>
</body>
</html>

