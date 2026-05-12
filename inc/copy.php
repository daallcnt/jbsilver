<div id="ft">
    <div>     
		<span class="copy_banner"><img src="<?php echo G5_IMG_URL ?>/banner_love.gif"></span>
		<span class="copy_link">
					<fieldset>
						<legend>관련사이트 링크</legend>
						<select name="siteLink" title="사이트선택" onchange="if(this.value){window.open(this.value, '_blank'); this.selectedIndex=0;}" style="height:24px;width:150px">
							<option value="">관련사이트</option>
							<option value="https://www.jeonbuk.go.kr/">전라북도청</option>
							<option value="https://www.jeonju.go.kr/">전주시청</option>
						</select>
					</fieldset>
		</span>
        <ul>
            <li><a href="javascript:menu11sub1();">이용약관</a></li>            
            <li><a href="javascript:menu11sub2();">개인정보처리방침</a></li>
			<li><a href="javascript:menu11sub3();">이메일무단수집거부</a></li>
			<li><a href="javascript:menu1sub7();">오시는 길</a></li>
			<li><a href="<?php echo G5_ADMIN_URL ?>">관리자</a></li>
			<li><a href="/sub/sub12_01.php">회원탈퇴</a></li>
        </ul>
        <p>[54981 ] 전라북도 전주시 완산구 백제대로 342 1층 전북노인일자리센터<br>
전화 : 063)255-9112, 9113 | 팩스 : 063)255-9114 | 메일 : 05jbsilver@hanmail.net<br>
Copyright (C) 2013 jbsilver.net All Rights Reseved.  
<?php
		if(G5_DEVICE_BUTTON_DISPLAY && !G5_IS_MOBILE) { ?>
		<a href="<?php echo get_device_change_url(); ?>" class="mobile_btn">모바일 버전</a>
		<?php
		}

		if ($config['cf_analytics']) {
			echo $config['cf_analytics'];
		}
		?></p>




    </div>


</div> 
