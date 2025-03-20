<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationGroup = 'Managemant Transaction';

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Payment Method';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('payment_name')
                    ->required()
                    ->label('Metode Pembayaran')
                    ->placeholder('Masukan Metode Pembayaran...')
                    ->columnSpan([
                        'default' => 2,
                        'md' => 2,
                        'lg' => 1,
                        'xl' => 1,
                    ]),
                TextInput::make('no_rekening')
                    ->numeric()
                    ->label('No Rekening')
                    ->placeholder('Masukan No Rekening...')
                    ->columnSpan([
                        'default' => 2,
                        'md' => 2,
                        'lg' => 1,
                        'xl' => 1,
                    ]),
                FileUpload::make('payment_img')
                    ->image()
                    ->label('Upload Gambar')
                    ->directory('payment')
                    ->visibility('public')
                    ->columnSpan(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('payment_img')
                    ->defaultImageUrl(url('/storage/payment/{filename}'))
                    ->width(200)
                    ->height(300),
                TextColumn::make('payment_name')
                    ->searchable()
                    ->label('Metode Pembayaran'),
                TextColumn::make('no_rekening')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('created_at')
                    ->date()
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
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
