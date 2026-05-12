<?php
$sub_menu = '750200';
include_once('./_common.php');

auth_check($auth[$sub_menu], "w");

if($_FILES['excelfile']['tmp_name']) {
    $file = $_FILES['excelfile']['tmp_name'];

    include_once(G5_LIB_PATH.'/Excel/reader.php');

    $data = new Spreadsheet_Excel_Reader();

    // Set output Encoding.
    $data->setOutputEncoding('UTF-8');

    /***
    * if you want you can change 'iconv' to mb_convert_encoding:
    * $data->setUTFEncoder('mb');
    *
    **/

    /***
    * By default rows & cols indeces start with 1
    * For change initial index use:
    * $data->setRowColOffset(0);
    *
    **/



    /***
    *  Some function for formatting output.
    * $data->setDefaultFormat('%.2f');
    * setDefaultFormat - set format for columns with unknown formatting
    *
    * $data->setColumnFormat(4, '%.3f');
    * setColumnFormat - set format for column (apply only to number fields)
    *
    **/

    $data->read($file);

    /*


     $data->sheets[0]['numRows'] - count rows
     $data->sheets[0]['numCols'] - count columns
     $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

     $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell

        $data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
            if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
        $data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format
        $data->sheets[0]['cellsInfo'][$i][$j]['colspan']
        $data->sheets[0]['cellsInfo'][$i][$j]['rowspan']
    */

    error_reporting(E_ALL ^ E_NOTICE);

    $fail_od_id = array();
    $total_count = 0;
    $fail_count = 0;
    $succ_count = 0;

    // $i 사용시 ordermail.inc.php의 $i 때문에 무한루프에 빠짐
    for ($k = 2; $k <= $data->sheets[0]['numRows']; $k++) {
        $total_count++;


        //$s_id               = addslashes(trim($data->sheets[0]['cells'][$k][1]));
		$subject               = addslashes(trim($data->sheets[0]['cells'][$k][1]));
		$id               = addslashes(trim($data->sheets[0]['cells'][$k][2]));
		$name               = addslashes(trim($data->sheets[0]['cells'][$k][3]));
		$mobile               = addslashes(trim($data->sheets[0]['cells'][$k][4]));
		$corp               = addslashes(trim($data->sheets[0]['cells'][$k][5]));
		$phone               = addslashes(trim($data->sheets[0]['cells'][$k][6]));
		$schedule               = addslashes(trim($data->sheets[0]['cells'][$k][7]));
		$edate               = addslashes(trim($data->sheets[0]['cells'][$k][9]));
		$cno               = addslashes(trim($data->sheets[0]['cells'][$k][8]));




        if(!$mobile || !$name || !$subject) {
            $fail_count++;
            $fail_od_id[] = $name;
            continue;
        }


        // 정보 업데이트
		$sql = " insert wp_completion 
					set wdate = '".G5_TIME_YMDHIS."',
						id         = '$id',
                		name       = '$name',
                		mobile     = '$mobile',
                		subject    = '$subject',
						schedule   = '$schedule',
						edate   = '$edate',
                		corp       = '$corp',
						cno       = '$cno',
                		phone      = '$phone' ";
		sql_query($sql);

        $succ_count++;


    }
}


$g5['title'] = '수료자 일괄처리 결과';
include_once (G5_ADMIN_PATH.'/admin.head.php');
?>

<div class="new_win">
    <h1><?php echo $g5['title']; ?></h1>

    <div class="local_desc01 local_desc">
        <p>수료자일괄처리를 완료했습니다.</p>
    </div>

    <dl id="excelfile_result">
        <dt>총수료자수</dt>
        <dd><?php echo number_format($total_count); ?></dd>
        <dt class="result_done">완료건수</dt>
        <dd class="result_done"><?php echo number_format($succ_count); ?></dd>
        <dt class="result_fail">실패건수</dt>
        <dd class="result_fail"><?php echo number_format($fail_count); ?></dd>
        <?php if($fail_count > 0) { ?>
        <dt>실패수료자코드</dt>
        <dd><?php echo implode(', ', $fail_od_id); ?></dd>
        <?php } ?>
    </dl>
</div>

<div class="btn_confirm01 btn_confirm">
    <a href="./completion_list.php?<?php echo $qstr?>">목록</a>
</div>
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>