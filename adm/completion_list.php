<?php
$sub_menu = '750300';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

//내용(컨텐츠)정보 테이블이 있는지 검사한다.
if(!sql_query(" DESCRIBE wp_completion ", false)) {
       $query_cp = sql_query(" CREATE TABLE IF NOT EXISTS `wp_completion` (
					  `c_id` int(11) NOT NULL auto_increment,
					  `id` varchar(16) NOT NULL default '',
					  `name` varchar(20) NOT NULL default '',
					  `mobile` varchar(20) NOT NULL default '',
					  `subject` varchar(255) NOT NULL default '',
					  `schedule` varchar(100) NOT NULL default '',
					  `corp` varchar(50) NOT NULL default '',
					  `phone` varchar(50) NOT NULL default '',
					  `cno` varchar(255) NOT NULL default '',
					  `wdate` datetime NOT NULL default '0000-00-00 00:00:00',
					  PRIMARY KEY  (`c_id`)			  
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ", true);
}

$sql_common = " from wp_completion ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "c_id" :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if ($sca) $sql_search .= " and SUBSTRING(schedule,1,4) = '$sca' ";

if (!$sst) {
    $sst = "wdate";
    $sod = "desc";
}

$sql_order = " order by $sst $sod ";

$sql = " select count(c_id) as cnt
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

$g5['title'] = '수료자관리';
include_once('./admin.head.php');

$colspan = 16;

$nc_category_location   = "./completion_list.php?$qstr&page=";
?>
<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총수료자 </span><span class="ov_num"><?php echo number_format($total_count) ?></span></span>
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
    <option value="name"<?php echo get_selected($_GET['sfl'], "name"); ?>>성명</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" class="btn_submit" value="검색">
</form>


<form name="fmemberlist" id="fedulist" action="./completion_list_delete.php" onsubmit="return fedulist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="sca" value="<?php echo $sca ?>">
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
        <th scope="col">수료자</th>
        <th scope="col">연락처</th>
        <th scope="col">기관명</th>
        <th scope="col">교육명</th>
        <th scope="col">교육일자</th>
        <th scope="col">발급번호</th>
        <th scope="col">발급일자</th>
        <!--<th scope="col">관리</th>-->
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {

	    $num = number_format($total_count - ($page - 1) * $rows - $i);

	$s_view = '<a href="javascript:GPEN_PRINT('.$row['c_id'].');">출력</a>';
	$s_del = '<a href="./completion_delete.php?'.$qstr.'&w=d&c_id='.$row['c_id'].'" onclick="return delete_confirm(this);">삭제</a>';

        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <input type="hidden" name="c_id[<?php echo $i ?>]" value="<?php echo $row['c_id'] ?>" id="c_id_<?php echo $i ?>">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['name']); ?></label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
        <td class="td_num"><?php echo $num; ?></td>
        <td><?php echo $row['name'] ?>(<?php echo $row['id'] ?>)</a></td>
        <td class="td_tel"><?php echo $row['mobile'] ?></td>
        <td class="td_id"><?php echo $row['corp'] ?></td>
        
        <td class="td_itopt"><?php echo $row['subject'] ?></td>
        <td class="td_datetime"><?php echo $row['schedule'] ?></td>    
        <td class="td_tel"><?php echo $row['cno'] ?></td>
        <td class="td_datetime"><?php echo $row['edate']?></td>
        <!--<td class="td_mngsmall"><?php //echo $s_view?> <?php echo $s_del?></td>-->
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
function GPEN_PRINT(val){ 
	var p = window.open("<?php echo G5_URL?>/complet/c_print.php?c_id="+val,"PrintWin","width=687, height=600, scrollbars=yes"); 
	p.focus(); 
}

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