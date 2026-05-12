<?php
include_once('../common.php');
include_once(G5_LIB_PATH.'/gnupushapp.lib.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$gnu_config = get_gnupushapp_config();

$pushstyle = "normal";
$image_src = "none";

if($gnu_config['push_style'] == "Y")
{
	$pushstyle = "big_text";
}

$file_info = $_FILES['push_img'];
$rando = get_random_string_gnu('5');

if($file_info['tmp_name'] && is_uploaded_file($file_info['tmp_name']))
{
	@mkdir(G5_DATA_PATH."/gnupushapp", G5_DIR_PERMISSION);
	@chmod(G5_DATA_PATH."/gnupushapp", G5_DIR_PERMISSION);
	$file_ext_array = explode(".",$file_info['name']);
	$count_file = count($file_ext_array)-1;
	$file_ext = $file_ext_array[$count_file];
	$dest_path = G5_DATA_PATH."/gnupushapp/".$rando.".".$file_ext;

	if(@move_uploaded_file($file_info['tmp_name'], $dest_path))
	{
		$pushstyle = "big_picture";
		$image_src = G5_DATA_URL."/gnupushapp/".$rando.".".$file_ext;
	}

}	

$address = $_POST['push_link'];
if(!$address) $address = G5_URL;
$data = array( "title" => $_POST['push_title'], "content" => $_POST['push_content'], "address" => $address, "level" => $_POST['level'], "m_page" => 0, "pushstyle" => $pushstyle, "image_src" => $image_src, "use_marketing" => $_POST['use_marketing'], "effect" => $_POST['effect'], "from" => "group");

if($gnu_config['push_m'] != 'S')
{
	$gp_target_url = $address;
	$gp_target_title = $_POST['push_title'];
	gnu_send_socket($data, '그룹별푸시알림', 'group', $gp_target_url, $gp_target_title);
}else{
	sync_proc_push('group',$data);
}


goto_url('push_group.php', false);

?>
