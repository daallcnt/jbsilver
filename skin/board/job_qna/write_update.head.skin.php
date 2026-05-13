<?php
if (!defined('_GNUBOARD_')) exit;

// 무엇이든 물어보세요 QnA는 개인정보가 포함될 수 있어 모든 글을 비밀글로 저장합니다.
$_POST['secret'] = 'secret';
