<?php

namespace App\Filament\Resources\SizeStockProductResource\Pages;

use App\Filament\Resources\SizeStockProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSizeStockProduct extends EditRecord
{
    protected static string $resource = SizeStockProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
