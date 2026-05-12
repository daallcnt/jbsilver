<?php 
include_once('header.php');
?>

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
	  <form role="form" name="frm" id="frm" action="config_update.php" method="post" enctype="multipart/form-data">
	  <input type="hidden" name="config_sort" value="board" />
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          푸시게시판설정
          <small>환경설정</small>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="index.php">
              <i class="fa fa-dashboard"></i> Home</a>
          </li>
          <li class="active">환경설정</li>
		  <li class="active">푸시게시판설정</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->

		<div class="row">
          <div class="col-lg-12">
            <div class="box box-danger box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">기본설정</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">

				<div class="form-group" style="margin-bottom:20px;">
				    <label><b>푸시 적용 안할 게시판 & 알림목록 순서 설정</b></label>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">선택한 모듈에서는 새글이나 새댓글이 올라와도 푸시기능을 하지 않습니다. 아래, 위 버튼을 눌러주셔서 알림설정 창에 나올 게시판들의 목록 순서를 정해주세요. 이 항목에서 설정하여 등록한 게시판만 알림설정 창에 뜹니다. <font color=red><b>새로운 게시판 생성했을 경우나 삭제 했을 경우에는 꼭 본 환경설정에서 다시 '수정'버튼을 눌러주셔야 알리설정 창이 제대로 뜹니다.</font></b></p>
					<?php
					$sql = " select * from {$g5['board_table']}";
					$result = sql_query($sql);

					$new_board_item = array();
					$board_item = array();

					unset($row);
					for ($i=0; $row_board=sql_fetch_array($result); $i++) {

						$exist_board = false;

						if($gnu_config['module_order'])
						{

							foreach($gnu_config['module_order'] as $key)
							{

								$key_bo_table = "";
								if(strpos($key, "#/") !== false)
								{  
									$key_array = explode("#/", $key);
									$key_bo_table = $key_array[1];
								}
								else
								{
									$key_array = explode("_", $key);
									if(count($key_array) > 2)
									{
										for($ii=0;$ii<count($key_array);$ii++)
										{
											if($ii==0) continue;
											if($ii==1)
											{
												$key_bo_table = $key_array[$ii];
												continue;
											}
											$key_bo_table .= "_".$key_array[$ii];
										}
									}
									else
									{
										$key_bo_table = $key_array[1];
									}
								}

								if(!is_array($board_item) || !in_array($key_bo_table, $board_item)) array_push($board_item,$key_bo_table);

								if($row_board['bo_table'] == $key_bo_table){
									$exist_board = true;
								}
							}
						}
						if(!$exist_board) array_push($new_board_item,$row_board['bo_table']);
					}

					$i=0;
					foreach($board_item as $key_bo_table)
					{

						$sql = " select * from {$g5['board_table']} where bo_table = '{$key_bo_table}'";
						$row = sql_fetch($sql);
						if($row){
					?>
					<div id="item_srl_<?php echo $row['bo_table'] ?>" style="padding-top:10px;">
					<img src="up.png" border=0 onclick="up('<?php echo $row['bo_table'] ?>');"><img src="down.png" border=0 onclick="down('<?php echo $row['bo_table'] ?>');">
						<label>
							<input type="hidden" id="input_item_<?php echo $row['bo_table'] ?>" name="module_order[]" value="<?php echo $i ?>#/<?php echo $row['bo_table'] ?>">
							<input type="checkbox" value="<?php echo $row['bo_table'] ?>" name="no_use_module_srls[]" <?php if(is_array($gnu_config['no_use_module_srls']) && in_array($row['bo_table'], $gnu_config["no_use_module_srls"])) { ?>checked="checked"<?php } ?> onClick="aaa(this);"><strong><?php echo get_text($row['bo_subject']) ?></strong> (<?php echo $row['gr_id'] ?>/<?php echo $row['bo_table'] ?>)
						</label>
					</div>
					<?php 
					$i++;

						}
					}

					foreach($new_board_item as $key_bo_table)
					{

						$sql = " select * from {$g5['board_table']} where bo_table = '{$key_bo_table}'";
						$row = sql_fetch($sql);
					
					
					?>
					<div id="item_srl_<?php echo $row['bo_table'] ?>" style="padding-top:10px;">
						<img src="up.png" border=0 onclick="up('<?php echo $row['bo_table'] ?>');"><img src="down.png" border=0 onclick="down('<?php echo $row['bo_table'] ?>');">
						<label>	
							<input type="hidden" id="input_item_<?php echo $row['bo_table'] ?>" name="module_order[]" value="<?php echo $i ?>#/<?php echo $row['bo_table'] ?>">
							<input type="checkbox" value="<?php echo $row['bo_table'] ?>" name="no_use_module_srls[]" <?php if(is_array($gnu_config['no_use_module_srls']) && in_array($row['bo_table'], $gnu_config["no_use_module_srls"])) { ?>checked="checked"<?php } ?> onClick="aaa(this);"><strong><?php echo get_text($row['bo_subject']) ?></strong> (<?php echo $row['gr_id'] ?>/<?php echo $row['bo_table'] ?>)
						</label>
					</div>
					  <?php 
					  $i++;
					  }

					  $item_srl = $i;
					  ?>
				  </div>
				  <div class="row" style="margin-bottom:20px;">
						<div class="col-lg-12">
							<center><input type="submit" value="수정" class="btn btn-danger"></center>
						</div>
					</div>

				</div>
				<!-- /.box-body -->
			  </div>
          </div>
		  
        </div>

		<div class="row">
          <div class="col-lg-12">
            <div class="box box-success box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">상담게시판</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">

				<div class="form-group" style="margin-bottom:20px;">
				    <label><b>상담 게시판</b></label>
					<p class="help-block">관리자 또는 게시판 관리자에게만 푸시알림이 가도록 할 게시판을 선택합니다.</p>
					<?php
					$sql = " select * from {$g5['board_table']}";
					$result = sql_query($sql);
					for ($i=0; $row=sql_fetch_array($result); $i++) {
					?>
					<div style="padding-top:10px;">
						<label>
							<input type="checkbox" value="<?php echo $row['bo_table'] ?>" name="only_admin_push_module_srls[]" <?php if(is_array($gnu_config['no_use_module_srls']) && in_array($row['bo_table'], $gnu_config["no_use_module_srls"])) { ?>disabled<?php }else if(is_array($gnu_config['only_admin_push_module_srls']) && in_array($row['bo_table'], $gnu_config["only_admin_push_module_srls"])) { ?>checked="checked"<?php } ?>><strong><?php echo get_text($row['bo_subject']) ?></strong> (<?php echo $row['gr_id'] ?>/<?php echo $row['bo_table'] ?>)
						</label>
					</div>
					<?php } ?>
				  </div>

				  <div class="row" style="margin-bottom:20px;">
						<div class="col-lg-12">
							<center><input type="submit" value="수정" class="btn btn-danger"></center>
						</div>
					</div>

				</div>
				<!-- /.box-body -->
			  </div>
          </div>
        </div>

		<div class="row">
          <div class="col-lg-12">
            <div class="box box-primary box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">관리자 공지 게시판</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">

				<div class="form-group" style="margin-bottom:20px;">
				    <label><b>관리자 공지 게시판</b></label>
					<?php
					$sql = " select * from {$g5['board_table']}";
					$result = sql_query($sql);
					for ($i=0; $row=sql_fetch_array($result); $i++) {
					?>
					<div style="padding-top:10px;">
						<label>
							<input type="checkbox" value="<?php echo $row['bo_table'] ?>" name="notice_module_srls[]" <?php if(is_array($gnu_config['no_use_module_srls']) && in_array($row['bo_table'], $gnu_config["no_use_module_srls"])) { ?>disabled<?php }else if(is_array($gnu_config['notice_module_srls']) && in_array($row['bo_table'], $gnu_config["notice_module_srls"])) { ?>checked="checked"<?php } ?>><strong><?php echo get_text($row['bo_subject']) ?></strong> (<?php echo $row['gr_id'] ?>/<?php echo $row['bo_table'] ?>)
						</label>
					</div>
					<?php } ?>
				  </div>

				  <div class="row" style="margin-bottom:20px;">
						<div class="col-lg-12">
							<center><input type="submit" value="수정" class="btn btn-danger"></center>
						</div>
					</div>

				</div>
				<!-- /.box-body -->
			  </div>
          </div>
        </div>

		<div class="row">
          <div class="col-lg-12">
            <div class="box box-warning box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">카테고리 사용 게시판</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">

				<div class="form-group" style="margin-bottom:20px;">
				    <label><b>카테고리 사용 게시판</b></label>
					<div>
						<div class="radio-inline icheck-primary">
							<input type="radio" id="category_defaultN" name="category_default" value="N" <?php if($gnu_config["category_default"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="category_defaultN">카테고리 접음</label>
						</div>
						<div class="radio-inline icheck-primary">
							<input type="radio" id="category_defaultY1" name="category_default" value="Y1" <?php if($gnu_config["category_default"] == "Y1") { ?>checked="checked" <?php } ?> />
							<label for="category_defaultY1">1단까지 펼침</label>
						</div>
					</div>
					<?php
					$sql = " select * from {$g5['board_table']}";
					$result = sql_query($sql);
					for ($i=0; $row=sql_fetch_array($result); $i++) {
					?>
					<div style="padding-top:10px;">
						<label>
							<input type="checkbox" value="<?php echo $row['bo_table'] ?>" name="category_module_srls[]" <?php if(is_array($gnu_config['no_use_module_srls']) && in_array($row['bo_table'], $gnu_config["no_use_module_srls"])) { ?>disabled<?php }else if(is_array($gnu_config['category_module_srls']) && in_array($row['bo_table'], $gnu_config["category_module_srls"])) { ?>checked="checked"<?php } ?>><strong><?php echo get_text($row['bo_subject']) ?></strong> (<?php echo $row['gr_id'] ?>/<?php echo $row['bo_table'] ?>)
						</label>
					</div>
					<?php } ?>
				  </div>

				  <div class="row" style="margin-bottom:20px;">
						<div class="col-lg-12">
							<center><input type="submit" value="수정" class="btn btn-danger"></center>
						</div>
					</div>

				</div>
				<!-- /.box-body -->
			  </div>
          </div>
        </div>

		<div class="row">
          <div class="col-lg-12">
            <div class="box box-primary box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">그룹 게시판</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">

				<div class="form-group" style="margin-bottom:20px;">
				    <label><b>그룹 게시판</b></label>
					<p class="help-block">게시판 그룹에 접근가능한 회원만 구독가능한 게시판입니다.</p>
					<?php
					$sql = " select * from {$g5['board_table']}";
					$result = sql_query($sql);
					for ($i=0; $row=sql_fetch_array($result); $i++) {
					?>
					<div style="padding-top:10px;">
						<label>
							<input type="checkbox" value="<?php echo $row['bo_table'] ?>" name="group_module_srls[]" <?php if(is_array($gnu_config['no_use_module_srls']) && in_array($row['bo_table'], $gnu_config["no_use_module_srls"])) { ?>disabled<?php }else if(is_array($gnu_config['group_module_srls']) && in_array($row['bo_table'], $gnu_config["group_module_srls"])) { ?>checked="checked"<?php } ?>><strong><?php echo get_text($row['bo_subject']) ?></strong> (<?php echo $row['gr_id'] ?>/<?php echo $row['bo_table'] ?>)
						</label>
					</div>
					<?php } ?>
				  </div>

				  <div class="row" style="margin-bottom:20px;">
						<div class="col-lg-12">
							<center><input type="submit" value="수정" class="btn btn-danger"></center>
						</div>
					</div>

				</div>
				<!-- /.box-body -->
			  </div>
          </div>
        </div>

		<div class="row">
          <div class="col-lg-12">
            <div class="box box-danger box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">신고기능</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">

				<div class="form-group" style="margin-bottom:20px;">
				    <label><b>신고기능사용</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="use_report_gnuY" name="use_report_gnu" value="Y" <?php if($gnu_config["use_report_gnu"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="use_report_gnuY">사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="use_report_gnuN" name="use_report_gnu" value="N" <?php if($gnu_config["use_report_gnu"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="use_report_gnuN">사용안함</label>
						</div>
					</div>
				  </div>

				  <div class="row" style="margin-bottom:20px;">
						<div class="col-lg-12">
							<center><input type="submit" value="수정" class="btn btn-danger"></center>
						</div>
					</div>

				</div>
				<!-- /.box-body -->
			  </div>
          </div>
        </div>
       
      </section>
      <!-- /.content -->

	  </form>


    </div>
    <!-- /.content-wrapper -->

<script type="text/javascript">

function up(b)
{

	var sel_val = jQuery("#input_item_"+b).val();
	var strArray_sel = sel_val.split('#/');
	var tt = strArray_sel[0];
	tt *= 1;
	if(tt == 0){

	}
	else
	{
		var rr = tt-1;
		jQuery("#input_item_"+b).val(rr+"#/"+b);

		var obj_prev = jQuery("#item_srl_"+b).prev();
		var currentId = obj_prev.attr('id');
		var strArray_upitem = currentId.split('_');
		var upitem_module_srl = strArray_upitem[2];
		jQuery("#input_item_"+upitem_module_srl).val(tt+"#/"+upitem_module_srl);

		jQuery("#item_srl_"+b).insertBefore(obj_prev);

	}
}

function down(b)
{

	var sel_val = jQuery("#input_item_"+b).val();
	var strArray_sel = sel_val.split('#/');
	var tt = strArray_sel[0];
	tt *= 1;
	if(tt == (<?php echo $item_srl ?>-1))
	{

	}
	else
	{
		var rr = tt+1;
		jQuery("#input_item_"+b).val(rr+"#/"+b);	

		var obj_next = jQuery("#item_srl_"+b).next();
		var currentId = obj_next.attr('id');
		var strArray_downitem = currentId.split('_');
		var downitem_module_srl = strArray_downitem[2];
		jQuery("#input_item_"+downitem_module_srl).val(tt+"#/"+downitem_module_srl);

		var obj_next = jQuery("#item_srl_"+b).next();
		jQuery("#item_srl_"+b).insertAfter(obj_next);

	}

}

function aaa(checkedObj)
{
	var obj = document.getElementsByName("only_admin_push_module_srls[]");
	var obj2 = document.getElementsByName("category_module_srls[]");
	var obj3 = document.getElementsByName("notice_module_srls[]");
	var obj7 = document.getElementsByName("group_module_srls[]");

	if(checkedObj.checked)
	{
		for(var i=0; i< obj.length; i++)
		{
			if(obj[i].value == checkedObj.value)
			{
				obj[i].checked = false;
				obj[i].disabled = true;
				obj2[i].checked = false;
				obj2[i].disabled = true;
				obj3[i].checked = false;
				obj3[i].disabled = true;
				obj7[i].checked = false;
				obj7[i].disabled = true;
			}
		}
	}
	else
	{
		for(var i=0; i< obj.length; i++)
		{
			if(obj[i].value == checkedObj.value)
			{
				obj[i].disabled = false;
				obj2[i].disabled = false;
				obj3[i].disabled = false;
				obj7[i].disabled = false;
			}
		}
	}
}

</script>

<?php 
include_once('footer.php');
?>
