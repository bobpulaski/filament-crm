<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\Client;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralLabel = 'Контакты';

    public static function getEloquentQuery (): Builder
    {
//        dd(auth()->id());
        return parent::getEloquentQuery ()->where ('user_id', auth ()->id ());
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('surname')
                    ->required()
                    ->maxLength(25)
                    ->label('Фамилия'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(25)
                    ->label('Имя'),
                Forms\Components\Select::make('client_id')
                ->relationship('client', 'name')
                ->label('Клиент')
                ->searchable()
                ->preload()
                ->options(Client::where('user_id', auth()->id())->pluck('name', 'id'))
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->minLength(3)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('inn')
                        ->required()
                        ->minLength(10)
                        ->maxLength(12),
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
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('surname')
                    ->label('Фамилия'),
                Tables\Columns\TextColumn::make('name')
                ->label('Имя'),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Клиент'),
//                Tables\Columns\TextColumn::make ('user_id'),
//                Tables\Columns\TextColumn::make ('user.name'),

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
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
