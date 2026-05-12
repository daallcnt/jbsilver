<?php 
include_once('header.php');

$pass_example = get_random_string_gnu('96');

?>

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
	  <form role="form" name="frm" id="frm" action="config_update.php" method="post" enctype="multipart/form-data">
	  <input type="hidden" name="config_sort" value="etc" />
		<input type="hidden" name="action_sort" value="config" id="action_sort"/>
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          팝업/애드몹
          <small>환경설정</small>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="index.php">
              <i class="fa fa-dashboard"></i> Home</a>
          </li>
          <li class="active">환경설정</li>
		  <li class="active">팝업/채팅/애드몹</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
			<div class="col-lg-12">
				<div class="box box-danger box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">팝업</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">
				

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <input type="hidden" name="eventimage0_d" id="eventimage0_d" value="none" />
					<input type="hidden" name="eventimage0_filename" value="<?php echo $gnu_config['eventimage0_filename'] ?>" />
				    <label><b>시작팝업 이미지파일</b></label>
					<div>
						<input type="file" id="eventimage0" name="eventimage0" >
						<?php if($gnu_config["eventimage0_filename"]) { 
							$filename_array = explode("/",$gnu_config["eventimage0_filename"]);
							$filename_start = $filename_array[count($filename_array)-1];
							
							?>
						<img src="<?php echo $gnu_config["eventimage0_filename"]; ?>" width=80 height=125 style="margin-top:10px;"><br>
						<button type="button" style="margin-top:7px;" id="DelWeb000" class="btn btn-primary mb-2"><?php echo $filename_start ?>파일삭제</button>
						<?php } ?>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label for="eventlink0"><b>시작팝업 링크</b></label>
					<input type="text" class="form-control" id="eventlink0"  name="eventlink0" value="<?php echo $gnu_config["eventlink0"]; ?>">
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">이미지 클릭시 이동할 페이지의 Full Url을 적어주세요.</p>
				</div>


				<div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label for="eventlink0_term"><b>시작팝업 창안보기 기간(일)</b></label>
					<div class="row">
						<div class="col-sm-3">
							<div class="input-group">
								<input type="text" class="form-control" id="eventlink0_term"  name="eventlink0_term" value="<?php echo $gnu_config["eventlink0_term"]; ?>">
								<span class="input-group-addon">일</span>
						    </div>
						</div>
						<div class="col-sm-9"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">[?일간 보지 않기]에 들어갈 ?값을 정해주세요.</p>
				</div>

				

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label>종료팝업(안드로이드만 해당)</label>
					<div>
						<table class="table table-striped table-bordered table-hover">
							<tr>
								<td>순서</td>
								<td>파일올리기</td>
								<td>이미지파일</td>
								<td>Link</td>
							</tr>
							<tr>
								<td>종료팝업1</td>
								<td>
									<input type="hidden" name="eventimage1_d" id="eventimage1_d" value="none" />
									<input type="hidden" name="eventimage1_filename" value="<?php echo $gnu_config['eventimage1_filename'] ?>" />
									<input type="file" id="eventimage1" name="eventimage1" >
								</td>
								<td><?php if($gnu_config["eventimage1_filename"]) { 
									$filename_array = explode("/",$gnu_config["eventimage1_filename"]);
									$filename_end1 = $filename_array[count($filename_array)-1];

									?>
									<img src="<?php echo $gnu_config['eventimage1_filename'] ?>" width="80" height="125">
									<button type="button" style="margin-top:7px;" id="DelWeb111" class="btn btn-primary mb-2"><?php echo $filename_end1 ?>파일삭제</button>
									<?php }else{ ?>
									없음
									<?php } ?>
								</td>
								<td><input class="form-control" name="eventlink1" value="<?php echo $gnu_config["eventlink1"]; ?>"></td>
							</tr>
							<tr>
								<td>종료팝업2</td>
								<td>
									<input type="hidden" name="eventimage2_d" id="eventimage2_d" value="none" />
									<input type="hidden" name="eventimage2_filename" value="<?php echo $gnu_config['eventimage2_filename'] ?>" />
									<input type="file" id="eventimage2" name="eventimage2" >
								</td>
								<td><?php if($gnu_config["eventimage2_filename"]) { 
									$filename_array = explode("/",$gnu_config["eventimage2_filename"]);
									$filename_end2 = $filename_array[count($filename_array)-1];

									?>
									<img src="<?php echo $gnu_config['eventimage2_filename'] ?>" width="80" height="125">
									<button type="button" style="margin-top:7px;" id="DelWeb222" class="btn btn-primary mb-2"><?php echo $filename_end2 ?>파일삭제</button>
									<?php }else{ ?>
									없음
									<?php } ?>
								</td>
								<td><input class="form-control" name="eventlink2" value="<?php echo $gnu_config["eventlink2"]; ?>"></td>
							</tr>
							<tr>
								<td>종료팝업3</td>
								<td>
									<input type="hidden" name="eventimage3_d" id="eventimage3_d" value="none" />
									<input type="hidden" name="eventimage3_filename" value="<?php echo $gnu_config['eventimage3_filename'] ?>" />
									<input type="file" id="eventimage3" name="eventimage3" >
								</td>
								<td><?php if($gnu_config["eventimage3_filename"]) { 
									$filename_array = explode("/",$gnu_config["eventimage3_filename"]);
									$filename_end3 = $filename_array[count($filename_array)-1];

									?>
									<img src="<?php echo $gnu_config['eventimage3_filename'] ?>" width="80" height="125">
									<button type="button" style="margin-top:7px;" id="DelWeb333" class="btn btn-primary mb-2"><?php echo $filename_end3 ?>파일삭제</button>
									<?php }else{ ?>
									없음
									<?php } ?>
								</td>
								<td><input class="form-control" name="eventlink3" value="<?php echo $gnu_config["eventlink3"]; ?>"></td>
							</tr>
							<tr>
								<td>종료팝업4</td>
								<td>
									<input type="hidden" name="eventimage4_d" id="eventimage4_d" value="none" />
									<input type="hidden" name="eventimage4_filename" value="<?php echo $gnu_config['eventimage4_filename'] ?>" />
									<input type="file" id="eventimage4" name="eventimage4" >
								</td>
								<td><?php if($gnu_config["eventimage4_filename"]) { 
									$filename_array = explode("/",$gnu_config["eventimage4_filename"]);
									$filename_end4 = $filename_array[count($filename_array)-1];

									?>
									<img src="<?php echo $gnu_config['eventimage4_filename'] ?>" width="80" height="125">
									<button type="button" style="margin-top:7px;" id="DelWeb444" class="btn btn-primary mb-2"><?php echo $filename_end4 ?>파일삭제</button>
									<?php }else{ ?>
									없음
									<?php } ?>
								</td>
								<td><input class="form-control" name="eventlink4" value="<?php echo $gnu_config["eventlink4"]; ?>"></td>
							</tr>
						</table>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">종료시 팝업은 올려진 이미지 중에서 랜덤하게 나옵니다. 네이티브 애드몹을 사용하실 경우 네이티브 애드몹이 나옵니다.</p>
				</div>

				  <div class="row" style="margin-bottom:10px;">
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
				  <h3 class="box-title">애드몹</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">



				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>하단 배너 애드몹</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="banner_admobY" name="banner_admob" value="Y" <?php if($gnu_config["banner_admob"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="banner_admobY">ON</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="banner_admobN" name="banner_admob" value="N" <?php if($gnu_config["banner_admob"] == "N") { ?>checked="checked" <?php } ?>/>
							<label for="banner_admobN">OFF</label>
						</div>
					</div>
				  </div>

				<input type="hidden" name="notadmob_url" value=""/>


				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>전면 애드몹</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="interstitial_admobY" name="interstitial_admob" value="Y" <?php if($gnu_config["interstitial_admob"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="interstitial_admobY">ON</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="interstitial_admobN" name="interstitial_admob" value="N" <?php if($gnu_config["interstitial_admob"] == "N") { ?>checked="checked" <?php } ?>/>
							<label for="interstitial_admobN">OFF</label>
						</div>
					</div>
				  </div>

				  <input type="hidden" name="interstitial_admob_r" value="N"/>
				  <input type="hidden" name="interstitial_admob_num" value=""/>
				  <input type="hidden" name="native_admob" value="N"/>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>리워드 애드몹</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="rewardadY" name="rewardad" value="Y" <?php if($gnu_config["rewardad"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="rewardadY">ON</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="rewardadN" name="rewardad" value="N" <?php if($gnu_config["rewardad"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="rewardadN">OFF</label>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label>리워드 셋팅값(암호용)</label>
					<div class="input-group" style="margin-bottom:4px;">
						<span class="input-group-addon">리워드수량</span>
						<input type="text" class="form-control" name="rewardad_quantity" value="<?php echo $gnu_config['rewardad_quantity'] ?>">
					</div>
					<div class="input-group" style="margin-bottom:4px;">
						<span class="input-group-addon">리워드상품</span>
						<input type="text" class="form-control" name="rewardad_content" value="<?php echo $gnu_config['rewardad_content'] ?>">
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">리워드 애드몹 광고 단위생성하실 때 입력할 [리워드 수량(숫자)],[리워드 상품(영문+숫자)]값입니다. 이 값은 리워드 광고 시청 여부를 확인하는 암호값으로 사용되니 복잡한 난수로 해서 입력해주시면 됩니다. 리워드 수량 난수 예 : <?php echo get_random_num_gnu(9); ?> / 리워드 상품 난수 예 : <?php echo get_random_string_gnu(75); ?></p>
				</div>


				<div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>리워드 애드몹 보상 종류</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="reward_typepoint" name="reward_type" value="point" <?php if($gnu_config["reward_type"] == "point") { ?>checked="checked" <?php } ?> />
							<label for="reward_typepoint">point</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="reward_typeetc" name="reward_type" value="etc" <?php if($gnu_config["reward_type"] == "etc") { ?>checked="checked" <?php } ?> />
							<label for="reward_typeetc">기타</label>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:20px;padding-bottom:10px;border-bottom: 1px solid #c6c6c6;">
					<label for="reward_amount">보상 point 수량</label>
					<div class="row">
						<div class="col-sm-3">
							<input type="text" class="form-control" id="reward_amount"  name="reward_amount" value="<?php echo $gnu_config['reward_amount'] ?>">
						</div>
						<div class="col-sm-9"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">보상 종류로 포인트를 선택하셨을 경우 보상할 포인트 수량을 입력해주세요.</p>
				</div>

				<div class="form-group" style="margin-bottom:20px;padding-bottom:10px;border-bottom: 1px solid #c6c6c6;">
					<label for="reward_limit">리워드 애드몹 보상 1일 횟수 제한</label>
					<div class="row">
						<div class="col-sm-3">
							<input type="text" class="form-control" id="reward_limit"  name="reward_limit" value="<?php echo $gnu_config['reward_limit'] ?>">
						</div>
						<div class="col-sm-9"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">0을 입력하시면 제한하지 않습니다.</p>
				</div>

				<div class="form-group" style="margin-bottom:20px;padding-bottom:10px;border-bottom: 1px solid #c6c6c6;">
                    <label for="iap_key">인앱결제 비밀번호</label>
                    <input type="text" class="form-control" id="iap_key"  name="iap_key" value="<?php echo $gnu_config['iap_key'] ?>">
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">비밀번호 예) <?php echo $pass_example; ?></p>
                </div>

				<div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>리워드애드몹 결과목록 사용여부</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="reward_adminY" name="reward_admin" value="Y" <?php if($gnu_config["reward_admin"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="reward_adminY">사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="reward_adminN" name="reward_admin" value="N" <?php if($gnu_config["reward_admin"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="reward_adminN">사용안함</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">리워드 결과목록을 관리자 페이지메뉴에 표시합니다. 리워드 사용하시는 경우에는 사용을 선택해주세요.</p>
				  </div>


				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>인앱결제 결과목록 사용여부</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="inapp_adminY" name="inapp_admin" value="Y" <?php if($gnu_config["inapp_admin"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="inapp_adminY">사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="inapp_adminN" name="inapp_admin" value="N" <?php if($gnu_config["inapp_admin"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="inapp_adminN">사용안함</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">인앱결제 결과목록을 관리자 페이지메뉴에 표시합니다. 인앱결제 사용하시는 경우에는 사용을 선택해주세요.</p>
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

jQuery(function($){
	$("#DelWeb000").click(function() {
		$('#eventimage0_d').val("DELETE");
		$( "#frm" ).submit();
	});

	$("#DelWeb111").click(function() {
		$('#eventimage1_d').val("DELETE");
		$( "#frm" ).submit();
	});

	$("#DelWeb222").click(function() {
		$('#eventimage2_d').val("DELETE");
		$( "#frm" ).submit();
	});

	$("#DelWeb333").click(function() {
		$('#eventimage3_d').val("DELETE");
		$( "#frm" ).submit();
	});

	$("#DelWeb444").click(function() {
		$('#eventimage4_d').val("DELETE");
		$( "#frm" ).submit();
	});
	
});

function usechat(){
	
}


function admob_num1()
{
	var obj2 = document.getElementById('interstitial_admob_num');
	obj2.value="";
	obj2.disabled = true;
}

function admob_num2()
{
	var obj2 = document.getElementById('interstitial_admob_num');
	obj2.disabled = false;
	obj2.value="30";
}
</script>

<?php 
include_once('footer.php');
?>
