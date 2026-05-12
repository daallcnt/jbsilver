<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
$is_password = false;
if ($is_guest || $is_admin) {
    $is_password = true;
	$is_name = true;
}
?>
<p>&nbsp;</p>
<div style="margin:0 0 20px 0; padding:15px; border:1px solid #ccc; background-color:#f8f8f8;">
	<ul type="circle" style="font-weight:bold;">
		<li>성명과 연락처를 입력하시고 확인버튼을 눌러주세요.</li>
	</ul>
</div>
<section id="bo_w">

    <!-- 게시물 작성/수정 시작 { -->
    <form name="fwrite" id="fwrite" action="./edu.php" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" style="width:<?php echo $width; ?>">
    <input type="hidden" name="case" value="sview" />
	<input type="hidden" name="edu" value="<?php echo $edu?>" />
<?php if (!$is_password) { ?>
<input type="hidden" name="pass" id="pass" value="<?php echo $member['mb_password']?>">
<?php }?>
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <tbody>
        <tr>
            <th scope="row"><label for="id">아이디</label></th>
            <td><input type="hidden" name="id" value="<?php echo ($w)?$row['id']:$member['mb_id'];?>"><?php echo ($w)?$row['id']:$member['mb_id'];?></td>
        </tr>
        <tr>
            <th scope="row"><label for="name">성명<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="name" value="<?php echo ($w)?$row['name']:$member['mb_name'];?>" id="name" required class="frm_input required" size="10" maxlength="20"></td>
        </tr>
        <tr>
            <th scope="row"><label for="mobile">연락처</label></th>
            <td><input type="text" name="mobile" value="<?php echo $row['mobile'] ?>" id="mobile" class="frm_input required"></td>
        </tr>   
        <?php if ($is_password) { ?>
        <tr>
            <th scope="row"><label for="pass">비밀번호</label></th>
            <td>
            <input type="text" name="pass" value="" id="pass" required class="frm_input required" placeholder="비밀번호">
            </td>
        </tr>
        <?php }?>                    
        </tbody>
        </table>
    </div>

    <div class="btn_confirm write_div">
        <input type="submit" value="확인" id="btn_submit" accesskey="s" class="btn_submit btn">
    </div>
    </form>

    <script>
    function fwrite_submit(f)
    {
        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
    </script>
</section>


