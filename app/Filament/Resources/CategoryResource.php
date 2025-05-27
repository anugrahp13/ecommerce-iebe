<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Kategori';
    protected static ?string $navigationGroup = 'Manajemen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug($state));
                    }),
                TextInput::make('slug')
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                FileUpload::make('image')
                    ->label('Gambar')
                    ->image()
                    ->directory('categories')
                    ->disk('public')
                    ->visibility('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->disk('public')
                    ->circular()
                    ->label('Gambar'),
                TextColumn::make('name')->searchable()->label('Nama'),
                TextColumn::make('slug'),
                TextColumn::make('created_at')->dateTime()->label('Dibuat'),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
            'view' => Pages\ViewCategory::route('/{record}'),
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
