<?php
include_once('./_common.php');

$count_write = 0;
$count_comment = 0;

$tmp_array = array();
if ($wr_id) // 건별삭제
    $tmp_array[0] = $wr_id;
else // 일괄삭제
    $tmp_array = $_POST['chk_wr_id'];


// 거꾸로 읽는 이유는 답변글부터 삭제가 되어야 하기 때문임
for ($i=count($tmp_array)-1; $i>=0; $i--) 
{
    $write = sql_fetch(" select * from $write_table where wr_id = '{$tmp_array[$i]}' ");

    if ($is_admin == "super") // 최고관리자 통과
        ;
    else if ($is_admin == "group") // 그룹관리자
    {
        $mb = get_member($write[mb_id]);
        if ($member[mb_id] == $group[gr_admin]) // 자신이 관리하는 그룹인가?
        {
            if ($member[mb_level] >= $mb[mb_level]) // 자신의 레벨이 크거나 같다면 통과
                ;
            else
                continue;
        } 
        else
            continue;
    } 
    else if ($is_admin == "board") // 게시판관리자이면
    {
        $mb = get_member($write[mb_id]);
        if ($member[mb_id] == $board[bo_admin]) // 자신이 관리하는 게시판인가?
            if ($member[mb_level] >= $mb[mb_level]) // 자신의 레벨이 크거나 같다면 통과
                ;
            else
                continue;
        else
            continue;
    } 
    else if ($member[mb_id] && $member[mb_id] == $write[mb_id]) // 자신의 글이라면
    {
        ;
    } 
    else if ($wr_password && !$write[mb_id] && sql_password($wr_password) == $write[wr_password]) // 패스워드가 같다면
    {
        ;
    } 
    else
        continue;   // 나머지는 삭제 불가


    // 게시글
    sql_query(" update $write_table set wr_10 = '채용완료' where wr_id = '$write[wr_id]' ");


}


goto_url("./board.php?bo_table=$bo_table&page=$page" . $qstr);
?>
