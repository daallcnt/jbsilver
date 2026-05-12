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
						<li><a href="#stab1">60+ 구인정보</a></li>
						<li><a href="#stab2">교육리스트</a></li>
						<li><a href="#stab3">공지사항</a></li>
					</ul>

					<div class="stab_container">
						<div id="stab1" class="stab_content"><?php echo latest("basic3", "sub04_01", 5, 26);?></div>
						<div id="stab2" class="stab_content">
                        <?php
						$r_sql = " select * from wp_education order by schedule desc limit 0, 5 ";
						$r_result = sql_query($r_sql);	
						?>
<div class="lt">
    <ul>
    <?php 
	for ($i=0; $row=sql_fetch_array($r_result); $i++) { 
		$r = sql_fetch(" select sum(person) as tot from wp_edu where s_id = '$row[s_id]' and progress <> '취소' "); 

			$stime = $row['s_day']." ".$row['s_time'];
			$ftime = $row['f_day']." ".$row['f_time'];
			$SumPerson = ($r['tot'])? $r['tot'] : "0" ;
		 	$PersonWating =  $row['person'] + $row['wating'];
			if($row['s_chk'] != 1) {
				if($SumPerson < $row['person']){
					if(G5_TIME_YMDHIS < $stime){
						$progress = "준비";
					}elseif(G5_TIME_YMDHIS > $ftime){
						$progress = "마감";
					}else{
						$progress = "신청";
					}
				}elseif($SumPerson < $PersonWating){
					if(G5_TIME_YMDHIS < $stime){
						$progress = "준비";
					}elseif(G5_TIME_YMDHIS > $ftime){
						$progress = "마감";
					}else{
						//$progress = "대기";
						$progress = "신청";
					}
				}else{
					$progress = "마감";
				}
			}else{
			$progress = "마감";
			}	
	?>
        <li>
				<?php if($progress == "준비"){?>
                <a href="javascript://" onclick="javascript:a_href('접수기간이 아닙니다.\n\n접수기간 : <?php echo $stime?>');">	
                <?php }else{?>
                <a href="<?php G5_URL?>/edu/index.php?case=view&amp;s_id=<?php echo $row['s_id']?>">
				<?php }?>        
                [<?php echo $progress?>] <?php echo $row['subject'] ?>
                </a>   
        </li>
    <?php }  ?>
    <?php if ($i == 0) { //게시물이 없을 때  ?>
    <li>게시물이 없습니다.</li>
    <?php }  ?>
    </ul>
	<div class="lt_more"><a href="javascript:menu10sub1();"><img src="<?php echo G5_IMG_URL ?>/board_more.gif"></a></div>
</div>

                        </div>	
						<div id="stab3" class="stab_content"><?php echo latest("basic2", "sub06_01", 5, 36);?></div>
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
