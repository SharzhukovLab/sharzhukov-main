<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identity')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('username')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        FileUpload::make('avatar_path')
                            ->label('Avatar')
                            ->image()
                            ->avatar()
                            ->disk('public')
                            ->directory('avatars')
                            ->maxSize(2048),

                        Textarea::make('bio')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Access')
                    ->schema([
                        Select::make('role')
                            ->options([
                                'user' => 'User',
                                'member' => 'Member',
                                'moderator' => 'Moderator',
                                'editor' => 'Editor',
                                'admin' => 'Admin',
                            ])
                            ->default('user')
                            ->required(),

                        Toggle::make('is_trusted'),
                        Toggle::make('is_banned'),
                        Toggle::make('show_in_team'),
                    ])
                    ->columns(2),

                Section::make('Team')
                    ->schema([
                        TextInput::make('team_role')->maxLength(255),
                        TextInput::make('team_year')->maxLength(20),
                        TextInput::make('team_order')->numeric()->default(99),
                    ])
                    ->columns(3)
                    ->collapsed(),
            ]);
    }
}