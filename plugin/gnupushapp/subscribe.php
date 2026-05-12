<?php

if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 

$sub_re = "nouse";

if($bo_table) $sub_re = get_my_gnupushspp_subscribe($bo_table);

$onoff = $sub_re;

if($sub_re == "grant" || $sub_re == "login") $onoff = "off";

if($sub_re != "nouse"){

?>
<style>
.rate {
    display: inline-block;
    position: relative;
}
.rate:not(:checked) > input {
    position:absolute;
    top:-9999px;
}
.rate:not(:checked) > label {
    float:right;
    width:1em;
    overflow:hidden;
    white-space:nowrap;
    cursor:pointer;
    font-size:30px;
    color:#ccc;
}
.rate:not(:checked) > label:before {
    content: '★ ';
}
.rate > input:checked ~ label {
    color: #ffc700;    
}
</style>
<div class="rate">
    <input type="checkbox" id="star5" name="rate" value="5" <?php if($onoff=="on"){ ?> checked <?php } ?> onClick="ajaxinapp();"/>
    <label for="star5" title="text">5 stars</label>
</div>

<script language="JavaScript">

var gnupushapp_onoff_s = "<?php echo $onoff; ?>";
function ajaxinapp(){

	<?php if($sub_re == "grant") { ?>

		alert("구독할 수 있는 권한이 없습니다.");
		$("#star5").prop('checked', false);

	<?php }else if($sub_re == "login") { ?>

		alert("구독기능은 로그인 후 사용하실 수 있습니다.");
		$("#star5").prop('checked', false);

	<?php }else if($sub_re == "none") { ?>

		alert("구독기능은 앱에서만 사용가능합니다.");
		$("#star5").prop('checked', false);

	<?php }else{ ?>
	var url = "<?php echo G5_PLUGIN_URL;?>/gnupushapp/procSaveSettingSubscribe.php";
	if(gnupushapp_onoff_s == "on"){
		gnupushapp_onoff_s = "off";
	}else{
		gnupushapp_onoff_s = "on";
	}

	$.post( url, { bo_table: "<?php echo $bo_table; ?>", onoff: gnupushapp_onoff_s} )
		.done(function( data ) {
			if(data == "off_ok" || data == "on_ok"){
				
			}
			if(data == "limit_grant"){
				alert("구독할 수 있는 권한이 없습니다.");
				$("#star5").prop('checked', false);
				gnupushapp_onoff_s = "off";
			}
			if(data == "regid_fail"){
				alert("등록된 기기가 없습니다. 관리자에게 문의하여 주세요.");
				$("#star5").prop('checked', false);
				gnupushapp_onoff_s = "off";
			}
		});

	<?php } ?>
}
</script>
<?php } ?>
