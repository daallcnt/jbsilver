<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/head.php');
    return;
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/head.php');
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
		  case 'sub01_02' :$SWF_Num = '2';	$board['bo_subject'] = '비전/미션';  break;
		  case 'sub01_03' :$SWF_Num = '3';	$board['bo_subject'] = '연혁';  break;
		  case 'sub01_04' :$SWF_Num = '4';	$board['bo_subject'] = '조직도';  break;
		  case 'sub01_05' :$SWF_Num = '5';	$board['bo_subject'] = '사업계획';  break;
		  case 'sub01_07' :$SWF_Num = '7';	$board['bo_subject'] = '찾아오시는 길';  break;
		}
		switch ($bo_table) {
		  case 'sub01_06' :$SWF_Num = '6';	$board['bo_subject'] = '수행기관/검색';  break;
		}
    break;
	case "sub02" :
		$group['gr_subject'] = '주요업무'; 
		$SWF_pageNum	= '2';
		switch ($co_id) {
		  case 'sub02_01' :$SWF_Num = '1';	$board['bo_subject'] = '일자리사업';  break;
		  case 'sub02_02' :$SWF_Num = '2';	$board['bo_subject'] = '노인일자리 양성 및 맞춤형 교육사업';  break;
		  case 'sub02_03' :$SWF_Num = '3';	$board['bo_subject'] = '노인일자리확충을 위한 네트워크사업';  break;
		  case 'sub02_04' :$SWF_Num = '4';	$board['bo_subject'] = '지역복지사업';  break;
		}
	break;
	case "sub03" :
		$group['gr_subject'] = '취업가이드'; 
		$SWF_pageNum	= '3';
		switch ($co_id) {
		  case 'sub03_01' :$SWF_Num = '1';	$board['bo_subject'] = '주요취업분야';  break;
		  case 'sub03_02' :$SWF_Num = '2';	$board['bo_subject'] = '이력서작성요령';  break;
		  case 'sub03_03' :$SWF_Num = '3';	$board['bo_subject'] = '면접준비';  break;
		  case 'sub03_04' :$SWF_Num = '4';	$board['bo_subject'] = '취업교육안내';  break;
		}
		switch ($bo_table) {
		  case 'sub03_05' :$SWF_Num = '5';	$board['bo_subject'] = '메인알림';  break;
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
		$group['gr_subject'] = '알림마당'; 
		$SWF_pageNum	= '6';
		switch ($co_id) {
		  case 'sub06_08' :$SWF_Num = '8';	$board['bo_subject'] = '노무·세무 상담';  break;
		}
		
		switch ($bo_table) {
		  case 'sub06_01' :$SWF_Num = '1';	$board['bo_subject'] = '공지사항';  break;
		  case 'sub06_02' :$SWF_Num = '2';	$board['bo_subject'] = '자유게시판';  break;
		  case 'sub06_03' :$SWF_Num = '3';	$board['bo_subject'] = '월별일정';  break;
		  case 'sub06_04' :$SWF_Num = '4';	$board['bo_subject'] = '언론보도';  break;
		  case 'sub06_05' :$SWF_Num = '5';	$board['bo_subject'] = '포토갤러리';  break;
		  case 'sub06_06' :$SWF_Num = '6';	$board['bo_subject'] = '금주의시';  break;
		  case 'sub06_07' :$SWF_Num = '7';	$board['bo_subject'] = '뉴스레터';  break;
		  case 'sub06_08' :$SWF_Num = '8';	$board['bo_subject'] = '노무·세무 상담';  break;
		}
	break;
	case "sub07" :
		$group['gr_subject'] = '자료실'; 
		$SWF_pageNum	= '7';
		switch ($bo_table) {
		  case 'sub07_01' :$SWF_Num = '1';	$board['bo_subject'] = '노인일자리사업자료';  break;
		  case 'sub07_02' :$SWF_Num = '2';	$board['bo_subject'] = '연구조사';  break;
		  case 'sub07_03' :$SWF_Num = '3';	$board['bo_subject'] = '동영상';  break;
		  case 'sub07_04' :$SWF_Num = '4';	$board['bo_subject'] = '시니어컴퍼니';  break;
		  case 'sub07_05' :$SWF_Num = '5';	$board['bo_subject'] = '아이디어제안';  break;
		}
	break;

	case "sub08" :
		$group['gr_subject'] = '노인채용기업'; 
		$SWF_pageNum	= '8';
		switch ($bo_table) {
		  case 'sub08_01' :$SWF_Num = '1';	$board['bo_subject'] = '시니어컴퍼니';  break;
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
		  case 'sub10_03' :$SWF_Num = '3';	$board['bo_subject'] = '신청현황';  break;
		  case 'sub10_04' :$SWF_Num = '4';	$board['bo_subject'] = '수료증';  break;
		  case 'sub10_02' :$SWF_Num = '2';	$board['bo_subject'] = '취업교육영상';  break;
		  case 'sub10_05' :$SWF_Num = '5';	$board['bo_subject'] = '월별교육일정';  break;
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

<!-- 상단메뉴 시작-->
<?php include_once(G5_PATH.'/inc/top.php'); ?>


<!--서브 비주얼 -->
<div id="sub_visual_line">
	<div id="sub_visual_area">
	</div>
</div>





<div id="sub_wrapper">

	<!--로케이션영역-->
	<div id="location_set"> 
		<ul>
			<li><span><?=$tit?><span></li>		
		</ul>
	</div> 
	<!--로케이션영역 끝-->

	<? if (!isset($co_id) || !in_array($co_id, array('sub01_02', 'sub02_02', 'sub02_03', 'sub04_00'))) include_once(G5_PATH.'/inc/left.php');?>

	<div id="sub" class="<?php echo (isset($co_id) && in_array($co_id, array('sub01_02', 'sub02_02', 'sub02_03', 'sub04_00'))) ? 'sub_full_width' : ''; ?>"> <!-- (s)sub-->	  
		<p class="pt_underline"><span id="pt_title"><?=$board['bo_subject']?></span><span class="pt_title_side">행복한 노후! 새로운 청춘! 전북노인일자리센터가 희망과 활기를 불어넣어 드리겠습니다.</span></p>							
			
		<div id="contents">
