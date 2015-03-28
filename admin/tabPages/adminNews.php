<?php require ('tabPagesHeader.php') ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <div id="toolBar">
            <div >
                <span title="Alt+A">        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newNews()">添加新闻</a></span>
                <span title="Alt+S">    <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editNews()">编辑新闻</a> </span>
                <span title="Alt+Z">  <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeNews()">删除新闻</a> </span>
            </div>
        </div>

        <div style="height:350px;overflow: scroll">
            <table class="easyui-datagrid" toolbar="#toolbar" pagination="true"  singleSelect="true"  fitColumns="true"
                   url="../admin/tabPagesBehind/adminNewsB.php?action=1" method="post" rownumbers="true" id="newsTable" 

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

                   $('#news_title').text(row.subject);
                   $('#news_admin').text(row.created_admin);
                   $('#news_time').text(row.updated_date);
                   $('#news_show_content').text(row.content);
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
                <div style="width:50%;display:inline-block;float:left" ><span style="color:#8A2BE2">标题：</span> <span id="news_title" ></span></div>
                <div style="width:24%;display:inline-block;float:left"  ><span style="color:#8A2BE2">更新者：</span><span id="news_admin" ></span></div>
                <div style="width:24%;display:inline-block;"  ><span style="color:#8A2BE2">更新时间：</span><span id="news_time"></span></div>

                <hr/>
            </div>

            <div style="margin: 10px">
                <span style="color:#8A2BE2">   内容：</span>
                <span id="news_show_content">
                </span>
            </div>

        </div>

        <div id="news_dlg" class="easyui-dialog" style="width:900px;height:580px;padding:10px 20px"
             closed="true" buttons="#news_dlg-buttons">
            <form id="news_fm" method="post" >

                <div class="line"><div class="inputTitle">标题:</div><input style="width:769px" class="easyui-validatebox textbox" 
                                                                          type="text" name="news_subject"  id = "news_subject" data-options="
                                                                          required:true,
                                                                          missingMessage:'标题不能为空'"></input></div>

                <div class="line" style="width:100%;height:250px">
                    <div>内容：</div>
                    <textarea style="width:850px;" rows="15" id="news_content" name="news_content"></textarea>
                </div>



                <input type="hidden" name="news_id"  id = "news_id"></input>

            </form>
        </div> 

        <div id="news_dlg-buttons" style="text-align: center">
            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveNews()">确认</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearNews()">取消</a>
        </div>    



        <script type="text/javascript">
            var newsUrl;
        new nicEditor().panelInstance('news_content');

            $('a').click(function() {
                checkTimeout();
            });

            function newNews() {

                $('#news_dlg').dialog({
                    title: '添加新闻',
                    modal: true
                });

                $('#news_dlg').dialog('open');

                dialogId = "news_dlg";
                saveFunction = "saveNews";
                     nicEditors.findEditor('news_content').setContent('');
   
                $('#news_fm').form('clear');
                newsUrl = "../admin/tabPagesBehind/adminNewsB.php?action=2";
            }

            function clearNews() {
                 nicEditors.findEditor('news_content').setContent('');
                $('#news_dlg').dialog('close');
                dialogId = null;
            }

            function editNews() {
                var row = $('#newsTable').datagrid('getSelected');
                if (row) {

                    $('#news_dlg').dialog({
                        title: '添加新闻',
                        modal: true
                    });
                    $('#news_dlg').dialog('open');

                    dialogId = "news_dlg";
                    saveFunction = "saveNews";

                    $('#news_fm').form('clear');
                    $('#news_subject').val(row.subject);
                    nicEditors.findEditor('news_content').setContent(row.content);
                
                    $('#news_id').val(row.id);
                    rowIndex = $('#newsTable').datagrid('getRowIndex', row);
                    newsUrl = "../admin/tabPagesBehind/adminNewsB.php?action=4";
                }
                else {
                    $.messager.alert('错误', "请您选择一行");
                }
            }

            function saveNews() {
                   nicEditors.findEditor('news_content').saveContent();
                $('#news_fm').form('submit', {
                    url: newsUrl,
                    onSubmit: function() {
                        
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');
                        if (result.success) {
                            $('#news_dlg').dialog('close');		// close the dialog
                            $('#newsTable').datagrid('reload');	// reload the user data

                            $('#news_title').text("");
                            $('#news_admin').text("");
                            $('#news_time').text("");
                            $('#news_show_content').text("");
                            setTimeout("selectRow('#newsTable')", 500);
                        } else {
                            $.messager.alert('错误', result.msg);
                        }
                    }
                });
            }

            function removeNews() {
                var row = $('#newsTable').datagrid('getSelected');
                if (row) {
                    $.messager.confirm('Confirm', '您确定要删除这条新闻吗?', function(r) {
                        if (r) {
                            $.post('../admin/tabPagesBehind/adminNewsB.php?action=3', {id: row.id}, function(result) {
                                if (result.success) {
                                    $('#newsTable').datagrid('reload');	// reload the user data
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
