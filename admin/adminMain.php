<?php
         date_default_timezone_set('Asia/Hong_Kong');
          session_start();
        
        if (!isset($_SESSION['userAdmin'])) {
            header('Location: ../global/errorPage.php');
        }
        ?>

<html>

    <head>

        <meta charset="UTF-8"/>
        <title>能通速递管理系统</title>
        <link rel="stylesheet" type="text/css" href="../css/themes/default/easyui.css"/>
        <link rel="stylesheet" type="text/css" href="../css/themes/icon.css"/>
        <link rel="stylesheet" type="text/css" href="../css/main.css"/>
        <script type="text/javascript" src="../js/jquery.min.js"></script>
        <script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="../js/jquery.easyui-cn.js" charset="utf-8"></script>
        <script type="text/javascript" src="../js/util.js"></script>
    <script src="../js/nicEdit.js" type="text/javascript"></script>
        <script type="text/javascript" src="../js/datagrid-detailview.js"></script>
        <script type="text/javascript" src="../js/jquery.jqprint.js"></script>
        <script type="text/javascript">
            var indexTitle;
            var dialogId;
            var saveFunction;
            var rowIndex;

            $(document).ready(function() {

                checkTimeout();
                $("#mainPanel").height($(window).height() - 150);

                $("#mainPanel").layout("resize", "");
                $("#mainTree").tree("expandAll", "");


//var node = $('#mainTree').tree('find',"remove");

// $("#mainTree").tree("remove",node.target);
                $('#mainTree').tree({
                    onClick: function(node) {
                        var value = $("#mainTree").tree('isLeaf', node.target);
                        if (!value)
                            return;

                        if (!($("#tabPanel").tabs('exists', node.text))) {
                            $('#tabPanel').tabs('add', {
                                title: node.text,
                                href: node.attributes,
                                closable: true
                            });
                        } else {
                            $("#tabPanel").tabs('select', node.text);
                        }
                    }
                });

                $("#tabPanel").tabs({
                    onSelect: function(title) {
                        indexTitle = title;
                    }

                });

                $(window).click(function() {
                    checkTimeout();
                });
                $('li').click(function() {
                    checkTimeout();
                });


                $(this).keydown(function(e) {

                    if (e.altKey && e.keyCode == 65) {
                        e.preventDefault();
                        if (indexTitle == "管理员管理") {
                            newAdmin();
                        }

                        if (indexTitle == "会员管理") {
                            watchUser();
                        }

                        if (indexTitle == "会员卡管理") {
                            newCard();
                        }

                        if (indexTitle == "订单管理") {
                            newAKTOrder();
                        }

                        if (indexTitle == "批次管理") {
                            addBatchRoute();
                        }

                        if (indexTitle == "卡类型管理") {
                            newCardType();
                        }

                        if (indexTitle == "新闻管理") {
                            newNews();
                        }

                        if (indexTitle == "公告管理") {
                            newNotice();
                        }
                    }

                    // alt+s
                    if (e.altKey && e.keyCode == 83) {
                        e.preventDefault();
                        if (indexTitle == "会员管理") {
                            deliveryCard();
                        }

                        if (indexTitle == "会员卡管理") {
                            bindUser();
                        }
                        if (indexTitle == "AKT订单管理") {
                            editAKTOrder();
                        }

                        if (indexTitle == "新订单管理") {
                            editNewOrder();
                        }

                        if (indexTitle == "批次管理") {
                            refreshBatchTable();
                        }

                        if (indexTitle == "卡类型管理") {
                            editCardType();
                        }

                        if (indexTitle == "新闻管理") {
                            editNews();
                        }

                        if (indexTitle == "公告管理") {
                            editNotice();
                        }
                        
                        if (indexTitle == "批次路线管理") {
                            editBatchRoute();
                        }

                        if (indexTitle == "留言管理") {
                            replyFeedback();
                        }
                    }


                    //alt+z
                    if (e.altKey && e.keyCode == 90) {
                        e.preventDefault();
                        if (indexTitle == "管理员管理") {
                            removeAdmin();
                        }
                        if (indexTitle == "会员管理") {
                            delUser();
                        }
                        if (indexTitle == "会员卡管理") {
                            delCard();
                        }

                        if (indexTitle == "订单管理") {
                            delAKTOrder();
                        }

                        if (indexTitle == "新订单管理") {
                            delNewOrder();
                        }

                        if (indexTitle == "卡类型管理") {
                            removeCardType();
                        }

                        if (indexTitle == "新闻管理") {
                            removeNews();
                        }

                        if (indexTitle == "公告管理") {
                            removeNotice();
                        }

                        if (indexTitle == "留言管理") {
                            delFeedback();
                        }
                        
                        if (indexTitle == "批次路线管理") {
                            delBatchRoute();
                        }
                    }

                    //alt+q
                    if (e.altKey && e.keyCode == 81) {
                        e.preventDefault();

                        if (indexTitle == "管理员管理") {
                            searchAdmin();
                        }
                        if (indexTitle == "会员管理") {
                            searchUser();
                        }

                        if (indexTitle == "会员卡管理") {
                            searchCard();
                        }

                        if (indexTitle == "订单管理") {
                            SearchAKTOrder();
                        }

                        if (indexTitle == "批次管理") {
                            searchBatch();
                        }

                        if (indexTitle == "新订单管理") {
                            newOrderSearch();
                        }
                    }

                    //alt+w
                    if (e.altKey && e.keyCode == 87) {
                        e.preventDefault();

                        if (indexTitle == "管理员管理") {
                            resetSearchAdmin();
                        }
                        if (indexTitle == "会员管理") {
                            resetSearchUser();
                        }

                        if (indexTitle == "会员卡管理") {
                            resetSearchCard();
                        }

                        if (indexTitle == "订单管理") {
                            resetSearchAKTOrder();
                        }

                        if (indexTitle == "批次管理") {
                            resetSearchBatch();
                        }

                        if (indexTitle == "新订单管理") {
                            resetNewOrderSearch();
                        }
                    }


                    //ctrl+enter
                    if (e.ctrlKey && e.keyCode == 13) {
                        e.preventDefault();

                        if (dialogId && saveFunction) {
                            eval(saveFunction + "()");
                        }
                    }
                    
                     if ( e.keyCode == 13) {
                        e.preventDefault();

                    }


                    //Esc

                    if (e.keyCode == 27) {
                        e.preventDefault();
                        
                        if(indexTitle=="订单管理"){
                             closeOrderDialog();
                        }
                        
                        else if (dialogId) {
                            $('#' + dialogId).dialog('close');

                        }
                        dialogId = null;
                    }


                });

            });

            function selectRow(name) {
                $(name).datagrid('selectRow', rowIndex);
                rowIndex = -1;
            }
            
            function isIE() {
                var myNav = navigator.userAgent.toLowerCase();
          
                return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
            }

            var ie = isIE();
        </script>
    </head>


    <body>
        <div >
            <div style="vertical-align: middle;line-height: 89px;padding-top:11px;" >
                <div style="text-align:center;">  <h2 style="margin:0;font-family: '黑体',Arial Unicode MS,Arial,sans-serif;vertical-align: middle">能通物联后台管理系统</h2></div>
            </div>     
        </div>
        <div style="text-align: right;font-family: arial,\5b8b\4f53,sans-serif;line-height: 20px;font-size:12px; ">
            欢迎回来，<b><span id="username"><?php echo $_SESSION['username'] ?></span></b>  <a style="text-decoration:none;" href="tabPagesBehind/adminLoginB.php?action=2">退出</a> 

        </div>
        <div class="easyui-layout" id="mainPanel" style="width:100%;">
            <div data-options="region:'west',split:false" title="功能菜单" style="width:200px;">
                <ul class="easyui-tree" data-options="animate:true" id="mainTree">
                    <li>
                        <span>个人信息管理</span>
                        <ul>
                            <li data-options="attributes:'tabPages/adminPass.php?action=1'">
                                <span>登录密码修改</span>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <span>管理员管理</span>
                        <ul>
                            <li data-options="attributes:'tabPages/adminManagement.php?action=1'">
                                <span>管理员管理</span>
                            </li> 
                        </ul>   
                    </li>
                    <li>
                        <span>会员管理</span>
                        <ul>
                            <li data-options="attributes:'tabPages/adminUser.php?action=1'">
                                <span>会员管理</span>
                            </li> 
                            <li data-options="attributes:'tabPages/adminCard.php?action=1'">
                                <span>会员卡管理</span>
                            </li> 
                        </ul>
                    </li>
                    <li>
                        <span>订单管理</span>
                        <ul>
                            <li data-options="attributes:'tabPages/adminAKTOrder.php?action=1'">
                                <span>订单管理</span>
                            </li>
                            <li data-options="attributes:'tabPages/adminNewOrder.php?action=1'">
                                <span>新订单管理</span>
                            </li> 

                        </ul>
                    </li>
                     <li>
                        <span>批次管理</span>
                        <ul>
                            <li data-options="attributes:'tabPages/adminBatch.php?action=1'">
                                <span>批次操作</span>
                            </li>
                            <li data-options="attributes:'tabPages/adminBatchRoute.php?action=1'">
                                <span>批次路线管理</span>
                            </li>

                        </ul>
                    </li>
                    
                    <li>
                        <span>初始化管理</span>
                        <ul>
                            <!--    <li data-options="attributes:'tabPages/adminPrintOrder.php?action=1'">
                                <span>订单打印设置</span>
                                </li> -->
                            <li data-options="attributes:'tabPages/adminCardType.php?action=1'">
                                <span>卡类型管理</span>
                            </li> 
                        </ul>
                    </li>
                    <li>
                        <span>前台内容管理</span>
                        <ul>
                            <li data-options="attributes:'tabPages/adminNews.php?action=1'">
                                <span>新闻管理</span>
                            </li> 
                            <li data-options="attributes:'tabPages/adminNotice.php?action=1'">
                                <span>公告管理</span>
                            </li> 

                            <li data-options="attributes:'tabPages/adminFeedback.php?action=1'">
                                <span>留言管理</span>
                            </li> 

                            <li data-options="attributes:'tabPages/adminTips.php?action=1'">
                                <span>温馨提示管理</span>
                            </li> 

                            <li data-options="attributes:'tabPages/adminOrderTips.php?action=1'">
                                <span>下单提示管理</span>
                            </li> 
                             <li data-options="attributes:'tabPages/adminNoticeIndex.php?action=1'">
                                <span>公告首页管理</span>
                            </li> 
                        </ul>
                    </li>                    
                </ul>

            </div>
            <div data-options="region:'center',title:'能通物联管理系统'" >
                <div id='tabPanel' class='easyui-tabs' >
                    <div title='首页' data-options='closable:false' style='overflow:auto;padding:20px;'> 
                        嗨，我是Nandy. 是这个网站的开发程序员，如果您遇到什么问题？你通过以下方式联系我: <br/>
                        <br/>
                        我的邮箱：ouyangnandi@hotmail.com  <br/>
                        我的电话：+852 5932 7952 （香港）  <br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        +86 18874068673 （中国大陆） <br/>
                        我的微信：648946479  <br/>
                        -------------------------------------我是冷艳高贵的分割线---------------------------------------------------------------------------------------------------------------<br/>
                        <br/>
                        2014.06.17 网站添加了一个邮箱：info@canexpress.com.au 作为网站标准的联系方式.
                        不过目前只能我打开。如果想使用这个邮箱，请联系我。
                        <br/>     <br/>
                        目前订单查询功能只支持6个快递公司：天天，ＴＮＴ，宅急送，汇通，EMS，顺丰，
                        如果想支持更多的快递公司，麻烦先跟我商量。
                        <br/>     <br/>
                        同时我让订单查询功能一次只能查询一个订单，主要原因是多单查询带来的不稳定性。
                        怕出现同时查几个单，一些单能查询出，一些查询不出的情况。
                        因为网站的订单查询信息是通过第三方免费接口，
                        对方明确指出如果查询过于频繁，则会屏蔽我们的网站<br/>
                        <br/>
                        -------------------------------------我是冷艳高贵的分割线---------------------------------------------------------------------------------------------------------------<br/>
                        <br/>
                        2014.06.15 网站增加了很多快捷键：<br/>
                        <br/>
                        一. 所有的对话框中，有2个按钮：确定按钮，取消按钮。<br/>
                        确定按钮 快捷键：CTRL+ENTER　<br/>
                        取消按钮 快捷键：ESC <br/>
                        <br/>
                        二. 有些页面的部分按钮增加了快捷键<br/>
                        查询 快捷键：ALT+Q　<br/>
                        重置查询 快捷键：ALT+W <br/>
                        <br/>
                        三. 当鼠标移动按钮上二秒，会显示这个按钮的快捷键。如果没有显示，就表示这个按钮没有快捷键<br/>
                    </div>

                </div>
            </div>
        </div>

        <div style="text-align: center;margin-top:10px;font-family: arial;font-size: 12px;color: #666;">
            @2014 能通速递 版权所有 
        </div>

    </body>
</html>
