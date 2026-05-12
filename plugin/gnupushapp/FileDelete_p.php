<?php
include_once('./_common.php');

if($mb_id = get_session('gnupushapp_file_mb_id')) {

	$session_r_num = get_session('gnupushapp_file_up');

	if($session_r_num == $mb_id){
		$mb_icon_path = G5_DATA_PATH.'/member/gnupushpf/'.$session_r_num.'.gif';
	}else{
		$gnu_config = get_gnupushapp_config();
		if($gnu_config['build_sort'] == 'A')
		{
			$mb_icon_path = G5_DATA_PATH.'/apms/photo/'.substr($mb_id,0,2).'/'.$mb_id.'.jpg';
		}else{
			$mb_icon_path = G5_DATA_PATH.'/member/'.substr($mb_id,0,2).'/'.$mb_id.'.gif';
		}
	}

	if(file_exists($mb_icon_path)){
		@unlink($mb_icon_path);
	}

?>
<script>

	parent.del_re();

</script>

<?php } ?>