<?php
include_once('./_common.php');


	$login_action_url = G5_PLUGIN_URL."/gnupushapp/login_check.php";
	$login_url = G5_PLUGIN_URL."/gnupushapp/login_ok.php";

?>

<script>

window.myJs_reg.callAndroid_start("ok");

function submit(a,b){
	document.getElementById('login_id').value = a;
	document.getElementById('login_pw').value = b;
	document.flogin.submit();
	return 'ok';

}
</script>

    <form name="flogin" action="<?php echo $login_action_url ?>" method="post">
    <input type="hidden" name="url" value="<?php echo $login_url ?>">
	<input type="text" name="mb_id" id="login_id" required hidden>
	<input type="password" name="mb_password" id="login_pw" required hidden>
	<input type="submit" hidden value="로그인">
	<input type="checkbox" hidden name="auto_login">    
    </form>

