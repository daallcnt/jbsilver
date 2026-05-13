<?php
$sub_menu = "300100";
include_once('./_common.php');
include_once(G5_PLUGIN_PATH.'/PHPExcel/PHPExcel.php');
include_once(G5_PLUGIN_PATH.'/PHPExcel/PHPExcel/IOFactory.php');

auth_check($auth[$sub_menu], 'w');

$bo_table = 'sub04_03';
$write_table = $g5['write_prefix'].$bo_table;
$board = sql_fetch(" select * from {$g5['board_table']} where bo_table = '{$bo_table}' ");
if (!$board['bo_table']) {
    alert('sub04_03 게시판이 없습니다.');
}

function qna_import_table_exists($table)
{
    $table = sql_real_escape_string($table);
    $row = sql_fetch(" show tables like '{$table}' ");
    return $row ? true : false;
}

function qna_import_create_write_table($table)
{
    $schema_file = G5_ADMIN_PATH.'/sql_write.sql';
    if (!is_file($schema_file)) {
        return false;
    }

    $sql = file_get_contents($schema_file);
    $sql = str_replace('__TABLE_NAME__', $table, $sql);
    return sql_query($sql, false) ? true : false;
}

function qna_import_cell_value($cell)
{
    $value = $cell ? $cell->getValue() : '';
    if (is_object($value)) {
        if (method_exists($value, 'getPlainText')) {
            $value = $value->getPlainText();
        } else {
            $value = (string) $value;
        }
    }
    $value = str_replace(array("\r\n", "\r", "\xc2\xa0"), array("\n", "\n", ' '), (string) $value);
    $value = preg_replace('/[ \t]+/u', ' ', $value);
    $value = preg_replace("/\n{3,}/", "\n\n", $value);
    return trim($value);
}

function qna_import_short_subject($text)
{
    $text = preg_replace('/\s+/u', ' ', trim($text));
    if ($text === '') {
        return '노인일자리 Q&A';
    }
    if (function_exists('mb_substr')) {
        return mb_substr($text, 0, 90, 'UTF-8');
    }
    return substr($text, 0, 180);
}

function qna_import_html($text)
{
    return nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
}

function qna_import_find_files()
{
    $files = array();
    $dirs = array(G5_PATH, dirname(G5_PATH), dirname(__DIR__));
    if (!empty($_SERVER['DOCUMENT_ROOT'])) {
        $dirs[] = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
        $dirs[] = dirname(rtrim($_SERVER['DOCUMENT_ROOT'], '/'));
    }
    $dirs = array_values(array_unique($dirs));
    foreach ($dirs as $dir) {
        foreach (array('7*.xlsx', '7*.xls', '6*.xlsx', '6*.xls') as $pattern) {
            foreach (glob($dir.'/'.$pattern) as $file) {
                if (is_file($file)) {
                    $files[] = $file;
                }
            }
        }
    }
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            continue;
        }
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $path => $info) {
            if (!$info->isFile()) {
                continue;
            }
            $depth = substr_count(str_replace($dir, '', $path), DIRECTORY_SEPARATOR);
            if ($depth > 2) {
                continue;
            }
            if (preg_match('/\/[67][^\/]*\.xlsx?$/i', $path)) {
                $files[] = $path;
            }
        }
    }
    return array_values(array_unique($files));
}

function qna_import_uploaded_files()
{
    $files = array();
    if (empty($_FILES['excel_files']) || empty($_FILES['excel_files']['name'])) {
        return $files;
    }

    $upload_dir = G5_DATA_PATH.'/qna_import_uploads';
    if (!is_dir($upload_dir)) {
        @mkdir($upload_dir, G5_DIR_PERMISSION, true);
        @chmod($upload_dir, G5_DIR_PERMISSION);
    }

    foreach ($_FILES['excel_files']['name'] as $idx => $name) {
        if (!isset($_FILES['excel_files']['tmp_name'][$idx]) || !is_uploaded_file($_FILES['excel_files']['tmp_name'][$idx])) {
            continue;
        }
        if (!preg_match('/^[67].*\.xlsx?$/iu', $name)) {
            continue;
        }

        $safe_name = preg_replace('/[^\w가-힣 ._()-]+/u', '_', $name);
        $target = $upload_dir.'/'.date('YmdHis').'_'.$idx.'_'.$safe_name;
        if (move_uploaded_file($_FILES['excel_files']['tmp_name'][$idx], $target)) {
            @chmod($target, G5_FILE_PERMISSION);
            $files[] = $target;
        }
    }

    return $files;
}

