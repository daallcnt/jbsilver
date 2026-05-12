<?php 
include_once('header.php');
?>

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
	  <form role="form" name="frm" id="frm" action="config_update.php" method="post" enctype="multipart/form-data">
	  <input type="hidden" name="config_sort" value="bottom" />
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          하단메뉴설정
          <small>환경설정</small>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="index.php">
              <i class="fa fa-dashboard"></i> Home</a>
          </li>
          <li class="active">환경설정</li>
		  <li class="active">하단메뉴설정</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-12">
            <div class="box box-danger box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">하단메뉴 기본설정</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">

				<div class="form-group" style="margin-bottom:20px;">
				    <label><b>하단메뉴기능</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="use_bottomY" name="use_bottom" value="Y" <?php if($gnu_config["use_bottom"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="use_bottomY">사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="use_bottomN" name="use_bottom" value="N" <?php if($gnu_config["use_bottom"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="use_bottomN">사용안함</label>
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

		<div class="row">
          <div class="col-lg-6">
            <div class="box box-success box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">안드로이드앱 하단메뉴</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>스타일</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="bottom_menu_styleC" name="bottom_menu_style" value="C" <?php if($gnu_config["bottom_menu_style"] == "C") { ?>checked="checked" <?php } ?> onclick="set_bottom_classic();" />
							<label for="bottom_menu_styleC">클래식 스타일</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="bottom_menu_styleI" name="bottom_menu_style" value="I" <?php if($gnu_config["bottom_menu_style"] == "I") { ?>checked="checked" <?php } ?> onclick="set_bottom_icontext();" />
							<label for="bottom_menu_styleI">아이콘+텍스트 스타일</label>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label for="notbottom_url">하단메뉴숨김 URL</label>
					<div class="row">
						<div class="col-sm-12">
							<input type="text" class="form-control" id="notbottom_url"  name="notbottom_url" value="<?php echo $gnu_config['notbottom_url'] ?>">
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">특정 페이지에서 하단메뉴가 안나오게 할 수 있습니다. 해당페이지의 url을 입력해주세요('http://', 'www' 는 빼고 입력해주세요). 구분은 ,로 해주시면 됩니다.</p>
				</div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>icon font 설정</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="bottom_menu_fontF" name="bottom_menu_font" value="F" <?php if($gnu_config["bottom_menu_font"] == "F") { ?>checked="checked" <?php } ?> />
							<label for="bottom_menu_fontF">font awesome</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="bottom_menu_fontX1" name="bottom_menu_font" value="X1" <?php if($gnu_config["bottom_menu_font"] == "X1") { ?>checked="checked" <?php } ?> />
							<label for="bottom_menu_fontX1">xeicon 1.0.4</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="bottom_menu_fontX2" name="bottom_menu_font" value="X2" <?php if($gnu_config["bottom_menu_font"] == "X2") { ?>checked="checked" <?php } ?> />
							<label for="bottom_menu_fontX2">xeicon 2.3.1</label>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label for="bottom_menu_c"><b>버튼 클릭시 text 색깔</b></label>
					<div class="row">
						<div class="col-sm-2"><input type="text" class="form-control" id="bottom_menu_c"  name="bottom_menu_c" value="<?php echo $gnu_config['bottom_menu_c'] ?>"></div>
						<div class="col-sm-10"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">입력하실 때 #ffffff 이런 형식으로 적어주세요. 비워두시면 기본값으로 정해집니다.</p>
				</div>

				<div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label for="bottom_menu_c">하단메뉴설정 방법 안내</label>
					<p class="help-block">* 설정하시기 전에 꼭 읽어보시고 설정해주세요!!!! 아래 내용에서 하나라도 비워두시거나 잘못 입력하시면 에러가 발생하니 꼭 주의해서 입력해주시기 바랍니다. <a href="https://gnupushapp.com/bbs/board.php?bo_table=guideapp&wr_id=15" target="_blank">하단메뉴 설정 방법 안내</a></p>
				</div>

				<div class="form-group" style="margin-bottom:20px;">
					<label>하단메뉴설정</label>
					<div>
						<table class="table table-striped table-bordered table-hover" id="bottom_classic" <?php if($gnu_config["bottom_menu_style"] != "C") { ?>style="display:none;"<?php } ?>>
							<tr>
								<td>순서</td>
								<td>icon</td>
								<td>Link/Action</td>
								<td>Color</td>
							</tr>
							<tr>
								<td>메뉴1</td>
								<td><input class="form-control" name="bottom_menuc1_icon" value="<?php echo $gnu_config['bottom_menuc1_icon'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc1_link" value="<?php echo $gnu_config['bottom_menuc1_link'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc1_color" value="<?php echo $gnu_config['bottom_menuc1_color'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴2</td>
								<td><input class="form-control" name="bottom_menuc2_icon" value="<?php echo $gnu_config['bottom_menuc2_icon'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc2_link" value="<?php echo $gnu_config['bottom_menuc2_link'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc2_color" value="<?php echo $gnu_config['bottom_menuc2_color'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴3</td>
								<td><input class="form-control" name="bottom_menuc3_icon" value="<?php echo $gnu_config['bottom_menuc3_icon'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc3_link" value="<?php echo $gnu_config['bottom_menuc3_link'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc3_color" value="<?php echo $gnu_config['bottom_menuc3_color'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴4</td>
								<td><input class="form-control" name="bottom_menuc4_icon" value="<?php echo $gnu_config['bottom_menuc4_icon'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc4_link" value="<?php echo $gnu_config['bottom_menuc4_link'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc4_color" value="<?php echo $gnu_config['bottom_menuc4_color'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴5</td>
								<td><input class="form-control" name="bottom_menuc5_icon" value="<?php echo $gnu_config['bottom_menuc5_icon'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc5_link" value="<?php echo $gnu_config['bottom_menuc5_link'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc5_color" value="<?php echo $gnu_config['bottom_menuc5_color'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴6</td>
								<td><input class="form-control" name="bottom_menuc6_icon" value="<?php echo $gnu_config['bottom_menuc6_icon'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc6_link" value="<?php echo $gnu_config['bottom_menuc6_link'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc6_color" value="<?php echo $gnu_config['bottom_menuc6_color'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴7</td>
								<td><input class="form-control" name="bottom_menuc7_icon" value="<?php echo $gnu_config['bottom_menuc7_icon'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc7_link" value="<?php echo $gnu_config['bottom_menuc7_link'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc7_color" value="<?php echo $gnu_config['bottom_menuc7_color'] ?>" ></td>
							</tr>
							<tr>
								<td>로그인버튼</td>
								<td><input class="form-control" name="bottom_menuc8_icon" value="<?php echo $gnu_config['bottom_menuc8_icon'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc8_link" value="<?php echo $gnu_config['bottom_menuc8_link'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc8_color" value="<?php echo $gnu_config['bottom_menuc8_color'] ?>" ></td>
							</tr>
						</table>
						<table class="table table-striped table-bordered table-hover" id="bottom_icontext" <?php if($gnu_config["bottom_menu_style"] != "I") { ?>style="display:none;"<?php } ?>>
							<tr>
								<td>순서</td>
								<td>title</td>
								<td>icon</td>
								<td>Link/Action</td>
								<td>Color</td>
							</tr>
							<tr>
								<td>메뉴1</td>
								<td><input class="form-control" name="bottom_menu1" value="<?php echo $gnu_config['bottom_menu1'] ?>"></td>
								<td><input class="form-control" name="bottom_menu1_icon" value="<?php echo $gnu_config['bottom_menu1_icon'] ?>"></td>
								<td><input class="form-control" name="bottom_menu1_link" value="<?php echo $gnu_config['bottom_menu1_link'] ?>"></td>
								<td><input class="form-control" name="bottom_menu1_color" value="<?php echo $gnu_config['bottom_menu1_color'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴2</td>
								<td><input class="form-control" name="bottom_menu2" value="<?php echo $gnu_config['bottom_menu2'] ?>"></td>
								<td><input class="form-control" name="bottom_menu2_icon" value="<?php echo $gnu_config['bottom_menu2_icon'] ?>"></td>
								<td><input class="form-control" name="bottom_menu2_link" value="<?php echo $gnu_config['bottom_menu2_link'] ?>"></td>
								<td><input class="form-control" name="bottom_menu2_color" value="<?php echo $gnu_config['bottom_menu2_color'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴3</td>
								<td><input class="form-control" name="bottom_menu3" value="<?php echo $gnu_config['bottom_menu3'] ?>"></td>
								<td><input class="form-control" name="bottom_menu3_icon" value="<?php echo $gnu_config['bottom_menu3_icon'] ?>"></td>
								<td><input class="form-control" name="bottom_menu3_link" value="<?php echo $gnu_config['bottom_menu3_link'] ?>"></td>
								<td><input class="form-control" name="bottom_menu3_color" value="<?php echo $gnu_config['bottom_menu3_color'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴4</td>
								<td><input class="form-control" name="bottom_menu4" value="<?php echo $gnu_config['bottom_menu4'] ?>"></td>
								<td><input class="form-control" name="bottom_menu4_icon" value="<?php echo $gnu_config['bottom_menu4_icon'] ?>"></td>
								<td><input class="form-control" name="bottom_menu4_link" value="<?php echo $gnu_config['bottom_menu4_link'] ?>"></td>
								<td><input class="form-control" name="bottom_menu4_color" value="<?php echo $gnu_config['bottom_menu4_color'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴5</td>
								<td><input class="form-control" name="bottom_menu6" value="<?php echo $gnu_config['bottom_menu6'] ?>"></td>
								<td><input class="form-control" name="bottom_menu6_icon" value="<?php echo $gnu_config['bottom_menu6_icon'] ?>"></td>
								<td><input class="form-control" name="bottom_menu6_link" value="<?php echo $gnu_config['bottom_menu6_link'] ?>"></td>
								<td><input class="form-control" name="bottom_menu6_color" value="<?php echo $gnu_config['bottom_menu6_color'] ?>" ></td>
							</tr>
							<tr>
								<td>로그인버튼</td>
								<td><input class="form-control" name="bottom_menu5" value="<?php echo $gnu_config['bottom_menu5'] ?>"></td>
								<td><input class="form-control" name="bottom_menu5_icon" value="<?php echo $gnu_config['bottom_menu5_icon'] ?>"></td>
								<td><input class="form-control" name="bottom_menu5_link" value="<?php echo $gnu_config['bottom_menu5_link'] ?>"></td>
								<td><input class="form-control" name="bottom_menu5_color" value="<?php echo $gnu_config['bottom_menu5_color'] ?>" ></td>
							</tr>
						</table>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;"><font color="red">* 하단메뉴 중에 '로그인'버튼을 사용하실 경우에는 로그인 상태일 때 아이콘 또는 링크가 변경되어야 합니다. 여기에 표시될 아이콘, 링크를 추가적으로 입력해주세요. 로그인 버튼을 사용하실 경우 반드시 입력해주셔야 하며, 로그인 버튼을 사용하지 않으실 경우는 무시하셔도 됩니다.</font></p>
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
		  <div class="col-lg-6">
            <div class="box box-warning box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">ios앱 하단메뉴</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>스타일</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="bottom_menu_styleiC" name="bottom_menu_stylei" value="C" <?php if($gnu_config["bottom_menu_stylei"] == "C") { ?>checked="checked" <?php } ?> onclick="set_bottom_classici();" />
							<label for="bottom_menu_styleiC">클래식 스타일</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="bottom_menu_styleiI" name="bottom_menu_stylei" value="I" <?php if($gnu_config["bottom_menu_stylei"] == "I") { ?>checked="checked" <?php } ?> onclick="set_bottom_icontexti();" />
							<label for="bottom_menu_styleiI">아이콘+텍스트 스타일</label>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>icon font 설정</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="bottom_menu_fontiF" name="bottom_menu_fonti" value="F" <?php if($gnu_config["bottom_menu_fonti"] == "F") { ?>checked="checked" <?php } ?> />
							<label for="bottom_menu_fontiF">font awesome</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="bottom_menu_fontiX1" name="bottom_menu_fonti" value="X1" <?php if($gnu_config["bottom_menu_fonti"] == "X1") { ?>checked="checked" <?php } ?> />
							<label for="bottom_menu_fontiX1">xeicon 1.0.4</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="bottom_menu_fontiX2" name="bottom_menu_fonti" value="X2" <?php if($gnu_config["bottom_menu_fonti"] == "X2") { ?>checked="checked" <?php } ?> />
							<label for="bottom_menu_fontiX2">xeicon 2.3.1</label>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label for="bottom_menu_ci"><b>버튼 클릭시 text 색깔</b></label>
					<div class="row">
						<div class="col-sm-2"><input type="text" class="form-control" id="bottom_menu_ci"  name="bottom_menu_ci" value="<?php echo $gnu_config['bottom_menu_ci'] ?>"></div>
						<div class="col-sm-10"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">입력하실 때 #ffffff 이런 형식으로 적어주세요. 비워두시면 기본값으로 정해집니다.</p>
				</div>

				<div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label for="bottom_menu_c">하단메뉴설정 방법 안내</label>
					<p class="help-block">* ios앱은 종료버튼 사용불가합니다. Link/Action항목에 finish값을 입력하실 수 없습니다. 그리고 설정하시기 전에 꼭 읽어보시고 설정해주세요!!!! 아래 내용에서 하나라도 비워두시거나 잘못 입력하시면 에러가 발생하니 꼭 주의해서 입력해주시기 바랍니다. <a href="http://gnupushapp.com/bbs/board.php?bo_table=guideapp&wr_id=15" target="_blank">하단메뉴 설정 방법 안내</a></p>
				</div>

				<div class="form-group" style="margin-bottom:20px;">
					<label>하단메뉴설정</label>
					<div>
						<table class="table table-striped table-bordered table-hover" id="bottom_classici" <?php if($gnu_config["bottom_menu_stylei"] != "C") { ?>style="display:none;"<?php } ?>>
							<tr>
								<td>순서</td>
								<td>icon</td>
								<td>Link/Action</td>
								<td>Color</td>
							</tr>
							<tr>
								<td>메뉴1</td>
								<td><input class="form-control" name="bottom_menuc1_iconi" value="<?php echo $gnu_config['bottom_menuc1_iconi'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc1_linki" value="<?php echo $gnu_config['bottom_menuc1_linki'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc1_colori" value="<?php echo $gnu_config['bottom_menuc1_colori'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴2</td>
								<td><input class="form-control" name="bottom_menuc2_iconi" value="<?php echo $gnu_config['bottom_menuc2_iconi'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc2_linki" value="<?php echo $gnu_config['bottom_menuc2_linki'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc2_colori" value="<?php echo $gnu_config['bottom_menuc2_colori'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴3</td>
								<td><input class="form-control" name="bottom_menuc3_iconi" value="<?php echo $gnu_config['bottom_menuc3_iconi'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc3_linki" value="<?php echo $gnu_config['bottom_menuc3_linki'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc3_colori" value="<?php echo $gnu_config['bottom_menuc3_colori'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴4</td>
								<td><input class="form-control" name="bottom_menuc4_iconi" value="<?php echo $gnu_config['bottom_menuc4_iconi'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc4_linki" value="<?php echo $gnu_config['bottom_menuc4_linki'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc4_colori" value="<?php echo $gnu_config['bottom_menuc4_colori'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴5</td>
								<td><input class="form-control" name="bottom_menuc5_iconi" value="<?php echo $gnu_config['bottom_menuc5_iconi'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc5_linki" value="<?php echo $gnu_config['bottom_menuc5_linki'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc5_colori" value="<?php echo $gnu_config['bottom_menuc5_colori'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴6</td>
								<td><input class="form-control" name="bottom_menuc6_iconi" value="<?php echo $gnu_config['bottom_menuc6_iconi'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc6_linki" value="<?php echo $gnu_config['bottom_menuc6_linki'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc6_colori" value="<?php echo $gnu_config['bottom_menuc6_colori'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴7</td>
								<td><input class="form-control" name="bottom_menuc7_iconi" value="<?php echo $gnu_config['bottom_menuc7_iconi'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc7_linki" value="<?php echo $gnu_config['bottom_menuc7_linki'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc7_colori" value="<?php echo $gnu_config['bottom_menuc7_colori'] ?>" ></td>
							</tr>
							<tr>
								<td>로그인버튼</td>
								<td><input class="form-control" name="bottom_menuc8_iconi" value="<?php echo $gnu_config['bottom_menuc8_iconi'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc8_linki" value="<?php echo $gnu_config['bottom_menuc8_linki'] ?>"></td>
								<td><input class="form-control" name="bottom_menuc8_colori" value="<?php echo $gnu_config['bottom_menuc8_colori'] ?>" ></td>
							</tr>
						</table>
						<table class="table table-striped table-bordered table-hover" id="bottom_icontexti" <?php if($gnu_config["bottom_menu_stylei"] != "I") { ?>style="display:none;"<?php } ?>>
							<tr>
								<td>순서</td>
								<td>title</td>
								<td>icon</td>
								<td>Link/Action</td>
								<td>Color</td>
							</tr>
							<tr>
								<td>메뉴1</td>
								<td><input class="form-control" name="bottom_menu1i" value="<?php echo $gnu_config['bottom_menu1i'] ?>"></td>
								<td><input class="form-control" name="bottom_menu1_iconi" value="<?php echo $gnu_config['bottom_menu1_iconi'] ?>"></td>
								<td><input class="form-control" name="bottom_menu1_linki" value="<?php echo $gnu_config['bottom_menu1_linki'] ?>"></td>
								<td><input class="form-control" name="bottom_menu1_colori" value="<?php echo $gnu_config['bottom_menu1_colori'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴2</td>
								<td><input class="form-control" name="bottom_menu2i" value="<?php echo $gnu_config['bottom_menu2i'] ?>"></td>
								<td><input class="form-control" name="bottom_menu2_iconi" value="<?php echo $gnu_config['bottom_menu2_iconi'] ?>"></td>
								<td><input class="form-control" name="bottom_menu2_linki" value="<?php echo $gnu_config['bottom_menu2_linki'] ?>"></td>
								<td><input class="form-control" name="bottom_menu2_colori" value="<?php echo $gnu_config['bottom_menu2_colori'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴3</td>
								<td><input class="form-control" name="bottom_menu3i" value="<?php echo $gnu_config['bottom_menu3i'] ?>"></td>
								<td><input class="form-control" name="bottom_menu3_iconi" value="<?php echo $gnu_config['bottom_menu3_iconi'] ?>"></td>
								<td><input class="form-control" name="bottom_menu3_linki" value="<?php echo $gnu_config['bottom_menu3_linki'] ?>"></td>
								<td><input class="form-control" name="bottom_menu3_colori" value="<?php echo $gnu_config['bottom_menu3_colori'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴4</td>
								<td><input class="form-control" name="bottom_menu4i" value="<?php echo $gnu_config['bottom_menu4i'] ?>"></td>
								<td><input class="form-control" name="bottom_menu4_iconi" value="<?php echo $gnu_config['bottom_menu4_iconi'] ?>"></td>
								<td><input class="form-control" name="bottom_menu4_linki" value="<?php echo $gnu_config['bottom_menu4_linki'] ?>"></td>
								<td><input class="form-control" name="bottom_menu4_colori" value="<?php echo $gnu_config['bottom_menu4_colori'] ?>" ></td>
							</tr>
							<tr>
								<td>메뉴5</td>
								<td><input class="form-control" name="bottom_menu6i" value="<?php echo $gnu_config['bottom_menu6i'] ?>"></td>
								<td><input class="form-control" name="bottom_menu6_iconi" value="<?php echo $gnu_config['bottom_menu6_iconi'] ?>"></td>
								<td><input class="form-control" name="bottom_menu6_linki" value="<?php echo $gnu_config['bottom_menu6_linki'] ?>"></td>
								<td><input class="form-control" name="bottom_menu6_colori" value="<?php echo $gnu_config['bottom_menu6_colori'] ?>" ></td>
							</tr>
							<tr>
								<td>로그인버튼</td>
								<td><input class="form-control" name="bottom_menu5i" value="<?php echo $gnu_config['bottom_menu5i'] ?>"></td>
								<td><input class="form-control" name="bottom_menu5_iconi" value="<?php echo $gnu_config['bottom_menu5_iconi'] ?>"></td>
								<td><input class="form-control" name="bottom_menu5_linki" value="<?php echo $gnu_config['bottom_menu5_linki'] ?>"></td>
								<td><input class="form-control" name="bottom_menu5_colori" value="<?php echo $gnu_config['bottom_menu5_colori'] ?>" ></td>
							</tr>
						</table>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;"><font color="red">* 하단메뉴 중에 '로그인'버튼을 사용하실 경우에는 로그인 상태일 때 아이콘 또는 링크가 변경되어야 합니다. 여기에 표시될 아이콘, 링크를 추가적으로 입력해주세요. 로그인 버튼을 사용하실 경우 반드시 입력해주셔야 하며, 로그인 버튼을 사용하지 않으실 경우는 무시하셔도 됩니다.</font></p>
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

function set_bottom_classic()
{
	var obj1 = document.getElementById('bottom_classic');
	var obj2 = document.getElementById('bottom_icontext');
	obj1.style.display = '';
	obj2.style.display = 'none';

}

function set_bottom_icontext()
{
	var obj2 = document.getElementById('bottom_classic');
	var obj1 = document.getElementById('bottom_icontext');
	obj1.style.display = '';
	obj2.style.display = 'none';

}

function set_bottom_classici()
{
	var obj1 = document.getElementById('bottom_classici');
	var obj2 = document.getElementById('bottom_icontexti');
	obj1.style.display = '';
	obj2.style.display = 'none';

}

function set_bottom_icontexti()
{
	var obj2 = document.getElementById('bottom_classici');
	var obj1 = document.getElementById('bottom_icontexti');
	obj1.style.display = '';
	obj2.style.display = 'none';

}
</script>

<?php 
include_once('footer.php');
?>
