/**
 * Hueman Custom Theme JavaScript
 * 수익형 워드프레스 블로그 테마
 */

jQuery(document).ready(function($) {
    
    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 800);
        }
    });
    
    // Mobile menu toggle
    $('.menu-toggle').on('click', function() {
        $('.nav-menu').toggleClass('mobile-active');
        $(this).toggleClass('active');
    });
    
    // Search form enhancement
    $('.header-search input[type="search"]').on('focus', function() {
        $(this).parent().addClass('search-active');
    }).on('blur', function() {
        if ($(this).val() === '') {
            $(this).parent().removeClass('search-active');
        }
    });
    
    // Sticky header on scroll
    var header = $('.site-header');
    var headerOffset = header.offset().top;
    
    $(window).scroll(function() {
        if ($(this).scrollTop() > headerOffset + 100) {
            header.addClass('sticky-header');
        } else {
            header.removeClass('sticky-header');
        }
    });
    
    // Image lazy loading fallback for older browsers
    if ('IntersectionObserver' in window) {
        var imageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        $('.lazy').each(function() {
            imageObserver.observe(this);
        });
    }
    
    // Social sharing popup windows
    $('.social-sharing a').on('click', function(e) {
        var url = $(this).attr('href');
        if (url.indexOf('mailto:') !== 0) {
            e.preventDefault();
            window.open(url, 'share', 'width=600,height=400,scrollbars=yes,resizable=yes');
        }
    });
    
    // Reading progress bar
    if ($('.single-post').length) {
        var progressBar = $('<div class="reading-progress"><div class="progress-fill"></div></div>');
        $('body').append(progressBar);
        
        $(window).scroll(function() {
            var windowTop = $(window).scrollTop();
            var documentHeight = $(document).height();
            var windowHeight = $(window).height();
            var progress = windowTop / (documentHeight - windowHeight);
            var progressPercentage = progress * 100;
            
            $('.progress-fill').css('width', progressPercentage + '%');
        });
    }
    
    // Newsletter signup enhancement
    $('form[action="#"]').on('submit', function(e) {
        e.preventDefault();
        var email = $(this).find('input[type="email"]').val();
        
        if (email && isValidEmail(email)) {
            // Here you can integrate with your email service
            // For now, we'll show a simple success message
            $(this).html('<p style="color: white; text-align: center;">감사합니다! 곧 연락드리겠습니다.</p>');
            
            // You can add integration with services like:
            // - Mailchimp
            // - ConvertKit
            // - AWeber
            // - Or your custom backend
            
        } else {
            alert('올바른 이메일 주소를 입력해주세요.');
        }
    });
    
    // Email validation function
    function isValidEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Post view tracking (enhanced)
    if (typeof post_id !== 'undefined') {
        // Track time spent on page
        var startTime = Date.now();
        var tracked = false;
        
        // Track after user spends 30 seconds on page
        setTimeout(function() {
            if (!tracked) {
                trackPostView();
                tracked = true;
            }
        }, 30000);
        
        // Also track when user scrolls past 50% of content
        $(window).scroll(function() {
            var scrollPercent = ($(window).scrollTop() / ($(document).height() - $(window).height())) * 100;
            if (scrollPercent > 50 && !tracked) {
                trackPostView();
                tracked = true;
            }
        });
        
        function trackPostView() {
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'track_post_view',
                    post_id: post_id,
                    nonce: ajax_object.nonce
                }
            });
        }
    }
    
    // Ad block detection (optional)
    setTimeout(function() {
        var adTest = $('<div class="ads ad adsbox doubleclick ad-placement carbon-ads" style="height:1px;"></div>');
        $('body').append(adTest);
        
        setTimeout(function() {
            if (adTest.height() === 0 || adTest.css('display') === 'none') {
                // Ad blocker detected - you can show a message or alternative content
                $('.ad-space').html('<p style="text-align:center; color:#666;">광고를 통해 무료 콘텐츠를 제공하고 있습니다. 광고 차단을 해제해주시면 감사하겠습니다!</p>');
            }
            adTest.remove();
        }, 100);
    }, 1000);
    
    // Related posts carousel (if more than 3)
    if ($('.related-grid .related-post-card').length > 3) {
        $('.related-grid').addClass('carousel-mode');
        // You can add carousel functionality here with a library like Swiper.js
    }
    
    // Comments enhancement
    $('.comment-reply-link').on('click', function(e) {
        e.preventDefault();
        var commentId = $(this).data('comment-id');
        $('#respond').insertAfter($(this).closest('.comment'));
        $('#comment_parent').val(commentId);
        $('#cancel-comment-reply-link').show();
        $('html, body').animate({
            scrollTop: $('#respond').offset().top - 100
        }, 500);
    });
    
    // Back to top button
    var backToTop = $('<a href="#" class="back-to-top"><i class="fas fa-chevron-up"></i></a>');
    $('body').append(backToTop);
    
    $(window).scroll(function() {
        if ($(this).scrollTop() > 300) {
            backToTop.fadeIn();
        } else {
            backToTop.fadeOut();
        }
    });
    
    backToTop.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop: 0}, 800);
    });
});

// Performance monitoring
(function() {
    if ('performance' in window && 'timing' in window.performance) {
        window.addEventListener('load', function() {
            var timing = window.performance.timing;
            var loadTime = timing.loadEventEnd - timing.navigationStart;
            
            // Log load time (you can send this to analytics)
            console.log('Page load time:', loadTime + 'ms');
            
            // If load time is too slow, you might want to optimize
            if (loadTime > 3000) {
                console.warn('Slow page load detected. Consider optimizing images and scripts.');
            }
        });
    }
})();
