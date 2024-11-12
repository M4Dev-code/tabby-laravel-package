# Tabby Laravel Package

The **Tabby Laravel Package** (`tabbyai/laravel`) provides an easy-to-use integration with Tabby, a payment gateway service, allowing developers to create seamless checkout sessions within their Laravel applications.

## Features

1. **Easy Setup**: Install via Composer and configure with API keys.
2. **Checkout Management**: Create and retrieve checkout sessions, including payment URLs for easy redirection.

3. **Payment Management**: Retrieve, update, capture, refund, close payments, and list payments with filters.

4. **Webhook Management**: Register, retrieve, update, and delete webhooks to receive real-time event notifications.

5. **Error Handling**: Built-in exception management for smoother integration and user experience.

## How to Use

### Step 1: Installation

To install the package via Composer, run:

```bash
composer require tabbyai/laravel
```

---

### Step 2: Configuration

Before you use the `TabbyService`, make sure you have the required API keys and configuration values. You need:

- `merchantCode` (your Tabby merchant code)
- `publicKey` (your Tabby public key)
- `secretKey` (your Tabby secret key)
- `currency` (default is `SAR` but can be changed)

---

### Step 3: Create an Instance of `TabbyService`

You need to initialize an instance of `TabbyService` by passing the necessary configuration values.

```php
use Tabby\Services\TabbyService;

$tabbyService = new TabbyService(
    merchantCode: 'your_merchant_code',
    publicKey: 'your_public_key',
    secretKey: 'your_secret_key',
    currency: 'SAR' // Optional, default is SAR
);
```

---

### Step 4: Using Checkout Functions

#### Creating a Checkout Session

To create a checkout session, you'll need the buyer's information, order details, and shipping address.

```php
use Tabby\Models\Buyer;
use Tabby\Models\Order;
use Tabby\Models\ShippingAddress;
use Tabby\Models\OrderItem;

try {
    // Sample buyer data
    $buyer = new Buyer(
        phone: '500000001',
        email: 'card.success@tabby.ai',
        name: 'John Doe',
        dob: '1990-01-01',
    );

    // Sample order data
    $order = new Order(
        referenceId: 'order-001',
        items: [
            new OrderItem(
                title: 'Product Name',
                category: 'electronics',
                unitPrice: 100,
                quantity: 1,
                referenceId: 'prod-001',
                description: 'Product Description',
            ),
        ],
    );

    // Sample shipping address data
    $shippingAddress = new ShippingAddress(
        city: 'Al-Khobar',
        address: 'Street Address',
        zip: '12345',
    );

    // Create a checkout session
    $checkoutSession = $tabbyService->createSession(
        amount: 200,
        buyer: $buyer,
        order: $order,
        shippingAddress: $shippingAddress,
        description: 'order description',
        successCallback: 'https://example.com/success',
        cancelCallback: 'https://example.com/cancel',
        failureCallback: 'https://example.com/failure',
        // lang: 'ar',            // optional
        // buyerHistory: $buyerHistory,   // optional
        // orderHistory: $orderHistory,   // optional
    );

    // Fetch the payment url from the checkout session
    $paymentUrl = $checkoutSession->getPaymentUrl();

    // Redirect to the payment page
    return redirect($paymentUrl);
} catch (Exception $e) {
    // Handle exceptions
    return response()->json(['error' => $e->getMessage()], 500);
}
```

This will return a `CheckoutSession` object containing session details.

#### Retrieving a Checkout Session

To retrieve a previously created checkout session, use the session ID:

```php
$checkoutSession = $tabbyService->retrieveCheckoutSession('session_id_here');
```

This will return a `CheckoutSession` object containing session details.

---

### Step 5: Using Payment Functions

#### Retrieve a Payment

To retrieve details of a specific payment by its ID:

```php
$payment = $tabbyService->retrievePayment('payment_id_here');
```

#### Update a Payment

```php
$updatedPayment = $tabbyService->updatePayment(
    paymentId: 'payment_id_here',  // Payment ID
    referenceId: 'new_reference_id',  // Optional updated reference ID
    deliveryTracking: [
        ['tracking_number' => 'xxx', 'courier_code' => 'yyy']
    ],
);
```

#### Capture a Payment

Capture a payment after the transaction:

```php
$capturedPayment = $tabbyService->capturePayment(
    paymentId: 'payment_id_here',  // Payment ID
    amount: 100.00, // Amount
    referenceId: 'reference_id_here' // Optional reference ID
);
```

#### Refund a Payment

```php
$refundedPayment = $tabbyService->refundPayment(
    paymentId: 'payment_id_here',  // Payment ID
    amount: 50.00, // Amount to refund
    referenceId: 'reference_id_here', // Optional reference ID
    reason: 'refund reason' // Optional
);
```

#### Close a Payment

```php
$closedPayment = $tabbyService->closePayment('payment_id_here');
```

#### List of Payments

```php
list($payments, $pagination) = $tabbyService->listPayments(
    createdAtGte: '', // Optional
    createdAtLte: '', // Optional
    status: null, // Optional
    limit: 10, // Optional
    offset: 0, // Optional
);
```

---

### Step 6: Using Webhook Functions

#### Register a Webhook

To register a new webhook for receiving events from Tabby:

```php
$webhook = $tabbyService->registerWebhook(
    url: 'https://your-domain.com/webhook-url',  // URL to receive webhook notifications
    isTest: true,  // Test mode (optional)
    headerTitle: 'Custom-Header-Title',  // Optional
    headerValue: 'Custom-Header-Value'  // Optional
);
```

#### Retrieve All Webhooks

To retrieve all registered webhooks:

```php
$webhooks = $tabbyService->retrieveAllWebhooks();
```

#### Retrieve a Specific Webhook

```php
$webhook = $tabbyService->retrieveWebhook('webhook_id_here');
```

#### Update a Webhook

```php
$webhook = $tabbyService->updateWebhook(
    webhookId: 'webhook_id_here',
    url: 'https://your-domain.com/webhook-url',  // URL to receive webhook notifications
    isTest: true,  // Test mode
);
```

#### Delete a Specific Webhook

```php
$webhook = $tabbyService->deleteWebhook('webhook_id_here');
```

---

## Exception Handling

The package throws exceptions for any failed API requests or missing configuration. Ensure to handle them properly in your code to provide a smooth user experience.

```php
try {
    // your code
} catch (Exception $e) {
    // handle the error
}
```

## License

This package is open-source software licensed under the [MIT license](LICENSE).
