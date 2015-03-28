<?php
        require('../../global/init.php');
        $con = DatabaseConn::getConn();
        $batchId = $_REQUEST['batchId'];
        $batchName= $_REQUEST['batchName'];
        mysqli_query($con, "set names 'utf8'");
        $rs = mysqli_query($con, "select * from tb_route where batch_id=". $batchId );
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>批次管理</title>
    
</head>
     <body>
        <table  id="routeTable<?php echo $batchId?>"  class="routeTableStyle" border="0" cellspacing="0" cellpadding="0">
         <col width="5%">
         <col width="40%">
         <col width="15%">
         <col width="15%">
          <col width="15%">
           <col width="10%">
            <thead>
            <tr>
                <th>路线ID</th>
                <th>描述</th>
                <th>地区</th>
                <th>更新时间</th>
                <th >更新人</th>
                <th ></th>
            </tr>
            </thead>
            <?php 
                while ($row = mysqli_fetch_object($rs)) {
                    
                    $descId = $batchId."_route_".$row->id."_desc";
                    $rowId = $batchId."_route_".$row->id."_row";
                    $areaId = $batchId."_route_".$row->id."_area";
                    $dateId = $batchId."_route_".$row->id."_date";
                    
                    echo "<tr id='$rowId'><td>$row->id</td>";
                    echo "<td id='$descId'>$row->description</td>";
                    echo "<td id='$areaId'>$row->area</td>";
                    echo "<td id='$dateId'>$row->updated_date</td>";
                    echo "<td>$row->updated_admin</td>";
                    echo "<td><span title='编辑路线'><a href='#' onclick='editRoute($row->id,\"$descId\",\"$areaId\",\"$dateId\")'><img src='../imgs/pencil.png' style='border:none'/></a></span>  &nbsp;";
                    echo "<span title='删除路线'>  <a href='#'  onclick='delRoute($row->id,\"$rowId\",\"$batchId\",\"$dateId\")'><img src='../imgs/edit_remove.png'  style='border:none'/></a> </span></td>";
                    echo "</tr>";
                }
         
            ?>
         
    </table>


    <script type="text/javascript">

       function delRoute(id,rowId,batchId) {
                    $.messager.confirm('Confirm', '您确定要删除这条路线吗?', function(r) {
                        if (r) {
                            $.post('../admin/tabPagesBehind/adminRouteB.php?action=3', {id: id,route_batch_id:batchId}, function(result) {
                                if (result.success) {
                                     $("#"+rowId).remove();
                                } else {
                                    $.messager.alert('错误', result.msg);
                                }
                            }, 'json');
                        }
                    });
       };
       
       function editRoute(id,descId,areaId,dateId) {
             routeUrl="../admin/tabPagesBehind/adminRouteB.php?action=4&routeId="+id;
                 $('#route_dlg').dialog({
                    title: '修改路线',
                    modal: true
                });
                
                var batchId="<?php echo $batchId ?>";
                var batchName="<?php echo $batchName ?>";
    
                $('#route_dlg_batch_id').text(batchId);
                $('#route_dlg_batch_name').text(batchName);
                
                $('#route_fm_date').datetimebox('setValue',$("#"+dateId).text());
                $('#route_fm_date_id').val(dateId);
               
                $('#route_fm_batch_id').val(batchId);
                $('#route_fm_batch_name').val(batchName);
                
                $('#route_fm_desc').val($("#"+descId).text());
                $('#route_fm_desc_id').val(descId);
                
                $('#route_fm_area').val($("#"+areaId).text());
                $('#route_fm_area_id').val(areaId)
                
                $('#route_dlg').dialog('open');
                dialogId="route_dlg";
                saveFunction="saveRoute";
                 $('#route_fm_area').focus();
                $('#route_fm_desc').focus();
            }

    </script>
     </body>
</html>