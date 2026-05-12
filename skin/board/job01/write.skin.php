<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
add_javascript(G5_POSTCODE_JS, 0);
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);

$wr1 = explode("|",$write['wr_1']);
$wr2 = explode("|",$write['wr_2']);
$wr3 = explode("|",$write['wr_3']);
$wr5 = explode("|",$write['wr_5']);
$wr6 = explode("|",$write['wr_6']);
$wr7 = explode("|",$write['wr_7']);
$wr8 = explode("|",$write['wr_8']);
$wr9 = explode("|",$write['wr_9']);


   if(strlen($f_date)>0) {  // 받은 날짜 argument 가 있을때..
     $f_year = (int)substr($f_date,0,4);$f_mon = (int)substr($f_date,4,2);$f_day = (int)substr($f_date,6,2);
   } else {                                    // 받은 날짜 argument 가 없거나, 이상할 때 오늘날짜로 세팅...
     $today = getdate();
     $f_mon = $today['mon'];$f_day = $today['mday'];$f_year = $today['year'];   
     $f_date = $t_year.sprintf("%02d",$f_mon).$t_day;
   }


$start_date = $wr7[0];
$start_date_y = substr($start_date,0,4);
$start_date_m = substr($start_date,4,2);
$start_date_d = substr($start_date,6,2);

 
if (strlen($start_date) == 8) // 기존에 들어있는 값이 있을 경우엔 기존의 값을 이용한다.
{
	//(int)
	$f_year =(int)$start_date_y;
	$f_mon  =(int)$start_date_m;
	$f_day  = (int)$start_date_d;
}

   // 날짜 관련 listbox html 생성 시작
   $lastday=array(0,31,28,31,30,31,30,31,31,30,31,30,31);
   if ($year%4 == 0) $lastday[2] = 29;
   for ($i=1;$i <= $lastday[$f_mon];$i++) {
     $temp_year = $f_year - 4 + $i;
     if($i <= 7) { // 년도 선택 listbox html 생성
       if ($temp_year==$f_year) { $htm_fyear .= "      <OPTION value=$temp_year selected>$temp_year</OPTION>\n"; }
       else { $htm_fyear .= "      <OPTION value=$temp_year>$temp_year</OPTION>\n"; }
     }
     if($i <=12) { // 월 선택 listbox html 생성
       $temp_mon = sprintf ("%02d",$i);
       if ($i==$f_mon) { $htm_fmon .= "      <OPTION value=$temp_mon selected>$i</OPTION>\n"; }
       else { $htm_fmon .= "      <OPTION value=$temp_mon>$i</OPTION>\n"; }
     }
     // 일 선택 listbox html 생성
     $temp_day = sprintf ("%02d",$i);
     if ($i==$f_day) { $htm_fday .= "      <OPTION value=$temp_day selected>$i</OPTION>\n"; }
     else { $htm_fday .= "      <OPTION value=$temp_day>$i</OPTION>\n"; }
   }
?>

