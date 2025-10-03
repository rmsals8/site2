<?php
/**
 * Hueman Custom Theme Functions
 * 수익형 워드프레스 블로그 테마
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function hueman_theme_setup() {
    // Add theme support
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => '주 메뉴',
        'footer' => '푸터 메뉴',
    ));
    
    // Set content width
    if (!isset($content_width)) {
        $content_width = 800;
    }
}
add_action('after_setup_theme', 'hueman_theme_setup');

// Enqueue scripts and styles
function hueman_scripts() {
    // Theme stylesheet
    wp_enqueue_style('hueman-style', get_stylesheet_uri(), array(), '1.0');
    
    // Additional styles for natural look
    wp_enqueue_style('hueman-additions', get_template_directory_uri() . '/style-additions.css', array('hueman-style'), '1.0');
    
    // Custom JavaScript
    wp_enqueue_script('hueman-script', get_template_directory_uri() . '/js/theme.js', array('jquery'), '1.0', true);
    
    // Responsive images
    wp_enqueue_script('picturefill', 'https://cdnjs.cloudflare.com/ajax/libs/picturefill/3.0.3/picturefill.min.js', array(), '3.0.3', true);
}
add_action('wp_enqueue_scripts', 'hueman_scripts');

// Register widget areas
function hueman_widgets_init() {
    register_sidebar(array(
        'name'          => 'Primary Sidebar',
        'id'            => 'sidebar-1',
        'description'   => '메인 사이드바',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => 'Footer Widgets',
        'id'            => 'footer-widgets',
        'description'   => '푸터 위젯',
        'before_widget' => '<div class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'hueman_widgets_init');

// Custom excerpt length
function hueman_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'hueman_excerpt_length');

// Custom excerpt more
function hueman_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'hueman_excerpt_more');

// Post view tracking for popular posts
function track_post_views() {
    if (is_single()) {
        $post_id = get_the_ID();
        $count = get_post_meta($post_id, 'post_views_count', true);
        $count = $count ? $count : 0;
        $count++;
        update_post_meta($post_id, 'post_views_count', $count);
    }
}
add_action('wp_head', 'track_post_views');

// AJAX handler for post view tracking
function ajax_track_post_view() {
    if (!wp_verify_nonce($_POST['nonce'], 'track_view_nonce')) {
        wp_die('Security check failed');
    }
    
    $post_id = intval($_POST['post_id']);
    if ($post_id) {
        $count = get_post_meta($post_id, 'post_views_count', true);
        $count = $count ? $count : 0;
        $count++;
        update_post_meta($post_id, 'post_views_count', $count);
    }
    
    wp_die();
}
add_action('wp_ajax_track_post_view', 'ajax_track_post_view');
add_action('wp_ajax_nopriv_track_post_view', 'ajax_track_post_view');

// Add custom admin options for ads
function hueman_add_admin_menu() {
    add_theme_page(
        'Theme Options',
        'Theme Options',
        'manage_options',
        'hueman-options',
        'hueman_options_page'
    );
}
add_action('admin_menu', 'hueman_add_admin_menu');

function hueman_options_page() {
    if (isset($_POST['submit'])) {
        update_option('header_ad_code', sanitize_textarea_field($_POST['header_ad_code']));
        update_option('sidebar_top_ad_code', sanitize_textarea_field($_POST['sidebar_top_ad_code']));
        update_option('sidebar_bottom_ad_code', sanitize_textarea_field($_POST['sidebar_bottom_ad_code']));
        update_option('content_bottom_ad_code', sanitize_textarea_field($_POST['content_bottom_ad_code']));
        update_option('footer_ad_code', sanitize_textarea_field($_POST['footer_ad_code']));
        update_option('google_analytics_code', sanitize_textarea_field($_POST['google_analytics_code']));
        echo '<div class="notice notice-success"><p>설정이 저장되었습니다!</p></div>';
    }
    
    $header_ad = get_option('header_ad_code', '');
    $sidebar_top_ad = get_option('sidebar_top_ad_code', '');
    $sidebar_bottom_ad = get_option('sidebar_bottom_ad_code', '');
    $content_bottom_ad = get_option('content_bottom_ad_code', '');
    $footer_ad = get_option('footer_ad_code', '');
    $analytics_code = get_option('google_analytics_code', '');
    
    ?>
    <div class="wrap">
        <h1>테마 옵션</h1>
        <form method="post" action="">
            <h2>광고 설정</h2>
            <table class="form-table">
                <tr>
                    <th scope="row">헤더 광고 (728x90)</th>
                    <td><textarea name="header_ad_code" rows="5" cols="50"><?php echo esc_textarea($header_ad); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row">사이드바 상단 광고 (300x250)</th>
                    <td><textarea name="sidebar_top_ad_code" rows="5" cols="50"><?php echo esc_textarea($sidebar_top_ad); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row">사이드바 하단 광고 (300x250)</th>
                    <td><textarea name="sidebar_bottom_ad_code" rows="5" cols="50"><?php echo esc_textarea($sidebar_bottom_ad); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row">콘텐츠 하단 광고 (728x90)</th>
                    <td><textarea name="content_bottom_ad_code" rows="5" cols="50"><?php echo esc_textarea($content_bottom_ad); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row">푸터 광고 (728x90)</th>
                    <td><textarea name="footer_ad_code" rows="5" cols="50"><?php echo esc_textarea($footer_ad); ?></textarea></td>
                </tr>
            </table>
            
            <h2>분석 도구</h2>
            <table class="form-table">
                <tr>
                    <th scope="row">Google Analytics 코드</th>
                    <td><textarea name="google_analytics_code" rows="10" cols="50" placeholder="Google Analytics 또는 GTM 코드를 입력하세요"><?php echo esc_textarea($analytics_code); ?></textarea></td>
                </tr>
            </table>
            
            <?php submit_button('설정 저장'); ?>
        </form>
        
        <h2>사용법</h2>
        <p><strong>AdSense 설정:</strong></p>
        <ol>
            <li>Google AdSense에 가입하고 승인받기</li>
            <li>광고 단위를 생성하고 코드 복사</li>
            <li>위의 해당 위치에 광고 코드 붙여넣기</li>
            <li>설정 저장</li>
        </ol>
        
        <p><strong>권장 광고 크기:</strong></p>
        <ul>
            <li>헤더/콘텐츠 하단/푸터: 728x90 (리더보드)</li>
            <li>사이드바: 300x250 (중형 직사각형)</li>
        </ul>
    </div>
    <?php
}

// Security enhancements
function hueman_security_headers() {
    if (!is_admin()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
    }
}
add_action('send_headers', 'hueman_security_headers');

// Performance optimizations
function hueman_optimize_performance() {
    // Remove unnecessary WordPress features for better performance
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Disable emojis if not needed
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
}
add_action('init', 'hueman_optimize_performance');

// SEO enhancements
function hueman_seo_meta() {
    if (is_singular() && !is_front_page()) {
        echo '<meta name="robots" content="index, follow, max-snippet:-1, max-video-preview:-1, max-image-preview:large">' . "\n";
    }
}
add_action('wp_head', 'hueman_seo_meta');

// Breadcrumb function
function hueman_breadcrumb() {
    if (!is_home() && !is_front_page()) {
        echo '<nav class="breadcrumb">';
        echo '<a href="' . home_url() . '">홈</a>';
        
        if (is_category()) {
            echo ' > 카테고리: ' . single_cat_title('', false);
        } elseif (is_single()) {
            $categories = get_the_category();
            if ($categories) {
                echo ' > <a href="' . get_category_link($categories[0]->term_id) . '">' . $categories[0]->name . '</a>';
            }
            echo ' > ' . get_the_title();
        } elseif (is_page()) {
            echo ' > ' . get_the_title();
        } elseif (is_search()) {
            echo ' > 검색 결과';
        }
        
        echo '</nav>';
    }
}

// Custom post types for portfolio (optional)
function hueman_create_post_types() {
    register_post_type('portfolio', array(
        'labels' => array(
            'name' => '포트폴리오',
            'singular_name' => '포트폴리오',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-portfolio',
    ));
}
add_action('init', 'hueman_create_post_types');

// Image optimization
function hueman_image_sizes() {
    add_image_size('post-thumbnail', 800, 400, true);
    add_image_size('featured-large', 1200, 600, true);
    add_image_size('sidebar-thumb', 100, 100, true);
}
add_action('after_setup_theme', 'hueman_image_sizes');

// Enable Jetpack features
function hueman_jetpack_setup() {
    add_theme_support('infinite-scroll', array(
        'container' => 'main',
        'render' => 'hueman_infinite_scroll_render',
        'footer' => 'page',
    ));
}
add_action('after_setup_theme', 'hueman_jetpack_setup');

function hueman_infinite_scroll_render() {
    while (have_posts()) {
        the_post();
        get_template_part('template-parts/content', get_post_format());
    }
}

?>
