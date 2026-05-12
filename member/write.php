<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
add_javascript(G5_POSTCODE_JS, 0);


    $mb = get_member($mb_id);
 


if($iso == "ok") {
	$a_url = G5_URL."/member/update.php";	

}else{
	$a_url = "./update.php";
}
?>

<section id="bo_w">
    <form id="fregisterform" name="fregisterform" action="<?php echo $a_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="w" value="u">
    <input type="hidden" name="iso" value="<?php echo $iso?>">
    <input type="hidden" name="case" value="<?php echo $case?>">
    <input type="hidden" name="mb_id" value="<?php echo $mb['mb_id']?>">

<p class="ptit">취업상담카드</p>

<div class="tbl_frm03 tbl_wrap">
    <table>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">성명</th>
        <td><?php echo $mb['mb_name']?></td>
    </tr>
    <tr>
        <th scope="row">생년월일</th>
        <td><input type="text" name="mb_birth" value="<?php echo $mb['mb_birth']?>" id="mb_birth" required class="frm_input required" size="10"  placeholder="생년월일"> ex) 19700101</td>
    </tr>    
    <tr>
        <th scope="row">집전화</th>
        <td>
        <input type="text" name="mb_tel" value="<?php echo get_text($mb['mb_tel']) ?>" id="reg_mb_tel" required class="frm_input half_input required" maxlength="20" placeholder="전화번호">
        </td>
    </tr>
     <tr>
        <th scope="row">휴대전화</th>
        <td>
                <input type="text" name="mb_hp" value="<?php echo get_text($mb['mb_hp']) ?>" id="reg_mb_hp" required class="frm_input right_input half_input required" maxlength="20" placeholder="휴대폰번호">
        </td>
    </tr>
     <tr>
        <th scope="row">가족관계</th>
        <td>
    <input type="checkbox" name="mb11[]" value="배우자" id="mb_111">
    <label for="mb_111">배우자 </label>  &nbsp;
    <input type="checkbox" name="mb11[]" value="자녀" id="mb_112">
    <label for="mb_112">자녀 </label> &nbsp;
    <input type="checkbox" name="mb11[]" value="손자손녀" id="mb_113">
    <label for="mb_113">손자손녀 </label> &nbsp;
    <input type="checkbox" name="mb11[]" value="독거" id="mb_114">
    <label for="mb_114">독거 </label> 
         
            <input type="hidden" name="mb_11" value="<?php echo $mb['mb_11']?>">
            <script type="text/javascript">
                (function(){
                   var value = '<?php echo $mb['mb_11']?>'.split(','), items = document.getElementsByName('mb11[]');
                   for(var i=0;i<value.length;i++){
                      for(var j=0;j<items.length;j++){
                         if(value[i]==items[j].value){
                            items[j].checked = true;
                            break;
                         }
                      }
                  }
                })();
          </script>        
        </td>
    </tr>
     <tr>
        <th scope="row">건강상태</th>
        <td>
        <select name="mb_12" id="mb_12" required class="frm_input required">
        <option value="">선택하세요</option>
        <option value="건강함" <?php echo get_selected($mb['mb_12'], "건강함")?>>건강함</option>
        <option value="보통" <?php echo get_selected($mb['mb_12'], "보통")?>>보통</option>
        <option value="건강하지 못함" <?php echo get_selected($mb['mb_12'], "건강하지 못함")?>>건강하지 못함</option>
        </select>        
        </td>
    </tr>
     <tr>
        <th scope="row">최종학력</th>
        <td>
        <select name="mb_13" id="mb_13" required class="frm_input required">
        <option value="">선택하세요</option>
        <option value="초졸" <?php echo get_selected($mb['mb_13'], "초졸")?>>초졸</option>
        <option value="중졸" <?php echo get_selected($mb['mb_13'], "중졸")?>>중졸</option>
        <option value="고졸" <?php echo get_selected($mb['mb_13'], "고졸")?>>고졸</option>
        <option value="대졸" <?php echo get_selected($mb['mb_13'], "대졸")?>>대졸</option>
        <option value="무학" <?php echo get_selected($mb['mb_13'], "무학")?>>무학</option>
        </select>        
        </td>
    </tr>
     <tr>
        <th scope="row">주소</th>
        <td>
                <input type="text" name="mb_zip" value="<?php echo $mb['mb_zip1'].$mb['mb_zip2']; ?>" id="reg_mb_zip" required class="frm_input required" size="5" maxlength="6"  placeholder="우편번호">
                <button type="button" class="btn_frmline" onclick="win_zip('fregisterform', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
                <input type="text" name="mb_addr1" value="<?php echo get_text($mb['mb_addr1']) ?>" id="reg_mb_addr1" required class="frm_input frm_address full_input required" size="50"  placeholder="기본주소">
                <br>
                <input type="text" name="mb_addr2" value="<?php echo get_text($mb['mb_addr2']) ?>" id="reg_mb_addr2" class="frm_input frm_address full_input" size="50"  placeholder="상세주소">
                <br>
                <input type="text" name="mb_addr3" value="<?php echo get_text($mb['mb_addr3']) ?>" id="reg_mb_addr3" class="frm_input frm_address full_input" size="50" readonly="readonly"  placeholder="참고항목">
                <input type="hidden" name="mb_addr_jibeon" value="<?php echo get_text($mb['mb_addr_jibeon']); ?>">        
        </td>
    </tr>
     <tr>
        <th scope="row">경력,특기 및 자격증</th>
        <td>
 <textarea name="mb_etc" id="reg_mb_etc" class="frm_input" placeholder="경력,특기 및 자격증"><?php echo $mb['mb_etc'] ?></textarea>       
        </td>
    </tr>
     <tr>
        <th scope="row">개인정보</th>
        <td>
    <ul>
    
    <li><input type="checkbox" name="mb14[]" value="복지카드" id="mb_141">
    <label for="mb_141">복지카드 </label></li>
    <li><input type="checkbox" name="mb14[]" value="유공자카드" id="mb_142">
    <label for="mb_142">유공자카드 </label></li>
    <li><input type="checkbox" name="mb14[]" value="운전면허증" id="mb_143">
    <label for="mb_143">운전면허증 </label> 
    ( <input type="text" name="mb_15" value="<?php echo $mb['mb_15']?>" id="mb_15" class="frm_input " size="2"> )</li>
    <li><input type="checkbox" name="mb14[]" value="건강보험" id="mb_144">
    <label for="mb_144">건강보험 </label></li>
    <li><input type="checkbox" name="mb14[]" value="기초생활수급자" id="mb_145">
    <label for="mb_145">기초생활수급자 </label></li>
    <li><input type="checkbox" name="mb14[]" value="기초노령연금" id="mb_146">
    <label for="mb_146">기초노령연금 </label></li>
    <li><input type="checkbox" name="mb14[]" value="장애급수" id="mb_147">
    <label for="mb_147">장애급수 </label>
    ( <input type="text" name="mb_16" value="<?php echo $mb['mb_16']?>" id="mb_16" class="frm_input " size="2"> )  </li>  
    <li><input type="checkbox" name="mb14[]" value="경비교육수료" id="mb_148">
    <label for="mb_148">경비교육수료 </label></li>
    </ul>     
            <input type="hidden" name="mb_14" value="<?php echo $mb['mb_14']?>">
            <script type="text/javascript">
                (function(){
                   var value1 = '<?php echo $mb['mb_14']?>'.split(','), items1 = document.getElementsByName('mb14[]');
                   for(var i=0;i<value1.length;i++){
                      for(var j=0;j<items1.length;j++){
                         if(value1[i]==items1[j].value){
                            items1[j].checked = true;
                            break;
                         }
                      }
                  }
                })();
          </script>        
        </td>
    </tr>
     <tr>
        <th scope="row">취업희망직종</th>
        <td>
    <ul>
    
    <li><input type="checkbox" name="mb17[]" value="행정사무직" id="mb_17_1">
    <label for="mb_17_1">행정사무직 </label></li>
    <li><input type="checkbox" name="mb17[]" value="현장관리직" id="mb_17_2">
    <label for="mb_17_2">현장관리직 </label></li>
    <li><input type="checkbox" name="mb17[]" value="주유원" id="mb_17_3">
    <label for="mb_17_3">주유원 </label></li>
    <li><input type="checkbox" name="mb17[]" value="운전/운송" id="mb_17_4">
    <label for="mb_17_4">운전/운송 </label></li>
    <li><input type="checkbox" name="mb17[]" value="택배업" id="mb_17_5">
    <label for="mb_17_5">택배업 </label></li>
    <li><input type="checkbox" name="mb17[]" value="영업/판매" id="mb_17_6">
    <label for="mb_17_6">영업/판매 </label></li>
    <li><input type="checkbox" name="mb17[]" value="경비관련" id="mb_17_7">
    <label for="mb_17_7">경비관련 </label></li>
    <li><input type="checkbox" name="mb17[]" value="청소관련" id="mb_17_8">
    <label for="mb_17_8">청소관련 </label></li>
    <li><input type="checkbox" name="mb17[]" value="식당/서비스" id="mb_17_9">
    <label for="mb_17_9">식당/서비스 </label></li>
    <li><input type="checkbox" name="mb17[]" value="가사도우미" id="mb_17_10">
    <label for="mb_17_10">가사도우미 </label></li>
    <li><input type="checkbox" name="mb17[]" value="기계/건설" id="mb_17_11">
    <label for="mb_17_11">기계/건설 </label></li>
    <li><input type="checkbox" name="mb17[]" value="생산작업" id="mb_17_12">
    <label for="mb_17_12">생산작업 </label></li>
    <li><input type="checkbox" name="mb17[]" value="농어촌인력" id="mb_17_13">
    <label for="mb_17_13">농어촌인력 </label></li>
    <li><input type="checkbox" name="mb17[]" value="교육/강사" id="mb_17_14">
    <label for="mb_17_14">교육/강사 </label></li>
    <li><input type="checkbox" name="mb17[]" value="산림관련" id="mb_17_15">
    <label for="mb_17_15">산림관련 </label></li>
    <li><input type="checkbox" name="mb17[]" value="골프장관련" id="mb_17_16">
    <label for="mb_17_16">골프장관련 </label></li>
     <li><input type="checkbox" name="mb17[]" value="지자체사업" id="mb_17_17">
    <label for="mb_17_17">지자체사업 </label></li>
    <li><input type="checkbox" name="mb17[]" value="기타" id="mb_17_18">
    <label for="mb_17_18">기타 </label>
    ( <input type="text" name="mb_18" value="<?php echo $mb['mb_18']?>" id="mb_18" size="8" class="frm_input"> )    </li>
    </ul>                        
            <input type="hidden" name="mb_17" value="<?php echo $mb['mb_17']?>">
            <script type="text/javascript">
                (function(){
                   var value2 = '<?php echo $mb['mb_17']?>'.split(','), items2 = document.getElementsByName('mb17[]');
                   for(var i=0;i<value2.length;i++){
                      for(var j=0;j<items2.length;j++){
                         if(value2[i]==items2[j].value){
                            items2[j].checked = true;
                            break;
                         }
                      }
                  }
                })();
          </script>        
        </td>
    </tr>
     <tr>
        <th scope="row">근무형태</th>
        <td>
    <input type="checkbox" name="mb19[]" value="시간제" id="mb_191">
    <label for="mb_191">시간제</label> &nbsp;
    <input type="checkbox" name="mb19[]" value="종일제" id="mb_192">
    <label for="mb_192">종일제</label> &nbsp;
    <input type="checkbox" name="mb19[]" value="격일제" id="mb_193">
    <label for="mb_193">격일제</label> &nbsp;
    <input type="checkbox" name="mb19[]" value="관계없음" id="mb_194">
    <label for="mb_194">관계없음</label>
         
            <input type="hidden" name="mb_19" value="<?php echo $mb['mb_19']?>">
            <script type="text/javascript">
                (function(){
                   var value3 = '<?php echo $mb['mb_19']?>'.split(','), items3 = document.getElementsByName('mb19[]');
                   for(var i=0;i<value3.length;i++){
                      for(var j=0;j<items3.length;j++){
                         if(value3[i]==items3[j].value){
                            items3[j].checked = true;
                            break;
                         }
                      }
                  }
                })();
          </script>        
        </td>
    </tr>
     <tr>
        <th scope="row">희망급여</th>
        <td>
        월(일) <input type="text" name="mb_20" value="<?php echo $mb['mb_20']?>" id="mb_20" size="10" class="frm_input">만원
        </td>
    </tr>
     <tr>
        <th scope="row">신청이유</th>
        <td>
        <select name="mb_21" id="mb_21" required class="frm_input required">
        <option value="">선택하세요</option>
        <option value="경제적이유" <?php echo get_selected($mb['mb_21'], "경제적이유")?>>경제적이유</option>
        <option value="여가활동" <?php echo get_selected($mb['mb_21'], "여가활동")?>>여가활동</option>
        <option value="사회참여" <?php echo get_selected($mb['mb_21'], "사회참여")?>>사회참여</option>
        </select>        
        </td>
    </tr>                                                                           
    </tbody>
    </table>
