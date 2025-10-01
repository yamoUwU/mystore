<?php

return [
    'name' => 'البائعون غير المتحققين',
    'verify' => 'التحقق من البائع ":name"',
    'forms' => [
        'email' => 'البريد الإلكتروني',
        'store_name' => 'اسم المتجر',
        'store_phone' => 'هاتف المتجر',
        'vendor_phone' => 'الهاتف',
        'verify_vendor' => 'التحقق من البائع',
        'registered_at' => 'تاريخ التسجيل',
        'certificate' => 'الشهادة',
        'government_id' => 'الهوية الحكومية',
    ],
    'view_certificate' => 'عرض الشهادة',
    'view_government_id' => 'عرض الهوية الحكومية',
    'approve' => 'موافقة',
    'reject' => 'رفض',
    'approve_vendor_confirmation' => 'تأكيد الموافقة على البائع',
    'approve_vendor_confirmation_description' => 'هل أنت متأكد من أنك تريد الموافقة على :vendor للبيع في هذا الموقع؟',
    'reject_vendor_confirmation' => 'تأكيد رفض البائع',
    'reject_vendor_confirmation_description' => 'هل أنت متأكد من أنك تريد رفض :vendor للبيع في هذا الموقع؟',
    'new_vendor_notifications' => [
        'new_vendor' => 'بائع جديد',
        'view' => 'عرض',
        'description' => ':customer قام بالتسجيل ولكن لم يتم التحقق منه.',
    ],
];