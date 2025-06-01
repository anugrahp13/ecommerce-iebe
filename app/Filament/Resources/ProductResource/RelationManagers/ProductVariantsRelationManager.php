<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class ProductVariantRelationManager extends RelationManager
{
    protected static string $relationship = 'variants'; // sesuaikan dengan relasi di model Product

    protected static ?string $title = 'Varian Produk';

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('sku')->label('SKU')->required()->unique(ignoreRecord: true),
            TextInput::make('color')->label('Warna')->required(),
            TextInput::make('size')->label('Ukuran')->required(),
            FileUpload::make('image')->label('Gambar')->image()->directory('variants'),
            TextInput::make('price')->label('Harga')->numeric()->required(),
            TextInput::make('stock')->label('Stok')->numeric()->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('size')->label('Ukuran'),
                Tables\Columns\TextColumn::make('color')->label('Warna'),
                Tables\Columns\ImageColumn::make('image')->label('Gambar'),
                Tables\Columns\TextColumn::make('price')->label('Harga')->money('IDR', true),
                Tables\Columns\TextColumn::make('stock')->label('Stok'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Buat variant'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ]);
    }
}
