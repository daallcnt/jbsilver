<?php 
include_once('header.php');
include_once(G5_LIB_PATH.'/json.lib.php');

set_today_statistic("access", 0);

$chart = htmlspecialchars($_GET['chart']);

if(!$chart) $chart = "day!day!day";

$chart_array = explode("!", $chart);

$chart_access = $chart_array[0];
$chart_new = $chart_array[1];
$chart_push = $chart_array[2];

$statistics = get_today_statistic();
$percent = 0;
if($statistics['grs_total_device'] != 0)
{
	$percent = ($statistics['grs_access'] / $statistics['grs_total_device'] ) * 100;
}
$nosync_count_d = sql_fetch(" SELECT count(*) as 'cnt' FROM g5_gnupushapp_gcmregid where gpr_sync = 'N'");
$nosync_count = $nosync_count_d['cnt'];
$sync_count = $statistics['grs_total_device'] - $nosync_count;

$sql = " SELECT * FROM  g5_gnupushapp_push ORDER BY gp_push_date DESC LIMIT 10";
$result_push_list = sql_query($sql);

// $gnuNew = getGnuNews();
// /* gnu푸시앱소식 부분의 글자가 깨져서 나오면 아래 내용 각주처리를 해제해주세요. */
// /*
// $gnuNew = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
//     return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
// }, $gnuNew);

// */
// /* gnu푸시앱소식 부분의 글자가 깨져서 나오면 위 내용 각주처리를 해제해주세요. */
// $arrayGnuNew = json_decode($gnuNew);
// foreach($arrayGnuNew as $key => $val)
// {
// 	if($key == "title0") $GnuNew0_title = $val;
// 	if($key == "title1") $GnuNew1_title = $val;
// 	if($key == "title2") $GnuNew2_title = $val;
// 	if($key == "title3") $GnuNew3_title = $val;
// 	if($key == "title4") $GnuNew4_title = $val;

// 	if($key == "link0") $GnuNew0_link = $val;
// 	if($key == "link1") $GnuNew1_link = $val;
// 	if($key == "link2") $GnuNew2_link = $val;
// 	if($key == "link3") $GnuNew3_link = $val;
// 	if($key == "link4") $GnuNew4_link = $val;

// 	if($key == "date0") $GnuNew0_date = $val;
// 	if($key == "date1") $GnuNew1_date = $val;
// 	if($key == "date2") $GnuNew2_date = $val;
// 	if($key == "date3") $GnuNew3_date = $val;
// 	if($key == "date4") $GnuNew4_date = $val;
// }

