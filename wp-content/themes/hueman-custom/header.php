<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- SEO Meta Tags -->
    <?php if (is_single() || is_page()): ?>
        <meta name="description" content="<?php echo wp_trim_words(get_the_excerpt(), 30); ?>">
        <meta property="og:title" content="<?php the_title(); ?>">
        <meta property="og:description" content="<?php echo wp_trim_words(get_the_excerpt(), 30); ?>">
        <meta property="og:url" content="<?php the_permalink(); ?>">
        <?php if (has_post_thumbnail()): ?>
            <meta property="og:image" content="<?php the_post_thumbnail_url('large'); ?>">
        <?php endif; ?>
    <?php endif; ?>
    
    <!-- Fonts - 더 자연스러운 폰트 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@300;400;700&family=Kalam:wght@300;400;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- JSON-LD Structured Data for SEO -->
    <?php if (is_single()): ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BlogPosting",
        "headline": "<?php the_title(); ?>",
        "description": "<?php echo wp_trim_words(get_the_excerpt(), 30); ?>",
        "author": {
            "@type": "Person",
            "name": "<?php the_author(); ?>"
        },
        "datePublished": "<?php echo get_the_date('c'); ?>",
        "dateModified": "<?php echo get_the_modified_date('c'); ?>"
        <?php if (has_post_thumbnail()): ?>
        ,"image": "<?php the_post_thumbnail_url('large'); ?>"
        <?php endif; ?>
    }
    </script>
    <?php endif; ?>
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main">메인 콘텐츠로 건너뛰기</a>

<header class="site-header">
    <div class="header-container">
        <div class="site-branding">
            <h1 class="site-title">
                <a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
            </h1>
            <p class="site-description"><?php bloginfo('description'); ?></p>
        </div>
        
        <div class="header-search">
            <form role="search" method="get" action="<?php echo home_url('/'); ?>">
                <input type="search" placeholder="검색..." value="<?php echo get_search_query(); ?>" name="s">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>
</header>

<nav class="main-navigation">
    <div class="nav-container">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_class' => 'nav-menu',
            'container' => false,
            'fallback_cb' => 'hueman_default_menu'
        ));
        
        // Default menu if no menu is set
        function hueman_default_menu() {
            echo '<ul class="nav-menu">
                    <li><a href="' . home_url() . '">홈</a></li>
                    <li><a href="' . home_url() . '/category/design/">디자인</a></li>
                    <li><a href="' . home_url() . '/category/lifestyle/">라이프스타일</a></li>
                    <li><a href="' . home_url() . '/category/music/">음악</a></li>
                    <li><a href="' . home_url() . '/category/food/">음식</a></li>
                    <li><a href="' . home_url() . '/about/">소개</a></li>
                  </ul>';
        }
        ?>
    </div>
</nav>

<div id="main" class="site-main"><?php echo "\n"; ?>
