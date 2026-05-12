<?php
$sub_menu = '750200';
include_once('./_common.php');

auth_check($auth[$sub_menu], "w");

$g5['title'] = '엑셀 수료자처리';
include_once (G5_ADMIN_PATH.'/admin.head.php');
?>

<div class="new_win">
    <h1><?php echo $g5['title']; ?></h1>

    <div class="local_desc01 local_desc">
        <p>
            엑셀파일을 이용하여 수료자정보를 일괄등록할 수 있습니다.<br>
            형식은 <strong>수료자 엑셀파일</strong>을 수료자 정보에 입력하시면 됩니다.<br>
            엑셀파일을 저장하실 때는 <strong>Excel 97 - 2003 통합문서 (*.xls)</strong> 로 저장하셔야 합니다.<br>
            <strong>교육명, 아이디, 신청자명, 개인연락처, 기관명, 기관연락처, 교육일자, 발급번호, 발급일자</strong> 순으로 넣어주세요.<br>
            <strong>두번째 라인</strong>부터 시작됩니다.
			<strong><a href="https://www.jbsilver.net/file/sample.xls" target="_blank">(샘플파일 다운로드)</a></strong> 
        </p>

        <!--p>
            <a href="<?php echo G5_ADMIN_URL; ?>/">수료자 일괄등록용 엑셀파일 다운로드</a>
        </p-->
    </div>

    <form name="forderdelivery" method="post" action="./completion_file.php" enctype="MULTIPART/FORM-DATA" autocomplete="off">

    <div id="excelfile_upload">
        <label for="excelfile">파일선택</label>
        <input type="file" name="excelfile" id="excelfile">
    </div>
<br />
    <div class="btn_confirm01 btn_confirm">
        <input type="submit" value="수료자정보 등록" class="btn_submit btn">
    </div>

    </form>

</div>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>