<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SizeStockProductResource\Pages;
use App\Filament\Resources\SizeStockProductResource\RelationManagers;
use App\Models\SizeStockProduct;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SizeStockProductResource extends Resource
{
    protected static ?string $model = SizeStockProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Managemant Product';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->relationship('product', 'product_name')
                    ->required()
                    ->placeholder('Pilih Product')
                    ->searchable()
                    ->reactive() // 🔥 Agar saat product berubah, size ikut berubah
                    ->columnSpan([
                        'default' => 2,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 2,
                    ]),

                Select::make('size')
                    ->placeholder('Pilih Size')
                    ->options(function ($get) {
                        if (!$get('product_id')) {
                            return [];
                        }

                        $usedSizes = SizeStockProduct::where('product_id', $get('product_id'))
                            ->pluck('size')
                            ->toArray();

                        $allSizes = [
                            '35' => '35',
                            '36' => '36',
                            '37' => '37',
                            '38' => '38',
                            '39' => '39',
                            '40' => '40',
                            '41' => '41',
                            '42' => '42',
                            '43' => '43',
                            '44' => '44',
                            '45' => '45',
                        ];

                        // 🔥 Hanya tampilkan size yang belum ada di database
                        return array_diff_key($allSizes, array_flip($usedSizes));
                    })
                    ->disabled(fn($get) => !$get('product_id')) // 🔥 Disabled jika product belum dipilih
                    ->required(),

                TextInput::make('stock')
                    ->numeric()
                    ->required()
                    ->placeholder('Masukan Stock...')
                    ->disabled(fn($get) => !$get('product_id')), // 🔥 Disabled jika product belum dipilih
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.product_name')
                    ->searchable(),
                TextColumn::make('product.brand.brand_name'),
                TextColumn::make('size'),
                TextColumn::make('stock'),
                TextColumn::make('created_at')
                ->date(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('Brand')
                    ->relationship('product.brand', 'brand_name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListSizeStockProducts::route('/'),
            'create' => Pages\CreateSizeStockProduct::route('/create'),
            'edit' => Pages\EditSizeStockProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
