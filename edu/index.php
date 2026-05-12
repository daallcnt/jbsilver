<?php
include_once('../common.php');
$gr_id = "sub10";
$bo_table = "sub10_01";
include_once(G5_PATH.'/head.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_SKIN_URL.'/board/basic/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_URL.'/edu/style.css">', 0);

//if (!$is_member)
//    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_URL."/edu/index.php"));

?>
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
	include "list.php";
 break;
}
?>
</p> 

<?php include_once(G5_PATH.'/tail.php'); ?>