<section id="bo_w">
    <!--<h2 class="sound_only"><?php echo $g5['title'] ?></h2>-->

    <!-- 게시물 작성/수정 시작 { -->
    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" style="width:<?php echo $width; ?>">
    <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
 <?php if(!$is_admin){?>
     <input type="hidden" name="wr_10" value="<?php echo (!$w)?"채용중":$write['wr_10'];?>">
 <?php }?>   
    <?php
    $option = '';
    $option_hidden = '';
    if ($is_notice || $is_html || $is_secret || $is_mail) {
        $option = '';
        if ($is_notice) {
            $option .= "\n".'<input type="checkbox" id="notice" name="notice" value="1" '.$notice_checked.'>'."\n".'<label for="notice">공지</label>';
        }

        if ($is_html) {
            if ($is_dhtml_editor) {
                $option_hidden .= '<input type="hidden" value="html1" name="html">';
            } else {
                $option .= "\n".'<input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" value="'.$html_value.'" '.$html_checked.'>'."\n".'<label for="html">HTML</label>';
            }
        }

        if ($is_secret) {
            if ($is_admin || $is_secret==1) {
                $option .= "\n".'<input type="checkbox" id="secret" name="secret" value="secret" '.$secret_checked.'>'."\n".'<label for="secret">비밀글</label>';
            } else {
                $option_hidden .= '<input type="hidden" name="secret" value="secret">';
            }
        }

        if ($is_mail) {
            $option .= "\n".'<input type="checkbox" id="mail" name="mail" value="mail" '.$recv_email_checked.'>'."\n".'<label for="mail">답변메일받기</label>';
        }
    }

    echo $option_hidden;
    ?>



    <div class="bo_w_info write_div">
    <?php if ($is_name) { ?>
        <label for="wr_name" class="sound_only">이름<strong>필수</strong></label>
        <input type="text" name="wr_name" value="<?php echo $name ?>" id="wr_name" required class="frm_input required" placeholder="이름">
    <?php } ?>

    <?php if ($is_password) { ?>
        <label for="wr_password" class="sound_only">비밀번호<strong>필수</strong></label>
        <input type="password" name="wr_password" id="wr_password" <?php echo $password_required ?> class="frm_input <?php echo $password_required ?>" placeholder="비밀번호">
    <?php } ?>
    </div>

    <?php if ($option) { ?>
    <div class="write_div">
        <span class="sound_only">옵션</span>
        <?php echo $option ?>
    </div>
    <?php } ?>



<div class="tbl_frm03 tbl_wrap">
    <table>
    <colgroup>
        <col width='16%'>
        <col>
    </colgroup>
    <tbody>
    <tr>
        <td colspan="2"><p style="background:#3375b2;font-size:18px;color:#ffffff;padding:5px;font-weight:bold;">기업정보</p></td>
    </tr>
	<tr>
        <th scope="row">회사명</th>
        <td>
<input type="text" name="wr11" value="<?php echo $wr1[0]?>" id="wr11" required class="frm_input full_input required" size="50" placeholder="회사명">        
        </td>
    </tr>
    <tr>
        <th scope="row">담당자명</th>
        <td>
<input type="text" name="wr12" value="<?php echo $wr1[1]?>" id="wr12" required class="frm_input full_input required" placeholder="담당자명"> 
        </td>
    </tr>
<?php if($is_admin){?>
    <tr>
        <th scope="row">채용현황</th>
        <td>
        <select name="wr_10" id="wr_10" class="frm_input">
        <option value="채용중">채용중</option>
        <option value="알선" <?php echo get_selected($write['wr_10'], "알선")?>>알선</option>
        <option value="채용완료" <?php echo get_selected($write['wr_10'], "채용완료")?>>채용완료</option>
        </select>
        </td>
    </tr>
<?php }?>    

    <tr>
        <th scope="row">회사전화</th>
        <td>
<input type="text" name="wr13" value="<?php echo $wr1[2]?>" id="wr13" required class="frm_input full_input required" placeholder="회사전화"> 
        </td>
    </tr>    
    <tr>
        <th scope="row">이메일</th>
        <td>
<input type="text" name="wr14" value="<?php echo $wr1[3]?>" id="wr14" class="frm_input full_input email" size="50" placeholder="이메일"> 
        </td>
    </tr>
    <tr>
        <th scope="row">회사주소</th>
        <td>
            <input type="text" name="wr21" value="<?php echo $wr2[0]?>" id="wr21" required class="frm_input readonly required" size="5" maxlength="6" placeholder="우편번호">
            <button type="button" class="btn_frmline btn" onclick="win_zip('fwrite', 'wr21', 'wr22', 'wr23', 'wr24', 'wr_etc');">주소 검색</button><br>
            <input type="text" name="wr22" value="<?php echo $wr2[1]?>" id="wr22" required class="frm_input readonly required full_input" placeholder="기본주소">
            <br />
            <input type="text" name="wr23" value="<?php echo $wr2[2]?>" id="wr23" class="frm_input full_input" placeholder="상세주소">
            <br />
            <input type="text" name="wr24" value="<?php echo $wr2[3]?>" id="wr24" class="frm_input full_input" placeholder="참고항목">
            <input type="hidden" name="wr_etc" value="">
        </td>
    </tr>
    <tr>
        <th scope="row">주요사업내용</th>
        <td>
<input type="text" name="wr31" value="<?php echo $wr3[0]?>" id="wr31" required class="frm_input full_input required" size="50" placeholder="주요사업내용"> 
        </td>
    </tr>
    <tr>
        <th scope="row">대표자</th>
        <td>
<input type="text" name="wr91" value="<?php echo $wr9[0]?>" id="wr91" required class="frm_input full_input required" placeholder="대표자"> 
        </td>
    </tr>
	<tr>
        <td colspan="2"><p style="background:#19916e;font-size:18px;color:#ffffff;padding:5px;font-weight:bold;">채용정보</p></td>
    </tr>
    <tr>
        <th scope="row">채용정보제목</th>
        <td>
<input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="frm_input full_input required" size="50" maxlength="255" placeholder="제목">
        </td>
    </tr>
    <?php if ($is_category) { ?>
    <tr>
        <th scope="row">지역</th>
        <td>
        <select name="ca_name" id="ca_name" required class="frm_input required">
            <option value="">지역을 선택하세요</option>
            <?php echo $category_option ?>
        </select>
        </td>
    </tr>
    <?php } ?>    
    <tr>
        <th scope="row">근무형태</th>
        <td>
<input type="text" name="wr32" value="<?php echo $wr3[1]?>" id="wr32" required class="frm_input full_input required" placeholder="근무형태">
        </td>
    </tr>
    <tr>
        <th scope="row">업무내용</th>
        <td>
<input type="text" name="wr33" value="<?php echo $wr3[2]?>" id="wr33" required class="frm_input full_input required" size="50" placeholder="업무내용">
        </td>
    </tr>
    <tr>
        <th scope="row">모집업종</th>
        <td>
        <select name="wr51" id="wr51" required class="frm_input required">
        <option value="">선택하세요</option>
        <option value="행정사무직" <?php echo get_selected($wr5[0], "행정사무직")?>>행정사무직</option>
        <option value="현장관리직" <?php echo get_selected($wr5[0], "현장관리직")?>>현장관리직</option>
        <option value="주유원" <?php echo get_selected($wr5[0], "주유원")?>>주유원</option>
        <option value="운전,운송" <?php echo get_selected($wr5[0], "운전,운송")?>>운전,운송</option>
        <option value="택배업" <?php echo get_selected($wr5[0], "택배업")?>>택배업</option>
        <option value="영업,판매" <?php echo get_selected($wr5[0], "영업,판매")?>>영업,판매</option>
        <option value="경비관련" <?php echo get_selected($wr5[0], "경비관련")?>>경비관련</option>
        <option value="청소관련" <?php echo get_selected($wr5[0], "청소관련")?>>청소관련</option>
        <option value="식당,서비스" <?php echo get_selected($wr5[0], "식당,서비스")?>>식당,서비스</option>
        <option value="가사도우미" <?php echo get_selected($wr5[0], "가사도우미")?>>가사도우미</option>
        <option value="기계,건설" <?php echo get_selected($wr5[0], "기계,건설")?>>기계,건설</option>
        <option value="생산작업" <?php echo get_selected($wr5[0], "생산작업")?>>생산작업</option>
        <option value="농어촌인력" <?php echo get_selected($wr5[0], "농어촌인력")?>>농어촌인력</option>
        <option value="교육,강사" <?php echo get_selected($wr5[0], "교육,강사")?>>교육,강사</option> 
        <option value="산림관련" <?php echo get_selected($wr5[0], "산림관련")?>>산림관련</option>
        <option value="골프장관련" <?php echo get_selected($wr5[0], "골프장관련")?>>골프장관련</option>
        <option value="지자체사업" <?php echo get_selected($wr5[0], "지자체사업")?>>지자체사업</option>
        <option value="기타" <?php echo get_selected($wr5[0], "기타")?>>기타</option>                                                                               
        </select>
        
        <input type="text" name="wr52" value="<?php echo $wr5[1]?>" id="wr52"  class="frm_input" placeholder="기타"> 
        
        </td>
    </tr>
    <tr>
        <th scope="row">급여사항</th>
        <td>
<input type="text" name="wr63" value="<?php echo $wr6[2]?>" id="wr63" class="frm_input full_input" placeholder="급여사항"> 
        </td>
    </tr>
    <tr>
        <th scope="row">근무시간</th>
        <td>
<input type="checkbox" name="wr95" value="on" <?php if($wr9[4] == "on")	 echo "checked"; ?>>주간&nbsp;&nbsp;<input type="checkbox" name="wr96" value="on" <?php if($wr9[5] == "on")	 echo "checked"; ?>>2교대&nbsp;&nbsp;<input type="checkbox" name="wr97" value="on" <?php if($wr9[6] == "on")	 echo "checked"; ?>>3교대&nbsp;&nbsp;시간 <input type="text" name="wr98" value="<?php echo $wr9[7]?>" id="wr98" class="frm_input" size="5" placeholder="시간"> ~ <input type="text" name="wr99" value="<?php echo $wr9[8]?>" id="wr99" class="frm_input" size="5" placeholder="시간">
        </td>
    </tr>
    <tr>
        <th scope="row">접수방식</th>
        <td>
구직등록자 : 전화문의, 미등록자 : 직접방문후 상담
        </td>
    </tr>
    <tr>
        <th scope="row">준비서류</th>
        <td>
<input type="checkbox" name="wr73" value="on" <?php if($wr7[2] == "on")	 echo "checked"; ?>>이력서&nbsp;&nbsp;<input type="checkbox" name="wr74" value="on" <?php if($wr7[3] == "on")	 echo "checked"; ?>>자기소개서&nbsp;&nbsp;<input type="checkbox" name="wr75" value="on" <?php if($wr7[4] == "on")	 echo "checked"; ?>>주민등록등본&nbsp;&nbsp;<input type="checkbox" name="wr76" value="on" <?php if($wr7[5] == "on")	 echo "checked"; ?>>사진&nbsp;&nbsp;<input type="checkbox" name="wr77" value="on" <?php if($wr7[6] == "on") echo "checked"; ?>>기타&nbsp;&nbsp;<input type="text" name="wr78" value="<?php echo $wr7[7]?>" id="wr78" class="frm_input" size="10" placeholder="기타">
        </td>
    </tr>
    <tr>
        <th scope="row">가입보험</th>
        <td>
<input type="checkbox" name="wr25" value="on" <?php if($wr2[4] == "on")	 echo "checked";?>>고용보험&nbsp;&nbsp;<input type="checkbox" name="wr26" value="on" <?php if($wr2[5] == "on")	echo "checked";?>>산재보험&nbsp;&nbsp;<input type="checkbox" name="wr27" value="on" <?php if($wr2[6] == "on")	 echo "checked"; ?>>건강보험&nbsp;&nbsp;<input type="checkbox" name="wr28" value="on" <?php if($wr2[7] == "on")	 echo "checked"; ?>>연금보험
        </td>
    </tr>
    <tr>
        <th scope="row">요구사항</th>
        <td>
<input type="text" name="wr34" value="<?php echo $wr3[3]?>" id="wr34" class="frm_input full_input" size="50" placeholder="그 밖의 요구사항"> 
        </td>
    </tr>
    <tr>
        <th scope="row">마감일</th>
        <td>
   <SELECT onchange="javascript:resetday('from');" name="fyear">
    <?php echo $htm_fyear?>
    </SELECT> 년
	<SELECT onchange="javascript:resetday('from');" name="fmon">
    <?php echo $htm_fmon?>
    </SELECT> 월
	<SELECT onchange="javascript:resetday('from');" name="fday">
    <?php echo $htm_fday?>
    </SELECT> 일
    <input type="hidden" name='wr71' value='<?php echo ($wr7[0])?$wr7[0]:$f_date?>'>

<input type="radio"	name="wr72" value="상시모집" <?php if($wr7[1] == "상시모집") echo "checked"; ?>	onclick="enddaychk(this);">상시모집&nbsp;<input	type="radio" name="wr72" <?php if($wr7[1] == "채용시까지" || !$w) echo "checked"; ?>	value="채용시까지" onClick="enddaychk(this);">채용시까지 <input	type="radio" name="wr72" <?php if($wr7[1] == "날짜입력") echo "checked"; ?>	value='날짜입력' onClick="enddaychkfalse();">날짜입력
        </td>
    </tr>
    <tr>
        <th scope="row">모집인원</th>
        <td>
<input type="text" name="wr81" value="<?php echo $wr8[0]?>" id="wr81" class="frm_input" size="5" placeholder="인원">명 
        </td>
    </tr>
    <tr>
        <th scope="row">근무지</th>
        <td>
<input type="text" name="wr93" value="<?php echo $wr9[2]?>" id="wr93" required class="frm_input full_input required" placeholder="근무지">
        </td>
    </tr>
    <tr>
        <th scope="row">연령</th>
        <td>
<input type="text" name="wr83" value="<?php echo $wr8[2]?>" id="wr83" class="frm_input" size="5" placeholder="연령">세 이상 ~ <input type="text" name="wr84" value="<?php echo $wr8[3]?>" id="wr84" class="frm_input" size="5" placeholder="연령">세 이하 
<input type="checkbox" name="wr85" value="무관"	onclick="agechk(this);" <?php if($wr8[4] == "무관") echo "checked"; ?>> 무관
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="wr_content" class="sound_only">상세정보<strong>필수</strong></label></th>
        <td>
        
        <div class="wr_content <?php echo $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
            <?php if($write_min || $write_max) { ?>
            <!-- 최소/최대 글자 수 사용 시 -->
            <p id="char_count_desc">이 게시판은 최소 <strong><?php echo $write_min; ?></strong>글자 이상, 최대 <strong><?php echo $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
            <?php } ?>
            <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
            <?php if($write_min || $write_max) { ?>
            <!-- 최소/최대 글자 수 사용 시 -->
            <div id="char_count_wrap"><span id="char_count"></span>글자</div>
            <?php } ?>
        </div> 
        </td>
    </tr>
    <tr>
        <th scope="row">문의번호</th>
        <td>

<input type="text" name="wr53" value="<?php echo $wr5[2]?>" id="wr53" required class="frm_input full_input required" placeholder="문의번호"> 
        </td>
    </tr>                                                                                          
	</tbody>
    </table>
</div>
    <div class="bo_w_link write_div">
        <label for="wr_link1"><i class="fa fa-link" aria-hidden="true"></i><span class="sound_only"> 회사사이트</span></label>
        <input type="text" name="wr_link1" value="<?php if($w=="u"){echo$write['wr_link1'];} ?>" id="wr_link1" class="frm_input full_input" size="50" placeholder="회사사이트">
    </div>

    <?php for ($i=0; $is_file && $i<$file_count; $i++) { ?>
    <div class="bo_w_flie write_div">
        <div class="file_wr write_div">
            <label for="bf_file_<?php echo $i+1 ?>" class="lb_icon"><i class="fa fa-download" aria-hidden="true"></i><span class="sound_only"> 파일 #<?php echo $i+1 ?></span></label>
            <input type="file" name="bf_file[]" id="bf_file_<?php echo $i+1 ?>" title="파일첨부 <?php echo $i+1 ?> : 용량 <?php echo $upload_max_filesize ?> 이하만 업로드 가능" class="frm_file ">
        </div>
        <?php if ($is_file_content) { ?>
        <input type="text" name="bf_content[]" value="<?php echo ($w == 'u') ? $file[$i]['bf_content'] : ''; ?>" title="파일 설명을 입력해주세요." class="full_input frm_input" size="50" placeholder="파일 설명을 입력해주세요.">
        <?php } ?>

        <?php if($w == 'u' && $file[$i]['file']) { ?>
        <span class="file_del">
            <input type="checkbox" id="bf_file_del<?php echo $i ?>" name="bf_file_del[<?php echo $i;  ?>]" value="1"> <label for="bf_file_del<?php echo $i ?>"><?php echo $file[$i]['source'].'('.$file[$i]['size'].')';  ?> 파일 삭제</label>
        </span>
        <?php } ?>
        
    </div>
    <?php } ?>


    <?php if ($is_use_captcha) { //자동등록방지  ?>
    <div class="write_div">
        <?php echo $captcha_html ?>
    </div>
    <?php } ?>


    <div class="btn_confirm write_div">
        <a href="./board.php?bo_table=<?php echo $bo_table ?>" class="btn_cancel btn">취소</a>
        <input type="submit" value="작성완료" id="btn_submit" accesskey="s" class="btn_submit btn">
    </div>
    </form>

    <script>
$(function(){
      //직접입력 인풋박스 기존에는 숨어있다가
<?php if($wr5[0] == "기타") {?>
$("#wr52").show();
<?php }else{ ?>
$("#wr52").hide();
<?php }?>
$("#wr51").change(function() {
                //직접입력을 누를 때 나타남
		if($("#wr51").val() == "기타") {
			$("#wr52").show();
		}  else {
			$("#wr52").hide();
		}
	}) 
});

<?php if(!$w){?>
		document.fwrite.fyear.disabled=true;
		document.fwrite.fmon.disabled=true;
		document.fwrite.fday.disabled=true;
<?php }?>
	
function enddaychk(tue){
	if(tue.checked){
		document.fwrite.fyear.disabled=true;
		document.fwrite.fmon.disabled=true;
		document.fwrite.fday.disabled=true;

	}else{
		document.fwrite.fyear.disabled=false;
		document.fwrite.fmon.disabled=false;
		document.fwrite.fday.disabled=false;
	}
}



function enddaychkfalse(val){
		document.fwrite.fyear.disabled=false;
		document.fwrite.fmon.disabled=false;
		document.fwrite.fday.disabled=false;
}



function agechk(gaw){

	if(gaw.checked){
		document.fwrite.wr83.disabled=true;
		document.fwrite.wr84.disabled=true;
		document.fwrite.wr83.style.background = "eeeeee";
		document.fwrite.wr84.style.background = "eeeeee";
		document.fwrite.wr83.value	=	"";
		document.fwrite.wr84.value	=	"";
	}else{
		document.fwrite.wr83.disabled=false;
		document.fwrite.wr84.disabled=false;
		document.fwrite.wr83.style.background = "ffffff";
		document.fwrite.wr84.style.background = "ffffff";
		document.fwrite.wr83.value	=	"";
		document.fwrite.wr84.value	=	"";
	}
}

    function resetday(a_val)
    {
        if (a_val=="from") {
            fwrite.wr71.value = fwrite.fyear.value+fwrite.fmon.value+fwrite.fday.value;
        }

		if (a_val=="all") {
			fwrite.wr71.value = fwrite.fyear.value+fwrite.fmon.value+fwrite.fday.value;
		}
    }
		
    <?php if($write_min || $write_max) { ?>
    // 글자수 제한
    var char_min = parseInt(<?php echo $write_min; ?>); // 최소
    var char_max = parseInt(<?php echo $write_max; ?>); // 최대
    check_byte("wr_content", "char_count");

    $(function() {
        $("#wr_content").on("keyup", function() {
            check_byte("wr_content", "char_count");
        });
    });

    <?php } ?>
    function html_auto_br(obj)
    {
        if (obj.checked) {
            result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
            if (result)
                obj.value = "html2";
            else
                obj.value = "html1";
        }
        else
            obj.value = "";
    }

    function fwrite_submit(f)
    {
        <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

        var subject = "";
        var content = "";
        $.ajax({
            url: g5_bbs_url+"/ajax.filter.php",
            type: "POST",
            data: {
                "subject": f.wr_subject.value,
                "content": f.wr_content.value
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data, textStatus) {
                subject = data.subject;
                content = data.content;
            }
        });

        if (subject) {
            alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
            f.wr_subject.focus();
            return false;
        }

        if (content) {
            alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
            if (typeof(ed_wr_content) != "undefined")
                ed_wr_content.returnFalse();
            else
                f.wr_content.focus();
            return false;
        }

        if (document.getElementById("char_count")) {
            if (char_min > 0 || char_max > 0) {
                var cnt = parseInt(check_byte("wr_content", "char_count"));
                if (char_min > 0 && char_min > cnt) {
                    alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
                    return false;
                }
                else if (char_max > 0 && char_max < cnt) {
                    alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
                    return false;
                }
            }
        }

        <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
    </script>
</section>
<!-- } 게시물 작성/수정 끝 -->