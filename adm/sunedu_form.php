<?php
$sub_menu = '750100';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[$sub_menu], "w");

$g5['title'] = "교육관리";
$bo_table = "education";
if ($w == "u")
{
    $html_title .= " 수정";

    $sql = " select * from wp_education where s_id = '$s_id' ";
    $row = sql_fetch($sql);
    if (!$row['s_id'])
        alert('등록된 자료가 없습니다.');

	$file = get_file($bo_table, $row['s_id']);
}
else
{
    $html_title .= ' 입력';	
}

$qstr  = "$qstr&sca=$sca&page=$page";

$readonly = "readonly";

include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>
<form name="frmeduform" action="./sunedu_form_update.php" onsubmit="return frmeduform_check(this);" method="post" enctype="MULTIPART/FORM-DATA" >
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="sca" value="<?php echo $sca; ?>">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sod" value="<?php echo $sod; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="s_id" value='<?php echo $row['s_id']?>' />
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
        <th scope="row"><label for="subject">교육명</label></th>
        <td><input type="text" name="subject" value="<?php echo $row['subject']?>" id="subject" required class="frm_input required" size="50"></td>
    </tr>
    <tr>
        <th scope="row"><label for="person">교육정원</label></th>
        <td><input type="text" name="person" value="<?php echo $row['person']?>" id="person" required class="frm_input required" size="5"> 명</td> 
    </tr>
    <tr>
        <th scope="row"><label for="wating">대기인원</label></th>
        <td><input type="text" name="wating" value="<?php echo $row['wating']?>" id="wating" class="frm_input" size="5"> 명</td> 
    </tr>        
    <tr>
        <th scope="row"><label for="schedule">교육일시</label></th>
        <td><input type="text" name="schedule" value="<?php echo $row['schedule']?>" id="schedule" required  class="frm_input required" size="12"> ~ <input type="text" name="edate" value="<?php echo $row['edate']?>" id="edate"  required class="frm_input required" size="12"></td>
    </tr>
    <tr>
        <th scope="row"><label for="place">교육장소</label></th>
        <td><input type="text" name="place" value="<?php echo $row['place']?>" id="place" required class="frm_input required" size="50"></td>
    </tr>
    <tr>
        <th scope="row"><label for="s_day">접수기간</label></th>
        <td><input type="text" name="s_day" value="<?php echo $row['s_day']?>" id="s_day" required  class="frm_input required" size="12"> <input type="text" name="s_time" value="<?php echo ($row['s_time'])?$row['s_time']:"00:00:00";?>" id="s_time" required  class="frm_input required" size="8"> ~ <input type="text" name="f_day" value="<?php echo $row['f_day']?>" id="f_day" required  class="frm_input required" size="12"> <input type="text" name="f_time" value="<?php echo ($row['f_time'])?$row['f_time']:"00:00:00";?>" id="f_time" required  class="frm_input required" size="8"></td>
    </tr> 
    <tr>
        <th scope="row"><label for="edu_01">강사명</label></th>
        <td><input type="text" name="edu_01" value="<?php echo $row['edu_01']?>" id="edu_01" class="frm_input" size="50"></td>
    </tr>  
    <tr>
        <th scope="row"><label for="edu_02">접수일시</label></th>
        <td><input type="text" name="edu_02" value="<?php echo $row['edu_02']?>" id="edu_02" class="frm_input" size="50"></td>
    </tr>
    <tr>
        <th scope="row"><label for="edu_03">비회원교육비</label></th>
        <td><input type="text" name="edu_03" value="<?php echo $row['edu_03']?>" id="edu_03" class="frm_input" size="50"></td>
    </tr>
    <tr>
        <th scope="row"><label for="edu_06">회원교육비</label></th>
        <td><input type="text" name="edu_06" value="<?php echo $row['edu_06']?>" id="edu_06" class="frm_input" size="50"></td>
    </tr>    
    <tr>
        <th scope="row"><label for="edu_04">입금계좌번호</label></th>
        <td><input type="text" name="edu_04" value="<?php echo $row['edu_04']?>" id="edu_04" class="frm_input" size="50"></td>
    </tr>        
    <tr>
        <th scope="row"><label for="edu_05">입금기한</label></th>
        <td><input type="text" name="edu_05" value="<?php echo $row['edu_05']?>" id="edu_05" class="frm_input" size="50"></td>
    </tr>             
    <tr>
        <th scope="row"><label for="contents">교육내용</label></th>
        <td><?php echo editor_html('contents', get_text($row['contents'], 0)); ?></td>
    </tr>
        <?php for ($i=0; $i<5; $i++) { ?>
        <tr>
            <th scope="row">파일 #<?php echo $i+1 ?></th>
            <td>
                <input type="file" name="bf_file[]" title="파일첨부 <?php echo $i+1 ?>" class="frm_file frm_input">
                <?php if($w == 'u' && $file[$i]['file']) { ?>
                <input type="checkbox" id="bf_file_del<?php echo $i ?>" name="bf_file_del[<?php echo $i;  ?>]" value="1"> <label for="bf_file_del<?php echo $i ?>"><?php echo $file[$i]['source'].'('.$file[$i]['size'].')';  ?> 파일 삭제</label>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>   
    <tr>
        <th scope="row"><label for="s_chk">마감</label></th>
        <td><input name="s_chk" type="checkbox" value="1" <?php echo ($row['s_chk'] == "1") ? "checked='checked'":"";?> > 체크시 마감됩니다.</td>
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
    <a href="./sunedu_list.php?<?php echo $qstr?>">목록</a>
</div>

</form>

<script>
$(function(){
    $("#s_day, #f_day, #schedule, #edate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99"/*, maxDate: "+0d"*/ });
});

function frmeduform_check(f)
{
    <?php echo get_editor_js('contents'); ?>
    <?php //echo chk_editor_js('contents'); ?>

    return true;
}
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>