<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Exceptions\MidtransConfigurationException;
use App\Models\Payment;
use App\Models\Rental;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function __construct(private readonly MidtransService $midtransService)
    {
    }

    /**
     * Generate Snap token for a rental (AJAX, requires auth).
     */
    public function generateToken(Request $request, Rental $rental): JsonResponse
    {
        abort_unless($rental->user_id === $request->user()->id, 403);

        $payment = $rental->payment;

        if (! $this->isMidtransPayment($payment)) {
            return response()->json([
                'error' => 'Pembayaran ini tidak dikelola oleh Midtrans.',
            ], 422);
        }

        // Re-use existing token if still valid
        if ($payment->snap_token && ! str_starts_with($payment->snap_token, 'SNAP-')) {
            return response()->json(['snap_token' => $payment->snap_token]);
        }

        try {
            $snapToken = $this->midtransService->generateSnapToken(
                $rental->load('details.costume'),
                $request->user()
            );

            $payment->update(['snap_token' => $snapToken]);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Throwable $e) {
            Log::error('Midtrans generateToken error', [
                'message' => $e->getMessage(),
                'rental_id' => $rental->id,
                'invoice_number' => $rental->invoice_number,
                'payment_id' => $payment->id,
                'midtrans' => $this->midtransService->configurationSummary(),
            ]);

            return response()->json([
                'error' => $e instanceof MidtransConfigurationException
                    ? 'Konfigurasi Midtrans di server belum sesuai. Cek mode production/sandbox dan API key.'
                    : 'Gagal membuat token pembayaran.',
            ], 500);
        }
    }

    /**
     * Handle Midtrans payment notification webhook.
     * No auth — Midtrans server calls this directly.
     */
    public function webhook(Request $request): Response
    {
        try {
            $data = $this->midtransService->parseNotification($request->getContent());
            $orderId = $data['order_id'];

            $rental = Rental::query()
                ->where('invoice_number', $orderId)
                ->first();

            if (! $rental) {
                Log::warning("Midtrans webhook ignored: unknown order_id [{$orderId}]");

                return response('IGNORED', 200);
            }

            $payment = $rental->payment;

            if (! $payment) {
                Log::warning("Midtrans webhook ignored: payment missing for [{$orderId}]");

                return response('IGNORED', 200);
            }

            $this->applyMidtransStatus($payment, $data);

            Log::info("Midtrans webhook: [{$orderId}] → {$payment->fresh()->status->value}");

            return response('OK', 200);
        } catch (\Throwable $e) {
            Log::error('Midtrans webhook error', [
                'message' => $e->getMessage(),
                'midtrans' => $this->midtransService->configurationSummary(),
            ]);

            return response('Error', 500);
        }
    }

    /**
     * Sync payment status on demand after Snap callbacks so UI does not wait for the webhook.
     */
    public function syncStatus(Request $request, Rental $rental): JsonResponse
    {
        abort_unless($rental->user_id === $request->user()->id, 403);

        $payment = $rental->payment;

        if (! $this->isMidtransPayment($payment)) {
            return response()->json([
                'error' => 'Pembayaran ini tidak dikelola oleh Midtrans.',
            ], 422);
        }

        try {
            $reference = $payment->midtrans_transaction_id ?: $rental->invoice_number;
            $data = $this->midtransService->getTransactionStatus($reference);
            $updatedPayment = $this->applyMidtransStatus($payment, $data);

            return response()->json([
                'status' => $updatedPayment->status->value,
                'label' => $updatedPayment->status->label(),
                'payment_type' => $updatedPayment->payment_type,
                'paid_at' => $updatedPayment->paid_at?->toIso8601String(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Midtrans syncStatus error', [
                'message' => $e->getMessage(),
                'rental_id' => $rental->id,
                'invoice_number' => $rental->invoice_number,
                'midtrans' => $this->midtransService->configurationSummary(),
            ]);

            return response()->json([
                'error' => $e instanceof MidtransConfigurationException
                    ? 'Konfigurasi Midtrans di server belum sesuai. Cek mode production/sandbox dan API key.'
                    : 'Gagal menyinkronkan status pembayaran.',
            ], 500);
        }
    }

    private function applyMidtransStatus(Payment $payment, array $data): Payment
    {
        $transactionStatus = $data['transaction_status'];
        $fraudStatus = $data['fraud_status'] ?? 'accept';
        $paymentType = $data['payment_type'] ?? null;
        $transactionId = $data['transaction_id'] ?? null;

        $resolvedStatus = $this->midtransService->resolvePaymentStatus($transactionStatus, $fraudStatus);
        $paymentStatus = PaymentStatus::from($resolvedStatus);

        $payment->update([
            'status' => $paymentStatus,
            'payment_type' => $paymentType ?? $payment->payment_type,
            'midtrans_transaction_id' => $transactionId ?? $payment->midtrans_transaction_id,
            'paid_at' => $paymentStatus === PaymentStatus::Settlement
                ? ($payment->paid_at ?? now())
                : $payment->paid_at,
        ]);

        return $payment->fresh();
    }

    private function isMidtransPayment(?Payment $payment): bool
    {
        if (! $payment) {
            return false;
        }

        $paymentType = strtolower(trim((string) ($payment->payment_type ?? '')));

        if ($paymentType === '' || str_contains($paymentType, 'midtrans')) {
            return true;
        }

    
        return filled($payment->snap_token) || filled($payment->midtrans_transaction_id);
    }
}
