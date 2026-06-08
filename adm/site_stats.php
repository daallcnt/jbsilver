<?php
$sub_menu = '400100';
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '통합 통계';

$date_pattern = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';
$default_fr_date = date('Y-m-d', G5_SERVER_TIME - (86400 * 6));
$default_to_date = date('Y-m-d', G5_SERVER_TIME);

$fr_date = isset($_GET['fr_date']) && preg_match($date_pattern, $_GET['fr_date']) ? $_GET['fr_date'] : $default_fr_date;
$to_date = isset($_GET['to_date']) && preg_match($date_pattern, $_GET['to_date']) ? $_GET['to_date'] : $default_to_date;

if ($fr_date > $to_date) {
    $tmp_date = $fr_date;
    $fr_date = $to_date;
    $to_date = $tmp_date;
}

$stat_days = get_site_stat_days($fr_date, $to_date);
$homepage_daily = get_homepage_visit_daily($fr_date, $to_date);
$qna_daily = get_qna_question_daily($fr_date, $to_date);
$security_stats = get_security_app_stats($fr_date, $to_date);
$security_daily = $security_stats['daily'];

$totals = array(
    'homepage' => array_sum($homepage_daily),
    'qna' => array_sum($qna_daily),
    'chatbot' => isset($security_stats['totals']['chatbot_question']) ? (int) $security_stats['totals']['chatbot_question'] : 0,
    'senior_store' => isset($security_stats['totals']['senior_store_visit']) ? (int) $security_stats['totals']['senior_store_visit'] : 0
);

