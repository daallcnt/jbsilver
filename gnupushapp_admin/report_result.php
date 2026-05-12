<?php 
include_once('header.php');

$page = $_GET['page'];

$page_limit = 0;

if(!$page) $page=1;

$page_limit = $page_limit + ($page-1)*30;

$sql = " SELECT * FROM  g5_board_report_gnu ORDER BY  bre_regdate DESC LIMIT {$page_limit} , 30";
$result = sql_query($sql);

$colspan = 8;

$sql_1 = " SELECT count(*) as 'cnt' FROM g5_board_report_gnu";
$row_count1 = sql_fetch($sql_1);
$total_page  = ceil($row_count1['cnt'] / 30);

?>

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">	  
      
      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->

		<div class="row">
          <div class="col-lg-12">
            <div class="box">
				<div class="box-header with-border">
				  <h3 class="box-title">게시글신고내용</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<table class="table table-striped table-bordered table-hover" >
						<tr>
							<td><center><b>종류</b></center></td>
							<td><center><b>게시판</b></center></td>
							<td><center><b>내용</b></center></td>
							<td><center><b>글작성자</b></center></td>
							<td><center><b>신고자</b></center></td>
							<td><center><b>날짜(수정된날짜)</b></center></td>
							<td><center><b>상태</b></center></td>
							<td><center><b>적용</b></center></td>
						</tr>
						<?php
						$count = 0;
						for ($i=0; $row=sql_fetch_array($result); $i++)
						{
							$board_config = sql_fetch(" select * from {$g5['board_table']} where bo_table = '{$row['bre_bo_table']}' ");
							$tmp_write_table = $g5['write_prefix'] . $row['bre_bo_table']; // 게시판 테이블 전체이름
							$sql = " select * from {$tmp_write_table} where wr_id = '{$row['bre_wr_id']}' ";
							$result_row = sql_fetch($sql);

							$member_wr = get_member($row['bre_target_mb_id']);
							$member_rp = get_member($row['bre_mb_id']);

							$address_board = G5_BBS_URL.'/board.php?bo_table='.$row['bre_bo_table'];
							$address_post = G5_BBS_URL.'/board.php?bo_table='.$row['bre_bo_table'].'&wr_id='.$row['bre_wr_id'];

							$status = "신고";
							if($row['bre_status'] == "D") $status = "삭제완료";
							if($row['bre_status'] == "B") $status = "반려";

							$type = "게시글";
							$title_report = $result_row['wr_subject'];

							if($row['bre_type'] == "C"){
								$type = "댓글";
								$address_post = G5_BBS_URL.'/board.php?bo_table='.$row['bre_bo_table'].'&wr_id='.$result_row['wr_parent'].'#c_'.$row['bre_wr_id'];
								$title_report = $result_row['wr_content'];
							}
							if($row['bre_status'] == "D"){
								$title_report = cut_str(strip_tags($row['bre_original_text']), 200, '');
							}
							
						?>
						<tr>
							<td align="center"><?php echo $type; ?></td>
							<td align="center"><a href="<?php echo $address_board; ?>" target=_blank><?php echo $board_config['bo_subject']; ?></a></td>
							<td align="center"><a href="<?php echo $address_post; ?>" target=_blank><?php echo $title_report; ?></a></td>

							<td align="center"><?php echo $member_wr['mb_nick']; ?></td>
							<td align="center"><?php echo $member_rp['mb_nick']; ?></td>


							<td align="center"><?php echo $row['bre_regdate']; ?>(<?php echo $row['bre_confirm']; ?>)</td>

							<td align="center"><span id="status_<?php echo $i;?>" style="display: block;"><?php echo $status; ?></span></td>

							<td align="center"><?php if($row['bre_status'] == "N") {?><span id="confirm_<?php echo $i;?>" style="display: block;"><a href="#" onclick="confirm_ok('<?php echo $i;?>','<?php echo $row['bre_ix']; ?>');">삭제</a> | <a href="#" onclick="confirm_back('<?php echo $i;?>','<?php echo $row['bre_ix']; ?>');">반려</a></span><?php } ?></td>
						</tr>
						<?php

						$count++;
							
						}

						if ($count == 0)
							echo '<tr><td colspan="'.$colspan.'"><center>자료가 없습니다.</center></td></tr>';
						?>
					</table>

					<div style="margin-top:10px;float:left;">
						<div class="dataTables_info" id="example2_info">현재페이지 : <?php echo $page ?> / 전체페이지 : <?php echo $total_page ?></div>
					</div>
					<div class="row" style="clear:both;">
						<div class="col-sm-12">
							<center>
							<div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
								<ul class="pagination">
									<li class="paginate_button previous <?php if($page == 1) {?>disabled<?php } ?>">
										<a href="<?php echo G5_URL.'/gnupushapp_admin/report_result.php?page=1'; ?>">◀◀</a>
									</li>
									<li class="paginate_button previous <?php if($page == 1) {?>disabled<?php } ?>">
										<a href="<?php echo G5_URL.'/gnupushapp_admin/report_result.php?page='.($page-1); ?>">Previous</a>
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
										<a href="<?php echo G5_URL.'/gnupushapp_admin/report_result.php?page='.$now_number; ?>" ><?php echo $now_number ?></a>
									</li>
									<?php } ?>
									<li class="paginate_button next <?php if($page == $total_page) {?>disabled<?php } ?>" id="example2_next">
										<a href="<?php echo G5_URL.'/gnupushapp_admin/report_result.php?page='.($page+1); ?>" >Next</a>
									</li>
									<li class="paginate_button next <?php if($page == $total_page) {?>disabled<?php } ?>">
										<a href="<?php echo G5_URL.'/gnupushapp_admin/report_result.php?page='.$total_page; ?>" >▶▶</a>
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
       
      </section>
      <!-- /.content -->


    </div>
    <!-- /.content-wrapper -->

<script type="text/javascript">


function confirm_ok(a,b){
	var url = "<?php echo G5_URL;?>/gnupushapp_admin/report_result_proc.php";
	
	$.post( url, { bre_ix: b, bre_status : "D"} )
		.done(function( data ) {
			if(data == "ok"){
				$('#confirm_'+a).hide();
				$('#status_'+a).text('삭제완료');
			}else{
				alert("오류가 발생되었습니다.");
			}
		});

}

function confirm_back(a,b){
	var url = "<?php echo G5_URL;?>/gnupushapp_admin/report_result_proc.php";
	
	$.post( url, { bre_ix: b, bre_status : "B"} )
		.done(function( data ) {
			if(data == "ok"){
				$('#confirm_'+a).hide();
				$('#status_'+a).text('반려');
			}else{
				alert("오류가 발생되었습니다.");
			}
		});

}


jQuery(function($){
	$("#clearance").click(function() {
		$("#pushform").submit();
	});
});

</script>

<?php 
include_once('footer.php');
?>
