<?php
$sub_menu = "750100";
include_once('./_common.php');

auth_check($auth['$sub_menu'], "r");

$html_title = "교육신청";
$g5['title'] = $html_title;

$sql = " select * from wp_edu where e_id = '$e_id' ";
$row = sql_fetch($sql);
  if (!$row['e_id']) 
        alert("신청자가 없습니다.");

if (isset($s_id)) $qstr .= "&s_id=$s_id";

include_once (G5_ADMIN_PATH.'/admin.head.php');
?>
<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">교육명</th>
        <td><?php echo $row['subject']?></td>
    </tr>
    <tr>
        <th scope="row">교육일시</th>
        <td><?php echo $row['schedule']?><?php echo ($row['schedule'] != $row['edate'])? " ~ ".$row['edate']:"";?></td>
    </tr>
    <tr>
        <th scope="row">아이디</th>
        <td><?php echo $row['id']?></td> 
    </tr>
    <tr>
        <th scope="row">신청자</th>
        <td><?php echo $row['name']?></td> 
    </tr>        

    <tr>
        <th scope="row">연락처</th>
        <td><?php echo $row['mobile']?></td>
    </tr>
    <tr>
        <th scope="row">교육인원</th>
        <td><?php echo $row['person']?>명</td>
    </tr> 
    <tr>
        <th scope="row">기관명</th>
        <td><?php echo $row['corp']?></td>
    </tr> 
    <tr>
        <th scope="row">비고</th>
        <td><?php echo $row['etc']?></td>
    </tr>      
    <tr>
        <th scope="row">기관전화</th>
        <td><?php echo $row['phone']?></td>
    </tr>
    <tr>
        <th scope="row">시설구분</th>
        <td><?php echo ($row['edu']==""?"비회원":$row['edu'])?></td>
    </tr>
    <tr>
        <th scope="row">진행상태</th>
        <td><?php echo $row['progress']?></td>
    </tr>
    <tr>
        <th scope="row">결제상태</th>
        <td><?php echo $row['payment']?></td>
    </tr>    
    <?php if($row['payment'] == "완납") {?>
    <tr>
        <th scope="row">영수증</th>
        <td><a href="javascript:GPEN_PRINT('<?php echo $row['e_id']?>');">출력</a></td>
    </tr>
    <?php }?>        
    <tr>
        <th scope="row">입력일시</th>
        <td><?php echo $row['wdate']?></td>
    </tr>
    <tr>
        <th scope="row">수정일시</th>
        <td><?php echo $row['mdate']?></td>
    </tr>                           
    </tbody>
    </table>
</div>  
<div class="btn_confirm01 btn_confirm">
    <a href="./edumember_list.php?s_id=<?php echo $row['s_id']?>">목록</a>
</div>
<script>
function GPEN_PRINT(val){ 
	var p = window.open("<?php echo G5_URL?>/edu/r_print.php?e_id="+val,"Printedu","width=687, height=600, scrollbars=yes"); 
	p.focus(); 
}
</script>
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