?>



    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Dashboard
          <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#">
              <i class="fa fa-dashboard"></i> Home</a>
          </li>
          <li class="active">Dashboard</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?php echo $statistics['grs_new']; ?></h3>

                <p>오늘 신규설치</p>
              </div>
              <div class="icon">
                <i class="fa fa-plus-circle"></i>
              </div>
              <a href="<?php echo G5_URL . '/gnupushapp_admin/device_list.php?level_group=today_install';?>" class="small-box-footer">More info
                <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3><?php echo $statistics['grs_access']; ?>(<?php echo ceil($percent); ?><sup style="font-size: 20px">%</sup>)
                </h3>

                <p>오늘 접속자수</p>
              </div>
              <div class="icon">
                <i class="fa fa-sign-in"></i>
              </div>
              <a href="<?php echo G5_URL . '/gnupushapp_admin/device_list.php?level_group=today_access';?>" class="small-box-footer">More info
                <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3><?php echo $statistics['grs_push']; ?></h3>

                <p>오늘 푸시발생횟수</p>
              </div>
              <div class="icon">
                <i class="fa fa-bell"></i>
              </div>
              <a href="push_result.php" class="small-box-footer">More info
                <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                <h3><?php echo $statistics['grs_total_device']; ?>(<?php echo $sync_count; ?>/<?php echo $nosync_count; ?>)</h3>

                <p>총 등록기기(회원/비회원)</p>
              </div>
              <div class="icon">
                <i class="fa fa-mobile"></i>
              </div>
              <a href="device_list.php" class="small-box-footer">More info
                <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->

		  <!-- /.row -->
            
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <!-- /.row (main row) -->
		<div class="row">
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
          <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right">
              <li <?php if($chart_access == "day") { ?>class="active"<?php } ?>><a href="index.php?chart=day!<?php echo $chart_new;?>!<?php echo $chart_push;?>">Day</a></li>
			  <li <?php if($chart_access == "month") { ?>class="active"<?php } ?>><a href="index.php?chart=month!<?php echo $chart_new;?>!<?php echo $chart_push;?>">Month</a></li>
              <li class="pull-left header"><i class="fa fa-inbox"></i> 접속통계</li>
            </ul>
            <div class="tab-content no-padding">
              <!-- Morris chart - Sales -->
			  <div class="chart tab-pane <?php if($chart_access == "day") { ?>active<?php } ?>" id="graph_day" style="position: relative; height: 200px;"></div>
			  <div class="chart tab-pane <?php if($chart_access == "month") { ?>active<?php } ?>" id="graph_month" style="position: relative; height: 200px;"></div>
            </div>
          </div>

		  <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right">
              <li <?php if($chart_new == "day") { ?>class="active"<?php } ?>><a href="index.php?chart=<?php echo $chart_access;?>!day!<?php echo $chart_push;?>">Day</a></li>
			  <li <?php if($chart_new == "month") { ?>class="active"<?php } ?>><a href="index.php?chart=<?php echo $chart_access;?>!month!<?php echo $chart_push;?>">Month</a></li>
              <li class="pull-left header"><i class="fa fa-inbox"></i> 신규설치</li>
            </ul>
            <div class="tab-content no-padding">
              <!-- Morris chart - Sales -->
			  <div class="chart tab-pane <?php if($chart_new == "day") { ?>active<?php } ?>" id="graph2_day" style="position: relative; height: 200px;"></div>
			  <div class="chart tab-pane <?php if($chart_new == "month") { ?>active<?php } ?>" id="graph2_month" style="position: relative; height: 200px;"></div>
            </div>
          </div>

          <!-- Custom tabs (Charts with tabs)-->
          <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right">
              <li <?php if($chart_push == "day") { ?>class="active"<?php } ?>><a href="index.php?chart=<?php echo $chart_access;?>!<?php echo $chart_new;?>!day">Day</a></li>
			  <li <?php if($chart_push == "month") { ?>class="active"<?php } ?>><a href="index.php?chart=<?php echo $chart_access;?>!<?php echo $chart_new;?>!month">Month</a></li>
              <li class="pull-left header"><i class="fa fa-inbox"></i> 푸시발생횟수</li>
            </ul>
            <div class="tab-content no-padding">
              <!-- Morris chart - Sales -->
			  <div class="chart tab-pane <?php if($chart_push == "day") { ?>active<?php } ?>" id="graph3_day" style="position: relative; height: 200px;"></div>
			  <div class="chart tab-pane <?php if($chart_push == "month") { ?>active<?php } ?>" id="graph3_month" style="position: relative; height: 200px;"></div>
            </div>
          </div>
          <!-- /.nav-tabs-custom -->
        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-5 connectedSortable">
		  <div class="box box-success">
			<div class="box-header with-border">
			  <h3 class="box-title">푸시알림</h3>

			  <div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
				</button>
			  </div>
			  <!-- /.box-tools -->
			</div>
			<!-- /.box-header -->
			<div class="box-body">
			  <ul class="nav nav-stacked">

<?php

$sort_string_array = array(
	"curl" => "curl",
	"quicksend" => "quicksend",
	"quicksendr" => "quicksend",
	"new_product" => "새상품",
	"group" => "그룹별일괄푸시",
	"delete" => "등록기기정리",
	"new_coupon" => "쿠폰",
	"new_post" => "새글",
	"new_comment" => "댓글",
	"new_memo" => "쪽지"
);

