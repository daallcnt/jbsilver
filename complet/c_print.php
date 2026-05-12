<?php
include_once('../common.php');
$g5['title'] = '수료증';
include_once(G5_PATH.'/head.sub.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_SKIN_URL.'/board/basic/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="./style.css">', 0);

//if (!$is_member)
//    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_URL."/complet/index.php"));


$sql = " select * from wp_completion where c_id = '$c_id'  ";
$row = sql_fetch($sql);
if(!$row['name']) { alert("수료자가 존재하지 않습니다."); }

function completion_certificate_date($row)
{
    foreach (array($row['schedule'], $row['edate']) as $date_text) {
        if (!$date_text) {
            continue;
        }

        if (preg_match('/([0-9]{4})[.\-\/]\s*([0-9]{1,2})[.\-\/]\s*([0-9]{1,2})/', $date_text, $match)) {
            return sprintf('%04d년 %02d월 %02d일', $match[1], $match[2], $match[3]);
        }
    }

    return '';
}

$certificate_date = completion_certificate_date($row);
?>
<span id="d1">
<center>
<p style="margin-top:50px;"><img src="print_back_top.gif"></p>
<div style="width:640px; font-weight:bold; font-size:16px; line-height:190%; padding:10px 50px 20px 50px; background:url('print_back.gif') left top repeat-y;">

<p style="font-size:16px; font-weight:bold; height:60px; text-align:left;">발급번호 : <?php echo $row['cno']?></p>
<p style="font-size:60px; font-weight:bold; font-family:궁서체; text-align:center;"> 수 료 증 </p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
    <table width="98%" cellspacing="0" cellpadding="4" border="0">
        <colgroup>
            <col width="100px" />
            <col width="*" />
        </colgroup>
        <tr>
            <td style="font-size:20px; font-weight:bold; height:50px;">성 &nbsp; &nbsp; &nbsp;명 : </td>
            <td style="font-size:20px; font-weight:bold;"><?php echo $row['name']?></td>
        </tr>
        <?php if($row['corp']) { ?>
        <tr>
            <td style="font-size:20px; font-weight:bold; height:50px;">소 &nbsp; &nbsp; &nbsp;속 : </td>
            <td style="font-size:20px; font-weight:bold;"><?php echo $row['corp']?></td>
        </tr>
        <?php }?>
        <tr>
            <td style="font-size:20px; font-weight:bold; height:50px;">교육과정 : </td>
            <td style="font-size:18px; font-weight:bold; letter-spacing:-0.05em;"><?php echo $row['subject']?></td>
        </tr>
        <tr>
            <td style="font-size:20px; font-weight:bold; height:50px;">교육일시 : </td>
            <td style="font-size:20px; font-weight:bold;"><?php echo $row['schedule']?></td>
        </tr>                
    </table>
	<div style="margin:50px 10px 30px 10px; text-align:center;font-size:24px; line-height:150%">위 사람은 전북노인일자리센터에서 운영한<br />상기 교육과정에 참여하여 성실히 교육에 임하고<br />
수료하였기에 이 증서를 드립니다.</div>
    <p>&nbsp;</p>
		<P style="font-size:20px;"><?php echo $certificate_date ?></P>
    <p>&nbsp;</p>
    <table width="78%" cellspacing="0" cellpadding="4" border="0" style="margin:30px; position:relative;">
        <colgroup>
            <col width="*" />
        </colgroup>
        <tr>
            <td><div style="font-size:38px; font-weight:bold; text-align:center;position:absolute; z-index:20">전북노인일자리센터장</div><div style="position:absolute; top:-20px; right:0px; z-index:10"><img src="../img/sg.png" border="0" width="97px" alt="원본 대조필 도장" /></div></td>
        </tr>                
    </table>    
<p style="padding-bottom:20px;">&nbsp;</p>
</div>
<p><img src="print_back_bottom.gif"></p>
</center>
</span>

<p align="center" style="margin:20px 0;">
<a href="javascript:printDiv('d1');" style="display:inline-block;background:#4c4f6f;color:#fff;padding:10px 20px;font-size:15px; font-weight:bold;">인 쇄</a> 
</p>
<?php include_once(G5_PATH.'/tail.sub.php'); ?>
