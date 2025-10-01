<?php

return [
    'product_specification' => 'مواصفات المنتج',
    'specification_groups' => [
        'title' => 'مجموعات المواصفات',
        'menu_name' => 'المجموعات',

        'create' => [
            'title' => 'إنشاء مجموعة مواصفات',
        ],

        'edit' => [
            'title' => 'تعديل مجموعة المواصفات ":name"',
        ],
    ],

    'specification_attributes' => [
        'title' => 'خصائص المواصفات',
        'menu_name' => 'الخصائص',

        'group' => 'المجموعة المرتبطة',
        'group_placeholder' => 'اختر أي مجموعة',
        'type' => 'نوع الحقل',
        'default_value' => 'القيمة الافتراضية',
        'options' => [
            'heading' => 'الخيارات',

            'add' => [
                'label' => 'إضافة خيار جديد',
            ],
        ],

        'create' => [
            'title' => 'إنشاء خاصية مواصفات',
        ],

        'edit' => [
            'title' => 'تعديل خاصية المواصفات ":name"',
        ],
    ],

    'specification_tables' => [
        'title' => 'جداول المواصفات',
        'menu_name' => 'الجداول',

        'create' => [
            'title' => 'إنشاء جدول مواصفات',
        ],

        'edit' => [
            'title' => 'تعديل جدول المواصفات ":name"',
        ],

        'fields' => [
            'groups' => 'اختر المجموعات للعرض في هذا الجدول',
            'name' => 'اسم المجموعة',
            'assigned_groups' => 'المجموعات المخصصة',
            'sorting' => 'الترتيب',
        ],
    ],

    'product' => [
        'specification_table' => [
            'options' => 'الخيارات',
            'title' => 'جدول المواصفات',
            'select_none' => 'لا شيء',
            'description' => 'اختر جدول المواصفات للعرض في هذا المنتج',
            'group' => 'المجموعة',
            'attribute' => 'الخاصية',
            'value' => 'قيمة الخاصية',
            'hide' => 'إخفاء',
            'sorting' => 'الترتيب',
        ],
    ],

    'enums' => [
        'field_types' => [
            'text' => 'نص',
            'textarea' => 'منطقة نص',
            'select' => 'قائمة منسدلة',
            'checkbox' => 'مربع اختيار',
            'radio' => 'زر راديو',
        ],
    ],
];