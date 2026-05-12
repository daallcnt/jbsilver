<?php
include_once('./_common.php'); //database설정관련 
include G5_PLUGIN_PATH .'/PHPExcel/PHPExcel.php'; 
$objPHPExcel = new PHPExcel();
include G5_PLUGIN_PATH .'/PHPExcel/PHPExcel/IOFactory.php'; //phpexcel 불러오기 

/* $sql_common = " wr_datetime = '".G5_TIME_YMDHIS."',
                 ca_name        = '$ca_name',
                 wr_subject     = '$wr_subject',
                 wr_content    = '$wr_content',                 
                 wr_1      = '$wr_1',                 
                 wr_2      = '$wr_2' ";*/
$write_table = "g5_write_sub01_06";				  
 
//$filename = './file.xlsx'; // 서버에 올려진 파일을 직접 지정할 경우
// excel_upload.php 파일을 이용해 업로드 한 경우

$filename = $_FILES['excelfile']['tmp_name'];

try {

    // 업로드 된 엑셀 형식에 맞는 Reader객체를 만든다.
    $objReader = PHPExcel_IOFactory::createReaderForFile($filename);

    // 읽기전용으로 설정
    $objReader->setReadDataOnly(true);

    // 엑셀파일을 읽는다
    $objExcel = $objReader->load($filename);



    // 첫번째 시트를 선택
    $objExcel->setActiveSheetIndex(0);

    $objWorksheet = $objExcel->getActiveSheet();

    $rowIterator = $objWorksheet->getRowIterator();

    foreach ($rowIterator as $row) { // 모든 행에 대해서
               $cellIterator = $row->getCellIterator();
               $cellIterator->setIterateOnlyExistingCells(false);
    }



    $maxRow = $objWorksheet->getHighestRow();

    $fail_od_id = array();
    $total_count = 0;
    $fail_count = 0;
    $succ_count = 0;
	$update = 0;
    for ($i = 3 ; $i <= $maxRow ; $i++) {
		$total_count++;
               //$ca_name = addslashes(trim($objWorksheet->getCell('A' . $i)->getValue())); // A열
               $wr_subject = addslashes(trim($objWorksheet->getCell('A' . $i)->getValue())); // B열
               $wr_content = addslashes(trim($objWorksheet->getCell('C' . $i)->getValue())); // C열
               $wr_1 = addslashes(trim($objWorksheet->getCell('E' . $i)->getValue())); // D열
			   $wr_2 = addslashes(trim($objWorksheet->getCell('B' . $i)->getValue())); // D열
			   $wr_3 = addslashes(trim($objWorksheet->getCell('D' . $i)->getValue())); // D열

			   /*
               $reg_date = $objWorksheet->getCell('F' . $i)->getValue(); // F열
               $reg_date = PHPExcel_Style_NumberFormat::toFormattedString($reg_date, 'YYYY-MM-DD'); // 날짜 형태의 셀을 읽을때는 toFormattedString를 사용한다.
			   */
        if(!$ca_name || !$wr_subject || !$wr_content) {
            $fail_count++;
            $fail_od_id[] = $ca_name . "/" . $wr_subject . "/" . $wr_content . "<br>";
            continue;
        }

	$res = sql_fetch("select * from $write_table where wr_subject='$wr_subject' and wr_content='$wr_content' ");
    if ($res) 
    {
         
    $update++;
	$arr_name .= $ca_name . "/" . $wr_subject . "/" . $wr_content . "<br/>\n";
    }
    else 
    {

        // 정보 업데이트

        $mb_id = $member['mb_id'];
        $wr_name = addslashes(clean_xss_tags($board['bo_use_name'] ? $member['mb_name'] : $member['mb_nick']));
        $wr_password = $member['mb_password'];
        $wr_email = addslashes($member['mb_email']);
        $wr_homepage = addslashes(clean_xss_tags($member['mb_homepage']));

        $wr_num = get_next_num($write_table);
        $wr_reply = '';


    $sql = " insert into $write_table 
                set wr_num = '$wr_num',
                     wr_reply = '$wr_reply',
                     wr_comment = 0,
                     ca_name = '$ca_name',
                     wr_option = '$html,$secret,$mail',
                     wr_subject = '$wr_subject',
                     wr_content = '$wr_content',
                     wr_link1 = '$wr_link1',
                     wr_link2 = '$wr_link2',
                     wr_link1_hit = 0,
                     wr_link2_hit = 0,
                     wr_hit = 0,
                     wr_good = 0,
                     wr_nogood = 0,
                     mb_id = '{$member['mb_id']}',
                     wr_password = '$wr_password',
                     wr_name = '$wr_name',
                     wr_email = '$wr_email',
                     wr_homepage = '$wr_homepage',
                     wr_datetime = '".G5_TIME_YMDHIS."',
                     wr_last = '".G5_TIME_YMDHIS."',
                     wr_ip = '{$_SERVER['REMOTE_ADDR']}',
                     wr_1 = '$wr_1',
                     wr_2 = '$wr_2',
                     wr_3 = '$wr_3',
                     wr_4 = '$wr_4',
                     wr_5 = '$wr_5',
                     wr_6 = '$wr_6',
                     wr_7 = '$wr_7',
                     wr_8 = '$wr_8',
                     wr_9 = '$wr_9',
                     wr_10 = '$wr_10' ";
    sql_query($sql);
//echo $sql;
    $wr_id = sql_insert_id();

    // 부모 아이디에 UPDATE
    sql_query(" update $write_table set wr_parent = '$wr_id' where wr_id = '$wr_id' ");

    // 게시글 1 증가
    sql_query("update {$g5['board_table']} set bo_count_write = bo_count_write + 1 where bo_table = '{$bo_table}'");



		/*$sql = " insert $write_table  
					set $sql_common ";
		sql_query($sql);*/
		

        $succ_count++;
	}
              // echo $mb_id . ", " . $mb_name . ", " . $it_name . "<br>";

    }

}



catch (exception $e) {

    $err = "엑셀 파일을 읽는 도중 오류가 발생 하였습니다.";

}


$g5['title'] = '일괄처리 결과';
include_once (G5_ADMIN_PATH.'/admin.head.php');
?>

<div class="new_win">
    <h1><?php echo $g5['title']; ?></h1>

    <div class="local_desc01 local_desc">
        <p><?php echo $err?$err:"일괄처리를 완료했습니다.";?></p>
    </div>

    <dl id="excelfile_result">
        <dt>총건수</dt>
        <dd><?php echo number_format($total_count); ?></dd>
        <dt class="result_done">완료건수</dt>
        <dd class="result_done"><?php echo number_format($succ_count); ?></dd>
        <dt class="result_fail">실패건수</dt>
        <dd class="result_fail"><?php echo number_format($fail_count); ?></dd>
        <dt class="result_fail">중복건수</dt>
        <dd class="result_fail"><?php echo number_format($update); ?></dd>        
        <?php if($fail_count > 0) { ?>
        <dt>실패코드</dt>
        <dd><?php echo implode(', ', $fail_od_id); ?></dd>
        <?php } ?>
        <dd><?php echo $arr_name?></dd>
    </dl>
</div>

<div class="btn_confirm01 btn_confirm">
    <a href="./tc_excellist.php?<?php echo $qstr?>">목록</a>
</div>
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
