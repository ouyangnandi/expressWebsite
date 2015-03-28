<?php
session_start();
if (isset($_SESSION['username'])) {
    
} else {
    header("Location: ../frontend/userLogin.html");
}
?>
<html>
    <head>
        <title>账户信息</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../../css/main.css" />
        <link rel="stylesheet" type="text/css" href="../../css/themes/default/easyui.css"/>
        <link rel="stylesheet" type="text/css" href="../../css/themes/icon.css"/>

        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
         <script type="text/javascript" src="../../js/jquery.easyui-cn.js" charset="utf-8"></script>

    </head>
    <body>

        <div id="headerContent"></div>


        <div style="background-color:#EEEEEE;padding-top:14px;">
            <div style="width:984px;margin:0 auto;">
                <div style="text-align:right;"><a href="#" class="accoutExit" onclick="logout();">退出</a></div>
                <div class="dashline" style="margin:0;height:15px"></div>


            </div>
            <div id="mainDiv" style="width:984px;margin:0 auto;background-color:white;">

                <div style="height:22px;width:100%"></div>
                <div style="text-align:center; font-family:微软雅黑;"><span><h3 style="font-weight:normal;color:#3c4980">账户信息</h3></span></div>
                <div style="height:20px;width:100%"></div>
                <div style="margin:0 50px" style="text-align: center"> <img src="../../imgs/gredient_account.png" width="900"/></div>
                <div style="margin:0 70px" class="accountLinks"><a href="account.php"> <img src="../../imgs/account_info.png" /> </a><a href="accountPass.php"><img src="../../imgs/account_pass.png" /> </a><a href="historyOrder.php"><img src="../../imgs/account_order.png" /></a></div>
                <div class="dashline" style="margin:0 70px;height:30px"></div>
                <div  style="margin:0 70px;height:25px;text-align: center;"><hr  color="#828282" style="margin: 0px;"/>
                    <div style="font-family:微软雅黑;background-color:white;width:100px;text-align: center;position:relative;bottom:12px;display:inline-block;"><h4 style="margin:0px;" >详细信息</h4></div>
                </div>

                <div style="margin:30px 70px 40px 70px;height:120px;background-color: #F7F7F7">

                    <div style="height:25px"></div>
                    <div style="margin:0 15px;text-align: center;height:25px;"><div class="accountDetail" style="width:50%"><div >帐号</div><div id="username"></div></div><div class="accountDetail" style="width:50%"><div>注册时间</div><div id="created_date" style="width:150px"></div></div></div>
                    <div style="height:25px"></div>
                    <div style="margin:0 15px;text-align: center;height:25px;"><div class="accountDetail" style="width:50%"><div >邮箱</div><span id="email"></span></div><div class="accountDetail" style="width:50%"><div>积分</div><span id="credits"></span><button style="margin-left:15px;" onclick="deliveryCard()">绑定卡</button></div></div>
                    <div style="height:25px"></div>

                </div>

                <div  style="margin:0 70px;height:25px;text-align: center;"><hr  color="#828282" style="margin: 0px;"/>
                    <div style="font-family:微软雅黑;background-color:white;width:150px;text-align: center;position:relative;bottom:12px;display:inline-block;"><h4 style="margin:0px;" >请完善以下信息</h4></div>
                </div>


                <div style="margin:0px 70px 40px 70px">
                    <form id="info" method="post">
                    <div style="height:25px"></div>
                    <div style="text-align: center;height:35px; vertical-align: middle;"><div class="accountDetail" style="width:50%;"><span class="accountSpan">姓名</span><input type="text" id="name" name="name" class="accountInput"/></div><div class="accountDetail" style="width:50%"><span class="accountSpan">性别</span><select style="width:100px" name="sex" id="sex">
                                                                                                                                                                                                                                                                                                            <option value="0">保密</option>
                                                                                                                                                                                                                                                                                                            <option value="1">男</option>
                                                                                                                                                                                                                                                                                                            <option value="2">女</option>
                                                                                                                                                                                                                                                                                                                 </select></div></div>
                    <div style="height:25px"></div>
                    <div style="text-align: center;height:35px; vertical-align: middle;"><div class="accountDetail" style="width:50%;"><span class="accountSpan">电话</span><input id="tel" name="tel" type="text" class="accountInput"/></div><div class="accountDetail" style="width:50%"><span class="accountSpan">学历</span><input id="edu" name="edu" type="text" class="accountInput"/></div></div>
                    <div style="height:25px"></div>
                    <div style="text-align: center;height:35px; vertical-align: middle;"><div class="accountDetail" style="width:50%;"><span class="accountSpan">公司</span><input id="company" name="company" type="text" class="accountInput"/></div><div class="accountDetail" style="width:50%"><span class="accountSpan">爱好</span><input id="habit" name="habit" type="text" class="accountInput"/></div></div>
                    <div style="height:25px"></div>
                    <div style="text-align: right"> <img src="../../imgs/account_save.png" onclick="update();" style="cursor: pointer"/></div>
                    </form>
                </div>

                <div style="height:10px"></div>
                <div id="bottomContent"> </div>
            </div>
            
              <div id="delivery_dlg" class="easyui-dialog" style="width:360px;padding:10px 20px"
             closed="true" buttons="#delivery_dlg-buttons">
            <form id="delivery_fm" method="post" >

                <div class="line"><div class="inputLine">卡号:</div><input class="easyui-validatebox textbox" type="text" name="deliveryCardNum" id="deliveryCardNum" >
                    </input></div>
                <div  style="display:none"><div class="inputLine">绑定用户ID:</div><input class="easyui-validatebox textbox" type="text" name="deliveryUserId" id="deliveryUserId" readonly style="background-color:#DEDEDE">
                    </input></div>
                <input  type="hidden" name="deliveryAction" id="deliveryAction" value="1">
                </input>
            </form>
        </div>
        <div id="delivery_dlg-buttons" style="text-align: center">
            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="deliverySaveCard()">确认</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#delivery_dlg').dialog('close');dialogId=null">取消</a>
        </div>




            <script type="text/javascript" >
                var bindCard = false;
                var userId = null;
                $(document).ready(function() {
                    $('#headerContent').load('../../header.html');
                    $('#bottomContent').load('../../bottom.html');


                    $.post('../backend/accountB.php?action=1', function(result) {

                        var result = eval('(' + result + ')');

                        if (result.status == 1) {
                            var row = result.info;
                            $("#username").text(row.username);
                            $("#created_date").text(row.created_date);
                            $("#email").text(row.email);

                            if (row .card_num == null) {
                                $("#credits").text("未绑定会员卡");
                                bindCard = false;
                            }
                            else {
                                $("#credits").text(row.credits + " (卡号: " +row.card_num+")" );
                                bindCard = true;
                            }
                                userId = row.id;
                                $("#name").val(row.name);
                                $("#tel").val(row.tel);
                                $("#edu").val(row.education);
                                $("#habit").val(row.interest);
                                $('#sex option[value = "'+row.gender+'"]').prop('selected', true);
                                $("#company").val(row.firm);
   
                        } else {
                            $.messager.alert('错误', "网站内部错误，请联系管理员");

                        }
                    });
                });
                
                function update(){
                         $('#info').form('submit', {
                    url: '../backend/accountB.php?action=2',
                    onSubmit: function() {
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');
                        if (result.status==1) {
                             $.messager.alert('成功', "修改成功");
                        }
                        else if (result.status==2) {
                             $.messager.alert('错误', "网站出现错误，请联系管理员");
                        }
                    }
                });
                }
                

                function logout() {

                    $.post('../backend/userLogin.php?action=1',
                            function() {

                                location.href = "../../index.html";
                            });

                }
                
                    function deliveryCard() {
                    if ( bindCard == false ) {

                        $('#delivery_dlg').dialog({
                            title: '绑定新卡',
                            modal: true
                        });

                        $('#delivery_dlg').dialog('open');
                        dialogId="delivery_dlg";
                        
                        $('#delivery_fm').form('clear');
                        $('#deliveryAction').val("1");
                        $('#deliveryUserId').val(userId);
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
                                
                                $('#delivery_fm').form('clear');
                                $('#deliveryUserId').val(userId);
                                $('#deliveryAction').val("2");
                            }
                        });
                    }
            }
            
                        function deliverySaveCard() {
                $('#delivery_fm').form('submit', {
                    url: '../../admin/tabPagesBehind/adminUserB.php?action=2',
                    onSubmit: function() {
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');

                        if (result.success) {
                            $('#delivery_dlg').dialog('close');		// close the dialog
                            $('#cardTable').datagrid('reload');	// reload the card data
                            $('#userTable').datagrid('reload');	// reload the card data
                        }
                        else {
                            $.messager.alert('错误', result.msg);
                        }
                    }
                });
            }
                
                
                
                
            </script>

        </div>


    </body>
</html>
