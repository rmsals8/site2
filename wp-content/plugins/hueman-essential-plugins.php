<?php
/**
 * Plugin Name: Hueman Essential Plugins Setup
 * Description: 수익형 블로그를 위한 필수 플러그인 자동 설치 및 설정
 * Version: 1.0
 * Author: Custom Design
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class HuemanEssentialPlugins {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_ajax_install_plugin', array($this, 'install_plugin_ajax'));
        add_action('admin_init', array($this, 'setup_jetpack_features'));
    }
    
    public function add_admin_menu() {
        add_management_page(
            'Essential Plugins',
            'Essential Plugins',
            'manage_options',
            'hueman-plugins',
            array($this, 'admin_page')
        );
    }
    
    public function admin_page() {
        $essential_plugins = array(
            'jetpack' => array(
                'name' => 'Jetpack',
                'slug' => 'jetpack/jetpack.php',
                'description' => 'CDN, 통계, 보안, 소셜 공유 기능',
                'download_url' => 'https://downloads.wordpress.org/plugin/jetpack.zip'
            ),
            'yoast-seo' => array(
                'name' => 'Yoast SEO',
                'slug' => 'wordpress-seo/wp-seo.php',
                'description' => 'SEO 최적화',
                'download_url' => 'https://downloads.wordpress.org/plugin/wordpress-seo.zip'
            ),
            'wp-super-cache' => array(
                'name' => 'WP Super Cache',
                'slug' => 'wp-super-cache/wp-cache.php',
                'description' => '캐싱으로 사이트 속도 개선',
                'download_url' => 'https://downloads.wordpress.org/plugin/wp-super-cache.zip'
            ),
            'advanced-ads' => array(
                'name' => 'Advanced Ads',
                'slug' => 'advanced-ads/advanced-ads.php',
                'description' => '광고 관리 (AdSense 통합)',
                'download_url' => 'https://downloads.wordpress.org/plugin/advanced-ads.zip'
            ),
            'contact-form-7' => array(
                'name' => 'Contact Form 7',
                'slug' => 'contact-form-7/wp-contact-form-7.php',
                'description' => '연락처 폼',
                'download_url' => 'https://downloads.wordpress.org/plugin/contact-form-7.zip'
            ),
            'wp-smushit' => array(
                'name' => 'Smush',
                'slug' => 'wp-smushit/wp-smush.php',
                'description' => '이미지 압축 최적화',
                'download_url' => 'https://downloads.wordpress.org/plugin/wp-smushit.zip'
            )
        );
        
        ?>
        <div class="wrap">
            <h1>Essential Plugins for 수익형 블로그</h1>
            <p>수익형 워드프레스 블로그에 필요한 핵심 플러그인들을 설치하고 설정하세요.</p>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>플러그인</th>
                        <th>설명</th>
                        <th>상태</th>
                        <th>작업</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($essential_plugins as $key => $plugin): ?>
                        <tr>
                            <td><strong><?php echo esc_html($plugin['name']); ?></strong></td>
                            <td><?php echo esc_html($plugin['description']); ?></td>
                            <td>
                                <?php if (is_plugin_active($plugin['slug'])): ?>
                                    <span style="color: green;">✓ 활성화됨</span>
                                <?php elseif (file_exists(WP_PLUGIN_DIR . '/' . dirname($plugin['slug']))): ?>
                                    <span style="color: orange;">설치됨 (비활성)</span>
                                <?php else: ?>
                                    <span style="color: red;">설치 필요</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!is_plugin_active($plugin['slug'])): ?>
                                    <button class="button button-primary install-plugin" 
                                            data-plugin="<?php echo esc_attr($key); ?>" 
                                            data-slug="<?php echo esc_attr($plugin['slug']); ?>"
                                            data-url="<?php echo esc_attr($plugin['download_url']); ?>">
                                        <?php echo file_exists(WP_PLUGIN_DIR . '/' . dirname($plugin['slug'])) ? '활성화' : '설치 & 활성화'; ?>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <h2>Jetpack 기능 설정</h2>
            <?php if (is_plugin_active('jetpack/jetpack.php')): ?>
                <div class="jetpack-features">
                    <h3>권장 Jetpack 기능들:</h3>
                    <ul>
                        <li><strong>Photon CDN:</strong> 이미지 로딩 속도 개선</li>
                        <li><strong>Site Stats:</strong> 방문자 통계</li>
                        <li><strong>Social Sharing:</strong> 소셜 미디어 공유 버튼</li>
                        <li><strong>Related Posts:</strong> 관련 글 추천</li>
                        <li><strong>Infinite Scroll:</strong> 무한 스크롤</li>
                        <li><strong>Contact Form:</strong> 연락처 폼</li>
                    </ul>
                    
                    <p><a href="<?php echo admin_url('admin.php?page=jetpack#/dashboard'); ?>" class="button button-primary">Jetpack 대시보드로 이동</a></p>
                </div>
            <?php else: ?>
                <p>Jetpack을 설치하고 활성화하면 CDN, 통계, 보안 등의 기능을 사용할 수 있습니다.</p>
            <?php endif; ?>
            
            <h2>수익화 설정 가이드</h2>
            <div class="monetization-guide">
                <h3>1. Google AdSense 설정</h3>
                <ol>
                    <li>Google AdSense 계정 생성 및 사이트 등록</li>
                    <li>외모 > Theme Options에서 광고 코드 입력</li>
                    <li>Advanced Ads 플러그인으로 고급 광고 관리</li>
                </ol>
                
                <h3>2. SEO 최적화</h3>
                <ol>
                    <li>Yoast SEO로 각 포스트 최적화</li>
                    <li>Google Search Console 연결</li>
                    <li>XML 사이트맵 제출</li>
                </ol>
                
                <h3>3. 성능 최적화</h3>
                <ol>
                    <li>WP Super Cache로 캐싱 활성화</li>
                    <li>Smush로 이미지 최적화</li>
                    <li>Jetpack CDN 활성화</li>
                </ol>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('.install-plugin').on('click', function() {
                var button = $(this);
                var plugin = button.data('plugin');
                var slug = button.data('slug');
                var url = button.data('url');
                
                button.prop('disabled', true).text('설치 중...');
                
                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: {
                        action: 'install_plugin',
                        plugin: plugin,
                        slug: slug,
                        download_url: url,
                        nonce: '<?php echo wp_create_nonce('install_plugin_nonce'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            button.text('완료').css('background', 'green');
                            location.reload();
                        } else {
                            button.text('오류 발생').css('background', 'red');
                            alert(response.data);
                        }
                    }
                });
            });
        });
        </script>
        <?php
    }
    
    public function install_plugin_ajax() {
        if (!wp_verify_nonce($_POST['nonce'], 'install_plugin_nonce')) {
            wp_die('Security check failed');
        }
        
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $plugin_slug = sanitize_text_field($_POST['slug']);
        $download_url = esc_url_raw($_POST['download_url']);
        
        // Include necessary WordPress functions
        if (!function_exists('plugins_api')) {
            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        }
        if (!function_exists('download_url')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        if (!function_exists('activate_plugin')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        try {
            // Check if plugin is already installed
            if (!file_exists(WP_PLUGIN_DIR . '/' . dirname($plugin_slug))) {
                // Download and install plugin
                $temp_file = download_url($download_url);
                if (is_wp_error($temp_file)) {
                    wp_send_json_error('Download failed: ' . $temp_file->get_error_message());
                }
                
                // Extract plugin
                $result = unzip_file($temp_file, WP_PLUGIN_DIR);
                unlink($temp_file);
                
                if (is_wp_error($result)) {
                    wp_send_json_error('Extraction failed: ' . $result->get_error_message());
                }
            }
            
            // Activate plugin
            $activate_result = activate_plugin($plugin_slug);
            if (is_wp_error($activate_result)) {
                wp_send_json_error('Activation failed: ' . $activate_result->get_error_message());
            }
            
            wp_send_json_success('Plugin installed and activated successfully');
            
        } catch (Exception $e) {
            wp_send_json_error('Error: ' . $e->getMessage());
        }
    }
    
    public function setup_jetpack_features() {
        if (is_plugin_active('jetpack/jetpack.php')) {
            // Auto-enable recommended Jetpack features
            $jetpack_active_modules = get_option('jetpack_active_modules', array());
            
            $recommended_modules = array(
                'photon', // CDN for images
                'stats', // Site statistics
                'sharing', // Social sharing buttons
                'related-posts', // Related posts
                'contact-form', // Contact forms
                'infinite-scroll', // Infinite scroll
                'sitemap' // XML sitemaps
            );
            
            foreach ($recommended_modules as $module) {
                if (!in_array($module, $jetpack_active_modules)) {
                    $jetpack_active_modules[] = $module;
                }
            }
            
            update_option('jetpack_active_modules', $jetpack_active_modules);
        }
    }
}

// Initialize the plugin
new HuemanEssentialPlugins();
?>
