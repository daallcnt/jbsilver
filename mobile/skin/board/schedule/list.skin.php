<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


$today = getdate(); 
$b_mon = $today['mon']; 
$b_day = $today['mday']; 
$b_year = $today['year']; 
if ($year < 1) :
   $month = $b_mon;
   $mday = $b_day;
   $year = $b_year;
endif;

$lastday=array(0,31,28,31,30,31,30,31,31,30,31,30,31);
if ($year%4 == 0) $lastday[2] = 29;
$dayoftheweek = date("w", mktime (0,0,0,$month,1,$year));

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<?php if ($rss_href || $write_href) { ?>
<ul class="<?php echo isset($view) ? 'view_is_list btn_top' : 'btn_top top';?>">
    <?php if ($rss_href) { ?><li><a href="<?php echo $rss_href ?>" class="btn_b01"><i class="fa fa-rss" aria-hidden="true"></i><span class="sound_only">RSS</span></a></li><?php } ?>
    <?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>" class="btn_admin"><i class="fa fa-user-circle" aria-hidden="true"></i><span class="sound_only">관리자</span></a></li><?php } ?>
    <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b02"><i class="fa fa-pencil" aria-hidden="true"></i> 글쓰기</a></li><?php } ?>
</ul>
<?php } ?>
<!-- 게시판 목록 시작 -->
<div id="bo_list" style="margin:0 5px;">

    <?php if ($is_category) { ?>
    <nav id="bo_cate">
        <h2><?php echo ($board['bo_mobile_subject'] ? $board['bo_mobile_subject'] : $board['bo_subject']) ?> 카테고리</h2>
        <ul id="bo_cate_ul">
            <?php echo $category_option ?>
        </ul>
    </nav>
    <?php } ?>

    <div id="bo_list_total">
        <span>전체 <?php echo number_format($total_count) ?>건</span>
        <?php echo $page ?> 페이지
    </div>


	<p class="daymenu">
		<a href="<?php echo "$_SERVER[PHP_SELF]?bo_table=$bo_table&"?><?php if ($month == 1) : $year_pre=$year-1; $month_pre=12; else : $year_pre=$year-1; $month_pre=$month;endif; echo ("year=$year_pre&month=$month_pre");?><?php echo $qstr?>" title="<?php echo $year_pre?>년"><img src="<?php echo $board_skin_url?>/img/day/y_prev.gif" border="0" alt="<?php echo $year_pre?>년" align="absmiddle" /></a>
		<a href="<?php echo "$_SERVER[PHP_SELF]?bo_table=$bo_table&"?><?php if ($month == 1) : $year_pre=$year-1; $month_pre=12; else : $year_pre=$year; $month_pre=$month-1;endif; echo ("year=$year_pre&month=$month_pre");?><?php echo $qstr?>" title="<?php echo $month_pre?>월"><img src="<?php echo $board_skin_url?>/img/day/prev.gif" border="0" alt="<?php echo $month_pre?>월" align="absmiddle" /></a>
		&nbsp; &nbsp;<a href="<?php echo "$_SERVER[PHP_SELF]?bo_table=$bo_table&"?>" title="오늘로"><b><?php echo ("$year".년." $month".월); ?></b></a> &nbsp; &nbsp;
		<a href="<?php echo "$_SERVER[PHP_SELF]?bo_table=$bo_table&"?><?php if ($month == 12) : $year_pre=$year+1; $month_pre=1; else : $year_pre=$year; $month_pre=$month+1;endif; echo ("&year=$year_pre&month=$month_pre");?><?php echo $qstr?>" title="<?php echo $month_pre?>월"><img src="<?php echo $board_skin_url?>/img/day/next.gif" border="0" alt="<?php echo $month_pre?>월" align="absmiddle" /></a>
		<a href="<?php echo "$_SERVER[PHP_SELF]?bo_table=$bo_table&"?><?php if ($month == 12) : $year_pre=$year+1; $month_pre=1; else : $year_pre=$year+1; $month_pre=$month;endif; echo ("&year=$year_pre&month=$month_pre");?><?php echo $qstr?>" title="<?php echo $year_pre?>년"><img src="<?php echo $board_skin_url?>/img/day/y_next.gif" border="0" alt="<?php echo $year_pre?>년" align="absmiddle" /></a>	
	</p>
<table border="0" cellpadding="3" cellspacing="0" class="caltable">
<thead>
<tr height="30">
    <th class="sunday">일</th>
    <th>월</th>
    <th>화</th>
    <th>수</th>
    <th>목</th>
    <th>금</th>
    <th class="saturday">토</th>
</tr>
</thead>
<tbody>
<?php
$cday = 1;
$k = 0;
$sel_mon = sprintf("%02d",$month);
if ($is_category) {
	if($sca) $caname = " and ca_name = '$sca' "; 
}else {
$caname = "";
}
$query = "SELECT * FROM $write_table WHERE left(wr_1,6) <= '$year$sel_mon'  and left(wr_2,6) >= '$year$sel_mon' $caname ORDER BY wr_id ASC";
$result = sql_query($query);

