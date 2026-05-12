<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
$thumb_width=50;
$thumb_height=50;

add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
?>

<!-- <?php echo $bo_subject; ?> 최신글 시작 { -->
<div class="ltx">
    <ul>
    
    <?php
        for ($i=0; $i<count($list); $i++) {
           $thumb = get_list_thumbnail($bo_table, $list[$i]['wr_id'], $thumb_width, $thumb_height);
            if($thumb['src']) {
                $img = '<img src="'.$thumb['src'].'" alt="'.$list[$i]['subject'].'" width="'.$thumb_width.'" height="'.$thumb_height.'">';
            } else {
                $img = G5_IMG_URL.'/no_img.png';
            }
	?>

        <li>
            <div class="img"><a href="<?php echo $list[$i]['href'];?>"><span class="roll sports"></span><?php echo $img;?></a></div>
            <div class="inner"><p class="tit"><?php
            //echo $list[$i]['icon_reply']." ";
            echo "<a href=\"".$list[$i]['href']."\">";
                echo $list[$i]['subject'];
				
            if (isset($list[$i]['icon_new'])) echo " " . $list[$i]['icon_new'];
             ?></a><br /><?php echo cut_str(strip_tags($list[$i]['wr_content']),38);?></p>
            </div>
        </li>
    <?php }  ?>
    <?php if (count($list) == 0) { //게시물이 없을 때  ?>
    <li>게시물이 없습니다.</li>
    <?php }  ?>
    </ul>
</div>