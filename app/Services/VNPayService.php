<?php

namespace App\Services;

class VNPayService
{
    public function createPaymentUrl(array $params): string
    {
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_Url = config('vnpay.vnp_Url');
        $vnp_Returnurl = route('payment.vnpay.return');

        $vnp_TxnRef = $params['order_code'];
        $vnp_OrderInfo = $params['order_desc'] ?? 'Thanh toan don hang ' . $vnp_TxnRef;
        $vnp_OrderType = 'other';
        $vnp_Amount = $params['amount'] * 100; // VNPay yêu cầu *100
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
            $hashdata .= $key . "=" . $value . '&';
        }

        $query = rtrim($query, '&');
        $hashdata = rtrim($hashdata, '&');

        $vnp_Url = $vnp_Url . "?" . $query;
        if ($vnp_HashSecret) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnp_Url;
    }

    public function validateReturn(array $params): bool
    {
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_SecureHash = $params['vnp_SecureHash'] ?? '';
        unset($params['vnp_SecureHash'], $params['vnp_SecureHashType']);

        ksort($params);
        $hashData = "";
        foreach ($params as $key => $value) {
            $hashData .= $key . "=" . $value . '&';
        }
        $hashData = rtrim($hashData, '&');

        $calcHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        return $calcHash === $vnp_SecureHash;
    }
}


