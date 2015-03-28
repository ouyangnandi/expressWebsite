<?php
require('../../global/init.php');
error_reporting(E_ALL);
date_default_timezone_set('Australia/Perth');
require_once '../../lib/PHPExcel.php';
exportInChinese();

function exportInChinese() {

    $con = DatabaseConn::getConn();
     mysqli_query($con,"set names 'utf8'");
    $searchBatchIds = isset($_REQUEST['batchIds']) ?$_REQUEST['batchIds']: '';
    $sql = 'select * from tb_akt_order where batch_id in ('. $searchBatchIds. ') order by id';
    $rs = mysqli_query($con,$sql);
    $data = array();
    while($row = mysqli_fetch_object($rs)){
                array_push($data, $row);
    }

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator('http://www.canexpress.com.au')
        ->setLastModifiedBy('http://www.canexpress.com.au')
        ->setTitle('Office 2007 XLSX Document')
        ->setSubject('Office 2007 XLSX Document')
        ->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')
        ->setKeywords('office 2007 openxml php')
        ->setCategory('Result file');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', '货物编号')
        ->setCellValue('B1', '快递单号')
        ->setCellValue('C1', '物品名称')
        ->setCellValue('D1', '物品重量')
        ->setCellValue('E1', '物品数量')
        ->setCellValue('F1', '寄件人')
        ->setCellValue('G1', '寄件人联系地址')
        ->setCellValue('H1', '寄件人电话')
        ->setCellValue('I1', '收件人')
        ->setCellValue('J1', '收件人地址')
        ->setCellValue('K1', '收件人电话')
        ->setCellValue('L1', '收件人证件号');

$objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("B1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("C1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("D1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("E1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("F1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("G1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("H1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("I1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("J1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("K1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("L1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(100);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(100);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);






$i = 2;
foreach ($data as $v) {
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $i, $v->batch_name.$v->batch_seq)
            ->setCellValue('B' . $i, $v->order_num)
            ->setCellValue('C' . $i, $v->category)
            ->setCellValue('D' . $i, $v->weight)
            ->setCellValue('E' . $i, $v->amount)
            ->setCellValue('F' . $i, $v->sender_name)
            ->setCellValue('G' . $i, $v->sender_addr)
            ->setCellValue('H' . $i, $v->sender_tel)
            ->setCellValue('I' . $i, $v->receiver_name)
            ->setCellValue('J' . $i, $v->receiver_addr)
            ->setCellValue('K' . $i, $v->receiver_tel)
            ->setCellValue('L' . $i, $v->certificate.' ');

       $objPHPExcel->getActiveSheet()->getStyle("A".$i)
               ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       $objPHPExcel->getActiveSheet()->getStyle("B".$i)
                ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       $objPHPExcel->getActiveSheet()->getStyle("C".$i)
                ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       $objPHPExcel->getActiveSheet()->getStyle("D".$i)
                ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       $objPHPExcel->getActiveSheet()->getStyle("E".$i)
                ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       $objPHPExcel->getActiveSheet()->getStyle("F".$i)
                ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       $objPHPExcel->getActiveSheet()->getStyle("G".$i)
                ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       $objPHPExcel->getActiveSheet()->getStyle("H".$i)
                ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       $objPHPExcel->getActiveSheet()->getStyle("I".$i)
                ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       $objPHPExcel->getActiveSheet()->getStyle("J".$i)
                ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       $objPHPExcel->getActiveSheet()->getStyle("K".$i)
                ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       $objPHPExcel->getActiveSheet()->getStyle("L".$i)
                ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    $i++;
}
$objPHPExcel->getActiveSheet()->setTitle('批次订单信息');
$objPHPExcel->setActiveSheetIndex(0);
$filename = urlencode('批次订单信息'.$searchBatchIds) . '_' . date('Y-m-dHis');

//生成xlsx文件
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
 header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');


$objWriter->save('php://output');
exit;
}
?>