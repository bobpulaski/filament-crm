<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientStatusResource\Pages;
use App\Filament\Resources\ClientStatusResource\RelationManagers;
use App\Models\ClientStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientStatusResource extends Resource
{
    protected static ?string $model = ClientStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->minLength(3)
                    ->maxLength(20)
                    ->label('Статус клиента'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->sortable()
                    ->label('Статус клиента'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListClientStatuses::route('/'),
            'create' => Pages\CreateClientStatus::route('/create'),
            'edit' => Pages\EditClientStatus::route('/{record}/edit'),
        ];
    }
}
