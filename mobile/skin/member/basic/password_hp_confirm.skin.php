<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
$token = md5(uniqid(rand(), true)); 
set_session("ss_token", $token);
?>

<div id="find_info" class="new_win">
    <h1 id="win_title">아이디/비밀번호 찾기</h1>


    <fieldset id="info_fs">
<p style="color:#0000FF;">
1. 홈페이지에서 회원아이디와 위에 적힌 임시 패스워드로 변경되었습니다.<br />
2. 로그인 하신 후 정보수정에서 새로운 패스워드로 변경하시기 바랍니다.
</p>
	<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption>회원정보</caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">회원아이디</th>
        <td>
           <?php echo $mb['mb_id']?>
        </td>
    </tr>
    <tr>            
        <th scope="row">임시 비밀번호</th>
        <td>
           <?php echo $change_password?>
        </td>
    </tr>
    <tr>        
        <th scope="row">이름</th>
        <td>
           <?php echo $mb['mb_name']?>
        </td>                
    </tr>
    </tbody>
    </table>
    </div>
    </fieldset>

    <div class="win_btn">
        <button type="button" onclick="window.close();">창닫기</button>
    </div>
</div>
