<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Form;
use Filament\Notifications\Livewire\Notifications;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\SizeStockProduct;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\Action;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Managemant Transaction';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->relationship('product', 'product_name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        $product = \App\Models\Product::find($state);
                        if ($product) {
                            $set('discounted_price', $product->discounted_price);
                        }
                        $set('size_stock_product_id', null); // Menetapkan ukuran stok menjadi null setelah memilih produk
                    }),
                
                Select::make('size_stock_product_id')
                    ->options(function (callable $get) {
                        $productId = $get('product_id');

                        if ($productId) {
                            return SizeStockProduct::where('product_id', $productId) // Pastikan menggunakan SizeStockProduct
                                ->pluck('size', 'id');
                        }
                        return [];
                    })
                    ->required(),

                TextInput::make('discounted_price')
                    ->required()
                    ->numeric()
                    ,

                    TextInput::make('qty')
                    ->required()
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        $sizeStockId = $get('size_stock_product_id');
                        $qty = $get('qty');
                
                        // Validasi qty terlebih dahulu
                        if ($sizeStockId) {
                            $sizeStock = SizeStockProduct::find($sizeStockId);
                            if ($sizeStock && $qty > $sizeStock->stock) {
                                $qty = $sizeStock->stock;
                                $set('qty', $qty); // Set qty ke stok maksimal jika melebihi stok
                            }
                        }
                
                        // Setelah validasi qty, hitung total_amount
                        $discountedPrice = $get('discounted_price');
                        if ($discountedPrice && $qty) {
                            $set('total_amount', $discountedPrice * $qty);
                        }
                    }),

                TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(true) // Pastikan field ini didehidrasi agar nilai disimpan ke database
                    ->reactive(),
                
                Select::make('payment_id')
                    ->relationship('payment', 'payment_name')
                    ->required(),
                
                Select::make('status')
                    ->options([
                        'cancel' => 'Cancel',
                        'pending' => 'Pending',
                        'pesanan dibuat' => 'Pesanan Dibuat',
                        'pesanan diantar' => 'Pesanan Diantar',
                        'pesanan sampai' => 'Pesanan Sampai',
                        'menunggu confirm user' => 'Menunggu Confirm User',
                        'completed' => 'Completed',
                    ]),
                FileUpload::make('payment_proof')
                    ->image()
                    ->label('Bukti Transaksi')
                    ->directory('payment_proof')
                    ->visibility('public')
                    ->columnSpan(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(
            Order::query()->latest()
            )
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->label('Nama'),
                TextColumn::make('user.email')
                    ->searchable()
                    ->label('Email')
                    ->alignCenter(),
                TextColumn::make('user.no_hp')
                    ->searchable()
                    ->label('No Telp')
                    ->alignCenter(),
                TextColumn::make('product.product_name')
                    ->searchable()
                    ->alignCenter(),
                TextColumn::make('product.brand.brand_name')
                    ->searchable()
                    ->alignCenter(),
                TextColumn::make('product.category.category_name')
                    ->searchable()
                    ->alignCenter(),
                TextColumn::make('sizeStock.size')
                    ->label('Size')
                    ->alignCenter(),
                TextColumn::make('qty')
                    ->label('Jumlah')
                    ->alignCenter(),
                TextColumn::make('total_amount')
                    ->label('Total harga')
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        return 'Rp ' . number_format($state, 0, ',', '.');
                    }),
                TextColumn::make('payment.payment_name')
                    ->label('Payment')
                    ->alignCenter(),
                TextColumn::make('city.province.province_name')
                    ->label('Provinsi')
                    ->alignCenter(),
                TextColumn::make('city.city_name')
                    ->label('Kota')
                    ->alignCenter(),
                TextColumn::make('addres')
                    ->copyable()
                    ->searchable()
                    ->label('alamat')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    }),
                TextColumn::make('city.shipping_price')
                    ->label('Ongkir')
                    ->formatStateUsing(function ($state) {
                        return 'Rp ' . number_format($state, 0, ',', '.');
                    }),
                ImageColumn::make('payment_proof')
                    ->defaultImageUrl(url('/storage/payment_proof/{filename}'))
                    ->width(200)
                    ->height(300)
                    ->alignCenter(),
                TextColumn::make('status')
                    ->color(fn (Order $record) => match ($record->status) {
                        'pending' => 'danger',
                        'pesanan dibuat' => 'info',
                        'pesanan diantar' => 'primary',
                        'pesanan sampai' => 'info',
                        'menunggu confirm user' => 'warning',
                        'completed' => 'success',
                        default => 'Tidak Dikenali',
                    }),
                TextColumn::make('created_at')
                    ->date()
                    ->sortable()
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('status')
                    ->options(
                        \App\Models\Order::query()
                            ->select('status')
                            ->distinct()
                            ->pluck('status', 'status')
                            ->toArray()
                    )
                    ->label('Status'),
                Tables\Filters\SelectFilter::make('Category')
                    ->relationship('product.category', 'category_name'),
                Tables\Filters\SelectFilter::make('Brand')
                    ->relationship('product.brand', 'brand_name'),
                Tables\Filters\SelectFilter::make('Provinsi')
                    ->relationship('city.province', 'province_name'),
                Tables\Filters\SelectFilter::make('Kota')
                    ->relationship('City', 'city_name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ActionGroup::make([
                    // Tombol "Proses Pesanan" jika status pending
                    Action::make('process_order')
                        ->label('Proses Pesanan')
                        ->color('warning')
                        ->icon('heroicon-o-clock') // Ikon waktu untuk pemrosesan
                        ->visible(fn (Order $record) => $record->status === 'pending')
                        ->action(function (Order $record) {
                            $record->status = 'pesanan dibuat';
                            $record->save();
                            
                            Notification::make()
                                ->title('Pesanan Diproses')
                                ->success()
                                ->body('Status diperbarui menjadi "Pesanan Dibuat".')
                                ->send();
                        }),
                
                    // Tombol "Batalkan Pesanan" jika status pending
                    Action::make('request_cancel')
                        ->label('Batalkan Pesanan')
                        ->color('danger')
                        ->icon('heroicon-o-x-circle') 
                        ->visible(fn (Order $record) => $record->status === 'request cancel')
                        ->action(function (Order $record) {
                            $record->status = 'cancel';
                            $record->save();
                
                            Notification::make()
                                ->title('Pesanan Dibatalkan')
                                ->danger()
                                ->body('Pesanan telah dibatalkan.')
                                ->send();
                        })
                        ->requiresConfirmation(),
                
                    // Tombol "Buat Pesanan Ulang" jika status cancel
                    Action::make('re_order')
                        ->label('Buat Pesanan Ulang')
                        ->color('warning')
                        ->icon('heroicon-o-clipboard-document-list') // Ikon refresh untuk reorder
                        ->visible(fn (Order $record) => $record->status === 'cancel')
                        ->action(function (Order $record) {
                            $record->status = 'pending'; // Mengembalikan ke pending
                            $record->save();
                
                            Notification::make()
                                ->title('Pesanan Dibuat Ulang')
                                ->success()
                                ->body('Pesanan telah dikembalikan ke status "Pending".')
                                ->send();
                        })
                        ->requiresConfirmation(),
                
                    // Tombol untuk update status bertahap
                    Action::make('status')
                        ->label(fn (Order $record) => match ($record->status) {
                            'pesanan dibuat' => 'Pesanan Diantar',
                            'pesanan diantar' => 'Pesanan Sampai',
                            'pesanan sampai' => 'Menunggu Konfirmasi',
                            'menunggu confirm user' => 'Selesaikan Pesanan',
                            default => 'Tidak Dikenali',
                        })
                        ->color(fn (Order $record) => match ($record->status) {
                            'pesanan dibuat' => 'info',
                            'pesanan diantar' => 'primary',
                            'pesanan sampai' => 'info',
                            'menunggu confirm user' => 'warning',
                            default => 'secondary',
                        })
                        ->icon(fn (Order $record) => match ($record->status) {
                            'pesanan dibuat' => 'heroicon-o-truck', // Ikon truk untuk pengiriman
                            'pesanan diantar' => 'heroicon-o-map', // Ikon peta untuk pesanan sampai
                            'pesanan sampai' => 'heroicon-o-bookmark-square', // Ikon user check untuk konfirmasi
                            'menunggu confirm user' => 'heroicon-o-check-circle', // Ikon check untuk selesai
                            default => 'heroicon-o-question-mark-circle',
                        })
                        ->action(function (Order $record) {
                            switch ($record->status) {
                                case 'pesanan dibuat':
                                    $record->status = 'pesanan diantar';
                                    Notification::make()
                                        ->title('Pesanan Diantar')
                                        ->success()
                                        ->body('Status diperbarui menjadi "Pesanan Diantar".')
                                        ->send();
                                    break;
                                case 'pesanan diantar':
                                    $record->status = 'pesanan sampai';
                                    Notification::make()
                                        ->title('Pesanan Sampai')
                                        ->success()
                                        ->body('Status diperbarui menjadi "Pesanan Sampai".')
                                        ->send();
                                    break;
                                case 'pesanan sampai':
                                    $record->status = 'menunggu confirm user';
                                    Notification::make()
                                        ->title('Menunggu Konfirmasi')
                                        ->success()
                                        ->body('Status diperbarui menjadi "Menunggu Konfirmasi User".')
                                        ->send();
                                    break;
                                case 'menunggu confirm user':
                                    $record->status = 'completed';
                                    Notification::make()
                                        ->title('Pesanan Selesai')
                                        ->success()
                                        ->body('Status diperbarui menjadi "Completed".')
                                        ->send();
                                    break;
                                default:
                                    Notification::make()
                                        ->title('Tidak Dapat Memperbarui Status')
                                        ->danger()
                                        ->body('Status tidak dikenali.')
                                        ->send();
                                    break;
                            }
                            $record->save();
                        })
                        ->requiresConfirmation()
                        ->visible(fn (Order $record) => !in_array($record->status, ['completed', 'pending', 'cancel']))
                ])
                ->label('Update Status') 
                ->icon('heroicon-o-cog') 
                ->button()
                ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
        }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    protected static function beforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        return $data;
    }

    protected static function beforeSave(array $data, $record): array
    {
        $data['user_id'] = Auth::id();
        return $data;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
