<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $navigationGroup = 'Manajemen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('Password Baru')
                    ->password()
                    ->revealable()
                    ->required(fn (string $context): bool => $context === 'create')
                    ->dehydrateStateUsing(fn ($state) => !empty($state) ? bcrypt($state) : null)
                    ->dehydrated(fn ($state) => filled($state)),
                FileUpload::make('image')
                    ->label("Gambar")    
                    ->image()
                    ->maxSize(1024) // dalam kilobytes
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->directory('avatars')
                    ->disk('public')
                    ->visibility('public'),
                Select::make('role')
                    ->options([
                        'Admin' => 'Admin',
                        'Seller' => 'Seller',
                        'Warehouse' => 'Warehouse',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->disk('public')
                    ->visibility('public')
                    ->url(fn ($record) => asset('storage/' . $record->image))
                    ->circular(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email'),
                TextColumn::make('role')->badge(),
                TextColumn::make('created_at')->label("Pembuatan")->dateTime(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
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
