<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>
<div class="webwidget_tab" id="webwidget_tab">
    <div class="tabContainer">
        <ul class="tabHead">
            <li <?php if($ca != 2){ ?>class="currentBtn"<?php }?>><a href="./index.php?ca=1">개인검색</a></li> 
            <li <?php if($ca == 2){ ?>class="currentBtn"<?php }?>><a href="./index.php?ca=2">기관검색</a></li>       
        </ul>
    </div>


	<div class="tabBody">
      <ul>
          <?php if($ca != 2){ ?>
          <li class="tabCot">
<p>&nbsp;</p>
<div style="margin:0 0 20px 0; border:1px solid #ccc; background-color:#f8f8f8;">
	<ul type="circle" style="font-weight:bold;">
		<li style="padding:15px;">성명과 연락처를 입력하시고 확인버튼을 눌러주세요.</li>
	</ul>
</div>

    <!-- 게시물 작성/수정 시작 { -->
    <form name="fwrite" id="fwrite" action="./index.php" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" style="width:<?php echo $width; ?>">
    <input type="hidden" name="case" value="list" />

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <tbody>
        <!--<tr>
            <th scope="row"><label for="s_day">교육일자</label></th>
            <td><input type="text" name="s_day" value="<?php echo $row['s_day']?>" id="s_day"  class="frm_input " size="12"> ~ <input type="text" name="f_day" value="<?php echo $row['f_day']?>" id="f_day"  class="frm_input" size="12"></td>
        </tr>-->
        <?php if($is_member){?>
        <tr>
            <th scope="row"><label for="id">아이디</label></th>
            <td><input type="hidden" name="id" value="<?php echo ($w)?$row['id']:$member['mb_id'];?>"><?php echo ($w)?$row['id']:$member['mb_id'];?></td>
        </tr>
        <?php }?>
        <tr>
            <th scope="row"><label for="name">성명<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="name" value="<?php echo ($w)?$row['name']:$member['mb_name'];?>" id="name" required class="frm_input required" size="10" maxlength="20"></td>
        </tr>
        <tr>
            <th scope="row"><label for="mobile">연락처</label></th>
            <td><input type="text" name="mobile" value="<?php echo $row['mobile'] ?>" id="mobile"  required class="frm_input  required"> (예 : 000-0000-0000)</td>
        </tr>               
        </tbody>
        </table>
    </div>

    <div class="btn_confirm">
        <input type="submit" value="확인" id="btn_submit" accesskey="s" class="btn_submit btn">
    </div>
    </form>
          </li>
          <?php }else{?>
          <li class="tabCot">          
<p>&nbsp;</p>
<div style="margin:0 0 20px 0; border:1px solid #ccc; background-color:#f8f8f8;">
	<ul type="circle" style="font-weight:bold;">
		<li style="padding:15px;">기관과 연락처를 입력하시고 확인버튼을 눌러주세요.</li>
	</ul>
</div>

    <!-- 게시물 작성/수정 시작 { -->
    <form name="fwrite" id="fwrite" action="./index.php" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" style="width:<?php echo $width; ?>">
    <input type="hidden" name="case" value="list2" />

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <tbody>
        <!--<tr>
            <th scope="row"><label for="s_day">교육일자</label></th>
            <td><input type="text" name="s_day" value="<?php echo $row['s_day']?>" id="s_day"  class="frm_input" size="12"> ~ <input type="text" name="f_day" value="<?php echo $row['f_day']?>" id="f_day"  class="frm_input" size="12"></td>
        </tr>-->
        <tr>
            <th scope="row"><label for="corp">기관<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="corp" value="<?php echo $row['corp']?>" id="corp" required class="frm_input required" size="30"></td>
        </tr>
        <tr>
            <th scope="row"><label for="phone">기관연락처<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="phone" value="<?php echo $row['phone'] ?>" id="phone" required class="frm_input required"></td>
        </tr>               
        </tbody>
        </table>
    </div>

    <div class="btn_confirm">
        <input type="submit" value="확인" id="btn_submit" accesskey="s" class="btn_submit btn">
    </div>
    </form>          
		  </li>  
         <?php }?>           
      </ul>
          <div style="clear: both;"></div>
    </div>  
</div>



    <script>
$(function(){
    $("#s_day, #f_day").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99"/*, maxDate: "+0d"*/ });
});
    function fwrite_submit(f)
    {
        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
    </script>
</section>