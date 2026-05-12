<?php
$sub_menu = "200100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');
$iso = "ok";
$g5['title'] = '회원관리';
include_once('./admin.head.php');

add_stylesheet('<link rel="stylesheet" href="'.G5_URL.'/member/style.css">', 0);
//add_stylesheet('<link rel="stylesheet" href="'.G5_URL.'/skin/member/basic/style.css">', 0);
    if (!$is_member)
        alert('로그인 후 이용하여 주십시오.', G5_URL);

$mb_id = $mb_id;
?>

<p>&nbsp;</p>
<p>
<?php
switch($case){
 case('write'): 
    include_once (G5_PATH."/member/write.php");
 break; 
 case('view'): 
    include_once (G5_PATH."/member/view.php");
 break;      
default:
	include_once (G5_PATH."/member/view.php");
 break;
}
?>
</p> 
<?php include_once ('./admin.tail.php'); ?>