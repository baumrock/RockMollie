# RockMollie

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

Can be paid via the checkout url:

```
checkout => stdClass
href => "https://www.mollie.com/payscreen/select-method/Hq3RjMCQQJ" (57)
type => "text/html" (9)
```

## SEPA Lastschrift

```php
$mollie = new \Mollie\Api\MollieApiClient();
$mollie->setApiKey("xxx");

$customer = $mollie->customers->create([
  "name" => "API Test @ ".date("Y-m-d H:i:s"),
  "email" => "test@baumrock.com",
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
  
  "description" => "Test via Tracy",
  "webhookUrl"  => "https://www.baumrock.com/mollie-webhook/",
]);
db($payment);
```
