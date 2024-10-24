# Tabby Laravel Package

The **Tabby Laravel Package** (`tabbyai/laravel`) provides an easy-to-use integration with Tabby, a payment gateway service, allowing developers to create seamless checkout sessions within their Laravel applications.

## Features

- Create Tabby checkout sessions easily.
- Set buyer, order, and shipping details.
- Configurable success, cancel, and failure callbacks.
- Support for various currencies, with SAR as default.
- Fully customizable via the Tabby API.

## Installation

To install the package via Composer, run:

```bash
composer require tabbyai/laravel
```

## Usage

### Initializing the Service

To use the Tabby Service, you need to initialize it with the required credentials (Merchant Code, Public Key, Secret Key, and optional Currency).

```php
use Tabby\Services\TabbyService;

$tabbyService = new TabbyService(
    merchantCode: 'xxx',
    publicKey: 'xxx',
    secretKey: 'xxx'
);
```

### Creating a Checkout Session

To create a checkout session, you will need the buyer’s information, buyer history, order details, order history, and shipping address.

```php
use Tabby\Models\TabbyBuyer;
use Tabby\Models\TabbyBuyerHistory;
use Tabby\Models\TabbyOrder;
use Tabby\Models\TabbyOrderHistory;
use Tabby\Models\TabbyShippingAddress;
use Tabby\Models\TabbyOrderItem;

try {
    // Sample buyer data
    $buyer = new TabbyBuyer(
        phone: '500000001',
        email: 'card.success@tabby.ai',
        name: 'John Doe',
        dob: '1990-01-01',
    );

    // Sample buyer history data
    $buyerHistory = new TabbyBuyerHistory(
        registeredSince: '2020-01-01T14:15:22Z', // optional
    );

    // Sample order data
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

    // Sample order history data
    $orderHistory = new TabbyOrderHistory(
        amount: 0.0
    );

    // Sample shipping address data
    $shippingAddress = new TabbyShippingAddress(
        city: 'Riyadh',
        address: 'Street Address',
        zip: '12345',
    );

    // Create session and get the payment URL
    $webUrl = $tabbyService->createSession(
        amount: 100,
        buyer: $buyer,
        buyerHistory: $buyerHistory,
        order: $order,
        orderHistory: $orderHistory,
        shippingAddress: $shippingAddress,
        successCallback: 'https://example.com/success', // optional
        cancelCallback: 'https://example.com/cancel', // optional
        failureCallback: 'https://example.com/failure', // optional
        lang: 'ar' // optional
    );

    // Redirect to the payment page
    return redirect($webUrl);
} catch (Exception $e) {
    // Handle exceptions
    return response()->json(['error' => $e->getMessage()], 500);
}
```

## Exception Handling

The package throws exceptions for any failed API requests or missing configuration. Ensure to handle them properly in your code to provide a smooth user experience.

## License

This package is open-source software licensed under the [MIT license](LICENSE).
