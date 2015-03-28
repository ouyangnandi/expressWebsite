<?php require ('tabPagesHeader.php') ?>
<html>
    <head>
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
    <body>
        <div id="toolBar">
            <div >
                <span title="Alt+A">       <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newCardType()">添加类型</a> </span>
                <span title="Alt+S">     <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editCardType()">编辑类型</a> </span>
                <span title="Alt+Z">      <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeCardType()">删除类型</a> </span>
             </div>

        </div>
     <table class="easyui-datagrid" toolbar="#toolbar" pagination="true"  singleSelect="true"  fitColumns="true"
            url="../admin/tabPagesBehind/adminCardTypeB.php?action=1" method="get" rownumbers="true" id="cardTypeTable"
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
                <th data-options="field:'name',width:80">卡类别</th>
                <th data-options="field:'credit_benchmark',width:100">升级所需积分</th>
                <th data-options="field:'update_date',width:100">更新时间</th>
                <th data-options="field:'created_admin',width:100">更新人</th>
            </tr>
        </thead>
    </table>
        
         <div id="cardType_dlg" class="easyui-dialog" style="width:360px;height:180px;padding:10px 20px"
			closed="true" buttons="#cardType_dlg-buttons">
		<form id="cardType_fm" method="post" >
                
                 <div class="line"><div class="inputLine">类型名:</div><input class="easyui-validatebox textbox" type="text" name="cardType_name"  id = "cardType_name" data-options="
                 required:true,
                 missingMessage:'类型的名字不能为空'"></input></div>
 
                 <div class="line"><div class="inputLine">积分:</div><input class="easyui-validatebox textbox" type="text" name="cardType_credit" id="cardType_credit" data-options="
                 required:true,
                 missingMessage:'卡积分不能为空',
                 validType:'checkIsInt'">
                 </input></div>
                    
                    <input type="hidden" name="cardType_id"  id = "cardType_id"></input>

		</form>
	</div> 
        
        <div id="cardType_dlg-buttons" style="text-align: center">
		<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveCardType()">确认</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#cardType_dlg').dialog('close');dialigId=null;">取消</a>
	</div>
        
        
        
    <script type="text/javascript">
        var url;
       $('a').click(function(){     
                checkTimeout();
            });
            
       $.extend($.fn.validatebox.defaults.rules, {
                checkIsInt: {
                    validator: function(value) {
                            var intValue = parseInt(value);
                            if (intValue+"" == "NaN") {   
                                 return false;
                               }
                            else if (intValue < 0)
                                {
                                    return false;
                                }
                                    return true;
                            },
                               message: '积分必须是大于或者等于0的整数'
                           }
             });         
            
            
      function newCardType() {
			$('#cardType_dlg').dialog({
                             title: '添加新类型',
                             modal: true
                        });
                        $('#cardType_dlg').dialog('open');
                        dialogId="cardType_dlg";
                        saveFunction="saveCardType";
                        
                        $('#cardType_fm').form('clear');
                        url="../admin/tabPagesBehind/adminCardTypeB.php?action=2";
       };
       
       function editCardType(){
			var row = $('#cardTypeTable').datagrid('getSelected');
			if (row){
                        $('#cardType_dlg').dialog({
                             title: '编辑新类型',
                             modal: true
                        });
                        $('#cardType_dlg').dialog('open');
                        dialogId="cardType_dlg";
                        saveFunction="saveCardType";
                        $('#cardType_fm').form('clear');
                        $('#cardType_name').val(row.name);
                        $('#cardType_credit').val(row.credit_benchmark);
                         $('#cardType_id').val(row.id);
                         rowIndex = $('#cardTypeTable').datagrid('getRowIndex',row);
                        url="../admin/tabPagesBehind/adminCardTypeB.php?action=4";
			}
                        else{
                                $.messager.alert( '错误',"请您选择一行");
                         }	
		}
       
       function saveCardType(){
			$('#cardType_fm').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					var result = eval('('+result+')');
					if (result.success){
						$('#cardType_dlg').dialog('close');		// close the dialog
						$('#cardTypeTable').datagrid('reload');	// reload the user data
                                                setTimeout("selectRow('#cardTypeTable')",500);
					} else {
						$.messager.alert('错误', result.msg);
					}
				}
			});
		}
                
        function removeCardType(){
			var row = $('#cardTypeTable').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','您确定要删除这个类型吗?',function(r){
					if (r){
						$.post('../admin/tabPagesBehind/adminCardTypeB.php?action=3',{id:row.id},function(result){
							if (result.success){
								$('#cardTypeTable').datagrid('reload');	// reload the user data
							} else {
								$.messager.alert( '错误', result.msg);
							}
						},'json');
					}
				});
			}
                        else{
                                $.messager.alert( '错误',"请您选择一行");
                         }	
		}
                
          
    </script>
    </body>
</html>
