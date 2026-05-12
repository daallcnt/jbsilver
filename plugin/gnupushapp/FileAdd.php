<?php
include_once('./_common.php');

$gnupushapp_img_src = get_session('gnupushapp_file_img_src');
$gnupushapp_file_count = get_session('gnupushapp_file_count');
$gnupushapp_file_source = get_session('gnupushapp_file_source');
$gnupushapp_file_size = get_session('gnupushapp_file_size');
$gnupushapp_file_bf_no = get_session('gnupushapp_file_bf_no');

$gnupushapp_file_size = number_format(floor($gnupushapp_file_size / 1000)) . 'KB';

?>
<script>

	parent.aftermobileFileUpload('<?php echo $gnupushapp_file_count ?>','<?php echo $gnupushapp_file_size ?>','<?php echo $gnupushapp_file_source ?>','<?php echo $gnupushapp_img_src ?>','<?php echo $gnupushapp_file_bf_no ?>');

</script>