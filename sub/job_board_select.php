<?php
include_once("../common.php");
$g5['title'] = "구인구직게시판";
$gr_id = "sub04";
$bo_table = "job_board_select";
include_once(G5_PATH.'/head.php');
?>

<div id="cct_con">
	<div id="sub_common">
		<div class="quick_hub">
			<div class="quick_hub_head quick_hub_job">
				<p class="quick_hub_eyebrow">구인구직게시판</p>
				<h2>필요한 서비스를 선택하세요</h2>
				<p>구직을 희망하는 시니어와 인재를 찾는 기관·기업을 위한 게시판으로 바로 이동합니다.</p>
			</div>
			<div class="quick_hub_grid two_cols">
				<a class="quick_hub_card job_seeker" href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=sub04_02">
					<span class="quick_hub_icon"><i class="fa fa-user" aria-hidden="true"></i></span>
					<strong>구직서비스</strong>
					<em>일자리를 찾고 있는 분들을 위한 구직 게시판</em>
				</a>
				<a class="quick_hub_card employer" href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=sub04_01">
					<span class="quick_hub_icon"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
					<strong>구인서비스</strong>
					<em>채용 정보와 모집 공고를 확인하는 구인 게시판</em>
				</a>
			</div>
		</div>
	</div>
</div>

<?php include_once(G5_PATH.'/tail.php'); ?>
