<?php

namespace Tests\Feature;

use App\Exceptions\MidtransConfigurationException;
use App\Models\Costume;
use App\Models\Rental;
use App\Models\User;
use App\Services\JwtService;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class LuwungragiFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_homepage_displays_luwungragi_catalog(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Sewa Kebaya Luwungragi')
            ->assertSee('The Heritage Collection');
    }

    public function test_admin_can_login_and_receive_jwt_cookie(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@luwungragi.test',
            'password' => 'password123',
        ]);

        $response
            ->assertRedirect(route('admin.dashboard'))
            ->assertCookie(config('jwt.cookie_name'));
    }

    public function test_login_with_remember_me_uses_extended_jwt_ttl(): void
    {
        Carbon::setTestNow('2026-04-08 10:00:00');

        try {
            $response = $this->post('/login', [
                'email' => 'admin@luwungragi.test',
                'password' => 'password123',
                'remember' => '1',
            ]);

            $cookie = $response->getCookie(config('jwt.cookie_name'));

            $this->assertNotNull($cookie);
            $this->assertSame(
                now()->addMinutes(config('jwt.remember_ttl'))->timestamp,
                $cookie->getExpiresTime(),
            );

            $payload = app(JwtService::class)->decode($cookie->getValue());

            $this->assertNotNull($payload);
            $this->assertSame(
                config('jwt.remember_ttl') * 60,
                $payload['exp'] - $payload['iat'],
            );
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_customer_can_access_dashboard_using_jwt_cookie(): void
    {
        $customer = User::query()->where('email', 'ratri@luwungragi.test')->firstOrFail();
        $token = app(JwtService::class)->issueToken($customer);

        $response = $this
            ->withCookie(config('jwt.cookie_name'), $token)
            ->get(route('customer.orders'));

        $response
            ->assertOk()
            ->assertSee('kelola jadwal sewa busana Anda di sini', false)
            ->assertSee($customer->name);
    }

    public function test_customer_can_view_rental_details_and_see_download_pdf(): void
    {
        $customer = User::query()->where('email', 'ratri@luwungragi.test')->firstOrFail();
        $rental = Rental::query()->where('user_id', $customer->id)->firstOrFail();
        $token = app(JwtService::class)->issueToken($customer);

        $response = $this
            ->withCookie(config('jwt.cookie_name'), $token)
            ->get(route('customer.rentals.show', $rental));

        $response
            ->assertOk()
            ->assertSee('E-Invoice')
            ->assertSee('Download PDF')
            ->assertSee('1 Sesi');
    }

    public function test_customer_can_create_booking(): void
    {
        $customer  = User::query()->where('email', 'bagas@luwungragi.test')->firstOrFail();
        $costume   = Costume::query()->where('name', 'Kostum Wisuda Nusantara')->firstOrFail();
        $token     = app(JwtService::class)->issueToken($customer);
        $eventDate = now()->addDays(Rental::BOOKING_BUFFER_DAYS + 8)->toDateString();
        $sessions  = 1;

        $response = $this
            ->withCookie(config('jwt.cookie_name'), $token)
            ->post(route('customer.checkout.store'), [
                'costume_id'    => $costume->id,
                'event_date'    => $eventDate,
                'sessions'      => $sessions,
                'quantity'      => 1,
                'identity_card' => \Illuminate\Http\UploadedFile::fake()->image('ktp.jpg'),
            ]);

        $response->assertRedirect();

        $createdRental = Rental::query()
            ->where('user_id', $customer->id)
            ->whereDate('event_date', $eventDate)
            ->latest('id')
            ->firstOrFail();

        $totalDays = $sessions * Rental::SESSION_DAYS; // 5

        // rental_date = event - BOOKING_BUFFER_DAYS = now+11-3 = now+8
        $this->assertSame('pending', $createdRental->status->value);
        $this->assertSame($eventDate, $createdRental->event_date->toDateString());
        $this->assertSame(now()->addDays(8)->toDateString(), $createdRental->rental_date->toDateString());
        $this->assertSame(now()->addDays(9)->toDateString(), $createdRental->payment_due_date->toDateString());
        $this->assertSame(now()->addDays(10)->toDateString(), $createdRental->pickup_date->toDateString());
        // return_date = event + totalDays - 1 + RETURN_BUFFER_DAYS = now+11+4+1 = now+16
        $this->assertSame(now()->addDays(16)->toDateString(), $createdRental->return_date->toDateString());
        $this->assertSame($totalDays, $createdRental->rental_duration_days);
        $this->assertSame($sessions, $createdRental->sessions_count);
        $this->assertSame((float) $costume->rental_price, (float) $createdRental->total_price);
        $this->assertNotNull($createdRental->identity_card);
        $this->assertSame('Midtrans Snap', $createdRental->payment->payment_type);
    }

    public function test_admin_rental_detail_uses_one_combined_save_button(): void
    {
        $admin = User::query()->where('email', 'admin@luwungragi.test')->firstOrFail();
        $rental = Rental::query()->whereHas('payment')->firstOrFail();
        $token = app(JwtService::class)->issueToken($admin);

        $response = $this
            ->withCookie(config('jwt.cookie_name'), $token)
            ->get(route('admin.rentals.show', $rental));

        $response
            ->assertOk()
            ->assertSee('Transaksi Penyewaan')
            ->assertSee('detailRentalModal')
            ->assertSee($rental->invoice_number)
            ->assertSee('Simpan Perubahan')
            ->assertDontSee('Simpan Status')
            ->assertDontSee('Update Pembayaran')
            ->assertDontSee('Simpan Pengembalian');
    }

    public function test_admin_can_update_status_and_payment_without_processing_return(): void
    {
        $admin = User::query()->where('email', 'admin@luwungragi.test')->firstOrFail();
        $rental = Rental::query()
            ->where('status', 'pending')
            ->whereHas('payment', fn ($query) => $query->where('status', 'pending'))
            ->firstOrFail();
        $token = app(JwtService::class)->issueToken($admin);

        $response = $this
            ->withCookie(config('jwt.cookie_name'), $token)
            ->patch(route('admin.rentals.update', $rental), [
                'rental_status' => 'active',
                'payment_status' => 'settlement',
                'returned_date' => '',
                'damage_fee' => 0,
            ]);

        $response->assertRedirect();

        $rental = $rental->fresh(['payment', 'returnRecord']);

        $this->assertSame('active', $rental->status->value);
        $this->assertSame('settlement', $rental->payment->status->value);
        $this->assertNotNull($rental->payment->paid_at);
        $this->assertNull($rental->returnRecord);
    }

    public function test_admin_can_process_return_from_combined_form(): void
    {
        $admin = User::query()->where('email', 'admin@luwungragi.test')->firstOrFail();
        $rental = Rental::query()
            ->where('status', 'active')
            ->whereDoesntHave('returnRecord')
            ->whereHas('payment')
            ->firstOrFail();
        $token = app(JwtService::class)->issueToken($admin);

        $response = $this
            ->withCookie(config('jwt.cookie_name'), $token)
            ->patch(route('admin.rentals.update', $rental), [
                'rental_status' => 'active',
                'payment_status' => $rental->payment->status->value,
                'returned_date' => now()->toDateString(),
                'damage_fee' => 25000,
            ]);

        $response->assertRedirect();

        $rental = $rental->fresh(['payment', 'returnRecord']);

        $this->assertSame('completed', $rental->status->value);
        $this->assertNotNull($rental->returnRecord);
        $this->assertSame(now()->toDateString(), $rental->returnRecord->returned_date->toDateString());
        $this->assertSame(25000.0, (float) $rental->returnRecord->fine_amount);
        $this->assertSame('Damaged', $rental->returnRecord->return_status);
    }

    public function test_admin_late_return_fee_uses_rp15000_per_day(): void
    {
        $admin = User::query()->where('email', 'admin@luwungragi.test')->firstOrFail();
        $rental = Rental::query()
            ->where('status', 'active')
            ->whereDoesntHave('returnRecord')
            ->whereHas('payment')
            ->firstOrFail();
        $token = app(JwtService::class)->issueToken($admin);

        $response = $this
            ->withCookie(config('jwt.cookie_name'), $token)
            ->patch(route('admin.rentals.update', $rental), [
                'rental_status' => 'active',
                'payment_status' => $rental->payment->status->value,
                'returned_date' => $rental->return_due_date->copy()->addDays(2)->toDateString(),
                'damage_fee' => 0,
            ]);

        $response->assertRedirect();

        $rental = $rental->fresh(['returnRecord']);

        $this->assertNotNull($rental->returnRecord);
        $this->assertSame(30000.0, (float) $rental->returnRecord->fine_amount);
        $this->assertSame('Late', $rental->returnRecord->return_status);
    }

    public function test_midtrans_webhook_updates_pending_payment_to_settlement(): void
    {
        $rental = Rental::query()
            ->whereHas('payment', fn ($query) => $query
                ->where('status', 'pending'))
            ->firstOrFail();

        $this->partialMock(MidtransService::class, function ($mock) use ($rental): void {
            $mock->shouldReceive('parseNotification')
                ->once()
                ->andReturn([
                    'order_id' => $rental->invoice_number,
                    'transaction_status' => 'settlement',
                    'fraud_status' => 'accept',
                    'payment_type' => 'qris',
                    'transaction_id' => 'trx-webhook-001',
                ]);
        });

        $response = $this->postJson(route('midtrans.webhook'), [
            'transaction_id' => 'trx-webhook-001',
        ]);

        $response->assertOk();
        $response->assertContent('OK');

        $payment = $rental->payment()->firstOrFail()->fresh();

        $this->assertSame('settlement', $payment->status->value);
        $this->assertSame('qris', $payment->payment_type);
        $this->assertSame('trx-webhook-001', $payment->midtrans_transaction_id);
        $this->assertNotNull($payment->paid_at);
    }

    public function test_customer_can_sync_midtrans_status_after_snap_success(): void
    {
        $rental = Rental::query()
            ->whereHas('payment', fn ($query) => $query
                ->where('status', 'pending'))
            ->with('user')
            ->firstOrFail();
        $token = app(JwtService::class)->issueToken($rental->user);

        $this->partialMock(MidtransService::class, function ($mock) use ($rental): void {
            $mock->shouldReceive('getTransactionStatus')
                ->once()
                ->with($rental->invoice_number)
                ->andReturn([
                    'order_id' => $rental->invoice_number,
                    'transaction_status' => 'settlement',
                    'fraud_status' => 'accept',
                    'payment_type' => 'qris',
                    'transaction_id' => 'trx-sync-001',
                ]);
        });

        $response = $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->postJson(route('customer.rentals.midtrans.sync', $rental), [
                'order_id' => $rental->invoice_number,
                'transaction_id' => 'trx-sync-001',
            ]);

        $response
            ->assertOk()
            ->assertJson([
                'status' => 'settlement',
                'label' => 'Lunas',
                'payment_type' => 'qris',
            ]);

        $payment = $rental->payment()->firstOrFail()->fresh();

        $this->assertSame('settlement', $payment->status->value);
        $this->assertSame('qris', $payment->payment_type);
        $this->assertSame('trx-sync-001', $payment->midtrans_transaction_id);
        $this->assertNotNull($payment->paid_at);
    }

    public function test_customer_can_continue_pending_midtrans_payment_after_channel_is_recorded(): void
    {
        $rental = Rental::query()
            ->whereHas('payment', fn ($query) => $query
                ->where('status', 'pending'))
            ->with('user', 'payment')
            ->firstOrFail();
        $token = app(JwtService::class)->issueToken($rental->user);

        $rental->payment()->update([
            'payment_type' => 'qris',
            'snap_token' => 'snap-reuse-001',
        ]);

        $response = $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->postJson(route('customer.rentals.midtrans.token', $rental));

        $response
            ->assertOk()
            ->assertJson([
                'snap_token' => 'snap-reuse-001',
            ]);
    }

    public function test_customer_can_sync_pending_midtrans_status_after_channel_is_recorded(): void
    {
        $rental = Rental::query()
            ->whereHas('payment', fn ($query) => $query
                ->where('status', 'pending'))
            ->with('user', 'payment')
            ->firstOrFail();
        $token = app(JwtService::class)->issueToken($rental->user);

        $rental->payment()->update([
            'payment_type' => 'qris',
            'midtrans_transaction_id' => 'trx-pending-001',
        ]);

        $this->partialMock(MidtransService::class, function ($mock): void {
            $mock->shouldReceive('getTransactionStatus')
                ->once()
                ->with('trx-pending-001')
                ->andReturn([
                    'order_id' => 'ignored-by-controller',
                    'transaction_status' => 'pending',
                    'fraud_status' => 'accept',
                    'payment_type' => 'qris',
                    'transaction_id' => 'trx-pending-001',
                ]);
        });

        $response = $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->postJson(route('customer.rentals.midtrans.sync', $rental), [
                'transaction_id' => 'trx-pending-001',
            ]);

        $response
            ->assertOk()
            ->assertJson([
                'status' => 'pending',
                'label' => 'Pending',
                'payment_type' => 'qris',
            ]);

        $payment = $rental->payment()->firstOrFail()->fresh();

        $this->assertSame('pending', $payment->status->value);
        $this->assertSame('qris', $payment->payment_type);
        $this->assertSame('trx-pending-001', $payment->midtrans_transaction_id);
        $this->assertNull($payment->paid_at);
    }

    public function test_customer_receives_actionable_error_when_midtrans_configuration_is_invalid(): void
    {
        $rental = Rental::query()
            ->whereHas('payment', fn ($query) => $query
                ->where('status', 'pending'))
            ->with('user')
            ->firstOrFail();
        $token = app(JwtService::class)->issueToken($rental->user);

        $this->partialMock(MidtransService::class, function ($mock): void {
            $mock->shouldReceive('generateSnapToken')
                ->once()
                ->andThrow(new MidtransConfigurationException('MIDTRANS_SERVER_KEY mismatch'));
            $mock->shouldReceive('configurationSummary')
                ->once()
                ->andReturn([
                    'is_production' => false,
                    'snap_base_url' => 'https://app.sandbox.midtrans.com/snap/v1',
                    'server_key_present' => true,
                    'server_key_format' => 'mid-prefixed',
                    'client_key_present' => true,
                    'client_key_format' => 'mid-prefixed',
                ]);
        });

        $response = $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->postJson(route('customer.rentals.midtrans.token', $rental));

        $response
            ->assertStatus(500)
            ->assertJson([
                'error' => 'Konfigurasi Midtrans di server belum sesuai. Cek mode production/sandbox dan API key.',
            ]);
    }

    public function test_midtrans_webhook_acknowledges_unknown_test_payload(): void
    {
        $this->partialMock(MidtransService::class, function ($mock): void {
            $mock->shouldReceive('parseNotification')
                ->once()
                ->andReturn([
                    'order_id' => 'MIDTRANS-TEST-ORDER',
                    'transaction_status' => 'settlement',
                    'fraud_status' => 'accept',
                    'payment_type' => 'qris',
                    'transaction_id' => 'midtrans-test-transaction',
                ]);
        });

        $response = $this->postJson(route('midtrans.webhook'), [
            'transaction_id' => 'midtrans-test-transaction',
        ]);

        $response->assertOk();
        $response->assertContent('IGNORED');
    }
}
