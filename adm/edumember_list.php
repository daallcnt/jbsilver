<?php
$sub_menu = "750100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

if(!$s_id) alert("잘못된 접근방식입니다.");

$sql01 = " select subject from wp_education where s_id = '$s_id' ";
$row01 = sql_fetch($sql01);

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
    $sod = "desc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listalls = '<a href="sunedu_list.php" class="ov_listall">교육관리</a>';
$listall = '<a href="'.$_SERVER['PHP_SELF'].'?s_id='.$s_id.'" class="ov_listall">전체목록</a>';

$g5['title'] = $row01['subject'].' 교육신청자';
include_once('./admin.head.php');

$colspan = 15;

if (isset($s_id)) $qstr .= "&s_id=$s_id";
?>

<div class="local_ov01 local_ov">
    <?php echo $listalls ?><?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">신청자수 <?php echo number_format($total_count) ?>개</span></span> 
    <a href="./edumember_excel.php?<?php echo $qstr?>" id="order_delivery" class="ov_a">엑셀다운</a>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<input type="hidden" name="s_id" value="<?php echo $s_id ?>">
<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="name"<?php echo get_selected($_GET['sfl'], "name", true); ?>>이름</option>
    <option value="mobile"<?php echo get_selected($_GET['sfl'], "mobile"); ?>>연락처</option>
    <option value="corp"<?php echo get_selected($_GET['sfl'], "corp"); ?>>기관명</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" value="검색" class="btn_submit">

</form>

<?php if ($is_admin == 'super') { ?>
<div class="btn_add01 btn_add">
    <a href="./edumember_form.php?<?php echo $qstr?>" id="bo_add">신청자 추가</a>
</div>
<?php } ?>

<form name="fboardlist" id="fboardlist" action="./edumember_list_update.php?<?php echo $qstr?>" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">
<input type="hidden" name="s_id" value="<?php echo $s_id ?>">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">
            <label for="chkall" class="sound_only">전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col">번호</th>
        <th scope="col">신청자</th>
        <th scope="col">연락처</th>
        <th scope="col">기관명</th>
        <th scope="col">기관연락처</th>
        <th scope="col">인원</th>
        <th scope="col">시설구분</th>
        <th scope="col">진행상태</th>
        <th scope="col">결제</th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
       $num = number_format($total_count - ($page - 1) * $rows - $i);
	   
	    $one_update = '<a href="./edumember_form.php?w=u&amp;e_id='.$row['e_id'].'&amp;'.$qstr.'">수정</a>';
        $bg = 'bg'.($i%2);

		if($row['payment'] == "완납") {
		$payment = "<a href=\"javascript:GPEN_PRINT('".$row['e_id']."');\">출력</a>";
		}else{
		$payment = "";
		}		
    ?>

    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['name']) ?></label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
            <input type="hidden" name="e_id[<?php echo $i ?>]" value="<?php echo $row['e_id'] ?>">
        </td>
        <td class="td_num"><?php echo $num; ?></td>
        <td class="td_itopt">
            <a href="./edumember_view.php?e_id=<?php echo $row['e_id']?>&amp;<?php echo $qstr?>"><?php echo $row['name'] ?>(<?php echo $row['id'] ?>)</a>
        </td>
        <td class="td_tel">
           <?php echo $row['mobile'] ?>
        </td>
        <td>
            <?php echo $row['corp'] ?>
        </td>
        <td class="td_tel">
            <?php echo $row['phone'] ?>
        </td>
        <td class="td_num">
            <?php echo $row['person'] ?>
        </td>
        <td class="td_mngsmall">
            <label for="edu_<?php echo $i; ?>" class="sound_only">시설구분</label>
            <select name="edu[<?php echo $i ?>]" id="edu_<?php echo $i ?>">
                <option value="비회원"<?php echo get_selected($row['edu'], '비회원', true); ?>>비회원</option>
                <option value="회원"<?php echo get_selected($row['edu'], '회원'); ?>>회원</option>
            </select>
        </td>
        <td class="td_mngsmall">
            <label for="progress_<?php echo $i; ?>" class="sound_only">접수현황</label>
            <select name="progress[<?php echo $i ?>]" id="progress_<?php echo $i ?>">
                <option value="접수"<?php echo get_selected($row['progress'], '접수', true); ?>>접수</option>
                <option value="완료"<?php echo get_selected($row['progress'], '완료'); ?>>완료</option>
                <option value="대기"<?php echo get_selected($row['progress'], '대기'); ?>>대기</option>
                <option value="취소"<?php echo get_selected($row['progress'], '취소'); ?>>취소</option>
            </select>
        </td>
        <td class="td_mngsmall">
            <label for="payment_<?php echo $i; ?>" class="sound_only">접수현황</label>
            <select name="payment[<?php echo $i ?>]" id="payment_<?php echo $i ?>">
                <option value="미납"<?php echo get_selected($row['payment'], '미납', true); ?>>미납</option>
                <option value="완납"<?php echo get_selected($row['payment'], '완납'); ?>>완납</option>
                <option value="환불"<?php echo get_selected($row['payment'], '환불'); ?>>환불</option>
            </select>
        </td>
        <td class="td_mngsmall">
            <?php echo $one_update ?> <?php echo $payment?>
        </td>
    </tr>
    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn_02">
    <?php if ($is_admin == 'super') { ?>
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">
    <?php } ?>
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['PHP_SELF'].'?'.$qstr.'&amp;page='); ?>

<script>
function GPEN_PRINT(val){ 
	var p = window.open("<?php echo G5_URL?>/edu/r_print.php?e_id="+val,"Printedu","width=687, height=600, scrollbars=yes"); 
	p.focus(); 
}
function fboardlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>

<?php
include_once('./admin.tail.php');
?>
