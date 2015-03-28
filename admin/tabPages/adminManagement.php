<html>
    <head>
        <?php require ('tabPagesHeader.php') ?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="../../css/themes/default/easyui.css"/>
        <link rel="stylesheet" type="text/css" href="../../css/themes/icon.css"/>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.easyui-cn.js" charset="utf-8"></script>
        <style>

            html { height: 100%; }
            body { min-height: 100%; }

        </style>
    </head>
    <body >

        <div id="toolBar">
            <div >
                <span title="Alt+A">  <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newAdmin()" >添加管理员</a></span>
                <span title="Alt+Z">   <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeAdmin()">删除管理员</a></span>|
                <a href="#" class="easyui-linkbutton" iconCls="icon-key" plain="true" onclick="initPass()">初始化密码</a>
            </div>
            <div>
                管理员账号:<input  style="width:150px;margin:0 5px 0 5px" id="adminSearchBox"  textField="text"/>
                <span title="Alt+Q">   <a href="#" class="easyui-linkbutton" style="margin-bottom:5px;width:80px" iconCls="icon-search" onclick="searchAdmin()">查询</a></span>
                <span title="Alt+W">   <a href="#" class="easyui-linkbutton" style="margin-bottom:5px;width:80px" iconCls="icon-search" onclick="resetSearchAdmin()">重置查询</a></span>
            </div>
        </div>

        <table class="easyui-datagrid" toolbar="#toolbar" pagination="true"  singleSelect="true"  fitColumns="true"
               url="../admin/tabPagesBehind/adminManagementB.php?action=1" method="post" rownumbers="true" id="adminTable" 

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
               }
               ">
            <thead>
                <tr>
                    <th data-options="field:'username',width:80">管理员账号</th>
                    <th data-options="field:'role',width:100">权限</th>
                    <th data-options="field:'created_date',width:100">创建时间</th>
                    <th data-options="field:'created_admin',width:100">创建人</th>
                </tr>
            </thead>
        </table>

        <div id="management_dlg" class="easyui-dialog" style="width:360px;height:240px;padding:10px 20px"
             closed="true" buttons="#management_dlg-buttons">
            <form id="management_fm" method="post" >

                <div class="line"><div class="inputLine" >账号:</div><input class="easyui-validatebox textbox" type="text" name="management_username"  id = "management_username" data-options="
                                                                         required:true,
                                                                         missingMessage:'账号不能为空',
                                                                         validType:{checkUsernameLength:[6]}"></input></div>

                <div class="line"><div class="inputLine">权限:</div> <select style="width:156px;margin-left: -6px;height:20px" id="management_role" name="management_role">
                        <option value="普通管理员" >普通管理员</option>
                        <option value="超级管理员">超级管理员</option>
                    </select>
                </div>


                <div class="line"><div class="inputLine">密码:</div><input class="easyui-validatebox textbox" type="password" name="management_password" id="management_password" data-options="
                                                                         required:true,
                                                                         missingMessage:'密码不能为空',
                                                                         validType:{checkLength:[6]}">
                    </input></div>

                <div class="line"><div class="inputLine">密码确认:</div><input class="easyui-validatebox textbox" type="password" id= "management_confirmPass" name="management_confirmPass" data-options="
                                                                           required:true,
                                                                           missingMessage:'密码不能为空',
                                                                           validType:{
                                                                           checkLength:[6],
                                                                           equals:['#management_password']
                                                                           }">
                    </input></div>
            </form>
        </div>
        <div id="management_dlg-buttons" style="text-align: center">
            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveAdmin()">确认</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#management_dlg').dialog('close');
                    dialogId = null">取消</a>
        </div>

        <script type="text/javascript">

            $('a').click(function() {
                checkTimeout();
            });

            $.extend($.fn.validatebox.defaults.rules, {
                checkLength: {
                    validator: function(value, param) {
                        return value.length >= param[0];
                    },
                    message: '密码至少 有6个字母'
                }
            });
            $.extend($.fn.validatebox.defaults.rules, {
                checkUsernameLength: {
                    validator: function(value, param) {
                        return value.length >= param[0];
                    },
                    message: '账号至少有6个字母'
                }
            });


            $.extend($.fn.validatebox.defaults.rules, {
                equals: {
                    validator: function(value, param) {
                        return value == $(param[0]).val();
                    },
                    message: '两次密码输入必须一致'
                }
            });

            function newAdmin() {
                $('#management_dlg').dialog({
                    title: '添加管理员',
                    modal: true
                });
                $('#management_dlg').dialog('open');

                dialogId = "management_dlg";
                saveFunction = "saveAdmin";

                $('#management_fm').form('clear');
                $('#management_role').val("普通管理员");
            }


            function saveAdmin() {
                $('#management_fm').form('submit', {
                    url: '../admin/tabPagesBehind/adminManagementB.php?action=2',
                    onSubmit: function() {
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');
                        if (result.success) {
                            $('#management_dlg').dialog('close');		// close the dialog
                            $('#adminTable').datagrid('reload');	// reload the user data
                        } else {
                            $.messager.alert('错误', result.msg);
                        }
                    }
                });
            }

            function removeAdmin() {
                var row = $('#adminTable').datagrid('getSelected');
                if (row) {
                    $.messager.confirm('Confirm', '您确定要删除这个账号吗?', function(r) {
                        if (r) {
                            $.post('../admin/tabPagesBehind/adminManagementB.php?action=3', {id: row.id, username: row.username}, function(result) {
                                if (result.success) {
                                    $('#adminTable').datagrid('reload');	// reload the user data
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

            function initPass() {

                var row = $('#adminTable').datagrid('getSelected');
                if (row) {

                    $.messager.confirm('Confirm', '您确定要初始化这个用户密码吗?', function(r) {
                        if (r) {
                            $.post('../admin/tabPagesBehind/adminManagementB.php?action=4', {id: row.id}, function(result) {
                                if (result.success) {
                                    $.messager.alert('密码初始化', result.msg);
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

            function searchAdmin() {
                $('#adminTable').datagrid('load', {
                    username: $("#adminSearchBox").val()
                });

            }

            function resetSearchAdmin() {
                $('#adminTable').datagrid('load', {
                    username: ""
                });
                $("#adminSearchBox").val("");
            }
        </script>
    </body>
</html>

