<?php

namespace RockCommerce\PaymentProviders;

use Mollie\Api\Resources\Payment as MolliePayment;
use ProcessWire\Module;
use ProcessWire\WireData;
use ProcessWire\WireException;
use RockCommerce\Order;
use RockCommerce\Payment;
use RockCommerce\PaymentProvider;
use RockMoney\Money;

use function ProcessWire\rockcommerce;
use function ProcessWire\wire;

require_once wire()->config->paths->siteModules . 'RockCommerce/classLoader/PaymentProvider.php';

class PaymentProviderMollie
extends WireData
implements Module, PaymentProvider
{
  public static function getModuleInfo(): array
  {
    return [
      'title' => 'Payment Provider Mollie',
      'version' => '1.0.0',
      'summary' => 'Mollie Payment Provider for RockCommerce',
      'icon' => 'money',
      'autoload' => true,
    ];
  }

  /**
   * Get an existing payment from Mollie
   */
  public function getPayment(string $id): Payment|false
  {
    /** @var RockMollie $mollie */
    $mollie = wire()->modules->get('RockMollie');
    $payment = $mollie->api()->payments->get($id);
    if (!$payment instanceof MolliePayment) return false;
    return new Payment(
      id: $payment->id,
      ref: $payment,
      status: $payment->status,
    );
  }

  /**
   * Create a new payment for an order
   */
  public function createPayment(
    Order $order,
    array $data = [],
  ): Payment|false {
    if ($order->paymentId()) {
      throw new WireException("A payment for this order has already been created.");
    }

    // create mollie payment
    /** @var RockMollie $mollie */
    $mollie = wire()->modules->get('RockMollie');
    try {
      $total = $order->total(1);
      if (!$total instanceof Money) {
        throw new WireException("Invalid Price");
      }
      $data = [
        "amount" => [
          "currency" => (string)$total->money->getCurrency(), // EUR
          "value" => $total->formatMollie(), // 39.50
        ],
        "description" => (string)$order->title,
        "redirectUrl" => rockcommerce()->url($data['redirectUrl'] ?? '', true),
        "webhookUrl" => rockcommerce()->webhookUrl(),
        "metadata" => ["orderid" => $order->name],
      ];
      $molliePayment = $mollie->api()->payments->create($data);

      // Create and return Payment object
      $payment = new Payment(
        id: $molliePayment->id,
        ref: $molliePayment,
        status: $molliePayment->status,
      );

      // Save payment to order
      $order->setPayment($payment);

      return $payment;
    } catch (\Throwable $th) {
      rockcommerce()->log($th->getMessage());
    }
    return false;
  }
}
