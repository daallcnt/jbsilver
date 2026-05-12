<?php
$sub_menu = '750100';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

//내용(컨텐츠)정보 테이블이 있는지 검사한다.
if(!sql_query(" DESCRIBE wp_education ", false)) {
       $query_cp = sql_query(" CREATE TABLE IF NOT EXISTS `wp_education` (
					  `s_id` int(11) NOT NULL auto_increment,
					  `subject` varchar(255) NOT NULL default '',
					  `person` int(12) NOT NULL default '0',
					  `wating` int(12) NOT NULL default '0',
					  `schedule` varchar(255) NOT NULL default '',
					  `edate` varchar(255) NOT NULL default '',
					  `place` varchar(255) NOT NULL default '',
					  `s_day` varchar(20) NOT NULL default '',
					  `f_day` varchar(20) NOT NULL default '',
					  `wr_file` int(12) NOT NULL default '0',
					  `contents` text NOT NULL,
					  `edu_01` varchar(255) NOT NULL default '',
					  `edu_02` varchar(255) NOT NULL default '',
					  `edu_03` varchar(255) NOT NULL default '',
					  `edu_04` varchar(255) NOT NULL default '',
					  `edu_05` varchar(255) NOT NULL default '',
					  `s_chk` varchar(10) NOT NULL default '',
					  `wdate` datetime NOT NULL default '0000-00-00 00:00:00',
					  `mdate` datetime NOT NULL default '0000-00-00 00:00:00',
                      PRIMARY KEY (`s_id`),
  					  KEY `s_day` (`s_day`)					  
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ", true);
}
if(!sql_query(" DESCRIBE wp_edu ", false)) {
       $query_cp = sql_query(" CREATE TABLE IF NOT EXISTS `wp_edu` (
					  `e_id` int(11) NOT NULL auto_increment,
					  `s_id` varchar(20) NOT NULL default '',
					  `id` varchar(16) NOT NULL default '',
					  `name` varchar(20) NOT NULL default '',
					  `mobile` varchar(20) NOT NULL default '',
					  `subject` varchar(255) NOT NULL default '',
					  `person` int(12) NOT NULL default '0',
					  `schedule` varchar(100) NOT NULL default '',
					  `edate` varchar(255) NOT NULL default '',
					  `corp` varchar(50) NOT NULL default '',
					  `phone` varchar(50) NOT NULL default '',
					  `progress` varchar(2) NOT NULL default '',
					  `payment` varchar(10) NOT NULL default '',
					  `wdate` datetime NOT NULL default '0000-00-00 00:00:00',
					  `mdate` datetime NOT NULL default '0000-00-00 00:00:00',
					  PRIMARY KEY  (`e_id`)			  
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ", true);
}


$sql_common = " from wp_education ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "s_id" :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if ($sca) $sql_search .= " and SUBSTRING(s_day,1,4) = '$sca' ";

if (!$sst) {
    $sst = "schedule";
    $sod = "desc";
}

$sql_order = " order by $sst $sod ";

$sql = " select count(s_id) as cnt
         $sql_common
         $sql_search
         $sql_order ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 15;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$sql = " select *
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);


$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '교육관리';
include_once('./admin.head.php');

$colspan = 16;

$nc_category_location   = "./sunedu_list.php?$qstr&page=";
?>
<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총교육수 </span><span class="ov_num"><?php echo number_format($total_count) ?></span></span>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
<select name='sca' id="sca" onChange="location='<?php echo $nc_category_location?>&sca='+this.value;"> 
    <option value=''>선택하세요    
<?php
for ($i=2016; $i<= date("Y") + 1; $i++)
{
    if ($i == $sca) $selected = " selected";
    else $selected = "";
    echo "<option value='{$i}'{$selected}>$i 년</option>";
}
?>
</select>

<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="subject"<?php echo get_selected($_GET['sfl'], "subject"); ?>>교육명</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" class="btn_submit" value="검색">
</form>

<div class="btn_add01 btn_add">
    <a href="./sunedu_form.php" id="edu_add">교육추가</a>
</div>
<form name="fmemberlist" id="fedulist" action="./sunedu_list_delete.php" onsubmit="return fedulist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo get_admin_token()?>">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">
            <label for="chkall" class="sound_only">전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col">번호</th>
        <th scope="col">교육명</th>
        <th scope="col">정원/대기</th>
        <th scope="col">교육일시</th>
        <th scope="col">장소</th>
        <th scope="col">현황</th>
        <th scope="col">신청내역</th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {

	    $num = number_format($total_count - ($page - 1) * $rows - $i);

	$s_mod = '<a href="./sunedu_form.php?'.$qstr.'&amp;w=u&amp;s_id='.$row['s_id'].'">수정</a>';
	$s_del = '<a href="./sunedu_delete.php?'.$qstr.'&amp;w=d&amp;s_id='.$row['s_id'].'" onclick="return delete_confirm(this);">삭제</a>';

	$s_vi = "<a href='./edumember_list.php?s_id=$row[s_id]&$qstr'>신청현황</a>";

	$r = sql_fetch(" select sum(person) as tot from wp_edu where s_id = '$row[s_id]' and progress <> '취소' "); 

			$SumPerson = ($r['tot'])? $r['tot'] : "0" ;
		 	$PersonWating =  $row['person'] + $row['wating'];
			if($row['s_chk'] != 1) {
				if($SumPerson < $row['person']){
					if(date("Y-m-d") < $row['s_day']){
						$progress = "준비";
					}elseif(date("Y-m-d") > $row['f_day']){
						$progress = "마감";
					}else{
						$progress = "신청";
					}
				}elseif($SumPerson < $PersonWating){
					if(date("Y-m-d") < $row['s_day']){
						$progress = "준비";
					}elseif(date("Y-m-d") > $row['f_day']){
						$progress = "마감";
					}else{
						$progress = "대기";
					}
				}else{
					$progress = "마감";
				}
			}else{
			$progress = "마감";
			}

        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <input type="hidden" name="s_id[<?php echo $i ?>]" value="<?php echo $row['s_id'] ?>" id="s_id_<?php echo $i ?>">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['subject']); ?></label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
        <td class="td_num"><?php echo $num; ?></td>
        <td><a href="./sunedu_view.php?s_id=<?php echo $row['s_id']?>"><?php echo $row['subject']?></a></td>
        <td class="td_grid"><?php echo number_format($row['person'])?> / <?php echo number_format($row['wating'])?></td>
        <td class="td_itopt"><?php echo $row['schedule']?><?php echo ($row['schedule'] != $row['edate'] && $row['edate'])? " ~ ".$row['edate']:"";?></td>
        <td class="td_bigpostal"><?php echo $row['place']?></td>        
        <td class="td_amount"><?php echo $progress?></td>
        <td class="td_amount"><?php echo $s_vi?></td>        
        <td class="td_mng"><?php echo $s_mod?> <?php echo $s_del?></td>
    </tr>

    <?php
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<script>
function fedulist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>