</div>

<br />
    <?php if($iso == "ok") {?>
<div class="btn_fixed_top">
    	<input type="submit" value=" 등록하기 " id="btn_submit" accesskey="s" class="btn_submit btn">

        <a href="javascript:history.go(-1)" class="btn btn_02">취소</a>
</div>
	<?php }else{?>
    <div class="btn_confirm write_div">
    	<input type="submit" value=" 등록하기 " id="btn_submit" accesskey="s" class="btn_submit btn">

        <a href="javascript:history.go(-1)" class="btn_cancel btn">취소</a>
        
    </div>
    <?php }?>
    </form>

    <script>
    function fregisterform_submit(f)
    {

	var Tar1 = document.getElementsByName('mb11[]');
	var tmp1 = 0;
	for(i=0;i<Tar1.length;i++){
	 if(Tar1[i].checked == true) tmp1++;
	 }
	 if(tmp1 < 1){
	  alert('가족관계를 선택하셔야 됩니다.');
	  f.mb_111.focus();
	  return false;
	 }

	var Tar2 = document.getElementsByName('mb14[]');
	var tmp2 = 0;
	for(i=0;i<Tar2.length;i++){
	 if(Tar2[i].checked == true) tmp2++;
	 }
	 if(tmp2 < 1){
	  alert('개인정보를 선택하셔야 됩니다.');
	  f.mb_141.focus();
	  return false;
	 }	 

	var Tar3 = document.getElementsByName('mb17[]');
	var tmp3 = 0;
	for(i=0;i<Tar3.length;i++){
	 if(Tar3[i].checked == true) tmp3++;
	 }
	 if(tmp3 < 1){
	  alert('취업희망직종을 선택하셔야 됩니다.');
	  f.mb_171.focus();
	  return false;
	 }

	var Tar4 = document.getElementsByName('mb19[]');
	var tmp4 = 0;
	for(i=0;i<Tar4.length;i++){
	 if(Tar4[i].checked == true) tmp4++;
	 }
	 if(tmp4 < 1){
	  alert('근무형태를 선택하셔야 됩니다.');
	  f.mb_111.focus();
	  return false;
	 }	 

        document.getElementById("btn_submit").disabled = "disabled";

        if(!confirm("취업상담카드를 등록하겠습니까?")) {
            return false;
        }else{

	        return true;
		}
    }
    </script>
</section>
<!-- } 게시물 작성/수정 끝 -->