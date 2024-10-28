<?php

namespace Tabby\Examples;

use App\Http\Controllers\Controller;
use Exception;

// Import Tabby Service
use Tabby\Services\TabbyService;
use Tabby\Models\TabbyBuyer;
use Tabby\Models\TabbyOrder;
use Tabby\Models\TabbyShippingAddress;
use Tabby\Models\TabbyOrderItem;

class ExampleController extends Controller
{
    public function getWebUrl()
    {
        try {
            $tabbyService = new TabbyService(merchantCode: 'xxx', publicKey: 'xxx', secretKey: 'xxx');

            $buyer = new TabbyBuyer(
                phone: '500000001',
                email: 'card.success@tabby.ai',
                name: 'Ahmed Ali',
                dob: '1990-08-24',
            );

            $order = new TabbyOrder(
                referenceId: 'order-001',
                items: [
                    new TabbyOrderItem(
                        title: 'Product Name',
                        description: 'Product Description',
                        quantity: 1,
                        unitPrice: 100,
                        referenceId: 'prod-001',
                        category: 'electronics'
                    ),
                ],
            );

            $shippingAddress = new TabbyShippingAddress(
                city: 'Al-Khobar',
                address: 'Street Address',
                zip: '12345',
            );

            $sessionData = $tabbyService->createSession(
                amount: 200,
                buyer: $buyer,
                order: $order,
                shippingAddress: $shippingAddress,
                description: 'order description',
                successCallback: 'https://example.com/success',
                cancelCallback: 'https://example.com/cancel',
                failureCallback: 'https://example.com/failure',
                // 'ar',            // optional
                // $buyerHistory,   // optional
                // $orderHistory,   // optional
            );

            $webUrl = $tabbyService->getPaymentUrl($sessionData);

            return $webUrl;
        } catch (Exception $e) {
            // -- Handle the error
        }
    }

    public function retrievePayment()
    {
        try {
            $tabbyService = new TabbyService(merchantCode: 'xxx', publicKey: 'xxx', secretKey: 'xxx');

            $paymentData = $tabbyService->retrievePayment('payment-001');

            return $paymentData;
        } catch (Exception $e) {
            // -- Handle the error
        }
    }
}
