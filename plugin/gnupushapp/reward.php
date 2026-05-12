<?php

if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 

$secretnum = get_random_string_gnu(75);

$gnu_config = get_gnupushapp_config();

$limit_ok = "false";


if($is_member)
{
	$my_id = $_SESSION['ss_mb_id'];

	if($gnu_config['reward_limit'] != 0)
	{
		$row_tmp_reward = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_reward where grr_regdate > date_format(now() , '%Y-%m-%d 00:00:00') and grr_mb_id = '{$my_id}' ");

		if($row_tmp_reward['cnt'] < $gnu_config['reward_limit'])
		{
			$limit_ok = "true";						
		}
	}
	else
	{
		$limit_ok = "true";
	}
}



if($gnu_config['reward_type'] == 'point' && $limit_ok == "true")
{
	set_session('reward_secret', $secretnum);
}
else
{
	set_session('reward_secret', '');
}

?>


<style>
	.article-title .reward {
		text-align: center;
	}
	.article-title .reward .btn {
		background: #3498db;
		background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
		background-image: -moz-linear-gradient(top, #3498db, #2980b9);
		background-image: -ms-linear-gradient(top, #3498db, #2980b9);
		background-image: -o-linear-gradient(top, #3498db, #2980b9);
		background-image: linear-gradient(to bottom, #3498db, #2980b9);
		-webkit-border-radius: 28;
		-moz-border-radius: 28;
		border-radius: 28px;
		color: #ffffff;
		font-size: 12px;
		padding: 10px 20px 10px 20px;
		text-decoration: none;
		outline:none;
	}

	.article-title  .reward .btn:hover {
		background: #3cb0fd;
		background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
		background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
		background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
		background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
		background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
		text-decoration: none;
	}

</style>

<div class="article-title" style="padding-top:0px;"><div class="reward"><button type="button" class="btn" onclick="clickbutton(); return false;">리워드광고</button></div>

<?php if(!preg_match("/GNUPUSH/", $_SERVER['HTTP_USER_AGENT']) || !$_SESSION['reg_id']){  ?>
<script language="JavaScript">
function clickbutton(){
	alert("앱에서만 동작합니다.");
}
</script>
<?php }elseif($gnu_config['reward_type'] == 'point' && $limit_ok == "true") { ?>
<script language="JavaScript">
function clickbutton(){
	<?php if(preg_match("/GNUPUSHIPHONE/", $_SERVER['HTTP_USER_AGENT'])){ ?>
		window.webkit.messageHandlers.callbackHandler.postMessage('reward?reward_secret=<?php echo $secretnum; ?>');
	<?php }else{ ?>		
		window.myJs.rewardShow("<?php echo $secretnum; ?>");
	<?php } ?>
}
</script>
<?php }elseif($is_member && $gnu_config['reward_type'] == 'point' && $limit_ok == "false") { ?>
<script language="JavaScript">
function clickbutton(){
	alert("리워드광고 1일 제한 횟수를 초과하였습니다(<?php echo $gnu_config['reward_limit']; ?>회).");
}
</script>
<?php }elseif(!$is_member && $gnu_config['reward_type'] == 'point'){ ?>
<script language="JavaScript">
function clickbutton(){
	alert("로그인이 필요합니다.");
}
</script>
<?php }else{ ?>
<script language="JavaScript">
function clickbutton(){
	alert("잘못된 요청입니다.");
}
</script>
<?php } ?>
