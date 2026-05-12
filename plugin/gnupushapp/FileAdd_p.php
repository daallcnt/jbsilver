<?php
include_once('./_common.php');

$gnupushapp_img_src = get_session('gnupushapp_file_img_src');

?>
<script>

	parent.aftermobileFileUpload('<?php echo $gnupushapp_img_src ?>');

</script>