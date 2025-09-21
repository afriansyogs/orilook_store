<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\SizeStockProduct;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        return $data;
    }

    protected function afterSave(): void
    {
        parent::afterSave();

        $order = $this->record;

        Log::info('Qty value: ' . $order->qty);

        $sizeStock = SizeStockProduct::find($order->size_stock_product_id);

        if ($sizeStock) {
            $sizeStock->stock -= $order->qty;
            $sizeStock->save();
        }
    }
}
