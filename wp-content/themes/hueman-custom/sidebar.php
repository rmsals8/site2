<aside class="sidebar">
    <!-- Follow Section -->
    <div class="widget follow-section">
        <h3>FOLLOW:</h3>
        <div class="social-buttons">
            <a href="#" class="social-btn twitter" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-btn facebook" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-btn google" aria-label="Google"><i class="fab fa-google"></i></a>
            <a href="#" class="social-btn instagram" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="<?php echo home_url('/feed'); ?>" class="social-btn" style="background:#ff6600;" aria-label="RSS"><i class="fas fa-rss"></i></a>
        </div>
    </div>

    <!-- Ad Space - Sidebar Top -->
    <div class="ad-space-sidebar">
        <p>광고 공간 - 사이드바 상단 (300x250)</p>
        <?php if (function_exists('wp_get_option') && get_option('sidebar_top_ad_code')): ?>
            <?php echo get_option('sidebar_top_ad_code'); ?>
        <?php endif; ?>
    </div>

    <!-- Recommended Posts -->
    <div class="widget">
        <h3 class="widget-title">RECOMMENDED</h3>
        <div class="recommended-posts">
            <?php
            $recommended_posts = new WP_Query(array(
                'posts_per_page' => 5,
                'meta_key' => 'post_views_count',
                'orderby' => 'meta_value_num',
                'order' => 'DESC',
                'post_status' => 'publish'
            ));

            if ($recommended_posts->have_posts()) :
                while ($recommended_posts->have_posts()) : $recommended_posts->the_post();
            ?>
                <div class="post-item">
                    <?php if (has_post_thumbnail()) : ?>
                        <img src="<?php the_post_thumbnail_url('thumbnail'); ?>" alt="<?php the_title(); ?>" class="post-thumb">
                    <?php else: ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/default-thumb.jpg" alt="<?php the_title(); ?>" class="post-thumb">
                    <?php endif; ?>
                    
                    <div class="post-info">
                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <span class="post-date"><?php echo get_the_date(); ?></span>
                    </div>
                </div>
            <?php 
                endwhile;
                wp_reset_postdata();
            else:
                // Fallback to recent posts if no view count exists
                $recent_posts = new WP_Query(array(
                    'posts_per_page' => 5,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));

                while ($recent_posts->have_posts()) : $recent_posts->the_post();
            ?>
                <div class="post-item">
                    <?php if (has_post_thumbnail()) : ?>
                        <img src="<?php the_post_thumbnail_url('thumbnail'); ?>" alt="<?php the_title(); ?>" class="post-thumb">
                    <?php else: ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/default-thumb.jpg" alt="<?php the_title(); ?>" class="post-thumb">
                    <?php endif; ?>
                    
                    <div class="post-info">
                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <span class="post-date"><?php echo get_the_date(); ?></span>
                    </div>
                </div>
            <?php 
                endwhile;
                wp_reset_postdata();
            endif; 
            ?>
        </div>
    </div>

    <!-- What's Hot Section -->
    <div class="widget">
        <h3 class="widget-title">WHAT'S HOT?</h3>
        <div class="whats-hot">
            <?php
            $hot_posts = new WP_Query(array(
                'posts_per_page' => 3,
                'meta_key' => 'post_views_count',
                'orderby' => 'meta_value_num',
                'order' => 'DESC',
                'date_query' => array(
                    array(
                        'after' => '1 month ago',
                    ),
                ),
            ));

            if ($hot_posts->have_posts()) :
                while ($hot_posts->have_posts()) : $hot_posts->the_post();
            ?>
                <div class="hot-post-item" style="margin-bottom: 20px;">
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>">
                            <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>" style="width:100%; height:150px; object-fit:cover; border-radius:8px;">
                        </a>
                    <?php endif; ?>
                    <h4 style="margin:10px 0 5px 0; font-size:0.95rem; line-height:1.3;">
                        <a href="<?php the_permalink(); ?>" style="color:#333; text-decoration:none;">
                            <?php the_title(); ?>
                        </a>
                    </h4>
                    <p style="color:#666; font-size:0.85rem; line-height:1.4;">
                        <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                    </p>
                </div>
            <?php 
                endwhile;
                wp_reset_postdata();
            endif; 
            ?>
        </div>
    </div>

    <!-- Ad Space - Sidebar Bottom -->
    <div class="ad-space-sidebar">
        <p>광고 공간 - 사이드바 하단 (300x250)</p>
        <?php if (function_exists('wp_get_option') && get_option('sidebar_bottom_ad_code')): ?>
            <?php echo get_option('sidebar_bottom_ad_code'); ?>
        <?php endif; ?>
    </div>

    <!-- Categories Widget -->
    <div class="widget category-widget">
        <h3 class="widget-title">✨ 카테고리</h3>
        <ul class="category-list">
            <?php
            $categories = get_categories(array(
                'orderby' => 'count',
                'order' => 'DESC',
                'number' => 8,
                'hide_empty' => true
            ));

            $emojis = ['🎨', '💖', '✨', '🌈', '🎪', '💫', '🎭', '🦄'];
            $i = 0;

            foreach ($categories as $category) {
                $emoji = isset($emojis[$i]) ? $emojis[$i] : '🎯';
                echo '<li class="category-item">
                        <a href="' . get_category_link($category->term_id) . '" class="category-link">
                            <span class="category-emoji">' . $emoji . '</span>
                            <span class="category-name">' . $category->name . '</span>
                            <span class="category-count">(' . $category->count . ')</span>
                        </a>
                      </li>';
                $i++;
            }
            ?>
        </ul>
    </div>



    <!-- Dynamic Sidebar for additional widgets -->
    <?php if (is_active_sidebar('sidebar-1')) : ?>
        <?php dynamic_sidebar('sidebar-1'); ?>
    <?php endif; ?>
</aside>
