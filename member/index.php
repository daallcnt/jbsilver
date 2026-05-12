<?php
include_once('./_common.php');
$gr_id = "";
$co_id = "";
include_once(G5_PATH.'/head.php');
add_stylesheet('<link rel="stylesheet" href="./style.css">', 0);
//add_stylesheet('<link rel="stylesheet" href="'.G5_URL.'/skin/member/basic/style.css">', 0);
    if (!$is_member)
        alert('로그인 후 이용하여 주십시오.', G5_URL);

$mb_id = $member['mb_id'];
?>

<p>&nbsp;</p>
<p>
<?php
switch($case){
 case('write'): 
    include "write.php";
 break; 
 case('view'): 
    include "view.php";
 break;      
default:
	include "write.php";
 break;
}
?>
</p> 
<?php include_once(G5_PATH.'/tail.php'); ?>