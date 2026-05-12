<?php
add_stylesheet('<link rel="stylesheet" href="'.G5_SKIN_URL.'/latest/basic1/style.css">', 0);
//&pref=B
$url = 'http://openapi.work.go.kr/opi/opi/opia/wantedApi.do?authKey=WNJ1YGMVVQWWSLPXZWZLE2VR1HL&callTp=L&returnType=XML&startPage=1&display=5&region=45000&pref=Y'; 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = cURL_exec($ch);
cURL_close($ch); 

$rss = simplexml_load_string($response); 

$rss->total; 

$total_count = $rss->total;
?>
<div class="lt">
    <ul>
    <?php   
	foreach($rss as $chan) {  
		if($chan->company) {	
	?>
        <li>
             - <a href="<?php echo G5_URL?>/work/w_view.php?no=<?php echo $chan->wantedAuthNo?>"><?php echo $chan->title?></a>       
             <span class="datatime"><?php echo $chan->regDt?></span>
        </li>
    <?php }}  ?>
    <?php if ($total_count == 0) { //게시물이 없을 때  ?>
    <li>게시물이 없습니다.</li>
    <?php }  ?>
    </ul>
</div>