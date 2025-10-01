/**
 * مفعل اللغة العربية التلقائي
 * هذا الملف يقوم بتفعيل اللغة العربية تلقائياً عند تحميل الصفحة
 */

(function ($) {
    'use strict';

    /**
     * تفعيل اللغة العربية
     */
    function activateArabicLanguage() {
        // التحقق من وجود زر تبديل اللغة
        const languageSwitcher = document.querySelector('.language-switcher');
        
        if (languageSwitcher) {
            // البحث عن رابط اللغة العربية
            const arabicLink = Array.from(languageSwitcher.querySelectorAll('a')).find(link => {
                return link.textContent.includes('العربية') || 
                       link.getAttribute('href').includes('/ar') || 
                       link.getAttribute('data-language') === 'ar';
            });
            
            // النقر على رابط اللغة العربية إذا وجد
            if (arabicLink && !document.documentElement.getAttribute('lang').includes('ar')) {
                arabicLink.click();
            }
        }
    }

    /**
     * إضافة سمة الاتجاه للصفحة
     */
    function setRtlDirection() {
        document.documentElement.setAttribute('dir', 'rtl');
        document.body.classList.add('rtl');
    }

    /**
     * تهيئة المكونات
     */
    function init() {
        // تفعيل اللغة العربية بعد تحميل الصفحة
        window.addEventListener('load', function() {
            setTimeout(function() {
                activateArabicLanguage();
                setRtlDirection();
            }, 500);
        });
    }

    // تشغيل التهيئة
    $(document).ready(function() {
        init();
    });

})(jQuery);