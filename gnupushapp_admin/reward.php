<?php 
include_once('header.php');

$page = $_GET['page'];

$page_limit = 0;

if(!$page) $page=1;

$page_limit = $page_limit + ($page-1)*30;

$sql = " SELECT * FROM  g5_gnupushapp_reward ORDER BY grr_regdate DESC LIMIT {$page_limit} , 30";
$result = sql_query($sql);

$colspan = 4;

$sql_1 = " SELECT count(*) as 'cnt' FROM g5_gnupushapp_reward";
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
				  <h3 class="box-title">리워드</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<table class="table table-striped table-bordered table-hover" >
						<tr>
							<td><center><b>보상종류</b></center></td>
							<td><center><b>회원id</b></center></td>
							<td><center><b>보상</b></center></td>
							<td><center><b>날짜</b></center></td>
						</tr>
						<?php
						$count = 0;
						for ($i=0; $row=sql_fetch_array($result); $i++)
						{
							
						?>
						<tr">
							<td><?php echo $row['grr_type'] ?></td>
							<td><?php echo $row['grr_mb_id'] ?></td>
							<td><?php echo number_format($row['grr_amount']); ?></td>
							<td><?php echo $row['grr_regdate'] ?></td>							
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
										<a href="<?php echo G5_URL.'/gnupushapp_admin/reward.php?page=1'; ?>">◀◀</a>
									</li>
									<li class="paginate_button previous <?php if($page == 1) {?>disabled<?php } ?>">
										<a href="<?php echo G5_URL.'/gnupushapp_admin/reward.php?page='.($page-1); ?>">Previous</a>
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
										<a href="<?php echo G5_URL.'/gnupushapp_admin/reward.php?page='.$now_number; ?>" ><?php echo $now_number ?></a>
									</li>
									<?php } ?>
									<li class="paginate_button next <?php if($page == $total_page) {?>disabled<?php } ?>" id="example2_next">
										<a href="<?php echo G5_URL.'/gnupushapp_admin/reward.php?page='.($page+1); ?>" >Next</a>
									</li>
									<li class="paginate_button next <?php if($page == $total_page) {?>disabled<?php } ?>">
										<a href="<?php echo G5_URL.'/gnupushapp_admin/reward.php?page='.$total_page; ?>" >▶▶</a>
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

jQuery(function($){
	$("#clearance").click(function() {
		$("#pushform").submit();
	});
});

</script>

<?php 
include_once('footer.php');
?>
