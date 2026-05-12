<?php 
include_once('header.php');
?>



    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
	  <form role="form" name="frm" id="frm" action="config_update.php" method="post" enctype="multipart/form-data">
	  <input type="hidden" name="config_sort" value="basic" />
	  <input type="hidden" name="list_show" value="<?php echo $gnu_config["list_show"]; ?>" />
	  <input type="hidden" id="appcache" name="appcache" value="<?php echo $gnu_config["appcache"]; ?>" />
	  <input type="hidden" id="appcache2" name="appcache2" value="<?php echo $appcache_new; ?>" />
	  <input type="hidden" name="must_login" value="<?php echo $gnu_config["must_login"]; ?>" />
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          기본설정
          <small>환경설정</small>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="index.php">
              <i class="fa fa-dashboard"></i> Home</a>
          </li>
          <li class="active">환경설정</li>
		  <li class="active">기본설정</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-12">
            <div class="box box-danger box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">필수기본설정</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">
				<?php 

$query = "show columns from g5_gnupushapp_gcmregid like 'gpr_setting_newpost' ";
$res = sql_fetch($query);
if (empty($res)) { ?>

				  <div class="well">
					<h4>설정 업데이트</h4>
					<p>db구조를 업데이트 합니다. 등록기기 목록 수가 많을 경우 업데이트 시간이 오래 걸릴 수 있습니다. 처리양이 많아서 오류가 발생할 경우 아래 버튼을 몇번 더 클릭해주시고, 문제가 계속될 시에는 개발자에게 문의해주세요.</p>
					<a class="btn btn-danger btn-lg btn-block" href="db_update.php">설정업데이트</a>
				  </div>
<?php } ?>

					
						
								
				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>그누보드 빌더 종류</b></label>
					<div>
						<div class="radio-inline icheck-primary">
							<input type="radio" id="build_sortG" name="build_sort" value="G" <?php if(!$gnu_config["build_sort"] || $gnu_config["build_sort"] == "G") { ?>checked="checked" <?php } ?> />
							<label for="build_sortG">그누보드</label>
						</div>
						<div class="radio-inline icheck-primary">
							<input type="radio" id="build_sortA" name="build_sort" value="A" <?php if($gnu_config["build_sort"] == "A") { ?>checked="checked" <?php } ?> />
							<label for="build_sortA">아미나</label>
						</div>
						<div class="radio-inline icheck-primary">
							<input type="radio" id="build_sortE" name="build_sort" value="E" <?php if($gnu_config["build_sort"] == "E") { ?>checked="checked" <?php } ?> />
							<label for="build_sortE">이윰</label>
						</div>
						<div class="radio-inline icheck-primary">
							<input type="radio" id="build_sortB" name="build_sort" value="B" <?php if($gnu_config["build_sort"] == "B") { ?>checked="checked" <?php } ?> />
							<label for="build_sortB">배추</label>
						</div>
					</div>
				  </div>

				  

				  <div class="form-group" style="margin-bottom:20px;padding-bottom:10px;border-bottom: 1px solid #c6c6c6;">
                    <label for="masterpassword">마스터패스워드</label>
					<div class="row">
						<div class="col-sm-3">
							<input type="text" class="form-control" id="masterpassword" name="masterpassword" value="<?php echo $gnu_config["masterpassword"]; ?>">
						</div>
						<div class="col-auto"><button type="button" id="changemasterpass" class="btn btn-primary mb-2">마스터패스워드 변경</button><input type="hidden" id="masterpassword2" name="masterpassword2" value="<?php echo $gnu_config["masterpassword2"]; ?>" /></div>
						<div class="col-auto"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">보안을 위한 장치입니다.</p>
				  </div>

				  <div class="form-group" style="margin-bottom:20px;padding-bottom:10px;border-bottom: 1px solid #c6c6c6;">
                    <label for="service_account_file">서비스계정 파일명</label>
                    <input type="text" class="form-control" id="service_account_file"  name="service_account_file" value="<?php echo $gnu_config['service_account_file'] ?>">
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">https://github.com/googleapis/google-api-php-client/releases?page=1 여기 링크로 들어가셔서 자신의 php 버전에 맞는 라이브러리 버전을 다운로드 후 xe의 libs폴더 안에 업로드해주시고(폴더명:google-api-php-client), firebase FCM(또는 google GCM) 콘솔의 프로젝트설정>서비스계정에서 생성한 비공개 키 파일(json)의 파일명을 입력해주세요(확장자포함). 해당 파일 역시 반드시 libs폴더 안에 업로드해주셔야 합니다.</p>
                  </div>

				  <div class="form-group" style="margin-bottom:20px;padding-bottom:10px;border-bottom: 1px solid #c6c6c6;">
                    <label for="project_name">프로젝트 이름</label>
                    <input type="text" class="form-control" id="project_name"  name="project_name" value="<?php echo $gnu_config['project_name'] ?>">
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">firebase FCM 콘솔의 프로젝트설정 에서 프로젝트 이름 값을 입력해주세요.</p>
                  </div>

				  <div class="form-group" style="margin-bottom:20px;">
                    <label for="privacy">개인정보처리방침 링크</label>
                    <input type="text" class="form-control" id="privacy" name="privacy" value="<?php echo $gnu_config['privacy'] ?>">
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">개인정보처리방침 링크(Full Url)를 입력해주세요.</p>
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
        <!-- /.row -->
        <!-- Main row -->
        <!-- /.row (main row) -->
		<div class="row">
        <!-- Left col -->
        <section class="col-lg-6 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
          <div class="box box-warning box-solid">
				<div class="box-header with-border">
			  <h3 class="box-title">안드로이드</h3>

			  <div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
				</button>
			  </div>
			  <!-- /.box-tools -->
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<div class="form-group" style="margin-bottom:20px;padding-bottom:10px;border-bottom: 1px solid #c6c6c6;">
					<label for="package">패키지명</label>
					<div class="row">
						<div class="col-sm-6">
							<input type="text" class="form-control" id="package"  name="package" value="<?php echo $gnu_config['package'] ?>">
						</div>
						<div class="col-sm-6"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">제작한 앱의 패키지명을 적어주세요. 예) com.gnupushapp.gnupushapp</p>
				</div>

				<div class="form-group" style="margin-bottom:20px;padding-bottom:10px;border-bottom: 1px solid #c6c6c6;">
					<label for="webview_version">앱 버전</label>
					<div class="row">
						<div class="col-sm-3">
							<input type="text" class="form-control" id="webview_version"  name="webview_version" value="<?php echo $gnu_config['webview_version'] ?>">
						</div>
						<div class="col-sm-9"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">앱의 현재 버전을 적어주세요. 예) 1(1.0)</p>
				</div>

			  <div class="form-group" style="margin-bottom:20px;">
				<label><b>업그레이드 알림 표시</b></label>
				<div>
					<div class="radio icheck-primary">
						<input type="radio" id="notevY" name="notev" value="Y" <?php if($gnu_config["notev"] == "Y") { ?>checked="checked" <?php } ?> />
						<label for="notevY">알림창 + 업그레이드 필수</label>
					</div>
					<div class="radio icheck-primary">
						<input type="radio" id="notevJ" name="notev" value="J" <?php if($gnu_config["notev"] == "J") { ?>checked="checked" <?php } ?> />
						<label for="notevJ">알림창 + 업그레이드 선택</label>
					</div>
					<div class="radio icheck-primary">
						<input type="radio" id="notevN" name="notev" value="N" <?php if($gnu_config["notev"] == "N") { ?>checked="checked" <?php } ?> />
						<label for="notevN">알림창 없음</label>
					</div>
				</div>
			  </div>

			  <div class="form-group" style="margin-bottom:20px;">
				<label><b>앱심사중</b></label>
				<div>
					<div class="radio icheck-primary">
						<input type="radio" id="under_reviewY" name="under_review" value="Y" <?php if($gnu_config["under_review"] == "Y") { ?>checked="checked" <?php } ?> />
						<label for="under_reviewY">심사중</label>
					</div>
					<div class="radio icheck-primary">
						<input type="radio" id="under_reviewN" name="under_review" value="N" <?php if($gnu_config["under_review"] == "N") { ?>checked="checked" <?php } ?> />
						<label for="under_reviewN">심사완료</label>
					</div>
				</div>
				<p class="help-block" style="color:#c6c6c6;font-size:9pt;">심사제출시 우회 사이트 접속기능을 사용할건지 여부를 결정합니다.</p>
			  </div>

				<div class="form-group" style="margin-bottom:20px;padding-bottom:10px;border-bottom: 1px solid #c6c6c6;">
					<label for="bypass_url">우회사이트 도메인 주소</label>
					<div class="row">
						<div class="col-sm-3">
							<input type="text" class="form-control" id="bypass_url"  name="bypass_url" value="<?php echo $gnu_config['bypass_url'] ?>">
						</div>
						<div class="col-sm-9"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">심사제출을 위해 앱접속 전용으로 만든 별도 도메인 주소를 http, www 포함한 Full url 도메인 주소를 입력해주세요. 그리고 끝에는 /를 붙이지 말아주세요. 예)https://www.naver.com </p>
				</div>


			  


			  <div class="row" style="margin-bottom:10px;">
					<div class="col-lg-12">
						<center><input type="submit" value="수정" class="btn btn-danger"></center>
					</div>
				  </div>

			  
			</div>
			<!-- /.box-body -->
		  </div>
          <!-- /.nav-tabs-custom -->
        </section>

		<section class="col-lg-6 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
          <div class="box box-primary box-solid">
				<div class="box-header with-border">
			  <h3 class="box-title">IOS</h3>

			  <div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
				</button>
			  </div>
			  <!-- /.box-tools -->
			</div>
			<!-- /.box-header -->
			<div class="box-body">
			  <div class="form-group" style="margin-bottom:20px;padding-bottom:10px;border-bottom: 1px solid #c6c6c6;">
				<label for="linki">앱ID</label>
				<input type="text" class="form-control" id="linki" name="linki" value="<?php echo $gnu_config['linki'] ?>">
				<p class="help-block" style="color:#c6c6c6;font-size:9pt;">앱ID는 apple developer페이지에서 Account -> App Store Connect -> '나의앱' -> [앱정보] 하단의 [일반정보]안에 있는 Apple ID값을 입력해주시면 됩니다. 예) 1234567890</p>
			  </div>

			  <div class="form-group" style="margin-bottom:20px;padding-bottom:10px;border-bottom: 1px solid #c6c6c6;">
					<label for="webview_versioni">앱 버전</label>
					<div class="row">
						<div class="col-sm-3">
							<input type="text" class="form-control" id="webview_versioni"  name="webview_versioni" value="<?php echo $gnu_config['webview_versioni'] ?>">
						</div>
						<div class="col-sm-9"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">앱의 현재 버전을 적어주세요. 예) 1(1.0)</p>
				</div>

			  <div class="form-group" style="margin-bottom:20px;">
				<label><b>업그레이드 알림 표시</b></label>
				<div>
					<div class="radio icheck-primary">
						<input type="radio" id="noteviJ" name="notevi" value="J" <?php if($gnu_config["notevi"] == "J") { ?>checked="checked" <?php } ?> />
						<label for="noteviJ">알림창 + 업그레이드 선택</label>
					</div>
					<div class="radio icheck-primary">
						<input type="radio" id="noteviN" name="notevi" value="N" <?php if($gnu_config["notevi"] == "N") { ?>checked="checked" <?php } ?> />
						<label for="noteviN">알림창 없음</label>
					</div>
				</div>
			  </div>


			  <div class="form-group" style="margin-bottom:20px;">
				<label><b>앱심사중</b></label>
				<div>
					<div class="radio icheck-primary">
						<input type="radio" id="under_reviewiY" name="under_reviewi" value="Y" <?php if($gnu_config["under_reviewi"] == "Y") { ?>checked="checked" <?php } ?> />
						<label for="under_reviewiY">심사중</label>
					</div>
					<div class="radio icheck-primary">
						<input type="radio" id="under_reviewiN" name="under_reviewi" value="N" <?php if($gnu_config["under_reviewi"] == "N") { ?>checked="checked" <?php } ?> />
						<label for="under_reviewiN">심사완료</label>
					</div>
				</div>
				<p class="help-block" style="color:#c6c6c6;font-size:9pt;">심사제출시 우회 사이트 접속기능을 사용할건지 여부를 결정합니다.</p>
			  </div>

				<div class="form-group" style="margin-bottom:20px;padding-bottom:10px;border-bottom: 1px solid #c6c6c6;">
					<label for="bypass_url">우회사이트 도메인 주소</label>
					<div class="row">
						<div class="col-sm-3">
							<input type="text" class="form-control" id="bypass_urli"  name="bypass_urli" value="<?php echo $gnu_config['bypass_urli'] ?>">
						</div>
						<div class="col-sm-9"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">심사제출을 위해 앱접속 전용으로 만든 별도 도메인 주소를 http, www 포함한 Full url 도메인 주소를 입력해주세요. 그리고 끝에는 /를 붙이지 말아주세요. 예)https://www.naver.com </p>
				</div>
				

			  <div class="row" style="margin-bottom:10px;">
					<div class="col-lg-12">
						<center><input type="submit" value="수정" class="btn btn-danger"></center>
					</div>
				  </div>
			</div>
			<!-- /.box-body -->
		  </div>
          <!-- /.nav-tabs-custom -->
        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->
		
      </section>
      <!-- /.content -->

	  </form>


    </div>
    <!-- /.content-wrapper -->
<script type="text/javascript">
jQuery(function($){
	$("#changemasterpass").click(function() {
		var newpass = $("#masterpassword2").val();
		$("#masterpassword").val(newpass);
		$( "#frm" ).submit();
	});
});
</script>
<?php 
include_once('footer.php');
?>

    