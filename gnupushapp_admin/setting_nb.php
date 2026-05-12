<?php 
include_once('header.php');
?>

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
	  <form role="form" name="frm" id="frm" action="config_update.php" method="post" enctype="multipart/form-data">
	  <input type="hidden" name="config_sort" value="nb" />
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          앱설정화면
          <small>환경설정</small>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="index.php">
              <i class="fa fa-dashboard"></i> Home</a>
          </li>
          <li class="active">환경설정</li>
		  <li class="active">앱설정화면</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-12">
            <div class="box box-danger box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">계정</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">

				<div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>[계정] 표시 항목</b></label>
					<div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="mem_infogroup" value="group" name="mem_info[]" <?php if(is_array($gnu_config['mem_info']) && in_array('group',$gnu_config["mem_info"])) { ?>checked="checked" <?php } ?>/>
							<label for="mem_infogroup">회원등급</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="mem_infolevel" value="level" name="mem_info[]" <?php if(is_array($gnu_config['mem_info']) && in_array('level',$gnu_config["mem_info"])) { ?>checked="checked" <?php } ?>/>
							<label for="mem_infolevel">Level</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="mem_infoexp" value="exp" name="mem_info[]" <?php if(is_array($gnu_config['mem_info']) && in_array('exp',$gnu_config["mem_info"])) { ?>checked="checked" <?php } ?>/>
							<label for="mem_infoexp">경험치</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="mem_infopoint" value="point" name="mem_info[]" <?php if(is_array($gnu_config['mem_info']) && in_array('point',$gnu_config["mem_info"])) { ?>checked="checked" <?php } ?>/>
							<label for="mem_infopoint">포인트</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">앱설정화면의 프로필사진 옆에 있는 회원 정보란에 노출시킬 내용을 정해주세요. 경험치는 아미나의 경우에만 해당되며, level은 아미나의 경우 아미나의 레벨이 표시됩니다. 아미나에서는 회원등급과 Level이 다르지만, 그외 그누보드나 다른 빌더의 경우에는 동일합니다.</p>
				  </div>
					
								
				  <div class="form-group" style="margin-bottom:20px;">
				    <label><b>[내 알림목록] 표시 & 뱃지 동기화 기능</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="mypushlist_y" name="mypushlist" value="Y" <?php if($gnu_config["mypushlist"] == "Y") { ?>checked="checked" <?php } ?> <?php if (!defined('APMS_VERSION') && !defined('_EYOOM_VESION_')){ ?> onclick="srdpushon();"<?php } ?> />
							<label for="mypushlist_y">사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="mypushlist_n" name="mypushlist" value="N" <?php if($gnu_config["mypushlist"] == "N") { ?>checked="checked" <?php } ?> <?php if (!defined('APMS_VERSION') && !defined('_EYOOM_VESION_')){ ?> onclick="srdpushoff();"<?php } ?> />
							<label for="mypushlist_n">사용안함</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">이 기능을 사용할 경우 이윰 또는 아미나의 경우 모든 푸시알림 내역을 내글반응에서 확인할 수 있습니다. 이윰 또는 아미나가 아닐 경우 이 기능을 사용하고자 하신다면, 반드시 <a href="https://sir.kr/g5_plugin/2153" target="_blank">srd.pushmsg플러그인</a>을 설치해 주셔야 합니다. 이 기능은 앱사용자가 자신이 알림받은 내역을 확인할 수 있도록 할 뿐만 아니라 확인안한 알림 숫자와 사용자의 앱 아이콘의 뱃지수가 일치되도록 합니다. 이 기능은 앱사용자 수가 많을 경우 서버에 부담이 될 수 있는 기능입니다. 이 기능 사용시 서버가 많이 느려지면 사용을 자제해주세요.<input type="hidden" name="pushmsg" id="pushmsg" <?php if (defined('APMS_VERSION') || defined('_EYOOM_VESION_')) { ?>value="N" <?php }else{ ?> value="<?php echo $gnu_config["mypushlist"] ?>" <?php } ?>/></p>
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
				  <h3 class="box-title">기본알림</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>[기본알림] 항목 표시</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="default_pushY" name="default_push" value="Y" <?php if($gnu_config["default_push"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="default_pushY">표시</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="default_pushN" name="default_push" value="N" <?php if($gnu_config["default_push"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="default_pushN">표시안함</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">기본알림은 '답변,내글에달린댓글,대댓글,꼬리글,공지사항,쪽지 등'의 알림을 설정하는 항목입니다.</p>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>[기본알림] 설정 초기값</b></label>
					<div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="setting_fg" value="g" name="setting_f[]" <?php if(is_array($gnu_config['setting_f']) && in_array('g', $gnu_config["setting_f"])) { ?>checked="checked"<?php } ?>/>
							<label for="setting_fg">공지사항</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">앱을 설치할 때 default 알림설정값을 정해주세요.</p>
				  </div>


				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>로그인 시 기본알림 설정값 변경 여부</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="change_sN" name="change_s" value="N" <?php if($gnu_config["change_s"] == "N") { ?>checked="checked" <?php } ?> onclick="ddd();" />
							<label for="change_sN">변동없음</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="change_sY" name="change_s" value="Y" <?php if($gnu_config["change_s"] == "Y") { ?>checked="checked" <?php } ?> onclick="eee();" />
							<label for="change_sY">아래의 설정으로 자동변경</label>
						</div>
					</div>
					<div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="setting_ac" value="c" name="setting_a[]" <?php if(is_array($gnu_config['setting_a']) && in_array('c', $gnu_config["setting_a"])) { ?>checked="checked"<?php } ?>/>
							<label for="setting_ac">답변</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="setting_ad" value="d" name="setting_a[]" <?php if(is_array($gnu_config['setting_a']) && in_array('d', $gnu_config["setting_a"])) { ?>checked="checked"<?php } ?>/>
							<label for="setting_ad">내글에 달린 댓글</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="setting_ae" value="e" name="setting_a[]" <?php if(is_array($gnu_config['setting_a']) && in_array('e', $gnu_config["setting_a"])) { ?>checked="checked"<?php } ?>/>
							<label for="setting_ae">대댓글</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="setting_af" value="f" name="setting_a[]" <?php if(is_array($gnu_config['setting_a']) && in_array('f', $gnu_config["setting_a"])) { ?>checked="checked"<?php } ?>/>
							<label for="setting_af">내가 댓글 단 글의 새댓글</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="setting_ag" value="g" name="setting_a[]" <?php if(is_array($gnu_config['setting_a']) && in_array('g', $gnu_config["setting_a"])) { ?>checked="checked"<?php } ?>/>
							<label for="setting_ag">공지사항</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="setting_ah" value="h" name="setting_a[]" <?php if(is_array($gnu_config['setting_a']) && in_array('h', $gnu_config["setting_a"])) { ?>checked="checked"<?php } ?>/>
							<label for="setting_ah">쪽지</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="setting_ai" value="i" disabled name="setting_a[]" <?php if(is_array($gnu_config['setting_a']) && in_array('i', $gnu_config["setting_a"])) { ?>checked="checked"<?php } ?>/>
							<label for="setting_ai">맨션알림</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="setting_aj" value="j" disabled name="setting_a[]" <?php if(is_array($gnu_config['setting_a']) && in_array('j', $gnu_config["setting_a"])) { ?>checked="checked"<?php } ?>/>
							<label for="setting_aj">추천알림</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">사용자가 앱에서 로그인했을 때, 자동으로 알림설정값을 조정해 줄 것인지, 아니면 아무 변동도 없게 할 것인지 정해주세요. 만일 자동으로 설정값을 조정하려면 아래의 체크버튼에서 해당되는 것을 체크해주세요.</p>
				  </div>

				  <div class="form-group" style="margin-bottom:20px;">
				    <label><b>"내 글에 달린 모든 댓글"의 의미</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="comment_sA" name="comment_s" value="A" <?php if($gnu_config["comment_s"] == "A") { ?>checked="checked" <?php } ?>/>
							<label for="comment_sA">모든 댓글 알림(댓글,대댓글 구분없음)</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="comment_sS" name="comment_s" value="S" <?php if($gnu_config["comment_s"] == "S") { ?>checked="checked" <?php } ?> />
							<label for="comment_sS">댓글만 알림되고 대댓글은 제외</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">기본알림 항목중에 "내 글에 달린 댓글 알림" 항목이 의미하는 바가 무엇인지 설정해주세요.</p>
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
				  <h3 class="box-title">구독알림</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>[구독게시판] 사용 및 표시</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" name="choose_board" id="choose_boardY" value="Y" <?php if($gnu_config["choose_board"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="choose_boardY">사용&표시</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" name="choose_board" id="choose_boardN" value="N" <?php if($gnu_config["choose_board"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="choose_boardN">사용&표시안함</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" name="choose_board" id="choose_boardX" value="X" <?php if($gnu_config["choose_board"] == "X") { ?>checked="checked" <?php } ?> />
							<label for="choose_boardX">사용안함</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">구독알림은 사용자가 원하는 특정한 게시판의 새글을 알림받는 기능을 말합니다.</p>
				  </div>


				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>[구독게시판] 설정 초기값</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" name="choose_board_s" id="choose_board_sF" <?php if($gnu_config["choose_board"] != "F") { ?>disabled<?php } ?> value="F" <?php if($gnu_config["choose_board_s"] == "F") { ?>checked="checked" <?php } ?> />
							<label for="choose_board_sF">모든게시판&댓글 알림(고정)</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" name="choose_board_s" id="choose_board_sF2" <?php if($gnu_config["choose_board"] != "F2") { ?>disabled<?php } ?> value="F2" <?php if($gnu_config["choose_board_s"] == "F2") { ?>checked="checked" <?php } ?> />
							<label for="choose_board_sF2">모든게시판 알림(고정)</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" name="choose_board_s" id="choose_board_sD" value="D" <?php if($gnu_config["choose_board_s"] == "D") { ?>checked="checked" <?php } ?> />
							<label for="choose_board_sD">모든게시판&댓글 알림</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" name="choose_board_s" id="choose_board_sY" value="Y" <?php if($gnu_config["choose_board_s"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="choose_board_sY">모든게시판 알림</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" name="choose_board_s" id="choose_board_sN" value="N" <?php if($gnu_config["choose_board_s"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="choose_board_sN">모든게시판&댓글 모두 알림받지 않음</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" name="choose_board_s" id="choose_board_sC" value="C" <?php if($gnu_config["choose_board_s"] == "C") { ?>checked="checked" <?php } ?> />
							<label for="choose_board_sC">임의설정</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">[모든게시판&댓글 알림(고정)]과 [모든게시판 알림(고정)]는 [[구독게시판] 사용 및 표시]항목이 '사용 & 표시안함'일 때만 설정가능하며, 푸시알림사용하는 모든 게시판에서 발생하는 새글 또는 댓글을 모든 사용자에게 알림합니다. 사용자는 이에 대해 선택권이 없습니다.</p>
				  </div>


				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;<?php if($gnu_config["choose_board_s"] != "C") { ?>display:none<?php } ?>" id="chooseboarddefault">
				    <label><b>구독게시판 [임의설정] 초기값</b></label>
					<p class="help-block">위 설정에서 [임의설정]하신 경우에만 적용됩니다.</p>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="subscribe_commentsY" name="subscribe_comments" value="Y" <?php if($gnu_config["subscribe_comments"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="subscribe_commentsY">댓글도 알림</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="subscribe_commentsN" name="subscribe_comments" value="N" <?php if($gnu_config["subscribe_comments"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="subscribe_commentsN">새글만 알림</label>
						</div>
					</div>
					<?php
					$sql = " select * from {$g5['board_table']}";
					$result = sql_query($sql);
					unset($row);
					for ($i=0; $row=sql_fetch_array($result); $i++) {
					?>
					<div style="padding-top:10px;">
						<label>
							<input type="checkbox" value="<?php echo $row['bo_table'] ?>" name="subscribe_default_module_srls[]" <?php if(is_array($gnu_config['no_use_module_srls']) && in_array($row['bo_table'], $gnu_config["no_use_module_srls"])) { ?>disabled<?php }else if(is_array($gnu_config['subscribe_default_module_srls']) && in_array($row['bo_table'], $gnu_config["subscribe_default_module_srls"])) { ?>checked="checked"<?php } ?>><strong><?php echo get_text($row['bo_subject']) ?></strong> (<?php echo $row['gr_id'] ?>/<?php echo $row['bo_table'] ?>)
						</label>
					</div>
					<?php } ?>

				  </div>


				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>[구독게시판] 키워드</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="choose_board_keywordY" name="choose_board_keyword" value="Y" <?php if($gnu_config["choose_board_keyword"] == "Y") { ?>checked="checked" <?php } ?>/>
							<label for="choose_board_keywordY">사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="choose_board_keywordN" name="choose_board_keyword" value="N" <?php if($gnu_config["choose_board_keyword"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="choose_board_keywordN">사용안함</label>
						</div>
					</div>
				  </div>


				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>[구독게시판] 목록표시 제한</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="board_grantY" name="board_grant" value="Y" <?php if($gnu_config["board_grant"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="board_grantY">앱 사용자의 권한에 따라 표시 & 푸시제한</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="board_grantD" name="board_grant" value="D" <?php if($gnu_config["board_grant"] == "D") { ?>checked="checked" <?php } ?> />
							<label for="board_grantD">모두표시 & 푸시제한</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="board_grantN" name="board_grant" value="N" <?php if($gnu_config["board_grant"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="board_grantN">모두표시 & 모두푸시알림</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">앱설정창의 구독게시판 목록을 게시판 권한에 따라 보여주게 할 것인지 설정합니다. 그리고 권한에 따른 푸시알림 여부도 설정합니다. [... & 푸시제한]을 선택하셨다면 아래 설정에서 권한 기준을 적절히 설정해주셔야 합니다.</p>
				  </div>

				  <div class="form-group" style="margin-bottom:20px;">
				    <label><b>[구독게시판] 목록표시 권한기준</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="board_grant_clist" name="board_grant_c" value="bo_list_level" <?php if($gnu_config["board_grant_c"] == "bo_list_level") { ?>checked="checked" <?php } ?> />
							<label for="board_grant_clist">목록보기(접근)</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="board_grant_cread" name="board_grant_c" value="bo_read_level" <?php if($gnu_config["board_grant_c"] == "bo_read_level") { ?>checked="checked" <?php } ?> />
							<label for="board_grant_cread">글읽기</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="board_grant_cwrite" name="board_grant_c" value="bo_write_level" <?php if($gnu_config["board_grant_c"] == "bo_write_level") { ?>checked="checked" <?php } ?> />
							<label for="board_grant_cwrite">글쓰기</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="board_grant_ccomment" name="board_grant_c" value="bo_comment_level" <?php if($gnu_config["board_grant_c"] == "bo_comment_level") { ?>checked="checked" <?php } ?> />
							<label for="board_grant_ccomment">댓글쓰기</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">위의 [게시판 권한설정에 따른 알림 설정] 항목의 기준을 설정해주세요. 표시제한이나 푸시제한이 설정되어 있을 경우에만 이 설정이 의미가 있습니다. 접근이나 목록보기, 열람에 권한 제한이 있는 게시판의 경우 여기서 작성되는 새글과 새댓글을 앱을 설치한 모든 회원에게 알림하도록 할 것인지, 아니면 권한제한에 걸리지 않는 회원에게만 푸시 알림하도록 할 것인지에 관한 설정입니다. 아미나의 경우 목록보기(접근)을 설정하실 경우 게시판 설정에 있는 접근레벨과 접근등급을 최우선적으로 적용하며 해당 설정이 없을 경우 목록보기 등급제한으로 적용됩니다.</p>
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
				  <h3 class="box-title">영카트</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">



				  <div class="form-group" style="margin-bottom:25px;">
					<label for="youngcart_name"><b> [영카트] 이름</b></label>
					<div class="row">
						<div class="col-sm-3">
							<input type="text" class="form-control" id="youngcart_name"  name="youngcart_name" value="<?php echo $gnu_config['youngcart_name'] ?>">
						</div>
						<div class="col-sm-9"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">쇼핑몰의 title을 입력해주세요.</p>
				</div>


				<div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>[영카트] 구독 기능</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="youngcart_categoryY" name="youngcart_category" value="Y" <?php if($gnu_config["youngcart_category"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="youngcart_categoryY">사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="youngcart_categoryN" name="youngcart_category" value="N" <?php if($gnu_config["youngcart_category"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="youngcart_categoryN">사용안함</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">영카트에서 원하는 카테고리의 새상품을 알림받는 기능입니다.</p>
				  </div>



				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>[영카트] 구독 목록의 카테고리 펼침</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="youngcart_category_defaultN" name="youngcart_category_default" value="N" <?php if($gnu_config["youngcart_category_default"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="youngcart_category_defaultN">카테고리 접음</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="youngcart_category_defaultY1" name="youngcart_category_default" value="Y1" <?php if($gnu_config["youngcart_category_default"] == "Y1") { ?>checked="checked" <?php } ?> />
							<label for="youngcart_category_defaultY1">1단까지 펼침</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="youngcart_category_defaultY2" name="youngcart_category_default" value="Y2" <?php if($gnu_config["youngcart_category_default"] == "Y2") { ?>checked="checked" <?php } ?> />
							<label for="youngcart_category_defaultY2">2단까지 펼침</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="youngcart_category_defaultY3" name="youngcart_category_default" value="Y3" <?php if($gnu_config["youngcart_category_default"] == "Y3") { ?>checked="checked" <?php } ?> />
							<label for="youngcart_category_defaultY3">3단까지 펼침</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="youngcart_category_defaultY4" name="youngcart_category_default" value="Y4" <?php if($gnu_config["youngcart_category_default"] == "Y4") { ?>checked="checked" <?php } ?> />
							<label for="youngcart_category_defaultY4">4단까지 펼침</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="youngcart_category_defaultY5" name="youngcart_category_default" value="Y5" <?php if($gnu_config["youngcart_category_default"] == "Y5") { ?>checked="checked" <?php } ?> />
							<label for="youngcart_category_defaultY5">5단까지 펼침</label>
						</div>
					</div>
				  </div>


				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>[영카트] 구독 기능 - 설정 초기값</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="youngcart_category_booleanY" name="youngcart_category_boolean" value="Y" <?php if($gnu_config["youngcart_category_boolean"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="youngcart_category_booleanY">전체선택</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="youngcart_category_booleanN" name="youngcart_category_boolean" value="N" <?php if($gnu_config["youngcart_category_boolean"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="youngcart_category_booleanN">전체선택해제</label>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:20px;">
				    <label><b>[영카트] 키워드 기능</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="youngcart_keywordY" name="youngcart_keyword" value="Y" <?php if($gnu_config["youngcart_keyword"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="youngcart_keywordY">사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="youngcart_keywordN" name="youngcart_keyword" value="N" <?php if($gnu_config["youngcart_keyword"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="youngcart_keywordN">사용안함</label>
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

jQuery(function($){
	$("#choose_board_sD").click(function() {
		$("#chooseboarddefault").hide();
	});

	$("#choose_board_sY").click(function() {
		$("#chooseboarddefault").hide();
	});

	$("#choose_board_sN").click(function() {
		$("#chooseboarddefault").hide();
	});

	$("#choose_board_sF").click(function() {
		$("#chooseboarddefault").hide();
	});

	$("#choose_board_sF2").click(function() {
		$("#chooseboarddefault").hide();
	});

	$("#choose_board_sC").click(function() {
		$("#chooseboarddefault").show();
	});

	$("#choose_boardY").click(function() {
		$("#choose_board_sF").prop( "disabled", true );
		$("#choose_board_sF2").prop( "disabled", true );
	});

	$("#choose_boardN").click(function() {
		$("#choose_board_sF").prop( "disabled", false );
		$("#choose_board_sF2").prop( "disabled", false );
	});

	$("#choose_boardX").click(function() {
		$("#choose_board_sF").prop( "disabled", false );
		$("#choose_board_sF2").prop( "disabled", false );
	});
});

function srdpushon()
{
	var obj = document.getElementById("pushmsg");
	obj.value = "Y";
}

function srdpushoff()
{
	var obj = document.getElementById("pushmsg");
	obj.value = "N";
}

function bbb()
{
	var obj = document.getElementsByName("setting_f[]");
	var obj2 = document.getElementsByName("setting_a[]");

	obj[0].checked = false;
	obj[1].checked = false;
	obj2[0].checked = false;
	obj2[1].checked = false;
	obj[0].disabled = true;
	obj[1].disabled = true;
	obj2[0].disabled = true;
	obj2[1].disabled = true;
}

function ccc()
{
	var obj = document.getElementsByName("setting_f[]");
	var obj2 = document.getElementsByName("setting_a[]");
	var obj3 = document.getElementsByName("change_s");
	
	obj[0].disabled = false;
	obj[1].disabled = false;
	if(obj3[0].checked == true)
	{

	}
	else
	{
		obj2[0].disabled = false;
		obj2[1].disabled = false;
	}

}

function ddd()
{
	var obj2 = document.getElementsByName("setting_a[]");

	obj2[0].disabled = true;
	obj2[1].disabled = true;
	obj2[2].disabled = true;
	obj2[3].disabled = true;
	obj2[4].disabled = true;
	obj2[5].disabled = true;
	obj2[6].disabled = true;
	obj2[7].disabled = true;
}

function eee()
{
	var obj2 = document.getElementsByName("setting_a[]");

	obj2[0].disabled = false;
	obj2[1].disabled = false;
	obj2[2].disabled = false;
	obj2[3].disabled = false;
	obj2[4].disabled = false;
	obj2[5].disabled = false;
	obj2[6].disabled = true;
	obj2[7].disabled = true;
}

</script>



<?php 
include_once('footer.php');
?>
