	<header id="hd">
		<h1 id="hd_h1"><?php echo $g5['title'] ?></h1>

		<div class="to_content"><a href="#container">본문 바로가기</a></div>

		<?php
		if(defined('_INDEX_')) { // index에서만 실행
			include G5_MOBILE_PATH.'/newwin.inc.php'; // 팝업레이어
		} ?>

		<div id="hd_wrapper">

			<div id="logo">
				<a href="<?php echo G5_URL ?>"><img src="<?php echo G5_IMG_URL ?>/mobile/logo_2025.png" alt="<?php echo $config['cf_title']; ?>"></a>
			</div>

			<button type="button" id="gnb_open" class="hd_opener"><span class="sound_only"> 메뉴열기</span></button>


			<div id="gnb" class="hd_div">
				<span style="position:absolute;top:10px;left:10px;"><img src="<?php echo G5_IMG_URL ?>/mobile/cate_logo.png""></span>
				<button type="button" id="gnb_close" class="hd_closer"><span class="sound_only">메뉴 </span>닫기</button>

				<ul id="gnb_1dul">
					<li class="gnb_1dli" style="background:#003b66;">
						<?php if ($is_member) {  ?>
						<a href="<?php echo G5_BBS_URL ?>/logout.php" style="float:left;margin:0 20px" ><i class="fa fa-unlock-alt"></i> <span>로그아웃</span></a><a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=<?php echo G5_BBS_URL ?>/register_form.php"><i class="fa fa-cog"></i> <span>정보변경</span></a>
						<?php } else {  ?>
						<a href="<?php echo G5_BBS_URL ?>/login.php?url=<?=$urlencode?>" style="float:left;margin:0 20px"><i class="fa fa-lock"></i> <span>로그인</span></a><a href="<?php echo G5_BBS_URL ?>/register.php"><i class="fa fa-user"></i> <span>회원가입</span></a>
						<?php }  ?>
					</li>
					<li class="gnb_1dli">
						<a href="javascript:menu1sub1();" class="gnb_1da">센터안내</a>
						<button type="button" class="btn_gnb_op">하위분류</button>
						<ul class="gnb_2dul">
							<li class="gnb_2dli"><a href="javascript:menu1sub1();" class="gnb_2da">인사말</a></li>
							<li class="gnb_2dli"><a href="javascript:menu1sub2();" class="gnb_2da">미션/비전</a></li>
							<li class="gnb_2dli"><a href="javascript:menu1sub3();" class="gnb_2da">연혁</a></li>
							<li class="gnb_2dli"><a href="javascript:menu1sub4();" class="gnb_2da">조직도</a></li>
							<li class="gnb_2dli"><a href="javascript:menu1sub7();" class="gnb_2da">찾아오시는길</a></li>
						</ul>
					</li>
					<li class="gnb_1dli">
						<a href="javascript:menu2sub3();" class="gnb_1da">사업안내</a>
						<button type="button" class="btn_gnb_op">하위분류</button>
						<ul class="gnb_2dul">
							<li class="gnb_2dli"><a href="javascript:menu2sub3();" class="gnb_2da">노인일자리확충을 위한 네트워크사업</a></li>
							<li class="gnb_2dli"><a href="javascript:menu2sub2();" class="gnb_2da">노인일자리 양성 및 맞춤형 교육사업</a></li>
							<li class="gnb_2dli"><a href="javascript:menu2sub4();" class="gnb_2da">지역복지사업</a></li>
							<li class="gnb_2dli"><a href="javascript:menuPreparing();" class="gnb_2da">외부공모사업 (연결 준비중)</a></li>
						</ul>
					</li>
					<li class="gnb_1dli">
						<a href="javascript:menu10sub1();" class="gnb_1da">교육안내</a>
						<button type="button" class="btn_gnb_op">하위분류</button>
						<ul class="gnb_2dul">
							<li class="gnb_2dli"><a href="javascript:menu10sub1();" class="gnb_2da">교육신청</a></li>
							<li class="gnb_2dli"><a href="javascript:menu10sub4();" class="gnb_2da">수료증발급</a></li>
						</ul>
					</li>
					<li class="gnb_1dli">
						<a href="javascript:menu4sub0();" class="gnb_1da">일자리안내</a>
						<button type="button" class="btn_gnb_op">하위분류</button>
						<ul class="gnb_2dul">
							<li class="gnb_2dli"><a href="javascript:menu4sub0();" class="gnb_2da">노인일자리소개</a></li>
							<li class="gnb_2dli"><a href="javascript:menu4sub2();" class="gnb_2da">구직서비스</a></li>
							<li class="gnb_2dli"><a href="javascript:menu4sub1();" class="gnb_2da">구인서비스</a></li>
						</ul>
					</li>
					<li class="gnb_1dli">
						<a href="javascript:menu4sub3();" class="gnb_1da">질문안내</a>
						<button type="button" class="btn_gnb_op">하위분류</button>
						<ul class="gnb_2dul">
							<li class="gnb_2dli"><a href="javascript:menu4sub3();" class="gnb_2da">무엇이든 물어보세요(Q&amp;A)</a></li>
						</ul>
					</li>
					<li class="gnb_1dli">
						<a href="javascript:menu6sub1();" class="gnb_1da">정보안내</a>
						<button type="button" class="btn_gnb_op">하위분류</button>
						<ul class="gnb_2dul">
							<li class="gnb_2dli"><a href="javascript:menu6sub1();" class="gnb_2da">공지사항</a></li>
							<li class="gnb_2dli"><a href="javascript:menu6sub3();" class="gnb_2da">월별일정</a></li>
							<li class="gnb_2dli"><a href="javascript:menu6sub4();" class="gnb_2da">보도자료</a></li>
							<li class="gnb_2dli"><a href="javascript:menu7sub1();" class="gnb_2da">자료실</a></li>
							<li class="gnb_2dli"><a href="javascript:menu6sub5();" class="gnb_2da">보도갤러리</a></li>
							<li class="gnb_2dli"><a href="javascript:menu6sub7();" class="gnb_2da">뉴스레터</a></li>
							<li class="gnb_2dli"><a href="javascript:menu9sub1();" class="gnb_2da">시니어스토어&amp;노인생산품</a></li>
						</ul>
					</li>


				</ul>
				<div style="position:absolute;right:10px; bottom:10px;"><a href="/sub/sub12_01.php" style="color:#ffffff; background:#186ab6;font-size:13px;padding:5px;">회원탈퇴</a></div>
			</div>



			<div id="user_btn"><a href="#" onclick="openlayer('layer1');"><img src="<?php echo G5_IMG_URL ?>/mobile/btn_search.png"></a></div>

			


			<script language='javascript' type='text/javascript'> 
			//<![CDATA[
			function openlayer(nm)
			{ 
				var obj = document.getElementById(nm); 
				obj.style.display = 'block'; 
			}
			 
			function closelayer(nm) 
			{ 
				var obj = document.getElementById(nm); 
				obj.style.display = 'none'; 
			}
			 
			//]]> 
			</script>



		<div id="layer1" style="width:100%;top:60px;position:absolute; z-index:9999; display:none;background:rgba(0, 0, 0, 0.7);min-height:55px;text-align:center;">
				<span style="position:absolute;top:-42px;right:5px;z-index:9999;"><a href="#" onclick="closelayer('layer1');"><img src="<?php echo G5_IMG_URL ?>/mobile/btn_search.png"></a></span>		
					<div id="hd_sch">
						<h2>사이트 내 전체검색</h2>
						<form name="fsearchbox" action="<?php echo G5_BBS_URL ?>/search.php" onsubmit="return fsearchbox_submit(this);" method="get">
						<input type="hidden" name="sfl" value="wr_subject||wr_content">
						<input type="hidden" name="sop" value="and">
						<input type="text" name="stx" id="sch_stx" placeholder="검색어(필수)" required maxlength="20">
						<button type="submit" value="검색" id="sch_submit"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색</span></button>
						</form>

						<script>
						function fsearchbox_submit(f)
						{
							if (f.stx.value.length < 2) {
								alert("검색어는 두글자 이상 입력하십시오.");
								f.stx.select();
								f.stx.focus();
								return false;
							}

							// 검색에 많은 부하가 걸리는 경우 이 주석을 제거하세요.
							var cnt = 0;
							for (var i=0; i<f.stx.value.length; i++) {
								if (f.stx.value.charAt(i) == ' ')
									cnt++;
							}

							if (cnt > 1) {
								alert("빠른 검색을 위하여 검색어에 공백은 한개만 입력할 수 있습니다.");
								f.stx.select();
								f.stx.focus();
								return false;
							}

							return true;
						}
						</script>
					</div>

		</div>










			<script>
			jQuery(function ($) {
				//폰트 크기 조정 위치 지정
				var font_resize_class = get_cookie("ck_font_resize_add_class");
				if( font_resize_class == 'ts_up' ){
					$("#text_size button").removeClass("select");
					$("#size_def").addClass("select");
				} else if (font_resize_class == 'ts_up2') {
					$("#text_size button").removeClass("select");
					$("#size_up").addClass("select");
				}

				$(".hd_opener").on("click", function(e) {
					var $this = $(this);
					var $hd_layer = $this.next(".hd_div");

					if($hd_layer.is(":visible")) {
						$hd_layer.hide();
						$this.find("span").text("열기");
					} else {
						var $hd_layer2 = $(".hd_div:visible");
						$hd_layer2.prev(".hd_opener").find("span").text("열기");
						$hd_layer2.hide();

						$hd_layer.show();
						$this.find("span").text("닫기");
					}
				});

				$("#container").on("click", function(e) {
					$(".hd_div").hide();
				}).on("click_font_resize", function(e) {

					var $this = $(this),
						$text_size_button = $("#text_size button");
					
					$text_size_button.removeClass("select");

					if( $this.hasClass("ts_up") ){
						$text_size_button.eq(1).addClass("select");
					} else if ( $this.hasClass("ts_up2") ) {
						$text_size_button.eq(2).addClass("select");
					} else {
						$text_size_button.eq(0).addClass("select");
					}
				});

				$(".btn_gnb_op").click(function(e){
					$(this).toggleClass("btn_gnb_cl").next(".gnb_2dul").slideToggle(300);
					
				});

				$(".hd_closer").on("click", function(e) {
					var idx = $(".hd_closer").index($(this));
					$(".hd_div:visible").hide();
					$(".hd_opener:eq("+idx+")").find("span").text("열기");
				});
			});
			</script>
			
		</div>
	</header>
