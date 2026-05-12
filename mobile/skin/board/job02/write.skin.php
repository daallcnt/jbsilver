<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
add_javascript(G5_POSTCODE_JS, 0);
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<section id="bo_w">
    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
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
    <?php
    $option = '';
    $option_hidden = '';
    if ($is_notice || $is_html || $is_secret || $is_mail) {
        $option = '';
        if ($is_notice) {
            $option .= PHP_EOL.'<input type="checkbox" id="notice" name="notice" value="1" '.$notice_checked.'>'.PHP_EOL.'<label for="notice">공지</label>';
        }

        if ($is_html) {
            if ($is_dhtml_editor) {
                $option_hidden .= '<input type="hidden" value="html1" name="html">';
            } else {
                $option .= PHP_EOL.'<input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" value="'.$html_value.'" '.$html_checked.'>'.PHP_EOL.'<label for="html">html</label>';
            }
        }

        if ($is_secret) {
            if ($is_admin || $is_secret==1) {
                $option .= PHP_EOL.'<input type="checkbox" id="secret" name="secret" value="secret" '.$secret_checked.'>'.PHP_EOL.'<label for="secret">비밀글</label>';
            } else {
                $option_hidden .= '<input type="hidden" name="secret" value="secret">';
            }
        }

        if ($is_mail) {
            $option .= PHP_EOL.'<input type="checkbox" id="mail" name="mail" value="mail" '.$recv_email_checked.'>'.PHP_EOL.'<label for="mail">답변메일받기</label>';
        }
    }

    echo $option_hidden;
    ?>
    <div class="form_01 write_div">
        <h2 class="sound_only"><?php echo $g5['title'] ?></h2>


        
        <?php if ($is_name) { ?>
        <div class="write_div">
            <label for="wr_name" class="sound_only">이름<strong>필수</strong></label>
            <input type="text" name="wr_name" value="<?php echo $name ?>" id="wr_name" required class="frm_input full_input required" maxlength="20" placeholder="이름">
        </div>
        <?php } ?>

        <?php if ($is_password) { ?>
        <div class="write_div">
            <label for="wr_password" class="sound_only">비밀번호<strong>필수</strong></label>
            <input type="password" name="wr_password" id="wr_password" <?php echo $password_required ?> class="frm_input full_input <?php echo $password_required ?>" maxlength="20" placeholder="비밀번호">
        </div>
        <?php } ?>



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
        <th scope="row">제목</th>
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
        <th scope="row">연락처</th>
        <td>
<input type="text" name="wr_3" value="<?php echo $write['wr_3']?>" id="wr_3" required class="frm_input full_input required" placeholder="연락처">
        </td>
    </tr>    
    <tr>
        <th scope="row">나이</th>
        <td>
<input type="text" name="wr_6" value="<?php echo $write['wr_6']?>" id="wr_6" size="5" required class="frm_input full_input required" placeholder="나이">
        </td>
    </tr>       
    <tr>
        <th scope="row">희망지역</th>
        <td>
        <select name="wr_1" id="wr_1" required class="frm_input required">
            <option value="">지역을 선택하세요</option>
            <option value="전주시" <?php echo get_selected($write['wr_1'], "전주시")?>>전주시</option>
            <option value="완주군" <?php echo get_selected($write['wr_1'], "완주군")?>>완주군</option>
            <option value="군산시" <?php echo get_selected($write['wr_1'], "군산시")?>>군산시</option>
            <option value="익산시" <?php echo get_selected($write['wr_1'], "익산시")?>>익산시</option>
            <option value="김제시" <?php echo get_selected($write['wr_1'], "김제시")?>>김제시</option>
            <option value="정읍시" <?php echo get_selected($write['wr_1'], "정읍시")?>>정읍시</option>
            <option value="남원시" <?php echo get_selected($write['wr_1'], "남원시")?>>남원시</option>
            <option value="임실군" <?php echo get_selected($write['wr_1'], "임실군")?>>임실군</option>
            <option value="진안군" <?php echo get_selected($write['wr_1'], "진안군")?>>진안군</option>
            <option value="무주군" <?php echo get_selected($write['wr_1'], "무주군")?>>무주군</option>
            <option value="장수군" <?php echo get_selected($write['wr_1'], "장수군")?>>장수군</option>
            <option value="부안군" <?php echo get_selected($write['wr_1'], "부안군")?>>부안군</option>
            <option value="고창군" <?php echo get_selected($write['wr_1'], "고창군")?>>고창군</option>
            <option value="순창군" <?php echo get_selected($write['wr_1'], "순창군")?>>순창군</option>
        </select>
        </td>
    </tr>    
    <tr>
        <th scope="row">희망직종</th>
        <td>
<input type="text" name="wr_2" value="<?php echo $write['wr_2']?>" id="wr_2" required class="frm_input full_input required" placeholder="희망직종">
        </td>
    </tr>
    <tr>
        <th scope="row">희망급여</th>
        <td>
<input type="text" name="wr_4" value="<?php echo $write['wr_4']?>" id="wr_4" required class="frm_input full_input required" placeholder="희망급여">
        </td>
    </tr>
    <tr>
        <th scope="row">희망시간</th>
        <td>
<input type="text" name="wr_5" value="<?php echo $write['wr_5']?>" id="wr_5" required class="frm_input full_input required" placeholder="희망시간">
        </td>
    </tr>    
    <tr>
        <th scope="row">비고</th>
        <td>
<input type="text" name="wr_7" value="<?php echo $write['wr_7']?>" id="wr_7" class="frm_input full_input" placeholder="비고">
        </td>
    </tr>        
    <tr>
        <th>보유<br />자격<br />및<br />경력</th>
        <td>
            <?php if($write_min || $write_max) { ?>
            <!-- 최소/최대 글자 수 사용 시 -->
            <p id="char_count_desc">이 게시판은 최소 <strong><?php echo $write_min; ?></strong>글자 이상, 최대 <strong><?php echo $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
            <?php } ?>
            <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
            <?php if($write_min || $write_max) { ?>
            <!-- 최소/최대 글자 수 사용 시 -->
            <div id="char_count_wrap"><span id="char_count"></span>글자</div>
            <?php } ?>
        </td>
    </tr>                                                                                             
	</tbody>
    </table>
</div>


        
        <?php for ($i=0; $is_file && $i<$file_count; $i++) { ?>
        <div class="bo_w_flie write_div">
            <div class="file_wr write_div">
                <label for="bf_file_<?php echo $i+1 ?>" class="lb_icon"><i class="fa fa-download" aria-hidden="true"></i><span class="sound_only">파일 #<?php echo $i+1 ?></span></label>
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

        <?php if ($is_use_captcha) { //자동등록방지 ?>
        <div class="write_div">
            <span class="sound_only">자동등록방지</span>
            <?php echo $captcha_html ?>
            
        </div>
        <?php } ?>

    </div>

    <div class="btn_top top write_div">
        <a href="./board.php?bo_table=<?php echo $bo_table ?>" class="btn_cancel">취소</a>
        <input type="submit" value="작성완료" id="btn_submit" class="btn_submit" accesskey="s">
    </div>
    </form>
</section>

<script>
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
