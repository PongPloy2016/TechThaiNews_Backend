<?php
/**
 * Asymmetrical Editorial Hero Slider & Recommended Videos Component
 * Matching reference layout:
 * - Left: Auto-Slide Hero Carousel with Prev/Next circles & Dash progress indicators
 * - Right: 6 news cards in 2x3 grid with square thumbnail & time
 * - Below: Recommended Videos (5 video cards with red play button & duration badge)
 */

if (!function_exists('getVarientThumbnail')) {
    function getVarientThumbnail($article, $fallbackIndex = 0) {
        return RssFeedService::getThumbnail($article, $fallbackIndex);
    }
}

$allArts = $articles ?? [];
$totalArts = count($allArts);

// 1. Featured Auto-Slide Stories (up to 6 articles)
$carouselArticles = array_slice($allArts, 0, min(6, max(1, $totalArts)));

// 2. Right Side 6 Cards (Articles 6 to 11)
$sideArticles = array_slice($allArts, count($carouselArticles), 6);
if (count($sideArticles) < 6 && $totalArts > 0) {
    $idx = 0;
    while (count($sideArticles) < 6) {
        $sideArticles[] = $allArts[$idx % $totalArts];
        $idx++;
    }
}

// 3. Recommended Videos (5 articles)
$videoArticles = array_slice($allArts, count($carouselArticles) + 6, 5);
if (count($videoArticles) < 5 && $totalArts > 0) {
    $idx = 1;
    while (count($videoArticles) < 5) {
        $videoArticles[] = $allArts[($idx + 3) % $totalArts];
        $idx++;
    }
}

// Realistic video durations to display (matching 01:07, 12:25, 10:21 in reference)
$sampleDurations = ['01:07', '12:25', '10:21', '13:11', '04:15', '06:30', '02:45', '08:12'];

// Author avatars array for variety
$sampleAvatars = [
    'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80',
    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=80',
    'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&auto=format&fit=crop&q=80',
    'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&auto=format&fit=crop&q=80',
    'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&auto=format&fit=crop&q=80',
    'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&auto=format&fit=crop&q=80'
];
?>

