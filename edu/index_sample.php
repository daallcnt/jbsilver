<?php
include_once('../common.php');
$gr_id = "sub10";
$bo_table = "sub10_01";
include_once(G5_PATH.'/head.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_SKIN_URL.'/board/basic/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_URL.'/edu/style.css">', 0);

//if (!$is_member)
//    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_URL."/edu/index.php"));

?>
<style>

.sub10_list_area {position:relative;width:100%;padding:10px 0;border-bottom:1px solid #dddddd;}
.sub10_list_area:after {display:block; visibility:hidden; clear:both; content:""; }

.sub10_list_area li:nth-child(1) {float:left; width:23%; text-align:center;}
.sub10_list_area li:nth-child(2) {float:left;width:54%; text-align:left;}
.sub10_list_area li:nth-child(3) {float:left; width:23%; text-align:center;}

.sub10_list_txt01 {font-size:18px;color:#000;font-weight:bold;padding:15px 0;}
.sub10_list_txt02 {font-size:13px;line-height:24px;color:#555}

.sub10_btn {padding-top:70px;}
.sub10_btn01 {display:inline-block;background:#666; color:#fff; text-align:center;width:60px;line-height:60px;}
.sub10_btn02 {display:inline-block;background:#327aff; color:#fff; text-align:center;width:60px;line-height:60px;}
.sub10_btn03 {display:inline-block;background:#ff324a; color:#fff; text-align:center;width:60px;line-height:60px;}

@media all and (max-width:650px)
{

.sub10_list_area li:nth-child(1) {float:left; width:100%; text-align:center;}
.sub10_list_area li:nth-child(2) {float:left;width:100%; text-align:left;padding:0 10px;}
.sub10_list_area li:nth-child(3) {float:left; width:100%; text-align:center;}

.sub10_list_txt01 {font-size:15px;color:#000;font-weight:bold;padding:15px 0;}
.sub10_list_txt02 {font-size:12px;line-height:24px;color:#555;letter-spacing:-0.05em;}

.sub10_btn {padding-top:10px;}
.sub10_btn01 {display:inline-block;background:#666; color:#fff; text-align:center;width:60px;line-height:30px;}
.sub10_btn02 {display:inline-block;background:#327aff; color:#fff; text-align:center;width:60px;line-height:30px;}
.sub10_btn03 {display:inline-block;background:#ff324a; color:#fff; text-align:center;width:60px;line-height:30px;}

}


</style>


<div>
	<ul class="sub10_list_area">
		<li><img src="<?php echo G5_IMG_URL ?>/noimage.jpg"></li>
		<li>
				<p class="sub10_list_txt01">바리스타 2급 자격과정 7차</p>
				<p class="sub10_list_txt02">- 교육정원 : 20명    | 강사명 : 오승연<br>
				- 교 육 비  : 재료비 및 자격증 비용 별도<br>
				- 교육일시 : 2020-11-23 ~ 2020-12-31<br>
				- 접수기간 : 2020-11-09 00:00:00 ~ 2020-12-30 00:00:00<br>
				- 교육장소 : 전라북도 노인회관 1층 드림카페 
				</p>		
		</li>
		<li><p class="sub10_btn"><a href="#" class="sub10_btn01">상세보기</a> <a href="#" class="sub10_btn02">접수중</a> <a href="#" class="sub10_btn03">접수마감</a></p></li>
	</ul>
</div>







<?php include_once(G5_PATH.'/tail.php'); ?>