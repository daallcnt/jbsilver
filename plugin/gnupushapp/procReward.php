<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$type = htmlspecialchars($_REQUEST['type']);
$amount = htmlspecialchars($_REQUEST['amount']);
$reward_secret = htmlspecialchars($_REQUEST['reward_secret']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 8, 29);

$response = "fail";
$message = "잘못된 요청입니다(오류코드 : aaaaaaa).";

if($str_mp == $masterpassword && $reward_secret_s = get_session('reward_secret') && $reg_id = get_session('reg_id'))
{
	if($gnu_config['rewardad_quantity'] != $amount || $gnu_config['rewardad_content'] != $type || $reward_secret_s != $reward_secret)
	{
		$response = "fail";
		$message = "잘못된 요청입니다(오류코드 : aaaaaa0).";
	}
	else
	{
		if($gnu_config['reward_type'] == 'point')
		{
			if($is_member)
			{
				$my_id = $_SESSION['ss_mb_id'];
				$row_info = get_device_info_by_regid($reg_id);
				if($row_info['gpr_mb_id'] == $my_id)
				{

					$row_tmp_secret = sql_fetch(" select * from g5_gnupushapp_reward where grr_regdate > date_format(now() , '%Y-%m-%d 00:00:00') and grr_secret = '{$reward_secret}' ");
					if($row_tmp_secret['grr_ix'])
					{
						$response = "fail";
						$message = "잘못된 요청입니다(오류코드 : fxfffff3).";
					}
					else
					{
						$go_reward = false;
						if($gnu_config['reward_limit'] != 0)
						{
							$row_tmp_reward = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_reward where grr_regdate > date_format(now() , '%Y-%m-%d 00:00:00') and grr_mb_id = '{$my_id}' ");
							
							if($row_tmp_reward['cnt'] <= $gnu_config['reward_limit'])
							{
								$go_reward = true;
							}
							else
							{
								$response = "fail";
								$message = "리워드 광고 1일 제한 횟수를 초과하였습니다(" . $gnu_config['reward_limit'] . "회).";
							}
						}
						else
						{
							$go_reward = true;
						}

						if($go_reward)
						{
							$str_point = substr($reward_secret, 2, 30);

							$po_rel_action = date('Y-m-d') . '리워드' . $str_point;
							insert_point($my_id, $gnu_config['reward_amount'], '리워드 광고 시청', '@pushapp', $my_id, $po_rel_action);
							sql_query(" INSERT INTO g5_gnupushapp_reward 
								set grr_reg_id = '{$reg_id}',
								grr_mb_id = '{$my_id}',
								grr_secret = '{$reward_secret}',
								grr_type = 'point',
								grr_regdate = '".G5_TIME_YMDHIS."',
								grr_amount = '{$gnu_config['reward_amount']}'
								", true);
							$response = "ok";
							$message = "포인트 " . $gnu_config['reward_amount'] . "이 지급되었습니다.";
							if($gnu_config['reward_limit'] != 0)
							{
								$message .= " (오늘 참여 횟수 : " . $row_tmp_reward['cnt'] . ")";
							}
						}
					}

				}
				else
				{
					$response = "fail";
					$message = "잘못된 요청입니다(오류코드 : gggfgg4).";
				}
			}
			else
			{
				$response = "fail";
				$message = "로그인이 필요합니다.";
			}
		}
		else
		{

		}

	}
}

$array = array("response" => $response, "message" => $message);

$json = "";
$json = json_encode($array);
header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();

?>