<?php 
include_once('header.php');

$sql = " SELECT * FROM {$g5['group_table']}";
$result_group_list = sql_query($sql);



?>



    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
	  <form role="form" name="frm" id="pushform" action="push_group_proc.php" method="post" enctype="multipart/form-data">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          그룹별푸시알림
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="index.php">
              <i class="fa fa-dashboard"></i> Home</a>
          </li>
          <li class="active">그룹별푸시알림</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-6">
            <div class="box box-danger box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">그룹별푸시알림</h3>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					
						
								
				  <div class="form-group" style="margin-bottom:25px;">
				    <label><b>그룹선택</b></label>
					<div>
						<select name="level" class="form-control">
						 <option value="1" selected="selected">1</option>
						 <option value="2">2</option>
						 <option value="3">3</option>
						 <option value="4">4</option>
						 <option value="5">5</option>
						 <option value="6">6</option>
						 <option value="7">7</option>
						 <option value="8">8</option>
						 <option value="9">9</option>
						 <option value="10">10</option>
						 <option value="all">전체</option>
						 <option value="oldversion">최신버전이 아닌 기기</option>
<?php
for ($i=0; $row=sql_fetch_array($result_group_list); $i++)
{
?>
						 <option value="GNU_group_<?php echo $row['gr_id'];?>"><?php echo $row['gr_subject'];?>(<?php echo $row['gr_id'];?>)</option>

<?php } ?>
						</select>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:20px;">
                    <label for="push_title">제목</label>
                    <input type="text" class="form-control" id="push_title"  name="push_title">
                  </div>

				  <div class="form-group" style="margin-bottom:20px;">
                    <label for="push_content">내용</label>
                    <input type="text" class="form-control" id="push_content"  name="push_content">
                  </div>

				  <div class="form-group" style="margin-bottom:20px;">
                    <label for="push_link">링크</label>
                    <input type="text" class="form-control" id="push_link"  name="push_link">
                  </div>

				<div class="form-group" style="margin-bottom:20px;">
					<label for="push_link">이미지파일(500px X 250px)</label>
					<input type="file" name="push_img" />
				</div>
				<div class="form-group" style="padding-top:10px;margin-bottom:30px;">
					<div class="checkbox icheck-primary">
						<input type="checkbox" id="use_marketing" name="use_marketing" value="Y"/>
						<label for="use_marketing">마케팅알림</label>
					</div>
				</div>

				<div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>푸시스타일(안드로이드만 해당)</b></label>
					<div>
						<div class="radio-inline icheck-primary">
							<input type="radio" id="effectfalse" name="effect" value="false"/>
							<label for="effectfalse">기본</label>
						</div>
						<div class="radio-inline icheck-primary">
							<input type="radio" id="effectheadsup" name="effect" value="headsup" checked="checked"/>
							<label for="effectheadsup">헤드업</label>
						</div>
						<div class="radio-inline icheck-primary">
							<input type="radio" id="effectpopup" name="effect" value="popup" />
							<label for="effectpopup">팝업</label>
						</div>
					</div>
				  </div>

				  <div class="row" style="margin-bottom:10px;">
					<div class="col-lg-12">
						<center><button type="button" class="btn btn-default" id="gopush">푸시발송</button></center>
					</div>
				  </div>

				</div>
				<!-- /.box-body -->
			  </div>
          </div>
		  <div class="col-lg-6">
		  </div>
        </div>
        <!-- /.row -->
        <!-- Main row -->
        
		
      </section>
      <!-- /.content -->

	  </form>


    </div>
    <!-- /.content-wrapper -->

<script type="text/javascript">

jQuery(function($){

	$("#gopush").click(function() {
		if($('#push_title').val().length === 0 || jQuery('#push_content').val().length === 0){
			alert('푸시알림할 제목과 내용을 적어주세요.');
		}else{
			$("#pushform").submit();
		}
	});
});
</script>

<?php 
include_once('footer.php');
?>

    