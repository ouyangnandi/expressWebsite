<?php require ('tabPagesHeader.php') ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>批次路线管理</title>
    </head>

    <body>

        <div id="toolBar">
            <div >
                <span title="Alt+S"> <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editBatchRoute()">编辑路线</a></span>
                <span title="Alt+Z"> <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="delBatchRoute()">删除路线</a></span>
            </div>
            <div>
                &nbsp; 时间从: <input class="easyui-datetimebox" id="batchRouteSearchFrom" style="width:150px"/>
                到: <input class="easyui-datetimebox"  id="batchRouteSearchTo" style="width:150px"/>
                &nbsp; 批次ID/批次号:<input  style="width:150px;margin:0 5px 0 5px" id="batchRouteSearchBox"  textField="text"/>
                <span title="Alt+Q">     <a href="#" class="easyui-linkbutton" style="margin-bottom:5px;width:80px" iconCls="icon-search" onclick="searchBatchRoute()">查询</a></span>
                <span title="Alt+W">   <a href="#" class="easyui-linkbutton" style="margin-bottom:5px;width:80px" iconCls="icon-search" onclick="resetSearchBatchRoute()">重置查询</a></span>
            </div>
        </div>

        <table class="easyui-datagrid" toolbar="#toolbar" pagination="true"    fitColumns="true" singleSelect="true"
               url="../admin/tabPagesBehind/adminBatchRouteB.php?action=1" method="post" rownumbers="true" id="batchRouteTable" 

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
                    <th data-options="field:'id',width:30">路线ID</th>
                    <th data-options="field:'batch_id',width:30">批次ID</th>
                    <th data-options="field:'batch_name',width:30">批次号</th>
                    <th data-options="field:'updated_date',width:50">更新时间</th>
                    <!--<th data-options="field:'area',width:50">地区</th>-->
                    <th data-options="field:'description',width:50">描述</th>
                </tr>
            </thead>
        </table>


        <div id="batch_route_dlg" class="easyui-dialog" style="width:500px;padding:10px 20px;height:300px"
             closed="true" buttons="#batch_route_dlg-buttons">
            <form id="batch_route_fm" method="post" >

                <div><h3>批次信息</h3></div>
                <hr/>
                <div style="width:49%;display:inline-block;height:25px;vertical-align: middle;float:left;" ><span style="color:#8A2BE2">批次ID：</span> <span id="batch_route_dlg_batch_id" ></span></div>
                <div style="width:49%;display:inline-block;height:25px;vertical-align: middle;float:left;"  ><span style="color:#8A2BE2">批次号：</span><span id="batch_route_dlg_batch_name" ></span></div>
                <div style="height:30px">
                     <div style="width:49%;display:inline-block;height:25px;vertical-align: middle;float:left;"  ><span style="color:#8A2BE2">时间 ：</span> <input class="easyui-datetimebox" required="true" id="batch_route_date" name="batch_route_date" style="width:140px"/></div>
                    <div style="width:49%;display:none;height:25px;vertical-align: middle;float:left;"  ><span style="color:#8A2BE2">地区：</span>  <input  class="easyui-validatebox textbox" required="true" id="batch_route_fm_area" name="batch_route_fm_area" value="暂不用" style="width:140px"/></div>
                </div>
            
                <div style="width:49%;;height:25px;vertical-align: middle;"  > <span style="color:#8A2BE2">描述：</span></div>
                <br/>
                <div>

                    <textarea rows=4 class="easyui-validatebox" required="true" id="batch_route_fm_desc" name="batch_route_fm_desc" style="width:98%">
                    </textarea>


                </div>

            </form>
        </div>
        <div id="batch_route_dlg-buttons" style="text-align: center">
            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveBatchRoute()">确认</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#batch_route_dlg').dialog('close');
                                                            dialogId = null;">取消</a>
        </div> 



        <script type="text/javascript">

            var routeUrl;

            $('a').click(function() {
                checkTimeout();
            });


            function saveBatchRoute() {
                $('#batch_route_fm').form('submit', {
                    url: routeUrl,
                    onSubmit: function() {
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');

                        if (result.success) {

                            $('#batch_route_dlg').dialog('close');		// close the dialog	
                            $('#batchRouteTable').datagrid('reload');	// reload the user data
                        }
                        else {
                            $.messager.alert('错误', result.msg);
                        }
                    }
                });
            }

            function editBatchRoute() {
                
                var row = $('#batchRouteTable').datagrid('getSelected');
                
                if(!row) {
                       $.messager.alert('错误', "请您选择一行");
                       return;
                }
                
                routeUrl = "../admin/tabPagesBehind/adminBatchRouteB.php?action=2&routeId=" + row.id;
                $('#batch_route_dlg').dialog({
                    title: '修改路线',
                    modal: true
                });

                var batchId = row.batch_id;
                var batchName = row.batch_name;

                $('#batch_route_dlg_batch_id').text(batchId);
                $('#batch_route_dlg_batch_name').text(batchName);
                $('#batch_route_fm_desc').val(row.description);
               // $('#batch_route_fm_area').val(row.area);
                $("#batch_route_date").datetimebox('setValue', row.updated_date);
                
                $('#batch_route_dlg').dialog('open');
                dialogId = "batch_route_dlg";
                saveFunction = "saveBatchRoute";
                $('#batch_route_fm_area').focus();
                $('#batch_route_fm_desc').focus();
            }

            function delBatchRoute() {
                
                var row = $('#batchRouteTable').datagrid('getSelected');
                
                if(!row) {
                       $.messager.alert('错误', "请您选择一行");
                       return;
                }
                
                
                $.messager.confirm('Confirm', '您确定要删除这条路线吗?', function(r) {
                    if (r) {
                        $.post('../admin/tabPagesBehind/adminBatchRouteB.php?action=3', {id: row.id, route_batch_id: row.batch_id}, function(result) {
                            if (result.success) {
                                            $('#batchRouteTable').datagrid('reload');	// reload the user data
                            } else {
                                $.messager.alert('错误', result.msg);
                            }
                        }, 'json');
                    }
                });
            }
            function searchBatchRoute() {
                $('#batchRouteTable').datagrid('load', {
                    searchBatch: $("#batchRouteSearchBox").val(),
                    searchTimeTo: $('#batchRouteSearchTo').datetimebox('getValue'),
                    searchTimeFrom: $('#batchRouteSearchFrom').datetimebox('getValue')
                });
            }

            function resetSearchBatchRoute() {
                $('#batchRouteTable').datagrid('load', {
                    searchBatch: "",
                    searchTimeTo: "",
                    searchTimeFrom: ""
                });

                $("#batchRouteSearchTo").datetimebox('setValue', "");
                $("#batchRouteSearchFrom").datetimebox('setValue', "");
            }


        </script>
    </body>
</html>
