<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/head.php');
    return;
}

include_once(G5_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

switch ($gr_id) {
	case "sub01" :	
        $group['gr_subject'] = '센터소개'; 
		$SWF_pageNum	= '1';
		switch ($co_id) {
		  case 'sub01_01' :$SWF_Num = '1';	$board['bo_subject'] = '인사말';  break;
		  case 'sub01_07' :$SWF_Num = '7';	$board['bo_subject'] = '찾아오시는 길';  break;
		}
		switch ($bo_table) {
		  case 'sub01_06' :$SWF_Num = '6';	$board['bo_subject'] = '수행기관/검색';  break;
		}
    break;
	case "sub02" :
		$group['gr_subject'] = '사업정보'; 
		$SWF_pageNum	= '2';
		switch ($co_id) {
		  case 'sub02_01' :$SWF_Num = '1';	$board['bo_subject'] = '일자리사업';  break;
		  case 'sub02_02' :$SWF_Num = '2';	$board['bo_subject'] = '교육사업';  break;
		  case 'sub02_03' :$SWF_Num = '3';	$board['bo_subject'] = '네트워크사업';  break;
		  case 'sub02_04' :$SWF_Num = '4';	$board['bo_subject'] = '지역복지사업';  break;
		}
	break;	
	case "sub04" :
		$group['gr_subject'] = '일자리안내'; 
		$SWF_pageNum	= '4';
		switch ($co_id) {
		  case 'sub04_00' :$SWF_Num = '1';	$board['bo_subject'] = '노인일자리소개';  break;
		}
	switch ($bo_table) {
	  case 'sub04_01' :$SWF_Num = '3';	$board['bo_subject'] = '60+구인정보';  break;
	  case 'sub04_02' :$SWF_Num = '2';	$board['bo_subject'] = '60+구직정보';  break;
	  case 'sub04_03' :$SWF_Num = '4';	$board['bo_subject'] = '구인구직 QnA';  break;
	}
	break;
	case "sub05" :
		$group['gr_subject'] = '일드림TV'; 
		$SWF_pageNum	= '5';
		switch ($bo_table) {
		  case 'sub05_01' :$SWF_Num = '1';	$board['bo_subject'] = '제이로그(Job-Log)';  break;
		  case 'sub05_03' :$SWF_Num = '3';	$board['bo_subject'] = '에듀로그(Edu-Log)';  break;
		  case 'sub05_04' :$SWF_Num = '4';	$board['bo_subject'] = '홍보관';  break;
		}
	break;
	case "sub06" :
		$group['gr_subject'] = '일자리이슈'; 
		$SWF_pageNum	= '6';
		switch ($bo_table) {
		  case 'sub06_01' :$SWF_Num = '1';	$board['bo_subject'] = '공지사항';  break;
		  case 'sub06_04' :$SWF_Num = '4';	$board['bo_subject'] = '보도자료';  break;
		}
	break;
	case "sub07" :
		$group['gr_subject'] = '시니어컴퍼니'; 
		$SWF_pageNum	= '7';
		switch ($bo_table) {
		  case 'sub07_04' :$SWF_Num = '4';	$board['bo_subject'] = '노인채용기업';  break;
		}
	
	break;
	case "sub08" :
		$group['gr_subject'] = '노인채용기업'; 
		$SWF_pageNum	= '8';
		switch ($bo_table) {
		  case 'sub08_01' :$SWF_Num = '1';	$board['bo_subject'] = '노인채용기업';  break;
		}
	break;
	case "sub09" :
		$group['gr_subject'] = '노인생산품'; 
		$SWF_pageNum	= '9';
		switch ($bo_table) {
		  case 'sub09_01' :$SWF_Num = '1';	$board['bo_subject'] = '노인생산품';  break;
		}
	break;
	case "sub10" :
		$group['gr_subject'] = '교육안내'; 
		$SWF_pageNum	= '10';
		switch ($bo_table) {
		  case 'sub10_01' :$SWF_Num = '1';	$board['bo_subject'] = '교육리스트';  break;
		  case 'sub10_05' :$SWF_Num = '5';	$board['bo_subject'] = '월별교육일정';  break;
		  case 'sub10_03' :$SWF_Num = '3';	$board['bo_subject'] = '신청현황';  break;
		  case 'sub10_04' :$SWF_Num = '4';	$board['bo_subject'] = '수료증';  break;
		  case 'sub10_02' :$SWF_Num = '1';	$board['bo_subject'] = '취업교육영상';  break;
		}
	break;
	case "sub11" :
		$group['gr_subject'] = '이용안내'; 
		$SWF_pageNum	= '11';
		switch ($co_id) {
		  case 'sub11_01' :$SWF_Num = '1';	$board['bo_subject'] = '이용약관';  break;
		  case 'sub11_02' :$SWF_Num = '2';	$board['bo_subject'] = '개인정보처리방침';  break;
		  case 'sub11_03' :$SWF_Num = '3';	$board['bo_subject'] = '이메일무단수집거부';  break;
		}
	break;

	default :
		$group['gr_subject'] = '회원공간';		
		$SWF_pageNum	= '12';
		$board['bo_subject'] = $g5['title'];
    break;
}


if(!$board['bo_subject']){
	$board['bo_subject'] = $g5['title'];
	$tit = "&gt; <strong>".$board['bo_subject']."</strong>";
}else{ 
	$tit = "Home &gt; ".$group['gr_subject']." &gt; <strong>".$board['bo_subject']."</strong>";
}
?>


<div id="main_back">

	<?php include_once(G5_MOBILE_PATH.'/inc/top.php'); ?>

</div>


<div id="sub_top">

<? if($gr_id == "sub01"){?>
	<p class="sub_menu_title"><?=$group['gr_subject']?></p>
	<p class="sub_title_bar"></p>
	<div id="left_menu">
		<li class="smenu1"><a href="javascript:menu1sub1();">인사말</a></li>
		<li class="smenu6"><a href="javascript:menu1sub6();">수행기관/검색</a></li>
		<li class="smenu7"><a href="javascript:menu1sub7();">찾아오시는길</a></li>	
	</div>
<? }?>


<? if($gr_id == "sub04"){?>
	<p class="sub_menu_title"><?=$group['gr_subject']?></p>
	<p class="sub_title_bar"></p>
	<div id="left_menu">
		<li class="smenu1"><a href="javascript:menu4sub0();">노인일자리소개</a></li>
		<li class="smenu2"><a href="javascript:menu4sub2();">60+구직정보</a></li>
		<li class="smenu3"><a href="javascript:menu4sub1();">60+구인정보</a></li>
		<li class="smenu4"><a href="javascript:menu4sub3();">구인구직 QnA</a></li>
	</div>
<? }?>

<? if($gr_id == "sub10"){?>
	<p class="sub_menu_title"><?=$group['gr_subject']?></p>
	<p class="sub_title_bar"></p>
	<div id="left_menu">
		<li class="smenu1"><a href="javascript:menu10sub1();">교육리스트</a></li>
		<li class="smenu5"><a href="javascript:menu10sub5();">월별교육일정</a></li>
		<li class="smenu3"><a href="javascript:menu10sub3();">신청현황</a></li>
		<li class="smenu4"><a href="javascript:menu10sub4();">수료증</a></li>

		<li class="smenu2"><a href="javascript:menu10sub2();">취업교육영상</a></li>
	</div>
<? }?>

<? if($gr_id == "sub06"){?>
	<p class="sub_menu_title"><?=$group['gr_subject']?></p>
	<p class="sub_title_bar"></p>
	<div id="left_menu">
		<li class="smenu1"><a href="javascript:menu6sub1();">공지사항</a></li>	
		<li class="smenu4"><a href="javascript:menu6sub4();">보도자료</a></li>	
	</div>
<? }?>

<? if($gr_id == "sub05"){?>
	<p class="sub_menu_title"><?=$group['gr_subject']?></p>
	<p class="sub_title_bar"></p>
	<div id="left_menu">
		<li class="smenu1"><a href="javascript:menu5sub1();">제이로그(Job-Log)</a></li>
		<li class="smenu3"><a href="javascript:menu5sub3();">에듀로그(Edu-Log)</a></li>	
		<li class="smenu4"><a href="javascript:menu5sub4();">홍보관</a></li>	
	</div>
<? }?>


<? if($gr_id == "sub02"){?>
	<p class="sub_menu_title"><?=$group['gr_subject']?></p>
	<p class="sub_title_bar"></p>
	<div id="left_menu">
		<li class="smenu1"><a href="javascript:menu2sub1();">일자리사업</a></li>
		<li class="smenu2"><a href="javascript:menu2sub2();">교육사업</a></li>
		<li class="smenu3"><a href="javascript:menu2sub3();">네트워크사업</a></li>	
		<li class="smenu4"><a href="javascript:menu2sub4();">지역복지사업</a></li>	
	</div>
<? }?>

<? if($gr_id == "sub07"){?>
	<p class="sub_menu_title"><?=$group['gr_subject']?></p>
	<p class="sub_title_bar"></p>
	<div id="left_menu">
		<li class="smenu1">시니어컴퍼니-노인채용기업을 소개합니다.</li>
	</div>
<? }?>


<? if($gr_id == "sub09"){?>
	<p class="sub_menu_title"><?=$group['gr_subject']?></p>
	<p class="sub_title_bar"></p>
	<div id="left_menu">
		<li class="smenu1">노인분들이 정성들여 생산한 제품을 소개합니다.</li>
	</div>
<? }?>

<? if($gr_id == "sub11"){?>
	<p class="sub_menu_title"><?=$group['gr_subject']?></p>
	<p class="sub_title_bar"></p>
	<div id="left_menu">
		<li class="smenu1"><a href="javascript:menu11sub1();">이용약관</a></li>
		<li class="smenu2"><a href="javascript:menu11sub2();">개인정보처리방침</a></li>
		<li class="smenu3"><a href="javascript:menu11sub3();">이메일무단수집거부</a></li>	
	</div>
<? }?>


<? if($SWF_pageNum == "12"){?>
	<p class="sub_menu_title"><?=$group['gr_subject']?></p>
	<p class="sub_title_bar"></p>
	<div id="left_menu">
		<li class="smenu1">일하는 노인! 새로운 청춘! 전북노인일자리센터</li>
	</div>
<? }?>


</div>




<div id="wrapper">

    <div id="container">
	<?php if (!defined("_INDEX_")) { ?><h2 id="container_title"><?=$board['bo_subject']?></h2><?php } ?>

	


