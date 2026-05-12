<?php 
include_once('header.php');

$list_num = $_GET['list_num'];
$level_group = $_GET['level_group'];
$stx = $_GET['stx'];
$search_target = $_GET['search_target'];
$page = $_GET['page'];

$limit_count = $gnu_config["list_show"];

if($list_num && $list_num != $limit_count){
	
	$limit_count = $gnu_config["list_show"] = $list_num;
	$config_json = base64_encode(serialize($gnu_config));

	$sql = " update g5_gnupushapp_config
				set gc_text            = '{$config_json}',
					gc_reg_date           = '".G5_TIME_YMDHIS."'
					where gc_ix = '1'
				";
	sql_query($sql);
}

$sql_search = "";
$group_select = "";
$sync_sort_select = "";
$qstr = "";
$search_target_val = "mb_name";
if($search_target) $search_target_val = $search_target;

$array_search_target = array("mb_name"=>"이름","mb_id"=>"아이디","mb_email"=>"이메일","mb_nick"=>"닉네임");

if($level_group) $qstr = "level_group=".$level_group;
if($stx && $search_target)
{
	if($qstr == "")
	{
		$qstr = "stx=".$stx."&search_target=".$search_target;
	}
	else
	{
		$qstr = $qstr . "&stx=".$stx."&search_target=".$search_target;
	}
}

if(!$level_group) $level_group = "all";

if($level_group != "all")
{
	if($level_group == "android"){
		$sync_sort_select = "gpr_sort = 'A'";
	}else if($level_group == "IOS"){
		$sync_sort_select = "gpr_sort = 'I'";
	}else if($level_group == "sync"){
		$sync_sort_select = "gpr_sync != 'N'";
	}else if($level_group == "nosync"){
		$sync_sort_select = "gpr_sync = 'N'";
	}else if($level_group == "today_install"){
		$sync_sort_select = "gpr_regdate > CURRENT_DATE()";
	}else if($level_group == "today_access"){
		$sync_sort_select = "gpr_last_login > CURRENT_DATE()";
	}else{
		$group_select = "mb_level = ".$level_group;
	}
}

if ($stx && $search_target){
	if($sync_sort_select) $sync_sort_select .= " and";
	if($group_select) $group_select .= " and";
	$sql_search = " WHERE {$sync_sort_select} gpr_mb_id in (SELECT mb_id FROM {$g5['member_table']} WHERE {$group_select} {$search_target} like '%{$stx}%' ) ";
}else{
	if($sync_sort_select || $group_select){
		$sql_search = " WHERE ";
		if($sync_sort_select && $group_select){
			$sql_search .= "{$sync_sort_select} and gpr_mb_id in (SELECT mb_id FROM {$g5['member_table']} WHERE {$group_select} )";
		}else{
			if($sync_sort_select) $sql_search .= $sync_sort_select;
			if($group_select) $sql_search .= " gpr_mb_id in (SELECT mb_id FROM {$g5['member_table']} WHERE {$group_select} )";
		}
	}
}

$page_limit = 0;

if(!$page) $page=1;

$page_limit = $page_limit + ($page-1)*$limit_count;

$sql = " SELECT * FROM  g5_gnupushapp_gcmregid as gcm {$sql_search} ORDER BY  gpr_regdate DESC LIMIT {$page_limit} , {$limit_count}";
$result = sql_query($sql);

$sql_1 = " SELECT count(*) as 'cnt' FROM g5_gnupushapp_gcmregid";
$row_count1 = sql_fetch($sql_1);

$sql_2 = " SELECT count(*) as 'cnt' FROM `g5_gnupushapp_gcmregid` as `gcm` {$sql_search}";
$row_count2 = sql_fetch($sql_2);
$total_count = $row_count1['cnt'];
$now_count = $row_count2['cnt'];
$colspan = 11;
$total_page  = ceil($now_count / $limit_count);

