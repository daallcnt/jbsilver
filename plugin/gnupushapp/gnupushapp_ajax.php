<?php
$querygnu = "select count(*) as 'cnt' from g5_gnupushapp_push where gp_issend = 'N'";
$rowgnu = sql_fetch($querygnu);
if($rowgnu['cnt'] > 0){
	$queryddd = "select gp_pushid from g5_gnupushapp_push where gp_issend = 'N'";
	$devices_select = sql_query($queryddd);

	for ($if=0; $rowdsd=sql_fetch_array($devices_select); $if++) {

?>

<script>
$(function(){

	var url = "<?php echo G5_PLUGIN_URL;?>/gnupushapp/procPush.php";
	var keypass_<?php echo $if;?> = "<?php echo $rowdsd['gp_pushid'];?>";
	$.post( url, { data: keypass_<?php echo $if;?>} );

});
</script>

<?php


	}

}

?>

<!--push num <?php echo $rowgnu['cnt'];?>-->