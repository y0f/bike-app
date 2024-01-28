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
        $importedRows = $import->successful_rows;
        $failedRowsCount = $import->getFailedRowsCount();

        $importedRowsText = $importedRows === 1 ? 'rij' : 'rijen';
        $failedRowsText = $failedRowsCount === 1 ? 'rij' : 'rijen';

        $body = "De import van uw gebruiker(s) is voltooid. Er " . ($importedRows === 1 ? 'is' : 'zijn') . " " . number_format($importedRows) . " $importedRowsText geïmporteerd.";

        if ($failedRowsCount === 1) {
            $body .= " $failedRowsCount $failedRowsText kon niet worden geïmporteerd.";
        } elseif ($failedRowsCount > 1) {
            $body .= " $failedRowsCount $failedRowsText konden niet worden geïmporteerd.";
        }

        return $body;
    }

}