for ($i=0; $row=sql_fetch_array($result_push_list); $i++)
{
	$sort_type = $row['gp_type'];
	if($sort_string_array[$sort_type])
	{
		$final_sort = $sort_string_array[$sort_type];
	}
	else
	{
		$final_sort = "quicksend";
	}

	if($row['gp_issend'] == 'Y'){
		$ex_array = explode("/", $row['gp_text']);
		$success = preg_replace("/[^0-9]*/s", "", $ex_array[1]);
	}

?>
				<li><a href="<?php echo $row['gp_target_url'] ?>">[<?php echo $final_sort;?>] <?php echo $row['gp_target_browser'] ?> - <?php echo cut_str(stripslashes($row['gp_target_title']), 20, '...'); if($row['gp_issend'] == 'Y'){ ?><span class="pull-right badge bg-green"><?php echo $success;?></span><?php }else{?><span class="pull-right badge bg-red">fail</span><?php } ?></a></li>

<?php } ?>


              </ul>
			</div>
			<!-- /.box-body -->
		  </div>


		  <div class="box box-warning">
			<div class="box-header with-border">
			  <h3 class="box-title">GNU푸시앱 새소식</h3>

			  <div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
				</button>
			  </div>
			  <!-- /.box-tools -->
			</div>
			<!-- /.box-header -->
			<div class="box-body">
			  <ul class="nav nav-stacked">
                
              </ul>
			</div>
			<!-- /.box-body -->
		  </div>
		  <!-- /.box -->
        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

      </section>
      <!-- /.content -->


    </div>
    <!-- /.content-wrapper -->

<?php

//------------------------------------------------ 접속통계 Day
$week_array = array('일','월','화','수','목','금','토');
$now_date = date("Y-m-d",time());
$now_date_array = explode("-", $now_date);
$graph_x = array();
$graph_y = array();
$graph_z = array();
$result_count = sql_fetch("select count(*) as 'cnt' from g5_gnupushapp_statistics order by grs_regdate desc limit 7");

if($result_count['cnt'] == 0)
{
	for ($j=0; $j<7; $j++)
	{
		$next_day  = mktime (0,0,0,$now_date_array[1], $now_date_array[2]+$j, $now_date_array[0]);
		$graph_x[$j] = date("Y-m-d",$next_day);
		$graph_y[$j] = null;
		$graph_z[$j] = null;
	}
}
else
{
	$result_data = sql_query("select * from g5_gnupushapp_statistics order by grs_regdate desc limit 7");
	for ($i=0; $rowddd=sql_fetch_array($result_data); $i++)
	{		
		$graph_x[$i] = date("Y-m-d", strtotime( $rowddd['grs_regdate'] ) );
		$graph_y[$i] = $rowddd['grs_access'];
		$graph_z[$i] = $rowddd['grs_total_device'];
	}

	$graph_x = array_reverse($graph_x);
	$graph_y = array_reverse($graph_y);
	$graph_z = array_reverse($graph_z);

	$i = count($graph_x);
	$num_left = 7 - $i;

	if($num_left > 0)
	{
		for ($j=0; $j<7; $j++)
		{
			if($j > (count($graph_x) - 1))
			{
				$date_this_array = explode("-", $graph_x[$j - 1]);
				$next_day  = mktime (0,0,0,$date_this_array[1], $date_this_array[2]+1, $date_this_array[0]);
				$graph_x[$j] = date("Y-m-d",$next_day);
				$graph_y[$j] = null;
				$graph_z[$j] = null;
			}
		}
	}
}

$access_array_day = array();
for ($j=0; $j<7; $j++)
{
	$this_array = array();
	$date_this_array = explode("-", $graph_x[$j]);
	$this_day  = mktime (0,0,0,$date_this_array[1], $date_this_array[2], $date_this_array[0]);
	$week_data = date("w", $this_day );
	$graph_x_date = date("n/j", $this_day );
	$graph_x[$j] = $graph_x_date . "(" . $week_array[$week_data] . ")";
	$this_array["x"] = $graph_x[$j];
	$this_array["y"] = $graph_y[$j];
	$this_array["z"] = $graph_z[$j];

	array_push($access_array_day, $this_array);
}


//------------------------------------------------ 접속통계 Month


$graph_x = array();
$graph_y = array();
$graph_z = array();

$month_default = date("Y-m",time());
$month_default = $month_default . "-01 00:00:00";
$result_count = sql_fetch("select count(*) as 'cnt' from g5_gnupushapp_statistics where grs_regdate > date_add('{$month_default}', interval -7 month) order by grs_regdate asc ");

