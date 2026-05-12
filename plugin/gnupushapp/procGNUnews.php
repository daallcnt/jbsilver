<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$sql_p = "select * from g5_write_guide where wr_is_comment = '0' order by wr_datetime desc limit 5 ";
$wr_p = sql_query($sql_p);
$array = array();
for ($i=0; $row_p=sql_fetch_array($wr_p); $i++)
{
	$array['title'.$i] = $row_p['wr_subject'];
	$array['link'.$i] = G5_BBS_URL.'/board.php?bo_table=guide&wr_id='.$row_p['wr_id'];
	$array['date'.$i] = date("Y-m-d", strtotime( $row_p['wr_datetime'] ) );
}

$array["response"] = "ok";

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();

?>