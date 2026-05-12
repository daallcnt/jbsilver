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

if($_SESSION['ss_mb_id'] && $msg_id){
	$mem_id = $_SESSION['ss_mb_id'];

$query = " delete from g5_srd_pushmsg where mb_id = '$mem_id' and msg_id = '{$msg_id}' ";
sql_query ($query);

?>

<script>
	var href = "<?php echo G5_URL; ?>/plugin/srd-pushmsg/index.php?page=<?php echo $page?>";
	document.location.replace(href);
</script>

<?php 
}
?>