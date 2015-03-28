<?php
require ('tabPagesHeader.php');
require('../../global/init.php');

$con = DatabaseConn::getConn();
mysqli_query($con, "set names 'utf8'");
$rs = mysqli_query($con, "select batch_name,batch_id,batch_seq from tb_akt_order ORDER BY id DESC LIMIT 1");
$row = mysqli_fetch_row($rs);
$lastBatchName = $row[0];
$lastBatchId = $row[1];
$lastBatchSeq = $row[2];

$rs = mysqli_query($con, "select * from tb_china_firm");
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../../css/themes/default/easyui.css"/>
        <link rel="stylesheet" type="text/css" href="../../css/themes/icon.css"/>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.jqprint.js"></script>
    </head>
    <body >
        <div id="toolBar">
            <div >
                <span title="Alt+A"> <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newAKTOrder()">添加订单</a></span>
                <span title="Alt+S">   <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editAKTOrder()">修改/查看订单</a> </span>
                <span title="Alt+Z">  <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="delAKTOrder()">删除订单</a></span>
                |
                <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="bindNewOrder()">一对一转新订单</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="bindMultipleNewOrder()">一对多转新订单</a>|
                <a href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="printOrder()">打印订单</a>

            </div>
            <div>
                &nbsp; 时间从: <input class="easyui-datetimebox" id="AKTOrderSearchFrom" style="width:150px">
                到: <input class="easyui-datetimebox" id="AKTOrderSearchTo"  style="width:150px">
                订单号/批次号/会员ID号:<input  style="width:150px;margin:0 5px 0 5px" id="AKTOrderSearchBox"  textField="text"/>
                <span title="Alt+Q">   <a href="#" class="easyui-linkbutton" style="margin-bottom:5px;width:80px" iconCls="icon-search" onclick="SearchAKTOrder()">查询</a></span>
                <span title="Alt+W">   <a href="#" class="easyui-linkbutton" style="margin-bottom:5px;width:80px" iconCls="icon-search" onclick="resetSearchAKTOrder()">重置查询</a></span>
            </div>
        </div>
        <table class="easyui-datagrid" toolbar="#toolbar" pagination="true"  singleSelect="true"  fitColumns="true" style="height:400px"
               url="../admin/tabPagesBehind/adminAKTOrderB.php?action=1" method="post" rownumbers="true" id="AKTOrderTable" 

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
                    <th data-options="field:'order_num',width:80">订单号</th>
                    <th data-options="field:'batch_id',width:100">批次ID</th>
                    <th data-options="field:'batch_name_seq',width:100">批次号</th>
                    <th data-options="field:'category',width:100">物品类别</th>
                    <th data-options="field:'certificate',width:80">证件号</th>
                    <th data-options="field:'weight',width:100">重量(公斤KG)</th>
                    <th data-options="field:'status',width:100">转单状态</th>
                    <th data-options="field:'username',width:100">会员账号</th>
                    <th data-options="field:'created_date',width:100">创建时间</th>
                </tr>
            </thead>
        </table>


        <div id="AKTOrder_dlg" class="easyui-dialog" style="width:700px;padding:0px 20px 0px 20px;height:800px;"
             closed="true" buttons="AKTOrder_buttons"  >
            <div style="text-align:center"> <h3>订单详情</h3> </div>
            <div class="subtitle"> 订单信息:</div>
            <hr/>
            <form id="AKTOrder_fm" method="post" >
                <table class="detailsTable2"> 
                    <tr><td class="textColor">订单号:</td><td><input class="easyui-validatebox textbox" required="true" type="text" id="AKTOrder_order_num" name="AKTOrder_order_num"/></td><td class="textColor">会员账号:</td><td><input class="easyui-validatebox textbox"  type="text" id="AKTOrder_user_name" name="AKTOrder_user_name"/></td></tr>
                    <tr><td class="textColor">批次号:</td><td><input class="easyui-validatebox textbox" required="true" type="text" id="AKTOrder_batch_name_seq" name="AKTOrder_batch_name_seq" onkeyup="changeBatchName()" data-options="validType:'checkBatchName'" /></td><td class="textColor">批次ID:</td><td id="AKTOrder_batch_id_value"/></td></tr>
                    <tr><td class="textColor">更新人:</td><td id="AKTOrder_admin"></td><td class="textColor">录单时间:</td><td id="AKTOrder_created_date"></td></tr>
                    <tr><td>
                            <input class="easyui-validatebox textbox" required="true" type="text" id="AKTOrder_batch_seq" name="AKTOrder_batch_seq"  style="display:none"/>
                            <input class="easyui-validatebox textbox" required="true" type="text" id="AKTOrder_batch_name" name="AKTOrder_batch_name"  style="display:none"/>       
                        </td></tr>
                </table>

                <div class="subtitle">寄件人信息:</div>
                <hr/>
                <table class="detailsTable2"> 
                    <tr><td class="textColor">姓名:</td><td><input class="easyui-validatebox textbox" type="text" id="AKTOrder_sender_name" name="AKTOrder_sender_name" /></td><td class="textColor"><span style="display:inline-block;float:left;margin-top:3px">电话:</span><span style="display:inline-block;float:right;margin-top:3px">&nbsp;—</span><input class="easyui-validatebox textbox" type="text" id="AKTOrder_sender_tel_area" name="AKTOrder_sender_tel_area" style="width:65px;float:right;margin-top:2px" data-options="validType:'checkTelAreaLength[6]'" /></td><td><input class="easyui-validatebox textbox" type="text" id="AKTOrder_sender_tel" name="AKTOrder_sender_tel" data-options="validType:'checkTel[6]'"/></td></tr>
                    <tr><td class="textColor">Email:</td><td colspan=3><input style="width:99%" class="easyui-validatebox textbox" type="text" id="AKTOrder_sender_email" name="AKTOrder_sender_email" data-options="validType:'email'"/></td><tr/>
                    <tr><td class="textColor">地址:</td>
                        <td colspan=3 ><textarea rows=2 style="width:100%;margin:0px;padding:0px" id="AKTOrder_sender_addr" name="AKTOrder_sender_addr"></textarea></td></tr>
                </table>

                <div class="subtitle">收件人信息:</div>
                <hr/>
                <table class="detailsTable2"> 
                    <tr><td class="textColor">姓名:</td><td><input class="easyui-validatebox textbox" type="text" id="AKTOrder_receiver_name" name="AKTOrder_receiver_name"/></td><td class="textColor"><span style="display:inline-block;float:left;margin-top:3px">电话:</span><span style="display:inline-block;float:right;margin-top:3px">&nbsp;—</span><input class="easyui-validatebox textbox" type="text" id="AKTOrder_receiver_tel_area" name="AKTOrder_receiver_tel_area" style="width:65px;float:right;margin-top:2px" data-options="validType:'checkTelAreaLength[6]'"/></td><td><input class="easyui-validatebox textbox" type="text" id="AKTOrder_receiver_tel" name="AKTOrder_receiver_tel" data-options="validType:'checkTel[6]'"/></td></tr>
                    <tr><td class="textColor">Email:</td><td colspan="3"><input style="width:99%" class="easyui-validatebox textbox" type="text" id="AKTOrder_receiver_email" name="AKTOrder_receiver_email" data-options="validType:'email'"/></td><tr/>
                    <tr><td class="textColor">地址:</td><td colspan="3" ><textarea rows=2 style="width:100%;margin:0px;padding:0px" id="AKTOrder_receiver_addr" name="AKTOrder_receiver_addr"></textarea></td></tr>
                </table>

                <div class="subtitle">订单路线信息:</div>
                <hr/>
                <table class="detailsTable2"> 
                    <tr><td class="textColor">路线信息:</td><td colspan=3 ><input  style="width:99%" class="easyui-validatebox textbox" type="text" id="AKTOrder_order_route_info" onblur="changeRouteDate()" name="AKTOrder_order_route_info"/></td></tr>
                    <tr><td class="textColor">时间:</td><td> <input class="easyui-datetimebox" id="AKTOrder_order_route_date" name="AKTOrder_order_route_date" ></td><td class="textColor">地区:</td><td> <input class="easyui-validatebox textbox"  type="text" id="AKTOrder_order_route_area" name="AKTOrder_order_route_area" /></td></tr>
                </table>   
                
                 <div class="subtitle">物品信息:</div>
                <hr/>
                <table class="detailsTable2"> 
                    <tr><td class="textColor">物品类别:</td><td><input class="easyui-validatebox textbox" type="text" id="AKTOrder_category" name="AKTOrder_category"/></td><td class="textColor">物品数量:</td><td><input class="easyui-validatebox textbox" type="text" id="AKTOrder_amount" name="AKTOrder_amount" data-options="validType:'checkInteger'"/></td></tr>
                    <tr><td class="textColor">物品重量(公斤KG):</td><td ><input class="easyui-validatebox textbox" type="text" id="AKTOrder_weight" name="AKTOrder_weight" data-options="validType:'checkNumber'"/></td><td class="textColor">证件号: <a href="javascript:void(0)" class="easyui-linkbutton" id="picButton" onclick="$('#picDialog').dialog('open')">照片已上传</a></td><td><input class="easyui-validatebox textbox" type="text" id="AKTOrder_certificate" name="AKTOrder_certificate"/></td></tr>
                </table>

                <div class="subtitle">备注</div>
                <hr/>
                <textarea rows=2 style="width:99%" id="AKTOrder_remarks" name="AKTOrder_remarks">

                </textarea>

                <input  type="hidden" id="AKTOrder_batch_id" name="AKTOrder_batch_id"/>
                <input type="hidden" name="AKTOrder_original_weight" id="AKTOrder_original_weight" />
                <input type="hidden" name="AKTOrder_original_user_id" id="AKTOrder_original_user_id" />
            </form>
            <div id="AKTOrder_buttons" style="text-align: center">
                <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveAKTOrder()">确定</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="closeOrderDialog()">取消</a>
            </div>
        </div>

        <div id="BindNewOrder_dlg" class="easyui-dialog" style="width:360px;padding:10px 20px 10px 20px"
             closed="true" buttons="BindNewOrder_buttons">
            <form id="BindNewOrder_fm" method="post" >
                <div class="line"><div class="inputLine">订单号:</div><span  name="AKTOrder_num" id="AKTOrder_num" ></span></div>

                <div class="line"><div class="inputLine">新订单号:</div><input class="easyui-validatebox textbox" type="text" id= "New_Order_Num" name="New_Order_Num" data-options="
                                                                           required:true,
                                                                           missingMessage:'新订单号不能为空'
                                                                           "/>
                </div>

                <div class="line"><div class="inputLine">快递公司:</div> <select style="width:156px;margin-left: -6px;height:20px" id="new_order_new_company" name="new_order_new_company">
                        <?php
                        while ($row = mysqli_fetch_object($rs)) {
                            echo "<option value=\"$row->code\">$row->name</option>";
                        }
                        ?>
                    </select>
                </div>

                <input type="hidden" name="AKTOrder_id" id="AKTOrder_id" />


            </form>
            <br/>
            <div id="BindNewOrder_buttons" style="text-align: center">
                <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveBindNewOrder()">确定</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="closeOrderDialog()">取消</a>
            </div>
        </div>

        <div id="myPrintArea" style="display:none">
            <div id="mp_AKT_order"></div>
            <div id="mp_batch_seq"></div>
            <div id="mp_receiver_name"></div>
            <div >AKT</div>
            <div id="mp_receiver_addr"></div>
            <div id="mp_receiver_tel"></div>
            <div id="mp_category"></div>
        </div>
        
        <div id="picDialog" class="easyui-dialog" closed="true" title="身份证图片" data-options="iconCls:'icon-save'" style="width:750px;height:520px;padding:10px">
             <div  style="text-align: center">
                 <div>
                     <h2> 身份证正面</h2>
            <img src=""  id="picName" width ="660" height = "420" border="1"  alt="无照片"/>
                 </div>
                 
             <div>
                     <h2> 身份证背面</h2>
            <img src=""  id="picName2" width ="660" height = "420" border="1"  alt="无照片"  />
                 </div>
            
                 <br/>
                <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#picDialog').dialog('close')">确定</a>
            </div>
        </div>
        
        
        <script type="text/javascript">

            $('a').click(function() {
                checkTimeout();
            });

            $.extend($.fn.validatebox.defaults.rules, {
                checkTelAreaLength: {
                    validator: function(value, param) {
                        if (!value)
                            return true;
                        if (!(Math.floor(value) == value && $.isNumeric(value)))
                            return false;

                        return value.length <= param[0];
                    },
                    message: '国际区号最多6个字母且必须为整数'
                }
            });

            $.extend($.fn.validatebox.defaults.rules, {
                checkTel: {
                    validator: function(value, param) {
                        if (!value)
                            return true;
                        if (!(Math.floor(value) == value && $.isNumeric(value)))
                            return false;

                        return value.length >= param[0];
                    },
                    message: '电话号码最少6个字母且必须为整数'
                }
            });

            $.extend($.fn.validatebox.defaults.rules, {
                checkNumber: {
                    validator: function(value, param) {
                        if (!value)
                            return true;
                        return !isNaN(value);
                    },
                    message: '必须为数字'
                }
            });

            $.extend($.fn.validatebox.defaults.rules, {
                checkInteger: {
                    validator: function(value, param) {
                        if (!value)
                            return true;
                        return Math.floor(value) == value && $.isNumeric(value);
                    },
                    message: '必须为整数'
                }
            });

            $.extend($.fn.validatebox.defaults.rules, {
                checkBatchName: {
                    validator: function(value, param) {
                        if (!value)
                            return false;
                        var patt = /^[a-zA-Z]+\d+$/g;
                        return patt.test(value);

                    },
                    message: '必须为字母+整数，比如"A01"，"LS02",并且不能为空'
                }
            });



            var AKTOrderUrl;
            var lastBatchName = "<?php echo $lastBatchName ?>";
            var lastBatchId = "<?php echo $lastBatchId ?>";
            var lastSeq = parseInt("<?php echo $lastBatchSeq ?>");
            
            function closeOrderDialog() {

                $('#AKTOrderTable').datagrid('reload');
                $('#newOrderTable').datagrid('reload');
                $('#batchTable').datagrid('reload');
                $('#userTable').datagrid('reload');
                $('#BindNewOrder_dlg').dialog('close');
                $('#AKTOrder_dlg').dialog('close');
                dialogId = null;
            }

            function newAKTOrder() {
                $('#AKTOrder_dlg').dialog({
                    title: '新建订单',
                    height: 800,
                    modal: true
                });
                $('#AKTOrder_fm').form('clear');
                $("#AKTOrder_created_date").text((new Date()).Format("yyyy-MM-dd hh:mm:ss"));
                $("#AKTOrder_admin").text($("#username").text());

                $("#AKTOrder_order_num").removeAttr("readonly");
                $('#AKTOrder_order_num').css('background-color', 'white');
                $('#AKTOrder_amount').val("1");
                $('#AKTOrder_batch_id').val(lastBatchId);
                $('#AKTOrder_batch_id_value').text(lastBatchId);

                $('#AKTOrder_batch_name_seq').attr('readonly', false);
                $('#AKTOrder_batch_name_seq').css('background-color', 'white');
                $('#AKTOrder_batch_name_seq').val(lastBatchName + (lastSeq + 1));
                $('#AKTOrder_batch_name_seq').focus();
                $('#picButton').linkbutton('disable');
                $('#picButton').linkbutton({text:'照片未上传' });

                $('#AKTOrder_batch_name').val(lastBatchName);
                $('#AKTOrder_batch_seq').val(lastSeq + 1);
                $("#AKTOrder_order_num").focus();

                $('#AKTOrder_dlg').dialog('open');
                dialogId = "AKTOrder_dlg";
                saveFunction = "saveAKTOrder";
                AKTOrderUrl = "../admin/tabPagesBehind/adminAKTOrderB.php?action=2";

            }
            ;

            function saveAKTOrder() {
                $('#AKTOrder_fm').form('submit', {
                    url: AKTOrderUrl,
                    onSubmit: function() {
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');
                        if (result.status == 0) {
                            lastBatchId = result.lastBatchId;
                            generateBatchName();

                            $.messager.show({
                                title: '新建成功',
                                msg: '订单信息储存成功',
                                timeout: 3000,
                                showType: 'slide'
                            });

                        }
                        else if (result.status == 1) {


                            $('#AKTOrder_dlg').dialog('close');
                            $('#AKTOrderTable').datagrid('reload');
                            $('#newOrderTable').datagrid('reload');
                            $('#batchTable').datagrid('reload');
                            $('#userTable').datagrid('reload');
                            setTimeout("selectRow('#AKTOrderTable')", 500);
                        }
                        else {
                            $.messager.alert('错误', result.msg);
                        }
                    }
                });
            }

            function changeRouteDate() {

                if ($("#AKTOrder_order_route_info").val() != "" && $('#AKTOrder_order_route_date').datetimebox('getValue') == "") {
                    var d = new Date();
                    $("#AKTOrder_order_route_date").datetimebox('setValue', d.getDay() + '/' + d.getMonth() + '/' + d.getFullYear() + ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds());
                }

            }


            function editAKTOrder() {

                var row = $('#AKTOrderTable').datagrid('getSelected');
                if (row) {
                    AKTOrderUrl = "../admin/tabPagesBehind/adminAKTOrderB.php?action=4&AKTOrder_id=" + row.id + "&AKTOrder_origi_order_num=" + row.order_num;
                    $('#AKTOrder_dlg').dialog({
                        title: '订单详情',
                        height: 800,
                        modal: true
                    });
                    $("#AKTOrder_batch_name_seq").val(row.batch_name_seq);
                    $("#AKTOrder_batch_name_seq").focus();
                    $('#AKTOrder_batch_name_seq').attr('readonly', true);
                    $('#AKTOrder_batch_name_seq').css('background-color', '#DEDEDE');
                    $('#AKTOrder_batch_id_value').text(row.batch_id);
                    $('#AKTOrder_batch_id').val(row.batch_id);

                    $('#AKTOrder_batch_seq').val(row.batch_seq);
                    $("#AKTOrder_batch_name").val(row.batch_name);
                    

                    $("#AKTOrder_order_num").val(row.order_num);
                    $("#AKTOrder_user_name").val(row.username);
                    $("#AKTOrder_original_user_id").val(row.user_id);

                    $("#AKTOrder_category").val(row.category);
                    $("#AKTOrder_amount").val(row.amount);
                    $("#AKTOrder_weight").val(row.weight);
                    $("#AKTOrder_original_weight").val(row.weight);
                    $("#AKTOrder_certificate").val(row.certificate);
                    $("#AKTOrder_sender_name").val(row.sender_name);
                    $("#AKTOrder_sender_tel").val(row.sender_tel);
                    $("#AKTOrder_sender_tel_area").val(row.sender_tel_area);
                    $("#AKTOrder_sender_email").val(row.sender_email);
                    $("#AKTOrder_receiver_name").val(row.receiver_name);
                    $("#AKTOrder_receiver_tel_area").val(row.receiver_tel_area);
                    $("#AKTOrder_receiver_tel").val(row.receiver_tel);
                    $("#AKTOrder_receiver_email").val(row.receiver_email);
                    $("#AKTOrder_receiver_addr").val(row.receiver_addr);
                    $("#AKTOrder_sender_addr").val(row.sender_addr);
                    $("#AKTOrder_order_route_info").val(row.order_route_info);
                    $("#AKTOrder_order_route_area").val(row.order_route_area);

                    if (row.order_route_date != "0000-00-00 00:00:00") {
                        $("#AKTOrder_order_route_date").datetimebox('setValue', row.order_route_date);
                    }
                    $("#AKTOrder_remarks").val(row.remarks);

                    $("#AKTOrder_created_date").text(row.created_date);
                    $("#AKTOrder_admin").text(row.created_admin);
                    $("#picName").attr("src","");
                    $("#picName2").attr("src","");
                    
                    if(row.pic_name) {
                         $('#picButton').linkbutton('enable');
                         $('#picButton').linkbutton({text:'照片已上传' });
                         $("#picName").attr("src","../uploadID/"+row.batch_id+"/"+row.pic_name);
                    }
    
                     if(row.pic_name2) {
                         $('#picButton').linkbutton('enable');
                         $('#picButton').linkbutton({text:'照片已上传' });
                         $("#picName2").attr("src","../uploadID/"+row.batch_id+"/"+row.pic_name2);
                    }
                    
                    if(!row.pic_name2 &&!row.pic_name ){
                         $('#picButton').linkbutton({text:'照片未上传' });
                         $('#picButton').linkbutton('disable');
                    }
                    
                    $('#AKTOrder_batch_id').focus();
                    $("#AKTOrder_order_num").focus();
                    

                    if (row.status == "已转单") {
                        $("#AKTOrder_order_num").attr("readonly", true);
                        $('#AKTOrder_order_num').css('background-color', '#DEDEDE');
                    }
                    else {
                        $("#AKTOrder_order_num").removeAttr("readonly");
                        $('#AKTOrder_order_num').css('background-color', 'white');
                    }

                    $('#AKTOrder_dlg').dialog('open');
                    dialogId = "AKTOrder_dlg";
                    saveFunction = "saveAKTOrder";
                    rowIndex = $('#AKTOrderTable').datagrid('getRowIndex', row);
                }
                else {
                    $.messager.alert('错误', "请您选择一行");
                }
            }
            ;

            function changeBatchName() {
                $("#AKTOrder_batch_name").val(getBatchName());
                $("#AKTOrder_batch_seq").val(getBatchSeq());

                var lastBatchNameSeq = lastBatchName + (lastSeq + 1);
                if (lastBatchNameSeq != $("#AKTOrder_batch_name_seq").val()) {
                    $('#AKTOrder_batch_id_value').text("");
                    $('#AKTOrder_batch_id').val("");
                }
                else {
                    $('#AKTOrder_batch_id_value').text(lastBatchId);
                    $('#AKTOrder_batch_id').val(lastBatchId);
                }
            }

            function getBatchName() {
                var batch_name_seq = $("#AKTOrder_batch_name_seq").val();
                var pattName = /^[a-zA-Z]+/g;
                return pattName.exec(batch_name_seq);
            }

            function getBatchSeq() {
                var batch_name_seq = $("#AKTOrder_batch_name_seq").val();
                var pattSeq = /\d+$/g;
                var s = pattSeq.exec(batch_name_seq);
                return parseInt(s, 10);
            }

            function generateBatchName() {

                var batchSeq = $("#AKTOrder_batch_seq").val();
                var value = parseInt(batchSeq);
                lastBatchName = $('#AKTOrder_batch_name').val();
                lastSeq = value;
                value++;

                $('#AKTOrder_fm').form('clear');
                $("#AKTOrder_batch_name_seq").val(lastBatchName + value);
                $("#AKTOrder_batch_seq").val(value);
                $('#AKTOrder_batch_name').val(lastBatchName);

                $('#AKTOrder_batch_id').val(lastBatchId);
                $('#AKTOrder_batch_id_value').text(lastBatchId);

                $('#AKTOrder_order_num').focus();
            }

            function delAKTOrder() {
                var row = $('#AKTOrderTable').datagrid('getSelected');
                if (row) {
                    $.messager.confirm('Confirm', '您确定要删除这条记录吗?', function(r) {
                        if (r) {
                            $.post('../admin/tabPagesBehind/adminAKTOrderB.php?action=3', {order_id: row.id, batch_id: row.batch_id, user_id: row.user_id, weight: row.weight}, function(result) {
                                if (result.success) {
                                    $('#AKTOrderTable').datagrid('reload');
                                    $('#BatchTable').datagrid('reload');
                                } else {
                                    $.messager.alert('错误', result.msg);
                                }
                            }, 'json');
                        }
                    });
                }
            }
            function bindNewOrder() {

                var row = $('#AKTOrderTable').datagrid('getSelected');
                if (row) {

                    if (row.status == "已转单") {
                        $.messager.alert('错误', "该订单已经转单");
                        return;
                    }

                    $('#BindNewOrder_dlg').dialog({
                        title: '一对一转新订单',
                        modal: true
                    });
                    $("#AKTOrder_id").val(row.id);
                    $("#AKTOrder_num").text(row.order_num);
                    $("#New_Order_Num").focus();
                    $('#BindNewOrder_dlg').dialog('open');
                    $("#new_order_new_company").val("shunfeng");
                    dialogId = "BindNewOrder_dlg";
                    saveFunction = "saveBindNewOrder";
                    rowIndex = $('#AKTOrderTable').datagrid('getRowIndex', row);
                }
                else {
                    $.messager.alert('错误', "请您选择一行");
                }

            }


            function saveBindNewOrder() {
                $('#BindNewOrder_fm').form('submit', {
                    url: '../admin/tabPagesBehind/adminNewOrderB.php?action=2',
                    onSubmit: function() {
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');
                        if (result.success) {
                            $('#AKTOrderTable').datagrid('reload');
                            $('#newOrderTable').datagrid('reload');
                            $('#BindNewOrder_dlg').dialog('close');
                            setTimeout("selectRow('#AKTOrderTable')", 500);
                        }
                        else {
                            $.messager.alert('错误', result.msg);
                        }
                    }
                });
            }
            ;
            function bindMultipleNewOrder() {
                alert("搞不懂业务逻辑")
            }
            ;

            function SearchAKTOrder() {
                $('#AKTOrderTable').datagrid('load', {
                    searchOrder: $("#AKTOrderSearchBox").val(),
                    searchTimeTo: $('#AKTOrderSearchTo').datetimebox('getValue'),
                    searchTimeFrom: $('#AKTOrderSearchFrom').datetimebox('getValue')
                });

                $("#AKTOrderSearchTo").datetimebox('setValue', "");
                $("#AKTOrderSearchFrom").datetimebox('setValue', "");
            }
            ;

            function resetSearchAKTOrder() {
                $('#AKTOrderTable').datagrid('load', {
                    searchOrder: "",
                    searchTimeTo: "",
                    searchTimeFrom: ""
                });
                $("#AKTOrderSearchBox").val("");
                $("#AKTOrderSearchTo").datetimebox('setValue', "");
                $("#AKTOrderSearchFrom").datetimebox('setValue', "");
            }
            ;

            function printOrder() {
                var row = $('#AKTOrderTable').datagrid('getSelected');
                if (row) {
                    $("#mp_AKT_order").text(row.order_num);
                    $("#mp_batch_seq").text(row.batch_name + row.batch_seq);
                    $("#mp_category").text(row.category + "*" + row.amount);
                    $("#Amp_receiver_tel").text(row.receiver_tel);
                    $("#mp_receiver_addr").text(row.receiver_addr);
                    $("#mp_receiver_name").text(row.receiver_name);
                    $("#myPrintArea").jqprint();
                }
                else {
                    $.messager.alert('错误', "请您选择一行");
                }
            }

        </script>
    </body>
</html>
