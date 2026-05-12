<?php
include_once('./_common.php');

define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/index.php');
    return;
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/index.php');
    return;
}

include_once(G5_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
?>

<!-- 상단메뉴 시작-->
<?php include_once(G5_PATH.'/inc/top.php'); ?>


<!-- 메인 비주얼 -->
<div id="main_visual_line">
	<div id="main_visual_area">

		<div id="main_visual_left">
		
			<div>
				<p class="visual_left_txt01">일하는 노인!  새로운 청춘!</p>
				<p class="visual_left_txt02">전북의 다양한 소식을 영상으로 전해드립니다.</p>
				<p class="visual_left_txt03">새로운 일자리! 새롭게 시작하는 실버라이프</p>	

				


	<script type="text/javascript">
		$(document).ready(function() {
			//Default Action
			$(".stab_content").hide(); //Hide all content
			$("ul.stabs li:first").addClass("active").show(); //Activate first tab
			$(".stab_content:first").show(); //Show first tab content			
			//On Click Event
			$("ul.stabs li").click(function() {
				$("ul.stabs li").removeClass("active"); //Remove any "active" class
				$(this).addClass("active"); //Add "active" class to selected tab
				$(".stab_content").hide(); //Hide all tab content
				var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
				$(activeTab).fadeIn(); //Fade in the active content
				return false;
			});

		});
	</script>

		<div class="stabs_container">	
			<ul class="stabs">
				<li><a href="#stab1">공지사항</a></li>
				<li><a href="#stab2">언론보도</a></li>
				<!--<li><a href="#stab3">도정소식</a></li>-->
			</ul>

			<div class="stab_container">
				<div id="stab1" class="stab_content"><?php echo latest("basic2", "sub06_01", 5, 36);?>	</div>
				<div id="stab2" class="stab_content"><?php echo latest("basic2", "sub06_04", 5, 36);?>	</div>	
				<!--<div id="stab3" class="stab_content"><span style="position:absolute;top:50px;right:0px"></a></span><?php echo jbba_api("http://www.jeonbuk.go.kr/board/openApi/rss.jeonbuk?boardId=JEONBUK_NOTICE",5,36,"DOM_000000102001001001")?></div>	-->
			</div>
		</div>	


				

			</div>
		
		</div>

		<div id="main_visual_right">
			<p class="visual_photo"><img src="<?php echo G5_IMG_URL ?>/visual_photo.png"></p>
			<div class="visual_tv"><iframe width="375" height="239" src="https://www.youtube.com/embed/<?php echo $config['cf_1']?>?autoplay=1" frameborder="0" allowfullscreen></iframe></div>		
		</div>

	</div>
</div>





<div id="board_job_line">
	<div id="board_job_area">

		<div id="board_job_left" style="padding-right:50px">
		<a href="javascript:menu4sub1();"><div id="board_title">60+ 구인정보<span class="board_more"><img src="<?php echo G5_IMG_URL ?>/board_more.gif"></span></div></a>
		<?php echo latest("basic3", "sub04_01", 5, 26);?>	
		</div>
		
		<div id="board_job_left"><a href="javascript:menu4sub2();"><div id="board_title">워크넷 구인정보<span class="board_more"><img src="<?php echo G5_IMG_URL ?>/board_more.gif"></span></div></a>
		<?php include_once(G5_PATH.'/work/latest.php');?></div>


	</div>
</div>





<div id="main_box_line">
	<div id="main_box_area">
		<li style="padding-right:20px"><a href="javascript:menu7sub4();"><img src="<?php echo G5_IMG_URL ?>/main_box01.gif" border="0"></a></li>
		<li style="padding-right:20px"><img src="<?php echo G5_IMG_URL ?>/main_box02.gif"></li>
		<li><a href="javascript:menu1sub6();"><img src="<?php echo G5_IMG_URL ?>/main_box03.gif" border="0"></a></li>

	</div>
</div>




<div id="intro_line">
				<div style="padding-left:410px;">
					<p class="intro_txt01">일하는 노인!  새로운 청춘!</p>
					<p class="intro_txt02">전북의 다양한 노인일자리 소식을 전해드립니다.</p>
					<p class="intro_txt03">새로운 일자리! 새롭게 시작하는 실버라이프</p>	

					<div id="intro_btn">
						<a href="javascript:menu1sub1();"><li>인사말</li></a>
						<a href="javascript:menu6sub7();"><li>뉴스레터</li></a>
						<a href="javascript:menu3sub1();"><li>취업가이드</li></a>
						<a href="javascript:menu1sub5();"><li>사업계획</li></a>
						<a href="javascript:menu5sub1();"><li>일드림TV</li></a>
					</div>
				</div>
</div>





<div id="banner_line">
	<div id="banner_line_area">
		<li><a href="http://www.jeonbuk.go.kr/index.jeonbuk" target="_blank"><img src="<?php echo G5_IMG_URL ?>/banner01.gif"></a></li>
		<li><a href="http://www.mohw.go.kr/front_new/index.jsp" target="_blank"><img src="<?php echo G5_IMG_URL ?>/banner02.gif"></a></li>
		<li><a href="http://www.moel.go.kr/" target="_blank"><img src="<?php echo G5_IMG_URL ?>/banner03.gif"></a></li>
		<li><a href="http://www.koreapeople.co.kr/" target="_blank"><img src="<?php echo G5_IMG_URL ?>/banner04.gif"></a></li>
		<li><a href="https://www.cwma.or.kr/index.do" target="_blank"><img src="<?php echo G5_IMG_URL ?>/banner05.gif"></a></li>
		<li><a href="http://blog.naver.com/jb9021829" target="_blank"><img src="<?php echo G5_IMG_URL ?>/banner06.gif"></a></li>
	</div>
</div>













<!-- 카피 시작-->
<?php include_once(G5_PATH.'/inc/copy.php'); ?>
















<?php include_once(G5_PATH.'/tail.sub.php'); ?>