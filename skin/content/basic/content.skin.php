<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$content_skin_url.'/style.css">', 0);
?>

<article id="ctt" class="ctt_<?php echo $co_id; ?>">
    <header>
        <h1><?php echo $g5['title']; ?></h1>
    </header>

    <div id="ctt_con">
        <?php if ($co_id == 'sub02_03') { ?>
        <?php $figma_img_url = G5_IMG_URL.'/figma_sub02_03'; ?>
        <div class="network_figma">
            <section class="network_section network_theme_amber">
                <div class="network_section_head">
                    <span>1</span>
                    <h3>노인일자리 정책포럼 및 조사연구사업</h3>
                </div>
                <div class="network_grid network_grid_half">
                    <figure>
                        <img src="<?php echo $figma_img_url; ?>/forum.png" alt="노인일자리 정책포럼">
                        <figcaption>노인일자리 정책포럼</figcaption>
                    </figure>
                    <figure>
                        <img src="<?php echo $figma_img_url; ?>/research.png" alt="노인일자리 조사연구사업">
                        <figcaption>노인일자리 조사연구사업</figcaption>
                    </figure>
                </div>
            </section>

            <section class="network_section network_theme_green">
                <div class="network_section_head">
                    <span>2</span>
                    <h3>노인일자리 민관네트워크사업</h3>
                </div>
                <div class="network_grid network_grid_half">
                    <figure>
                        <img src="<?php echo $figma_img_url; ?>/meeting_officials.png" alt="전북 14개 시군 노인일자리 담당 공무원 간담회">
                        <figcaption>전북 14개 시군 노인일자리 담당 공무원 간담회</figcaption>
                    </figure>
                    <figure>
                        <img src="<?php echo $figma_img_url; ?>/meeting_local.png" alt="노인일자리 지역별 간담회">
                        <figcaption>노인일자리 지역별 간담회</figcaption>
                    </figure>
                </div>
            </section>

            <section class="network_section network_theme_sky">
                <div class="network_section_head">
                    <span>3</span>
                    <h3>노인일자리지원사업</h3>
                </div>
                <div class="network_grid network_grid_half">
                    <figure>
                        <img src="<?php echo $figma_img_url; ?>/consulting_1.png" alt="노인일자리 수행기관 컨설팅">
                        <figcaption>노인일자리 수행기관 컨설팅</figcaption>
                    </figure>
                    <figure>
                        <img src="<?php echo $figma_img_url; ?>/consulting_2.png" alt="노인일자리 수행기관 컨설팅">
                        <figcaption>노인일자리 수행기관 컨설팅</figcaption>
                    </figure>
                </div>
            </section>

            <section class="network_section network_theme_rose">
                <div class="network_section_head">
                    <span>4</span>
                    <h3>노인일자리 홍보사업</h3>
                </div>
                <div class="network_grid network_grid_quarter">
                    <figure>
                        <img src="<?php echo $figma_img_url; ?>/job_web.png" alt="구인구직일자리웹">
                        <figcaption>구인구직일자리웹</figcaption>
                    </figure>
                    <figure>
                        <img src="<?php echo $figma_img_url; ?>/youtube_tv.png" alt="유튜브 전북노인일자리센터TV">
                        <figcaption>유튜브 전북노인일자리센터TV</figcaption>
                    </figure>
                    <figure>
                        <img src="<?php echo $figma_img_url; ?>/instagram.png" alt="인스타그램">
                        <figcaption>인스타그램</figcaption>
                    </figure>
                    <figure>
                        <img src="<?php echo $figma_img_url; ?>/ai_platform.png" alt="노인일자리 AI 통합플랫폼">
                        <figcaption>노인일자리 AI 통합플랫폼</figcaption>
                    </figure>
                </div>
            </section>

            <section class="network_section network_theme_violet">
                <div class="network_section_head">
                    <span>5</span>
                    <h3>민간일자리활성화사업</h3>
                </div>
                <div class="network_grid network_grid_half">
                    <figure>
                        <img src="<?php echo $figma_img_url; ?>/job_fair.png" alt="노인희망채용박람회">
                        <figcaption>노인희망채용박람회</figcaption>
                    </figure>
                    <figure>
                        <img src="<?php echo $figma_img_url; ?>/company_meeting.png" alt="노인채용희망기업체 간담회">
                        <figcaption>노인채용희망기업체 간담회</figcaption>
                    </figure>
                </div>
            </section>

            <section class="network_section network_theme_teal">
                <div class="network_section_head">
                    <span>6</span>
                    <h3>노인상담소운영</h3>
                </div>
                <div class="network_grid network_grid_quarter">
                    <figure>
                        <img src="<?php echo $figma_img_url; ?>/mobile_counseling.png" alt="찾아가는 이동상담">
                        <figcaption>찾아가는 이동상담</figcaption>
                    </figure>
                    <figure>
                        <img src="<?php echo $figma_img_url; ?>/job_training.png" alt="취업교육">
                        <figcaption>취업교육</figcaption>
                    </figure>
                    <figure>
                        <img src="<?php echo $figma_img_url; ?>/resume_photo.png" alt="이력서작성 및 증명사진촬영">
                        <figcaption>이력서작성 및 증명사진촬영</figcaption>
                    </figure>
                    <figure>
                        <img src="<?php echo $figma_img_url; ?>/job_counseling.png" alt="구직상담">
                        <figcaption>구직상담</figcaption>
                    </figure>
                </div>
            </section>
        </div>
        <?php } else if ($co_id == 'sub01_02') { ?>
        <div class="vision_mission_figma">
            <div class="vm_top_grid">
                <section class="vm_card vm_card_vision">
                    <div class="vm_card_head">
                        <span class="vm_icon vm_icon_vision"><i class="fa fa-eye" aria-hidden="true"></i></span>
                        <h3>비전 (Vision)</h3>
                    </div>
                    <p>일하는 노인, 새로운 청춘 - 지역과 함께 만드는 지속가능한 노인일자리 생태계</p>
                </section>
                <section class="vm_card vm_card_mission">
                    <div class="vm_card_head">
                        <span class="vm_icon vm_icon_mission"><i class="fa fa-flag-o" aria-hidden="true"></i></span>
                        <h3>미션 (Mission)</h3>
                    </div>
                    <p>지역사회 협력 기반의 노인일자리 확대와 질적 고도화를 통해 건강하고 활기찬 노후를 지원한다</p>
                </section>
            </div>

            <section class="vm_values">
                <h3>핵심가치 (운영방향)</h3>
                <div class="vm_value_grid">
                    <div class="vm_value vm_value_coop">
                        <span class="vm_value_icon"><i class="fa fa-handshake-o" aria-hidden="true"></i></span>
                        <strong>협력성</strong>
                        <p>지자체, 유관기관, 민간과의 유기적 협력</p>
                    </div>
                    <div class="vm_value vm_value_pro">
                        <span class="vm_value_icon"><i class="fa fa-graduation-cap" aria-hidden="true"></i></span>
                        <strong>전문성</strong>
                        <p>교육, 컨설팅 기반 일자리 품질 강화</p>
                    </div>
                    <div class="vm_value vm_value_field">
                        <span class="vm_value_icon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                        <strong>현장성</strong>
                        <p>찾아가는 네트워크 및 지역 밀착 운영</p>
                    </div>
                    <div class="vm_value vm_value_sustain">
                        <span class="vm_value_icon"><i class="fa fa-recycle" aria-hidden="true"></i></span>
                        <strong>지속가능성</strong>
                        <p>일자리 발굴부터 사후관리까지 선순환 구조 구축</p>
                    </div>
                </div>
            </section>
        </div>
        <?php } else { ?>
        <?php echo $str; ?>
        <?php } ?>
    </div>

</article>
