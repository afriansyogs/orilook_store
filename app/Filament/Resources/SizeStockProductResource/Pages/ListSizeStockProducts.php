<?php

namespace App\Filament\Resources\SizeStockProductResource\Pages;

use App\Filament\Resources\SizeStockProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSizeStockProducts extends ListRecords
{
    protected static string $resource = SizeStockProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
