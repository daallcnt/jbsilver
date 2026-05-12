<?php
include_once('./_common.php');

define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/index.php');
    return;
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/index_1110.php');
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
		<div class="main_service_grid">
			<a class="main_service_card card_job_guide" href="https://security.jbsilver.net/chatbot?mode=board">
				<img src="<?php echo G5_IMG_URL ?>/main_menu_figma/button_images/btn-job-guide.png" alt="구인 구직 안내">
			</a>
			<a class="main_service_card card_chatbot" href="https://security.jbsilver.net/chatbot?mode=manual">
				<img src="<?php echo G5_IMG_URL ?>/main_menu_figma/button_images/btn-chatbot.png" alt="노인일자리 운영가이드 안내">
			</a>
			<a class="main_service_card card_qna" href="<?php echo G5_BBS_URL ?>/board.php?bo_table=sub04_03">
				<img src="<?php echo G5_IMG_URL ?>/main_menu_figma/button_images/btn-qna.png" alt="Q&A 게시판">
			</a>
			<a class="main_service_card card_support" href="https://security.jbsilver.net/login">
				<img src="<?php echo G5_IMG_URL ?>/main_menu_figma/button_images/btn-support.png" alt="업무지원 서비스">
			</a>
			<a class="main_service_card card_store" href="https://security.jbsilver.net/senior-store">
				<img src="<?php echo G5_IMG_URL ?>/main_menu_figma/button_images/btn-store.png" alt="시니어 스토어">
			</a>
			<a class="main_service_card card_contact" href="tel:0632559112">
				<img src="<?php echo G5_IMG_URL ?>/main_menu_figma/button_images/btn-contact.png" alt="상담 & 문의">
			</a>
		</div>

	</div>
</div>



	<div id="main_box_line">
		<div id="main_box_area">
			<div class="main_quick_row main_quick_row_top">
				<a class="main_quick_tile quick_apply" href="<?php echo G5_URL ?>/edu/index.php">
					<span class="quick_icon"><i class="fa fa-list-alt" aria-hidden="true"></i></span>
					<strong>교육신청</strong>
					<span class="quick_arrow"><i class="fa fa-arrow-right" aria-hidden="true"></i></span>
				</a>
				<a class="main_quick_tile quick_job_board" href="<?php echo G5_URL ?>/sub/job_board_select.php">
					<span class="quick_icon"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
					<strong>구인구직게시판</strong>
					<span class="quick_arrow"><i class="fa fa-arrow-right" aria-hidden="true"></i></span>
				</a>
			</div>
			<div class="main_quick_row main_quick_row_bottom">
				<a class="main_quick_tile quick_calendar" href="<?php echo G5_BBS_URL ?>/board.php?bo_table=sub06_03">
					<span class="quick_icon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
					<strong>월별일정</strong>
					<span class="quick_arrow"><i class="fa fa-arrow-right" aria-hidden="true"></i></span>
				</a>
				<a class="main_quick_tile quick_promotion" href="<?php echo G5_URL ?>/sub/promotion.php">
					<span class="quick_icon"><i class="fa fa-bullhorn" aria-hidden="true"></i></span>
					<span class="quick_text">
						<strong>전북노인일자리센터 홍보</strong>
						<em>유튜브 · 인스타 · 언론보도</em>
					</span>
					<span class="quick_arrow"><i class="fa fa-arrow-right" aria-hidden="true"></i></span>
				</a>
				<a class="main_quick_tile quick_tv" href="<?php echo G5_URL ?>/sub/ildreamtv.php">
					<span class="quick_icon"><i class="fa fa-desktop" aria-hidden="true"></i></span>
					<strong>일드림TV</strong>
					<span class="quick_arrow"><i class="fa fa-arrow-right" aria-hidden="true"></i></span>
				</a>
			</div>
		</div>
	</div>







































<?php include_once(G5_PATH.'/tail.sub.php'); ?>
