<?php 
include_once('header.php');
?>

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
	  <form role="form" name="frm" id="frm" action="config_update.php" method="post" enctype="multipart/form-data">
	  <input type="hidden" name="config_sort" value="push" />
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          푸시동작설정
          <small>환경설정</small>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="index.php">
              <i class="fa fa-dashboard"></i> Home</a>
          </li>
          <li class="active">환경설정</li>
		  <li class="active">푸시동작설정</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-12">
            <div class="box box-danger box-solid">
				<div class="box-header with-border">
				  <h3 class="box-title">푸시동작</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">

				<div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>푸시 동작</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="useY" name="use" value="Y" <?php if($gnu_config["use"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="useY">동작</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="useN" name="use" value="N" <?php if($gnu_config["use"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="useN">동작안함</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">'동작안함'을 선택하시면 모든 푸시알림이 동작하지 않습니다.</p>
				  </div>

				<div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>푸시 동작 항목 설정</b></label>
					<div>
						<?php if (defined('G5_USE_SHOP') && G5_USE_SHOP) { ?>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="push_settingsyoungcart" value="youngcart" name="push_settings[]" <?php if($gnu_config["use_youngcart"] == "Y") { ?>checked="checked" <?php } ?>/>
							<label for="push_settingsyoungcart">영카트</label>
						</div>
						<?php } ?>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="push_settingsdocument" value="document" name="push_settings[]" <?php if($gnu_config["use_d"] == "Y") { ?>checked="checked" <?php } ?>/>
							<label for="push_settingsdocument">새글</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="push_settingscomment" value="comment" name="push_settings[]" <?php if($gnu_config["use_c"] == "Y") { ?>checked="checked" <?php } ?>/>
							<label for="push_settingscomment">댓글</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="push_settingsmessage" value="message" name="push_settings[]" <?php if($gnu_config["use_m"] == "Y") { ?>checked="checked" <?php } ?>/>
							<label for="push_settingsmessage">쪽지</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="push_settingsmention" disabled value="mention" name="push_settings[]"/>
							<label for="push_settingsmention">맨션</label>
						</div>
						<div class="checkbox-inline icheck-primary">
							<input type="checkbox" id="push_settingsrecommand" disabled value="recommand" name="push_settings[]"/>
							<label for="push_settingsrecommand">추천</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">맨션과 추천은 현재 지원되지 않습니다.</p>
				  </div>

				  <?php if (defined('G5_USE_SHOP') && G5_USE_SHOP) { ?>
					<input type="hidden" name="use_youngcart" id="use_youngcart" value="<?php echo $gnu_config['use_youngcart'] ?>" />
					<?php }else{ ?>
					<input type="hidden" name="use_youngcart" id="use_youngcart" value="N" />
					<?php } ?>
					<input type="hidden" name="use_d" id="use_d" value="<?php echo $gnu_config['use_d'] ?>" />
					<input type="hidden" name="use_c" id="use_c" value="<?php echo $gnu_config['use_c'] ?>" />
					<input type="hidden" name="use_m" id="use_m" value="<?php echo $gnu_config['use_m'] ?>" />
					<input type="hidden" name="use_mention" id="use_mention" value="N" />
					<input type="hidden" name="use_v" id="use_v" value="N" />
					
								
				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>푸시비동기방식</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="push_mY" name="push_m" value="Y" <?php if($gnu_config["push_m"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="push_mY">소켓방식</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="push_mN" name="push_m" value="N" <?php if($gnu_config["push_m"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="push_mN">ajax방식</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="push_mS" name="push_m" value="S" <?php if($gnu_config["push_m"] == "S") { ?>checked="checked" <?php } ?> />
							<label for="push_mS">동기방식</label>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>푸시curl처리방식</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="curl_waynormal" name="curl_way" value="normal" <?php if($gnu_config["curl_way"] == "normal") { ?>checked="checked" <?php } ?> />
							<label for="curl_waynormal">기본 curl방식</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="curl_waynormal2" name="curl_way" value="normal2" <?php if($gnu_config["curl_way"] == "normal2") { ?>checked="checked" <?php } ?> />
							<label for="curl_waynormal2">기본 curl방식2</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="curl_waymulti" name="curl_way" value="multi" <?php if($gnu_config["curl_way"] == "multi") { ?>checked="checked" <?php } ?> />
							<label for="curl_waymulti">curl_multi 사용</label>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label for="push_delay"><b>소켓방식 딜레이값</b></label>
					<div class="row">
						<div class="col-sm-2">
							<input type="text" class="form-control" id="push_delay"  name="push_delay" value="<?php echo $gnu_config['push_delay'] ?>">
						</div>
						<div class="col-sm-10"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">등록기기목록에서 개별푸시알림은 잘 되는데, 그 외에 푸시알림이 잘 안될 때는 서버 DB속도가 느려서 그런 것이니, 딜레이시간을 1-2초 정도 늘려주면 제대로 동작하게 됩니다. 먼저 딜레이를 1초 입력해서 테스트해보시고 그래도 안되면 2초 입력해서 테스트해주세요. 주의할 점은 모든 글,댓글작성시 작성완료시간이 이 시간만큼 딜레이되기 때문에 너무 많이 늘려주시면 안 됩니다. 딜레이시간을 늘리고 싶지 않으신 경우에는 ajax방식을 사용해주시기 바랍니다.</p>
				</div>

				<div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>마케팅 푸시 알림</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="marketing_pushY" name="marketing_push" value="Y" <?php if($gnu_config["marketing_push"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="marketing_pushY">사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="marketing_pushN" name="marketing_push" value="N" <?php if($gnu_config["marketing_push"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="marketing_pushN">사용안함</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">영리목적의 푸시알림을 날리고자 한다면, 반드시 이 기능을 사용해주세요(<a href="http://helloworld.fingerpush.com/%EC%A0%95%EB%B3%B4%ED%86%B5%EC%8B%A0%EB%A7%9D%EB%B2%95-%EC%A4%80%EC%88%98%EB%A5%BC-%EC%9C%84%ED%95%9C-%EC%95%B1-%ED%91%B8%EC%8B%9C-%EA%B4%91%EA%B3%A0-%EA%B0%80%EC%9D%B4%EB%93%9C%EB%9D%BC%EC%9D%B8-2/" target="_blank">관련사항보기</a> 이 기능을 사용하면 앱 첫실행시 마케팅 푸시 알림 동의 여부를 물어보고, 설정 여부에 따라 마케팅 푸시알림을 날리게 됩니다. 마케팅 관련 푸시는 GNU푸시앱 관리자 페이지의 등록기기목록(개별푸시알림)과 그룹별 일괄 푸시알림 페이지에서 보낼 수 있습니다.</p>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
					<label for="push_div_num"><b>푸시 처리시 등록기기 분할수 설정</b></label>
					<div class="row">
						<div class="col-sm-2">
							<input type="text" class="form-control" id="push_div_num"  name="push_div_num" value="<?php echo $gnu_config['push_div_num'] ?>">
						</div>
						<div class="col-sm-10"></div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">등록기기가 수천 또는 수만이 넘어갈 경우 푸시 처리과정에서 서버성능에 따라 오류가 발생할 수 있기 때문에 설정된 수로 나누어서 푸시 알림 보냅니다. 기본값은 3000이며, 서버 상황에 따라 조절해주시면 됩니다.</p>
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
				  <h3 class="box-title">푸시스타일(안드로이드만 해당)</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				  </div>
				  <!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>푸시 스타일 설정</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="push_styleY" name="push_style" value="Y" <?php if($gnu_config["push_style"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="push_styleY">big_picture, big_text 스타일 사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="push_styleN" name="push_style" value="N" <?php if($gnu_config["push_style"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="push_styleN">normal 스타일만 사용</label>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>푸시 알림 아이콘 설정</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="profile_pY" name="profile_p" value="Y" <?php if($gnu_config["profile_p"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="profile_pY">글 작성자의 프로필 사진 표시</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="profile_pN" name="profile_p" value="N" <?php if($gnu_config["profile_p"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="profile_pN">앱 아이콘 표시</label>
						</div>
					</div>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>big_picture 스타일 설정</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="push_style_bpY" name="push_style_bp" value="Y" <?php if($gnu_config["push_style_bp"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="push_style_bpY">이미지 하단 (작성자/게시판명) 표시되는 addaction사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="push_style_bpN" name="push_style_bp" value="N" <?php if($gnu_config["push_style_bp"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="push_style_bpN">이미지만 나오도록 함</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">bic_picture스타일의 경우 이미지 하단에 addaction이 나오도록 할 것인지 여부를 설정합니다. addaction사용설정하시면 이미지 하단부분이 가려지지만, 각종 정보(작성자, 게시판명, 영카트의 경우 가격과 분류)가 표시됩니다. 이미지만 나오도록 하면 각종 정보를 볼 수 없는 대신 이미지가 가려지지 않게 됩니다.</p>
				  </div>


				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>푸시알림 중첩 표시 여부</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="push_duplicationY" name="push_duplication" value="Y" <?php if($gnu_config["push_duplication"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="push_duplicationY">푸시알림 중첩</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="push_duplicationN" name="push_duplication" value="N" <?php if($gnu_config["push_duplication"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="push_duplicationN">하나의 푸시 알림만 표시</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">여러개의 알림이 푸시되었을 때, 알림목록창에 하나만 표시되도록 할 것인지(이전 알림은 사라짐), 여러개의 알림 모두를 표시하도록 할 것인지 여부를 설정합니다.</p>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>Wake Lock 사용</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="wake_lockY" name="wake_lock" value="Y" <?php if($gnu_config["wake_lock"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="wake_lockY">사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="wake_lockN" name="wake_lock" value="N" <?php if($gnu_config["wake_lock"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="wake_lockN">사용안함</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">오레오 이전 버전만 해당되며, Wake Lock 사용하시면 화면이 꺼져 있어도 푸시알림시 알림이 표시되면서 화면이 켜집니다. 호불호가 갈리는 부분이기 때문에 '사용'으로 설정하셔도 앱 사용자가 개별적으로 다르게 설정할 수 있으며, 앱사용자의 설정에 따라 작동합니다.</p>
				  </div>



				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>Heads-Up 푸시 기능 사용</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="headsup_push_styleY" name="headsup_push_style" value="Y" <?php if($gnu_config["headsup_push_style"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="headsup_push_styleY">사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="headsup_push_styleN" name="headsup_push_style" value="N" <?php if($gnu_config["headsup_push_style"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="headsup_push_styleN">사용안함</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">푸시 알림시 카카오톡처럼 상단에 슬라이드 배너로 푸시알림이 표시됩니다. 안드로이드 롤리팝 이상버전에서만 지원하며, 사용안함을 선택하실 경우 상태바에만 ticker표시됩니다. 호불호가 갈리는 부분이기 때문에 '사용'으로 설정하셔도 앱 사용자가 개별적으로 다르게 설정할 수 있으며, 앱사용자의 설정에 따라 작동합니다.</p>
				  </div>


				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>Heads-Up 푸시 게시판</b></label>
					<p class="help-block">[Heads-Up 푸시 기능]을 사용하신 경우에만 적용됩니다.</p>
					<?php
					$sql = " select * from {$g5['board_table']}";
					$result = sql_query($sql);
					for ($i=0; $row=sql_fetch_array($result); $i++) {
					?>
					<div style="padding-top:10px;">
						<label>
							<input type="checkbox" value="<?php echo $row['bo_table'] ?>" name="headsup_module_srls[]" <?php if(is_array($gnu_config['no_use_module_srls']) && in_array($row['bo_table'], $gnu_config["no_use_module_srls"])) { ?>disabled<?php }else if(is_array($gnu_config['headsup_module_srls']) && in_array($row['bo_table'], $gnu_config["headsup_module_srls"])) { ?>checked="checked"<?php } ?>><strong><?php echo get_text($row['bo_subject']) ?></strong> (<?php echo $row['gr_id'] ?>/<?php echo $row['bo_table'] ?>)
						</label>
					</div>
					<?php } ?>
				  </div>

				  <div class="form-group" style="margin-bottom:25px;padding-bottom:15px;border-bottom: 1px solid #c6c6c6;">
				    <label><b>팝업 푸시 사용 여부</b></label>
					<div>
						<div class="radio icheck-primary">
							<input type="radio" id="popup_push_styleY" name="popup_push_style" value="Y" <?php if($gnu_config["popup_push_style"] == "Y") { ?>checked="checked" <?php } ?> />
							<label for="popup_push_styleY">사용</label>
						</div>
						<div class="radio icheck-primary">
							<input type="radio" id="popup_push_styleN" name="popup_push_style" value="N" <?php if($gnu_config["popup_push_style"] == "N") { ?>checked="checked" <?php } ?> />
							<label for="popup_push_styleN">사용안함</label>
						</div>
					</div>
					<p class="help-block" style="color:#c6c6c6;font-size:9pt;">팝업으로 알림하는 기능입니다. 호불호가 갈리는 부분이기 때문에 '사용'으로 설정하셔도 앱 사용자가 개별적으로 다르게 설정할 수 있으며, 앱사용자의 설정에 따라 작동합니다. 보통 극혐(?)의 기능으로 간주되기 때문에 꼭 필요한 경우에만 사용해주실 것을 당부드립니다.</p>
				  </div>


				  <div class="form-group" style="margin-bottom:20px;">
				    <label><b>팝업 푸시 게시판</b></label>
					<p class="help-block">[팝업 푸시 기능]을 사용하신 경우에만 적용됩니다.</p>
					<?php
					$sql = " select * from {$g5['board_table']}";
					$result = sql_query($sql);
					for ($i=0; $row=sql_fetch_array($result); $i++) {
					?>
					<div style="padding-top:10px;">
						<label>
							<input type="checkbox" value="<?php echo $row['bo_table'] ?>" name="popup_module_srls[]" <?php if(is_array($gnu_config['no_use_module_srls']) && in_array($row['bo_table'], $gnu_config["no_use_module_srls"])) { ?>disabled<?php }else if(is_array($gnu_config['popup_module_srls']) && in_array($row['bo_table'], $gnu_config["popup_module_srls"])) { ?>checked="checked"<?php } ?>><strong><?php echo get_text($row['bo_subject']) ?></strong> (<?php echo $row['gr_id'] ?>/<?php echo $row['bo_table'] ?>)
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
