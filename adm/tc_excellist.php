<?php
$sub_menu = '400960';
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$g5['title'] = '엑셀 등록';
include_once (G5_ADMIN_PATH.'/admin.head.php');
?>
<style>
.new_excel{ padding:0 20px 20px 20px; margin-top:20px;}
.new_excel h1{margin:10px 0;}
.excel_info {margin-bottom:10px; line-height:18px;}
.btn_confirm {margin-top:15px;}
</style>

<div class="new_excel">

    <!--<div class="excel_info">
        <p>
			엑셀파일을 저장하실 때는 <strong>Excel 97 - 2003 통합문서 (*.xls)</strong> 로 저장하셔야 합니다.
            xls, xlsx, csv 로 저장
        </p>
	</div>-->

    <form name="fitemexcelup" id="fitemexcelup" method="post" action="./tc_excelupdate.php" enctype="MULTIPART/FORM-DATA" autocomplete="off">

        <select name="ca_name" id="ca_name" required>
            <option value="">시군구를 선택하세요</option>
            <option value="전주시">전주시</option>
<option value="군산시">군산시</option>
<option value="익산시">익산시</option>
<option value="김제시">김제시</option>
<option value="정읍시">정읍시</option>
<option value="남원시">남원시</option>
<option value="완주군">완주군</option>
<option value="임실군">임실군</option>
<option value="진안군">진안군</option>
<option value="무주군">무주군</option>
<option value="장수군">장수군</option>
<option value="부안군">부안군</option>
<option value="고창군">고창군</option>
<option value="순창군">순창군</option>
        </select>

    <div id="excelfile_upload" style="text-align:center;">
       <input type="file" name="excelfile" id="excelfile">
    </div>

    <div class="btn_confirm01 btn_confirm">
        <input type="submit" value="엑셀파일 등록" class="btn_submit">
    </div>

    </form>

</div>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>

