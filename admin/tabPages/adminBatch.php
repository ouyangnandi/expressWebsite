<?php require ('tabPagesHeader.php') ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>批次管理</title>
    </head>

    <body>

        <div id="toolBar">
            <div >
                <span title="Alt+S">       <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="refreshBatchTable()">刷新</a></span>
                <span title="Alt+A">    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addBatchRoute()">批次路线添加</a></span>
                <span >    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="downloadID()">下载批次身份证文件</a>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="generateExcelInPinyin()">生成拼音Excel</a>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="generateExcelInChinese()">生成中文Excel</a>
            </div>
            <div>
                &nbsp; 时间从: <input class="easyui-datetimebox" id="BatchSearchFrom" style="width:150px"/>
                到: <input class="easyui-datetimebox"  id="BatchSearchTo" style="width:150px"/>
                &nbsp; 批次ID/批次号:<input  style="width:150px;margin:0 5px 0 5px" id="BatchSearchBox"  textField="text"/>
                <span title="Alt+Q">     <a href="#" class="easyui-linkbutton" style="margin-bottom:5px;width:80px" iconCls="icon-search" onclick="searchBatch()">查询</a></span>
                <span title="Alt+W">   <a href="#" class="easyui-linkbutton" style="margin-bottom:5px;width:80px" iconCls="icon-search" onclick="resetSearchBatch()">重置查询</a></span>
            </div>
        </div>

        <table class="easyui-datagrid" toolbar="#toolbar" pagination="true"    fitColumns="true"
               url="../admin/tabPagesBehind/adminBatchB.php?action=1" method="post" rownumbers="true" id="batchTable" 

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
                    <th field="ck" checkbox="true"></th>
                    <th data-options="field:'id',width:30">批次ID</th>
                    <th data-options="field:'name',width:30">批次号</th>
                    <th data-options="field:'amount',width:30">物品数量</th>
                    <th data-options="field:'route_num',width:50">路线数量</th>
                    <th data-options="field:'created_date',width:100">产生时间</th>
                    <th data-options="field:'created_admin',width:100">创建人</th>
                </tr>
            </thead>
        </table>


        <div id="add_route_dlg" class="easyui-dialog" style="width:500px;padding:10px 20px;height:300px"
             closed="true" buttons="#add_route_dlg-buttons">
            <form id="add_route_fm" method="post" >

                <div><h3>添加路线</h3></div>
                <hr/>
                <div style="margin:5px 0;height:25px;vertical-align: middle;">
                    <span style="float:right;color:#8A2BE2;display:none">地区：<input  class="easyui-validatebox textbox" required="true" id="add_route_fm_area" name="add_route_fm_area" value="暂不用" style="width:150px"></span>
                    <span style="float:left;color:#8A2BE2">时间：<input  class="easyui-datetimebox"  required="true" id="add_route_fm_date" name="add_route_fm_date"  style="width:150px"></span>
                </div>
                <div style="color:#8A2BE2;float:left">描述：</div>
                <br/>
                <div>

                    <textarea rows=4 class="easyui-validatebox" required="true" id="add_route_fm_desc" name="add_route_fm_desc" style="width:98%">
                    </textarea>

                </div>

            </form>
        </div>
        <div id="add_route_dlg-buttons" style="text-align: center">
            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveAddRoute()">确认</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#add_route_dlg').dialog('close');
                    dialogId = null">取消</a>
        </div> 


        <div id="route_dlg" class="easyui-dialog" style="width:500px;padding:10px 20px;height:300px"
             closed="true" buttons="#route_dlg-buttons">
            <form id="route_fm" method="post" >

                <div><h3>批次信息</h3></div>
                <hr/>
                <div style="width:49%;display:inline-block;height:25px;vertical-align: middle;float:left;" ><span style="color:#8A2BE2">批次ID：</span> <span id="route_dlg_batch_id" ></span></div>
                <div style="width:49%;display:inline-block;height:25px;vertical-align: middle;float:left;"  ><span style="color:#8A2BE2">批次号：</span><span id="route_dlg_batch_name" ></span></div>
                <div>
                    <div style="width:49%;display:inline-block;height:25px;vertical-align: middle;float:left;"  ><span style="color:#8A2BE2">时间 ：</span> <input class="easyui-datetimebox" required="true" id="route_fm_date" name="route_fm_date" style="width:140px"/></div>  
                    <div style="width:49%;display:none;height:25px;vertical-align: middle;float:left;"  ><span style="color:#8A2BE2">地区：</span>  <input  class="easyui-validatebox textbox" required="true" id="route_fm_area" name="route_fm_area" value="暂不用" style="width:140px"/></div>
                </div>
                <div style="width:49%;height:25px;vertical-align: middle;"  ><span style="color:#8A2BE2">描述：</span></div>
                <br/>
                <div>

                    <textarea rows=4 class="easyui-validatebox" required="true" id="route_fm_desc" name="route_fm_desc" style="width:98%">
                    </textarea>


                </div>
                <input  type="hidden" name="route_fm_batch_id" id="route_fm_batch_id"/>
                <input  type="hidden" name="route_fm_batch_name" id="route_fm_batch_name"/>
                <input  type="hidden" name="route_fm_desc_id" id="route_fm_desc_id"/>
                <input  type="hidden" name="route_fm_area_id" id="route_fm_area_id"/>
                <input  type="hidden" name="route_fm_date_id" id="route_fm_date_id"/>


            </form>
        </div>
        <div id="route_dlg-buttons" style="text-align: center">
            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveRoute()">确认</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#route_dlg').dialog('close');
                    dialogId = null;">取消</a>
        </div> 



        <script type="text/javascript">

            var routeUrl;
            function addBatchRoute() {
                routeUrl = "../admin/tabPagesBehind/adminRouteB.php?action=2&batchIds=";
                var rows = $('#batchTable').datagrid('getChecked');

                if (rows.length >= 1) {
                    for (var i = 0; i < rows.length; i++) {
                        routeUrl = routeUrl + rows[i].id + ",";
                    }
                    routeUrl = routeUrl + "&batchNames=";

                    for (var i = 0; i < rows.length; i++) {
                        routeUrl = routeUrl + rows[i].name + ",";
                    }


                    $('#add_route_dlg').dialog({
                        title: '添加路线',
                        modal: true
                    });

                    $('#add_route_dlg').dialog('open');
                    dialogId = "add_route_dlg";
                    saveFunction = "saveAddRoute";

                    $('#add_route_fm_area').val("澳大利亚");
                    $('#add_route_fm_desc').val("");
                    $('#add_route_fm_date').datetimebox('setValue', (new Date()).Format('yyyy-MM-dd hh:mm:ss'));

                    $('#add_route_fm_desc').focus();
                }
                else {
                    $.messager.alert('错误', "请您选择一行");
                }

            }

            function saveAddRoute() {
                $('#add_route_fm').form('submit', {
                    url: routeUrl,
                    onSubmit: function() {
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');
                        if (result.success) {
                            $('#add_route_dlg').dialog('close');
                            $('#batchTable').datagrid('reload');
                        }
                        else {
                            $.messager.alert('错误', result.msg);
                        }
                    }
                });
            }


            $('#batchTable').datagrid({
                view: detailview,
                detailFormatter: function(index, row) {
                    if (row.route_num == 0) {
                        return;
                    }
                    return "<div class='ddv' style='padding:5px 0 0 0'></div>";
                },
                onExpandRow: function(index, row) {

                    if (row.route_num == 0) {
                        return;
                    }

                    var ddv = $(this).datagrid('getRowDetail', index).find('div.ddv');
                    ddv.panel({
                        border: false,
                        cache: false,
                        height: row.route_num * 25 + 30,
                        href: 'tabPages/adminRoute.php?batchId=' + row.id + '&batchName=' + row.name,
                        onLoad: function() {
                            setTimeout(function() {
                                $('#batchTable').datagrid('fixDetailRowHeight', index);
                            }, 1);
                        }
                    });

                }
            });

            $('a').click(function() {
                checkTimeout();
            });


            function saveRoute() {
                $('#route_fm').form('submit', {
                    url: routeUrl,
                    onSubmit: function() {
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');

                        if (result.success) {
                            var decId = $("#route_fm_desc_id").val();
                            var areaId = $("#route_fm_area_id").val();
                            var dateId = $("#route_fm_date_id").val();
                            $("#" + decId).text($('#route_fm_desc').val());
                            $("#" + areaId).text($('#route_fm_area').val());
                            $("#" + dateId).text($('#route_fm_date').datetimebox('getValue'));
                            $('#route_dlg').dialog('close');		// close the dialog	
                        }
                        else {
                            $.messager.alert('错误', result.msg);
                        }
                    }
                });
            }
            ;


            function refreshBatchTable() {
                $('#batchTable').datagrid('reload');
            }

            function searchBatch() {
                $('#batchTable').datagrid('load', {
                    searchBatch: $("#BatchSearchBox").val(),
                    searchTimeTo: $('#BatchSearchTo').datetimebox('getValue'),
                    searchTimeFrom: $('#BatchSearchFrom').datetimebox('getValue')
                });


            }

            function resetSearchBatch() {
                $('#batchTable').datagrid('load', {
                    searchBatch: "",
                    searchTimeTo: "",
                    searchTimeFrom: ""
                });

                $("#BatchSearchTo").datetimebox('setValue', "");
                $("#BatchSearchFrom").datetimebox('setValue', "");
            }

            function generateExcelInPinyin() {
                var exportUrl = "tabPagesBehind/exportExcelInPinYin.php?batchIds=";
                var rows = $('#batchTable').datagrid('getChecked');
                if (rows.length >= 1) {
                    for (var i = 0; i < rows.length; i++) {
                        exportUrl = exportUrl + rows[i].id + ",";
                    }
                    exportUrl = exportUrl.substr(0, exportUrl.length - 1);

                    window.location.assign(exportUrl);
                }
                else {
                    $.messager.alert('错误', "请您选择一行");
                }
            }

            function downloadID() {
                var exportUrl = "tabPagesBehind/downloadIDB.php?action=1&batchIds=";
                var rows = $('#batchTable').datagrid('getChecked');
                if (rows.length == 1) {
                    $.post(exportUrl + rows[0].id, function(result) {
                        if (result.success) {
                                 window.location.assign("tabPagesBehind/downloadIDB.php?action=2&batchIds="+rows[0].id);
                        } else {
                            $.messager.alert('错误', result.msg);
                        }
                    }, 'json');

                }
                else {
                    $.messager.alert('错误', "请您选择一行,且只能选择一行");
                }
            }



            function generateExcelInChinese() {
                var exportUrl = "tabPagesBehind/exportExcelInChinese.php?batchIds=";
                var rows = $('#batchTable').datagrid('getChecked');
                if (rows.length >= 1) {
                    for (var i = 0; i < rows.length; i++) {
                        exportUrl = exportUrl + rows[i].id + ",";
                    }
                    exportUrl = exportUrl.substr(0, exportUrl.length - 1);
                    window.location.assign(exportUrl);
                }
                else {
                    $.messager.alert('错误', "请您选择一行");
                }
            }

        </script>
    </body>
</html>
