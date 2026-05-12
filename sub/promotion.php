<?php
include_once("../common.php");
$g5['title'] = "전북노인일자리센터 홍보";
$gr_id = "sub06";
$bo_table = "promotion_hub";
include_once(G5_PATH.'/head.php');
?>

<div id="cct_con">
	<div id="sub_common">
		<div class="quick_hub">
			<div class="quick_hub_head quick_hub_promotion">
				<p class="quick_hub_eyebrow">전북노인일자리센터 홍보</p>
				<h2>센터 소식을 다양한 채널에서 만나보세요</h2>
				<p>영상, SNS, 언론보도를 통해 전북노인일자리센터의 활동과 현장 소식을 확인할 수 있습니다.</p>
			</div>
			<div class="quick_hub_grid three_cols">
				<a class="quick_hub_card youtube" href="https://youtube.com/@tv-tu7dx?si=oJ6DYK2MzIGHJHFC" target="_blank">
					<span class="quick_hub_icon"><i class="fa fa-youtube-play" aria-hidden="true"></i></span>
					<strong>유튜브</strong>
					<em>일드림TV 영상과 센터 홍보 콘텐츠 보기</em>
				</a>
				<a class="quick_hub_card instagram" href="https://www.instagram.com/jb_silver__?igsh=Nzlic2xzajZ2emtw" target="_blank">
					<span class="quick_hub_icon"><i class="fa fa-instagram" aria-hidden="true"></i></span>
					<strong>인스타그램</strong>
					<em>전북노인일자리센터 공식 인스타그램 보기</em>
				</a>
				<a class="quick_hub_card press" href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=sub06_04">
					<span class="quick_hub_icon"><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>
					<strong>보도자료</strong>
					<em>언론에 소개된 센터 소식과 보도자료 보기</em>
				</a>
			</div>
		</div>
	</div>
</div>

<?php include_once(G5_PATH.'/tail.php'); ?>
