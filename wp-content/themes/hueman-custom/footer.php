</div> <!-- End of site-main -->

<footer class="site-footer">
    <div class="footer-content">
        <!-- Footer Ad Space -->
        <div class="ad-space" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); color: rgba(255,255,255,0.7);">
            <p>광고 공간 - 푸터 배너 (728x90)</p>
            <?php if (function_exists('wp_get_option') && get_option('footer_ad_code')): ?>
                <?php echo get_option('footer_ad_code'); ?>
            <?php endif; ?>
        </div>
        
        <div class="footer-info">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
            <p>Powered by WordPress |  블로그</p>
            
            <!-- Footer Navigation -->
            <nav class="footer-nav" style="margin-top: 20px;">
                <a href="<?php echo home_url('/privacy-policy'); ?>" style="color: #bdc3c7; margin: 0 10px; text-decoration: none;">개인정보처리방침</a>
                <a href="<?php echo home_url('/terms'); ?>" style="color: #bdc3c7; margin: 0 10px; text-decoration: none;">이용약관</a>
                <a href="<?php echo home_url('/contact'); ?>" style="color: #bdc3c7; margin: 0 10px; text-decoration: none;">문의하기</a>
                <a href="<?php echo home_url('/sitemap'); ?>" style="color: #bdc3c7; margin: 0 10px; text-decoration: none;">사이트맵</a>
            </nav>
            
            <!-- Social Links -->
            <div class="footer-social" style="margin-top: 20px;">
                <a href="#" style="color: #3498db; margin: 0 10px; font-size: 1.2rem;"><i class="fab fa-twitter"></i></a>
                <a href="#" style="color: #3498db; margin: 0 10px; font-size: 1.2rem;"><i class="fab fa-facebook"></i></a>
                <a href="#" style="color: #3498db; margin: 0 10px; font-size: 1.2rem;"><i class="fab fa-instagram"></i></a>
                <a href="<?php echo home_url('/feed'); ?>" style="color: #3498db; margin: 0 10px; font-size: 1.2rem;"><i class="fas fa-rss"></i></a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

<!-- Performance and Analytics Scripts -->
<script>
// Lazy loading for images
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => {
        img.classList.add('lazy-load');
        imageObserver.observe(img);
    });
});

// Smooth scrolling
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Post view tracking for popular posts
<?php if (is_single()): ?>
function trackPostView() {
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=track_post_view&post_id=<?php echo get_the_ID(); ?>&nonce=<?php echo wp_create_nonce('track_view_nonce'); ?>'
    });
}

// Track view after 15 seconds
setTimeout(trackPostView, 15000);
<?php endif; ?>
</script>

<!-- Google Analytics placeholder -->
<?php if (get_option('google_analytics_code')): ?>
    <?php echo get_option('google_analytics_code'); ?>
<?php endif; ?>

</body>
</html>
