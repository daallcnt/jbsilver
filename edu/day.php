<?php
include_once('../common.php');
$gr_id = "sub10";
$bo_table = "sub10_05";
include_once(G5_PATH.'/head.php');
$board_skin_url = G5_SKIN_URL.'/board/schedule';
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_URL.'/edu/style.css">', 0);

//if (!$is_member)
//    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_URL."/edu/index.php"));


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
?>

<!-- 게시판 목록 시작 { -->
<div id="bo_list" style="width:<?php echo $width; ?>">

	<p class="daymenu">
		<a href="<?php echo "$_SERVER[PHP_SELF]?"?><?php if ($month == 1) : $year_pre=$year-1; $month_pre=12; else : $year_pre=$year-1; $month_pre=$month;endif; echo ("year=$year_pre&month=$month_pre");?><?php echo $qstr?>" title="<?php echo $year_pre?>년"><img src="<?php echo $board_skin_url?>/img/day/y_prev.gif" border="0" alt="<?php echo $year_pre?>년" align="absmiddle" /></a>
		<a href="<?php echo "$_SERVER[PHP_SELF]?"?><?php if ($month == 1) : $year_pre=$year-1; $month_pre=12; else : $year_pre=$year; $month_pre=$month-1;endif; echo ("year=$year_pre&month=$month_pre");?><?php echo $qstr?>" title="<?php echo $month_pre?>월"><img src="<?php echo $board_skin_url?>/img/day/prev.gif" border="0" alt="<?php echo $month_pre?>월" align="absmiddle" /></a>
		&nbsp; &nbsp;<a href="<?php echo "$_SERVER[PHP_SELF]?"?>" title="오늘로"><b><?php echo "{$year}년 {$month}월"; ?></b></a> &nbsp; &nbsp;
		<a href="<?php echo "$_SERVER[PHP_SELF]?"?><?php if ($month == 12) : $year_pre=$year+1; $month_pre=1; else : $year_pre=$year; $month_pre=$month+1;endif; echo ("&year=$year_pre&month=$month_pre");?><?php echo $qstr?>" title="<?php echo $month_pre?>월"><img src="<?php echo $board_skin_url?>/img/day/next.gif" border="0" alt="<?php echo $month_pre?>월" align="absmiddle" /></a>
		<a href="<?php echo "$_SERVER[PHP_SELF]?"?><?php if ($month == 12) : $year_pre=$year+1; $month_pre=1; else : $year_pre=$year+1; $month_pre=$month;endif; echo ("&year=$year_pre&month=$month_pre");?><?php echo $qstr?>" title="<?php echo $year_pre?>년"><img src="<?php echo $board_skin_url?>/img/day/y_next.gif" border="0" alt="<?php echo $year_pre?>년" align="absmiddle" /></a>	
	</p>
<table border="0" cellpadding="3" cellspacing="0" class="caltable">
<thead>
<tr height="35">
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
$query = "SELECT * FROM wp_education WHERE left(schedule,7) <= '$year-$sel_mon'  and left(edate,7) >= '$year-$sel_mon' ORDER BY s_id ASC";
$result = sql_query($query);

// 내용을 보여주는 부분
for ($j=0; $row=sql_fetch_array($result); $j++) { // 제목글 뽑아서 링크 문자열 만들기..
	$to_date = date("Ym", strtotime($row['schedule']));
	$mo_date = date("Ym", strtotime($row['edate']));

 if ($to_date <  $year.$sel_mon) :
	 $start_day = 1; 
	 $start_day = (int)$start_day;
 else :
	 $start_day = substr($row['schedule'],8,2);
     $start_day = (int)$start_day;
 endif;

 if ($mo_date >  $year.$sel_mon) :
	 $end_day = $lastday[$month];
	 $end_day = (int)$end_day;
 else :
	 $end_day = substr($row['edate'],8,2);
	 $end_day = (int)$end_day;
 endif;

  for ($i = $start_day; $i <= $end_day;  $i++) :



	$functionlayer= "./index.php?case=view&amp;s_id={$row['s_id']}";

      //$showLayer=" onmouseover=\"PopupShow('".$k."')\" onmouseout=\"PopupHide('".$k."')\" ";

		
	//$html_day[$i].= "<br><img src='$board_skin_url/img/day/icon.gif' border=0 align=absmiddle alt=''><a href='{$functionlayer}' class=d_text id='subject_".$k."' ".$showLayer.">" .cut_str($row['wr_subject'], 8, '…' ). "</a>"."\n";
		$html_day[$i].= "<br><img src='$board_skin_url/img/day/icon.gif' border=0 align=absmiddle alt=''><a href='{$functionlayer}' class=d_text title='{$row['subject']}'>" .cut_str($row['subject'], 14, '…' ). "</a>"."\n";
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
	   echo "<td $bgcolor>\n";

         echo "<span class=day>$daytext</span>\n";

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
<?php include_once(G5_PATH.'/tail.php'); ?>
