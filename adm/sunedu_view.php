<?php
$sub_menu = "750100";
include_once('./_common.php');

auth_check($auth['$sub_menu'], "r");

$html_title = "교육관리";
$g5['title'] = $html_title;
$bo_table = "education";
if(!$s_id){
	alert("제대로 된 값이 넘어오지 않았습니다.");
}

	$ss_name = 'ss_view_'.$bo_table.'_'.$s_id;
    if (!get_session($ss_name))
    {
        set_session($ss_name, TRUE);
    }


$qstr  = "$qstr&sca=$sca&page=$page";

    $sql = " select * from wp_education where s_id = '$s_id' ";
    $row = sql_fetch($sql);


	$r = sql_fetch(" select sum(person) as tot from wp_edu where s_id = '$row[s_id]' and progress <> '취소' "); 

			$SumPerson = ($r['tot'])? $r['tot'] : "0" ;
		 	$PersonWating =  $row['person'] + $row['wating'];
			if($row['s_chk'] != 1) {
				if($SumPerson < $row['person']){
					if(date("Y-m-d") < $row['s_day']){
						$progress = "준비";
					}elseif(date("Y-m-d") > $row['f_day']){
						$progress = "마감";
					}else{
						$progress = "신청";
					}
				}elseif($SumPerson < $PersonWating){
					if(date("Y-m-d") < $row['s_day']){
						$progress = "준비";
					}elseif(date("Y-m-d") > $row['f_day']){
						$progress = "마감";
					}else{
						$progress = "대기";
					}
				}else{
					$progress = "마감";
				}
			}else{
			$progress = "마감";
			}

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
        <th scope="row">교육정원</th>
        <td><?php echo $row['person']?> 명</td> 
    </tr>
    <tr>
        <th scope="row">대기인원</th>
        <td><?php echo $row['wating']?> 명</td> 
    </tr>        
    <tr>
        <th scope="row">교육일시</th>
        <td><?php echo $row['schedule']?> <?php echo ($row['schedule'] != $row['edate'] && $row['edate'])? " ~ ".$row['edate']:"";?></td>
    </tr>
    <tr>
        <th scope="row">교육장소</th>
        <td><?php echo $row['place']?></td>
    </tr>
    <tr>
        <th scope="row">접수기간</th>
        <td><?php echo $row['s_day']?> ~ <?php echo $row['f_day']?></td>
    </tr> 
    <tr>
        <th scope="row">강사명</th>
        <td><?php echo $row['edu_01']?></td>
    </tr>  
    <tr>
        <th scope="row">접수일시</th>
        <td><?php echo $row['edu_02']?></td>
    </tr>
    <tr>
        <th scope="row">비회원교육비</th>
        <td><?php echo $row['edu_03']?></td>
    </tr>
    <tr>
        <th scope="row">회원교육비</th>
        <td><?php echo $row['edu_06']?></td>
    </tr>    
    <tr>
        <th scope="row">입금계좌번호</th>
        <td><?php echo $row['edu_04']?></td>
    </tr>        
    <tr>
        <th scope="row">입금기한</th>
        <td><?php echo $row['edu_05']?></td>
    </tr>             
    <tr>
        <th scope="row">교육내용</th>
        <td><?php echo conv_content($row['contents'], 1); ?></td>
    </tr>
     <?php
	 $file = get_file($bo_table, $row['s_id']);
		if ($file['count']) {
			$cnt = 0;
			for ($i=0; $i<count($file); $i++) {
				if (isset($file[$i]['source']) && $file[$i]['source'])
					$cnt++;
			}
		}	 	 	  	 
	 	  
      if($cnt) {        
        // 가변 파일
        for ($i=0; $i<count($file); $i++) {
            if (isset($file[$i]['source']) && $file[$i]['source']) {
         ?>

        <tr>
            <th scope="row">첨부파일</th>
            <td> 
                 <a href="<?php echo $file[$i]['href'];  ?>" class="view_file_download">
                    <strong><?php echo $file[$i]['source'] ?></strong> (<?php echo $file[$i]['size'] ?>)
                </a>
            </td>
        </tr>
        <?php
            }
        }      
      } ?> 
         
    <tr>
        <th scope="row">현황</th>
        <td><?php echo $progress?></td>
    </tr>
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
    <a href="./sunedu_list.php?<?php echo $qstr?>">목록</a>
    <a href="./edumember_form.php?s_id=<?php echo $row['s_id']?>">교육신청</a>
    <a href="./edumember_list.php?s_id=<?php echo $row['s_id']?>">신청명단</a>
</div>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
