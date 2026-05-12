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
		<div class="main_visual_heading">
			<p class="visual_left_txt01">일하는 노인! 새로운 청춘!</p>
			<p class="visual_left_txt02">새로운 일자리! 새롭게 시작하는 실버라이프</p>
		</div>

		<a class="main_visual_robot" href="https://security.jbsilver.net/chatbot?mode=manual">
			<img src="<?php echo G5_IMG_URL ?>/main_visual_figma/robot.png" alt="">
		</a>
		<div class="main_visual_card_area">
			<div class="main_visual_helper" onclick="if (!event.target.closest('a')) location.href='https://security.jbsilver.net/chatbot?mode=manual';">
				<p class="main_visual_helper_title">노인 일자리에 대해<br>물어보세요!</p>
				<a class="main_visual_helper_btn" href="https://security.jbsilver.net/chatbot?mode=manual">
					<span>매뉴얼 챗봇</span>
				</a>
				<a class="main_visual_helper_btn" href="https://security.jbsilver.net/chatbot?mode=board">
					<span>구인구직 챗봇</span>
				</a>
			</div>

			<div class="main_visual_latest_card">
				<?php
				$main_job_sql = " select wr_id, wr_subject, ca_name, wr_6, wr_9
									from {$g5['write_prefix']}sub04_01
									where wr_is_comment = 0
									  and wr_10 = '채용중'
									order by wr_datetime desc, wr_id desc
									limit 0, 8 ";
				$main_job_result = sql_query($main_job_sql);
				$main_job_count = 0;
				?>
				<div id="mainJobSlider" class="main_job_slider">
					<?php for ($i=0; $row=sql_fetch_array($main_job_result); $i++) {
						$main_wr6 = explode("|", $row['wr_6']);
						$main_wr9 = explode("|", $row['wr_9']);
						$job_area_detail = isset($main_wr9[2]) ? $main_wr9[2] : '';
						$job_salary_value = isset($main_wr6[2]) ? $main_wr6[2] : '';
						$job_area = trim($row['ca_name'].' '.$job_area_detail);
						$job_salary = trim($job_salary_value);
						$job_area = $job_area ? $job_area : '근무지역 미정';
						$job_salary = $job_salary ? $job_salary : '급여 협의';
						$main_job_count++;
					?>
					<a class="main_job_slide <?php echo $i == 0 ? 'is-active' : ''; ?>" href="<?php echo G5_BBS_URL ?>/board.php?bo_table=sub04_01&amp;wr_id=<?php echo $row['wr_id']; ?>">
						<span class="main_job_status">채용중</span>
						<strong><?php echo get_text($row['wr_subject']); ?></strong>
						<span class="main_job_location"><?php echo get_text($job_area); ?></span>
						<span class="main_job_meta">
							<em>급여</em>
							<?php echo get_text($job_salary); ?>
						</span>
					</a>
					<?php } ?>
					<?php if ($main_job_count == 0) { ?>
					<div class="main_job_empty">
						<strong>현재 채용중인 공고가 없습니다.</strong>
						<span>새로운 구인정보가 등록되면 이곳에 표시됩니다.</span>
					</div>
					<?php } ?>
				</div>
				<?php if ($main_job_count > 1) { ?>
				<button type="button" class="main_job_arrow main_job_arrow_prev" aria-label="이전 채용정보"></button>
				<button type="button" class="main_job_arrow main_job_arrow_next" aria-label="다음 채용정보"></button>
				<script type="text/javascript">
				$(function() {
					var $slides = $('#mainJobSlider .main_job_slide');
					var current = 0;
					var timer;

					function showSlide(next, direction) {
						if (next === current) return;

						$slides.removeClass('is-prev is-next is-active');
						$slides.eq(current).addClass(direction === 'prev' ? 'is-next' : 'is-prev');
						$slides.eq(next).addClass(direction === 'prev' ? 'is-prev' : 'is-next');

						setTimeout(function() {
							$slides.eq(next).removeClass('is-prev is-next').addClass('is-active');
						}, 20);

						current = next;
					}

					function nextSlide() {
						showSlide((current + 1) % $slides.length, 'next');
					}

					function prevSlide() {
						showSlide((current - 1 + $slides.length) % $slides.length, 'prev');
					}

					function resetTimer() {
						clearInterval(timer);
						timer = setInterval(nextSlide, 8000);
					}

					$('.main_job_arrow_next').on('click', function() {
						nextSlide();
						resetTimer();
					});

					$('.main_job_arrow_prev').on('click', function() {
						prevSlide();
						resetTimer();
					});

					resetTimer();
				});
				</script>
				<?php } ?>
			</div>

			<div id="main_visual_right">
				<div class="visual_tv">
					<iframe width="282" height="159" src="https://www.youtube.com/embed/<?php echo $config['cf_1']?>?autoplay=1" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
		</div>

	</div>
</div>



<div id="main_box_line" style="padding:70px 0;background:#fff;">
	<div id="main_box_area">
		<li style="margin-right:30px"><a href="javascript:menu1sub6();"><img src="<?php echo G5_IMG_URL ?>/main_box_img01.jpg" border="0"></a></li>
		<li style="margin-right:30px"><a href="../bbs/board.php?bo_table=sub06_09"><img src="<?php echo G5_IMG_URL ?>/main_box_img02.jpg" border="0"></a></li>
		<li><img src="<?php echo G5_IMG_URL ?>/main_box_img03.jpg"></li>
		

	</div>
</div>



<div style="width:100%;background:#ebf5fa">
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
</div>




<div id="banner_line">
	<div id="banner_line_area">
		<li><a href="http://www.jeonbuk.go.kr/" target="_blank"><img src="<?php echo G5_IMG_URL ?>/banner01_2025.gif"></a></li>
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
