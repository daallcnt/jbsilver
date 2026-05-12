<?php
include_once('./_common.php');
?>

<!-- <?php echo $_SERVER['HTTP_USER_AGENT'];?> -->

<?php
if(preg_match("/GNUPUSH/", $_SERVER['HTTP_USER_AGENT'])) {

?>

<script>
function regin_submit(a,b,c,d,e){
	document.getElementById('reg_id_regin').value = a;
	document.getElementById('version_regin').value = b;
	document.getElementById('masterpassword_regin').value = c;
	document.getElementById('phoneinfo_regin').value = d;
	document.getElementById('sort_regin').value = e;
	document.RegIn.submit();
	return 'ok';
}
</script>

<form action="./procRegIn.php" name="RegIn" method="post" class="ff">
	<input type="password" hidden id="reg_id_regin" name="reg_id" value="" />
	<input type="hidden" id="version_regin" name="version" value=""/>
	<input type="password" id="masterpassword_regin" hidden name="masterpassword" value="" />
	<input type="hidden" id="phoneinfo_regin" name="phoneinfo" value="" />
	<input type="hidden" id="sort_regin" name="sort" value="" />
</form>


<!--*******************************************************************************************-->


<script>
function logininfo_submit(a){
	document.getElementById('masterpassword_logininfo').value = a;
	document.LoginInfo.submit();
	return 'ok';
}
</script>

<form action="./procCheckLogin.php" name="LoginInfo" method="post" class="ff">
	<input type="password" id="masterpassword_logininfo" hidden name="masterpassword" value="" />
</form>


<!--*******************************************************************************************-->


<script>
function downmid_submit(a,b){
	document.getElementById('quick_bo_table_downmid').value = a;
	document.getElementById('quick_wr_id_downmid').value = b;
	document.DownMid.submit();
	return 'ok';
}
</script>

<form action="./downMid.php" name="DownMid" method="post" class="ff">
	<input type="hidden" id="quick_bo_table_downmid" name="quick_bo_table" value="" />
	<input type="hidden" id="quick_wr_id_downmid" name="quick_wr_id" value=""/>
</form>



<!--*******************************************************************************************-->


<script>
function syncout_submit(a,b){
	document.getElementById('reg_id_syncout').value = a;
	document.getElementById('masterpassword_syncout').value = b;
	document.SyncOut.submit();
	return 'ok';
}
</script>

<form action="./procSyncOut.php" name="SyncOut" method="post" class="ff">
	<input type="hidden" id="reg_id_syncout" name="reg_id" value=""/>
	<input type="hidden" id="masterpassword_syncout" name="masterpassword" value=""/>
</form>


<!--*******************************************************************************************-->

<script>
function downsetting_submit(a,b){
	document.getElementById('reg_id_downsetting').value = a;
	document.getElementById('masterpassword_downsetting').value = b;
	document.DownSetting.submit();
	return 'ok';
}
</script>

<form action="./procSettingDown.php" name="DownSetting" method="post" class="ff">
	<input type="hidden" id="reg_id_downsetting" name="reg_id" value=""/>
	<input type="hidden" id="masterpassword_downsetting" name="masterpassword" value=""/>
</form>


<!--*******************************************************************************************-->

<script>
function syncsettingn_submit(a,b,c,d){
	document.getElementById('reg_id_syncsettingn').value = a;
	document.getElementById('setting_syncsettingn').value = b;
	document.getElementById('setting_board_syncsettingn').value = c;
	document.getElementById('masterpassword_syncsettingn').value = d;
	document.SyncSettingN.submit();
	return 'ok';
}
</script>

<form action="./procSaveSetting.php" name="SyncSettingN" method="post" class="ff">
	<input type="hidden" id="reg_id_syncsettingn" name="reg_id" value=""/>
	<input type="hidden" id="setting_syncsettingn" name="setting" value=""/>
	<input type="hidden" id="setting_board_syncsettingn" name="setting_board" value=""/>
	<input type="hidden" id="masterpassword_syncsettingn" name="masterpassword" value=""/>
</form>

<!--*******************************************************************************************-->

<script>
function syncsettingns_submit(a,b,c){
	document.getElementById('reg_id_syncsettingns').value = a;
	document.getElementById('setting_syncsettingns').value = b;
	document.getElementById('masterpassword_syncsettingns').value = c;
	document.SyncSettingNS.submit();
	return 'ok';
}
</script>

<form action="./procSaveSetting2.php" name="SyncSettingNS" method="post" class="ff">
	<input type="hidden" id="reg_id_syncsettingns" name="reg_id" value=""/>
	<input type="hidden" id="setting_syncsettingns" name="setting" value=""/>
	<input type="hidden" id="masterpassword_syncsettingns" name="masterpassword" value=""/>
</form>

<!--*******************************************************************************************-->

<script>
function syncyoungsettingns_submit(a,b,c){
	document.getElementById('reg_id_youngsyncsettingns').value = a;
	document.getElementById('setting_youngsyncsettingns').value = b;
	document.getElementById('masterpassword_youngsyncsettingns').value = c;
	document.SyncyoungSettingNS.submit();
	return 'ok';
}
</script>

<form action="./procSaveSetting3.php" name="SyncyoungSettingNS" method="post" class="ff">
	<input type="hidden" id="reg_id_youngsyncsettingns" name="reg_id" value=""/>
	<input type="hidden" id="setting_youngsyncsettingns" name="setting" value=""/>
	<input type="hidden" id="masterpassword_youngsyncsettingns" name="masterpassword" value=""/>
</form>

<!--*******************************************************************************************-->

<script>
function syncsettingbs_submit(a,b,c){
	document.getElementById('reg_id_syncsettingbs').value = a;
	document.getElementById('setting_board_syncsettingbs').value = b;
	document.getElementById('masterpassword_syncsettingbs').value = c;
	document.SyncSettingBS.submit();
	return 'ok';
}
</script>

<form action="./procSaveSetting4.php" name="SyncSettingBS" method="post" class="ff">
	<input type="hidden" id="reg_id_syncsettingbs" name="reg_id" value=""/>
	<input type="hidden" id="setting_board_syncsettingbs" name="setting_board" value=""/>
	<input type="hidden" id="masterpassword_syncsettingbs" name="masterpassword" value=""/>
</form>

<!--*******************************************************************************************-->

<script>
function errorlog_submit(a,b,c){
	document.getElementById('reg_id_errorlog').value = a;
	document.getElementById('error_errorlog').value = b;
	document.getElementById('masterpassword_errorlog').value = c;
	document.ErrorLog.submit();
	return 'ok';
}

</script>

<form action="./procErrorLog.php" name="ErrorLog" method="post" class="ff">
	<input type="hidden" id="reg_id_errorlog" name="reg_id" value=""/>
	<input type="hidden" id="error_errorlog" name="error" value=""/>
	<input type="hidden" id="masterpassword_errorlog" name="masterpassword" value=""/>
</form>


<!--*******************************************************************************************-->

<script>
function sessionok_submit(a,b){
	document.getElementById('reg_id_session').value = a;
	document.getElementById('masterpassword_session').value = b;
	document.SessionOk.submit();
	return 'ok';
}
</script>

<form action="./procSession.php" name="SessionOk" method="post" class="ff">
	<input type="hidden" id="reg_id_session" name="reg_id" value=""/>
	<input type="hidden" id="masterpassword_session" name="masterpassword" value=""/>
</form>

<!--*******************************************************************************************-->

<script>
function filedown_submit(a,b,c,d,e,f){
	document.getElementById('reg_id_filedown').value = a;
	document.getElementById('masterpassword_filedown').value = b;
	document.getElementById('file_bo_table_filedown').value = c;
	document.getElementById('file_wr_id_filedown').value = d;
	document.getElementById('file_num_filedown').value = e;
	document.getElementById('file_confirm_filedown').value = f;
	document.FiledownOk.submit();
	return 'ok';
}
</script>

<form action="./procFileDown.php" name="FiledownOk" method="post" class="ff">	
	<input type="hidden" id="reg_id_filedown" name="reg_id" value=""/>
	<input type="hidden" id="masterpassword_filedown" name="masterpassword" value=""/>
	<input type="hidden" id="file_bo_table_filedown" name="bo_table" value=""/>
	<input type="hidden" id="file_wr_id_filedown" name="wr_id" value=""/>
	<input type="hidden" id="file_num_filedown" name="no" value=""/>
	<input type="hidden" id="file_confirm_filedown" name="confirm" value=""/>
</form>


<!--*******************************************************************************************-->

<script>
function chatdownlist_submit(a,b){
	document.getElementById('masterpassword_chatdownlist').value = a;
	document.getElementById('page_chatdownlist').value = b;
	document.ChatdownlistOk.submit();
	return 'ok';
}
</script>

<form action="./chatDownList.php" name="ChatdownlistOk" method="post" class="ff">	
	<input type="hidden" id="masterpassword_chatdownlist" name="masterpassword" value=""/>
	<input type="hidden" id="page_chatdownlist" name="page" value=""/>
</form>


<!--*******************************************************************************************-->

<script>
function chatmemberdownlist_submit(a,b){
	document.getElementById('masterpassword_chatmemberdownlist').value = a;
	document.getElementById('value_chatmemberdownlist').value = b;
	document.ChatmemberdownlistOk.submit();
	return 'ok';
}
</script>

<form action="./chatSearchMember.php" name="ChatmemberdownlistOk" method="post" class="ff">	
	<input type="hidden" id="masterpassword_chatmemberdownlist" name="masterpassword" value=""/>
	<input type="hidden" id="value_chatmemberdownlist" name="value" value=""/>
</form>


<!--*******************************************************************************************-->

<script>
function chatcontentdown_submit(a,b,c,d,e){
	document.getElementById('masterpassword_chatcontentdown').value = a;
	document.getElementById('room_id_chatcontentdown').value = b;
	document.getElementById('room_type_chatcontentdown').value = c;
	document.getElementById('target_mb_id_chatcontentdown').value = d;
	document.getElementById('nowDate_chatcontentdown').value = e;
	document.ChatcontentdownOk.submit();
	return 'ok';
}
</script>

<form action="./chatContentListDown.php" name="ChatcontentdownOk" method="post" class="ff">	
	<input type="hidden" id="masterpassword_chatcontentdown" name="masterpassword" value=""/>
	<input type="hidden" id="room_id_chatcontentdown" name="room_id" value=""/>
	<input type="hidden" id="room_type_chatcontentdown" name="room_type" value=""/>
	<input type="hidden" id="target_mb_id_chatcontentdown" name="target_mb_id" value=""/>
	<input type="hidden" id="nowDate_chatcontentdown" name="nowDate" value=""/>
</form>


<!--*******************************************************************************************-->

<script>
function chatcontentdownnew_submit(a,b,c,d,e){
	document.getElementById('masterpassword_chatcontentdownnew').value = a;
	document.getElementById('room_id_chatcontentdownnew').value = b;
	document.getElementById('nowDate_chatcontentdownnew').value = c;
	document.getElementById('room_type_chatcontentdownnew').value = d;
	document.getElementById('target_mb_id_chatcontentdownnew').value = e;
	document.ChatcontentdownNewOk.submit();
	return 'ok';
}
</script>

<form action="./chatContentListDown.php" name="ChatcontentdownNewOk" method="post" class="ff">	
	<input type="hidden" id="masterpassword_chatcontentdownnew" name="masterpassword" value=""/>
	<input type="hidden" id="room_id_chatcontentdownnew" name="room_id" value=""/>
	<input type="hidden" id="nowDate_chatcontentdownnew" name="nowDate" value=""/>
	<input type="hidden" id="room_type_chatcontentdownnew" name="room_type" value=""/>
	<input type="hidden" id="target_mb_id_chatcontentdownnew" name="target_mb_id" value=""/>
</form>

<!--*******************************************************************************************-->

<script>
function chatinput_submit(a,b,c,d,e){
	document.getElementById('masterpassword_chatinput').value = a;
	document.getElementById('room_chatinput').value = b;
	document.getElementById('value_chatinput').value = c;
	document.getElementById('key_chatinput').value = d;
	document.getElementById('is_file_chatinput').value = e;
	document.ChatinputOk.submit();
	return 'ok';
}
</script>

<form action="./chatInput.php" name="ChatinputOk" method="post" class="ff">	
	<input type="hidden" id="masterpassword_chatinput" name="masterpassword" value=""/>
	<input type="hidden" id="room_chatinput" name="room_id" value=""/>
	<input type="hidden" id="value_chatinput" name="value" value=""/>
	<input type="hidden" id="key_chatinput" name="key" value=""/>
	<input type="hidden" id="is_file_chatinput" name="is_file" value=""/>
</form>

<!--*******************************************************************************************-->

<script>
function chatdownnew_submit(a,b){
	document.getElementById('masterpassword_chatdownnew').value = a;
	document.getElementById('key_chatdownnew').value = b;
	document.ChatdownnewOk.submit();
	return 'ok';
}
</script>

<form action="./chatdownnew.php" name="ChatdownnewOk" method="post" class="ff">	
	<input type="hidden" id="masterpassword_chatdownnew" name="masterpassword" value=""/>
	<input type="hidden" id="key_chatdownnew" name="cc_ix" value=""/>
</form>


<!--*******************************************************************************************-->

<script>
function chatgetroomrandom_submit(a){
	document.getElementById('masterpassword_chatroomrandom').value = a;
	document.ChatroomrandomOk.submit();
	return 'ok';
}
</script>

<form action="./chatGetRoomRandom.php" name="ChatroomrandomOk" method="post" class="ff">	
	<input type="hidden" id="masterpassword_chatroomrandom" name="masterpassword" value=""/>
</form>


<!--*******************************************************************************************-->

<script>
function chatreadyfiledown_submit(a,b,c){
	document.getElementById('masterpassword_chatfiledown').value = a;
	document.getElementById('key_chatfiledown').value = b;
	document.getElementById('random_chatfiledown').value = c;
	document.chatreadyfiledown.submit();
	return 'ok';
}
</script>

<form action="./chatReadyfiledownIos.php" name="chatreadyfiledown" method="post" class="ff">	
	<input type="hidden" id="masterpassword_chatfiledown" name="masterpassword" value=""/>
	<input type="hidden" id="key_chatfiledown" name="key" value=""/>
	<input type="hidden" id="random_chatfiledown" name="random" value=""/>
</form>

<!--*******************************************************************************************-->

<script>
function chatdelete_submit(a,b){
	document.getElementById('masterpassword_deletec').value = a;
	document.getElementById('key_deletec').value = b;
	document.ChatdeletecOk.submit();
	return 'ok';
}
</script>

<form action="./chatDelete.php" name="ChatdeletecOk" method="post" class="ff">	
	<input type="hidden" id="masterpassword_deletec" name="masterpassword" value=""/>
	<input type="hidden" id="key_deletec" name="cc_ix" value=""/>
</form>


<!--*******************************************************************************************-->

<script>
function chatdroomexit_submit(a,b){
	document.getElementById('masterpassword_roomexit').value = a;
	document.getElementById('room_id_roomexit').value = b;
	document.chatExitRoomOk.submit();
	return 'ok';
}
</script>

<form action="./chatExitRoom.php" name="chatExitRoomOk" method="post" class="ff">	
	<input type="hidden" id="masterpassword_roomexit" name="masterpassword" value=""/>
	<input type="hidden" id="room_id_roomexit" name="room_id" value=""/>
</form>


<!--*******************************************************************************************-->

<script>
function reward_submit(a,b,c,d){
	document.getElementById('masterpassword_reward').value = a;
	document.getElementById('rewardtype_reward').value = b;
	document.getElementById('rewardamount_reward').value = c;
	document.getElementById('rewardsecret_reward').value = d;
	document.rewardOk.submit();
	return 'ok';
}
</script>

<form action="./procReward.php" name="rewardOk" method="post" class="ff">	
	<input type="hidden" id="masterpassword_reward" name="masterpassword" value=""/>
	<input type="hidden" id="rewardtype_reward" name="type" value=""/>
	<input type="hidden" id="rewardamount_reward" name="amount" value=""/>
	<input type="hidden" id="rewardsecret_reward" name="reward_secret" value=""/>
</form>


<!--*******************************************************************************************-->


<script>
function iap_purchaseOk(a,b,c){
	document.getElementById('reg_id_purchaseok').value = a;
	document.getElementById('masterpassword_purchaseok').value = b;
	document.getElementById('inapp_secret_purchaseok').value = c;
	document.procIAPPurchaseOK.submit();
	return 'ok';
}
</script>

<form action="./procIAPPurchaseOK.php" name="procIAPPurchaseOK" method="post" class="ff">
	<input type="hidden" id="reg_id_purchaseok" name="reg_id" value=""/>
	<input type="hidden" id="masterpassword_purchaseok" name="masterpassword" value=""/>
	<input type="hidden" id="inapp_secret_purchaseok" name="inapp_secret" value=""/>
</form>


<!--*******************************************************************************************-->


<script>
function iap_sendpassOk(a,b,c){
	document.getElementById('reg_id_sendpassok').value = a;
	document.getElementById('masterpassword_sendpassok').value = b;
	document.getElementById('pass_sendpassok').value = c;
	document.procIAPCheck.submit();
	return 'ok';
}
</script>

<form action="./procIAPCheck.php" name="procIAPCheck" method="post" class="ff">
	<input type="hidden" id="reg_id_sendpassok" name="reg_id" value=""/>
	<input type="hidden" id="masterpassword_sendpassok" name="masterpassword" value=""/>
	<input type="hidden" id="pass_sendpassok" name="pass" value=""/>
</form>

<?php } ?>