if($result_count['cnt'] == 0)
{
	for ($j=0; $j<7; $j++)
	{
		$next_month  = mktime (0,0,0,$now_date_array[1]+$j, $now_date_array[2], $now_date_array[0]);
		$graph_x[$j] = date("Y-m",$next_month);
		$graph_y[$j] = null;
		$graph_z[$j] = null;
	}
}
else
{
	$result_data = sql_query("select * from g5_gnupushapp_statistics where grs_regdate > date_add('{$month_default}', interval -7 month) order by grs_regdate asc ");
	$j=0;
	$access_sum = 0;
	$total_sum = 0;
	$day_num = 0;
	for ($i=0; $rowddd=sql_fetch_array($result_data); $i++)
	{
		
		$this_date = date("Y-m", strtotime( $rowddd['grs_regdate'] ) );
		if($i == 0)
		{
			$graph_x[$j] = $this_date;
		}

		if($this_date != $graph_x[$j])
		{
			$graph_y[$j] = ceil($access_sum / $day_num);
			$graph_z[$j] = ceil($total_sum / $day_num);
			$j++;
			$graph_x[$j] = date("Y-m", strtotime( $rowddd['grs_regdate'] ) );
			$access_sum = 0;
			$total_sum = 0;
			$day_num = 0;
		}
		$access_sum = $access_sum + $rowddd['grs_access'];
		$total_sum = $total_sum + $rowddd['grs_total_device'];
		$day_num++;
	}
	$graph_y[$j] = ceil($access_sum / $day_num);
	$graph_z[$j] = ceil($total_sum / $day_num);

	$i = count($graph_x) - 1;

	for ($j=0; $j<7; $j++)
	{
		if($i < $j)
		{
			$date_this_array = explode("-", $graph_x[$j-1]);
			$next_month_time  = mktime (0,0,0,$date_this_array[1] + 1, 1, $date_this_array[0]);
			$graph_x[$j] = date("Y-m",$next_month_time);
			$graph_y[$j] = null;
			$graph_z[$j] = null;
		}
	}
}

$access_array_month = array();
for ($j=0; $j<7; $j++)
{
	$this_array = array();
	$this_array["x"] = $graph_x[$j];
	$this_array["y"] = $graph_y[$j];
	$this_array["z"] = $graph_z[$j];

	array_push($access_array_month, $this_array);
}


//------------------------------------------------ 신규설치 Day

$graph_x = array();
$graph_y = array();
$result_count = sql_fetch("select count(*) as 'cnt' from g5_gnupushapp_statistics order by grs_regdate desc limit 7");

if($result_count['cnt'] == 0)
{
	for ($j=0; $j<7; $j++)
	{
		$next_day  = mktime (0,0,0,$now_date_array[1], $now_date_array[2]+$j, $now_date_array[0]);
		$graph_x[$j] = date("Y-m-d",$next_day);
		$graph_y[$j] = null;
	}
}
else
{
	$result_data = sql_query("select * from g5_gnupushapp_statistics order by grs_regdate desc limit 7");
	for ($i=0; $rowddd=sql_fetch_array($result_data); $i++)
	{		
		$graph_x[$i] = date("Y-m-d", strtotime( $rowddd['grs_regdate'] ) );
		$graph_y[$i] = $rowddd['grs_new'];
	}

	$graph_x = array_reverse($graph_x);
	$graph_y = array_reverse($graph_y);

	$i = count($graph_x);
	$num_left = 7 - $i;

	if($num_left > 0)
	{
		for ($j=0; $j<7; $j++)
		{
			if($j > (count($graph_x) - 1))
			{
				$date_this_array = explode("-", $graph_x[$j - 1]);
				$next_day  = mktime (0,0,0,$date_this_array[1], $date_this_array[2]+1, $date_this_array[0]);
				$graph_x[$j] = date("Y-m-d",$next_day);
				$graph_y[$j] = null;
			}
		}
	}
}

$new_array_day = array();
for ($j=0; $j<7; $j++)
{
	$this_array = array();
	$date_this_array = explode("-", $graph_x[$j]);
	$this_day  = mktime (0,0,0,$date_this_array[1], $date_this_array[2], $date_this_array[0]);
	$week_data = date("w", $this_day );
	$graph_x_date = date("n/j", $this_day );
	$graph_x[$j] = $graph_x_date . "(" . $week_array[$week_data] . ")";
	$this_array["x"] = $graph_x[$j];
	$this_array["y"] = $graph_y[$j];

	array_push($new_array_day, $this_array);
}


