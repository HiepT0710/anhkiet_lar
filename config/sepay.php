<?php

return [
    // SePay merchant config
    'merchant_id' => env('SEPAY_MERCHANT_ID', ''),
    'api_key'     => env('SEPAY_API_KEY', ''),
    // Base API endpoint for SePay (if provided by SePay). If empty, the service will fallback to a simple QR generator.
    'api_url'     => env('SEPAY_API_URL', ''),
    // Optional account details for generating SePay hosted QR images (qr.sepay.vn)
    'account'     => env('SEPAY_ACCOUNT', ''),
    'bank'        => env('SEPAY_BANK', ''), // e.g. MBBank
];


