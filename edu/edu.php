<?php
include_once('../common.php');
$gr_id = "sub10";

if($edu=="1"){
	$bo_table = "sub10_05";
}else{
	$bo_table = "sub10_03";
}


include_once(G5_PATH.'/head.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_SKIN_URL.'/board/basic/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="./style.css">', 0);

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
 case('slist'): 
    include "edu_list.php";
 break;
 case('sview'): 
    include "edu_view.php";
 break;  
default:
	include "edu_search.php";
 break;
}
?>
</p> 
<?php include_once(G5_PATH.'/tail.php'); ?>