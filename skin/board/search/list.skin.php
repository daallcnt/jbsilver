<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 6;

if ($is_checkbox) $colspan++;
if ($is_good) $colspan++;
if ($is_nogood) $colspan++;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
set_session("ss_delete_token", $token = uniqid(time()));
?>

<!-- 게시판 목록 시작 { -->

	<div id="jb_map">		
		<div class="map01"><a href="../bbs/board.php?bo_table=sub01_06&sca=군산시"></a></div>
		<div class="map02"><a href="../bbs/board.php?bo_table=sub01_06&sca=익산시"></a></div>
		<div class="map03"><a href="../bbs/board.php?bo_table=sub01_06&sca=완주군"></a></div>
		<div class="map04"><a href="../bbs/board.php?bo_table=sub01_06&sca=진안군"></a></div>
		<div class="map05"><a href="../bbs/board.php?bo_table=sub01_06&sca=무주군"></a></div> 
		<div class="map06"><a href="../bbs/board.php?bo_table=sub01_06&sca=김제시"></a></div>
		<div class="map07"><a href="../bbs/board.php?bo_table=sub01_06&sca=전주시"></a></div>
		<div class="map08"><a href="../bbs/board.php?bo_table=sub01_06&sca=부안군"></a></div>
		<div class="map09"><a href="../bbs/board.php?bo_table=sub01_06&sca=정읍시"></a></div>
		<div class="map10"><a href="../bbs/board.php?bo_table=sub01_06&sca=임실군"></a></div>
		<div class="map11"><a href="../bbs/board.php?bo_table=sub01_06&sca=장수군"></a></div>
		<div class="map12"><a href="../bbs/board.php?bo_table=sub01_06&sca=고창군"></a></div>
		<div class="map13"><a href="../bbs/board.php?bo_table=sub01_06&sca=순창군"></a></div>
		<div class="map14"><a href="../bbs/board.php?bo_table=sub01_06&sca=남원시"></a></div>	
	</div>

<!--
<div style="text-align:center;padding-bottom:10px;"><img src="../img/jeonbuk_map.png" usemap="#jb_Map" border="0"></div>
<map name="jb_Map">
  <area shape="rect" coords="74,67,117,89" href="../bbs/board.php?bo_table=sub01_06&sca=군산시">
  <area shape="rect" coords="149,45,190,67" href="../bbs/board.php?bo_table=sub01_06&sca=익산시">
  <area shape="rect" coords="213,65,254,87" href="../bbs/board.php?bo_table=sub01_06&sca=완주군">
  <area shape="rect" coords="358,75,399,97" href="../bbs/board.php?bo_table=sub01_06&sca=무주군">
  <area shape="rect" coords="116,118,157,140" href="../bbs/board.php?bo_table=sub01_06&sca=김제시">
  <area shape="rect" coords="188,118,228,140" href="../bbs/board.php?bo_table=sub01_06&sca=전주시">
  <area shape="rect" coords="271,119,312,141" href="../bbs/board.php?bo_table=sub01_06&sca=진안군">
  <area shape="rect" coords="41,174,83,196" href="../bbs/board.php?bo_table=sub01_06&sca=부안군">
  <area shape="rect" coords="117,201,157,223" href="../bbs/board.php?bo_table=sub01_06&sca=정읍시">
  <area shape="rect" coords="216,209,257,231" href="../bbs/board.php?bo_table=sub01_06&sca=임실군">
  <area shape="rect" coords="309,183,351,205" href="../bbs/board.php?bo_table=sub01_06&sca=장수군">
  <area shape="rect" coords="28,264,69,286" href="../bbs/board.php?bo_table=sub01_06&sca=고창군">
  <area shape="rect" coords="186,275,228,297" href="../bbs/board.php?bo_table=sub01_06&sca=순창군">
  <area shape="rect" coords="280,267,322,289" href="../bbs/board.php?bo_table=sub01_06&sca=남원시">
</map>
-->

<div id="bo_list" style="width:<?php echo $width; ?>;font-size:13px;">

    <?php if ($is_category) { ?>
    <nav id="bo_cate">
        <h2><?php echo ($board['bo_mobile_subject'] ? $board['bo_mobile_subject'] : $board['bo_subject']) ?> 카테고리</h2>
        <ul id="bo_cate_ul">
            <?php echo $category_option ?>
        </ul>
    </nav>
    <?php } ?>

<div style="padding-bottom:30px;">
<table width="100%" style="border:3px solid #008ed0;padding:20px;">
	<tr>
    	<td width="30%">  
<?php if ($is_category) { ?>
<form name="fcategory" method="get">
<input type="hidden" name="bo_table" value="<?php echo $bo_table; ?>">
시군구 : &nbsp; 
<select name="sca" onchange="location='<?php echo $category_href?>&sca='+encodeURIComponent(this.value)">
    <option value=''>전체</option>
    <?php echo get_category_option($bo_table, $sca); // SELECT OPTION 태그로 넘겨받음 ?>
</select>
</form>
<?php } ?>        
		</td>
        <td>
    <fieldset id="bo_sch">
        <legend>게시물 검색</legend>
        <form name="fsearch" method="get">
        <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
        <input type="hidden" name="sca" value="<?php echo $sca ?>">
        <input type="hidden" name="sop" value="and">
        <label for="sfl" class="sound_only">검색대상</label>
        <select name="sfl" id="sfl">
            <option value="wr_subject"<?php echo get_selected($sfl, 'wr_subject', true); ?>>사업수행기관</option>
            <option value="wr_content"<?php echo get_selected($sfl, 'wr_content'); ?>>사업명</option>
        </select>
        <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
        <input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" required id="stx" class="sch_input" size="25" maxlength="20" placeholder="검색어를 입력해주세요">
        <button type="submit" value="검색" class="sch_btn"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색</span></button>
        </form>
    </fieldset>        
        </td>
	</tr>
</table>
</div>    
    <!-- 게시판 페이지 정보 및 버튼 시작 { -->
    <div id="bo_btn_top">
        <div id="bo_list_total">
            <span>Total <?php echo number_format($total_count) ?>건</span>
            <?php echo $page ?> 페이지
        </div>

        <?php if ($rss_href || $write_href) { ?>
        <ul class="btn_bo_user">
            <?php if ($rss_href) { ?><li><a href="<?php echo $rss_href ?>" class="btn_b01 btn"><i class="fa fa-rss" aria-hidden="true"></i> RSS</a></li><?php } ?>
            <?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>" class="btn_admin btn"><i class="fa fa-user-circle" aria-hidden="true"></i> 관리자</a></li><?php } ?>
            <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b02 btn"><i class="fa fa-pencil" aria-hidden="true"></i> 글쓰기</a></li><?php } ?>
        </ul>
        <?php } ?>
    </div>
    <!-- } 게시판 페이지 정보 및 버튼 끝 -->



    <form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="sw" value="">

    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption><?php echo $board['bo_subject'] ?> 목록</caption>
        <thead>
        <tr>
            <?php if ($is_checkbox) { ?>
            <th scope="col">
                <label for="chkall" class="sound_only">현재 페이지 게시물 전체</label>
                <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
            </th>
            <?php } ?>
            <th scope="col">번호</th>
            <th scope="col" width="270px">사업수행기관</th>
            <th scope="col">사업유형</th>
            <th scope="col">사업명</th>
            <th scope="col" width="200px">주소</th>
            <th scope="col">전화번호</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for ($i=0; $i<count($list); $i++) {
         ?>
        <tr class="<?php if ($list[$i]['is_notice']) echo "bo_notice"; ?>">
            <?php if ($is_checkbox) { ?>
            <td class="td_chk">
                <label for="chk_wr_id_<?php echo $i ?>" class="sound_only"><?php echo $list[$i]['subject'] ?></label>
                <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
            </td>
            <?php } ?>
            <td class="td_num2">
            <?php
            if ($list[$i]['is_notice']) // 공지사항
                echo '<strong class="notice_icon"><i class="fa fa-bullhorn" aria-hidden="true"></i><span class="sound_only">공지</span></strong>';
            else if ($wr_id == $list[$i]['wr_id'])
                echo "<span class=\"bo_current\">열람중</span>";
            else
                echo $list[$i]['num'];
             ?>
            </td>

            <td class="td_subject" style="text-align:center;padding-left:<?php echo $list[$i]['reply'] ? (strlen($list[$i]['wr_reply'])*10) : '0'; ?>px">
                <?php
                /*if ($is_category && $list[$i]['ca_name']) {
                 ?>
                <a href="<?php echo $list[$i]['ca_name_href'] ?>" class="bo_cate_link"><?php echo $list[$i]['ca_name'] ?></a>
                <?php }*/ ?>
                <div class="bo_tit">
                    
                    
                        <?php echo $list[$i]['icon_reply'] ?>
                        <?php
                            if (isset($list[$i]['icon_secret'])) echo rtrim($list[$i]['icon_secret']);
                         ?>
                        <?php echo $list[$i]['subject'] ?>
                       
                 
                    <?php
                    // if ($list[$i]['file']['count']) { echo '<'.$list[$i]['file']['count'].'>'; }
                    //if (isset($list[$i]['icon_file'])) echo rtrim($list[$i]['icon_file']);
                    //if (isset($list[$i]['icon_link'])) echo rtrim($list[$i]['icon_link']);
                    if (isset($list[$i]['icon_new'])) echo rtrim($list[$i]['icon_new']);
                    //if (isset($list[$i]['icon_hot'])) echo rtrim($list[$i]['icon_hot']);
                    ?>
                    <?php if ($list[$i]['comment_cnt']) { ?><span class="sound_only">댓글</span><span class="cnt_cmt">+ <?php echo $list[$i]['wr_comment']; ?></span><span class="sound_only">개</span><?php } ?>
<?php
$update_href = $delete_href = '';
// 로그인중이고 자신의 글이라면 또는 관리자라면 비밀번호를 묻지 않고 바로 수정, 삭제 가능
if (($member['mb_id'] && ($member['mb_id'] == $list[$i]['mb_id'])) || $is_admin) {
    $update_href = './write.php?w=u&amp;bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].'&amp;page='.$page.$qstr;
    $delete_href = './delete.php?bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].'&amp;page='.$page.urldecode($qstr);
    if ($is_admin)
    {
        $delete_href ='./delete.php?bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].'&amp;token='.$token.'&amp;page='.$page.urldecode($qstr);
    }
}                    

if ($update_href || $delete_href) { 
           if ($update_href) { 
		   echo "<a href='".$update_href."'><img src='".$board_skin_url."/img/btn_comment_update.gif' alt='수정' width='11' height='11' border='0'  align='absmiddle' /></a>";
		   }
           if ($delete_href) { 
           echo "<a href='".$delete_href."' onclick='del(this.href); return false;'><img src='".$board_skin_url."/img/btn_comment_delete.gif' alt='삭제' width='11' height='11' border='0' align='absmiddle' /></a>";
           } 							
} 
?>  
                </div>

            </td>
            <td class="td_mng"><?php echo $list[$i]['wr_2'] ?></td>
            <td class="ta_c"><?php echo $list[$i]['wr_content'] ?></td>
            <td class="td_name"><?php echo $list[$i]['wr_3'] ?></td>
            <td class="td_nick"><?php echo $list[$i]['wr_1'] ?></td>

        </tr>
        <?php } ?>
        <?php if (count($list) == 0) { echo '<tr><td colspan="'.$colspan.'" class="empty_table">게시물이 없습니다.</td></tr>'; } ?>
        </tbody>
        </table>
    </div>

    <?php if ($list_href || $is_checkbox || $write_href) { ?>
    <div class="bo_fx">
        <?php if ($list_href || $write_href) { ?>
        <ul class="btn_bo_user">
            <?php if ($is_checkbox) { ?>
            <li><button type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_admin"><i class="fa fa-trash-o" aria-hidden="true"></i> 선택삭제</button></li>
            <li><button type="submit" name="btn_submit" value="선택복사" onclick="document.pressed=this.value" class="btn btn_admin"><i class="fa fa-files-o" aria-hidden="true"></i> 선택복사</button></li>
            <li><button type="submit" name="btn_submit" value="선택이동" onclick="document.pressed=this.value" class="btn btn_admin"><i class="fa fa-arrows" aria-hidden="true"></i> 선택이동</button></li>
            <?php } ?>
            <?php if ($list_href) { ?><li><a href="<?php echo $list_href ?>" class="btn_b01 btn"><i class="fa fa-list" aria-hidden="true"></i> 목록</a></li><?php } ?>
            <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b02 btn"><i class="fa fa-pencil" aria-hidden="true"></i> 글쓰기</a></li><?php } ?>
        </ul>
        <?php } ?>
    </div>
    <?php } ?>

    </form>
     
  
</div>

<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>



<!-- 페이지 -->
<?php echo $write_pages;  ?>


<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]")
            f.elements[i].checked = sw;
    }
}

function fboardlist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택복사") {
        select_copy("copy");
        return;
    }

    if(document.pressed == "선택이동") {
        select_copy("move");
        return;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
            return false;

        f.removeAttribute("target");
        f.action = "./board_list_update.php";
    }

    return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
    var f = document.fboardlist;

    if (sw == "copy")
        str = "복사";
    else
        str = "이동";

    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

    f.sw.value = sw;
    f.target = "move";
    f.action = "./move.php";
    f.submit();
}
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->
