<?php
include_once('../common.php');
$gr_id = "sub04";
$bo_table = "sub04_02";
include_once(G5_PATH.'/head.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_SKIN_URL.'/board/basic/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="./style.css">', 0);
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)


$url = 'http://openapi.work.go.kr/opi/opi/opia/wantedApi.do?authKey=WNJ1YGMVVQWWSLPXZWZLE2VR1HL&callTp=L&returnType=XML&startPage='.$page.'&display=10&region=45000&pref=Y'; 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = cURL_exec($ch);
cURL_close($ch); 

$rss = simplexml_load_string($response); 


$rss->total; 

$total_count = $rss->total;

$rows = 10;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
$from_record = ($page - 1) * $rows; // 시작 열을 구함
?>
<!-- 게시판 목록 시작 { -->
<div id="bo_list" style="width:100%;">

    <!-- 게시판 페이지 정보 및 버튼 시작 { -->
    <div class="bo_fx">
        <div id="bo_list_total" style="padding-left:10px">
            <span>Total <?php echo number_format($total_count) ?>건</span>
            <?php echo $page ?> 페이지
        </div>       
    </div>
    <!-- } 게시판 페이지 정보 및 버튼 끝 -->

    <form name="fboardlist" id="fboardlist" method="post">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="sw" value="">

    <div class="tbl_head01 tbl_wrap">
        <table class="tab02">
        <caption>구인 목록</caption>
        <thead>
        <tr>
            <th scope="col" class="none03"><strong>회사명</strong></th>
            <th scope="col"><?php if(G5_IS_MOBILE) {?>채용직종 / 업체명 / 근무지역 / 마감일<?php }else{?>채용직종/근무지역<?php }?></th>
            <th scope="col" class="none01"><strong>임금</strong></th>
            <th scope="col" class="none01"><strong>근무형태</strong></th>
            <th scope="col" class="none02"><strong>학력/경력</strong></th>
            <th scope="col" class="none01"><strong>등록일</strong></th>
            <th scope="col" class="none02"><strong>마감일</strong></th>
        </tr>
        </thead>
        <tbody>
    <?php
	foreach($rss as $chan) {  
		if($chan->company) {
		
        $bg = 'bg'.($i%2);
       ?>
        <tr>
            <td style="width:190px;" class="none03">
                    <a href="./w_view.php?no=<?php echo $chan->wantedAuthNo?>"><?php echo $chan->company?></a>
            </td>
            <td class="td_subject">
            <?php if(G5_IS_MOBILE) {?>
            	<a href="./w_view.php?no=<?php echo $chan->wantedAuthNo?>"><strong><?php echo $chan->title?></strong></a><br />
            	<a href="./w_view.php?no=<?php echo $chan->wantedAuthNo?>"><?php echo $chan->company?></a><br />[<?php echo $chan->region?>] <span style="float:right">마감일 : <?php echo $chan->closeDt?></span>
            <?php }else{?>
            <a href="./w_view.php?no=<?php echo $chan->wantedAuthNo?>"><strong><?php echo $chan->title?></strong></a><br /><?php echo $chan->region?>
            <?php }?>
            </td>
            <td class="td_mng none01" style="width:160px;"><?php echo $chan->salTpNm?><?php echo $chan->sal?></td>
            <td class="td_date none01" style="width:70px;"><?php echo $chan->holidayTpNm?></td>            
            <td class="td_datetime none02" style="width:90px;"><?php echo $chan->minEdubg?><br /><?php echo $chan->career?></td>
            <td class="td_mng none01"><?php echo $chan->regDt?></td>
            <td style="width:140px;text-align:center;" class="none02"><?php echo $chan->closeDt?></td>
        </tr>
    <?php }} ?>
    <?php if ($total_count == 0) { echo '<tr><td colspan="7" class="empty_table">게시물이 없습니다.</td></tr>'; } ?>
        </tbody>
        </table>
    </div>


<div class="btn_confirm01 btn_confirm">
    <a href="./work.php" class="btn_b01">목록</a>
</div>    
    </form>
</div>

<!-- 페이지 -->
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['PHP_SELF'].'?'.$qstr.'&amp;page='); ?>


<?php include_once(G5_PATH.'/tail.php'); ?>