if($qstr == "")
{
	$qstr = "page=";
}
else
{
	$qstr = $qstr . "&page=";
}

?>

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">	  
      <section class="content-header">
        <h1>
          등록기기목록
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="index.php">
              <i class="fa fa-dashboard"></i> Home</a>
          </li>
          <li class="active">등록기기목록</li>
        </ol>
      </section>
      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-lg-12">
				<div class="callout callout-info" style="margin-top:10px;">
				  총 등록기기 수 : <?php echo number_format($total_count);?> &nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp; 현재 검색된 등록기기 수 : <?php echo number_format($now_count);?>
				</div>
			</div>
		</div>

        <div class="row">
          <div class="col-lg-12">
            <div class="box">
				<div class="box-header with-border">
				  <h3 class="box-title">검색</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">

				<form name="fsearch" id="fsearch" method="get" class="form-inline" style="padding-top:10px;">
				  <div class="form-group mb-3">
				    <label>그룹선택 : </label>
                    <select class="form-control" name="level_group" style="margin-left:6px;">
                     <option value="all" <?php if($level_group == "all") { ?>selected="selected" <?php } ?>>전체</option>
					 <option value="1" <?php if($level_group == 1) { ?>selected="selected" <?php } ?>>1</option>
					 <option value="2" <?php if($level_group == 2) { ?>selected="selected" <?php } ?>>2</option>
					 <option value="3" <?php if($level_group == 3) { ?>selected="selected" <?php } ?>>3</option>
					 <option value="4" <?php if($level_group == 4) { ?>selected="selected" <?php } ?>>4</option>
					 <option value="5" <?php if($level_group == 5) { ?>selected="selected" <?php } ?>>5</option>
					 <option value="6" <?php if($level_group == 6) { ?>selected="selected" <?php } ?>>6</option>
					 <option value="7" <?php if($level_group == 7) { ?>selected="selected" <?php } ?>>7</option>
					 <option value="8" <?php if($level_group == 8) { ?>selected="selected" <?php } ?>>8</option>
					 <option value="9" <?php if($level_group == 9) { ?>selected="selected" <?php } ?>>9</option>
					 <option value="10" <?php if($level_group == 10) { ?>selected="selected" <?php } ?>>10</option>
					 <option value="android" <?php if($level_group == "android") { ?>selected="selected" <?php } ?>>Android</option>
					 <option value="IOS" <?php if($level_group == "IOS") { ?>selected="selected" <?php } ?>>IOS</option>
					 <option value="sync" <?php if($level_group == "sync") { ?>selected="selected" <?php } ?>>동기화된 기기</option>
					 <option value="nosync" <?php if($level_group == "nosync") { ?>selected="selected" <?php } ?>>비동기화된 기기</option>
					 <option value="today_install" <?php if($level_group == "today_install") { ?>selected="selected" <?php } ?>>오늘신규설치</option>
					 <option value="today_access" <?php if($level_group == "today_access") { ?>selected="selected" <?php } ?>>오늘접속</option>
                    </select>
				  </div>
				  <div class="form-group mx-sm-3 mb-2" style="padding-left:20px;">
				  <input type="hidden" id="search_target" name="search_target" value="<?php echo $search_target_val; ?>">
					<label class="sr-only">Search</label>
					  <div class="input-group">
						<div class="input-group-btn">
						  <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><span id="actiontitle"><?php echo $array_search_target[$search_target_val];?></span>
							<span class="fa fa-caret-down"></span></button>
						  <ul class="dropdown-menu">
							<li id="mb_name"><a href="#">이름</a></li>
							<li id="mb_id"><a href="#">아이디</a></li>
							<li id="mb_email"><a href="#">이메일</a></li>
							<li id="mb_nick"><a href="#">닉네임</a></li>
						  </ul>
						</div>
						<!-- /btn-group -->
						<input type="text" class="form-control" name="stx" value="<?php echo $stx ?>">
					  </div>
				  </div>
				  <div class="form-group mb-2" style="padding-left:20px;">
					<button type="submit" class="btn btn-primary">검색</button>
				  </div>
				</form>

				<form name="flist" id="flist" method="get" class="form-inline" style="padding-top:25px;margin-bottom:15px;">
				  <div class="form-group mb-2">
				    <label><b>한번에 볼 목록수 : </b></label>
				  </div>
				  <div class="form-group mx-sm-3 mb-2" style="padding-left:10px;">
					<label class="sr-only">Search</label>
					<select class="form-control" name="list_num">
						<option value="10" <?php if($gnu_config["list_show"] == 10) { ?>selected="selected" <?php } ?>>10</option>
						<option value="30" <?php if($gnu_config["list_show"] == 30) { ?>selected="selected" <?php } ?>>30</option>
						<option value="60" <?php if($gnu_config["list_show"] == 60) { ?>selected="selected" <?php } ?>>60</option>
						<option value="100" <?php if($gnu_config["list_show"] == 100) { ?>selected="selected" <?php } ?>>100</option>
						<option value="500" <?php if($gnu_config["list_show"] == 500) { ?>selected="selected" <?php } ?>>500</option>
					</select>
				  </div>
				  <div class="form-group mb-2" style="padding-left:10px;">
					<button type="submit" class="btn btn-primary">보기</button>
				  </div>
				</form>

				</div>
				<!-- /.box-body -->
			  </div>
          </div>
        </div>

		<form action="device_list_proc.php" id="pushform" method="post" enctype="multipart/form-data">
		<input type="hidden" name="action_sort" id="action_sort" value="">

		<div class="modal modal-info fade" id="modal-info">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">선택기기푸시알림</h4>
              </div>
              <div class="modal-body">
                <p>선택된 기기수 : <span id="select_devices"></span></p>
				<div class="form-group" style="margin-bottom:20px;">
				  <label for="push_title">제목</label>
				  <input type="text" class="form-control" id="push_title"  name="push_title">
			    </div>
				<div class="form-group" style="margin-bottom:20px;">
				  <label for="push_content">내용</label>
				  <input type="text" class="form-control" id="push_content"  name="push_content">
			    </div>
				<div class="form-group" style="margin-bottom:20px;">
				  <label for="push_link">링크주소</label>
				  <input type="text" class="form-control" id="push_link"  name="push_link">
			    </div>
				<div class="form-group" style="margin-bottom:20px;">
				  <label for="push_link">이미지파일(500px X 250px)</label>
				  <input type="file" name="push_img" />
			    </div>
				<div class="form-group" style="margin-bottom:20px;">
					<div class="checkbox icheck-primary">
						<input type="checkbox" id="use_marketing" name="use_marketing" value="Y"/>
						<label for="use_marketing">마케팅알림</label>
					</div>
				</div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-outline" id="gopush">선택기기푸시알림</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

		<div class="row">
          <div class="col-lg-12">
            <div class="box">
				<div class="box-header with-border">
				  <h3 class="box-title">등록기기목록</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">

					<div style="margin-bottom:10px;float:right;">
						<button type="button" class="btn btn-default" id="clearance">등록기기정리</button>
						<button type="button" class="btn btn-danger" style="margin-left:10px;" id="delete">선택기기삭제</button>
						<button type="button" class="btn btn-info" style="margin-left:10px;" data-toggle="modal" data-target="#modal-info" id="select_push">선택기기푸시알림</button>
					</div>
					<table class="table table-striped table-bordered table-hover" >
						<tr>
							<td><center><b><input type="checkbox" name="chkall" value="1" id="checkAll"></b></center></td>
							<td><center><b>종류</b></center></td>
							<td><center><b>앱버전</b></center></td>
							<td><center><b>이름(id)</b></center></td>
							<td><center><b>닉네임(level)</b></center></td>
							<td><center><b>email</b></center></td>
							<td><center><b>소셜로그인</b></center></td>
							<td><center><b>reg_id</b></center></td>
							<td><center><b>기기정보</b></center></td>
							<td><center><b>설치한날짜</b></center></td>
							<td><center><b>최근접속</b></center></td>
						</tr>
						<?php
						$count = 0;
						for ($i=0; $row=sql_fetch_array($result); $i++)
						{
							$row_member = get_member($row['gpr_mb_id']);
							$reg_id_cut = cut_str($row['gpr_reg_id'], 20);
							if($row['gpr_sort'] == "A") $sort_gpr = "Android";
							if($row['gpr_sort'] == "I") $sort_gpr = "IOS";
							$social_val = "";
							if($row['gpr_social'] != 'none'){
								$array_social = explode("#$%",$row['gpr_social']);
								$social_val = $array_social[0];
							}
							
						?>
						<tr>
							<td>
								<center><input type="checkbox" name="chk[]" value="<?php echo $row['gpr_reg_id'] ?>" id="chk_<?php echo $i ?>"></center>
							</td>
							<td><center><?php echo $sort_gpr ?></center></td>
							<td><center><?php echo $row['gpr_version'] ?></center></td>
							<td><center><?php if($row['gpr_mb_id']) echo $row_member['mb_name']."(".$row_member['mb_id'].")"; ?></center></td>
							<td><center><?php echo $row_member['mb_nick'] ?><?php if($row_member['mb_level']) echo "(".$row_member['mb_level'].")"; ?></center></td>
							<td><center><?php echo $row_member['mb_email'] ?></center></td>
							<td><center><?php echo $social_val; ?></center></td>
							<td><center><?php echo $reg_id_cut ?></center></td>
							<td><center><?php echo $row['gpr_phoneinfo'] ?></center></td>
							<td><center><?php echo $row['gpr_regdate'] ?></center></td>
							<td><center><?php echo $row['gpr_last_login'] ?></center></td>
						</tr>
						<?php
							$count++;
						}

						if ($count == 0)
							echo '<tr><center><td colspan="'.$colspan.'">자료가 없습니다.</center></td></tr>';
						?>
					</table>

					<div style="margin-top:10px;float:right;">
						<button type="button" class="btn btn-default" id="clearance2">등록기기정리</button>
						<button type="button" class="btn btn-danger" style="margin-left:10px;" id="delete2">선택기기삭제</button>
						<button type="button" class="btn btn-info" style="margin-left:10px;" data-toggle="modal" data-target="#modal-info" id="select_push2">선택기기푸시알림</button>
					</div>

					<div style="margin-top:10px;float:left;">
						<div class="dataTables_info" id="example2_info">현재페이지 : <?php echo $page ?> / 전체페이지 : <?php echo $total_page ?></div>
					</div>
					<div class="row" style="clear:both;">
						<div class="col-sm-12">
							<center>
							<div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
								<ul class="pagination">
									<li class="paginate_button previous <?php if($page == 1) {?>disabled<?php } ?>">
										<a href="<?php echo G5_URL.'/gnupushapp_admin/device_list.php?'.$qstr.'1'; ?>">◀◀</a>
									</li>
									<li class="paginate_button previous <?php if($page == 1) {?>disabled<?php } ?>">
										<a href="<?php echo G5_URL.'/gnupushapp_admin/device_list.php?'.$qstr.($page-1); ?>">Previous</a>
									</li>

									<?php
									$aaaaaa = $page/10;
									$bbbbbb = floor($aaaaaa);
									if(($page % 10) == 0) $bbbbbb--;

									for($i=0;$i<10;$i++)
									{
										$now_number = ($i+1) + ($bbbbbb*10);
										if($now_number > $total_page) break;
									?>
									<li class="paginate_button <?php if($page == $now_number) {?>active<?php } ?>">
										<a href="<?php echo G5_URL.'/gnupushapp_admin/device_list.php?'.$qstr.$now_number; ?>" ><?php echo $now_number ?></a>
									</li>
									<?php } ?>
									<li class="paginate_button next <?php if($page == $total_page) {?>disabled<?php } ?>" id="example2_next">
										<a href="<?php echo G5_URL.'/gnupushapp_admin/device_list.php?'.$qstr.($page+1); ?>" >Next</a>
									</li>
									<li class="paginate_button next <?php if($page == $total_page) {?>disabled<?php } ?>">
										<a href="<?php echo G5_URL.'/gnupushapp_admin/device_list.php?'.$qstr.$total_page; ?>" >▶▶</a>
									</li>
								</ul>
							</div>
							</center>
						</div>
					</div>

				</div>
				<!-- /.box-body -->
			  </div>
          </div>
        </div>

		</form>

		
        
       
      </section>
      <!-- /.content -->


    </div>
    <!-- /.content-wrapper -->