//------------------------------------------------ 신규설치 Month


$graph_x = array();
$graph_y = array();

$month_default = date("Y-m",time());
$month_default = $month_default . "-01 00:00:00";
$result_count = sql_fetch("select count(*) as 'cnt' from g5_gnupushapp_statistics where grs_regdate > date_add('{$month_default}', interval -7 month) order by grs_regdate asc ");

if($result_count['cnt'] == 0)
{
	for ($j=0; $j<7; $j++)
	{
		
		$next_month  = mktime (0,0,0,$now_date_array[1]+$j, $now_date_array[2], $now_date_array[0]);
		$graph_x[$j] = date("Y-m",$next_month);
		$graph_y[$j] = null;
	}
}
else
{
	$result_data = sql_query("select * from g5_gnupushapp_statistics where grs_regdate > date_add('{$month_default}', interval -7 month) order by grs_regdate asc ");
	$j=0;
	$new_sum = 0;
	for ($i=0; $rowddd=sql_fetch_array($result_data); $i++)
	{
		
		$this_date = date("Y-m", strtotime( $rowddd['grs_regdate'] ) );
		if($i == 0)
		{
			$graph_x[$j] = $this_date;
		}

		if($this_date != $graph_x[$j])
		{
			$graph_y[$j] = $new_sum;
			$j++;
			$graph_x[$j] = date("Y-m", strtotime( $rowddd['grs_regdate'] ) );
			$new_sum = 0;
		}
		$new_sum = $new_sum + $rowddd['grs_new'];
	}
	$graph_y[$j] = $new_sum;

	$i = count($graph_x) - 1;

	for ($j=0; $j<7; $j++)
	{
		if($i < $j)
		{
			$date_this_array = explode("-", $graph_x[$j-1]);
			$next_month_time  = mktime (0,0,0,$date_this_array[1] + 1, 1, $date_this_array[0]);
			$graph_x[$j] = date("Y-m",$next_month_time);
			$graph_y[$j] = null;
		}
	}
}

$new_array_month = array();
for ($j=0; $j<7; $j++)
{
	$this_array = array();
	$this_array["x"] = $graph_x[$j];
	$this_array["y"] = $graph_y[$j];

	array_push($new_array_month, $this_array);
}


//------------------------------------------------ 푸시발생 Day

$graph_x = array();
$graph_y = array();
$result_count = sql_fetch("select count(*) as 'cnt' from g5_gnupushapp_statistics order by grs_regdate desc limit 7");

if($result_count['cnt'] == 0)
{
	for ($j=0; $j<7; $j++)
	{
		$next_day  = mktime (0,0,0,$now_date_array[1], $now_date_array[2]+$j, $now_date_array[0]);
		$graph_x[$j] = date("Y-m-d",$next_day);
		$graph_y[$j] = null;
	}
}
else
{
	$result_data = sql_query("select * from g5_gnupushapp_statistics order by grs_regdate desc limit 7");
	for ($i=0; $rowddd=sql_fetch_array($result_data); $i++)
	{		
		$graph_x[$i] = date("Y-m-d", strtotime( $rowddd['grs_regdate'] ) );
		$graph_y[$i] = $rowddd['grs_push'];
	}

	$graph_x = array_reverse($graph_x);
	$graph_y = array_reverse($graph_y);

	$i = count($graph_x);
	$num_left = 7 - $i;

	if($num_left > 0)
	{
		for ($j=0; $j<7; $j++)
		{
			if($j > (count($graph_x) - 1))
			{
				$date_this_array = explode("-", $graph_x[$j - 1]);
				$next_day  = mktime (0,0,0,$date_this_array[1], $date_this_array[2]+1, $date_this_array[0]);
				$graph_x[$j] = date("Y-m-d",$next_day);
				$graph_y[$j] = null;
			}
		}
	}
}

$push_array_day = array();
for ($j=0; $j<7; $j++)
{
	$this_array = array();
	$date_this_array = explode("-", $graph_x[$j]);
	$this_day  = mktime (0,0,0,$date_this_array[1], $date_this_array[2], $date_this_array[0]);
	$week_data = date("w", $this_day );
	$graph_x_date = date("n/j", $this_day );
	$graph_x[$j] = $graph_x_date . "(" . $week_array[$week_data] . ")";
	$this_array["x"] = $graph_x[$j];
	$this_array["y"] = $graph_y[$j];

	array_push($push_array_day, $this_array);
}


