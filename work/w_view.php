<?php
include_once('../common.php');
$gr_id = "sub04";
$bo_table = "sub04_02";
include_once(G5_PATH.'/head.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_SKIN_URL.'/board/basic/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="./style.css">', 0);

if(!$no){
	alert("제대로 된 값이 넘어오지 않았습니다.");
}



$url = 'http://openapi.work.go.kr/opi/opi/opia/wantedApi.do?authKey=WNJ1YGMVVQWWSLPXZWZLE2VR1HL&callTp=L&returnType=XML&keyword='.$no; 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = cURL_exec($ch);
cURL_close($ch); 

$rss = simplexml_load_string($response); 
?>
<article id="bo_v" style="width:100%">
    <header>
        <h1 id="bo_v_title" style="font-size:15px">
            <a href="<?php echo $rss->wanted->wantedInfoUrl?>" target="_blank"><?php echo $rss->wanted->title?></a>
        </h1>
    </header>

<p>&nbsp;</p>
    <section id="bo_v_atc">
        <h2 id="bo_v_atc_title">본문</h2>


        <!-- 본문 내용 시작 { -->
<div class="tbl_frm03 tbl_wrap">
    <table class="tab02">
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">회사명</th>
        <td><?php echo $rss->wanted->company?></td> 
    </tr>      
    <tr>
        <th scope="row">급여</th>
        <td><?php echo $rss->wanted->salTpNm?> <?php echo $rss->wanted->sal?></td>
    </tr>
    <tr>
        <th scope="row">근무지역</th>
        <td><?php echo $rss->wanted->region?></td>
    </tr>
    <tr>
        <th scope="row">근무형태</th>
        <td><?php echo $rss->wanted->holidayTpNm?></td>
    </tr>
    <tr>
        <th scope="row">학력</th>
        <td><?php echo $rss->wanted->minEdubg?> <?php echo ($rss->wanted->maxEdubg)?"~ ".$rss->wanted->maxEdubg:"";?></td>
    </tr>
    <tr>
        <th scope="row">경력</th>
        <td><?php echo $rss->wanted->career?></td>
    </tr>
    <tr>
        <th scope="row">주소</th>
        <td><?php echo $rss->wanted->zipCd?"[".$rss->wanted->zipCd."]":"";?> <?php echo $rss->wanted->basicAddr?> <?php echo $rss->wanted->detailAddr?></td>
    </tr>
    <tr>
        <th scope="row">등록일자</th>
        <td><?php echo $rss->wanted->regDt?></td>
    </tr>
    <tr>
        <th scope="row">마감일자</th>
        <td><?php echo $rss->wanted->closeDt?></td>
    </tr>
    <tr>
        <th scope="row">링크</th>
        <td><a href="<?php echo $rss->wanted->wantedInfoUrl?>" target="_blank">워크넷 상세 보기</a></td>
    </tr>                                                                            
    </tbody>
    </table>
</div>  
        <!-- } 본문 내용 끝 -->


    </section>


<div class="btn_confirm01 btn_confirm">
    <a href="./work.php" class="btn_b01">목록</a>
</div>
    <!-- } 링크 버튼 끝 -->

</article>
<!-- } 게시판 읽기 끝 -->

<?php include_once(G5_PATH.'/tail.php'); ?>
