<?php
$sub_menu = '750100';
include_once('./_common.php');

if ($w == "u")
    check_demo();

auth_check($auth[$sub_menu], "w");

//check_admin_token();


$sql_common = " s_id       = '$s_id',
				id         = '$id',
                name       = '$name',
                mobile     = '$mobile',
                subject    = '$subject',
				schedule   = '$schedule',
				edate   = '$edate',
                person     = '$person',
                corp       = '$corp',
				etc      = '$etc',
                phone      = '$phone',
                progress   = '$progress',
				edu        = '$edu',
				payment    = '$payment' ";

if ($w == "")
{
    $sql = " select s_id from wp_edu where s_id = '$s_id' and id = '$id' ";
    $row = sql_fetch($sql);
    if ($row['s_id'])
        alert("이미 해당 교육을 신청하셨습니다.");

    $sql = " insert wp_edu 
                set wdate = '".G5_TIME_YMDHIS."',
                    $sql_common ";
    sql_query($sql);

	
	$e_id = sql_insert_id();
}
else if ($w == "u")
{	
    $sql = " update wp_edu  
                set mdate = '".G5_TIME_YMDHIS."',
					$sql_common
              where e_id = '$e_id' ";
    sql_query($sql);
}

if ($w == "u")
{
   alert("수정되었습니다.","./edumember_form.php?$qstr&w=u&e_id={$e_id}");  
}
else
{
    goto_url("./edumember_list.php?$qstr&s_id={$s_id}");
}
?>