<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 6;

if(!$corp) alert("잘못된 접근방식입니다.");

$sql_common = " from wp_completion ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "c_id" :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (isset($phone))  { // search part (검색 파트[구간])
    //$phone = mysql_real_escape_string($phone);
    $qstr .= '&phone=' . urlencode($phone);
	$sql_search .= " and (REPLACE( phone, '-', '') = '$phone' or phone = '$phone') ";
}
if (isset($case))  { // search part (검색 파트[구간])
    //$case = mysql_real_escape_string($case);
    $qstr .= '&case=' . urlencode($case);
}
if (isset($corp))  { // search part (검색 파트[구간])
    //$corp = mysql_real_escape_string($corp);
    $qstr .= '&corp=' . urlencode($corp);
	$sql_search .= " and corp = '$corp' ";
}
if (isset($s_day))  { // search part (검색 파트[구간])
    //$s_day = mysql_real_escape_string($s_day);
    $qstr .= '&s_day=' . urlencode($s_day);
}
if (isset($f_day))  { // search part (검색 파트[구간])
    //$f_day = mysql_real_escape_string($f_day);
    $qstr .= '&f_day=' . urlencode($f_day);
}

if($s_day || $f_day) {
$sql_search .= " and (SUBSTRING(schedule,1,10) between '".$s_day."' and '".$f_day."') ";
}

if (!$sst) {
    $sst = "wdate";
    $sod = "desc";
}

$sql_order = " order by $sst $sod ";

$sql = " select count(c_id) as cnt
         $sql_common
         $sql_search
         $sql_order ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 15;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$sql = " select *
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);



if (isset($c_id)) $qstr .= "&c_id=$c_id";
?>
<!-- 게시판 목록 시작 { -->
<div id="bo_list" style="width:<?php echo $width; ?>">

    <!-- 게시판 페이지 정보 및 버튼 시작 { -->
    <div class="bo_fx">
        <div id="bo_list_total">
            <span>Total <?php echo number_format($total_count) ?>명</span>
            <?php echo $page ?> 페이지
        </div>
    </div>
    <!-- } 게시판 페이지 정보 및 버튼 끝 -->

    <form name="fboardlist" id="fboardlist" method="post">
    <input type="hidden" name="case" value="<?php echo $case ?>" />
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="sw" value="">

    <div class="edu_head01 tbl_wrap">
        <table>
        <caption><?php echo $corp?> 수료자 현황</caption>
        <thead>
        <tr>
            <th scope="col" class="none01">번호</th>
            <th scope="col" class="none02">성명</th>
            <th scope="col">교육명</th>
            <th scope="col" class="none03">교육일시</th>
            <th scope="col">출력</th>
        </tr>
        </thead>
        <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {

	    $num = number_format($total_count - ($page - 1) * $rows - $i);
		
        $bg = 'bg'.($i%2);
       ?>
        <tr>
            <td class="edu_td_num none01"><?php echo $num?></td>
            <td class="edu_td_nick none02"><?php echo $row['name']?></td>
            <td class="edu_td_subject"><?php echo $row['subject'] ?></td>
            <td class="edu_td_board none03"><?php echo $row['schedule']?></td>
            <td class="td_num"><a href="javascript:GPEN_PRINT('<?php echo $row['c_id']?>');">출력</a></td>
        </tr>
        <?php } ?>
        <?php if ($i == 0) { echo '<tr><td colspan="'.$colspan.'" class="empty_table">게시물이 없습니다.</td></tr>'; } ?>
        </tbody>
        </table>
    </div>

    <div class="bo_fx">
        <ul class="btn_bo_user">
            <li><a href="./index.php?case=list2&amp;corp=<?php echo urlencode($corp)?><?php echo $qstr?>" class="btn_b01 btn">목록</a></li>
        </ul>
    </div>
    </form>
</div>

<!-- 페이지 -->
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['PHP_SELF'].'?'.$qstr.'&amp;page='); ?>

<!-- 게시판 검색 시작 { -->
<fieldset id="bo_sch">
    <legend>게시물 검색</legend>

    <form name="fsearch" method="get">
    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="subject"<?php echo get_selected($_GET['sfl'], 'subject', true); ?>>교육명</option>
    </select>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" required id="stx" class="frm_input required" size="15" maxlength="20">
        <button type="submit" value="검색" class="sch_btn"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색</span></button>
    </form>
</fieldset>
<!-- } 게시판 검색 끝 -->


<!-- } 게시판 목록 끝 -->
<script>
function GPEN_PRINT(val){ 
	var p = window.open("<?php echo G5_URL?>/complet/c_print.php?c_id="+val,"PrintWin","width=687, height=600, scrollbars=yes"); 
	p.focus(); 
}
</script>