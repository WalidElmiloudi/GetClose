<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\VendorPayoutService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Recalculate vendor available balances daily at midnight
Schedule::call(function () {
    $service = new VendorPayoutService();
    $service->recalculateAvailableBalances();
})->daily()->at('00:00')->name('recalculate-vendor-balances');
