<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
$token = md5(uniqid(rand(), true)); 
set_session("ss_token", $token);
?>

<div id="find_info" class="new_win">
    <h1 id="win_title">아이디/비밀번호 찾기</h1>

    <form name="fpasswordlost" action="<?php echo $action_url ?>" onsubmit="return fpasswordlost_submit(this);" method="post" autocomplete="off">
<input type=hidden name=mb_datetime    value="" id="mb_datetime">
<input type=hidden name=mb_no    value="" id="mb_no">
<input type=hidden name=token value="<?php echo $token?>">
	<div style="text-align:center; margin-bottom:5px;">
<input type="radio" name="types" value="mail" <?php echo ($types == "mail")?"checked":"";?> onclick="location='<?php echo G5_HTTPS_BBS_URL?>/password_lost.php?types='+this.value;" /> <strong>E-mail로 찾기</strong> &nbsp; &nbsp; <input type="radio" name="type" value="hp" <?php echo ($types == "hp")?"checked":"";?> onclick="location='<?php echo G5_HTTPS_BBS_URL?>/password_lost.php?types='+this.value;" /> <strong>휴대폰으로 찾기</strong>    
    </div>
    <fieldset id="info_fs">
        <p>
            회원가입시 등록하신 휴대폰번호 입력<br /> 
            핸드폰 번호 인증을 하셔야 로그인을 하실 수 있습니다.<br /> 
            핸드폰으로 전송된 인증번호를 입력하신 후 회원정보를 수정하시기 바랍니다.<br /> 
        </p>
        <p style="height:10px">&nbsp;</p>
            <label for="mb_name" class="sound_only">성명<strong class="sound_only">필수</strong></label>
            <input type="text" name="mb_name" id="mb_name" required class="required frm_input" size="15" placeholder="성명" />
            <p style="height:10px">&nbsp;</p>
            <label for="mb_hp" class="sound_only">핸드폰<strong class="sound_only">필수</strong></label>
            <input type="text" name="mb_hp" id="mb_hp" required class="required frm_input" size="15" placeholder="핸드폰"/> <input type=button value='인증번호 전송' onclick="hp_certify(this.form);" style='cursor:pointer; height:18px; width:110px; padding-top:2px; BORDER: #aaaaaa 1px solid; BACKGROUND-COLOR: #f8f8f8;'>
            <p style="height:10px">&nbsp;</p>
             <label for="mb_hp_certify" class="sound_only">인증번호<strong class="sound_only">필수</strong></label>
             <input type=text name='mb_hp_certify' required class="required frm_input"size=6 maxlength=6 value="" placeholder="인증번호"> 6자리 숫자
    </fieldset>

    <div class="win_btn">
        <input type="submit" class="btn_submit" value="확인">
        <button type="button" onclick="window.close();">창닫기</button>
    </div>
    </form>
</div>

<iframe width=0 height=0 name='hiddenframe' style='display:none;'></iframe>
<script>
function hp_certify(f) { 
	var pattern = /^01[0-9][-]{0,1}[0-9]{3,4}[-]{0,1}[0-9]{4}$/; 
    if(!pattern.test(f.mb_hp.value)){ 
		alert("핸드폰 번호가 입력되지 않았거나 번호가 틀립니다.\n\n핸드폰 번호를 010-123-4567 또는 01012345678 과 같이 입력해 주십시오."); 
        f.mb_hp.select(); 
        f.mb_hp.focus(); 
        return false; 
    } 

     window.open(g5_bbs_url+"/password_hp_certify.php?mb_hp="+f.mb_hp.value+"&mb_name="+f.mb_name.value+"&token=<?php echo $token?>", "hiddenframe");
} 

function fpasswordlost_submit(f)
{
    if (f.mb_hp_certify.value == "") {
        alert("휴대폰 인증을 해주십시오.");
        f.mb_hp_certify.focus();
        return false;
    }else if(f.mb_no.value == "") {
        alert("휴대폰 인증을 제대로 해주십시오.");
        f.mb_hp.focus();
        return false;	
	}

    return true;
}


$(function() {
    var sw = screen.width;
    var sh = screen.height;
    var cw = document.body.clientWidth;
    var ch = document.body.clientHeight;
    var top  = sh / 2 - ch / 2 - 100;
    var left = sw / 2 - cw / 2;
    moveTo(left, top);
});
</script>