// 내용을 보여주는 부분
for ($j=0; $row=sql_fetch_array($result); $j++) { // 제목글 뽑아서 링크 문자열 만들기..
 if (substr($row['wr_1'],0,6) <  $year.$sel_mon) :
	 $start_day = 1; 
	 $start_day = (int)$start_day;
 else :
	 $start_day = substr($row['wr_1'],6,2);
     $start_day = (int)$start_day;
 endif;

 if (substr($row['wr_2'],0,6) >  $year.$sel_mon) :
	 $end_day = $lastday[$month];
	 $end_day = (int)$end_day;
 else :
	 $end_day = substr($row['wr_2'],6,2);
	 $end_day = (int)$end_day;
 endif;

  for ($i = $start_day; $i <= $end_day;  $i++) :

	//if ($is_admin) {
	$functionlayer=G5_BBS_URL."/board.php?bo_table=$bo_table&year=$year&month=$month&wr_id=$row[wr_id]" ;
	//} else { $functionlayer="javascript://";}
	
    if ($member['mb_level'] < $board['bo_read_level']) {
      $showLayer="" ;
    } else { 
      $showLayer=" onmouseover=\"PopupShow('".$k."')\" onmouseout=\"PopupHide('".$k."')\" ";
    }
		
	//$html_day[$i].= "<br><img src='$board_skin_url/img/day/icon.gif' border=0 align=absmiddle alt=''><a href='{$functionlayer}' class=d_text id='subject_".$k."' ".$showLayer.">" .cut_str($row['wr_subject'], 8, '…' ). "</a>"."\n";
	$html_day[$i].= "<br><img src='$board_skin_url/img/day/icon.gif' border=0 align=absmiddle alt=''><a href='{$functionlayer}' class=d_text title='{$row[wr_subject]}'>" .cut_str($row['wr_subject'], 2, '…' ). "</a>"."\n";
?>
<? /*
<DIV ID=popup_<?=$k?> style="position:absolute; display:none; width:200px; border:solid 1px #a2a2a2; background-color:white; padding:5px; z-index:2;"> 
<?php
    $html = 0;
    if (strstr($row[wr_option], "html1"))
      $html = 1;
    else if (strstr($row[wr_option], "html2"))
      $html = 2;
   $viewlist = cut_str(conv_content($row['wr_content'], $html),200,"…");
   $from_date = sprintf("%2d",substr($row['wr_1'],4,2))."월 ".sprintf("%2d",substr($row['wr_1'],6,2))."일";
   $to_date   = sprintf("%2d",substr($row['wr_2'],4,2))."월 ".sprintf("%2d",substr($row['wr_2'],6,2))."일";
echo "<b>$row[wr_subject]</b><br>";
echo "날짜 : ".$from_date." ~ ".$to_date."<br>";
echo "─────────<br>";
echo $viewlist;
?>
</DIV>
*/?>
<?php
  $k++;		
  endfor;
}

// 달력의 틀을 보여주는 부분

$temp = 7- (($lastday[$month]+$dayoftheweek)%7);

if ($temp == 7) $temp = 0;
     $lastcount = $lastday[$month]+$dayoftheweek + $temp;

for ($iz = 1; $iz <= $lastcount; $iz++) { // 42번을 칠하게 된다.
	$bgcolor = "";  // 쭉 흰색으로 칠하고
	if ($b_year==$year && $b_mon==$month && $b_day==$cday) $bgcolor = "bgcolor='#DFFDDF'";      //  "#DFFDDF"; 
	if (($iz%7) == 1) echo "<tr>\n"; // 주당 7개씩 한쎌씩을 쌓는다.
	if ($dayoftheweek < $iz  &&  $iz <= $lastday[$month]+$dayoftheweek)	{
	 // 전체 루프안에서 숫자가 들어가는 셀들만 해당됨
	 // 즉 11월 달에서 1일부터 30 일까지만 해당
	   $daytext = "$cday";   // $cday 는 숫자 예> 11월달은 1~ 30일 까지
	   //$daytext 은 셀에 써질 날짜 숫자 넣을 공간
	   if ($iz%7 == 1) $daytext = "<span class=sun>$daytext</span>"; // 일요일
	   if ($iz%7 == 0) $daytext = "<span class=sat>$daytext</span>"; // 토요일
  
       // 여기까지 숫자와 들어갈 내용에 대한 변수들의 세팅이 끝나고 
       // 이제 여기 부터 직접 셀이 그려지면서 그 안에 내용이 들어 간다.
	   echo "<td $bgcolor style='text-align:left;'>\n";
	   if ($write_href) { 
		 // $write_href (글쓰기 권한)이 있으면
		 // 날짜에 누르면 글씨쓰기가 가능한 링크를 넣어서 출력하기
		 $f_date = $year.sprintf("%02d",$month).sprintf("%02d",$cday);
       	 echo "<a href='$write_href&f_date=$f_date&t_date=$f_date' title='글쓰기'><span class=day>$daytext</span></a>\n";
	   }else{ // 글쓰기 권한이 없으면 글쓰기 링크는 넣지 않고 그냥 숫자만 출력하기 
         echo "<span class=day>$daytext</span>\n";
       }
	   echo $html_day[$cday];
	   echo "</td>\n";  // 한칸을 마무리
	  $cday++; // 날짜를 카운팅
	}else { 
		echo "<td>&nbsp;</td>\n";
	}
   if (($iz%7) == 0) echo "</tr>\n";   
} // 반복구문이 끝남
?>
</tbody>
</table>	
</div>
<script language="JavaScript">
<!--
// 미리보기 팝업 보이기
function PopupShow(n) {
	var position = $("#subject_"+n).position(); 
	$("#popup_"+n).animate({left:position.left-10+"px", top:position.top+30+"px"},0);
	$("#popup_"+n).show();
}

// 미리보기 팝업 숨기기
function PopupHide(n) {
	$("#popup_"+n).hide();
}
//-->
</script>