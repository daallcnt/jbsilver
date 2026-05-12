/*************************************************************************************************************

그누보드 알림 서비스 Plugin
plugin name	: srd-pushmsg
var		: ver 0.2
개발자		: Rido
개발일		: 2015 05 29
최종수정일	: 2016 11 26
mail		: salrido@korea.com
homepage	: http://salrido.kr
그누보드 id 	: rido
저작권		: 그누보드5의 저작권을 이어 받습니다
				기본적인 style과 css, html등은 그누보드5사이트의 것을 가지고 작업했습니다.
				front 작업은 그누보드5의 소스 90% 이상을 사용하고 있습니다.
				수정 및 사용에 제한을 두진 않고 있습니다.
				재배포시 개발자에세 메일한통 쏴주시는 센스 부탁드립니다.

*************************************************************************************************************/

ver 0.2 수정사항 
	-- 그누보드 최신버전에서 디비생성이 되지 않는 버그수정
	-- <? 단축코드 사용으로 생기던 오류수정 <?php 로 수정됨 
	-- 알림시점이 사용자의 시점에서 알림이 발생한 시점으로 변경 
		(기존의 경우 사용자가 로그인 한 시점이나 페이지 이벤트가 있는경우의 시간으로 책정되었음)
	-- 필수 테이블 생성시 기존 g5_ 를 기본 접두사로 사용하였으나, 
		install시 사용한 접두사를 가지고 오는것으로 변경됨.



플러그인 파일정보 ::::
/expend/srd.pushmsg.php
	: 해당 프로그램에서 필요한 함수와 변수를 정의
/plugin/srd-pushmsg/
	/images									-> 해당 플러그인의 이미지
	/_common.php						-> 그누보드의 common.php를 호출
	/ajax.read_pushmsg.php		-> 비동기형태로 알림을 읽음처리
	/pushmsg_delete.php				-> 알림서비스 삭제/읽음 처리 
	/readme.txt								-> 본 help페이지
	----- 이상 back-end

	/ajax.list_pushmsg.php			-> 비동기형태로 짤막한 리스트를 호출
	/index.php								-> 해당 플러그인의 시작페이지
	/pushmsg_view.php				-> 알림 navibar형태로 사용시 include되는 페이지 
	/style.css									-> 해당 플러그인에서 필요로 하는 스타일 (현재 그누보드5의 그것의 형태)
	----- 이상 front-end (front개발자 분들은 이 4가지 파일만 수정하시면 됩니다.)


해당 알림서비스는 아래 파일에서 알림 자동삭제 기능을 제공하고 있습니다.
/expend/srd.pushmsg.php
	$del_day = 60 ; 		// 0 일경우 알림을 자동삭제 하지 않음 ex)60 으로 설정시 60일 이상된 알림은 자동삭제	
	
	
알림최신글 호출법
<?php include_once(G5_PATH.'/plugin/srd-pushmsg/pushmsg_view.php'); ?> 


알림글 리스트 페이지
/plugin/srd-pushmsg/index.php		


플러그인에 필요한 추가 테이블
	CREATE TABLE IF NOT EXISTS `g5_srd_pushmsg` (
		`msg_id` int(11) NOT NULL auto_increment,
		//idx
		`msg_check` set('n','y','d')  NOT NULL default 'd',
		//n : 읽지않음 , y : 읽음 , d : 알림을 마지막으로 체크
		`mb_id` varchar(20) NOT NULL default '',
		//회원아이디
		`mbto_id` varchar(20) NOT NULL default '',
		//댓글이나 메모를 남긴 회원의 아이디 그누보드  thisgun님의 의견
		`msg_subject` varchar(255) NOT NULL default '',
		//알림제목
		`msg_link` varchar(255) NOT NULL default '',
		//알림링크
		`msg_type` varchar(100) NOT NULL default '',			
		//알림의 타입 (메모,게시판,등등..)
		`msg_date` date NOT NULL DEFAULT '0000-00-00',
		//알림을 읽은 날짜
		`msg_wdate` datetime NOT NULL default '0000-00-00 00:00:00',			
		//알림을 생성한 날짜
		PRIMARY KEY  (`msg_id`),
		KEY `mb_id` (`mb_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;	
	
	
차후 업그레이드 관련
	현재로는 업그레이드 관련은 생각이 없으며, 버그수정은 계획이 있습니다.
	업그레이드시는 현재의 버전과 호환이 되지 않을 수도 있습니다. 