function qna_import_search_dirs()
{
    $dirs = array(G5_PATH, dirname(G5_PATH), dirname(__DIR__));
    if (!empty($_SERVER['DOCUMENT_ROOT'])) {
        $dirs[] = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
        $dirs[] = dirname(rtrim($_SERVER['DOCUMENT_ROOT'], '/'));
    }
    return array_values(array_unique($dirs));
}

function qna_import_detect_columns($sheet)
{
    $max_row = min(10, $sheet->getHighestRow());
    $max_col = PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());

    for ($row = 1; $row <= $max_row; $row++) {
        $cols = array();
        for ($col = 0; $col < $max_col; $col++) {
            $value = trim(qna_import_cell_value($sheet->getCellByColumnAndRow($col, $row)));
            if ($value !== '') {
                $cols[$value] = $col;
            }
        }

        if (isset($cols['질문']) && isset($cols['답변'])) {
            $post_col = isset($cols['게시번호']) ? $cols['게시번호'] : (isset($cols['연번']) ? $cols['연번'] : null);
            $seq_col = isset($cols['게시번호']) && isset($cols['연번']) ? $cols['연번'] : null;

            return array(
                'header_row' => $row,
                'seq_col' => $seq_col,
                'post_col' => $post_col,
                'question_col' => $cols['질문'],
                'answer_col' => $cols['답변'],
            );
        }
    }

    return null;
}

function qna_import_read_rows($files)
{
    $rows = array();
    $errors = array();

    foreach ($files as $file) {
        try {
            $reader = PHPExcel_IOFactory::createReaderForFile($file);
            $reader->setReadDataOnly(true);
            $excel = $reader->load($file);
            $sheet = $excel->getSheet(0);
            $columns = qna_import_detect_columns($sheet);
            if (!$columns) {
                $errors[] = basename($file).' : 질문/답변 헤더를 찾지 못했습니다.';
                continue;
            }

            $max_row = $sheet->getHighestRow();
            for ($row = $columns['header_row'] + 1; $row <= $max_row; $row++) {
                $question = qna_import_cell_value($sheet->getCellByColumnAndRow($columns['question_col'], $row));
                $answer = qna_import_cell_value($sheet->getCellByColumnAndRow($columns['answer_col'], $row));
                $post_id = $columns['post_col'] === null ? '' : qna_import_cell_value($sheet->getCellByColumnAndRow($columns['post_col'], $row));
                $seq = $columns['seq_col'] === null ? '' : qna_import_cell_value($sheet->getCellByColumnAndRow($columns['seq_col'], $row));

                if ($question === '' && $answer === '' && $post_id === '') {
                    continue;
                }

                $rows[] = array(
                    'file' => basename($file),
                    'row' => $row,
                    'seq' => $seq,
                    'post_id' => $post_id,
                    'question' => $question,
                    'answer' => $answer,
                    'hash' => sha1($question."\0".$answer),
                );
            }
        } catch (Exception $e) {
            $errors[] = basename($file).' : '.$e->getMessage();
        }
    }

    return array($rows, $errors);
}

function qna_import_prepare_rows($rows)
{
    $prepared = array();
    $skipped = array(
        'empty_question' => 0,
        'empty_answer' => 0,
        'duplicate_post_id_in_file' => 0,
        'duplicate_content_in_file' => 0,
    );
    $seen_post_ids = array();
    $seen_hashes = array();

    foreach ($rows as $row) {
        if ($row['question'] === '') {
            $skipped['empty_question']++;
            continue;
        }
        if ($row['answer'] === '') {
            $skipped['empty_answer']++;
            continue;
        }
        if ($row['post_id'] !== '') {
            if (isset($seen_post_ids[$row['post_id']])) {
                $skipped['duplicate_post_id_in_file']++;
                continue;
            }
            $seen_post_ids[$row['post_id']] = true;
        }
        if (isset($seen_hashes[$row['hash']])) {
            $skipped['duplicate_content_in_file']++;
            continue;
        }
        $seen_hashes[$row['hash']] = true;
        $prepared[] = $row;
    }

    return array($prepared, $skipped);
}

