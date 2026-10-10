<?php

namespace App\Filament\Admin\Resources\NewsletterSubscribers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsletterSubscriberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Subscriber')->schema([
                TextInput::make('email')->email()->required()->maxLength(255),
                TextInput::make('name')->maxLength(255),
                Select::make('status')->options([
                    'pending'      => 'Pending',
                    'confirmed'    => 'Confirmed',
                    'unsubscribed' => 'Unsubscribed',
                ])->required(),
                TextInput::make('source')->maxLength(32),
                TextInput::make('locale')->maxLength(10),
            ])->columns(2),

            Section::make('Timeline')->schema([
                DateTimePicker::make('confirmed_at')->disabled(),
                DateTimePicker::make('unsubscribed_at')->disabled(),
            ])->columns(2)->collapsed(),
        ]);
    }
}