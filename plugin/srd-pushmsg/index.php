<?php
include_once('./_common.php');

/*
	프로그램 : srd_pushmsg 
	그누보드5의 알림서비스 플러그인
	ver . beta 0.1
	개발자 : salrido@korea.com
	그누보드 : rido
	개발일 : 2015 05 29
	- 세상만사 다 귀찮다 -_- 킁 먹고살기 힘들다.
	- 소스 수정 / 사용은 알아서들 하시고 재배포 및 소스포함시 저작권만 유지해주세요 
	- 수정시 수정사항을 메일로 피드백 해주시면 감사하겠습니다.
*/
$g5['title']  = '받은메시지함';

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
// 스타일은 그누보드 알림에서 뽀려옴 -_-;;; 만들기 귀찮음

$del_day = 60;

if($_SESSION['ss_mb_id']){
	include_once(G5_PATH.'/_head.php');
	$mem_id = $_SESSION['ss_mb_id'];

//60일 지난 메시지는 모두 자동삭제
srd_pushmsg_del($del_day);

if ($read) {
	$where  = " and msg_check = '{$read}'";
}

$sql_count = " select count(*) as cnt from g5_srd_pushmsg where mb_id = '{$mem_id}' {$where} and  msg_check != 'd'  ";
$row = sql_fetch($sql_count);
$total_count = $row['cnt'];

$rows = 10;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = "select * from g5_srd_pushmsg where mb_id = '{$mem_id}' and msg_check != 'd' {$where}  order by msg_id desc limit {$from_record}, {$rows}";
$result = sql_query($sql);

add_stylesheet('<link rel="stylesheet" href="'.G5_URL.'/plugin/srd-pushmsg/style.css">', 0);


?>

<div id="">
<br>
<div style="margin:0 10px;">
    <p>총 <?php echo $total_count?> 건, 알림 보관 기간은 <?php echo $del_day?>일입니다.</p><br>
</div>
<div style="margin:0 10px;">
<form name="fnewlist" method="post" id="sir_armv" action="#" onsubmit="return fnew_submit(this);" autocomplete="off">
<input type="hidden" name="read" value="">
<input type="hidden" name="page" value="<?php echo $page?>">
<input type="hidden" name="pressed" value="">
<input type="hidden" name="p_type" value="">

<div class="sir_bw02 sir_bw">
    <!-- <label for="all_chk" class="sir_sr">목록 전체</label>
    <input type="checkbox" id="all_chk"> -->
    <button type="button" class="all_chk sir_b01_adm">전체선택</button>
    <input type="submit" value="선택삭제" class="sir_b01_adm" data-type="del">
    <!--<input type="submit" value="읽음표시" class="sir_b01_adm" data-type="read">
	<button type="button" class="sir_b01_adm" id="allread" style="background-color:red;">일괄확인</button-->
</div>

<!--div class="sir_bw03 sir_bw">
    <button type="button" id="armv_all" class="sir_b01">전체보기</button>
    <button type="button" id="armv_read" class="sir_b01">읽은알림</button>
    <button type="button" id="armv_yet" class="sir_b01">안읽은알림</button>
</div-->
<div style="clear:both;"></div><br>

<table cellpadding=0 cellspacing=0 border=0 style="width:100%">
<tr height="1" bgcolor="#e9e9e9"><td colspan=3></td></tr>
<?php
while ($row = sql_fetch_array($result)) {
?>
<tr style="padding-top:10px;padding-bottom:10px;">
<td valign="middle" align="center" width="35"><input type="checkbox" name="chk_bn_id[]" value="<?php echo $row['msg_id']?>"></td>
<td style="white-space:break-all;">
<?php
if ($row['msg_check'] == 'y') {
	$check_class = 'list_read';
} else {
	$check_class = '';
}
?>
<?php if($row['msg_link'] == "http://m.jbsilver.net" || $row['msg_link'] == "") { ?>
	<span class="<?php echo $check_class?> list_link">
    (<?php echo srd_date_return($row['msg_wdate'])?>) <?php if ($row['msg_check'] != 'y') { ?><font color="red"><b><?php } ?><?php echo nl2br($row['msg_subject'])?><?php if ($row['msg_check'] != 'y') { ?></font></b><?php } ?>
    </span>
<?php }else{?>
<a href="#" onclick="go_push('<?php echo $row['msg_id']; ?>', '<?php echo $row['msg_check']; ?>','<?php echo $row['msg_link']; ?>','<?php echo $row['msg_type']; ?>');" class="<?php echo $check_class?> list_link">
	(<?php echo srd_date_return($row['msg_wdate'])?>) <?php if ($row['msg_check'] != 'y') { ?><font color="red"><b><?php } ?><?php echo $row['msg_subject']?><?php if ($row['msg_check'] != 'y') { ?></font></b><?php } ?>
</a>
<?php }?>
</td>
<td valign="middle" align="center" width="40"><a href="javascript:deletethis('<?php echo $row['msg_id']?>');" style="padding:0 2px 2px 2px;background:#ff4f91"><img src="<?php echo G5_URL; ?>/plugin/srd-pushmsg/images/ico_del.gif" alt="알림삭제"></a></td>
</tr>
<tr height="1" bgcolor="#e9e9e9"><td colspan=3></td></tr>

<?php  } // row end?>

	<? if($total_count == 0) {?>
	<tr height="60" valign="middle" align="center"><td colspan=3>새로운 알림이 없습니다.</td></tr>
	<tr height="1" bgcolor="#e9e9e9"><td colspan=3></td></tr>
	<? } ?>

</table>

<br><br>
<div class="sir_bw02 sir_bw">
    <button type="button" class="all_chk sir_b01_adm">전체선택</button>
    <input type="submit" value="선택삭제" class="sir_b01_adm" data-type="del">
    <input type="submit" value="읽음표시" class="sir_b01_adm" data-type="read">
</div>

</form>
</div>
<br><br>
<center><?php echo get_paging("10", $page, $total_page, "?$qstr&amp;page="); ?></center>


<br><br><br>


<script>
/* sir에서 스크립트도 그냥 긁어다가 쓰고있음 차후 에러 발생시 수정 */

function go_push(msg_id,msg_check,href,sort){

	if(msg_check != "y"){
		document.location.href = "<?php echo G5_URL; ?>/plugin/srd-pushmsg/read_pushmsg.php?msg_id=" + msg_id;
	}else{
		if(sort == "memo"){
            win_memo(href, '');
		}else{
			document.location.href = href;
		}
	}
}

function deletethis(msg_id){

	//if (confirm("선택한 알림을 정말 삭제 하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다")) {
		document.location.href = "<?php echo G5_URL; ?>/plugin/srd-pushmsg/delete_pushmsg.php?msg_id=" + msg_id;
	//}
}

(function($){
    $('.all_chk').bind("click", function(){
        if (!$(this).data('toggle_enable')) { 
            $(this).data('toggle_enable', true); 
        } else { 
            $(this).data('toggle_enable', false);
        } 
        $('[name="chk_bn_id[]"]').attr('checked', $(this).data('toggle_enable') );
    });

	$('#allread').bind('click', function(e){ //일괄확인 클릭
        var url = "<?php echo G5_URL; ?>/plugin/srd-pushmsg/readall_pushmsg.php";
        document.location.href = url;
	});

    $('#armv_all').bind('click', function(e){ //전체보기 클릭
        var url = "<?php echo G5_URL; ?>/plugin/srd-pushmsg/index.php?page=1";
        document.location.href = url;
    });
    $('#armv_read').bind('click', function(e){ //읽은 알림 클릭
        var url = "<?php echo G5_URL; ?>/plugin/srd-pushmsg/index.php?page=1&read=y";
        document.location.href = url;
    });
    $('#armv_yet').bind('click', function(e){ //안 읽은 알림 클릭
        var url = "<?php echo G5_URL; ?>/plugin/srd-pushmsg/index.php?page=1&read=n";
        document.location.href = url;
    });

    $("form[name='fnewlist'] input[type='submit']").bind("click", function(e){
        e.preventDefault();
        var p_type = $(this).attr("data-type")
            $form = $("form[name='fnewlist']");
        if( !p_type ){
            return false;
        }
        document.pressed = $(this).val();
        $form.find("input[name='p_type']").val( p_type );
        if( p_type ){
            $form.submit();
        }
    });
})(jQuery);

function fnew_submit(f)
{
    f.pressed.value = document.pressed;

    var cnt = 0;
    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_bn_id[]" && f.elements[i].checked)
            cnt++;
    }

    if (!cnt) {
        alert(document.pressed+"할 알림을 하나 이상 선택하세요.");
        return false;
    }
    if( f.p_type.value == "del" ){
        if (!confirm("선택한 알림을 정말 "+document.pressed+" 하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다")) {
            return false;
        }
    }

    f.action = "<?php echo G5_URL; ?>/plugin/srd-pushmsg/pushmsg_delete.php";

    return true;
}
</script>

</div>
<?php
include_once(G5_PATH.'/_tail.php');
}
?>