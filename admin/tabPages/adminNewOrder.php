<?php require ('tabPagesHeader.php') ?>

<?php
        require('../../global/init.php');
        $con = DatabaseConn::getConn();
        mysqli_query($con, "set names 'utf8'");
        $rs = mysqli_query($con, "select * from tb_china_firm");
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>新订单管理</title>
    </head>
    <body>
        <div id="toolBar">
            <div >
                <span title="Alt+S">    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="editNewOrder()">修改新订单</a></span>
                <span title="Alt+Z">   <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="delNewOrder()">删除新订单</a></span>
             </div>
             <div>
                 AKT订单号/新订单号:<input  style="width:150px;margin:0 5px 0 5px" id="newOrderSearchBox"  textField="text"/>
                 <span title="Alt+Q"> <a href="#" class="easyui-linkbutton" style="margin-bottom:5px;width:80px" iconCls="icon-search" onclick="newOrderSearch()">查询</a> </span>
                 <span title="Alt+W">  <a href="#" class="easyui-linkbutton" style="margin-bottom:5px;width:80px" iconCls="icon-search" onclick="resetNewOrderSearch()">重置查询</a></span>
             </div>
        </div>
        <table class="easyui-datagrid" toolbar="#toolbar" pagination="true"  singleSelect="true"  fitColumns="true"
               url="../admin/tabPagesBehind/adminNewOrderB.php?action=1" method="post" rownumbers="true" id="newOrderTable" 

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
                <th data-options="field:'order_num',width:80">AKT订单号</th>
                <th data-options="field:'new_order_num',width:100">新订单号</th>
                 <th data-options="field:'name',width:100">国内快递公司</th>
                <th data-options="field:'updated_date',width:100">操作时间</th>
                <th data-options="field:'updated_admin',width:100">操作人</th>
            </tr>
        </thead>
    </table>
        
          <div id="NewOrder_dlg" class="easyui-dialog" style="width:360px;padding:10px 20px 10px 20px"
              closed="true" buttons="NewOrder_buttons">
              <form id="NewOrder_fm" method="post" >
                  <div class="line"><div class="inputLine">AKT订单号:</div><span  name="newOrder_AKTOrder_num" id="newOrder_AKTOrder_num" ></span></div>

                <div class="line"><div class="inputLine">新订单号:</div><input class="easyui-validatebox textbox" type="text" id= "newOrder_order_num" name="newOrder_order_num" data-options="
                 required:true,
                 missingMessage:'新订单号不能为空'
                    "/>
                 </div>
                  
                <div class="line"><div class="inputLine">快递公司:</div> <select style="width:156px;margin-left: -6px;height:20px" id="new_order_company" name="new_order_company">
            <?php 
                while ($row = mysqli_fetch_object($rs)) {
                    echo "<option value=\"$row->code\">$row->name</option>";
                }
         
            ?>
                    </select>
                </div>
                  
                  <input id="newOrder_id" name="newOrder_id" type="hidden"/>
                    <input id="Origi_Order_Num" name="Origi_Order_Num" type="hidden"/>
                  
              </form>
                <br/>
          <div id="NewOrder_buttons" style="text-align: center">
                <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveNewOrder()">确定</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#NewOrder_dlg').dialog('close');dialogId=null">取消</a>
        </div>
         </div>
    <script type="text/javascript">
        
        $('a').click(function() {
                checkTimeout();
            });
            
       function saveNewOrder(){
           
                   $('#NewOrder_fm').form('submit', {
                    url: '../admin/tabPagesBehind/adminNewOrderB.php?action=4',
                    onSubmit: function() {
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');
                        if (result.success) {
                          $('#AKTOrderTable').datagrid('reload');
                          $('#newOrderTable').datagrid('reload');
                          $('#NewOrder_dlg').dialog('close');   
                            setTimeout("selectRow('#newOrderTable')", 500);
                        }
                        else {
                            $.messager.alert('错误', result.msg);
                        }
                    }
                }); 
           
       }    
            
       function editNewOrder() {
                  var row = $('#newOrderTable').datagrid('getSelected');
                if (row) {
                    
                    
                    $('#NewOrder_dlg').dialog({
                        title: '编辑一对一转新订单',
                        modal: true
                    }); 
                     $("#newOrder_AKTOrder_num").text(row.order_num);
                     $("#newOrder_order_num").val(row.new_order_num);
                     $("#Origi_Order_Num").val(row.new_order_num);
                     $("#newOrder_id").val(row.id);
                     $("#NnewOrder_order_num").focus();
                     
                     $("#new_order_company").val(row.new_order_company);
                     
                     $('#NewOrder_dlg').dialog('open');   
                     dialogId="NewOrder_dlg";
                     saveFunction="saveNewOrder";
                        rowIndex = $('#newOrderTable').datagrid('getRowIndex',row);
                }
                else{
                       $.messager.alert('错误', "请您选择一行");
                }
           
       };
       
       
       function delNewOrder() {
            var row = $('#newOrderTable').datagrid('getSelected');
            if (row) {
                    $.messager.confirm('Confirm', '您确定要删除这条记录吗?', function(r) {
                        if (r) {
                            $.post('../admin/tabPagesBehind/adminNewOrderB.php?action=3', {id: row.id,akt_order_num:row.order_num}, function(result) {
                                if (result.success) {
                          $('#AKTOrderTable').datagrid('reload');
                          $('#newOrderTable').datagrid('reload');
                                } else {
                                    $.messager.alert('错误', result.msg);
                                }
                            }, 'json');
                        }
                    });
        }
           
           
       };

       function newOrderSearch(){
                $('#newOrderTable').datagrid('load', {
                    searchNewOrder: $("#newOrderSearchBox").val()
                });

       };
       
      function resetNewOrderSearch(){
                $('#newOrderTable').datagrid('load', {
                    searchNewOrder: ""
                });
                 $("#newOrderSearchBox").val("");
       }
       
    </script>
    </body>
</html>
