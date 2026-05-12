<?php
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

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
// 스타일은 그누보드 알림에서 뽀려옴 -_-;;; 만들기 귀찮음

$query = "
	select count(*) as cnt from g5_srd_pushmsg  
	where mb_id = '{$member['mb_id']}' and (msg_check != 'd' and msg_check = 'n') 
";

$result = sql_fetch ($query);

// 그누보드  익명닉네임  추가사항
if ($result['cnt'] > 1) $armbg = 'arm1';
elseif ($result['cnt'] == 0) $armbg = 'arm0';

$msg_count = $result['cnt'];
?>

<link rel="stylesheet" href="<?php echo G5_URL; ?>/plugin/srd-pushmsg/style.css">

<div id="tnb">
<ul>
<li id="ol_arm">알림 <span class="tnb_nb nb_nb_new <?php echo $armbg ?>" id="arm_cnt"><?php echo $msg_count?></span><dl></dl>	<dl id="dd_arm" style="display: none;">
<!-- ajax return -->
<?php
//include_once (G5_PATH.'/plugin/srd-pushmsg/ajax.list_pushmsg.php');
?>
<!-- // ajax return -->	
</li>
</ul>
</div>
<script>
//알림글을 ajax로 불러온다
function ajax_msgload () {
	$.post("<?php echo G5_URL; ?>/plugin/srd-pushmsg/ajax.list_pushmsg.php",
		{},
		function(data) {
			$('#dd_arm').html('');
			$('#dd_arm').append(data);
				$('#arm_cnt').html($('#msg_count').val());	
		}
	);					
}

//해당글을 읽음처리이후 링크이동
function msg_link (msg_link, msg_type, msg_id){	
	var blank = false;
	if (msg_type == 'memo') {
		blank = true;
	}
	$.post("<?php echo G5_URL; ?>/plugin/srd-pushmsg/ajax.read_pushmsg.php",
	{   
		'g_ids':msg_id
	},
		function(data) {
			if(blank){
				win_memo( msg_link );
			} else {
				document.location.href = msg_link;						
			}
		}
	);	
}

//선택글 삭제 (목록에서 삭제이기 때문에 읽음표시로 처리됨)
function msg_del (msg_id){	
	$.post("<?php echo G5_URL; ?>/plugin/srd-pushmsg/ajax.read_pushmsg.php",
	{   
		'g_ids':msg_id
	},
		function(data) {
			ajax_msgload();
			$("#dd_arm").css("z-index:100000");			
			$("#dd_arm").show();			
		}
	);	
}

(function($){		
	$("#ol_arm").on("click",function(e){
		var show = $("#dd_arm").css('display');
		if (show == 'none') {
			$("#dd_arm").css("z-index:100000");
			ajax_msgload();
			$("#dd_arm").show();
		} else {
			$("#dd_arm").hide();
		}
	});
})(jQuery);
</script>