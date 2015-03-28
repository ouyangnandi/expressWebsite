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

        <div id="toolBar">
            <div >
                <span title="Alt+A">   <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newCard()">添加会员卡</a></span>
                <span title="Alt+Z">  <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="delCard()">删除会员卡</a></span>
                <span title="Alt+S">   <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="bindUser()">绑定会员卡</a></span>
            </div>
            <div>
                会员ID/卡号:<input  style="width:150px;margin:0 5px 0 5px" id="cardSearchBox"  textField="text"/>
                <span title="Alt+Q">  <a href="#" class="easyui-linkbutton" style="margin-bottom:5px;width:80px" iconCls="icon-search" onclick="searchCard()">查询</a></span>
                <span title="Alt+W">  <a href="#" class="easyui-linkbutton" style="margin-bottom:5px;width:80px" iconCls="icon-search" onclick="resetSearchCard()">重置查询</a></span>
            </div>
        </div>
        <table class="easyui-datagrid" toolbar="#toolbar" pagination="true"  singleSelect="true"  fitColumns="true"
               url="../admin/tabPagesBehind/adminCardB.php?action=1" method="post" rownumbers="true" id="cardTable" 

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
                    <th data-options="field:'id',width:80">卡ID</th>
                    <th data-options="field:'card_num',width:80">卡号</th>
                    <th data-options="field:'username',width:100">会员ID</th>
                    <th data-options="field:'card_type',width:100">卡类别</th>
                    <th data-options="field:'status',width:100">状态</th>
                    <th data-options="field:'active_date',width:100">激活时间</th>
                    <th data-options="field:'active_admin',width:100">激活人</th>
                    <th data-options="field:'created_date',width:100">创建时间</th>
                    <th data-options="field:'created_admin',width:100">创建人</th>
                </tr>
            </thead>
        </table>

        <div id="card_dlg" class="easyui-dialog" style="width:360px;padding:10px 20px"
             closed="true" buttons="#card_dlg-buttons">
            <form id="card_fm" method="post" >

                <div class="line"><div class="inputLine">卡号:</div><input class="easyui-validatebox textbox" type="text" name="card_num"  id = "card_num" data-options="
                                                                         required:true,
                                                                         missingMessage:'卡号不能为空',
                                                                         validType:{checkCardNumLength:[6]}"></input></div>

                <div class="line"><div class="inputLine">绑定用户账号:</div><input class="easyui-validatebox textbox" type="text" name="card_user_name" id="card_user_name">
                    </input></div>
                <div style="display:none;" class="line"><input type="checkbox" name="isGenerateUser" id="isGenerateUser"  value="Y" >是否自动生成用户
                    </input></div>

            </form>
        </div>
        <div id="card_dlg-buttons" style="text-align: center">
            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="checkCard()">确认</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#card_dlg').dialog('close');
                    dialogId = null;">取消</a>
        </div>


        <div id="bind_dlg" class="easyui-dialog" style="width:360px;padding:10px 20px"
             closed="true" buttons="#bind_dlg-buttons">
            <form id="bind_fm" method="post" >

                <div class="line"><div class="inputLine">卡ID:</div><input class="easyui-validatebox textbox" type="text" name="bindCardId" id="bindCardId" readonly style="background-color:#DEDEDE">
                    </input></div>
                <div class="line"><div class="inputLine">卡号:</div><input class="easyui-validatebox textbox" type="text" name="bindCardNum" id="bindCardNum" readonly style="background-color:#DEDEDE">
                    </input></div>
                <div class="line"><div class="inputLine">绑定用户账号:</div><input class="easyui-validatebox textbox" type="text" name="bindUserName" id="bindUserName">
                    </input></div>
                <input  type="hidden" name="bindCardTypeId" id="bindCardTypeId">
                </input>

            </form>
        </div>
        <div id="bind_dlg-buttons" style="text-align: center">
            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="bindCard()">确认</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#bind_dlg').dialog('close');
                    dialogId = null;">取消</a>
        </div>


        <script type="text/javascript">

            $('a').click(function() {
                checkTimeout();
            });

            $('#isGenerateUser').change(function() {
                if ($('#isGenerateUser').prop('checked')) {
                    $('#card_user_id').val('');
                    $('#card_user_id').attr('readonly', true);
                    $('#card_user_id').css('background-color', '#DEDEDE');
                }
                else {
                    $('#card_user_id').val('');
                    $('#card_user_id').attr('readonly', false);
                    $('#card_user_id').css('background-color', 'white');
                }

            });

            $.extend($.fn.validatebox.defaults.rules, {
                checkCardNumLength: {
                    validator: function(value, param) {
                        return value.length >= param[0] && $.isNumeric(value);
                    },
                    message: '卡号至少是6位以上的整数'
                }
            });



            function newCard() {
                $('#card_dlg').dialog({
                    title: '添加新卡',
                    modal: true
                });
                $('#card_dlg').dialog('open');
                dialogId = "card_dlg";
                saveFunction = "checkCard";

                $('#card_fm').form('clear');
                $('#card_type_id').val("普通卡");
                getCardInfo();
            }

            function checkCard() {
                $('#card_fm').form('submit', {
                    url: '../admin/tabPagesBehind/adminCardB.php?action=4',
                    onSubmit: function() {
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');

                        if (result.success) {
                            if ($('#isGenerateUser').prop('checked')) {
                                saveCard(2);  //创建新用户
                            }
                            else {
                                saveCard(0);
                            }
                        }
                        else if (result.msg == "1") {

                            $.messager.confirm('Confirm', '该用户已经绑定了另外一张卡，如果操作继续，另外一张卡将作废，请问您继续操作吗?', function(r) {
                                if (r) {
                                    $('#card_type_id').val(result.msg);
                                    saveCard(1); //废除其它卡
                                }
                            });
                        }
                        else if (result.msg == "3") {
                            saveCard(3);
                        }

                        else {
                            $.messager.alert('错误', result.msg);
                        }
                    }
                });
            }

            function saveCard(actionType) {
                $('#card_fm').form('submit', {
                    url: '../admin/tabPagesBehind/adminCardB.php?action=2&type=' + actionType,
                    onSubmit: function() {
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');

                        if (result.success) {

                            $('#card_dlg').dialog('close');		// close the dialog
                            $('#cardTable').datagrid('reload');	// reload the card data

                            if (actionType == "2") {
                                $('#userTable').datagrid('reload');
                            }
                        }

                        else {
                            $.messager.alert('错误', result.msg);
                        }
                    }
                });
            }


            function delCard() {
                var row = $('#cardTable').datagrid('getSelected');
                if (row) {

                    if (row.status == "已激活") {
                        $.messager.alert('错误', "该卡已经绑定了用户，不能删除");
                        return;
                    }

                    $.messager.confirm('Confirm', '您确定要删除这张卡吗?', function(r) {
                        if (r) {
                            $.post('../admin/tabPagesBehind/adminCardB.php?action=3', {card_id: row.id}, function(result) {
                                if (result.success) {
                                    $('#cardTable').datagrid('reload');	// reload the user data
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


            function bindUser() {

                var row = $('#cardTable').datagrid('getSelected');

                if (row) {

                    if (row.status != "未激活") {
                        $.messager.alert('错误', "该卡已经绑定了用户");
                        return;
                    }
                    $('#bind_dlg').dialog({
                        title: '绑定用户',
                        modal: true
                    });
                    $('#bind_dlg').dialog('open');

                    dialogId = "bind_dlg";
                    saveFunction = "bindCard";

                    $('#bind_fm').form('clear');
                    $('#bindCardId').val(row.id);
                    $('#bindCardNum').val(row.card_num);
                    rowIndex = $('#cardTable').datagrid('getRowIndex', row);
                }
                else {
                    $.messager.alert('错误', "请您选择一行");
                }
            }

            function bindCard() {

                $('#bind_fm').form('submit', {
                    url: '../admin/tabPagesBehind/adminCardB.php?action=5',
                    onSubmit: function() {
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');

                        if (result.success) {

                            $('#bind_dlg').dialog('close');		// close the dialog
                            $('#cardTable').datagrid('reload');
                            $('#userTable').datagrid('reload');
                            setTimeout("selectRow('#cardTable')", 500);
                        }

                        else if ($.isNumeric(result.msg)) {
                            $.messager.confirm('Confirm', '该用户已经绑定了另外一张卡，如果操作继续，另外一张卡将作废，请问您继续操作吗?', function(r) {
                                if (r) {
                                    $('#bindCardTypeId').val(result.msg);
                                    transferCard();

                                }
                            });
                        }
                        else {
                            $.messager.alert('错误', result.msg);
                        }
                    }
                });

            }

            function transferCard() {

                $('#bind_fm').form('submit', {
                    url: '../admin/tabPagesBehind/adminCardB.php?action=6',
                    onSubmit: function() {
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');

                        if (result.success) {

                            $('#bind_dlg').dialog('close');		// close the dialog
                            $('#cardTable').datagrid('reload');
                            $('#userTable').datagrid('reload');
                            setTimeout("selectRow('#cardTable')", 500);
                        }
                        else {
                            $.messager.alert('错误', result.msg);
                        }
                    }
                });


            }

            function searchCard() {

                $('#cardTable').datagrid('load', {
                    searchCard: $("#cardSearchBox").val()
                });

            }

            function resetSearchCard() {
                $('#cardTable').datagrid('load', {
                    searchCard: ""
                });

                $("#cardSearchBox").val("");
            }

            function getCardInfo() {
                $.post('../admin/tabPagesBehind/adminCardB.php?action=7', function(data) {
                    $('#card_type_id').empty();
                    for (var i in data) {
                        $('#card_type_id').append("<option value='" + data[i].id + "'>" + data[i].name + "</option>");
                    }
                }, 'json');

            }
        </script>
    </body>
</html>

