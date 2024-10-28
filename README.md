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

$tabbyService = new TabbyService(merchantCode: 'xxx', publicKey: 'xxx', secretKey: 'xxx');
```

### Creating a Checkout Session

To create a checkout session, you will need the buyer’s information, buyer history, order details, order history, and shipping address.

```php
use Tabby\Models\TabbyBuyer;
use Tabby\Models\TabbyOrder;
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

    // Sample shipping address data
    $shippingAddress = new TabbyShippingAddress(
        city: 'Al-Khobar',
        address: 'Street Address',
        zip: '12345',
    );

    // Create session and get the payment URL
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

    // Fetch the payment url from the responsed data
    $webUrl = $tabbyService->getPaymentUrl($sessionData);

    // Redirect to the payment page
    return redirect($webUrl);
} catch (Exception $e) {
    // Handle exceptions
    return response()->json(['error' => $e->getMessage()], 500);
}
```

### Retrieve Payment

```php
try {
    // Fetch the payment data from tabby system
    $paymentData = $this->tabbyService->retrievePayment($request->payment_id);
} catch (Exception $e) {
    return response()->json(['error' => $e->getMessage()], 500);
}
```

## Exception Handling

The package throws exceptions for any failed API requests or missing configuration. Ensure to handle them properly in your code to provide a smooth user experience.

## License

This package is open-source software licensed under the [MIT license](LICENSE).
