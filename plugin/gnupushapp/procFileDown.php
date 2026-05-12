<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$reg_id = htmlspecialchars($_POST['reg_id']);
$bo_table = htmlspecialchars($_POST['bo_table']);
$wr_id = htmlspecialchars($_POST['wr_id']);
$no = htmlspecialchars($_POST['no']);
$confirmF = htmlspecialchars($_POST['confirm']);
$masterpassword = htmlspecialchars($_POST['masterpassword']);
$no = (int)$no;


$msg = "다운로드 할 수 있는 권한이 없습니다.";
$url = "none";
$sort_file = 'none';
$filedown_id = 'none';
$filename = 'none';



if($reg_id_s = get_session('reg_id')){

	if($reg_id_s == $reg_id && $str_mp == $masterpassword){

		if($gnu_config['build_sort'] == "A")
		{

			if (!get_session('ss_view_'.$bo_table.'_'.$wr_id)){
				$msg = '잘못된 접근입니다.';
			}else{

				// 다운로드 차감일 때 비회원은 다운로드 불가
				if($board['bo_download_point'] < 0 && $is_guest){
					$msg = '다운로드 권한이 없습니다. 회원이시라면 로그인 후 이용해 보십시오.';
					$url = G5_BBS_URL.'/login.php?wr_id='.$wr_id.'&amp;'.$qstr.'&amp;url='.urlencode(G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&amp;wr_id='.$wr_id);
					$sort_file = "url";
				}else{
					
					$sql = " select bf_source, bf_file from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$no' ";
					$file = sql_fetch($sql);
					if (!$file['bf_file']){
						$msg = '파일 정보가 존재하지 않습니다.';
					}else{

						// JavaScript 불가일 때
						if($confirmF != 'on' && $board['bo_download_point'] < 0) {
							$msg = $file['bf_source'].' 파일을 다운로드 하시면 포인트가 차감('.number_format($board['bo_download_point']).'점)됩니다. 포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다. 그래도 다운로드 하시겠습니까?';
							$sort_file = 'confirm';
						}else{

							$is_manager = apms_admin($xp['xp_manager']);

							if (!$is_manager && $member['mb_level'] < $board['bo_download_level']) {

								if ($member['mb_id']){
									$msg = '다운로드 권한이 없습니다.';
								}else{
									$msg = '다운로드 권한이 없습니다. 회원이시라면 로그인 후 이용해 보십시오.';
									$url = G5_BBS_URL.'/login.php?wr_id='.$wr_id.'&amp;'.$qstr.'&amp;url='.urlencode(G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&amp;wr_id='.$wr_id);
									$sort_file = "url";
								}
							}else{

								$filepath = G5_DATA_PATH.'/file/'.$bo_table.'/'.$file['bf_file'];
								$filepath = addslashes($filepath);
								if (!is_file($filepath) || !file_exists($filepath)){
									$msg = '파일이 존재하지 않습니다.';
								}else{

									$error = false;

									// 사용자 코드 실행
									$is_apms_download = true;
									$ss_name = 'ss_down_'.$bo_table.'_'.$wr_id;

									// 이미 다운로드 받은 파일인지를 검사한 후 게시물당 한번만 포인트를 차감하도록 수정
									if (!get_session($ss_name) && $is_apms_download) {

										// 다운로드 내역확인
										$is_download = true;

										// 자신의 글이라면 통과
										// 관리자인 경우 통과
										if (($write['mb_id'] && $write['mb_id'] == $member['mb_id']) || $is_admin || $is_manager) {
											$is_download = false;

										} else if ($board['bo_download_level'] >= 1) { // 회원이상 다운로드가 가능하다면

											if($member['mb_id'] && $board['bo_download_point'] < 0) {
												$row = sql_fetch(" select count(*) as cnt from {$g5['point_table']} where mb_id = '{$member['mb_id']}' and po_rel_table = '$bo_table' and po_rel_id = '$wr_id' and po_rel_action = '다운로드' ");
												if($row['cnt']) {
													$is_download = false;
												}
											}

											if ($is_download) {

												// 다운로드 포인트가 음수이고 회원의 포인트가 0 이거나 작다면
												if ($member['mb_point'] + $board['bo_download_point'] < 0) {
													$msg = '보유하신 포인트('.number_format($member['mb_point']).')가 없거나 모자라서 다운로드('.number_format($board['bo_download_point']).')가 불가합니다. 포인트를 적립하신 후 다시 다운로드 해 주십시오.';
													$error = true;
												}

												// 게시물당 한번만 차감하도록 수정
												insert_point($member['mb_id'], $board['bo_download_point'], "{$board['bo_subject']} $wr_id 파일 다운로드", $bo_table, $wr_id, "다운로드");
											}
										}

										if($is_download) {
											// 다운로드 카운트 증가
											$sql = " update {$g5['board_file_table']} set bf_download = bf_download + 1 where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$no' ";
											sql_query($sql);
											sql_query(" update {$write_table} set as_download = as_download + 1 where wr_id = '{$wr_id}' ", false);

											// 새글DB 업데이트
											apms_board_new('as_download', $bo_table, $wr_id);
										}

										set_session($ss_name, TRUE);
									}

									if(!$error){
										$filename = $file['bf_source'];
										$ext = substr($filename, -4);
										$ext = strtolower($ext);
										$extImg = in_array($ext, array('.jpg', 'jpeg', '.gif', '.png'));

										if($extImg){
											$sort_file = 'img';
										}else{
											$sort_file = 'download';
										}
										$rnum = get_random_string_gnu(30);
										$filedown_id = date('Ymd') . $rnum;

										sql_query(" INSERT INTO g5_gnupushapp_filedown 
											set ggf_keypass = '$filedown_id',
											ggf_bo_table = '$bo_table',
											ggf_wr_id = '$wr_id',
											ggf_no = '$no',
											ggf_downloadok = 'N',
											ggf_regdate = '".G5_TIME_YMDHIS."'
											", true);

									}

								}
							}
						}
					}
				}
			}

		}else{

			if (!get_session('ss_view_'.$bo_table.'_'.$wr_id)){
				$msg = '잘못된 접근입니다.';
			}else{

				// 다운로드 차감일 때 비회원은 다운로드 불가
				if($board['bo_download_point'] < 0 && $is_guest){
					$msg = '다운로드 권한이 없습니다. 회원이시라면 로그인 후 이용해 보십시오.';
					$url = G5_BBS_URL.'/login.php?wr_id='.$wr_id.'&amp;'.$qstr.'&amp;url='.urlencode(G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&amp;wr_id='.$wr_id);
					$sort_file = "url";
				}else{
					
					$sql = " select bf_source, bf_file from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$no' ";
					$file = sql_fetch($sql);
					if (!$file['bf_file']){
						$msg = '파일 정보가 존재하지 않습니다.';
					}else{

						// JavaScript 불가일 때
						if($confirmF != 'on' && $board['bo_download_point'] < 0) {
							$msg = $file['bf_source'].' 파일을 다운로드 하시면 포인트가 차감('.number_format($board['bo_download_point']).'점)됩니다. 포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다. 그래도 다운로드 하시겠습니까?';
							$sort_file = 'confirm';
						}else{

							if ($member['mb_level'] < $board['bo_download_level']) {

								if ($member['mb_id']){
									$msg = '다운로드 권한이 없습니다.';
								}else{
									$msg = '다운로드 권한이 없습니다. 회원이시라면 로그인 후 이용해 보십시오.';
									$url = G5_BBS_URL.'/login.php?wr_id='.$wr_id.'&amp;'.$qstr.'&amp;url='.urlencode(G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&amp;wr_id='.$wr_id);
									$sort_file = "url";
								}
							}else{

								$filepath = G5_DATA_PATH.'/file/'.$bo_table.'/'.$file['bf_file'];
								$filepath = addslashes($filepath);
								if (!is_file($filepath) || !file_exists($filepath)){
									$msg = '파일이 존재하지 않습니다.';
								}else{

									$error = false;

									$ss_name = 'ss_down_'.$bo_table.'_'.$wr_id;
									if (!get_session($ss_name))
									{
										// 자신의 글이라면 통과
										// 관리자인 경우 통과
										if (($write['mb_id'] && $write['mb_id'] == $member['mb_id']) || $is_admin)
											;
										else if ($board['bo_download_level'] >= 1) // 회원이상 다운로드가 가능하다면
										{
											// 다운로드 포인트가 음수이고 회원의 포인트가 0 이거나 작다면
											if ($member['mb_point'] + $board['bo_download_point'] < 0){
												$error = true;
												$msg = '보유하신 포인트('.number_format($member['mb_point']).')가 없거나 모자라서 다운로드('.number_format($board['bo_download_point']).')가 불가합니다.\\n\\n포인트를 적립하신 후 다시 다운로드 해 주십시오.';
											}

											// 게시물당 한번만 차감하도록 수정
											insert_point($member['mb_id'], $board['bo_download_point'], "{$board['bo_subject']} $wr_id 파일 다운로드", $bo_table, $wr_id, "다운로드");
										}

										// 다운로드 카운트 증가
										$sql = " update {$g5['board_file_table']} set bf_download = bf_download + 1 where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$no' ";
										sql_query($sql);

										set_session($ss_name, TRUE);
									}

									if(!$error){
										$filename = $file['bf_source'];
										$ext = substr($filename, -4);
										$ext = strtolower($ext);
										$extImg = in_array($ext, array('.jpg', 'jpeg', '.gif', '.png'));

										if($extImg){
											$sort_file = 'img';
										}else{
											$sort_file = 'download';
										}
										$rnum = get_random_string_gnu(30);
										$filedown_id = date('Ymd') . $rnum;

										sql_query(" INSERT INTO g5_gnupushapp_filedown 
											set ggf_keypass = '$filedown_id',
											ggf_bo_table = '$bo_table',
											ggf_wr_id = '$wr_id',
											ggf_no = '$no',
											ggf_downloadok = 'N',
											ggf_regdate = '".G5_TIME_YMDHIS."'
											", true);

									}

								}
							}
						}
					}
				}
			}




		}




	}

}

$array = array("msg" => $msg, "url" => $url, "sort_file" => $sort_file, "filedown_id" => $filedown_id, "filename" => $filename);

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;

exit();

?>