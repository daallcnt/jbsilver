<?php
include_once('./_common.php');

$bf_no = htmlspecialchars($_REQUEST['bf_no']);

if($session_r_num = get_session('gnupushapp_file_up') && $bo_table = get_session('gnupushapp_file_bo_table')) {

	$gnupushapp_file_wr_id = get_session('gnupushapp_file_wr_id');

	$query = "select count(*) as cnt from {$g5['board_file_table']} 
								where bo_table = '{$bo_table}'
                                and wr_id = '{$gnupushapp_file_wr_id}'
                                and bf_no = '{$bf_no}' ";
	$row = sql_fetch($query);

	// DB가 있으면 그대로 진행
	if($row['cnt']>0){

		$query = "select bf_file from {$g5['board_file_table']} 
								where bo_table = '{$bo_table}'
                                and wr_id = '{$gnupushapp_file_wr_id}'
                                and bf_no = '{$bf_no}' ";
		$row = sql_fetch($query);

		@unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row['bf_file']);
		// 이미지파일이면 썸네일삭제
		if(preg_match("/\.({$config['cf_image_extension']})$/i", $row['bf_file'])) {
			delete_board_thumbnail($bo_table, $row['bf_file']);
		}
		
		

		$sql = " update {$g5['board_file_table']}
							set bf_source = '',
								 bf_file = '',
								 bf_content = '',
								 bf_download = 0,
								 bf_filesize = 0,
								 bf_width = 0,
								 bf_height = 0,
								 bf_type = 0,
								 bf_rstring = 'none'
						  where bo_table = '{$bo_table}'
									and wr_id = '{$gnupushapp_file_wr_id}'
									and bf_no = '{$bf_no}' ";
		sql_query($sql); 




	}else{
	// DB값이 없고 수정 작업의 기존 파일일 경우는 기존 wr_id로 찾아서 삭제

		$w = get_session('gnupushapp_file_w');
		$wr_id = get_session('gnupushapp_file_original_wr_id');
		if($w == 'u'){

			$query = "select bf_file from {$g5['board_file_table']} 
									where bo_table = '{$bo_table}'
									and wr_id = '{$wr_id}'
									and bf_no = '{$bf_no}' ";
			$row = sql_fetch($query);

			@unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row['bf_file']);
			// 이미지파일이면 썸네일삭제
			if(preg_match("/\.({$config['cf_image_extension']})$/i", $row['bf_file'])) {
				delete_board_thumbnail($bo_table, $row['bf_file']);
			}
			
			

			$sql = " update {$g5['board_file_table']}
								set bf_source = '',
									 bf_file = '',
									 bf_content = '',
									 bf_download = 0,
									 bf_filesize = 0,
									 bf_width = 0,
									 bf_height = 0,
									 bf_type = 0,
									 bf_rstring = 'none'
							  where bo_table = '{$bo_table}'
										and wr_id = '{$wr_id}'
										and bf_no = '{$bf_no}' ";
			sql_query($sql);

		}

	}

?>
<script>

	parent.del_re('<?php echo $bf_no; ?>');

</script>

<?php } ?>