<?php require ('tabPagesHeader.php') ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <div id="toolBar">
            <div >
             <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">添加设置</a>
             <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="delUser()">编辑设置</a>
             <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="delUser()">删除设置</a>
             </div>

        </div>
     <table class="easyui-datagrid" toolbar="#toolbar" pagination="true"  singleSelect="true"  fitColumns="true"
            url="datagrid_data1.json" method="get" rownumbers="true" id="adminTable"
            >
        <thead>
            <tr>
                <th data-options="field:'itemid',width:80">设置名</th>
                <th data-options="field:'listprice',width:100">创建时间</th>
            </tr>
        </thead>
    </table>
    <script type="text/javascript">
       function newUser() {alert("new User")};
       function delUser() {alert("del User")};
       function destroyUser() {alert("destroy User")};
       function doSearch(){alert("Search User")};
    </script>
    </body>
</html>
