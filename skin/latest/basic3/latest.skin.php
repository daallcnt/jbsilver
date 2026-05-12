<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
if($bo_table == "sub04_01") {
$today = date('Ymd');
}
?>

<!-- <?php echo $bo_subject; ?> 최신글 시작 { -->
<div class="lt">
    <!--<strong class="lt_title"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $bo_table ?>"><?php echo $bo_subject; ?></a></strong>-->
    <ul>
    <?php for ($i=0; $i<count($list); $i++) {  ?>
        <li>

            <?php
            //echo $list[$i]['icon_reply']." ";
            echo "- <a href=\"".$list[$i]['href']."\">";
			if($bo_table == "sub04_01") {
		if($list[$i]['wr_10'] == "채용완료"){ 
		echo "[<span style='color:red;'>{$list[$i]['wr_10']}</span>]";
		}else if($list[$i]['wr_10'] == "알선"){
		echo "[<span>{$list[$i]['wr_10']}</span>]";	
		}else{
		echo "[<span style='color:blue;'>채용중</span>]";
		}
			}
            if ($list[$i]['is_notice'])
                echo "<strong>".$list[$i]['subject']."</strong>";
            else
                echo $list[$i]['subject'];

            //if ($list[$i]['comment_cnt'])
              //  echo $list[$i]['comment_cnt'];

            echo "</a>";

            // if ($list[$i]['link']['count']) { echo "[{$list[$i]['link']['count']}]"; }
            // if ($list[$i]['file']['count']) { echo "<{$list[$i]['file']['count']}>"; }

            if (isset($list[$i]['icon_new'])) echo " " . $list[$i]['icon_new'];
            //if (isset($list[$i]['icon_hot'])) echo " " . $list[$i]['icon_hot'];
            //if (isset($list[$i]['icon_file'])) echo " " . $list[$i]['icon_file'];
            //if (isset($list[$i]['icon_link'])) echo " " . $list[$i]['icon_link'];
            //if (isset($list[$i]['icon_secret'])) echo " " . $list[$i]['icon_secret'];
             ?>
			<span class="datatime">
			<?php
			if($bo_table == "sub04_01") {
				$wr7[$i] = explode("|",$list[$i]['wr_7']);
							
				if($wr7[$i][1] == '날짜입력'){
					if($today >= $wr7[$i][0]){
				 echo " 기간만료";
					}else{
				 echo date("y-m-d", strtotime($wr7[$i][0]));	
					}
				}else{
					echo $wr7[$i][1];
				}
			}else{
			 echo $list[$i]['datetime'] ;
			}
			 ?>            
            </span>
        </li>
    <?php }  ?>
    <?php if (count($list) == 0) { //게시물이 없을 때  ?>
    <li>게시물이 없습니다.</li>
    <?php }  ?>
    </ul>
   <div class="lt_more"><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $bo_table ?>"><img src="<?php echo G5_IMG_URL ?>/board_more.gif"></a></div>
</div>
<!-- } <?php echo $bo_subject; ?> 최신글 끝 -->