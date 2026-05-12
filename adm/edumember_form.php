<?php
$sub_menu = "750100";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[$sub_menu], 'w');

$html_title = '교육신청';

if ($w == '') {

    if(!$s_id) alert("잘못된 접근방식입니다.");

    $html_title .= ' 생성';

    $sql = " select s_id, subject, schedule from wp_education where s_id = '$s_id' ";
    $rows = sql_fetch($sql);

} else if ($w == 'u') {

    $html_title .= ' 수정';

    $sql = " select * from wp_edu where e_id = '$e_id' ";
    $row = sql_fetch($sql);
    if (!$row['e_id']) 
        alert("신청자가 없습니다.");	
	
	$sql = " select s_id, subject, schedule from wp_education where s_id = '$row[s_id]' ";
    $rows = sql_fetch($sql);
	$s_id = $rows['s_id'];	
}

$g5['title'] = $html_title;
include_once (G5_ADMIN_PATH.'/admin.head.php');

if (isset($s_id)) $qstr .= "&s_id=$s_id";
?>
<form name="frmeduform" action="./edumember_form_update.php" onsubmit="return frmeduform_check(this);" method="post" enctype="MULTIPART/FORM-DATA" >
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="sca" value="<?php echo $sca; ?>">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sod" value="<?php echo $sod; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="s_id" value='<?php echo $rows['s_id']?>' />
<input type="hidden" name="e_id" value='<?php echo $row['e_id']?>' />
<input type="hidden" name="token">
<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">교육명</th>
        <td><input type="hidden" name="subject" value="<?php echo $rows['subject']?>"><?php echo $rows['subject']?></td>
    </tr>
    <tr>
        <th scope="row">교육일시</th>
        <td><input type="hidden" name="schedule" value="<?php echo $rows['schedule']?>"><input type="hidden" name="edate" value="<?php echo $row['edate']?>"><?php echo $rows['schedule']?><?php echo ($row['schedule'] != $row['edate'] && $row['edate'])? " ~ ".$row['edate']:"";?></td>
    </tr>    
    <tr>
        <th scope="row"><label for="id">아이디</label></th>
        <td><input type="text" name="id" value="<?php echo $row['id']?>" id="id" required class="frm_input required" size="30"></td> 
    </tr>
    <tr>
        <th scope="row"><label for="name">신청자</label></th>
        <td><input type="text" name="name" value="<?php echo $row['name']?>" id="name" required class="frm_input required" size="30"></td>
    </tr>
    <tr>
        <th scope="row"><label for="mobile">연락처</label></th>
        <td><input type="text" name="mobile" value="<?php echo $row['mobile']?>" id="mobile" required class="frm_input required" size="30"></td> 
    </tr>        

    <tr>
        <th scope="row"><label for="person">교육인원</label></th>
        <td><input type="text" name="person" value="<?php echo $row['person']?>" id="person" required class="required frm_input" size="10">명 ※ 자신을 포함한 인원수</td>
    </tr>
    <tr>
        <th scope="row"><label for="corp">기관명</label></th>
        <td><input type="text" name="corp" value="<?php echo $row['corp']?>" id="corp" class="frm_input" size="50"></td>
    </tr> 
    <tr>
        <th scope="row"><label for="etc">비고</label></th>
        <td><input type="text" name="etc" value="<?php echo $row['etc']?>" id="etc" class="frm_input" size="50"></td>
    </tr>     
    <tr>
        <th scope="row"><label for="phone">기관전화</label></th>
        <td><input type="text" name="phone" value="<?php echo $row['phone']?>" id="phone" class="frm_input" size="30"></td>
    </tr>  
    <tr>
        <th scope="row"><label for="edu">시설구분</label></th>
        <td>
            <select name="edu" id="edu">
                <option value="회원"<?php echo get_selected($row['edu'], '회원'); ?>>회원</option>
                <option value="비회원"<?php echo get_selected($row['edu'], '비회원', true); ?>>비회원</option>
            </select>        
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="progress">진행상태</label></th>
        <td>
            <select name="progress" id="progress">
				<option value="접수"<?php echo get_selected($row['progress'], '접수', true); ?>>접수</option>
                <option value="완료"<?php echo get_selected($row['progress'], '완료'); ?>>완료</option>
                <option value="대기"<?php echo get_selected($row['progress'], '대기'); ?>>대기</option>
                <option value="취소"<?php echo get_selected($row['progress'], '취소'); ?>>취소</option>
            </select>        
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="payment">결제상태</label></th>
        <td>
            <select name="payment" id="payment">
                <option value="미납"<?php echo get_selected($row['payment'], '미납', true); ?>>미납</option>
                <option value="완납"<?php echo get_selected($row['payment'], '완납'); ?>>완납</option>
                <option value="환불"<?php echo get_selected($row['payment'], '환불'); ?>>환불</option>
            </select>        
        </td>
    </tr>
<?php if ($w == "u") { ?>
    <tr>
        <th scope="row"><label for="wdate">입력일시</label></th>
        <td><?php echo $row['wdate']?></td>
    </tr>
<?php }?>                           
    </tbody>
    </table>
</div>
<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey="s">
    <a href="./edumember_list.php?<?php echo $qstr?>">목록</a>
</div>

</form>

<script>
function frmeduform_check(f)
{

    return true;
}
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