//------------------------------------------------ 푸시발생 Month


$graph_x = array();
$graph_y = array();

$month_default = date("Y-m",time());
$month_default = $month_default . "-01 00:00:00";
$result_count = sql_fetch("select count(*) as 'cnt' from g5_gnupushapp_statistics where grs_regdate > date_add('{$month_default}', interval -7 month) order by grs_regdate asc ");

if($result_count['cnt'] == 0)
{
	for ($j=0; $j<7; $j++)
	{
		
		$next_month  = mktime (0,0,0,$now_date_array[1]+$j, $now_date_array[2], $now_date_array[0]);
		$graph_x[$j] = date("Y-m",$next_month);
		$graph_y[$j] = null;
	}
}
else
{
	$result_data = sql_query("select * from g5_gnupushapp_statistics where grs_regdate > date_add('{$month_default}', interval -7 month) order by grs_regdate asc ");
	$j=0;
	$push_sum = 0;
	for ($i=0; $rowddd=sql_fetch_array($result_data); $i++)
	{
		
		$this_date = date("Y-m", strtotime( $rowddd['grs_regdate'] ) );
		if($i == 0)
		{
			$graph_x[$j] = $this_date;
		}

		if($this_date != $graph_x[$j])
		{
			$graph_y[$j] = $new_sum;
			$j++;
			$graph_x[$j] = date("Y-m", strtotime( $rowddd['grs_regdate'] ) );
			$push_sum = 0;
		}
		$push_sum = $push_sum + $rowddd['grs_push'];
	}
	$graph_y[$j] = $push_sum;

	$i = count($graph_x) - 1;

	for ($j=0; $j<7; $j++)
	{
		if($i < $j)
		{
			$date_this_array = explode("-", $graph_x[$j-1]);
			$next_month_time  = mktime (0,0,0,$date_this_array[1] + 1, 1, $date_this_array[0]);
			$graph_x[$j] = date("Y-m",$next_month_time);
			$graph_y[$j] = null;
		}
	}
}

$push_array_month = array();
for ($j=0; $j<7; $j++)
{
	$this_array = array();
	$this_array["x"] = $graph_x[$j];
	$this_array["y"] = $graph_y[$j];

	array_push($push_array_month, $this_array);
}


?>

<script>

  // Use Morris.Area instead of Morris.Line
Morris.Line({
  element: 'graph_day',
  data: <?php echo json_encode($access_array_day); ?>,
  xkey: 'x',
  hideHover: true,
  ykeys: ['y', 'z'],
  parseTime: false,
  labels: ['접속', '전체']
});


Morris.Line({
  element: 'graph_month',
  data: <?php echo json_encode($access_array_month); ?>,
  xkey: 'x',
  hideHover: true,
  ykeys: ['y', 'z'],
  parseTime: false,
  labels: ['접속', '전체']
});

// Use Morris.Bar
Morris.Bar({
  element: 'graph2_day',
  data: <?php echo json_encode($new_array_day); ?>,
  xkey: 'x',
  hideHover: true,
  ykeys: ['y'],
  parseTime: false,
  labels: ['신규설치']
});

  // Use Morris.Bar
Morris.Bar({
  element: 'graph2_month',
  data: <?php echo json_encode($new_array_month); ?>,
  xkey: 'x',
  ykeys: ['y'],
  hideHover: true,
  parseTime: false,
  labels: ['신규설치']
});

// Use Morris.Bar
Morris.Bar({
  element: 'graph3_day',
  data: <?php echo json_encode($push_array_day); ?>,
  xkey: 'x',
  hideHover: true,
  parseTime: false,
  ykeys: ['y'],
  labels: ['푸시']
});

  // Use Morris.Bar
Morris.Bar({
  element: 'graph3_month',
  data: <?php echo json_encode($push_array_month); ?>,
  xkey: 'x',
  hideHover: true,
  parseTime: false,
  ykeys: ['y'],
  labels: ['푸시']
});

</script>

<?php 
include_once('footer.php');
?>

    