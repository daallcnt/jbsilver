<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
if(!$e_id) {
	$name = '';
	if (isset($name)) {
		$name = substr(trim($name),0,255);
		$name = preg_replace("#[\\\]+$#", "", $name);
	}
	$mobile = '';
	if (isset($mobile)) {
		$mobile = substr(trim($mobile),0,255);
		$mobile = preg_replace("#[\\\]+$#", "", $mobile);
		$receive_number = preg_replace("/[^0-9]/", "", $mobile);
	}
	if($id) {
$sql = " select * from wp_edu where id = '$id' order by wdate desc ";		
	}else{
$sql = " select * from wp_edu where name = '$name' and replace(mobile,'-','') = '$receive_number'  order by wdate desc ";
	}
}else{
$sql = " select * from wp_edu where e_id = '$e_id' ";
}
$row = sql_fetch($sql);
if (!$row['name']) 
    alert("신청내역이 존재하지 않습니다.");



if ($is_update) {
}else{
    if (!$is_admin) {
		if (!($is_member && $member['mb_id'] === $row['id'])) {
            if (!check_password($_POST['pass'], $row['pass'])) {
                alert('비밀번호가 틀립니다.');
            }
        }
    }
}


/*
if(!$e_id){
	alert("제대로 된 값이 넘어오지 않았습니다.");
}

    $sql = " select * from wp_edu where e_id = '$e_id' ";
    $row = sql_fetch($sql);
*/
?>
<!-- 게시물 읽기 시작 { -->

<article id="bo_v" style="width:<?php echo $width; ?>">
    <header>
        <h1 id="bo_v_title" style="font-size:18px;border-bottom:1px solid #dddddd;padding-bottom:10px;">
            <?php echo $row['subject']?>
        </h1>
    </header>

    <!-- 게시물 상단 버튼 시작 { -->
    <div id="bo_v_top">
        <?php
        ob_start();
         ?>
        <ul class="bo_v_com">
            <!--
            <li><a href="./edu.php?case=slist&amp;id=<?php echo $row['id']?>&amp;edu=<?php echo $edu?>&is_update=1" class="btn_b01 btn">목록</a></li>
			--->
            <li><a href="./index.php?case=write&amp;w=u&amp;e_id=<?php echo $row['e_id']?>&is_update=1" class="btn_b02 btn">수정/취소</a></li>
        </ul>
        <?php
        $link_buttons = ob_get_contents();
        ob_end_flush();
         ?>
    </div>
    <!-- } 게시물 상단 버튼 끝 -->
    
    <section id="bo_v_atc">
        <h2 id="bo_v_atc_title">본문</h2>


        <!-- 본문 내용 시작 { -->
<div class="edu_frm01 tbl_wrap">
    <table>
    <caption style="font-size:15px;">교육신청내역</caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">교육명</th>
        <td><b><?php echo $row['subject']?></b></td>
    </tr>
    <tr>
        <th scope="row">교육일시</th>
        <td><?php echo $row['schedule']?><?php echo ($row['schedule'] != $row['edate'] && $row['edate'])? " ~ ".$row['edate']:"";?></td>
    </tr>
    <tr>
        <th scope="row">아이디</th>
        <td><?php echo $row['id']?></td> 
    </tr>
    <tr>
        <th scope="row">신청자</th>
        <td><?php echo $row['name']?></td> 
    </tr>        

    <tr>
        <th scope="row">연락처</th>
        <td><?php echo $row['mobile']?></td>
    </tr>
    <tr>
        <th scope="row">교육인원</th>
        <td><?php echo $row['person']?>명</td>
    </tr> 
    <tr>
        <th scope="row">기관명</th>
        <td><?php echo $row['corp']?></td>
    </tr>
    <tr>
        <th scope="row">비고</th>
        <td><?php echo $row['etc']?></td>
    </tr>      
    <tr>
        <th scope="row">기관전화</th>
        <td><?php echo $row['phone']?></td>
    </tr>
    <tr>
        <th scope="row">진행상태</th>
        <td><?php echo $row['progress']?></td>
    </tr>
    <tr>
        <th scope="row">결제상태</th>
        <td><?php echo $row['payment']?></td>
    </tr>
    <?php /*if($row['payment'] == "완납") {?>
    <tr>
        <th scope="row">영수증</th>
        <td><a href="javascript:GPEN_PRINT('<?php echo $row['e_id']?>');">출력</a></td>
    </tr>
    <?php }*/?>            
    <tr>
        <th scope="row">입력일시</th>
        <td><?php echo $row['wdate']?></td>
    </tr>                           
    </tbody>
    </table>
</div>  
        <!-- } 본문 내용 끝 -->


    </section>

    <!-- 링크 버튼 시작 { -->
    <div id="bo_v_bot">
        <?php echo $link_buttons ?>
    </div>
    <!-- } 링크 버튼 끝 -->

</article>
<!-- } 게시판 읽기 끝 -->
<script>
function GPEN_PRINT(val){ 
	var p = window.open("<?php echo G5_URL?>/edu/r_print.php?e_id="+val,"Printedu","width=687, height=600, scrollbars=yes"); 
	p.focus(); 
}
</script>