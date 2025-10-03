<?php get_header(); ?>

<div class="site-content">
    <main class="main-content">
        <?php while (have_posts()) : the_post(); ?>
            
            <!-- Breadcrumb -->
            <?php hueman_breadcrumb(); ?>
            
            <article class="single-post">
                <!-- Post Header -->
                <header class="post-header">
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)) {
                        echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="post-category">' . esc_html($categories[0]->name) . '</a>';
                    }
                    ?>
                    
                    <h1 class="post-title"><?php the_title(); ?></h1>
                    
                    <div class="post-meta">
                        <span class="post-date"><?php echo get_the_date(); ?></span>
                        <span class="post-author">by <?php the_author(); ?></span>
                        <span class="post-comments"><?php comments_number('0 댓글', '1 댓글', '% 댓글'); ?></span>
                        <span class="post-views"><?php echo get_post_meta(get_the_ID(), 'post_views_count', true) ?: '0'; ?> 조회</span>
                    </div>
                </header>

                <!-- Featured Image -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-featured-image">
                        <img src="<?php the_post_thumbnail_url('featured-large'); ?>" alt="<?php the_title(); ?>" class="featured-img">
                    </div>
                <?php endif; ?>

                <!-- Ad Space - After Title -->
                <div class="ad-space">
                    <p>광고 공간 - 제목 하단 (728x90)</p>
                    <?php if (function_exists('wp_get_option') && get_option('header_ad_code')): ?>
                        <?php echo get_option('header_ad_code'); ?>
                    <?php endif; ?>
                </div>

                <!-- Post Content -->
                <div class="post-content-area">
                    <?php 
                    $content = get_the_content();
                    $content_parts = explode('</p>', $content);
                    $half_way = ceil(count($content_parts) / 2);
                    
                    // Display first half of content
                    for ($i = 0; $i < $half_way; $i++) {
                        if (isset($content_parts[$i])) {
                            echo $content_parts[$i] . '</p>';
                        }
                    }
                    ?>
                    
                    <!-- Mid-content Ad -->
                    <div class="ad-space">
                        <p>광고 공간 - 콘텐츠 중간 (728x90)</p>
                        <?php if (function_exists('wp_get_option') && get_option('content_bottom_ad_code')): ?>
                            <?php echo get_option('content_bottom_ad_code'); ?>
                        <?php endif; ?>
                    </div>
                    
                    <?php 
                    // Display second half of content
                    for ($i = $half_way; $i < count($content_parts); $i++) {
                        if (isset($content_parts[$i]) && !empty(trim($content_parts[$i]))) {
                            echo $content_parts[$i] . '</p>';
                        }
                    }
                    ?>
                </div>

                <!-- Post Tags -->
                <?php if (has_tag()) : ?>
                    <div class="post-tags">
                        <h4>태그:</h4>
                        <?php the_tags('', ', ', ''); ?>
                    </div>
                <?php endif; ?>

                <!-- Social Sharing -->
                <div class="social-sharing">
                    <h4>공유하기:</h4>
                    <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" target="_blank" class="share-btn twitter">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank" class="share-btn facebook">
                        <i class="fab fa-facebook"></i> Facebook
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php the_permalink(); ?>" target="_blank" class="share-btn linkedin">
                        <i class="fab fa-linkedin"></i> LinkedIn
                    </a>
                    <a href="mailto:?subject=<?php the_title(); ?>&body=<?php the_permalink(); ?>" class="share-btn email">
                        <i class="fas fa-envelope"></i> Email
                    </a>
                </div>

                <!-- Author Box -->
                <div class="author-box">
                    <div class="author-avatar">
                        <?php echo get_avatar(get_the_author_meta('ID'), 80); ?>
                    </div>
                    <div class="author-info">
                        <h4><?php the_author(); ?></h4>
                        <p><?php echo get_the_author_meta('description') ?: '블로그 작성자입니다.'; ?></p>
                        <div class="author-links">
                            <?php if (get_the_author_meta('url')): ?>
                                <a href="<?php the_author_meta('url'); ?>" target="_blank">웹사이트</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Related Posts -->
                <div class="related-posts">
                    <h3>관련 글</h3>
                    <div class="related-grid">
                        <?php
                        $categories = get_the_category();
                        $category_ids = array();
                        foreach ($categories as $category) {
                            $category_ids[] = $category->term_id;
                        }

                        $related_posts = new WP_Query(array(
                            'post_type' => 'post',
                            'posts_per_page' => 3,
                            'post__not_in' => array(get_the_ID()),
                            'category__in' => $category_ids,
                            'orderby' => 'rand'
                        ));

                        if ($related_posts->have_posts()) :
                            while ($related_posts->have_posts()) : $related_posts->the_post();
                        ?>
                                <div class="related-post-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>">
                                        </a>
                                    <?php endif; ?>
                                    <div class="related-content">
                                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                        <p><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                                        <span class="related-date"><?php echo get_the_date(); ?></span>
                                    </div>
                                </div>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                </div>

                <!-- Comments -->
                <?php if (comments_open() || get_comments_number()) : ?>
                    <div class="comments-section">
                        <?php comments_template(); ?>
                    </div>
                <?php endif; ?>
            </article>

        <?php endwhile; ?>

        <!-- Ad Space - Bottom -->
        <div class="ad-space">
            <p>광고 공간 - 하단 배너 (728x90)</p>
            <?php if (function_exists('wp_get_option') && get_option('content_bottom_ad_code')): ?>
                <?php echo get_option('content_bottom_ad_code'); ?>
            <?php endif; ?>
        </div>
    </main>

    <?php get_sidebar(); ?>
