<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// 선택옵션으로 인해 셀합치기가 가변적으로 변함
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
include_once(G5_PATH.'/edu/education.lib.php');
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
if($schedule) {
	$sql_search .= " and schedule >= '$schedule' ";
}
if($edate) {
	$sql_search .= " and edate <= '$edate' ";
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

$rows = 10;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$sql = " select *
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);

$bo_gallery_cols = 1;
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>
<!-- 게시판 목록 시작 { -->
<div id="bo_list" style="width:100%">
	
<!-- 게시판 검색 시작 { -->
<!--
<form name="flist" id="flist" class="local_sch03 local_sch">
    <input type="hidden" name="bo_table" value="education">
    <input type="hidden" name="sfl" value="subject">
<div>
    <strong style="letter-spacing:0.09em">기간</strong>
	<input type="text" name="schedule" value="<?php echo $schedule?>" id="schedule" class="frm_input" size="12"> ~ <input type="text" name="edate" value="<?php echo $edate?>" id="edate" class="frm_input" size="12">
</div>
<div>
    <strong>제목</strong>
    <input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" id="stx" class="frm_input fc_input" maxlength="20">           
</div>

<div id="search_list" style="text-align:center">
		<input type="submit" value="검색" class="btn_submit">
</div>
</form>
-->
<!-- } 게시판 검색 끝 -->


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
    <div id="gall_ul">
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
         ?>
	<ul class="sub10_list_area">
		<li>
				<?php if($progress == "준비"){?>
                <a href="javascript://" onclick="javascript:a_href('접수기간이 아닙니다.\n\n접수기간 : <?php echo $stime?>');">	
                <?php }else{?>
                <a href="./index.php?case=view&amp;s_id=<?php echo $row['s_id']?>">
				<?php }?>        
                    <?php						
                        $thumb = get_education_thumbnail($row['s_id'], $row['contents'], 320, 220);

                        if($thumb && $thumb['src']) {
                            $img_content = '<img src="'.$thumb['src'].'" alt="'.$thumb['alt'].'">';
                        } else {
                            $img_content = '<img src="'.G5_IMG_URL.'/noimage.jpg">';
                        }

                        echo $img_content;
                     ?>
                 </a>            
        </li>
		<li>
				<p class="sub10_list_txt01"><?php if($progress == "준비"){?>
                <a href="javascript://" onclick="javascript:a_href('접수기간이 아닙니다.\n\n접수기간 : <?php echo $stime?>');">	
                <?php }else{?>
                <a href="./index.php?case=view&amp;s_id=<?php echo $row['s_id']?>">
				<?php }?> <?php echo $row['subject'] ?></a></p>
				<p class="sub10_list_txt02">- 교육정원 : <?php echo $row['person']?>명    | 강사명 : <?php echo $row['edu_01']?><br>
				- 교 육 비  : <?php echo $row['edu_03']?><br>
				- 교육일시 : <?php echo $row['schedule']?> <?php echo ($row['schedule'] != $row['edate'] && $row['edate'])? " ~ ".$row['edate']:"";?><br>
				- 접수기간 : <?php echo $row['s_day']?> <?php echo $row['s_time']?> ~ <?php echo $row['f_day']?> <?php echo $row['f_time']?><br>
				- 교육장소 : <?php echo $row['place']?> 
				</p>		
		</li>
		<li>
                 <p class="sub10_btn">
				<?php if($progress == "준비"){?>
                <a href="javascript://" onclick="javascript:a_href('접수기간이 아닙니다.\n\n접수기간 : <?php echo $stime?>');" class="sub10_btn01">	
                <?php }else{?>
                <a href="./index.php?case=view&amp;s_id=<?php echo $row['s_id']?>" class="sub10_btn01">
				<?php }?>
                상세보기
                </a>
                <?php if($progress == "준비"){?>
                <a href="javascript://" onclick="javascript:a_href('접수기간이 아닙니다.\n\n접수기간 : <?php echo $stime?>');" class="sub10_btn03">	
                <?php }else if($progress == "신청"){?>
                <a href="./index.php?case=view&amp;s_id=<?php echo $row['s_id']?>" class="sub10_btn02">
				<?php }else{?>
                <a href="./index.php?case=view&amp;s_id=<?php echo $row['s_id']?>" class="sub10_btn03">
				<?php }?>			
				<?php echo $progress?>
				</a>        
      			</p>
        </li>
	</ul>
        <?php } ?>
        <?php if ($i == 0) { echo "<ul class='sub10_list_area'><li class=\"empty_list\">게시물이 없습니다.</li></ul>"; } ?>
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


<script>
$(function(){
    $("#s_day, #f_day, #schedule, #edate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99"/*, maxDate: "+0d"*/ });
});
function a_href(msg){ 
	alert(msg);
}
</script>
<!-- } 게시판 목록 끝 -->
