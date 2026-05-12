<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

//카테고리로 최신글 보이기
//  사용법 : <?php echo latest_category("최신글스킨", "게시판이름", 게시물수, 제목글자수, "카테고리이름");? >
// 최신글 카테고리 데이타만 추출
function latest_category ($skin_dir="", $bo_table, $rows=10, $subject_len=40, $options="") {
     global $g5;
     //static $css = array();

     if (!$skin_dir) $skin_dir = 'basic';

     if(G5_IS_MOBILE) {
         $latest_skin_path = G5_MOBILE_PATH.'/'.G5_SKIN_DIR.'/latest/'.$skin_dir;
         $latest_skin_url  = G5_MOBILE_URL.'/'.G5_SKIN_DIR.'/latest/'.$skin_dir;
     } else {
         $latest_skin_path = G5_SKIN_PATH.'/latest/'.$skin_dir;
         $latest_skin_url  = G5_SKIN_URL.'/latest/'.$skin_dir;
     }

     $cache_fwrite = false;
     if(G5_USE_CACHE) {
         $cache_file = G5_DATA_PATH."/cache/latest-{$bo_table}-{$options}-category-{$skin_dir}-{$rows}-{$subject_len}.php";

         if(!file_exists($cache_file)) {
             $cache_fwrite = true;
         } else {
             if($cache_time > 0) {
                 $filetime = filemtime($cache_file);
                 if($filetime && $filetime < (G5_SERVER_TIME - 3600 * $cache_time)) {
                     @unlink($cache_file);
                     $cache_fwrite = true;
                 }
             }

             if(!$cache_fwrite)
                 include_once($cache_file);
         }
     }

     if(!G5_USE_CACHE || $cache_fwrite) {
         $list = array();

         $sql = " select * from {$g5['board_table']} where bo_table = '{$bo_table}' ";
         $board = sql_fetch($sql);
         $bo_subject = get_text($board['bo_subject']);

         $tmp_write_table = $g5['write_prefix'] . $bo_table; // 게시판 테이블 전체이름
        $sql = " select * from {$tmp_write_table} where wr_is_comment = 0 and ca_name = '{$options}' order by wr_num limit 0, {$rows} ";
         $result = sql_query($sql);
         for ($i=0; $row = sql_fetch_array($result); $i++) {
             $list[$i] = get_list($row, $board, $latest_skin_url, $subject_len);
         }

         if($cache_fwrite) {
             $handle = fopen($cache_file, 'w');
             $cache_content = "<?php\nif (!defined('_GNUBOARD_')) exit;\n\$bo_subject=\"".$bo_subject."\";\n\$list=".var_export($list, true)."?>";
             fwrite($handle, $cache_content);
             fclose($handle);
         }
     }

     /*
     // 같은 스킨은 .css 를 한번만 호출한다.
     if (!in_array($skin_dir, $css) && is_file($latest_skin_path.'/style.css')) {
         echo '<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">';
         $css[] = $skin_dir;
     }
     */

     ob_start();
     include $latest_skin_path.'/latest.skin.php';
     $content = ob_get_contents();
     ob_end_clean();

     return $content;
}
//   사용법 : <?php echo latest_group("최신글스킨", "그룹이름", 게시물수, 제목글자수, 본문글자수);? >
 //출처: http://sir.co.kr/bbs/board.php?bo_table=g4_skin&wr_id=95895
 function latest_group($skin_dir="", $gr_id, $rows=10, $subject_len=40, $contents_len=200, $category="", $orderby="") {
   global $config;
   global $g5;
  
   $list = array();
   $limitrows = $rows;
  
   $sqlgroup = " select bo_table, bo_subject from $g5[board_table] where gr_id = '$gr_id' and  bo_use_search=1 order by bo_order";  // 해피정닷컴 2014-08-28 수정
  $rsgroup = sql_query($sqlgroup);
   //echo $sqlgroup;
   if ($skin_dir)
     $latest_skin_path = G5_PATH."/skin/latest/$skin_dir";
   else
     $latest_skin_path = G5_PATH."/skin/latest/$config[cf_latest_skin]";
  
   for ($j=0, $k=0; $rowgroup=sql_fetch_array($rsgroup); $j++) {
     $bo_table = $rowgroup[bo_table];
     
     // 테이블 이름구함
    $sql = " select * from {$g5[board_table]} where bo_table = '$bo_table'";
     $board = sql_fetch($sql);
     
     $tmp_write_table = $g5[write_prefix] . $bo_table; // 게시판 테이블 실제이름
    
     // 옵션에 따라 정렬
    $sql = "select * from $tmp_write_table where wr_is_comment = 0 ";
     $sql .= (!$category) ? "" : " and ca_name = '$category' ";
     $sql .= (!$orderby) ? "  order by wr_id desc " : "  order by $orderby desc, wr_id desc ";
     $sql .= " limit $limitrows";
     //echo $sql;
     $result = sql_query($sql);
     
     for ($i=0; $row = sql_fetch_array($result); $i++, $k++) {
       
       if(!$orderby) $op_list[$k] = $row[wr_datetime];
       else  {
         $op_list[$k] = is_string($row[$orderby]) ? sprintf("%-256s", $row[$orderby]) : sprintf("%016d", $row[$orderby]);
         $op_list[$k] .= $row[wr_datetime];
         $op_list[$k] .= $row[wr_name];
         $op_list[$k] .= $row[wr_10];
       }
       
       $list[$k] = get_list($row, $board, $latest_skin_path, $subject_len, $wr_name, $wr_10);
       
       $list[$k][bo_table] = $board[bo_table];
       $list[$k][bo_subject] = $board[bo_subject];
       $list[$k][wr_name] = $board[wr_name];
       $list[$k][wr_10] = $board[wr_10];
       
       $list[$k][bo_wr_subject] = cut_str($board[bo_subject] . $list[$k][wr_subject], $subject_len, $wr_name, $wr_10);
     }
   }
  
   if($k>0) array_multisort($op_list, SORT_DESC, $list);
   if($k>$rows) array_splice($list, $rows);

   ob_start();
   include $latest_skin_path.'/latest.skin.php';
   $content = ob_get_contents();
   ob_end_clean();
   return $content;
}
// 작성일자로 최신글 추출
//   사용법 : <?php echo latest_datetime("최신글스킨", "게시판이름", 게시물수, 제목글자수);? >
 // 작성일자로 최신글 추출
