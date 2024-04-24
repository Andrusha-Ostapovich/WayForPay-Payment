<?php

namespace Ostapovich;

class WayForPay
{
    public string $url = 'https://secure.wayforpay.com/pay?behavior=offline';

    public string $account;
    public string $secret_key;
    public string $domain_merchant;
    public function __construct($account, $secret_key, $domain_merchant)
    {
        $this->account = $account;
        $this->secret_key = $secret_key;
        $this->domain_merchant = $domain_merchant;
    }

    public function pay(int $order_num, float $amount, string $currency_code = 'UAH', string $return_url = null, string $webhook_url, $order_name = 'Замовлення'): string|array
    {
        $time = time();
        $string = $this->account . ';' . $this->domain_merchant . ';' . $order_num . ';' . $time . ';' . $amount . ';' . $currency_code . ';' . $order_name . ';' . 1 . ';' . $order_num;
        $signature = hash_hmac("md5", $string, $this->secret_key);

        $data = [
            'merchantAccount' => $this->account,
            'returnUrl' => $return_url,
            'serviceUrl' => $webhook_url,
            'merchantDomainName' => $this->domain_merchant,
            'orderReference' => $order_num,
            'orderDate' => $time,
            'merchantSignature' => $signature,
            'amount' => $amount,
            'currency' => $currency_code,
            'productName' => [$order_name],
            'productPrice' => [$order_num],
            'productCount' => [1],
        ];

        // Визначення параметрів запиту
        $postFields = http_build_query($data);

        // Ініціалізація об'єкту CURL
        $ch = curl_init($this->url);

        // Налаштування параметрів CURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

        // Виконання HTTP-запиту
        $response = curl_exec($ch);

        // Закриття CURL-сеансу
        curl_close($ch);

        $responseData = json_decode($response, true);

        if (isset($responseData['reason'])) {
            return $responseData;
        }
        return $responseData;
    }
}
