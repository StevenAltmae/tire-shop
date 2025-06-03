<?php

namespace App\Filament\Resources\TireResource\Pages;

use App\Filament\Resources\TireResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTire extends EditRecord
{
    protected static string $resource = TireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
