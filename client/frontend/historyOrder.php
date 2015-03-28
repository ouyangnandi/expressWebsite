<?php
session_start();
if (isset($_SESSION['username'])) {
    
} else {
    header("Location: ../frontend/userLogin.html");
}
?>


<html>
    <head>
        <title>历史订单</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../../css/main.css" />
        <link rel="stylesheet" type="text/css" href="../../css/themes/default/easyui.css"/>
        <link rel="stylesheet" type="text/css" href="../../css/themes/icon.css"/>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.easyui-cn.js" charset="utf-8"></script>
        <script type="text/javascript" src="../../js/spin.min.js"></script>

        <script language="javascript">
            var spinner = null;
            function json2str(o) {
                var arr = [];
                var fmt = function(s) {
                    if (typeof s == 'object' && s != null)
                        return json2str(s);
                    return /^(string|number)$/.test(typeof s) ? "\"" + s + "\"" : s;
                }
                for (var i in o)
                    arr.push("\"" + i + "\":" + fmt(o[i]));
                return '{' + arr.join(',') + '}';
            }

            function queryRoute(orderNum) {

                var opts = {
                    lines: 14, // The number of lines to draw
                    length: 12, // The length of each line
                    width: 3, // The line thickness
                    radius: 10, // The radius of the inner circle
                    corners: 1, // Corner roundness (0..1)
                    rotate: 0, // The rotation offset
                    direction: 1, // 1: clockwise, -1: counterclockwise
                    color: '#C0D4FE', // #rgb or #rrggbb or array of colors
                    speed: 1, // Rounds per second
                    trail: 100, // Afterglow percentage
                    shadow: true, // Whether to render a shadow
                    hwaccel: false, // Whether to use hardware acceleration
                    className: 'spinnerCool', // The CSS class to assign to the spinner
                    zIndex: 2e9, // The z-index (defaults to 2000000000)
                    top: '50%', // Top position relative to parent
                    left: '50%' // Left position relative to parent
                };
                var target = document.getElementById('spin');
                spinner = new Spinner(opts).spin(target);
                 $("#spin").show();
               loading();

                setTimeout("query('" + orderNum + "')", 3000);

            }

            function query(orderNum) {
                $.post('../backend/queryRoute.php', {orderNum: orderNum}, function(result) {

                    if (result.status == 0) {
                        $("#routeStatus").val(result.status);
                        $("#routeInfo").val(json2str(result.msg));
                        $("#orderNum").val(orderNum);

                         $("#routeForm").submit();
                    } else if (result.status == 1) {
                        unloading();

                        alert("订单号不存在");
                    } else if (result.status == 2) {
                        unloading();

                        alert("很抱歉，查询出现错误，请重新查询一遍");
                    } else if (result.status == 3) {
                        unloading();

                        alert("订单号格式不对");
                    }
                }, 'json');
            }

            function loading() {
                $("body").addClass('overlay');
                document.addEventListener("click", handler, true);
            }
            function handler(e) {
                e.stopPropagation();
                e.preventDefault();
            }

            function unloading() {
                spinner.stop();
                $("body").removeClass('overlay');
                document.removeEventListener("click", handler, true);

            }

        </script>
    </head>
    <body>

        <div id="headerContent"></div>


        <div style="background-color:#EEEEEE;padding-top:14px;">
            <div style="width:984px;margin:0 auto;">
                <div style="text-align:right;"><a href="#" class="accoutExit" id="logout" onclick="logout();">退出</a></div>
                <div class="dashline" style="margin:0;height:15px"></div>


            </div>
            <div id="mainDiv" style="width:984px;margin:0 auto;background-color:white;">

                <div style="height:22px;width:100%"></div>
                <div style="text-align:center; font-family:微软雅黑;"><span><h3 style="font-weight:normal;color:#3c4980">历史订单</h3></span></div>
                <div style="height:20px;width:100%"></div>
                <div style="margin:0 50px" style="text-align: center"> <img src="../../imgs/gredient_account.png" width="900"/></div>
                <div style="margin:0 70px" class="accountLinks"><a href="account.php"> <img src="../../imgs/account_info.png" /> </a><a href="accountPass.php"><img src="../../imgs/account_pass.png" /> </a><a href="historyOrder.php"><img src="../../imgs/account_order.png" /></a></div>
                <div class="dashline" style="margin:0 70px;height:30px"></div>
                <div  style="margin:0 70px;height:25px;text-align: center;"><hr  color="#828282" style="margin: 0px;"/>
                    <div style="font-family:微软雅黑;background-color:white;width:100px;text-align: center;position:relative;bottom:12px;display:inline-block;"><h4 style="margin:0px;" >详细信息</h4></div>
                </div>

                <div style="margin:30px 70px 40px 70px;height:120px;background-color: #F7F7F7">

                    <div style="height:25px"></div>
                    <div style="margin:0 15px;text-align: center;height:25px;"><div class="accountDetail" style="width:50%"><div >帐号</div><div id="username"></div></div><div class="accountDetail" style="width:50%"><div>注册时间</div><div id="created_date" style="width:200px"></div></div></div>
                    <div style="height:25px"></div>
                    <div style="margin:0 15px;text-align: center;height:25px;"><div class="accountDetail" style="width:50%"><div >邮箱</div><div id="email"></div></div><div class="accountDetail" style="width:50%"><div>积分</div><div id="credits"></div></div></div>
                    <div style="height:25px;width:100%" id="spin"></div>

                </div>

                <div  style="margin:0 70px;height:25px;text-align: center;"><hr  color="#828282" style="margin: 0px;"/>
                    <div style="font-family:微软雅黑;background-color:white;width:150px;text-align: center;position:relative;bottom:12px;display:inline-block;"><h4 style="margin:0px;" >历史订单</h4></div>
                </div>

                <div style="margin:25px 70px 50px 70px;height:70px;">

                    <div style="display: inline-block;float:left;width:220px;margin-right:20px;">
                        <div class="accountPassText">订单号</div>
                        <div class="accountPassInput"><input id="searchOrder" name="searchOrder"/></div>
                    </div>


                    <div style="display: inline-block;float:left;width:220px;margin-right:20px;">
                        <div class="accountPassText">开始时间</div>
                        <div class="accountPassInput"><input class="easyui-datetimebox" style="width: 200px" id="searchTimeFrom" name="searchTimeFrom"/></div>
                    </div>

                    <div style="display: inline-block;float:left;width:220px;margin-right:20px;">
                        <div class="accountPassText">截至时间</div>
                        <div class="accountPassInput"><input class="easyui-datetimebox" style="width: 200px" id="searchTimeTo" name="searchTimeTo" /></div>
                    </div>

                    <img src="../../imgs/submitBotton.jpg" style="display: inline-block;float:left;margin-top: 37px;cursor:pointer" onclick="search()">

                    </img>
                     
                </div>
                <div  style="margin:0 70px;height:25px;text-align: center;"><hr  color="#828282" style="margin: 0px;"/>
                    <div style="font-family:微软雅黑;background-color:white;width:150px;text-align: center;position:relative;bottom:12px;display:inline-block;"><h4 style="margin:0px;" >查询结果</h4></div>
                </div>

                <table class="orderTable" id="historyTable">
                    <tr><th>编号</th><th>订单号</th><th>录单时间</th><th style="text-align:right">路线信息</th></tr>

                </table>

                <div style="display:none">
                    <form action="queryRoute.php?title=22" id='routeForm' method="post">
                        <textarea id='routeStatus' name='routeStatus'></textarea>
                        <textarea id='routeInfo' name='routeInfo'></textarea>
                        <textarea id='orderNum' name='orderNum'></textarea>
                    </form>
                </div>

                <div style="height:10px"></div>
                <div id="bottomContent"> </div>
            </div>


            <script type="text/javascript" >



                $(document).ready(function() {
                    $('#headerContent').load('../../header.html');
                    $('#bottomContent').load('../../bottom.html');

                    $.post('../backend/historyOrderB.php?action=1', function(result) {

                        var result = eval('(' + result + ')');

                        if (result.status == 1) {
                            $("#username").text(result.username);
                            $("#created_date").text(result.created_date);
                            $("#email").text(result.email);

                            if (result.card_num == null) {
                                $("#credits").text("未绑定会员卡");
                            }
                            else {
                                $("#credits").text(result.credits);
                            }
                            var rows = result.info;
                            for (var i in rows) {
                                if (i % 2 == 0) {
                                    $('#historyTable').append("<tr class='orderTableT1'><td>" + rows[i].id + "</td><td>" + rows[i].order_num + "</td><td>" + rows[i].created_date + "</td><td style='text-align:right'><a href='#' onclick='queryRoute(\"" + rows[i].order_num + "\")'>查看路线信息</a></td></tr>");
                                }
                                else {
                                    $('#historyTable').append("<tr class='orderTableT2'><td>" + rows[i].id + "</td><td>" + rows[i].order_num + "</td><td>" + rows[i].created_date + "</td><td style='text-align:right'><a href='#' onclick='queryRoute(\"" + rows[i].order_num + "\")'>查看路线信息</a></td></tr>");

                                }
                            }
                        } else {
                            $.messager.alert('错误', "网站内部错误，请联系管理员");

                        }
                    });
                });



                function search() {
                    $('#historyTable').html("");
                    $('#historyTable').append("<tr><th>编号</th><th>订单号</th><th>录单时间</th><th style='text-align:right'>路线信息</th></tr>");
                    var orderNum = $("#searchOrder").val();
                    var timeFrom = $("#searchTimeFrom").datetimebox('getValue');
                    var timeTo = $("#searchTimeTo").datetimebox('getValue');

                    $.post('../backend/historyOrderB.php?action=2', {searchOrder: orderNum, searchTimeFrom: timeFrom, searchTimeTo: timeTo}, function(result) {
                        var result = eval('(' + result + ')');
                        if (result.status == 1) {
                            var rows = result.info;
                            for (var i in rows) {
                                if (i % 2 == 0) {
                                    $('#historyTable').append("<tr class='orderTableT1'><td>" + rows[i].id + "</td><td>" + rows[i].order_num + "</td><td>" + rows[i].created_date + "</td><td style='text-align:right'><a href='#' onclick='queryRoute(\"" + rows[i].order_num + "\")'>查看路线信息</a></td></tr>");
                                }
                                else {
                                    $('#historyTable').append("<tr class='orderTableT2'><td>" + rows[i].id + "</td><td>" + rows[i].order_num + "</td><td>" + rows[i].created_date + "</td><td style='text-align:right'><a href='#' onclick='queryRoute(\"" + rows[i].order_num + "\")'>查看路线信息</a></td></tr>");

                                }
                            }

                        } else {
                            $.messager.alert('错误', "网站内部错误，请联系管理员");

                        }
                    });

                }

                function logout() {

                    $.post('../backend/userLogin.php?action=1', function() {
                        location.href = "../../index.html";
                    });


                }

            </script>


        </div>


    </body>
</html>
