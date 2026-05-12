<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 6;

if(!$id) alert("잘못된 접근방식입니다.");

$sql_common = " from wp_edu ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "s_id" :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (isset($id))  { // search part (검색 파트[구간])
    //$id = mysql_real_escape_string($id);
    $qstr .= '&id=' . urlencode($id);
}
if (isset($case))  { // search part (검색 파트[구간])
    //$case = mysql_real_escape_string($case);
    $qstr .= '&case=' . urlencode($case);
}
if (isset($name))  { // search part (검색 파트[구간])
    //$name = mysql_real_escape_string($name);
    $qstr .= '&name=' . urlencode($name);
	//$sql_search .= " and name = '$name' ";
}


$sql_search .= " and id = '$id' ";


if (!$sst) {
    $sst = "wdate";
    $sod = "desc";
}

$sql_order = " order by $sst $sod ";

$sql = " select count(s_id) as cnt
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


if (isset($s_id)) $qstr .= "&s_id=$s_id";
if (isset($e_id)) $qstr .= "&e_id=$e_id";
?>
<!-- 게시판 목록 시작 { -->
<div id="bo_list" style="width:<?php echo $width; ?>">

    <!-- 게시판 페이지 정보 및 버튼 시작 { -->
    <div class="bo_fx">
        <div id="bo_list_total">
            <span>Total <?php echo number_format($total_count) ?>건</span>
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
    <input type="hidden" name="edu" value="<?php echo $edu?>" />

    <div class="edu_head01 tbl_wrap">
        <table>
        <caption>교육신청현황</caption>
        <thead>
        <tr>
            <th scope="col" class="none01">번호</th>
            <th scope="col">교육명</th>
            <th scope="col" class="none02">교육인원</th>
            <th scope="col" class="none03">교육일시</th>
            <th scope="col">현황</th>
            <th scope="col">결제</th>
        </tr>
        </thead>
        <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {

	    $num = number_format($total_count - ($page - 1) * $rows - $i);
		
        $bg = 'bg'.($i%2);
		
		if($row['payment'] == "완납") {
		$payment = "<a href=\"javascript:GPEN_PRINT('".$row['e_id']."');\">$row[payment]</a>";
		}else{
		$payment = $row['payment'];
		}
       ?>
        <tr>
            <td class="edu_td_num none01"><?php echo $num?></td>
            <td class="edu_td_subject">
                <b>
				<a href="./edu.php?case=sview&amp;e_id=<?php echo $row['e_id']?>&amp;edu=<?php echo $edu?>">
                    <?php echo $row['subject'] ?>
                </a>
				</b>
            </td>
            <td class="edu_td_mng none02"><?php echo $row['person']?></td>
            <td class="edu_td_board none03"><?php echo $row['schedule']?><?php echo ($row['schedule'] != $row['edate'] && $row['edate'])? " ~ ".$row['edate']:"";?></td>            
            <td class="edu_td_num"><?php echo $row['progress']?></td>
            <td class="edu_td_num"><?php echo $payment?></td>
        </tr>
        <?php } ?>
        <?php if ($i == 0) { echo '<tr><td colspan="'.$colspan.'" class="empty_table">게시물이 없습니다.</td></tr>'; } ?>
        </tbody>
        </table>
    </div>

    <div class="bo_fx">
        <ul class="btn_bo_user">
            <li><a href="./edu.php?case=slist&amp;id=<?php echo $member['mb_id']?>&amp;edu=<?php echo $edu?>" class="btn_b01 btn">목록</a></li>
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
<input type="hidden" name="edu" value="<?php echo $edu?>" />
    <input type="hidden" name="case" value="<?php echo $case ?>" />
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
	var p = window.open("<?php echo G5_URL?>/edu/r_print.php?e_id="+val,"Printedu","width=687, height=600, scrollbars=yes"); 
	p.focus(); 
}
</script>