<?php
$sub_menu = "750100";
include_once('./_common.php');

auth_check($auth[$sub_menu], "w");


$sql_common = " from wp_education a ";
$sql_search = " where (1) ";

$sql_common .= " , wp_edu b ";
$sql_search .= " and (a.s_id = b.s_id and b.s_id = '$s_id') ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "name" :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
        case "a.s_id" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        default :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "b.wdate";
    $sod = "asc";
}
$sql_order = " order by $sst $sod ";


$sql = " select * {$sql_common} {$sql_search} {$sql_order} ";
$result = sql_query($sql);

if(!@sql_num_rows($result))
    alert_close('접수 내역이 없습니다.');

/*================================================================================
php_writeexcel http://www.bettina-attack.de/jonny/view.php/projects/php_writeexcel/
=================================================================================*/

include_once(G5_LIB_PATH.'/Excel/php_writeexcel/class.writeexcel_workbook.inc.php');
include_once(G5_LIB_PATH.'/Excel/php_writeexcel/class.writeexcel_worksheet.inc.php');

$fname = tempnam(G5_DATA_PATH, "tmp-edumember.xls");
$workbook = new writeexcel_workbook($fname);
$worksheet = $workbook->addworksheet();

// Put Excel data
$data = array('번호', '교육명', '아이디', '신청자', '개인연락처', '기관명', '비고','기관연락처', '인원', '일시', '시설구분', '진행', '결제', '등록일', '수정일');
$data = array_map('iconv_euckr', $data);

$col = 0;
foreach($data as $cell) {
    $worksheet->write(0, $col++, $cell);
}
$j = 1;
for($i=1; $row=sql_fetch_array($result); $i++) {
    $row = array_map('iconv_euckr', $row);

	//$member = $row['name']."(".$row['id'].")";

    $worksheet->write($j, 0, $j);
	$worksheet->write($j, 1, $row['subject']);
	$worksheet->write($j, 2, ' '.$row['id']);
	$worksheet->write($j, 3, $row['name']);
	$worksheet->write($j, 4, ' '.$row['mobile']);
    $worksheet->write($j, 5, $row['corp']);
    $worksheet->write($j, 6, $row['etc']);	
    $worksheet->write($j, 7, ' '.$row['phone']);
    $worksheet->write($j, 8, $row['person']);
    $worksheet->write($j, 9, $row['schedule']."~".$row['edate']);
	$worksheet->write($j, 10, ($row['edu']==""?"비회원":$row['edu']));
    $worksheet->write($j, 11, $row['progress']);
    $worksheet->write($j, 12, $row['payment']);
	$worksheet->write($j, 13, $row['wdate']);
	$worksheet->write($j, 14, $row['mdate']);
	$j++;
}

$workbook->close();

header("Content-Type: application/x-msexcel; name=\"edu-".date("ymd", time()).".xls\"");
header("Content-Disposition: inline; filename=\"edu-".date("ymd", time()).".xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>