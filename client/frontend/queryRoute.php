<?PHP
$status = $_POST["routeStatus"];
$info = $_POST["routeInfo"];
$orderNum = $_POST["orderNum"];
$newOrderNum =   $_POST["orderNewNum"];  
$orderNewCompany =  $_POST["orderNewCompany"];  
// 将JSON格式数据进行解码，解码后不是JSON数据格式，不可用echo直接输出 
$arr = json_decode(stripslashes($info), true);
?>
<html>
    <head>
        <title>能通速递</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../../css/main.css" />
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/spin.min.js"></script>
        <script language="javaScript" >
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


            $(document).ready(function() {
                $('#headerContent').load('../../header.html');
                $('#bottomContent').load('../../bottom.html');
            });

            function query() {

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
                    className: 'spinner', // The CSS class to assign to the spinner
                    zIndex: 2e9, // The z-index (defaults to 2000000000)
                    top: '50%', // Top position relative to parent
                    left: '50%' // Left position relative to parent
                };
                var target = document.getElementById('spin');
                spinner = new Spinner(opts).spin(target);
                loading();
                //  $("#spin").show();
                setTimeout("startQuery()", 3000);

            }

            function queryKey(event) {
                if (event.keyCode == 13) {
                    query();
                }
            }

            function startQuery() {
                var orderNum = $("#order_num").val();
                $.post('../backend/queryRoute.php', {orderNum: orderNum}, function(result) {

                    if (result.status == 0) {

                        $("#routeStatus").val(result.status);
                        $("#routeInfo").val(json2str(result.msg));
                        $("#orderNum").val(orderNum);
                         $("#orderNewNum").val(result.newOrderNumber);
                         $("#orderNewCompany").val(result.newOrderCompany);
                        spinner.stop();
                        unloading();
                        //  $("#spin").hide();
                        $("#routeForm").submit();
                    } else  {
                        spinner.stop();
                        unloading();
                        alert(result.msg);
                    }
                }, 'json');
            }

            $(document).ready(function() {
                $("#orderTips").load("../../static/orderTips.html");
            });

            function loading() {
                $("body").addClass('overlay');
                document.addEventListener("click", handler, true);
                $("#order_num").attr("disabled", "disabled");

            }
            function handler(e) {
                e.stopPropagation();
                e.preventDefault();
            }

            function unloading() {
                $("body").removeClass('overlay');
                document.removeEventListener("click", handler, true);
                $("#order_num").removeAttr("disabled");
            }

        </script>
    </head>


    <body>
        <div id="headerContent"></div>

        <div style="background-color:#EEEEEE;">

            <div style="width:984px;margin:0 auto;">
                <div style="height:19px;width:100%"></div>
                <hr style="margin: 0px;"/>
                <div class="queryHeadDiv">
                    <div > 快件跟踪信息:</div>
                </div>
            </div>


            <div id="mainDiv" style="width:984px;margin:0 auto;background-color:white;">
                <div style="height:7px;width:100%"></div>
                <div class="dashline" style="margin:0 28px;height:18px"></div>
                <div class="queryBlueDiv">
                    <div class="blueWhite" style="float:left;" ><span style="margin:0 25px;">订单号</span> <?PHP echo $orderNum ?><span style="margin-left:10px;">转运公司</span> <?PHP echo $orderNewCompany ?><span style="margin-left:10px;">转运订单号</span> <?PHP echo $newOrderNum ?></div>

                    <span style="float:right"><input type="text" value="请输入订单号"  id="order_num" style="border:0;height:28px;width:200px;background-color:#F0F0F0;color:#9B9B9B" onfocus="javascript:$('#order_num').val('');" onkeydown="queryKey(event);"/>
                        <img src="../../imgs/query.png" style="position: relative;top:5px;right:37px;border: 0 none;cursor: pointer" onclick="query()"/></span>
                </div>

                <div class="queryTableDiv" >

                    <?PHP
                    $colorNum = 0;
                    $style = "style='color:black'";
                    foreach (array_reverse($arr) as $i => $v) {

                        echo " <table class='routeTable'><tr><th width='110px;'></th> <th width='145px'></th><th width='600px'></th> </tr>";
//                        $citys = array();
//                        $j = 0;
//                        foreach ($v["items"] as $index => $value) {
//
//                            $citys[$j] = $value["city"];
//                            $j++;
//                        }


                        $i = 0;
                        foreach (array_reverse($v["items"]) as $index => $value) {
                            $i++;
                            $colorNum++;
                            if ($i == count($v["items"])) {
                                echo "<tr><td class='dateColumn' >" . $v["date"] . "</td>";
                            } else {
                                echo "<tr><td ></td>";
                            }

//                            if ($i == count($v["items"])) {
//                                echo " <td class='cityColumn '" . $style . " > <img src='../../imgs/query_circle.png' width='16' height='16' style='margin:0 5px 0 20px'>" . $value["city"] . "</td> ";
//                            } else if ($citys[$i] != $value["city"]) {
//                                echo " <td class='cityColumn '" . $style . " > <img src='../../imgs/query_circle.png' width='16' height='16' style='margin:0 5px 0 20px'>" . $value["city"] . "</td> ";
//                            } else {
//                                echo " <td class='cityColumn '" . $style . "> </td>";
//                            }

                            $itemInfo = $value["info"];

                            if (strlen($itemInfo) > 100) {
                                $itemInfo = mb_strcut($itemInfo, 0, 95, 'utf-8') . "...";
                            }

                            if ($style <> "") {
                                echo " <td class='timeColumn '" . $style . " > --- " . substr($value["time"], 0, 5) . "<img src='../../imgs/dul_arrow_g.jpg' width='50' height='10' style='margin:0 10px' >  </td> ";
                            } else {
                                echo " <td class='timeColumn '" . $style . " > --- " . substr($value["time"], 0, 5) . "<img src='../../imgs/dul_arrow_w.jpg' width='50' height='10' style='margin:0 10px' >  </td> ";
                            }
                            if ($colorNum % 2 == 1) {
                                echo " <td class='infoColumn'" . $style . " > <span title=" . $value["info"] . "> " . $itemInfo . "</span></td></tr>";
                            } else {
                                echo " <td class='infoColumn2'" . $style . " > <span title=" . $value["info"] . "> " . $itemInfo . "</span></td></tr>";
                            }
                            $style = "";
                        }

                        echo "</table><div class='dashline' style='margin:0 12px 0 0;height:2px;position:relative;bottom:3px;'></div>";
                    }
                    ?>         
                </div>
                <div id="spin"></div>
                
                <div id="orderTips" class="orderTips"></div>
                <div class="queryShadow" >


                </div>
                <div style="display:none">
                    <form action="queryRoute.php?title=22" id='routeForm' method="post">
                        <textarea id='routeStatus' name='routeStatus'></textarea>
                        <textarea id='routeInfo' name='routeInfo'></textarea>
                        <textarea id='orderNum' name='orderNum'></textarea>
                                        <input id='orderNewNum' name='orderNewNum'/>
                                                      <input id='orderNewCompany' name='orderNewCompany'/>
                    </form>
                </div>

                <div id="bottomContent"> </div>
            </div>

        </div>


    </body>
</html>
