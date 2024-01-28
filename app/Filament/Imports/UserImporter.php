<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('phone')
                ->rules(['max:255']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('password')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
        ];
    }

    public function resolveRecord(): ?User
    {
        $user = new User();

        // Set the default role_id to vehicleowner
        $user->role_id = 3;

        $user->name = $this->data['name'];
        $user->phone = $this->data['phone'];
        $user->email = $this->data['email'];
        $user->email_verified_at = null;
        $user->password = bcrypt($this->data['password']);

        return $user;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Uw gebruikersimport is voltooid en ' . number_format($import->successful_rows) . ' ' . str('rij')->plural($import->successful_rows) . ' geïmporteerd.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('rij')->plural($failedRowsCount) . ' kon niet worden geïmporteerd.';
        }

        return $body;
    }
}
