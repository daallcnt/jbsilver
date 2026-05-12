<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);
$chat_page = htmlspecialchars($_REQUEST['page']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

if($gnu_config['login_session'] == 'Y'){
	$_SESSION['reg_id'] = htmlspecialchars($_REQUEST['reg_id']);
	$_SESSION['ss_mb_id'] = null;
	$is_member = false;
	$device_in_sub = get_device_info_by_regid($_SESSION['reg_id']);
	if($device_in_sub['gpr_sync'] == "Y" || $device_in_sub['gpr_sync'] == "S" || $device_in_sub['gpr_sync'] == "D"){
		$_SESSION['ss_mb_id'] = $device_in_sub['gpr_mb_id'];
		$is_member = true;
	}
}

$array = array();
$my_id = "none";

if($str_mp == $masterpassword)
{
    $device_in = get_device_info_by_regid($_SESSION['reg_id']);
    
    //비회원일 경우 임의로 만든 아이디를 부여하기 위해 미리 준비함.
	$temp_my_id = substr($_SESSION['reg_id'], 30, 18);

	if($is_member){
		$my_id = $_SESSION['ss_mb_id'];
	}else{

        if($gnu_config['chatting_nonmembers'] == "Y"){
            $my_id = $temp_my_id;
        }else{
            //로그인 사용자만 자신만의 채팅목록을 가지고 있으므로 접속실패로 간주...

            $array["response"] = "fail";
            $json = json_encode($array);

            header('Content-Type: application/json; charset=utf-8');
            header('Content-Length: ' . mb_strlen($json));
            echo $json;
            exit();
        }
        
        
    }
    
    //내가 확인안한 채팅 목록 -> 방목록 data 에 먼저 넣음.
    //데이터 조회후 시간순으로 차례로 넣음.

    $array = array();
	$array_data = array();

    $array_my_room_list = array();
    $array_my_room_notread_count = array();

    $count = 0;
    $total_count = 0;
	$have_more = "false";

    $row_result = sql_query(" SELECT * from g5_gnupushapp_newchatting_content_readlist where ( reg_id = '{$_SESSION['reg_id']}' or mb_id = '$my_id') and is_readed = 'N' ");
    for ($i=0; $row_tmp=sql_fetch_array($row_result); $i++)
	{
        if(is_array($array_my_room_list) && !in_array($row_tmp['cr_ix'],$array_my_room_list)){
            $total_count++;
            if($chat_page != 0){
                if($total_count > (($chat_page - 1) * $gnu_config['chatting_page_num']) && $total_count <= ($chat_page * $gnu_config['chatting_page_num']))
                {
                    array_push($array_my_room_list,$row_tmp['cr_ix']);
                    $array_my_room_notread_count[$row_tmp['cr_ix']] = 1;
                }else{
                    if($total_count > ($chat_page * $gnu_config['chatting_page_num']))
                    {
                        $have_more = "true";
                    }
                    if($total_count <= (($chat_page - 1) * $gnu_config['chatting_page_num']))
                    {
                        
                    }
                    else
                    {
                        break;
                    }
                }
            }else{
                array_push($array_my_room_list,$row_tmp['cr_ix']);
                $array_my_room_notread_count[$row_tmp['cr_ix']] = 1;
            }
            
        }else{
            //count ++
            $array_my_room_notread_count[$row_tmp['cr_ix']]++;
        }
    }

    $array_data = get_chat_list($my_id, $array_data, $array_my_room_list, $array_my_room_notread_count);

    if($have_more == "true")
    {
        foreach($array_data as $data)
        {
            $strcount = $count;
            $array["chatroom".$strcount] = urlencode(json_encode($data));
            $count++;
        }
    }
    else
    {

        $remain_my_room_list = array();

        //나머지 전체 채팅 목록 가져오기
        $row_result = sql_query("SELECT * from g5_gnupushapp_newchatting_room_joinlist where mb_id = '$my_id' and (c_status = 'join' or c_status = 'nowjoin') order by regdate desc");
        for ($i=0; $row_tmp=sql_fetch_array($row_result); $i++)
        {
            if(is_array($array_my_room_list) && in_array($row_tmp['cr_ix'],$array_my_room_list)) continue;

            $total_count++;

            if($chat_page != 0){

                if($total_count > (($chat_page - 1) * $gnu_config['chatting_page_num']) && $total_count <= ($gnu_config['chatting_page_num']))
                {
                
                    array_push($array_my_room_list,$row_tmp['cr_ix']);

                    array_push($remain_my_room_list,$row_tmp['cr_ix']);

                    $array_my_room_notread_count[$row_tmp['cr_ix']] = 0;
                }
                else
                {
                    if($total_count > ($chat_page * $gnu_config['chatting_page_num']))
                    {
                        $have_more = "true";
                    }
                    if($total_count <= (($chat_page - 1) * $gnu_config['chatting_page_num']))
                    {
                        
                    }
                    else
                    {
                        break;
                    }

                }
            }else{
                array_push($array_my_room_list,$row_tmp['cr_ix']);

                array_push($remain_my_room_list,$row_tmp['cr_ix']);

                $array_my_room_notread_count[$row_tmp['cr_ix']] = 0;
            }

        }

        $array_data = get_chat_list($my_id, $array_data, $remain_my_room_list, $array_my_room_notread_count);

        foreach($array_data as $data)
        {
            $strcount = $count;
            $array["chatroom".$strcount] = urlencode(json_encode($data));
            $count++;
        }
        
    }

    $array["havemore"] = $have_more;	
    $array["count"] = $count;
    $array["response"] = "ok";

    $json = json_encode($array);

	header('Content-Type: application/json; charset=utf-8');
	header('Content-Length: ' . mb_strlen($json));
	echo $json;
	exit();
}
else
{
	$array["response"] = "fail";
	$json = json_encode($array);

	header('Content-Type: application/json; charset=utf-8');
	header('Content-Length: ' . mb_strlen($json));
	echo $json;
	exit();
}


?>