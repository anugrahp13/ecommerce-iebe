<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherResource\Pages;
use App\Filament\Resources\VoucherResource\RelationManagers;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Voucher';
    protected static ?string $navigationGroup = 'Manajemen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                ->required()
                ->unique(ignoreRecord: true),

                TextInput::make('value')
                    ->label('Nilai Voucher')
                    ->numeric()
                    ->inputMode('decimal')
                    ->required(),

                TextInput::make('min_purchase')
                    ->label('Minimal Pembelian')
                    ->numeric()
                    ->inputMode('decimal')
                    ->required(),

                TextInput::make('quota')
                    ->label('Kuota')
                    ->numeric()
                    ->inputMode('numeric')
                    ->required(),

                Forms\Components\DateTimePicker::make('start_at')
                    ->label('Mulai Berlaku')
                    ->required(),
                
                Forms\Components\DateTimePicker::make('end_at')
                    ->label('Berakhir')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('value')->label('Nilai')->money('IDR', true),
                Tables\Columns\TextColumn::make('min_purchase')->label('Min. Pembelian')->money('IDR', true),
                Tables\Columns\TextColumn::make('quota'),
                Tables\Columns\TextColumn::make('start_at')->dateTime(),
                Tables\Columns\TextColumn::make('end_at')->dateTime(),
            ])
            ->defaultSort('start_at', 'desc')
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
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
            'view' => Pages\ViewVoucher::route('/{record}'),
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
