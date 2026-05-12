<?php
$sub_menu = "200150";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

//if ($is_admin != 'super')
//    alert('최고관리자만 접근 가능합니다.');

// uniqid 테이블이 없을 경우 생성
if(!sql_query(" DESC g5_mblevel ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `g5_mblevel` (
                  `gm_no` int(11) NOT NULL AUTO_INCREMENT,
				  `gm_id` varchar(20) NOT NULL,
                  `gm_name` varchar(255) NOT NULL,
                  PRIMARY KEY (`gm_no`),
                  KEY `gm_id` (`gm_id`)
                ) ", false);

sql_query(" INSERT INTO g5_mblevel (`gm_no`, `gm_id`, `gm_name`) VALUES (1, '1', '비회원'), (2, '2', '정회원'), (3, '3', ''), (4, '4', ''), (5, '5', ''), (6, '6', ''), (7, '7', ''), (8, '8', ''), (9, '9', ''), (10, '10', '관리자') ");
 				
}

$g5['title'] = '회원레벨관리';
include_once ('./admin.head.php');
?>
<form name="fmemform" id="fmemform" action="./mem_form_update.php" onsubmit="return fmemform_submit(this);" method="post">
<input type="hidden" name="token" value="">

<div class="tbl_head01 tbl_wrap">
        <table>
        <caption>회원레벨 기본 설정</caption>
        <thead>
        <tr>
        <th scope="col">
            <label for="chkall" class="sound_only">전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">        
        </th>
        <th scope="col">회원레벨</th>
        <th scope="col">레벨명</th>
        </tr>
        </thead>
        <tbody>        
        <?php 
		$sql = " select * from g5_mblevel order by gm_no asc";
		$result = sql_query($sql);
		for ($i=0; $row=sql_fetch_array($result); $i++) { 
		?>
        <tr>
            <td class="td_chk">
            <input type="hidden" name="gm_no[<?php echo $i ?>]" value="<?php echo $row['gm_no'] ?>" id="gm_no_<?php echo $i ?>">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['gm_id']; ?></label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
            </td>                    
            <td class="td_grid"><?php echo $row['gm_id'] ?></th>
            <td class="td_input">
<label for="gm_name_<?php echo $i; ?>" class="sound_only">회원레벨명</label>
            <input type="text" name="gm_name[<?php echo $i ?>]" value="<?php echo get_text($row['gm_name']) ?>" id="gm_name_<?php echo $i ?>" class="frm_input">                
            </td>
        </tr>
        <?php } ?>
        </tbody>
        </table>
    </div>
</section>

<div class="btn_fixed_top">
    <input type="submit" name="act_button" onclick="document.pressed=this.value" value="선택수정" class="btn btn_submit">
</div>

</form>

<script>
function fmemform_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }
		
    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
