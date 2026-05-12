<?php 
include_once('header.php');
?>

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
	  <form role="form" name="frm" id="frm" action="config_update.php" method="post" enctype="multipart/form-data">
	  <input type="hidden" name="config_sort" value="app_basic" />
	  <input type="hidden" id="appcache2" name="appcache2" value="<?php echo $appcache_new; ?>" />
	  <input type="hidden" name="home_page"  value="Y" />
	  <input type="hidden" name="menubutton" value="N" />
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          앱기본설정
          <small>환경설정</small>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="index.php">
              <i class="fa fa-dashboard"></i> Home</a>
          </li>
          <li class="active">환경설정</li>
		  <li class="active">앱기본설정</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
			<section class="col-lg-6 connectedSortable">
				<div class="box box-danger box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">앱로그인</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					
								
				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>로그인 방식</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="login_methodid" name="login_method" value="id" <?php if($gnu_config["login_method"] == "id") { ?>checked="checked" <?php } ?> />
							<label for="login_methodid">아이디 로그인</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="login_methodemail" name="login_method" value="email" <?php if($gnu_config["login_method"] == "email") { ?>checked="checked" <?php } ?> />
							<label for="login_methodemail">email 로그인</label>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>로그인 페이지 설정</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="loginpageY" name="loginpage" value="Y" <?php if($gnu_config["loginpage"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="loginpageY">처음실행시에만 표시</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="loginpageN" name="loginpage" value="N" <?php if($gnu_config["loginpage"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="loginpageN">항상 표시 안함</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="loginpageA" name="loginpage" value="A" <?php if($gnu_config["loginpage"] == "A") { ?>checked="checked" <?php } ?> />
							<label for="loginpageA">비로그인시 항상 표시</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">'처음 실행시에만 표시'를 선택할 경우, '로그인 하지 않고 시작하기'버튼을 누르면 이후 로그인 페이지 나오지 않습니다.</p>
				  </div>


				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>로그인 필수 여부</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="nologinNM" name="nologin" value="NM" <?php if($gnu_config["nologin"] == "NM") { ?>checked="checked" <?php } ?> />
							<label for="nologinNM">로그인 필수</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="nologinY" name="nologin" value="Y" <?php if($gnu_config["nologin"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="nologinY">로그인 선택(닫기 버튼 보임)</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="nologinN" name="nologin" value="N" <?php if($gnu_config["nologin"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="nologinN">로그인 선택(닫기 버튼 안보임)</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">'로그인 선택('로그인하지 않고 시작하기' 버튼 안보임)' 선택하시면 x버튼 또는 '로그인하지 않고 시작하기' 버튼이 안나오지만, '뒤로가기'버튼 누르면 메인페이지로 이동합니다. 한편 '로그인 필수'로 선택하시면 로그인 하지 않을 경우 메인화면으로 진입이 불가합니다(회원가입, 아이디 비번 찾기로는 진입 가능).</p>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>로그인 페이지 닫기 버튼</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="nologinBT" name="nologinB" value="T" <?php if($gnu_config["nologinB"] == "T") { ?>checked="checked" <?php } ?> />
							<label for="nologinBT">상단 우측 x 버튼만 사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="nologinBB" name="nologinB" value="B" <?php if($gnu_config["nologinB"] == "B") { ?>checked="checked" <?php } ?> />
							<label for="nologinBB">하단 '로그인 하지 않고 시작하기' 버튼만 사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="nologinBTB" name="nologinB" value="TB" <?php if($gnu_config["nologinB"] == "TB") { ?>checked="checked" <?php } ?> />
							<label for="nologinBTB">상단 우측 x 버튼 + 하단 '로그인 하지 않고 시작하기' 버튼 모두 표시</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">위의 [로그인 필수 여부] 설정에서 '로그인 선택(닫기 버튼 보임)'을 선택하신 경우에만 해당됩니다.</p>
				  </div>


				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>소셜로그인 설정</b></label>
					<div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="socialnaver" value="naver" name="social[]" <?php if(is_array($gnu_config['social']) && in_array('naver',$gnu_config["social"])) { ?>checked="checked" <?php } ?>/>
							<label for="socialnaver">naver</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="socialkakao" value="kakao" name="social[]" <?php if(is_array($gnu_config['social']) && in_array('kakao',$gnu_config["social"])) { ?>checked="checked" <?php } ?>/>
							<label for="socialkakao">kakao</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="socialfacebook" value="facebook" name="social[]" <?php if(is_array($gnu_config['social']) && in_array('facebook',$gnu_config["social"])) { ?>checked="checked" <?php } ?>/>
							<label for="socialfacebook">facebook</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="socialgoogle" value="google" name="social[]" <?php if(is_array($gnu_config['social']) && in_array('google',$gnu_config["social"])) { ?>checked="checked" <?php } ?>/>
							<label for="socialgoogle">google</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="socialtwitter" value="twitter" name="social[]" <?php if(is_array($gnu_config['social']) && in_array('twitter',$gnu_config["social"])) { ?>checked="checked" <?php } ?>/>
							<label for="socialtwitter">twitter</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">소셜로그인을 사용할 경우 해당되는 것만 체크해주시면 됩니다. 그리고 아래 항목에서 선택된 소셜로그인의 링크 주소를 적어주세요. 주의! 최대 5개만 사용가능합니다.</p>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label><b>소셜로그인 링크 주소</b></label>
					<div>
						<div class="input-group" style="margin-bottom:4px;">
							<span class="input-group-addon">naver</span>
							<input type="text" name="social_naver" class="form-control" value="<?php echo $gnu_config['social_naver'] ?>">
						</div>
						<div class="input-group" style="margin-bottom:4px;">
							<span class="input-group-addon">kakao</span>
							<input type="text" name="social_kakao" class="form-control" value="<?php echo $gnu_config['social_kakao'] ?>">
						</div>
						<div class="input-group" style="margin-bottom:4px;">
							<span class="input-group-addon">facebook</span>
							<input type="text" name="social_facebook" class="form-control" value="<?php echo $gnu_config['social_facebook'] ?>">
						</div>
						<div class="input-group" style="margin-bottom:4px;">
							<span class="input-group-addon">google</span>
							<input type="text" name="social_google" class="form-control" value="<?php echo $gnu_config['social_google'] ?>">
						</div>
						<div class="input-group" style="margin-bottom:4px;">
							<span class="input-group-addon">twitter</span>
							<input type="text" name="social_twitter" class="form-control" value="<?php echo $gnu_config['social_twitter'] ?>">
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">소셜로그인 이동 주소를 적어주세요. 자세한 내용은 <a href="#">여기</a>를 참조해주세요.</p>
				</div>

				<div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label for="social_callback"><b>소셜로그인 플러그인 폴더명</b></label>
					<div class="row">
						<div class="col-sm-2">
							<input type="text" class="form-control" id="social_callback"  name="social_callback" value="<?php echo $gnu_config['social_callback'] ?>">
						</div>
						<div class="col-sm-10"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">소셜로그인 사용시 반드시 입력해주셔야 합니다. 소셜플러그인 플러그인의 폴더명을 적어주세요. 아미나의 소셜로그인의 경우 'login-oauth'를 입력해주시고, 편리님의 소셜로그인의 경우 'oauth'를, 그누보드 5.3일 경우에는 'social'를 입력해주세요.</p>
				</div>


				<div class="form-group" style="margin-bottom:20px;">
				    <label><b>소셜로그인만 사용 여부</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="onlysocialN" name="onlysocial" value="N" <?php if($gnu_config["onlysocial"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="onlysocialN">기본로그인 OR 기본로그인+소셜로그인 혼용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="onlysocialY" name="onlysocial" value="Y" <?php if($gnu_config["onlysocial"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="onlysocialY">소셜로그인만 사용</label>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>로그인 세션 미동작 오류 처리 기능</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="login_sessiony" name="login_session" value="Y" <?php if($gnu_config["login_session"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="login_sessiony">사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="login_sessionn" name="login_session" value="N" <?php if($gnu_config["login_session"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="login_sessionn">사용안함</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">로그인 세션 유지가 안되는 오류가 발생시에만 사용해주셔야 합니다.</p>
				  </div>

				  <div class="row" style="margin-bottom:10px;">
					<div class="col-lg-12">
						<center><input type="submit" value="수정" class="btn btn-danger"></center>
					</div>
				  </div>

				</div>

				<!-- /.box-body -->
			  </div>

			</section>
			<section class="col-lg-6 connectedSortable">
				<div class="box box-success box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">로딩화면</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					
								
				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <input type="hidden" name="is_loading_file" id="is_loading_file"  value="<?php echo $gnu_config['is_loading_file'] ?>" /><input type="hidden" name="loading_file_name" value="<?php echo $gnu_config['loading_file_name'] ?>" />
				    <label><b>로딩화면 변경</b></label>
					<div>
						<input type="file" name="loading_file">
						<?php if($gnu_config["is_loading_file"] == "Y") { ?><button type="button" style="margin-top:7px;" id="DelWeb2" class="btn btn-primary mb-2"><?php echo $gnu_config['loading_file_name'] ?>파일삭제</button><?php } ?>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">로딩화면을 임시로 바꿀 수 있습니다. 파일을 삭제하시면 기존의 로딩화면으로 돌아옵니다. 로딩화면 이미지 파일명에 한글이나 공백이 없도록 해주시고, 로딩화면 이미지 용량을 최대한 작게 해서 올려주세요. 로딩화면 이미지가 고화질일 경우 앱이 비정상적으로 종료될 수 있습니다.</p>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label for="loading_s"><b>로딩화면 표시시간(초)</b></label>
					<div class="row">
						<div class="col-sm-2">
							<input type="text" class="form-control" id="loading_s"  name="loading_s" value="<?php echo $gnu_config['loading_s'] ?>">
						</div>
						<div class="col-sm-10"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">로딩화면 표시 시간은 로딩화면 이미지를 올리셨을 때에만 적용되며, 0을 입력하시면 페이지로딩 완료될때까지만 표시합니다.</p>
				</div>


				  <div class="form-group" style="margin-bottom:20px;">
				    <label><b>로딩화면 ProgressBar(원형)</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="progressbarY" name="progressbar" value="Y" <?php if($gnu_config["progressbar"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="progressbarY">ProgressBar 표시</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="progressbarN" name="progressbar" value="N" <?php if($gnu_config["progressbar"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="progressbarN">ProgressBar 표시안함</label>
						</div>
					</div>
				  </div>

				  <div class="row" style="margin-bottom:10px;">
					<div class="col-lg-12">
						<center><input type="submit" value="수정" class="btn btn-danger"></center>
					</div>
				  </div>

				</div>
				<!-- /.box-body -->
			  </div>
			  <div class="box box-primary box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">메인화면 첫 페이지</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					
								
				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>메인화면 첫페이지</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="first_pageY" name="first_page" value="Y" <?php if($gnu_config["first_page"] == "Y") { ?>checked="checked" <?php } ?> onclick="asdfd();" />
							<label for="first_pageY">기본 홈페이지를 첫 페이지로 설정</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="first_pageN" name="first_page" value="N" <?php if($gnu_config["first_page"] == "N") { ?>checked="checked" <?php } ?> onclick="sdfgd();" />
							<label for="first_pageN">다른 페이지를 첫 페이지로 사용</label>
						</div>
						<div class="row">
							<div class="col-sm-8">
								<div class="input-group" style="margin-bottom:4px;">
									<span class="input-group-addon">첫페이지</span>
									<input type="text" class="form-control" name="first_page_url" id="first_page_url" value="<?php echo $gnu_config['first_page_url'] ?>" <?php if($gnu_config["first_page"] == "Y") { ?>disabled <?php } ?>>
								</div>
							</div>
							<div class="col-sm-4"></div>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>로그인 회원 첫 페이지 설정</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="login_first_pageN" name="login_first_page" value="N" <?php if($gnu_config["login_first_page"] == "N") { ?>checked="checked" <?php } ?> onclick="asdfddd();" />
							<label for="login_first_pageN">기본 홈페이지를 첫 페이지로 설정</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="login_first_pageY" name="login_first_page" value="Y" <?php if($gnu_config["login_first_page"] == "Y") { ?>checked="checked" <?php } ?> onclick="sdfgddd();" />
							<label for="login_first_pageY">다른 페이지를 첫 페이지로 사용</label>
						</div>
						<div class="row">
							<div class="col-sm-8">
								<div class="input-group" style="margin-bottom:4px;">
									<span class="input-group-addon">첫페이지</span>
									<input type="text" class="form-control" name="login_first_page_url" id="login_first_page_url" value="<?php echo $gnu_config['login_first_page_url'] ?>" <?php if($gnu_config["login_first_page"] == "N") { ?>disabled <?php } ?>>
								</div>
							</div>
							<div class="col-sm-4"></div>
						</div>
					</div>
				  </div>

				  <div class="row" style="margin-bottom:10px;">
					<div class="col-lg-12">
						<center><input type="submit" value="수정" class="btn btn-danger"></center>
					</div>
				  </div>

				</div>
				<!-- /.box-body -->
			  </div>
			  <div class="box box-warning box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">그외 앱동작</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>앱종료 방식</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="back_finishA" name="back_finish" value="A" <?php if($gnu_config["back_finish"] == "A") { ?>checked="checked" <?php } ?> />
							<label for="back_finishA">Alert창</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="back_finishT" name="back_finish" value="T" <?php if($gnu_config["back_finish"] == "T") { ?>checked="checked" <?php } ?> />
							<label for="back_finishT">뒤로가기 두번 클릭시 자동 종료</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">뒤로가기 버튼을 눌렀을 때, 종료되는 방식을 설정합니다.</p>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>잠금기능</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="lockY" name="lock" value="Y" <?php if($gnu_config["lock"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="lockY">사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="lockN" name="lock" value="N" <?php if($gnu_config["lock"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="lockN">사용안함</label>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label for="lock_second"><b>잠금동작시간(초)</b></label>
					<div class="row">
						<div class="col-sm-5">
							<div class="input-group">
								<input type="text" class="form-control" id="lock_second"  name="lock_second" value="<?php echo $gnu_config['lock_second'] ?>">
								<span class="input-group-addon">초 앱에서 벗어났을 때 앱잠금</span>
						    </div>
						</div>
						<div class="col-sm-7"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">잠금기능을 사용할 경우에만 설정해주세요.</p>
				</div>


				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label for="notnw_url"><b>앱에서 표시할 외부 페이지</b></label>
					<div class="row">
						<div class="col-sm-7">
							<input type="text" class="form-control" id="notnw_url"  name="notnw_url" value="<?php echo $gnu_config['notnw_url'] ?>">
						</div>
						<div class="col-sm-5"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">설정한 url에 대해서는 링크되었을 때 네이버나 크롬 동의 브라우저를 호출하지 않고 앱에서 외부페이지를 보여줍니다. http:// 는 빼고 도메인만 적어주시면 됩니다. 구분은 ,로 해주시면 됩니다. 예) naver.com,daum.net</p>
				</div>

				<div class="form-group" style="margin-bottom:20px;">
					<label for="notnw_url"><b>앱캐시삭제</b></label>
					<div class="row">
					<div class="col-sm-12">
						<button type="button" class="btn btn-primary mb-2" id="gocache">앱캐시삭제</button>
					</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">모든 사용자의 앱캐시를 삭제합니다. 웹사이트에 디자인과 관련한 변경사항이 제대로 적용되지 않을 경우 클릭해주세요.</p>
				</div>

				<div class="row" style="margin-bottom:10px;">
					<div class="col-lg-12">
						<center><input type="submit" value="수정" class="btn btn-danger"></center>
					</div>
				  </div>

				</div>
				<!-- /.box-body -->
			  </div>
			</section>

		</div>

		<div class="row">
          <div class="col-lg-12">
            
          </div>
        </div>
        
       
      </section>
      <!-- /.content -->

	  </form>


    </div>
    <!-- /.content-wrapper -->

        

<script type="text/javascript">

jQuery(function($){
	$("#gocache").click(function() {
		var obj2 = $('#appcache2').val();
		$('#appcache').val(obj2);
		$( "#frm" ).submit();
	});

	$("#DelWeb2").click(function() {
		$('#is_loading_file').val("DELETE");
		$( "#frm" ).submit();
	});
	
});


function asdfd()
{

	var obj2 = document.getElementById('first_page_url');
	obj2.value="";
	obj2.disabled = true;

}

function sdfgd()
{

	var obj2 = document.getElementById('first_page_url');
	obj2.disabled = false;
	
}

function asdfddd()
{

	var obj2 = document.getElementById('login_first_page_url');
	obj2.value="";
	obj2.disabled = true;

}

function sdfgddd()
{

	var obj2 = document.getElementById('login_first_page_url');
	obj2.disabled = false;	

}

</script>

<?php 
include_once('footer.php');
?>
