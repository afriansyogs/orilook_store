<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Closure;
use Filament\Forms;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Stmt\Label;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Managemant Product';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('product_name')
                    ->required()
                    ->label('Nama Product')
                    ->Placeholder('Masukan Nama Product...')
                    ->columnSpan(2),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->label('Harga Product')
                    ->placeholder('Masukan Harga Product...')
                    ->prefix('Rp')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $price = is_numeric($state) ? floatval($state) : 0;
                        $discount = is_numeric($get('discount')) ? floatval($get('discount')) : 0;
                        $discounted_price = max(0, $price - $discount);
                        $set('discounted_price', $discounted_price);
                    })
                    ->debounce(500)
                    ->columnSpan([
                        'default' => 2,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                    ]),
                TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->reactive()
                    ->label('Harga discount')
                    ->placeholder('Masukan Harga...')
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $price = is_numeric($get('price')) ? floatval($get('price')) : 0;
                        $discount = is_numeric($state) ? floatval($state) : 0;
                        if ($discount > $price) {
                            $discount = $price;
                            $set('discount', $discount);
                        }
                        $discounted_price = max(0, $price - $discount); 
                        $set('discounted_price', $discounted_price);
                    })
                    ->debounce(500)
                    ->columnSpan([
                        'default' => 2,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                    ]),
                TextInput::make('discounted_price')
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Harga Akhir')
                    ->placeholder('Harga Akhir...')
                    ->reactive()
                    ->columnSpan(2),
                Select::make('category_id')
                    ->relationship('category', 'category_name')
                    ->required()
                    ->placeholder('Pilih Category')
                    ->columnSpan([
                        'default' => 2,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                    ]),     
                Select::make('brand_id')
                    ->relationship('brand', 'brand_name')
                    ->required()
                    ->placeholder('Pilih Brand')
                    ->columnSpan([
                        'default' => 2,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                    ]),     
                FileUpload::make('product_img')
                    ->image()
                    ->required()
                    ->multiple()
                    ->label('Upload Gambar')
                    ->directory('products')
                    ->visibility('public')
                    ->columnSpan(2),
                Textarea::make('description')
                    ->required()
                    ->label('Deskripsi Product')
                    ->Placeholder('Masukan Deskripsi Product...')
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('product_img')
                ->defaultImageUrl(url('/storage/products/{filename}'))
                    ->stacked(),
                TextColumn::make('product_name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Product'),
                TextColumn::make('category.category_name'),
                TextColumn::make('brand.brand_name'),
                TextColumn::make('sizeStock.size')
                    ->Label('Size'),
                TextColumn::make('sizeStock.stock')
                    ->Label('Stock'),
                TextColumn::make('price')
                    ->label('Harga Product')
                    ->formatStateUsing(function ($state) {
                        return 'Rp ' . number_format($state, 0, ',', '.');
                    }),
                TextColumn::make('discount')
                    ->label('discount')
                    ->formatStateUsing(function ($state) {
                        return 'Rp ' . number_format($state, 0, ',', '.');
                    }),
                TextColumn::make('discounted_price')
                    ->label('Harga Akhir')
                    ->formatStateUsing(function ($state) {
                        return 'Rp ' . number_format($state, 0, ',', '.');
                    }),
                TextColumn::make('description')
                    ->copyable()
                    ->label('Deskripsi Product')
                    ->limit(50)
                        ->tooltip(function (TextColumn $column): ?string {
                            $state = $column->getState();
                            if (strlen($state) <= $column->getCharacterLimit()) {
                                return null;
                            }
                            return $state;
                        }),
                TextColumn::make('created_at')
                ->date(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('Category')
                    ->relationship('category', 'category_name'),
                Tables\Filters\SelectFilter::make('Brand')
                    ->relationship('brand', 'brand_name'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
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
