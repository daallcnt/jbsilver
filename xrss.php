<?php
include_once('./_common.php');

// 특수문자 변환
function specialchars_replace($str, $len=0) {
    if ($len) {
        $str = substr($str, 0, $len);
    }

    $str = str_replace(array("&", "<", ">"), array("&amp;", "&lt;", "&gt;"), $str);

    /*
    $str = preg_replace("/&/", "&amp;", $str);
    $str = preg_replace("/</", "&lt;", $str);
    $str = preg_replace("/>/", "&gt;", $str);
    */

    return $str;
}

$sql = " select * from {$g5['board_table']} where bo_table = '$bo_table' ";
$row = sql_fetch($sql);
$subj2 = specialchars_replace($row['bo_subject'], 255);
$lines = $row['bo_page_rows'];

// 비회원 읽기가 가능한 게시판만 RSS 지원
if ($member['mb_level'] >= 2) {
    echo '비회원 읽기가 가능한 게시판만 RSS 지원합니다.';
    exit;
}



header('Content-type: text/xml');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

echo '<?xml version="1.0" encoding="utf-8" ?>'."\n";
?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
<channel>
<title>교육안내</title>
<link><?php echo specialchars_replace(G5_URL.'/edu/index.php') ?></link>
<description>교육리스트</description>
<language>ko</language>

<?php
$sql = " select *
            from wp_education 
            
            order by schedule desc limit 0, 10 ";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $file = '';
?>

<item>
<title><?php echo specialchars_replace($row['subject']) ?></title>
<link><?php echo specialchars_replace(G5_URL.'/edu/index.php?case=view&amp;s_id='.$row['s_id']) ?></link>
<description><![CDATA[<?php echo $file ?><?php echo conv_content($row['contents'], 1) ?>]]></description>

<?php
$date = $row['wdate'];
// rss 리더 스킨으로 호출하면 날짜가 제대로 표시되지 않음
//$date = substr($date,0,10) . "T" . substr($date,11,8) . "+09:00";
$date = date('r', strtotime($date));
?>
<dc:date><?php echo $date ?></dc:date>
</item>

<?php
}

echo '</channel>'."\n";
echo '</rss>'."\n";
?>
