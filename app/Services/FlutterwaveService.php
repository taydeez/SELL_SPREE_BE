<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */

// app/Services/FlutterwaveService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FlutterwaveService
{
    protected string $secretKey;
    protected string $publicKey;
    protected string $baseUrl = 'https://api.flutterwave.com/v3';

    protected string $providerName = 'flutterwave';

    public function __construct()
    {
        $this->secretKey = config('services.flutterwave.secret_key');
        $this->publicKey = config('services.flutterwave.public_key');
    }

    /**
     * Generate a unique transaction reference
     */
    public function generateReference(): string
    {
        return 'TXN-' . strtoupper(Str::random(10)) . '-' . time();
    }

    /**
     * Initialize a standard payment (redirect to Flutterwave checkout)
     */
    public function initializePayment(array $data): array
    {
        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/payments", $data);

        return $response->json();
    }

    /**
     * Verify a transaction by ID
     */
    public function verifyTransaction(string $transactionId): array
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/transactions/{$transactionId}/verify");

        return $response->json();
    }

    /**
     * Verify a transaction by reference
     */
    public function verifyByReference(string $txRef): array
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/transactions", [
                'tx_ref' => $txRef,
            ]);

        return $response->json();
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(string $signature): bool
    {
        return $signature === config('services.flutterwave.secret_hash');
    }

    /**
     * Get all banks for a country
     */
    public function getBanks(string $country = 'NG'): array
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/banks/{$country}");

        return $response->json();
    }


    public function verifyBankAccount(array $data)
    {
        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/accounts/resolve", $data);

        return $response->json();
    }


    public function createSubAccount(array $data): array
    {
        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/subaccounts", $data);

        return $response->json();
    }

    public function getSubAccounts(): array
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/subaccounts");

        return $response->json();
    }

    public function initiateTransfer(array $data): array
    {
        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/transfers", [
                'account_bank'   => $data['bank_code'],
                'account_number' => $data['account_number'],
                'amount'         => $data['amount'] / 100,
                'currency'       => 'NGN',
                'reference'      => $data['reference'],
                'narration'      => $data['narration'] ?? 'Affiliate earnings payout',
            ]);

        return $response->json();
    }

    public function verifyTransfer(string $id): array
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/transfers/{$id}");

        return $response->json();
    }



}
