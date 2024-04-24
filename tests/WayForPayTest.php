<?php

use PHPUnit\Framework\TestCase;
use Ostapovich\WayForPay;

class WayForPayTest extends TestCase
{
    public function testPaySuccess()
    {
        $account = 'your_account';
        $secretKey = 'your_secret_key';
        $domainMerchant = 'your_domain_merchant';
        
        $wayForPay = new WayForPay($account, $secretKey, $domainMerchant);
        
        $orderNum = 123456;
        $amount = 100.00;
        $currencyCode = 'USD';
        $returnUrl = 'https://example.com/return';
        $webhookUrl = 'https://example.com/webhook';
        $orderName = 'Test Order';
        
        $response = $wayForPay->pay($orderNum, $amount, $currencyCode, $returnUrl, $webhookUrl, $orderName);
        
        $this->assertIsArray($response);
        $this->assertArrayHasKey('reason', $response);
        $this->assertNull($response['reason']);
    }
    
    public function testPayWithError()
    {
        $account = 'your_account';
        $secretKey = 'invalid_secret_key'; // Providing an invalid secret key intentionally to simulate an error
        $domainMerchant = 'your_domain_merchant';
        
        $wayForPay = new WayForPay($account, $secretKey, $domainMerchant);
        
        $orderNum = 123456;
        $amount = 100.00;
        $currencyCode = 'USD';
        $returnUrl = 'https://example.com/return';
        $webhookUrl = 'https://example.com/webhook';
        $orderName = 'Test Order';
        
        $response = $wayForPay->pay($orderNum, $amount, $currencyCode, $returnUrl, $webhookUrl, $orderName);
        
        $this->assertIsArray($response);
        $this->assertArrayHasKey('reason', $response);
        $this->assertNotNull($response['reason']);
    }
}
