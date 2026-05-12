<?php
include_once('../common.php');


if (isset($s_id)) $qstr .= "&s_id=$s_id";

		if(!$w && $id) {
		        $wr_password = $member['mb_password'];
		}else{
		        $wr_password = get_encrypt_string($pass);
		}


    if ($pass)
        $sql_password = " , pass = '".$wr_password."' ";
    else
        $sql_password = "";

$sql_common = " s_id       = '$_POST[s_id]',
				id         = '$_POST[id]',
                name       = '$_POST[name]',
                mobile     = '$_POST[mobile]',
                subject    = '$_POST[subject]',
				schedule   = '$_POST[schedule]',
				edate   = '$_POST[edate]',
                person     = '$_POST[person]',
                corp       = '$_POST[corp]',
				etc       = '$_POST[etc]',
                phone      = '$_POST[phone]' ";


if ($w == "")
{
	

	$receive_number = preg_replace("/[^0-9]/", "", $mobile);
    $sql = " select s_id from wp_edu where s_id = '$s_id' and name = '$name' and replace(mobile,'-','') = '$receive_number'  ";
    $row = sql_fetch($sql);
    if ($row['s_id'])
        alert("이미 해당 교육을 신청하셨습니다.");
	
	$sqls = " select * from wp_education where s_id = '$s_id' ";
    $rows = sql_fetch($sqls);	

	$r = sql_fetch(" select sum(person) as tot from wp_edu where s_id = '$rows[s_id]'  "); 
		
			$SumPerson = ($r['tot'])? $r['tot'] : "0" ;
		 	$PersonWating =  $rows['person'] + $rows['wating'];
				if($SumPerson < $rows['person']){
						$progress = "접수";
				}else{
						$progress = "접수";
				}

	if($member['mb_id']){
		$edu = "회원";
	}else{
		$edu = "비회원";
	}


    $sql = " insert wp_edu
                set wdate = '".G5_TIME_YMDHIS."',
					progress = '".$progress."',
					edu = '".$edu."',
					payment = '미납',
                    $sql_common
					$sql_password  ";
    sql_query($sql);
	
	$e_id = sql_insert_id();
}
else if ($w == "u")
{	
	if($_POST['progress']) { $sql_common .= ", progress = '$_POST[progress]' "; } 
	
    $sql = " update wp_edu 
                set mdate = '".G5_TIME_YMDHIS."',				
					$sql_common
					$sql_password 
              where e_id = '$_POST[e_id]' ";
    sql_query($sql);
}
else
    alert("제대로 된 값이 넘어오지 않았습니다.");


if($w == "u") {
alert("수정되었습니다.","./edu.php?case=sview&e_id=$e_id&is_update=1$qstr");
}else{
goto_url("./edu.php?case=sview&e_id=$e_id&is_update=1$qstr");
}

?>