<div class="container-xl">
    <?php if (!empty($carouselArticles)): ?>
    <!-- ==================== TOP HERO ROW: SLIDER + 6 CARDS ==================== -->
    <section class="hero-showcase-row">
        <!-- 1. Left: Auto-Slide Featured Carousel -->
        <div class="hero-slider-wrap" id="heroSliderContainer">
            <div class="hero-slider-track" id="heroSliderTrack">
                <?php foreach ($carouselArticles as $sIdx => $slide): 
                    $slideThumb = getVarientThumbnail($slide, $sIdx);
                    $slideAuthor = $slide['author'] ?: $slide['source_label'];
                    $slideAvatar = $sampleAvatars[$sIdx % count($sampleAvatars)];
                    $slideCat = !empty($slide['group_label']) ? $slide['group_label'] : ($currentGroupKey === 'all' ? 'ข่าวเด่น' : 'LEAD FEATURE');
                    $cleanSlideCat = trim(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $slideCat));
                    $slideDate = !empty($slide['time_ago']) ? $slide['time_ago'] : date('d M Y');
                ?>
                    <div class="hero-slide-item">
                        <img src="<?php echo htmlspecialchars($slideThumb); ?>" alt="<?php echo htmlspecialchars($slide['title']); ?>" class="hero-slide-img" loading="<?php echo $sIdx === 0 ? 'eager' : 'lazy'; ?>" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=1000&auto=format&fit=crop&q=80';">
                        
                        <div class="hero-slide-content">
                            <span class="editorial-kicker" style="background-color: var(--accent-coral);">
                                <?php echo htmlspecialchars($cleanSlideCat ?: 'ข่าวเด่น'); ?> &bull; <?php echo htmlspecialchars($slide['source_label']); ?>
                            </span>

                            <h2 class="hero-slide-title">
                                <a href="news_detail.php?id=<?php echo urlencode($slide['id']); ?>&group=<?php echo urlencode($slide['group_key'] ?? $currentGroupKey); ?>" class="text-white text-decoration-none">
                                    <?php echo htmlspecialchars($slide['title']); ?>
                                </a>
                            </h2>

                            <div class="hero-slide-meta">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="<?php echo htmlspecialchars($slideAvatar); ?>" alt="<?php echo htmlspecialchars($slideAuthor); ?>" class="hero-author-avatar-img">
                                    <span class="fw-semibold text-white">By <?php echo htmlspecialchars($slideAuthor); ?></span>
                                </div>
                                <span class="opacity-50">&bull;</span>
                                <span><?php echo htmlspecialchars($slideDate); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Slider Navigation: Prev/Next Circles + Dash Indicators (Matching Screenshot) -->
            <div class="hero-slider-controls">
                <button type="button" class="slider-circle-btn" id="heroSliderPrev" aria-label="Previous Slide">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <div class="slider-dashes-container" id="heroSliderDashes">
                    <?php for ($i = 0; $i < count($carouselArticles); $i++): ?>
                        <div class="slider-dash-bar <?php echo $i === 0 ? 'active' : ''; ?>" data-slide-index="<?php echo $i; ?>" aria-label="Slide <?php echo $i + 1; ?>"></div>
                    <?php endfor; ?>
                </div>

                <button type="button" class="slider-circle-btn" id="heroSliderNext" aria-label="Next Slide">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- 2. Right: 6 Cards in 2x3 Grid (Matching Screenshot) -->
        <div class="hero-side-grid">
            <?php foreach ($sideArticles as $cardIdx => $card): 
                $cardThumb = getVarientThumbnail($card, $cardIdx + 6);
                $cardTime = !empty($card['time_ago']) ? $card['time_ago'] : 'ไม่กี่นาทีที่ผ่านมา';
            ?>
                <a href="news_detail.php?id=<?php echo urlencode($card['id']); ?>&group=<?php echo urlencode($card['group_key'] ?? $currentGroupKey); ?>" class="hero-side-card">
                    <div class="hero-side-thumb-wrap">
                        <img src="<?php echo htmlspecialchars($cardThumb); ?>" alt="<?php echo htmlspecialchars($card['title']); ?>" class="hero-side-thumb" loading="lazy" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=1000&auto=format&fit=crop&q=80';">
                    </div>
                    <div class="hero-side-body">
                        <h4 class="hero-side-title">
                            <?php echo htmlspecialchars($card['title']); ?>
                        </h4>
                        <span class="hero-side-time">
                            <?php echo htmlspecialchars($cardTime); ?>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ==================== 3. RECOMMENDED VIDEOS SECTION ==================== -->
    <section class="recommended-videos-section">
        <div class="videos-header-row">
            <h3 class="videos-title">
                Recommended Videos <span class="fs-6 fw-normal text-muted ms-1">(วิดีโอแนะนำ)</span>
            </h3>
            <a href="index.php?group=all" class="videos-see-all">
                See All <i class="bi bi-chevron-right small"></i>
            </a>
        </div>

        <div class="videos-grid-row">
            <?php foreach ($videoArticles as $vIdx => $video): 
                $vThumb = getVarientThumbnail($video, $vIdx + 12);
                $vDuration = $sampleDurations[$vIdx % count($sampleDurations)];
                $vCat = !empty($video['group_label']) ? $video['group_label'] : 'TECHNOLOGY';
                $cleanVCat = strtoupper(trim(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $vCat)));
                $vDate = !empty($video['time_ago']) ? $video['time_ago'] : 'วันนี้';
            ?>
                <article class="video-card-item">
                    <a href="news_detail.php?id=<?php echo urlencode($video['id']); ?>&group=<?php echo urlencode($video['group_key'] ?? $currentGroupKey); ?>" class="video-thumb-container">
                        <img src="<?php echo htmlspecialchars($vThumb); ?>" alt="<?php echo htmlspecialchars($video['title']); ?>" class="video-thumb-img" loading="lazy" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1518770660439-4636190af475?w=600&auto=format&fit=crop&q=80';">
                        <div class="video-thumb-overlay"></div>
                        <div class="video-play-icon">
                            <i class="bi bi-play-fill"></i>
                        </div>
                        <span class="video-duration-tag"><?php echo $vDuration; ?></span>
                    </a>

                    <div class="video-card-body">
                        <h4 class="video-card-title">
                            <a href="news_detail.php?id=<?php echo urlencode($video['id']); ?>&group=<?php echo urlencode($video['group_key'] ?? $currentGroupKey); ?>" class="text-decoration-none" style="color: inherit;">
                                <?php echo htmlspecialchars($video['title']); ?>
                            </a>
                        </h4>
                        <span class="video-category-label">
                            <?php echo htmlspecialchars($cleanVCat ?: 'TECHNOLOGY'); ?>
                        </span>
                        <span class="video-date-label">
                            <?php echo htmlspecialchars($vDate); ?>
                        </span>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<!-- Auto-Slide Carousel Controller Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('heroSliderTrack');
    const container = document.getElementById('heroSliderContainer');
    const prevBtn = document.getElementById('heroSliderPrev');
    const nextBtn = document.getElementById('heroSliderNext');
    const dashes = document.querySelectorAll('#heroSliderDashes .slider-dash-bar');
    const totalSlides = <?php echo count($carouselArticles); ?>;

    if (!track || totalSlides <= 1) return;

    let currentIndex = 0;
    let autoSlideTimer = null;
    const slideDuration = 4500; // 4.5 seconds per slide auto-play

    function goToSlide(index) {
        if (index < 0) {
            index = totalSlides - 1;
        } else if (index >= totalSlides) {
            index = 0;
        }
        currentIndex = index;
        track.style.transform = `translateX(-${currentIndex * 100}%)`;

        dashes.forEach((dash, i) => {
            if (i === currentIndex) {
                dash.classList.add('active');
            } else {
                dash.classList.remove('active');
            }
        });
    }

    function nextSlide() {
        goToSlide(currentIndex + 1);
    }

    function prevSlide() {
        goToSlide(currentIndex - 1);
    }

    function startTimer() {
        stopTimer();
        autoSlideTimer = setInterval(nextSlide, slideDuration);
    }

    function stopTimer() {
        if (autoSlideTimer) {
            clearInterval(autoSlideTimer);
            autoSlideTimer = null;
        }
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            nextSlide();
            startTimer();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            prevSlide();
            startTimer();
        });
    }

    dashes.forEach((dash) => {
        dash.addEventListener('click', function() {
            const idx = parseInt(this.getAttribute('data-slide-index'), 10);
            if (!isNaN(idx)) {
                goToSlide(idx);
                startTimer();
            }
        });
    });

    if (container) {
        // Pause on mouse hover, resume on leave
        container.addEventListener('mouseenter', stopTimer);
        container.addEventListener('mouseleave', startTimer);

        // Touch Swipe Support for mobile devices
        let touchStartX = 0;
        let touchEndX = 0;
        container.addEventListener('touchstart', function(e) {
            stopTimer();
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        container.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            if (touchStartX - touchEndX > 45) {
                nextSlide();
            } else if (touchEndX - touchStartX > 45) {
                prevSlide();
            }
            startTimer();
        }, { passive: true });
    }

    // Launch auto slide cycle
    startTimer();
});
</script>