include_once('./admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<style>
.site-stats-summary {display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin:0 0 18px}
.site-stats-card {border:1px solid #d9dee3;background:#fff;padding:16px}
.site-stats-card strong {display:block;margin-bottom:8px;color:#59636e;font-size:13px}
.site-stats-card span {font-size:28px;font-weight:700;color:#1f2933}
.site-stats-notice {margin:0 0 14px;padding:11px 13px;border:1px solid #e8d8a8;background:#fff8df;color:#6b5413}
.site-stats-table .td_num {text-align:right}
@media (max-width:1100px){.site-stats-summary{grid-template-columns:repeat(2,minmax(0,1fr))}}
</style>

<form name="fstats" id="fstats" class="local_sch03 local_sch" method="get">
<div class="sch_last">
    <strong>기간별검색</strong>
    <input type="text" name="fr_date" value="<?php echo get_text($fr_date); ?>" id="fr_date" class="frm_input" size="11" maxlength="10">
    <label for="fr_date" class="sound_only">시작일</label>
    ~
    <input type="text" name="to_date" value="<?php echo get_text($to_date); ?>" id="to_date" class="frm_input" size="11" maxlength="10">
    <label for="to_date" class="sound_only">종료일</label>
    <input type="submit" value="검색" class="btn_submit">
</div>
</form>

<?php if (!$security_stats['ok']) { ?>
<p class="site-stats-notice">security-app 통계 API 연결에 실패했습니다. 홈페이지 접속량과 QnA 질문 수만 표시합니다. <?php echo get_text($security_stats['message']); ?></p>
<?php } ?>

<div class="site-stats-summary">
    <div class="site-stats-card">
        <strong>홈페이지 접속량</strong>
        <span><?php echo number_format($totals['homepage']); ?></span>
    </div>
    <div class="site-stats-card">
        <strong>QnA 질문 수</strong>
        <span><?php echo number_format($totals['qna']); ?></span>
    </div>
    <div class="site-stats-card">
        <strong>챗봇 사용량</strong>
        <span><?php echo number_format($totals['chatbot']); ?></span>
    </div>
    <div class="site-stats-card">
        <strong>시니어 스토어 접속량</strong>
        <span><?php echo number_format($totals['senior_store']); ?></span>
    </div>
</div>

<div class="tbl_head01 tbl_wrap site-stats-table">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">날짜</th>
        <th scope="col">홈페이지 접속량</th>
        <th scope="col">QnA 질문 수</th>
        <th scope="col">챗봇 사용량</th>
        <th scope="col">시니어 스토어 접속량</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($stat_days as $day) { ?>
    <tr>
        <td class="td_datetime"><?php echo get_text($day); ?></td>
        <td class="td_num"><?php echo number_format(isset($homepage_daily[$day]) ? $homepage_daily[$day] : 0); ?></td>
        <td class="td_num"><?php echo number_format(isset($qna_daily[$day]) ? $qna_daily[$day] : 0); ?></td>
        <td class="td_num"><?php echo number_format(isset($security_daily[$day]['chatbot_question']) ? $security_daily[$day]['chatbot_question'] : 0); ?></td>
        <td class="td_num"><?php echo number_format(isset($security_daily[$day]['senior_store_visit']) ? $security_daily[$day]['senior_store_visit'] : 0); ?></td>
    </tr>
    <?php } ?>
    <?php if (empty($stat_days)) { ?>
    <tr><td colspan="5" class="empty_table">자료가 없습니다.</td></tr>
    <?php } ?>
    </tbody>
    </table>
</div>

<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>

<?php
include_once('./admin.tail.php');

function get_site_stat_days($fr_date, $to_date)
{
    $days = array();
    $current = strtotime($fr_date);
    $end = strtotime($to_date);

    while ($current <= $end) {
        $days[] = date('Y-m-d', $current);
        $current += 86400;
    }

    return $days;
}

function get_homepage_visit_daily($fr_date, $to_date)
{
    global $g5;

    $daily = array();
    $sql = " select vi_date as stat_date, count(*) as cnt
                from {$g5['visit_table']}
                where vi_date between '{$fr_date}' and '{$to_date}'
                group by vi_date ";
    $result = sql_query($sql);

    while ($row = sql_fetch_array($result)) {
        $daily[$row['stat_date']] = (int) $row['cnt'];
    }

    return $daily;
}

function get_qna_question_daily($fr_date, $to_date)
{
    global $g5;

    $daily = array();
    $write_table = $g5['write_prefix'].'sub04_03';
    if (!preg_match('/^[A-Za-z0-9_]+$/', $write_table)) {
        return $daily;
    }

    $exists = sql_fetch(" show tables like '{$write_table}' ", false);
    if (!$exists) {
        return $daily;
    }

    $sql = " select substr(wr_datetime, 1, 10) as stat_date, count(*) as cnt
                from {$write_table}
                where wr_is_comment = 0
                  and substr(wr_datetime, 1, 10) between '{$fr_date}' and '{$to_date}'
                group by stat_date ";
    $result = sql_query($sql);

    while ($row = sql_fetch_array($result)) {
        $daily[$row['stat_date']] = (int) $row['cnt'];
    }

    return $daily;
}

function get_security_app_stats($fr_date, $to_date)
{
    $empty = array(
        'ok' => false,
        'message' => '',
        'totals' => array('chatbot_question' => 0, 'senior_store_visit' => 0),
        'daily' => array()
    );

    if (!function_exists('curl_init')) {
        $empty['message'] = 'PHP curl 확장이 없습니다.';
        return $empty;
    }

    $url = 'https://security.jbsilver.net/api/usage-events/stats?from='.rawurlencode($fr_date).'&to='.rawurlencode($to_date);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    $body = curl_exec($ch);
    $http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($body === false || $http_code < 200 || $http_code >= 300) {
        $empty['message'] = $curl_error ? $curl_error : 'HTTP '.$http_code;
        return $empty;
    }

    $data = json_decode($body, true);
    if (!is_array($data)) {
        $empty['message'] = '응답 JSON을 해석할 수 없습니다.';
        return $empty;
    }

    $daily = array();
    if (isset($data['daily']) && is_array($data['daily'])) {
        foreach ($data['daily'] as $row) {
            if (!isset($row['date'], $row['eventType'], $row['count'])) {
                continue;
            }
            $date = $row['date'];
            $event_type = $row['eventType'];
            if (!isset($daily[$date])) {
                $daily[$date] = array();
            }
            $daily[$date][$event_type] = (int) $row['count'];
        }
    }

    return array(
        'ok' => true,
        'message' => '',
        'totals' => isset($data['totals']) && is_array($data['totals']) ? $data['totals'] : $empty['totals'],
        'daily' => $daily
    );
}
?>