function qna_import_existing_duplicate($write_table, $row)
{
    $post_id = sql_real_escape_string($row['post_id']);
    $hash = sql_real_escape_string($row['hash']);

    if ($post_id !== '') {
        $exists = sql_fetch(" select wr_id from {$write_table} where wr_is_comment = 0 and wr_1 = '{$post_id}' limit 1 ");
        if ($exists['wr_id']) {
            return 'existing_post_id';
        }
    }

    $exists = sql_fetch(" select wr_id from {$write_table} where wr_is_comment = 0 and wr_3 = '{$hash}' limit 1 ");
    if ($exists['wr_id']) {
        return 'existing_content';
    }

    return '';
}

function qna_import_db_error()
{
    global $g5;

    if (function_exists('mysqli_error') && defined('G5_MYSQLI_USE') && G5_MYSQLI_USE) {
        return mysqli_errno($g5['connect_db']).' : '.mysqli_error($g5['connect_db']);
    }

    if (function_exists('mysql_error')) {
        return mysql_errno().' : '.mysql_error();
    }

    return '';
}

$uploaded_files = qna_import_uploaded_files();
$files = array_values(array_unique(array_merge($uploaded_files, qna_import_find_files())));
$search_dirs = qna_import_search_dirs();
list($all_rows, $read_errors) = qna_import_read_rows($files);
list($prepared_rows, $prepare_skipped) = qna_import_prepare_rows($all_rows);
$write_table_ready = qna_import_table_exists($write_table);
$write_table_created = false;

