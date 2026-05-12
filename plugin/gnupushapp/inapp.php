<?php

if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 

$secretnum = get_random_string_gnu(75);

$gnu_config = get_gnupushapp_config();

if($is_member)
{
	set_session('secret_inapp', $secretnum);

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

<div class="article-title" style="padding-top:0px;"><div class="reward">
<button type="button" class="btn" onclick="clickbutton('consume', 'inapptest_1100', '1100');">인앱결제 1100원 포인트 1100구매하기</button></div>
<?php
// clickbutton('a','b','c');
// a: type -> consume(point와 같이 소비되어 재구매해야 하는 상품), noconsume(1회 구매하면 다시 구매할 수 없고 소진되지 않는 상품), subscription(구독상품)
// b: product_id -> 상품ID
// c: 상품금액(숫자만)
?>
<script language="JavaScript">
var now_purchase = false;
function clickbutton(a,b,c){	

	if(!now_purchase){

		<?php if(!preg_match("/GNUPUSH/", $_SERVER['HTTP_USER_AGENT']) || !$_SESSION['reg_id']){  ?>
			alert("앱에서만 결제하실 수 있습니다.");
		<?php }else if($is_member) { ?>

			now_purchase = true;			
			ajaxinapp(a, b, c, '<?php echo $_SESSION["reg_id"];?>');

		<?php }else{ ?>
			loginplz();
		<?php } ?>

	}else{
		alert("현재 결제중입니다. 기다려주세요.");
	}

}
function ajaxinapp(a,b,c,d){
	var url = "<?php echo G5_PLUGIN_URL;?>/gnupushapp/procIAPStart.php";
	$.post( url, { type: a, product_id: b, money: c, regid: d} )
		.done(function( data ) {
			if(data == "ok"){
				goPurchase(a,b);
			}else{
				alert("새로고침하여 다시 결제해주세요. 계속 이 메시지가 나올 경우 관리자에게 문의해주세요.");
			}

		});
}
function goPurchase(a,b){
	<?php if(preg_match("/GNUPUSHIPHONE/", $_SERVER['HTTP_USER_AGENT'])){ ?>
		window.webkit.messageHandlers.callbackHandler.postMessage('inapp?type='+a+'&product_id='+b+'&pass=<?php echo $secretnum; ?>');
	<?php }else{ ?>		
		window.myJs.callAndroid_inapp(a, b, '<?php echo $secretnum; ?>');
	<?php } ?>
}
function loginplz(){
	alert("로그인이 필요합니다.");
}
</script>

