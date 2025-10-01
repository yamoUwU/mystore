<?php

use Botble\Base\Facades\BaseHelper;
use Botble\Language\Facades\Language;
use Botble\Theme\Facades\Theme;

app()->booted(function (): void {
    // تفعيل اللغة العربية كلغة افتراضية
    if (is_plugin_active('language')) {
        add_action(BASE_ACTION_META_BOXES, function ($priority, $data): void {
            if ($data instanceof \Botble\Base\Models\BaseModel && Language::getCurrentLocale() !== 'ar') {
                Language::setCurrentLocale('ar');
                // Fix: Remove argument from setDefaultLocale
                Language::setDefaultLocale();
            }
        }, 55, 2);

        // إضافة سمة الاتجاه من اليمين إلى اليسار للصفحة
        add_filter(THEME_FRONT_HEADER, function ($html): string {
            return str_replace('<html lang="', '<html dir="rtl" lang="', $html);
        }, 55);

        // إضافة الخط العربي
        add_filter(THEME_FRONT_HEADER, function ($html): string {
            $arabicFont = '<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">';
            return str_replace('</head>', $arabicFont . '</head>', $html);
        }, 55);

        // تعديل إعدادات اللغة
        add_filter(BASE_FILTER_BEFORE_RENDER_FORM, function ($form, $data): object {
            // Fix: Check for form class without using the undefined class directly
            if (class_exists('\\Botble\\Language\\Forms\\LanguageForm') && get_class($form) === '\\Botble\\Language\\Forms\\LanguageForm') {
                $form->setValues([
                    'lang_name' => 'العربية',
                    'lang_code' => 'ar',
                    'lang_locale' => 'ar',
                    'lang_flag' => 'sa',
                    'lang_is_rtl' => '1',
                    'lang_order' => '0',
                    'lang_is_default' => '1',
                ]);
            }
            return $form;
        }, 120, 2);

        // تعديل إعدادات الثيم
        add_filter(THEME_FRONT_HEADER, function ($html): string {
            Theme::asset()
                ->container('footer')
                ->usePath(false)
                ->add('arabic-language-activator', 'themes/martfury/js/arabic-language-activator.js', ['jquery']);

            return $html;
        }, 55);
    }
});