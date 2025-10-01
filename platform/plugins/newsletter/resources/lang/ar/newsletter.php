<?php

return [
    'name' => 'النشرات الإخبارية',
    'newsletter_form' => 'نموذج النشرات الإخبارية',
    'description' => 'عرض وحذف مشتركي النشرة الإخبارية',
    'settings' => [
        'email' => [
            'templates' => [
                'title' => 'النشرة الإخبارية',
                'description' => 'تكوين قوالب بريد النشرة الإخبارية',
                'to_admin' => [
                    'title' => 'بريد إلكتروني يُرسل للمدير',
                    'description' => 'قالب إرسال بريد إلكتروني للمدير',
                    'subject' => 'مستخدم جديد اشترك في نشرتك الإخبارية',
                    'newsletter_email' => 'بريد المستخدم الذي اشترك في النشرة الإخبارية',
                ],
                'to_user' => [
                    'title' => 'بريد إلكتروني يُرسل للمستخدم',
                    'description' => 'قالب إرسال بريد إلكتروني للمشترك',
                    'subject' => '{{ site_title }}: تم تأكيد الاشتراك!',
                    'newsletter_name' => 'الاسم الكامل للمستخدم الذي اشترك في النشرة الإخبارية',
                    'newsletter_email' => 'بريد المستخدم الذي اشترك في النشرة الإخبارية',
                    'newsletter_unsubscribe_link' => 'رابط إلغاء الاشتراك في النشرة الإخبارية',
                    'newsletter_unsubscribe_url' => 'رابط إلغاء الاشتراك في النشرة الإخبارية',
                ],
            ],
        ],
        'title' => 'النشرة الإخبارية',
        'panel_description' => 'عرض وتحديث إعدادات النشرة الإخبارية',
        'description' => 'إعدادات النشرة الإخبارية (إرسال تلقائي لبريد النشرة الإخبارية إلى SendGrid، Mailchimp... عندما يشترك شخص في النشرة الإخبارية على الموقع).',
        'mailchimp_api_key' => 'مفتاح Mailchimp API',
        'mailchimp_list_id' => 'معرف قائمة Mailchimp',
        'mailchimp_list' => 'قائمة Mailchimp',
        'sendgrid_api_key' => 'مفتاح Sendgrid API',
        'sendgrid_list_id' => 'معرف قائمة Sendgrid',
        'sendgrid_list' => 'قائمة Sendgrid',
        'enable_newsletter_contacts_list_api' => 'تفعيل API قائمة جهات اتصال النشرة الإخبارية؟',
    ],
    'statuses' => [
        'subscribed' => 'مشترك',
        'unsubscribed' => 'غير مشترك',
    ],
    'email_templates' => [
        // Admin email template
        'admin_new_subscriber_title' => 'مشترك جديد',
        'admin_new_subscriber_message' => 'مستخدم جديد اشترك في نشرتك الإخبارية:',

        // Subscriber email template
        'subscriber_success_title' => 'تم الاشتراك بنجاح',
        'subscriber_thank_you_message' => 'شكراً لاشتراكك في نشرتنا الإخبارية!',
        'subscriber_unsubscribe_instruction' => 'لإلغاء الاشتراك في النشرة الإخبارية، انقر :newsletter_unsubscribe_link',
    ],
];