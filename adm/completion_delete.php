<?php
$sub_menu = "750300";
include_once("./_common.php");

check_demo();

auth_check($auth[$sub_menu], "d");


check_admin_token();
echo $c_id;
if ($w == "d") 
{	
    $row = sql_fetch(" select c_id, s_id from wp_completion where c_id = '".$c_id."' ");
    if (!$row['c_id'])
        alert("존재하지 않는 게시물입니다.");

	sql_query(" delete from wp_completion where c_id = '$c_id' ");

}
else
    alert("제대로 된 값이 넘어오지 않았습니다.");

//goto_url("./completion_list.php?$qstr");
?>
