<?php

namespace App\Filament\Resources\VoucherResource\Pages;

use App\Filament\Resources\VoucherResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVoucher extends CreateRecord
{
    protected static string $resource = VoucherResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->shouldRedirectToIndex ? VoucherResource::getUrl('index') : null;
    }

    protected bool $shouldRedirectToIndex = true;
}
