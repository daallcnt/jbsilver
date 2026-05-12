<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
//if (!$is_member)
//    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_URL."/edu/index.php?case=write&s_id=".$s_id));

if ($w == '') {

    if(!$s_id) alert("잘못된 접근방식입니다.");


    $sql = " select * from wp_education where s_id = '$s_id' ";
    $row = sql_fetch($sql);

     $r = sql_fetch(" select sum(person) as tot from wp_edu where s_id = '$row[s_id]'  and progress <> '취소' ");  
     $SumPerson = $r['tot'];
    
     $r = sql_fetch(" select sum(person) as tot from wp_edu where s_id = '$row[s_id]' and progress = '대기' ");  
     $SumWating = $r['tot'];
    
        $AddPerson = $row['person'] + $rows['wating'] - $SumPerson;
        $AddWating = $row['wating'] - $SumWating; 
} else if ($w == 'u') {

    $sql = " select * from wp_edu where e_id = '$e_id' ";
    $row = sql_fetch($sql);
    if (!$row['e_id']) 
        alert("신청자가 없습니다.");	

    $sqls = " select subject, schedule, person, wating from wp_education where s_id = '$row[s_id]' ";
    $rows = sql_fetch($sqls);

     $r = sql_fetch(" select sum(person) as tot from wp_edu where s_id = '$row[s_id]'  and progress <> '취소' ");  
     $SumPerson = $r['tot'];
    
     $r = sql_fetch(" select sum(person) as tot from wp_edu where s_id = '$row[s_id]' and progress = '대기' ");  
     $SumWating = $r['tot'];
    
        $AddPerson = $rows['person'] + $rows['wating'] - $SumPerson;
        $AddWating = $rows['wating'] - $SumWating; 
}


$is_password = false;
if ($is_guest || ($is_admin && $w == 'u' && $member['mb_id'] !== $row['id'])) {
    $is_name = true;
    $is_password = true;
}

if ($w == '') {
    $password_required = 'required';
} else if ($w == 'u') {
    $password_required = '';
}
?>
<p>&nbsp;</p>
<section id="bo_w">

    <!-- 게시물 작성/수정 시작 { -->
    <form name="fwrite" id="fwrite" action="./update.php" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" style="width:<?php echo $width; ?>">
    <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="s_id" value='<?php echo $row['s_id']?>' />
    <input type="hidden" name="e_id" value='<?php echo $row['e_id']?>' />
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="case" value="<?php echo $case ?>" />
    <input type="hidden" name="pro" value="<?php echo $pro ?>" />

    <div class="edu_frm01 tbl_wrap">
        <table>
        <tbody>
        <tr>
            <th scope="row">교육명</th>
            <td><b><input type="hidden" name="subject" value="<?php echo $row['subject']?>"><?php echo $row['subject']?></b></td>
        </tr>
        <tr>
            <th scope="row">교육일시</th>
            <td><input type="hidden" name="schedule" value="<?php echo $row['schedule']?>"><input type="hidden" name="edate" value="<?php echo $row['edate']?>"><?php echo $row['schedule']?><?php echo ($row['schedule'] != $row['edate'])? " ~ ".$row['edate']:"";?></td>
        </tr> 
        <tr>
            <th scope="row"><label for="id">아이디</label></th>
            <td><input type="hidden" name="id" value="<?php echo ($w)?$row['id']:$member['mb_id'];?>"><?php echo ($w)?$row['id']:$member['mb_id'];?></td>
        </tr>
        <tr>
            <th scope="row"><label for="name">신청자<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="name" value="<?php echo ($w)?$row['name']:$member['mb_name'];?>" id="name" required class="frm_input required" size="10" maxlength="20"></td>
        </tr>
        <?php if ($is_password) { ?>
        <tr>
            <th scope="row"><label for="pass">비밀번호<strong class="sound_only">필수</strong></label></th>
            <td><input type="password" name="pass" id="pass" <?php echo $password_required ?> value="" class="frm_input <?php echo $password_required ?>"></td>
        </tr>
        <?php }?>	        
        <tr>
            <th scope="row"><label for="mobile">연락처<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="mobile" value="<?php echo $row['mobile'] ?>" id="mobile" required class="frm_input required"></td>
        </tr>
        <tr>
            <th scope="row"><label for="person">교육인원<strong class="sound_only">필수</strong></label></th>
            <td>
            <input type="text" name="person" value="<?php echo ($w)?$row['person']:1;?>" id="person" required class="frm_input required" size="5"  readonly="readonly">명
	 <br />(신청가능인원: <?php echo $AddPerson?> <!--/ 신청가능한 대기인원:<?php echo $AddWating?>-->)
            </td>
        </tr>
       
        <tr>
            <th scope="row"><label for="corp">기관명</label></th>
            <td><p style="padding-bottom:5px;">기관명, 기관 전화번호 입력란은 기관신청시에만 입력해 주세요.</p><input type="text" name="corp" value="<?php echo $row['corp']?>" id="corp" class="frm_input fc_input" size="40"></td>
        </tr> 
        <tr>
            <th scope="row"><label for="etc">비고</label></th>
            <td>
            <input type="text" name="etc" value="<?php echo $row['etc']?>" id="etc" class="frm_input" size="40">
            </td>
        </tr> 
        <tr>
            <th scope="row"><label for="phone">기관전화</label></th>
            <td><input type="text" name="phone" value="<?php echo $row['phone']?>" id="phone" class="frm_input"></td>
        </tr> 
<?php if($w) {?>
        <tr>
            <th scope="row"><label for="progress">진행상태</label></th>
            <td><?php echo $row['progress']?>  ( <input name="progress" type="checkbox" id="progress" value="취소" <?php echo ($row['progress']=="취소")?"checked='checked'":"";?> /> 취소 )</td>
        </tr> 
<?php }?>               
        </tbody>
        </table>
    </div>

	<div class="btn_confirm write_div">
        <input type="submit" value="작성완료" id="btn_submit" accesskey="s" class="btn_submit btn">
        <?php if($w) {?>
        <a href="./edu.php?case=sview&amp;e_id=<?php echo $row['e_id'] ?>" class="btn_cancel btn">취소</a>
		<?php }else{?>
        <a href="./index.php?case=view&amp;s_id=<?php echo $row['s_id'] ?>" class="btn_cancel btn">취소</a>
        <?php }?>
    </div>
    </form>

    <script>
    function fwrite_submit(f)
    {
        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
    </script>
</section>
<!-- } 게시물 작성/수정 끝 -->