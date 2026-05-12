<?php
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('get_education_thumbnail')) {
function get_education_thumbnail($s_id, $contents, $thumb_width, $thumb_height)
{
    global $g5, $config;

    $bo_table = 'education';
    $filename = $filepath = $alt = '';
    $editor_data_path = '';

    $sql = " select bf_file, bf_content from {$g5['board_file_table']}
                where bo_table = '{$bo_table}' and wr_id = '{$s_id}' and bf_type between '1' and '3' order by bf_no limit 0, 1 ";
    $row = sql_fetch($sql);

    if ($row['bf_file']) {
        $filename = $row['bf_file'];
        $filepath = G5_DATA_PATH.'/file/'.$bo_table;
        $alt = get_text($row['bf_content']);
    } else {
        $matches = get_editor_image($contents, false);

        for ($i = 0; $matches && $i < count($matches[1]); $i++) {
            $parsed = parse_url($matches[1][$i]);
            if (empty($parsed['path']))
                continue;

            if (strpos($parsed['path'], '/'.G5_DATA_DIR.'/') != 0)
                $data_path = preg_replace('/^\/.*\/'.G5_DATA_DIR.'/', '/'.G5_DATA_DIR, $parsed['path']);
            else
                $data_path = $parsed['path'];

            $srcfile = G5_PATH.$data_path;
            if (preg_match("/\.({$config['cf_image_extension']})$/i", $srcfile) && is_file($srcfile)) {
                $size = @getimagesize($srcfile);
                if (!$size)
                    continue;

                $filename = basename($srcfile);
                $filepath = dirname($srcfile);
                $editor_data_path = $data_path;
                break;
            }
        }
    }

    if (!$filename)
        return false;

    $thumb_name = thumbnail($filename, $filepath, $filepath, $thumb_width, $thumb_height, false, true);
    if (!$thumb_name)
        return false;

    if ($editor_data_path) {
        $src = G5_URL.str_replace($filename, $thumb_name, $editor_data_path);
        $ori = G5_URL.$editor_data_path;
    } else {
        $src = G5_DATA_URL.'/file/'.$bo_table.'/'.$thumb_name;
        $ori = G5_DATA_URL.'/file/'.$bo_table.'/'.$filename;
    }

    return array('src' => $src, 'ori' => $ori, 'alt' => $alt);
}
}
?>
