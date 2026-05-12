<?php
include_once('./_common.php');

// clean the output buffer
ob_end_clean();

$gnu_config = get_gnupushapp_config();

$bo_table = htmlspecialchars($_REQUEST['bo_table']);
$wr_id = htmlspecialchars($_REQUEST['wr_id']);
$no = htmlspecialchars($_REQUEST['no']);
$filedown_id = htmlspecialchars($_REQUEST['filedown_id']);

$no = (int)$no;

$row_tmp = sql_fetch(" select * from g5_gnupushapp_filedown where ggf_keypass = '$filedown_id' and ggf_bo_table = '$bo_table' and ggf_wr_id = '$wr_id' and ggf_no = '$no' and ggf_downloadok = 'N' ");



if($row_tmp['ggf_keypass']){

$sqld = " update g5_gnupushapp_filedown set ggf_downloadok = 'Y' where ggf_keypass = '{$row_tmp['ggf_keypass']}' ";
sql_query($sqld);

$sql = " select bf_source, bf_file from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$no' ";
$file = sql_fetch($sql);

$filepath = G5_DATA_PATH.'/file/'.$bo_table.'/'.$file['bf_file'];
$filepath = addslashes($filepath);


$original = iconv('utf-8', 'euc-kr', $file['bf_source']); // SIR 잉끼님 제안코드

header("content-type: doesn/matter");
header("content-length: ".filesize("$filepath"));
header("content-disposition: attachment; filename=\"$original\"");
header("Content-Transfer-Encoding: binary\n");
header("pragma: no-cache");
header("expires: 0");
flush();

$fp = fopen($filepath, 'rb');

// 4.00 대체
// 서버부하를 줄이려면 print 나 echo 또는 while 문을 이용한 방법보다는 이방법이...
//if (!fpassthru($fp)) {
//    fclose($fp);
//}

$download_rate = 10;

while(!feof($fp)) {
    //echo fread($fp, 100*1024);
    /*
    echo fread($fp, 100*1024);
    flush();
    */

    print fread($fp, round($download_rate * 1024));
    flush();
    usleep(1000);
}
fclose ($fp);
flush();

}
?>