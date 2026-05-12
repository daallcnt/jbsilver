<?php
include_once("../common.php");
$g5['title'] = "일드림TV";
$gr_id = "sub05";
$bo_table = "ildreamtv_hub";
include_once(G5_PATH.'/head.php');
?>

<div id="cct_con">
	<div id="sub_common">
		<div class="quick_hub">
			<div class="quick_hub_head quick_hub_tv">
				<p class="quick_hub_eyebrow">일드림TV</p>
				<h2>보고 싶은 콘텐츠를 선택하세요</h2>
				<p>일자리 현장, 교육 콘텐츠, 홍보 영상을 한 곳에서 빠르게 찾아볼 수 있습니다.</p>
			</div>
			<div class="quick_hub_grid three_cols">
				<a class="quick_hub_card joblog" href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=sub05_01">
					<span class="quick_hub_icon"><i class="fa fa-video-camera" aria-hidden="true"></i></span>
					<strong>제이로그(Job-Log)</strong>
					<em>일자리 현장과 활동 이야기를 영상으로 확인</em>
				</a>
				<a class="quick_hub_card edulog" href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=sub05_03">
					<span class="quick_hub_icon"><i class="fa fa-graduation-cap" aria-hidden="true"></i></span>
					<strong>에듀로그(Edu-Log)</strong>
					<em>교육 관련 영상과 학습 콘텐츠 보기</em>
				</a>
				<a class="quick_hub_card prhall" href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=sub05_04">
					<span class="quick_hub_icon"><i class="fa fa-bullhorn" aria-hidden="true"></i></span>
					<strong>홍보관</strong>
					<em>센터 홍보 영상과 주요 활동 콘텐츠 보기</em>
				</a>
			</div>
		</div>
	</div>
</div>

<?php include_once(G5_PATH.'/tail.php'); ?>
