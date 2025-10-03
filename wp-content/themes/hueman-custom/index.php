<?php get_header(); ?>

<div class="site-content">
    <main class="main-content">
        
        <!-- Ad Space - Header Banner -->
        <div class="ad-space">
            <p>광고 공간 - 헤더 배너 (728x90)</p>
            <?php if (function_exists('wp_get_option') && get_option('header_ad_code')): ?>
                <?php echo get_option('header_ad_code'); ?>
            <?php endif; ?>
        </div>

        <div class="post-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article class="post-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title(); ?>" class="post-thumbnail">
                        <?php else: ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/images/default-post.jpg" alt="<?php the_title(); ?>" class="post-thumbnail">
                        <?php endif; ?>
                        
                        <div class="post-content">
                            <?php
                            $categories = get_the_category();
                            if (!empty($categories)) {
                                echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="post-category">' . esc_html($categories[0]->name) . '</a>';
                            }
                            ?>
                            
                            <h2 class="post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            
                            <div class="post-meta">
                                <span class="post-date"><?php echo get_the_date(); ?></span>
                                <span class="post-author">by <?php the_author(); ?></span>
                                <span class="post-comments"><?php comments_number('0 댓글', '1 댓글', '% 댓글'); ?></span>
                            </div>
                            
                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="read-more">더 읽기</a>
                        </div>
                    </article>
                <?php endwhile; ?>
                
                <!-- Pagination -->
                <div class="pagination">
                    <?php
                    echo paginate_links(array(
                        'prev_text' => '« 이전',
                        'next_text' => '다음 »',
                    ));
                    ?>
                </div>
                
            <?php else : ?>
                <div class="no-posts">
                    <h2>게시물이 없습니다</h2>
                    <p>아직 게시된 글이 없습니다.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Ad Space - Content Bottom -->
        <div class="ad-space">
            <p>광고 공간 - 콘텐츠 하단 (728x90)</p>
            <?php if (function_exists('wp_get_option') && get_option('content_bottom_ad_code')): ?>
                <?php echo get_option('content_bottom_ad_code'); ?>
            <?php endif; ?>
        </div>
    </main>

    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>

