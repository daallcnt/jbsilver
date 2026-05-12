<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/index.php');
    return;
}

include_once(G5_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
?>




<div id="main_back">

	<?php include_once(G5_MOBILE_PATH.'/inc/top.php'); ?>



	<!-- 공지및 로그인
	<div id="main_noticeline">
	
		<div><a href="#" class="notice_txt"><i class="fa fa-bullhorn"></i> 2019년 시니어인턴십 참여기업...</a></div>
		<div class="login_area">
			<?php if ($is_member) {  ?>
			<li style="padding-right:10px;"><a href="<?php echo G5_BBS_URL ?>/logout.php"><i class="fa fa-unlock-alt"></i> 로그아웃</a></li>
			<li><a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=<?php echo G5_BBS_URL ?>/register_form.php" id="snb_modify"><i class="fa fa-cog"></i> 정보수정</a></li>
			<?php } else {  ?>
			<li style="padding-right:10px;"><a href="<?php echo G5_BBS_URL ?>/login.php?url=<?=$urlencode?>"><i class="fa fa-lock"></i> 로그인</a></li>
			<li><a href="<?php echo G5_BBS_URL ?>/register.php" id="snb_join"><i class="fa fa-user"></i> 회원가입</a></li>
			<?php }  ?>
		</div>
	
	</div>
	-->




	<!-- 구인박스 -->
	<div id="guin_line"">
	
		<a href="javascript:menu4sub1();" class="guin_color01"><li style="background:#f27e8c"><p class="pt_20"><img src="<?php echo G5_IMG_URL ?>/mobile/guin_icon01.png"></p><p class="pt_10">60+ 구인정보</p></li></a>
		<a href="javascript:menu10sub1();" class="guin_color02"><li style="background:#1797e8"><p class="pt_20"><img src="<?php echo G5_IMG_URL ?>/mobile/guin_icon02.png"></p><p class="pt_10">교육안내</p></li></a>
	
	</div>



	<!-- 아이콘박스 -->
	<div id="icon_line">
		<div class="slider-demo">
			<a href="#" id="previous"><img src="<?php echo G5_IMG_URL ?>/mobile/a_left.png"></a>
			
			<div id="slider-inner">
				<ul>						
					<li><a href="javascript:menu2sub1();"><img src="<?php echo G5_IMG_URL ?>/mobile/m_icon01.png"></a>
					<span class="icon_txt">사업정보</span></li>					
					<li><a href="javascript:menu1sub6();"><img src="<?php echo G5_IMG_URL ?>/mobile/m_icon02.png"></a>
					<span class="icon_txt">수행기관/검색</span></li>												
					<li><a href="https://www.instagram.com/05jbsilver/" target="_blank"><img src="<?php echo G5_IMG_URL ?>/mobile/m_icon05.png"></a>
					<span class="icon_txt">인스타그램</span></li>
					<li><a href="javascript:menu7sub4();"><img src="<?php echo G5_IMG_URL ?>/mobile/m_icon03.png"></a>
					<span class="icon_txt">노인채용기업</span></li>	
					<li><a href="javascript:menu9sub1();"><img src="<?php echo G5_IMG_URL ?>/mobile/m_icon04.png"></a>
					<span class="icon_txt">노인생산품</span></li>
				</ul>
		  </div>
		  <a href="#" id="next"><img src="<?php echo G5_IMG_URL ?>/mobile/a_right.png"></a>
		</div>



	</div>
    <script type="text/javascript" src="<?php echo G5_JS_URL ?>/flat.js"></script>
	
    <script type="text/javascript">
        $(function() {
            $('#slider-inner').slider('#previous', '#next');
        });
    </script>


	<!-- 공지사항 -->
	<div id="notice_line">
		
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
				<li><a href="#stab1">60+구인정보</a></li>
				<li><a href="#stab2">공지사항</a></li>
				<li><a href="#stab3">도정소식</a></li>
			</ul>

			<div class="stab_container">
				<div id="stab1" class="stab_content"><?php echo latest("basic1", "sub04_01", 3, 17);?>	</div>
				<div id="stab2" class="stab_content"><?php echo latest("basic1", "sub06_01", 3, 24);?>	</div>	
				<div id="stab3" class="stab_content"><?php echo jbba_api("http://www.jeonbuk.go.kr/board/openApi/rss.jeonbuk?boardId=JEONBUK_NOTICE",3,23,"DOM_000000102001001001")?></div>	
			</div>
		</div>	

	</div>



	<!-- 전화연결 -->
	<div id="tel_line"">
	
		<a href="tel:063-255-9112" class="tel_color01"><li class="tel_icon01"><p class="pt_15 pl_10">전화연결</p><p class="tel_color01_1 pt_5 pl_10">063-255-9112</p></li></a>
		<a href="javascript:menu1sub7();" class="tel_color02"><li class="tel_icon02"><p class="pt_15 pl_10">오시는길</p><p class="tel_color02_1 pt_5 pl_10">전주시 완산구 백제대로 342</p></li></a>
	
	</div>




	<!--
	<div id="ad_pop">
		
	<style>
	/* 메인 비쥬얼*/
	#main_visual{background:#aaaaaa;height:200px;min-width:340px;}/*border-top:1px solid #dddddd;border-bottom:1px solid #dddddd;*/
	#visual_area{width:100%;margin:0 auto;}
	/*메인이미지 롤링*/
	*{margin:0;padding:0}
	#slideshow {float:left; width:100%; height:200px;margin:0;}

	#slides {position:relative; width:100%; height:200px; list-style:none; overflow:auto;}
	#slides li {width:100%; height:200px}

	.rolling01 {background:url("<?php echo G5_IMG_URL ?>/mobile/main_visual01.jpg")top center no-repeat;background-size:cover;}
	.rolling02 {background:url("<?php echo G5_IMG_URL ?>/mobile/main_visual02.jpg")top center no-repeat;background-size:cover;}
	.rolling03 {background:url("<?php echo G5_IMG_URL ?>/mobile/main_visual03.jpg")top center no-repeat;background-size:cover;}

	.rolling01 div, .rolling02 div, .rolling03 div{width:340px;margin:0 auto;}

	.roll_txt01 {text-align:center;padding:60px 0 0 0;color:#ffffff;font-size:15px;text-shadow:1px 1px 1px #000000;}/*offset-x, offset-y, blur, spread, color, inset*/
	.roll_txt02 {text-align:center;padding:0;color:#ffffff;font-size:15px;text-shadow:1px 1px 1px #000000;}/*offset-x, offset-y, blur, spread, color, inset*/

	#visual_al{position:relative;width:340px; margin:0 auto;z-index:399}
	.pagination2 {position:absolute;width:100px;top:175px;margin-left:140px;} /* 버튼위치 background:#ddd*/
	.pagination2 li {float:left;list-style:none;cursor:pointer; padding:6px 6px;margin:0 8px 0 0; text-align:center;background:url("<?php echo G5_IMG_URL ?>/mobile/slide_off.png");background-repeat: no-repeat;}
	.pagination2 li:hover {background:url("<?php echo G5_IMG_URL ?>/mobile/slide_on.png");background-repeat: no-repeat;}
	li.current {background:url("<?php echo G5_IMG_URL ?>/mobile/slide_on.png");background-repeat: no-repeat;}

	#arrow_rea{position:relative;width:100%; margin:0 auto;z-index:399}  /* 화살표 background:#ddd*/
	#arrow_rea div{position:absolute;top:90px;width:100%;}
	#arrow_rea li{list-style:none;}
	#arrow_rea li:nth-child(1n){list-style:none;float:left;}
	#arrow_rea li:nth-child(2n){list-style:none;float:right;}

	</style>

	<div id="main_visual">		
		<div id="visual_area">


			<script src="<?php echo G5_JS_URL ?>/tinyfader.js"></script>
					<div id="slideshow">	
						<div id="arrow_rea">
							<div>
								<li><a href="javascript://"><img src="<?php echo G5_IMG_URL ?>/mobile/left_a.png" alt="Previous" onclick="slideshow.move(-1)"></a></li>
								<li><a href="javascript://"><img src="<?php echo G5_IMG_URL ?>/mobile/right_a.png" alt="Next" onclick="slideshow.move(1)"></a></li>
							</div>
						</div>

						
						<div id="visual_al">		
							<div id="pagination2" class="pagination2">
								<li onclick="slideshow.pos(0)">&nbsp;</li>
								<li onclick="slideshow.pos(1)">&nbsp;</li>
								<li onclick="slideshow.pos(2)">&nbsp;</li>
							</div>
						</div>	
						
						<ul id="slides">		
							<li class="rolling01"></li>
							<li class="rolling02"></li>
							<li class="rolling03"></li>
						</ul>
					</div>	

					<script type="text/javascript">
					var slideshow=new TINY.fader.fade('slideshow',{
						id:'slides',
						auto:4,
						resume:true,
						navid:'pagination2',
						activeclass:'current',
						visible:true,
						position:0
					});
					</script>
		
		
		</div>	
	</div>

	</div>
	-->



	<!-- 동영상 -->
	<div id="movie_line">
		<iframe width="100%" height="100%" src="https://www.youtube.com/embed/rpRZ96Plu8s?autoplay=1&loop=1&playlist=rpRZ96Plu8s" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
	</div>
	<br>




	<!-- 지도 
	<div id="map_line">
		<p class="map_txt">전북노인일자리센터 수행기관 안내</p>
		<img src="<?php echo G5_IMG_URL ?>/mobile/jbmap.png">
	</div>
	-->


	<!--<div id="copy_line">Copyright (C) 2019 jbsilver.net All Rights Reseved.</div>-->





</div>












<?php
include_once(G5_MOBILE_PATH.'/tail.php');
?>