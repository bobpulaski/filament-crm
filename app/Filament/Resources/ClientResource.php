<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use App\Models\ClientStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class ClientResource extends Resource
{

    protected static ?string $model = Client::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralLabel = 'Клиенты';


    public static function getEloquentQuery (): Builder
    {
        return parent::getEloquentQuery ()->where ('user_id', auth ()->id ());
    }

    public static function form (Form $form): Form
    {
        return $form
            ->schema ([
                Forms\Components\TextInput::make ('name')
                    ->required ()
                    ->minLength (3)
                    ->maxLength (255)
                    ->label ('Наименование'),
                Forms\Components\TextInput::make ('inn')
                    ->required ()
                    ->minLength (10)
                    ->maxLength (12)
                    ->label ('ИНН'),
                Forms\Components\Select::make ('status_id')
                    ->required ()
                    ->relationship ('status', 'type')
                    ->label ('Статус')
                    ->searchable ()
                    ->preload ()
                    ->createOptionForm ([
                        Forms\Components\TextInput::make ('type')
                            ->required ()
                    ]),
            ]);
    }

    public static function table (Table $table): Table
    {
        return $table
            ->columns ([
                Tables\Columns\TextColumn::make ('name')
                    ->searchable ()
                    ->sortable ()
                    ->label ('Наименование'),
                Tables\Columns\TextColumn::make ('inn')
                    ->searchable ()
                    ->sortable ()
                    ->label ('ИНН'),
//                Tables\Columns\TextColumn::make ('status.type')
//                    ->label ('Клиент'),
                Tables\Columns\SelectColumn::make('status_id')
                    ->options(function () {
                        return ClientStatus::all()->pluck('type', 'id');
                    })
                    ->selectablePlaceholder(false)
                    ->label ('Статус'),

                Tables\Columns\TextColumn::make ('user_id'),
                Tables\Columns\TextColumn::make ('user.name'),
            ])
            ->filters ([
                //
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
            ])

            ->bulkActions ([
                Tables\Actions\BulkActionGroup::make ([
                    Tables\Actions\DeleteBulkAction::make (),
                ]),
            ]);
    }

    public static function getRelations (): array
    {
        return [
            RelationManagers\ContactsRelationManager::class,
        ];
    }

    public static function getPages (): array
    {
        return [
            'index' => Pages\ListClients::route ('/'),
            'create' => Pages\CreateClient::route ('/create'),
            'edit' => Pages\EditClient::route ('/{record}/edit'),
        ];
    }
}
