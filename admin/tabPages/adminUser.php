<?php require ('tabPagesHeader.php') ?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../../css/themes/default/easyui.css"/>
        <link rel="stylesheet" type="text/css" href="../../css/themes/icon.css"/>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>

    </head>
    <body >

        <div id="user_toolBar">
            <div >
                <!-- <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="activeUserCard()">激活会员卡</a> -->
                <span title="Alt+A">   <a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="watchUser()">查看会员</a></span>
                <span title="Alt+Z">   <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="delUser()">删除会员</a></span>
                |
                <span title="Alt+S">   <a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="deliveryCard()">绑定会员卡</a></span>
                |
                <a href="#" class="easyui-linkbutton" iconCls="icon-key" plain="true" onclick="initUserPass()">初始化密码</a>
            </div>
            <div>
                是否发卡:        <select value="2" id="userStatus">
                    <option value="2" selected="true">所有</option>
                    <option value="1"> 已经发卡</option>
                    <option value="0">未发卡</option>
                </select>
                会员账号/会员ID/会员Email/卡号:<input  style="width:150px;margin:0 5px 0 5px" id="searchUserBox"  textField="text"/>
                <span title="Alt+Q">    <a href="#" class="easyui-linkbutton" style="margin-bottom:5px;width:80px" iconCls="icon-search" onclick="searchUser()">查询</a></span>
                <span title="Alt+W">    <a href="#" class="easyui-linkbutton" style="margin-bottom:5px;width:80px" iconCls="icon-search" onclick="resetSearchUser()">重置查询</a></span>
            </div>
        </div>
        <table class="easyui-datagrid" toolbar="#user_toolbar" pagination="true"  singleSelect="true"  fitColumns="true"
               url="../admin/tabPagesBehind/adminUserB.php?action=1" method="post" rownumbers="true" id="userTable" 
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
                    <th data-options="field:'id',width:80">会员ID</th>
                    <th data-options="field:'username',width:100">会员账号</th>
                    <th data-options="field:'name',width:100">真实姓名</th>
                    <th data-options="field:'email',width:100">电子邮件</th>
                    <th data-options="field:'credits',width:100">积分</th>
                    <th data-options="field:'status',width:100">状态</th>
                    <th data-options="field:'card_num',width:100">卡号</th>
                    <th data-options="field:'created_date',width:100">注册时间</th>
                    <th data-options="field:'active_admin',width:100">激活人</th>
                </tr>
            </thead>
        </table>


        <div id="user_details_dlg" class="easyui-dialog" style="width:600px;padding:10px 20px"
             closed="true" buttons="user_details_buttons">
            <div style="text-align:center"> <h3>客户详情</h3> </div>
            <div class="subtitle"> 账户信息:</div>
            <hr/>
            <table class="detailsTable"> 
                <tr><td class="textColor">会员ID:</td><td id="details_id"></td><td class="textColor">账户名:</td><td id="details_username"></td></tr>
                <tr><td class="textColor">积分:</td><td id="details_credits"></td><td class="textColor">状态:</td><td id="details_status"></td></tr>
                <tr><td class="textColor">绑定卡号:</td><td colspan=3 id="details_card_num"></td></tr>
                <tr><td class="textColor">激活人:</td><td id="details_active_admin"></td><td class="textColor">注册时间:</td><td id="details_created_time"></td></tr>
            </table>
            <br/>
            <div class="subtitle">个人信息:</b></h4></div>
            <hr/>
            <table class="detailsTable"> 
                <tr><td class="textColor">姓名:</td><td id="details_name"></td><td class="textColor">性别:</td><td id="details_gender"></td></tr>
                <tr ><td class="textColor">年龄:</td><td id="details_age"></td><td class="textColor">教育程度:</td><td id="details_edu"></td></tr>
                  <tr ><td class="textColor">大学:</td><td id="details_uni"></td><td class="textColor">公司:</td><td id="details_firm"></td></tr>
                <tr><td class="textColor">兴趣爱好:</td></tr>
                <tr><td colspan=4 id="details_interest"></td></tr>
            </table>
            <br/>
            <div class="subtitle">联系方式:</b></h4></div>
            <hr/>
            <table class="detailsTable"> 
                <tr><td class="textColor">邮箱:</td><td id="details_email"></td><td class="textColor">电话:</td><td id="details_tel"></td></tr>
                <tr><td class="textColor">地址:</td></tr>
                <tr><td colspan=4 id="details_address"></td></tr>
            </table>
            <br/>

            <div class="subtitle">备注</b></h4></div>
            <hr/>
            <p style="font-size:12px" id="details_notes">
            </p>

            <div id="user_details_buttons" style="text-align: center">
                <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="closeWatchUser()">确定</a>
            </div>
        </div>


        <div id="delivery_dlg" class="easyui-dialog" style="width:360px;padding:10px 20px"
             closed="true" buttons="#delivery_dlg-buttons">
            <form id="delivery_fm" method="post" >

                <div class="line"><div class="inputLine">卡号:</div><input class="easyui-validatebox textbox" type="text" name="deliveryCardNum" id="deliveryCardNum" >
                    </input></div>
                <div class="line"><div class="inputLine">绑定用户ID:</div><input class="easyui-validatebox textbox" type="text" name="deliveryUserId" id="deliveryUserId" readonly style="background-color:#DEDEDE">
                    </input></div>
                <input  type="hidden" name="deliveryAction" id="deliveryAction" value="1">
                </input>
            </form>
        </div>
        <div id="delivery_dlg-buttons" style="text-align: center">
            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="deliverySaveCard()">确认</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#delivery_dlg').dialog('close');dialogId=null">取消</a>
        </div>



        <script type="text/javascript">
            function delUser() {
                var row = $('#userTable').datagrid('getSelected');
                if (row) {
                    $.messager.confirm('Confirm', '删除这个用户，会让绑定用户的卡作废，您确定要删除这个用户吗?', function(r) {
                        if (r) {
                            $.post('../admin/tabPagesBehind/adminUserB.php?action=3', {id: row.id}, function(result) {
                                if (result.success) {
                                    $('#userTable').datagrid('reload');	// reload the user data
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
          
          function closeWatchUser(){
              $('#user_details_dlg').dialog('close');
              dialogId=null;
          }

            function watchUser() {
                var row = $('#userTable').datagrid('getSelected');
                if (row) {
                    $('#user_details_dlg').dialog({
                        title: '客户信息',
                        modal: true
                    });
                    $("#details_id").text(row.id);
                    $("#details_username").text(row.username);
                    $("#details_credits").text(row.credits);
                    $("#details_status").text(row.status);
                    $("#details_card_num").text(row.card_num);
                    $("#details_active_admin").text(row.active_admin);
                    $("#details_created_time").text(row.created_date);
                    $("#details_name").text(row.name);
                    $("#details_gender").text(row.gender);
                    $("#details_age").text(row.age);
                    $("#details_edu").text(row.education);
                    $("#details_uni").text(row.university);
                    $("#details_firm").text(row.firm);
                    $("#details_interest").text(row.interest);
                    $("#details_email").text(row.email);
                    $("#details_tel").text(row.tel);
                    $("#details_address").text(row.address);
                    $("#details_notes").text(row.notes);

                    $('#user_details_dlg').dialog('open');
                        
                    dialogId="user_details_dlg";
                    saveFunction="closeWatchUser";
                }
                else {
                    $.messager.alert('错误', "请您选择一行");
                }
            }

            function initUserPass() {
                var row = $('#userTable').datagrid('getSelected');
                if (row) {
                    $.messager.confirm('Confirm', '您确定要初始化这个用户密码吗?', function(r) {
                        if (r) {
                            $.post('../admin/tabPagesBehind/adminUserB.php?action=4', {id: row.id, username:row.username, email:row.email}, function(result) {
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

            function activeUserCard() {

                alert("不知道业务逻辑");
            }

            function deliverySaveCard() {
                $('#delivery_fm').form('submit', {
                    url: '../admin/tabPagesBehind/adminUserB.php?action=2',
                    onSubmit: function() {
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');

                        if (result.success) {
                            $('#delivery_dlg').dialog('close');		// close the dialog
                            $('#cardTable').datagrid('reload');	// reload the card data
                            $('#userTable').datagrid('reload');	// reload the card data
                           setTimeout("selectRow('#userTable')", 500);
                        }
                        else {
                            $.messager.alert('错误', result.msg);
                        }
                    }
                });
            }

            function deliveryCard() {
                var row = $('#userTable').datagrid('getSelected');
                
                if (row) {
                    if (row.status == "未绑定卡") {

                        $('#delivery_dlg').dialog({
                            title: '绑定新卡',
                            modal: true
                        });

                        $('#delivery_dlg').dialog('open');
                        dialogId="delivery_dlg";
                        saveFunction=null;
                        
                        $('#delivery_fm').form('clear');
                        $('#deliveryAction').val("1");
                        $('#deliveryUserId').val(row.id);
                          rowIndex = $('#userTable').datagrid('getRowIndex',row);
                    }
                    else {
                        $.messager.confirm('Confirm', '该用户已经绑定了一张卡，如果操作继续，该卡将作废，请问您继续操作吗?', function(r) {
                            if (r) {
                                $('#delivery_dlg').dialog({
                                    title: '绑定新卡',
                                    modal: true
                                });

                                $('#delivery_dlg').dialog('open');
                                dialogId="delivery_dlg";
                                saveFunction="deliverySaveCard";
                                
                                $('#delivery_fm').form('clear');
                                $('#deliveryUserId').val(row.id);
                                $('#deliveryAction').val("2");
                                  rowIndex = $('#userTable').datagrid('getRowIndex',row);
                            }
                        });
                    }
                }
                else {
                    $.messager.alert('错误', "请您选择一行");
                }
            }

            function searchUser() {
                $('#userTable').datagrid('load', {
                    searchUser: $("#searchUserBox").val(),
                    searchUserStatus: $("#userStatus").val()
                });
            }
            ;

            function resetSearchUser() {
                $('#userTable').datagrid('load', {
                    searchUser: "",
                    searchUserStatus: ""
                });
                $("#searchUserBox").val("");
                $("#userStatus").val("");
            }
        </script>
    </body>
</html>

