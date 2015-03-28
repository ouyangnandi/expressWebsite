<?php

$action = $_GET['action'];




if ($action == 1) {
    checkDownload();
} else if ($action == 2) {
    download();
}

function checkDownload() {
    $batchID = $_GET['batchIds'];
    $file = "bak.zip";
    $filename = "./bak.zip"; //最终生成的文件名（含路径）
    if (file_exists($file)) {
        unlink($file);
    }

//获取列表 
    $datalist = list_dir("../../uploadID/" . $batchID . "/");
    if (!file_exists($filename)) {
//重新生成文件   
        $zip = new ZipArchive(); //使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释   
        if ($zip->open($filename, ZIPARCHIVE::CREATE) !== TRUE) {
            exit('无法打开文件，或者文件创建失败');
        }
        foreach ($datalist as $val) {
            if (file_exists($val)) {
                $zip->addFile($val, basename($val)); //第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下   
            }
        }
        $zip->close(); //关闭   
    }
    if (!file_exists($filename)) {
        echo json_encode(array('msg' => '文件不存在，请联系程序员'));
        return;
    } else {
        echo json_encode(array('success' => true));
        return;
    }
}

function download() {
    $filename = "./bak.zip"; //最终生成的文件名（含路径）
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header('Content-disposition: attachment; filename=' . basename($filename)); //文件名   
    header("Content-Type: application/zip"); //zip格式的   
    Header("Accept-Ranges: bytes");
    header('Content-Length: ' . filesize($filename)); //告诉浏览器，文件大小   
    $file = fopen($filename, "r");
    //修改之後，一次只傳輸1024個字節的數據给客戶端
    //向客戶端回送數據
    $buffer = 4 * 1024; //
    //判斷文件是否讀完
    while (!feof($file)) {
        //將文件讀入內存
        echo fgets($file, $buffer);
        //每次向客戶端回送1024 * 1024個字節的數據

    }

    fclose($file);
}

//获取文件列表
function list_dir($dir) {
    $result = array();
    if (is_dir($dir)) {
        $file_dir = scandir($dir);
        foreach ($file_dir as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            } elseif (is_dir($dir . $file)) {
                $result = array_merge($result, list_dir($dir . $file . '/'));
            } else {
                array_push($result, $dir . $file);
            }
        }
    }
    return $result;
}
?>

