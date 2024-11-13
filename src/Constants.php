<?php

namespace Tabby;

class Constants
{
    const REJECTION_REASON_NOT_AVAILABLE = [
        'ar' => 'نأسف، تابي غير قادرة على الموافقة على هذه العملية. الرجاء استخدام طريقة دفع أخرى.',
        'en' => 'Sorry, Tabby is unable to approve this purchase. Please use an alternative payment method for your order.',
    ];

    const REJECTION_REASON_ORDER_AMOUNT_TOO_HIGH = [
        'ar' => 'قيمة الطلب تفوق الحد الأقصى المسموح به حاليًا مع تابي. يُرجى تخفيض قيمة السلة أو استخدام وسيلة دفع أخرى.',
        'en' => 'This purchase is above your current spending limit with Tabby, try a smaller cart or use another payment method',
    ];

    const REJECTION_REASON_ORDER_AMOUNT_TOO_LOW = [
        'ar' => 'قيمة الطلب أقل من الحد الأدنى المطلوب لاستخدام خدمة تابي. يُرجى زيادة قيمة الطلب أو استخدام وسيلة دفع أخرى.',
        'en' => 'The purchase amount is below the minimum amount required to use Tabby, try adding more items or use another payment method',
    ];

    const PAYMENT_CANCELLATION_REASON = [
        'ar' => 'لقد ألغيت الدفعة. فضلاً حاول مجددًا أو اختر طريقة دفع أخرى.',
        'en' => 'You aborted the payment. Please retry or choose another payment method.',
    ];

    const PAYMENT_FAILURE_REASON = [
        'ar' => 'نأسف، تابي غير قادرة على الموافقة على هذه العملية. الرجاء استخدام طريقة دفع أخرى.',
        'en' => 'Sorry, Tabby is unable to approve this purchase. Please use an alternative payment method for your order',
    ];
}