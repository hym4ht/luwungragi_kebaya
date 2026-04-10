<?php

namespace Tests\Unit;

use App\Exceptions\MidtransConfigurationException;
use App\Models\Rental;
use App\Models\User;
use App\Services\MidtransService;
use Tests\TestCase;

class MidtransServiceTest extends TestCase
{
    public function test_configuration_summary_marks_mid_prefixed_keys_without_rejecting_them(): void
    {
        config([
            'midtrans.server_key' => 'Mid-server-example',
            'midtrans.client_key' => 'Mid-client-example',
            'midtrans.is_production' => false,
        ]);

        $service = new MidtransService();

        $summary = $service->configurationSummary();

        $this->assertSame('mid-prefixed', $summary['server_key_format']);
        $this->assertSame('mid-prefixed', $summary['client_key_format']);
    }

    public function test_generate_snap_token_requires_server_key(): void
    {
        config([
            'midtrans.server_key' => '',
            'midtrans.client_key' => 'SB-Mid-client-example',
            'midtrans.is_production' => false,
        ]);

        $service = new MidtransService();

        $this->expectException(MidtransConfigurationException::class);
        $this->expectExceptionMessage('MIDTRANS_SERVER_KEY belum diisi');

        $service->generateSnapToken(new Rental(), new User());
    }
}
