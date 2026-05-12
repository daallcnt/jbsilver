<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
$wr1 = explode("|",$view['wr_1']);
$wr2 = explode("|",$view['wr_2']);
$wr3 = explode("|",$view['wr_3']);
$wr5 = explode("|",$view['wr_5']);
$wr6 = explode("|",$view['wr_6']);
$wr7 = explode("|",$view['wr_7']);
$wr8 = explode("|",$view['wr_8']);
$wr9 = explode("|",$view['wr_9']);
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<!-- <div id="bo_v_table"><?php echo ($board['bo_mobile_subject'] ? $board['bo_mobile_subject'] : $board['bo_subject']); ?></div> -->
    <div class="btn_top top"> 
        <?php if ($reply_href) { ?><a href="<?php echo $reply_href ?>" class="btn_b01"><i class="fa fa-reply" aria-hidden="true"></i> 답변</a><?php } ?>
        <?php if ($write_href) { ?><a href="<?php echo $write_href ?>" class="btn_b02 btn"><i class="fa fa-pencil" aria-hidden="true"></i> 글쓰기</a><?php } ?>

    </div>
<article id="bo_v" style="width:<?php echo $width; ?>">
    <header>
        <h2 id="bo_v_title">
            <span class="bo_v_tit">
            <?php
            echo get_text($view['wr_subject']); // 글제목 출력
            ?></span>
        </h2>
        <!--<p><span class="sound_only">작성일</span><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo date("y-m-d H:i", strtotime($view['wr_datetime'])) ?></p>-->
    </header>

    <section id="bo_v_info">
        <h2>페이지 정보</h2>
		<span class="sound_only">작성일</span><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo date("y-m-d H:i", strtotime($view['wr_datetime'])) ?>
        <span class="sound_only">작성자</span><?php echo $view['name'] ?><span class="ip"><?php if ($is_ip_view) { echo "&nbsp;($ip)"; } ?></span>
        <span class="sound_only">조회</span><strong><i class="fa fa-eye" aria-hidden="true"></i> <?php echo number_format($view['wr_hit']) ?>회</strong>
        <span class="sound_only">댓글</span><strong><i class="fa fa-commenting-o" aria-hidden="true"></i> <?php echo number_format($view['wr_comment']) ?>건</strong>
    </section>

    <div id="bo_v_top">
        <?php
        ob_start();
         ?>
        <ul class="bo_v_left">
            <?php if ($update_href) { ?><li><a href="<?php echo $update_href ?>" class="btn_b01 btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 수정</a></li><?php } ?>
            <?php if ($delete_href) { ?><li><a href="<?php echo $delete_href ?>" class="btn_b01 btn" onclick="del(this.href); return false;"><i class="fa fa-trash-o" aria-hidden="true"></i> 삭제</a></li><?php } ?>
            <?php if ($copy_href) { ?><li><a href="<?php echo $copy_href ?>" class="btn_admin btn" onclick="board_move(this.href); return false;"><i class="fa fa-files-o" aria-hidden="true"></i> 복사</a></li><?php } ?>
            <?php if ($move_href) { ?><li><a href="<?php echo $move_href ?>" class="btn_admin btn" onclick="board_move(this.href); return false;"><i class="fa fa-arrows" aria-hidden="true"></i> 이동</a></li><?php } ?>
            <?php if ($search_href) { ?><li><a href="<?php echo $search_href ?>" class="btn_b01 btn">검색</a></li><?php } ?>

        </ul>

        <?php
        $link_buttons = ob_get_contents();
        ob_end_flush();
         ?>
    </div>

    <section id="bo_v_atc">
        <h2 id="bo_v_atc_title">본문</h2>

        <?php
        // 파일 출력
/*
        $v_img_count = count($view['file']);
        if($v_img_count) {
            echo "<div id=\"bo_v_img\">\n";

            for ($i=0; $i<=count($view['file']); $i++) {
                if ($view['file'][$i]['view']) {
                    //echo $view['file'][$i]['view'];
                    echo get_view_thumbnail($view['file'][$i]['view']);
                }
            }

            echo "</div>\n";
        }*/
         ?>
<div class="tbl_frs01 tbl_wrap">
    <table>
                    <colgroup>
                        <col width="25%">
                        <col width="75%" class="tbl_frs02">                        
                    </colgroup>
    <tbody>
    <tr>
        <th scope="row">회사명</th>
        <td><?php echo $wr1[0]?></td>
    </tr>
    <tr>
        <th scope="row">대표자</th>
        <td><?php echo $wr9[0]?></td>
    </tr>
    <tr>
        <th scope="row">마감일자</th>
        <td>
<?php $today = date('Ymd');
if($wr7[1] == '날짜입력'){
	if($today >= $wr7[0]){
 echo " <img src='{$board_skin_url}/img/icon_off.gif' align='absmiddle' />	";
	}else{
 echo date("Y-m-d", strtotime($wr7[0]));	
	}
}else{
 echo $wr7[1];	
}
?>        
        </td>        
    </tr>
    <tr>
        <th scope="row">사업내용</th>
        <td><?php echo $wr3[0]?></td>
    </tr>
    <tr>
        <th scope="row">회사주소</th>
        <td><?php echo sprintf("(%s)", $wr2[0]).' '.print_address($wr2[1], $wr2[2], $wr2[3], ""); ?></td>
    </tr>

    <?php if($is_admin && $wr1[2]){?>         
    <tr>
        <th scope="row">회사전화</th>
        <td><a href="tel:<?php echo $wr1[2]?>"><?php echo $wr1[2]?></a></td>
    </tr>
    <?php }?>
    <tr>
        <th scope="row">업무내용</th>
        <td><?php echo $wr3[2]?></td>
    </tr>
    <tr>        
        <th scope="row">모집업종</th>
        <td><?php echo ($wr5[0] == "기타")?$wr5[1]:$wr5[0];?></td>        
    </tr>
    <tr>
        <th scope="row">근무지역</th>
        <td>
        <?php if ($category_name) { ?>
           <!--<span class="bo_v_cate">-->[<?php echo $view['ca_name']; // 분류 출력 끝 ?>] <!--</span> -->
        <?php } ?>		
		<?php echo $wr9[2]?>
        </td>
    </tr>    
    <tr>
        <th scope="row">근무형태</th>
        <td><?php echo $wr3[1]?></td>
    </tr>
    <tr>        
        <th scope="row">급여</th>
        <td><?php echo $wr6[2]?></td>        
    </tr>
    <tr>
        <th scope="row">모집인원</th>
        <td><?php echo number_format($wr8[0])?>명</td>
    </tr>
    <tr>        
        <th scope="row">연령</th>
        <td><?php echo (!$wr8[4]) ? $wr8[2].'세	~	'.$wr8[3].'세'	:	$wr8[4];?></td>        
    </tr>    
    <tr>
        <th scope="row">근무시간</th>
        <td>
<?php if($wr9[4]=='on') { ?>주간<?php } ?><?php if($wr9[5]=='on') { ?>2교대<?php } ?><?php if($wr9[6]=='on') { ?>3교대<?php } ?>&nbsp;시간(&nbsp;<?php echo $wr9[7]?>&nbsp;~&nbsp;<?php echo $wr9[8]?>&nbsp;)		
        </td>
    </tr>
    <tr>
        <th scope="row">접수방식</th>
        <td>구직등록자 : 전화문의, 미등록자 : 직접방문후 상담</td>
    </tr>
    <tr>
        <th scope="row">준비서류</th>
        <td>
<?php if($wr7[2]=='on') { ?>이력서<?php } ?>&nbsp;&nbsp;<?php if($wr7[3]=='on') { ?>자기소개서<?php } ?>&nbsp;&nbsp;<?php if($wr7[4]=='on') { ?>주민등록등본<?php } ?>&nbsp;&nbsp;<?php if($wr7[5]=='on') { ?>사진<?php } ?>&nbsp;&nbsp;<?php if($wr7[6]=='on') { ?>기타(&nbsp;<?php echo $wr7[7]?>&nbsp;)<?php } ?>        
        </td>
    </tr>                                                                       
    <tr>
        <th scope="row">가입보험</th>
        <td>
<?php if($wr2[4]=='on') { ?>고용보험<?php } ?>&nbsp;&nbsp;<?php if($wr2[5]=='on') { ?>산재보험<?php } ?>&nbsp;&nbsp;<?php if($wr2[6]=='on') { ?>건강보험<?php } ?>&nbsp;&nbsp;<?php if($wr2[7]=='on') { ?>연금보험<?php } ?>        
        </td>
    </tr>
    <tr>
        <th scope="row">요구사항</th>
        <td><?php echo $wr3[3]?></td>
    </tr> 
    <tr>
        <th scope="row">상세조건</th>
        <td><!--<div id="bo_v_con">--><?php echo get_view_thumbnail($view['content']); ?><!--</div>--></td>
    </tr>     
    <tr>
        <th scope="row">전형방법</th>
        <td>구직등록자 : 전화문의, 미등록자 : 직접방문후 상담</td>
    </tr>
    <tr>
        <th scope="row">담당자</th>
        <td><?php if($wr1[1]){?><?=$wr1[1]?><?php }else{?>전북노인일자리센터<?php }?></td>
    </tr>
    <tr>
        <th scope="row">문의처</th>
        <td><?php if($view['wr_10'] != "채용완료") {?><a href="tel:<?php echo $wr5[2]?>"><?php }?><?php echo $wr5[2]?><?php if($view['wr_10'] != "채용완료") {?></a><?php }?> &nbsp; <?php echo ($wr1[4])?"	FAX : $wr1[4]":""; ?>&nbsp;&nbsp;&nbsp;이메일 : <?php echo $wr1[3];?></td>
    </tr>    
    <!--tr>
        <th scope="row">연락처</th>
        <td><?php if($is_admin){?><a href="tel:<?php echo $wr1[2]?>"><?php echo $wr1[2]?></a> <?php echo ($wr1[4])?"	FAX : $wr1[4]":""; ?>&nbsp;&nbsp;&nbsp;&nbsp;이메일 : <?php echo $wr1[3];?><br /><?php }?>
        전북노인일자리센터 <br /> <a href="tel:063-255-9112">063-255-9112</a>, <a href="tel:063-273-2086">063-273-2086</a></td>
    </tr-->                                              
    </tbody>
    </table>
<?php if($view['wr_10'] == "채용완료") {?>
	<div style="text-align:center;margin-top:20px;"><span style="background:#ff0000;border-radius:5px;color:#ffffff;font-weight:bold;padding:10px 20px;font-size:13px;">채용이 완료되었습니다</span></div>
</div>
<?php }else{ ?>
	<div style="text-align:center;margin-top:20px;"><a href="tel:<?php echo $wr5[2]?>" style="background:#ff0000;border-radius:5px;color:#ffffff;font-weight:bold;padding:10px 20px;font-size:13px;">전화연결 : <i class="fa fa-phone-square" aria-hidden="true"></i> <?php echo $wr5[2]?></a></div>
</div>
<?php }?>
        <?php //echo $view['rich_content']; // {이미지:0} 과 같은 코드를 사용할 경우 ?>

        <?php if ($is_signature) { ?><p><?php echo $signature ?></p><?php } ?>

        <?php if ( $good_href || $nogood_href) { ?>
        <div id="bo_v_act">
            <?php if ($good_href) { ?>
            <span class="bo_v_act_gng">
                <a href="<?php echo $good_href.'&amp;'.$qstr ?>" id="good_button"  class="bo_v_good"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i><br><span class="sound_only">추천</span><strong><?php echo number_format($view['wr_good']) ?></strong></a>
                <b id="bo_v_act_good">이 글을 추천하셨습니다</b>
            </span>
            <?php } ?>
            <?php if ($nogood_href) { ?>
            <span class="bo_v_act_gng">
                <a href="<?php echo $nogood_href.'&amp;'.$qstr ?>" id="nogood_button" class="bo_v_nogood"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i><br><span class="sound_only">비추천</span><strong><?php echo number_format($view['wr_nogood']) ?></strong></a>
                <b id="bo_v_act_nogood"></b>
            </span>
            <?php } ?>
        </div>
        <?php } else {
            if($board['bo_use_good'] || $board['bo_use_nogood']) {
        ?>
        <div id="bo_v_act">
            <?php if($board['bo_use_good']) { ?><span class="bo_v_good"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i><br><span class="sound_only">추천</span><strong><?php echo number_format($view['wr_good']) ?></strong></span><?php } ?>
            <?php if($board['bo_use_nogood']) { ?><span class="bo_v_nogood"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i><br><span class="sound_only">비추천</span> <strong><?php echo number_format($view['wr_nogood']) ?></strong></span><?php } ?>
        </div>
        <?php
            }
        }
        ?>

        <div id="bo_v_share">
            <!--<?php if ($scrap_href) { ?><a href="<?php echo $scrap_href;  ?>" target="_blank" class=" btn_scrap" onclick="win_scrap(this.href); return false;"><i class="fa fa-thumb-tack" aria-hidden="true"></i> 스크랩</a><?php } ?>-->

            <?php
            include_once(G5_SNS_PATH."/view.sns.skin.php");
            ?>
        </div>
    </section>


    
    <?php
    $cnt = 0;
    if ($view['file']['count']) {
        for ($i=0; $i<count($view['file']); $i++) {
            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view'])
                $cnt++;
        }
    }
     ?>

    <?php if($cnt) { ?>
    <section id="bo_v_file">
        <h2>첨부파일</h2>
        <ul>
        <?php
        // 가변 파일
        for ($i=0; $i<count($view['file']); $i++) {
            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
         ?>
            <li>
                <a href="<?php echo $view['file'][$i]['href'];  ?>" class="view_file_download">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    <strong><?php echo $view['file'][$i]['source'] ?></strong>
                    <?php echo $view['file'][$i]['content'] ?> (<?php echo $view['file'][$i]['size'] ?>)
                </a>
                <span class="bo_v_file_cnt"><?php echo $view['file'][$i]['download'] ?>회 다운로드</span> |
                <span>DATE : <?php echo $view['file'][$i]['datetime'] ?></span>
            </li>
        <?php
            }
        }
         ?>
        </ul>
    </section>
    <?php } ?>

    <?php if(array_filter($view['link'])) { ?>
    <!-- 관련링크 시작 { -->

    <section id="bo_v_link">
        <h2>관련링크</h2>
        <ul>
        <?php
        // 링크
        $cnt = 0;
        for ($i=1; $i<=count($view['link']); $i++) {
            if ($view['link'][$i]) {
                $cnt++;
                $link = cut_str($view['link'][$i], 70);
         ?>
            <li>
                <a href="<?php echo $view['link_href'][$i] ?>" target="_blank">
                    <i class="fa fa-link" aria-hidden="true"></i>
                    <strong><?php echo $link ?></strong>
                </a>
                <span class="bo_v_link_cnt"><?php echo $view['link_hit'][$i] ?>회 연결</span>
            </li>
        <?php
            }
        }
         ?>
        </ul>
    </section>
    <!-- } 관련링크 끝 -->
    <?php } ?>

    <?php if ($prev_href || $next_href) { ?>
    <ul class="bo_v_nb">
        <?php if ($prev_href) { ?><li class="bo_v_prev"><a href="<?php echo $prev_href ?>"><i class="fa fa-caret-left" aria-hidden="true"></i> 이전글</a></li><?php } ?>
        <?php if ($next_href) { ?><li class="bo_v_next"><a href="<?php echo $next_href ?>">다음글 <i class="fa fa-caret-right" aria-hidden="true"></i></a></li><?php } ?>
        <li style="padding:5px 0;"><a href="<?php echo $list_href ?>" class="btn_b02" style="border-radius:5px;color:#ffffff;font-weight:bold;"><i class="fa fa-list" aria-hidden="true"></i> 목록</a></li>

    </ul>
    <?php } ?>
<?php if($is_admin) {?>
    <?php
    // 코멘트 입출력
    include_once(G5_BBS_PATH.'/view_comment.php');
     ?>
<?php }?>
</article>

<script>
<?php if ($board['bo_download_point'] < 0) { ?>
$(function() {
    $("a.view_file_download").click(function() {
        if(!g5_is_member) {
            alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
            return false;
        }

        var msg = "파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

        if(confirm(msg)) {
            var href = $(this).attr("href")+"&js=on";
            $(this).attr("href", href);

            return true;
        } else {
            return false;
        }
    });
});
<?php } ?>

function board_move(href)
{
    window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
}
</script>

<!-- 게시글 보기 끝 -->

<script>
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

function excute_good(href, $el, $tx)
{
    $.post(
        href,
        { js: "on" },
        function(data) {
            if(data.error) {
                alert(data.error);
                return false;
            }

            if(data.count) {
                $el.find("strong").text(number_format(String(data.count)));
                if($tx.attr("id").search("nogood") > -1) {
                    $tx.text("이 글을 비추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                } else {
                    $tx.text("이 글을 추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                }
            }
        }, "json"
    );
}
</script>