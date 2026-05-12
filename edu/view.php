<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
include_once(G5_PATH.'/edu/education.lib.php');
if(!$s_id){
	alert("제대로 된 값이 넘어오지 않았습니다.");
}

	$ss_name = 'ss_view_education_'.$s_id;
    if (!get_session($ss_name))
    {
        set_session($ss_name, TRUE);
    }


$qstr  = "$qstr&sca=$sca&page=$page";

    $sql = " select * from wp_education where s_id = '$s_id' ";
    $row = sql_fetch($sql);


		$r = sql_fetch(" select sum(person) as tot from wp_edu where s_id = '{$row['s_id']}' and progress <> '취소'  "); 
	/*and progress <> '취소'*/
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
<style>
#bo_v_atc img { width:100%;}
</style>
<!-- 게시물 읽기 시작 { -->

<article id="bo_v" style="width:<?php echo $width; ?>">
    <header>
        <h1 id="bo_v_title" style="font-size:18px;border-bottom:1px solid #dddddd;padding-bottom:10px;">
            <?php echo $row['subject']?>
        </h1>
    </header>
    <?php
	 $cnt = 0;
	 $file = get_file("education", $row['s_id']);
		if ($file['count']) {
			$cnt = 0;
			for ($i=0; $i<count($file); $i++) {
				if (isset($file[$i]['source']) && $file[$i]['source'])
					$cnt++;
			}
		}	 	
			
      if($cnt) {        
     ?>
    <!-- 첨부파일 시작 { -->
    <section id="bo_v_file">
        <h2>첨부파일</h2>
        <ul>
        <?php
        // 가변 파일
        for ($i=0; $i<count($file); $i++) {
            if (isset($file[$i]['source']) && $file[$i]['source']) {
         ?>
            <li>
                 <a href="<?php echo $file[$i]['href'];  ?>" class="view_file_download">
                    <img src="<?php echo G5_SKIN_URL?>/board/basic/img/icon_file.gif" alt="첨부">
                    <strong><?php echo $file[$i]['source'] ?></strong> (<?php echo $file[$i]['size'] ?>)
                </a>
            </li>
        <?php
            }
        }
         ?>
        </ul>
    </section>
    <!-- } 첨부파일 끝 -->
    <?php } ?>

<p>&nbsp;</p>
<div id="sit_ov_wrap">

   <!-- 이미지 미리보기 시작 { -->
    <div id="sit_pvi">
        <div id="sit_pvi_big">
        <?php
                        $thumb = get_education_thumbnail($row['s_id'], $row['contents'], 360, 360);

                        if($thumb && $thumb['src']) {
                            $img_content = '<img src="'.$thumb['src'].'" alt="'.$thumb['alt'].'">';
                        } else {
                            $img_content = '<img src="'.G5_IMG_URL.'/noimage.jpg">';
                        }        
		
		echo $img_content;
        ?>  
        </div>
    </div>
    <!-- } 이미지 미리보기 끝 -->

    <!-- 요약정보 { -->
    <section id="sit_ov">

        <!-- 본문 내용 시작 { -->
<div class="tbl_frm01 tbl_wrap">
    <table>
    <colgroup>
        <col class="grid_4" width="30%">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">교육정원</th>
        <td><?php echo $row['person']?> 명</td> 
    </tr>      
    <tr>
        <th scope="row">교육일시</th>
        <td><?php echo $row['schedule']?> <?php echo ($row['schedule'] != $row['edate'] && $row['edate'])? " ~ ".$row['edate']:"";?></td>
    </tr>
    <tr>
        <th scope="row">교육장소</th>
        <td><?php echo $row['place']?></td>
    </tr>
    <tr>
        <th scope="row">접수기간</th>
        <td><?php echo $row['s_day']?> <?php echo $row['s_time']?> ~ <?php echo $row['f_day']?> <?php echo $row['f_time']?></td>
    </tr> 
    <?php if($row['edu_01']) {?>
    <tr>
        <th scope="row">강사명</th>
        <td><?php echo $row['edu_01']?></td>
    </tr>
    <?php }?>
    <?php if($row['edu_02']) {?>  
    <tr>
        <th scope="row">접수일시</th>
        <td><?php echo $row['edu_02']?></td>
    </tr>
    <?php }?>
    <?php if($row['edu_03']) {?>    
    <tr>
        <th scope="row">교육비</th>
        <td><?php echo $row['edu_03']?></td>
    </tr>
    <?php }?>
    <?php if($row['edu_04']) {?>    
    <tr>
        <th scope="row">입금계좌번호</th>
        <td><?php echo $row['edu_04']?></td>
    </tr>
    <?php }?>
    <?php if($row['edu_05']) {?>            
    <tr>
        <th scope="row">입금기한</th>
        <td><?php echo $row['edu_05']?></td>
    </tr> 
    <?php }?>                       
    <tr>
        <th scope="row">현황</th>
        <td><?php echo $progress?></td>
    </tr>                        
    </tbody>
    </table>
</div>  
        <!-- } 본문 내용 끝 -->


    </section>
</div>
    <!-- 게시물 상단 버튼 시작 { -->
    <div id="bo_v_top">
        <?php
        ob_start();
         ?>
        <ul class="bo_v_com">
            <li><a href="./index.php" class="btn_b01 btn">목록</a></li>
            <?php if($progress == "신청"){ ?>
            <li><a href="./index.php?case=write&amp;s_id=<?php echo $row['s_id']?>" class="btn_b02 btn">교육신청</a></li>
            <?php }?>
            <?php /*if($progress == "대기"){ ?>
            <li><a href="./index.php?case=write&amp;s_id=<?php echo $row['s_id']?>&pro=대기" onClick="if(confirm('<?php echo $row['subject']?>- 교육을 대기상태로 신청하시겠습니까?')){return true;}else{return false;}" class="btn_b02">교육신청</a></li>
            <?php }*/?>            
        </ul>
        <?php
        $link_buttons = ob_get_contents();
        ob_end_flush();
         ?>
    </div>
    <!-- } 게시물 상단 버튼 끝 -->
    
    
        <div id="bo_v_con"><?php echo conv_content($row['contents'], 1); ?></div>



</article>
<!-- } 게시판 읽기 끝 -->
<script>
$(function(){
    // 이미지 첫번째 링크
    $("#sit_pvi_big a:first").addClass("visible");

    // 이미지 미리보기 (썸네일에 마우스 오버시)
    $("#sit_pvi .img_thumb").bind("mouseover focus", function(){
        var idx = $("#sit_pvi .img_thumb").index($(this));
        $("#sit_pvi_big a.visible").removeClass("visible");
        $("#sit_pvi_big a:eq("+idx+")").addClass("visible");
    });
});

$(function() {
    $("a.view_image").click(function() {
        window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
        return false;
    });

    // 추천, 비추천
    $("#good_button, #nogood_button").click(function() {
        var $tx;
        if(this.id == "good_button")
            $tx = $("#bo_v_act_good");
        else
            $tx = $("#bo_v_act_nogood");

        excute_good(this.href, $(this), $tx);
        return false;
    });

    // 이미지 리사이즈
    $("#bo_v_atc").viewimageresize();
});
</script>
