<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

$imgwidth = 150; //표시할 이미지의 가로사이즈
$imgheight = 80; //표시할 이미지의 세로사이즈
?>

<style>
#oneshot { position:relative;margin:0 0 0 -5px;}
#oneshot .img_set{width:<?php echo $imgwidth ?>px; height:<?php echo $imgheight ?>px; background:#fafafa;padding:0;}
#oneshot .subject_set{width:<?php echo $imgwidth - 13 ?>px; height:58px; padding:5px 10px 10px 3px; z-index:1; bottom:0; left:0;}
#oneshot .subject_set .sub_title{color:#333;height:17px;overflow:hidden;padding:3px 0 0 0;font-size:1.2em;}

#oneshot ul {list-style:none;clear:both;margin:0;padding:0;}
#oneshot li{float:left;list-style:none;text-decoration:none;padding:0 0 0 5px}
.subject_set  a:link, a:visited {color:#333;text-decoration:none}
.subject_set  a:hover, a:focus, a:active {color:#e60012;text-decoration:none}
</style>
<div id="oneshot">
	<ul>
	<?php for ($i=0; $i<count($list); $i++) { ?>	
		<li>
			<div class="img_set">
				<a href="<?php echo $list[$i]['href'] ?>">
					<?php                
					$thumb = get_list_thumbnail($bo_table, $list[$i]['wr_id'], $imgwidth, $imgheight);    					            
					if($thumb['src']) {
					$img_content = '<img class="img_left" src="'.$thumb['src'].'" alt="'.$list[$i]['subject'].'" width="'.$imgwidth.'" height="'.$imgheight.'">';
					} else {
					$img_content = G5_IMG_URL.'/no_img.png';
					}                
					echo $img_content;												               
					?>
				</a>
			</div>
			<div class="s_set">
				<div class="sub_title"><a href="<?php echo $list[$i]['href'] ?>"><?php echo $list[$i]['subject']?></a></div>
           </div>     
		</li>
	<?php } ?>
	</ul>
</div>
<div style="clear:both;"></div>
