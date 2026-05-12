<?php 
include_once('header.php');

$page = $_GET['page'];

$page_limit = 0;

if(!$page) $page=1;

$page_limit = $page_limit + ($page-1)*30;

$sql = " SELECT * FROM  g5_gnupushapp_push ORDER BY  gp_push_date DESC LIMIT {$page_limit} , 30";
$result = sql_query($sql);

$colspan = 4;

$sql_1 = " SELECT count(*) as 'cnt' FROM g5_gnupushapp_push";
$row_count1 = sql_fetch($sql_1);
$total_page  = ceil($row_count1['cnt'] / 30);

?>

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">	  
      
      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->

		<form action="push_result_proc.php" id="pushform" method="post">

		<div class="row">
          <div class="col-lg-12">
            <div class="box">
				<div class="box-header with-border">
				  <h3 class="box-title">푸시결과값 목록</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">

					<div style="margin-bottom:10px;float:right;">
						<button type="button" class="btn btn-default" id="clearance">전체목록 지우기</button>
					</div>
					<table class="table table-striped table-bordered table-hover" >
						<tr>
							<td><center><b>푸시알림종류</b></center></td>
							<td><center><b>알림내용</b></center></td>
							<td><center><b>알림날짜</b></center></td>
							<td><center><b>알림결과</b></center></td>
						</tr>
						<?php
						$count = 0;
						for ($i=0; $row=sql_fetch_array($result); $i++)
						{
							
						?>
						<tr">
							<td><?php echo $row['gp_type'] ?></td>
							<td><a href="<?php echo $row['gp_target_url'] ?>" target=_blank>[<?php echo $row['gp_target_browser'] ?>] <?php echo stripslashes($row['gp_target_title']); ?></a></td>
							<td><?php echo $row['gp_push_date'] ?></td>

							<?php
							if($row['gp_issend'] == 'Y'){
							?>
							<td><?php echo $row['gp_text'] ?></td>
							<?php
							}else{
							?>
							<td>현재 발송중에 있습니다. 오랜시간 이 메시지가 보인다면 푸시발송에 실패한 것입니다.</td>
							<?php
							}
							?>
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
										<a href="<?php echo G5_URL.'/gnupushapp_admin/push_result.php?page=1'; ?>">◀◀</a>
									</li>
									<li class="paginate_button previous <?php if($page == 1) {?>disabled<?php } ?>">
										<a href="<?php echo G5_URL.'/gnupushapp_admin/push_result.php?page='.($page-1); ?>">Previous</a>
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
										<a href="<?php echo G5_URL.'/gnupushapp_admin/push_result.php?page='.$now_number; ?>" ><?php echo $now_number ?></a>
									</li>
									<?php } ?>
									<li class="paginate_button next <?php if($page == $total_page) {?>disabled<?php } ?>" id="example2_next">
										<a href="<?php echo G5_URL.'/gnupushapp_admin/push_result.php?page='.($page+1); ?>" >Next</a>
									</li>
									<li class="paginate_button next <?php if($page == $total_page) {?>disabled<?php } ?>">
										<a href="<?php echo G5_URL.'/gnupushapp_admin/push_result.php?page='.$total_page; ?>" >▶▶</a>
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
	$("#clearance").click(function() {
		$("#pushform").submit();
	});
});

</script>

<?php 
include_once('footer.php');
?>
