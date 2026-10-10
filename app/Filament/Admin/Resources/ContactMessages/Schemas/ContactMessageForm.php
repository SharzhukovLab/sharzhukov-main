<?php

namespace App\Filament\Admin\Resources\ContactMessages\Schemas;

use App\Models\ContactMessage;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        $categories = [];
        foreach (ContactMessage::categories() as $k => $v) {
            $categories[$k] = $v['label'];
        }

        return $schema->components([
            Section::make('Sender')->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('email')->email()->required()->maxLength(255),
                TextInput::make('subject')->required()->maxLength(255)->columnSpanFull(),
                Select::make('category')->options($categories)->required()->native(false),
                TextInput::make('role_interest')->maxLength(255)
                    ->visible(fn ($get) => $get('category') === 'join_team'),
            ])->columns(2),

            Section::make('Message')->schema([
                Textarea::make('message')->required()->rows(8)->columnSpanFull(),
            ]),

            Section::make('Moderation')->schema([
                Select::make('status')->options([
                    'new'      => 'New',
                    'read'     => 'Read',
                    'replied'  => 'Replied',
                    'archived' => 'Archived',
                ])->required()->default('new'),
                Textarea::make('admin_note')->rows(3)->columnSpanFull(),
                Textarea::make('reply_body')->rows(4)->columnSpanFull()
                    ->helperText('Заметка о том, что ответили (не отправляется автоматически)'),
            ])->columns(2),

            Section::make('Meta')->schema([
                TextInput::make('ip_address')->disabled()->maxLength(45),
                TextInput::make('user_agent')->disabled()->maxLength(255),
                TextInput::make('user_id')->numeric()->disabled(),
            ])->columns(3)->collapsed(),
        ]);
    }
}
