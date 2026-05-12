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

//// 그누보드  익명닉네임  추가사항
$query = "
	select count(*) as cnt from g5_srd_pushmsg  
	where mb_id = '{$member['mb_id']}' and (msg_check != 'd' and msg_check = 'n') 
";
$result = sql_fetch ($query);
$msg_count = $result['cnt'];
//$msg_count = 0;

echo "<input type='hidden' id='msg_count' value='{$msg_count}' />";

$query = "
	select * from g5_srd_pushmsg  
	where mb_id = '{$member['mb_id']}' and (msg_check != 'd' and msg_check = 'n') order by msg_id desc limit 0,5
";
$result = sql_query($query);

for ($i=0; $row=sql_fetch_array($result); $i++) {
?>
	<dd class="comment">
	<?php if($row['msg_link'] == "http://m.sjjc.or.kr" || $row['msg_link'] == "") { ?>
	<?php echo $row['msg_subject']?>
	<span class="arm_time"><?php echo srd_date_return($row['msg_wdate'])?></span>    
    <?php }else{?>
    <a href="javascript:msg_link('<? echo $row['msg_link']?>','<?php echo $row['msg_type']?>','<?php echo $row['msg_id']?>');" class="redirect_link">
	<?php echo $row['msg_subject']?>
	<span class="arm_time"><?php echo srd_date_return($row['msg_wdate'])?></span></a>
    <?php }?>

	<a href="javascript:msg_del('<?php echo $row['msg_id']?>')" class="arm_del"><img src="<?php echo G5_URL; ?>/plugin/srd-pushmsg/images/ico_del.gif" alt="알림읽음"></a>
	</dd>
<?php //알림이 없을경우
} if ($i == 0) {
?>
	<dd id="arm_empty">
	새로운 알림이 없습니다
	</dd>
<?php
} //for end
?>
	<dd id="arm_all"><a href="<?php echo G5_URL; ?>/plugin/srd-pushmsg/">모두보기</a></dd>
	</dl>