</div>

<style>
/* Single Post Styles */
.single-post {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 30px;
}

.post-header {
    padding: 30px 30px 20px 30px;
    border-bottom: 1px solid #eee;
}

.post-featured-image {
    width: 100%;
    height: 400px;
    overflow: hidden;
}

.featured-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.post-content-area {
    padding: 30px;
    line-height: 1.8;
    font-size: 1.1rem;
}

.post-content-area p {
    margin-bottom: 20px;
}

.post-tags {
    padding: 20px 30px;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.post-tags a {
    display: inline-block;
    background: #f8f9fa;
    color: #666;
    padding: 5px 12px;
    margin: 2px;
    border-radius: 15px;
    text-decoration: none;
    font-size: 0.9rem;
}

.social-sharing {
    padding: 20px 30px;
    border-bottom: 1px solid #eee;
}

.share-btn {
    display: inline-block;
    padding: 8px 15px;
    margin: 5px;
    border-radius: 5px;
    color: white;
    text-decoration: none;
    font-size: 0.9rem;
}

.share-btn.twitter { background: #1da1f2; }
.share-btn.facebook { background: #3b5998; }
.share-btn.linkedin { background: #0077b5; }
.share-btn.email { background: #666; }

.author-box {
    display: flex;
    align-items: center;
    padding: 30px;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
}

.author-avatar {
    margin-right: 20px;
}

.author-avatar img {
    border-radius: 50%;
}

.related-posts {
    padding: 30px;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.related-post-card {
    background: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
}

.related-post-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.related-content {
    padding: 15px;
}

.related-content h4 {
    margin-bottom: 10px;
}

.related-content h4 a {
    color: #333;
    text-decoration: none;
}

.related-date {
    color: #999;
    font-size: 0.85rem;
}

.breadcrumb {
    margin-bottom: 20px;
    padding: 10px 0;
    font-size: 0.9rem;
    color: #666;
}

.breadcrumb a {
    color: #3498db;
    text-decoration: none;
}

.comments-section {
    padding: 30px;
    border-top: 1px solid #eee;
}

@media (max-width: 768px) {
    .post-header,
    .post-content-area,
    .social-sharing,
    .author-box,
    .related-posts,
    .comments-section {
        padding: 20px;
    }
    
    .author-box {
        flex-direction: column;
        text-align: center;
    }
    
    .author-avatar {
        margin-right: 0;
        margin-bottom: 15px;
    }
}
</style>

<?php get_footer(); ?>
