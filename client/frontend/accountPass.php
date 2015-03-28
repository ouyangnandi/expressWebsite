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
        <script type="text/javascript">


            $.extend($.fn.validatebox.defaults.rules, {
                checkLength: {
                    validator: function(value, param) {
                        return value.length >= param[0];
                    },
                    message: '密码至少 有6个字母'
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
        </script>
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
                <div style="text-align:center; font-family:微软雅黑;"><span><h3 style="font-weight:normal;color:#3c4980">修改密码</h3></span></div>
                <div style="height:20px;width:100%"></div>
                <div style="margin:0 50px" style="text-align: center"> <img src="../../imgs/gredient_account.png" width="900"/></div>
                <div style="margin:0 70px" class="accountLinks"><a href="account.php"> <img src="../../imgs/account_info.png" /> </a><a href="accountPass.php"><img src="../../imgs/account_pass.png" /> </a><a href="historyOrder.php"><img src="../../imgs/account_order.png" /></a></div>
                <div class="dashline" style="margin:0 70px;height:30px"></div>
                <div  style="margin:0 70px;height:25px;text-align: center;"><hr  color="#828282" style="margin: 0px;"/>
                    <div style="font-family:微软雅黑;background-color:white;width:100px;text-align: center;position:relative;bottom:12px;display:inline-block;"><h4 style="margin:0px;" >密码修改</h4></div>
                </div>

                <div style="margin:0 70px 40px 70px"> 

                    <div style="width:455px;background-color:#F7F7F7;height:240px;border-right:1px solid #b8b8b8;display:inline-block;float:left; ">

                        <div style="margin:9px 0  0 87px;">
                            <form id="passChange" method="post">
                            <div class="accountPassText">原始密码</div>
                            <div class="accountPassInput"><input class="easyui-validatebox textbox" type="password" name="originPass" id="originPass" data-options="
                                                                 required:true,
                                                                 missingMessage:'密码不能为空',
                                                                 validType:{checkLength:[6]}"/></div>
                            <div class="accountPassText">新密码</div>
                            <div class="accountPassInput"><input class="easyui-validatebox textbox" type="password" name="newPass" id="newPass" data-options="
                                                                 required:true,
                                                                 missingMessage:'密码不能为空',
                                                                 validType:{checkLength:[6]}" /></div>
                            <div class="accountPassText">确认新密码</div>
                            <div class="accountPassInput"><input class="easyui-validatebox textbox" type="password" id= "confirmPass" name="confirmPass" data-options="
                                                                 required:true,
                                                                 missingMessage:'密码不能为空',
                                                                 validType:{
                                                                 checkLength:[6],
                                                                 equals:['#newPass']
                                                                 }"/>
                                <img style="margin-left: 36px;vertical-align: middle;position: relative;bottom:3px;cursor:pointer;"  src="../../imgs/account_save.png"  onclick="submitForm();"/></div>
                            </form>
                        </div>

                    </div>

                    <div class="accountPassHelp">
                        <div style="height:210px;"></div>
                     <a style="margin-left:170px" href="mailto:info@canexpress.com.au">    <img src="../../imgs/email_us.png"  ></a>
                    </div>

                </div>





                <div style="height:10px"></div>
                <div id="bottomContent"> </div>
            </div>




            <script type="text/javascript" >



                $(document).ready(function() {
                    $('#headerContent').load('../../header.html');
                    $('#bottomContent').load('../../bottom.html');
                });

                function logout() {

                    $.post('../backend/userLogin.php?action=1',function(){
                        
                           location.href ="../../index.html";
                    });
      

                }
                
                     function submitForm() {
                    
                    $('#passChange').form('submit',{
				url: '../backend/accountPassB.php',
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					var result = eval('('+result+')');
					if (result.success){
						$.messager.alert('密码修改成功', result.msg);
                                                 $('#passChange').form('clear');
					} else {
						$.messager.alert('错误', result.msg);
                                                 $('#passChange').form('clear');
					}
				}
			});
                }
                
                
            </script>
            <div style="height:20px"></div>
            <div id="mainDiv" style="width:984px;margin:0 auto;background-color:white;">
                <div style="height:10px"></div>
                <div id="bottomContent"> </div>
            </div>
        </div>


    </body>
</html>
