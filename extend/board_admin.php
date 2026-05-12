<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
//게시판 관리자 여러명으로 정하기 
if($is_member && $board['bo_admin']){ 
 $tmpArr= explode(',', $board['bo_admin']); 
 if( in_array( $member['mb_id'], $tmpArr)){ $board['bo_admin']=$member['mb_id']; $is_admin = 'board'; } 
} 


//그룹 관리자 여러명으로 정하기 
if($is_member && $group['gr_admin']){ 
 $tmpArr= explode(',', $group['gr_admin']); 
 if( in_array( $member['mb_id'], $tmpArr)){ $group['gr_admin']=$member['mb_id']; $is_admin = 'group'; } 
}


// 주요기능 : 회원관리자에서 삭제된 회원을 완전히 삭제함..
if ($is_admin=='super' && strstr($PHP_SELF,"/member_list_update.php") && count($chk) &&!$g5[proc_member_list_delete] ) {
	$is_delete=false;
	$g5[proc_member_list_delete]=true;
	$achk=array();
	for ($i=0; $i<count($chk); $i++) 
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];
		$mb = get_member($_POST['mb_id'][$k]);
		if ($mb[mb_level]==1 && !$mb[mb_password]) {
			$sql2="delete from $g5[member_table] where mb_id='$mb[mb_id]'";
			sql_query($sql2);
			$is_delete=true;
		}
		else {
			$achk[]=$k;
		}
	}
	if ($is_delete) {
		$chk=$achk;
	}
}
?>