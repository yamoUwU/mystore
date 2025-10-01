@php
use Botble\Base\Facades\BaseHelper;
use Botble\Theme\Facades\Theme;
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! BaseHelper::googleFonts('https://fonts.googleapis.com/css2?family=' . urlencode(theme_option('primary_font', 'Work Sans')) . ':wght@300;400;500;600;700&family=Tajawal:wght@300;400;500;700;800&display=swap') !!}

    <style>
        :root {
            --color-1st: {{ theme_option('primary_color', '#00a69c') }};
            --primary-color: {{ theme_option('primary_color', '#00a69c') }};
            --color-2nd: {{ theme_option('secondary_color', '#2d3e50') }};
            --secondary-color: {{ theme_option('secondary_color', '#2d3e50') }};
            --primary-font: '{{ theme_option('primary_font', 'Work Sans') }}', sans-serif;
            --button-text-color: {{ theme_option('button_text_color', '#ffffff') }};
            --header-text-color: {{ theme_option('header_text_color', '#2d3e50') }};
            --header-button-background-color: {{ theme_option('header_button_background_color', '#00a69c') }};
            --header-button-text-color: {{ theme_option('header_button_text_color', '#ffffff') }};
            --header-text-hover-color: {{ theme_option('header_text_hover_color', '#00a69c') }};
            --header-text-accent-color: {{ theme_option('header_text_accent_color', '#00a69c') }};
            --header-diliver-border-color: {{ BaseHelper::hexToRgba(theme_option('header_text_color', '#2d3e50'), 0.15) }};
        }
    </style>

    @php
        Theme::asset()->remove('language-css');
        Theme::asset()->container('footer')->remove('language-public-js');
        Theme::asset()->container('footer')->remove('simple-slider-owl-carousel-css');
        Theme::asset()->container('footer')->remove('simple-slider-owl-carousel-js');
        Theme::asset()->container('footer')->remove('simple-slider-css');
        Theme::asset()->container('footer')->remove('simple-slider-js');
    @endphp

    <!-- تحسينات الألوان والخطوط للذوق العربي -->
    <link rel="stylesheet" href="{{ Theme::asset()->url('css/arabic-style.css') }}">
    
    <!-- تحسينات تجربة المستخدم على الأجهزة المحمولة -->
    <link rel="stylesheet" href="{{ Theme::asset()->url('css/mobile-optimization.css') }}">
    
    <!-- تحسينات سرعة الموقع وأدائه -->
    <link rel="stylesheet" href="{{ Theme::asset()->url('css/performance-optimization.css') }}">
    
    <!-- تحسينات تصميم صفحة المنتج -->
    <link rel="stylesheet" href="{{ Theme::asset()->url('css/product-page-enhancement.css') }}">
    
    <!-- تحسينات تصميم سلة التسوق وعملية الدفع -->
    <link rel="stylesheet" href="{{ Theme::asset()->url('css/cart-checkout-enhancement.css') }}">
    
    <!-- تصحيحات الاتجاه من اليمين إلى اليسار -->
    <link rel="stylesheet" href="{{ Theme::asset()->url('css/rtl-fixes.css') }}">

    {!! Theme::header() !!}
</head>
