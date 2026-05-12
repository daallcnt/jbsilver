<?
include_once("../common.php");
$gr_id = "sub12";
$co_id = "sub12_01";
include_once(G5_PATH.'/head.php');
if (!$is_member)
	alert('로그인 한 회원만 접근하실 수 있습니다.', G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/sub/sub12_01.php').'');
   //goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_URL."/sub/sub12_01.php"));
?>





<div id="cct_con">


<div id="sub_common">	



	<div style="text-align:center;"><img src="../img/sub12_01_img01.jpg"></div>
	<div class="sub02_txt03" style="text-align:center; padding-top:20px;">회원탈퇴시 수집된 개인정보는 모두 삭제됩니다.<br>
추후 다시 회원가입시 같은 아이디로 가입이 불가하오니<br><strong>신중한 선택 부탁드립니다.</strong></div>

	<div style="text-align:center;padding-top:40px;"><a href="<?php echo G5_BBS_URL; ?>/member_confirm.php?url=member_leave.php" onclick="return member_leave();" class="sub12_01_btn">회원탈퇴하기</a></div>

</div>




</div>


<script>
function member_leave()
{
    return confirm('정말 회원에서 탈퇴 하시겠습니까?')
}	
</script>
<?
include_once(G5_PATH.'/tail.php');
?>