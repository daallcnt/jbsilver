<?php
include_once('../common.php');
$gr_id = "sub10";
$bo_table = "sub10_04";
include_once(G5_PATH.'/head.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_SKIN_URL.'/board/basic/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_URL.'/edu/style.css">', 0);

//if (!$is_member)
//    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_URL."/complet/index.php"));

?>
<p>
<?php
switch($case){
 case('list'): 
    include "c_list.php";
 break;
 case('list2'): 
    include "c_list2.php";
 break;
 case('list3'): 
    include "c_list3.php";
 break;   
default:
	include "c_search.php";
 break;
}
?>
</p> 
<?php include_once(G5_PATH.'/tail.php'); ?>