<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountResource\Pages;
use App\Filament\Resources\DiscountResource\RelationManagers;
use App\Models\Discount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Diskon';
    protected static ?string $navigationGroup = 'Manajemen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Diskon')
                    ->required(),

                TextInput::make('amount')
                    ->label('Nominal Diskon (Rp)')
                    ->numeric()
                    ->required(),

                DateTimePicker::make('start_at')
                    ->label('Mulai')
                    ->required(),

                DateTimePicker::make('end_at')
                    ->label('Berakhir')
                    ->required(),

                Select::make('products')
                    ->label('Pilih Produk')
                    ->multiple()
                    ->relationship('products', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Diskon')->searchable(),
                Tables\Columns\TextColumn::make('amount')->label('Nominal')->money('IDR'),
                Tables\Columns\TextColumn::make('start_at')->label('Mulai')->dateTime(),
                Tables\Columns\TextColumn::make('end_at')->label('Berakhir')->dateTime(),
                Tables\Columns\TextColumn::make('products.name')
                    ->label('Produk')
                    ->badge()
                    ->limitList(3),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
            'view' => Pages\ViewDiscount::route('/{record}'),
        ];
    }

    // Config role Admin
    public static function getMiddleware(): array
    {
        return ['is.admin'];
    }
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && Auth::user()->role === 'Admin';
    }
    public static function authorizeResourceAccess(): bool
    {
        return Filament::auth()->check() && Filament::auth()->user()->role === 'Admin';
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->role === 'Admin';
    }

    public static function canView(mixed $record): bool
    {
        return Auth::user()?->role === 'Admin';
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->role === 'Admin';
    }

    public static function canEdit(mixed $record): bool
    {
        return Auth::user()?->role === 'Admin';
    }

    public static function canDelete(mixed $record): bool
    {
        return Auth::user()?->role === 'Admin';
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->role === 'Admin';
    }
    public static function getNavigationUrl(): string
    {
        return static::canAccess() ? parent::getNavigationUrl() : '/admin';
    }
}
