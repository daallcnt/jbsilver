<?php
include_once('../common.php');
$g5['title'] = '영수증';
include_once(G5_PATH.'/head.sub.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_SKIN_URL.'/board/basic/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="./style.css">', 0);

if (!$is_member)
    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_URL."/edu/index.php"));

$row = sql_fetch(" select * from wp_edu where e_id = '$e_id' ");
if(!$row['name']) { alert("신청자가 존재하지 않습니다."); }

$rows = sql_fetch(" select * from wp_education where s_id = '$row[s_id]' ");
?>
<span id="d1">
<p>&nbsp;</p>
<center>
<div style="width:400px; font-weight:bold; font-family:Nanum Gothic,돋움,Dotum,굴림,Gulim,Helvetica,AppleGothic, sans-serif; font-size:14px; letter-spacing:0.1em; line-height:190%; padding:30px; border:1px solid #000;">
<p style="font-size:28px; font-weight:bold; text-align:center; margin-bottom:30px;"><u> 영&nbsp; &nbsp;수&nbsp; &nbsp;증 </u></p>
    <table width="100%" cellspacing="0" cellpadding="4" border="0">
        <colgroup>
            <col width="90px" />
            <col width="*" />
        </colgroup>
        <tr>
            <td style="font-size:13px; font-weight:bold; height:40px;">- 내 역 : </td>
            <td style="font-size:13px; font-weight:bold;"><?php echo $rows['subject']?></td>
        </tr>
		<tr>
            <td style="font-size:13px; font-weight:bold; height:40px;">- 일 자 : </td>
            <td style="font-size:13px; font-weight:bold;"><?php echo substr($row['schedule'],0,4)?>년 <?php echo substr($row['schedule'],5,2)?>월 <?php echo substr($row['schedule'],8,2)?>일 <?php echo ($row['schedule'] != $row['edate'] && $row['edate'])? " ~ ".substr($row['edate'],0,4)."년 ".substr($row['edate'],5,2)."월 ".substr($row['edate'],8,2)."일":"";?></td>
        </tr>
        <tr>
            <td style="font-size:13px; font-weight:bold; height:36px;">- 금 액 : </td>
            <td style="font-size:13px; font-weight:bold;"><?php echo ($row['edu']=="회원"?$rows['edu_06']:$rows['edu_03'])?></td>
        </tr>
    </table>    
	<div style="margin:70px 0 30px 0px; text-align:center;">위 금액을 정히 영수합니다.</div>
	<P><?php echo substr($row['schedule'],0,4)?>년 &nbsp;&nbsp;&nbsp; <?php //echo substr($row['schedule'],5,2)?>월 &nbsp;&nbsp;&nbsp; <?php //echo substr($row['schedule'],8,2)?>일</P>
    <div style="text-align:left; margin:50px 0 30px 20px; position:relative;">
  <div style="position:absolute; top:-55px; right:50px;"><img src="../img/sg.png" border="0" width="97px" alt="원본 대조필 도장" /></div>    
    영 수 자 : 전라북도사회복지협의회 (인)
    <br />
    사업자등록번호 : 402-82-07355
    <br />
    전화번호 : (063)224-1861  FAX: (063)224-1863
    <br />
    주 &nbsp; 소 : 전주시 덕진구 전주천동로 483
    </div>
</div>
</center>
</span>
<br />
<p align="center">
<a href="javascript:printDiv('d1');" class="btn_b01">인 쇄</a> 
</p>
<?php include_once(G5_PATH.'/tail.sub.php'); ?>