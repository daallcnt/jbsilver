<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

function b_sc($skin_dir='', $bo_table, $rows=10, $subject_len=40, $so='')
{
    global $g5, $is_member;
    static $css = array();

	$to_day = date("Ymd"); 

    if (!$skin_dir) $skin_dir = 'basic';

    if(G5_IS_MOBILE) {
        $latest_skin_path = G5_MOBILE_PATH.'/'.G5_SKIN_DIR.'/latest/'.$skin_dir;
        $latest_skin_url  = G5_MOBILE_URL.'/'.G5_SKIN_DIR.'/latest/'.$skin_dir;
    } else {
        $latest_skin_path = G5_SKIN_PATH.'/latest/'.$skin_dir;
        $latest_skin_url  = G5_SKIN_URL.'/latest/'.$skin_dir;
    }

    $cache_file = G5_DATA_PATH."/cache/latest-{$bo_table}-{$skin_dir}-{$rows}-{$subject_len}-a{$so}.php";
    if (!G5_USE_CACHE || !file_exists($cache_file)) {
        $list = array();

        $sql = " select * from {$g5['board_table']} where bo_table = '{$bo_table}' ";
        $board = sql_fetch($sql);

        $tmp_write_table = $g5['write_prefix'] . $bo_table; // 게시판 테이블 전체이름

    	$sql = "select * from $tmp_write_table where wr_is_comment = 0 ";
     	$sql .= ($so == "1") ? " and (wr_1 <= '$to_day'  and wr_2 >= '$to_day') " : "";
		$sql .= ($so == "2") ? " and (wr_2 < '$to_day') " : "";
		$sql .= ($so == "3") ? " and (wr_1 > '$to_day') " : "";

		$sql .= " order by wr_datetime desc limit 0, {$rows} ";
        $result = sql_query($sql);
        for ($i=0; $row = sql_fetch_array($result); $i++) {
            $list[$i] = get_list($row, $board, $latest_skin_url, $subject_len);
        }

        $handle = fopen($cache_file, 'w');
        $cache_content = "<?php\nif (!defined('_GNUBOARD_')) exit;\n\$bo_subject=\"".get_text($board['bo_subject'])."\";\n\$list=".var_export($list, true)."?>";
        fwrite($handle, $cache_content);
        fclose($handle);
    }

    include_once($cache_file);

    ob_start();
    include $latest_skin_path.'/latest.skin.php';
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}
function jbba_api($url, $rows=5, $subject_len=40, $mucd = '')
{
    global $g5;

	if($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);
	curl_close($ch);

	$rss = simplexml_load_string($result); 

	$str = "<div class='lt'><ul>";
	$ym = 0;
	foreach($rss->channel->item as $chan) {  
		if($ym < $rows) {
			  $pubdate = date('Y-m-d H:i:s', strtotime($chan->pubDate)); 
			  $arr_date = substr($pubdate, 0,4)."-".substr($pubdate, 5,2)."-".substr($pubdate, 8,2);
	  			
			$str .= "<li><a href=\"". str_replace("&amp;","&",$chan->link) ."&menuCd=".$mucd."\" target='_blank'>";
			$str .=  cut_str($chan->title,$subject_len);    
			//if(G5_IS_MOBILE) {
			//$str .= "</a></li>\n";
			//}else{
			$str .= "</a><span class='datatime'>".$arr_date."</span></li>\n";
			//}
		}
		$ym++;
	} 
	if ($ym == 0) {
	$str .= "<li>게시물이 없습니다.</li>";	
	}
	
	$str .= "</ul>";
	if(G5_IS_MOBILE) {
	$str .= "<div class='lt_more'><a href='http://www.jeonbuk.go.kr/board/list.jeonbuk?boardId=JEONBUK_NOTICE&menuCd=DOM_000000102001001001&contentsSid=3086&cpath=' target='_blank'><img src='".G5_IMG_URL."/mobile/main_board_more01.gif'></a></div>";
    }else{
	$str .= "<div class='lt_more'><a href='http://www.jeonbuk.go.kr/board/list.jeonbuk?boardId=JEONBUK_NOTICE&menuCd=DOM_000000102001001001&contentsSid=3086&cpath=' target='_blank'><img src='".G5_IMG_URL."/mobile/main_board_more01.gif'></a></div>";		
	}
    $str .= "</div>";
	}
	return $str;
}
?>