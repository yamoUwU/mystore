<?php

return [
    // بريد إلكتروني للعميل عند طلب جديد
    'customer_new_order_title' => 'تم الطلب بنجاح!',
    'customer_new_order_greeting' => 'عزيزي :customer_name،',
    'customer_new_order_message' => 'شكراً لك لشراء منتجاتنا، سنتواصل معك عبر الهاتف <strong>:customer_phone</strong> لتأكيد الطلب!',
    'customer_new_order_customer_info' => 'معلومات العميل',
    'customer_new_order_name' => 'الاسم',
    'customer_new_order_phone' => 'الهاتف',
    'customer_new_order_email' => 'البريد الإلكتروني',
    'customer_new_order_address' => 'العنوان',
    'customer_new_order_products' => 'إليك ما طلبته:',
    'customer_new_order_note' => 'ملاحظة',
    'customer_new_order_shipping_method' => 'طريقة الشحن',
    'customer_new_order_order_number' => 'رقم الطلب',
    'customer_new_order_payment_method' => 'طريقة الدفع',

    // بريد إلكتروني للمدير عند طلب جديد
    'admin_new_order_title' => 'تلقيت طلباً جديداً من :site_title',
    'admin_new_order_greeting' => 'مرحباً أيها المدير،',
    'admin_new_order_message' => 'تم وضع طلب جديد بنجاح من قبل :customer_name',
    'admin_new_order_customer_info' => 'معلومات العميل',
    'admin_new_order_name' => 'الاسم',
    'admin_new_order_phone' => 'الهاتف',
    'admin_new_order_email' => 'البريد الإلكتروني',
    'admin_new_order_address' => 'العنوان',
    'admin_new_order_products' => 'تفاصيل الطلب:',
    'admin_new_order_note' => 'ملاحظة',
    'admin_new_order_total' => 'الإجمالي',
    'admin_new_order_shipping_method' => 'طريقة الشحن',
    'admin_new_order_order_number' => 'رقم الطلب',
    'admin_new_order_payment_method' => 'طريقة الدفع',

    // بريد إلكتروني تأكيد الطلب
    'order_confirmation_title' => 'تأكيد الطلب #:order_id',
    'order_confirmation_greeting' => 'مرحباً :customer_name،',
    'order_confirmation_message' => 'تم تأكيد طلبك بنجاح. إليك تفاصيل طلبك:',
    'order_confirmation_order_info' => 'معلومات الطلب',
    'order_confirmation_order_number' => 'رقم الطلب',
    'order_confirmation_order_date' => 'تاريخ الطلب',
    'order_confirmation_order_status' => 'حالة الطلب',
    'order_confirmation_payment_method' => 'طريقة الدفع',
    'order_confirmation_shipping_method' => 'طريقة الشحن',

    // بريد إلكتروني تحديث حالة الطلب
    'order_status_changed_title' => 'تم تحديث حالة طلبك #:order_id',
    'order_status_changed_greeting' => 'مرحباً :customer_name،',
    'order_status_changed_message' => 'تم تحديث حالة طلبك إلى: :order_status',

    // بريد إلكتروني الشحن
    'order_shipped_title' => 'تم شحن طلبك #:order_id',
    'order_shipped_greeting' => 'مرحباً :customer_name،',
    'order_shipped_message' => 'تم شحن طلبك. رقم التتبع: :tracking_number',

    // بريد إلكتروني الإلغاء
    'order_cancelled_title' => 'تم إلغاء طلبك #:order_id',
    'order_cancelled_greeting' => 'مرحباً :customer_name،',
    'order_cancelled_message' => 'تم إلغاء طلبك. إذا كان لديك أي أسئلة، يرجى التواصل معنا.',

    // بريد إلكتروني الإرجاع
    'order_returned_title' => 'تم إرجاع طلبك #:order_id',
    'order_returned_greeting' => 'مرحباً :customer_name،',
    'order_returned_message' => 'تم إرجاع طلبك بنجاح. سيتم رد المبلغ خلال 3-5 أيام عمل.',

    // بريد إلكتروني المنتج الرقمي
    'digital_product_title' => 'رابط تحميل منتجك الرقمي',
    'digital_product_greeting' => 'مرحباً :customer_name،',
    'digital_product_message' => 'شكراً لشرائك منتجنا الرقمي. يمكنك تحميله من الرابط أدناه:',
    'digital_product_download_link' => 'رابط التحميل',
    'digital_product_note' => 'ملاحظة: هذا الرابط صالح لمدة :expires_at أيام.',

    // بريد إلكتروني استعادة كلمة المرور
    'password_reminder_title' => 'إعادة تعيين كلمة المرور',
    'password_reminder_greeting' => 'مرحباً :customer_name،',
    'password_reminder_message' => 'تلقيت هذا البريد الإلكتروني لأنك طلبت إعادة تعيين كلمة المرور لحسابك.',
    'password_reminder_button' => 'إعادة تعيين كلمة المرور',
    'password_reminder_expire' => 'رابط إعادة تعيين كلمة المرور سينتهي خلال :count دقيقة.',
    'password_reminder_note' => 'إذا لم تطلب إعادة تعيين كلمة المرور، فلا حاجة لاتخاذ أي إجراء.',

    // بريد إلكتروني التحقق من البريد الإلكتروني
    'verify_email_title' => 'تحقق من عنوان بريدك الإلكتروني',
    'verify_email_greeting' => 'مرحباً :customer_name،',
    'verify_email_message' => 'يرجى النقر على الزر أدناه للتحقق من عنوان بريدك الإلكتروني.',
    'verify_email_button' => 'تحقق من البريد الإلكتروني',
    'verify_email_note' => 'إذا لم تقم بإنشاء حساب، فلا حاجة لاتخاذ أي إجراء.',

    // بريد إلكتروني الترحيب
    'welcome_title' => 'مرحباً بك في :site_title',
    'welcome_greeting' => 'مرحباً :customer_name،',
    'welcome_message' => 'مرحباً بك في موقعنا! نحن سعداء لانضمامك إلينا.',
    'welcome_button' => 'ابدأ التسوق',

    // بريد إلكتروني المراجعة
    'review_request_title' => 'شارك تجربتك مع منتجاتنا',
    'review_request_greeting' => 'مرحباً :customer_name،',
    'review_request_message' => 'نأمل أن تكون راضياً عن مشترياتك. يرجى مشاركة تجربتك معنا.',
    'review_request_button' => 'اكتب مراجعة',

    // بريد إلكتروني الفاتورة
    'invoice_title' => 'فاتورة طلبك #:order_id',
    'invoice_greeting' => 'مرحباً :customer_name،',
    'invoice_message' => 'إليك فاتورة طلبك الأخير.',
    'invoice_download' => 'تحميل الفاتورة',

    // متغيرات عامة
    'order_id' => 'رقم الطلب',
    'order_date' => 'تاريخ الطلب',
    'order_status' => 'حالة الطلب',
    'customer_name' => 'اسم العميل',
    'customer_email' => 'بريد العميل',
    'customer_phone' => 'هاتف العميل',
    'customer_address' => 'عنوان العميل',
    'payment_method' => 'طريقة الدفع',
    'shipping_method' => 'طريقة الشحن',
    'tracking_number' => 'رقم التتبع',
    'total_amount' => 'المبلغ الإجمالي',
    'subtotal' => 'المجموع الفرعي',
    'tax_amount' => 'مبلغ الضريبة',
    'shipping_amount' => 'مبلغ الشحن',
    'discount_amount' => 'مبلغ الخصم',
    'product_name' => 'اسم المنتج',
    'product_price' => 'سعر المنتج',
    'product_quantity' => 'كمية المنتج',
    'site_title' => 'عنوان الموقع',
    'site_url' => 'رابط الموقع',
    'site_logo' => 'شعار الموقع',
    'footer_text' => 'شكراً لك لاختيارك موقعنا!',
];