function latest_datetime($skin_dir='', $bo_table, $rows=10, $subject_len=40, $cache_time=1, $options='') {
     global $g5;
     //static $css = array();

     if (!$skin_dir) $skin_dir = 'basic';

     if(G5_IS_MOBILE) {
         $latest_skin_path = G5_MOBILE_PATH.'/'.G5_SKIN_DIR.'/latest/'.$skin_dir;
         $latest_skin_url  = G5_MOBILE_URL.'/'.G5_SKIN_DIR.'/latest/'.$skin_dir;
     } else {
         $latest_skin_path = G5_SKIN_PATH.'/latest/'.$skin_dir;
         $latest_skin_url  = G5_SKIN_URL.'/latest/'.$skin_dir;
     }

     $cache_fwrite = false;
     if(G5_USE_CACHE) {
         $cache_file = G5_DATA_PATH."/cache/latest-{$bo_table}-datetime-{$skin_dir}-{$rows}-{$subject_len}.php";

         if(!file_exists($cache_file)) {
             $cache_fwrite = true;
         } else {
             if($cache_time > 0) {
                 $filetime = filemtime($cache_file);
                 if($filetime && $filetime < (G5_SERVER_TIME - 3600 * $cache_time)) {
                     @unlink($cache_file);
                     $cache_fwrite = true;
                 }
             }

             if(!$cache_fwrite)
                 include_once($cache_file);
         }
     }

     if(!G5_USE_CACHE || $cache_fwrite) {
         $list = array();

         $sql = " select * from {$g5['board_table']} where bo_table = '{$bo_table}' ";
         $board = sql_fetch($sql);
         $bo_subject = get_text($board['bo_subject']);

         $tmp_write_table = $g5['write_prefix'] . $bo_table; // 게시판 테이블 전체이름
        $sql = " select * from {$tmp_write_table} where wr_is_comment = 0 order by wr_datetime desc limit 0, {$rows} ";
         $result = sql_query($sql);
         for ($i=0; $row = sql_fetch_array($result); $i++) {
             $list[$i] = get_list($row, $board, $latest_skin_url, $subject_len);
         }

         if($cache_fwrite) {
             $handle = fopen($cache_file, 'w');
             $cache_content = "<?php\nif (!defined('_GNUBOARD_')) exit;\n\$bo_subject=\"".$bo_subject."\";\n\$list=".var_export($list, true)."?>";
             fwrite($handle, $cache_content);
             fclose($handle);
         }
     }

     /*
     // 같은 스킨은 .css 를 한번만 호출한다.
     if (!in_array($skin_dir, $css) && is_file($latest_skin_path.'/style.css')) {
         echo '<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">';
         $css[] = $skin_dir;
     }
     */

     ob_start();
     include $latest_skin_path.'/latest.skin.php';
     $content = ob_get_contents();
     ob_end_clean();

     return $content;
}
// 답글이 원본글 밑에 붙는 방식
//   사용법 : <?php echo latest_re("최신글스킨", "게시판이름", 게시물수, 제목글자수);? >
 // 최신글 추출 ## 답글이 원본글 밑에 붙는 방식
