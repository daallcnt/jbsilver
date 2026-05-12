<?php
include_once('../common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

@mkdir(G5_DATA_PATH."/gnupushapp", G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH."/gnupushapp", G5_DIR_PERMISSION);

/***/
$db_reload = false;


if($_POST['config_sort'] == "push")
{

	if(defined('G5_USE_SHOP') && G5_USE_SHOP && is_array($_POST['push_settings']) && in_array('youngcart', $_POST["push_settings"])){
		$_POST['use_youngcart'] = "Y";
	}else{
		$_POST['use_youngcart'] = "N";
	}

	if(is_array($_POST['push_settings']) && in_array('document', $_POST["push_settings"])){
		$_POST['use_d'] = "Y";
	}else{
		$_POST['use_d'] = "N";
	}

	if(is_array($_POST['push_settings']) && in_array('comment', $_POST["push_settings"])){
		$_POST['use_c'] = "Y";
	}else{
		$_POST['use_c'] = "N";
	}

	if(is_array($_POST['push_settings']) && in_array('message', $_POST["push_settings"])){
		$_POST['use_m'] = "Y";
	}else{
		$_POST['use_m'] = "N";
	}

}

if($_POST['config_sort'] == "etc")
{
	if($_POST['eventimage0_d'] == "DELETE")
	{
		$event0_file_dest = G5_DATA_PATH."/gnupushapp/".$_POST['eventimage0_filename'];
		@unlink($event0_file_dest);
		$image_src = str_replace(G5_URL, G5_PATH, $_POST['eventimage0_filename']);
		@unlink($image_src);
		$_POST['eventimage0_filename'] = "";

	}


	if($_POST['eventimage1_d'] == "DELETE")
	{
		$event1_file_dest = G5_DATA_PATH."/gnupushapp/".$_POST['eventimage1_filename'];
		@unlink($event1_file_dest);
		$image_src = str_replace(G5_URL, G5_PATH, $_POST['eventimage1_filename']);
		@unlink($image_src);
		$_POST['eventimage1_filename'] = "";

	}

	if($_POST['eventimage2_d'] == "DELETE")
	{
		$event2_file_dest = G5_DATA_PATH."/gnupushapp/".$_POST['eventimage2_filename'];
		@unlink($event2_file_dest);
		$image_src = str_replace(G5_URL, G5_PATH, $_POST['eventimage2_filename']);
		@unlink($image_src);
		$_POST['eventimage2_filename'] = "";

	}

	if($_POST['eventimage3_d'] == "DELETE")
	{
		$event3_file_dest = G5_DATA_PATH."/gnupushapp/".$_POST['eventimage3_filename'];
		@unlink($event3_file_dest);
		$image_src = str_replace(G5_URL, G5_PATH, $_POST['eventimage3_filename']);
		@unlink($image_src);
		$_POST['eventimage3_filename'] = "";

	}

	if($_POST['eventimage4_d'] == "DELETE")
	{
		$event4_file_dest = G5_DATA_PATH."/gnupushapp/".$_POST['eventimage4_filename'];
		@unlink($event4_file_dest);
		$image_src = str_replace(G5_URL, G5_PATH, $_POST['eventimage4_filename']);
		@unlink($image_src);
		$_POST['eventimage4_filename'] = "";
	}

	$event0_file_info = $_FILES['eventimage0'];
	$event1_file_info = $_FILES['eventimage1'];
	$event2_file_info = $_FILES['eventimage2'];
	$event3_file_info = $_FILES['eventimage3'];
	$event4_file_info = $_FILES['eventimage4'];

	$rando0 = get_random_string_gnu('5');
	$rando1 = get_random_string_gnu('5');
	$rando2 = get_random_string_gnu('5');
	$rando3 = get_random_string_gnu('5');
	$rando4 = get_random_string_gnu('5');


	if($event0_file_info['name'])
	{
		$event0_file_info['name'] = preg_replace("/\s/",'',$event0_file_info['name']);
		$dest_path = G5_DATA_PATH."/gnupushapp/".$rando0.$event0_file_info['name'];

		if(@move_uploaded_file($event0_file_info['tmp_name'], $dest_path))
		{
			@chmod($dest_path, G5_FILE_PERMISSION);
			$image_src = str_replace(G5_PATH, G5_URL, $dest_path);
			$_POST['eventimage0_filename'] = $image_src;
		}

	}

	if($event1_file_info['name'])
	{
		$event1_file_info['name'] = preg_replace("/\s/",'',$event1_file_info['name']);
		$dest_path = G5_DATA_PATH."/gnupushapp/".$rando1.$event1_file_info['name'];

		if(@move_uploaded_file($event1_file_info['tmp_name'], $dest_path))
		{
			@chmod($dest_path, G5_FILE_PERMISSION);
			$image_src = str_replace(G5_PATH, G5_URL, $dest_path);
			$_POST['eventimage1_filename'] = $image_src;
		}

	}

	if($event2_file_info['name'])
	{
		$event2_file_info['name'] = preg_replace("/\s/",'',$event2_file_info['name']);
		$dest_path = G5_DATA_PATH."/gnupushapp/".$rando2.$event2_file_info['name'];

		if(@move_uploaded_file($event2_file_info['tmp_name'], $dest_path))
		{
			@chmod($dest_path, G5_FILE_PERMISSION);
			$image_src = str_replace(G5_PATH, G5_URL, $dest_path);
			$_POST['eventimage2_filename'] = $image_src;
		}

	}

	if($event3_file_info['name'])
	{
		$event3_file_info['name'] = preg_replace("/\s/",'',$event3_file_info['name']);
		$dest_path = G5_DATA_PATH."/gnupushapp/".$rando3.$event3_file_info['name'];

		if(@move_uploaded_file($event3_file_info['tmp_name'], $dest_path))
		{
			@chmod($dest_path, G5_FILE_PERMISSION);
			$image_src = str_replace(G5_PATH, G5_URL, $dest_path);
			$_POST['eventimage3_filename'] = $image_src;
		}

	}

	if($event4_file_info['name'])
	{
		$event4_file_info['name'] = preg_replace("/\s/",'',$event4_file_info['name']);
		$dest_path = G5_DATA_PATH."/gnupushapp/".$rando4.$event4_file_info['name'];

		if(@move_uploaded_file($event4_file_info['tmp_name'], $dest_path))
		{
			@chmod($dest_path, G5_FILE_PERMISSION);
			$image_src = str_replace(G5_PATH, G5_URL, $dest_path);
			$_POST['eventimage4_filename'] = $image_src;
		}

	}

}

/***/

if($_POST['config_sort'] == "app_basic")
{

	$rando = get_random_string_gnu('5');

	if(!$_POST['loading_file_name']) $_POST['loading_file_name'] = 'none';

	if($_POST['is_loading_file'] == "DELETE")
	{
		@unlink(G5_DATA_PATH."/gnupushapp/{$_POST['loading_file_name']}");
		$_POST['is_loading_file'] = "N";

	}
	elseif($_FILES['loading_file']['name'])
	{
		$dest_path = G5_DATA_PATH."/gnupushapp/".$rando.$_FILES['loading_file']['name'];

		if(@move_uploaded_file($_FILES['loading_file']['tmp_name'], $dest_path))
		{
			@chmod($dest_path, G5_FILE_PERMISSION);
			$_POST['is_loading_file'] = "Y";
			$_POST['loading_file_name'] = $rando . $_FILES['loading_file']['name'];

		}

	}
}

$gnu_config = get_gnupushapp_config();
$gnu_config = stripslashes_deep($gnu_config);

foreach($_POST as $key => $val)
{
	if($key != "config_sort" && $key != "masterpassword2")
	{
		
		$gnu_config[$key] = $val;
	}
}

if($_POST['config_sort'] == "app_basic")
{
	if ($_POST['social']) {
		$gnu_config['social'] = array();
	}
}

if($_POST['config_sort'] == "nb")
{
	if (!$_POST['mem_info']) {
		$gnu_config['mem_info'] = array();
	}
}
if($_POST['config_sort'] == "push")
{
	if (!$_POST['push_settings']) {
		$gnu_config['push_settings'] = array();
	}
	if (!$_POST['headsup_module_srls']) {
		$gnu_config['headsup_module_srls'] = array();
	}
	if (!$_POST['popup_module_srls']) {
		$gnu_config['popup_module_srls'] = array();
	}
}

if($_POST['config_sort'] == "quick")
{

	if (!$_POST['quick_default']) {
		$gnu_config['quick_default'] = array();
	}
}

if($_POST['config_sort'] == "board")
{
	if (!$_POST['no_use_module_srls']) {
		$gnu_config['no_use_module_srls'] = array();
	}
	if (!$_POST['only_admin_push_module_srls']) {
		$gnu_config['only_admin_push_module_srls'] = array();
	}
	if (!$_POST['notice_module_srls']) {
		$gnu_config['notice_module_srls'] = array();
	}
	if (!$_POST['category_module_srls']) {
		$gnu_config['category_module_srls'] = array();
	}
	if (!$_POST['group_module_srls']) {
		$gnu_config['group_module_srls'] = array();
	}
}

$config_json = base64_encode(serialize($gnu_config));


$sql = " update g5_gnupushapp_config
            set gc_text            = '{$config_json}',
                gc_reg_date           = '".G5_TIME_YMDHIS."'
                where gc_ix = '1'
            ";
sql_query($sql);

if($db_reload){
	alert("DB를 갱신합니다.", 'setting_' . $_POST['config_sort'] . '.php');
}else{
	goto_url('setting_' . $_POST['config_sort'] . '.php', false);
}
?>