$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_import'])) {
    check_admin_token();

    if (!$write_table_ready) {
        $write_table_created = qna_import_create_write_table($write_table);
        $write_table_ready = qna_import_table_exists($write_table);
    }

    $inserted = 0;
    $existing_post_id = 0;
    $existing_content = 0;
    $failed = 0;
    $failed_messages = array();

    if (!$write_table_ready) {
        $failed = count($prepared_rows);
        $failed_messages[] = $write_table.' 테이블이 없고 자동 생성에도 실패했습니다.';
    }

    foreach ($write_table_ready ? $prepared_rows : array() as $row) {
        $dup = qna_import_existing_duplicate($write_table, $row);
        if ($dup === 'existing_post_id') {
            $existing_post_id++;
            continue;
        } else if ($dup === 'existing_content') {
            $existing_content++;
            continue;
        }

        $subject = sql_real_escape_string(qna_import_short_subject($row['question']));
        $content = '<div class="imported-qna">';
        $content .= '<p><strong>질문</strong></p><div>'.qna_import_html($row['question']).'</div>';
        $content .= '<p style="margin-top:18px;"><strong>답변</strong></p><div>'.qna_import_html($row['answer']).'</div>';
        if ($row['post_id'] !== '') {
            $content .= '<p style="margin-top:18px;color:#777;font-size:12px;">원본 게시번호: '.htmlspecialchars($row['post_id'], ENT_QUOTES, 'UTF-8').'</p>';
        }
        $content .= '</div>';

        $wr_num = get_next_num($write_table);
        $wr_name = '전북노인일자리센터';
        $wr_password = get_encrypt_string(uniqid('qna_import_', true));
        $now = G5_TIME_YMDHIS;
        $ip = sql_real_escape_string($_SERVER['REMOTE_ADDR']);

        $sql = " insert into {$write_table}
                    set wr_num = '{$wr_num}',
                        wr_reply = '',
                        wr_parent = 0,
                        wr_is_comment = 0,
                        wr_comment = 0,
                        wr_comment_reply = '',
                        ca_name = '',
                        wr_option = 'html1',
                        wr_subject = '{$subject}',
                        wr_content = '".sql_real_escape_string($content)."',
                        wr_link1 = '',
                        wr_link2 = '',
                        wr_link1_hit = 0,
                        wr_link2_hit = 0,
                        wr_hit = 0,
                        wr_good = 0,
                        wr_nogood = 0,
                        mb_id = '',
                        wr_password = '{$wr_password}',
                        wr_name = '".sql_real_escape_string($wr_name)."',
                        wr_email = '',
                        wr_homepage = '',
                        wr_datetime = '{$now}',
                        wr_file = 0,
                        wr_last = '{$now}',
                        wr_ip = '{$ip}',
                        wr_facebook_user = '',
                        wr_twitter_user = '',
                        wr_1 = '".sql_real_escape_string($row['post_id'])."',
                        wr_2 = '".sql_real_escape_string($row['seq'])."',
                        wr_3 = '".sql_real_escape_string($row['hash'])."',
                        wr_4 = '".sql_real_escape_string($row['file'])."',
                        wr_5 = '".sql_real_escape_string($row['row'])."',
                        wr_6 = '',
                        wr_7 = '',
                        wr_8 = '',
                        wr_9 = '',
                        wr_10 = '' ";

        $insert_result = sql_query($sql, false);
        if (!$insert_result) {
            $failed++;
            if (count($failed_messages) < 5) {
                $error_message = qna_import_db_error();
                if (!$error_message || $error_message === '0 : ') {
                    $error_message = 'insert 실패: '.substr($sql, 0, 500);
                }
                $failed_messages[] = $error_message;
            }
            continue;
        }

        $wr_id = sql_insert_id();
        sql_query(" update {$write_table} set wr_parent = '{$wr_id}' where wr_id = '{$wr_id}' ");
        sql_query(" insert into {$g5['board_new_table']} (bo_table, wr_id, wr_parent, bn_datetime, mb_id) values ('{$bo_table}', '{$wr_id}', '{$wr_id}', '{$now}', '') ");
        $inserted++;
    }

    $count = $write_table_ready ? sql_fetch(" select count(*) as cnt from {$write_table} where wr_is_comment = 0 ") : array('cnt' => 0);
    if ($write_table_ready) {
        sql_query(" update {$g5['board_table']} set bo_count_write = '{$count['cnt']}' where bo_table = '{$bo_table}' ");
    }

    $result = array(
        'inserted' => $inserted,
        'existing_post_id' => $existing_post_id,
        'existing_content' => $existing_content,
        'failed' => $failed,
        'board_total' => $count['cnt'],
        'failed_messages' => array_values(array_unique(array_filter($failed_messages))),
        'write_table_ready' => $write_table_ready,
        'write_table_created' => $write_table_created,
    );
}

$g5['title'] = '노인일자리 Q&A 엑셀 등록';
include_once('./admin.head.php');
?>

<div class="local_desc01 local_desc">
    <p><strong>6*.xls(x), 7*.xls(x)</strong> 파일을 읽어 <strong>sub04_03 무엇이든 물어보세요 QnA</strong> 게시판에 등록합니다.</p>
    <p>7번 파일을 먼저 읽고, 6번 파일은 중복 게시번호/내용이면 자동으로 건너뜁니다.</p>
    <p>검색 경로: <?php echo htmlspecialchars(implode(' / ', $search_dirs), ENT_QUOTES, 'UTF-8'); ?></p>
    <p>글 테이블: <?php echo htmlspecialchars($write_table, ENT_QUOTES, 'UTF-8'); ?> / 상태: <?php echo $write_table_ready ? '있음' : '없음'; ?></p>
</div>

