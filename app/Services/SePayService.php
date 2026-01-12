<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SePayService
{
    protected Client $http;

    public function __construct()
    {
        $this->http = new Client([
            'timeout' => 10,
        ]);
    }

    /**
     * Create a QR for payment.
     *
     * Returns array with keys:
     *  - success: bool
     *  - qr_image: (string) URL or data URI to show the QR
     *  - payment_url: (string|null) link to payment page (if any)
     */
    public function createQr(array $params): array
    {
        $merchantId = config('sepay.merchant_id');
        $apiKey = config('sepay.api_key');
        $apiUrl = rtrim(config('sepay.api_url') ?? '', '/');
        // Read env directly to avoid issues when config cache/env is not loaded
        $sepayAccount = env('SEPAY_ACCOUNT', config('sepay.account'));
        $sepayBank = env('SEPAY_BANK', config('sepay.bank'));
        \Log::info('SePayService using account/bank', ['account' => $sepayAccount, 'bank' => $sepayBank]);

        $payload = [
            'merchant_id' => $merchantId,
            'order_code' => $params['order_code'] ?? null,
            'amount' => $params['amount'] ?? null,
            'return_url' => $params['return_url'] ?? null,
            'notify_url' => $params['notify_url'] ?? null,
        ];

        // If no remote API configured, fallback to generating a QR via Google Chart API
        // Prefer SePay hosted QR generator if account + bank configured
        if (empty($apiUrl) && !empty($sepayAccount) && !empty($sepayBank)) {
            $description = $payload['order_code'] ?? '';
            $amount = (int) ($payload['amount'] ?? 0);
            $qrImage = 'https://qr.sepay.vn/img?bank=' . urlencode($sepayBank)
                . '&acc=' . urlencode($sepayAccount)
                . '&template=compact'
                . '&amount=' . $amount
                . '&des=' . urlencode($description);

            return [
                'success' => true,
                'qr_image' => $qrImage,
                'payment_url' => null,
            ];
        }

        if (empty($apiUrl)) {
            $qrPayload = json_encode($payload);
            $qrImage = 'https://chart.googleapis.com/chart?cht=qr&chs=400x400&chl=' . urlencode($qrPayload);

            return [
                'success' => true,
                'qr_image' => $qrImage,
                'payment_url' => null,
            ];
        }

        try {
            $response = $this->http->post($apiUrl . '/create-qr', [
                'json' => $payload,
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => $apiKey ? 'Bearer ' . $apiKey : '',
                ],
            ]);

            $body = json_decode((string) $response->getBody(), true);
            if (isset($body['data']['qr_image']) || isset($body['data']['payment_url'])) {
                return [
                    'success' => true,
                    'qr_image' => $body['data']['qr_image'] ?? null,
                    'payment_url' => $body['data']['payment_url'] ?? null,
                ];
            }

            // Fallback: encode response as QR content
            $qrPayload = json_encode($body);
            $qrImage = 'https://chart.googleapis.com/chart?cht=qr&chs=400x400&chl=' . urlencode($qrPayload);

            return [
                'success' => true,
                'qr_image' => $qrImage,
                'payment_url' => null,
            ];
        } catch (\Throwable $ex) {
            Log::error('SePay createQr error: ' . $ex->getMessage());
            // last resort fallback QR
            $qrPayload = json_encode($payload);
            $qrImage = 'https://chart.googleapis.com/chart?cht=qr&chs=400x400&chl=' . urlencode($qrPayload);

            return [
                'success' => false,
                'qr_image' => $qrImage,
                'payment_url' => null,
            ];
        }
    }
}


