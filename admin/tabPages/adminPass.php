<?php require ('tabPagesHeader.php') ?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../../css/themes/default/easyui.css"/>
        <link rel="stylesheet" type="text/css" href="../../css/themes/icon.css"/>
        <link rel="stylesheet" type="text/css" href="../../css/main.css"/>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
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
        validator: function(value,param){
            return value == $(param[0]).val();
        },
        message: '两次密码输入必须一致'
    }
});
        </script>
        <title>密码管理</title>
    </head>
    <body>
        <div class="center"><h2>密码管理</h2></div>
        <div style="margin:20px 0;"></div>
        <div  style="text-align:center">

            <form id="passChange" method="post" >
          
                <div class="line" ><div class="inputLine" style="float:left;width:45%"><div style="float:right">您的密码:</div></div><input style="float:left" class="easyui-validatebox textbox" type="password" name="originPass" data-options="
                 required:true,
                 missingMessage:'密码不能为空',
                 validType:{checkLength:[6]}"></input></div><br/>
                 <div class="line"><div class="inputLine"style="float:left;width:45%"><div style="float:right">新密码:</div></div><input style="float:left" class="easyui-validatebox textbox"  type="password" name="newPass" id="newPass" data-options="
                 required:true,
                 missingMessage:'密码不能为空',
                 validType:{checkLength:[6]}">
                 </input></div><br/>

                 <div class="line"><div class="inputLine" style="float:left;width:45%"><div style="float:right">密码确认:</div></div><input style="float:left" class="easyui-validatebox textbox" type="password" id= "confirmPass" name="confirmPass" data-options="
                 required:true,
                 missingMessage:'密码不能为空',
                 validType:{
                 checkLength:[6],
		 equals:['#newPass']
                    }">
                 </input></div><br/>

            </form>
            <br/>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="easyui-linkbutton" style="width:80px;margin-right:10px" onclick="submitForm()">确认</a>

                <a href="javascript:void(0)" class="easyui-linkbutton" style="width:80px" onclick="clearForm()">取消</a>
            </div>



            <script>
                function submitForm() {
                    
                    $('#passChange').form('submit',{
				url: '../admin/tabPagesBehind/adminPassB.php',
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
                
                function clearForm() {
                    $('#passChange').form('clear');
                }
            </script>
    </body>
</html>
