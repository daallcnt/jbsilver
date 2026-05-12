<?php
$sub_menu = '643100';
include_once('./_common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

auth_check($auth[$sub_menu], "w");

include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_EDITOR_LIB);
?>
<br><br>
<a href="<?php echo G5_URL;?>/gnupushapp_admin/index.php" target="_blank">새창보기</a>

<script>
document.location.href= "<?php echo G5_URL;?>/gnupushapp_admin/index.php";
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
2016-01-04