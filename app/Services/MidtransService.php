<?php

namespace App\Services;

use App\Exceptions\MidtransConfigurationException;
use App\Models\Rental;
use App\Models\User;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    private readonly string $serverKey;

    private readonly string $clientKey;

    private readonly bool $isProduction;

    public function __construct()
    {
        $this->serverKey = trim((string) config('midtrans.server_key', ''));
        $this->clientKey = trim((string) config('midtrans.client_key', ''));
        $this->isProduction = (bool) config('midtrans.is_production', false);

        Config::$serverKey = $this->serverKey;
        Config::$clientKey = $this->clientKey;
        Config::$isProduction = $this->isProduction;
        Config::$isSanitized = (bool) config('midtrans.is_sanitized', true);
        Config::$is3ds = (bool) config('midtrans.is_3ds', true);
    }

    /**
     * Generate Snap token for a rental.
     */
    public function generateSnapToken(Rental $rental, User $user): string
    {
        $this->assertSnapConfiguration();

        $duration = max($rental->rental_duration_days, 1);

        $params = [
            'transaction_details' => [
                'order_id'     => $rental->invoice_number,
                'gross_amount' => (int) $rental->total_price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
            ],
            'item_details' => $rental->details->map(function ($detail) use ($duration) {
                return [
                    'id'       => (string) $detail->costume_id,
                    'price'    => (int) $detail->unit_price,
                    'quantity' => $detail->quantity * $duration,
                    'name'     => $detail->costume->name,
                ];
            })->toArray(),
            'callbacks' => [
                'finish' => route('customer.rentals.show', $rental->id),
                'error' => route('customer.rentals.show', $rental->id),
                'unfinish' => route('customer.rentals.show', $rental->id),
            ],
        ];

        return Snap::getSnapToken($params);
    }

    /**
     * Verify and parse Midtrans webhook notification.
     */
    public function parseNotification(?string $rawPayload = null): array
    {
        $payload = json_decode($rawPayload ?? file_get_contents('php://input'), true, flags: JSON_THROW_ON_ERROR);
        $reference = $payload['transaction_id'] ?? $payload['order_id'] ?? null;

        if (! $reference && ! $this->canUseRawNotification($payload)) {
            throw new \InvalidArgumentException('Midtrans notification does not contain transaction_id or order_id.');
        }

        if ($reference) {
            try {
                return $this->getTransactionStatus($reference);
            } catch (\Throwable $e) {
                if (! $this->canUseRawNotification($payload)) {
                    throw $e;
                }
            }
        }

        return $this->normalizeNotificationPayload($payload);
    }

    /**
     * Fetch a transaction status from Midtrans using either order_id or transaction_id.
     */
    public function getTransactionStatus(string $reference): array
    {
        $this->assertServerConfiguration();

        $status = Transaction::status($reference);

        return [
            'order_id'           => $status->order_id,
            'transaction_status' => $status->transaction_status,
            'fraud_status'       => $status->fraud_status ?? 'accept',
            'payment_type'       => $status->payment_type ?? null,
            'transaction_id'     => $status->transaction_id ?? null,
            'gross_amount'       => $status->gross_amount ?? null,
        ];
    }

    /**
     * Map Midtrans transaction_status to our PaymentStatus.
     */
    public function resolvePaymentStatus(string $transactionStatus, string $fraudStatus = 'accept'): string
    {
        if ($transactionStatus === 'capture') {
            return $fraudStatus === 'challenge' ? 'pending' : 'settlement';
        }

        return match ($transactionStatus) {
            'settlement' => 'settlement',
            'pending'    => 'pending',
            'deny', 'cancel', 'failure' => 'cancel',
            'expire'     => 'expire',
            default      => 'pending',
        };
    }

    public function configurationSummary(): array
    {
        return [
            'is_production' => $this->isProduction,
            'snap_base_url' => Config::getSnapBaseUrl(),
            'server_key_present' => $this->serverKey !== '',
            'server_key_format' => $this->describeKeyFormat($this->serverKey),
            'client_key_present' => $this->clientKey !== '',
            'client_key_format' => $this->describeKeyFormat($this->clientKey),
        ];
    }

    private function canUseRawNotification(array $payload): bool
    {
        return filled($payload['order_id'] ?? null)
            && filled($payload['transaction_status'] ?? null);
    }

    private function normalizeNotificationPayload(array $payload): array
    {
        return [
            'order_id' => $payload['order_id'],
            'transaction_status' => $payload['transaction_status'],
            'fraud_status' => $payload['fraud_status'] ?? 'accept',
            'payment_type' => $payload['payment_type'] ?? null,
            'transaction_id' => $payload['transaction_id'] ?? null,
            'gross_amount' => $payload['gross_amount'] ?? null,
        ];
    }

    private function assertSnapConfiguration(): void
    {
        $this->assertKeyIsPresent('MIDTRANS_SERVER_KEY', $this->serverKey);
        $this->assertKeyIsPresent('MIDTRANS_CLIENT_KEY', $this->clientKey);
    }

    private function assertServerConfiguration(): void
    {
        $this->assertKeyIsPresent('MIDTRANS_SERVER_KEY', $this->serverKey);
    }

    private function assertKeyIsPresent(string $envName, string $value): void
    {
        if ($value === '') {
            throw new MidtransConfigurationException("{$envName} belum diisi di environment server.");
        }
    }

    private function describeKeyFormat(string $value): string
    {
        if (str_starts_with($value, 'SB-Mid-')) {
            return 'sb-mid-prefixed';
        }

        if (str_starts_with($value, 'Mid-')) {
            return 'mid-prefixed';
        }

        return 'unknown';
    }
}
