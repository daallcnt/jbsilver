<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

    $mb = get_member($mb_id);

?>

<p class="ptit">취업상담카드</p>
<p>&nbsp;</p>
<div class="tbl_frs01 tbl_wrap">
    <table>
                    <colgroup>
                        <col width="25%">
                        <col width="75%" class="tbl_frs02">                        
                    </colgroup>
    <tbody>
    <tr>
        <th scope="row">성명</th>
        <td><?php echo $mb['mb_name']?></td>
    </tr>
    <tr>
        <th scope="row">생년월일</th>
        <td><?php echo $mb['mb_birth']?></td>
    </tr>    
    <tr>
        <th scope="row">집전화</th>
        <td><?php echo get_text($mb['mb_tel']) ?></td>
    </tr>
     <tr>
        <th scope="row">휴대전화</th>
        <td><?php echo get_text($mb['mb_hp']) ?></td>
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
        <td><?php echo $mb['mb_12']?></td>
    </tr>
     <tr>
        <th scope="row">최종학력</th>
        <td><?php echo $mb['mb_13']?></td>
    </tr>
     <tr>
        <th scope="row">주소</th>
        <td><?php echo sprintf("(%s%s)", $mb['mb_zip1'],$mb['mb_zip2']).' '.print_address($mb['mb_addr1'], $mb['mb_addr2'], $mb['mb_addr3'], $mb['mb_addr_jibeon']); ?></td>
    </tr>
     <tr>
        <th scope="row">경력,특기 및 자격증</th>
        <td><?php echo $mb['mb_etc']?conv_content($mb['mb_etc'],0):"&nbsp;"; ?></td>
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
        월(일) <?php echo $mb['mb_20']?> 만원
        </td>
    </tr>
     <tr>
        <th scope="row">신청이유</th>
        <td><?php echo $mb['mb_21']?></td>
    </tr>                            
    </tbody>
    </table>
</div>
    <?php if($iso == "ok") {?>
<div class="btn_fixed_top">
    <a href="<?php echo G5_ADMIN_URL ?>/member_list.php" class="btn btn_02">목록</a>
	<a href="./card.php?w=u&case=write&iso=ok&mb_id=<?php echo $mb['mb_id']?>" class="btn btn_submit">수정</a>
</div>    
	<?php }else{ ?>
<div class="btn_confirm01 btn_confirm">
    <a href="<?php echo G5_URL ?>">메인으로</a>
	<a href="./index.php?w=u&case=write">수정</a>    
</div>
    <?php }?>