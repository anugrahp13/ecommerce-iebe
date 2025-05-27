<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Filament\Resources\ActivityLogResource\RelationManagers;
use App\Models\ActivityLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Riwayat Aktivitas';
    protected static ?string $navigationGroup = 'Manajemen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable(),
                BadgeColumn::make('action')
                    ->label('Action')
                    ->colors([
                        'success' => 'created',
                        'warning' => 'updated',
                        'danger' => 'deleted',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->searchable(),
                Tables\Columns\TextColumn::make('resource_type')->searchable(),
                Tables\Columns\TextColumn::make('resource_id')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Waktu')->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->searchPlaceholder('Cari aktivitas...')
            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->label('Export')
                    ->fileName('activity-log')
                    ->disableAdditionalColumns() // Nonaktifkan kolom tambahan
                    ->disableFilterColumns() // Nonaktifkan filter kolom
                    ->disablePreview() // Nonaktifkan preview
                    ->defaultFormat('xlsx') // Format default
            ])
            ->bulkActions([
                FilamentExportBulkAction::make('Export')
                    ->label('Export')
                    ->fileName('activity-log')
                    ->defaultFormat('xlsx')
            ])
            ->searchOnBlur()
            ->persistSearchInSession();
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
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit(Model $record): bool { return false; }
    public static function canDelete(Model $record): bool { return false; }

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

    public static function canAccess(): bool
    {
        return Auth::user()?->role === 'Admin';
    }
    public static function getNavigationUrl(): string
    {
        return static::canAccess() ? parent::getNavigationUrl() : '/admin';
    }
}
