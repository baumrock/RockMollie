# RockMollie

Docs are under construction 😇

## Create a simple payment

```php
$mollie = new \Mollie\Api\MollieApiClient();
$mollie->setApiKey('xxx');
$payment = $mollie->payments->create([
  "amount" => [
    "currency" => "EUR",
    "value" => "10.00"
  ],
  "description" => "My first API payment",
  "redirectUrl" => "https://www.baumrock.com/thanks/",
  "webhookUrl"  => "https://www.baumrock.com/mollie-webhook/",
]);
db($payment);
```

## SEPA Direct Debit Example

```php
$mollie = new \Mollie\Api\MollieApiClient();
$mollie->setApiKey("xxx");

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
