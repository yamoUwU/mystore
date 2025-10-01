<?php

return [
    'statuses' => [
        'draft' => 'مسودة',
        'pending' => 'معلق',
        'published' => 'منشور',
    ],
    'system_updater_steps' => [
        'download' => 'تنزيل ملفات التحديث',
        'update_files' => 'تحديث ملفات النظام',
        'update_database' => 'تحديث قواعد البيانات',
        'publish_core_assets' => 'نشر أصول النواة',
        'publish_packages_assets' => 'نشر أصول الحزم',
        'clean_up' => 'تنظيف ملفات تحديث النظام',
        'done' => 'تم تحديث النظام بنجاح',

        'messages' => [
            'download' => 'جاري تنزيل ملفات التحديث...',
            'update_files' => 'جاري تحديث ملفات النظام...',
            'update_database' => 'جاري تحديث قواعد البيانات...',
            'publish_core_assets' => 'جاري نشر أصول النواة...',
            'publish_packages_assets' => 'جاري نشر أصول الحزم...',
            'clean_up' => 'جاري تنظيف ملفات تحديث النظام...',
            'done' => 'تم! سيتم تحديث متصفحك خلال 30 ثانية.',
        ],

        'failed_messages' => [
            'download' => 'لا يمكن تنزيل ملفات التحديث',
            'update_files' => 'لا يمكن تحديث ملفات النظام',
            'update_database' => 'لا يمكن تحديث قواعد البيانات',
            'publish_core_assets' => 'لا يمكن نشر أصول النواة',
            'publish_packages_assets' => 'لا يمكن نشر أصول الحزم',
            'clean_up' => 'لا يمكن تنظيف ملفات تحديث النظام',
        ],

        'success_messages' => [
            'download' => 'تم تنزيل ملفات التحديث بنجاح.',
            'update_files' => 'تم تحديث ملفات النظام بنجاح.',
            'update_database' => 'تم تحديث قواعد البيانات بنجاح.',
            'publish_core_assets' => 'تم نشر أصول النواة بنجاح.',
            'publish_packages_assets' => 'تم نشر أصول الحزم بنجاح.',
            'clean_up' => 'تم تنظيف ملفات تحديث النظام بنجاح.',
        ],
    ],
];