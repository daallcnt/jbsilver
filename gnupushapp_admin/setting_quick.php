<?php 
include_once('header.php');
?>

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
	  <form role="form" name="frm" id="frm" action="config_update.php" method="post" enctype="multipart/form-data">
	  <input type="hidden" name="config_sort" value="quick" />
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          퀵메뉴설정
          <small>환경설정</small>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="index.php">
              <i class="fa fa-dashboard"></i> Home</a>
          </li>
          <li class="active">환경설정</li>
		  <li class="active">퀵메뉴설정</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->

		<div class="row">
          <div class="col-lg-12">
            <div class="box box-success box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">퀵메뉴설정</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">

				<div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>퀵메뉴사용</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="use_quickY" name="use_quick" value="Y" <?php if($gnu_config["use_quick"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="use_quickY">사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="use_quickN" name="use_quick" value="N" <?php if($gnu_config["use_quick"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="use_quickN">사용안함</label>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>icon font 설정</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="quick_menu_fontF" name="quick_menu_font" value="F" <?php if($gnu_config["quick_menu_font"] == "F") { ?>checked="checked" <?php } ?> />
							<label for="quick_menu_fontF">font awesome</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="quick_menu_fontX1" name="quick_menu_font" value="X1" <?php if($gnu_config["quick_menu_font"] == "X1") { ?>checked="checked" <?php } ?> />
							<label for="quick_menu_fontX1">xeicon 1.0.4</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="quick_menu_fontX2" name="quick_menu_font" value="X2" <?php if($gnu_config["quick_menu_font"] == "X2") { ?>checked="checked" <?php } ?> />
							<label for="quick_menu_fontX2">xeicon 2.3.1</label>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>버튼위치</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="quick_pR" name="quick_p" value="R" <?php if($gnu_config["quick_p"] == "R") { ?>checked="checked" <?php } ?> />
							<label for="quick_pR">우측하단</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="quick_pL" name="quick_p" value="L" <?php if($gnu_config["quick_p"] == "L") { ?>checked="checked" <?php } ?> />
							<label for="quick_pL">좌측하단</label>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label for="quick_bottom_margin"><b>버튼 높이(+)</b></label>
					<div class="row">
						<div class="col-sm-2"><input type="text" class="form-control" id="quick_bottom_margin"  name="quick_bottom_margin" value="<?php echo $gnu_config['quick_bottom_margin'] ?>"></div>
						<div class="col-sm-10"></div>
					</div>
				</div>

				<div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label>버튼색깔</label>
					<div class="row">
						<div class="col-sm-4">
							<div class="input-group" style="margin-bottom:4px;">
								<span class="input-group-addon">버튼색깔</span>
								<input type="text" class="form-control" name="quick_b_c" value="<?php echo $gnu_config['quick_b_c'] ?>">
							</div>
							<div class="input-group" style="margin-bottom:4px;">
								<span class="input-group-addon">버튼클릭시색깔</span>
								<input type="text" class="form-control" name="quick_b_cc" value="<?php echo $gnu_config['quick_b_cc'] ?>">
							</div>
						</div>
						<div class="col-sm-8"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">색깔을 지정하시려면 두 항목 모두 적어주셔야 합니다. 하나만 입력하시면 정상적으로 작동하지 않습니다. 입력하실 때 #ffffff 이런 형식으로 적어주세요. 비워두시면 파란색 기본값으로 정해집니다.</p>
				</div>


				<div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>기본버튼</b></label>
					<div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="quick_defaulta" value="a" name="quick_default[]" <?php if(is_array($gnu_config['quick_default']) && in_array('a', $gnu_config["quick_default"])) { ?>checked="checked"<?php } ?>/>
							<label for="quick_defaulta">현재페이지 URL 복사</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="quick_defaultb" value="b" name="quick_default[]" <?php if(is_array($gnu_config['quick_default']) && in_array('b', $gnu_config["quick_default"])) { ?>checked="checked"<?php } ?>/>
							<label for="quick_defaultb">공유하기</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="quick_defaultc" value="c" name="quick_default[]" <?php if(is_array($gnu_config['quick_default']) && in_array('c', $gnu_config["quick_default"])) { ?>checked="checked"<?php } ?>/>
							<label for="quick_defaultc">로그인</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="quick_defaultd" value="d" name="quick_default[]" <?php if(is_array($gnu_config['quick_default']) && in_array('d', $gnu_config["quick_default"])) { ?>checked="checked"<?php } ?>/>
							<label for="quick_defaultd">설정</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="quick_defaulte" value="e" name="quick_default[]" <?php if(is_array($gnu_config['quick_default']) && in_array('e', $gnu_config["quick_default"])) { ?>checked="checked"<?php } ?>/>
							<label for="quick_defaulte">앱종료</label>
						</div>
					</div>
				  </div>


				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>카카오톡 링크 기능</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="use_kakao_linkY" name="use_kakao_link" value="Y" <?php if($gnu_config["use_kakao_link"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="use_kakao_linkY">사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="use_kakao_linkN" name="use_kakao_link" value="N" <?php if($gnu_config["use_kakao_link"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="use_kakao_linkN">사용안함</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">공유하기 기능에 이미 카카오톡 링크기능이 있습니다. 이 '카카오톡 링크'기능은 카카오톡 수신자가 해당 링크를 클릭시 앱으로 링크되도록 하는 기능으로서 이 기능을 사용하시려면 앱제작시에 추가기능으로 요청해주셔야 합니다.</p>
				  </div>

				<div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label for="bottom_menu_c">퀵메뉴 설정 방법</label>
					<p class="help-block">* 설정하시기 전에 꼭 읽어보시고 설정해주세요!!!! 아래 내용에서 하나라도 비워두시거나 잘못 입력하시면 에러가 발생하니 꼭 주의해서 입력해주시기 바랍니다. <a href="https://gnupushapp.com/bbs/board.php?bo_table=guideapp&wr_id=15" target="_blank">퀵메뉴 설정 방법 안내</a></p>
				</div>


				<div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label>퀵메뉴 추가 커스텀</label>
					<div>
						<table class="table table-striped table-bordered table-hover">
							<tr>
								<td>순서</td>
								<td>Title</td>
								<td>Link/Action</td>
								<td>Icon</td>
								<td>Color</td>
							</tr>
							<tr>
								<td>메뉴1</td>
								<td><input class="form-control" name="quick1" value="<?php echo $gnu_config['quick1'] ?>"></td>
								<td><input class="form-control" name="quick1_link" value="<?php echo $gnu_config['quick1_link'] ?>"></td>
								<td><input class="form-control" name="quick1_icon" value="<?php echo $gnu_config['quick1_icon'] ?>" ></td>
								<td><input class="form-control" name="quick1_color" value="<?php echo $gnu_config['quick1_color'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴2</td>
								<td><input class="form-control" name="quick2" value="<?php echo $gnu_config['quick2'] ?>"></td>
								<td><input class="form-control" name="quick2_link" value="<?php echo $gnu_config['quick2_link'] ?>"></td>
								<td><input class="form-control" name="quick2_icon" value="<?php echo $gnu_config['quick2_icon'] ?>" ></td>
								<td><input class="form-control" name="quick2_color" value="<?php echo $gnu_config['quick2_color'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴3</td>
								<td><input class="form-control" name="quick3" value="<?php echo $gnu_config['quick3'] ?>"></td>
								<td><input class="form-control" name="quick3_link" value="<?php echo $gnu_config['quick3_link'] ?>"></td>
								<td><input class="form-control" name="quick3_icon" value="<?php echo $gnu_config['quick3_icon'] ?>" ></td>
								<td><input class="form-control" name="quick3_color" value="<?php echo $gnu_config['quick3_color'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴4</td>
								<td><input class="form-control" name="quick4" value="<?php echo $gnu_config['quick4'] ?>"></td>
								<td><input class="form-control" name="quick4_link" value="<?php echo $gnu_config['quick4_link'] ?>"></td>
								<td><input class="form-control" name="quick4_icon" value="<?php echo $gnu_config['quick4_icon'] ?>" ></td>
								<td><input class="form-control" name="quick4_color" value="<?php echo $gnu_config['quick4_color'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴5</td>
								<td><input class="form-control" name="quick5" value="<?php echo $gnu_config['quick5'] ?>"></td>
								<td><input class="form-control" name="quick5_link" value="<?php echo $gnu_config['quick5_link'] ?>"></td>
								<td><input class="form-control" name="quick5_icon" value="<?php echo $gnu_config['quick5_icon'] ?>" ></td>
								<td><input class="form-control" name="quick5_color" value="<?php echo $gnu_config['quick5_color'] ?>" ></td>
							</tr>
							<tr>
								<td>로그인버튼</td>
								<td><input class="form-control" name="quick_login" value="<?php echo $gnu_config['quick_login'] ?>"></td>
								<td><input class="form-control" name="quick_login_link" value="<?php echo $gnu_config['quick_login_link'] ?>"></td>
								<td><input class="form-control" name="quick_login_icon" value="<?php echo $gnu_config['quick_login_icon'] ?>" ></td>
								<td><input class="form-control" name="quick_login_color" value="<?php echo $gnu_config['quick_login_color'] ?>" ></td>
							</tr>
						</table>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;"><font color="red">*퀵메뉴 중에 '로그인'버튼을 사용하실 경우에는 로그인 상태일 때 아이콘 또는 링크가 변경되어야 합니다. 여기에 표시될 아이콘, 링크를 추가적으로 입력해주세요. 로그인 버튼을 사용하실 경우 반드시 입력해주셔야 하며, 로그인 버튼을 사용하지 않으실 경우는 무시하셔도 됩니다.</font></p>
				</div>


				<div class="form-group" style="margin-bottom:20px;">
					<label>글쓰기버튼 사용안할 게시판</label>
					<p class="help-block">게시판진입시 자동으로 '~에 글쓰기'버튼이 생성됩니다. 이 기능을 사용안할 게시판을 체크해주세요.</p>
					<?php
					$sql = " select * from {$g5['board_table']}";
					$result = sql_query($sql);
					for ($i=0; $row=sql_fetch_array($result); $i++) {
					?>
					<div style="padding-top:10px;">
						<label>
							<input type="checkbox" value="<?php echo $row['bo_table'] ?>" name="quick_module_srls[]" <?php if(is_array($gnu_config['quick_module_srls']) && in_array($row['bo_table'], $gnu_config["quick_module_srls"])) { ?>checked="checked"<?php } ?>><strong><?php echo get_text($row['bo_subject']) ?></strong> (<?php echo $row['gr_id'] ?>/<?php echo $row['bo_table'] ?>)
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
        
       
      </section>
      <!-- /.content -->

	  </form>


    </div>
    <!-- /.content-wrapper -->



<?php 
include_once('footer.php');
?>
