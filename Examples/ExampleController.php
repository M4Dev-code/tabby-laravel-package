<?php

namespace Tabby\Examples;

use App\Http\Controllers\Controller;
use Exception;

// Import Tabby Service
use Tabby\Services\TabbyService;
use Tabby\Models\TabbyBuyer;
use Tabby\Models\TabbyBuyerHistory;
use Tabby\Models\TabbyOrder;
use Tabby\Models\TabbyOrderHistory;
use Tabby\Models\TabbyShippingAddress;
use Tabby\Models\TabbyOrderItem;

class ExampleController extends Controller
{
    public function getWebUrl()
    {
        try {
            $tabbyService = new TabbyService(
                merchantCode: '',
                publicKey: '',
                secretKey: '',
            );

            $buyer = new TabbyBuyer(
                phone: '500000001',
                email: 'card.success@tabby.ai',
                name: 'string',
                dob: '2019-08-24',
            );

            $buyerHistory = new TabbyBuyerHistory(
                registeredSince: '2019-08-24T14:15:22Z',
            );

            $order = new TabbyOrder(
                referenceId: 'abc',
                items: [
                    new TabbyOrderItem(
                        title: 'a',
                        description: 'b',
                        quantity: 1,
                        unitPrice: 5,
                        referenceId: '123',
                        category: 'tea'
                    ),
                ],
            );

            $orderHistory = new TabbyOrderHistory(
                amount: 0.0
            );

            $shippingAddress = new TabbyShippingAddress(
                city: 'khobar',
                address: 'address',
                zip: '12345',
            );

            $webUrl = $tabbyService->createSession(
                200,
                $buyer,
                $buyerHistory,
                $order,
                $orderHistory,
                $shippingAddress,
                '/success',
                '/cancel',
                'failure',
            );

            return $webUrl;
        } catch (Exception $e) {
            // -- Handle the error
        }
    }
}
