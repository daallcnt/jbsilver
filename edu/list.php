<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 8;

$sql_common = " from wp_education ";

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

if ($sca) $sql_search .= " and SUBSTRING(s_day,1,4) = '$sca' ";

if (!$sst) {
    $sst = "schedule";
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

    <div class="edu_head01 tbl_wrap">
        <table>
        <caption>교육 목록</caption>
        <thead>
        <tr>
            <th scope="col" class="none01">번호</th>
            <th scope="col">교육명</th>
            <th scope="col" class="none03">교육일시</th>
            <th scope="col" class="none02">접수기간</th>             
            <th scope="col" class="none02">교육장소</th>
                      
            <th scope="col" class="none02">정원</th>
            <th scope="col">신청하기</th>
        </tr>
        </thead>
        <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {

	    $num = number_format($total_count - ($page - 1) * $rows - $i);
		
		$r = sql_fetch(" select sum(person) as tot from wp_edu where s_id = '$row[s_id]' and progress <> '취소' "); 

			$stime = $row['s_day']." ".$row['s_time'];
			$ftime = $row['f_day']." ".$row['f_time'];
			$SumPerson = ($r['tot'])? $r['tot'] : "0" ;
		 	$PersonWating =  $row['person'] + $row['wating'];
			if($row['s_chk'] != 1) {
				if($SumPerson < $row['person']){
					if(G5_TIME_YMDHIS < $stime){
						$progress = "준비";
					}elseif(G5_TIME_YMDHIS > $ftime){
						$progress = "마감";
					}else{
						$progress = "신청";
					}
				}elseif($SumPerson < $PersonWating){
					if(G5_TIME_YMDHIS < $stime){
						$progress = "준비";
					}elseif(G5_TIME_YMDHIS > $ftime){
						$progress = "마감";
					}else{
						//$progress = "대기";
						$progress = "신청";
					}
				}else{
					$progress = "마감";
				}
			}else{
			$progress = "마감";
			}
			
        $bg = 'bg'.($i%2);
       ?>
        <tr>
            <td class="edu_td_num none01"><?php echo $num?></td>
            <td class="edu_td_subject"><b>
                <?php if($progress == "준비"){?>
                <a href="javascript://" onclick="javascript:a_href('접수기간이 아닙니다.\n\n접수기간 : <?php echo $stime?>');">	
                <?php }else{?>
                <a href="./index.php?case=view&amp;s_id=<?php echo $row['s_id']?>">
				<?php }?>
                    <?php echo $row['subject'] ?>
                </a>
				</b>
            </td>
            <td class="edu_td_board01 none03"><?php echo $row['schedule']?> <?php echo ($row['schedule'] != $row['edate'] && $row['edate'])? " ~ ".$row['edate']:"";?></td>
            <td class="edu_td_board01 none02"><?php echo $row['s_day']?> ~ <?php echo $row['f_day']?></td>
            <td class="edu_td_mng none02"><?php echo $row['place']?></td>
            <td class="edu_td_mem none02"><?php echo $SumPerson?> / <?php echo $row['person']?></td>            
            <td class="edu_td_enter">
                <?php if($progress == "준비"){?>
                <a href="javascript://" onclick="javascript:a_href('접수기간이 아닙니다.\n\n접수기간 : <?php echo $stime?>');">	
                <?php }else if($progress == "신청"){?>
                <a href="./index.php?case=view&amp;s_id=<?php echo $row['s_id']?>" style="background:#0078ff; color:#ffffff;padding:5px">
				<?php }else{?>
                <a href="./index.php?case=view&amp;s_id=<?php echo $row['s_id']?>" style="background:#ff324a; color:#ffffff;padding:5px">
				<?php }?>			
				<?php echo $progress?>
				</a>
            </td>
        </tr>
        <?php } ?>
        <?php if ($i == 0) { echo '<tr><td colspan="'.$colspan.'" class="empty_table">게시물이 없습니다.</td></tr>'; } ?>
        </tbody>
        </table>
    </div>

    <div class="bo_fx">
        <ul class="btn_bo_user">
            <li><a href="./index.php" class="btn_b01 btn">목록</a></li>
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
<script>
function a_href(msg){ 
	alert(msg);
}
</script>
<!-- } 게시판 목록 끝 -->
