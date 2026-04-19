<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class PayHereService
{
    public function checkoutHash(string $orderId, float $amount, string $currency = 'LKR'): string
    {
        $merchantId = (string) config('payhere.merchant_id');
        $secret = (string) config('payhere.merchant_secret');
        $amountFormatted = number_format($amount, 2, '.', '');

        return strtoupper(md5(
            $merchantId.
            $orderId.
            $amountFormatted.
            $currency.
            strtoupper(md5($secret))
        ));
    }

    /**
     * @param  array<string, string>  $payload
     */
    public function notifyIsValid(array $payload): bool
    {
        $merchantId = (string) ($payload['merchant_id'] ?? '');
        $orderId = (string) ($payload['order_id'] ?? '');
        $payhereAmount = (string) ($payload['payhere_amount'] ?? '');
        $payhereCurrency = (string) ($payload['payhere_currency'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $md5sigReceived = strtoupper((string) ($payload['md5sig'] ?? ''));
        $secret = (string) config('payhere.merchant_secret');

        $local = strtoupper(md5(
            $merchantId.
            $orderId.
            $payhereAmount.
            $payhereCurrency.
            $statusCode.
            strtoupper(md5($secret))
        ));

        $ok = hash_equals($local, $md5sigReceived);
        if (! $ok) {
            Log::warning('PayHere notify md5 mismatch', ['order_id' => $orderId]);
        }

        return $ok;
    }

    public function checkoutUrl(): string
    {
        return config('payhere.sandbox')
            ? 'https://sandbox.payhere.lk/pay/checkout'
            : 'https://www.payhere.lk/pay/checkout';
    }
}
