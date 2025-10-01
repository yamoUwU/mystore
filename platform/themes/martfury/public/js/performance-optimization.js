/**
 * تحسينات أداء الموقع
 * هذا الملف يحتوي على تحسينات لزيادة سرعة الموقع وتحسين تجربة المستخدم
 */

(function ($) {
    'use strict';

    /**
     * تحميل الصور بشكل كسول (Lazy Loading)
     * يقوم بتحميل الصور فقط عندما تظهر في نطاق الرؤية
     */
    function initLazyLoading() {
        const lazyImages = document.querySelectorAll('img[data-lazy-src]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const image = entry.target;
                        image.src = image.dataset.lazySrc;
                        image.removeAttribute('data-lazy-src');
                        imageObserver.unobserve(image);
                    }
                });
            });

            lazyImages.forEach(function(image) {
                imageObserver.observe(image);
            });
        } else {
            // Fallback for browsers that don't support IntersectionObserver
            lazyImages.forEach(function(image) {
                image.src = image.dataset.lazySrc;
                image.removeAttribute('data-lazy-src');
            });
        }
    }

    /**
     * تأخير تحميل السكربتات غير الضرورية
     * يقوم بتحميل السكربتات غير الضرورية بعد تحميل الصفحة
     */
    function deferNonCriticalScripts() {
        const deferScripts = document.querySelectorAll('script[data-defer]');
        
        window.addEventListener('load', function() {
            setTimeout(function() {
                deferScripts.forEach(function(script) {
                    const deferScript = document.createElement('script');
                    if (script.getAttribute('src')) {
                        deferScript.setAttribute('src', script.getAttribute('src'));
                    }
                    deferScript.textContent = script.textContent;
                    document.body.appendChild(deferScript);
                    script.parentNode.removeChild(script);
                });
            }, 100);
        });
    }

    /**
     * تحسين التمرير السلس
     * يضيف تمرير سلس للروابط الداخلية
     */
    function initSmoothScrolling() {
        $('a[href^="#"]:not([href="#"])').on('click', function(event) {
            const target = $(this.getAttribute('href'));
            
            if (target.length) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        });
    }

    /**
     * تحسين تحميل المنتجات
     * يقوم بتحميل المنتجات بشكل تدريجي عند التمرير
     */
    function initInfiniteScroll() {
        if ($('.ps-products-listing').length) {
            let loading = false;
            let lastScrollTop = 0;
            
            $(window).scroll(function() {
                const st = $(this).scrollTop();
                
                if (st > lastScrollTop) {
                    // Scrolling down
                    const productsWrapper = $('.ps-products-listing');
                    const threshold = productsWrapper.offset().top + productsWrapper.outerHeight() - $(window).height() - 300;
                    
                    if (st > threshold && !loading && $('.ps-products-listing .ps-product').length < 100) {
                        loading = true;
                        
                        // Add loading indicator
                        const loadingIndicator = $('<div class="ps-loading-indicator"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                        productsWrapper.append(loadingIndicator);
                        
                        // Simulate loading more products (in a real implementation, this would be an AJAX call)
                        setTimeout(function() {
                            loadingIndicator.remove();
                            loading = false;
                        }, 1000);
                    }
                }
                
                lastScrollTop = st;
            });
        }
    }

    /**
     * تحسين التنقل على الأجهزة المحمولة
     * يقوم بتحسين تجربة التنقل على الأجهزة المحمولة
     */
    function optimizeMobileNavigation() {
        if ($(window).width() < 992) {
            // تحسين قائمة التنقل على الأجهزة المحمولة
            $('.menu-toggle').on('click', function(e) {
                e.preventDefault();
                const menu = $('.navigation');
                
                if (menu.hasClass('active')) {
                    menu.removeClass('active');
                    $('body').removeClass('navigation-open');
                } else {
                    menu.addClass('active');
                    $('body').addClass('navigation-open');
                }
            });
            
            // إغلاق القائمة عند النقر خارجها
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.navigation').length && !$(e.target).closest('.menu-toggle').length) {
                    $('.navigation').removeClass('active');
                    $('body').removeClass('navigation-open');
                }
            });
        }
    }

    /**
     * تحسين أداء السلة
     * يقوم بتحسين أداء السلة وتقليل عدد الطلبات
     */
    function optimizeCartPerformance() {
        // تحديث السلة بشكل متزامن
        let cartUpdateTimeout;
        
        $('.ps-cart__content').on('change', '.qty-input', function() {
            clearTimeout(cartUpdateTimeout);
            
            cartUpdateTimeout = setTimeout(function() {
                // هنا يمكن إضافة كود لتحديث السلة
            }, 500);
        });
    }

    /**
     * تحسين تحميل الصفحة
     * يقوم بتحسين تحميل الصفحة وتقليل وقت التحميل
     */
    function optimizePageLoad() {
        // تأخير تحميل الصور في الشرائح غير النشطة
        $('.ps-carousel').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
            const slideToLoad = $('.ps-carousel .slick-slide').eq(nextSlide);
            
            slideToLoad.find('img[data-lazy-src]').each(function() {
                this.src = this.dataset.lazySrc;
                this.removeAttribute('data-lazy-src');
            });
        });
    }

    /**
     * تحسين البحث
     * يقوم بتحسين أداء البحث وتقليل عدد الطلبات
     */
    function optimizeSearch() {
        let searchTimeout;
        
        $('.ps-search-box input').on('input', function() {
            clearTimeout(searchTimeout);
            
            searchTimeout = setTimeout(function() {
                // هنا يمكن إضافة كود للبحث
            }, 500);
        });
    }

    /**
     * تهيئة جميع التحسينات
     */
    function init() {
        initLazyLoading();
        deferNonCriticalScripts();
        initSmoothScrolling();
        initInfiniteScroll();
        optimizeMobileNavigation();
        optimizeCartPerformance();
        optimizePageLoad();
        optimizeSearch();
    }

    // تشغيل التحسينات عند تحميل الصفحة
    $(document).ready(function() {
        init();
    });

})(jQuery);