<?php if ($result) { ?>
<div class="local_ov01 local_ov">
    <span class="btn_ov01"><span class="ov_txt">신규 등록</span><span class="ov_num"><?php echo number_format($result['inserted']); ?>건</span></span>
    <span class="btn_ov01"><span class="ov_txt">기존 게시번호 중복</span><span class="ov_num"><?php echo number_format($result['existing_post_id']); ?>건</span></span>
    <span class="btn_ov01"><span class="ov_txt">기존 내용 중복</span><span class="ov_num"><?php echo number_format($result['existing_content']); ?>건</span></span>
    <span class="btn_ov01"><span class="ov_txt">실패</span><span class="ov_num"><?php echo number_format($result['failed']); ?>건</span></span>
    <span class="btn_ov01"><span class="ov_txt">게시판 총 글수</span><span class="ov_num"><?php echo number_format($result['board_total']); ?>건</span></span>
    <span class="btn_ov01"><span class="ov_txt">글 테이블</span><span class="ov_num"><?php echo $result['write_table_ready'] ? '정상' : '없음'; ?><?php echo $result['write_table_created'] ? '(자동생성)' : ''; ?></span></span>
</div>
<?php if (!empty($result['failed_messages'])) { ?>
<div class="local_desc01 local_desc">
    <p><strong>DB 오류</strong></p>
    <?php foreach ($result['failed_messages'] as $message) { ?>
    <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php } ?>
</div>
<?php } ?>
<?php } ?>

<section>
    <h2 class="h2_frm">읽은 파일</h2>
    <div class="tbl_head01 tbl_wrap">
        <table>
            <thead>
            <tr>
                <th scope="col">파일</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$files) { ?>
            <tr><td class="empty_table">검색 경로에서 6 또는 7로 시작하는 엑셀 파일을 찾지 못했습니다.</td></tr>
            <?php } else { ?>
            <?php foreach ($files as $file) { ?>
            <tr><td><?php echo htmlspecialchars($file, ENT_QUOTES, 'UTF-8'); ?></td></tr>
            <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>

<section>
    <h2 class="h2_frm">등록 전 요약</h2>
    <div class="tbl_head01 tbl_wrap">
        <table>
            <tbody>
            <tr><th scope="row">엑셀 원본 행</th><td><?php echo number_format(count($all_rows)); ?>건</td></tr>
            <tr><th scope="row">등록 후보</th><td><?php echo number_format(count($prepared_rows)); ?>건</td></tr>
            <tr><th scope="row">질문 없음 제외</th><td><?php echo number_format($prepare_skipped['empty_question']); ?>건</td></tr>
            <tr><th scope="row">답변 없음 제외</th><td><?php echo number_format($prepare_skipped['empty_answer']); ?>건</td></tr>
            <tr><th scope="row">엑셀 내부 게시번호 중복 제외</th><td><?php echo number_format($prepare_skipped['duplicate_post_id_in_file']); ?>건</td></tr>
            <tr><th scope="row">엑셀 내부 내용 중복 제외</th><td><?php echo number_format($prepare_skipped['duplicate_content_in_file']); ?>건</td></tr>
            </tbody>
        </table>
    </div>
</section>

<?php if ($read_errors) { ?>
<section>
    <h2 class="h2_frm">읽기 오류</h2>
    <div class="local_desc01 local_desc">
        <?php foreach ($read_errors as $error) { ?>
        <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php } ?>
    </div>
</section>
<?php } ?>

<form method="post" enctype="multipart/form-data" onsubmit="return confirm('엑셀 Q&A 데이터를 sub04_03 게시판에 등록합니다. 진행할까요?');">
    <input type="hidden" name="token" value="<?php echo get_admin_token(); ?>">
    <section>
        <h2 class="h2_frm">엑셀 직접 업로드</h2>
        <div class="tbl_frm01 tbl_wrap">
            <table>
                <tbody>
                <tr>
                    <th scope="row"><label for="excel_files">엑셀 파일</label></th>
                    <td>
                        <input type="file" name="excel_files[]" id="excel_files" multiple accept=".xls,.xlsx">
                        <p class="frm_info">서버 경로에서 파일을 못 찾으면 여기에서 6번, 7번 엑셀을 직접 선택한 뒤 등록 버튼을 누르세요.</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </section>
    <div class="btn_confirm01 btn_confirm">
        <input type="submit" name="run_import" value="Q&A 엑셀 한번에 등록" class="btn_submit">
        <a href="../bbs/board.php?bo_table=sub04_03" target="_blank">Q&A 게시판 보기</a>
    </div>
</form>

<?php
include_once('./admin.tail.php');
?>
