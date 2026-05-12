<div id="top_line">
<?php if(defined('_INDEX_')) { // index에서만 실행
        include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
} ?>
<?php
$current_gr_id = isset($gr_id) ? $gr_id : '';
$current_bo_table = isset($bo_table) ? $bo_table : '';
?>
	
	<div id="top_line_area">
		<div id="btn_home">
			<li><a href="<?php echo G5_URL ?>">HOME</a></li>
			<!--<li><a href="http://www.jbsilver.net/" target="_blank">전북노인일자리센터</a></li>		-->
		</div>
		<div id="btn_log">
			<?php if ($is_member) {  ?>
			<?php if ($is_admin) {  ?>
			<li><a href="<?php echo G5_ADMIN_URL ?>">관리자</a></li>
			<?php }  ?>
			<li><a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=<?php echo G5_BBS_URL ?>/register_form.php">정보변경</a></li>
			<li><a href="<?php echo G5_BBS_URL ?>/logout.php">로그아웃</a></li>
			<?php } else {  ?>
			<li><a href="<?php echo G5_BBS_URL ?>/register.php">회원가입</a></li>
			<li><a href="<?php echo G5_BBS_URL ?>/login.php?url=<?=$urlencode?>">로그인</a></li>
			
			<?php }  ?>	
		</div>
	</div>
</div>




<div id="logo_line">
	<div id="logo">
		<a href="<?php echo G5_URL ?>"><img src="<?php echo G5_IMG_URL ?>/logo_2025.gif" alt="<?php echo $config['cf_title']; ?>"></a>
	</div>
	
	<div id="youtube">
		<!--<a href="javascript:menu6sub8();"><img src="<?php echo G5_IMG_URL ?>/top_btn_con.gif"></a>-->
		<a href="https://youtube.com/@tv-tu7dx?si=oJ6DYK2MzIGHJHFC" target="_blank" style="padding-left:20px"><img src="<?php echo G5_IMG_URL ?>/youtube_ch_2025.gif"></a>
		<!--<a href="https://blog.naver.com/05jbsilver" target="_blank" style="padding-left:20px"><img src="<?php echo G5_IMG_URL ?>/naverblog.gif"></a>-->
	</div>
	
</div>



<script>
$(function(){
	var $categoryWrap = $("#category_wrap");
	var $categoryMenus = $("#category_wrap ul");

	$("#gnb_menu > li").mouseenter(function(){
		var i = $(this).index();
		var $menu = $categoryMenus.hide().eq(i).show();
		$categoryWrap.stop().animate({"height":$menu.outerHeight(true) + 26},200);
	});

	$("#menu_line").mouseleave(function(){
		$categoryWrap.stop().animate({"height":0},200);
	});
});
</script>




<!--메인라인-->
<div id="menu_line">
	<div id="menu_set">
			<ul id="gnb_menu">
				<li class="menu1 <?php echo ($current_gr_id == 'sub01') ? 'is_active' : ''; ?>"><a href="javascript:menu1sub1();">센터안내</a></li>
				<li class="menu2 <?php echo ($current_gr_id == 'sub02') ? 'is_active' : ''; ?>"><a href="javascript:menu2sub3();">사업안내</a></li>
				<li class="menu8 <?php echo ($current_gr_id == 'sub10') ? 'is_active' : ''; ?>"><a href="javascript:menu10sub1();">교육안내</a></li>
				<li class="menu4 <?php echo ($current_gr_id == 'sub04' && $current_bo_table != 'sub04_03') ? 'is_active' : ''; ?>"><a href="javascript:menu4sub2();">일자리안내</a></li>
				<li class="menu5 <?php echo ($current_bo_table == 'sub04_03') ? 'is_active' : ''; ?>"><a href="javascript:menu4sub3();">질문안내</a></li>
				<li class="menu6 <?php echo ($current_gr_id == 'sub06' || $current_gr_id == 'sub07' || $current_gr_id == 'sub09') ? 'is_active' : ''; ?>"><a href="javascript:menu6sub1();">정보안내</a></li>
				
			</ul>
	</div>

	<!--메인메뉴 펼침메뉴-->
	<div id="category_wrap">
		<div id="category">
			
				<ul class="sub1">
					<li><a href="javascript:menu1sub1();">인사말</a></li>
					<li><a href="javascript:menu1sub2();">미션/비전</a></li>
					<li><a href="javascript:menu1sub3();">연혁</a></li>
					<li><a href="javascript:menu1sub4();">조직도</a></li>
					<li><a href="javascript:menu1sub7();">찾아오는길</a></li>
				</ul>

				
				<ul class="sub2">
					<li><a href="javascript:menu2sub3();">노인일자리확충을 위한 네트워크사업</a></li>
					<li><a href="javascript:menu2sub2();">노인일자리 양성 및 맞춤형 교육사업</a></li>
					<li><a href="javascript:menu2sub4();">지역복지사업</a></li>
					<li><a href="javascript:menuPreparing();">외부공모사업 <span class="nav_pending">연결 준비중</span></a></li>
				</ul>

				
				<ul class="sub3">
					<li><a href="javascript:menu10sub1();">교육신청</a></li>
					<li><a href="javascript:menu10sub4();">수료증발급</a></li>
				</ul>	
				
				
				<ul class="sub4">
					<li><a href="javascript:menuPreparing();">노인일자리소개 <span class="nav_pending">연결 준비중</span></a></li>
					<li><a href="javascript:menu4sub2();">구직서비스</a></li>
					<li><a href="javascript:menu4sub1();">구인서비스</a></li>
				</ul>

				
				<ul class="sub5">
					<li><a href="javascript:menu4sub3();">무엇이든 물어보세요(Q&amp;A)</a></li>
				</ul>

				
				<ul class="sub6">
					<li><a href="javascript:menu6sub1();">공지사항</a></li>
					<li><a href="javascript:menu6sub3();">월별일정</a></li>
					<li><a href="javascript:menu6sub4();">보도자료</a></li>
					<li><a href="javascript:menu7sub1();">자료실</a></li>
					<li><a href="javascript:menu6sub5();">보도갤러리</a></li>
					<li><a href="javascript:menu6sub7();">뉴스레터</a></li>
					<li><a href="javascript:menu9sub1();">시니어스토어&amp;노인생산품</a></li>
				</ul>

			

		</div>
	</div>
        
</div>