<script type="text/javascript">

jQuery(function($){

	$("#checkAll").click(function(){
		$('input:checkbox').not(this).prop('checked', this.checked);
	});

	$("#mb_name").click(function() {
		$("#actiontitle").text("이름");
		$("#search_target").val("mb_name");		
	});

	$("#mb_id").click(function() {
		$("#actiontitle").text("아이디");
		$("#search_target").val("mb_id");
	});

	$("#mb_email").click(function() {
		$("#actiontitle").text("이메일");
		$("#search_target").val("mb_email");
	});

	$("#mb_nick").click(function() {
		$("#actiontitle").text("닉네임");
		$("#search_target").val("mb_nick");
	});

	$("#select_push").click(function() {
		if($('input:checkbox[name="chk[]"]:checked').length == 0){
			$("#select_devices").text("0");
			alert('푸시알림할 기기목록을 하나 이상 체크하세요.');
		}else{
			var num = $('input:checkbox[name="chk[]"]:checked').length;
			$("#select_devices").text(num);
		}
	});

	$("#select_push2").click(function() {
		if($('input:checkbox[name="chk[]"]:checked').length == 0){
			$("#select_devices").text("0");
			alert('푸시알림할 기기목록을 하나 이상 체크하세요.');
		}else{
			var num = $('input:checkbox[name="chk[]"]:checked').length;
			$("#select_devices").text(num);
		}
	});

	$("#gopush").click(function() {
		if($('input:checkbox[name="chk[]"]:checked').length == 0){
			alert('푸시알림할 기기목록을 하나 이상 체크하세요.');
		}else if($('#push_title').val().length === 0 || jQuery('#push_content').val().length === 0){
			alert('푸시알림할 제목과 내용을 적어주세요.');
		}else{
			$("#action_sort").val("push");
			$("#pushform").submit();
		}
	});

	$("#clearance").click(function() {
		$("#action_sort").val("clearance");
		$("#pushform").submit();
	});

	$("#clearance2").click(function() {
		$("#action_sort").val("clearance");
		$("#pushform").submit();
	});

	$("#delete").click(function() {
		if($('input:checkbox[name="chk[]"]:checked').length == 0){			
			alert('선택된 기기가 없습니다.');
		}else{
			var check = confirm("정말 선택된 기기를 삭제하시겠습니까?");
			if(check)
			{
				$("#action_sort").val("delete");
				$("#pushform").submit();
			}
		}
		
	});

	$("#delete2").click(function() {
		if($('input:checkbox[name="chk[]"]:checked').length == 0){			
			alert('선택된 기기가 없습니다.');
		}else{
			var check = confirm("정말 선택된 기기를 삭제하시겠습니까?");
			if(check)
			{
				$("#action_sort").val("delete");
				$("#pushform").submit();
			}
		}
	});
});
</script>

<?php 
include_once('footer.php');
?>
