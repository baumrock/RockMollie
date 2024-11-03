# RockMollie

Docs are under construction 😇

This module is a wrapper for the Mollie API. Please refer to the [Mollie API docs](https://docs.mollie.com/docs/getting-started) for more information.

## Setup

To use RockMollie, you need to set the Mollie API key in the `config.php` file:

```php
// for development
$config->mollieApiKey = 'test_XXX';

// for production
$config->mollieApiKey = 'live_XXX';
```

## Create a simple payment

```php
$mollie = wire()->modules->get('RockMollie')->api();
$payment = $mollie->payments->create([
  "amount" => [
    "currency" => "EUR",
    "value" => "10.00"
  ],
  "description" => "My first API payment",
  "redirectUrl" => "https://www.example.com/thanks/",
  "webhookUrl"  => "https://www.example.com/mollie-webhook/",
]);
db($payment);
```

## SEPA Direct Debit Example

```php
$mollie = wire()->modules->get('RockMollie')->api();

$customer = $mollie->customers->create([
  "name" => "API Test @ ".date("Y-m-d H:i:s"),
  "email" => "test@example.com",
]);
db($customer);

$mandate = $mollie->customers->get($customer->id)->createMandate([
  "method" => \Mollie\Api\Types\MandateMethod::DIRECTDEBIT,
  "consumerName" => "John Doe",
  "consumerAccount" => "NL55INGB0000000000",
  "consumerBic" => "INGBNL2A",
  "signatureDate" => "2018-05-07",
  "mandateReference" => "YOUR-COMPANY-MD13804",
]);
db($mandate);

$payment = $mollie->payments->create([
  "amount" => [
    "currency" => "EUR",
    "value" => "25.00"
  ],
  "customerId" => $customer->id,

  // even for one-off payments the sequenceType is "recurring"
  // otherwise it does not work - I don't know why...
  "sequenceType" => "recurring",

  "description" => "Testing RockMollie",
  "webhookUrl"  => "https://www.example.com/my-mollie-webhook/",
]);
db($payment);
```