function latest_re($skin_dir="", $bo_table, $rows=10, $subject_len=40, $options="") {
     global $g5;
     //static $css = array();

     if (!$skin_dir) $skin_dir = 'basic';

     if(G5_IS_MOBILE) {
         $latest_skin_path = G5_MOBILE_PATH.'/'.G5_SKIN_DIR.'/latest/'.$skin_dir;
         $latest_skin_url  = G5_MOBILE_URL.'/'.G5_SKIN_DIR.'/latest/'.$skin_dir;
     } else {
         $latest_skin_path = G5_SKIN_PATH.'/latest/'.$skin_dir;
         $latest_skin_url  = G5_SKIN_URL.'/latest/'.$skin_dir;
     }

     $cache_fwrite = false;
     if(G5_USE_CACHE) {
         $cache_file = G5_DATA_PATH."/cache/latest-{$bo_table}-re-{$skin_dir}-{$rows}-{$subject_len}.php";

         if(!file_exists($cache_file)) {
             $cache_fwrite = true;
         } else {
             if($cache_time > 0) {
                 $filetime = filemtime($cache_file);
                 if($filetime && $filetime < (G5_SERVER_TIME - 3600 * $cache_time)) {
                     @unlink($cache_file);
                     $cache_fwrite = true;
                 }
             }

             if(!$cache_fwrite)
                 include_once($cache_file);
         }
     }

     if(!G5_USE_CACHE || $cache_fwrite) {
         $list = array();

         $sql = " select * from {$g5['board_table']} where bo_table = '{$bo_table}' ";
         $board = sql_fetch($sql);
         $bo_subject = get_text($board['bo_subject']);

         $tmp_write_table = $g5['write_prefix'] . $bo_table; // 게시판 테이블 전체이름
        $sql = " select * from {$tmp_write_table} where wr_is_comment = 0 order by wr_num asc, wr_id asc limit 0, {$rows} ";
         $result = sql_query($sql);
         for ($i=0; $row = sql_fetch_array($result); $i++) {
             $list[$i] = get_list($row, $board, $latest_skin_url, $subject_len);
         }

         if($cache_fwrite) {
             $handle = fopen($cache_file, 'w');
             $cache_content = "<?php\nif (!defined('_GNUBOARD_')) exit;\n\$bo_subject=\"".$bo_subject."\";\n\$list=".var_export($list, true)."?>";
             fwrite($handle, $cache_content);
             fclose($handle);
         }
     }

     /*
     // 같은 스킨은 .css 를 한번만 호출한다.
     if (!in_array($skin_dir, $css) && is_file($latest_skin_path.'/style.css')) {
         echo '<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">';
         $css[] = $skin_dir;
     }
     */

     ob_start();
     include $latest_skin_path.'/latest.skin.php';
     $content = ob_get_contents();
     ob_end_clean();

     return $content;
}
// 최신 스케줄 추출
function latest_sc($skin_dir='', $bo_table, $rows=10, $subject_len=40, $Ym='')
{
    global $g5;
    static $css = array();

    if (!$skin_dir) $skin_dir = 'basic';

    if(G5_IS_MOBILE) {
        $latest_skin_path = G5_MOBILE_PATH.'/'.G5_SKIN_DIR.'/latest/'.$skin_dir;
        $latest_skin_url  = G5_MOBILE_URL.'/'.G5_SKIN_DIR.'/latest/'.$skin_dir;
    } else {
        $latest_skin_path = G5_SKIN_PATH.'/latest/'.$skin_dir;
        $latest_skin_url  = G5_SKIN_URL.'/latest/'.$skin_dir;
    }

    //$cache_file = G5_DATA_PATH."/cache/latest-{$bo_table}-{$skin_dir}-{$rows}-{$subject_len}.php";
    //if (!G5_USE_CACHE || !file_exists($cache_file)) {
        $list = array();

        $sql = " select * from {$g5['board_table']} where bo_table = '{$bo_table}' ";
        $board = sql_fetch($sql);

        $tmp_write_table = $g5['write_prefix'] . $bo_table; // 게시판 테이블 전체이름
        $sql = " select * from {$tmp_write_table}";
		if($Ym) {
		$sql .= " where left(wr_1,6) <= '$Ym'  and left(wr_2,6) >= '$Ym' ";
		}else{
        $sql .= " where wr_2 >= ".date('Ymd');
		}
		$sql .= " order by wr_1 limit 0, {$rows} ";
        $result = sql_query($sql);
        for ($i=0; $row = sql_fetch_array($result); $i++) {
            $list[$i] = get_list($row, $board, $latest_skin_url, $subject_len);
        }

        $handle = fopen($cache_file, 'w');
        $cache_content = "<?php\nif (!defined('_GNUBOARD_')) exit;\n\$bo_subject=\"".get_text($board['bo_subject'])."\";\n\$list=".var_export($list, true)."?>";
        fwrite($handle, $cache_content);
        fclose($handle);
    //}

    //include_once($cache_file);

    ob_start();
    include $latest_skin_path.'/latest.skin.php';
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}
/*
* 사용방법 
- latest_multi(스킨, 게시판아이디, 출력라인, 글자수, 캐시갱신시간, 옵션들); 
ex) echo latest_multi("basic", $row['bo_table'], 5, 25, 0, "notice_up"); 
* 옵션의 종류 
- notice_only : 해당 게시판의 공지글만 가져온다. 
- notice_up : 공지글이 상단에 위치하도록 가져온다. 
- notice_exclude : 공지글은 빼고 가져온다. 
- reply_exclude : 답변글은 제외한 목록 글들만 가져온다. 
- file_exist : 첨부파일이 있는 글들만 가져온다. 
- datetime_asc : 날짜가 오래된 순으로 가져온다. 
- hit_desc : 조회수 많은 순으로 가져온다. 
- last_asc : 최근글 이전것부터 가져온다. 
- comment_desc : 코멘트 달린 갯수 많은 순으로 가져온다. 
- good_desc : 추천수 많은 순으로 가져온다. 
- random : 랜덤으로 가져온다. 
- subject_asc : 제목 순으로 가져온다. 
- subject_desc : 제목 역순으로 가져온다. 
*/
function latest_multi($skin_dir='', $bo_table, $rows=10, $subject_len=40, $cache_time=0, $options='')
{
	global $g5;

	if (!$skin_dir) $skin_dir = 'basic';

	if(preg_match('#^theme/(.+)$#', $skin_dir, $match)) {
		if (G5_IS_MOBILE) {
			$latest_skin_path = G5_THEME_MOBILE_PATH.'/'.G5_SKIN_DIR.'/latest/'.$match[1];
			if(!is_dir($latest_skin_path))
				$latest_skin_path = G5_THEME_PATH.'/'.G5_SKIN_DIR.'/latest/'.$match[1];
			$latest_skin_url = str_replace(G5_PATH, G5_URL, $latest_skin_path);
		} else {
			$latest_skin_path = G5_THEME_PATH.'/'.G5_SKIN_DIR.'/latest/'.$match[1];
			$latest_skin_url = str_replace(G5_PATH, G5_URL, $latest_skin_path);
		}
		$skin_dir = $match[1];
	} else {
		if(G5_IS_MOBILE) {
			$latest_skin_path = G5_MOBILE_PATH.'/'.G5_SKIN_DIR.'/latest/'.$skin_dir;
			$latest_skin_url  = G5_MOBILE_URL.'/'.G5_SKIN_DIR.'/latest/'.$skin_dir;
		} else {
			$latest_skin_path = G5_SKIN_PATH.'/latest/'.$skin_dir;
			$latest_skin_url  = G5_SKIN_URL.'/latest/'.$skin_dir;
		}
	}

	$cache_fwrite = false;
	if(G5_USE_CACHE) {
		$cache_file = G5_DATA_PATH."/cache/latest-{$bo_table}-{$skin_dir}-{$rows}-{$subject_len}.php";

		if(!file_exists($cache_file)) {
			$cache_fwrite = true;
		} else {
			if($cache_time > 0) {
				$filetime = filemtime($cache_file);
				if($filetime && $filetime < (G5_SERVER_TIME - 3600 * $cache_time)) {
					@unlink($cache_file);
					$cache_fwrite = true;
				}
			}

			if(!$cache_fwrite)
				include($cache_file);
		}
	}

	if(!G5_USE_CACHE || $cache_fwrite) {
		$list = array();

		$sql = " select * from {$g5['board_table']} where bo_table = '{$bo_table}' ";
		$board = sql_fetch($sql);
		$bo_subject = get_text($board['bo_subject']);

		$tmp_write_table = $g5['write_prefix'] . $bo_table; // 게시판 테이블 전체이름

		$sql_where = " where wr_is_comment = 0 ";
		if (stristr($options, "notice_only"))		$sql_where .= " and INSTR(concat(',','$board[bo_notice]',','),concat(',',wr_id,',')) > 0 ";
		if (stristr($options, "notice_exclude"))	$sql_where .= " and INSTR(concat(',','$board[bo_notice]',','),concat(',',wr_id,',')) = 0 ";
		if (stristr($options, "reply_exclude"))		$sql_where .= " and wr_reply = '' ";
		if (stristr($options, "file_exist"))		$sql_where .= " and wr_file > 0 ";
		//if (stristr($options, "mine_only"))			$sql_where .= " and mb_id = '{$member[mb_id]}' ";	// 이 기능을 사용하려면 global 에 $member 를 추가해야 한다. 하지만, 사용하려 해도 최신글 캐시 기능 때문에 활용이 어렵다.
		//echo $sql_where;

		$sql_order = " order by ";
		if (stristr($options, "notice_up"))			$sql_order .= " case when INSTR(concat(',','$board[bo_notice]',','),concat(',',wr_id,',')) > 0 then 0 else 1 end, ";
		if (stristr($options, "reply_list"))		$sql_order .= " wr_num, wr_reply, ";
		if (stristr($options, "datetime_asc"))		$sql_order .= " wr_datetime asc, ";
		if (stristr($options, "datetime_desc"))		$sql_order .= " wr_datetime desc, ";
		if (stristr($options, "hit_asc"))			$sql_order .= " wr_hit asc, ";
		if (stristr($options, "hit_desc"))			$sql_order .= " wr_hit desc, ";
		if (stristr($options, "last_asc"))			$sql_order .= " wr_last asc, ";
		if (stristr($options, "last_desc"))			$sql_order .= " wr_last desc, ";
		if (stristr($options, "comment_asc"))		$sql_order .= " wr_comment asc, ";
		if (stristr($options, "comment_desc"))		$sql_order .= " wr_comment desc, ";
		if (stristr($options, "comment_cnt_desc"))	$sql_order .= " wr_comment desc, ";
		if (stristr($options, "good_asc"))			$sql_order .= " wr_good asc, ";
		if (stristr($options, "good_desc"))			$sql_order .= " wr_good desc, ";
		if (stristr($options, "subject_asc"))		$sql_order .= " wr_subject asc, ";
		if (stristr($options, "subject_desc"))		$sql_order .= " wr_subject desc, ";
		if (stristr($options, "random"))			$sql_order .= " rand(), ";
		$sql_order .= " wr_datetime desc limit 0, {$rows} ";
		//echo $sql_order;

		$sql = " select * from {$tmp_write_table} " . $sql_where . $sql_order;
		$result = sql_query($sql);
		for ($i=0; $row = sql_fetch_array($result); $i++) {
			$list[$i] = get_list($row, $board, $latest_skin_url, $subject_len);
		}

		if($cache_fwrite) {
			$handle = fopen($cache_file, 'w');
			$cache_content = "<?php\nif (!defined('_GNUBOARD_')) exit;\n\$bo_subject='".$bo_subject."';\n\$list=".var_export($list, true)."?>";
			fwrite($handle, $cache_content);
			fclose($handle);
		}
	}

	ob_start();
	include $latest_skin_path.'/latest.skin.php';
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
function v_day($m, $d, $y) {
  $day = Array("일","월","화","수","목","금","토");
  return $day[date("w", mktime(0,0,0,$m,$d,$y))];
}
?>